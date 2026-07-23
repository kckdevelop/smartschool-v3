@extends('layouts.app')

@section('title', 'Laporan Pelanggaran & Reward — SmartSchool')
@section('header_title', 'Laporan Pelanggaran & Reward')
@section('header_subtitle', 'Analisis data kedisiplinan dan penghargaan siswa')

@push('styles')
<style>
    .report-filter-card {
        background: var(--card-bg, #fff);
        border-radius: 16px;
        padding: 20px;
        border: 1.5px solid var(--border-color, #e2e8f0);
        margin-bottom: 24px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .report-grid-charts {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    .report-grid-rankings {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 992px) {
        .report-grid-charts,
        .report-grid-rankings {
            grid-template-columns: 1fr;
        }
    }

    .chart-card {
        background: var(--card-bg, #fff);
        border-radius: 16px;
        padding: 24px;
        border: 1.5px solid var(--border-color, #e2e8f0);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        display: flex;
        flex-direction: column;
        height: 380px;
    }

    .chart-card-title {
        font-size: 1.05rem;
        font-weight: 700;
        color: var(--text-primary, #0f172a);
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .chart-container {
        position: relative;
        flex-grow: 1;
        height: 100%;
    }

    .ranking-card {
        background: var(--card-bg, #fff);
        border-radius: 16px;
        border: 1.5px solid var(--border-color, #e2e8f0);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .ranking-header {
        padding: 18px 20px;
        border-bottom: 1.5px solid var(--border-color, #e2e8f0);
        background: #f8fafc;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ranking-header h3 {
        font-size: 1rem;
        font-weight: 700;
        margin: 0;
        color: var(--text-primary, #0f172a);
    }

    .ranking-list {
        padding: 10px 0;
    }

    .ranking-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 20px;
        border-bottom: 1px solid #f1f5f9;
        transition: background 0.2s;
    }

    .ranking-item:last-child {
        border-bottom: none;
    }

    .ranking-item:hover {
        background: #f8fafc;
    }

    .siswa-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .siswa-avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #4f46e5;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
    }

    .siswa-details {
        display: flex;
        flex-direction: column;
    }

    .siswa-nama {
        font-weight: 700;
        color: var(--text-primary, #0f172a);
        font-size: 0.9rem;
    }

    .siswa-kelas {
        font-size: 0.78rem;
        color: var(--text-muted, #64748b);
    }

    .poin-badge {
        padding: 6px 12px;
        border-radius: 12px;
        font-weight: 800;
        font-size: 0.85rem;
    }

    .poin-badge.danger {
        background: #fef2f2;
        color: #dc2626;
    }

    .poin-badge.success {
        background: #f0fdf4;
        color: #16a34a;
    }

    .badge-rank {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 6px;
        font-weight: 800;
        font-size: 0.8rem;
        margin-right: 8px;
    }

    .badge-rank.gold { background: #fef3c7; color: #d97706; }
    .badge-rank.silver { background: #e2e8f0; color: #475569; }
    .badge-rank.bronze { background: #ffedd5; color: #ea580c; }
    .badge-rank.normal { background: #f1f5f9; color: #64748b; }

    .btn-action-print {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        font-weight: 700;
        border-radius: 10px;
        transition: all 0.2s;
    }
</style>
@endpush

@section('content')
<div class="page-content">

    {{-- ── 1. Global Semester Selector ── --}}
    <div class="report-filter-card">
        <form method="GET" action="{{ route('bk.laporan.index') }}" id="form-global-filter" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
            <div style="display:flex; align-items:center; gap:8px;">
                <i class="fa-regular fa-calendar-days text-primary" style="font-size:1.25rem;"></i>
                <div>
                    <span style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-muted); display:block; line-height:1;">Periode Laporan</span>
                    <strong style="font-size:1rem; color:var(--text-primary);">Semester {{ $selectedSemester->semester }} (T.A. {{ $selectedSemester->tahunAjaran->tahun }})</strong>
                </div>
            </div>
            <div style="display:flex; gap:10px; align-items:center;">
                <label for="id_semester" style="font-size:0.85rem; font-weight:700; color:var(--text-primary);">Pilih Semester:</label>
                <select name="id_semester" id="id_semester" class="form-control" style="width:240px; padding:8px 12px; border-radius:8px;" onchange="document.getElementById('form-global-filter').submit();">
                    @foreach($semesters as $sem)
                        <option value="{{ $sem->id_semester }}" {{ $sem->id_semester == $selectedSemester->id_semester ? 'selected' : '' }}>
                            Semester {{ $sem->semester }} - T.A. {{ $sem->tahunAjaran->tahun }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="id_kelas" value="{{ $selectedKelasId }}">
            </div>
        </form>
    </div>

    {{-- ── 2. Top Charts Section ── --}}
    <div class="report-grid-charts">
        {{-- Chart 1: Pelanggaran --}}
        <div class="chart-card">
            <div class="chart-card-title">
                <i class="fa-solid fa-triangle-exclamation text-danger"></i>
                <span>Kategori Pelanggaran Terbanyak</span>
            </div>
            <div class="chart-container">
                <canvas id="chartPelanggaran"></canvas>
            </div>
        </div>

        {{-- Chart 2: Reward --}}
        <div class="chart-card">
            <div class="chart-card-title">
                <i class="fa-solid fa-award text-success"></i>
                <span>Kategori Reward Terbanyak</span>
            </div>
            <div class="chart-container">
                <canvas id="chartReward"></canvas>
            </div>
        </div>
    </div>

    {{-- ── 3. Top Student Rankings Section ── --}}
    <div class="report-grid-rankings">
        {{-- Rankings: Pelanggaran --}}
        <div class="ranking-card">
            <div class="ranking-header">
                <div class="siswa-avatar" style="background:#fef2f2; color:#ef4444;"><i class="fa-solid fa-user-slash"></i></div>
                <div>
                    <h3>Siswa Poin Pelanggaran Terbanyak</h3>
                    <p style="margin:0; font-size:0.75rem; color:var(--text-muted);">Urutan berdasarkan total akumulasi poin pelanggaran</p>
                </div>
            </div>
            <div class="ranking-list">
                @forelse($topSiswaPelanggaran as $index => $row)
                    @php
                        $rankClass = $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : 'normal'));
                    @endphp
                    <div class="ranking-item">
                        <div class="siswa-info">
                            <span class="badge-rank {{ $rankClass }}">{{ $index + 1 }}</span>
                            <div class="siswa-avatar">
                                {{ strtoupper(substr($row->siswa->nama_siswa ?? 'S', 0, 1)) }}
                            </div>
                            <div class="siswa-details">
                                <span class="siswa-nama">{{ $row->siswa->nama_siswa ?? 'N/A' }}</span>
                                <span class="siswa-kelas">Kelas: {{ $row->siswa->kelas->nama_kelas ?? '-' }} · NIS: {{ $row->nis }}</span>
                            </div>
                        </div>
                        <span class="poin-badge danger">{{ $row->total_poin }} Poin</span>
                    </div>
                @empty
                    <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                        <i class="fa-solid fa-folder-open mb-2" style="font-size: 2rem;"></i>
                        <p style="margin:0; font-size:0.85rem;">Tidak ada data pelanggaran pada semester ini</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Rankings: Reward --}}
        <div class="ranking-card">
            <div class="ranking-header">
                <div class="siswa-avatar" style="background:#f0fdf4; color:#10b981;"><i class="fa-solid fa-user-shield"></i></div>
                <div>
                    <h3>Siswa Reward Terbanyak</h3>
                    <p style="margin:0; font-size:0.75rem; color:var(--text-muted);">Urutan berdasarkan total akumulasi poin penghargaan</p>
                </div>
            </div>
            <div class="ranking-list">
                @forelse($topSiswaReward as $index => $row)
                    @php
                        $rankClass = $index == 0 ? 'gold' : ($index == 1 ? 'silver' : ($index == 2 ? 'bronze' : 'normal'));
                    @endphp
                    <div class="ranking-item">
                        <div class="siswa-info">
                            <span class="badge-rank {{ $rankClass }}">{{ $index + 1 }}</span>
                            <div class="siswa-avatar">
                                {{ strtoupper(substr($row->siswa->nama_siswa ?? 'S', 0, 1)) }}
                            </div>
                            <div class="siswa-details">
                                <span class="siswa-nama">{{ $row->siswa->nama_siswa ?? 'N/A' }}</span>
                                <span class="siswa-kelas">Kelas: {{ $row->siswa->kelas->nama_kelas ?? '-' }} · NIS: {{ $row->nis }}</span>
                            </div>
                        </div>
                        <span class="poin-badge success">+{{ $row->total_poin }} Poin</span>
                    </div>
                @empty
                    <div style="padding: 40px; text-align: center; color: var(--text-muted);">
                        <i class="fa-solid fa-folder-open mb-2" style="font-size: 2rem;"></i>
                        <p style="margin:0; font-size:0.85rem;">Tidak ada data reward pada semester ini</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ── 4. Rekap Poin Per Kelas (Tombol Cetak per Baris) ── --}}
    <div class="card" style="border-radius:16px; border:1.5px solid var(--border-color, #e2e8f0); box-shadow:0 4px 6px -1px rgba(0, 0, 0, 0.05); overflow:hidden; margin-bottom:24px;">
        <div class="card-header" style="background:#f8fafc; border-bottom:1.5px solid var(--border-color); padding:20px 24px; display:flex; justify-content:space-between; align-items:center;">
            <div>
                <h3 style="margin:0; font-size:1.1rem; font-weight:700; color:var(--text-primary);">
                    <i class="fa-solid fa-table-list text-primary" style="margin-right:8px;"></i>
                    Rekap Poin Per Kelas — Semester {{ $selectedSemester->semester }} (T.A. {{ $selectedSemester->tahunAjaran->tahun }})
                </h3>
                <p style="margin:4px 0 0; font-size:0.78rem; color:var(--text-muted);">Klik tombol <strong>Cetak</strong> pada baris kelas untuk membuka laporan siswa kelas tersebut</p>
            </div>
        </div>

        <div class="card-body" style="padding:0;">
            <div class="table-responsive">
                <table class="table" style="width:100%; border-collapse:collapse; margin:0;">
                    <thead style="background:#f8fafc; border-bottom:1.5px solid var(--border-color);">
                        <tr>
                            <th style="padding:13px 14px; font-weight:700; font-size:0.78rem; text-transform:uppercase; text-align:center; width:50px;">No</th>
                            <th style="padding:13px 20px; font-weight:700; font-size:0.78rem; text-transform:uppercase; text-align:left;">Kelas</th>
                            <th style="padding:13px 14px; font-weight:700; font-size:0.78rem; text-transform:uppercase; text-align:center; width:120px;">Jumlah Siswa</th>
                            <th style="padding:13px 14px; font-weight:700; font-size:0.78rem; text-transform:uppercase; text-align:center; width:160px;">Total Pelanggaran</th>
                            <th style="padding:13px 14px; font-weight:700; font-size:0.78rem; text-transform:uppercase; text-align:center; width:140px;">Total Reward</th>
                            <th style="padding:13px 14px; font-weight:700; font-size:0.78rem; text-transform:uppercase; text-align:center; width:150px;">Total Net</th>
                            <th style="padding:13px 14px; font-weight:700; font-size:0.78rem; text-transform:uppercase; text-align:center; width:110px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $index => $row)
                            @php
                                $netPoin = $row->total_net;
                                $badgeColor = $netPoin < 0
                                    ? 'background:#fef2f2; color:#dc2626;'
                                    : ($netPoin > 0 ? 'background:#f0fdf4; color:#16a34a;' : 'background:#f1f5f9; color:#475569;');
                                $printUrl = route('bk.laporan.print', [
                                    'id_semester' => $selectedSemester->id_semester,
                                    'id_kelas'    => $row->id_kelas,
                                ]);
                            @endphp
                            <tr style="border-bottom:1px solid #e2e8f0; transition:background 0.15s;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background=''">
                                <td style="padding:13px 14px; text-align:center; font-weight:600; color:var(--text-muted); font-size:0.85rem;">{{ $index + 1 }}</td>
                                <td style="padding:13px 20px; font-weight:700; color:var(--text-primary);">
                                    <i class="fa-solid fa-door-open" style="color:#7c3aed; font-size:0.8rem; margin-right:6px;"></i>
                                    Kelas {{ $row->nama_kelas }}
                                </td>
                                <td style="padding:13px 14px; text-align:center; color:var(--text-primary); font-size:0.88rem;">{{ $row->jumlah_siswa }} Siswa</td>
                                <td style="padding:13px 14px; text-align:center;">
                                    <span class="poin-badge danger" style="padding:4px 10px; font-size:0.8rem;">{{ $row->total_poin }}</span>
                                </td>
                                <td style="padding:13px 14px; text-align:center;">
                                    <span class="poin-badge success" style="padding:4px 10px; font-size:0.8rem;">+{{ $row->total_reward }}</span>
                                </td>
                                <td style="padding:13px 14px; text-align:center;">
                                    <span class="poin-badge" style="padding:5px 12px; font-size:0.8rem; {{ $badgeColor }}">{{ $netPoin > 0 ? '+' : '' }}{{ $netPoin }}</span>
                                </td>
                                <td style="padding:13px 14px; text-align:center;">
                                    <a href="{{ $printUrl }}" target="_blank"
                                       style="display:inline-flex; align-items:center; gap:5px; padding:7px 14px; background:linear-gradient(135deg,#7c3aed,#4f46e5); color:#fff; border-radius:8px; font-size:0.78rem; font-weight:700; text-decoration:none; box-shadow:0 2px 6px rgba(124,58,237,0.25); transition:opacity 0.2s;"
                                       onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                                        <i class="fa-solid fa-print"></i> Cetak
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding:50px; text-align:center; color:var(--text-muted);">
                                    <i class="fa-solid fa-school-flag" style="font-size:2.5rem; display:block; margin:0 auto 12px; opacity:0.4;"></i>
                                    Tidak ada kelas aktif yang ditemukan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
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

    // ── 1. Horizontal Bar Chart: Pelanggaran Kategori ──
    const ctxPelanggaran = document.getElementById('chartPelanggaran').getContext('2d');
    const topPelanggaranData = @json($topPelanggaran);
    new Chart(ctxPelanggaran, {
        type: 'bar',
        data: {
            labels: topPelanggaranData.length > 0 ? topPelanggaranData.map(item => item.pelanggaran) : ['Belum ada data'],
            datasets: [{
                label: 'Jumlah Kasus',
                data: topPelanggaranData.length > 0 ? topPelanggaranData.map(item => item.total) : [0],
                backgroundColor: 'rgba(239, 68, 68, 0.85)', // Red
                borderRadius: 8,
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
                x: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { stepSize: 1 } },
                y: { grid: { display: false } }
            }
        }
    });

    // ── 2. Horizontal Bar Chart: Reward Kategori ──
    const ctxReward = document.getElementById('chartReward').getContext('2d');
    const topRewardData = @json($topReward);
    new Chart(ctxReward, {
        type: 'bar',
        data: {
            labels: topRewardData.length > 0 ? topRewardData.map(item => item.reward) : ['Belum ada data'],
            datasets: [{
                label: 'Jumlah Reward',
                data: topRewardData.length > 0 ? topRewardData.map(item => item.total) : [0],
                backgroundColor: 'rgba(16, 185, 129, 0.85)', // Emerald
                borderRadius: 8,
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
                x: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { stepSize: 1 } },
                y: { grid: { display: false } }
            }
    });

    // ── 3. Dynamic Print URL Helper ──
    function updatePrintUrl() {
        const idSemester = "{{ $selectedSemester->id_semester }}";
        const idKelas = document.getElementById('id_kelas_table').value;
        const idGuruBk = document.getElementById('id_guru_bk').value;
        
        const printUrl = "{{ route('bk.laporan.print') }}" + "?id_semester=" + idSemester + "&id_kelas=" + idKelas + "&id_guru_bk=" + idGuruBk;
        document.getElementById('btn-print-laporan').href = printUrl;
    }

    document.addEventListener('DOMContentLoaded', () => {
        updatePrintUrl();
    });
</script>
@endpush
