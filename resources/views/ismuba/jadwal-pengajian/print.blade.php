<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Jadwal Pengajian — {{ $periodeLabel }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12px;
            color: #1e293b;
            background: #fff;
        }
        .page {
            max-width: 800px;
            margin: 0 auto;
            padding: 30px 24px;
        }
        /* Header */
        .report-header {
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 3px solid #059669;
            padding-bottom: 14px;
            margin-bottom: 18px;
        }
        .header-icon {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, #059669, #10b981);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            color: #fff;
            flex-shrink: 0;
        }
        .header-icon svg { width: 28px; height: 28px; fill: #fff; }
        .header-text h1 { font-size: 1.1rem; font-weight: 800; color: #059669; }
        .header-text p { font-size: 0.8rem; color: #64748b; margin-top: 3px; }
        .periode-badge {
            margin-left: auto;
            background: #d1fae5;
            color: #065f46;
            font-weight: 700;
            font-size: 0.78rem;
            padding: 6px 14px;
            border-radius: 20px;
            white-space: nowrap;
        }
        /* Stat row */
        .stats-row {
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
        }
        .stat-box {
            flex: 1;
            padding: 10px 14px;
            border-radius: 8px;
            text-align: center;
        }
        .stat-box.kegiatan { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .stat-box.hadir    { background: #f0fdf4; border: 1px solid #bbf7d0; }
        .stat-box.ijin     { background: #fffbeb; border: 1px solid #fde68a; }
        .stat-box.alpha    { background: #fef2f2; border: 1px solid #fecaca; }
        .stat-num { font-size: 1.4rem; font-weight: 800; line-height: 1; }
        .stat-num.kegiatan { color: #059669; }
        .stat-num.hadir    { color: #059669; }
        .stat-num.ijin     { color: #d97706; }
        .stat-num.alpha    { color: #dc2626; }
        .stat-lbl { font-size: 0.72rem; color: #64748b; margin-top: 3px; }
        /* Table */
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        thead th {
            background: #059669;
            color: #fff;
            font-weight: 700;
            font-size: 0.78rem;
            padding: 8px 10px;
            text-align: left;
        }
        thead th.center { text-align: center; }
        tbody td {
            padding: 7px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 0.8rem;
            vertical-align: top;
        }
        tbody tr:nth-child(even) td { background: #f8fafc; }
        tbody tr:last-child td { border-bottom: none; }
        .badge-h { background: #d1fae5; color: #065f46; padding: 2px 7px; border-radius: 20px; font-weight: 700; }
        .badge-i { background: #fef3c7; color: #92400e; padding: 2px 7px; border-radius: 20px; font-weight: 700; }
        .badge-a { background: #fee2e2; color: #991b1b; padding: 2px 7px; border-radius: 20px; font-weight: 700; }
        .tc { text-align: center; }
        tfoot td {
            background: #f1f5f9;
            font-weight: 700;
            padding: 8px 10px;
            border-top: 2px solid #059669;
            font-size: 0.82rem;
        }
        /* Progress */
        .bar-outer { background: #e2e8f0; height: 6px; border-radius: 20px; overflow: hidden; width: 60px; display: inline-block; vertical-align: middle; margin-right: 4px; }
        .bar-inner { height: 100%; background: linear-gradient(90deg, #059669, #10b981); border-radius: 20px; }
        /* Footer */
        .report-footer {
            margin-top: 24px;
            padding-top: 12px;
            border-top: 1px solid #e2e8f0;
            font-size: 0.72rem;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
        }
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }
        .gmaps-text { font-size: 0.72rem; color: #0369a1; }
        .section-title {
            font-size: 0.88rem;
            font-weight: 700;
            color: #059669;
            border-bottom: 1.5px solid #bbf7d0;
            padding-bottom: 6px;
            margin: 20px 0 10px;
        }
    </style>
</head>
<body>
<div class="page">
    {{-- Kop Surat --}}
    @include('partials.kop-surat')

    {{-- Judul & Periode --}}
    <div style="text-align: center; margin-bottom: 16px;">
        <div style="font-size: 1rem; font-weight: 800; color: #059669; text-transform: uppercase; letter-spacing: 0.5px;">Laporan Jadwal Pengajian</div>
        <div style="font-size: 0.8rem; color: #64748b; margin-top: 4px;">Periode: <strong>{{ $periodeLabel }}</strong> &bull; Dicetak: {{ now()->translatedFormat('d F Y, H:i') }}</div>
    </div>

    {{-- Stats --}}
    <div class="stats-row">
        <div class="stat-box kegiatan">
            <div class="stat-num kegiatan">{{ $jadwalList->count() }}</div>
            <div class="stat-lbl">Total Kegiatan</div>
        </div>
        <div class="stat-box hadir">
            <div class="stat-num hadir">{{ $totalHadir }}</div>
            <div class="stat-lbl">Total Hadir</div>
        </div>
        <div class="stat-box ijin">
            <div class="stat-num ijin">{{ $totalIjin }}</div>
            <div class="stat-lbl">Total Ijin</div>
        </div>
        <div class="stat-box alpha">
            <div class="stat-num alpha">{{ $totalAlpha }}</div>
            <div class="stat-lbl">Total Alpha</div>
        </div>
    </div>

    {{-- Tabel Kegiatan --}}
    <div class="section-title"><i>&#128197;</i> Daftar Kegiatan Pengajian</div>
    @if($jadwalList->isEmpty())
        <p style="color:#94a3b8; text-align:center; padding:20px;">Tidak ada data pada periode ini.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width:28px;" class="center">#</th>
                    <th>Nama Kegiatan</th>
                    <th style="min-width:90px;">Tanggal</th>
                    <th>Tempat</th>
                    <th class="center">Hadir</th>
                    <th class="center">Ijin</th>
                    <th class="center">Alpha</th>
                    <th class="center">Total</th>
                    <th class="center" style="min-width:80px;">% Hadir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jadwalList as $idx => $jadwal)
                    @php
                        $hadir  = $jadwal->hadir_count;
                        $ijin   = $jadwal->ijin_count;
                        $alpha  = $jadwal->alpha_count;
                        $total  = $jadwal->total;
                        $persen = $jadwal->persen_hadir;
                    @endphp
                    <tr>
                        <td class="tc" style="color:#94a3b8; font-weight:700;">{{ $idx + 1 }}</td>
                        <td style="font-weight:600;">{{ $jadwal->nama_kegiatan }}</td>
                        <td>
                            <div style="font-weight:700;">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d M Y') }}</div>
                            <div style="font-size:0.72rem; color:#64748b;">{{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l') }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $jadwal->tempat }}</div>
                            @if($jadwal->lokasi_gmaps)
                                <div class="gmaps-text">&#128205; {{ Str::limit($jadwal->lokasi_gmaps, 40) }}</div>
                            @endif
                        </td>
                        <td class="tc"><span class="badge-h">{{ $hadir }}</span></td>
                        <td class="tc"><span class="badge-i">{{ $ijin }}</span></td>
                        <td class="tc"><span class="badge-a">{{ $alpha }}</span></td>
                        <td class="tc" style="font-weight:700;">{{ $total }}</td>
                        <td class="tc">
                            <span class="bar-outer"><span class="bar-inner" style="width:{{ $persen }}%;"></span></span>
                            <span style="font-weight:700; color:{{ $persen >= 75 ? '#059669' : ($persen >= 50 ? '#d97706' : '#dc2626') }};">{{ $persen }}%</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align:right; color:#64748b;">TOTAL</td>
                    <td class="tc"><span class="badge-h">{{ $totalHadir }}</span></td>
                    <td class="tc"><span class="badge-i">{{ $totalIjin }}</span></td>
                    <td class="tc"><span class="badge-a">{{ $totalAlpha }}</span></td>
                    <td class="tc">{{ $totalPeserta }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    @endif

    {{-- Rekap per guru & karyawan --}}
    @php
        $rekapPrint = collect();
        if ($jadwalList->isNotEmpty()) {
            $jadwalIds = $jadwalList->pluck('id_jadwal');

            $rekapGuru = \App\Models\Guru::where('status', 'aktif')
                ->withCount([
                    'kehadiranPengajian as total_kegiatan' => function($q) use ($jadwalIds) {
                        $q->whereIn('id_jadwal', $jadwalIds);
                    },
                    'kehadiranPengajian as total_hadir' => function ($q) use ($jadwalIds) {
                        $q->whereIn('id_jadwal', $jadwalIds)->where('status', 'hadir');
                    },
                    'kehadiranPengajian as total_ijin' => function ($q) use ($jadwalIds) {
                        $q->whereIn('id_jadwal', $jadwalIds)->where('status', 'ijin');
                    },
                    'kehadiranPengajian as total_alpha' => function ($q) use ($jadwalIds) {
                        $q->whereIn('id_jadwal', $jadwalIds)->where('status', 'alpha');
                    },
                ])
                ->orderBy('nama_guru')
                ->get()
                ->filter(fn($item) => $item->total_kegiatan > 0)
                ->map(function($item) {
                    $item->nama_tampil = $item->nama_guru;
                    $item->tipe = 'Guru';
                    return $item;
                });

            $rekapKaryawan = \App\Models\Karyawan::where('status', 'aktif')
                ->withCount([
                    'kehadiranPengajian as total_kegiatan' => function($q) use ($jadwalIds) {
                        $q->whereIn('id_jadwal', $jadwalIds);
                    },
                    'kehadiranPengajian as total_hadir' => function ($q) use ($jadwalIds) {
                        $q->whereIn('id_jadwal', $jadwalIds)->where('status', 'hadir');
                    },
                    'kehadiranPengajian as total_ijin' => function ($q) use ($jadwalIds) {
                        $q->whereIn('id_jadwal', $jadwalIds)->where('status', 'ijin');
                    },
                    'kehadiranPengajian as total_alpha' => function ($q) use ($jadwalIds) {
                        $q->whereIn('id_jadwal', $jadwalIds)->where('status', 'alpha');
                    },
                ])
                ->orderBy('nama_karyawan')
                ->get()
                ->filter(fn($item) => $item->total_kegiatan > 0)
                ->map(function($item) {
                    $item->nama_tampil = $item->nama_karyawan;
                    $item->tipe = 'Karyawan';
                    return $item;
                });

            $rekapPrint = $rekapGuru->concat($rekapKaryawan)->sortBy('nama_tampil')->values();
        }
    @endphp

    @if($rekapPrint->isNotEmpty())
        <div class="section-title"><i>&#128200;</i> Rekap Kehadiran per Guru Karyawan</div>
        <table>
            <thead>
                <tr>
                    <th style="width:28px;" class="center">#</th>
                    <th>Nama</th>
                    <th class="center">Tipe</th>
                    <th class="center">Kegiatan</th>
                    <th class="center">Hadir</th>
                    <th class="center">Ijin</th>
                    <th class="center">Alpha</th>
                    <th class="center">Total</th>
                    <th class="center">% Hadir</th>
                </tr>
            </thead>
            <tbody>
                @foreach($rekapPrint as $idx => $r)
                    @php
                        $tot = $r->total_hadir + $r->total_ijin + $r->total_alpha;
                        $pct = $tot > 0 ? round(($r->total_hadir / $tot) * 100, 1) : 0;
                    @endphp
                    <tr>
                        <td class="tc" style="color:#94a3b8; font-weight:700;">{{ $idx + 1 }}</td>
                        <td style="font-weight:600;">{{ $r->nama_tampil }}</td>
                        <td class="tc">
                            <span style="background:{{ $r->tipe === 'Guru' ? '#dbeafe' : '#fce7f3' }}; color:{{ $r->tipe === 'Guru' ? '#1d4ed8' : '#be185d' }}; padding:2px 7px; border-radius:20px; font-size:0.78rem; font-weight:700;">{{ $r->tipe }}</span>
                        </td>
                        <td class="tc"><span style="background:#e0f2fe; color:#0369a1; padding:2px 7px; border-radius:20px; font-weight:700;">{{ $r->total_kegiatan }}x</span></td>
                        <td class="tc"><span class="badge-h">{{ $r->total_hadir }}</span></td>
                        <td class="tc"><span class="badge-i">{{ $r->total_ijin }}</span></td>
                        <td class="tc"><span class="badge-a">{{ $r->total_alpha }}</span></td>
                        <td class="tc" style="font-weight:700;">{{ $tot }}</td>
                        <td class="tc">
                            <span class="bar-outer"><span class="bar-inner" style="width:{{ $pct }}%;"></span></span>
                            <span style="font-weight:700; color:{{ $pct >= 75 ? '#059669' : ($pct >= 50 ? '#d97706' : '#dc2626') }};">{{ $pct }}%</span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Footer --}}
    <div class="report-footer">
        <span>SmartSchool &mdash; Sistem Informasi Sekolah Digital</span>
        <span>Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB</span>
    </div>
</div>

<div class="no-print" style="text-align:center; padding:20px;">
    <button onclick="window.print()"
            style="background:#059669; color:#fff; border:none; padding:10px 24px; border-radius:8px; font-weight:700; font-size:0.9rem; cursor:pointer; margin-right:8px;">
        &#128424; Cetak Halaman
    </button>
    <button onclick="window.close()"
            style="background:#f1f5f9; color:#475569; border:1px solid #e2e8f0; padding:10px 24px; border-radius:8px; font-weight:700; font-size:0.9rem; cursor:pointer;">
        &#10005; Tutup
    </button>
</div>

<script>
    // Auto-print on load
    window.addEventListener('load', function() {
        setTimeout(function() { window.print(); }, 400);
    });
</script>
</body>
</html>
