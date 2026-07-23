<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan IMT Siswa — SmartSchool</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background: #fff;
            padding: 20px;
            font-size: 11px;
            line-height: 1.4;
        }

        /* ─── KOP SURAT ─── */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 12px;
            margin-bottom: 16px;
        }
        .kop-logo { width: 70px; height: 70px; object-fit: contain; margin-right: 20px; }
        .kop-detail { flex: 1; text-align: center; }
        .kop-sekolah { font-size: 15px; font-weight: 800; text-transform: uppercase; margin-bottom: 2px; letter-spacing: 0.5px; }
        .kop-npsn { font-size: 10px; font-weight: 500; color: #64748b; margin-bottom: 4px; }
        .kop-alamat { font-size: 9.5px; font-style: italic; color: #475569; }

        /* ─── TITLE & META ─── */
        .report-title {
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0 0 6px 0;
            letter-spacing: 0.3px;
        }
        .report-subtitle {
            text-align: center;
            font-size: 10px;
            color: #475569;
            margin-bottom: 14px;
        }

        /* ─── CLASS SECTION ─── */
        .class-section {
            page-break-inside: auto;
            margin-bottom: 28px;
        }
        .class-header {
            page-break-inside: avoid;
            background: #f1f5f9;
            border-left: 4px solid #6366f1;
            padding: 8px 12px;
            margin-bottom: 6px;
            border-radius: 0 4px 4px 0;
        }
        .report-table tr {
            page-break-inside: avoid;
        }
        .class-name {
            font-size: 12px;
            font-weight: 800;
            color: #3730a3;
        }
        .class-meta {
            font-size: 9px;
            color: #64748b;
            margin-top: 2px;
        }
        .class-summary {
            display: flex;
            gap: 16px;
            font-size: 9.5px;
            margin-top: 4px;
        }
        .class-summary span { display: inline-flex; align-items: center; gap: 4px; }

        /* ─── TABLE ─── */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .report-table th {
            background-color: #e0e7ff;
            border: 1px solid #c7d2fe;
            padding: 6px 8px;
            font-weight: 700;
            text-align: center;
            font-size: 9px;
            text-transform: uppercase;
            color: #3730a3;
        }
        .report-table td {
            border: 1px solid #e2e8f0;
            padding: 5px 8px;
            font-size: 9.5px;
        }
        .report-table tr:nth-child(even) td { background: #f8fafc; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }

        /* ─── IMT CATEGORY BADGE ─── */
        .badge-kat {
            display: inline-block;
            padding: 2px 7px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 8.5px;
        }
        .kat-kurus    { background: #fef3c7; color: #92400e; }
        .kat-normal   { background: #dcfce7; color: #166534; }
        .kat-gemuk    { background: #ffedd5; color: #9a3412; }
        .kat-obesitas { background: #fee2e2; color: #991b1b; }
        .kat-default  { background: #f1f5f9; color: #475569; }

        /* ─── TREND BADGE ─── */
        .trend-badge {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            padding: 2px 7px;
            border-radius: 20px;
            font-weight: 700;
            font-size: 8.5px;
        }
        .trend-naik   { background: #fee2e2; color: #991b1b; }
        .trend-turun  { background: #dcfce7; color: #166534; }
        .trend-tetap  { background: #f1f5f9; color: #475569; }
        .trend-baru   { background: #dbeafe; color: #1e40af; }
        .trend-belum  { background: #fef9c3; color: #854d0e; }

        /* ─── SUMMARY FOOTER ─── */
        .category-summary {
            display: flex;
            gap: 8px;
            font-size: 9px;
            margin-top: 4px;
            flex-wrap: wrap;
        }
        .cat-pill {
            padding: 2px 8px;
            border-radius: 12px;
            font-weight: 700;
        }

        /* ─── SIGNATURE ─── */
        .signature-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-box { text-align: center; width: 200px; }
        .signature-title { margin-bottom: 48px; font-size: 10px; }
        .signature-name { font-weight: 700; text-decoration: underline; font-size: 10px; }
        .signature-nip { font-size: 8.5px; color: #475569; }

        /* ─── PRINT ─── */
        @media print {
            body { padding: 0; }
            @page { size: A4; margin: 1.5cm; }
            .class-section { page-break-inside: auto; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    {{-- Kop Surat --}}
    @include('partials.kop-surat')

    {{-- Title --}}
    <h2 class="report-title">Laporan Status Gizi (IMT) Siswa</h2>
    <div class="report-subtitle">
        Periode: {{ $semesterLabel }} &nbsp;|&nbsp;
        Analisis tren naik/turun IMT dibandingkan semester sebelumnya
    </div>

    {{-- Print button (no-print) --}}
    <div class="no-print" style="text-align:right; margin-bottom:14px;">
        <button onclick="window.print()"
                style="background:#6366f1; color:#fff; border:none; padding:8px 18px; border-radius:6px; font-weight:700; cursor:pointer; font-size:12px;">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    {{-- Per Class Sections --}}
    @forelse($imtPerKelas as $kelasGroup)
        @php
            $kelas     = $kelasGroup['kelas'];
            $siswaData = $kelasGroup['siswaData'];
            $kat       = $kelasGroup['kategoriCount'];
            $total     = $kelasGroup['totalSiswa'];
            $diperiksa = $kelasGroup['totalDiperiksa'];
        @endphp

        <div class="class-section">
            <div class="class-header">
                <div class="class-name">
                    Kelas {{ $kelas->nama_kelas }}
                    @if($kelas->jurusan) — {{ $kelas->jurusan->nama_jurusan }} @endif
                </div>
                <div class="class-summary">
                    <span>Total Siswa: <strong>{{ $total }}</strong></span>
                    <span>Diperiksa: <strong>{{ $diperiksa }}</strong></span>
                    <span>Belum: <strong>{{ $total - $diperiksa }}</strong></span>
                    <span style="margin-left:8px;">
                        <span class="cat-pill" style="background:#fef3c7; color:#92400e;">Kurus: {{ $kat['Kurus'] }}</span>
                        <span class="cat-pill" style="background:#dcfce7; color:#166534;">Normal: {{ $kat['Normal'] }}</span>
                        <span class="cat-pill" style="background:#ffedd5; color:#9a3412;">Gemuk: {{ $kat['Gemuk'] }}</span>
                        <span class="cat-pill" style="background:#fee2e2; color:#991b1b;">Obesitas: {{ $kat['Obesitas'] }}</span>
                    </span>
                </div>
            </div>

            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width:36px;">No</th>
                        <th>Nama Siswa</th>
                        <th style="width:70px;">TB (cm)</th>
                        <th style="width:70px;">BB (kg)</th>
                        <th style="width:64px;">IMT</th>
                        <th style="width:90px;">Kategori</th>
                        <th style="width:110px;">Tren IMT</th>
                        <th style="width:90px;">Tgl Periksa</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($siswaData as $i => $row)
                    <tr>
                        <td class="text-center">{{ $i + 1 }}</td>
                        <td class="font-bold">{{ $row['siswa']->nama_siswa }}</td>

                        @if($row['current'])
                            <td class="text-center">{{ $row['current']->tinggi_badan ?? '-' }}</td>
                            <td class="text-center">{{ $row['current']->berat_badan ?? '-' }}</td>
                            <td class="text-center font-bold">{{ number_format($row['current']->imt, 1) }}</td>
                            <td class="text-center">
                                @php
                                    $katLower = strtolower($row['current']->kategori ?? '');
                                    $katCls = str_contains($katLower, 'kurus') ? 'kat-kurus'
                                        : (str_contains($katLower, 'normal') ? 'kat-normal'
                                        : (str_contains($katLower, 'gemuk') ? 'kat-gemuk'
                                        : (str_contains($katLower, 'obesitas') ? 'kat-obesitas' : 'kat-default')));
                                @endphp
                                <span class="badge-kat {{ $katCls }}">{{ $row['current']->kategori ?? '-' }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $trendCls = 'trend-' . $row['trend'];
                                    $trendText = match($row['trend']) {
                                        'naik'  => '↑ ' . $row['trendLabel'],
                                        'turun' => '↓ ' . $row['trendLabel'],
                                        'tetap' => '— Tetap',
                                        'baru'  => '★ Baru',
                                        default => '—',
                                    };
                                @endphp
                                <span class="trend-badge {{ $trendCls }}">{{ $trendText }}</span>
                                @if($row['prev'] && in_array($row['trend'], ['naik','turun','tetap']))
                                    <div style="font-size:8px; color:#94a3b8; margin-top:2px;">
                                        Sem. lalu: {{ number_format($row['prev']->imt, 1) }}
                                    </div>
                                @endif
                            </td>
                            <td class="text-center" style="color:#64748b;">
                                {{ \Carbon\Carbon::parse($row['current']->tanggal)->format('d/m/Y') }}
                            </td>
                        @else
                            <td colspan="5" class="text-center">
                                <span class="trend-badge trend-belum">Belum Diperiksa</span>
                            </td>
                            <td></td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <p style="text-align:center; color:#94a3b8; padding:40px 0;">Tidak ada data kelas aktif yang tersedia.</p>
    @endforelse

    {{-- Tanda Tangan --}}
    <div class="signature-container">
        <div class="signature-box">
            <div style="margin-bottom:4px; font-size:10px;">
                {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
            </div>
            <div class="signature-title">Admin UKS</div>
            <div class="signature-name">{{ Auth::user()->nama_lengkap ?? 'Admin UKS' }}</div>
            <div class="signature-nip">{{ Auth::user()->level ?? 'Admin' }}</div>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => window.print(), 600);
        });
    </script>
</body>
</html>
