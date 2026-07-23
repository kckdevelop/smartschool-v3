<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Jurnal Mengajar</title>
    <style>
        /* ── A4 Page Setup ─────────────────────────────────────────────────── */
        @page { size: A4 portrait; margin: 0; }

        /* ── Reset ────────────────────────────────────────────────────────── */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: Arial, sans-serif;
            font-size: 10.5px;
            color: #1a1a1a;
            line-height: 1.45;
            background: #fff;
            /* Padding dalam dokumen sesuai format A4 resmi */
            padding: 1.8cm 2.2cm 2.2cm 2.5cm;
        }
        .page-break { page-break-before: always; }

        /* ── KOP SURAT ────────────────────────────────────────────────────── */
        .kop-image { width: 100%; display: block; margin-bottom: 2px; }
        .kop-fallback {
            width: 100%;
            border-collapse: collapse;
            border-bottom: 3px double #000;
            margin-bottom: 6px;
        }
        .kop-fallback td { padding: 3px 5px; vertical-align: middle; }
        .kop-logo  { width: 72px; text-align: center; }
        .kop-nama  { font-size: 17px; font-weight: bold; text-transform: uppercase; text-align: center; }
        .kop-npsn  { font-size: 10px; font-weight: bold; text-align: center; }
        .kop-sub   { font-size: 9.5px; text-align: center; color: #444; }
        .kop-line  { border-bottom: 3px double #000; margin-bottom: 0; }

        /* ── JUDUL HALAMAN ────────────────────────────────────────────────── */
        .page-title {
            text-align: center;
            font-size: 15px;
            font-weight: bold;
            letter-spacing: 1px;
            color: #1a6b52;
            text-transform: uppercase;
            margin: 14px 0 8px;
        }
        .page-subtitle {
            text-align: center;
            font-size: 10px;
            color: #444;
            margin-bottom: 14px;
        }

        /* ── BOX IDENTITAS (Hal. 1) ───────────────────────────────────────── */
        .identitas-box {
            border: 1px solid #ccc;
            padding: 8px 12px;
            margin-bottom: 14px;
        }
        .identitas-table { width: 100%; border-collapse: collapse; }
        .identitas-table td { padding: 2px 5px; font-size: 10.5px; }
        .identitas-table .lbl { width: 110px; color: #555; }
        .identitas-table .val { font-weight: bold; }

        /* ── SECTION HEADER ───────────────────────────────────────────────── */
        .section-header {
            display: flex;
            align-items: center;
            font-size: 10.5px;
            font-weight: bold;
            text-transform: uppercase;
            color: #1a1a1a;
            letter-spacing: 0.3px;
            margin: 14px 0 8px;
            padding-left: 2px;
        }
        .section-bar {
            width: 4px;
            height: 16px;
            background: #1a6b52;
            display: inline-block;
            margin-right: 8px;
            vertical-align: middle;
        }

        /* ── RINGKASAN (Hal. 1) ───────────────────────────────────────────── */
        .ringkasan-box {
            border: 1px solid #ddd;
            padding: 12px 14px;
            font-size: 10.5px;
            line-height: 1.7;
            margin-bottom: 4px;
        }
        .ringkasan-stats {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .ringkasan-stats td {
            padding: 4px 6px;
            font-size: 10.5px;
            border-top: 1px solid #eee;
        }
        .ringkasan-stats .stat-label { color: #555; }
        .ringkasan-stats .stat-value {
            font-weight: bold;
            color: #1a6b52;
            text-align: right;
        }

        /* ── LEMBAR PENGESAHAN (Hal. 1) ───────────────────────────────────── */
        .ttd-table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        .ttd-table td {
            width: 50%;
            font-size: 10.5px;
            text-align: center;
            vertical-align: top;
            padding: 0 10px;
        }
        .ttd-space { height: 55px; }
        .ttd-name {
            font-size: 12px;
            font-weight: bold;
            display: block;
            border-top: 1px solid #333;
            padding-top: 3px;
        }
        .ttd-nip { font-size: 10px; color: #555; }
        .page-footer {
            text-align: center;
            font-size: 9.5px;
            color: #888;
            margin-top: 30px;
            padding-top: 8px;
            border-top: 1px solid #eee;
        }

        /* ── TABEL REKAP KEHADIRAN (Hal. 2) ──────────────────────────────── */
        .kehadiran-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        .kehadiran-table thead tr th {
            background: #1a6b52;
            color: #fff;
            border: 1px solid #1a6b52;
            padding: 5px 3px;
            text-align: center;
            font-size: 9px;
        }
        .kehadiran-table thead tr.sub-header th {
            background: #e8f5f0;
            color: #1a1a1a;
            font-size: 8.5px;
            padding: 3px 2px;
        }
        .kehadiran-table tbody tr td {
            border: 1px solid #ccc;
            padding: 3px 4px;
            text-align: center;
            vertical-align: middle;
        }
        .kehadiran-table tbody tr td.nama-col {
            text-align: left;
            font-size: 8.5px;
            padding: 3px 5px;
        }
        .kehadiran-table tbody tr:nth-child(even) { background: #f9f9f9; }
        /* Status warna */
        .status-H { color: #1a6b52; font-weight: bold; }
        .status-S { color: #d97706; font-weight: bold; }
        .status-I { color: #2563eb; font-weight: bold; }
        .status-A { color: #dc2626; font-weight: bold; }
        .status-pct { font-weight: bold; color: #1a1a1a; font-size: 9px; }

        /* ── DETAIL PERTEMUAN (Hal. 3) ────────────────────────────────────── */
        .pertemuan-block {
            margin-bottom: 10px;
        }
        .pertemuan-header {
            width: 100%;
            border-collapse: collapse;
        }
        .pertemuan-header td {
            padding: 4px 8px;
            font-size: 10px;
            vertical-align: top;
        }
        .pertemuan-no {
            font-size: 11px;
            font-weight: bold;
            color: #1a1a1a;
            width: 80px;
        }
        .pertemuan-info-label {
            font-size: 8.5px;
            color: #888;
            display: block;
        }
        .pertemuan-info-val {
            font-size: 10px;
            font-weight: bold;
            display: block;
        }
        .pertemuan-hadir-val {
            font-size: 11px;
            font-weight: bold;
            color: #1a6b52;
        }
        .pertemuan-absen-tag {
            display: inline-block;
            font-size: 9px;
            font-weight: bold;
            padding: 0 2px;
        }
        .pertemuan-materi {
            border-left: 3px solid #1a6b52;
            padding: 5px 10px;
            font-size: 10px;
            background: #f8fdf9;
            margin-top: 2px;
        }
    </style>
</head>
<body>

{{-- ╔══════════════════════════════════════════════════════════════════════╗ --}}
{{-- ║  HALAMAN 1 — JURNAL PELAKSANAAN PEMBELAJARAN + LEMBAR PENGESAHAN   ║ --}}
{{-- ╚══════════════════════════════════════════════════════════════════════╝ --}}

{{-- KOP SURAT --}}
@if(!empty($sekolah->kop))
    <img class="kop-image" src="{{ public_path('storage/' . $sekolah->kop) }}" alt="Kop Surat">
    <div class="kop-line"></div>
@else
    <table class="kop-fallback">
        <tr>
            @if(!empty($sekolah->logo))
                <td class="kop-logo">
                    <img src="{{ public_path('storage/' . $sekolah->logo) }}" style="max-height:68px;max-width:68px;">
                </td>
            @endif
            <td>
                <div class="kop-nama">{{ $sekolah->nama_sekolah ?? 'NAMA SEKOLAH' }}</div>
                <div class="kop-npsn">NPSN: {{ $sekolah->npsn ?? '-' }}{{ !empty($sekolah->ijin) ? ' | Ijin: '.$sekolah->ijin : '' }}</div>
                <div class="kop-sub">{{ $sekolah->alamat_sekolah ?? '' }} {{ $sekolah->kota ?? '' }}</div>
            </td>
        </tr>
    </table>
@endif

{{-- JUDUL --}}
<div class="page-title">Jurnal Pelaksanaan Pembelajaran</div>

{{-- IDENTITAS --}}
<div class="identitas-box">
    <table class="identitas-table">
        <tr>
            <td class="lbl">Guru Pengampu</td>
            <td style="width:6px;">:</td>
            <td class="val">{{ $guru->nama_guru ?? '-' }}</td>
            <td class="lbl" style="padding-left:20px;">Kelas/Semester</td>
            <td style="width:6px;">:</td>
            <td class="val">{{ $kelas->nama_kelas ?? '-' }} / {{ $semester->semester ?? '-' }}</td>
        </tr>
        <tr>
            <td class="lbl">Mata Pelajaran</td>
            <td>:</td>
            <td class="val">{{ $mapel->nama_mapel ?? '-' }}</td>
            <td class="lbl" style="padding-left:20px;">Bulan dan Tahun</td>
            <td>:</td>
            <td class="val">{{ $bulan_tahun }}</td>
        </tr>
    </table>
</div>

{{-- RINGKASAN PELAKSANAAN --}}
<div class="section-header">
    <span class="section-bar"></span>
    Ringkasan Pelaksanaan Pembelajaran
</div>
<div class="ringkasan-box">
    <p style="text-align:justify;">
        Demikian jurnal pelaksanaan pembelajaran mata pelajaran
        <strong>{{ $mapel->nama_mapel ?? '-' }}</strong>
        disusun dengan sebenar-benarnya untuk melaporkan kegiatan pembelajaran yang telah dilaksanakan
        selama periode bulan <strong>{{ $bulan_tahun }}</strong>.
        Kegiatan pembelajaran dilaksanakan sesuai dengan kalender pendidikan dan program semester yang telah ditetapkan.
    </p>
    <table class="ringkasan-stats">
        <tr>
            <td class="stat-label" style="width: 25%;">Periode Pelaporan</td>
            <td class="stat-value" style="color:#1a6b52; font-weight:bold; text-align:right; width: 30%;">
                {{ $statistik['periode_awal'] }} – {{ $statistik['periode_akhir'] }}
            </td>
            <td style="width: 5%;"></td>
            <td class="stat-label" style="width: 25%;">Jumlah Peserta Didik</td>
            <td class="stat-value" style="width: 15%;">{{ $statistik['jumlah_siswa'] }} Siswa</td>
        </tr>
        <tr>
            <td class="stat-label">Rata-rata Kehadiran</td>
            <td class="stat-value">{{ $statistik['avg_kehadiran'] }}%</td>
            <td></td>
            <td class="stat-label">Jumlah Pertemuan</td>
            <td class="stat-value">{{ $statistik['total_pertemuan'] }} Pertemuan</td>
        </tr>
    </table>
</div>

{{-- LEMBAR PENGESAHAN --}}
<div class="section-header" style="margin-top:18px;">
    <span class="section-bar"></span>
    Lembar Pengesahan
</div>
<p style="font-size:10.5px;">
    Menyatakan bahwa jurnal pelaksanaan pembelajaran ini telah diperiksa dan disahkan kebenarannya.
</p>

<table class="ttd-table">
    <tr>
        <td>
            Mengetahui,<br>
            <strong>Wali Amanah / Wali Kelas</strong>
            <div class="ttd-space"></div>
            <span class="ttd-name">{{ $kelas->guru->nama_guru ?? '...........................' }}</span>
            <span class="ttd-nip">{{ $kelas->guru->no_id ?? '-' }}</span>
        </td>
        <td>
            {{ $sekolah->kota ?? '...........' }}, {{ $tanggal_cetak }}<br>
            <strong>Guru Mata Pelajaran</strong>
            <div class="ttd-space"></div>
            <span class="ttd-name">{{ $guru->nama_guru ?? '-' }}</span>
            <span class="ttd-nip">{{ $guru->no_id ?? '-' }}</span>
        </td>
    </tr>
</table>

<div class="page-footer">Halaman I – Lembar Pengesahan</div>


{{-- ╔══════════════════════════════════════════════════════════════════════╗ --}}
{{-- ║  HALAMAN 2 — REKAP KEHADIRAN SISWA BULANAN                         ║ --}}
{{-- ╚══════════════════════════════════════════════════════════════════════╝ --}}
<div class="page-break">

    <div class="page-title">Rekap Kehadiran Siswa Bulanan</div>
    <div class="page-subtitle">
        {{ $kelas->nama_kelas ?? '-' }} | {{ $mapel->nama_mapel ?? '-' }} | {{ $bulan_tahun }}
    </div>

    <div class="section-header">
        <span class="section-bar"></span>
        A. Data Kehadiran Siswa ({{ $statistik['jumlah_siswa'] }} Siswa)
    </div>

    <table class="kehadiran-table">
        <thead>
            <tr>
                <th style="width:24px;">No</th>
                <th style="text-align:left; padding-left:6px; min-width:90px;">Nama Siswa</th>
                @foreach($jurnals as $idx => $j)
                    <th style="min-width:30px;">
                        P{{ $idx + 1 }}
                        <br>
                        <span style="font-size:7px; font-weight:normal; display:block;">
                            {{ $j->tanggal instanceof \Carbon\Carbon
                                ? $j->tanggal->format('Y-m')
                                : \Carbon\Carbon::parse($j->tanggal)->format('Y-m') }}
                            <br>
                            {{ $j->tanggal instanceof \Carbon\Carbon
                                ? $j->tanggal->format('d')
                                : \Carbon\Carbon::parse($j->tanggal)->format('d') }}
                        </span>
                    </th>
                @endforeach
                <th style="width:26px;">%</th>
            </tr>
        </thead>
        <tbody>
            @foreach($matrix_kehadiran as $no => $siswa)
                <tr>
                    <td style="text-align:center; font-size:9px;">{{ $no + 1 }}</td>
                    <td class="nama-col">{{ $siswa['nama'] }}</td>
                    @foreach($siswa['kehadiran'] as $st)
                        <td class="status-{{ $st }}">{{ $st }}</td>
                    @endforeach
                    <td class="status-pct">{{ $siswa['pct'] }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Legenda --}}
    <div style="margin-top:8px; font-size:9px; color:#555;">
        Keterangan:
        <span class="status-H" style="margin-left:8px;">H</span> = Hadir &nbsp;|&nbsp;
        <span class="status-S">S</span> = Sakit &nbsp;|&nbsp;
        <span class="status-I">I</span> = Ijin &nbsp;|&nbsp;
        <span class="status-A">A</span> = Alpa/Alpha
    </div>

    <div class="page-footer">Halaman II – Rekap Kehadiran Siswa</div>
</div>


{{-- ╔══════════════════════════════════════════════════════════════════════╗ --}}
{{-- ║  HALAMAN 3 — DETAIL PELAKSANAAN PER PERTEMUAN                      ║ --}}
{{-- ╚══════════════════════════════════════════════════════════════════════╝ --}}
<div class="page-break">

    <div class="page-title">Detail Pelaksanaan Per Pertemuan</div>
    <div class="page-subtitle">
        {{ $kelas->nama_kelas ?? '-' }} | {{ $mapel->nama_mapel ?? '-' }} | {{ $guru->nama_guru ?? '-' }}
    </div>

    @foreach($jurnals as $idx => $jurnal)
        @php
            $tgl       = $jurnal->tanggal instanceof \Carbon\Carbon
                            ? $jurnal->tanggal
                            : \Carbon\Carbon::parse($jurnal->tanggal);
            $hariIndo  = $tgl->translatedFormat('l');
            $tglFormat = $tgl->translatedFormat('d F Y');
            $p         = $jurnal->presensi ?? ['H'=>0,'S'=>0,'I'=>0,'A'=>0];
            $totalSiswa = $jurnal->jml_siswa ?? 0;
        @endphp
        <div class="pertemuan-block">
            <table class="pertemuan-header" style="border-bottom: 1px solid #eee;">
                <tr>
                    <td class="pertemuan-no" style="width:68px; vertical-align:middle;">
                        Pertemuan<br>
                        <span style="font-size:14px; font-weight:bold; color:#1a6b52;">#{{ $idx + 1 }}</span>
                    </td>
                    <td style="width:120px;">
                        <span class="pertemuan-info-label">Tanggal</span>
                        <span class="pertemuan-info-val">{{ $hariIndo }}</span>
                        <span class="pertemuan-info-val" style="font-weight:normal;">{{ $tglFormat }}</span>
                    </td>
                    <td style="width:80px;">
                        <span class="pertemuan-info-label">Jam Ke</span>
                        <span class="pertemuan-info-val">{{ $jurnal->jam_ke ?? '-' }}</span>
                    </td>
                    <td style="width:90px;">
                        <span class="pertemuan-info-label">Hadir</span>
                        <span class="pertemuan-hadir-val">{{ $p['H'] }}/{{ $totalSiswa }}</span>
                    </td>
                    <td>
                        <span class="pertemuan-info-label">Absen</span>
                        <span style="font-size:10px;">
                            <span class="pertemuan-absen-tag status-S">{{ $p['S'] }}S</span>
                            <span class="pertemuan-absen-tag status-I">{{ $p['I'] }}I</span>
                            <span class="pertemuan-absen-tag status-A">{{ $p['A'] }}A</span>
                        </span>
                    </td>
                </tr>
            </table>
            <div class="pertemuan-materi">{{ $jurnal->materi ?? '—' }}</div>
        </div>
    @endforeach

    <div class="page-footer">Halaman III – Detail Pelaksanaan Per Pertemuan</div>
</div>

</body>
</html>
