<?php

namespace App\Http\Controllers;

use App\Models\Shift;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::with(['user', 'branch'])
            ->latest()
            ->paginate(15);

        $activeShift = Shift::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        return view('shifts.index', compact('shifts', 'activeShift'));
    }

    public function open(Request $request)
    {
        $request->validate([
            'starting_cash' => 'required|numeric|min:0',
        ]);

        // Check if already has an open shift
        $activeShift = Shift::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if ($activeShift) {
            return back()->with('error', 'You already have an active shift open.');
        }

        Shift::create([
            'user_id' => Auth::id(),
            'branch_id' => Auth::user()->branch_id,
            'start_time' => now(),
            'starting_cash' => $request->starting_cash,
            'status' => 'open',
        ]);

        return redirect()->route('pos.index')->with('success', 'Shift started successfully.');
    }

    public function close(Request $request)
    {
        $request->validate([
            'actual_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $shift = Shift::where('user_id', Auth::id())
            ->where('status', 'open')
            ->firstOrFail();

        // Calculate expected cash from transactions during this shift (CASH only)
        $cashSales = Transaction::where('user_id', Auth::id())
            ->where('created_at', '>=', $shift->start_time)
            ->whereHas('payments', function ($q) {
                $q->where('method', 'cash');
            })
            ->sum('total');

        $expectedCash = $shift->starting_cash + $cashSales;
        $difference = $request->actual_cash - $expectedCash;

        $shift->update([
            'end_time' => now(),
            'ending_cash' => $expectedCash,
            'actual_cash' => $request->actual_cash,
            'difference' => $difference,
            'notes' => $request->notes,
            'status' => 'closed',
        ]);

        return redirect()->route('shifts.index')->with('success', 'Shift closed. Balance difference: Rp'.number_format($difference));
    }
}
