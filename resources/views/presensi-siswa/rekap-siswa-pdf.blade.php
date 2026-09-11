<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekap Presensi — {{ $siswa->nama_siswa }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10.5pt;
            color: #111;
            background: #fff;
            padding: 18mm 20mm 20mm 25mm;
        }

        @page { size: A4 portrait; margin: 0; }

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
            margin-bottom: 4px;
        }
        .doc-divider {
            border: none;
            border-bottom: 1px solid #aaa;
            margin: 8px auto 16px;
            width: 70%;
        }

        /* ── Info Siswa ────────────────────────── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
            font-size: 9.5pt;
        }
        .info-table td {
            padding: 4px 6px;
            border: none;
            vertical-align: middle;
        }
        .info-lbl  { width: 110px; font-weight: bold; color: #333; white-space: nowrap; }
        .info-sep  { width: 12px; }
        .info-val  { }
        .info-gap  { width: 28px; }

        /* ── Ringkasan Statistik ─────────────── */
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
            background: #1e3a5f;
            color: #fff;
        }
        .stats-table td {
            padding: 8px 4px;
            text-align: center;
            font-size: 14pt;
            font-weight: bold;
            border: 1px solid #ccc;
        }
        .td-hadir  { background: #d1fae5; color: #065f46; }
        .td-sakit  { background: #fef9c3; color: #92400e; }
        .td-izin   { background: #dbeafe; color: #1e40af; }
        .td-alfa   { background: #fee2e2; color: #7f1d1d; }
        .td-total  { background: #f3f4f6; color: #111; }
        .td-persen { background: #ede9fe; color: #4c1d95; }

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
        .mono   { font-family: 'Courier New', monospace; }

        .badge-hadir  { color: #065f46; background: #d1fae5; padding: 2px 8px; font-weight: 700; font-size: 8.5pt; }
        .badge-sakit  { color: #92400e; background: #fef3c7; padding: 2px 8px; font-weight: 700; font-size: 8.5pt; }
        .badge-izin   { color: #1e40af; background: #dbeafe; padding: 2px 8px; font-weight: 700; font-size: 8.5pt; }
        .badge-alfa   { color: #7f1d1d; background: #fee2e2; padding: 2px 8px; font-weight: 700; font-size: 8.5pt; }

        /* ── Tanda Tangan ────────────────────── */
        .ttd-table { width: 100%; border-collapse: collapse; margin-top: 30px; font-size: 10pt; }
        .ttd-table td { border: none; padding: 0; }
        .ttd-right { width: 210px; text-align: center; }
        .ttd-gap   { height: 54px; }
        .ttd-nama  { font-weight: bold; border-top: 1.5px solid #111; display: inline-block; min-width: 175px; padding-top: 4px; }

        .footer-cetak {
            text-align: center;
            font-size: 7.5pt;
            color: #aaa;
            margin-top: 16px;
            border-top: 1px dashed #ddd;
            padding-top: 6px;
        }
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
     JUDUL
════════════════════════════════════════ --}}
<div class="doc-title">Rekap Presensi Siswa Per Semester</div>
<div class="doc-subtitle">
    Semester {{ ucfirst($semester->semester ?? '-') }}
    @if($semester->tahunAjaran) &mdash; Tahun Ajaran {{ $semester->tahunAjaran->tahun }} @endif
    &nbsp;|&nbsp;
    {{ \Carbon\Carbon::parse($awal)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($akhir)->translatedFormat('d F Y') }}
</div>
<div class="doc-divider"></div>

{{-- ═══════════════════════════════════════
     INFO SISWA
════════════════════════════════════════ --}}
<table class="info-table">
    <tr>
        <td class="info-lbl">NIS</td>
        <td class="info-sep">:</td>
        <td class="info-val"><strong>{{ $siswa->nis }}</strong></td>
        <td class="info-gap"></td>
        <td class="info-lbl">Kelas</td>
        <td class="info-sep">:</td>
        <td class="info-val">
            @if($kelas)
                {{ $kelas->tingkat }} {{ $kelas->rombel }}
                @if($kelas->jurusan) &mdash; {{ $kelas->jurusan->nama_jurusan }} @endif
            @else &mdash; @endif
        </td>
    </tr>
    <tr>
        <td class="info-lbl">Nama Siswa</td>
        <td class="info-sep">:</td>
        <td class="info-val"><strong>{{ $siswa->nama_siswa }}</strong></td>
        <td class="info-gap"></td>
        <td class="info-lbl">Wali Kelas</td>
        <td class="info-sep">:</td>
        <td class="info-val">{{ $waliKelas ?? '&mdash;' }}</td>
    </tr>
    <tr>
        <td class="info-lbl">Semester</td>
        <td class="info-sep">:</td>
        <td class="info-val">
            Semester {{ ucfirst($semester->semester ?? '-') }}
            @if($semester->tahunAjaran) &mdash; TA {{ $semester->tahunAjaran->tahun }} @endif
        </td>
        <td class="info-gap"></td>
        <td class="info-lbl">Periode</td>
        <td class="info-sep">:</td>
        <td class="info-val">
            {{ \Carbon\Carbon::parse($awal)->translatedFormat('d M Y') }}
            &ndash;
            {{ \Carbon\Carbon::parse($akhir)->translatedFormat('d M Y') }}
        </td>
    </tr>
</table>

{{-- ═══════════════════════════════════════
     RINGKASAN STATISTIK
════════════════════════════════════════ --}}
<table class="stats-table">
    <thead>
        <tr>
            <th>Hadir</th>
            <th>Sakit</th>
            <th>Izin</th>
            <th>Alfa</th>
            <th>Total Hari</th>
            <th>% Kehadiran</th>
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

{{-- ═══════════════════════════════════════
     TABEL DETAIL PRESENSI
════════════════════════════════════════ --}}
<table class="presensi-table">
    <thead>
        <tr>
            <th style="width:28px;">No</th>
            <th class="left" style="width:38%;">Hari, Tanggal</th>
            <th style="width:64px;">Status</th>
            <th style="width:66px; white-space:nowrap;">Jam Finger</th>
            <th class="left">Keterangan</th>
        </tr>
    </thead>
    <tbody>
        @forelse($presensiList as $idx => $p)
        @php
            $isHadir    = $p->status_label === 'Hadir';
            $tampilJam  = $p->jam && $isHadir;
            $ketDisplay = $p->keterangan ?: ($tampilJam ? 'Mesin Finger' : null);
            $ketIsMesin = (!$p->keterangan && $tampilJam);
        @endphp
        <tr>
            <td class="center" style="color:#888; font-size:8.5pt;">{{ $idx + 1 }}</td>
            <td>
                {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('l') }},
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
            <td class="center mono" style="white-space:nowrap;">
                @if($tampilJam)
                    {{ \Carbon\Carbon::parse($p->jam)->format('H:i') }}
                @else
                    <span style="color:#bbb;">&mdash;</span>
                @endif
            </td>
            <td style="{{ $ketIsMesin ? 'color:#6b7280; font-style:italic;' : (!$ketDisplay ? 'color:#bbb; font-style:italic;' : '') }}">
                @if($ketDisplay)
                    {{ $ketDisplay }}
                @else
                    &mdash;
                @endif
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

{{-- ═══════════════════════════════════════
     TANDA TANGAN WALI KELAS
════════════════════════════════════════ --}}
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
        <td class="ttd-right" style="padding-top:4px; font-weight:bold;">Wali Kelas</td>
    </tr>
    <tr>
        <td></td>
        <td class="ttd-gap"></td>
    </tr>
    <tr>
        <td></td>
        <td class="ttd-right">
            <span class="ttd-nama">{{ $waliKelas ?? '________________________' }}</span>
        </td>
    </tr>
</table>

<div class="footer-cetak">
    Dicetak melalui SmartSchool &mdash; {{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB
</div>

</body>
</html>
