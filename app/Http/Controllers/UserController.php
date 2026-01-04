<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Teacher;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['teacher', 'parent'])->latest()->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:guru,wali_murid',
            // Specific validation based on role
            'nip' => 'required_if:role,guru|unique:teachers,nip',
            'phone' => 'nullable',
            'address' => 'nullable',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            if ($request->role === 'guru') {
                Teacher::create([
                    'user_id' => $user->id,
                    'nip' => $request->nip,
                    'name' => $request->name,
                ]);
            } elseif ($request->role === 'wali_murid') {
                ParentModel::create([
                    'user_id' => $user->id,
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address,
                ]);
            }
        });

        return back()->with('success', 'Pengguna berhasil dibuat.');
    }

    public function update(Request $request, User $user)
    {
        // Add update logic later if needed
        $user->update([
            'name' => $request->name,
            'username' => $request->username,
        ]);
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Update profile
        if ($user->role == 'guru' && $user->teacher) {
            $user->teacher->update(['name' => $request->name, 'nip' => $request->nip]);
        }
        if ($user->role == 'wali_murid' && $user->parent) {
            $user->parent->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address
            ]);
        }

        return back()->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'Pengguna berhasil dihapus.');
    }
}
