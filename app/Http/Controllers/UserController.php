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
        $users = User::with(['teacher'])->latest()->get();
        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            // 'username' => 'required|unique:users', // No longer used
            'password' => 'required|min:6',
            'role' => 'required|in:guru,guru_mapel',
            'nip' => 'required_if:role,guru,guru_mapel|unique:teachers,nip',
        ]);

        DB::transaction(function () use ($request) {
            $email = null;
            $username = null;

            if ($request->role === 'guru' || $request->role === 'guru_mapel') {
                $email = $request->nip . '@teacher.ac.id';
                $username = $request->nip;
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $email,
                'username' => $username, // Keep explicit username for now as fallback
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);

            if ($request->role === 'guru' || $request->role === 'guru_mapel') {
                Teacher::create([
                    'user_id' => $user->id,
                    'nip' => $request->nip,
                    'name' => $request->name,
                ]);
            }
        });

        return back()->with('success', 'Pengguna berhasil dibuat.');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'nullable|min:6',
        ]);

        $user->update([
            'name' => $request->name,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Update profile
        if (($user->role == 'guru' || $user->role == 'guru_mapel') && $user->teacher) {
            $request->validate([
                'nip' => 'required|unique:teachers,nip,' . $user->teacher->id,
            ]);

            $user->teacher->update(['name' => $request->name, 'nip' => $request->nip]);

            // Update User Email/Username if NIP changes
            $user->update([
                'email' => $request->nip . '@teacher.ac.id',
                'username' => $request->nip
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
