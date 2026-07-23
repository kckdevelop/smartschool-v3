<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Layar Display Utama — SmartSchool</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Share+Tech+Mono&display=swap" rel="stylesheet">

    {{-- FontAwesome Icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Chart.js CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            /* Bright / Light Theme variables */
            --bg-light: #f0fdfa;
            --bg-card: #ffffff;
            --bg-card-hover: #fafafa;
            --border-card: rgba(13, 148, 136, 0.08);
            --border-glow: rgba(99, 102, 241, 0.3);
            
            --text-main: #0f172a;
            --text-secondary: #0d9488;
            --text-muted: #64748b;
            
            --color-primary: #4f46e5; /* Indigo */
            --color-secondary: #06b6d4; /* Cyan */
            --color-success: #10b981; /* Emerald */
            --color-warning: #f59e0b; /* Amber */
            --color-danger: #ef4444; /* Rose/Red */
            
            --glow-primary: 0 4px 14px rgba(99, 102, 241, 0.15);
            --glow-success: 0 4px 14px rgba(16, 185, 129, 0.15);
            
            --font-family: 'Plus Jakarta Sans', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font-family);
        }

        body {
            background: linear-gradient(135deg, #f0fdfa 0%, #e2f8f5 50%, #d1f4f0 100%);
            color: var(--text-main);
            width: 100vw;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Subtle Soft Background Orbs */
        .ambient-orb-1 {
            position: absolute;
            top: -10%;
            left: -10%;
            width: 40%;
            height: 40%;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.06) 0%, transparent 70%);
            z-index: 1;
            pointer-events: none;
        }

        .ambient-orb-2 {
            position: absolute;
            bottom: -10%;
            right: -10%;
            width: 40%;
            height: 40%;
            background: radial-gradient(circle, rgba(13, 148, 136, 0.06) 0%, transparent 70%);
            z-index: 1;
            pointer-events: none;
        }

        /* ══════════════════════ HEADER ══════════════════════ */
        header {
            height: 80px;
            padding: 0 40px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-card);
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 10;
            box-shadow: 0 2px 10px rgba(13, 148, 136, 0.02);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .brand-logo {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .brand-logo img {
            max-width: 32px;
            max-height: 32px;
            object-fit: contain;
        }

        .brand-logo i {
            font-size: 22px;
            color: #ffffff;
        }

        .brand-info {
            display: flex;
            flex-direction: column;
        }

        .brand-title {
            font-size: 20px;
            font-weight: 800;
            letter-spacing: 0.5px;
            color: #1e293b;
        }

        .brand-subtitle {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .clock-container {
            display: flex;
            align-items: center;
            gap: 24px;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(13, 148, 136, 0.12);
            padding: 8px 20px;
            border-radius: 30px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
        }

        .date-box {
            display: flex;
            flex-direction: column;
            text-align: right;
        }

        .date-text {
            font-size: 14px;
            font-weight: 700;
            color: #1e293b;
        }

        .cycle-badge {
            font-size: 11px;
            color: var(--text-secondary);
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .clock-box {
            font-family: 'Share Tech Mono', monospace;
            font-size: 28px;
            color: #0f172a;
            font-weight: bold;
            letter-spacing: 1px;
        }

        /* ══════════════════════ MAIN CONTENT ══════════════════════ */
        main {
            flex: 1;
            padding: 30px 40px;
            z-index: 5;
            position: relative;
        }

        .slide-container {
            width: 100%;
            height: 100%;
            position: relative;
        }

        /* Slide Transition */
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.6s ease-in-out, visibility 0.6s ease-in-out;
            display: flex;
            flex-direction: column;
        }

        .slide.active {
            opacity: 1;
            visibility: visible;
        }

        .slide-title {
            font-size: 24px;
            font-weight: 800;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            color: #1e293b;
        }

        .slide-title i {
            color: var(--color-primary);
        }

        /* ── Slide Kelas per Tingkat (Simplified layout) ── */
        .grade-list-container {
            display: flex;
            flex-direction: column;
            gap: 14px;
            flex: 1;
            overflow-y: auto;
            padding-right: 4px;
        }

        /* Custom Scrollbar for grade list */
        .grade-list-container::-webkit-scrollbar {
            width: 6px;
        }
        .grade-list-container::-webkit-scrollbar-track {
            background: transparent;
        }
        .grade-list-container::-webkit-scrollbar-thumb {
            background: rgba(13, 148, 136, 0.15);
            border-radius: 6px;
        }

        .grade-row {
            display: flex;
            align-items: center;
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 16px;
            padding: 12px 24px;
            min-height: 85px;
            gap: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.01), 0 2px 4px -1px rgba(0, 0, 0, 0.01);
            transition: all 0.25s ease;
        }

        .grade-row:hover {
            transform: translateX(4px);
            border-color: var(--border-glow);
            box-shadow: 0 10px 15px -3px rgba(13, 148, 136, 0.04);
        }

        .grade-row-class {
            width: 140px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 800;
            font-size: 18px;
            color: #1e293b;
            flex-shrink: 0;
            border-right: 2px solid rgba(13, 148, 136, 0.08);
            height: 50px;
        }

        .grade-row-class i {
            color: var(--color-primary);
            font-size: 18px;
        }

        .grade-row-schedules {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            flex: 1;
            align-items: center;
        }

        /* Schedule Block - Simple Template Style */
        .schedule-block {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 130px;
            height: 70px;
            padding: 6px 10px;
            border-radius: 10px;
            border: 1px solid;
            font-size: 11px;
            position: relative;
            transition: all 0.2s ease;
        }

        .schedule-block:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px rgba(0,0,0,0.03);
        }

        .schedule-block.is-filled {
            background: #d1fae5; /* Soft Green */
            border-color: #34d399;
            color: #065f46;
        }

        .schedule-block.is-empty {
            background: #f1f5f9; /* Soft Slate */
            border-color: #e2e8f0;
            color: #475569;
        }

        .block-top {
            display: flex;
            justify-content: space-between;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .is-filled .block-top { color: #047857; }
        .is-empty .block-top { color: #64748b; }

        .block-mapel {
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin: 2px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
        }

        .block-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 9px;
            font-weight: 700;
        }

        .block-guru {
            text-transform: uppercase;
        }

        .block-ruang {
            font-style: italic;
        }

        .no-schedule-row {
            display: flex;
            align-items: center;
            color: var(--text-muted);
            gap: 8px;
            font-size: 13px;
            font-weight: 500;
        }

        .no-schedule-row i {
            font-size: 16px;
            opacity: 0.7;
        }

        /* ── Slide 2: Siswa Analytics ── */
        .analytics-container {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            flex: 1;
        }

        .panel-left {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .card-stat {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 20px;
            padding: 30px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0,0,0,0.01);
        }

        .card-stat::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--color-primary);
        }

        .card-stat-label {
            font-size: 14px;
            color: var(--text-secondary);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card-stat-value {
            font-size: 48px;
            font-weight: 800;
            margin: 10px 0;
            color: #0f172a;
        }

        .gender-ratio {
            margin-top: 15px;
        }

        .gender-label-row {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .gender-l { color: #0284c7; }
        .gender-p { color: #db2777; }

        .ratio-bar-bg {
            height: 10px;
            background: #f1f5f9;
            border-radius: 5px;
            overflow: hidden;
            display: flex;
        }

        .ratio-fill-l {
            background: linear-gradient(to right, #0ea5e9, #38bdf8);
            height: 100%;
        }

        .ratio-fill-p {
            background: linear-gradient(to right, #ec4899, #f472b6);
            height: 100%;
        }

        .panel-right {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 20px;
            padding: 30px;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 6px rgba(0,0,0,0.01);
        }

        .chart-header {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 20px;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .chart-header i {
            color: var(--color-primary);
        }

        .chart-wrapper {
            flex: 1;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Slide 3: BTAQ Analytics ── */
        .btaq-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            flex: 1;
        }

        .btaq-stats-grid {
            display: grid;
            grid-template-rows: repeat(3, 1fr);
            gap: 15px;
        }

        .btaq-stat-row {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 16px;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.01);
        }

        .btaq-stat-row:hover {
            border-color: var(--border-glow);
            background: #fafafa;
        }

        .btaq-level-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .btaq-level-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .icon-iqro {
            background: rgba(245, 158, 11, 0.08);
            color: var(--color-warning);
            border: 1px solid rgba(245, 158, 11, 0.15);
        }

        .icon-quran {
            background: rgba(16, 185, 129, 0.08);
            color: var(--color-success);
            border: 1px solid rgba(16, 185, 129, 0.15);
        }

        .icon-empty {
            background: rgba(148, 163, 184, 0.08);
            color: var(--text-muted);
            border: 1px solid rgba(148, 163, 184, 0.15);
        }

        .btaq-level-name {
            font-size: 15px;
            font-weight: 700;
            color: #1e293b;
        }

        .btaq-level-desc {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 1px;
        }

        .btaq-level-value {
            text-align: right;
        }

        .btaq-count {
            font-size: 22px;
            font-weight: 800;
            color: #0f172a;
        }

        .btaq-pct {
            font-size: 12px;
            color: var(--text-secondary);
            font-weight: 700;
        }

        /* ── Slide 4: Jurnal Analytics ── */
        .jurnal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-bottom: 24px;
        }

        .jurnal-stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.01);
        }

        .jurnal-stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .js-approved { background: rgba(16, 185, 129, 0.08); color: var(--color-success); border: 1px solid rgba(16, 185, 129, 0.15); }
        .js-pending { background: rgba(245, 158, 11, 0.08); color: var(--color-warning); border: 1px solid rgba(245, 158, 11, 0.15); }
        .js-rejected { background: rgba(239, 68, 68, 0.08); color: var(--color-danger); border: 1px solid rgba(239, 68, 68, 0.15); }

        .jurnal-stat-details {
            display: flex;
            flex-direction: column;
        }

        .jurnal-stat-num {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
        }

        .jurnal-stat-label {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 700;
            text-transform: uppercase;
        }

        .jurnal-lower-row {
            display: grid;
            grid-template-columns: 1.2fr 2fr;
            gap: 24px;
            flex: 1;
        }

        .today-progress-card {
            background: var(--bg-card);
            border: 1px solid var(--border-card);
            border-radius: 20px;
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.01);
        }

        .progress-circle-wrapper {
            position: relative;
            width: 140px;
            height: 140px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        .progress-circle-svg {
            transform: rotate(-90deg);
            width: 100%;
            height: 100%;
        }

        .progress-circle-bg {
            fill: none;
            stroke: #f1f5f9;
            stroke-width: 12;
        }

        .progress-circle-bar {
            fill: none;
            stroke: url(#progressGradient);
            stroke-width: 12;
            stroke-linecap: round;
            stroke-dasharray: 440;
            stroke-dashoffset: 440;
            transition: stroke-dashoffset 1.5s ease-out;
        }

        .progress-circle-text {
            position: absolute;
            font-size: 26px;
            font-weight: 800;
            color: #0f172a;
        }

        .progress-text-label {
            font-size: 13px;
            color: var(--text-main);
            font-weight: 700;
            text-align: center;
        }

        .progress-text-sub {
            font-size: 11px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 600;
        }

        /* ══════════════════════ FOOTER ══════════════════════ */
        footer {
            height: 60px;
            padding: 0 40px;
            border-top: 1px solid var(--border-card);
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: relative;
            z-index: 10;
        }

        /* Slideshow Progress Bar */
        .progress-bar-container {
            position: absolute;
            top: -2px;
            left: 0;
            width: 100%;
            height: 3px;
            background: rgba(0, 0, 0, 0.03);
        }

        .progress-bar-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(to right, var(--color-primary), var(--color-secondary));
            transition: width 0.1s linear;
        }

        .footer-left {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 700;
        }

        .footer-left span {
            color: var(--text-secondary);
        }

        .slideshow-controls {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .control-btn {
            background: #ffffff;
            border: 1px solid var(--border-card);
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-muted);
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }

        .control-btn:hover {
            background: #fafafa;
            color: var(--text-main);
            border-color: rgba(13, 148, 136, 0.2);
        }

        .control-btn:active {
            transform: scale(0.95);
        }

        .slide-indicators {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .indicator-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: rgba(13, 148, 136, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator-dot.active {
            background: var(--color-primary);
            width: 24px;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    <div class="ambient-orb-1"></div>
    <div class="ambient-orb-2"></div>

    <!-- HEADER -->
    <header>
        <div class="brand">
            <div class="brand-logo">
                @if(isset($sekolah) && $sekolah->logo)
                    <img src="{{ asset('storage/' . $sekolah->logo) }}" alt="Logo Sekolah">
                @else
                    <i class="fa-solid fa-graduation-cap"></i>
                @endif
            </div>
            <div class="brand-info">
                <span class="brand-title">{{ $sekolah->nama_sekolah ?? 'SMARTSCHOOL' }}</span>
                <span class="brand-subtitle">Layar Display Utama Akademik</span>
            </div>
        </div>

        <div class="clock-container">
            <div class="date-box">
                <span class="date-text">{{ $carbonDate->translatedFormat('l, d F Y') }}</span>
                @if($hariSiklus)
                    <span class="cycle-badge">Hari Siklus: {{ $hariSiklus }}</span>
                @else
                    <span class="cycle-badge">Hari Libur / Non-Siklus</span>
                @endif
            </div>
            <div class="clock-box" id="live-clock">00:00:00</div>
        </div>
    </header>

    <!-- MAIN SLIDES CONTAINER -->
    <main>
        <div class="slide-container">
            @php
                // Group class status by grade level (tingkat)
                $groupedClasses = collect($classJournalStatus)->groupBy('tingkat')->sortKeys();
            @endphp

            {{-- 1. GRADE-BASED PAGES (KELAS 10, 11, 12) --}}
            @foreach($groupedClasses as $tingkat => $classItems)
                <div class="slide {{ $loop->first ? 'active' : '' }}" id="slide-grade-{{ $tingkat }}">
                    <h2 class="slide-title">
                        <i class="fa-solid fa-list-check"></i>
                        Status Pengisian Jurnal Mengajar Harian — Kelas {{ $tingkat }}
                    </h2>
                    
                    <div class="grade-list-container">
                        @foreach($classItems as $classData)
                            <div class="grade-row">
                                <div class="grade-row-class">
                                    <i class="fa-solid fa-school"></i>
                                    <span>{{ $classData['nama_kelas'] }}</span>
                                </div>
                                <div class="grade-row-schedules">
                                    @if(count($classData['schedules']) > 0)
                                        @foreach($classData['schedules'] as $sch)
                                            <div class="schedule-block {{ $sch['is_filled'] ? 'is-filled' : 'is-empty' }}" title="Materi: {{ $sch['materi'] ?? '—' }}">
                                                <div class="block-top">
                                                    <span>{{ $sch['jam_ke'] }}</span>
                                                    <span class="block-ruang">{{ $sch['ruang'] }}</span>
                                                </div>
                                                <div class="block-mapel" title="{{ $sch['mapel'] }}">{{ $sch['kode_mapel'] }}</div>
                                                <div class="block-bottom">
                                                    <span class="block-guru">{{ $sch['kode_guru'] }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="no-schedule-row">
                                            <i class="fa-solid fa-calendar-xmark"></i>
                                            <span>Tidak ada KBM hari ini</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            {{-- 2. STUDENT ANALYTICS SLIDE --}}
            <div class="slide" id="slide-siswa">
                <h2 class="slide-title">
                    <i class="fa-solid fa-user-graduate"></i>
                    Analisis Data Siswa Aktif
                </h2>
                <div class="analytics-container">
                    <div class="panel-left">
                        <div class="card-stat">
                            <div class="card-stat-label">Total Siswa Aktif</div>
                            <div class="card-stat-value" id="siswa-total-val">{{ number_format($totalSiswa, 0, ',', '.') }}</div>
                            <div class="gender-ratio">
                                <div class="gender-label-row">
                                    <span class="gender-l">Laki-Laki ({{ $totalSiswa > 0 ? round(($siswaLaki / $totalSiswa) * 100, 1) : 0 }}%)</span>
                                    <span class="gender-p">Perempuan ({{ $totalSiswa > 0 ? round(($siswaPerempuan / $totalSiswa) * 100, 1) : 0 }}%)</span>
                                </div>
                                <div class="ratio-bar-bg">
                                    @php
                                        $lPct = $totalSiswa > 0 ? ($siswaLaki / $totalSiswa) * 100 : 50;
                                        $pPct = $totalSiswa > 0 ? ($siswaPerempuan / $totalSiswa) * 100 : 50;
                                    @endphp
                                    <div class="ratio-fill-l" style="width: {{ $lPct }}%"></div>
                                    <div class="ratio-fill-p" style="width: {{ $pPct }}%"></div>
                                </div>
                                <div class="gender-label-row" style="margin-top: 6px; font-size: 11px; color: var(--text-muted);">
                                    <span>{{ number_format($siswaLaki, 0, ',', '.') }} Siswa</span>
                                    <span>{{ number_format($siswaPerempuan, 0, ',', '.') }} Siswi</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-stat" style="flex: 1; padding: 20px 24px;">
                            <div class="card-stat-label" style="font-size: 11px; margin-bottom: 14px;">Distribusi Jenis Kelamin Per Tingkat</div>
                            <div style="display: flex; flex-direction: column; gap: 14px;">
                                @php
                                    $tingkatList = array_unique(array_keys(array_flip($siswaTingkatLabels)));
                                @endphp
                                @foreach($siswaTingkatLabels as $tIdx => $tLabel)
                                    @php
                                        $tL = $siswaTingkatLaki[$tIdx] ?? 0;
                                        $tP = $siswaTingkatPerempuan[$tIdx] ?? 0;
                                        $tTotal = $tL + $tP;
                                        $tLPct = $tTotal > 0 ? round(($tL / $tTotal) * 100, 1) : 50;
                                        $tPPct = $tTotal > 0 ? round(($tP / $tTotal) * 100, 1) : 50;
                                    @endphp
                                    <div>
                                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                            <span style="font-size: 13px; font-weight: 800; color: #1e293b;">{{ $tLabel }}</span>
                                            <span style="font-size: 11px; font-weight: 700; color: var(--text-muted);">{{ number_format($tTotal, 0, ',', '.') }} siswa</span>
                                        </div>
                                        <div class="ratio-bar-bg" style="height: 12px; border-radius: 6px;">
                                            <div class="ratio-fill-l" style="width: {{ $tLPct }}%; border-radius: 6px 0 0 6px;"></div>
                                            <div class="ratio-fill-p" style="width: {{ $tPPct }}%; border-radius: 0 6px 6px 0;"></div>
                                        </div>
                                        <div style="display: flex; justify-content: space-between; font-size: 10px; font-weight: 700; margin-top: 3px;">
                                            <span class="gender-l"><i class="fa-solid fa-mars" style="font-size: 9px;"></i> {{ number_format($tL, 0, ',', '.') }} ({{ $tLPct }}%)</span>
                                            <span class="gender-p"><i class="fa-solid fa-venus" style="font-size: 9px;"></i> {{ number_format($tP, 0, ',', '.') }} ({{ $tPPct }}%)</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="panel-right">
                        <div class="chart-header">
                            <i class="fa-solid fa-chart-column"></i>
                            <span>Jumlah Siswa Per Tingkat (Laki-Laki vs Perempuan)</span>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="siswaTingkatChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. BTAQ ANALYTICS SLIDE --}}
            <div class="slide" id="slide-btaq">
                <h2 class="slide-title">
                    <i class="fa-solid fa-book-quran"></i>
                    Analisis Kemampuan BTAQ Siswa
                </h2>
                <div class="btaq-container">
                    <div class="panel-right" style="order: 2;">
                        <div class="chart-header">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span>Proporsi Kemampuan BTAQ</span>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="btaqDonutChart"></canvas>
                        </div>
                    </div>

                    <div class="btaq-stats-grid" style="order: 1;">
                        <div class="btaq-stat-row">
                            <div class="btaq-level-info">
                                <div class="btaq-level-icon icon-quran">
                                    <i class="fa-solid fa-book-open-reader"></i>
                                </div>
                                <div>
                                    <div class="btaq-level-name">Level Al-Qur'an</div>
                                    <div class="btaq-level-desc">Siswa yang sudah lancar membaca kitab suci</div>
                                </div>
                            </div>
                            <div class="btaq-level-value">
                                <div class="btaq-count" style="color: var(--color-success);">{{ number_format($btaqAlquranCount, 0, ',', '.') }}</div>
                                <div class="btaq-pct">{{ $totalSiswa > 0 ? round(($btaqAlquranCount / $totalSiswa) * 100, 1) : 0 }}% dari siswa</div>
                            </div>
                        </div>

                        <div class="btaq-stat-row">
                            <div class="btaq-level-info">
                                <div class="btaq-level-icon icon-iqro">
                                    <i class="fa-solid fa-book"></i>
                                </div>
                                <div>
                                    <div class="btaq-level-name">Level Iqro / Iqra</div>
                                    <div class="btaq-level-desc">Siswa dalam masa bimbingan iqro 1 s.d. 6</div>
                                </div>
                            </div>
                            <div class="btaq-level-value">
                                <div class="btaq-count" style="color: var(--color-warning);">{{ number_format($btaqIqroCount, 0, ',', '.') }}</div>
                                <div class="btaq-pct">{{ $totalSiswa > 0 ? round(($btaqIqroCount / $totalSiswa) * 100, 1) : 0 }}% dari siswa</div>
                            </div>
                        </div>

                        <div class="btaq-stat-row">
                            <div class="btaq-level-info">
                                <div class="btaq-level-icon icon-empty">
                                    <i class="fa-solid fa-folder-minus"></i>
                                </div>
                                <div>
                                    <div class="btaq-level-name">Belum Terdata / Kosong</div>
                                    <div class="btaq-level-desc">Siswa yang belum mengikuti tes penempatan level</div>
                                </div>
                            </div>
                            <div class="btaq-level-value">
                                <div class="btaq-count" style="color: var(--text-muted);">{{ number_format($btaqKosongCount, 0, ',', '.') }}</div>
                                <div class="btaq-pct">{{ $totalSiswa > 0 ? round(($btaqKosongCount / $totalSiswa) * 100, 1) : 0 }}% dari siswa</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. LEARNING ACTIVITIES SLIDE --}}
            <div class="slide" id="slide-jurnal">
                <h2 class="slide-title">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    Statistik Kegiatan Pembelajaran & Jurnal Guru
                </h2>
                <div class="slide-content-jurnal" style="display: flex; flex-direction: column; flex: 1;">
                    <div class="jurnal-grid">
                        <div class="jurnal-stat-card">
                            <div class="jurnal-stat-icon js-approved">
                                <i class="fa-solid fa-check-double"></i>
                            </div>
                            <div class="jurnal-stat-details">
                                <div class="jurnal-stat-num">{{ number_format($jurnalApproved, 0, ',', '.') }}</div>
                                <div class="jurnal-stat-label">Jurnal Disetujui</div>
                            </div>
                        </div>
                        <div class="jurnal-stat-card">
                            <div class="jurnal-stat-icon js-pending">
                                <i class="fa-solid fa-clock"></i>
                            </div>
                            <div class="jurnal-stat-details">
                                <div class="jurnal-stat-num">{{ number_format($jurnalPending, 0, ',', '.') }}</div>
                                <div class="jurnal-stat-label">Menunggu Review</div>
                            </div>
                        </div>
                        <div class="jurnal-stat-card">
                            <div class="jurnal-stat-icon js-rejected">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div class="jurnal-stat-details">
                                <div class="jurnal-stat-num">{{ number_format($jurnalRejected, 0, ',', '.') }}</div>
                                <div class="jurnal-stat-label">Perlu Revisi</div>
                            </div>
                        </div>
                    </div>

                    <div class="jurnal-lower-row">
                        <div class="today-progress-card">
                            <div class="progress-circle-wrapper">
                                <svg class="progress-circle-svg" viewBox="0 0 160 160">
                                    <defs>
                                        <linearGradient id="progressGradient" x1="0%" y1="0%" x2="100%" y2="100%">
                                            <stop offset="0%" stop-color="var(--color-primary)" />
                                            <stop offset="100%" stop-color="var(--color-secondary)" />
                                        </linearGradient>
                                    </defs>
                                    <circle class="progress-circle-bg" cx="80" cy="80" r="70" />
                                    <circle class="progress-circle-bar" cx="80" cy="80" r="70" id="today-progress-ring" />
                                </svg>
                                <span class="progress-circle-text" id="today-progress-text">0%</span>
                            </div>
                            <div class="progress-text-label">Pengisian Jurnal Hari Ini</div>
                            <div class="progress-text-sub" id="today-progress-sub">0 dari 0 kelas terisi</div>
                        </div>

                        <div class="panel-right">
                            <div class="chart-header">
                                <i class="fa-solid fa-chart-line"></i>
                                <span>Tren Pengisian Jurnal Bulanan ({{ number_format($jurnalTotal, 0, ',', '.') }} Jurnal)</span>
                            </div>
                            <div class="chart-wrapper">
                                <canvas id="jurnalMonthlyChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 5. PKL ANALYTICS SLIDE --}}
            <div class="slide" id="slide-pkl">
                <h2 class="slide-title">
                    <i class="fa-solid fa-briefcase"></i>
                    Analisis Praktek Kerja Lapangan (PKL) Siswa
                </h2>
                <div class="analytics-container">
                    <div class="panel-left">
                        <div class="card-stat">
                            <div class="card-stat-label">Siswa PKL Aktif</div>
                            <div class="card-stat-value">{{ number_format($totalPklSiswa, 0, ',', '.') }}</div>
                            <div style="font-size: 13px; font-weight: 700; color: var(--text-secondary); display: flex; align-items: center; gap: 8px; margin-top: 10px;">
                                <i class="fa-solid fa-building"></i>
                                Total Mitra DUDI Terdata: {{ number_format($totalDudi, 0, ',', '.') }} perusahaan
                            </div>
                        </div>

                        <div class="card-stat" style="flex: 1; padding: 24px;">
                            <div class="card-stat-label" style="font-size: 12px; margin-bottom: 12px;">Distribusi Status Penempatan</div>
                            <div style="display: flex; flex-direction: column; gap: 8px;">
                                @php
                                    $pklTotal = array_sum($pklStatusData);
                                @endphp
                                @foreach(['Aktif', 'Selesai', 'Ditarik', 'Batal', 'Pindah'] as $idx => $label)
                                    @php
                                        $val = $pklStatusData[$idx];
                                        $pct = $pklTotal > 0 ? round(($val / $pklTotal) * 100, 1) : 0;
                                        $barColor = match($label) {
                                            'Aktif' => 'var(--color-success)',
                                            'Selesai' => 'var(--color-secondary)',
                                            'Ditarik' => 'var(--color-warning)',
                                            'Batal' => 'var(--color-danger)',
                                            default => 'var(--text-muted)'
                                        };
                                    @endphp
                                    <div>
                                        <div style="display: flex; justify-content: space-between; font-size: 11px; font-weight: 700; margin-bottom: 2px;">
                                            <span>{{ $label }}</span>
                                            <span>{{ $val }} ({{ $pct }}%)</span>
                                        </div>
                                        <div style="height: 6px; background: #f1f5f9; border-radius: 3px; overflow: hidden;">
                                            <div style="height: 100%; background: {{ $barColor }}; width: {{ $pct }}%; border-radius: 3px;"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="panel-right">
                        <div class="chart-header">
                            <i class="fa-solid fa-chart-bar"></i>
                            <span>Mitra Industri Penempatan Siswa Terbanyak</span>
                        </div>
                        <div class="chart-wrapper">
                            <canvas id="pklDudiChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 6. UKS ANALYTICS SLIDE --}}
            <div class="slide" id="slide-uks">
                <h2 class="slide-title">
                    <i class="fa-solid fa-heart-pulse"></i>
                    Analisis Kunjungan UKS & Medis Siswa
                </h2>
                <div class="analytics-container">
                    <div class="panel-left">
                        <div class="card-stat">
                            <div class="card-stat-label">Kunjungan Hari Ini</div>
                            <div class="card-stat-value" style="color: var(--color-danger);">{{ number_format($uksToday, 0, ',', '.') }}</div>
                            <div style="font-size: 13px; font-weight: 700; color: var(--text-muted); display: flex; align-items: center; gap: 8px; margin-top: 10px;">
                                <i class="fa-solid fa-calendar-days"></i>
                                Kunjungan Bulan Ini: {{ number_format($uksMonth, 0, ',', '.') }} kali
                            </div>
                        </div>

                        <div class="card-stat" style="flex: 1; padding: 24px; display: flex; flex-direction: column; justify-content: center;">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div style="width: 48px; height: 48px; background: rgba(239, 68, 68, 0.08); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--color-danger); font-size: 20px;">
                                    <i class="fa-solid fa-pills"></i>
                                </div>
                                <div>
                                    <h4 style="color: #0f172a; font-size: 15px; font-weight: 700;">Penggunaan Obat & Tindakan</h4>
                                    <p style="color: var(--text-muted); font-size: 11px; margin-top: 2px;">
                                        Telah terdata sebanyak {{ number_format($totalObat, 0, ',', '.') }} pemberian obat di klinik UKS sekolah.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-right">
                        <div class="chart-header">
                            <i class="fa-solid fa-chart-line"></i>
                            <span>Tren Kunjungan UKS Bulanan</span>
                        </div>
                        <div class="chart-wrapper" style="height: 180px; margin-bottom: 20px;">
                            <canvas id="uksMonthlyChart"></canvas>
                        </div>
                        
                        <div style="border-top: 1px solid rgba(13, 148, 136, 0.08); padding-top: 15px;">
                            <div class="chart-header" style="margin-bottom: 10px; font-size: 14px;">
                                <i class="fa-solid fa-notes-medical"></i>
                                <span>Gejala / Keluhan Siswa Terbanyak</span>
                            </div>
                            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                @if(count($topComplaintsLabels) > 0)
                                    @foreach($topComplaintsLabels as $cIdx => $lbl)
                                        <div style="background: rgba(239, 68, 68, 0.05); border: 1px solid rgba(239, 68, 68, 0.15); color: #b91c1c; padding: 6px 14px; border-radius: 20px; font-size: 11px; font-weight: 700; display: flex; align-items: center; gap: 6px;">
                                            <i class="fa-solid fa-triangle-exclamation"></i>
                                            <span>{{ $lbl }} ({{ $topComplaintsData[$cIdx] }})</span>
                                        </div>
                                    @endforeach
                                @else
                                    <div style="font-size: 12px; color: var(--text-muted); font-style: italic;">Tidak ada data keluhan terdata</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 7. BK ANALYTICS SLIDE --}}
            <div class="slide" id="slide-bk">
                <h2 class="slide-title">
                    <i class="fa-solid fa-handshake-angle"></i>
                    Analisis Layanan Bimbingan Konseling (BK)
                </h2>
                <div class="analytics-container">
                    <div class="panel-left">
                        <div class="card-stat">
                            <div class="card-stat-label">Total Layanan BK</div>
                            <div class="card-stat-value" style="color: var(--color-primary);">{{ number_format($totalBk, 0, ',', '.') }}</div>
                            <div style="margin-top: 15px;">
                                <div class="gender-label-row">
                                    <span style="color: var(--color-success);">Selesai ({{ $totalBk > 0 ? round(($bkSelesai / $totalBk) * 100, 1) : 0 }}%)</span>
                                    <span style="color: var(--color-warning);">Proses ({{ $totalBk > 0 ? round(($bkProses / $totalBk) * 100, 1) : 0 }}%)</span>
                                </div>
                                <div class="ratio-bar-bg">
                                    @php
                                        $selPct = $totalBk > 0 ? ($bkSelesai / $totalBk) * 100 : 50;
                                        $proPct = $totalBk > 0 ? ($bkProses / $totalBk) * 100 : 50;
                                    @endphp
                                    <div class="ratio-fill-l" style="width: {{ $selPct }}%; background: var(--color-success);"></div>
                                    <div class="ratio-fill-p" style="width: {{ $proPct }}%; background: var(--color-warning);"></div>
                                </div>
                                <div class="gender-label-row" style="margin-top: 6px; font-size: 11px; color: var(--text-muted);">
                                    <span>{{ number_format($bkSelesai, 0, ',', '.') }} Kasus</span>
                                    <span>{{ number_format($bkProses, 0, ',', '.') }} Kasus</span>
                                </div>
                            </div>
                        </div>

                        <div class="card-stat" style="flex: 1; padding: 24px; display: flex; flex-direction: column; justify-content: center;">
                            <div style="display: flex; align-items: center; gap: 16px;">
                                <div style="width: 48px; height: 48px; background: rgba(99, 102, 241, 0.08); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--color-primary); font-size: 20px;">
                                    <i class="fa-solid fa-comments"></i>
                                </div>
                                <div>
                                    <h4 style="color: #0f172a; font-size: 15px; font-weight: 700;">Konseling Pribadi & Akademik</h4>
                                    <p style="color: var(--text-muted); font-size: 11px; margin-top: 2px;">
                                        Membantu membimbing siswa mencapai potensi optimal dalam masalah pribadi, sosial, belajar, dan karir.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="panel-right">
                        <div class="chart-header">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span>Distribusi Bidang Masalah (BK)</span>
                        </div>
                        <div class="chart-wrapper">
                            @if(count($problemTypesData) > 0)
                                <canvas id="bkProblemChart"></canvas>
                            @else
                                <div style="font-size: 13px; color: var(--text-muted); font-style: italic;">Tidak ada data bimbingan terdata</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <!-- FOOTER & PROGRESS BAR -->
    <footer>
        <div class="progress-bar-container">
            <div class="progress-bar-fill" id="slideshow-progress"></div>
        </div>

        <div class="footer-left">
            <i class="fa-solid fa-tv"></i>
            <span>SMARTSCHOOL MONITORING TV — Hari ini: {{ date('d-m-Y') }}</span>
        </div>

        <div class="slideshow-controls">
            <button class="control-btn" id="btn-prev" title="Slide Sebelumnya">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button class="control-btn" id="btn-play-pause" title="Jeda Slideshow">
                <i class="fa-solid fa-pause" id="play-pause-icon"></i>
            </button>
            <button class="control-btn" id="btn-next" title="Slide Selanjutnya">
                <i class="fa-solid fa-chevron-right"></i>
            </button>

            <div style="width: 1px; height: 20px; background: var(--border-card); margin: 0 4px;"></div>

            <div class="slide-indicators" id="slide-indicators-box">
                <!-- Dynamically populated dot indicators -->
            </div>
        </div>
    </footer>

    <script>
        // ── Digital Clock ──
        function updateClock() {
            const clockEl = document.getElementById('live-clock');
            const now = new Date();
            const hrs = String(now.getHours()).padStart(2, '0');
            const mins = String(now.getMinutes()).padStart(2, '0');
            const secs = String(now.getSeconds()).padStart(2, '0');
            clockEl.textContent = `${hrs}:${mins}:${secs}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // ── Slideshow Configuration ──
        const slideIds = [
            @foreach($groupedClasses as $tingkat => $classItems)
                'slide-grade-{{ $tingkat }}',
            @endforeach
            'slide-siswa',
            'slide-btaq',
            'slide-jurnal',
            'slide-pkl',
            'slide-uks',
            'slide-bk'
        ];

        let currentIdx = 0;
        let isPaused = false;
        const slideDuration = 60000; // 60 seconds per slide
        let lastTickTime = Date.now();
        let elapsedForSlide = 0;
        let animationFrameId = null;

        // ── Auto Scroll for Overflow Content ──
        let scrollInterval = null;
        function startAutoScroll(container) {
            stopAutoScroll();
            if (!container) return;
            
            container.scrollTop = 0;
            
            // Wait 2 seconds before beginning to scroll
            let delayTicks = 40; // 2 seconds delay (40 * 50ms)
            let direction = 1; // 1 = down, -1 = up
            
            scrollInterval = setInterval(() => {
                if (isPaused) return; // pause scroll if slideshow is paused
                
                if (delayTicks > 0) {
                    delayTicks--;
                    return;
                }
                
                const maxScroll = container.scrollHeight - container.clientHeight;
                if (maxScroll <= 0) {
                    stopAutoScroll();
                    return;
                }
                
                if (direction === 1) {
                    container.scrollTop += 0.8; // scroll down slowly
                    if (container.scrollTop >= maxScroll) {
                        direction = -1;
                        delayTicks = 40; // pause at bottom
                    }
                } else {
                    container.scrollTop -= 1.5; // scroll up faster
                    if (container.scrollTop <= 0) {
                        direction = 1;
                        delayTicks = 40; // pause at top
                    }
                }
            }, 50);
        }

        function stopAutoScroll() {
            if (scrollInterval) {
                clearInterval(scrollInterval);
                scrollInterval = null;
            }
        }

        const slides = slideIds.map(id => document.getElementById(id));
        const indicatorsBox = document.getElementById('slide-indicators-box');
        
        // Create indicator dots
        slideIds.forEach((id, idx) => {
            const dot = document.createElement('div');
            dot.className = 'indicator-dot' + (idx === 0 ? ' active' : '');
            dot.title = `Beralih ke slide ${idx + 1}`;
            dot.addEventListener('click', () => {
                goToSlide(idx);
            });
            indicatorsBox.appendChild(dot);
        });
        const dots = indicatorsBox.querySelectorAll('.indicator-dot');

        function goToSlide(idx) {
            // Stop scroll on old slide
            stopAutoScroll();

            // Detect loop-back: moving past last slide → soft refresh before going to 0
            if (idx >= slideIds.length) {
                softRefresh(() => {
                    goToSlide(0);
                });
                return;
            }

            // Remove active from old slide & dot
            slides[currentIdx].classList.remove('active');
            dots[currentIdx].classList.remove('active');

            // Set new index
            currentIdx = idx;
            if (currentIdx < 0) currentIdx = slideIds.length - 1;

            // Add active to new slide & dot
            slides[currentIdx].classList.add('active');
            dots[currentIdx].classList.add('active');

            // Reset progress
            elapsedForSlide = 0;
            lastTickTime = Date.now();
            updateProgressVisual(0);

            // Start auto scroll on new active container if it has scrollable classes
            const activeSlide = slides[currentIdx];
            const scrollContainer = activeSlide.querySelector('.grade-list-container');
            if (scrollContainer) {
                setTimeout(() => {
                    startAutoScroll(scrollContainer);
                }, 100);
            }

            // Re-animate ring if it's the journal slide
            if (slideIds[currentIdx] === 'slide-jurnal') {
                animateProgressRing();
            }
        }

        // ── Progress Bar Tick ──
        function slideshowTick() {
            const now = Date.now();
            const delta = now - lastTickTime;
            lastTickTime = now;

            if (!isPaused) {
                elapsedForSlide += delta;
                const percentage = Math.min((elapsedForSlide / slideDuration) * 100, 100);
                updateProgressVisual(percentage);

                if (elapsedForSlide >= slideDuration) {
                    goToSlide(currentIdx + 1);
                }
            }

            animationFrameId = requestAnimationFrame(slideshowTick);
        }

        function updateProgressVisual(percentage) {
            document.getElementById('slideshow-progress').style.width = percentage + '%';
        }

        // ── Soft Refresh (fetch live data without page reload) ──
        let chartSiswa = null;   // references to updatable charts
        let chartBtaq  = null;
        let chartJurnal = null;
        let chartUks   = null;
        let chartBk    = null;

        function softRefresh(callback) {
            const url = '{{ route("display.data") }}?tanggal=' + encodeURIComponent('{{ $tanggal }}');
            fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                .then(r => r.json())
                .then(d => {
                    // ── 1. Update grade slide HTML (journal status blocks) ──
                    Object.entries(d.gradeHtml).forEach(([tingkat, html]) => {
                        const container = document.querySelector('#slide-grade-' + tingkat + ' .grade-list-container');
                        if (container) container.innerHTML = html;
                    });

                    // ── 2. Update siswa stat cards ──
                    setText('siswa-total-val', d.totalSiswa.toLocaleString('id-ID'));
                    const siswaTotal = d.totalSiswa || 1;
                    const lPct = ((d.siswaLaki / siswaTotal) * 100).toFixed(1);
                    const pPct = ((d.siswaPerempuan / siswaTotal) * 100).toFixed(1);
                    const fillL = document.querySelector('.ratio-fill-l');
                    const fillP = document.querySelector('.ratio-fill-p');
                    if (fillL) fillL.style.width = lPct + '%';
                    if (fillP) fillP.style.width = pPct + '%';

                    // ── 3. Update UKS counts ──
                    const uksEl = document.querySelector('#slide-uks .card-stat-value');
                    if (uksEl) uksEl.textContent = d.uksToday.toLocaleString('id-ID');

                    // ── 4. Update BK counts ──
                    const bkEl = document.querySelector('#slide-bk .card-stat-value');
                    if (bkEl) bkEl.textContent = d.totalBk.toLocaleString('id-ID');

                    // ── 5. Update PKL count ──
                    const pklEl = document.querySelector('#slide-pkl .card-stat-value');
                    if (pklEl) pklEl.textContent = d.totalPklSiswa.toLocaleString('id-ID');

                    // ── 6. Update Chart datasets ──
                    if (chartSiswa) {
                        chartSiswa.data.labels = d.siswaTingkatLabels;
                        chartSiswa.data.datasets[0].data = d.siswaTingkatLaki;
                        chartSiswa.data.datasets[1].data = d.siswaTingkatPerempuan;
                        chartSiswa.update();
                    }
                    if (chartBtaq) {
                        chartBtaq.data.datasets[0].data = [d.btaqAlquranCount, d.btaqIqroCount, d.btaqKosongCount];
                        chartBtaq.update();
                    }
                    if (chartJurnal) {
                        chartJurnal.data.labels = d.monthlyTrendLabels;
                        chartJurnal.data.datasets[0].data = d.monthlyTrendData;
                        chartJurnal.update();
                    }
                    if (chartUks) {
                        chartUks.data.labels = d.monthlyUksLabels;
                        chartUks.data.datasets[0].data = d.monthlyUksData;
                        chartUks.update();
                    }
                    if (chartBk && d.problemTypesData.length > 0) {
                        chartBk.data.labels = d.problemTypesLabels;
                        chartBk.data.datasets[0].data = d.problemTypesData;
                        chartBk.update();
                    }

                    // ── 7. Update progress ring with live rate ──
                    animateProgressRingWithRate(d.todayCompletionRate, d.filledSchedulesCount, d.totalSchedulesCount);

                    // ── 8. Show a brief soft-refresh toast ──
                    showRefreshToast();

                })
                .catch(err => console.warn('Soft refresh failed:', err))
                .finally(() => {
                    if (callback) callback();
                });
        }

        function setText(id, val) {
            const el = document.getElementById(id);
            if (el) el.textContent = val;
        }

        function showRefreshToast() {
            let toast = document.getElementById('refresh-toast');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'refresh-toast';
                toast.style.cssText = 'position:fixed;bottom:80px;right:24px;background:rgba(16,185,129,0.9);color:#fff;padding:8px 18px;border-radius:20px;font-size:12px;font-weight:700;z-index:999;box-shadow:0 4px 14px rgba(0,0,0,0.12);transition:opacity 0.5s ease;';
                document.body.appendChild(toast);
            }
            toast.textContent = '⟳ Data diperbarui — ' + new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            toast.style.opacity = '1';
            setTimeout(() => { toast.style.opacity = '0'; }, 3000);
        }

        // Play / Pause Controls
        const btnPlayPause = document.getElementById('btn-play-pause');
        const playPauseIcon = document.getElementById('play-pause-icon');

        btnPlayPause.addEventListener('click', () => {
            isPaused = !isPaused;
            if (isPaused) {
                playPauseIcon.className = 'fa-solid fa-play';
                btnPlayPause.title = 'Lanjutkan Slideshow';
            } else {
                playPauseIcon.className = 'fa-solid fa-pause';
                btnPlayPause.title = 'Jeda Slideshow';
                lastTickTime = Date.now(); // reset delta reference
            }
        });

        document.getElementById('btn-prev').addEventListener('click', () => {
            goToSlide(currentIdx - 1);
        });

        document.getElementById('btn-next').addEventListener('click', () => {
            goToSlide(currentIdx + 1);
        });

        // Progress ring — static initial call (uses Blade value)
        function animateProgressRing() {
            animateProgressRingWithRate({{ $todayCompletionRate }}, {{ $filledSchedulesCount }}, {{ $totalSchedulesCount }});
        }

        // Progress ring — dynamic call (used by softRefresh)
        function animateProgressRingWithRate(rate, filled, total) {
            const circle = document.getElementById('today-progress-ring');
            if (!circle) return;

            const radius = circle.r.baseVal.value;
            const circumference = radius * 2 * Math.PI;

            circle.style.strokeDasharray = `${circumference} ${circumference}`;

            const unusedRate = rate; // kept to avoid redeclaration (rate is param)
            const offset = circumference - (rate / 100) * circumference;
            
            circle.style.strokeDashoffset = circumference;
            setTimeout(() => {
                circle.style.strokeDashoffset = offset;
                
                // Animate text counter
                const textEl = document.getElementById('today-progress-text');
                const subEl = document.getElementById('today-progress-sub');
                let count = 0;
                const end = rate;
                const duration = 1200; // 1.2s
                const stepTime = Math.abs(Math.floor(duration / end));
                
                if (end === 0) {
                    textEl.textContent = '0%';
                    subEl.textContent = `0 dari ${total} jadwal terisi`;
                    return;
                }

                const timer = setInterval(() => {
                    count += 0.5;
                    if (count >= end) {
                        textEl.textContent = end + '%';
                        clearInterval(timer);
                    } else {
                        textEl.textContent = Math.floor(count) + '%';
                    }
                }, stepTime / 2);

                subEl.textContent = `${filled} dari ${total} jadwal terisi`;
            }, 50);
        }

        // Start ticker loop
        animationFrameId = requestAnimationFrame(slideshowTick);

        // ── Chart.js Configurations ──
        document.addEventListener('DOMContentLoaded', () => {
            Chart.defaults.color = '#475569';
            Chart.defaults.font.family = 'Plus Jakarta Sans, sans-serif';

            // Chart 1: Siswa Per Tingkat — Grouped Bar (Laki-Laki vs Perempuan)
            const ctxTingkat = document.getElementById('siswaTingkatChart').getContext('2d');
            chartSiswa = new Chart(ctxTingkat, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($siswaTingkatLabels) !!},
                    datasets: [
                        {
                            label: 'Laki-Laki',
                            data: {!! json_encode($siswaTingkatLaki) !!},
                            backgroundColor: 'rgba(14, 165, 233, 0.70)',
                            borderColor: '#0ea5e9',
                            borderWidth: 2,
                            borderRadius: 6,
                        },
                        {
                            label: 'Perempuan',
                            data: {!! json_encode($siswaTingkatPerempuan) !!},
                            backgroundColor: 'rgba(236, 72, 153, 0.70)',
                            borderColor: '#ec4899',
                            borderWidth: 2,
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                font: { weight: 700, size: 13 },
                                padding: 16,
                                usePointStyle: true,
                                pointStyle: 'rectRounded'
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(ctx) {
                                    const total = ctx.chart.data.datasets.reduce((sum, ds) => sum + (ds.data[ctx.dataIndex] || 0), 0);
                                    const pct = total > 0 ? ((ctx.parsed.y / total) * 100).toFixed(1) : 0;
                                    return ` ${ctx.dataset.label}: ${ctx.parsed.y} (${pct}%)`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: 700, size: 13 } }
                        },
                        y: {
                            grid: { color: 'rgba(0,0,0,0.04)' },
                            ticks: { precision: 0, font: { weight: 600 } }
                        }
                    }
                }
            });

            // Chart 2: BTAQ Levels (Doughnut Chart)
            const ctxBtaq = document.getElementById('btaqDonutChart').getContext('2d');
            chartBtaq = new Chart(ctxBtaq, {
                type: 'doughnut',
                data: {
                    labels: ['Al-Qur\'an', 'Iqro', 'Belum Terdata'],
                    datasets: [{
                        data: [{{ $btaqAlquranCount }}, {{ $btaqIqroCount }}, {{ $btaqKosongCount }}],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.65)',  // Al-quran
                            'rgba(245, 158, 11, 0.65)',  // Iqro
                            'rgba(148, 163, 184, 0.45)'   // Empty
                        ],
                        borderColor: [
                            '#10b981',
                            '#f59e0b',
                            '#94a3b8'
                        ],
                        borderWidth: 2,
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
                                font: { weight: 600 },
                                padding: 16
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            // Chart 3: Tren Jurnal Bulanan (Line Chart)
            const ctxJurnal = document.getElementById('jurnalMonthlyChart').getContext('2d');
            const lineGradient = ctxJurnal.createLinearGradient(0, 0, 0, 200);
            lineGradient.addColorStop(0, 'rgba(79, 70, 229, 0.25)');
            lineGradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

            chartJurnal = new Chart(ctxJurnal, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyTrendLabels) !!},
                    datasets: [{
                        label: 'Total Jurnal Terisi',
                        data: {!! json_encode($monthlyTrendData) !!},
                        borderColor: '#4f46e5',
                        borderWidth: 3,
                        pointBackgroundColor: '#4f46e5',
                        pointBorderColor: '#ffffff',
                        pointHoverRadius: 6,
                        fill: true,
                        backgroundColor: lineGradient,
                        tension: 0.35
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        x: {
                            grid: { display: false },
                            ticks: { font: { weight: 600 } }
                        },
                        y: {
                            grid: { color: 'rgba(0,0,0,0.03)' }
                        }
                    }
                }
            });

            // Chart 4: PKL Top DUDI Placements (Horizontal Bar Chart)
            const ctxPkl = document.getElementById('pklDudiChart');
            if (ctxPkl) {
                new Chart(ctxPkl.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: {!! json_encode($topDudiLabels) !!},
                        datasets: [{
                            label: 'Jumlah Siswa',
                            data: {!! json_encode($topDudiData) !!},
                            backgroundColor: 'rgba(6, 182, 212, 0.65)',
                            borderColor: '#06b6d4',
                            borderWidth: 2,
                            borderRadius: 6,
                        }]
                    },
                    options: {
                        indexAxis: 'y', // makes it horizontal
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                grid: { color: 'rgba(0,0,0,0.03)' },
                                ticks: { precision: 0 }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { font: { weight: 600 } }
                            }
                        }
                    }
                });
            }

            // Chart 5: UKS Monthly Visits (Line Chart)
            const ctxUks = document.getElementById('uksMonthlyChart');
            if (ctxUks) {
                const uksGradient = ctxUks.getContext('2d').createLinearGradient(0, 0, 0, 150);
                uksGradient.addColorStop(0, 'rgba(239, 68, 68, 0.25)');
                uksGradient.addColorStop(1, 'rgba(239, 68, 68, 0)');

                chartUks = new Chart(ctxUks.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($monthlyUksLabels) !!},
                        datasets: [{
                            label: 'Jumlah Kunjungan',
                            data: {!! json_encode($monthlyUksData) !!},
                            borderColor: '#ef4444',
                            borderWidth: 3,
                            pointBackgroundColor: '#ef4444',
                            pointBorderColor: '#ffffff',
                            pointHoverRadius: 6,
                            fill: true,
                            backgroundColor: uksGradient,
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { font: { weight: 600 } }
                            },
                            y: {
                                grid: { color: 'rgba(0,0,0,0.03)' },
                                ticks: { precision: 0 }
                            }
                        }
                    }
                });
            }

            // Chart 6: BK Problem Distribution (Doughnut Chart)
            const ctxBk = document.getElementById('bkProblemChart');
            if (ctxBk) {
                chartBk = new Chart(ctxBk.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($problemTypesLabels) !!},
                        datasets: [{
                            data: {!! json_encode($problemTypesData) !!},
                            backgroundColor: [
                                'rgba(99, 102, 241, 0.65)',  // Indigo
                                'rgba(6, 182, 212, 0.65)',   // Cyan
                                'rgba(16, 185, 129, 0.65)',  // Emerald
                                'rgba(245, 158, 11, 0.65)',  // Amber
                                'rgba(239, 68, 68, 0.65)'    // Rose
                            ],
                            borderColor: [
                                '#6366f1',
                                '#06b6d4',
                                '#10b981',
                                '#f59e0b',
                                '#ef4444'
                            ],
                            borderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    font: { weight: 600 },
                                    padding: 12
                                }
                            }
                        },
                        cutout: '65%'
                    }
                });
            }

            // Initialize Ring Animation at Startup
            animateProgressRing();

            // Start auto scroll on the first active slide if it has grade-list-container
            const initialContainer = slides[0].querySelector('.grade-list-container');
            if (initialContainer) {
                startAutoScroll(initialContainer);
            }
        });
    </script>
</body>
</html>
