<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon; // Import Carbon for date handling

class UserController extends Controller
{
    // Display a listing of the users
    public function index()
    {
        // Search by name, paginate results
        $users = User::where('name', 'like', '%' . request('name') . '%')
            ->orderBy('id', 'desc')
            ->paginate(10);
        return view('pages.users.index', compact('users'));
    }

    // Show the form for creating a new user
    public function create()
    {
        return view('pages.users.create');
    }

    // Store a newly created user in storage
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'position' => $request->position,
            'department' => $request->department,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    // Show the form for editing the specified user
    public function edit(User $user)
    {
        return view('pages.users.edit', compact('user'));
    }

    // Update the specified user in storage
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'position' => $request->position,
            'department' => $request->department,
        ]);

        // Update password if filled
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully');
    }

    // Remove the specified user from storage
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }

    // // Download PDF report of users
    // public function downloadPDF(Request $request)
    // {
    //     $user = User::query()
    //         ->when($request->input('date'), function ($query, $date) {
    //             $query->whereDate('created_at', $date); // Assuming you want to filter by created_at
    //         })
    //         ->when($request->input('month'), function ($query, $month) {
    //             $query->whereMonth('created_at', $month);
    //         })
    //         ->when($request->input('year'), function ($query, $year) {
    //             $query->whereYear('created_at', $year);
    //         })
    //         ->get();

    //     $dateFilter = $request->input('date')
    //         ? Carbon::parse($request->input('date'))->format('d F Y')
    //         : null;
    //     $monthFilter = $request->input('month')
    //         ? Carbon::createFromDate(null, $request->input('month'))->format('F')
    //         : null;
    //     $yearFilter = $request->input('year');

    //     $pdf = PDF::loadView('pages.users.pdf', compact('user', 'dateFilter', 'monthFilter', 'yearFilter'));

    //     return $pdf->download('users_report.pdf');
    // }

    // Dashboard showing total users
    public function dashboard()
    {
        $totalattendances = User::count();
        return view('pages.dashboard', compact('totalattendances'));
    }
}
