@extends('layouts.app')

@section('title', 'Jurnal Guru — SmartSchool')
@section('header_title', 'Jurnal Guru')
@section('header_subtitle', 'Agenda dan kemajuan belajar harian')

@section('content')
<style>
/* ═══════════════════════════════════════════
   DATE NAVIGATOR
═══════════════════════════════════════════ */
.date-navigator {
    display: flex;
    gap: 10px;
    margin-bottom: 24px;
}
.date-nav-box {
    flex: 1;
    background: #fff;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 18px 10px 14px;
    text-align: center;
    cursor: pointer;
    text-decoration: none !important;
    transition: all 0.22s ease;
    display: flex;
    flex-direction: column;
    align-items: center;
    color: var(--text-primary);
    position: relative;
    overflow: hidden;
}
.date-nav-box:hover {
    border-color: rgba(13,148,136,0.45);
    transform: translateY(-4px);
    box-shadow: 0 10px 28px rgba(13,148,136,0.13);
    color: var(--color-primary);
    text-decoration: none;
}
.date-nav-box.active-today {
    background: linear-gradient(135deg, #0d9488 0%, #10b981 100%);
    border-color: transparent;
    color: #fff !important;
    box-shadow: 0 10px 32px rgba(13,148,136,0.38);
    transform: translateY(-4px);
}
.date-nav-box.active-selected:not(.active-today) {
    border-color: var(--color-primary);
    background: rgba(13,148,136,0.07);
    color: var(--color-primary) !important;
    box-shadow: 0 6px 20px rgba(13,148,136,0.1);
}
.date-nav-today-pill {
    position: absolute;
    top: -1px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(255,255,255,0.25);
    color: #fff;
    font-size: 0.55rem;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 0 0 8px 8px;
    letter-spacing: 0.6px;
    white-space: nowrap;
    text-transform: uppercase;
}
.date-nav-day {
    font-size: 0.62rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    opacity: 0.65;
    margin-bottom: 6px;
}
.date-nav-num {
    font-size: 2.1rem;
    font-weight: 900;
    line-height: 1;
    margin-bottom: 3px;
}
.date-nav-month {
    font-size: 0.65rem;
    font-weight: 600;
    opacity: 0.7;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}
.date-nav-box.active-today .date-nav-day,
.date-nav-box.active-today .date-nav-month { opacity: 0.88; }
.date-nav-dot {
    width: 5px; height: 5px;
    border-radius: 50%;
    background: currentColor;
    opacity: 0.5;
    margin-top: 6px;
}
.date-nav-box.active-today .date-nav-dot { background: rgba(255,255,255,0.7); opacity: 1; }

.date-nav-box.date-nav-weekend {
    background: #fef2f2 !important;
    border-color: #fca5a5 !important;
    color: #ef4444 !important;
    opacity: 0.65;
    cursor: not-allowed !important;
    pointer-events: none;
    box-shadow: none !important;
    transform: none !important;
}
.date-nav-box.date-nav-weekend .date-nav-day,
.date-nav-box.date-nav-weekend .date-nav-num,
.date-nav-box.date-nav-weekend .date-nav-month {
    color: #ef4444 !important;
}
.date-nav-weekend-pill {
    position: absolute;
    top: -1px;
    left: 50%;
    transform: translateX(-50%);
    background: #ef4444;
    color: #fff;
    font-size: 0.52rem;
    font-weight: 800;
    padding: 2px 8px;
    border-radius: 0 0 8px 8px;
    letter-spacing: 0.6px;
    white-space: nowrap;
    text-transform: uppercase;
}

/* ═══════════════════════════════════════════
   PROGRESS BAR
═══════════════════════════════════════════ */
.progress-bar-wrap {
    background: #f1f5f9;
    border-radius: 99px;
    height: 6px;
    width: 140px;
    overflow: hidden;
}
.progress-bar-fill {
    height: 100%;
    border-radius: 99px;
    background: linear-gradient(90deg, #10b981, #0d9488);
    transition: width 0.4s ease;
}

/* ═══════════════════════════════════════════
   SCHEDULE LIST ROWS
═══════════════════════════════════════════ */
.schedule-list { display: flex; flex-direction: column; }

.schedule-row {
    display: flex;
    align-items: center;
    gap: 18px;
    padding: 18px 24px;
    border-bottom: 1px solid #f1f5f9;
    cursor: pointer;
    transition: background 0.15s ease;
    position: relative;
    text-decoration: none;
    color: inherit;
}
.schedule-row::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 4px;
    background: #e2e8f0;
    transition: background 0.2s ease;
}
.schedule-row.row-filled::before  { background: #10b981; }
.schedule-row.row-approved::before { background: #059669; }
.schedule-row.row-rejected::before { background: #ef4444; }
.schedule-row:hover { background: rgba(13,148,136,0.03); }
.schedule-row:last-child { border-bottom: none; }

.sched-jam-badge {
    min-width: 58px; height: 58px;
    border-radius: 14px;
    background: rgba(13,148,136,0.1);
    color: var(--color-primary);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    font-weight: 900;
    font-size: 1rem;
    line-height: 1;
    flex-shrink: 0;
    gap: 2px;
}
.sched-jam-label { font-size: 0.55rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.4px; opacity: 0.65; }

.sched-info { flex: 1; min-width: 0; }
.sched-guru-name {
    font-weight: 700;
    font-size: 0.93rem;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.sched-sub {
    font-size: 0.78rem;
    color: var(--text-muted);
    margin-top: 4px;
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}
.sched-sub span { display: flex; align-items: center; gap: 5px; }

.sched-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 7px;
    flex-shrink: 0;
}
.sched-action-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 7px 16px;
    border-radius: 99px;
    font-size: 0.78rem;
    font-weight: 700;
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    color: #475569;
    transition: all 0.18s ease;
    white-space: nowrap;
}
.sched-action-pill.pill-filled {
    background: rgba(16,185,129,0.1);
    border-color: rgba(16,185,129,0.35);
    color: #059669;
}
.schedule-row:hover .sched-action-pill { background: var(--color-primary); border-color: var(--color-primary); color: #fff; }

/* ═══════════════════════════════════════════
   PHOTO UPLOAD ZONES
═══════════════════════════════════════════ */
.photo-upload-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 12px;
}
.photo-zone {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    min-height: 130px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 7px;
    cursor: pointer;
    background: #f8fafc;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
    padding: 16px 10px;
    text-align: center;
}
.photo-zone:hover {
    border-color: var(--color-primary);
    background: rgba(13,148,136,0.05);
}
.photo-zone.pz-add-btn {
    border-style: dashed;
    border-color: #cbd5e1;
    background: #f8fafc;
    color: #64748b;
}
.photo-zone.pz-add-btn:hover {
    border-color: var(--color-primary);
    background: rgba(13,148,136,0.05);
    color: var(--color-primary);
}
.photo-zone.pz-add-btn i {
    font-size: 1.6rem;
    margin-bottom: 2px;
}
.photo-zone.pz-add-btn span {
    font-size: 0.74rem;
    font-weight: 700;
}
.photo-zone.has-photo {
    border-style: solid;
    border-color: var(--color-primary);
    background: #fff;
    padding: 0;
}
.photo-zone .pz-preview {
    width: 100%;
    height: 130px;
    object-fit: cover;
    display: none;
    border-radius: 10px;
}
.photo-zone.has-photo .pz-preview { display: block; }
.photo-zone .pz-placeholder { pointer-events: none; display: flex; flex-direction: column; align-items: center; gap: 6px; }
.photo-zone.has-photo .pz-placeholder { display: none; }
.pz-icon { font-size: 1.6rem; color: #94a3b8; }
.pz-label { font-size: 0.74rem; font-weight: 700; color: var(--text-muted); }
.pz-sublabel { font-size: 0.64rem; color: #94a3b8; }
.pz-remove {
    position: absolute;
    top: 6px; right: 6px;
    width: 24px; height: 24px;
    border-radius: 50%;
    background: rgba(239,68,68,0.9);
    color: #fff;
    border: none;
    display: none;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.65rem;
    z-index: 5;
    transition: transform 0.15s ease;
}
.photo-zone.has-photo .pz-remove { display: flex; }
.pz-remove:hover { transform: scale(1.15); }
.pz-num-badge {
    position: absolute;
    bottom: 6px; left: 8px;
    background: rgba(0,0,0,0.45);
    color: #fff;
    font-size: 0.6rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 99px;
    display: none;
    z-index: 4;
}
.photo-zone.has-photo .pz-num-badge { display: block; }

/* ═══════════════════════════════════════════
   STUDENT ATTENDANCE
   ═══════════════════════════════════════════ */
.attend-container {
    max-height: 290px;
    overflow-y: auto;
    padding-right: 4px;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.attend-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    padding: 8px 14px;
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 12px;
    transition: all 0.15s ease;
}
.attend-row:hover {
    border-color: #cbd5e1;
    background: #f8fafc;
}
.attend-student-name {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
}
.attend-options {
    display: flex;
    gap: 5px;
    flex-shrink: 0;
}
.attend-opt-btn {
    border: 1.5px solid #e2e8f0;
    background: #f8fafc;
    color: #64748b;
    padding: 5px 12px;
    font-size: 0.72rem;
    font-weight: 700;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.15s ease;
    user-select: none;
    outline: none;
    display: flex;
    align-items: center;
    gap: 4px;
}
.attend-opt-btn:hover {
    border-color: #cbd5e1;
    color: #475569;
}
.attend-opt-btn.opt-hadir.active {
    background: #10b981;
    border-color: #10b981;
    color: #fff;
    box-shadow: 0 2px 6px rgba(16,185,129,0.2);
}
.attend-opt-btn.opt-sakit.active {
    background: #f59e0b;
    border-color: #f59e0b;
    color: #fff;
    box-shadow: 0 2px 6px rgba(245,158,11,0.2);
}
.attend-opt-btn.opt-ijin.active {
    background: #3b82f6;
    border-color: #3b82f6;
    color: #fff;
    box-shadow: 0 2px 6px rgba(59,130,246,0.2);
}
.attend-opt-btn.opt-alpha.active {
    background: #ef4444;
    border-color: #ef4444;
    color: #fff;
    box-shadow: 0 2px 6px rgba(239,68,68,0.2);
}

/* ═══════════════════════════════════════════
   MODAL OVERRIDES
═══════════════════════════════════════════ */
.modal-jurnal-body {
    max-height: calc(90vh - 180px);
    overflow-y: auto;
    padding: 0;
}
.modal-section {
    padding: 20px 24px;
    border-bottom: 1px solid #f1f5f9;
}
.modal-section:last-child { border-bottom: none; }
.section-label {
    font-size: 0.74rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    margin-bottom: 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 8px;
}
.modal-info-bar {
    background: linear-gradient(135deg, rgba(13,148,136,0.07), rgba(99,102,241,0.05));
    padding: 14px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    flex-wrap: wrap;
    gap: 12px 20px;
}
.modal-info-chip {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-primary);
}
.modal-info-chip i { width: 18px; text-align: center; }

/* ═══════════════════════════════════════════
   FILTER CARD & ELEMENTS
   ═══════════════════════════════════════════ */
.filter-card {
    background: #fff;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 16px 20px;
    margin-bottom: 20px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
}
.filter-row {
    display: flex;
    align-items: flex-end;
    gap: 16px;
    flex-wrap: wrap;
}
.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex: 1;
    min-width: 180px;
}
.filter-group-date {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 2;
    min-width: 280px;
}
.filter-label {
    font-size: 0.72rem;
    font-weight: 700;
    color: var(--text-primary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.filter-select, .filter-input {
    padding: 9px 12px;
    border-radius: 10px;
    border: 1.5px solid #cbd5e1;
    font-size: 0.82rem;
    font-weight: 600;
    background-color: #fff;
    color: var(--text-primary);
    transition: all 0.2s ease;
    width: 100%;
}
.filter-select:focus, .filter-input:focus {
    border-color: var(--color-primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(13,148,136,0.15);
}
.btn-filter-submit {
    background: var(--color-primary);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 10px 20px;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
    height: 38px;
}
.btn-filter-submit:hover {
    background: #0b7a70;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(13,148,136,0.25);
}
.btn-filter-reset {
    background: #f8fafc;
    color: #475569;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    padding: 8px 18px;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none !important;
    display: flex;
    align-items: center;
    gap: 8px;
    height: 38px;
    box-sizing: border-box;
}
.btn-filter-reset:hover {
    background: #f1f5f9;
    color: #1e293b;
    border-color: #94a3b8;
}

/* ═══════════════════════════════════════════
   CLASS TABS BAR
   ═══════════════════════════════════════════ */
.class-tabs-container {
    display: flex;
    gap: 8px;
    padding: 12px 24px;
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    overflow-x: auto;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
}
.class-tabs-container::-webkit-scrollbar {
    height: 4px;
}
.class-tabs-container::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}
.class-tab-btn {
    border: 1.5px solid #cbd5e1;
    background: #fff;
    color: #475569;
    padding: 8px 16px;
    font-size: 0.8rem;
    font-weight: 700;
    border-radius: 99px;
    cursor: pointer;
    transition: all 0.18s ease;
    user-select: none;
    outline: none;
}
.class-tab-btn:hover {
    border-color: #94a3b8;
    color: #1e293b;
    background: #f1f5f9;
}
.class-tab-btn.active {
    background: var(--color-primary);
    border-color: var(--color-primary);
    color: #fff;
    box-shadow: 0 4px 12px rgba(13,148,136,0.25);
}

@media (max-width: 768px) {
    .date-navigator { gap: 6px; }
    .date-nav-num { font-size: 1.5rem; }
    .date-nav-box { padding: 14px 6px 10px; border-radius: 12px; }
    .photo-upload-grid { grid-template-columns: 1fr 1fr 1fr; gap: 8px; }
    .attend-grid { grid-template-columns: 1fr 1fr; }
    .schedule-row { padding: 14px 16px; gap: 12px; }
    .sched-jam-badge { min-width: 48px; height: 48px; font-size: 0.85rem; }
}

.ck-editor__editable_inline {
    min-height: 150px !important;
    max-height: 300px !important;
}
</style>

<div class="page-content">
    @include('partials.flash')

    {{-- ── Filter Card ── --}}
    <div class="filter-card">
        <form action="{{ route('jurnal-guru.index') }}" method="GET" class="filter-form">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="filter-periode" class="filter-label">Periode</label>
                    <select name="periode" id="filter-periode" class="filter-select">
                        <option value="hari_ini" {{ $periode === 'hari_ini' ? 'selected' : '' }}>Hari Ini</option>
                        <option value="minggu_ini" {{ $periode === 'minggu_ini' ? 'selected' : '' }}>Minggu Ini</option>
                        <option value="bulan_ini" {{ $periode === 'bulan_ini' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="tanggal_pilihan" {{ $periode === 'tanggal_pilihan' ? 'selected' : '' }}>Tanggal Pilihan</option>
                    </select>
                </div>

                <div class="filter-group-date" id="custom-date-group" style="display: none;">
                    <div style="flex: 1;">
                        <label for="tanggal_dari" class="filter-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" id="tanggal_dari" class="filter-input" value="{{ $tanggal_dari }}">
                    </div>
                    <div style="flex: 1;">
                        <label for="tanggal_sampai" class="filter-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" id="tanggal_sampai" class="filter-input" value="{{ $tanggal_sampai }}">
                    </div>
                </div>

                <div style="display: flex; gap: 10px;">
                    <button type="submit" class="btn-filter-submit">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    @if($periode !== 'hari_ini' || $tanggal !== date('Y-m-d'))
                        <a href="{{ route('jurnal-guru.index') }}" class="btn-filter-reset">
                            <i class="fa-solid fa-arrow-rotate-left"></i> Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- ── Date Navigator ── --}}
    @if($periode === 'hari_ini')
        <div class="date-navigator">
            @foreach($dateNav as $d)
            @php
                $dStr     = $d->format('Y-m-d');
                $isToday  = $dStr === date('Y-m-d');
                $isActive = $dStr === $tanggal;
                $hasJadwal = in_array($dStr, $datesWithJadwal);
                $isWeekend = $d->isSaturday() || $d->isSunday();
                $boxClass = $isWeekend ? 'date-nav-weekend' : ($isToday ? 'active-today' : ($isActive ? 'active-selected' : ''));
            @endphp
            <a @if(!$isWeekend) href="{{ route('jurnal-guru.index', ['tanggal' => $dStr, 'periode' => 'hari_ini']) }}" @endif
               class="date-nav-box {{ $boxClass }}"
               @if($isWeekend) style="pointer-events: none; cursor: not-allowed; opacity: 0.65;" @endif>
                @if($isToday && !$isWeekend)
                    <span class="date-nav-today-pill">Hari Ini</span>
                @elseif($isWeekend)
                    <span class="date-nav-weekend-pill">Libur</span>
                @endif
                <span class="date-nav-day">{{ $d->translatedFormat('D') }}</span>
                <span class="date-nav-num">{{ $d->format('d') }}</span>
                <span class="date-nav-month">{{ $d->translatedFormat('M Y') }}</span>
                @if($hasJadwal && !$isWeekend)
                    <span class="date-nav-dot"></span>
                @endif
            </a>
            @endforeach
        </div>
    @else
        <div class="alert alert-info" style="margin-bottom: 24px; border-radius: 16px; background: rgba(13,148,136,0.07); border: 1px solid rgba(13,148,136,0.2); color: var(--color-primary); display: flex; align-items: center; gap: 12px; padding: 14px 20px; font-weight: 600; font-size: 0.88rem;">
            <i class="fa-solid fa-calendar-days" style="font-size: 1.2rem;"></i>
            <div>
                <span style="opacity: 0.85;">Menampilkan jadwal untuk periode:</span> &nbsp;
                <strong>
                    @if($periode === 'minggu_ini')
                        Minggu Ini ({{ \Carbon\Carbon::now()->startOfWeek()->translatedFormat('d M Y') }} &mdash; {{ \Carbon\Carbon::now()->endOfWeek()->translatedFormat('d M Y') }})
                    @elseif($periode === 'bulan_ini')
                        Bulan Ini ({{ \Carbon\Carbon::now()->startOfMonth()->translatedFormat('F Y') }})
                    @elseif($periode === 'tanggal_pilihan')
                        {{ \Carbon\Carbon::parse($tanggal_dari)->translatedFormat('d M Y') }} &mdash; {{ \Carbon\Carbon::parse($tanggal_sampai)->translatedFormat('d M Y') }}
                    @endif
                </strong>
            </div>
        </div>
    @endif

    {{-- ── Schedule Card ── --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fa-solid fa-chalkboard-user"></i>
                @if($periode === 'hari_ini')
                    Jadwal &mdash; {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y') }}
                @elseif($periode === 'minggu_ini')
                    Jadwal Minggu Ini
                @elseif($periode === 'bulan_ini')
                    Jadwal Bulan Ini
                @elseif($periode === 'tanggal_pilihan')
                    Jadwal Periode Pilihan
                @endif
            </h2>
            @php
                $filledCount = $jadwalList->filter(fn($j) => $j->jurnal)->count();
                $total = $jadwalList->count();
                $pct = $total > 0 ? round($filledCount / $total * 100) : 0;
            @endphp
            @if($total > 0)
            <div class="card-header-right" style="gap:12px; align-items:center;">
                <span style="font-size:0.8rem; color:var(--text-muted);" id="tab-filled-summary">
                    <strong style="color:var(--color-primary);">{{ $filledCount }}</strong> / {{ $total }} jurnal terisi
                </span>
                <div class="progress-bar-wrap">
                    <div class="progress-bar-fill" id="tab-progress-bar" style="width:{{ $pct }}%;"></div>
                </div>
            </div>
            @endif
        </div>

        @php
            $uniqueClasses = [];
            foreach ($jadwalList as $j) {
                if ($j->kelas) {
                    $cId = $j->id_kelas;
                    $cName = $j->kelas->tingkat . ' ' . $j->kelas->rombel;
                    $uniqueClasses[$cId] = $cName;
                }
            }
            asort($uniqueClasses);
        @endphp

        @if(count($uniqueClasses) > 0)
        <div class="class-tabs-container">
            <button type="button" class="class-tab-btn active" onclick="filterByClass('all', this)">Semua Kelas</button>
            @foreach($uniqueClasses as $cId => $cName)
                <button type="button" class="class-tab-btn" onclick="filterByClass('{{ $cId }}', this)">{{ $cName }}</button>
            @endforeach
        </div>
        @endif

        <div class="card-body p-0">
            <div class="schedule-list">
                @forelse($jadwalList as $jadwal)
                @php
                    $jrn       = $jadwal->jurnal;
                    $hasJrn    = !is_null($jrn);
                    $isApproved= $hasJrn && $jrn->status_approval === 'approved';
                    $isRejected= $hasJrn && $jrn->status_approval === 'rejected';
                    $rowClass  = $hasJrn ? ($isApproved ? 'row-approved' : ($isRejected ? 'row-rejected' : 'row-filled')) : '';

                    $namaKelas = '';
                    if ($jadwal->kelas) {
                        $namaKelas = $jadwal->kelas->tingkat . ' ' . $jadwal->kelas->rombel;
                    }

                    $payload = [
                        'id_kemajuan'    => $hasJrn ? $jrn->id_kemajuan : null,
                        'tanggal'        => $jadwal->tanggal->format('Y-m-d'),
                        'jam_ke'         => (string) $jadwal->jam_ke,
                        'id_guru'        => $jadwal->id_guru,
                        'nama_guru'      => $jadwal->guru->nama_guru ?? '—',
                        'id_mapel'       => $jadwal->id_mapel,
                        'nama_mapel'     => $jadwal->mapel->nama_mapel ?? '—',
                        'id_kelas'       => $jadwal->id_kelas,
                        'nama_kelas'     => $namaKelas ?: '—',
                        'materi'         => $hasJrn ? ($jrn->materi ?? '') : '',
                        'jml_siswa'      => $hasJrn ? $jrn->jml_siswa : 0,
                        'absen'          => $hasJrn ? ($jrn->absen ?? '') : '',
                        'keterangan'     => $hasJrn ? ($jrn->keterangan ?? '') : '',
                        'status_approval'=> $hasJrn ? ($jrn->status_approval ?? null) : null,
                        'fotos'          => ($hasJrn && is_array($jrn->fotos)) ? array_map(fn($f) => request()->getSchemeAndHttpHost().'/storage/'.$f, $jrn->fotos) : (($hasJrn && $jrn->foto_1) ? array_filter([($jrn->foto_1 ? request()->getSchemeAndHttpHost().'/storage/'.$jrn->foto_1 : null), ($jrn->foto_2 ? request()->getSchemeAndHttpHost().'/storage/'.$jrn->foto_2 : null), ($jrn->foto_3 ? request()->getSchemeAndHttpHost().'/storage/'.$jrn->foto_3 : null)]) : []),
                    ];
                @endphp
                <div class="schedule-row {{ $rowClass }}"
                     data-class-id="{{ $jadwal->id_kelas }}"
                     onclick="openJurnalModal({{ json_encode($payload) }})">

                    {{-- Jam Badge --}}
                    <div class="sched-jam-badge">
                        <span>{{ $jadwal->jam_ke }}</span>
                        <span class="sched-jam-label">Jam</span>
                    </div>

                    {{-- Info --}}
                    <div class="sched-info">
                        <div class="sched-guru-name">{{ $jadwal->guru->nama_guru ?? '—' }}</div>
                        <div class="sched-sub">
                            @if($periode !== 'hari_ini')
                                <span><i class="fa-solid fa-calendar-day" style="font-size:0.68rem; color:#e11d48;"></i> {{ $jadwal->tanggal->translatedFormat('d M Y') }}</span>
                            @endif
                            <span><i class="fa-solid fa-book" style="font-size:0.68rem; color:#10b981;"></i> {{ $jadwal->mapel->nama_mapel ?? '—' }}</span>
                            <span><i class="fa-solid fa-chalkboard" style="font-size:0.68rem; color:#6366f1;"></i> {{ $namaKelas ?: '—' }}</span>
                            @if($hasJrn)
                            <span><i class="fa-solid fa-users" style="font-size:0.68rem; color:#f59e0b;"></i> {{ $jrn->jml_siswa }} siswa hadir</span>
                            @endif
                        </div>
                    </div>

                    {{-- Right: Status + Action --}}
                    <div class="sched-right">
                        @if($hasJrn)
                            @if($isApproved)
                                <span class="badge badge-success" style="font-size:0.67rem; padding:3px 8px; background:#10b981; color:#fff;">
                                    <i class="fa-solid fa-circle-check"></i> Disetujui
                                </span>
                            @elseif($isRejected)
                                <span class="badge badge-danger" style="font-size:0.67rem; padding:3px 8px;">
                                    <i class="fa-solid fa-circle-xmark"></i> Ditolak
                                </span>
                            @else
                                <span class="badge" style="font-size:0.67rem; padding:3px 8px; background:#f59e0b; color:#fff;">
                                    <i class="fa-solid fa-clock"></i> Menunggu
                                </span>
                            @endif
                            <span class="sched-action-pill pill-filled">
                                <i class="fa-solid fa-eye"></i> Lihat / Edit
                            </span>
                        @else
                            <span class="badge" style="font-size:0.67rem; padding:3px 8px; background:#94a3b8; color:#fff;">
                                <i class="fa-solid fa-circle-minus"></i> Belum Mengisi
                            </span>
                            <span class="sched-action-pill">
                                <i class="fa-solid fa-pen-to-square"></i> Isi Jurnal
                            </span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="empty-state" style="padding:52px 20px; text-align:center; color:var(--text-muted);">
                    <i class="fa-solid fa-calendar-xmark" style="font-size:2.8rem; opacity:0.2; display:block; margin-bottom:14px;"></i>
                    <p style="font-weight:600; margin-bottom:6px;">Tidak ada jadwal mengajar untuk periode ini.</p>
                    <p style="font-size:0.8rem;">Pastikan jadwal harian sudah di-generate di menu
                        <a href="{{ route('jadwal-mengajar.index') }}" style="color:var(--color-primary);">Jadwal Mengajar</a>.
                    </p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════════════════════════════
     MODAL JURNAL GURU
════════════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-jurnal">
    <div class="modal" style="max-width:820px; width:95vw; max-height:92vh; display:flex; flex-direction:column; padding:0; overflow:hidden;">

        {{-- Header (sticky) --}}
        <div class="modal-header" style="padding:18px 24px; border-bottom:1px solid #e2e8f0; flex-shrink:0;">
            <h3 id="modal-jurnal-title" style="display:flex; align-items:center; gap:10px;">
                <i class="fa-solid fa-book-open-reader" style="color:var(--color-primary);"></i>
                Isi Jurnal Guru
            </h3>
            <button onclick="closeJurnalModal()" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>

        {{-- Info bar (sticky) --}}
        <div class="modal-info-bar" id="modal-info-bar" style="flex-shrink:0;">
            <div class="modal-info-chip"><i class="fa-solid fa-calendar" style="color:var(--color-primary);"></i> <span id="info-tanggal"></span></div>
            <div class="modal-info-chip"><i class="fa-solid fa-clock" style="color:#6366f1;"></i> Jam ke-<span id="info-jam"></span></div>
            <div class="modal-info-chip"><i class="fa-solid fa-chalkboard-user" style="color:#f59e0b;"></i> <span id="info-guru"></span></div>
            <div class="modal-info-chip"><i class="fa-solid fa-book" style="color:#10b981;"></i> <span id="info-mapel"></span></div>
            <div class="modal-info-chip"><i class="fa-solid fa-users" style="color:#8b5cf6;"></i> <span id="info-kelas"></span></div>
        </div>

        {{-- Scrollable body --}}
        <div class="modal-jurnal-body">
            <form id="form-jurnal" enctype="multipart/form-data" onsubmit="submitJurnalForm(event)">
                @csrf
                <input type="hidden" name="tanggal"   id="j_tanggal">
                <input type="hidden" name="jam_ke"    id="j_jam_ke">
                <input type="hidden" name="id_guru"   id="j_id_guru">
                <input type="hidden" name="id_mapel"  id="j_id_mapel">
                <input type="hidden" name="id_kelas"  id="j_id_kelas">
                <input type="hidden" name="jml_siswa" id="j_jml_siswa" value="0">
                <input type="hidden" name="absen"     id="j_absen">

                {{-- ── Materi ── --}}
                <div class="modal-section">
                    <div class="section-label" style="color:var(--color-primary);">
                        <span><i class="fa-solid fa-graduation-cap"></i> &nbsp;Materi Pembelajaran <span class="required" style="color:#ef4444;">*</span></span>
                    </div>
                    <textarea name="materi" id="j_materi" class="form-control" rows="3"
                              placeholder="Tuliskan topik / materi yang diajarkan hari ini..."></textarea>
                </div>

                {{-- ── Jumlah Siswa (auto + manual fallback) ── --}}
                <div class="modal-section" style="padding-top:16px; padding-bottom:16px;">
                    <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; flex-wrap:wrap;">
                        <div class="section-label" style="color:#6366f1; margin-bottom:0;">
                            <span><i class="fa-solid fa-user-check"></i> &nbsp;Jumlah Siswa Hadir <span class="required" style="color:#ef4444;">*</span></span>
                        </div>
                        <div style="display:flex; align-items:center; gap:10px;">
                            <input type="number" id="j_jml_display" class="form-control" min="0"
                                   style="width:90px; font-weight:700; font-size:1rem; text-align:center;"
                                   placeholder="0" oninput="document.getElementById('j_jml_siswa').value = this.value">
                            <span id="jml-auto-note" style="display:none; font-size:0.72rem; color:var(--color-primary); font-weight:600;">
                                <i class="fa-solid fa-bolt"></i> Otomatis
                            </span>
                        </div>
                    </div>
                </div>

                {{-- ── Absensi Siswa ── --}}
                <div class="modal-section">
                    <div class="section-label" style="color:#6366f1;">
                        <span><i class="fa-solid fa-clipboard-user"></i> &nbsp;Absensi Siswa</span>
                        <span id="attend-summary" style="font-size:0.75rem; font-weight:600; color:var(--text-muted); text-transform:none; letter-spacing:0;"></span>
                    </div>

                    <div id="attend-loading" style="text-align:center; padding:24px; color:var(--text-muted); font-size:0.85rem;">
                        <i class="fa-solid fa-spinner fa-spin" style="margin-right:6px;"></i> Memuat daftar siswa...
                    </div>
                    <div id="attend-wrapper" style="display:none;">
                        <div id="attend-instruction" class="alert alert-info" style="margin-bottom: 12px; border-radius: 10px; background: #f0f9ff; border: 1px solid #bae6fd; color: #0369a1; padding: 10px 14px; font-size: 0.78rem; font-weight: 500; display: flex; align-items: start; gap: 8px;">
                            <i class="fa-solid fa-circle-info" style="font-size: 0.95rem; color: #0284c7; margin-top: 1px;"></i>
                            <div>
                                <strong>Petunjuk:</strong> Tekan tombol status presensi (Hadir, Sakit, Ijin, atau Alpha) di sebelah kanan nama siswa untuk mengubah status presensi mereka secara langsung.
                            </div>
                        </div>
                        <div class="attend-container" id="attend-grid"></div>
                    </div>
                    <div id="attend-empty" style="display:none; text-align:center; padding:20px; font-size:0.82rem; color:var(--text-muted);">
                        <i class="fa-solid fa-users-slash" style="font-size:1.6rem; opacity:0.25; display:block; margin-bottom:8px;"></i>
                        Tidak ada data siswa aktif untuk kelas ini.
                    </div>
                    <div id="attend-error" style="display:none; font-size:0.8rem; color:#ef4444; padding:10px 0;">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                        Gagal memuat daftar siswa. Isi jumlah hadir secara manual di atas.
                    </div>
                </div>

                {{-- ── Foto Dokumentasi ── --}}
                <div class="modal-section">
                    <div class="section-label" style="color:#d97706;">
                        <span><i class="fa-solid fa-camera"></i> &nbsp;Foto Dokumentasi <span class="required" style="color:#ef4444;">*</span></span>
                        <span style="font-size:0.7rem; font-weight:400; text-transform:none; color:var(--text-muted);">Wajib diisi minimal 3 foto • maks. 5 MB per foto • JPG/PNG/WEBP</span>
                    </div>
                    <div class="photo-upload-grid" id="photo-upload-container">
                        <!-- Dynamic photo slots will be rendered here by JS -->
                    </div>
                    {{-- Container for file/hidden inputs --}}
                    <div id="hidden-photo-inputs" style="display:none;"></div>
                </div>

                {{-- ── Keterangan ── --}}
                <div class="modal-section">
                    <div class="section-label" style="color:#8b5cf6;">
                        <span><i class="fa-solid fa-note-sticky"></i> &nbsp;Catatan / Keterangan</span>
                        <span style="font-size:0.7rem; font-weight:400; text-transform:none; color:var(--text-muted);">Opsional</span>
                    </div>
                    <textarea name="keterangan" id="j_keterangan" class="form-control" rows="2"
                              placeholder="Evaluasi, catatan kendala, atau hal penting lainnya..."></textarea>
                </div>

            </form>{{-- end form --}}
        </div>{{-- end scrollable body --}}

        {{-- Footer (sticky) --}}
        <div class="modal-footer" style="border-top:1px solid #e2e8f0; flex-shrink:0; gap:10px;">

            {{-- Approve/Reject (left side, shown for non-approved journals) --}}
            <div id="approval-actions" style="margin-right:auto; display:none; gap:8px;">
                <form id="form-approve" method="POST" style="display:inline;" onsubmit="return handleApprove(event,'approve')">@csrf</form>
                <button type="button" onclick="doApprove()" class="btn btn-sm" style="background:#10b981;color:#fff;border-color:#10b981;padding:7px 14px;">
                    <i class="fa-solid fa-check"></i> Setujui
                </button>
                <button type="button" onclick="doReject()" class="btn btn-sm" style="background:#ef4444;color:#fff;border-color:#ef4444;padding:7px 14px;">
                    <i class="fa-solid fa-xmark"></i> Tolak
                </button>
            </div>

            <button type="button" onclick="closeJurnalModal()" class="btn btn-secondary">Batal</button>
            <button type="submit" form="form-jurnal" class="btn btn-primary" id="btn-save-jurnal">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Jurnal
            </button>
        </div>
    </div>
</div>

{{-- Lightbox untuk foto --}}
<div id="lightbox-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.88); z-index:9999; align-items:center; justify-content:center; cursor:pointer;"
     onclick="closeLightbox()">
    <img id="lightbox-img" src="" alt="" style="max-width:92vw; max-height:90vh; border-radius:10px; box-shadow:0 20px 60px rgba(0,0,0,0.5);">
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
// ═════════════════════════════════════════════════════
// STATE
// ═════════════════════════════════════════════════════
let currentData   = null;
let studentList   = [];
let studentStatus  = {}; // { nis: 'hadir' | 'sakit' | 'ijin' | 'alpha' }
let currentJurnalId = null;
let isCurrentJurnalApproved = false;
let materiEditor = null;

const IND_MONTHS = ['Januari','Februari','Maret','April','Mei','Juni',
                    'Juli','Agustus','September','Oktober','November','Desember'];
const IND_DAYS   = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];

function formatTanggalID(iso) {
    const d = new Date(iso + 'T00:00:00');
    return `${IND_DAYS[d.getDay()]}, ${d.getDate()} ${IND_MONTHS[d.getMonth()]} ${d.getFullYear()}`;
}

// ═════════════════════════════════════════════════════
// OPEN MODAL
// ═════════════════════════════════════════════════════
function openJurnalModal(data) {
    currentData     = data;
    currentJurnalId = data.id_kemajuan;

    const isApproved = data.status_approval === 'approved';
    isCurrentJurnalApproved = isApproved;

    // ── Info bar ──
    document.getElementById('info-tanggal').textContent = formatTanggalID(data.tanggal);
    document.getElementById('info-jam').textContent     = data.jam_ke;
    document.getElementById('info-guru').textContent    = data.nama_guru;
    document.getElementById('info-mapel').textContent   = data.nama_mapel;
    document.getElementById('info-kelas').textContent   = data.nama_kelas;

    // ── Hidden fields ──
    document.getElementById('j_tanggal').value  = data.tanggal;
    document.getElementById('j_jam_ke').value   = data.jam_ke;
    document.getElementById('j_id_guru').value  = data.id_guru;
    document.getElementById('j_id_mapel').value = data.id_mapel;
    document.getElementById('j_id_kelas').value = data.id_kelas;

    // ── Form action ──
    const form = document.getElementById('form-jurnal');
    form.action = data.id_kemajuan ? `/jurnal-guru/${data.id_kemajuan}` : '/jurnal-guru';

    // ── Modal title ──
    const titleIcon = data.id_kemajuan
        ? '<i class="fa-solid fa-pen-to-square" style="color:var(--color-primary);"></i>'
        : '<i class="fa-solid fa-book-open-reader" style="color:var(--color-primary);"></i>';
    document.getElementById('modal-jurnal-title').innerHTML =
        titleIcon + (data.id_kemajuan ? ' Edit Jurnal Guru' : ' Isi Jurnal Guru');

    // ── Fill form fields ──
    let keteranganVal = '';
    if (data.keterangan) {
        try {
            const parsed = JSON.parse(data.keterangan);
            if (parsed && typeof parsed === 'object') {
                keteranganVal = parsed.hambatan || '';
            } else {
                keteranganVal = data.keterangan;
            }
        } catch (e) {
            keteranganVal = data.keterangan;
        }
    }
    document.getElementById('j_keterangan').value = keteranganVal;
    if (materiEditor) {
        materiEditor.setData(data.materi || '');
        if (isApproved) {
            materiEditor.enableReadOnlyMode('editor-lock');
        } else {
            materiEditor.disableReadOnlyMode('editor-lock');
        }
    } else {
        document.getElementById('j_materi').value = data.materi || '';
    }

    // ── Photos ──
    initPhotos(data.fotos || []);

    // ── Locked state (approved) ──
    document.getElementById('btn-save-jurnal').style.display  = isApproved ? 'none' : '';
    document.getElementById('j_materi').readOnly              = isApproved;
    document.getElementById('j_keterangan').readOnly          = isApproved;
    document.querySelectorAll('#photo-upload-container .photo-zone').forEach(z => z.style.pointerEvents = isApproved ? 'none' : '');
    document.getElementById('attend-instruction').style.display = isApproved ? 'none' : 'flex';

    // ── Approval actions (shown for filled but not yet approved) ──
    const approvalDiv = document.getElementById('approval-actions');
    approvalDiv.style.display = (data.id_kemajuan && !isApproved) ? 'flex' : 'none';

    // ── Load students ──
    loadStudents(data.id_kelas, data.absen || '', data.jml_siswa || 0);

    openModal('modal-jurnal');
}

function closeJurnalModal() {
    closeModal('modal-jurnal');
    // reset
    studentList = []; studentStatus = {}; currentData = null; currentJurnalId = null; isCurrentJurnalApproved = false;
    journalPhotos = [];
    if (materiEditor) {
        materiEditor.setData('');
        materiEditor.disableReadOnlyMode('editor-lock');
    }
    document.getElementById('photo-upload-container').innerHTML = '';
    document.getElementById('hidden-photo-inputs').innerHTML = '';
    document.getElementById('form-jurnal').reset();
    document.getElementById('j_jml_display').value = '';
    document.getElementById('jml-auto-note').style.display = 'none';
}

document.getElementById('modal-jurnal').addEventListener('click', function(e) {
    if (e.target === this) closeJurnalModal();
});

// ═════════════════════════════════════════════════════
// LOAD STUDENTS
// ═════════════════════════════════════════════════════
const CYCLE_ORDER  = ['hadir', 'sakit', 'ijin', 'alpha'];
const STATUS_LABEL = { hadir: 'Hadir', sakit: 'Sakit', ijin: 'Ijin', alpha: 'Alpha' };

async function loadStudents(idKelas, existingAbsen, existingJml) {
    const loading = document.getElementById('attend-loading');
    const wrapper = document.getElementById('attend-wrapper');
    const empty   = document.getElementById('attend-empty');
    const errDiv  = document.getElementById('attend-error');
    const grid    = document.getElementById('attend-grid');

    loading.style.display = 'block';
    wrapper.style.display = 'none';
    empty.style.display   = 'none';
    errDiv.style.display  = 'none';
    grid.innerHTML = '';

    try {
        const res  = await fetch(`/jurnal-guru/students/${idKelas}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();
        studentList = data;
        loading.style.display = 'none';

        if (!data.length) { empty.style.display = 'block'; return; }

        // Parse existing absen string → build studentStatus map
        // Supports both "Nama (Sakit)" and legacy "Nama" formats
        studentStatus = {};
        data.forEach(s => { studentStatus[String(s.nis)] = 'hadir'; }); // default all hadir
        if (existingAbsen) {
            const parts = existingAbsen.split(',').map(p => p.trim()).filter(Boolean);
            parts.forEach(part => {
                const match = part.match(/^(.+?)\s*\((sakit|ijin|alpha|tidak hadir)\)$/i);
                let namePart   = match ? match[1].trim().toLowerCase() : part.toLowerCase();
                let statusPart = match ? match[2].toLowerCase() : 'alpha';
                if (statusPart === 'tidak hadir') statusPart = 'alpha';
                // Find matching student
                const found = data.find(s =>
                    s.nama_siswa.toLowerCase() === namePart ||
                    s.nama_siswa.toLowerCase().startsWith(namePart) ||
                    namePart.startsWith(s.nama_siswa.toLowerCase())
                );
                if (found && CYCLE_ORDER.includes(statusPart)) {
                    studentStatus[String(found.nis)] = statusPart;
                }
            });
        }

        renderAttend();
        wrapper.style.display = 'block';
        updateAttendSummary();

    } catch(err) {
        loading.style.display = 'none';
        errDiv.style.display  = 'block';
        if (existingJml > 0) {
            document.getElementById('j_jml_display').value = existingJml;
            document.getElementById('j_jml_siswa').value   = existingJml;
        }
    }
}

function renderAttend() {
    const grid = document.getElementById('attend-grid');
    grid.innerHTML = '';
    
    const disabledAttr = isCurrentJurnalApproved ? 'disabled style="pointer-events: none; opacity: 0.85;"' : '';
    
    studentList.forEach(s => {
        const nis    = String(s.nis);
        const status = studentStatus[nis] || 'hadir';
        
        const row = document.createElement('div');
        row.className = 'attend-row';
        row.innerHTML = `
            <span class="attend-student-name">${s.nama_siswa}</span>
            <div class="attend-options">
                <button type="button" class="attend-opt-btn opt-hadir ${status === 'hadir' ? 'active' : ''}" ${disabledAttr} onclick="setStudentStatus('${nis}', 'hadir')">
                    <i class="fa-solid fa-check" style="font-size:0.6rem;"></i> Hadir
                </button>
                <button type="button" class="attend-opt-btn opt-sakit ${status === 'sakit' ? 'active' : ''}" ${disabledAttr} onclick="setStudentStatus('${nis}', 'sakit')">
                    <i class="fa-solid fa-kit-medical" style="font-size:0.6rem;"></i> Sakit
                </button>
                <button type="button" class="attend-opt-btn opt-ijin ${status === 'ijin' ? 'active' : ''}" ${disabledAttr} onclick="setStudentStatus('${nis}', 'ijin')">
                    <i class="fa-solid fa-envelope" style="font-size:0.6rem;"></i> Ijin
                </button>
                <button type="button" class="attend-opt-btn opt-alpha ${status === 'alpha' ? 'active' : ''}" ${disabledAttr} onclick="setStudentStatus('${nis}', 'alpha')">
                    <i class="fa-solid fa-xmark" style="font-size:0.6rem;"></i> Alpha
                </button>
            </div>
        `;
        grid.appendChild(row);
    });
}

function setStudentStatus(nis, status) {
    if (isCurrentJurnalApproved) return;
    studentStatus[nis] = status;
    renderAttend();
    updateAttendSummary();
}

function updateAttendSummary() {
    const total = studentList.length;
    const hadir = studentList.filter(s => (studentStatus[String(s.nis)] || 'hadir') === 'hadir').length;
    const sakit = studentList.filter(s => studentStatus[String(s.nis)] === 'sakit').length;
    const ijin  = studentList.filter(s => studentStatus[String(s.nis)] === 'ijin').length;
    const alpha = studentList.filter(s => studentStatus[String(s.nis)] === 'alpha').length;

    document.getElementById('j_jml_siswa').value   = hadir;
    document.getElementById('j_jml_display').value  = hadir;
    document.getElementById('jml-auto-note').style.display = 'inline';

    let summary = `Hadir: <strong>${hadir}</strong> / ${total}`;
    if (sakit) summary += ` &nbsp;•&nbsp; <span style="color:#b45309;">Sakit: ${sakit}</span>`;
    if (ijin)  summary += ` &nbsp;•&nbsp; <span style="color:#1e40af;">Ijin: ${ijin}</span>`;
    if (alpha) summary += ` &nbsp;•&nbsp; <span style="color:#991b1b;">Alpha: ${alpha}</span>`;
    document.getElementById('attend-summary').innerHTML = summary;

    // Build absen string: "Nama (Sakit), Nama (Ijin), Nama (Alpha)"
    const absenParts = studentList
        .filter(s => (studentStatus[String(s.nis)] || 'hadir') !== 'hadir')
        .map(s => {
            const st = STATUS_LABEL[studentStatus[String(s.nis)]] || 'Alpha';
            return `${s.nama_siswa} (${st})`;
        });
    document.getElementById('j_absen').value = absenParts.join(', ');
}

// ═════════════════════════════════════════════════════
// PHOTO UPLOAD — DYNAMIC MULTI-SLOT
// ═════════════════════════════════════════════════════
const MIN_PHOTOS = 3;
let journalPhotos = [];
// Each element: { url: string|null, file: File|null }

function initPhotos(existingUrls) {
    // Build initial array: existing URLs padded to MIN_PHOTOS empty slots
    journalPhotos = [];
    existingUrls.forEach(url => {
        journalPhotos.push({ url: url || null, file: null });
    });
    while (journalPhotos.length < MIN_PHOTOS) {
        journalPhotos.push({ url: null, file: null });
    }
    renderPhotoZones();
}

function renderPhotoZones() {
    const container = document.getElementById('photo-upload-container');
    container.innerHTML = '';

    journalPhotos.forEach((photo, idx) => {
        const zone = document.createElement('div');
        const hasPic = !!(photo.url || photo.file);
        zone.className = 'photo-zone' + (hasPic ? ' has-photo' : '');
        zone.id = `pz-dyn-${idx}`;

        if (hasPic) {
            const src = photo.url || URL.createObjectURL(photo.file);
            zone.innerHTML = `
                <img class="pz-preview" src="${src}" alt="Foto ${idx + 1}" style="cursor:zoom-in;">
                <button type="button" class="pz-remove" onclick="removePhotoSlot(event, ${idx})">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <span class="pz-num-badge">Foto ${idx + 1}</span>
            `;
            zone.querySelector('.pz-preview').addEventListener('click', function(e) {
                e.stopPropagation();
                openLightbox(src);
            });
        } else {
            zone.innerHTML = `
                <div class="pz-placeholder">
                    <i class="fa-solid fa-camera pz-icon"></i>
                    <span class="pz-label">Foto ${idx + 1}</span>
                    <span class="pz-sublabel">Klik untuk pilih</span>
                </div>
            `;
            zone.addEventListener('click', () => triggerPhotoSelect(idx));
        }

        container.appendChild(zone);
    });

    // Add "+" button card
    const addBtn = document.createElement('div');
    addBtn.className = 'photo-zone pz-add-btn';
    addBtn.onclick = addNewPhotoSlot;
    addBtn.innerHTML = `
        <i class="fa-solid fa-plus"></i>
        <span>Tambah Foto</span>
    `;
    container.appendChild(addBtn);
}

function addNewPhotoSlot() {
    journalPhotos.push({ url: null, file: null });
    renderPhotoZones();
    // Immediately open file picker for the new slot
    triggerPhotoSelect(journalPhotos.length - 1);
}

function triggerPhotoSelect(idx) {
    if (isCurrentJurnalApproved) return;
    // Create a temporary file input
    const fi = document.createElement('input');
    fi.type = 'file';
    fi.accept = 'image/jpeg,image/png,image/webp,image/gif';
    fi.style.display = 'none';
    document.body.appendChild(fi);
    fi.addEventListener('change', function() {
        handlePhotoSelect(idx, fi);
        document.body.removeChild(fi);
    });
    fi.click();
}

function handlePhotoSelect(idx, input) {
    if (!input.files || !input.files[0]) return;
    const file = input.files[0];
    // Validate size (5 MB)
    if (file.size > 5 * 1024 * 1024) {
        alert('Ukuran foto maksimal 5 MB.');
        return;
    }
    journalPhotos[idx] = { url: null, file: file };
    renderPhotoZones();
}

function removePhotoSlot(event, idx) {
    event.stopPropagation();
    // If it's one of the first MIN_PHOTOS, just clear it; otherwise remove the slot
    if (idx < MIN_PHOTOS) {
        journalPhotos[idx] = { url: null, file: null };
    } else {
        journalPhotos.splice(idx, 1);
    }
    renderPhotoZones();
}

function syncPhotoInputs() {
    // Populate hidden-photo-inputs with actual file inputs for new uploads
    // and hidden text inputs for existing URLs
    const container = document.getElementById('hidden-photo-inputs');
    container.innerHTML = '';

    journalPhotos.forEach((photo, idx) => {
        if (photo.file) {
            // New file upload — use DataTransfer to bind the File object
            const fi = document.createElement('input');
            fi.type = 'file';
            fi.name = `fotos[${idx}]`;
            const dt = new DataTransfer();
            dt.items.add(photo.file);
            fi.files = dt.files;
            container.appendChild(fi);
        } else if (photo.url) {
            // Existing URL — send it as a text field so the backend can keep it
            const hi = document.createElement('input');
            hi.type = 'hidden';
            hi.name = `existing_fotos[${idx}]`;
            hi.value = photo.url;
            container.appendChild(hi);
        }
        // If both null: empty slot, no input → backend treats as "no file here"
    });
}

// ═════════════════════════════════════════════════════
// LIGHTBOX
// ═════════════════════════════════════════════════════
function openLightbox(url) {
    const lb = document.getElementById('lightbox-overlay');
    document.getElementById('lightbox-img').src = url;
    lb.style.display = 'flex';
}
function closeLightbox() {
    document.getElementById('lightbox-overlay').style.display = 'none';
}

// ═════════════════════════════════════════════════════
// SUBMIT
// ═════════════════════════════════════════════════════
async function submitJurnalForm(e) {
    e.preventDefault();

    // ── Validate minimum 3 photos ──
    const filledPhotos = journalPhotos.filter(p => p.url || p.file);
    if (filledPhotos.length < MIN_PHOTOS) {
        alert(`Foto dokumentasi wajib diisi minimal ${MIN_PHOTOS} foto. Baru ada ${filledPhotos.length} foto.`);
        return;
    }

    // ── Sync CKEditor data to textarea before validation & submission ──
    if (materiEditor) {
        document.getElementById('j_materi').value = materiEditor.getData().trim();
    }

    const materiVal = document.getElementById('j_materi').value.trim();
    const plainText = materiVal.replace(/<[^>]*>/g, '').replace(/&nbsp;/g, '').trim();
    if (!plainText) {
        alert('Materi Pembelajaran wajib diisi.');
        return;
    }

    const btn  = document.getElementById('btn-save-jurnal');
    const orig = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

    // ── Sync photo inputs into DOM before building FormData ──
    syncPhotoInputs();

    const form     = document.getElementById('form-jurnal');
    const formData = new FormData(form);

    // Sync jml_siswa from display input (in case student list didn't load)
    const displayJml = document.getElementById('j_jml_display').value;
    if (displayJml !== '') formData.set('jml_siswa', displayJml);

    // Include photo files/existing directly from journalPhotos (already in formData via syncPhotoInputs)
    // Remove any leftover old foto_1/2/3 keys just in case
    formData.delete('foto_1'); formData.delete('foto_2'); formData.delete('foto_3');

    const csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content
                   || (document.querySelector('input[name="_token"]') || {}).value || '';

    try {
        const res    = await fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            body: formData,
        });
        const result = await res.json();

        if (result.success || res.ok) {
            closeJurnalModal();
            window.location.reload();
        } else {
            // Validation errors
            const msgs = result.errors ? Object.values(result.errors).flat().join('\n') : (result.message || 'Gagal menyimpan.');
            alert(msgs);
        }
    } catch(err) {
        alert('Terjadi kesalahan koneksi. Silakan coba lagi.');
    } finally {
        btn.disabled = false;
        btn.innerHTML = orig;
    }
}

// ═════════════════════════════════════════════════════
// APPROVE / REJECT
// ═════════════════════════════════════════════════════
async function doApproveReject(action) {
    if (!currentJurnalId) return;
    const label = action === 'approve' ? 'menyetujui' : 'menolak';
    if (!confirm(`Yakin ingin ${label} jurnal ini?`)) return;

    const csrfToken = (document.querySelector('meta[name="csrf-token"]') || {}).content
                   || (document.querySelector('input[name="_token"]') || {}).value || '';

    try {
        const res = await fetch(`/jurnal-guru/${currentJurnalId}/${action}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `_token=${encodeURIComponent(csrfToken)}`,
        });
        const result = await res.json();
        if (result.success || res.ok) {
            closeJurnalModal();
            window.location.reload();
        }
    } catch(err) {
        alert('Gagal memproses permintaan.');
    }
}

function doApprove() { doApproveReject('approve'); }
function doReject()  { doApproveReject('reject'); }

// Filter schedules by active class tab
function filterByClass(classId, btnEl) {
    const tabs = document.querySelectorAll('.class-tab-btn');
    tabs.forEach(t => t.classList.remove('active'));
    btnEl.classList.add('active');

    const rows = document.querySelectorAll('.schedule-row');
    let visibleCount = 0;
    let filledCount = 0;
    
    rows.forEach(row => {
        const rowClassId = row.dataset.classId;
        const isFilled = row.classList.contains('row-filled') || row.classList.contains('row-approved') || row.classList.contains('row-rejected');
        
        if (classId === 'all' || rowClassId === classId) {
            row.style.display = 'flex';
            visibleCount++;
            if (isFilled) filledCount++;
        } else {
            row.style.display = 'none';
        }
    });

    const summaryText = document.getElementById('tab-filled-summary');
    const progressBar = document.getElementById('tab-progress-bar');
    if (summaryText && progressBar) {
        summaryText.innerHTML = `<strong style="color:var(--color-primary);">${filledCount}</strong> / ${visibleCount} jurnal terisi`;
        const pct = visibleCount > 0 ? Math.round((filledCount / visibleCount) * 100) : 0;
        progressBar.style.width = pct + '%';
    }
}

// Toggle Custom Date inputs based on Periode selection
document.addEventListener('DOMContentLoaded', function() {
    const periodeSelect = document.getElementById('filter-periode');
    const customDateGroup = document.getElementById('custom-date-group');

    function toggleCustomDate() {
        if (periodeSelect && customDateGroup) {
            if (periodeSelect.value === 'tanggal_pilihan') {
                customDateGroup.style.display = 'flex';
            } else {
                customDateGroup.style.display = 'none';
            }
        }
    }

    if (periodeSelect) {
        periodeSelect.addEventListener('change', toggleCustomDate);
        toggleCustomDate();
    }

    // Initialize CKEditor 5
    ClassicEditor
        .create(document.querySelector('#j_materi'), {
            toolbar: ['bold', 'italic', 'underline', 'bulletedList', 'numberedList', 'undo', 'redo']
        })
        .then(editor => {
            materiEditor = editor;
            if (isCurrentJurnalApproved) {
                editor.enableReadOnlyMode('editor-lock');
            }
        })
        .catch(error => {
            console.error(error);
        });
});
</script>
@endpush
@endsection
