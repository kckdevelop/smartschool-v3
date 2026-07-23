<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Check-Up Gukar — SmartSchool</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background: #fff;
            padding: 20px;
            font-size: 10px;
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

        /* ─── TABLE ─── */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .report-table th {
            background-color: #e0f2fe;
            border: 1px solid #bae6fd;
            padding: 6px 8px;
            font-weight: 700;
            text-align: center;
            font-size: 9px;
            text-transform: uppercase;
            color: #0369a1;
        }
        .report-table td {
            border: 1px solid #e2e8f0;
            padding: 5px 8px;
            font-size: 9.5px;
        }
        .report-table tr:nth-child(even) td { background: #f8fafc; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }

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
            @page { size: A4 landscape; margin: 1.5cm; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    {{-- Kop Surat --}}
    @include('partials.kop-surat')

    {{-- Title --}}
    <h2 class="report-title">Laporan Hasil Check-Up Kesehatan Guru &amp; Karyawan</h2>
    <div class="report-subtitle">
        Periode: {{ $semesterLabel }}
    </div>

    {{-- Print button (no-print) --}}
    <div class="no-print" style="text-align:right; margin-bottom:14px;">
        <button onclick="window.print()"
                style="background:#0284c7; color:#fff; border:none; padding:8px 18px; border-radius:6px; font-weight:700; cursor:pointer; font-size:12px;">
            🖨️ Cetak / Simpan PDF
        </button>
    </div>

    <table class="report-table">
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th>Nama Lengkap</th>
                <th style="width:90px; text-align:center;">Peran</th>
                <th style="width:100px; text-align:center;">TB / BB</th>
                <th style="width:110px; text-align:center;">IMT (Kategori)</th>
                <th style="width:100px; text-align:center;">Tek. Darah</th>
                <th style="width:90px; text-align:center;">Kolesterol</th>
                <th style="width:90px; text-align:center;">Gula Darah</th>
                <th style="width:90px; text-align:center;">Asam Urat</th>
                <th style="width:110px; text-align:center;">Tgl Periksa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($gukarCheckupList as $index => $row)
                @php
                    $nama = '';
                    $peran = '';
                    if ($row->guru) {
                        $nama = $row->guru->nama_guru;
                        $peran = 'Guru';
                    } elseif ($row->karyawan) {
                        $nama = $row->karyawan->nama_karyawan;
                        $peran = 'Karyawan';
                    }
                @endphp
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="font-bold">{{ $nama ?: '-' }}</td>
                    <td class="text-center">{{ $peran }}</td>
                    <td class="text-center">{{ $row->tinggi_badan ?? '-' }} cm / {{ $row->berat_badan ?? '-' }} kg</td>
                    <td class="text-center font-bold">
                        @if($row->imt)
                            {{ number_format($row->imt, 1) }}
                            @if($row->kategori)
                                <span style="font-weight:normal; font-size:8.5px; color:#475569;">({{ $row->kategori }})</span>
                            @endif
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">{{ $row->tekanan_darah ?? '-' }}</td>
                    <td class="text-center">
                        @if($row->kolesterol !== null)
                            @php
                                $cholKat = $row->kolesterol < 200 ? 'Normal' : 'Tinggi';
                            @endphp
                            {{ $row->kolesterol }} <span style="font-size: 8px; color: #475569;">({{ $cholKat }})</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if($row->gula_darah !== null)
                            @php
                                $glu = $row->gula_darah;
                                $tipe = $row->tipe_gula_darah ?? 'sewaktu';
                                $gluKat = '';
                                if ($tipe === 'puasa') {
                                    if ($glu < 75) $gluKat = 'Rendah';
                                    elseif ($glu <= 99) $gluKat = 'Normal';
                                    elseif ($glu <= 125) $gluKat = 'Prediabetes';
                                    else $gluKat = 'Diabetes';
                                } else {
                                    if ($glu < 140) $gluKat = 'Normal';
                                    elseif ($glu <= 199) $gluKat = 'Prediabetes';
                                    else $gluKat = 'Diabetes';
                                }
                            @endphp
                            {{ $row->gula_darah }} <span style="font-size: 8px; color: #475569;">({{ $gluKat }} - {{ ucfirst($tipe) }})</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center">
                        @if($row->asam_urat !== null)
                            @php
                                $uric = $row->asam_urat;
                                $gender = 'L';
                                if ($row->guru) {
                                    $gender = $row->guru->jenkel;
                                } elseif ($row->karyawan) {
                                    $gender = $row->karyawan->jenkel;
                                }
                                $uricKat = '';
                                if ($gender === 'P') {
                                    if ($uric < 2.4) $uricKat = 'Rendah';
                                    elseif ($uric <= 6.0) $uricKat = 'Normal';
                                    else $uricKat = 'Tinggi';
                                } else {
                                    if ($uric < 2.4) $uricKat = 'Rendah';
                                    elseif ($uric <= 7.0) $uricKat = 'Normal';
                                    else $uricKat = 'Tinggi';
                                }
                            @endphp
                            {{ $row->asam_urat }} <span style="font-size: 8px; color: #475569;">({{ $uricKat }})</span>
                        @else
                            -
                        @endif
                    </td>
                    <td class="text-center" style="color:#475569;">
                        {{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center" style="padding:20px; color:#64748b;">Tidak ada data check-up.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

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
