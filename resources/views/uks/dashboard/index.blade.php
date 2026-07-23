@extends('layouts.app')

@section('title', 'Dashboard UKS — SmartSchool')
@section('header_title', 'Dashboard UKS')
@section('header_subtitle', 'Statistik dan analisis data kesehatan & kunjungan')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Global Tabs Switcher --}}
    <div class="dashboard-tabs">
        <button class="tab-button active" onclick="switchTab(event, 'tab-siswa')">
            <i class="fa-solid fa-graduation-cap"></i> Analisis Kesehatan Siswa
        </button>
        <button class="tab-button" onclick="switchTab(event, 'tab-gukar')">
            <i class="fa-solid fa-users-gear"></i> Analisis Kesehatan Guru & Karyawan
        </button>
    </div>

    {{-- ══════════════════════ TAB 1: KESEHATAN SISWA ══════════════════════ --}}
    <div id="tab-siswa" class="tab-content active">
        {{-- Stats Cards Row --}}
        <div class="stats-grid">
            <div class="stat-card-premium" style="background: linear-gradient(135deg, #0284c7, #0ea5e9);">
                <div class="stat-icon-premium"><i class="fa-solid fa-users"></i></div>
                <div class="stat-info-premium">
                    <span class="stat-num-premium">{{ number_format($totalSiswa) }}</span>
                    <span class="stat-lbl-premium">Total Siswa Terdaftar</span>
                </div>
            </div>
            <div class="stat-card-premium" style="background: linear-gradient(135deg, #0d9488, #14b8a6);">
                <div class="stat-icon-premium"><i class="fa-solid fa-heart-pulse"></i></div>
                <div class="stat-info-premium">
                    <span class="stat-num-premium">{{ number_format($totalDiperiksa) }}</span>
                    <span class="stat-lbl-premium">Siswa Sudah Diperiksa</span>
                </div>
            </div>
            <div class="stat-card-premium" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                <div class="stat-icon-premium"><i class="fa-solid fa-user-slash"></i></div>
                <div class="stat-info-premium">
                    <span class="stat-num-premium">{{ number_format($totalBelumDiperiksa) }}</span>
                    <span class="stat-lbl-premium">Siswa Belum Diperiksa</span>
                </div>
            </div>
            <div class="stat-card-premium" style="background: linear-gradient(135deg, #4f46e5, #6366f1);">
                <div class="stat-icon-premium"><i class="fa-solid fa-hospital-user"></i></div>
                <div class="stat-info-premium">
                    <span class="stat-num-premium">{{ number_format($totalKunjungan) }}</span>
                    <span class="stat-lbl-premium">Total Kunjungan UKS</span>
                </div>
            </div>
        </div>

        {{-- Main Chart: Siswa per Tingkat --}}
        <div class="card" style="margin-bottom: 20px;">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-chart-bar" style="color:var(--color-primary, #0ea5e9);"></i> Statistik Pemeriksaan Siswa per Tingkat</h2>
            </div>
            <div class="card-body">
                <div style="position: relative; height: 320px; width: 100%;">
                    <canvas id="chartTingkatSiswa"></canvas>
                </div>
            </div>
        </div>

        {{-- Category breakdown per Tingkat --}}
        <h3 style="margin-top: 30px; margin-bottom: 15px; font-weight: 700; color: #1e293b; font-size: 1.25rem;">
            <i class="fa-solid fa-notes-medical" style="color:var(--color-primary, #0ea5e9); margin-right: 6px;"></i> Distribusi Kategori Status Gizi per Tingkat
        </h3>

        <div class="dashboard-grid">
            @foreach($allTingkat as $t)
                @php
                    $hasData = array_sum($kategoriPerTingkat[$t]) > 0;
                @endphp
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">Tingkat {{ $t }}</h2>
                        <span class="badge badge-muted">Diperiksa: {{ $diperiksaPerTingkat[$t] }} / {{ $siswaPerTingkat[$t] }}</span>
                    </div>
                    <div class="card-body" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 250px;">
                        @if($hasData)
                            <div style="position: relative; height: 180px; width: 100%;">
                                <canvas id="chartKategoriTingkat{{ $t }}"></canvas>
                            </div>
                        @else
                            <div style="text-align: center; color: #94a3b8; padding: 20px;">
                                <i class="fa-solid fa-file-invoice" style="font-size: 2.5rem; opacity: 0.3; margin-bottom: 12px; display: block;"></i>
                                <span>Belum ada data pemeriksaan</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Grafik Kunjungan + Keluhan Terbanyak --}}
        <div style="margin-top: 20px;">

            {{-- Filter Semester --}}
            <div style="display:flex; align-items:center; gap:10px; margin-bottom:14px; flex-wrap:wrap;">
                <span style="font-weight:600; color:var(--text-primary); font-size:0.9rem;">
                    <i class="fa-solid fa-filter" style="color:#0ea5e9;"></i> Filter Semester:
                </span>
                <form method="GET" action="{{ route('uks.dashboard') }}" id="formFilterSemester" style="display:inline;">
                    <select name="semester_id" id="selectSemester"
                        class="form-control form-control-sm"
                        style="min-width:260px; font-weight:600;"
                        onchange="document.getElementById('formFilterSemester').submit()">
                        @foreach($semuaSemester as $sem)
                            <option value="{{ $sem->id_semester }}"
                                {{ $sem->id_semester == $selectedSemesterId ? 'selected' : '' }}>
                                Semester {{ $sem->semester }} — {{ $sem->tahunAjaran->tahun ?? '' }}
                                ({{ \Carbon\Carbon::parse($sem->awal)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($sem->akhir)->format('d M Y') }})
                                @if($sem->status === 'aktif') ★ Aktif @endif
                            </option>
                        @endforeach
                    </select>
                </form>
                <span style="font-size:0.8rem; color:var(--text-muted);">
                    {{ $semStart->format('d M Y') }} s/d {{ $semEnd->format('d M Y') }}
                </span>
            </div>

            {{-- Grid Grafik + Keluhan --}}
            <div class="kunjungan-chart-grid" style="display: grid; grid-template-columns: 1fr 380px; gap: 20px;">

                {{-- Line Chart: Kunjungan per Bulan --}}
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fa-solid fa-chart-line" style="color:#0ea5e9;"></i>
                            Grafik Kunjungan &mdash; {{ $semLabel }}
                        </h2>
                    </div>
                    <div class="card-body">
                        <div style="position: relative; height: 240px; width: 100%;">
                            <canvas id="chartKunjunganBulanan"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Keluhan Terbanyak --}}
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">
                            <i class="fa-solid fa-ranking-star" style="color:#f97316;"></i> Keluhan Terbanyak
                        </h2>
                    </div>
                    <div class="card-body" style="padding: 0;">
                        @if($keluhanTerbanyak->isEmpty())
                            <div style="text-align:center; padding: 40px 20px; color: var(--text-muted);">
                                <i class="fa-solid fa-file-circle-xmark" style="font-size:2rem; opacity:0.3; display:block; margin-bottom:8px;"></i>
                                Belum ada data kunjungan
                            </div>
                        @else
                            <table style="width:100%; border-collapse:collapse; font-size:0.85rem;">
                                <thead>
                                    <tr style="background: var(--bg-secondary, #f8fafc); border-bottom: 2px solid var(--border-color, #e2e8f0);">
                                        <th style="padding: 10px 16px; text-align:left; font-weight:600; color:var(--text-muted); font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em;">Keluhan</th>
                                        <th style="padding: 10px 16px; text-align:right; font-weight:600; color:var(--text-muted); font-size:0.72rem; text-transform:uppercase; letter-spacing:0.05em;">Frekuensi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($keluhanTerbanyak as $i => $k)
                                    <tr style="border-bottom: 1px solid var(--border-color, #f1f5f9); transition: background 0.15s;" onmouseover="this.style.background='var(--bg-secondary, #f8fafc)'" onmouseout="this.style.background=''">
                                        <td style="padding: 10px 16px; color: var(--text-primary);">
                                            <span style="display:inline-flex; align-items:center; gap:8px;">
                                                <span style="width:22px; height:22px; border-radius:50%; background:{{ $i === 0 ? '#f59e0b' : ($i === 1 ? '#94a3b8' : ($i === 2 ? '#f97316' : '#e2e8f0')) }}; display:inline-flex; align-items:center; justify-content:center; font-size:0.65rem; font-weight:700; color:{{ $i <= 2 ? '#fff' : '#64748b' }};">{{ $i + 1 }}</span>
                                                {{ ucfirst($k->keluhan_norm) }}
                                            </span>
                                        </td>
                                        <td style="padding: 10px 16px; text-align:right;">
                                            <span class="badge badge-info" style="font-size:0.75rem;">{{ $k->frekuensi }} kali</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════ TAB 2: KESEHATAN GUKAR ══════════════════════ --}}
    <div id="tab-gukar" class="tab-content">
        {{-- Stats Cards Row --}}
        <div class="stats-grid">
            <div class="stat-card-premium" style="background: linear-gradient(135deg, #0284c7, #0ea5e9);">
                <div class="stat-icon-premium"><i class="fa-solid fa-users-gear"></i></div>
                <div class="stat-info-premium">
                    <span class="stat-num-premium">{{ number_format($totalGukar) }}</span>
                    <span class="stat-lbl-premium">Total Gukar Terdaftar</span>
                </div>
            </div>
            <div class="stat-card-premium" style="background: linear-gradient(135deg, #0d9488, #14b8a6);">
                <div class="stat-icon-premium"><i class="fa-solid fa-clipboard-check"></i></div>
                <div class="stat-info-premium">
                    <span class="stat-num-premium">{{ number_format($totalGukarDiperiksa) }}</span>
                    <span class="stat-lbl-premium">Gukar Sudah Diperiksa</span>
                </div>
            </div>
            <div class="stat-card-premium" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                <div class="stat-icon-premium"><i class="fa-solid fa-heart-crack"></i></div>
                <div class="stat-info-premium">
                    <span class="stat-num-premium">{{ number_format($totalGukarBelumDiperiksa) }}</span>
                    <span class="stat-lbl-premium">Gukar Belum Diperiksa</span>
                </div>
            </div>
        </div>

        @php
            $hasGukarData = $totalGukarDiperiksa > 0;
        @endphp

        @if($hasGukarData)
            <div class="dashboard-grid" style="grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));">
                
                {{-- Chart 1: IMT (Status Gizi) --}}
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fa-solid fa-weight-scale" style="color:#0ea5e9;"></i> Status Gizi (IMT / BMI)</h2>
                    </div>
                    <div class="card-body" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 280px;">
                        <div style="position: relative; height: 230px; width: 100%;">
                            <canvas id="chartGukarIMT"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Chart 2: Tekanan Darah --}}
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fa-solid fa-gauge-high" style="color:#ef4444;"></i> Status Tekanan Darah (Hipertensi)</h2>
                    </div>
                    <div class="card-body" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 280px;">
                        <div style="position: relative; height: 230px; width: 100%;">
                            <canvas id="chartGukarTekananDarah"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Chart 3: Gula Darah --}}
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fa-solid fa-vial" style="color:#eab308;"></i> Profil Gula Darah</h2>
                    </div>
                    <div class="card-body" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 280px;">
                        <div style="position: relative; height: 230px; width: 100%;">
                            <canvas id="chartGukarGulaDarah"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Chart 3.5: Kolesterol --}}
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fa-solid fa-droplet" style="color:#f97316;"></i> Profil Kolesterol</h2>
                    </div>
                    <div class="card-body" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 280px;">
                        <div style="position: relative; height: 230px; width: 100%;">
                            <canvas id="chartGukarKolesterol"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Chart 4: Asam Urat --}}
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title"><i class="fa-solid fa-cube" style="color:#8b5cf6;"></i> Profil Kadar Asam Urat</h2>
                    </div>
                    <div class="card-body" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 280px;">
                        <div style="position: relative; height: 230px; width: 100%;">
                            <canvas id="chartGukarAsamUrat"></canvas>
                        </div>
                    </div>
                </div>

            </div>
        @else
            <div class="card" style="margin-top: 20px; text-align: center; padding: 60px 20px;">
                <div class="card-body">
                    <i class="fa-solid fa-heart-pulse" style="font-size: 4rem; opacity: 0.15; color: var(--color-primary); margin-bottom: 16px; display: block;"></i>
                    <h3 style="font-weight:700; color:var(--text-primary); margin-bottom:8px;">Belum Ada Data Pemeriksaan Guru & Karyawan</h3>
                    <p style="color:var(--text-muted); max-width: 450px; margin: 0 auto 20px; font-size: 0.9rem;">
                        Data check-up Gukar masih kosong. Tambahkan data check-up melalui menu Data Check-Up Gukar atau import dari Excel.
                    </p>
                    <a href="{{ route('uks.checkup-gukar.index') }}" class="btn btn-primary" style="display:inline-flex; align-items:center; gap:8px;">
                        <i class="fa-solid fa-plus"></i> Input Check-Up Gukar
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>

@push('styles')
<style>
.dashboard-tabs {
    display: flex;
    gap: 8px;
    border-bottom: 2px solid var(--border-color, #e2e8f0);
    padding-bottom: 4px;
    margin-bottom: 24px;
    flex-wrap: wrap;
}
.tab-button {
    background: transparent;
    border: none;
    outline: none;
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-muted, #64748b);
    padding: 10px 18px;
    cursor: pointer;
    transition: all 0.2s ease;
    border-radius: 8px 8px 0 0;
    border-bottom: 3px solid transparent;
    display: flex;
    align-items: center;
    gap: 8px;
}
.tab-button:hover {
    color: var(--text-primary, #1e293b);
}
.tab-button.active {
    color: var(--color-primary, #0ea5e9);
    border-bottom-color: var(--color-primary, #0ea5e9);
}
.tab-content {
    display: none;
}
.tab-content.active {
    display: block;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}
.stat-card-premium {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px 24px;
    border-radius: 12px;
    color: #fff;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.stat-card-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}
.stat-icon-premium {
    width: 52px;
    height: 52px;
    background: rgba(255, 255, 255, 0.18);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
}
.stat-info-premium {
    display: flex;
    flex-direction: column;
}
.stat-num-premium {
    font-size: 2rem;
    font-weight: 800;
    line-height: 1.1;
}
.stat-lbl-premium {
    font-size: 0.78rem;
    opacity: 0.9;
    margin-top: 4px;
    font-weight: 500;
}
.dashboard-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
    margin-top: 15px;
}
@media(max-width: 768px) {
    .stats-grid {
        grid-template-columns: 1fr 1fr;
    }
    .dashboard-grid {
        grid-template-columns: 1fr;
    }
    .kunjungan-chart-grid {
        grid-template-columns: 1fr !important;
    }
}
@media(max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
// Tab Switching function
function switchTab(event, tabId) {
    const tabs = document.querySelectorAll('.tab-content');
    const buttons = document.querySelectorAll('.tab-button');
    
    tabs.forEach(t => t.classList.remove('active'));
    buttons.forEach(b => b.classList.remove('active'));
    
    document.getElementById(tabId).classList.add('active');
    event.currentTarget.classList.add('active');
}

document.addEventListener("DOMContentLoaded", function() {
    // ChartJS Config Globals
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
    Chart.defaults.font.size   = 11;
    Chart.defaults.color       = '#64748b';

    const tooltipStyle = {
        backgroundColor: 'rgba(15,23,42,0.92)',
        titleColor: '#f8fafc',
        bodyColor: '#cbd5e1',
        borderColor: 'rgba(255,255,255,0.08)',
        borderWidth: 1,
        padding: 10,
        cornerRadius: 8,
        boxPadding: 4,
    };

    // ── 0. Line Chart: Kunjungan per Bulan ──
    const ctxKunjungan = document.getElementById('chartKunjunganBulanan').getContext('2d');
    new Chart(ctxKunjungan, {
        type: 'line',
        data: {
            labels: @json($bulanLabels),
            datasets: [{
                label: 'Jumlah Kunjungan',
                data: @json($bulanData),
                borderColor: '#0ea5e9',
                backgroundColor: 'rgba(14, 165, 233, 0.12)',
                borderWidth: 2.5,
                pointBackgroundColor: '#0ea5e9',
                pointBorderColor: '#ffffff',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
                tension: 0.35,
                fill: true,
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
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { precision: 0 }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // ── 1. Main Bar Chart: Registered vs Examined Students per Tingkat ──
    const ctxMain = document.getElementById('chartTingkatSiswa').getContext('2d');
    new Chart(ctxMain, {
        type: 'bar',
        data: {
            labels: @json(array_map(fn($t) => "Tingkat " . $t, $allTingkat)),
            datasets: [
                {
                    label: 'Total Siswa',
                    data: @json(array_values($siswaPerTingkat)),
                    backgroundColor: '#0ea5e9',
                    borderRadius: 6,
                    maxBarThickness: 32
                },
                {
                    label: 'Sudah Diperiksa',
                    data: @json(array_values($diperiksaPerTingkat)),
                    backgroundColor: '#10b981',
                    borderRadius: 6,
                    maxBarThickness: 32
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: { boxWidth: 12, usePointStyle: true, pointStyle: 'circle' }
                },
                tooltip: tooltipStyle
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: '#f1f5f9' },
                    ticks: { precision: 0 }
                },
                x: {
                    grid: { display: false }
                }
            }
        }
    });

    // ── 2. Doughnut Charts: Category Breakdown per Tingkat ──
    const colors = ['#f59e0b', '#10b981', '#fb7185', '#ef4444']; // Kurus, Normal, Gemuk, Obesitas

    @foreach($allTingkat as $t)
        @if(array_sum($kategoriPerTingkat[$t]) > 0)
            const ctxCat{{ $t }} = document.getElementById('chartKategoriTingkat{{ $t }}').getContext('2d');
            new Chart(ctxCat{{ $t }}, {
                type: 'doughnut',
                data: {
                    labels: @json(array_keys($kategoriPerTingkat[$t])),
                    datasets: [{
                        data: @json(array_values($kategoriPerTingkat[$t])),
                        backgroundColor: colors,
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 10,
                                padding: 12,
                                usePointStyle: true,
                                pointStyle: 'circle',
                                font: { size: 10 }
                            }
                        },
                        tooltip: tooltipStyle
                    }
                }
            });
        @endif
    @endforeach

    // ── 3. Gukar Chart 1: Status Gizi (IMT) ──
    @if($hasGukarData && array_sum($gukarKategoriIMT) > 0)
        const ctxGukarIMT = document.getElementById('chartGukarIMT').getContext('2d');
        new Chart(ctxGukarIMT, {
            type: 'doughnut',
            data: {
                labels: @json(array_keys($gukarKategoriIMT)),
                datasets: [{
                    data: @json(array_values($gukarKategoriIMT)),
                    backgroundColor: ['#f59e0b', '#10b981', '#fb7185', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '65%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, padding: 12, usePointStyle: true, pointStyle: 'circle' }
                    },
                    tooltip: tooltipStyle
                }
            }
        });
    @endif

    // ── 4. Gukar Chart 2: Tekanan Darah ──
    @if($hasGukarData && array_sum($gukarTekananDarah) > 0)
        const ctxGukarBP = document.getElementById('chartGukarTekananDarah').getContext('2d');
        new Chart(ctxGukarBP, {
            type: 'polarArea',
            data: {
                labels: @json(array_keys($gukarTekananDarah)),
                datasets: [{
                    data: @json(array_values($gukarTekananDarah)),
                    backgroundColor: [
                        'rgba(16, 185, 129, 0.75)', // Normal (Green)
                        'rgba(245, 158, 11, 0.75)',  // Prehypertension (Amber)
                        'rgba(244, 63, 94, 0.75)',   // Stage 1 (Rose)
                        'rgba(239, 68, 68, 0.75)'    // Stage 2 (Red)
                    ],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    r: {
                        ticks: { display: false },
                        grid: { color: '#f1f5f9' }
                    }
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, padding: 8, usePointStyle: true, pointStyle: 'circle' }
                    },
                    tooltip: tooltipStyle
                }
            }
        });
    @endif

    // ── 5. Gukar Chart 3: Gula Darah ──
    @if($hasGukarData && array_sum($gukarGulaDarah) > 0)
        const ctxGukarGD = document.getElementById('chartGukarGulaDarah').getContext('2d');
        new Chart(ctxGukarGD, {
            type: 'pie',
            data: {
                labels: @json(array_keys($gukarGulaDarah)),
                datasets: [{
                    data: @json(array_values($gukarGulaDarah)),
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, padding: 12, usePointStyle: true, pointStyle: 'circle' }
                    },
                    tooltip: tooltipStyle
                }
            }
        });
    @endif

    // ── 5.5. Gukar Chart 3.5: Kolesterol ──
    @if($hasGukarData && array_sum($gukarKolesterol) > 0)
        const ctxGukarChol = document.getElementById('chartGukarKolesterol').getContext('2d');
        new Chart(ctxGukarChol, {
            type: 'pie',
            data: {
                labels: @json(array_keys($gukarKolesterol)),
                datasets: [{
                    data: @json(array_values($gukarKolesterol)),
                    backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, padding: 12, usePointStyle: true, pointStyle: 'circle' }
                    },
                    tooltip: tooltipStyle
                }
            }
        });
    @endif

    // ── 6. Gukar Chart 4: Asam Urat ──
    @if($hasGukarData && array_sum($gukarAsamUrat) > 0)
        const ctxGukarUA = document.getElementById('chartGukarAsamUrat').getContext('2d');
        new Chart(ctxGukarUA, {
            type: 'pie',
            data: {
                labels: @json(array_keys($gukarAsamUrat)),
                datasets: [{
                    data: @json(array_values($gukarAsamUrat)),
                    backgroundColor: ['#f59e0b', '#10b981', '#ef4444'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 10, padding: 12, usePointStyle: true, pointStyle: 'circle' }
                    },
                    tooltip: tooltipStyle
                }
            }
        });
    @endif
});
</script>
@endpush
@endsection
