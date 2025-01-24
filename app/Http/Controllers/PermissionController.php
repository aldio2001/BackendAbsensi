<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;
use App\Models\User;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Resend\Laravel\Facades\Resend;
use App\Mail\ApprovedPermissionConfirmation;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\PermissionRejected;

class PermissionController extends Controller
{
    // Index
    public function index(Request $request)
    {
        $permissions = Permission::with('user')
            ->when($request->input('name'), function ($query, $name) {
                $query->whereHas('user', function ($query) use ($name) {
                    $query->where('name', 'like', '%' . $name . '%');
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('pages.permission.index', compact('permissions'));
    }

    // View
    public function show($id)
    {
        $permission = Permission::with('user')->find($id);

        return view('pages.permission.show', compact('permission'));
    }

    // Edit
    public function edit($id)
    {
        $permission = Permission::find($id);

        return view('pages.permission.edit', compact('permission'));
    }

    // Unduh PDF laporan absensi
    public function downloadPDF(Request $request)
    {
        $permission = Permission::with('user')
            ->when($request->input('date'), fn($query, $date) => $query->whereDate('date_permission', Carbon::parse($date)->format('Y-m-d')))
            ->when($request->input('month'), fn($query, $month) => $query->whereMonth('date_permission', $month))
            ->when($request->input('year'), fn($query, $year) => $query->whereYear('date_permission', $year))
            ->get();

        if ($permission->isEmpty()) {
            return back()->with('error', 'No permissions found for the selected filter.');
        }

        $dateFilter = $request->input('date') ? Carbon::parse($request->input('date'))->format('d F Y') : null;
        $monthFilter = $request->input('month') ? Carbon::createFromDate(null, $request->input('month'))->format('F') : null;
        $yearFilter = $request->input('year') ? $request->input('year') : null;

        $pdf = Pdf::loadView('pages.permission.pdf', compact('permission', 'dateFilter', 'monthFilter', 'yearFilter'));

        return $pdf->download('permission_report.pdf');
    }

    // Update permission status
    public function update(Request $request, $id)
    {
        $permission = Permission::find($id);
        $permission->is_approved = $request->is_approved;
        $status = $request->is_approved == 1 ? 'Disetujui' : 'Ditolak';
        $permission->save();

        $user = User::find($permission->user_id);
        $permission_date = $permission->date_permission;
        $date = Carbon::parse($permission_date)->translatedFormat('d F Y');
        $reason = $permission->reason;

        if ($request->is_approved == 1) {
            // Email jika disetujui
            Resend::emails()->send([
                'from' => 'onboarding@resend.dev',
                'name' => 'Dinas Pendidikan Provinsi Riau',
                'to' => $user->email,
                'subject' => 'Approved Permission - ' . $user->name,
                'html' => (new ApprovedPermissionConfirmation($user, $date, $reason))->render(),
            ]);
        } else {
            // Email jika ditolak
            \Mail::to($user->email)->send(new PermissionRejected($user, $date, $reason));
        }

        return redirect()->route('permissions.index')->with('success', 'Permission updated successfully');
    }

    // Hapus permission
    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permissions.index')->with('success', 'Permission deleted successfully');
    }

    // Kirim notifikasi ke user
    public function sendNotificationToUser($userId, $message)
    {
        $user = User::find($userId);
        $token = $user->fcm_token;

        $messaging = app('firebase.messaging');
        $notification = Notification::create('Status Izin', $message);

        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification);

        $messaging->send($message);
    }
}
