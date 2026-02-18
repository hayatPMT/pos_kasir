<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\ShiftSchedule;
use App\Models\User;
use Illuminate\Http\Request;

class ShiftScheduleController extends Controller
{
    public function index()
    {
        $schedules = ShiftSchedule::with(['user', 'branch'])->get();
        $users = User::where('role', 'cashier')->get();
        $branches = Branch::all();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        return view('schedules.index', compact('schedules', 'users', 'branches', 'days'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'branch_id' => 'required|exists:branches,id',
            'day_of_week' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        ShiftSchedule::create($validated);

        return redirect()->route('schedules.index')->with('success', 'Schedule added successfully.');
    }

    public function destroy(ShiftSchedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')->with('success', 'Schedule deleted.');
    }
}
