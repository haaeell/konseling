<?php

namespace App\Http\Controllers;

use App\Models\TahunAkademik;
use Illuminate\Http\Request;

class TahunAkademikController extends Controller
{
    public function index()
    {
        $tahunAkademiks = TahunAkademik::all();
        $kelas = \App\Models\Kelas::with('tahunAkademik')->get();
        return view('tahun-akademik.index', compact('tahunAkademiks', 'kelas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tahun' => 'required|string|max:255',
        ]);

        TahunAkademik::create($request->only('tahun'));
        return redirect()->route('tahun-akademik.index')->with('success', 'Tahun Akademik berhasil ditambahkan.');
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'tahun' => 'required|string|max:255',
        ]);

        $tahunAkademik = TahunAkademik::findOrFail($id);
        $tahunAkademik->update($request->only('tahun'));
        return redirect()->route('tahun-akademik.index')->with('success', 'Tahun Akademik berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tahunAkademik = TahunAkademik::findOrFail($id);
        $tahunAkademik->delete();
        return redirect()->route('tahun-akademik.index')->with('success', 'Tahun Akademik berhasil dihapus.');
    }
}
