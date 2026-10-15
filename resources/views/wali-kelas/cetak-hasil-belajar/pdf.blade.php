<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raport — {{ $siswa->nama_siswa }}</title>
    <style>
        /* ── Reset & Base ─────────────────────────────────────── */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10.5pt;
            color: #000;
            background: #fff;
            line-height: 1.4;
        }

        /* ── Page ─────────────────────────────────────────────── */
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 15mm 18mm 18mm;
        }

        /* ── Divider ──────────────────────────────────────────── */
        .divider-double {
            border: none;
            border-top: 3px double #000;
            margin: 6px 0 12px;
        }
        .divider-single {
            border: none;
            border-top: 1px solid #aaa;
            margin: 10px 0;
        }

        /* ── Judul ────────────────────────────────────────────── */
        .judul-raport {
            text-align: center;
            margin: 10px 0 16px;
        }
        .judul-raport h2 {
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .judul-raport p {
            font-size: 9pt;
            color: #444;
            margin-top: 3px;
        }

        /* ── Section Title ────────────────────────────────────── */
        .section-title {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            border-left: 4px solid #1a5276;
            padding-left: 8px;
            margin: 16px 0 10px;
            color: #1a5276;
        }

        /* ── Data Siswa Table ─────────────────────────────────── */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9.5pt;
            margin-bottom: 6px;
        }
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .info-table .lbl {
            width: 140px;
            color: #444;
            font-weight: normal;
        }
        .info-table .sep { width: 12px; color: #666; }
        .info-table .val { font-weight: bold; color: #000; }

        /* ── Presensi Stats ───────────────────────────────────── */
        .prs-stats {
            display: flex; /* DomPDF tidak support flex sepenuhnya, pakai table fallback */
        }
        .prs-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            font-size: 9pt;
        }
        .prs-table td {
            text-align: center;
            padding: 8px 6px;
            border: 1px solid #e0e0e0;
            vertical-align: middle;
        }
        .prs-table .val { font-size: 15pt; font-weight: bold; line-height: 1; display: block; }
        .prs-table .lbl { font-size: 8pt; font-weight: 600; display: block; margin-top: 2px; }
        .prs-h { background: #d5f5e3; color: #1e8449; }
        .prs-i { background: #d6eaf8; color: #1a5276; }
        .prs-s { background: #fef9c3; color: #7d6608; }
        .prs-a { background: #fadbd8; color: #922b21; }
        .prs-t { background: #f2f3f4; color: #2c3e50; }

        /* ── Data Table ───────────────────────────────────────── */
        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
            margin-bottom: 14px;
        }
        .data-table th {
            background: #eaf0fb;
            color: #1a3a5c;
            font-weight: bold;
            font-size: 8pt;
            padding: 6px 8px;
            border: 1px solid #c5cfe3;
            text-align: center;
        }
        .data-table td {
            padding: 5px 8px;
            border: 1px solid #dce3ed;
            color: #000;
            vertical-align: top;
        }
        .data-table tbody tr:nth-child(even) { background: #f7f9fc; }
        .data-table .tc { text-align: center; }

        /* ── BTAQ Stats ───────────────────────────────────────── */
        .btaq-stats-table {
            width: 260px;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 10px;
        }
        .btaq-stats-table td { padding: 6px 10px; border: 1px solid #dce3ed; }
        .btaq-stats-table .lbl { color: #555; }
        .btaq-stats-table .val { font-weight: bold; }

        /* ── UKS Note ─────────────────────────────────────────── */
        .uks-note {
            background: #fff0f0;
            border: 1px solid #f5c6c6;
            border-radius: 4px;
            padding: 6px 10px;
            font-size: 8.5pt;
            color: #7b0000;
            margin-bottom: 8px;
        }

        /* ── TTD ──────────────────────────────────────────────── */
        .ttd-wrap { margin-top: 36px; text-align: right; }
        .ttd-block { display: inline-block; text-align: center; min-width: 200px; font-size: 9.5pt; }
        .ttd-space { height: 68px; }
        .ttd-name  { font-weight: bold; text-decoration: underline; }

        /* ── Badges ───────────────────────────────────────────── */
        .badge {
            display: inline-block;
            border-radius: 3px;
            padding: 1px 7px;
            font-size: 7.5pt;
            font-weight: bold;
        }
        .badge-hadir  { background: #d5f5e3; color: #1e8449; }
        .badge-izin   { background: #d6eaf8; color: #1a5276; }
        .badge-sakit  { background: #fef9c3; color: #7d6608; }
        .badge-alpha  { background: #fadbd8; color: #922b21; }
        .badge-iqro   { background: #d6eaf8; color: #1a5276; }
        .badge-quran  { background: #fef9c3; color: #7d6608; }

        .empty-note { font-style: italic; color: #888; font-size: 9pt; padding: 8px 0; }

        @page { margin: 0; }

        @media print {
            body { font-size: 10pt; }
        }
    </style>
</head>
<body>
<div class="page">

    {{-- KOP SEKOLAH --}}
    @include('partials.kop-surat', ['isPdf' => true])

    {{-- Judul --}}
    <div class="judul-raport">
        <h2>Laporan Hasil Belajar Siswa</h2>
        <p>
            Semester {{ $semester?->semester ?? '—' }}
            — Tahun Ajaran {{ optional($semester?->tahunAjaran)->tahun ?? '—' }}
            @if($semester?->awal && $semester?->akhir)
            &nbsp;|&nbsp; Periode:
            {{ \Carbon\Carbon::parse($semester->awal)->format('d/m/Y') }} s/d
            {{ \Carbon\Carbon::parse($semester->akhir)->format('d/m/Y') }}
            @endif
        </p>
    </div>
    <div class="divider-double"></div>

    {{-- DATA SISWA ─────────────────────────────────────── --}}
    <div class="section-title">I. Data Siswa</div>
    <table class="info-table">
        <tr>
            <td class="lbl">Nama Lengkap</td>
            <td class="sep">:</td>
            <td class="val">{{ $siswa->nama_siswa }}</td>
            <td class="lbl">Kelas</td>
            <td class="sep">:</td>
            <td class="val">{{ optional($siswa->kelas)->nama_kelas ?? '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">NIS / NISN</td>
            <td class="sep">:</td>
            <td class="val">{{ $siswa->nis }} / {{ $siswa->nisn ?? '—' }}</td>
            <td class="lbl">Jurusan</td>
            <td class="sep">:</td>
            <td class="val">{{ optional(optional($siswa->kelas)->jurusan)->nama_jurusan ?? '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">Jenis Kelamin</td>
            <td class="sep">:</td>
            <td>{{ $siswa->jenkel === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td class="lbl">Wali Kelas</td>
            <td class="sep">:</td>
            <td>{{ optional(optional($siswa->kelas)->guru)->nama_guru ?? '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">Tempat, Tgl Lahir</td>
            <td class="sep">:</td>
            <td>
                {{ $siswa->tempat_lahir ?? '—' }},
                {{ $siswa->tgl_lahir ? \Carbon\Carbon::parse($siswa->tgl_lahir)->translatedFormat('d F Y') : '—' }}
            </td>
            <td class="lbl">Alamat</td>
            <td class="sep">:</td>
            <td>{{ optional($siswa->detail)->alamat ?? '—' }}</td>
        </tr>
    </table>
    <div class="divider-single"></div>

    {{-- PRESENSI ────────────────────────────────────────── --}}
    <div class="section-title">II. Data Presensi</div>
    <table class="prs-table">
        <tr>
            <td class="prs-h">
                <span class="val">{{ $presensi['hadir'] }}</span>
                <span class="lbl">Hadir</span>
            </td>
            <td class="prs-i">
                <span class="val">{{ $presensi['izin'] }}</span>
                <span class="lbl">Izin</span>
            </td>
            <td class="prs-s">
                <span class="val">{{ $presensi['sakit'] }}</span>
                <span class="lbl">Sakit</span>
            </td>
            <td class="prs-a">
                <span class="val">{{ $presensi['alpha'] }}</span>
                <span class="lbl">Alpha</span>
            </td>
            <td class="prs-t">
                <span class="val">{{ $presensi['total'] }}</span>
                <span class="lbl">Total</span>
            </td>
        </tr>
    </table>

    @if($presensi['riwayat']->isNotEmpty())
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:28px;">No</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th style="width:70px;">Status</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($presensi['riwayat'] as $idx => $p)
            @php
                $statusMap = ['H'=>['Hadir','badge-hadir'],'I'=>['Izin','badge-izin'],
                              'S'=>['Sakit','badge-sakit'],'A'=>['Alpha','badge-alpha']];
                [$label,$cls] = $statusMap[$p->status] ?? [$p->status,''];
            @endphp
            <tr>
                <td class="tc">{{ $idx + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}</td>
                <td class="tc">{{ $p->jam ?? '—' }}</td>
                <td class="tc"><span class="badge {{ $cls }}">{{ $label }}</span></td>
                <td>{{ $p->keterangan ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p class="empty-note">Tidak ada data presensi pada periode ini.</p>
    @endif
    <div class="divider-single"></div>

    {{-- BTAQ ─────────────────────────────────────────────── --}}
    <div class="section-title">III. Riwayat BTAQ</div>
    <table class="btaq-stats-table">
        <tr>
            <td class="lbl">Total Sesi BTAQ</td>
            <td class="val">{{ $btaq['total'] }} sesi</td>
        </tr>
        <tr>
            <td class="lbl">Level Iqro</td>
            <td class="val">{{ $btaq['level_iqro'] }} sesi</td>
        </tr>
        <tr>
            <td class="lbl">Level Al-Qur'an</td>
            <td class="val">{{ $btaq['level_quran'] }} sesi</td>
        </tr>
        @if($btaq['terakhir'])
        <tr>
            <td class="lbl">Sesi Terakhir</td>
            <td class="val">{{ \Carbon\Carbon::parse($btaq['terakhir']->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        @endif
    </table>

    @if($btaq['riwayat']->isNotEmpty())
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:28px;">No</th>
                <th>Tanggal</th>
                <th style="width:70px;">Level</th>
                <th>Awal</th>
                <th>Akhir</th>
                <th>Guru</th>
            </tr>
        </thead>
        <tbody>
            @foreach($btaq['riwayat'] as $idx => $b)
            <tr>
                <td class="tc">{{ $idx + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tanggal)->translatedFormat('d F Y') }}</td>
                <td class="tc">
                    <span class="badge {{ $b->level==='iqro'?'badge-iqro':'badge-quran' }}" style="text-transform:capitalize;">
                        {{ $b->level }}
                    </span>
                </td>
                <td class="tc">{{ $b->awal ?? '—' }}</td>
                <td class="tc">{{ $b->akhir ?? '—' }}</td>
                <td>{{ optional($b->guru)->nama_guru ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p class="empty-note">Tidak ada data BTAQ pada periode ini.</p>
    @endif
    <div class="divider-single"></div>

    {{-- RIWAYAT KUNJUNGAN UKS ───────────────────────────── --}}
    <div class="section-title">IV. Riwayat Kunjungan UKS</div>

    @if($kunjunganUks['riwayat']->isNotEmpty())
    <div class="uks-note">
        Total kunjungan UKS: <strong>{{ $kunjunganUks['total'] }} kali</strong> selama periode ini.
    </div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:28px;">No</th>
                <th>Tanggal</th>
                <th style="width:55px;">Jam</th>
                <th>Keluhan</th>
                <th>Diagnosa</th>
                <th>Tindakan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kunjunganUks['riwayat'] as $idx => $k)
            <tr>
                <td class="tc">{{ $idx + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d F Y') }}</td>
                <td class="tc">{{ $k->jam ?? '—' }}</td>
                <td>{{ $k->keluhan ?? '—' }}</td>
                <td>{{ $k->diagnosa ?? '—' }}</td>
                <td>{{ $k->tindakan ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p class="empty-note">Tidak ada data kunjungan UKS pada periode ini.</p>
    @endif

    {{-- TANDA TANGAN ────────────────────────────────────── --}}
    <div class="ttd-wrap">
        <div class="ttd-block">
            <div>{{ optional($sekolah)->kota ?? 'Kota' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div>Wali Kelas,</div>
            <div class="ttd-space"></div>
            <div class="ttd-name">
                {{ optional(optional($siswa->kelas)->guru)->nama_guru ?? '( ________________________ )' }}
            </div>
        </div>
    </div>

</div>{{-- /page --}}
</body>
</html>
