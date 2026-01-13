<?php

namespace App\Http\Controllers;

use App\Models\RiwayatKonselingRule;
use Illuminate\Http\Request;

class RiwayatKonselingRuleController extends Controller
{
    public function index()
    {
        $rules = RiwayatKonselingRule::orderBy('min')->get();
        return view('riwayat-rule.index', compact('rules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'min'  => 'required|integer|min:0',
            'max'  => 'nullable|integer|gte:min',
            'nama' => 'required',
            'skor' => 'required|integer'
        ]);

        RiwayatKonselingRule::create($request->all());

        return back()->with('success', 'Rule berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'min'  => 'required|integer|min:0',
            'max'  => 'nullable|integer|gte:min',
            'nama' => 'required',
            'skor' => 'required|integer'
        ]);

        RiwayatKonselingRule::findOrFail($id)
            ->update($request->only('min', 'max', 'nama', 'skor'));

        return back()->with('success', 'Rule berhasil diupdate');
    }

    public function destroy($id)
    {
        RiwayatKonselingRule::findOrFail($id)->delete();
        return back()->with('success', 'Rule berhasil dihapus');
    }
}
