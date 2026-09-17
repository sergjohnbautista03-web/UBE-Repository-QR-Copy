<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    public function admins()
    {
        $admins = User::where('role', 'admin')->orderBy('created_at', 'desc')->get();
        return view('super-admin.admins', compact('admins'));
    }

    public function createAdmin()
    {
        return view('super-admin.create-admin');
    }
public function storeAdmin(Request $request)
   {
    $request->validate([
        'firstname'  => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
        'lastname'   => ['required', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
        'middlename' => ['nullable', 'regex:/^[a-zA-Z\s]+$/', 'max:255'],
        'email'      => 'required|email|unique:users',
        'password'   => ['required', 'confirmed', \Illuminate\Validation\Rules\Password::min(8)->numbers()->symbols()],
    ], [
        'firstname.regex'  => 'First name must contain letters only.',
        'lastname.regex'   => 'Last name must contain letters only.',
        'middlename.regex' => 'Middle name must contain letters only.',
        'password.*'       => 'Password must be at least 8 characters, include at least one number and one special character (@, #, $, %, !).',
    ]);

    $fullName = trim($request->firstname . ' ' . ($request->middlename ? $request->middlename . ' ' : '') . $request->lastname);

    User::create([
        'name'        => $fullName,
        'email'       => $request->email,
        'password'    => Hash::make($request->password),
        'role'        => 'admin',
        'is_approved' => true,
        'is_active'   => true,
    ]);

    return redirect()->route('super-admin.admins')->with('success', 'Admin account created successfully!');
    }
    public function deleteAdmin(User $user)
    {
        if ($user->is_super_admin) {
            return back()->with('error', 'Cannot delete the Super Admin account.');
        }

        if ($user->role !== 'admin') {
            return back()->with('error', 'User is not an admin.');
        }

        $user->delete();
        return back()->with('success', 'Admin account deleted successfully.');
    }

    public function toggleAdmin(User $user)
    {
        if ($user->is_super_admin) {
            return back()->with('error', 'Cannot deactivate the Super Admin account.');
        }

        $user->update(['is_active' => !$user->is_active]);
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Admin account {$status} successfully.");
    }
    public function trash()
    {
    $deletedResearches = \App\Models\Research::onlyTrashed()
        ->orderBy('deleted_at', 'desc')
        ->paginate(15);
    return view('super-admin.trash', compact('deletedResearches'));
    }

    public function restore($id)
    {
    \App\Models\Research::onlyTrashed()->findOrFail($id)->restore();
    return back()->with('success', 'Research restored successfully.');
    }

    public function forceDelete($id)
    {
    \App\Models\Research::onlyTrashed()->findOrFail($id)->forceDelete();
    return back()->with('success', 'Research permanently deleted.');
    }

    public function emptyTrash()
    {
    \App\Models\Research::onlyTrashed()->forceDelete();
    return back()->with('success', 'Trash emptied successfully.');
    }
}