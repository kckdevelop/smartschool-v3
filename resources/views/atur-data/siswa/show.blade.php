@extends('layouts.app')

@section('title', 'Detail Siswa — SmartSchool')
@section('header_title', 'Detail Siswa')
@section('header_subtitle', 'Informasi lengkap data siswa')

@section('content')
<style>
/* ─── Tab System ─── */
.detail-tabs { display:flex; gap:4px; border-bottom:2px solid #e2e8f0; margin-bottom:0; padding:0 24px; background:#f8fafc; border-radius:0; }
.detail-tab-btn {
    padding:13px 20px; font-size:0.82rem; font-weight:600; border:none; background:transparent;
    color:var(--text-muted); cursor:pointer; border-bottom:2px solid transparent; margin-bottom:-2px;
    transition:var(--transition-smooth); display:flex; align-items:center; gap:7px; border-radius:8px 8px 0 0;
}
.detail-tab-btn:hover { color:var(--text-primary); background:rgba(13,148,136,0.05); }
.detail-tab-btn.active { color:var(--color-primary); border-bottom-color:var(--color-primary); background:rgba(13,148,136,0.07); font-weight:700; }
.detail-tab-btn i { font-size:0.9rem; }
.tab-pane { display:none; padding:24px; }
.tab-pane.active { display:block; }

/* ─── Profile Header ─── */
.student-hero {
    background:linear-gradient(135deg, #0d9488 0%, #6366f1 100%);
    border-radius:var(--radius-card) var(--radius-card) 0 0;
    padding:28px 28px 20px;
    display:flex; align-items:center; gap:24px; color:#fff;
}
.student-avatar {
    width:84px; height:84px; border-radius:18px;
    background:rgba(255,255,255,0.2); border:3px solid rgba(255,255,255,0.4);
    display:flex; align-items:center; justify-content:center;
    font-size:2.2rem; font-weight:800; color:#fff; flex-shrink:0;
    backdrop-filter:blur(8px); box-shadow:0 8px 24px rgba(0,0,0,0.15);
}
.student-name { font-size:1.4rem; font-weight:800; margin-bottom:4px; letter-spacing:-0.3px; }
.student-meta { font-size:0.82rem; opacity:0.85; display:flex; flex-wrap:wrap; gap:12px; margin-top:6px; }
.student-meta span { display:flex; align-items:center; gap:5px; background:rgba(255,255,255,0.15); padding:4px 10px; border-radius:20px; }
.student-hero-actions { margin-left:auto; display:flex; flex-direction:column; gap:8px; align-items:flex-end; }

/* ─── Info Grid ─── */
.info-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; }
.info-grid-3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; }
.info-item { display:flex; flex-direction:column; gap:4px; }
.info-label { font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:var(--text-muted); }
.info-value { font-size:0.92rem; font-weight:600; color:var(--text-primary); }
.info-value.empty { color:var(--text-muted); font-style:italic; font-weight:400; }

/* ─── Section block ─── */
.detail-section { margin-bottom:24px; }
.detail-section-title {
    font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:0.7px;
    color:var(--color-primary); margin-bottom:14px; display:flex; align-items:center; gap:8px;
    padding-bottom:8px; border-bottom:1.5px solid rgba(13,148,136,0.1);
}
.detail-section-title i { font-size:0.85rem; }

/* ─── Parent block ─── */
.parent-cards { display:grid; grid-template-columns:1fr 1fr 1fr; gap:16px; }
.parent-card {
    background:#f8fafc; border:1.5px solid #e2e8f0; border-radius:12px; padding:16px;
    transition:var(--transition-smooth);
}
.parent-card:hover { border-color:rgba(13,148,136,0.25); background:rgba(13,148,136,0.02); }
.parent-card-title { font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:var(--text-secondary); margin-bottom:10px; display:flex; align-items:center; gap:6px; }

/* ─── History Table ─── */
.history-table { width:100%; border-collapse:collapse; font-size:0.85rem; }
.history-table thead tr { background:linear-gradient(90deg,#f0fdfa,#f8fafc); }
.history-table th { padding:10px 14px; color:var(--text-muted); font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; white-space:nowrap; border-bottom:1.5px solid #e2e8f0; }
.history-table td { padding:11px 14px; color:var(--text-primary); border-bottom:1px solid #f1f5f9; vertical-align:middle; font-weight:500; }
.history-table tbody tr:last-child td { border-bottom:none; }
.history-table tbody tr:hover { background:#fafbff; }

/* ─── Stat mini boxes ─── */
.summary-stats { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:20px; }
.summary-stat { background:#fff; border:1.5px solid #e2e8f0; border-radius:12px; padding:14px 16px; text-align:center; transition:var(--transition-smooth); }
.summary-stat:hover { border-color:rgba(13,148,136,0.3); transform:translateY(-2px); box-shadow:var(--shadow-hover); }
.summary-stat-num { font-size:1.6rem; font-weight:800; line-height:1.1; }
.summary-stat-lbl { font-size:0.7rem; font-weight:600; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; margin-top:3px; }

/* empty state */
.empty-state { text-align:center; padding:36px 20px; color:var(--text-muted); }
.empty-state i { font-size:2.4rem; opacity:0.3; margin-bottom:10px; display:block; }
.empty-state p { font-size:0.875rem; }

@media(max-width:768px) {
    .info-grid { grid-template-columns:1fr; }
    .info-grid-3 { grid-template-columns:1fr 1fr; }
    .parent-cards { grid-template-columns:1fr; }
    .summary-stats { grid-template-columns:repeat(2,1fr); }
    .student-hero { flex-direction:column; align-items:flex-start; }
    .student-hero-actions { margin-left:0; flex-direction:row; }
    .detail-tabs { overflow-x:auto; flex-wrap:nowrap; padding:0 12px; }
    .detail-tab-btn { white-space:nowrap; padding:11px 14px; }
}

/* ─── Clickable stat cards ─── */
a.summary-stat {
    display:block;
    text-decoration:none;
    cursor:pointer;
}
a.summary-stat:hover {
    border-color:rgba(13,148,136,0.4);
    background:rgba(13,148,136,0.04);
    transform:translateY(-3px);
    box-shadow:var(--shadow-hover);
}
a.summary-stat.stat-active {
    border-color:currentColor;
    box-shadow:0 0 0 3px rgba(13,148,136,0.12);
    transform:translateY(-2px);
}
a.summary-stat.stat-active-hadir  { border-color:#059669; box-shadow:0 0 0 3px rgba(5,150,105,0.12); }
a.summary-stat.stat-active-sakit  { border-color:#d97706; box-shadow:0 0 0 3px rgba(217,119,6,0.12); }
a.summary-stat.stat-active-izin   { border-color:#0284c7; box-shadow:0 0 0 3px rgba(2,132,199,0.12); }
a.summary-stat.stat-active-alpha  { border-color:#dc2626; box-shadow:0 0 0 3px rgba(220,38,38,0.12); }
</style>

<div class="page-content">
    @include('partials.flash')

    <div class="card" style="overflow:visible;">
        {{-- ─── Hero Header ─── --}}
        <div class="student-hero">
            <div class="student-avatar" style="padding:0; overflow:hidden;">
                @if($siswa->detail && $siswa->detail->foto)
                    <img src="{{ asset('storage/' . $siswa->detail->foto) }}" alt="{{ $siswa->nama_siswa }}" style="width:100%; height:100%; object-fit:cover; display:block;">
                @else
                    {{ strtoupper(substr($siswa->nama_siswa, 0, 1)) }}
                @endif
            </div>
            <div>
                <div class="student-name">{{ $siswa->nama_siswa }}</div>
                <div style="font-size:0.83rem;opacity:0.8;">NIS: <strong>{{ $siswa->nis }}</strong></div>
                <div class="student-meta">
                    <span><i class="fa-solid fa-chalkboard-user"></i>
                        {{ $siswa->kelas ? $siswa->kelas->tingkat.' '.$siswa->kelas->rombel : 'Tidak ada kelas' }}
                    </span>
                    <span><i class="fa-solid fa-venus-mars"></i>{{ $siswa->jenkel == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    <span><i class="fa-solid fa-circle-dot" style="color:{{ $siswa->status=='aktif'?'#86efac':($siswa->status=='tidak'?'#cbd5e1':'#fca5a5') }};"></i>
                        {{ ucfirst($siswa->status) }}
                    </span>
                </div>
            </div>
            <div class="student-hero-actions">
                <a href="{{ route('atur-data.siswa.edit-detail', $siswa->nis) }}" class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:#fff;border:1.5px solid rgba(255,255,255,0.4);backdrop-filter:blur(4px);">
                    <i class="fa-solid fa-pen-to-square"></i> Edit Detail
                </a>
                <a href="{{ route('atur-data.siswa') }}" class="btn btn-sm" style="background:rgba(255,255,255,0.12);color:#fff;border:1.5px solid rgba(255,255,255,0.25);">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        {{-- ─── Tabs ─── --}}
        <div class="detail-tabs" id="siswa-tabs">
            <button class="detail-tab-btn active" data-target="tab-pribadi">
                <i class="fa-solid fa-user"></i> Data Pribadi
            </button>
            <button class="detail-tab-btn" data-target="tab-ortu">
                <i class="fa-solid fa-people-roof"></i> Orang Tua & Alamat
            </button>
            <button class="detail-tab-btn" data-target="tab-presensi">
                <i class="fa-solid fa-clipboard-user"></i> Presensi
                <span class="badge badge-info" style="font-size:0.65rem;">{{ $siswa->presensi_count }}</span>
            </button>
            <button class="detail-tab-btn" data-target="tab-poin">
                <i class="fa-solid fa-triangle-exclamation"></i> Poin Pelanggaran
                <span class="badge badge-danger" style="font-size:0.65rem;">{{ $siswa->riwayatPoin->count() }}</span>
            </button>
            <button class="detail-tab-btn" data-target="tab-uks">
                <i class="fa-solid fa-heart-pulse"></i> Kunjungan UKS
                <span class="badge badge-pink" style="font-size:0.65rem;">{{ $siswa->kunjunganUks->count() }}</span>
            </button>
            <button class="detail-tab-btn" data-target="tab-kesehatan">
                <i class="fa-solid fa-notes-medical"></i> Riwayat Kesehatan
                <span class="badge badge-success" style="font-size:0.65rem; background:#10b981; color:#fff;">{{ $siswa->riwayatKesehatan->count() }}</span>
            </button>
            <button class="detail-tab-btn" data-target="tab-bk">
                <i class="fa-solid fa-comments"></i> Bimbingan Konseling
                <span class="badge badge-purple" style="font-size:0.65rem;">{{ $siswa->bimbinganKonseling->count() }}</span>
            </button>
            <button class="detail-tab-btn" data-target="tab-ismuba">
                <i class="fa-solid fa-book-quran"></i> Data ISMUBA
                @php $ismubaTotalCount = $siswa->btaq->count() + $siswa->pantauIbadah->count(); @endphp
                @if($ismubaTotalCount > 0)
                    <span class="badge" style="background:linear-gradient(135deg,#047857,#10b981);color:#fff;font-size:0.65rem;">{{ $ismubaTotalCount }}</span>
                @endif
            </button>
        </div>

        {{-- ═══════════════ TAB 1: DATA PRIBADI ═══════════════ --}}
        <div class="tab-pane active" id="tab-pribadi">
            <div class="detail-section">
                <div class="detail-section-title"><i class="fa-solid fa-id-card"></i> Identitas Diri</div>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Nama Lengkap</span>
                        <span class="info-value">{{ $siswa->nama_siswa }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">NIS</span>
                        <span class="info-value font-mono">{{ $siswa->nis }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jenis Kelamin</span>
                        <span class="info-value">{{ $siswa->jenkel == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Agama</span>
                        <span class="info-value {{ !($siswa->detail->agama ?? null) ? 'empty' : '' }}">
                            {{ $siswa->detail->agama ?? 'Belum diisi' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tempat Lahir</span>
                        <span class="info-value {{ !$siswa->tempat_lahir ? 'empty' : '' }}">{{ $siswa->tempat_lahir ?? 'Belum diisi' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Tanggal Lahir</span>
                        <span class="info-value {{ !$siswa->tgl_lahir ? 'empty' : '' }}">
                            {{ $siswa->tgl_lahir ? \Carbon\Carbon::parse($siswa->tgl_lahir)->translatedFormat('d F Y') : 'Belum diisi' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Golongan Darah</span>
                        <span class="info-value {{ !($siswa->detail->golongan_darah ?? null) ? 'empty' : '' }}">
                            {{ $siswa->detail->golongan_darah ?? 'Belum diisi' }}
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Status</span>
                        <span class="info-value">
                            @if($siswa->status === 'aktif') <span class="badge badge-success">Aktif</span>
                            @elseif($siswa->status === 'tidak') <span class="badge badge-muted">Non-Aktif</span>
                            @else <span class="badge badge-danger">Keluar</span> @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="detail-section">
                <div class="detail-section-title"><i class="fa-solid fa-chalkboard-user"></i> Informasi Kelas</div>
                <div class="info-grid-3">
                    <div class="info-item">
                        <span class="info-label">Kelas</span>
                        <span class="info-value">{{ $siswa->kelas ? $siswa->kelas->tingkat.' '.$siswa->kelas->rombel : '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Jurusan</span>
                        <span class="info-value">{{ $siswa->kelas->jurusan->nama_jurusan ?? '-' }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">Kode Jurusan</span>
                        <span class="info-value">{{ $siswa->kelas->jurusan->kode_jurusan ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══════════════ TAB 2: ORANG TUA & ALAMAT ═══════════════ --}}
        <div class="tab-pane" id="tab-ortu">
            <div class="detail-section">
                <div class="detail-section-title"><i class="fa-solid fa-map-location-dot"></i> Alamat Lengkap</div>
                @if($siswa->detail && $siswa->detail->alamat)
                    <p style="font-size:0.9rem;color:var(--text-primary);line-height:1.7;background:#f8fafc;padding:14px 18px;border-radius:10px;border:1.5px solid #e2e8f0;">{{ $siswa->detail->alamat }}</p>
                @else
                    <div class="empty-state" style="padding:20px;">
                        <i class="fa-solid fa-map-pin"></i>
                        <p>Alamat belum diisi</p>
                    </div>
                @endif
            </div>

            <div class="detail-section">
                <div class="detail-section-title"><i class="fa-solid fa-people-roof"></i> Data Orang Tua & Wali</div>
                <div class="parent-cards">
                    <div class="parent-card">
                        <div class="parent-card-title" style="color:#0d9488;"><i class="fa-solid fa-person"></i> Data Ayah</div>
                        <div class="info-item" style="margin-bottom:10px;">
                            <span class="info-label">Nama</span>
                            <span class="info-value {{ !($siswa->detail->nama_ayah ?? null) ? 'empty' : '' }}">{{ $siswa->detail->nama_ayah ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="info-item" style="margin-bottom:10px;">
                            <span class="info-label">Pekerjaan</span>
                            <span class="info-value {{ !($siswa->detail->pekerjaan_ayah ?? null) ? 'empty' : '' }}">{{ $siswa->detail->pekerjaan_ayah ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">No. Telepon</span>
                            @if($siswa->detail->no_telp_ayah ?? null)
                                <a href="tel:{{ $siswa->detail->no_telp_ayah }}" class="info-value" style="color:var(--color-primary);text-decoration:none;">
                                    <i class="fa-solid fa-phone" style="font-size:0.75rem;"></i> {{ $siswa->detail->no_telp_ayah }}
                                </a>
                            @else
                                <span class="info-value empty">Belum diisi</span>
                            @endif
                        </div>
                    </div>

                    <div class="parent-card">
                        <div class="parent-card-title" style="color:#6366f1;"><i class="fa-solid fa-person-dress"></i> Data Ibu</div>
                        <div class="info-item" style="margin-bottom:10px;">
                            <span class="info-label">Nama</span>
                            <span class="info-value {{ !($siswa->detail->nama_ibu ?? null) ? 'empty' : '' }}">{{ $siswa->detail->nama_ibu ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="info-item" style="margin-bottom:10px;">
                            <span class="info-label">Pekerjaan</span>
                            <span class="info-value {{ !($siswa->detail->pekerjaan_ibu ?? null) ? 'empty' : '' }}">{{ $siswa->detail->pekerjaan_ibu ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">No. Telepon</span>
                            @if($siswa->detail->no_telp_ibu ?? null)
                                <a href="tel:{{ $siswa->detail->no_telp_ibu }}" class="info-value" style="color:var(--color-primary);text-decoration:none;">
                                    <i class="fa-solid fa-phone" style="font-size:0.75rem;"></i> {{ $siswa->detail->no_telp_ibu }}
                                </a>
                            @else
                                <span class="info-value empty">Belum diisi</span>
                            @endif
                        </div>
                    </div>

                    <div class="parent-card">
                        <div class="parent-card-title" style="color:#8b5cf6;"><i class="fa-solid fa-user-shield"></i> Data Wali</div>
                        <div class="info-item" style="margin-bottom:10px;">
                            <span class="info-label">Nama</span>
                            <span class="info-value {{ !($siswa->detail->nama_wali ?? null) ? 'empty' : '' }}">{{ $siswa->detail->nama_wali ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="info-item" style="margin-bottom:10px;">
                            <span class="info-label">Pekerjaan</span>
                            <span class="info-value {{ !($siswa->detail->pekerjaan_wali ?? null) ? 'empty' : '' }}">{{ $siswa->detail->pekerjaan_wali ?? 'Belum diisi' }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">No. Telepon</span>
                            @if($siswa->detail->no_telp_wali ?? null)
                                <a href="tel:{{ $siswa->detail->no_telp_wali }}" class="info-value" style="color:var(--color-primary);text-decoration:none;">
                                    <i class="fa-solid fa-phone" style="font-size:0.75rem;"></i> {{ $siswa->detail->no_telp_wali }}
                                </a>
                            @else
                                <span class="info-value empty">Belum diisi</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- WhatsApp Presensi --}}
            <div class="detail-section">
                <div class="detail-section-title" style="color:#10b981;"><i class="fa-brands fa-whatsapp"></i> WhatsApp Penerima Presensi</div>
                @if($siswa->detail && $siswa->detail->no_wa_presensi)
                    <div style="display:flex; align-items:center; gap:14px; background:linear-gradient(135deg,rgba(16,185,129,0.07),rgba(16,185,129,0.02)); border:1.5px solid rgba(16,185,129,0.2); border-radius:12px; padding:16px 20px;">
                        <div style="width:44px; height:44px; border-radius:12px; background:#25D366; display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 4px 12px rgba(37,211,102,0.3);">
                            <i class="fa-brands fa-whatsapp" style="color:#fff; font-size:1.4rem;"></i>
                        </div>
                        <div>
                            <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:#059669; margin-bottom:3px;">Nomor Aktif</div>
                            <span style="font-size:1.1rem; font-weight:700; color:#059669;">
                                {{ $siswa->detail->no_wa_presensi }}
                            </span>
                        </div>
                    </div>
                @else
                    <div style="display:flex; align-items:center; gap:12px; background:#f8fafc; border:1.5px dashed #d1d5db; border-radius:12px; padding:14px 18px;">
                        <i class="fa-brands fa-whatsapp" style="font-size:1.5rem; color:#d1d5db;"></i>
                        <div>
                            <div style="font-size:0.85rem; color:var(--text-muted);">Nomor WhatsApp belum diisi</div>
                            <a href="{{ route('atur-data.siswa.edit-detail', $siswa->nis) }}" style="font-size:0.78rem; color:var(--color-primary); text-decoration:none;">
                                <i class="fa-solid fa-pen"></i> Isi sekarang
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══════════════ TAB 3: PRESENSI ═══════════════ --}}
        <div class="tab-pane" id="tab-presensi">
            @php
                $activeFilter   = $filterStatus ?? request('filter_status');
                $activeTahun    = $filterTahun ?? request('filter_tahun');
                $activeSemester = $filterSemester ?? request('filter_semester');
                $baseUrl = route('atur-data.siswa.show', $siswa->nis);
            @endphp

            {{-- ─── Filter Tahun Ajaran & Semester ─── --}}
            <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px;padding:14px 18px;margin-bottom:18px;">
                <div style="display:flex;flex-wrap:wrap;gap:12px;align-items:flex-end;">

                    <div style="display:flex;flex-direction:column;gap:4px;min-width:160px;">
                        <label style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);">Tahun Ajaran</label>
                        <select id="filter_tahun" class="form-control" style="font-size:0.85rem;height:36px;padding:0 10px;"
                                onchange="applyPresensiFilter()">
                            <option value="">Semua Tahun</option>
                            @foreach($tahunList as $ta)
                                <option value="{{ $ta->tahun }}" {{ $activeTahun == $ta->tahun ? 'selected' : '' }}>
                                    {{ $ta->tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:4px;min-width:180px;">
                        <label style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;color:var(--text-muted);">Semester</label>
                        <select id="filter_semester" class="form-control" style="font-size:0.85rem;height:36px;padding:0 10px;"
                                onchange="applyPresensiFilter()">
                            <option value="">Semua Semester</option>
                            @foreach($semesterList as $sem)
                                <option value="{{ $sem->id_semester }}" {{ $activeSemester == $sem->id_semester ? 'selected' : '' }}>
                                    {{ $sem->tahunAjaran->tahun ?? '-' }} &ndash; Semester {{ $sem->semester }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if($activeTahun || $activeSemester)
                        <a href="{{ $baseUrl }}{{ $activeFilter ? '?filter_status='.$activeFilter : '' }}#tab-presensi"
                           style="font-size:0.8rem;color:var(--text-muted);display:flex;align-items:center;gap:5px;text-decoration:none;padding:8px 0;">
                            <i class="fa-solid fa-rotate-left"></i> Reset Filter
                        </a>
                    @endif
                </div>
            </div>

            {{-- Stat cards (clickable filter) --}}
            <div class="summary-stats">
                @php
                    $qTahun    = $activeTahun    ? '&filter_tahun='.$activeTahun       : '';
                    $qSemester = $activeSemester  ? '&filter_semester='.$activeSemester : '';
                    $qPeriod   = $qTahun . $qSemester;
                @endphp

                <a href="{{ $baseUrl }}?filter_status=1{{ $qPeriod }}#tab-presensi"
                   class="summary-stat {{ $activeFilter == '1' ? 'stat-active stat-active-hadir' : '' }}">
                    <div class="summary-stat-num" style="color:#059669;">{{ $presensiStats->hadir ?? 0 }}</div>
                    <div class="summary-stat-lbl">Hadir</div>
                </a>

                <a href="{{ $baseUrl }}?filter_status=2{{ $qPeriod }}#tab-presensi"
                   class="summary-stat {{ $activeFilter == '2' ? 'stat-active stat-active-sakit' : '' }}">
                    <div class="summary-stat-num" style="color:#d97706;">{{ $presensiStats->sakit ?? 0 }}</div>
                    <div class="summary-stat-lbl">Sakit</div>
                </a>

                <a href="{{ $baseUrl }}?filter_status=3{{ $qPeriod }}#tab-presensi"
                   class="summary-stat {{ $activeFilter == '3' ? 'stat-active stat-active-izin' : '' }}">
                    <div class="summary-stat-num" style="color:#0284c7;">{{ $presensiStats->izin ?? 0 }}</div>
                    <div class="summary-stat-lbl">Izin</div>
                </a>

                <a href="{{ $baseUrl }}?filter_status=4{{ $qPeriod }}#tab-presensi"
                   class="summary-stat {{ $activeFilter == '4' ? 'stat-active stat-active-alpha' : '' }}">
                    <div class="summary-stat-num" style="color:#dc2626;">{{ $presensiStats->alfa ?? 0 }}</div>
                    <div class="summary-stat-lbl">Alpha</div>
                </a>
            </div>

            {{-- Info filter aktif --}}
            @if($activeFilter)
                @php
                    $filterLabel = match($activeFilter) { '1'=>'Hadir','2'=>'Sakit','3'=>'Izin','4'=>'Alpha', default=>'' };
                    $filterColor = match($activeFilter) { '1'=>'#059669','2'=>'#d97706','3'=>'#0284c7','4'=>'#dc2626', default=>'#64748b' };
                @endphp
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;background:#f8fafc;border:1.5px solid #e2e8f0;border-left:4px solid {{ $filterColor }};border-radius:8px;padding:8px 14px;">
                    <span style="font-size:0.82rem;color:var(--text-secondary);">Menampilkan data: <strong style="color:{{ $filterColor }}">{{ $filterLabel }}</strong></span>
                    <a href="{{ $baseUrl }}{{ $qPeriod ? '?'.ltrim($qPeriod,'&') : '' }}#tab-presensi" style="font-size:0.78rem;color:var(--text-muted);text-decoration:none;display:flex;align-items:center;gap:4px;">
                        <i class="fa-solid fa-xmark"></i> Tampilkan Semua
                    </a>
                </div>
            @endif

            @if($presensi->total() > 0)
                <div style="overflow-x:auto;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($presensi as $i => $p)
                            <tr>
                                <td style="color:var(--text-muted);font-size:0.8rem;">{{ $presensi->firstItem() + $i }}</td>
                                <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d M Y') }}</td>
                                <td>{{ $p->jam ?? '-' }}</td>
                                <td>
                                    @php
                                        $st = $p->status;
                                        $stNum = is_numeric($st) ? (int)$st : match(strtolower((string)$st)) {
                                            'hadir' => 1,
                                            'sakit' => 2,
                                            'izin'  => 3,
                                            default => 4,
                                        };
                                    @endphp
                                    @if($stNum === 1) <span class="badge badge-success">Hadir</span>
                                    @elseif($stNum === 2) <span class="badge badge-warning">Sakit</span>
                                    @elseif($stNum === 3) <span class="badge badge-info">Izin</span>
                                    @else <span class="badge badge-danger">Alpha</span>
                                    @endif
                                </td>
                                <td style="color:var(--text-muted);">{{ $p->keterangan ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Pagination custom (Kembali / nomor / Lanjut + info) --}}
                    {{ $presensi->fragment('tab-presensi')->links('pagination.presensi') }}
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-clipboard-question"></i>
                    <p>Belum ada riwayat presensi untuk siswa ini.</p>
                </div>
            @endif
        </div>

        {{-- ═══════════════ TAB 4: RIWAYAT POIN ═══════════════ --}}
        <div class="tab-pane" id="tab-poin">
            @php
                $totalPoin = $siswa->riwayatPoin->sum('poin');
            @endphp

            <div class="summary-stats" style="grid-template-columns:1fr 1fr; max-width:400px;">
                <div class="summary-stat">
                    <div class="summary-stat-num" style="color:#dc2626;">{{ $siswa->riwayatPoin->count() }}</div>
                    <div class="summary-stat-lbl">Total Pelanggaran</div>
                </div>
                <div class="summary-stat">
                    <div class="summary-stat-num" style="color:#d97706;">{{ $totalPoin }}</div>
                    <div class="summary-stat-lbl">Total Poin</div>
                </div>
            </div>

            @if($siswa->riwayatPoin->count() > 0)
                <div style="overflow-x:auto;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal Input</th>
                                <th>Pelanggaran</th>
                                <th>Poin</th>
                                <th>Tingkat</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa->riwayatPoin->sortByDesc('tgl_input') as $i => $poin)
                            <tr>
                                <td style="color:var(--text-muted);font-size:0.8rem;">{{ $i+1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($poin->tgl_input)->translatedFormat('d M Y') }}</td>
                                <td>{{ $poin->pelanggaran ?? '-' }}</td>
                                <td><span class="badge badge-danger">-{{ $poin->poin }}</span></td>
                                <td><span class="badge badge-muted">Tingkat {{ $poin->tingkat }}</span></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-shield-halved"></i>
                    <p>Belum ada riwayat poin pelanggaran untuk siswa ini.</p>
                </div>
            @endif
        </div>

        {{-- ═══════════════ TAB 5: KUNJUNGAN UKS ═══════════════ --}}
        <div class="tab-pane" id="tab-uks">
            @if($siswa->kunjunganUks->count() > 0)
                <div class="summary-stats" style="grid-template-columns:1fr; max-width:200px;">
                    <div class="summary-stat">
                        <div class="summary-stat-num" style="color:#be185d;">{{ $siswa->kunjunganUks->count() }}</div>
                        <div class="summary-stat-lbl">Total Kunjungan</div>
                    </div>
                </div>
                <div style="overflow-x:auto;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Keluhan</th>
                                <th>Diagnosa</th>
                                <th>Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa->kunjunganUks->sortByDesc('tanggal') as $i => $uks)
                            <tr>
                                <td style="color:var(--text-muted);font-size:0.8rem;">{{ $i+1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($uks->tanggal)->translatedFormat('d M Y') }}</td>
                                <td>{{ $uks->jam ?? '-' }}</td>
                                <td>{{ $uks->keluhan ?? '-' }}</td>
                                <td style="color:var(--text-muted);">{{ $uks->diagnosa ?? '-' }}</td>
                                <td>
                                    @if($uks->tindakan)
                                        <span class="badge badge-info">{{ $uks->tindakan }}</span>
                                    @else
                                        <span style="color:var(--text-muted);">-</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-heart-pulse"></i>
                    <p>Belum ada riwayat kunjungan UKS untuk siswa ini.</p>
                </div>
            @endif
        </div>

        {{-- ═══════════════ TAB 6: BIMBINGAN KONSELING ═══════════════ --}}
        <div class="tab-pane" id="tab-bk">
            @if($siswa->bimbinganKonseling->count() > 0)
                <div class="summary-stats" style="grid-template-columns:repeat(3,1fr); max-width:480px; margin-bottom:20px;">
                    @php
                        $bkProses   = $siswa->bimbinganKonseling->where('status','proses')->count();
                        $bkSelesai  = $siswa->bimbinganKonseling->where('status','selesai')->count();
                        $bkTotal    = $siswa->bimbinganKonseling->count();
                    @endphp
                    <div class="summary-stat">
                        <div class="summary-stat-num" style="color:#7c3aed;">{{ $bkTotal }}</div>
                        <div class="summary-stat-lbl">Total Kasus</div>
                    </div>
                    <div class="summary-stat">
                        <div class="summary-stat-num" style="color:#d97706;">{{ $bkProses }}</div>
                        <div class="summary-stat-lbl">Proses</div>
                    </div>
                    <div class="summary-stat">
                        <div class="summary-stat-num" style="color:#059669;">{{ $bkSelesai }}</div>
                        <div class="summary-stat-lbl">Selesai</div>
                    </div>
                </div>

                <div style="overflow-x:auto;">
                    <table class="history-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Tanggal</th>
                                <th>Jenis Masalah</th>
                                <th>Uraian</th>
                                <th>Tindak Lanjut</th>
                                <th>Guru BK</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($siswa->bimbinganKonseling->sortByDesc('tanggal') as $i => $bk)
                            <tr>
                                <td style="color:var(--text-muted);font-size:0.8rem;">{{ $loop->iteration }}</td>
                                <td>{{ \Carbon\Carbon::parse($bk->tanggal)->translatedFormat('d M Y') }}</td>
                                <td>
                                    <span class="badge" style="background:rgba(124,58,237,0.1);color:#7c3aed;font-size:0.75rem;">
                                        {{ $bk->jenis_masalah }}
                                    </span>
                                </td>
                                <td style="max-width:260px;">
                                    <span style="font-size:0.83rem;color:var(--text-primary);line-height:1.5;display:block;">{{ $bk->uraian }}</span>
                                </td>
                                <td style="max-width:220px;">
                                    @if($bk->tindak_lanjut)
                                        <span style="font-size:0.83rem;color:var(--text-secondary);display:block;">{{ $bk->tindak_lanjut }}</span>
                                    @else
                                        <span style="color:var(--text-muted);">–</span>
                                    @endif
                                </td>
                                <td>
                                    @if($bk->guru)
                                        <span style="font-size:0.82rem;font-weight:600;">{{ $bk->guru->nama_guru }}</span>
                                    @else
                                        <span style="color:var(--text-muted);">–</span>
                                    @endif
                                </td>
                                <td>
                                    @if($bk->status === 'selesai')
                                        <span class="badge badge-success">Selesai</span>
                                    @else
                                        <span class="badge badge-warning">Proses</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-comments"></i>
                    <p>Belum ada riwayat bimbingan konseling untuk siswa ini.</p>
                </div>
            @endif
        </div>

        {{-- ═══════════════ TAB 7: DATA ISMUBA ═══════════════ --}}
        <div class="tab-pane" id="tab-ismuba">
            @php
                $totalBtaq         = $btaqSiswa->count();
                $totalIbadah       = $ibadahSiswa->count();
                $countFardu        = $ibadahSiswa->where('jenis_ibadah','sholat_fardu')->count();
                $countJenazah      = $ibadahSiswa->where('jenis_ibadah','sholat_jenazah')->count();
                $countWudhu        = $ibadahSiswa->where('jenis_ibadah','gerakan_wudhu')->count();
                $ibadahNilaiA      = $ibadahSiswa->where('nilai','A')->count();
            @endphp

            {{-- ─── Stat Summary ─── --}}
            <div class="summary-stats" style="grid-template-columns:repeat(3,1fr);margin-bottom:24px;">
                <div class="summary-stat">
                    <div class="summary-stat-num" style="color:#047857;">{{ $totalBtaq }}</div>
                    <div class="summary-stat-lbl">Sesi BTAQ</div>
                </div>
                <div class="summary-stat">
                    <div class="summary-stat-num" style="color:#1d4ed8;">{{ $totalIbadah }}</div>
                    <div class="summary-stat-lbl">Penilaian Ibadah</div>
                </div>
                <div class="summary-stat">
                    <div class="summary-stat-num" style="color:#059669;">{{ $ibadahNilaiA }}</div>
                    <div class="summary-stat-lbl">Nilai A (Sangat Baik)</div>
                </div>
            </div>

            {{-- ─── Section: Riwayat BTAQ ─── --}}
            <div class="detail-section">
                <div class="detail-section-title" style="color:#047857;">
                    <i class="fa-solid fa-book-quran"></i> Riwayat Pantauan BTAQ
                    <a href="{{ route('ismuba.btaq.index', ['search' => $siswa->nis]) }}" class="btn btn-sm" style="margin-left:auto;font-size:0.72rem;padding:4px 10px;background:rgba(4,120,87,0.1);color:#047857;border:1px solid rgba(4,120,87,0.2);border-radius:6px;text-decoration:none;">
                        <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Semua
                    </a>
                </div>

                @if($totalBtaq > 0)
                    <div style="overflow-x:auto;">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Level</th>
                                    <th>Halaman Awal</th>
                                    <th>Halaman Akhir</th>
                                    <th>Guru Penguji</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($btaqSiswa->take(10) as $i => $btaq)
                                <tr>
                                    <td style="color:var(--text-muted);font-size:0.8rem;">{{ $i + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($btaq->tanggal)->translatedFormat('d M Y') }}</td>
                                    <td>
                                        <span class="badge" style="background:linear-gradient(135deg,#047857,#10b981);color:#fff;font-size:0.75rem;">
                                            {{ $btaq->level }}
                                        </span>
                                    </td>
                                    <td style="font-size:0.83rem;">{{ $btaq->awal }}</td>
                                    <td style="font-size:0.83rem;">{{ $btaq->akhir }}</td>
                                    <td style="font-size:0.83rem;">{{ $btaq->guru?->nama_guru ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($totalBtaq > 10)
                        <div style="text-align:center;padding:10px 0;font-size:0.8rem;color:var(--text-muted);">
                            Menampilkan 10 dari {{ $totalBtaq }} sesi. 
                            <a href="{{ route('ismuba.btaq.index', ['search' => $siswa->nis]) }}" style="color:var(--color-primary);">Lihat semua →</a>
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <i class="fa-solid fa-book-quran"></i>
                        <p>Belum ada riwayat pantauan BTAQ untuk siswa ini.</p>
                    </div>
                @endif
            </div>

            {{-- ─── Section: Penilaian Ibadah ─── --}}
            <div class="detail-section">
                <div class="detail-section-title" style="color:#1d4ed8;">
                    <i class="fa-solid fa-person-praying"></i> Riwayat Penilaian Ibadah
                    <div style="margin-left:auto;display:flex;gap:6px;align-items:center;">
                        {{-- Mini stats per jenis --}}
                        @if($countFardu > 0)
                            <span class="badge" style="background:rgba(29,78,216,0.1);color:#1d4ed8;border:1px solid rgba(29,78,216,0.2);font-size:0.7rem;">
                                Fardu: {{ $countFardu }}
                            </span>
                        @endif
                        @if($countJenazah > 0)
                            <span class="badge" style="background:rgba(124,58,237,0.1);color:#7c3aed;border:1px solid rgba(124,58,237,0.2);font-size:0.7rem;">
                                Jenazah: {{ $countJenazah }}
                            </span>
                        @endif
                        @if($countWudhu > 0)
                            <span class="badge" style="background:rgba(3,105,161,0.1);color:#0369a1;border:1px solid rgba(3,105,161,0.2);font-size:0.7rem;">
                                Wudhu: {{ $countWudhu }}
                            </span>
                        @endif
                        <a href="{{ route('ismuba.ibadah.index', ['search' => $siswa->nis]) }}" class="btn btn-sm" style="font-size:0.72rem;padding:4px 10px;background:rgba(29,78,216,0.08);color:#1d4ed8;border:1px solid rgba(29,78,216,0.2);border-radius:6px;text-decoration:none;">
                            <i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Semua
                        </a>
                    </div>
                </div>

                @if($totalIbadah > 0)
                    <div style="overflow-x:auto;">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Jenis Ibadah</th>
                                    <th>Nilai</th>
                                    <th>Catatan</th>
                                    <th>Guru Penilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ibadahSiswa->take(10) as $i => $ibadah)
                                <tr>
                                    <td style="color:var(--text-muted);font-size:0.8rem;">{{ $i + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($ibadah->tanggal)->translatedFormat('d M Y') }}</td>
                                    <td>
                                        @php
                                            $jMap = [
                                                'sholat_fardu'   => ['label'=>'Bacaan Sholat Fardu',  'bg'=>'rgba(29,78,216,0.1)',   'color'=>'#1d4ed8',  'border'=>'rgba(29,78,216,0.2)'],
                                                'sholat_jenazah' => ['label'=>'Sholat Jenazah',        'bg'=>'rgba(124,58,237,0.1)',  'color'=>'#7c3aed', 'border'=>'rgba(124,58,237,0.2)'],
                                                'gerakan_wudhu'  => ['label'=>'Gerakan Wudhu',         'bg'=>'rgba(3,105,161,0.1)',   'color'=>'#0369a1', 'border'=>'rgba(3,105,161,0.2)'],
                                            ];
                                            $jc = $jMap[$ibadah->jenis_ibadah] ?? ['label'=>$ibadah->jenis_ibadah,'bg'=>'#f1f5f9','color'=>'#64748b','border'=>'#e2e8f0'];
                                        @endphp
                                        <span class="badge" style="background:{{ $jc['bg'] }};color:{{ $jc['color'] }};border:1px solid {{ $jc['border'] }};font-size:0.73rem;">
                                            {{ $jc['label'] }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $nc = ['A'=>'#047857','B'=>'#0369a1','C'=>'#b45309','D'=>'#dc2626'][$ibadah->nilai] ?? '#64748b';
                                        @endphp
                                        <span class="badge" style="background:{{ $nc }};color:#fff;font-weight:800;min-width:28px;text-align:center;font-size:0.88rem;">
                                            {{ $ibadah->nilai }}
                                        </span>
                                    </td>
                                    <td style="font-size:0.82rem;color:var(--text-muted);max-width:180px;">
                                        {{ $ibadah->catatan ? \Str::limit($ibadah->catatan, 60) : '–' }}
                                    </td>
                                    <td style="font-size:0.83rem;">{{ $ibadah->guru?->nama_guru ?? '-' }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if($totalIbadah > 10)
                        <div style="text-align:center;padding:10px 0;font-size:0.8rem;color:var(--text-muted);">
                            Menampilkan 10 dari {{ $totalIbadah }} penilaian.
                            <a href="{{ route('ismuba.ibadah.index', ['search' => $siswa->nis]) }}" style="color:var(--color-primary);">Lihat semua →</a>
                        </div>
                    @endif
                @else
                    <div class="empty-state">
                        <i class="fa-solid fa-person-praying"></i>
                        <p>Belum ada riwayat penilaian ibadah untuk siswa ini.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══════════════ TAB 8: RIWAYAT KESEHATAN ═══════════════ --}}
        <div class="tab-pane" id="tab-kesehatan">
            <div class="detail-section">
                <div class="detail-section-title" style="color:#059669;">
                    <i class="fa-solid fa-notes-medical"></i> Riwayat Kesehatan Siswa
                </div>

                @if($siswa->riwayatKesehatan->count() > 0)
                    <div style="overflow-x:auto;">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Tinggi (cm)</th>
                                    <th>Berat (kg)</th>
                                    <th>Gol. Darah</th>
                                    <th>Penyakit Bawaan</th>
                                    <th>Alergi</th>
                                    <th>Riwayat Penyakit</th>
                                    <th>Catatan Khusus</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswa->riwayatKesehatan->sortByDesc('tanggal') as $i => $riwayat)
                                <tr>
                                    <td style="color:var(--text-muted);font-size:0.8rem;">{{ $i + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($riwayat->tanggal)->translatedFormat('d M Y') }}</td>
                                    <td>{{ $riwayat->tinggi_badan ?? '-' }}</td>
                                    <td>{{ $riwayat->berat_badan ?? '-' }}</td>
                                    <td>
                                        <span class="badge" style="background:#64748b; color:#fff; font-weight:700; padding:3px 8px; border-radius:4px; font-size:0.75rem;">
                                            {{ $riwayat->golongan_darah ?? '-' }}
                                        </span>
                                    </td>
                                    <td>{{ $riwayat->penyakit_bawaan ?? '-' }}</td>
                                    <td>{{ $riwayat->alergi ?? '-' }}</td>
                                    <td>{{ $riwayat->riwayat_penyakit ?? '-' }}</td>
                                    <td style="font-size:0.82rem;color:var(--text-muted);max-width:200px;">
                                        {{ $riwayat->catatan_khusus ?? '-' }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fa-solid fa-notes-medical"></i>
                        <p>Belum ada riwayat kesehatan yang diisi oleh siswa ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
// ─── Navigate with #tab-presensi hash preserved ───────────────────────────
function applyPresensiFilter() {
    var base  = '{{ route('atur-data.siswa.show', $siswa->nis) }}';
    var params = new URLSearchParams();

    var tahun    = document.getElementById('filter_tahun')    ? document.getElementById('filter_tahun').value    : '';
    var semester = document.getElementById('filter_semester') ? document.getElementById('filter_semester').value : '';
    var status   = '{{ $activeFilter ?? '' }}';

    if (tahun)    params.set('filter_tahun',    tahun);
    if (semester) params.set('filter_semester', semester);
    if (status)   params.set('filter_status',   status);

    var qs = params.toString();
    window.location.href = base + (qs ? '?' + qs : '') + '#tab-presensi';
}

// ─── Tab System ──────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    const tabs  = document.querySelectorAll('.detail-tab-btn');
    const panes = document.querySelectorAll('.tab-pane');

    function activateTab(targetId) {
        tabs.forEach(t  => t.classList.remove('active'));
        panes.forEach(p => p.classList.remove('active'));
        const btn  = document.querySelector('.detail-tab-btn[data-target="' + targetId + '"]');
        const pane = document.getElementById(targetId);
        if (btn && pane) {
            btn.classList.add('active');
            pane.classList.add('active');
        } else {
            tabs[0]  && tabs[0].classList.add('active');
            panes[0] && panes[0].classList.add('active');
        }
    }

    // Auto-activate presensi tab when filter query params are present
    var urlParams = new URLSearchParams(window.location.search);
    var hasPresensiFilter = urlParams.has('filter_status')
                         || urlParams.has('filter_tahun')
                         || urlParams.has('filter_semester');

    // Priority: URL hash > presensi filter params > default first tab
    var hash = window.location.hash.replace('#', '');
    if (hash) {
        activateTab(hash);
    } else if (hasPresensiFilter) {
        activateTab('tab-presensi');
        // Silently update hash in URL bar without scrolling
        history.replaceState(null, '', window.location.pathname + window.location.search + '#tab-presensi');
    } else {
        tabs[0]  && tabs[0].classList.add('active');
        panes[0] && panes[0].classList.add('active');
    }

    tabs.forEach(function (btn) {
        btn.addEventListener('click', function () {
            activateTab(btn.dataset.target);
            history.replaceState(null, '', '#' + btn.dataset.target);
        });
    });
});
</script>
@endsection
