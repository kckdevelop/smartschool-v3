@extends('layouts.app')

@section('title', 'Dashboard BK — SmartSchool')
@section('header_title', 'Dashboard Guru BK')
@section('header_subtitle', 'Ringkasan dan statistik pengelolaan bimbingan dan konseling siswa')

@push('styles')
<style>
    /* Custom styles for BK Dashboard */
    .bk-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .bk-stat-card {
        border-radius: 16px;
        padding: 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .bk-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .bk-stat-icon {
        position: absolute;
        right: 20px;
        bottom: 20px;
        font-size: 4rem;
        opacity: 0.18;
        pointer-events: none;
        transition: transform 0.3s ease;
    }

    .bk-stat-card:hover .bk-stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .bk-stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 8px;
    }

    .bk-stat-label {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        opacity: 0.85;
    }

    .bk-stat-meta {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 0.82rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .bk-stat-meta span {
        opacity: 0.9;
        font-weight: 500;
    }

    .bk-charts-grid {
        display: grid;
        grid-template-columns: 1.6fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 1024px) {
        .bk-charts-grid {
            grid-template-columns: 1fr;
        }
    }

    .bk-info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 992px) {
        .bk-info-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Tabs Styling */
    .tab-container {
        background: var(--card-bg, #fff);
        border-radius: 12px;
        border: 1.5px solid var(--border-color, #e2e8f0);
        margin-bottom: 24px;
    }

    .tab-header {
        display: flex;
        border-bottom: 1.5px solid var(--border-color, #e2e8f0);
        background: #f8fafc;
        border-radius: 12px 12px 0 0;
        padding: 0 16px;
    }

    .tab-btn {
        padding: 16px 20px;
        font-weight: 700;
        font-size: 0.92rem;
        color: var(--text-muted, #64748b);
        border: none;
        background: none;
        cursor: pointer;
        position: relative;
        transition: color 0.2s;
    }

    .tab-btn:hover {
        color: var(--color-primary, #7c3aed);
    }

    .tab-btn.active {
        color: var(--color-primary, #7c3aed);
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1.5px;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--color-primary, #7c3aed);
        border-radius: 3px 3px 0 0;
    }

    .tab-content {
        display: none;
        padding: 0;
    }

    .tab-content.active {
        display: block;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    .status-badge.proses {
        background: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
    }

    .status-badge.selesai {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .status-badge.belum_hadir {
        background: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }

    .status-badge.sudah_hadir {
        background: #f0fdf4;
        color: #15803d;
        border: 1px solid #bbf7d0;
    }

    .status-badge.tidak_hadir {
        background: #f5f5f5;
        color: #737373;
        border: 1px solid #e5e5e5;
    }

    .status-badge.dijadwalkan {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }

    .status-badge.batal {
        background: #f5f5f5;
        color: #737373;
        border: 1px solid #e5e5e5;
    }
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Banner Selamat Datang BK --}}
    <div style="background: linear-gradient(135deg, #7c3aed, #4f46e5); border-radius: 16px; padding: 22px 28px; color: #fff; margin-bottom: 24px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; box-shadow: 0 4px 20px rgba(124, 58, 237, 0.15);">
        <div>
            <div style="font-size:.8rem; opacity:.85; font-weight:600; text-transform:uppercase; letter-spacing:.8px;">Bimbingan & Konseling</div>
            <div style="font-size:1.45rem; font-weight:800; margin-top:4px;">Panel Pemantauan Guru BK</div>
            <div style="font-size:.88rem; opacity:.9; margin-top:4px;">
                Tahun Ajaran Aktif: <strong style="color:#d9f99d;">{{ $tahunAjaranNama }}</strong> &nbsp;·&nbsp; Memantau perkembangan kedisiplinan dan kesejahteraan siswa
            </div>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('bk.catat-pelanggaran.index') }}" class="btn btn-sm" style="background:#ffffff25; color:#fff; border:1.5px solid #ffffff40; backdrop-filter:blur(4px);">
                <i class="fa-solid fa-circle-plus"></i> Catat Pelanggaran
            </a>
            <a href="{{ route('bk.buku-konsultasi.index') }}" class="btn btn-sm" style="background:#ffffff25; color:#fff; border:1.5px solid #ffffff40; backdrop-filter:blur(4px);">
                <i class="fa-solid fa-comments"></i> Konsultasi Baru
            </a>
        </div>
    </div>

    {{-- ══════════════════════ STAT CARDS ══════════════════════ --}}
    <div class="bk-stats-grid">
        
        {{-- Card 1: Pelanggaran --}}
        <div class="bk-stat-card" style="background: linear-gradient(135deg, #ef4444, #b91c1c);">
            <div class="bk-stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div class="bk-stat-number">{{ number_format($sumPelanggaranPoin, 0, ',', '.') }}</div>
            <div class="bk-stat-label">Poin Pelanggaran</div>
            <div class="bk-stat-meta">
                <span>Total Insiden: <strong>{{ $countPelanggaran }}</strong></span>
                <a href="{{ route('bk.catat-pelanggaran.index') }}" style="color:#fff; text-decoration:underline; font-weight:600;">Detail <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i></a>
            </div>
        </div>

        {{-- Card 2: Reward --}}
        <div class="bk-stat-card" style="background: linear-gradient(135deg, #10b981, #047857);">
            <div class="bk-stat-icon"><i class="fa-solid fa-award"></i></div>
            <div class="bk-stat-number">{{ number_format($sumRewardPoin, 0, ',', '.') }}</div>
            <div class="bk-stat-label">Poin Penghargaan</div>
            <div class="bk-stat-meta">
                <span>Total Reward: <strong>{{ $countReward }}</strong></span>
                <a href="{{ route('bk.catat-reward.index') }}" style="color:#fff; text-decoration:underline; font-weight:600;">Detail <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i></a>
            </div>
        </div>

        {{-- Card 3: Buku Kasus --}}
        <div class="bk-stat-card" style="background: linear-gradient(135deg, #8b5cf6, #5b21b6);">
            <div class="bk-stat-icon"><i class="fa-solid fa-folder-open"></i></div>
            <div class="bk-stat-number">{{ $countBukuKasus }}</div>
            <div class="bk-stat-label">Buku Kasus Siswa</div>
            <div class="bk-stat-meta">
                <span>Kasus Aktif (Proses): <strong style="background:#fff; color:#5b21b6; padding:1px 6px; border-radius:10px;">{{ $countBukuKasusProses }}</strong></span>
                <a href="{{ route('bk.buku-kasus.index') }}" style="color:#fff; text-decoration:underline; font-weight:600;">Detail <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i></a>
            </div>
        </div>

        {{-- Card 4: Buku Konsultasi --}}
        <div class="bk-stat-card" style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);">
            <div class="bk-stat-icon"><i class="fa-solid fa-comments"></i></div>
            <div class="bk-stat-number">{{ $countBukuKonsultasi }}</div>
            <div class="bk-stat-label">Bimbingan & Konseling</div>
            <div class="bk-stat-meta">
                <span>Sesi Berjalan: <strong style="background:#fff; color:#1d4ed8; padding:1px 6px; border-radius:10px;">{{ $countBukuKonsultasiProses }}</strong></span>
                <a href="{{ route('bk.buku-konsultasi.index') }}" style="color:#fff; text-decoration:underline; font-weight:600;">Detail <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i></a>
            </div>
        </div>

        {{-- Card 5: Panggilan Ortu --}}
        <div class="bk-stat-card" style="background: linear-gradient(135deg, #ec4899, #be185d);">
            <div class="bk-stat-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
            <div class="bk-stat-number">{{ $countPanggilOrtu }}</div>
            <div class="bk-stat-label">Panggilan Orang Tua</div>
            <div class="bk-stat-meta">
                <span>Belum Hadir: <strong style="background:#fff; color:#be185d; padding:1px 6px; border-radius:10px;">{{ $countPanggilOrtuBelumHadir }}</strong></span>
                <a href="{{ route('bk.panggil-ortu.index') }}" style="color:#fff; text-decoration:underline; font-weight:600;">Detail <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i></a>
            </div>
        </div>

        {{-- Card 6: Home Visit --}}
        <div class="bk-stat-card" style="background: linear-gradient(135deg, #f59e0b, #b45309);">
            <div class="bk-stat-icon"><i class="fa-solid fa-house-chimney-user"></i></div>
            <div class="bk-stat-number">{{ $countHomeVisit }}</div>
            <div class="bk-stat-label">Home Visit (Kunjungan)</div>
            <div class="bk-stat-meta">
                <span>Dijadwalkan: <strong style="background:#fff; color:#b45309; padding:1px 6px; border-radius:10px;">{{ $countHomeVisitDijadwalkan }}</strong></span>
                <a href="{{ route('bk.home-visit.index') }}" style="color:#fff; text-decoration:underline; font-weight:600;">Detail <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i></a>
            </div>
        </div>

    </div>

    {{-- ══════════════════════ CHARTS ROW ══════════════════════ --}}
    <div class="bk-charts-grid">
        
        {{-- Chart 1: Tren Bulanan --}}
        <div class="card" style="margin-bottom:0;">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa-solid fa-chart-line" style="color:var(--color-primary);"></i> 
                    Tren Insiden Pelanggaran & Penerimaan Reward
                </h2>
                <span class="badge badge-info">T.A. {{ $tahunAjaranNama }}</span>
            </div>
            <div class="card-body">
                <div style="position:relative; height:280px; width:100%;">
                    <canvas id="chartTrenBulanan"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart 2: Top Pelanggaran --}}
        <div class="card" style="margin-bottom:0;">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa-solid fa-chart-pie" style="color:var(--color-primary);"></i> 
                    Top 5 Jenis Pelanggaran Terbanyak
                </h2>
            </div>
            <div class="card-body">
                <div style="position:relative; height:280px; width:100%;">
                    <canvas id="chartKategoriPelanggaran"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════ CHARTS ROW 2: STATUS BREAKDOWN ══════════════════════ --}}
    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px; margin-bottom:24px;">
        
        {{-- Status Buku Kasus --}}
        <div class="card" style="margin-bottom:0; padding:20px;">
            <div class="card-header" style="border-bottom:none; padding-bottom:0;">
                <h2 class="card-title" style="font-size:0.95rem;"><i class="fa-solid fa-shield-halved" style="color:#8b5cf6;"></i> Status Penyelesaian Kasus Siswa</h2>
            </div>
            <div class="card-body" style="display:flex; align-items:center; gap:30px; padding:10px 0 0;">
                <div style="position:relative; width:130px; height:130px; flex-shrink:0;">
                    <canvas id="chartStatusKasus"></canvas>
                </div>
                <div style="flex:1; display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.85rem;">
                        <span style="font-weight:600; color:var(--text-primary);"><i class="fa-solid fa-circle" style="color:#f59e0b; font-size:0.6rem; margin-right:6px;"></i> Kasus Sedang Proses</span>
                        <span style="font-weight:700; color:#b45309;">{{ $statusKasus['proses'] }} kasus</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.85rem;">
                        <span style="font-weight:600; color:var(--text-primary);"><i class="fa-solid fa-circle" style="color:#10b981; font-size:0.6rem; margin-right:6px;"></i> Kasus Selesai</span>
                        <span style="font-weight:700; color:#047857;">{{ $statusKasus['selesai'] }} kasus</span>
                    </div>
                    <div style="border-top:1px solid var(--border-color); padding-top:8px; display:flex; justify-content:space-between; font-size:0.8rem; color:var(--text-muted);">
                        <span>Total Buku Kasus:</span>
                        <strong style="color:var(--text-primary);">{{ $countBukuKasus }} kasus</strong>
                    </div>
                </div>
            </div>
        </div>

        {{-- Status Konsultasi --}}
        <div class="card" style="margin-bottom:0; padding:20px;">
            <div class="card-header" style="border-bottom:none; padding-bottom:0;">
                <h2 class="card-title" style="font-size:0.95rem;"><i class="fa-solid fa-comments" style="color:#3b82f6;"></i> Status Bimbingan & Konseling</h2>
            </div>
            <div class="card-body" style="display:flex; align-items:center; gap:30px; padding:10px 0 0;">
                <div style="position:relative; width:130px; height:130px; flex-shrink:0;">
                    <canvas id="chartStatusKonsultasi"></canvas>
                </div>
                <div style="flex:1; display:flex; flex-direction:column; gap:10px;">
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.85rem;">
                        <span style="font-weight:600; color:var(--text-primary);"><i class="fa-solid fa-circle" style="color:#3b82f6; font-size:0.6rem; margin-right:6px;"></i> Sesi Sedang Proses</span>
                        <span style="font-weight:700; color:#1d4ed8;">{{ $statusKonsultasi['proses'] }} sesi</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.85rem;">
                        <span style="font-weight:600; color:var(--text-primary);"><i class="fa-solid fa-circle" style="color:#10b981; font-size:0.6rem; margin-right:6px;"></i> Sesi Selesai</span>
                        <span style="font-weight:700; color:#047857;">{{ $statusKonsultasi['selesai'] }} sesi</span>
                    </div>
                    <div style="border-top:1px solid var(--border-color); padding-top:8px; display:flex; justify-content:space-between; font-size:0.8rem; color:var(--text-muted);">
                        <span>Total Sesi Konsultasi:</span>
                        <strong style="color:var(--text-primary);">{{ $countBukuKonsultasi }} sesi</strong>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════ INFO GRID: TOP SISWA ══════════════════════ --}}
    <div class="bk-info-grid">
        
        {{-- Top Siswa Pelanggaran --}}
        <div class="card" style="margin-bottom:0;">
            <div class="card-header">
                <h2 class="card-title" style="color:#ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Siswa Butuh Perhatian (Poin Tertinggi)</h2>
            </div>
            <div class="card-body p-0">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th style="text-align:center;">Kasus</th>
                            <th style="text-align:center; width:100px;">Akumulasi Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSiswaPelanggaran as $index => $item)
                        <tr>
                            <td><strong>{{ $index + 1 }}</strong></td>
                            <td>
                                <div style="font-weight:700; color:var(--text-primary);">{{ $item->siswa->nama_siswa ?? $item->nis }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">NIS: {{ $item->nis }}</div>
                            </td>
                            <td>
                                @if($item->siswa && $item->siswa->kelas)
                                    <span class="badge" style="background:var(--color-primary-light); color:var(--color-primary);">
                                        {{ $item->siswa->kelas->nama_kelas }}
                                    </span>
                                @else
                                    <span class="badge">Tingkat {{ $item->tingkat }}</span>
                                @endif
                            </td>
                            <td style="text-align:center;">{{ $item->total_kasus }} kali</td>
                            <td style="text-align:center;">
                                <span class="badge badge-danger" style="font-weight:800; font-size:0.88rem; padding:4px 10px;">
                                    -{{ $item->total_poin }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">Belum ada data pelanggaran</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Top Siswa Reward --}}
        <div class="card" style="margin-bottom:0;">
            <div class="card-header">
                <h2 class="card-title" style="color:#10b981;"><i class="fa-solid fa-award"></i> Siswa Berprestasi (Poin Reward Tertinggi)</h2>
            </div>
            <div class="card-body p-0">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th style="text-align:center;">Reward</th>
                            <th style="text-align:center; width:100px;">Akumulasi Poin</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topSiswaReward as $index => $item)
                        <tr>
                            <td><strong>{{ $index + 1 }}</strong></td>
                            <td>
                                <div style="font-weight:700; color:var(--text-primary);">{{ $item->siswa->nama_siswa ?? $item->nis }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">NIS: {{ $item->nis }}</div>
                            </td>
                            <td>
                                @if($item->siswa && $item->siswa->kelas)
                                    <span class="badge" style="background:var(--color-primary-light); color:var(--color-primary);">
                                        {{ $item->siswa->kelas->nama_kelas }}
                                    </span>
                                @else
                                    <span class="badge">Tingkat {{ $item->tingkat }}</span>
                                @endif
                            </td>
                            <td style="text-align:center;">{{ $item->total_reward }} kali</td>
                            <td style="text-align:center;">
                                <span class="badge badge-success" style="font-weight:800; font-size:0.88rem; padding:4px 10px;">
                                    +{{ $item->total_poin }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">Belum ada data reward</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- SEKSI A: ANALISIS PRESENSI SISWA SEMESTER INI             --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div style="margin-bottom:32px;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
            <div style="width:4px; height:28px; background:linear-gradient(180deg,#3b82f6,#06b6d4); border-radius:4px;"></div>
            <div>
                <div style="font-size:1.1rem; font-weight:800; color:var(--text-primary);">
                    <i class="fa-solid fa-calendar-check" style="color:#3b82f6;"></i> Analisis Presensi Siswa
                </div>
                <div style="font-size:0.8rem; color:var(--text-muted);">Semester {{ $semesterNama }} · {{ \Carbon\Carbon::parse($semAwal)->format('d M Y') }} – {{ \Carbon\Carbon::parse($semAkhir)->format('d M Y') }}</div>
            </div>
        </div>

        {{-- Mini stat cards presensi --}}
        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(160px,1fr)); gap:14px; margin-bottom:20px;">
            
            <div style="background:linear-gradient(135deg,#22c55e,#15803d); border-radius:14px; padding:18px 20px; color:#fff; position:relative; overflow:hidden; box-shadow:0 4px 16px rgba(34,197,94,.2);">
                <div style="position:absolute; right:12px; bottom:8px; font-size:3rem; opacity:.15;"><i class="fa-solid fa-user-check"></i></div>
                <div style="font-size:1.9rem; font-weight:800;">{{ number_format($presensiHadir,0,',','.') }}</div>
                <div style="font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; opacity:.9;">Total Hadir</div>
                <div style="margin-top:8px; padding-top:8px; border-top:1px solid rgba(255,255,255,.2); font-size:0.78rem; opacity:.9;">
                    Tingkat kehadiran: <strong>{{ $persenHadir }}%</strong>
                </div>
            </div>

            <div style="background:linear-gradient(135deg,#f59e0b,#b45309); border-radius:14px; padding:18px 20px; color:#fff; position:relative; overflow:hidden; box-shadow:0 4px 16px rgba(245,158,11,.2);">
                <div style="position:absolute; right:12px; bottom:8px; font-size:3rem; opacity:.15;"><i class="fa-solid fa-thermometer-half"></i></div>
                <div style="font-size:1.9rem; font-weight:800;">{{ number_format($presensiSakit,0,',','.') }}</div>
                <div style="font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; opacity:.9;">Sakit</div>
                <div style="margin-top:8px; padding-top:8px; border-top:1px solid rgba(255,255,255,.2); font-size:0.78rem; opacity:.9;">
                    {{ $totalPresensi > 0 ? round(($presensiSakit/$totalPresensi)*100,1) : 0 }}% dari total
                </div>
            </div>

            <div style="background:linear-gradient(135deg,#06b6d4,#0e7490); border-radius:14px; padding:18px 20px; color:#fff; position:relative; overflow:hidden; box-shadow:0 4px 16px rgba(6,182,212,.2);">
                <div style="position:absolute; right:12px; bottom:8px; font-size:3rem; opacity:.15;"><i class="fa-solid fa-file-signature"></i></div>
                <div style="font-size:1.9rem; font-weight:800;">{{ number_format($presensiIzin,0,',','.') }}</div>
                <div style="font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; opacity:.9;">Izin</div>
                <div style="margin-top:8px; padding-top:8px; border-top:1px solid rgba(255,255,255,.2); font-size:0.78rem; opacity:.9;">
                    {{ $totalPresensi > 0 ? round(($presensiIzin/$totalPresensi)*100,1) : 0 }}% dari total
                </div>
            </div>

            <div style="background:linear-gradient(135deg,#ef4444,#b91c1c); border-radius:14px; padding:18px 20px; color:#fff; position:relative; overflow:hidden; box-shadow:0 4px 16px rgba(239,68,68,.2);">
                <div style="position:absolute; right:12px; bottom:8px; font-size:3rem; opacity:.15;"><i class="fa-solid fa-circle-xmark"></i></div>
                <div style="font-size:1.9rem; font-weight:800;">{{ number_format($presensiAlfa,0,',','.') }}</div>
                <div style="font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; opacity:.9;">Alfa (Tanpa Keterangan)</div>
                <div style="margin-top:8px; padding-top:8px; border-top:1px solid rgba(255,255,255,.2); font-size:0.78rem; opacity:.9;">
                    {{ $totalPresensi > 0 ? round(($presensiAlfa/$totalPresensi)*100,1) : 0 }}% dari total
                </div>
            </div>

            <div style="background:linear-gradient(135deg,#8b5cf6,#5b21b6); border-radius:14px; padding:18px 20px; color:#fff; position:relative; overflow:hidden; box-shadow:0 4px 16px rgba(139,92,246,.2);">
                <div style="position:absolute; right:12px; bottom:8px; font-size:3rem; opacity:.15;"><i class="fa-solid fa-list-check"></i></div>
                <div style="font-size:1.9rem; font-weight:800;">{{ number_format($totalPresensi,0,',','.') }}</div>
                <div style="font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; opacity:.9;">Total Catatan Presensi</div>
                <div style="margin-top:8px; padding-top:8px; border-top:1px solid rgba(255,255,255,.2); font-size:0.78rem; opacity:.9;">
                    Semester {{ $semesterNama }}
                </div>
            </div>

        </div>

        {{-- Chart tren presensi + Top Alfa --}}
        <div style="display:grid; grid-template-columns:1.7fr 1fr; gap:20px;">
            <div class="card" style="margin-bottom:0;">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-chart-line" style="color:#3b82f6;"></i> Tren Kehadiran vs Alfa per Bulan</h2>
                    <span class="badge badge-info">Semester {{ $semesterNama }}</span>
                </div>
                <div class="card-body">
                    <div style="position:relative; height:240px; width:100%;">
                        <canvas id="chartPresensiTren"></canvas>
                    </div>
                </div>
            </div>
            <div class="card" style="margin-bottom:0;">
                <div class="card-header">
                    <h2 class="card-title" style="color:#ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Siswa Alfa Terbanyak</h2>
                </div>
                <div class="card-body p-0">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th style="text-align:center;">Alfa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topSiswaAlfa as $idx => $item)
                            <tr>
                                <td><strong>{{ $idx+1 }}</strong></td>
                                <td>
                                    <div style="font-weight:700; font-size:.85rem;">{{ $item->siswa->nama_siswa ?? $item->nis }}</div>
                                    <div style="font-size:.72rem; color:var(--text-muted);">NIS: {{ $item->nis }}</div>
                                </td>
                                <td>
                                    @if($item->siswa && $item->siswa->kelas)
                                        <span class="badge" style="background:var(--color-primary-light); color:var(--color-primary); font-size:.72rem;">
                                            {{ $item->siswa->kelas->nama_kelas }}
                                        </span>
                                    @else <span style="font-size:.75rem; color:var(--text-muted);">-</span>
                                    @endif
                                </td>
                                <td style="text-align:center;">
                                    <span class="badge badge-danger" style="font-weight:800;">{{ $item->total_alfa }}x</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">Tidak ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- SEKSI B: KASUS PELANGGARAN SEMESTER INI PER TINGKAT       --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div style="margin-bottom:32px;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
            <div style="width:4px; height:28px; background:linear-gradient(180deg,#ef4444,#f59e0b); border-radius:4px;"></div>
            <div>
                <div style="font-size:1.1rem; font-weight:800; color:var(--text-primary);">
                    <i class="fa-solid fa-gavel" style="color:#ef4444;"></i> Kasus Pelanggaran Semester Ini
                </div>
                <div style="font-size:0.8rem; color:var(--text-muted);">Data dari tabel Point Pelanggaran · Semester {{ $semesterNama }}</div>
            </div>
        </div>

        {{-- Summary cards pelanggaran semester --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:20px;">
            <div style="background:linear-gradient(135deg,#ef4444,#b91c1c); border-radius:14px; padding:20px 24px; color:#fff; display:flex; align-items:center; gap:20px; box-shadow:0 4px 16px rgba(239,68,68,.2);">
                <div style="font-size:2.5rem; opacity:.7;"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div>
                    <div style="font-size:2rem; font-weight:800;">{{ number_format($totalPelanggaranSemester,0,',','.') }}</div>
                    <div style="font-size:0.8rem; font-weight:700; opacity:.9;">Total Kasus Pelanggaran</div>
                    <div style="font-size:0.75rem; opacity:.8; margin-top:3px;">Semester {{ $semesterNama }}</div>
                </div>
            </div>
            <div style="background:linear-gradient(135deg,#f59e0b,#b45309); border-radius:14px; padding:20px 24px; color:#fff; display:flex; align-items:center; gap:20px; box-shadow:0 4px 16px rgba(245,158,11,.2);">
                <div style="font-size:2.5rem; opacity:.7;"><i class="fa-solid fa-minus-circle"></i></div>
                <div>
                    <div style="font-size:2rem; font-weight:800;">{{ number_format($totalPoinSemester,0,',','.') }}</div>
                    <div style="font-size:0.8rem; font-weight:700; opacity:.9;">Akumulasi Poin Dikurangi</div>
                    <div style="font-size:0.75rem; opacity:.8; margin-top:3px;">Semester {{ $semesterNama }}</div>
                </div>
            </div>
        </div>

        {{-- Chart per tingkat + Top Kategori Semester --}}
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
            
            <div class="card" style="margin-bottom:0;">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-chart-bar" style="color:#ef4444;"></i> Kasus per Tingkat & per Bulan</h2>
                </div>
                <div class="card-body">
                    {{-- Per tingkat --}}
                    <div style="margin-bottom:16px;">
                        <div style="font-size:0.78rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:10px; letter-spacing:.5px;">Distribusi per Tingkat</div>
                        @forelse($pelanggaranSemesterPerTingkat as $pt)
                        @php
                            $maxKasus = $pelanggaranSemesterPerTingkat->max('total_kasus') ?: 1;
                            $barWidth = round(($pt->total_kasus / $maxKasus) * 100);
                        @endphp
                        <div style="margin-bottom:10px;">
                            <div style="display:flex; justify-content:space-between; font-size:0.82rem; margin-bottom:4px;">
                                <span style="font-weight:700;">Tingkat {{ $pt->tingkat }}</span>
                                <span style="color:var(--text-muted);">{{ $pt->total_kasus }} kasus · <strong style="color:#ef4444;">-{{ $pt->total_poin }} poin</strong></span>
                            </div>
                            <div style="height:8px; background:var(--border-color,#e2e8f0); border-radius:4px; overflow:hidden;">
                                <div style="height:100%; width:{{ $barWidth }}%; background:linear-gradient(90deg,#ef4444,#f59e0b); border-radius:4px; transition:width .5s;"></div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center; color:var(--text-muted); padding:20px; font-size:.85rem;">Belum ada pelanggaran di semester ini</div>
                        @endforelse
                    </div>
                    {{-- Tren per bulan --}}
                    <div style="position:relative; height:160px; width:100%;">
                        <canvas id="chartPelanggaranSemester"></canvas>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-bottom:0;">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-list-ol" style="color:#f59e0b;"></i> Top Jenis Pelanggaran Semester Ini</h2>
                </div>
                <div class="card-body p-0">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width:40px;">#</th>
                                <th>Jenis Pelanggaran</th>
                                <th style="text-align:center; width:80px;">Kasus</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topKategoriSemester as $idx => $item)
                            <tr>
                                <td>
                                    <span style="display:inline-flex; width:24px; height:24px; align-items:center; justify-content:center; border-radius:50%; background:{{ $idx===0?'#ef4444':($idx===1?'#f59e0b':($idx===2?'#8b5cf6':'#64748b')) }}; color:#fff; font-size:.7rem; font-weight:800;">{{ $idx+1 }}</span>
                                </td>
                                <td style="font-weight:600; font-size:.85rem;">{{ $item->pelanggaran }}</td>
                                <td style="text-align:center;">
                                    <span class="badge badge-danger" style="font-weight:800;">{{ $item->total }}x</span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">Belum ada data</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- SEKSI C: STATISTIK GAYA BELAJAR PER TINGKAT               --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div style="margin-bottom:32px;">
        <div style="display:flex; align-items:center; gap:12px; margin-bottom:16px;">
            <div style="width:4px; height:28px; background:linear-gradient(180deg,#8b5cf6,#06b6d4); border-radius:4px;"></div>
            <div>
                <div style="font-size:1.1rem; font-weight:800; color:var(--text-primary);">
                    <i class="fa-solid fa-brain" style="color:#8b5cf6;"></i> Statistik Gaya Belajar Siswa
                </div>
                <div style="font-size:0.8rem; color:var(--text-muted);">Distribusi gaya belajar per tingkat berdasarkan asesmen BK</div>
            </div>
        </div>

        <div style="display:grid; grid-template-columns:1.8fr 1fr; gap:20px;">

            {{-- Stacked bar chart per tingkat --}}
            <div class="card" style="margin-bottom:0;">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-chart-bar" style="color:#8b5cf6;"></i> Distribusi Gaya Belajar per Tingkat</h2>
                </div>
                <div class="card-body">
                    @if(count($gayaBelajarPerTingkat) > 0)
                    {{-- Legend --}}
                    <div style="display:flex; flex-wrap:wrap; gap:14px; margin-bottom:16px;">
                        <span style="display:flex; align-items:center; gap:6px; font-size:0.8rem; font-weight:600;"><span style="width:12px; height:12px; border-radius:3px; background:#3b82f6; display:inline-block;"></span>Visual</span>
                        <span style="display:flex; align-items:center; gap:6px; font-size:0.8rem; font-weight:600;"><span style="width:12px; height:12px; border-radius:3px; background:#10b981; display:inline-block;"></span>Auditori</span>
                        <span style="display:flex; align-items:center; gap:6px; font-size:0.8rem; font-weight:600;"><span style="width:12px; height:12px; border-radius:3px; background:#f59e0b; display:inline-block;"></span>Kinestetik</span>
                        <span style="display:flex; align-items:center; gap:6px; font-size:0.8rem; font-weight:600;"><span style="width:12px; height:12px; border-radius:3px; background:#8b5cf6; display:inline-block;"></span>Campuran</span>
                    </div>
                    @foreach($gayaBelajarPerTingkat as $gb)
                    @php
                        $gbTotal = max($gb['total'], 1);
                        $gbVisualW     = round(($gb['visual']     / $gbTotal) * 100);
                        $gbAuditoriW   = round(($gb['auditori']   / $gbTotal) * 100);
                        $gbKinestetikW = round(($gb['kinestetik'] / $gbTotal) * 100);
                        $gbCampuranW   = max(0, 100 - $gbVisualW - $gbAuditoriW - $gbKinestetikW);
                    @endphp
                    <div style="margin-bottom:14px;">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:5px;">
                            <span style="font-weight:700; font-size:0.85rem;">Tingkat {{ $gb['tingkat'] }}</span>
                            <span style="font-size:0.75rem; color:var(--text-muted);">{{ $gb['total'] }} siswa terdata</span>
                        </div>
                        @if($gb['total'] > 0)
                        <div style="display:flex; height:20px; border-radius:6px; overflow:hidden; gap:1px;">
                            @if($gbVisualW > 0)
                            <div style="width:{{ $gbVisualW }}%; background:#3b82f6; display:flex; align-items:center; justify-content:center;" title="Visual: {{ $gb['visual'] }}">
                                @if($gbVisualW > 8)<span style="font-size:.65rem; font-weight:700; color:#fff;">{{ $gb['visual'] }}</span>@endif
                            </div>
                            @endif
                            @if($gbAuditoriW > 0)
                            <div style="width:{{ $gbAuditoriW }}%; background:#10b981; display:flex; align-items:center; justify-content:center;" title="Auditori: {{ $gb['auditori'] }}">
                                @if($gbAuditoriW > 8)<span style="font-size:.65rem; font-weight:700; color:#fff;">{{ $gb['auditori'] }}</span>@endif
                            </div>
                            @endif
                            @if($gbKinestetikW > 0)
                            <div style="width:{{ $gbKinestetikW }}%; background:#f59e0b; display:flex; align-items:center; justify-content:center;" title="Kinestetik: {{ $gb['kinestetik'] }}">
                                @if($gbKinestetikW > 8)<span style="font-size:.65rem; font-weight:700; color:#fff;">{{ $gb['kinestetik'] }}</span>@endif
                            </div>
                            @endif
                            @if($gbCampuranW > 0)
                            <div style="width:{{ $gbCampuranW }}%; background:#8b5cf6; display:flex; align-items:center; justify-content:center;" title="Campuran: {{ $gb['campuran'] }}">
                                @if($gbCampuranW > 8)<span style="font-size:.65rem; font-weight:700; color:#fff;">{{ $gb['campuran'] }}</span>@endif
                            </div>
                            @endif
                        </div>
                        <div style="display:flex; gap:10px; margin-top:4px; flex-wrap:wrap;">
                            @if($gb['visual']>0)<span style="font-size:.7rem; color:#3b82f6;">Visual: {{ $gb['visual'] }}</span>@endif
                            @if($gb['auditori']>0)<span style="font-size:.7rem; color:#10b981;">Auditori: {{ $gb['auditori'] }}</span>@endif
                            @if($gb['kinestetik']>0)<span style="font-size:.7rem; color:#f59e0b;">Kinestetik: {{ $gb['kinestetik'] }}</span>@endif
                            @if($gb['campuran']>0)<span style="font-size:.7rem; color:#8b5cf6;">Campuran: {{ $gb['campuran'] }}</span>@endif
                        </div>
                        @else
                        <div style="height:20px; background:var(--border-color,#e2e8f0); border-radius:6px;"></div>
                        <div style="font-size:.72rem; color:var(--text-muted); margin-top:3px;">Belum ada data asesmen</div>
                        @endif
                    </div>
                    @endforeach
                    @else
                    <div style="text-align:center; padding:40px; color:var(--text-muted);">
                        <i class="fa-solid fa-brain" style="font-size:2rem; opacity:.3; display:block; margin-bottom:10px;"></i>
                        Belum ada data tingkat aktif
                    </div>
                    @endif
                </div>
            </div>

            {{-- Donut + summary --}}
            <div class="card" style="margin-bottom:0;">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-chart-pie" style="color:#8b5cf6;"></i> Keseluruhan</h2>
                </div>
                <div class="card-body" style="display:flex; flex-direction:column; align-items:center; gap:16px;">
                    <div style="position:relative; width:180px; height:180px;">
                        <canvas id="chartGayaBelajarDonut"></canvas>
                        @php $gbTotalAll = array_sum($gayaBelajarTotal ?: [0]); @endphp
                        <div style="position:absolute; inset:0; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                            <div style="font-size:1.6rem; font-weight:800; color:var(--text-primary);">{{ $gbTotalAll }}</div>
                            <div style="font-size:0.7rem; color:var(--text-muted); font-weight:600;">Siswa</div>
                        </div>
                    </div>
                    <div style="width:100%; display:flex; flex-direction:column; gap:8px;">
                        @php
                            $gbItems = [
                                ['key'=>'visual',     'label'=>'Visual',     'color'=>'#3b82f6'],
                                ['key'=>'auditori',   'label'=>'Auditori',   'color'=>'#10b981'],
                                ['key'=>'kinestetik', 'label'=>'Kinestetik', 'color'=>'#f59e0b'],
                                ['key'=>'campuran',   'label'=>'Campuran',   'color'=>'#8b5cf6'],
                            ];
                        @endphp
                        @foreach($gbItems as $gbi)
                        @php $gbiCount = $gayaBelajarTotal[$gbi['key']] ?? 0; @endphp
                        <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.83rem;">
                            <span style="display:flex; align-items:center; gap:7px; font-weight:600;">
                                <span style="width:10px; height:10px; border-radius:2px; background:{{ $gbi['color'] }};"></span>
                                {{ $gbi['label'] }}
                            </span>
                            <span>
                                <strong style="color:{{ $gbi['color'] }};">{{ $gbiCount }}</strong>
                                <span style="color:var(--text-muted); font-size:.75rem;"> ({{ $gbTotalAll > 0 ? round(($gbiCount/$gbTotalAll)*100,1) : 0 }}%)</span>
                            </span>
                        </div>
                        @endforeach
                        <div style="border-top:1px solid var(--border-color); padding-top:8px; display:flex; justify-content:space-between; font-size:.8rem;">
                            <span style="color:var(--text-muted);">Total terdata:</span>
                            <strong>{{ $gbTotalAll }} siswa</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════ TAB CONTAINER: RECENT CASES & COUNSELING ══════════════════════ --}}
    <div class="tab-container">
        
        <div class="tab-header">
            <button class="tab-btn active" onclick="switchTab(event, 'tab-kasus')">
                <i class="fa-solid fa-folder-open"></i> Kasus Aktif Terbaru ({{ $recentBukuKasus->count() }})
            </button>
            <button class="tab-btn" onclick="switchTab(event, 'tab-konsultasi')">
                <i class="fa-solid fa-comments"></i> Konsultasi Aktif Terbaru ({{ $recentBimbingan->count() }})
            </button>
        </div>

        {{-- Tab Content 1: Buku Kasus --}}
        <div class="tab-content active" id="tab-kasus">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:100px;">Tanggal</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Judul Kasus</th>
                            <th>Guru Pelapor</th>
                            <th>Status</th>
                            <th style="width:100px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBukuKasus as $item)
                        <tr>
                            <td>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div style="font-weight:700; color:var(--text-primary);">{{ $item->siswa->nama_siswa ?? $item->nis }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">NIS: {{ $item->nis }}</div>
                            </td>
                            <td>
                                @if($item->siswa && $item->siswa->kelas)
                                    <span class="badge" style="background:var(--color-primary-light); color:var(--color-primary);">
                                        {{ $item->siswa->kelas->nama_kelas }}
                                    </span>
                                @endif
                            </td>
                            <td style="font-weight:600;">{{ $item->judul_kasus }}</td>
                            <td>{{ $item->guru->nama_guru ?? 'Administrator' }}</td>
                            <td>
                                <span class="status-badge {{ $item->status }}">
                                    <i class="fa-solid {{ $item->status === 'proses' ? 'fa-clock' : 'fa-check-circle' }}"></i>
                                    {{ $item->status === 'proses' ? 'Dalam Proses' : 'Selesai' }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <a href="{{ route('bk.buku-kasus.index') }}" class="btn btn-sm btn-secondary" style="padding:4px 10px; font-size:0.8rem;">
                                    Kelola
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Tidak ada kasus siswa aktif</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tab Content 2: Bimbingan & Konseling --}}
        <div class="tab-content" id="tab-konsultasi">
            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:100px;">Tanggal</th>
                            <th>Siswa</th>
                            <th>Kelas</th>
                            <th>Jenis Masalah</th>
                            <th>Guru Pembimbing</th>
                            <th>Status</th>
                            <th style="width:100px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBimbingan as $item)
                        <tr>
                            <td>{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}</td>
                            <td>
                                <div style="font-weight:700; color:var(--text-primary);">{{ $item->siswa->nama_siswa ?? $item->nis }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">NIS: {{ $item->nis }}</div>
                            </td>
                            <td>
                                @if($item->siswa && $item->siswa->kelas)
                                    <span class="badge" style="background:var(--color-primary-light); color:var(--color-primary);">
                                        {{ $item->siswa->kelas->nama_kelas }}
                                    </span>
                                @endif
                            </td>
                            <td style="font-weight:600;">{{ $item->jenis_masalah }}</td>
                            <td>{{ $item->guru->nama_guru ?? 'Administrator' }}</td>
                            <td>
                                <span class="status-badge {{ $item->status }}">
                                    <i class="fa-solid {{ $item->status === 'proses' ? 'fa-clock' : 'fa-check-circle' }}"></i>
                                    {{ $item->status === 'proses' ? 'Dalam Proses' : 'Selesai' }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <a href="{{ route('bk.buku-konsultasi.index') }}" class="btn btn-sm btn-secondary" style="padding:4px 10px; font-size:0.8rem;">
                                    Kelola
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Tidak ada sesi bimbingan aktif</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
    // Tab Switcher
    function switchTab(evt, tabId) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tab-content" and hide them
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }

        // Get all elements with class="tab-btn" and remove the class "active"
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(tabId).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    // Chart.js Configuration
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.font.size   = 11;
    Chart.defaults.color       = '#64748b';

    const tooltipStyle = {
        backgroundColor: 'rgba(15,23,42,0.92)',
        titleColor: '#f8fafc',
        bodyColor: '#cbd5e1',
        borderColor: 'rgba(255,255,255,0.1)',
        borderWidth: 1,
        padding: 12,
        cornerRadius: 10,
        boxPadding: 4,
    };

    // ── 1. Grouped Bar Chart: Tren Bulanan ──
    const ctxTren = document.getElementById('chartTrenBulanan').getContext('2d');
    new Chart(ctxTren, {
        type: 'bar',
        data: {
            labels: @json($months),
            datasets: [
                {
                    label: 'Pelanggaran',
                    data: @json($pelanggaranPerBulan),
                    backgroundColor: 'rgba(239, 68, 68, 0.85)', // Red
                    borderRadius: 6,
                    borderSkipped: false
                },
                {
                    label: 'Reward',
                    data: @json($rewardPerBulan),
                    backgroundColor: 'rgba(16, 185, 129, 0.85)', // Emerald
                    borderRadius: 6,
                    borderSkipped: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, boxHeight: 8, padding: 20 }
                },
                tooltip: tooltipStyle
            },
            scales: {
                x: { grid: { display: false } },
                y: { 
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { stepSize: 1 } 
                }
            }
        }
    });

    // ── 2. Horizontal Bar Chart: Top Pelanggaran Kategori ──
    const ctxKategori = document.getElementById('chartKategoriPelanggaran').getContext('2d');
    const topKategoriData = @json($topKategoriPelanggaran);
    
    new Chart(ctxKategori, {
        type: 'bar',
        data: {
            labels: topKategoriData.map(item => item.pelanggaran),
            datasets: [{
                label: 'Jumlah Kejadian',
                data: topKategoriData.map(item => item.total),
                backgroundColor: 'rgba(139, 92, 246, 0.85)', // Purple
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: tooltipStyle
            },
            scales: {
                x: { 
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { stepSize: 1 } 
                },
                y: { grid: { display: false } }
            }
        }
    });

    // ── 3. Doughnut Chart: Status Buku Kasus ──
    const ctxStatusKasus = document.getElementById('chartStatusKasus').getContext('2d');
    new Chart(ctxStatusKasus, {
        type: 'doughnut',
        data: {
            labels: ['Proses', 'Selesai'],
            datasets: [{
                data: [{{ $statusKasus['proses'] }}, {{ $statusKasus['selesai'] }}],
                backgroundColor: ['#f59e0b', '#10b981'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: tooltipStyle
            }
        }
    });

    // ── 4. Doughnut Chart: Status Konsultasi ──
    const ctxStatusKonsultasi = document.getElementById('chartStatusKonsultasi').getContext('2d');
    new Chart(ctxStatusKonsultasi, {
        type: 'doughnut',
        data: {
            labels: ['Proses', 'Selesai'],
            datasets: [{
                data: [{{ $statusKonsultasi['proses'] }}, {{ $statusKonsultasi['selesai'] }}],
                backgroundColor: ['#3b82f6', '#10b981'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: tooltipStyle
            }
        }
    });

    // ── 5. Line Chart: Tren Presensi (Hadir vs Alfa) Semester Ini ──
    const ctxPresensiTren = document.getElementById('chartPresensiTren').getContext('2d');
    new Chart(ctxPresensiTren, {
        type: 'line',
        data: {
            labels: @json($presensiTrenLabels),
            datasets: [
                {
                    label: 'Hadir',
                    data: @json($presensiTrenHadir),
                    borderColor: '#22c55e',
                    backgroundColor: 'rgba(34,197,94,0.12)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#22c55e',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Alfa',
                    data: @json($presensiTrenAlfa),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239,68,68,0.08)',
                    borderWidth: 2.5,
                    pointBackgroundColor: '#ef4444',
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    tension: 0.4,
                    fill: true,
                    borderDash: [5,3]
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { usePointStyle: true, boxHeight: 7, padding: 16 }
                },
                tooltip: tooltipStyle
            },
            scales: {
                x: { grid: { display: false } },
                y: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { stepSize: 1 } }
            }
        }
    });

    // ── 6. Bar Chart: Tren Pelanggaran per Bulan Semester Ini ──
    const ctxPelanggaranSemester = document.getElementById('chartPelanggaranSemester').getContext('2d');
    new Chart(ctxPelanggaranSemester, {
        type: 'bar',
        data: {
            labels: @json($presensiTrenLabels),
            datasets: [{
                label: 'Kasus Pelanggaran',
                data: @json($pelanggaranSemesterPerBulan),
                backgroundColor: 'rgba(239,68,68,0.82)',
                borderRadius: 5,
                borderSkipped: false
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
                x: { grid: { display: false } },
                y: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { stepSize: 1 } }
            }
        }
    });

    // ── 7. Doughnut Chart: Gaya Belajar Keseluruhan ──
    const ctxGayaBelajarDonut = document.getElementById('chartGayaBelajarDonut').getContext('2d');
    new Chart(ctxGayaBelajarDonut, {
        type: 'doughnut',
        data: {
            labels: ['Visual', 'Auditori', 'Kinestetik', 'Campuran'],
            datasets: [{
                data: [
                    {{ $gayaBelajarTotal['visual']     ?? 0 }},
                    {{ $gayaBelajarTotal['auditori']   ?? 0 }},
                    {{ $gayaBelajarTotal['kinestetik'] ?? 0 }},
                    {{ $gayaBelajarTotal['campuran']   ?? 0 }}
                ],
                backgroundColor: ['#3b82f6', '#10b981', '#f59e0b', '#8b5cf6'],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '68%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    ...tooltipStyle,
                    callbacks: {
                        label: function(ctx) {
                            const total = ctx.dataset.data.reduce((a,b) => a+b, 0);
                            const pct = total > 0 ? Math.round((ctx.parsed / total) * 100) : 0;
                            return ` ${ctx.label}: ${ctx.parsed} siswa (${pct}%)`;
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
@endsection
