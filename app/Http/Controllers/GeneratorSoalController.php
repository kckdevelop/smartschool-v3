<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Sekolah;
use App\Models\RiwayatGenerateSoal;
use App\Models\RiwayatGenerateKisiKisi;
use App\Models\Tugas;
use App\Services\LlmService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GeneratorSoalController extends Controller
{
    protected $llmService;

    public function __construct(LlmService $llmService)
    {
        $this->llmService = $llmService;
    }

    // ─────────────────────────────────────────────────────────
    //  GENERATE SOAL
    // ─────────────────────────────────────────────────────────

    /**
     * Display the question generator dashboard and history.
     */
    public function index(Request $request)
    {
        $gurus  = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $kelas  = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();

        $history = RiwayatGenerateSoal::with(['guru', 'mapel', 'kelas'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $sekolah = Sekolah::first();

        return view('generator-soal.index', compact('gurus', 'mapels', 'kelas', 'history', 'sekolah'));
    }

    /**
     * AJAX endpoint to generate questions.
     */
    public function generate(Request $request)
    {
        set_time_limit(300); // 5 menit untuk proses AI

        $request->validate([
            'model'            => 'required|string|max:100',
            'id_guru'          => 'required|integer|exists:guru,id_guru',
            'id_mapel'         => 'required|integer|exists:mapel,id_mapel',
            'tingkat'          => 'required|integer|min:1|max:12',
            'topik'            => 'required|string|max:255',
            'jumlah_soal'      => 'required|integer|min:1|max:50',
            'tipe_soal'        => 'required|array|min:1',
            'tipe_soal.*'      => 'string|in:pilihan_ganda,essay,benar_salah',
            'kesulitan'        => 'required|string|in:mudah,sedang,sulit,lots,mots,hots,campuran',
            'semester'         => 'nullable|integer|in:1,2',
            'kompetensi_dasar' => 'nullable|string|max:1000',
            'indikator'        => 'nullable|string|max:1000',
        ]);

        $this->llmService->useModel($request->model);

        if (!$this->llmService->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'API Key LLM belum dikonfigurasi. Silakan masuk ke Pengaturan LLM.'
            ], 400);
        }

        try {
            $mapel    = Mapel::findOrFail($request->id_mapel);
            $tingkat  = (int) $request->tingkat;
            $jenjang  = $tingkat <= 6 ? 'SD' : ($tingkat <= 9 ? 'SMP' : 'SMK/SMA');
            $namaKelas = 'Kelas ' . $tingkat . ' ' . $jenjang;

            // Cari id_kelas dari tabel kelas berdasarkan tingkat (ambil yg pertama)
            $kelasObj = Kelas::where('tingkat', $tingkat)->first();
            $idKelas  = $kelasObj ? $kelasObj->id_kelas : null;

            $rawJson = $this->llmService->generateQuestions(
                $mapel->nama_mapel,
                $namaKelas,
                $request->topik,
                $request->jumlah_soal,
                $request->tipe_soal,         // array
                $request->kesulitan,
                $request->semester ?? 1,
                $request->kompetensi_dasar,
                $request->indikator,
                false
            );

            // Structure validation
            $questionsArray = $this->parseJsonResponse($rawJson);
            $tipeSoalForSave = implode(', ', (array) $request->tipe_soal);
            $isMixedType = count((array) $request->tipe_soal) > 1;

            foreach ($questionsArray as $q) {
                if (!isset($q['pertanyaan'])) {
                    throw new \Exception('Format soal dari AI tidak lengkap (kolom pertanyaan tidak ditemukan).');
                }
                if (!$isMixedType && in_array('pilihan_ganda', (array) $request->tipe_soal) && (!isset($q['pilihan']) || !is_array($q['pilihan']))) {
                    throw new \Exception('Format pilihan ganda dari AI tidak lengkap (kolom pilihan tidak ditemukan).');
                }
            }

            $riwayat = RiwayatGenerateSoal::create([
                'id_guru'          => $request->id_guru,
                'id_mapel'         => $request->id_mapel,
                'id_kelas'         => $idKelas,
                'topik'            => $request->topik,
                'jumlah_soal'      => $request->jumlah_soal,
                'tipe_soal'        => $tipeSoalForSave,
                'kesulitan'        => $request->kesulitan,
                'hasil_json'       => $questionsArray,
                'semester'         => $request->semester ?? 1,
                'kompetensi_dasar' => $request->kompetensi_dasar,
                'indikator'        => $request->indikator,
            ]);

            // Decrement active provider quota
            $sekolah = Sekolah::first();
            if ($sekolah) {
                if ($this->llmService->getProvider() === 'gemini') {
                    $sekolah->decrement('gemini_quota');
                } else {
                    $sekolah->decrement('groq_quota');
                }
            }

            return response()->json([
                'success'    => true,
                'history_id' => $riwayat->id_riwayat,
                'data'       => $questionsArray
            ]);

        } catch (\Exception $e) {
            Log::error('Error in GeneratorSoalController@generate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat soal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show(Request $request, $id)
    {
        $riwayat = RiwayatGenerateSoal::with(['guru', 'mapel', 'kelas'])->findOrFail($id);

        if ($request->has('print')) {
            return view('generator-soal.show_print', compact('riwayat'));
        }

        $kelas = [];
        if ($riwayat->kelas) {
            $kelas = Kelas::where('tingkat', $riwayat->kelas->tingkat)
                ->where('status', 'aktif')
                ->orderBy('rombel')
                ->get();
        }

        return view('generator-soal.show', compact('riwayat', 'kelas'));
    }

    /**
     * Delete a generation history record.
     */
    public function destroy($id)
    {
        RiwayatGenerateSoal::findOrFail($id)->delete();
        return redirect()->route('generator-soal.index')
            ->with('success', 'Riwayat generate soal berhasil dihapus.');
    }

    /**
     * Create a task from generated questions.
     */
    public function buatTugas(Request $request)
    {
        $request->validate([
            'id_riwayat'  => 'required|integer|exists:riwayat_generate_soal,id_riwayat',
            'judul_tugas' => 'required|string|max:50',
            'id_kelas'    => 'required|integer|exists:kelas,id_kelas',
        ]);

        try {
            $riwayat   = RiwayatGenerateSoal::findOrFail($request->id_riwayat);
            $questions = $riwayat->hasil_json;

            $descriptionHtml  = "<p><strong>Topik/Materi:</strong> {$riwayat->topik}</p>";
            $descriptionHtml .= "<p><strong>Tingkat Kesulitan:</strong> " . ucfirst($riwayat->kesulitan) . "</p>";
            if ($riwayat->kompetensi_dasar) {
                $descriptionHtml .= "<p><strong>Kompetensi Dasar:</strong> {$riwayat->kompetensi_dasar}</p>";
            }
            $descriptionHtml .= "<hr><h3>Daftar Soal:</h3><ol>";

            foreach ($questions as $idx => $q) {
                $descriptionHtml .= "<li><p>" . e($q['pertanyaan']) . "</p>";



                $qType = $q['tipe'] ?? '';
                if (empty($qType)) {
                    if (isset($q['pilihan']) && is_array($q['pilihan'])) {
                        $qType = 'pilihan_ganda';
                    } elseif (isset($q['kunci_jawaban']) && in_array($q['kunci_jawaban'], ['Benar', 'Salah'])) {
                        $qType = 'benar_salah';
                    } else {
                        $qType = 'essay';
                    }
                }

                if ($qType === 'pilihan_ganda' && isset($q['pilihan'])) {
                    $descriptionHtml .= "<ul style='list-style-type:upper-alpha;padding-left:20px'>";
                    foreach ($q['pilihan'] as $pilihanText) {
                        $descriptionHtml .= "<li>" . e($pilihanText) . "</li>";
                    }
                    $descriptionHtml .= "</ul>";
                } elseif ($qType === 'benar_salah') {
                    $descriptionHtml .= "<p style='margin-left:10px;'><em>( &nbsp; ) Benar &nbsp;&nbsp;&nbsp;&nbsp; ( &nbsp; ) Salah</em></p>";
                }
                $descriptionHtml .= "</li>";
            }
            $descriptionHtml .= "</ol>";

            Tugas::create([
                'tanggal'     => date('Y-m-d'),
                'id_guru'     => $riwayat->id_guru,
                'judul_tugas' => $request->judul_tugas,
                'id_kelas'    => $request->id_kelas,
                'deskripsi'   => $descriptionHtml,
                'lampiran'    => null,
                'status'      => 'aktif',
            ]);

            return response()->json(['success' => true, 'message' => 'Soal berhasil disimpan sebagai tugas baru!']);

        } catch (\Exception $e) {
            Log::error('Error in GeneratorSoalController@buatTugas: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan tugas: ' . $e->getMessage()], 500);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  PENGATURAN LLM
    // ─────────────────────────────────────────────────────────

    public function pengaturan()
    {
        $sekolah = Sekolah::first();
        if (!$sekolah) {
            return redirect()->route('dashboard')->with('error', 'Silakan isi Data Sekolah terlebih dahulu.');
        }
        return view('generator-soal.pengaturan', compact('sekolah'));
    }

    public function simpanPengaturan(Request $request)
    {
        $sekolah = Sekolah::first();
        if (!$sekolah) {
            return redirect()->route('dashboard')->with('error', 'Silakan isi Data Sekolah terlebih dahulu.');
        }

        $request->validate([
            'groq_key'    => 'nullable|string|max:1000',
            'groq_status' => 'required|string|in:aktif,nonaktif',
            'groq_model'  => 'required|string|max:100',
            'groq_quota'  => 'required|integer|min:0',
            'gemini_key'    => 'nullable|string|max:1000',
            'gemini_status' => 'required|string|in:aktif,nonaktif',
            'gemini_model'  => 'required|string|max:100',
            'gemini_quota'  => 'required|integer|min:0',
        ]);

        // Auto determine main llm_provider for backward compatibility
        $provider = 'gemini';
        $apiKey = $request->gemini_key;
        $model = $request->gemini_model;

        if ($request->gemini_status === 'aktif') {
            $provider = 'gemini';
            $apiKey = $request->gemini_key;
            $model = $request->gemini_model;
        } elseif ($request->groq_status === 'aktif') {
            $provider = 'groq';
            $apiKey = $request->groq_key;
            $model = $request->groq_model;
        }

        $sekolah->update([
            'llm_provider'  => $provider,
            'llm_api_key'   => $apiKey,
            'llm_model'     => $model,
            'groq_key'      => $request->groq_key,
            'groq_status'   => $request->groq_status,
            'groq_model'    => $request->groq_model,
            'groq_quota'    => $request->groq_quota,
            'gemini_key'    => $request->gemini_key,
            'gemini_status' => $request->gemini_status,
            'gemini_model'  => $request->gemini_model,
            'gemini_quota'  => $request->gemini_quota,
        ]);

        return redirect()->route('generator-soal.pengaturan')
            ->with('success', 'Pengaturan API Key LLM berhasil diperbarui.');
    }

    // ─────────────────────────────────────────────────────────
    //  GENERATE KISI-KISI
    // ─────────────────────────────────────────────────────────

    /**
     * Display the kisi-kisi generator page and history.
     */
    public function kisikisiIndex()
    {
        $gurus  = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
        $mapels = Mapel::orderBy('nama_mapel')->get();
        $kelas  = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();

        $history = RiwayatGenerateKisiKisi::with(['guru', 'mapel', 'kelas'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $sekolah = Sekolah::first();

        return view('generator-soal.kisikisi_index', compact('gurus', 'mapels', 'kelas', 'history', 'sekolah'));
    }

    /**
     * AJAX endpoint to generate kisi-kisi.
     */
    public function kisikisiGenerate(Request $request)
    {
        $request->validate([
            'model'           => 'required|string|max:100',
            'id_guru'         => 'required|integer|exists:guru,id_guru',
            'id_mapel'        => 'required|integer|exists:mapel,id_mapel',
            'id_kelas'        => 'required|integer|exists:kelas,id_kelas',
            'semester'        => 'required|integer|in:1,2',
            'jenis_penilaian' => 'required|string|in:PTS,PAS,UAS,UTS,Harian,Akhir Semester',
            'tahun_pelajaran' => 'required|string|max:20',
            'kurikulum'       => 'required|string|in:Merdeka,K13,KTSP',
            'alokasi_waktu'   => 'required|integer|min:15|max:240',
            'jumlah_soal'     => 'required|integer|min:5|max:100',
            'tipe_soal'       => 'required|array|min:1',
            'tipe_soal.*'     => 'string|in:pilihan_ganda,essay,uraian,benar_salah',
        ]);

        $this->llmService->useModel($request->model);

        if (!$this->llmService->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'API Key LLM belum dikonfigurasi. Silakan masuk ke Pengaturan LLM.'
            ], 400);
        }

        try {
            $mapel    = Mapel::findOrFail($request->id_mapel);
            $kelasObj = Kelas::findOrFail($request->id_kelas);
            $namaKelas = $kelasObj->tingkat . ' ' . $kelasObj->rombel;

            $rawJson = $this->llmService->generateKisiKisi(
                $mapel->nama_mapel,
                $namaKelas,
                $request->semester,
                $request->jenis_penilaian,
                $request->tahun_pelajaran,
                $request->kurikulum,
                $request->alokasi_waktu,
                $request->jumlah_soal,
                $request->tipe_soal
            );

            $kisiKisiArray = $this->parseJsonResponse($rawJson);

            $riwayat = RiwayatGenerateKisiKisi::create([
                'id_guru'         => $request->id_guru,
                'id_mapel'        => $request->id_mapel,
                'id_kelas'        => $request->id_kelas,
                'semester'        => $request->semester,
                'jenis_penilaian' => $request->jenis_penilaian,
                'tahun_pelajaran' => $request->tahun_pelajaran,
                'kurikulum'       => $request->kurikulum,
                'alokasi_waktu'   => $request->alokasi_waktu,
                'jumlah_soal'     => $request->jumlah_soal,
                'tipe_soal'       => implode(', ', $request->tipe_soal),
                'hasil_json'      => $kisiKisiArray,
            ]);

            // Decrement active provider quota
            $sekolah = Sekolah::first();
            if ($sekolah) {
                if ($this->llmService->getProvider() === 'gemini') {
                    $sekolah->decrement('gemini_quota');
                } else {
                    $sekolah->decrement('groq_quota');
                }
            }

            return response()->json([
                'success'    => true,
                'history_id' => $riwayat->id_kisikisi,
                'data'       => $kisiKisiArray
            ]);

        } catch (\Exception $e) {
            Log::error('Error in GeneratorSoalController@kisikisiGenerate: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat kisi-kisi: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show detail / print preview of a kisi-kisi record.
     */
    public function kisikisiShow($id)
    {
        $riwayat = RiwayatGenerateKisiKisi::with(['guru', 'mapel', 'kelas'])->findOrFail($id);
        return view('generator-soal.kisikisi_show', compact('riwayat'));
    }

    /**
     * Delete a kisi-kisi history record.
     */
    public function kisikisiDestroy($id)
    {
        RiwayatGenerateKisiKisi::findOrFail($id)->delete();
        return redirect()->route('generator-soal.kisikisi.index')
            ->with('success', 'Riwayat kisi-kisi berhasil dihapus.');
    }

    // ─────────────────────────────────────────────────────────
    //  GENERATE SOAL FROM KISI-KISI
    // ─────────────────────────────────────────────────────────

    /**
     * Display the "Generate Soal dari Kisi-Kisi" page.
     */
    public function fromKisiKisi()
    {
        $mapels  = Mapel::orderBy('nama_mapel')->get();
        $gurus   = Guru::where('status', 'aktif')->orderBy('nama_guru')->get();
        $kelas   = Kelas::where('status', 'aktif')->orderBy('tingkat')->orderBy('rombel')->get();
        $sekolah = Sekolah::first();

        return view('generator-soal.from_kisikisi', compact('mapels', 'gurus', 'kelas', 'sekolah'));
    }

    /**
     * AJAX: Get list of kisi-kisi records for a given mapel.
     */
    public function getKisiKisiByMapel(Request $request)
    {
        $request->validate(['id_mapel' => 'required|integer|exists:mapel,id_mapel']);

        $list = RiwayatGenerateKisiKisi::with(['kelas', 'guru'])
            ->where('id_mapel', $request->id_mapel)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($k) {
                return [
                    'id'              => $k->id_kisikisi,
                    'jenis_penilaian' => $k->jenis_penilaian,
                    'tahun_pelajaran' => $k->tahun_pelajaran,
                    'kurikulum'       => $k->kurikulum,
                    'semester'        => $k->semester,
                    'kelas'           => isset($k->kelas->tingkat) ? 'Kelas ' . $k->kelas->tingkat : '-',
                    'guru'            => $k->guru->nama_guru ?? '-',
                    'id_guru'         => $k->id_guru,
                    'id_kelas'        => $k->id_kelas,
                    'tingkat'         => $k->kelas->tingkat ?? null,
                    'tipe_soal'       => $k->tipe_soal,
                    'jumlah_butir'    => $k->jumlah_soal ?? count($k->hasil_json ?? []),
                    'created_at'      => $k->created_at->format('d M Y'),
                ];
            });

        return response()->json(['success' => true, 'data' => $list]);
    }

    /**
     * AJAX: Generate soal from a selected kisi-kisi record.
     */
    public function generateFromKisiKisi(Request $request)
    {
        set_time_limit(300); // 5 menit untuk proses AI

        $request->validate([
            'model'           => 'required|string|max:100',
            'id_kisikisi'     => 'required|integer|exists:riwayat_generate_kisikisi,id_kisikisi',
            'id_guru'         => 'required|integer|exists:guru,id_guru',
            'tingkat'         => 'required|integer|min:1|max:12',
            'tipe_soal'       => 'required|array|min:1',
            'tipe_soal.*'     => 'string|in:pilihan_ganda,essay,uraian,benar_salah',
            'jumlah_soal'     => 'required|integer|min:1|max:50',
            'kesulitan'       => 'required|string|in:mudah,sedang,sulit,lots,mots,hots,campuran',
        ]);

        $this->llmService->useModel($request->model);

        if (!$this->llmService->isConfigured()) {
            return response()->json([
                'success' => false,
                'message' => 'API Key LLM belum dikonfigurasi. Silakan masuk ke Pengaturan LLM.'
            ], 400);
        }

        try {
            $kisiKisi  = RiwayatGenerateKisiKisi::with(['mapel', 'kelas'])->findOrFail($request->id_kisikisi);
            $tingkat   = (int) $request->tingkat;
            $jenjang   = $tingkat <= 6 ? 'SD' : ($tingkat <= 9 ? 'SMP' : 'SMK/SMA');
            $namaKelas = 'Kelas ' . $tingkat . ' ' . $jenjang;

            // Cari id_kelas dari tabel kelas berdasarkan tingkat (ambil yg pertama)
            $kelasObj = Kelas::where('tingkat', $tingkat)->first();
            $idKelas  = $kelasObj ? $kelasObj->id_kelas : null;

            $rawJson = $this->llmService->generateQuestionsFromKisiKisi(
                $kisiKisi->hasil_json ?? [],
                $kisiKisi->mapel->nama_mapel ?? '-',
                $namaKelas,
                $kisiKisi->semester,
                $kisiKisi->jenis_penilaian,
                $kisiKisi->tahun_pelajaran,
                $request->tipe_soal,
                $request->jumlah_soal,
                $request->kesulitan,
                false
            );

            $questionsArray = $this->parseJsonResponse($rawJson);

            // Validate structure
            foreach ($questionsArray as $q) {
                if (!isset($q['pertanyaan'])) {
                    throw new \Exception('Format soal dari AI tidak lengkap (kolom pertanyaan tidak ditemukan).');
                }
            }

            // Determine effective tipe_soal for saving
            $tipeSoalForSave = implode(', ', $request->tipe_soal);

            $riwayat = RiwayatGenerateSoal::create([
                'id_guru'          => $request->id_guru,
                'id_mapel'         => $kisiKisi->id_mapel,
                'id_kelas'         => $idKelas,
                'topik'            => "Dari Kisi-Kisi: {$kisiKisi->jenis_penilaian} {$kisiKisi->tahun_pelajaran}",
                'jumlah_soal'      => $request->jumlah_soal,
                'tipe_soal'        => $tipeSoalForSave,
                'kesulitan'        => $request->kesulitan,
                'hasil_json'       => $questionsArray,
                'semester'         => $kisiKisi->semester,
                'kompetensi_dasar' => null,
                'indikator'        => null,
            ]);

            // Decrement active provider quota
            $sekolah = Sekolah::first();
            if ($sekolah) {
                if ($this->llmService->getProvider() === 'gemini') {
                    $sekolah->decrement('gemini_quota');
                } else {
                    $sekolah->decrement('groq_quota');
                }
            }

            return response()->json([
                'success'    => true,
                'history_id' => $riwayat->id_riwayat,
                'data'       => $questionsArray,
            ]);

        } catch (\Exception $e) {
            Log::error('Error in GeneratorSoalController@generateFromKisiKisi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat soal: ' . $e->getMessage()
            ], 500);
        }
    }

    // ─────────────────────────────────────────────────────────
    //  HELPER
    // ─────────────────────────────────────────────────────────

    /**
     * Parse and normalize JSON response from LLM.
     */
    protected function parseJsonResponse($rawJson)
    {
        $cleanJson = trim($rawJson);
        if (strpos($cleanJson, '```') === 0) {
            $cleanJson = preg_replace('/^```(?:json)?\s+|\s+```$/i', '', $cleanJson);
            $cleanJson = trim($cleanJson);
        }
        // strip trailing markdown code fence
        $cleanJson = preg_replace('/```$/', '', $cleanJson);
        $cleanJson = trim($cleanJson);

        $decoded = json_decode($cleanJson, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('LLM JSON Decode error: ' . json_last_error_msg() . ' | Raw: ' . $rawJson);
            throw new \Exception('AI tidak mengembalikan format JSON yang valid. Silakan coba kembali.');
        }

        if (!is_array($decoded)) {
            throw new \Exception('AI tidak mengembalikan array data yang valid.');
        }

        // Normalize wrapped objects e.g. {"soal": [...]} or {"kisi_kisi": [...]}
        if (!array_is_list($decoded)) {
            foreach ($decoded as $val) {
                if (is_array($val) && array_is_list($val)) {
                    return $val;
                }
            }
            return [$decoded];
        }

        return $decoded;
    }
}
