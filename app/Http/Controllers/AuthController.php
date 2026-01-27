<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $input = $request->input('email');

        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $rules = [
            'email' => ['required'], // Just required, not email format if it's username
            'password' => ['required'],
        ];

        // If it's an email, we can add email validation, but 'admin' string fails that.
        // So let's keep it simple 'required'.

        $credentials = [
            $fieldType => $input,
            'password' => $request->input('password')
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on role
            $user = Auth::user();
            if ($user->role === 'siswa') {
                return redirect()->intended('dashboard');
            } elseif ($user->role === 'guru' || $user->role === 'guru_mapel') {
                return redirect()->intended('dashboard');
            }

            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'Username atau Email tidak ditemukan.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
