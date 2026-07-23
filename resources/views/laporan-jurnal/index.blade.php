@extends('layouts.app')

@section('title', 'Laporan Jurnal Guru — SmartSchool')
@section('header_title', 'Laporan Jurnal Guru')
@section('header_subtitle', 'Rekap dan detail jurnal mengajar per semester')

@section('content')
<style>
/* ═══════════════════════════════════════════
   STAT CARDS
═══════════════════════════════════════════ */
.stat-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}
.stat-card {
    background: #fff;
    border: 2px solid #e2e8f0;
    border-radius: 18px;
    padding: 20px 22px;
    display: flex;
    align-items: center;
    gap: 16px;
    transition: all 0.2s ease;
    position: relative;
    overflow: hidden;
}
.stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: 18px 18px 0 0;
}
.stat-card.sc-total::before   { background: linear-gradient(90deg, #0d9488, #10b981); }
.stat-card.sc-approved::before{ background: linear-gradient(90deg, #059669, #34d399); }
.stat-card.sc-pending::before { background: linear-gradient(90deg, #f59e0b, #fcd34d); }
.stat-card.sc-rejected::before{ background: linear-gradient(90deg, #ef4444, #fca5a5); }
.stat-card.sc-siswa::before   { background: linear-gradient(90deg, #6366f1, #a5b4fc); }
.stat-card:hover { transform: translateY(-3px); box-shadow: 0 10px 28px rgba(0,0,0,0.08); }
.stat-icon {
    width: 48px; height: 48px;
    border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
    flex-shrink: 0;
}
.sc-total   .stat-icon { background: rgba(13,148,136,0.12);  color: #0d9488; }
.sc-approved .stat-icon{ background: rgba(5,150,105,0.12);   color: #059669; }
.sc-pending  .stat-icon{ background: rgba(245,158,11,0.12);  color: #d97706; }
.sc-rejected .stat-icon{ background: rgba(239,68,68,0.12);   color: #dc2626; }
.sc-siswa    .stat-icon{ background: rgba(99,102,241,0.12);   color: #4f46e5; }
.stat-body { flex: 1; min-width: 0; }
.stat-value {
    font-size: 2rem;
    font-weight: 900;
    line-height: 1;
    color: var(--text-primary);
    margin-bottom: 3px;
}
.stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

/* ═══════════════════════════════════════════
   FILTER BAR
═══════════════════════════════════════════ */
.filter-card {
    background: #fff;
    border: 2px solid #e2e8f0;
    border-radius: 16px;
    padding: 18px 22px;
    margin-bottom: 24px;
}
.filter-row {
    display: flex;
    gap: 14px;
    flex-wrap: wrap;
    align-items: flex-end;
}
.filter-group {
    display: flex;
    flex-direction: column;
    gap: 5px;
    flex: 1;
    min-width: 160px;
}
.filter-label {
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
}
.filter-select {
    padding: 9px 12px;
    border-radius: 10px;
    border: 1.5px solid #cbd5e1;
    font-size: 0.83rem;
    font-weight: 600;
    background: #fff;
    color: var(--text-primary);
    transition: all 0.2s;
    width: 100%;
}
.filter-select:focus {
    border-color: var(--color-primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(13,148,136,0.15);
}
.btn-filter {
    background: var(--color-primary);
    color: #fff;
    border: none;
    border-radius: 10px;
    padding: 10px 22px;
    font-size: 0.83rem;
    font-weight: 700;
    cursor: pointer;
    display: flex; align-items: center; gap: 7px;
    transition: all 0.18s;
    height: 40px;
}
.btn-filter:hover { background: #0b7a70; transform: translateY(-1px); }
.btn-reset {
    background: #f8fafc;
    color: #475569;
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    padding: 9px 18px;
    font-size: 0.83rem;
    font-weight: 700;
    cursor: pointer;
    text-decoration: none;
    display: flex; align-items: center; gap: 7px;
    height: 40px;
    transition: all 0.18s;
}
.btn-reset:hover { background: #f1f5f9; color: #1e293b; }

/* ═══════════════════════════════════════════
   SEMESTER PILL
═══════════════════════════════════════════ */
.semester-banner {
    background: linear-gradient(135deg, rgba(13,148,136,0.07), rgba(99,102,241,0.06));
    border: 1px solid rgba(13,148,136,0.2);
    border-radius: 14px;
    padding: 14px 22px;
    margin-bottom: 22px;
    display: flex;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}
.semester-banner-icon {
    width: 40px; height: 40px;
    background: var(--color-primary);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: #fff;
    font-size: 1.1rem;
    flex-shrink: 0;
}
.semester-banner-text { flex: 1; }
.semester-banner-title {
    font-weight: 800;
    font-size: 0.95rem;
    color: var(--text-primary);
}
.semester-banner-sub {
    font-size: 0.78rem;
    color: var(--text-muted);
    margin-top: 2px;
}
.semester-status-badge {
    padding: 4px 12px;
    border-radius: 99px;
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}
.badge-aktif { background: rgba(16,185,129,0.15); color: #059669; }
.badge-inactive { background: #f1f5f9; color: #64748b; }

/* ═══════════════════════════════════════════
   REKAP GURU CARDS
═══════════════════════════════════════════ */
.rekap-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 14px;
    margin-bottom: 24px;
}
.rekap-card {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 16px;
    padding: 18px 20px;
    transition: all 0.2s;
}
.rekap-card:hover {
    border-color: rgba(13,148,136,0.35);
    box-shadow: 0 6px 20px rgba(13,148,136,0.1);
    transform: translateY(-2px);
}
.rekap-guru-head {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 14px;
}
.rekap-avatar {
    width: 40px; height: 40px;
    border-radius: 12px;
    background: linear-gradient(135deg, #0d9488, #10b981);
    color: #fff;
    display: flex; align-items: center; justify-content: center;
    font-weight: 900;
    font-size: 1rem;
    flex-shrink: 0;
}
.rekap-guru-name {
    font-weight: 700;
    font-size: 0.88rem;
    color: var(--text-primary);
}
.rekap-guru-total {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-top: 2px;
}
.rekap-bars { display: flex; flex-direction: column; gap: 6px; }
.rekap-bar-row {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.73rem;
}
.rekap-bar-label {
    width: 58px;
    font-weight: 600;
    color: var(--text-muted);
    flex-shrink: 0;
}
.rekap-bar-track {
    flex: 1;
    height: 6px;
    background: #f1f5f9;
    border-radius: 99px;
    overflow: hidden;
}
.rekap-bar-fill {
    height: 100%;
    border-radius: 99px;
    transition: width 0.5s ease;
}
.fill-approved { background: #10b981; }
.fill-pending  { background: #f59e0b; }
.fill-rejected { background: #ef4444; }
.rekap-bar-val {
    width: 22px;
    text-align: right;
    font-weight: 700;
    color: var(--text-primary);
    flex-shrink: 0;
}

/* ═══════════════════════════════════════════
   JURNAL TABLE
═══════════════════════════════════════════ */
.table-responsive { overflow-x: auto; }
.tbl-jurnal {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.82rem;
}
.tbl-jurnal thead th {
    background: #f8fafc;
    color: var(--text-muted);
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-size: 0.7rem;
    padding: 10px 14px;
    border-bottom: 2px solid #e2e8f0;
    white-space: nowrap;
    position: sticky;
    top: 0;
}
.tbl-jurnal tbody tr {
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.12s;
}
.tbl-jurnal tbody tr:hover { background: rgba(13,148,136,0.03); }
.tbl-jurnal td {
    padding: 11px 14px;
    vertical-align: middle;
    color: var(--text-primary);
}
.tbl-jurnal tbody tr:last-child { border-bottom: none; }
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 3px 10px;
    border-radius: 99px;
    font-size: 0.7rem;
    font-weight: 700;
    white-space: nowrap;
}
.sp-approved { background: rgba(16,185,129,0.12); color: #059669; }
.sp-pending  { background: rgba(245,158,11,0.12);  color: #b45309; }
.sp-rejected { background: rgba(239,68,68,0.12);   color: #dc2626; }

.foto-thumbs {
    display: flex;
    gap: 4px;
}
.foto-thumb {
    width: 36px; height: 36px;
    border-radius: 6px;
    object-fit: cover;
    cursor: zoom-in;
    border: 1.5px solid #e2e8f0;
    transition: transform 0.15s;
}
.foto-thumb:hover { transform: scale(1.1); z-index: 2; }
.foto-count {
    width: 36px; height: 36px;
    border-radius: 6px;
    background: #f1f5f9;
    color: #64748b;
    display: flex; align-items: center; justify-content: center;
    font-size: 0.66rem;
    font-weight: 700;
    border: 1.5px solid #e2e8f0;
}

/* Sections tabs */
.section-tabs {
    display: flex;
    gap: 0;
    border-bottom: 2px solid #e2e8f0;
    margin-bottom: 0;
    overflow-x: auto;
}
.sec-tab {
    padding: 12px 22px;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--text-muted);
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    cursor: pointer;
    transition: all 0.18s;
    white-space: nowrap;
    margin-bottom: -2px;
}
.sec-tab.active {
    color: var(--color-primary);
    border-bottom-color: var(--color-primary);
}
.sec-tab:hover:not(.active) { color: var(--text-primary); background: rgba(13,148,136,0.04); }
.sec-panel { display: none; padding: 22px 0 0; }
.sec-panel.active { display: block; }

/* Empty state */
.empty-state {
    text-align: center;
    padding: 52px 20px;
    color: var(--text-muted);
}
.empty-state i { font-size: 3rem; opacity: 0.18; display: block; margin-bottom: 14px; }
.empty-state p { font-weight: 600; font-size: 0.9rem; }

@media (max-width: 768px) {
    .stat-grid { grid-template-columns: 1fr 1fr; }
    .rekap-grid { grid-template-columns: 1fr; }
    .filter-row { flex-direction: column; }
}
</style>

<div class="page-content">
    @include('partials.flash')

    {{-- ── Filter Card ── --}}
    <div class="filter-card">
        <form method="GET" action="{{ route('jurnal-guru.laporan') }}">
            <div class="filter-row">

                {{-- Semester --}}
                <div class="filter-group" style="flex: 2; min-width: 220px;">
                    <label class="filter-label">Semester</label>
                    <select name="id_semester" class="filter-select" id="sel-semester">
                        <option value="" {{ $selectedSemesterId === '' ? 'selected' : '' }}>
                            ★ Semua Semester
                        </option>
                        @foreach($semesterList as $sm)
                            <option value="{{ $sm->id_semester }}"
                                {{ $sm->id_semester == $selectedSemesterId ? 'selected' : '' }}>
                                Semester {{ $sm->semester }}
                                &mdash; {{ $sm->tahunAjaran->tahun ?? '?' }}
                                {{ $sm->status === 'aktif' ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Guru --}}
                <div class="filter-group">
                    <label class="filter-label">Guru</label>
                    <select name="id_guru" class="filter-select">
                        <option value="">Semua Guru</option>
                        @foreach($guruList as $g)
                            <option value="{{ $g->id_guru }}" {{ $filterGuru == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama_guru }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Kelas --}}
                <div class="filter-group">
                    <label class="filter-label">Kelas</label>
                    <select name="id_kelas" class="filter-select">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ $filterKelas == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->tingkat }} {{ $k->rombel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div class="filter-group" style="min-width: 150px;">
                    <label class="filter-label">Status</label>
                    <select name="status_approval" class="filter-select">
                        <option value="">Semua Status</option>
                        <option value="approved" {{ $filterStatus === 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="pending"  {{ $filterStatus === 'pending'  ? 'selected' : '' }}>Menunggu</option>
                        <option value="rejected" {{ $filterStatus === 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div style="display: flex; gap: 8px; align-items: flex-end;">
                    <button type="submit" class="btn-filter">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('jurnal-guru.laporan') }}" class="btn-reset">
                        <i class="fa-solid fa-arrow-rotate-left"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- ── Semester Banner ── --}}
    @if($selectedSemester)
    <div class="semester-banner">
        <div class="semester-banner-icon">
            <i class="fa-solid fa-calendar-days"></i>
        </div>
        <div class="semester-banner-text">
            <div class="semester-banner-title">
                Semester {{ $selectedSemester->semester }}
                &mdash; {{ $selectedSemester->tahunAjaran->tahun ?? '?' }}
            </div>
            <div class="semester-banner-sub">
                {{ \Carbon\Carbon::parse($selectedSemester->awal)->translatedFormat('d F Y') }}
                &mdash;
                {{ \Carbon\Carbon::parse($selectedSemester->akhir)->translatedFormat('d F Y') }}
            </div>
        </div>
        <span class="semester-status-badge {{ $selectedSemester->status === 'aktif' ? 'badge-aktif' : 'badge-inactive' }}">
            {{ $selectedSemester->status === 'aktif' ? '● Aktif' : '○ Tidak Aktif' }}
        </span>
    </div>
    @else
    <div class="semester-banner" style="background: linear-gradient(135deg, rgba(99,102,241,0.07), rgba(139,92,246,0.05)); border-color: rgba(99,102,241,0.2);">
        <div class="semester-banner-icon" style="background: #6366f1;">
            <i class="fa-solid fa-layer-group"></i>
        </div>
        <div class="semester-banner-text">
            <div class="semester-banner-title">Semua Semester</div>
            <div class="semester-banner-sub">Menampilkan seluruh jurnal tanpa batasan semester</div>
        </div>
        <span class="semester-status-badge" style="background: rgba(99,102,241,0.12); color: #4f46e5;">● Semua Data</span>
    </div>
    @endif

    {{-- ── Stat Cards ── --}}
    <div class="stat-grid">
        <div class="stat-card sc-total">
            <div class="stat-icon"><i class="fa-solid fa-book-open"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalJurnal }}</div>
                <div class="stat-label">Total Jurnal</div>
            </div>
        </div>
        <div class="stat-card sc-approved">
            <div class="stat-icon"><i class="fa-solid fa-circle-check"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalApproved }}</div>
                <div class="stat-label">Disetujui</div>
            </div>
        </div>
        <div class="stat-card sc-pending">
            <div class="stat-icon"><i class="fa-solid fa-clock"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalPending }}</div>
                <div class="stat-label">Menunggu</div>
            </div>
        </div>
        <div class="stat-card sc-rejected">
            <div class="stat-icon"><i class="fa-solid fa-circle-xmark"></i></div>
            <div class="stat-body">
                <div class="stat-value">{{ $totalRejected }}</div>
                <div class="stat-label">Ditolak</div>
            </div>
        </div>

    </div>

    {{-- ── Main Card with Tabs ── --}}
    <div class="card">
        <div class="card-header" style="border-bottom: none; padding-bottom: 0;">
            <h2 class="card-title">
                <i class="fa-solid fa-file-chart-column"></i>
                Detail Laporan Jurnal
            </h2>
            @if($totalJurnal > 0)
            <div class="card-header-right">
                <span style="font-size: 0.8rem; color: var(--text-muted);">
                    <strong style="color: var(--color-primary);">{{ $totalJurnal }}</strong> data ditemukan
                </span>
            </div>
            @endif
        </div>

        {{-- Tabs --}}
        <div class="section-tabs" style="padding: 0 24px;">
            <button class="sec-tab active" onclick="switchTab('rekap', this)">
                <i class="fa-solid fa-chart-bar"></i> Rekap per Guru
            </button>
            <button class="sec-tab" onclick="switchTab('detail', this)">
                <i class="fa-solid fa-list"></i> Detail Jurnal
            </button>
        </div>

        <div class="card-body" style="padding: 0 24px 24px;">

            {{-- ── Tab: Rekap per Guru ── --}}
            <div id="tab-rekap" class="sec-panel active">
                @if($rekapGuru->count() > 0)
                <div class="rekap-grid" style="margin-top: 0; padding-top: 20px;">
                    @foreach($rekapGuru as $item)
                    @php $pct = $item['total'] > 0 ? 100 : 0; @endphp
                    <div class="rekap-card">
                        <div class="rekap-guru-head">
                            <div class="rekap-avatar">{{ strtoupper(substr($item['nama_guru'], 0, 1)) }}</div>
                            <div>
                                <div class="rekap-guru-name">{{ $item['nama_guru'] }}</div>
                                <div class="rekap-guru-total">{{ $item['total'] }} jurnal &bull; {{ $item['jml_siswa'] }} siswa hadir</div>
                            </div>
                        </div>
                        <div class="rekap-bars">
                            <div class="rekap-bar-row">
                                <span class="rekap-bar-label" style="color: #059669;">Disetujui</span>
                                <div class="rekap-bar-track">
                                    <div class="rekap-bar-fill fill-approved" style="width: {{ $item['total'] > 0 ? round($item['approved']/$item['total']*100) : 0 }}%;"></div>
                                </div>
                                <span class="rekap-bar-val">{{ $item['approved'] }}</span>
                            </div>
                            <div class="rekap-bar-row">
                                <span class="rekap-bar-label" style="color: #b45309;">Menunggu</span>
                                <div class="rekap-bar-track">
                                    <div class="rekap-bar-fill fill-pending" style="width: {{ $item['total'] > 0 ? round($item['pending']/$item['total']*100) : 0 }}%;"></div>
                                </div>
                                <span class="rekap-bar-val">{{ $item['pending'] }}</span>
                            </div>
                            <div class="rekap-bar-row">
                                <span class="rekap-bar-label" style="color: #dc2626;">Ditolak</span>
                                <div class="rekap-bar-track">
                                    <div class="rekap-bar-fill fill-rejected" style="width: {{ $item['total'] > 0 ? round($item['rejected']/$item['total']*100) : 0 }}%;"></div>
                                </div>
                                <span class="rekap-bar-val">{{ $item['rejected'] }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="empty-state">
                    <i class="fa-solid fa-chart-simple"></i>
                    <p>Tidak ada data jurnal untuk filter yang dipilih.</p>
                </div>
                @endif
            </div>

            {{-- ── Tab: Detail Jurnal ── --}}
            <div id="tab-detail" class="sec-panel">
                @if($jurnals->count() > 0)
                <div class="table-responsive" style="padding-top: 16px;">
                    <table class="tbl-jurnal">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Jam Ke</th>
                                <th>Guru</th>
                                <th>Mata Pelajaran</th>
                                <th>Kelas</th>
                                <th>Materi</th>
                                <th>Siswa Hadir</th>
                                <th>Status</th>
                                <th>Foto</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($jurnals as $idx => $j)
                            @php
                                $namaKelas = '';
                                if ($j->kelas) {
                                    $namaKelas = $j->kelas->tingkat . ' ' . $j->kelas->rombel;
                                }
                                $statusClass = $j->status_approval === 'approved' ? 'sp-approved'
                                    : ($j->status_approval === 'rejected' ? 'sp-rejected' : 'sp-pending');
                                $statusLabel = $j->status_approval === 'approved' ? 'Disetujui'
                                    : ($j->status_approval === 'rejected' ? 'Ditolak' : 'Menunggu');
                                $statusIcon  = $j->status_approval === 'approved' ? 'fa-circle-check'
                                    : ($j->status_approval === 'rejected' ? 'fa-circle-xmark' : 'fa-clock');

                                // Collect photos
                                $fotosArr = [];
                                if (is_array($j->fotos) && count($j->fotos)) {
                                    $fotosArr = array_map(fn($f) => asset('storage/'.$f), $j->fotos);
                                } else {
                                    foreach (['foto_1','foto_2','foto_3'] as $fk) {
                                        if ($j->$fk) $fotosArr[] = asset('storage/'.$j->$fk);
                                    }
                                }
                            @endphp
                            <tr>
                                <td style="color: var(--text-muted); font-weight: 600;">{{ $jurnals->firstItem() + $loop->index }}</td>
                                <td style="white-space: nowrap; font-weight: 600;">
                                    {{ \Carbon\Carbon::parse($j->tanggal)->translatedFormat('d M Y') }}
                                </td>
                                <td style="text-align: center;">
                                    <span style="background: rgba(13,148,136,0.1); color: var(--color-primary); padding: 3px 10px; border-radius: 8px; font-weight: 800; font-size: 0.78rem;">
                                        {{ $j->jam_ke }}
                                    </span>
                                </td>
                                <td style="font-weight: 600;">{{ $j->guru->nama_guru ?? '—' }}</td>
                                <td>{{ $j->mapel->nama_mapel ?? '—' }}</td>
                                <td style="white-space: nowrap;">{{ $namaKelas ?: '—' }}</td>
                                <td style="max-width: 200px;">
                                    <div style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 200px;" title="{{ $j->materi }}">
                                        {{ $j->materi }}
                                    </div>
                                </td>
                                <td style="text-align: center; font-weight: 700; color: #4f46e5;">{{ $j->jml_siswa }}</td>
                                <td>
                                    <span class="status-pill {{ $statusClass }}">
                                        <i class="fa-solid {{ $statusIcon }}"></i>
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td>
                                    <div class="foto-thumbs">
                                        @forelse(array_slice($fotosArr, 0, 3) as $fUrl)
                                            <img src="{{ $fUrl }}" class="foto-thumb"
                                                 onclick="openLightbox('{{ $fUrl }}')"
                                                 alt="Foto dokumentasi">
                                        @empty
                                            <span style="color: var(--text-muted); font-size: 0.75rem;">—</span>
                                        @endforelse
                                        @if(count($fotosArr) > 3)
                                            <div class="foto-count">+{{ count($fotosArr) - 3 }}</div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $jurnals->links('pagination.presensi') }}
                @else
                <div class="empty-state" style="padding-top: 40px;">
                    <i class="fa-solid fa-inbox"></i>
                    <p>Tidak ada jurnal ditemukan untuk filter yang dipilih.</p>
                    <p style="font-size: 0.8rem; font-weight: 400; margin-top: 4px;">Coba ubah filter semester atau kriteria lainnya.</p>
                </div>
                @endif
            </div>

        </div>{{-- end card-body --}}
    </div>{{-- end card --}}

</div>{{-- end page-content --}}

{{-- Lightbox --}}
<div id="lightbox-overlay" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.88); z-index:9999; align-items:center; justify-content:center; cursor:pointer;"
     onclick="closeLightbox()">
    <img id="lightbox-img" src="" alt="" style="max-width:92vw; max-height:90vh; border-radius:10px; box-shadow:0 20px 60px rgba(0,0,0,0.5);">
</div>

@push('scripts')
<script>
function switchTab(name, btnEl) {
    document.querySelectorAll('.sec-tab').forEach(t => t.classList.remove('active'));
    document.querySelectorAll('.sec-panel').forEach(p => p.classList.remove('active'));
    btnEl.classList.add('active');
    document.getElementById('tab-' + name).classList.add('active');
}

function openLightbox(url) {
    const lb = document.getElementById('lightbox-overlay');
    document.getElementById('lightbox-img').src = url;
    lb.style.display = 'flex';
}
function closeLightbox() {
    document.getElementById('lightbox-overlay').style.display = 'none';
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});
</script>
@endpush
@endsection
