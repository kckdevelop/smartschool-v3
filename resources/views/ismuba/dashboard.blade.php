@extends('layouts.app')

@section('title', 'Dashboard ISMUBA — SmartSchool')
@section('header_title', 'Dashboard ISMUBA')
@section('header_subtitle', 'Ringkasan dan statistik pantauan keagamaan siswa serta pengajian guru')

@push('styles')
<style>
    /* Custom styles for ISMUBA Dashboard */
    .ismuba-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }

    .ismuba-stat-card {
        border-radius: 16px;
        padding: 24px;
        color: #fff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .ismuba-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .ismuba-stat-icon {
        position: absolute;
        right: 20px;
        bottom: 20px;
        font-size: 4rem;
        opacity: 0.18;
        pointer-events: none;
        transition: transform 0.3s ease;
    }

    .ismuba-stat-card:hover .ismuba-stat-icon {
        transform: scale(1.1) rotate(5deg);
    }

    .ismuba-stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        line-height: 1;
        margin-bottom: 8px;
    }

    .ismuba-stat-label {
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        opacity: 0.85;
    }

    .ismuba-stat-meta {
        margin-top: 14px;
        padding-top: 14px;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
        font-size: 0.82rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .ismuba-stat-meta a {
        color: #fff;
        text-decoration: underline;
        font-weight: 600;
        transition: opacity 0.2s;
    }

    .ismuba-stat-meta a:hover {
        opacity: 0.8;
    }

    .ismuba-charts-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 24px;
    }

    @media (max-width: 1024px) {
        .ismuba-charts-grid {
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
        flex-wrap: wrap;
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
        color: var(--color-primary, #10b981);
    }

    .tab-btn.active {
        color: var(--color-primary, #10b981);
    }

    .tab-btn.active::after {
        content: '';
        position: absolute;
        bottom: -1.5px;
        left: 0;
        right: 0;
        height: 3px;
        background: var(--color-primary, #10b981);
        border-radius: 3px 3px 0 0;
    }

    .tab-content {
        display: none;
        padding: 0;
    }

    .tab-content.active {
        display: block;
    }

    /* Custom Badges */
    .ismuba-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.78rem;
        font-weight: 700;
    }
    .ismuba-badge.btaq-iqro {
        background: #fef3c7;
        color: #92400e;
        border: 1px solid #fde68a;
    }
    .ismuba-badge.btaq-quran {
        background: #d1fae5;
        color: #065f46;
        border: 1px solid #a7f3d0;
    }
    .ismuba-badge.ibadah-fardu {
        background: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .ismuba-badge.ibadah-jenazah {
        background: #faf5ff;
        color: #6b21a8;
        border: 1px solid #e9d5ff;
    }
    .ismuba-badge.ibadah-wudhu {
        background: #ecfeff;
        color: #0891b2;
        border: 1px solid #c5f2f7;
    }
    .ismuba-badge.class-tag {
        background: #e0f2fe;
        color: #0369a1;
        border: 1px solid #bae6fd;
    }

    .badge-grade {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        font-weight: 800;
        font-size: 0.85rem;
    }

    .badge-grade.A { background: #d1fae5; color: #065f46; }
    .badge-grade.B { background: #dbeafe; color: #1e40af; }
    .badge-grade.C { background: #fef3c7; color: #92400e; }

    /* Progress bar styles for BTAQ status */
    .progress-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }
    .progress-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .progress-label {
        display: flex;
        justify-content: space-between;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-primary, #1e293b);
    }
    .progress-bar-wrap {
        height: 6px;
        background: #f1f5f9;
        border-radius: 99px;
        overflow: hidden;
        width: 100%;
    }
    .progress-bar {
        height: 100%;
        border-radius: 99px;
        transition: width 0.8s ease;
    }
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Banner Selamat Datang ISMUBA --}}
    <div style="background: linear-gradient(135deg, #059669, #10b981); border-radius: 16px; padding: 22px 28px; color: #fff; margin-bottom: 24px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; box-shadow: 0 4px 20px rgba(16, 185, 129, 0.15);">
        <div>
            <div style="font-size:.8rem; opacity:.85; font-weight:600; text-transform:uppercase; letter-spacing:.8px;">ISMUBA & KEAGAMAAN</div>
            <div style="font-size:1.45rem; font-weight:800; margin-top:4px;">Panel Pemantauan ISMUBA</div>
            <div style="font-size:.88rem; opacity:.9; margin-top:4px;">
                Memantau perkembangan baca tulis Al-Qur'an (BTAQ), tadarus kelas, penilaian ibadah praktis, dan pengajian guru.
            </div>
        </div>
        <div style="display:flex; gap:10px; flex-wrap:wrap;">
            <a href="{{ route('ismuba.btaq.index') }}" class="btn btn-sm" style="background:#ffffff25; color:#fff; border:1.5px solid #ffffff40; backdrop-filter:blur(4px);">
                <i class="fa-solid fa-book-open-reader"></i> Pantau BTAQ
            </a>
            <a href="{{ route('ismuba.ibadah.index') }}" class="btn btn-sm" style="background:#ffffff25; color:#fff; border:1.5px solid #ffffff40; backdrop-filter:blur(4px);">
                <i class="fa-solid fa-circle-check"></i> Penilaian Ibadah
            </a>
        </div>
    </div>

    {{-- ══════════════════════ STAT CARDS ══════════════════════ --}}
    <div class="ismuba-stats-grid">
        
        {{-- Card 1: BTAQ --}}
        <div class="ismuba-stat-card" style="background: linear-gradient(135deg, #0284c7, #0ea5e9);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-book-open-reader"></i></div>
            <div class="ismuba-stat-number">{{ number_format($countBtaq, 0, ',', '.') }}</div>
            <div class="ismuba-stat-label">Pantauan BTAQ</div>
            <div class="ismuba-stat-meta">
                <span>Iqro: <strong>{{ $btaqIqroCount }}</strong> · Al-Qur'an: <strong>{{ $btaqAlquranCount }}</strong></span>
                <a href="{{ route('ismuba.btaq.index') }}">Detail <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i></a>
            </div>
        </div>

        {{-- Card 2: Tadarus --}}
        <div class="ismuba-stat-card" style="background: linear-gradient(135deg, #059669, #10b981);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-book-quran"></i></div>
            <div class="ismuba-stat-number">{{ number_format($countTadarus, 0, ',', '.') }}</div>
            <div class="ismuba-stat-label">Sesi Tadarus Kelas</div>
            <div class="ismuba-stat-meta">
                <span>Total Aktivitas Tadarus</span>
                <a href="{{ route('ismuba.tadarus.index') }}">Detail <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i></a>
            </div>
        </div>

        {{-- Card 3: Ibadah --}}
        <div class="ismuba-stat-card" style="background: linear-gradient(135deg, #7c3aed, #8b5cf6);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-hands-praying"></i></div>
            <div class="ismuba-stat-number">{{ number_format($countIbadah, 0, ',', '.') }}</div>
            <div class="ismuba-stat-label">Penilaian Ibadah</div>
            <div class="ismuba-stat-meta">
                <span>Praktik Wudhu & Sholat</span>
                <a href="{{ route('ismuba.ibadah.index') }}">Detail <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i></a>
            </div>
        </div>

        {{-- Card 4: Pengajian --}}
        <div class="ismuba-stat-card" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-mosque"></i></div>
            <div class="ismuba-stat-number">{{ $rataRataKehadiranPengajian }}%</div>
            <div class="ismuba-stat-label">Rata-rata Kehadiran</div>
            <div class="ismuba-stat-meta">
                <span>Kegiatan Pengajian: <strong>{{ $totalPengajian }}</strong></span>
                <a href="{{ route('ismuba.jadwal-pengajian.index') }}">Detail <i class="fa-solid fa-chevron-right" style="font-size:0.7rem;"></i></a>
            </div>
        </div>

    </div>

    {{-- ══════════════════════ CHARTS ROW 1 ══════════════════════ --}}
    <div class="ismuba-charts-grid">
        
        {{-- Chart 1: Progres BTAQ --}}
        <div class="card" style="margin-bottom:0; display:flex; flex-direction:column; justify-content:space-between;">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; border-bottom: 1.5px solid var(--border-color, #e2e8f0); padding-bottom:12px;">
                <div>
                    <h2 class="card-title" style="margin:0;">
                        <i class="fa-solid fa-chart-pie" style="color:var(--color-primary);"></i> 
                        Data Status BTAQ Siswa
                    </h2>
                    <div style="font-size:0.8rem; color:var(--text-muted, #64748b); margin-top:4px; margin-left:26px;">Sebaran status BTAQ siswa aktif</div>
                </div>
                <span class="ismuba-badge" style="background:#faf5ff; color:#a855f7; border:1px solid #e9d5ff; font-weight:700;">BTAQ</span>
            </div>
            <div class="card-body" style="flex:1; display:flex; flex-direction:column; justify-content:center; min-height:280px; padding:16px;">
                <div style="position:relative; width:100%; height:130px; margin-bottom:16px;">
                    <canvas id="chartBtaqProgres"></canvas>
                </div>
                <div class="progress-list" id="btaqProgress">
                    @php
                        $btaqColors = ['#d4e157', '#108c10', '#e51c23']; // Yellow (Iqro), Green (Alquran), Red (Kosong)
                        $totalBtaq = array_sum($btaqData);
                    @endphp
                    @foreach($btaqLabels as $j => $lbl)
                        @php
                            $pct = $totalBtaq > 0 ? round(($btaqData[$j] / $totalBtaq) * 100, 2) : 0;
                            $color = $btaqColors[$j];
                        @endphp
                        <div class="progress-item">
                            <div class="progress-label">
                                <span><i class="fa-solid fa-circle" style="color:{{ $color }}; font-size:0.55rem; margin-right:6px;"></i>{{ $lbl }}</span>
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

        {{-- Chart 2: Detail Level Iqro --}}
        <div class="card" style="margin-bottom:0;">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa-solid fa-chart-bar" style="color:var(--color-primary);"></i> 
                    Distribusi Siswa di Tingkat Iqro
                </h2>
            </div>
            <div class="card-body">
                <div style="position:relative; height:240px; width:100%;">
                    <canvas id="chartIqroDetail"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════ CHARTS ROW 2 ══════════════════════ --}}
    <div class="ismuba-charts-grid">
        
        {{-- Chart 3: Ibadah Breakdown --}}
        <div class="card" style="margin-bottom:0;">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa-solid fa-award" style="color:var(--color-primary);"></i> 
                    Predikat Nilai Praktik Ibadah
                </h2>
            </div>
            <div class="card-body">
                <div style="position:relative; height:240px; width:100%;">
                    <canvas id="chartIbadahDetail"></canvas>
                </div>
            </div>
        </div>

        {{-- Chart 4: Tadarus per Kelas --}}
        <div class="card" style="margin-bottom:0;">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa-solid fa-users" style="color:var(--color-primary);"></i> 
                    Aktivitas Tadarus per Kelas
                </h2>
            </div>
            <div class="card-body">
                <div style="position:relative; height:240px; width:100%;">
                    @if($tadarusPerKelas->isEmpty())
                        <div style="text-align:center; padding:80px 20px; color:var(--text-muted);">
                            <i class="fa-solid fa-chart-line" style="font-size:3rem; opacity:0.2; display:block; margin-bottom:14px;"></i>
                            <div style="font-weight:600;">Belum ada data aktivitas tadarus</div>
                        </div>
                    @else
                        <canvas id="chartTadarusKelas"></canvas>
                    @endif
                </div>
            </div>
        </div>

    </div>

    {{-- ══════════════════════ TAB CONTAINER: RECENT LOGS ══════════════════════ --}}
    <div class="tab-container">
        
        <div class="tab-header">
            <button class="tab-btn active" onclick="switchTab(event, 'tab-btaq')">
                <i class="fa-solid fa-book-open-reader"></i> Progres BTAQ Terbaru
            </button>
            <button class="tab-btn" onclick="switchTab(event, 'tab-tadarus')">
                <i class="fa-solid fa-book-quran"></i> Tadarus Kelas Terbaru
            </button>
            <button class="tab-btn" onclick="switchTab(event, 'tab-ibadah')">
                <i class="fa-solid fa-hands-praying"></i> Penilaian Ibadah Terbaru
            </button>
        </div>

        {{-- Tab Content 1: BTAQ --}}
        <div class="tab-content active" id="tab-btaq">
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:100px;">Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Tingkat/Level</th>
                            <th>Detail Progress</th>
                            <th>Guru Penguji</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBtaq as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                <div style="font-weight:700; color:var(--text-primary);">{{ $item->siswa->nama_siswa ?? $item->nis }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">NIS: {{ $item->nis }}</div>
                            </td>
                            <td>
                                @if($item->siswa && $item->siswa->kelas)
                                    <span class="ismuba-badge class-tag">
                                        {{ $item->siswa->kelas->nama_kelas }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="ismuba-badge {{ stripos($item->level, 'iqro') !== false ? 'btaq-iqro' : 'btaq-quran' }}">
                                    {{ stripos($item->level, 'iqro') !== false ? 'Iqro' : 'Al-Qur\'an' }}
                                </span>
                            </td>
                            <td style="font-weight:600;">
                                @if(stripos($item->level, 'iqro') !== false && $item->iqroAwal)
                                    Jilid {{ $item->iqroAwal->jilid }}, Halaman {{ $item->iqroAwal->halaman }}{{ !empty($item->iqroAwal->baris) ? ', Baris ' . $item->iqroAwal->baris : '' }}
                                @elseif($item->alquranAwal)
                                    QS. {{ $item->alquranAwal->surat }}: {{ $item->alquranAwal->ayat }}
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $item->guru->nama_guru ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">Belum ada data progres BTAQ</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tab Content 2: Tadarus --}}
        <div class="tab-content" id="tab-tadarus">
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:100px;">Tanggal</th>
                            <th>Kelas</th>
                            <th>Awal Surat/Ayat</th>
                            <th>Akhir Surat/Ayat</th>
                            <th>Guru Pendamping</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentTadarus as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                @if($item->kelas)
                                    <span class="ismuba-badge class-tag">
                                        {{ $item->kelas->nama_kelas }}
                                    </span>
                                @else
                                    -
                                @endif
                            </td>
                            <td style="font-weight:600;">QS. {{ $item->awal_surat }}: {{ $item->awal_ayat }}</td>
                            <td style="font-weight:600;">QS. {{ $item->akhir_surat }}: {{ $item->akhir_ayat }}</td>
                            <td>{{ $item->guru->nama_guru ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">Belum ada data tadarus kelas</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Tab Content 3: Ibadah --}}
        <div class="tab-content" id="tab-ibadah">
            <div style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:100px;">Tanggal</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Jenis Ibadah</th>
                            <th style="text-align:center; width:100px;">Nilai/Predikat</th>
                            <th>Catatan</th>
                            <th>Guru Penguji</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentIbadah as $item)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                            <td>
                                <div style="font-weight:700; color:var(--text-primary);">{{ $item->siswa->nama_siswa ?? $item->nis }}</div>
                                <div style="font-size:0.75rem; color:var(--text-muted);">NIS: {{ $item->nis }}</div>
                            </td>
                            <td>
                                @if($item->siswa && $item->siswa->kelas)
                                    <span class="ismuba-badge class-tag">
                                        {{ $item->siswa->kelas->nama_kelas }}
                                    </span>
                                @endif
                            </td>
                            <td>
                                <span class="ismuba-badge {{ $item->jenis_ibadah === 'sholat_fardu' ? 'ibadah-fardu' : ($item->jenis_ibadah === 'sholat_jenazah' ? 'ibadah-jenazah' : 'ibadah-wudhu') }}">
                                    {{ $item->label_jenis }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <span class="badge-grade {{ $item->nilai }}">{{ $item->nilai }}</span>
                            </td>
                            <td style="font-size:0.83rem; color:var(--text-secondary);">{{ $item->catatan ?? '—' }}</td>
                            <td>{{ $item->guru->nama_guru ?? '-' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5">Belum ada data pantauan ibadah</td>
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
    // Tab Switcher
    function switchTab(evt, tabId) {
        var i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tab-content");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].classList.remove("active");
        }
        tablinks = document.getElementsByClassName("tab-btn");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(tabId).classList.add("active");
        evt.currentTarget.classList.add("active");
    }

    // Chart.js Default styling matching SmartSchool
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

    // ── 1. Pie: BTAQ Progres ──
    const ctxBtaq = document.getElementById('chartBtaqProgres').getContext('2d');
    new Chart(ctxBtaq, {
        type: 'pie',
        data: {
            labels: @json($btaqLabels),
            datasets: [{
                data: @json($btaqData),
                backgroundColor: ['#d4e157', '#108c10', '#e51c23'], // Yellow (Iqro), Green (Alquran), Red (Kosong)
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: tooltipStyle
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
                        const percent = total > 0 ? ((value / total) * 100).toFixed(12) + '%' : '0%';
                        
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

    // ── 2. Bar Chart: Iqro Detail ──
    const ctxIqro = document.getElementById('chartIqroDetail').getContext('2d');
    new Chart(ctxIqro, {
        type: 'bar',
        data: {
            labels: @json(array_keys($iqroBreakdown)),
            datasets: [{
                label: 'Jumlah Siswa',
                data: @json(array_values($iqroBreakdown)),
                backgroundColor: 'rgba(14, 165, 233, 0.85)',
                borderRadius: 6,
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
                y: { 
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // ── 3. Grouped Bar Chart: Ibadah Detail (A, B, C Predicates) ──
    const ctxIbadah = document.getElementById('chartIbadahDetail').getContext('2d');
    new Chart(ctxIbadah, {
        type: 'bar',
        data: {
            labels: ['Sholat Fardu', 'Sholat Jenazah', 'Gerakan Wudhu'],
            datasets: [
                {
                    label: 'Predikat A',
                    data: [
                        {{ $ibadahData['sholat_fardu']['A'] }},
                        {{ $ibadahData['sholat_jenazah']['A'] }},
                        {{ $ibadahData['gerakan_wudhu']['A'] }}
                    ],
                    backgroundColor: 'rgba(16, 185, 129, 0.85)',
                    borderRadius: 6,
                },
                {
                    label: 'Predikat B',
                    data: [
                        {{ $ibadahData['sholat_fardu']['B'] }},
                        {{ $ibadahData['sholat_jenazah']['B'] }},
                        {{ $ibadahData['gerakan_wudhu']['B'] }}
                    ],
                    backgroundColor: 'rgba(59, 130, 246, 0.85)',
                    borderRadius: 6,
                },
                {
                    label: 'Predikat C',
                    data: [
                        {{ $ibadahData['sholat_fardu']['C'] }},
                        {{ $ibadahData['sholat_jenazah']['C'] }},
                        {{ $ibadahData['gerakan_wudhu']['C'] }}
                    ],
                    backgroundColor: 'rgba(245, 158, 11, 0.85)',
                    borderRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom', labels: { boxHeight: 8, usePointStyle: true } },
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

    // ── 4. Bar Chart: Tadarus per Kelas ──
    @if(!$tadarusPerKelas->isEmpty())
        const ctxTadarus = document.getElementById('chartTadarusKelas').getContext('2d');
        new Chart(ctxTadarus, {
            type: 'bar',
            data: {
                labels: @json($tadarusPerKelas->pluck('nama_kelas')),
                datasets: [{
                    label: 'Jumlah Sesi',
                    data: @json($tadarusPerKelas->pluck('count')),
                    backgroundColor: 'rgba(16, 185, 129, 0.85)',
                    borderRadius: 6,
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
                    y: { 
                        grid: { color: 'rgba(0,0,0,0.05)' },
                        ticks: { stepSize: 1 }
                    }
                }
            }
        });
    @endif

    /* ── Animate progress bars ── */
    document.addEventListener('DOMContentLoaded', () => {
        setTimeout(() => {
            document.querySelectorAll('.progress-bar[data-target]').forEach(bar => {
                bar.style.width = bar.dataset.target + '%';
            });
        }, 400);
    });
</script>
@endpush
