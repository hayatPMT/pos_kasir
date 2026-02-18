<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = \App\Models\Member::query();

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('member_code', 'like', "%{$search}%");
        }

        $members = $query->latest()->paginate(10);

        return view('members.index', compact('members'));
    }

    public function create()
    {
        return view('members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        // Generate member code if not provided (simple logic)
        $code = 'MEM-'.strtoupper(uniqid());

        \App\Models\Member::create([
            'branch_id' => 1, // Default to 1 for now or auth()->user()->branch_id
            'name' => $request->name,
            'member_code' => $code,
            'phone' => $request->phone,
            'email' => $request->email,
            'address' => $request->address,
            'points' => 0,
            'is_active' => true,
        ]);

        return redirect()->route('members.index')->with('success', 'Member created successfully.');
    }

    public function edit(\App\Models\Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, \App\Models\Member $member)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $member->update($request->only('name', 'phone', 'email', 'address'));

        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }

    public function destroy(\App\Models\Member $member)
    {
        $member->delete();

        return redirect()->route('members.index')->with('success', 'Member deleted successfully.');
    }
}
