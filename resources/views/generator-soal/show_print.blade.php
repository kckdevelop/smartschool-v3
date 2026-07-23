<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Soal - {{ $riwayat->topik }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Times New Roman', Times, serif;
            color: #000;
            background: #fff;
            padding: 30px 35px;
            line-height: 1.5;
            font-size: 11pt;
        }

        /* ──────────────────────────────── KOP ────────────────────────────────── */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 18px;
        }
        .kop-logo {
            width: 75px;
            height: auto;
            margin-right: 18px;
        }
        .kop-text { flex-grow: 1; text-align: center; }
        .kop-sekolah {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .kop-sub { font-size: 9.5pt; margin-top: 2px; font-style: italic; }

        /* ──────────────────────────────── JUDUL ──────────────────────────────── */
        .dokumen-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin-bottom: 16px;
            letter-spacing: 0.5px;
        }
        .dokumen-subtitle {
            text-align: center;
            font-size: 9.5pt;
            font-style: italic;
            margin-top: 3px;
            font-weight: normal;
            text-decoration: none;
        }

        /* ──────────────────────────────── META ───────────────────────────────── */
        .meta-table {
            width: 100%;
            margin-bottom: 18px;
            font-size: 10pt;
            border-collapse: collapse;
        }
        .meta-table td { padding: 2.5px 0; vertical-align: top; }
        .meta-separator { border-top: 1px solid #000; margin-bottom: 16px; }

        /* ─────────────────────────────── SOAL ───────────────────────────────── */
        .soal-list { padding-left: 22px; margin: 0; }
        .soal-item {
            margin-bottom: 18px;
            page-break-inside: avoid;
        }
        .soal-tanya { margin-bottom: 6px; text-align: justify; }

        .pilihan-table {
            width: 100%;
            margin-left: 8px;
            border-collapse: collapse;
        }
        .pilihan-table td { padding: 2px 4px; vertical-align: top; }
        .pilihan-huruf { width: 22px; font-weight: bold; }

        .jawaban-kotak {
            margin-top: 8px;
            margin-left: 8px;
            font-size: 10pt;
            font-weight: bold;
            letter-spacing: 2px;
        }

        /* ─────────────────────── LEMBAR KUNCI JAWABAN ───────────────────────── */
        .page-kunci {
            page-break-before: always;
            padding-top: 20px;
        }

        /* Tabel ringkasan kunci (nomor + jawaban) */
        .tabel-kunci-ringkas {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            font-size: 10.5pt;
        }
        .tabel-kunci-ringkas th {
            background: #000;
            color: #fff;
            padding: 5px 10px;
            text-align: center;
            font-size: 10pt;
        }
        .tabel-kunci-ringkas td {
            border: 1px solid #888;
            padding: 5px 10px;
            text-align: center;
            vertical-align: middle;
        }
        .tabel-kunci-ringkas tr:nth-child(even) td { background: #f5f5f5; }

        /* Pembahasan detail */
        .pembahasan-list { list-style: none; padding: 0; margin: 0; }
        .pembahasan-item {
            margin-bottom: 16px;
            page-break-inside: avoid;
            border-left: 3px solid #333;
            padding: 8px 12px;
            background: #fafafa;
        }
        .pembahasan-nomor {
            font-weight: bold;
            font-size: 10.5pt;
            margin-bottom: 4px;
        }
        .pembahasan-soal {
            font-size: 9.5pt;
            color: #333;
            margin-bottom: 6px;
            font-style: italic;
        }
        .pembahasan-kunci {
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 3px;
        }
        .pembahasan-teks { font-size: 10pt; text-align: justify; }

        /* ──────────────────────────────── PRINT ─────────────────────────────── */
        @media print {
            body { padding: 0; }
            .no-print { display: none !important; }
        }

        /* ──────────────────────────────── TOOLBAR ───────────────────────────── */
        .toolbar {
            background: #1e293b;
            padding: 10px 20px;
            margin: -30px -35px 28px -35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .toolbar-title { font-family: sans-serif; font-weight: bold; color: #fff; font-size: 13px; }
        .toolbar-btns { display: flex; gap: 8px; }
        .btn-toolbar {
            padding: 6px 16px;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            font-size: 12px;
        }
        .btn-print  { background: #0d9488; color: #fff; }
        .btn-close  { background: #64748b; color: #fff; }
    </style>
</head>
<body>

    {{-- TOOLBAR (tidak tercetak) --}}
    <div class="toolbar no-print">
        <div class="toolbar-title">
            🖨️ Pratinjau Cetak — Soal + Kunci Jawaban (Lembar Terpisah)
        </div>
        <div class="toolbar-btns">
            <button onclick="window.print()" class="btn-toolbar btn-print">Cetak Sekarang</button>
            <button onclick="window.close()" class="btn-toolbar btn-close">Tutup</button>
        </div>
    </div>

    {{-- ══════════════════ HALAMAN 1 — LEMBAR SOAL ══════════════════ --}}

    @include('partials.kop-surat')

    <div class="dokumen-title">
        LEMBAR SOAL
        @php
            $tipeLabel = match($riwayat->tipe_soal) {
                'pilihan_ganda'           => 'PILIHAN GANDA',
                'benar_salah'             => 'BENAR - SALAH',
                default                   => 'ESSAY / URAIAN',
            };
        @endphp
        {{ $tipeLabel }}
    </div>

    {{-- META INFO --}}
    <table class="meta-table">
        <tr>
            <td style="width:18%;">Mata Pelajaran</td>
            <td style="width:2%;">:</td>
            <td style="width:38%;font-weight:bold;">{{ $riwayat->mapel->nama_mapel ?? '—' }}</td>
            <td style="width:18%;">Kelas / Tingkat</td>
            <td style="width:2%;">:</td>
            <td style="font-weight:bold;">Kelas {{ $riwayat->kelas->tingkat ?? '—' }}</td>
        </tr>
        <tr>
            <td>Topik / Materi</td>
            <td>:</td>
            <td>{{ $riwayat->topik }}</td>
            <td>Tingkat Kesulitan</td>
            <td>:</td>
            <td>{{ in_array(strtolower($riwayat->kesulitan), ['lots', 'mots', 'hots']) ? strtoupper($riwayat->kesulitan) : ucfirst($riwayat->kesulitan) }}</td>
        </tr>
        <tr>
            <td>Guru Pengampu</td>
            <td>:</td>
            <td>{{ $riwayat->guru->nama_guru ?? '—' }}</td>
            <td>Tanggal Buat</td>
            <td>:</td>
            <td>{{ $riwayat->created_at->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Jumlah Soal</td>
            <td>:</td>
            <td>{{ count($riwayat->hasil_json) }} Butir</td>
            <td>Semester</td>
            <td>:</td>
            <td>{{ $riwayat->semester ? 'Semester ' . $riwayat->semester : '—' }}</td>
        </tr>
    </table>
    <div class="meta-separator"></div>

    {{-- IDENTITAS PESERTA --}}
    <table style="width:100%;margin-bottom:18px;font-size:10pt;">
        <tr>
            <td style="width:48%;">Nama &nbsp;&nbsp;: ___________________________________</td>
            <td style="width:4%;"></td>
            <td style="width:48%;">No. Absen : _____________ &nbsp; Nilai : _____________</td>
        </tr>
    </table>

    {{-- DAFTAR SOAL --}}
    <ol class="soal-list">
        @foreach($riwayat->hasil_json as $idx => $q)
            <li class="soal-item">
                <p class="soal-tanya">{!! nl2br(e($q['pertanyaan'])) !!}</p>



                @php
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
                @endphp

                @if($qType === 'pilihan_ganda' && isset($q['pilihan']))
                    <table class="pilihan-table">
                        @foreach($q['pilihan'] as $h => $pText)
                            <tr>
                                <td class="pilihan-huruf">{{ $h }}.</td>
                                <td>{{ $pText }}</td>
                            </tr>
                        @endforeach
                    </table>
                @elseif($qType === 'benar_salah')
                    <div class="jawaban-kotak">
                        ( &nbsp; ) Benar &nbsp;&nbsp;&nbsp;&nbsp; ( &nbsp; ) Salah
                    </div>
                @else
                    <div style="margin-top:8px;margin-left:8px;">
                        <div style="border-bottom:1px solid #aaa;margin-bottom:6px;height:20px;"></div>
                        <div style="border-bottom:1px solid #aaa;margin-bottom:6px;height:20px;"></div>
                        <div style="border-bottom:1px solid #aaa;height:20px;"></div>
                    </div>
                @endif
            </li>
        @endforeach
    </ol>

    {{-- ══════════════════ HALAMAN 2 — KUNCI JAWABAN & PEMBAHASAN ══════════════════ --}}
    <div class="page-kunci">

        @include('partials.kop-surat')

        <div class="dokumen-title">
            KUNCI JAWABAN &amp; PEMBAHASAN
            <div class="dokumen-subtitle">
                {{ $riwayat->mapel->nama_mapel ?? '—' }} &nbsp;|&nbsp;
                {{ $riwayat->topik }} &nbsp;|&nbsp;
                {{ $tipeLabel }} &nbsp;|&nbsp;
                Kesulitan: {{ in_array(strtolower($riwayat->kesulitan), ['lots', 'mots', 'hots']) ? strtoupper($riwayat->kesulitan) : ucfirst($riwayat->kesulitan) }}
            </div>
        </div>

        {{-- TABEL RINGKASAN KUNCI --}}
        @if(!str_contains($riwayat->tipe_soal, 'essay') && !str_contains($riwayat->tipe_soal, 'uraian'))
        <p style="font-weight:bold;margin-bottom:8px;font-size:10.5pt;">A. Ringkasan Kunci Jawaban</p>
        <table class="tabel-kunci-ringkas">
            <thead>
                <tr>
                    <th style="width:60px;">No.</th>
                    <th>Kunci Jawaban</th>
                    <th style="width:60px;">No.</th>
                    <th>Kunci Jawaban</th>
                    <th style="width:60px;">No.</th>
                    <th>Kunci Jawaban</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $soalArr  = array_values($riwayat->hasil_json);
                    $total    = count($soalArr);
                    $cols     = 3;
                    $perCol   = (int) ceil($total / $cols);
                @endphp
                @for($row = 0; $row < $perCol; $row++)
                    <tr>
                        @for($col = 0; $col < $cols; $col++)
                            @php $idx = $col * $perCol + $row; @endphp
                            @if($idx < $total)
                                <td>{{ $idx + 1 }}</td>
                                <td style="text-align:left;padding-left:14px;">
                                    <strong>{{ $soalArr[$idx]['kunci_jawaban'] ?? '—' }}</strong>
                                </td>
                            @else
                                <td></td><td></td>
                            @endif
                        @endfor
                    </tr>
                @endfor
            </tbody>
        </table>
        @endif

        {{-- PEMBAHASAN DETAIL --}}
        <p style="font-weight:bold;margin-bottom:10px;font-size:10.5pt;">
            {{ !str_contains($riwayat->tipe_soal, 'essay') && !str_contains($riwayat->tipe_soal, 'uraian') ? 'B.' : 'A.' }}
            Pembahasan Lengkap
        </p>
        <ul class="pembahasan-list">
            @foreach($riwayat->hasil_json as $index => $q)
                <li class="pembahasan-item">
                    <div class="pembahasan-nomor">Soal No. {{ $index + 1 }}</div>
                    <div class="pembahasan-soal">{{ Str::limit($q['pertanyaan'], 120) }}</div>
                    <div class="pembahasan-kunci">
                        Kunci Jawaban: {{ $q['kunci_jawaban'] ?? '—' }}
                    </div>
                    <div class="pembahasan-teks">
                        <strong>Pembahasan:</strong>
                        {{ $q['pembahasan'] ?? 'Tidak ada pembahasan tersedia.' }}
                    </div>
                </li>
            @endforeach
        </ul>

        <div style="margin-top:30px;border-top:1px solid #000;padding-top:10px;font-size:9.5pt;text-align:right;font-style:italic;">
            Dokumen ini digenerate oleh SmartSchool AI — {{ now()->translatedFormat('d F Y, H:i') }} WIB
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => { window.print(); }, 500);
        });
    </script>
</body>
</html>
