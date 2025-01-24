<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class AttendanceController extends Controller
{
    // Menampilkan data absensi
    public function index(Request $request)
    {
        $attendances = Attendance::with('user')
            ->when($request->input('name'), function ($query, $name) {
                $query->whereHas('user', function ($query) use ($name) {
                    $query->where('name', 'like', '%' . $name . '%');
                });
            })
            ->when($request->input('date'), function ($query, $date) {
                $query->whereDate('date', $date);
            })
            ->when($request->input('month'), function ($query, $month) {
                $query->whereMonth('date', $month);
            })
            ->when($request->input('year'), function ($query, $year) {
                $query->whereYear('date', $year);
            })
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('pages.absensi.index', compact('attendances'));
    }

    // Mengunduh PDF laporan absensi
    public function downloadPDF(Request $request)
    {
        $attendances = Attendance::with('user')
            ->when($request->input('date'), function ($query, $date) {
                $query->whereDate('date', $date);
            })
            ->when($request->input('month'), function ($query, $month) {
                $query->whereMonth('date', $month);
            })
            ->when($request->input('year'), function ($query, $year) {
                $query->whereYear('date', $year);
            })
            ->get();

        $dateFilter = $request->input('date')
            ? Carbon::parse($request->input('date'))->format('d F Y')
            : null;
        $monthFilter = $request->input('month')
            ? Carbon::createFromDate(null, $request->input('month'))->format('F')
            : null;
        $yearFilter = $request->input('year');

        $pdf = Pdf::loadView('pages.absensi.pdf', compact('attendances', 'dateFilter', 'monthFilter', 'yearFilter'));

        return $pdf->download('attendance_report.pdf');
    }

    // Dashboard
    public function dashboard()
    {
        // Total pengguna
        $totalUsers = User::count();

        // Total hadir hari ini
        $today = Carbon::today();
        $totalAttendancesToday = Attendance::whereDate('date', $today)->count();

        // Kirim data ke view
        return view('pages.dashboard', compact('totalUsers', 'totalAttendancesToday'));
    }
}
