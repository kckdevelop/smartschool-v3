<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Raport Kelas {{ $kelas->nama_kelas ?? '' }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 0;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 8.5pt;
            color: #000;
            background: #fff;
            line-height: 1.25;
        }
        .page-siswa {
            width: 100%;
            padding: 12mm 16mm 14mm 16mm;
            page-break-after: always;
        }
        .page-siswa:last-child {
            page-break-after: avoid;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }
        tr {
            page-break-inside: avoid;
        }

        .divider-double {
            border: none;
            border-top: 2.5px double #000;
            margin: 4px 0 8px;
        }
        .divider-single {
            border: none;
            border-top: 1px solid #ccc;
            margin: 8px 0;
        }

        .judul-raport {
            text-align: center;
            margin: 6px 0 10px;
        }
        .judul-raport h2 {
            font-size: 11.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .judul-raport p {
            font-size: 8pt;
            color: #444;
            margin-top: 2px;
        }

        .section-title {
            font-size: 8.5pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            border-left: 3px solid #1a5276;
            padding-left: 6px;
            margin: 10px 0 6px;
            color: #1a5276;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            margin-bottom: 4px;
        }
        .info-table td {
            padding: 2px 4px;
            vertical-align: middle;
        }
        .info-table .lbl {
            width: 95px;
            color: #444;
        }
        .info-table .sep {
            width: 8px;
            color: #666;
            text-align: center;
        }
        .info-table .val {
            font-weight: bold;
            color: #000;
        }

        .prs-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 7.5pt;
        }
        .prs-table td {
            text-align: center;
            padding: 4px 3px;
            border: 1px solid #d0d7de;
            vertical-align: middle;
        }
        .prs-table .val {
            font-size: 11pt;
            font-weight: bold;
            line-height: 1;
            display: block;
        }
        .prs-table .lbl {
            font-size: 6.5pt;
            font-weight: 600;
            display: block;
            margin-top: 1px;
        }
        .prs-h { background: #d5f5e3; color: #1e8449; }
        .prs-i { background: #d6eaf8; color: #1a5276; }
        .prs-s { background: #fef9c3; color: #7d6608; }
        .prs-a { background: #fadbd8; color: #922b21; }
        .prs-t { background: #f2f3f4; color: #2c3e50; }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7pt;
            margin-bottom: 8px;
            line-height: 1.15;
        }
        .data-table th {
            background: #eaf0fb;
            color: #1a3a5c;
            font-weight: bold;
            font-size: 6.8pt;
            padding: 3.5px 5px;
            border: 1px solid #c5cfe3;
            text-align: center;
        }
        .data-table td {
            padding: 2.2px 5px;
            border: 1px solid #dce3ed;
            color: #000;
            vertical-align: middle;
        }
        .data-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }
        .data-table .tc {
            text-align: center;
        }

        .btaq-stats-table {
            width: 240px;
            border-collapse: collapse;
            font-size: 7.5pt;
            margin-bottom: 6px;
        }
        .btaq-stats-table td {
            padding: 3px 6px;
            border: 1px solid #dce3ed;
        }
        .btaq-stats-table .lbl { color: #555; }
        .btaq-stats-table .val { font-weight: bold; }

        .uks-note {
            background: #fff0f0;
            border: 1px solid #f5c6c6;
            border-radius: 3px;
            padding: 4px 8px;
            font-size: 7.5pt;
            color: #7b0000;
            margin-bottom: 6px;
        }

        .ttd-wrap {
            margin-top: 18px;
            text-align: right;
        }
        .ttd-block {
            display: inline-block;
            text-align: center;
            min-width: 180px;
            font-size: 8pt;
        }
        .ttd-space {
            height: 48px;
        }
        .ttd-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .badge {
            display: inline-block;
            border-radius: 3px;
            padding: 1px 5px;
            font-size: 6.5pt;
            font-weight: bold;
        }
        .badge-hadir { background: #d5f5e3; color: #1e8449; }
        .badge-izin  { background: #d6eaf8; color: #1a5276; }
        .badge-sakit { background: #fef9c3; color: #7d6608; }
        .badge-alpha { background: #fadbd8; color: #922b21; }
        .badge-muted { background: #f2f3f4; color: #555555; }
        .badge-iqro  { background: #d6eaf8; color: #1a5276; }
        .badge-quran { background: #fef9c3; color: #7d6608; }

        .empty-note {
            font-style: italic;
            color: #888;
            font-size: 7.5pt;
            padding: 4px 0;
        }
    </style>
</head>
<body>

@foreach($dataRaport as $rowIdx => $row)
@php
    $siswa        = $row['siswa'];
    $presensi     = $row['presensi'];
    $btaq         = $row['btaq'];
    $kunjunganUks = $row['kunjunganUks'];
@endphp
<div class="page-siswa">

    {{-- KOP SEKOLAH --}}
    @if(!empty($kopSrc))
        <div class="kop-surat-container kop-uploaded" style="width: 100%; text-align: center; margin-bottom: 6px;">
            <img src="{{ $kopSrc }}" alt="Kop Surat Sekolah" style="width: 100%; max-height: 110px; object-fit: contain; display: block; margin: 0 auto;">
        </div>
    @elseif(!empty($logoSrc))
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 6px; border: none;">
            <tr style="border: none;">
                <td style="width: 80px; text-align: center; vertical-align: middle; padding: 0 15px 10px 0; border: none;">
                    <img src="{{ $logoSrc }}" alt="Logo" style="max-width: 65px; max-height: 65px; object-fit: contain; display: block; margin: 0 auto;">
                </td>
                <td style="text-align: center; vertical-align: middle; padding-bottom: 10px; border: none; padding-right: 80px;">
                    <div style="font-family: 'Times New Roman', Times, serif; font-size: 15pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.2; color: #000; text-align: center;">
                        {{ $sekolah->nama_sekolah ?? 'SMART SCHOOL ACADEMY' }}
                    </div>
                    @if($sekolah && $sekolah->alamat)
                        <div style="font-family: 'Times New Roman', Times, serif; font-size: 8.5pt; margin-top: 3px; color: #333; line-height: 1.25; text-align: center;">
                            {{ $sekolah->alamat }}
                            @if($sekolah->kota || $sekolah->provinsi)
                                , {{ $sekolah->kota }} {{ $sekolah->provinsi }}
                            @endif
                        </div>
                    @endif
                    @if($sekolah && ($sekolah->telepon || $sekolah->email || $sekolah->website))
                        <div style="font-family: 'Times New Roman', Times, serif; font-size: 7.5pt; margin-top: 2px; color: #555; text-align: center;">
                            @if($sekolah->telepon) Telp: {{ $sekolah->telepon }} @endif
                            @if($sekolah->email) | Email: {{ $sekolah->email }} @endif
                            @if($sekolah->website) | Web: {{ $sekolah->website }} @endif
                        </div>
                    @endif
                </td>
            </tr>
        </table>
    @else
        <div style="text-align: center; margin-bottom: 6px;">
            <div style="font-family: 'Times New Roman', Times, serif; font-size: 15pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.2; color: #000;">
                {{ $sekolah->nama_sekolah ?? 'SMART SCHOOL ACADEMY' }}
            </div>
            @if($sekolah && $sekolah->alamat)
                <div style="font-family: 'Times New Roman', Times, serif; font-size: 8.5pt; margin-top: 3px; color: #333;">
                    {{ $sekolah->alamat }}
                </div>
            @endif
        </div>
    @endif

    {{-- Judul --}}
    <div class="judul-raport">
        <h2>Laporan Hasil Belajar Siswa</h2>
        <p>
            Semester {{ $semester?->semester ?? '—' }}
            &mdash; Tahun Ajaran {{ optional($semester?->tahunAjaran)->tahun ?? '—' }}
            @if($semester?->awal && $semester?->akhir)
            &nbsp;|&nbsp; Periode:
            {{ \Carbon\Carbon::parse($semester->awal)->format('d/m/Y') }} s/d
            {{ \Carbon\Carbon::parse($semester->akhir)->format('d/m/Y') }}
            @endif
        </p>
    </div>
    <div class="divider-double"></div>

    {{-- I. DATA SISWA --}}
    <div class="section-title">I. Data Siswa</div>
    <table class="info-table">
        <tr>
            <td class="lbl">Nama Lengkap</td><td class="sep">:</td>
            <td class="val">{{ $siswa->nama_siswa }}</td>
            <td class="lbl">Kelas</td><td class="sep">:</td>
            <td class="val">{{ optional($siswa->kelas)->nama_kelas ?? '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">NIS / NISN</td><td class="sep">:</td>
            <td class="val">{{ $siswa->nis }} / {{ $siswa->nisn ?? '—' }}</td>
            <td class="lbl">Jurusan</td><td class="sep">:</td>
            <td class="val">{{ optional(optional($siswa->kelas)->jurusan)->nama_jurusan ?? '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">Jenis Kelamin</td><td class="sep">:</td>
            <td class="val">{{ $siswa->jenkel === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td class="lbl">Wali Kelas</td><td class="sep">:</td>
            <td class="val">{{ optional(optional($siswa->kelas)->guru)->nama_guru ?? '—' }}</td>
        </tr>
        <tr>
            <td class="lbl">Tempat, Tgl Lahir</td><td class="sep">:</td>
            <td class="val">
                {{ $siswa->tempat_lahir ?? '—' }},
                {{ $siswa->tgl_lahir ? \Carbon\Carbon::parse($siswa->tgl_lahir)->translatedFormat('d F Y') : '—' }}
            </td>
            <td class="lbl">Alamat</td><td class="sep">:</td>
            <td class="val">{{ optional($siswa->detail)->alamat ?? '—' }}</td>
        </tr>
    </table>
    <div class="divider-single"></div>

    {{-- II. PRESENSI --}}
    <div class="section-title">II. Data Presensi</div>
    <table class="prs-table">
        <tr>
            <td class="prs-h"><span class="val">{{ $presensi['hadir'] }}</span><span class="lbl">Hadir</span></td>
            <td class="prs-i"><span class="val">{{ $presensi['izin'] }}</span><span class="lbl">Izin</span></td>
            <td class="prs-s"><span class="val">{{ $presensi['sakit'] }}</span><span class="lbl">Sakit</span></td>
            <td class="prs-a"><span class="val">{{ $presensi['alpha'] }}</span><span class="lbl">Alpha</span></td>
            <td class="prs-t"><span class="val">{{ $presensi['total'] }}</span><span class="lbl">Total</span></td>
        </tr>
    </table>
    @if($presensi['riwayat']->isNotEmpty())
    <table class="data-table">
        <thead><tr>
            <th style="width:28px;">No</th><th>Tanggal</th>
            <th>Jam</th><th style="width:70px;">Status</th><th>Keterangan</th>
        </tr></thead>
        <tbody>
            @foreach($presensi['riwayat'] as $idx => $p)
            @php
                $st = strval($p->status);
                if (in_array($st, ['1','Hadir','hadir','H','h'], true))
                    { $label='Hadir'; $cls='badge-hadir'; $jam=$p->jam ?? '—'; }
                elseif (in_array($st, ['2','Sakit','sakit','S','s'], true))
                    { $label='Sakit'; $cls='badge-sakit'; $jam='—'; }
                elseif (in_array($st, ['3','Izin','izin','I','i'], true))
                    { $label='Izin';  $cls='badge-izin';  $jam='—'; }
                elseif (in_array($st, ['4','Alfa','alfa','Alpha','alpha','A','a'], true))
                    { $label='Alpha'; $cls='badge-alpha'; $jam='—'; }
                else { $label=$p->status ?: '—'; $cls='badge-muted'; $jam='—'; }
            @endphp
            <tr>
                <td class="tc">{{ $idx+1 }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}</td>
                <td class="tc">{{ $jam }}</td>
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

    {{-- III. BTAQ --}}
    <div class="section-title">III. Riwayat BTAQ</div>
    <table class="btaq-stats-table">
        <tr><td class="lbl">Total Sesi BTAQ</td><td class="val">{{ $btaq['total'] }} sesi</td></tr>
        <tr><td class="lbl">Level Iqro</td><td class="val">{{ $btaq['level_iqro'] }} sesi</td></tr>
        <tr><td class="lbl">Level Al-Qur'an</td><td class="val">{{ $btaq['level_quran'] }} sesi</td></tr>
        @if($btaq['terakhir'])
        <tr>
            <td class="lbl">Sesi Terakhir</td>
            <td class="val">{{ \Carbon\Carbon::parse($btaq['terakhir']->tanggal)->translatedFormat('d F Y') }}</td>
        </tr>
        @endif
    </table>
    @if($btaq['riwayat']->isNotEmpty())
    <table class="data-table">
        <thead><tr>
            <th style="width:28px;">No</th><th>Tanggal</th>
            <th style="width:70px;">Level</th><th>Awal</th><th>Akhir</th><th>Guru</th>
        </tr></thead>
        <tbody>
            @foreach($btaq['riwayat'] as $idx => $b)
            @php $isIqro = (stripos($b->level ?? '', 'iqr') !== false); @endphp
            <tr>
                <td class="tc">{{ $idx+1 }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tanggal)->translatedFormat('d F Y') }}</td>
                <td class="tc">
                    <span class="badge {{ $isIqro ? 'badge-iqro' : 'badge-quran' }}" style="text-transform:capitalize;">
                        {{ $b->level }}
                    </span>
                </td>
                <td class="tc">{{ $b->awal_display ?? $b->awal_formatted }}</td>
                <td class="tc">{{ $b->akhir_display ?? $b->akhir_formatted }}</td>
                <td>{{ optional($b->guru)->nama_guru ?? '—' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
        <p class="empty-note">Tidak ada data BTAQ pada periode ini.</p>
    @endif
    <div class="divider-single"></div>

    {{-- IV. UKS --}}
    <div class="section-title">IV. Riwayat Kunjungan UKS</div>
    @if($kunjunganUks['riwayat']->isNotEmpty())
    <div class="uks-note">
        Total kunjungan UKS: <strong>{{ $kunjunganUks['total'] }} kali</strong> selama periode ini.
    </div>
    <table class="data-table">
        <thead><tr>
            <th style="width:28px;">No</th><th>Tanggal</th>
            <th style="width:55px;">Jam</th><th>Keluhan</th><th>Diagnosa</th><th>Tindakan</th>
        </tr></thead>
        <tbody>
            @foreach($kunjunganUks['riwayat'] as $idx => $k)
            <tr>
                <td class="tc">{{ $idx+1 }}</td>
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

    {{-- TANDA TANGAN --}}
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

</div>{{-- /page-siswa --}}
@endforeach

</body>
</html>