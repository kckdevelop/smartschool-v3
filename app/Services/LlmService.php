<?php

namespace App\Services;

use App\Models\Sekolah;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LlmService
{
    protected $provider;
    protected $apiKey;
    protected $model;

    public function __construct()
    {
        $sekolah = Sekolah::first();
        if ($sekolah) {
            // Use gemini if active and has key
            if ($sekolah->gemini_status === 'aktif' && !empty($sekolah->gemini_key)) {
                $this->provider = 'gemini';
                $this->apiKey = $sekolah->gemini_key;
                $this->model = $sekolah->gemini_model ?? 'gemini-1.5-flash';
            }
            // Else use groq if active and has key
            elseif ($sekolah->groq_status === 'aktif' && !empty($sekolah->groq_key)) {
                $this->provider = 'groq';
                $this->apiKey = $sekolah->groq_key;
                $this->model = $sekolah->groq_model ?? 'llama-3.3-70b-versatile';
            }
            // Fallback to legacy database settings
            else {
                $this->provider = $sekolah->llm_provider ?? 'gemini';
                $this->apiKey = $sekolah->llm_api_key;
                $this->model = $sekolah->llm_model ?? 'gemini-1.5-flash';
            }
        } else {
            $this->provider = 'gemini';
            $this->model = 'gemini-1.5-flash';
        }
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function getModel()
    {
        return $this->model;
    }

    /**
     * Dynamically override model, provider, and API key.
     */
    public function useModel($modelName)
    {
        $sekolah = Sekolah::first();
        if (!$sekolah) return;

        $this->model = $modelName;
        if (str_starts_with($modelName, 'gemini')) {
            $this->provider = 'gemini';
            $this->apiKey = $sekolah->gemini_key;
        } elseif (str_starts_with($modelName, 'llama') || str_starts_with($modelName, 'mixtral') || str_starts_with($modelName, 'gemma')) {
            $this->provider = 'groq';
            $this->apiKey = $sekolah->groq_key;
        } else {
            // Fallback based on active status
            if ($sekolah->gemini_status === 'aktif') {
                $this->provider = 'gemini';
                $this->apiKey = $sekolah->gemini_key;
            } else {
                $this->provider = 'groq';
                $this->apiKey = $sekolah->groq_key;
            }
        }
    }

    /**
     * Check if LLM API Key is configured.
     */
    public function isConfigured()
    {
        return !empty($this->apiKey);
    }

    /**
     * Generate questions via LLM API with enriched parameters.
     *
     * @param string $mapelName
     * @param string $className
     * @param string $topic
     * @param int    $questionCount
     * @param string $type        pilihan_ganda | essay | benar_salah
     * @param string $difficulty  mudah | sedang | sulit
     * @param int    $semester    1 | 2
     * @param string $kd         Kompetensi Dasar (optional)
     * @param string $indikator  Indikator Pencapaian Kompetensi (optional)
     * @return string Raw JSON string from LLM
     * @throws \Exception
     */
    public function generateQuestions(
        $mapelName,
        $className,
        $topic,
        $questionCount,
        $type,
        $difficulty,
        $semester = 1,
        $kd = null,
        $indikator = null,
        $useImage = false
    ) {
        if (!$this->isConfigured()) {
            throw new \Exception('API Key LLM belum dikonfigurasi. Silakan atur API Key di menu Pengaturan LLM terlebih dahulu.');
        }

        $semesterText = "Semester {$semester}";
        $typeArray = is_array($type) ? $type : explode(',', $type);
        $typeArray = array_map('trim', $typeArray);
        $isMixed = count($typeArray) > 1;
        $effectiveType = $isMixed ? 'mixed' : ($typeArray[0] ?? 'pilihan_ganda');

        switch ($effectiveType) {
            case 'pilihan_ganda':
                $typeText = 'Pilihan Ganda (dengan 5 pilihan: A, B, C, D, E)';
                break;
            case 'benar_salah':
                $typeText = 'Benar-Salah (pernyataan yang dinilai Benar atau Salah)';
                break;
            case 'essay':
                $typeText = 'Essay / Uraian Bebas';
                break;
            default:
                $typeText = 'Campuran (gabungan ' . implode(' dan ', array_map(fn($t) => $t === 'pilihan_ganda' ? 'Pilihan Ganda' : ($t === 'benar_salah' ? 'Benar-Salah' : 'Essay'), $typeArray)) . ')';
        }

        $difficultyLower = strtolower($difficulty);
        if ($difficultyLower === 'lots') {
            $difficultyDesc = "LOTS (Lower Order Thinking Skills - Tingkat Kognitif Bloom C1-C2: Mengingat & Memahami)";
        } elseif ($difficultyLower === 'mots') {
            $difficultyDesc = "MOTS (Middle Order Thinking Skills - Tingkat Kognitif Bloom C3-C4: Menerapkan & Menganalisis)";
        } elseif ($difficultyLower === 'hots') {
            $difficultyDesc = "HOTS (Higher Order Thinking Skills - Tingkat Kognitif Bloom C5-C6: Mengevaluasi & Mencipta)";
        } elseif ($difficultyLower === 'mudah') {
            $difficultyDesc = "Mudah (LOTS - C1-C2)";
        } elseif ($difficultyLower === 'sedang') {
            $difficultyDesc = "Sedang (MOTS - C3-C4)";
        } elseif ($difficultyLower === 'sulit') {
            $difficultyDesc = "Sulit (HOTS - C5-C6)";
        } else {
            $difficultyDesc = "Campuran (seimbang antara LOTS, MOTS, dan HOTS)";
        }

        $prompt  = "Anda adalah guru profesional pembuat soal ujian. Buatlah {$questionCount} soal {$typeText} ";
        $prompt .= "dengan tingkat kesulitan / level kognitif {$difficultyDesc} didasarkan pada Taksonomi Bloom untuk:\n";
        $prompt .= "- Mata Pelajaran  : {$mapelName}\n";
        $prompt .= "- Kelas/Tingkat   : {$className}\n";
        $prompt .= "- Semester        : {$semesterText}\n";
        $prompt .= "- Topik/Materi    : {$topic}\n";

        if (!empty($kd)) {
            $prompt .= "- Kompetensi Dasar (KD): {$kd}\n";
        }
        if (!empty($indikator)) {
            $prompt .= "- Indikator Pencapaian Kompetensi (IPK): {$indikator}\n";
        }

        $prompt .= "\nSoal harus relevan, sesuai kurikulum Indonesia, dan berkualitas tinggi.\n";

        $prompt .= "Keluaran HARUS berupa JSON valid tanpa penjelasan tambahan di luar JSON. ";
        $prompt .= "Format JSON berupa array objek dengan struktur PERSIS berikut:\n\n";

        if ($effectiveType === 'pilihan_ganda') {
            $prompt .= '[\n  {\n    "no": 1,\n    "pertanyaan": "Teks pertanyaan...",\n    "pilihan": {\n      "A": "Pilihan A",\n      "B": "Pilihan B",\n      "C": "Pilihan C",\n      "D": "Pilihan D",\n      "E": "Pilihan E"\n    },\n    "kunci_jawaban": "A",\n    "pembahasan": "Pembahasan singkat mengapa jawaban A yang benar..."\n  }\n]';
        } elseif ($effectiveType === 'benar_salah') {
            $prompt .= '[\n  {\n    "no": 1,\n    "pertanyaan": "Pernyataan yang perlu dinilai kebenarannya...",\n    "kunci_jawaban": "Benar",\n    "pembahasan": "Penjelasan mengapa pernyataan tersebut benar atau salah..."\n  }\n]';
        } elseif ($effectiveType === 'essay') {
            $prompt .= '[\n  {\n    "no": 1,\n    "pertanyaan": "Teks pertanyaan...",\n    "kunci_jawaban": "Contoh jawaban ideal atau kata kunci jawaban...",\n    "pembahasan": "Pembahasan singkat materi terkait..."\n  }\n]';
        } else {
            // mixed
            $prompt .= '[\n  {\n    "no": 1,\n    "tipe": "pilihan_ganda",\n    "pertanyaan": "Teks pertanyaan...",\n    "pilihan": {"A":"Pilihan A","B":"Pilihan B","C":"Pilihan C","D":"Pilihan D","E":"Pilihan E"},\n    "kunci_jawaban": "A",\n    "pembahasan": "Pembahasan singkat..."\n  },\n  {\n    "no": 2,\n    "tipe": "essay",\n    "pertanyaan": "Soal essay...",\n    "kunci_jawaban": "Kunci jawaban",\n    "pembahasan": "Pembahasan..."\n  }\n]';
        }

        return $this->callLlm($prompt);
    }

    /**
     * Generate kisi-kisi penilaian via LLM API.
     *
     * @param string $mapelName
     * @param string $className
     * Generate kisi-kisi soal ujian based on Indonesian curriculum.
     */
    public function generateKisiKisi(
        $mapelName,
        $className,
        $semester,
        $jenisPenilaian,
        $tahunPelajaran,
        $kurikulum,
        $alokasiWaktu,
        $jumlahSoal = 20,
        $tipeSoal = 'pilihan_ganda'
    ) {
        if (!$this->isConfigured()) {
            throw new \Exception('API Key LLM belum dikonfigurasi. Silakan atur API Key di menu Pengaturan LLM terlebih dahulu.');
        }

        // Normalize $tipeSoal to an array
        $tipeSoalArray = is_array($tipeSoal) ? $tipeSoal : explode(',', $tipeSoal);
        $tipeSoalArray = array_map('trim', $tipeSoalArray);

        $labels = [];
        foreach ($tipeSoalArray as $t) {
            if ($t === 'pilihan_ganda') {
                $labels[] = 'Pilihan Ganda';
            } elseif ($t === 'essay' || $t === 'uraian') {
                $labels[] = 'Uraian';
            } elseif ($t === 'benar_salah') {
                $labels[] = 'Benar-Salah';
            }
        }
        if (empty($labels)) {
            $labels[] = 'Pilihan Ganda';
        }
        $tipeSoalLabel = implode(', ', $labels);

        $prompt  = "Anda adalah guru profesional pembuat kisi-kisi soal ujian berdasarkan kurikulum Indonesia.\n";
        $prompt .= "Buatlah kisi-kisi penilaian yang lengkap dan terstruktur untuk:\n";
        $prompt .= "- Mata Pelajaran   : {$mapelName}\n";
        $prompt .= "- Kelas/Tingkat    : {$className}\n";
        $prompt .= "- Semester         : {$semester}\n";
        $prompt .= "- Jenis Penilaian  : {$jenisPenilaian}\n";
        $prompt .= "- Tahun Pelajaran  : {$tahunPelajaran}\n";
        $prompt .= "- Kurikulum        : {$kurikulum}\n";
        $prompt .= "- Alokasi Waktu    : {$alokasiWaktu} menit\n";
        $prompt .= "- Jumlah Soal      : {$jumlahSoal} butir soal\n";
        $prompt .= "- Bentuk/Tipe Soal : {$tipeSoalLabel}\n";
        $prompt .= "\nBuatlah minimal 8 butir baris kisi-kisi yang mencakup berbagai level kognitif (C1 sampai C6 Bloom's Taxonomy) yang secara total mencakup seluruh {$jumlahSoal} butir soal tersebut.\n";
        $prompt .= "Pada kolom `no_soal`, sebar nomor soal dari 1 sampai {$jumlahSoal} secara logis (misalnya '1-2', '3-5', atau '6').\n";
        $prompt .= "Pada kolom `bentuk_soal`, Anda HARUS memilih secara variatif dan mendistribusikannya secara logis dari salah satu tipe berikut: " . implode(' atau ', $labels) . ".\n";
        $prompt .= "Keluaran HARUS berupa JSON valid berupa array objek dengan struktur PERSIS berikut:\n\n";
        $prompt .= '[\n  {\n    "no": 1,\n    "kompetensi_dasar": "Teks Kompetensi Dasar (KD)...",\n    "materi_pokok": "Nama materi pokok...",\n    "indikator": "Siswa mampu ... dengan tepat",\n    "level_kognitif": "C2 - Memahami",\n    "no_soal": "1-3",\n    "bentuk_soal": "' . $tipeSoalLabel . '"\n  }\n]';

        return $this->callLlm($prompt);
    }

    /**
     * Generate soal berdasarkan data kisi-kisi yang sudah tersimpan.
     *
     * @param array  $kisiKisiData   Array of kisi-kisi rows from hasil_json
     * @param string $mapelName
     * @param string $className
     * @param int    $semester
     * @param string $jenisPenilaian
     * @param string $tahunPelajaran
     * @param string $tipeSoal       pilihan_ganda | essay | benar_salah | mixed
     * @param int    $jumlahSoal     Total jumlah soal yang dibuat
     * @param string $kesulitan      mudah | sedang | sulit | campuran
     * @return string Raw JSON string from LLM
     * @throws \Exception
     */
    public function generateQuestionsFromKisiKisi(
        array $kisiKisiData,
        $mapelName,
        $className,
        $semester,
        $jenisPenilaian,
        $tahunPelajaran,
        $tipeSoal,
        $jumlahSoal,
        $kesulitan = 'sedang',
        $useImage = false
    ) {
        if (!$this->isConfigured()) {
            throw new \Exception('API Key LLM belum dikonfigurasi. Silakan atur API Key di menu Pengaturan LLM terlebih dahulu.');
        }

        // Normalize $tipeSoal to an array
        $tipeSoalArray = is_array($tipeSoal) ? $tipeSoal : explode(',', $tipeSoal);
        $tipeSoalArray = array_map('trim', $tipeSoalArray);

        $isMixed = count($tipeSoalArray) > 1;
        $effectiveType = $isMixed ? 'mixed' : ($tipeSoalArray[0] ?? 'pilihan_ganda');

        switch ($effectiveType) {
            case 'pilihan_ganda':
                $typeText = 'Pilihan Ganda (dengan 5 pilihan: A, B, C, D, E)';
                break;
            case 'benar_salah':
                $typeText = 'Benar-Salah (pernyataan yang dinilai Benar atau Salah)';
                break;
            case 'essay':
                $typeText = 'Essay / Uraian Bebas';
                break;
            default:
                $typeText = 'Campuran (gabungan ' . implode(' dan ', array_map(fn($t) => $t === 'pilihan_ganda' ? 'Pilihan Ganda' : ($t === 'benar_salah' ? 'Benar-Salah' : 'Essay'), $tipeSoalArray)) . ')';
        }

        $difficultyLower = strtolower($kesulitan);
        if ($difficultyLower === 'lots') {
            $difficultyDesc = "LOTS (Lower Order Thinking Skills - Tingkat Kognitif Bloom C1-C2: Mengingat & Memahami)";
        } elseif ($difficultyLower === 'mots') {
            $difficultyDesc = "MOTS (Middle Order Thinking Skills - Tingkat Kognitif Bloom C3-C4: Menerapkan & Menganalisis)";
        } elseif ($difficultyLower === 'hots') {
            $difficultyDesc = "HOTS (Higher Order Thinking Skills - Tingkat Kognitif Bloom C5-C6: Mengevaluasi & Mencipta)";
        } elseif ($difficultyLower === 'mudah') {
            $difficultyDesc = "Mudah (LOTS - C1-C2)";
        } elseif ($difficultyLower === 'sedang') {
            $difficultyDesc = "Sedang (MOTS - C3-C4)";
        } elseif ($difficultyLower === 'sulit') {
            $difficultyDesc = "Sulit (HOTS - C5-C6)";
        } else {
            $difficultyDesc = "Campuran (seimbang antara LOTS, MOTS, dan HOTS)";
        }

        $prompt  = "Anda adalah guru profesional pembuat soal ujian. Buatlah {$jumlahSoal} soal {$typeText} ";
        $prompt .= "dengan tingkat kesulitan / level kognitif {$difficultyDesc} didasarkan pada Taksonomi Bloom untuk:\n";
        $prompt .= "- Mata Pelajaran  : {$mapelName}\n";
        $prompt .= "- Kelas/Tingkat   : {$className}\n";
        $prompt .= "- Semester        : {$semester}\n";
        $prompt .= "- Jenis Penilaian : {$jenisPenilaian}\n";
        $prompt .= "- Tahun Pelajaran : {$tahunPelajaran}\n\n";
        $prompt .= "Soal-soal HARUS dibuat berdasarkan kisi-kisi penilaian berikut ini:\n\n";

        foreach ($kisiKisiData as $i => $row) {
            $no = ($i + 1);
            $prompt .= "  [{$no}] KD: " . ($row['kompetensi_dasar'] ?? '-') . "\n";
            $prompt .= "       Materi Pokok: " . ($row['materi_pokok'] ?? '-') . "\n";
            $prompt .= "       Indikator: " . ($row['indikator'] ?? '-') . "\n";
            $prompt .= "       Level Kognitif: " . ($row['level_kognitif'] ?? '-') . "\n";
            $prompt .= "       Bentuk Soal: " . ($row['bentuk_soal'] ?? $effectiveType) . "\n\n";
        }

        $prompt .= "Distribusikan soal merata mengikuti seluruh butir kisi-kisi di atas.\n";
        $prompt .= "Soal harus relevan, sesuai kurikulum Indonesia, dan berkualitas tinggi.\n";

        $prompt .= "Keluaran HARUS berupa JSON valid tanpa penjelasan tambahan di luar JSON. ";
        $prompt .= "Format JSON berupa array objek dengan struktur PERSIS berikut:\n\n";

        if ($effectiveType === 'pilihan_ganda') {
            $prompt .= '[\n  {\n    "no": 1,\n    "pertanyaan": "Teks pertanyaan...",\n    "pilihan": {\n      "A": "Pilihan A",\n      "B": "Pilihan B",\n      "C": "Pilihan C",\n      "D": "Pilihan D",\n      "E": "Pilihan E"\n    },\n    "kunci_jawaban": "A",\n    "pembahasan": "Penjelasan singkat mengapa A benar"\n  }\n]';
        } elseif ($effectiveType === 'benar_salah') {
            $prompt .= '[\n  {\n    "no": 1,\n    "pertanyaan": "Pernyataan yang dievaluasi...",\n    "kunci_jawaban": "Benar",\n    "pembahasan": "Penjelasan singkat"\n  }\n]';
        } elseif ($effectiveType === 'essay') {
            $prompt .= '[\n  {\n    "no": 1,\n    "pertanyaan": "Soal essay...",\n    "kunci_jawaban": "Kunci jawaban / poin penting yang diharapkan",\n    "pembahasan": "Penjelasan lebih detail"\n  }\n]';
        } else {
            // mixed
            $prompt .= '[\n  {\n    "no": 1,\n    "tipe": "pilihan_ganda",\n    "pertanyaan": "Teks pertanyaan...",\n    "pilihan": {"A":"...","B":"...","C":"...","D":"...","E":"..."}, \n    "kunci_jawaban": "A",\n    "pembahasan": "Penjelasan"\n  },\n  {\n    "no": 2,\n    "tipe": "essay",\n    "pertanyaan": "Soal essay...",\n    "kunci_jawaban": "Kunci jawaban",\n    "pembahasan": "Penjelasan"\n  }\n]';
        }

        return $this->callLlm($prompt);
    }

    /**
     * Route prompt to the correct provider (Gemini or OpenAI).
     */
    protected function callLlm($prompt)
    {
        if ($this->provider === 'gemini') {
            // Daftar model fallback: jika model utama high demand, coba model berikutnya
            $modelChain = array_unique(array_filter([
                $this->model,
                'gemini-1.5-flash',
                'gemini-1.5-pro',
            ]));

            $lastException = null;
            foreach ($modelChain as $modelName) {
                try {
                    return $this->callGemini($prompt, $modelName);
                } catch (\Exception $e) {
                    $lastException = $e;
                    $isHighDemand = stripos($e->getMessage(), 'high demand') !== false
                        || stripos($e->getMessage(), 'overloaded') !== false
                        || stripos($e->getMessage(), 'sedang sibuk') !== false
                        || stripos($e->getMessage(), 'resource has been exhausted') !== false;

                    if ($isHighDemand && $modelName !== end($modelChain)) {
                        Log::warning("Model {$modelName} high demand, fallback ke model berikutnya.");
                        sleep(3); // jeda singkat sebelum model fallback
                        continue;
                    }
                    throw $e;
                }
            }
            throw $lastException ?? new \Exception('Semua model Gemini sedang tidak tersedia.');
        } else {
            return $this->callGroq($prompt);
        }
    }

    /**
     * Call Google Gemini API.
     * - Gemini 2.x+ : uses /v1beta endpoint + responseMimeType (JSON mode supported)
     * - Gemini 1.x  : uses /v1 stable endpoint, no responseMimeType
     */
    protected function callGemini($prompt, $modelName = null)
    {
        $modelName = $modelName ?? $this->model;

        // Detect model generation to pick the right endpoint & config
        $isGemini2  = str_starts_with($modelName, 'gemini-2') || str_starts_with($modelName, 'gemini-exp');
        $apiVersion = $isGemini2 ? 'v1beta' : 'v1';
        $url        = "https://generativelanguage.googleapis.com/{$apiVersion}/models/{$modelName}:generateContent?key={$this->apiKey}";

        $generationConfig = ['temperature' => 0.7];
        if ($isGemini2) {
            $generationConfig['responseMimeType'] = 'application/json';
        }

        $maxRetries    = 3;
        $retryDelays   = [5, 15, 30];
        $lastException = null;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                ])->timeout(240)->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt]
                            ]
                        ]
                    ],
                    'generationConfig' => $generationConfig,
                ]);

                if ($response->failed()) {
                    $status    = $response->status();
                    $errorData = $response->json();
                    $errorMsg  = $errorData['error']['message'] ?? '';

                    $isRetryable = in_array($status, [429, 500, 503])
                        || stripos($errorMsg, 'high demand') !== false
                        || stripos($errorMsg, 'overloaded') !== false
                        || stripos($errorMsg, 'resource has been exhausted') !== false
                        || stripos($errorMsg, 'try again later') !== false;

                    if ($isRetryable && $attempt < $maxRetries) {
                        $delay = $retryDelays[$attempt - 1] ?? 30;
                        Log::warning("[{$modelName}] Gemini high demand (attempt {$attempt}/{$maxRetries}), retry in {$delay}s. Status: {$status}.");
                        sleep($delay);
                        continue;
                    }

                    Log::error("[{$modelName}] Gemini API Error: " . $response->body());
                    // Lempar dengan flag high demand agar callLlm bisa fallback ke model lain
                    $friendlyMsg = ($isRetryable)
                        ? "Server Gemini sedang sibuk (high demand) [{$modelName}]. Mencoba model alternatif..."
                        : 'Gemini API Error: ' . $errorMsg;
                    throw new \Exception($friendlyMsg);
                }

                $result = $response->json();
                $text   = $result['candidates'][0]['content']['parts'][0]['text'] ?? '';

                if (empty($text)) {
                    throw new \Exception('Gagal mendapatkan respons teks dari API Gemini.');
                }

                if ($modelName !== $this->model) {
                    Log::info("[Fallback] Soal berhasil dibuat menggunakan model {$modelName}.");
                }

                return $text;

            } catch (\Exception $e) {
                $lastException = $e;

                $isRetryable = stripos($e->getMessage(), 'high demand') !== false
                    || stripos($e->getMessage(), 'overloaded') !== false
                    || stripos($e->getMessage(), 'sedang sibuk') !== false
                    || stripos($e->getMessage(), 'resource has been exhausted') !== false
                    || stripos($e->getMessage(), 'cURL error 28') !== false;

                if ($isRetryable && $attempt < $maxRetries) {
                    $delay = $retryDelays[$attempt - 1] ?? 30;
                    Log::warning("[{$modelName}] Gemini retry (attempt {$attempt}/{$maxRetries}) in {$delay}s: " . $e->getMessage());
                    sleep($delay);
                    continue;
                }

                Log::error("[{$modelName}] LlmService callGemini failed: " . $e->getMessage());
                throw $e;
            }
        }

        Log::error("[{$modelName}] LlmService callGemini: semua percobaan gagal.");
        throw $lastException ?? new \Exception("Server Gemini sedang sibuk (high demand) [{$modelName}]. Mencoba model alternatif...");
    }



    /**
     * Call Groq API (OpenAI-compatible).
     */
    protected function callGroq($prompt)
    {
        $url = "https://api.groq.com/openai/v1/chat/completions";

        $body = [
            'model'           => $this->model,
            'response_format' => ['type' => 'json_object'],
            'temperature'     => 0.7,
            'messages'        => [
                [
                    'role'    => 'system',
                    'content' => 'Anda adalah guru profesional pembuat soal ujian dan kisi-kisi penilaian sekolah Indonesia. Anda selalu memformat jawaban dalam format JSON sesuai yang diminta.'
                ],
                [
                    'role'    => 'user',
                    'content' => $prompt
                ]
            ]
        ];

        $maxRetries  = 3;
        $retryDelays = [5, 15, 30];
        $lastException = null;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type'  => 'application/json',
                ])->timeout(90)->post($url, $body);

                if ($response->failed()) {
                    $status    = $response->status();
                    $errorData = $response->json();
                    $errorMsg  = $errorData['error']['message'] ?? '';
                    $errorCode = $errorData['error']['code'] ?? '';
                    
                    $isQuotaExceeded = ($errorCode === 'insufficient_quota') 
                        || stripos($errorMsg, 'quota') !== false 
                        || stripos($errorMsg, 'billing') !== false;

                    $isDecommissioned = ($errorCode === 'model_decommissioned')
                        || stripos($errorMsg, 'decommissioned') !== false
                        || stripos($errorMsg, 'no longer supported') !== false;

                    if ($isDecommissioned) {
                        Log::error('Groq model decommissioned: ' . $response->body());
                        throw new \Exception("Model Groq '{$this->model}' sudah tidak tersedia (decommissioned). Silakan pilih model lain seperti llama-3.3-70b-versatile di Pengaturan LLM.");
                    }

                    if ($isQuotaExceeded) {
                        Log::error('Groq API Quota Exceeded: ' . $response->body());
                        throw new \Exception('Saldo / Kuota API Groq Anda telah habis. Silakan isi ulang saldo di dashboard.groq.com.');
                    }

                    $isRetryable = in_array($status, [429, 500, 503])
                        || stripos($errorMsg, 'rate limit') !== false
                        || stripos($errorMsg, 'overloaded') !== false;

                    if ($isRetryable && $attempt < $maxRetries) {
                        $delay = $retryDelays[$attempt - 1] ?? 30;
                        Log::warning("Groq rate limit/error (attempt {$attempt}/{$maxRetries}), retry in {$delay}s. Status: {$status}");
                        sleep($delay);
                        continue;
                    }

                    Log::error('Groq API Error details: ' . $response->body());
                    $friendlyMsg = $status === 429
                        ? 'Server Groq sedang sibuk (rate limit). Silakan coba beberapa saat lagi.'
                        : 'Groq API Error: ' . $errorMsg;
                    throw new \Exception($friendlyMsg);
                }

                $result = $response->json();
                $text   = $result['choices'][0]['message']['content'] ?? '';

                if (empty($text)) {
                    throw new \Exception('Gagal mendapatkan respons teks dari API Groq.');
                }

                return $text;

            } catch (\Exception $e) {
                $lastException = $e;

                $isRetryable = stripos($e->getMessage(), 'rate limit') !== false
                    || stripos($e->getMessage(), 'overloaded') !== false
                    || stripos($e->getMessage(), 'cURL error 28') !== false;

                if ($isRetryable && $attempt < $maxRetries) {
                    $delay = $retryDelays[$attempt - 1] ?? 30;
                    Log::warning("Groq retryable error (attempt {$attempt}/{$maxRetries}), retry in {$delay}s: " . $e->getMessage());
                    sleep($delay);
                    continue;
                }

                Log::error('LlmService callGroq failed: ' . $e->getMessage());
                throw $e;
            }
        }

        Log::error('LlmService callGroq: semua percobaan gagal.');
        throw $lastException ?? new \Exception('Gagal menghubungi API Groq setelah beberapa percobaan.');
    }
}
