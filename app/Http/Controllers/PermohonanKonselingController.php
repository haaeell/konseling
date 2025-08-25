<?php

namespace App\Http\Controllers;

use App\Models\PermohonanKonseling;
use App\Models\KategoriKonseling;
use App\Models\Siswa;
use App\Notifications\PermohonanKonselingNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class PermohonanKonselingController extends Controller
{
    public function index()
    {
        $permohonanKonseling = PermohonanKonseling::with(['siswa.user', 'kategori'])
            ->orderBy('skor_prioritas', 'desc')
            ->orderBy('created_at', 'asc')
            ->get();
        $kategoriKonseling = KategoriKonseling::all();
        return view('permohonan-konseling.index', compact('permohonanKonseling', 'kategoriKonseling'));
    }

    public function store(Request $request)
    {
        // Cek apakah user adalah siswa
        if (Auth::user()->role !== 'siswa') {
            return redirect()->route('permohonan-konseling.index')->with('error', 'Hanya siswa yang dapat membuat permohonan konseling.');
        }

        $request->validate([
            'kategori_id' => 'required|exists:kategori_konseling,id',
            'tanggal_pengajuan' => 'required|date',
            'deskripsi_permasalahan' => 'required|string',
        ]);

        $kategori = KategoriKonseling::findOrFail($request->kategori_id);
        $siswa = Siswa::where('user_id', Auth::id())->firstOrFail();

        $permohonan = PermohonanKonseling::create([
            'siswa_id' => $siswa->id,
            'kategori_id' => $request->kategori_id,
            'tanggal_pengajuan' => $request->tanggal_pengajuan,
            'deskripsi_permasalahan' => $request->deskripsi_permasalahan,
            'status' => 'menunggu',
            'skor_prioritas' => $kategori->skor_prioritas,
        ]);

        // Kirim notifikasi ke siswa
        $user = $siswa->user;
        Notification::send($user, new PermohonanKonselingNotification($permohonan, 'Permohonan konseling Anda telah dikirim.'));

        return redirect()->route('permohonan-konseling.index')->with('success', 'Permohonan konseling berhasil diajukan.');
    }

    public function approve(Request $request, $id)
    {
        // Cek apakah user adalah guru dengan role BK
        if (Auth::user()->role !== 'guru' || !Auth::user()->guru || Auth::user()->guru->role_guru !== 'bk') {
            return redirect()->route('permohonan-konseling.index')->with('error', 'Hanya guru BK yang dapat menyetujui permohonan.');
        }

        $request->validate([
            'tanggal_disetujui' => 'required|date',
            'tempat' => 'required|string|max:255',
        ]);

        $permohonan = PermohonanKonseling::findOrFail($id);
        $permohonan->update([
            'status' => 'disetujui',
            'tanggal_disetujui' => $request->tanggal_disetujui,
            'tempat' => $request->tempat,
        ]);

        // Kirim notifikasi ke siswa
        $user = $permohonan->siswa->user;
        Notification::send($user, new PermohonanKonselingNotification($permohonan, 'Permohonan konseling Anda telah disetujui.'));

        return redirect()->route('permohonan-konseling.index')->with('success', 'Permohonan konseling berhasil disetujui.');
    }

    public function reject(Request $request, $id)
    {
        // Cek apakah user adalah guru dengan role BK
        if (Auth::user()->role !== 'guru' || !Auth::user()->guru || Auth::user()->guru->role_guru !== 'bk') {
            return redirect()->route('permohonan-konseling.index')->with('error', 'Hanya guru BK yang dapat menolak permohonan.');
        }

        $permohonan = PermohonanKonseling::findOrFail($id);
        $permohonan->update([
            'status' => 'ditolak',
        ]);

        // Kirim notifikasi ke siswa
        $user = $permohonan->siswa->user;
        Notification::send($user, new PermohonanKonselingNotification($permohonan, 'Permohonan konseling Anda telah ditolak.'));

        return redirect()->route('permohonan-konseling.index')->with('success', 'Permohonan konseling berhasil ditolak.');
    }

    public function complete(Request $request, $id)
    {
        // Cek apakah user adalah guru dengan role BK
        if (Auth::user()->role !== 'guru' || !Auth::user()->guru || Auth::user()->guru->role_guru !== 'bk') {
            return redirect()->route('permohonan-konseling.index')->with('error', 'Hanya guru BK yang dapat menyelesaikan permohonan.');
        }

        $request->validate([
            'rangkuman' => 'required|string',
        ]);

        $permohonan = PermohonanKonseling::findOrFail($id);
        $permohonan->update([
            'status' => 'selesai',
            'rangkuman' => $request->rangkuman,
        ]);

        // Kirim notifikasi ke siswa
        $user = $permohonan->siswa->user;
        Notification::send($user, new PermohonanKonselingNotification($permohonan, 'Permohonan konseling Anda telah selesai.'));

        return redirect()->route('permohonan-konseling.index')->with('success', 'Permohonan konseling telah selesai.');
    }
}
