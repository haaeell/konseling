<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\SubKriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KriteriaController extends Controller
{
    // ================== KRITERIA ==================

    public function index()
    {
        $kriteria = Kriteria::with('subKriteria')->get();
        return view('kriteria.index', compact('kriteria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'  => 'required',
            'bobot' => 'required|numeric'
        ]);

        Kriteria::create($request->only('nama', 'bobot'));

        return back()->with('success', 'Kriteria berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama'  => 'required',
            'bobot' => 'required|numeric'
        ]);

        Kriteria::findOrFail($id)->update($request->only('nama', 'bobot'));

        return back()->with('success', 'Kriteria berhasil diupdate');
    }

    public function destroy($id)
    {
        Kriteria::findOrFail($id)->delete();
        return back()->with('success', 'Kriteria berhasil dihapus');
    }

    // ================== SUB KRITERIA ==================

    public function storeSub(Request $request)
    {
        $request->validate([
            'kriteria_id' => 'required|exists:kriteria,id',
            'nama_sub'    => 'required',
            'skor'        => 'required|integer'
        ]);

        SubKriteria::create($request->all());

        return back()->with('success', 'Sub kriteria berhasil ditambahkan');
    }

    public function updateSub(Request $request, $id)
    {
        $request->validate([
            'nama_sub' => 'required',
            'skor'     => 'required|integer'
        ]);

        SubKriteria::findOrFail($id)->update($request->only('nama_sub', 'skor'));

        return back()->with('success', 'Sub kriteria berhasil diupdate');
    }

    public function destroySub($id)
    {
        SubKriteria::findOrFail($id)->delete();
        return back()->with('success', 'Sub kriteria berhasil dihapus');
    }
}
