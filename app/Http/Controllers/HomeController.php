<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $countSiswa = \App\Models\User::where('role', 'siswa')->count();
        $countOrtu = \App\Models\User::where('role', 'orangtua')->count();
        $countGuru = \App\Models\User::where('role', 'guru')->count();
        $countWalikelas = \App\Models\Guru::where('role_guru', 'walikelas')->count();
        $countGuruBk = \App\Models\Guru::where('role_guru', 'bk')->count();
        return view('home', compact('countSiswa', 'countOrtu', 'countGuru', 'countWalikelas', 'countGuruBk'));
    }
}
