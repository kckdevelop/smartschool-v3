<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekap Presensi — {{ $siswa->nama_siswa }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10.5pt;
            color: #111;
            background: #fff;
            padding: 18mm 20mm 20mm 25mm;
        }

        /* ── Kop Header ─────────────────────────── */
        .kop-surat   { display: flex; align-items: center; border-bottom: 3px double #000; padding-bottom: 10px; margin-bottom: 14px; }
        .kop-logo    { width: 75px; height: 75px; object-fit: contain; margin-right: 18px; }
        .kop-detail  { flex: 1; text-align: center; }
        .kop-sekolah { font-size: 15pt; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; }
        .kop-npsn    { font-size: 8.5pt; color: #555; margin: 2px 0; }
        .kop-alamat  { font-size: 9pt; font-style: italic; color: #333; }

        /* ── Judul Dokumen ─────────────────────── */
        .doc-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: .6px;
            margin: 14px 0 4px;
        }
        .doc-subtitle {
            text-align: center;
            font-size: 9.5pt;
            color: #555;
            margin-bottom: 16px;
        }

        /* ── Info Siswa ────────────────────────── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 9.5pt;
        }
        .info-table td {
            padding: 3px 6px;
            border: none;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 120px;
            font-weight: bold;
            color: #333;
        }
        .info-table td:nth-child(2) {
            width: 14px;
        }

        /* ── Ringkasan Statistik ─────────────── */
        .stats-wrap {
            display: flex;
            gap: 0;
            margin-bottom: 14px;
            border: 1.5px solid #000;
            border-radius: 0;
            overflow: hidden;
        }
        .stat-box {
            flex: 1;
            text-align: center;
            padding: 8px 4px;
            border-right: 1px solid #ccc;
        }
        .stat-box:last-child { border-right: none; }
        .stat-num  { font-size: 17pt; font-weight: bold; }
        .stat-lbl  { font-size: 8pt; color: #555; text-transform: uppercase; letter-spacing: .3px; }
        .stat-hadir  { background: #d1fae5; }
        .stat-sakit  { background: #fef9c3; }
        .stat-izin   { background: #dbeafe; }
        .stat-alfa   { background: #fee2e2; }
        .stat-total  { background: #f3f4f6; }
        .stat-persen { background: #ede9fe; }

        /* ── Tabel Presensi ──────────────────── */
        .presensi-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        .presensi-table thead tr {
            background: #1e3a5f;
            color: #fff;
        }
        .presensi-table th {
            padding: 6px 8px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #1e3a5f;
            font-size: 9pt;
        }
        .presensi-table th.left { text-align: left; }
        .presensi-table td {
            padding: 5px 8px;
            border: 1px solid #d1d5db;
            vertical-align: middle;
        }
        .presensi-table tbody tr:nth-child(even) { background: #f8fafc; }
        .presensi-table .center { text-align: center; }
        .presensi-table .mono   { font-family: 'Courier New', monospace; }

        .badge-hadir  { color: #065f46; background: #d1fae5; padding: 2px 8px; border-radius: 10px; font-weight: 700; font-size: 8pt; }
        .badge-sakit  { color: #92400e; background: #fef3c7; padding: 2px 8px; border-radius: 10px; font-weight: 700; font-size: 8pt; }
        .badge-izin   { color: #1e40af; background: #dbeafe; padding: 2px 8px; border-radius: 10px; font-weight: 700; font-size: 8pt; }
        .badge-alfa   { color: #7f1d1d; background: #fee2e2; padding: 2px 8px; border-radius: 10px; font-weight: 700; font-size: 8pt; }

        /* ── Tanda Tangan ────────────────────── */
        .ttd-section {
            margin-top: 28px;
            display: flex;
            justify-content: flex-end;
        }
        .ttd-box {
            text-align: center;
            width: 220px;
        }
        .ttd-kota    { font-size: 9.5pt; margin-bottom: 2px; }
        .ttd-jabatan { font-size: 9.5pt; font-weight: bold; margin-bottom: 50px; }
        .ttd-nama    { font-size: 10pt; font-weight: bold; border-top: 1.5px solid #111; padding-top: 4px; display: inline-block; min-width: 160px; }

        .page-number { text-align: center; font-size: 8pt; color: #888; margin-top: 12px; }

        @page { size: A4 portrait; margin: 0; }
    </style>
</head>
<body>

{{-- ═══════════════════════════════════════
     KOP SURAT SEKOLAH
════════════════════════════════════════ --}}
@php
    $sekolah = $sekolah ?? \App\Models\Sekolah::first();
    $isPdf   = true;
@endphp
@include('partials.kop-surat')

{{-- ═══════════════════════════════════════
     JUDUL DOKUMEN
════════════════════════════════════════ --}}
<div class="doc-title">Rekap Presensi Siswa Per Semester</div>
<div class="doc-subtitle">
    Semester {{ ucfirst($semester->semester ?? '-') }}
    @if($semester->tahunAjaran) — Tahun Ajaran {{ $semester->tahunAjaran->tahun }} @endif
    &nbsp;|&nbsp;
    {{ \Carbon\Carbon::parse($awal)->translatedFormat('d F Y') }} s.d {{ \Carbon\Carbon::parse($akhir)->translatedFormat('d F Y') }}
</div>

{{-- ═══════════════════════════════════════
     INFO SISWA
════════════════════════════════════════ --}}
<table class="info-table">
    <tr>
        <td>NIS</td><td>:</td>
        <td><strong>{{ $siswa->nis }}</strong></td>
        <td width="30"></td>
        <td width="120">Kelas</td><td width="14">:</td>
        <td>
            @if($kelas)
                {{ $kelas->tingkat }} {{ $kelas->rombel }}
                @if($kelas->jurusan) — {{ $kelas->jurusan->nama_jurusan }} @endif
            @else
                —
            @endif
        </td>
    </tr>
    <tr>
        <td>Nama Siswa</td><td>:</td>
        <td><strong>{{ $siswa->nama_siswa }}</strong></td>
        <td></td>
        <td>Wali Kelas</td><td>:</td>
        <td>{{ $waliKelas ?? '—' }}</td>
    </tr>
</table>

{{-- ═══════════════════════════════════════
     RINGKASAN STATISTIK
════════════════════════════════════════ --}}
<table width="100%" border="1" cellspacing="0" cellpadding="0"
       style="border-collapse:collapse; margin-bottom:14px; border:1.5px solid #000;">
    <thead>
        <tr style="background:#1e3a5f; color:#fff; font-size:8pt; text-align:center; font-family:'Times New Roman';">
            <th style="padding:5px; border:1px solid #1e3a5f;">Hadir</th>
            <th style="padding:5px; border:1px solid #1e3a5f;">Sakit</th>
            <th style="padding:5px; border:1px solid #1e3a5f;">Izin</th>
            <th style="padding:5px; border:1px solid #1e3a5f;">Alfa</th>
            <th style="padding:5px; border:1px solid #1e3a5f;">Total Hari</th>
            <th style="padding:5px; border:1px solid #1e3a5f;">% Kehadiran</th>
        </tr>
    </thead>
    <tbody>
        <tr style="text-align:center; font-size:13pt; font-weight:bold;">
            <td style="padding:8px 4px; background:#d1fae5; color:#065f46; border:1px solid #ccc;">{{ $stats['hadir'] }}</td>
            <td style="padding:8px 4px; background:#fef9c3; color:#92400e; border:1px solid #ccc;">{{ $stats['sakit'] }}</td>
            <td style="padding:8px 4px; background:#dbeafe; color:#1e40af; border:1px solid #ccc;">{{ $stats['izin'] }}</td>
            <td style="padding:8px 4px; background:#fee2e2; color:#7f1d1d; border:1px solid #ccc;">{{ $stats['alfa'] }}</td>
            <td style="padding:8px 4px; background:#f3f4f6; color:#111; border:1px solid #ccc;">{{ $stats['total'] }}</td>
            <td style="padding:8px 4px; background:#ede9fe; color:#4c1d95; border:1px solid #ccc;">{{ $stats['persentase'] }}%</td>
        </tr>
    </tbody>
</table>

{{-- ═══════════════════════════════════════
     TABEL DETAIL PRESENSI
════════════════════════════════════════ --}}
<table class="presensi-table">
    <thead>
        <tr>
            <th style="width:32px;">No</th>
            <th class="left" style="min-width:140px;">Hari, Tanggal</th>
            <th style="width:90px;">Status</th>
            <th style="width:90px;">Jam Finger</th>
            <th class="left">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($presensiList as $index => $p)
        <tr>
            <td class="center">{{ $index + 1 }}</td>
            <td>
                <strong>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('l') }}</strong>,
                {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}
            </td>
            <td class="center">
                @if($p->status_label === 'Hadir')
                    <span class="badge-hadir">Hadir</span>
                @elseif($p->status_label === 'Sakit')
                    <span class="badge-sakit">Sakit</span>
                @elseif($p->status_label === 'Izin')
                    <span class="badge-izin">Izin</span>
                @else
                    <span class="badge-alfa">Alfa</span>
                @endif
            </td>
            <td class="center mono">
                @if($p->jam)
                    {{ \Carbon\Carbon::parse($p->jam)->format('H:i') }}
                @else
                    —
                @endif
            </td>
            <td>{{ $p->keterangan ?: '—' }}</td>
        </tr>
        @empty
        <tr>
            <td colspan="5" style="text-align:center; padding:16px; font-style:italic; color:#888;">
                Tidak ada data presensi pada semester ini.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- ═══════════════════════════════════════
     TANDA TANGAN WALI KELAS
════════════════════════════════════════ --}}
<table width="100%" style="margin-top:30px; font-family:'Times New Roman', Times, serif; font-size:10pt; border:none;">
    <tr>
        <td width="60%"></td>
        <td width="40%" style="text-align:center;">
            <div style="margin-bottom:4px;">
                {{ $sekolah?->kota ?? '____________' }},
                {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
            </div>
            <div style="font-weight:bold; margin-bottom:60px;">Wali Kelas</div>
            <div style="font-weight:bold; border-top:1.5px solid #111; display:inline-block; min-width:180px; padding-top:4px;">
                {{ $waliKelas ?? '______________________' }}
            </div>
        </td>
    </tr>
</table>

<div class="page-number">
    Dicetak oleh SmartSchool — {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
</div>

</body>
</html>
