<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\PermohonanKonseling;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();

        if (in_array($user->role, ['siswa', 'orangtua'])) {

            $siswaId = null;

            if ($user->role === 'siswa') {
                $siswaId = $user->siswa->id;
            }

            if ($user->role === 'orangtua') {
                $siswaId = $user->orangtua->siswa->id;
            }

            $konselingSelesai = PermohonanKonseling::where('siswa_id', $siswaId)
                ->where('status', 'selesai')->count();

            $konselingPending = PermohonanKonseling::where('siswa_id', $siswaId)
                ->where('status', 'menunggu')->count();

            $konselingDisetujui = PermohonanKonseling::where('siswa_id', $siswaId)
                ->where('status', 'disetujui')->count();

            $jadwalKonseling = PermohonanKonseling::where('siswa_id', $siswaId)
                ->orderBy('tanggal_disetujui', 'asc')
                ->whereIn('status', ['disetujui', 'menunggu'])
                ->limit(5)
                ->get();

            $notifikasi = $user->notifications()
                ->where('read_at', null)
                ->latest()
                ->limit(5)
                ->get();

            return view('dashboard.siswa', compact(
                'konselingSelesai',
                'konselingPending',
                'konselingDisetujui',
                'jadwalKonseling',
                'notifikasi'
            ));
        }

        $countSiswa = User::where('role', 'siswa')->count();
        $countOrtu = User::where('role', 'orangtua')->count();
        $countGuru = User::where('role', 'guru')->count();
        $countWalikelas = Guru::where('role_guru', 'walikelas')->count();
        $countGuruBk = Guru::where('role_guru', 'bk')->count();


        return view('home', compact('countSiswa', 'countOrtu', 'countGuru', 'countWalikelas', 'countGuruBk'));
    }
}
