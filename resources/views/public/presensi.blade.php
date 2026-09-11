<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Informasi Kehadiran Siswa — {{ $sekolah->nama_sekolah ?? 'SmartSchool' }}</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    {{-- FontAwesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            
            --primary: #4f46e5;
            --primary-light: #eef2ff;
            --success: #10b981;
            --success-light: #ecfdf5;
            --warning: #f59e0b;
            --warning-light: #fffbeb;
            --info: #06b6d4;
            --info-light: #ecfeff;
            --danger: #ef4444;
            --danger-light: #fef2f2;
            
            --radius-lg: 16px;
            --radius-md: 12px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.05);
            --shadow-md: 0 4px 20px -2px rgba(0,0,0,0.06);
            --shadow-hover: 0 10px 25px -5px rgba(79,70,229,0.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #f1f5f9;
            color: var(--text-main);
            min-height: 100vh;
            padding-bottom: 40px;
        }

        /* ── HEADER ── */
        .public-header {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, #4338ca 100%);
            color: #ffffff;
            padding: 24px 32px 64px 32px;
            position: relative;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .header-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            max-width: 1320px;
            margin: 0 auto;
            flex-wrap: wrap;
            gap: 16px;
        }

        .brand-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand-logo {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid rgba(255,255,255,0.25);
        }

        .brand-logo img {
            max-width: 36px;
            max-height: 36px;
            object-fit: contain;
        }

        .brand-logo i {
            font-size: 24px;
            color: #fbbf24;
        }

        .brand-text h1 {
            font-size: 1.4rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            line-height: 1.2;
        }

        .brand-text p {
            font-size: 0.85rem;
            color: #c7d2fe;
            font-weight: 500;
        }

        .header-meta {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .live-badge {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid rgba(16, 185, 129, 0.4);
            color: #6ee7b7;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 6px 14px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .live-badge .pulse-dot {
            width: 8px;
            height: 8px;
            background: #10b981;
            border-radius: 50%;
            box-shadow: 0 0 10px #10b981;
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7); }
            70% { transform: scale(1); box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
            100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
        }

        .clock-box {
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255,255,255,0.15);
            padding: 6px 16px;
            border-radius: 30px;
            font-size: 0.85rem;
            font-weight: 600;
            color: #e0e7ff;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* ── MAIN CONTAINER ── */
        .main-container {
            max-width: 1320px;
            margin: -40px auto 0 auto;
            padding: 0 24px;
            position: relative;
            z-index: 10;
        }

        /* ── FILTER TOOLBAR ── */
        .toolbar-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 16px 24px;
            box-shadow: var(--shadow-md);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            border: 1px solid var(--border-color);
        }

        .toolbar-left, .toolbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .input-group-custom {
            display: flex;
            align-items: center;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: var(--radius-md);
            padding: 8px 14px;
            gap: 8px;
            transition: all 0.2s;
        }

        .input-group-custom:focus-within {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.15);
            background: #ffffff;
        }

        .input-group-custom input, .input-group-custom select {
            border: none;
            background: transparent;
            outline: none;
            font-size: 0.88rem;
            font-weight: 600;
            color: var(--text-main);
        }

        .btn-refresh {
            background: var(--primary-light);
            color: var(--primary);
            border: none;
            padding: 9px 18px;
            border-radius: var(--radius-md);
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-refresh:hover {
            background: var(--primary);
            color: #ffffff;
            transform: translateY(-1px);
        }

        /* ── SUMMARY KPI CARDS ── */
        .kpi-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .kpi-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            position: relative;
            overflow: hidden;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-hover);
        }

        .kpi-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
        }

        .kpi-total::before { background: var(--primary); }
        .kpi-hadir::before { background: var(--success); }
        .kpi-sakit::before { background: var(--warning); }
        .kpi-izin::before  { background: var(--info); }
        .kpi-alfa::before  { background: var(--danger); }

        .kpi-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 12px;
        }

        .kpi-title {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--text-muted);
        }

        .kpi-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .kpi-total .kpi-icon { background: var(--primary-light); color: var(--primary); }
        .kpi-hadir .kpi-icon { background: var(--success-light); color: var(--success); }
        .kpi-sakit .kpi-icon { background: var(--warning-light); color: var(--warning); }
        .kpi-izin .kpi-icon  { background: var(--info-light); color: var(--info); }
        .kpi-alfa .kpi-icon  { background: var(--danger-light); color: var(--danger); }

        .kpi-value {
            font-size: 1.9rem;
            font-weight: 800;
            line-height: 1;
            margin-bottom: 6px;
            color: var(--text-main);
        }

        .kpi-sub {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--text-muted);
        }

        /* ── CHARTS SECTION ── */
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 24px;
            margin-bottom: 24px;
        }

        @media (max-width: 992px) {
            .charts-grid {
                grid-template-columns: 1fr;
            }
        }

        .chart-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
        }

        .chart-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .chart-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-title i {
            color: var(--primary);
        }

        .chart-body {
            position: relative;
            width: 100%;
            height: 280px;
        }

        /* ── CLASS TABLE SECTION ── */
        .table-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-color);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .table-header-box {
            padding: 20px 24px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            background: #ffffff;
        }

        .table-title {
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--text-main);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            display: block;
        }

        .custom-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            background: #ffffff;
        }

        .custom-table th {
            background: #f8fafc;
            padding: 14px 16px;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
            white-space: nowrap;
            vertical-align: middle;
        }

        .custom-table td {
            padding: 14px 16px;
            font-size: 0.88rem;
            font-weight: 600;
            border-bottom: 1px solid #f1f5f9;
            color: var(--text-main);
            vertical-align: middle;
        }

        .custom-table tbody tr {
            transition: background 0.15s;
        }

        .custom-table tbody tr:hover {
            background: #f8fafc;
        }

        /* BADGES & PILLS */
        .pill {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .pill-hadir { background: var(--success-light); color: #047857; }
        .pill-sakit { background: var(--warning-light); color: #b45309; }
        .pill-izin  { background: var(--info-light); color: #0e7490; }
        .pill-alfa  { background: var(--danger-light); color: #b91c1c; }
        .pill-muted { background: #f1f5f9; color: #64748b; }

        /* PROGRESS BAR */
        .progress-box {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 120px;
        }

        .progress-bar-bg {
            flex: 1;
            height: 8px;
            background: #e2e8f0;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            border-radius: 10px;
            transition: width 0.4s ease;
        }

        .progress-text {
            font-size: 0.8rem;
            font-weight: 700;
            width: 42px;
            text-align: right;
        }

        /* FOOTER */
        .public-footer {
            max-width: 1320px;
            margin: 30px auto 0 auto;
            text-align: center;
            font-size: 0.82rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .public-footer strong {
            color: var(--primary);
        }
    </style>
</head>
<body>

    {{-- HEADER PUBLIC --}}
    <header class="public-header">
        <div class="header-top">
            <div class="brand-info">
                <div class="brand-logo">
                    @if(!empty($sekolah->logo))
                        <img src="{{ asset('storage/' . $sekolah->logo) }}" alt="Logo">
                    @else
                        <i class="fa-solid fa-graduation-cap"></i>
                    @endif
                </div>
                <div class="brand-text">
                    <h1>{{ $sekolah->nama_sekolah ?? 'SMARTSCHOOL' }}</h1>
                    <p>Dashboard Informasi Kehadiran Siswa — Akses Publik</p>
                </div>
            </div>

            <div class="header-meta">
                <div class="live-badge">
                    <span class="pulse-dot"></span> LIVE SYSTEM
                </div>
                <div class="clock-box">
                    <i class="fa-regular fa-clock"></i>
                    <span id="liveClock">00:00:00 WIB</span>
                </div>
            </div>
        </div>
    </header>

    {{-- MAIN CONTAINER --}}
    <main class="main-container">

        {{-- FILTER TOOLBAR --}}
        <div class="toolbar-card">
            <div class="toolbar-left">
                <div class="input-group-custom">
                    <i class="fa-solid fa-calendar-day" style="color:var(--primary);"></i>
                    <input type="date" id="filterTanggal" value="{{ $tanggal }}" onchange="updateDashboardData()">
                </div>

                <div class="input-group-custom">
                    <i class="fa-solid fa-filter" style="color:var(--primary);"></i>
                    <select id="filterTingkat" onchange="applyTableFilter()">
                        <option value="all">Semua Tingkat</option>
                        <option value="10">Kelas X</option>
                        <option value="11">Kelas XI</option>
                        <option value="12">Kelas XII</option>
                    </select>
                </div>

                <div class="input-group-custom" style="min-width: 220px;">
                    <i class="fa-solid fa-magnifying-glass" style="color:var(--text-muted);"></i>
                    <input type="text" id="searchKelas" placeholder="Cari nama kelas / wali..." onkeyup="applyTableFilter()">
                </div>
            </div>

            <div class="toolbar-right">
                <span id="lastUpdated" style="font-size:0.8rem; font-weight:600; color:var(--text-muted);">
                    Tanggal: {{ $formatted_tanggal }}
                </span>
                <button class="btn-refresh" onclick="updateDashboardData(true)">
                    <i class="fa-solid fa-rotate-right" id="refreshIcon"></i> Perbarui Data
                </button>
            </div>
        </div>

        {{-- KPI SUMMARY CARDS --}}
        <div class="kpi-grid">
            <div class="kpi-card kpi-total">
                <div class="kpi-header">
                    <span class="kpi-title">Total Siswa</span>
                    <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
                </div>
                <div class="kpi-value" id="kpiTotalSiswa">{{ number_format($summary['total_siswa']) }}</div>
                <div class="kpi-sub">Siswa Aktif Terdaftar</div>
            </div>

            <div class="kpi-card kpi-hadir">
                <div class="kpi-header">
                    <span class="kpi-title">Siswa Hadir</span>
                    <div class="kpi-icon"><i class="fa-solid fa-user-check"></i></div>
                </div>
                <div class="kpi-value" id="kpiTotalHadir">{{ number_format($summary['total_hadir']) }}</div>
                <div class="kpi-sub" id="kpiPersenHadir">Kehadiran: {{ $summary['persen_hadir'] }}%</div>
            </div>

            <div class="kpi-card kpi-sakit">
                <div class="kpi-header">
                    <span class="kpi-title">Sakit</span>
                    <div class="kpi-icon"><i class="fa-solid fa-notes-medical"></i></div>
                </div>
                <div class="kpi-value" id="kpiTotalSakit">{{ number_format($summary['total_sakit']) }}</div>
                <div class="kpi-sub">Izin Sakit</div>
            </div>

            <div class="kpi-card kpi-izin">
                <div class="kpi-header">
                    <span class="kpi-title">Izin</span>
                    <div class="kpi-icon"><i class="fa-solid fa-envelope-open-text"></i></div>
                </div>
                <div class="kpi-value" id="kpiTotalIzin">{{ number_format($summary['total_izin']) }}</div>
                <div class="kpi-sub">Izin Keperluan</div>
            </div>

            <div class="kpi-card kpi-alfa">
                <div class="kpi-header">
                    <span class="kpi-title">Alfa / Tanpa Ket.</span>
                    <div class="kpi-icon"><i class="fa-solid fa-user-xmark"></i></div>
                </div>
                <div class="kpi-value" id="kpiTotalAlfa">{{ number_format($summary['total_alfa']) }}</div>
                <div class="kpi-sub">Belum/Tidak Hadir</div>
            </div>
        </div>

        {{-- CHARTS SECTION --}}
        <div class="charts-grid">
            {{-- DONUT CHART --}}
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fa-solid fa-chart-pie"></i> Persentase Kehadiran
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="pieChart"></canvas>
                </div>
            </div>

            {{-- BAR CHART PER KELAS --}}
            <div class="chart-card">
                <div class="chart-header">
                    <div class="chart-title">
                        <i class="fa-solid fa-chart-column"></i> Kehadiran Siswa Per Kelas
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="barChart"></canvas>
                </div>
            </div>
        </div>

        {{-- REKAP PER KELAS TABLE --}}
        <div class="table-card">
            <div class="table-header-box">
                <div class="table-title">
                    <i class="fa-solid fa-table-list" style="color:var(--primary);"></i> Rekap Presensi Per Kelas
                </div>
                <div style="font-size:0.82rem; font-weight:600; color:var(--text-muted);" id="tableCountInfo">
                    Menampilkan {{ count($classes) }} Kelas
                </div>
            </div>

            <div class="table-responsive">
                <table class="custom-table" id="classTable">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th>Nama Kelas &amp; Wali</th>
                            <th style="text-align: center;">Total Siswa</th>
                            <th style="text-align: center;">Hadir</th>
                            <th style="text-align: center;">Izin</th>
                            <th style="text-align: center;">Sakit</th>
                            <th style="text-align: center;">Alfa</th>
                            <th style="text-align: center;">Belum Finger</th>
                            <th style="min-width: 160px;">% Kehadiran</th>
                        </tr>
                    </thead>
                    <tbody id="classTableBody">
                        @forelse($classes as $idx => $c)
                        <tr data-tingkat="{{ $c['tingkat'] }}" data-search="{{ strtolower($c['nama_kelas'] . ' ' . $c['wali_kelas']) }}">
                            <td style="text-align: center; color:var(--text-muted);">{{ $idx + 1 }}</td>
                            <td>
                                <div style="font-weight: 800; color:var(--primary); font-size:0.95rem;">{{ $c['nama_kelas'] }}</div>
                                <div style="font-size: 0.78rem; color:var(--text-muted); font-weight: 500; margin-top:2px;">
                                    <i class="fa-solid fa-user-tie" style="font-size:0.75rem; margin-right:4px;"></i>{{ $c['wali_kelas'] }}
                                </div>
                            </td>
                            <td style="text-align: center; font-weight: 700;">{{ $c['total_siswa'] }}</td>
                            <td style="text-align: center;">
                                <span class="pill pill-hadir"><i class="fa-solid fa-check"></i> {{ $c['hadir'] }}</span>
                            </td>
                            <td style="text-align: center;">
                                <span class="pill {{ $c['izin'] > 0 ? 'pill-izin' : 'pill-muted' }}">{{ $c['izin'] }}</span>
                            </td>
                            <td style="text-align: center;">
                                <span class="pill {{ $c['sakit'] > 0 ? 'pill-sakit' : 'pill-muted' }}">{{ $c['sakit'] }}</span>
                            </td>
                            <td style="text-align: center;">
                                <span class="pill {{ $c['alfa'] > 0 ? 'pill-alfa' : 'pill-muted' }}">{{ $c['alfa'] }}</span>
                            </td>
                            <td style="text-align: center; color:var(--text-muted);">
                                {{ $c['belum_absen'] }}
                            </td>
                            <td>
                                <div class="progress-box">
                                    <div class="progress-bar-bg">
                                        <div class="progress-bar-fill" style="width: {{ $c['persen_hadir'] }}%;"></div>
                                    </div>
                                    <span class="progress-text">{{ $c['persen_hadir'] }}%</span>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 30px; color:var(--text-muted); font-style:italic;">
                                Tidak ada data kelas aktif.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    {{-- FOOTER --}}
    <footer class="public-footer">
        <p>&copy; {{ date('Y') }} <strong>{{ $sekolah->nama_sekolah ?? 'SmartSchool' }}</strong>. Sistem Presensi Harian Real-Time.</p>
    </footer>

    {{-- JAVASCRIPT --}}
    <script>
        // Data Inisialisasi awal
        let chartPieInstance = null;
        let chartBarInstance = null;

        const initialPieData = @json($chart_pie);
        const initialBarData = @json($chart_bar);

        // Realtime Clock
        function updateClock() {
            const now = new Date();
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) + ' WIB';
            document.getElementById('liveClock').textContent = timeStr;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Render Charts
        function initCharts(pieData, barData) {
            // 1. Pie / Doughnut Chart
            const pieCtx = document.getElementById('pieChart').getContext('2d');
            if (chartPieInstance) chartPieInstance.destroy();

            chartPieInstance = new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: pieData.labels,
                    datasets: [{
                        data: pieData.data,
                        backgroundColor: pieData.colors,
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' },
                                padding: 14,
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        const dataset = data.datasets[0];
                                        return data.labels.map(function(label, i) {
                                            const val = dataset.data[i] || 0;
                                            const fill = dataset.backgroundColor[i];
                                            return {
                                                text: `${label} (${val})`,
                                                fillStyle: fill,
                                                strokeStyle: fill,
                                                lineWidth: 0,
                                                hidden: isNaN(dataset.data[i]) || (chart.getDatasetMeta(0).data[i] && chart.getDatasetMeta(0).data[i].hidden),
                                                index: i
                                            };
                                        });
                                    }
                                    return [];
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = context.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return ` ${label}: ${value} Siswa (${percentage}%)`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            // 2. Bar Chart Per Kelas
            const barCtx = document.getElementById('barChart').getContext('2d');
            if (chartBarInstance) chartBarInstance.destroy();

            chartBarInstance = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: barData.labels,
                    datasets: [
                        {
                            label: 'Hadir',
                            data: barData.hadir,
                            backgroundColor: '#10b981',
                            borderRadius: 6
                        },
                        {
                            label: 'Sakit',
                            data: barData.sakit,
                            backgroundColor: '#f59e0b',
                            borderRadius: 6
                        },
                        {
                            label: 'Izin',
                            data: barData.izin,
                            backgroundColor: '#06b6d4',
                            borderRadius: 6
                        },
                        {
                            label: 'Alfa',
                            data: barData.alfa,
                            backgroundColor: '#ef4444',
                            borderRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: false,
                            grid: { display: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', size: 11, weight: '600' } }
                        },
                        y: {
                            stacked: false,
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { font: { family: 'Plus Jakarta Sans', size: 11 } }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: { font: { family: 'Plus Jakarta Sans', size: 12, weight: '600' } }
                        }
                    }
                }
            });
        }

        // Apply Live Client Search & Filter on Table
        function applyTableFilter() {
            const tingkatVal = document.getElementById('filterTingkat').value;
            const searchVal = document.getElementById('searchKelas').value.toLowerCase().trim();
            const rows = document.querySelectorAll('#classTableBody tr[data-tingkat]');

            let visibleCount = 0;

            rows.forEach(row => {
                const rowTingkat = row.getAttribute('data-tingkat');
                const rowSearch = row.getAttribute('data-search');

                const matchTingkat = (tingkatVal === 'all' || rowTingkat === tingkatVal);
                const matchSearch = (!searchVal || rowSearch.includes(searchVal));

                if (matchTingkat && matchSearch) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            document.getElementById('tableCountInfo').textContent = `Menampilkan ${visibleCount} Kelas`;
        }

        // Fetch & Refresh Dashboard Data via AJAX
        function updateDashboardData(isManual = false) {
            const dateVal = document.getElementById('filterTanggal').value;
            const refreshIcon = document.getElementById('refreshIcon');

            if (refreshIcon) refreshIcon.classList.add('fa-spin');

            fetch(`{{ route('public.presensi.data') }}?tanggal=${dateVal}`)
                .then(res => res.json())
                .then(res => {
                    if (res.status === 'success') {
                        const data = res.data;

                        // Update KPI
                        document.getElementById('kpiTotalSiswa').textContent = data.summary.total_siswa.toLocaleString();
                        document.getElementById('kpiTotalHadir').textContent = data.summary.total_hadir.toLocaleString();
                        document.getElementById('kpiPersenHadir').textContent = `Kehadiran: ${data.summary.persen_hadir}%`;
                        document.getElementById('kpiTotalSakit').textContent = data.summary.total_sakit.toLocaleString();
                        document.getElementById('kpiTotalIzin').textContent = data.summary.total_izin.toLocaleString();
                        document.getElementById('kpiTotalAlfa').textContent = data.summary.total_alfa.toLocaleString();
                        document.getElementById('lastUpdated').textContent = `Tanggal: ${data.formatted_tanggal}`;

                        // Update Charts
                        initCharts(data.chart_pie, data.chart_bar);

                        // Update Table
                        renderTableBody(data.classes);
                        applyTableFilter();
                    }
                })
                .catch(err => console.error('Error fetching presensi data:', err))
                .finally(() => {
                    if (refreshIcon) refreshIcon.classList.remove('fa-spin');
                });
        }

        // Render Table Body Rows Dynamically
        function renderTableBody(classes) {
            const tbody = document.getElementById('classTableBody');
            if (!classes || classes.length === 0) {
                tbody.innerHTML = `<tr><td colspan="9" style="text-align: center; padding: 30px; color:var(--text-muted); font-style:italic;">Tidak ada data kelas.</td></tr>`;
                return;
            }

            let html = '';
            classes.forEach((c, idx) => {
                const searchStr = (c.nama_kelas + ' ' + c.wali_kelas).toLowerCase();
                html += `
                <tr data-tingkat="${c.tingkat}" data-search="${searchStr}">
                    <td style="text-align: center; color:var(--text-muted);">${idx + 1}</td>
                    <td>
                        <div style="font-weight: 800; color:var(--primary); font-size:0.95rem;">${c.nama_kelas}</div>
                        <div style="font-size: 0.78rem; color:var(--text-muted); font-weight: 500; margin-top:2px;">
                            <i class="fa-solid fa-user-tie" style="font-size:0.75rem; margin-right:4px;"></i>${c.wali_kelas}
                        </div>
                    </td>
                    <td style="text-align: center; font-weight: 700;">${c.total_siswa}</td>
                    <td style="text-align: center;">
                        <span class="pill pill-hadir"><i class="fa-solid fa-check"></i> ${c.hadir}</span>
                    </td>
                    <td style="text-align: center;">
                        <span class="pill ${c.izin > 0 ? 'pill-izin' : 'pill-muted'}">${c.izin}</span>
                    </td>
                    <td style="text-align: center;">
                        <span class="pill ${c.sakit > 0 ? 'pill-sakit' : 'pill-muted'}">${c.sakit}</span>
                    </td>
                    <td style="text-align: center;">
                        <span class="pill ${c.alfa > 0 ? 'pill-alfa' : 'pill-muted'}">${c.alfa}</span>
                    </td>
                    <td style="text-align: center; color:var(--text-muted);">${c.belum_absen}</td>
                    <td>
                        <div class="progress-box">
                            <div class="progress-bar-bg">
                                <div class="progress-bar-fill" style="width: ${c.persen_hadir}%;"></div>
                            </div>
                            <span class="progress-text">${c.persen_hadir}%</span>
                        </div>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;
        }

        // Initialize Charts on Load
        document.addEventListener('DOMContentLoaded', () => {
            initCharts(initialPieData, initialBarData);
        });

        // Auto Refresh Setiap 30 Detik
        setInterval(() => {
            updateDashboardData();
        }, 30000);
    </script>
</body>
</html>
