<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request): \Illuminate\View\View
    {
        $search = $request->query('search');

        $users = User::with('branch')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10);

        return view('users.index', compact('users'));
    }

    public function create(): \Illuminate\View\View
    {
        $branches = Branch::where('is_active', true)->get();

        return view('users.create', compact('branches'));
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,cashier',
        ]);

        User::create([
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Staff member registered successfully.');
    }

    public function edit(User $user): \Illuminate\View\View
    {
        $branches = Branch::where('is_active', true)->get();

        return view('users.edit', compact('user', 'branches'));
    }

    public function update(Request $request, User $user): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,cashier',
        ]);

        $user->update([
            'branch_id' => $request->branch_id,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('users.index')->with('success', 'User profile updated successfully.');
    }

    public function destroy(User $user): \Illuminate\Http\RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot deauthorize your own account.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User access revoked.');
    }
}
