@extends('layouts.app')

@section('title', 'SmartSchool — Dashboard')
@section('header_title', 'Dashboard')
@section('header_subtitle', 'Selamat datang! Pantau aktivitas sekolah secara real-time.')

@push('styles')
<style>
/* ═══════════════════════════════════════════
   DASHBOARD SECTION STYLES
   ─ Sekat / divider antar bagian dashboard
═══════════════════════════════════════════ */

/* ── Section Wrapper ── */
.dashboard-section {
    background: var(--card-bg);
    border: 1.5px solid var(--border-color);
    border-radius: 20px;
    padding: 24px 24px 20px;
    margin-bottom: 24px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.04);
    transition: box-shadow 0.2s;
}
.dashboard-section:hover {
    box-shadow: 0 4px 24px rgba(79,70,229,0.07);
}

/* ── Section Header ── */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 20px;
    padding-bottom: 16px;
    border-bottom: 1.5px solid var(--border-color);
}
.section-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.section-icon {
    width: 40px;
    height: 40px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 1rem;
}
.section-icon-blue   { background: linear-gradient(135deg,#4f46e5,#818cf8); color:#fff; }
.section-icon-green  { background: linear-gradient(135deg,#10b981,#34d399); color:#fff; }
.section-icon-pink   { background: linear-gradient(135deg,#ec4899,#f472b6); color:#fff; }
.section-icon-orange { background: linear-gradient(135deg,#f59e0b,#fbbf24); color:#fff; }
.section-icon-purple { background: linear-gradient(135deg,#8b5cf6,#a78bfa); color:#fff; }
.section-icon-cyan   { background: linear-gradient(135deg,#0ea5e9,#38bdf8); color:#fff; }
.section-icon-indigo { background: linear-gradient(135deg,#6366f1,#818cf8); color:#fff; }
.section-icon-brain  { background: linear-gradient(135deg,#4f46e5,#ec4899); color:#fff; }

.section-title {
    font-size: 1rem;
    font-weight: 800;
    color: var(--text-primary);
    line-height: 1.2;
}
.section-subtitle {
    font-size: 0.78rem;
    color: var(--text-muted);
    margin-top: 2px;
}
.section-action-link {
    font-size: 0.8rem;
    color: var(--color-primary);
    font-weight: 600;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 14px;
    border-radius: 20px;
    border: 1.5px solid var(--color-primary);
    transition: all 0.18s;
    white-space: nowrap;
}
.section-action-link:hover {
    background: var(--color-primary);
    color: #fff;
}
.section-badge {
    font-size: 0.73rem;
    font-weight: 700;
    padding: 4px 12px;
    border-radius: 20px;
    letter-spacing: 0.3px;
    white-space: nowrap;
}
.sbadge-blue   { background:#eff6ff; color:#2563eb; }
.sbadge-green  { background:#f0fdf4; color:#059669; }
.sbadge-pink   { background:#fdf2f8; color:#db2777; }
.sbadge-orange { background:#fff7ed; color:#ea580c; }
.sbadge-purple { background:#faf5ff; color:#7c3aed; }

/* ── Inner chart / table cards ── */
.dashboard-section .chart-card {
    border: 1.5px solid var(--border-color);
    border-radius: 14px;
    background: var(--input-bg, #f8fafc);
    padding: 16px;
    transition: border-color 0.2s;
}
[data-theme="dark"] .dashboard-section .chart-card {
    background: rgba(255,255,255,0.03);
}
.dashboard-section .chart-card:hover {
    border-color: rgba(79,70,229,0.25);
}

/* ── Summary chips row ── */
.summary-chips {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    margin-bottom: 18px;
}
.summary-chip {
    background: var(--card-bg);
    border: 1.5px solid var(--border-color);
    border-radius: 12px;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 130px;
    transition: border-color 0.18s, box-shadow 0.18s;
}
.summary-chip:hover {
    border-color: rgba(79,70,229,0.3);
    box-shadow: 0 2px 10px rgba(79,70,229,0.08);
}
.chip-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.85rem;
    color: #fff;
}
.chip-number { font-size: 1.25rem; font-weight: 800; color: var(--text-primary); line-height: 1; }
.chip-label  { font-size: 0.72rem; color: var(--text-muted); margin-top: 1px; }
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════════
     SECTION 1 ─ STATISTIK UTAMA
═══════════════════════════════════════════════════════════ --}}
<div class="dashboard-section" id="section-statistik">

    <div class="section-header">
        <div class="section-header-left">
            <div class="section-icon section-icon-blue">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <div>
                <div class="section-title">Statistik Utama</div>
                <div class="section-subtitle">Ringkasan data keseluruhan sekolah</div>
            </div>
        </div>
        <span class="section-badge sbadge-blue">Overview</span>
    </div>

    <div class="stats-grid">

        <div class="stat-card siswa" id="stat-siswa">
            <div class="stat-icon"><i class="fa-solid fa-user-graduate"></i></div>
            <div>
                <div class="stat-number" data-count="{{ $siswaCount }}">0</div>
                <div class="stat-label">Total Siswa</div>
            </div>
        </div>

        <div class="stat-card guru" id="stat-guru">
            <div class="stat-icon"><i class="fa-solid fa-chalkboard-user"></i></div>
            <div>
                <div class="stat-number" data-count="{{ $guruCount }}">0</div>
                <div class="stat-label">Total Guru</div>
            </div>
        </div>

        <div class="stat-card kelas" id="stat-kelas">
            <div class="stat-icon"><i class="fa-solid fa-school"></i></div>
            <div>
                <div class="stat-number" data-count="{{ $kelasCount }}">0</div>
                <div class="stat-label">Kelas Aktif</div>
            </div>
        </div>

        <div class="stat-card presensi" id="stat-presensi">
            <div class="stat-icon"><i class="fa-solid fa-clipboard-user"></i></div>
            <div>
                <div class="stat-number" data-count="{{ $presensiHariIni }}">0</div>
                <div class="stat-label">Presensi Hari Ini</div>
            </div>
        </div>

        <div class="stat-card uks" id="stat-uks">
            <div class="stat-icon"><i class="fa-solid fa-heart-pulse"></i></div>
            <div>
                <div class="stat-number" data-count="{{ $kunjunganUks }}">0</div>
                <div class="stat-label">Kunjungan UKS</div>
            </div>
        </div>

        <div class="stat-card tadarus" id="stat-tadarus">
            <div class="stat-icon"><i class="fa-solid fa-book-quran"></i></div>
            <div>
                <div class="stat-number" data-count="{{ $tadarusHariIni }}">0</div>
                <div class="stat-label">Tadarus Hari Ini</div>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     SECTION 2 ─ PRESENSI SISWA
═══════════════════════════════════════════════════════════ --}}
<div class="dashboard-section" id="section-presensi">

    <div class="section-header">
        <div class="section-header-left">
            <div class="section-icon section-icon-green">
                <i class="fa-solid fa-clipboard-user"></i>
            </div>
            <div>
                <div class="section-title">Presensi Siswa</div>
                <div class="section-subtitle">Rekap kehadiran T.A. {{ $tahunAjaranNama }}</div>
            </div>
        </div>
        <span class="section-badge sbadge-green">T.A. {{ $tahunAjaranNama }}</span>
    </div>

    <div class="charts-row charts-row-2">

        {{-- Bar: Presensi per Bulan --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Grafik Presensi Siswa</div>
                    <div class="chart-card-subtitle">Rekap kehadiran per bulan</div>
                </div>
                <span class="chart-badge badge-blue">Bar Chart</span>
            </div>
            <div class="chart-area" style="height:270px;">
                <canvas id="chartPresensi"></canvas>
            </div>
        </div>

        {{-- Donut: Status hari ini --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Status Presensi Hari Ini</div>
                    <div class="chart-card-subtitle">{{ \Carbon\Carbon::parse($today)->translatedFormat('d F Y') }}</div>
                </div>
                <span class="chart-badge badge-green">Live</span>
            </div>

            @php
                $statuses = ['Hadir','Sakit','Izin','Alfa'];
                $donutColors = ['#10b981','#f59e0b','#0ea5e9','#ef4444'];
                $todayTotal = array_sum(array_values($presensiTodayBreakdown));
            @endphp

            <div class="donut-layout" style="margin-top:10px;">
                <div class="donut-chart-wrap" style="height:170px;">
                    <canvas id="chartDonut"></canvas>
                </div>
                <div class="donut-legend">
                    @foreach($statuses as $i => $s)
                        <div class="legend-item">
                            <div class="legend-dot" style="background:{{ $donutColors[$i] }};"></div>
                            <span class="legend-label">{{ $s }}</span>
                            <span class="legend-value">{{ $presensiTodayBreakdown[$s] ?? 0 }}</span>
                        </div>
                    @endforeach
                    <div style="border-top:1.5px solid var(--border-color); padding-top:10px; font-size:0.82rem; color:var(--text-muted);">
                        Total: <strong style="color:var(--text-primary);">{{ $todayTotal }}</strong> siswa
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     SECTION 3 ─ UKS, BK & ISMUBA
═══════════════════════════════════════════════════════════ --}}
<div class="dashboard-section" id="section-uks-bk">

    <div class="section-header">
        <div class="section-header-left">
            <div class="section-icon section-icon-pink">
                <i class="fa-solid fa-kit-medical"></i>
            </div>
            <div>
                <div class="section-title">UKS, Bimbingan Konseling & ISMUBA</div>
                <div class="section-subtitle">Kunjungan UKS, Pelanggaran, dan Tadarus — T.A. {{ $tahunAjaranNama }}</div>
            </div>
        </div>
        <span class="section-badge sbadge-pink">Per Bulan</span>
    </div>

    <div class="charts-row charts-row-3">

        {{-- Line: UKS per bulan --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Kunjungan UKS</div>
                    <div class="chart-card-subtitle">Per bulan – T.A. {{ $tahunAjaranNama }}</div>
                </div>
                <span class="chart-badge badge-pink">UKS</span>
            </div>
            <div class="chart-area" style="height:190px;">
                <canvas id="chartUks"></canvas>
            </div>
        </div>

        {{-- Bar: Pelanggaran per bulan --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Pelanggaran Siswa</div>
                    <div class="chart-card-subtitle">Per bulan – T.A. {{ $tahunAjaranNama }}</div>
                </div>
                <span class="chart-badge badge-orange">BK</span>
            </div>
            <div class="chart-area" style="height:190px;">
                <canvas id="chartPelanggaran"></canvas>
            </div>
        </div>

        {{-- Bar: Tadarus per bulan --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Kegiatan Tadarus</div>
                    <div class="chart-card-subtitle">Per bulan – T.A. {{ $tahunAjaranNama }}</div>
                </div>
                <span class="chart-badge badge-purple">PAI</span>
            </div>
            <div class="chart-area" style="height:190px;">
                <canvas id="chartTadarus"></canvas>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     SECTION 4 ─ JURNAL GURU
═══════════════════════════════════════════════════════════ --}}
<div class="dashboard-section" id="section-jurnal">

    <div class="section-header">
        <div class="section-header-left">
            <div class="section-icon section-icon-indigo">
                <i class="fa-solid fa-book-open-reader"></i>
            </div>
            <div>
                <div class="section-title">Jurnal Guru</div>
                <div class="section-subtitle">Statistik pengisian jurnal dan riwayat terbaru</div>
            </div>
        </div>
        <a href="{{ route('jurnal-guru.index') }}" class="section-action-link">
            Lihat Semua <i class="fa-solid fa-arrow-right" style="font-size:0.7rem;"></i>
        </a>
    </div>

    {{-- Summary Chips --}}
    <div class="summary-chips">
        <div class="summary-chip">
            <div class="chip-icon" style="background:linear-gradient(135deg,#4f46e5,#818cf8);">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <div class="chip-number">{{ $jurnalTotal }}</div>
                <div class="chip-label">Total Jurnal</div>
            </div>
        </div>
        <div class="summary-chip">
            <div class="chip-icon" style="background:linear-gradient(135deg,#10b981,#34d399);">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <div class="chip-number" style="color:#059669;">{{ $jurnalApproved }}</div>
                <div class="chip-label">Disetujui</div>
            </div>
        </div>
        <div class="summary-chip">
            <div class="chip-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <div class="chip-number" style="color:#d97706;">{{ $jurnalPending }}</div>
                <div class="chip-label">Menunggu</div>
            </div>
        </div>
        <div class="summary-chip">
            <div class="chip-icon" style="background:linear-gradient(135deg,#ef4444,#f87171);">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div>
                <div class="chip-number" style="color:#dc2626;">{{ $jurnalRejected }}</div>
                <div class="chip-label">Ditolak</div>
            </div>
        </div>
        <div class="summary-chip">
            <div class="chip-icon" style="background:linear-gradient(135deg,#8b5cf6,#a78bfa);">
                <i class="fa-solid fa-calendar-day"></i>
            </div>
            <div>
                <div class="chip-number" style="color:#7c3aed;">{{ $jurnalBulanIni }}</div>
                <div class="chip-label">Bulan Ini</div>
            </div>
        </div>
    </div>

    <div class="charts-row charts-row-bottom">

        {{-- Bar Chart: Jurnal per Bulan --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Grafik Jurnal per Bulan</div>
                    <div class="chart-card-subtitle">Jumlah jurnal yang diisi setiap bulan</div>
                </div>
                <span class="chart-badge badge-blue">Bar Chart</span>
            </div>
            <div class="chart-area" style="height:220px;">
                <canvas id="chartJurnal"></canvas>
            </div>
        </div>

        {{-- Table: 8 jurnal terbaru --}}
        <div class="chart-card" style="flex:2; min-width:0;">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Jurnal Terbaru</div>
                    <div class="chart-card-subtitle">8 entri jurnal paling baru</div>
                </div>
                <span class="chart-badge badge-green">Terbaru</span>
            </div>
            <div class="table-wrapper" style="margin-top:8px;">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Guru</th>
                            <th>Mapel</th>
                            <th>Kelas</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentJurnal as $i => $j)
                            <tr>
                                <td style="color:var(--text-muted); font-weight:600;">{{ $i + 1 }}</td>
                                <td style="font-weight:700; color:var(--color-primary);">{{ $j->guru->nama_guru ?? '—' }}</td>
                                <td>{{ $j->mapel->nama_mapel ?? '—' }}</td>
                                <td>{{ $j->kelas->nama_kelas ?? '—' }}</td>
                                <td>{{ $j->tanggal ? \Carbon\Carbon::parse($j->tanggal)->format('d M Y') : '—' }}</td>
                                <td>{{ $j->jam_ke ?? '—' }}</td>
                                <td>
                                    @php
                                        $approvalStatus = $j->status_approval ?? 'pending';
                                        $approvalClass = match($approvalStatus) {
                                            'approved' => 'hadir',
                                            'rejected' => 'alfa',
                                            default    => 'izin'
                                        };
                                        $approvalText = match($approvalStatus) {
                                            'approved' => 'Disetujui',
                                            'rejected' => 'Ditolak',
                                            default    => 'Menunggu'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $approvalClass }}">{{ $approvalText }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align:center; padding:40px; color:var(--text-muted);">
                                    <i class="fa-solid fa-inbox" style="font-size:1.8rem; display:block; margin-bottom:10px;"></i>
                                    Belum ada data jurnal guru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     SECTION 5 ─ DISTRIBUSI SISWA & BTAQ
═══════════════════════════════════════════════════════════ --}}
<div class="dashboard-section" id="section-siswa-btaq">

    <div class="section-header">
        <div class="section-header-left">
            <div class="section-icon section-icon-cyan">
                <i class="fa-solid fa-users-viewfinder"></i>
            </div>
            <div>
                <div class="section-title">Distribusi Siswa & Status BTAQ</div>
                <div class="section-subtitle">Sebaran siswa per tingkat dan status kemampuan BTAQ</div>
            </div>
        </div>
        <span class="section-badge sbadge-blue">Kelas & BTAQ</span>
    </div>

    <div class="charts-row charts-row-2">

        {{-- Polar: Distribusi siswa per tingkat --}}
        <div class="chart-card" style="display:flex; flex-direction:column;">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Distribusi Siswa</div>
                    <div class="chart-card-subtitle">Berdasarkan tingkat kelas</div>
                </div>
                <span class="chart-badge badge-cyan">Kelas</span>
            </div>
            <div style="flex:1; display:flex; flex-direction:column; justify-content:center;">
                <div class="chart-area" style="height:180px; margin-bottom:16px;">
                    <canvas id="chartSiswaTingkat"></canvas>
                </div>
                <div class="progress-list" id="siswaTingkatProgress">
                    @php
                        $tingkatColors = ['#4f46e5','#10b981','#f59e0b','#ec4899'];
                        $totalSiswa = array_sum($siswaTingkatData);
                    @endphp
                    @foreach($siswaTingkatLabels as $j => $lbl)
                        @php
                            $pct = $totalSiswa > 0 ? round(($siswaTingkatData[$j] / $totalSiswa) * 100) : 0;
                            $color = $tingkatColors[$j % count($tingkatColors)];
                        @endphp
                        <div class="progress-item">
                            <div class="progress-label">
                                <span>{{ $lbl }}</span>
                                <span>{{ $siswaTingkatData[$j] }} siswa ({{ $pct }}%)</span>
                            </div>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" style="width:0%; background:{{ $color }};" data-target="{{ $pct }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Pie: Sebaran Status BTAQ Siswa --}}
        <div class="chart-card" style="display:flex; flex-direction:column;">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Data Status BTAQ Siswa</div>
                    <div class="chart-card-subtitle">Sebaran status BTAQ siswa aktif</div>
                </div>
                <span class="chart-badge badge-purple">BTAQ</span>
            </div>
            <div style="flex:1; display:flex; flex-direction:column; justify-content:center;">
                <div class="chart-area" style="height:180px; margin-bottom:16px;">
                    <canvas id="chartBtaq"></canvas>
                </div>
                <div class="progress-list" id="btaqProgress">
                    @php
                        $btaqColors = ['#d4e157', '#108c10', '#e51c23'];
                        $totalBtaq = array_sum($btaqData);
                    @endphp
                    @foreach($btaqLabels as $j => $lbl)
                        @php
                            $pct = $totalBtaq > 0 ? round(($btaqData[$j] / $totalBtaq) * 100, 2) : 0;
                            $color = $btaqColors[$j];
                        @endphp
                        <div class="progress-item">
                            <div class="progress-label">
                                <span><i class="fa-solid fa-circle" style="color:{{ $color }}; font-size:0.6rem; margin-right:6px;"></i>{{ $lbl }}</span>
                                <span>{{ $btaqData[$j] }} siswa ({{ round($pct, 1) }}%)</span>
                            </div>
                            <div class="progress-bar-wrap">
                                <div class="progress-bar" style="width:0%; background:{{ $color }};" data-target="{{ $pct }}"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     SECTION 6 ─ GAYA BELAJAR SISWA
═══════════════════════════════════════════════════════════ --}}
@php
    $gayaColorMap = [
        'visual'     => ['bg'=>'#4f46e5', 'light'=>'#ede9fe', 'text'=>'#4f46e5'],
        'auditori'   => ['bg'=>'#10b981', 'light'=>'#d1fae5', 'text'=>'#059669'],
        'kinestetik' => ['bg'=>'#f59e0b', 'light'=>'#fef3c7', 'text'=>'#d97706'],
    ];
    $gayaIcons = [
        'visual'     => 'fa-eye',
        'auditori'   => 'fa-ear-listen',
        'kinestetik' => 'fa-person-running',
    ];
@endphp

<div class="dashboard-section" id="section-gaya-belajar">

    <div class="section-header">
        <div class="section-header-left">
            <div class="section-icon section-icon-brain">
                <i class="fa-solid fa-brain"></i>
            </div>
            <div>
                <div class="section-title">Statistik Gaya Belajar Siswa</div>
                <div class="section-subtitle">Distribusi gaya belajar berdasarkan tingkat, jurusan, dan kelas</div>
            </div>
        </div>
        <a href="{{ route('bk.gaya-belajar.index') }}" class="section-action-link">
            Kelola Data <i class="fa-solid fa-arrow-right" style="font-size:0.7rem;"></i>
        </a>
    </div>

    {{-- Summary Chips --}}
    <div class="summary-chips">
        <div class="summary-chip">
            <div class="chip-icon" style="background:linear-gradient(135deg,#4f46e5,#818cf8);">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <div>
                <div class="chip-number">{{ $gayaTotal }}</div>
                <div class="chip-label">Total Terdata</div>
            </div>
        </div>
        @foreach($gayaTypes as $i => $gt)
        @php $c = $gayaColorMap[$gt]; $cnt = $gayaOverallData[$i]; @endphp
        <div class="summary-chip" style="background:{{ $c['light'] }}; border-color:{{ $c['bg'] }}33;">
            <div class="chip-icon" style="background:{{ $c['bg'] }};">
                <i class="fa-solid {{ $gayaIcons[$gt] }}"></i>
            </div>
            <div>
                <div class="chip-number" style="color:{{ $c['text'] }};">{{ $cnt }}</div>
                <div class="chip-label" style="color:{{ $c['text'] }}; opacity:0.8;">{{ $gayaLabels[$i] }}</div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Row: Overall Donut + Per Tingkat Stacked Bar --}}
    <div class="charts-row charts-row-2" style="margin-bottom:16px;">

        {{-- Donut: Distribusi Keseluruhan --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Distribusi Keseluruhan</div>
                    <div class="chart-card-subtitle">Total {{ $gayaTotal }} siswa terdata</div>
                </div>
                <span class="chart-badge" style="background:#ede9fe;color:#4f46e5;">BK</span>
            </div>
            <div style="display:flex;align-items:center;gap:20px;padding:8px 0 4px;">
                <div style="position:relative;width:160px;height:160px;flex-shrink:0;">
                    <canvas id="chartGayaOverall"></canvas>
                </div>
                <div style="flex:1;display:flex;flex-direction:column;gap:8px;">
                    @foreach($gayaTypes as $i => $gt)
                    @php
                        $c   = $gayaColorMap[$gt];
                        $cnt = $gayaOverallData[$i];
                        $pct = $gayaTotal > 0 ? round(($cnt / $gayaTotal) * 100, 1) : 0;
                    @endphp
                    <div>
                        <div style="display:flex;justify-content:space-between;font-size:0.78rem;margin-bottom:3px;">
                            <span style="display:flex;align-items:center;gap:5px;">
                                <i class="fa-solid fa-circle" style="color:{{ $c['bg'] }};font-size:0.55rem;"></i>
                                <span style="font-weight:600;color:var(--text-primary);">{{ $gayaLabels[$i] }}</span>
                            </span>
                            <span style="color:var(--text-muted);">{{ $cnt }} <small>({{ $pct }}%)</small></span>
                        </div>
                        <div style="height:5px;border-radius:99px;background:var(--border-color);overflow:hidden;">
                            <div style="height:100%;width:{{ $pct }}%;background:{{ $c['bg'] }};border-radius:99px;transition:width 0.8s ease;"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Stacked Bar: Per Tingkat --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Gaya Belajar per Tingkat</div>
                    <div class="chart-card-subtitle">Distribusi per tingkat kelas</div>
                </div>
                <span class="chart-badge badge-blue">Tingkat</span>
            </div>
            <div class="chart-area" style="height:210px;">
                <canvas id="chartGayaTingkat"></canvas>
            </div>
        </div>

    </div>

    {{-- Row: Per Jurusan + Per Kelas --}}
    <div class="charts-row charts-row-2">

        {{-- Stacked Bar: Per Jurusan --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Gaya Belajar per Jurusan</div>
                    <div class="chart-card-subtitle">Distribusi per jurusan / program</div>
                </div>
                <span class="chart-badge badge-green">Jurusan</span>
            </div>
            <div class="chart-area" style="height:210px;">
                <canvas id="chartGayaJurusan"></canvas>
            </div>
        </div>

        {{-- Horizontal Stacked Bar: Per Kelas --}}
        <div class="chart-card">
            <div class="chart-card-header">
                <div>
                    <div class="chart-card-title">Gaya Belajar per Kelas</div>
                    <div class="chart-card-subtitle">Detail distribusi tiap rombel</div>
                </div>
                <span class="chart-badge badge-orange">Kelas</span>
            </div>
            <div style="overflow-y:auto;max-height:230px;padding-right:4px;">
                <div style="min-width:200px;" id="chartGayaKelasWrap">
                    <canvas id="chartGayaKelas"></canvas>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ══════════════════════════════════════════════
     CHART.JS + SCRIPTS
═══════════════════════════════════════════════ --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
/* ── data from PHP ── */
const months       = @json($months);
const presensi     = @json($presensiPerBulan);
const uksData      = @json($uksPerBulan);
const pelData      = @json($pelanggaranPerBulan);
const tadarusData  = @json($tadarusPerBulan);
const donutData    = [
    {{ $presensiTodayBreakdown['Hadir']  ?? 0 }},
    {{ $presensiTodayBreakdown['Sakit']  ?? 0 }},
    {{ $presensiTodayBreakdown['Izin']   ?? 0 }},
    {{ $presensiTodayBreakdown['Alfa']   ?? 0 }},
];
const tingkatLabels = @json($siswaTingkatLabels);
const tingkatData   = @json($siswaTingkatData);
const btaqLabels    = @json($btaqLabels);
const btaqData      = @json($btaqData);
const jurnalData    = @json($jurnalPerBulan);

/* ── Chart defaults ── */
Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
Chart.defaults.font.size   = 12;
Chart.defaults.color       = '#64748b';
Chart.defaults.plugins.legend.labels.usePointStyle = true;
Chart.defaults.plugins.legend.labels.pointStyleWidth = 10;
Chart.defaults.plugins.legend.labels.boxHeight = 8;

const tooltipStyle = {
    backgroundColor: 'rgba(15,23,42,0.92)',
    titleColor: '#f8fafc',
    bodyColor: '#cbd5e1',
    borderColor: 'rgba(255,255,255,0.1)',
    borderWidth: 1,
    padding: 12,
    cornerRadius: 10,
    displayColors: true,
    boxPadding: 4,
};

/* ── 1. Grouped Bar: Presensi per bulan ── */
new Chart(document.getElementById('chartPresensi'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [
            { label:'Sakit', data: presensi['Sakit'], backgroundColor:'rgba(245,158,11,0.85)',  borderRadius:5, borderSkipped:false },
            { label:'Izin',  data: presensi['Izin'],  backgroundColor:'rgba(14,165,233,0.85)', borderRadius:5, borderSkipped:false },
            { label:'Alfa',  data: presensi['Alfa'],  backgroundColor:'rgba(239,68,68,0.85)',   borderRadius:5, borderSkipped:false },
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { position:'bottom', labels:{ padding:20 } },
            tooltip: { ...tooltipStyle, mode:'index', intersect:false }
        },
        scales: {
            x: { grid:{ display:false }, border:{ display:false } },
            y: { grid:{ color:'rgba(0,0,0,0.05)' }, border:{ display:false }, ticks:{ stepSize:10 } }
        },
        interaction: { mode:'index', intersect:false }
    }
});

/* ── 2. Doughnut: Status hari ini ── */
const hasDonutData = donutData.some(v => v > 0);
new Chart(document.getElementById('chartDonut'), {
    type: 'doughnut',
    data: {
        labels: ['Hadir','Sakit','Izin','Alfa'],
        datasets: [{
            data: hasDonutData ? donutData : [1,0,0,0],
            backgroundColor: ['#10b981','#f59e0b','#0ea5e9','#ef4444'],
            borderWidth: 3,
            borderColor: '#ffffff',
            hoverBorderWidth: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '68%',
        plugins: {
            legend: { display:false },
            tooltip: { ...tooltipStyle, enabled: hasDonutData }
        }
    }
});

/* ── 3. Line: Kunjungan UKS ── */
new Chart(document.getElementById('chartUks'), {
    type: 'line',
    data: {
        labels: months,
        datasets: [{
            label: 'Kunjungan UKS',
            data: uksData,
            borderColor: '#ec4899',
            backgroundColor: 'rgba(236,72,153,0.12)',
            borderWidth: 2.5,
            fill: true,
            tension: 0.4,
            pointBackgroundColor: '#ec4899',
            pointRadius: 4,
            pointHoverRadius: 7,
        }]
    },
    options: {
        responsive:true, maintainAspectRatio:false,
        plugins:{ legend:{display:false}, tooltip:tooltipStyle },
        scales:{
            x:{ grid:{display:false}, border:{display:false} },
            y:{ grid:{color:'rgba(0,0,0,0.05)'}, border:{display:false}, ticks:{stepSize:1} }
        }
    }
});

/* ── 4. Bar: Pelanggaran ── */
new Chart(document.getElementById('chartPelanggaran'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Pelanggaran',
            data: pelData,
            backgroundColor: 'rgba(249,115,22,0.85)',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive:true, maintainAspectRatio:false,
        plugins:{ legend:{display:false}, tooltip:tooltipStyle },
        scales:{
            x:{ grid:{display:false}, border:{display:false} },
            y:{ grid:{color:'rgba(0,0,0,0.05)'}, border:{display:false} }
        }
    }
});

/* ── 5. Bar: Tadarus ── */
new Chart(document.getElementById('chartTadarus'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Tadarus',
            data: tadarusData,
            backgroundColor: 'rgba(168,85,247,0.85)',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive:true, maintainAspectRatio:false,
        plugins:{ legend:{display:false}, tooltip:tooltipStyle },
        scales:{
            x:{ grid:{display:false}, border:{display:false} },
            y:{ grid:{color:'rgba(0,0,0,0.05)'}, border:{display:false} }
        }
    }
});

/* ── 6. Polar area: Distribusi siswa per tingkat ── */
const tingkatColors = ['#4f46e5','#10b981','#f59e0b','#ec4899','#0ea5e9','#a855f7'];
new Chart(document.getElementById('chartSiswaTingkat'), {
    type: 'polarArea',
    data: {
        labels: tingkatLabels.length ? tingkatLabels : ['Belum ada data'],
        datasets:[{
            data: tingkatData.length ? tingkatData : [1],
            backgroundColor: tingkatColors.slice(0, Math.max(tingkatLabels.length,1)).map(c => c + 'cc'),
            borderWidth: 2,
            borderColor: '#ffffff',
        }]
    },
    options: {
        responsive:true, maintainAspectRatio:false,
        plugins:{
            legend:{ display:false },
            tooltip: tooltipStyle,
        },
        scales:{ r:{ ticks:{display:false}, grid:{color:'rgba(0,0,0,0.06)'} } }
    }
});

/* ── 7. Pie Chart: Status BTAQ Siswa ── */
const btaqChartColors = ['#d4e157', '#108c10', '#e51c23'];
new Chart(document.getElementById('chartBtaq'), {
    type: 'pie',
    data: {
        labels: btaqLabels,
        datasets: [{
            data: btaqData,
            backgroundColor: btaqChartColors,
            borderWidth: 2,
            borderColor: '#ffffff',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: tooltipStyle,
        }
    },
    plugins: [{
        id: 'datalabels',
        afterDraw(chart) {
            const { ctx } = chart;
            ctx.save();
            chart.data.datasets.forEach((dataset, i) => {
                const meta = chart.getDatasetMeta(i);
                meta.data.forEach((datapoint, index) => {
                    const total = dataset.data.reduce((a, b) => a + b, 0);
                    const value = dataset.data[index];
                    if (value === 0) return;
                    const percent = total > 0 ? ((value / total) * 100).toFixed(1) + '%' : '0%';
                    const pos = datapoint.tooltipPosition ? datapoint.tooltipPosition() : { x: datapoint.x, y: datapoint.y };
                    ctx.font = 'bold 9px "Plus Jakarta Sans", sans-serif';
                    ctx.fillStyle = '#ffffff';
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillText(percent, pos.x, pos.y);
                });
            });
            ctx.restore();
        }
    }]
});

/* ── 8. Bar Chart: Jurnal Guru per Bulan ── */
new Chart(document.getElementById('chartJurnal'), {
    type: 'bar',
    data: {
        labels: months,
        datasets: [{
            label: 'Jurnal Guru',
            data: jurnalData,
            backgroundColor: 'rgba(99, 102, 241, 0.85)',
            borderRadius: 6,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: tooltipStyle
        },
        scales: {
            x: { grid: { display: false }, border: { display: false } },
            y: { grid: { color: 'rgba(0,0,0,0.05)' }, border: { display: false }, ticks: { stepSize: 5 } }
        }
    }
});

/* ── Count-up animation for stat cards ── */
document.querySelectorAll('.stat-number[data-count]').forEach(el => {
    const target = parseInt(el.dataset.count, 10) || 0;
    if (target === 0) { el.textContent = '0'; return; }
    const duration = 1200;
    const step = Math.ceil(target / (duration / 16));
    let current = 0;
    const timer = setInterval(() => {
        current = Math.min(current + step, target);
        el.textContent = current.toLocaleString('id-ID');
        if (current >= target) clearInterval(timer);
    }, 16);
});

/* ── Animate progress bars ── */
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(() => {
        document.querySelectorAll('.progress-bar[data-target]').forEach(bar => {
            bar.style.width = bar.dataset.target + '%';
        });
    }, 400);
});

/* ════════════════════════════════════════════════════════
   GAYA BELAJAR CHARTS
════════════════════════════════════════════════════════ */
const gayaTypes      = @json($gayaTypes);
const gayaLabels     = @json($gayaLabels);
const gayaColors     = ['#4f46e5', '#10b981', '#f59e0b', '#ec4899'];
const gayaColorsAlpha = ['rgba(79,70,229,0.85)','rgba(16,185,129,0.85)','rgba(245,158,11,0.85)','rgba(236,72,153,0.85)'];

const gayaOverallData    = @json($gayaOverallData);
const gayaTingkatLabels  = @json($gayaPerTingkatLabels);
const gayaTingkatData    = @json($gayaPerTingkatData);
const gayaJurusanLabels  = @json($gayaPerJurusanLabels);
const gayaJurusanData    = @json($gayaPerJurusanData);
const gayaKelasLabels    = @json($gayaPerKelasLabels);
const gayaKelasData      = @json($gayaPerKelasData);

function buildGayaDatasets(dataObj) {
    return gayaTypes.map((t, i) => ({
        label: gayaLabels[i],
        data: dataObj[t] || [],
        backgroundColor: gayaColorsAlpha[i],
        borderRadius: 4,
        borderSkipped: false,
        stack: 'gaya',
    }));
}

const gayaStackedOptions = (horizontal = false) => ({
    responsive: true,
    maintainAspectRatio: false,
    indexAxis: horizontal ? 'y' : 'x',
    plugins: {
        legend: { position: 'bottom', labels: { padding: 14, usePointStyle: true, pointStyleWidth: 10, boxHeight: 8 } },
        tooltip: { ...tooltipStyle, mode: 'index', intersect: false }
    },
    scales: {
        x: { stacked: true, grid: { display: horizontal }, border: { display: false },
             ticks: horizontal ? { stepSize: 1 } : {} },
        y: { stacked: true, grid: { color: horizontal ? 'rgba(0,0,0,0)' : 'rgba(0,0,0,0.05)' }, border: { display: false },
             ticks: horizontal ? {} : { stepSize: 1 } }
    },
    interaction: { mode: 'index', intersect: false }
});

/* ── G1. Doughnut: Distribusi Keseluruhan ── */
const hasGayaData = gayaOverallData.some(v => v > 0);
new Chart(document.getElementById('chartGayaOverall'), {
    type: 'doughnut',
    data: {
        labels: gayaLabels,
        datasets: [{
            data: hasGayaData ? gayaOverallData : [1, 0, 0, 0],
            backgroundColor: gayaColors,
            borderWidth: 3,
            borderColor: '#ffffff',
            hoverBorderWidth: 4,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        cutout: '65%',
        plugins: {
            legend: { display: false },
            tooltip: { ...tooltipStyle, enabled: hasGayaData }
        }
    }
});

/* ── G2. Stacked Bar: Per Tingkat ── */
if (gayaTingkatLabels.length) {
    new Chart(document.getElementById('chartGayaTingkat'), {
        type: 'bar',
        data: { labels: gayaTingkatLabels, datasets: buildGayaDatasets(gayaTingkatData) },
        options: gayaStackedOptions(false),
    });
} else {
    const ctx = document.getElementById('chartGayaTingkat').getContext('2d');
    ctx.fillStyle = '#94a3b8'; ctx.font = '13px sans-serif'; ctx.textAlign = 'center';
    ctx.fillText('Belum ada data gaya belajar', ctx.canvas.width / 2, 80);
}

/* ── G3. Stacked Bar: Per Jurusan ── */
if (gayaJurusanLabels.length) {
    new Chart(document.getElementById('chartGayaJurusan'), {
        type: 'bar',
        data: { labels: gayaJurusanLabels, datasets: buildGayaDatasets(gayaJurusanData) },
        options: gayaStackedOptions(false),
    });
} else {
    const ctx = document.getElementById('chartGayaJurusan').getContext('2d');
    ctx.fillStyle = '#94a3b8'; ctx.font = '13px sans-serif'; ctx.textAlign = 'center';
    ctx.fillText('Belum ada data gaya belajar', ctx.canvas.width / 2, 80);
}

/* ── G4. Horizontal Stacked Bar: Per Kelas ── */
if (gayaKelasLabels.length) {
    const kelasCount = gayaKelasLabels.length;
    const kelasHeight = Math.max(200, kelasCount * 34 + 80);
    const wrap = document.getElementById('chartGayaKelasWrap');
    if (wrap) wrap.style.height = kelasHeight + 'px';

    new Chart(document.getElementById('chartGayaKelas'), {
        type: 'bar',
        data: { labels: gayaKelasLabels, datasets: buildGayaDatasets(gayaKelasData) },
        options: gayaStackedOptions(true),
    });
} else {
    const ctx = document.getElementById('chartGayaKelas').getContext('2d');
    ctx.fillStyle = '#94a3b8'; ctx.font = '13px sans-serif'; ctx.textAlign = 'center';
    ctx.fillText('Belum ada data gaya belajar', ctx.canvas.width / 2, 80);
}
</script>

@endsection
