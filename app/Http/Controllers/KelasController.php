<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index()
    {
        $kelas = Kelas::with('tahunAkademik')->get();
        $tahunAkademiks = TahunAkademik::all();
        return view('kelas.index', compact('kelas', 'tahunAkademiks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
        ]);

        Kelas::create($request->only('nama', 'tahun_akademik_id'));
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'tahun_akademik_id' => 'required|exists:tahun_akademik,id',
        ]);

        $kelas = Kelas::findOrFail($id);
        $kelas->update($request->only('nama', 'tahun_akademik_id'));
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->delete();
        return redirect()->route('kelas.index')->with('success', 'Kelas berhasil dihapus.');
    }
}
