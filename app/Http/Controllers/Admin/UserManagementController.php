<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load('campaigns', 'donations', 'verification');

        return view('admin.users.show', compact('user'));
    }

    public function suspend(User $user)
    {
        $user->update([
            'account_status' => 'suspended',
            'suspended_at' => now(),
        ]);

        return back()->with('success', 'User suspended successfully.');
    }

    public function activate(User $user)
    {
        $user->update([
            'account_status' => 'active',
            'suspended_at' => null,
        ]);

        return back()->with('success', 'User activated successfully.');
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:donor,recipient,admin',
        ]);

        $user->update(['role' => $request->role]);

        return back()->with('success', 'User role updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}