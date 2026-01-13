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
            'skor'        => 'required|integer',
            'guide_text'  => 'required',
            'range_min'   => 'required|integer',
            'range_max'   => 'required|integer|gte:range_min',
        ]);

        $data = $request->all();

        if (!isset($data['range_min'])) {
            $data['range_min'] = null;
            $data['range_max'] = null;
        }

        SubKriteria::create($data);

        return back()->with('success', 'Sub kriteria berhasil ditambahkan');
    }

    public function updateSub(Request $request, $id)
    {
        $request->validate([
            'nama_sub' => 'required',
            'skor'     => 'required|integer',
            'range_min' => 'required|integer',
            'range_max' => 'required|integer|gte:range_min',
            'guide_text' => 'required'
        ]);

        $data = $request->only('nama_sub', 'skor', 'guide_text', 'range_min', 'range_max');

        SubKriteria::findOrFail($id)->update($data);

        return back()->with('success', 'Sub kriteria berhasil diupdate');
    }

    public function destroySub($id)
    {
        SubKriteria::findOrFail($id)->delete();
        return back()->with('success', 'Sub kriteria berhasil dihapus');
    }
}
