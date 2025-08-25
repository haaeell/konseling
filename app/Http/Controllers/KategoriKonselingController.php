<?php

namespace App\Http\Controllers;

use App\Models\KategoriKonseling;
use Illuminate\Http\Request;

class KategoriKonselingController extends Controller
{
    public function index()
    {
        $kategoriKonseling = KategoriKonseling::all();
        return view('kategori-konseling.index', compact('kategoriKonseling'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'skor_prioritas' => 'required|integer|min:1|max:10',
        ]);

        KategoriKonseling::create($request->only('nama', 'skor_prioritas'));
        return redirect()->route('kategori-konseling.index')->with('success', 'Kategori Konseling berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'skor_prioritas' => 'required|integer|min:1|max:10',
        ]);

        $kategori = KategoriKonseling::findOrFail($id);
        $kategori->update($request->only('nama', 'skor_prioritas'));
        return redirect()->route('kategori-konseling.index')->with('success', 'Kategori Konseling berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $kategori = KategoriKonseling::findOrFail($id);
        $kategori->delete();
        return redirect()->route('kategori-konseling.index')->with('success', 'Kategori Konseling berhasil dihapus.');
    }
}
