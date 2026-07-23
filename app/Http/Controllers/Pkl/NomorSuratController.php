<?php

namespace App\Http\Controllers\Pkl;

use App\Http\Controllers\Controller;
use App\Models\PklNomorSurat;
use Illuminate\Http\Request;

class NomorSuratController extends Controller
{
    public function index()
    {
        $jenis = ['permohonan', 'penempatan', 'penarikan'];
        $records = [];
        foreach ($jenis as $j) {
            $records[$j] = PklNomorSurat::firstOrCreate(
                ['jenis_surat' => $j],
                [
                    'format_nomor'     => '{NO}/PKL/{BULAN-ROMAWI}/{TAHUN}',
                    'counter_terakhir' => 0,
                    'tahun_reset'      => date('Y'),
                ]
            );
        }

        return view('pkl.nomor-surat.index', compact('records'));
    }

    public function update(Request $request, string $jenis)
    {
        $request->validate([
            'format_nomor' => 'required|string|max:150',
            'prefix'       => 'nullable|string|max:50',
        ]);

        PklNomorSurat::updateOrCreate(
            ['jenis_surat' => $jenis],
            [
                'format_nomor' => $request->format_nomor,
                'prefix'       => $request->prefix,
            ]
        );

        return back()->with('success', 'Format nomor surat berhasil diperbarui.');
    }

    public function resetCounter(string $jenis)
    {
        PklNomorSurat::where('jenis_surat', $jenis)->update(['counter_terakhir' => 0]);
        return back()->with('success', 'Counter nomor surat berhasil direset.');
    }
}
