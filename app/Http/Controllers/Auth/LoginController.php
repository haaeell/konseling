<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // $user = Auth::user();
            // if ($user->isBK()) {
            //     return redirect()->intended('/bk/dashboard');
            // } elseif ($user->isWaliKelas()) {
            //     return redirect()->intended('/wali/dashboard');
            // } elseif ($user->isKepalaSekolah()) {
            //     return redirect()->intended('/kepsek/dashboard');
            // } elseif ($user->isOrangTua()) {
            //     return redirect()->intended('/orangtua/dashboard');
            // } elseif ($user->isSiswa()) {
            //     return redirect()->intended('/siswa/dashboard');
            // }

            return redirect()->intended('/home');
        }

        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
