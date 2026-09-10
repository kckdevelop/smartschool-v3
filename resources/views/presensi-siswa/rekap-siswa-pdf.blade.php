<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi — {{ $siswa->nama_siswa }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: #111;
            background: #fff;
            /* padding sebagai fallback untuk DomPDF */
            padding: 20mm 22mm 24mm 25mm;
        }

        /* ─── Halaman & margin ─── */
        /* DomPDF menggunakan @page untuk margin kertas fisik */
        @page { size: A4 portrait; margin: 20mm 22mm 24mm 25mm; }

        /* ─── Kop surat (override partial agar rapi di DomPDF) ─── */
        .kop-surat-table { width: 100%; border-collapse: collapse; }
        .kop-surat-table td { border: none; }
        .kop-divider { border: none; border-top: 3px double #000; margin: 6px 0 12px; }

        /* ─── Garis bawah judul ─── */
        .title-wrap { text-align: center; margin-bottom: 12px; }
        .title-main {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 3px;
        }
        .title-sub {
            font-size: 9pt;
            color: #555;
        }
        .title-divider {
            border: none;
            border-bottom: 1.5px solid #888;
            margin: 8px auto 14px;
            width: 80%;
        }

        /* ─── Info Siswa (box bingkai) ─── */
        .info-box {
            border: 1.5px solid #999;
            border-radius: 0;
            margin-bottom: 12px;
            padding: 0;
        }
        .info-box-header {
            background: #1e3a5f;
            color: #fff;
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 5px 10px;
        }
        .info-grid {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
        }
        .info-grid td {
            padding: 5px 10px;
            vertical-align: middle;
            border: none;
        }
        .info-lbl {
            width: 100px;
            font-weight: bold;
            color: #333;
            white-space: nowrap;
        }
        .info-sep { width: 10px; }
        .info-val { }
        .info-spacer { width: 24px; }
        .info-divider-row td { padding: 0; }
        .info-hr { border: none; border-top: 1px solid #e5e7eb; margin: 0; }

        /* ─── Ringkasan Statistik ─── */
        .stats-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 9pt;
        }
        .stats-table th {
            padding: 6px 4px;
            text-align: center;
            font-weight: bold;
            border: 1px solid #1e3a5f;
            font-size: 8.5pt;
            letter-spacing: 0.3px;
        }
        .stats-table td {
            padding: 9px 4px;
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            border: 1px solid #ccc;
        }
        .th-hadir  { background: #1e3a5f; color: #fff; }
        .th-sakit  { background: #1e3a5f; color: #fff; }
        .th-izin   { background: #1e3a5f; color: #fff; }
        .th-alfa   { background: #1e3a5f; color: #fff; }
        .th-total  { background: #1e3a5f; color: #fff; }
        .th-persen { background: #1e3a5f; color: #fff; }
        .td-hadir  { background: #d1fae5; color: #065f46; }
        .td-sakit  { background: #fef9c3; color: #92400e; }
        .td-izin   { background: #dbeafe; color: #1e40af; }
        .td-alfa   { background: #fee2e2; color: #7f1d1d; }
        .td-total  { background: #f3f4f6; color: #111; }
        .td-persen { background: #ede9fe; color: #4c1d95; }

        /* ─── Tabel Presensi ─── */
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
            padding: 7px 8px;
            font-weight: bold;
            border: 1px solid #1e3a5f;
            font-size: 9pt;
            text-align: center;
        }
        .presensi-table th.left { text-align: left; }
        .presensi-table td {
            padding: 5px 8px;
            border: 1px solid #d1d5db;
            vertical-align: middle;
        }
        .presensi-table tbody tr:nth-child(even) { background: #f8fafc; }
        .center { text-align: center; }

        /* Badge status (DomPDF friendly — no border-radius needed) */
        .badge {
            display: inline-block;
            padding: 2px 10px;
            font-weight: 700;
            font-size: 8.5pt;
        }
        .badge-hadir { color: #065f46; background: #d1fae5; }
        .badge-sakit { color: #92400e; background: #fef3c7; }
        .badge-izin  { color: #1e40af; background: #dbeafe; }
        .badge-alfa  { color: #7f1d1d; background: #fee2e2; }

        .jam-text {
            font-family: 'Courier New', monospace;
            font-size: 9.5pt;
        }

        /* ─── Tanda Tangan ─── */
        .ttd-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 28px;
            font-size: 10pt;
        }
        .ttd-table td { border: none; padding: 0; }
        .ttd-right { width: 200px; text-align: center; }
        .ttd-gap { height: 52px; }
        .ttd-line { border-top: 1.5px solid #111; display: block; padding-top: 4px; }

        /* ─── Footer cetak ─── */
        .footer-cetak {
            text-align: center;
            font-size: 7.5pt;
            color: #aaa;
            margin-top: 14px;
            border-top: 1px dashed #ddd;
            padding-top: 6px;
        }
    </style>
</head>
<body>

{{-- ══════════════════════════════════════
     KOP SURAT SEKOLAH
══════════════════════════════════════ --}}
@php
    $sekolah = $sekolah ?? \App\Models\Sekolah::first();
    $isPdf   = true;
@endphp
@include('partials.kop-surat')

{{-- ══════════════════════════════════════
     JUDUL
══════════════════════════════════════ --}}
<div class="title-wrap">
    <span class="title-main">Rekap Presensi Siswa Per Semester</span>
    <span class="title-sub">
        Semester {{ ucfirst($semester->semester ?? '-') }}
        @if($semester->tahunAjaran) &mdash; Tahun Ajaran {{ $semester->tahunAjaran->tahun }} @endif
        &nbsp;|&nbsp;
        {{ \Carbon\Carbon::parse($awal)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($akhir)->translatedFormat('d F Y') }}
    </span>
    <div class="title-divider"></div>
</div>

{{-- ══════════════════════════════════════
     INFO SISWA
══════════════════════════════════════ --}}
<div class="info-box">
    <div class="info-box-header">&#128100; Data Siswa</div>
    <table class="info-grid">
        <tr>
            <td class="info-lbl">NIS</td>
            <td class="info-sep">:</td>
            <td class="info-val"><strong>{{ $siswa->nis }}</strong></td>
            <td class="info-spacer"></td>
            <td class="info-lbl">Kelas</td>
            <td class="info-sep">:</td>
            <td class="info-val">
                @if($kelas)
                    {{ $kelas->tingkat }} {{ $kelas->rombel }}
                    @if($kelas->jurusan)&nbsp;&mdash;&nbsp;{{ $kelas->jurusan->nama_jurusan }}@endif
                @else &mdash; @endif
            </td>
        </tr>
        <tr class="info-divider-row">
            <td colspan="7" style="padding:0 10px;"><div class="info-hr"></div></td>
        </tr>
        <tr>
            <td class="info-lbl">Nama Siswa</td>
            <td class="info-sep">:</td>
            <td class="info-val"><strong>{{ $siswa->nama_siswa }}</strong></td>
            <td class="info-spacer"></td>
            <td class="info-lbl">Wali Kelas</td>
            <td class="info-sep">:</td>
            <td class="info-val">{{ $waliKelas ?? '&mdash;' }}</td>
        </tr>
        <tr class="info-divider-row">
            <td colspan="7" style="padding:0 10px;"><div class="info-hr"></div></td>
        </tr>
        <tr>
            <td class="info-lbl">Semester</td>
            <td class="info-sep">:</td>
            <td class="info-val">
                Semester {{ ucfirst($semester->semester ?? '-') }}
                @if($semester->tahunAjaran) &mdash; TA {{ $semester->tahunAjaran->tahun }} @endif
            </td>
            <td class="info-spacer"></td>
            <td class="info-lbl">Periode</td>
            <td class="info-sep">:</td>
            <td class="info-val">
                {{ \Carbon\Carbon::parse($awal)->translatedFormat('d M Y') }}
                &ndash;
                {{ \Carbon\Carbon::parse($akhir)->translatedFormat('d M Y') }}
            </td>
        </tr>
    </table>
</div>

{{-- ══════════════════════════════════════
     RINGKASAN STATISTIK
══════════════════════════════════════ --}}
<table class="stats-table">
    <thead>
        <tr>
            <th class="th-hadir">Hadir</th>
            <th class="th-sakit">Sakit</th>
            <th class="th-izin">Izin</th>
            <th class="th-alfa">Alfa</th>
            <th class="th-total">Total Hari</th>
            <th class="th-persen">% Kehadiran</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="td-hadir">{{ $stats['hadir'] }}</td>
            <td class="td-sakit">{{ $stats['sakit'] }}</td>
            <td class="td-izin">{{ $stats['izin'] }}</td>
            <td class="td-alfa">{{ $stats['alfa'] }}</td>
            <td class="td-total">{{ $stats['total'] }}</td>
            <td class="td-persen">{{ $stats['persentase'] }}%</td>
        </tr>
    </tbody>
</table>

{{-- ══════════════════════════════════════
     TABEL DETAIL PRESENSI
══════════════════════════════════════ --}}
<table class="presensi-table">
    <thead>
        <tr>
            <th style="width:36px;">No</th>
            <th class="left" style="width:52%;">Hari, Tanggal</th>
            <th style="width:80px;">Status</th>
            <th style="width:76px;">Jam Finger</th>
            <th class="left">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($presensiList as $idx => $p)
        <tr>
            <td class="center" style="color:#555; font-size:8.5pt;">{{ $idx + 1 }}</td>
            <td>
                {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('l') }},
                {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}
            </td>
            <td class="center">
                @if($p->status_label === 'Hadir')
                    <span class="badge badge-hadir">Hadir</span>
                @elseif($p->status_label === 'Sakit')
                    <span class="badge badge-sakit">Sakit</span>
                @elseif($p->status_label === 'Izin')
                    <span class="badge badge-izin">Izin</span>
                @else
                    <span class="badge badge-alfa">Alfa</span>
                @endif
            </td>
            <td class="center">
                @if($p->jam)
                    <span class="jam-text">{{ \Carbon\Carbon::parse($p->jam)->format('H:i') }}</span>
                @else
                    <span style="color:#aaa;">&mdash;</span>
                @endif
            </td>
            <td style="color: {{ $p->keterangan ? '#111' : '#aaa' }}; font-style: {{ $p->keterangan ? 'normal' : 'italic' }};">
                {{ $p->keterangan ?: '&mdash;' }}
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="center" style="padding:18px; font-style:italic; color:#999;">
                Tidak ada data presensi pada semester ini.
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- ══════════════════════════════════════
     TANDA TANGAN WALI KELAS
══════════════════════════════════════ --}}
<table class="ttd-table">
    <tr>
        <td></td>
        <td class="ttd-right">
            {{ $sekolah?->kota ?? '____________' }},
            {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
        </td>
    </tr>
    <tr>
        <td></td>
        <td class="ttd-right" style="padding-top:3px; font-weight:bold;">Wali Kelas</td>
    </tr>
    <tr>
        <td></td>
        <td class="ttd-gap"></td>
    </tr>
    <tr>
        <td></td>
        <td class="ttd-right">
            <span class="ttd-line">{{ $waliKelas ?? '________________________' }}</span>
        </td>
    </tr>
</table>

<div class="footer-cetak">
    Dicetak melalui SmartSchool &mdash; {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
</div>

</body>
</html>
