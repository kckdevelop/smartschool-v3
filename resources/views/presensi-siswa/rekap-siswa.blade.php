@extends('layouts.app')

@section('title', 'Rekap Presensi Per Siswa — SmartSchool')
@section('header_title', 'Rekap Presensi Per Siswa')
@section('header_subtitle', 'Riwayat kehadiran lengkap seorang siswa berdasarkan semester terpilih')

@push('styles')
<style>
    /* ─── Filter form ─── */
    .filter-row { display:flex; flex-wrap:wrap; gap:14px; align-items:flex-end; }
    .filter-group { display:flex; flex-direction:column; gap:6px; }
    .filter-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--text-muted); }

    /* ─── Info grid ─── */
    .siswa-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 0;
        border: 1px solid var(--border-color, #e5e7eb);
        border-radius: 8px;
        overflow: hidden;
    }
    .siswa-info-cell {
        padding: 14px 18px;
        border-right: 1px solid var(--border-color, #e5e7eb);
        border-bottom: 1px solid var(--border-color, #e5e7eb);
    }
    .siswa-info-cell:nth-child(3n) { border-right: none; }
    .siswa-info-cell:nth-last-child(-n+3) { border-bottom: none; }
    .info-cell-label { font-size:.7rem; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:var(--text-muted); margin-bottom:5px; }
    .info-cell-val   { font-weight:600; color:var(--text-primary); font-size:.93rem; line-height:1.35; }

    /* ─── Stat cards ─── */
    .rekap-stats { display:grid; grid-template-columns:repeat(6,1fr); gap:12px; margin-bottom:22px; }
    @media(max-width:900px){ .rekap-stats{ grid-template-columns:repeat(3,1fr); } }
    @media(max-width:540px){ .rekap-stats{ grid-template-columns:repeat(2,1fr); } }

    .stat-pill {
        border-radius: 10px;
        padding: 18px 12px;
        text-align: center;
        border: 1px solid rgba(0,0,0,.06);
    }
    .stat-pill-num { font-size: 1.9rem; font-weight: 800; line-height: 1; }
    .stat-pill-lbl { font-size: .68rem; font-weight: 700; text-transform: uppercase; letter-spacing: .5px; margin-top: 5px; }

    .pill-hadir  { background:linear-gradient(135deg,#d1fae5,#a7f3d0); }
    .pill-hadir  .stat-pill-num { color:#065f46; } .pill-hadir .stat-pill-lbl { color:#047857; }
    .pill-sakit  { background:linear-gradient(135deg,#fef9c3,#fde68a); }
    .pill-sakit  .stat-pill-num { color:#78350f; } .pill-sakit .stat-pill-lbl { color:#92400e; }
    .pill-izin   { background:linear-gradient(135deg,#dbeafe,#bfdbfe); }
    .pill-izin   .stat-pill-num { color:#1e3a8a; } .pill-izin .stat-pill-lbl { color:#1d4ed8; }
    .pill-alfa   { background:linear-gradient(135deg,#fee2e2,#fecaca); }
    .pill-alfa   .stat-pill-num { color:#7f1d1d; } .pill-alfa .stat-pill-lbl { color:#b91c1c; }
    .pill-total  { background:linear-gradient(135deg,#f1f5f9,#e2e8f0); }
    .pill-total  .stat-pill-num { color:#0f172a; } .pill-total .stat-pill-lbl { color:#475569; }
    .pill-persen { background:linear-gradient(135deg,#ede9fe,#ddd6fe); }
    .pill-persen .stat-pill-num { color:#4c1d95; } .pill-persen .stat-pill-lbl { color:#6d28d9; }

    /* ─── Tabel detail ─── */
    .hari-text { font-weight:700; color:var(--text-primary); }
    .tgl-text  { color:var(--text-muted); font-size:.85rem; }
    .jam-chip  {
        font-family: monospace;
        font-weight: 600;
        background: var(--bg-card-hover, #f3f4f6);
        padding: 3px 10px;
        border-radius: 5px;
        font-size: .85rem;
    }
    .empty-muted { color:var(--text-muted); font-style:italic; }
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- ══════════════════════════════════════
         FILTER PANEL
    ══════════════════════════════════════ --}}
    <div class="card mb-6">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-filter"></i> Filter Rekap Per Siswa</h2>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('presensi-siswa.rekap-siswa') }}" id="form-filter">
                <div class="filter-row">

                    {{-- Kelas --}}
                    <div class="filter-group" style="flex:1.4; min-width:200px;">
                        <label class="filter-label">Kelas</label>
                        <select name="id_kelas" id="select-kelas" class="form-control" required>
                            <option value="">— Pilih Kelas —</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id_kelas }}" {{ $id_kelas == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->tingkat }} {{ $k->rombel }}
                                    @if($k->jurusan) — {{ $k->jurusan->nama_jurusan }} @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Siswa —dinamis AJAX --}}
                    <div class="filter-group" style="flex:2; min-width:240px;">
                        <label class="filter-label">Siswa</label>
                        <select name="nis" id="select-siswa" class="form-control" required {{ !$id_kelas ? 'disabled' : '' }}>
                            @if(!$id_kelas)
                                <option value="">— Pilih Kelas Terlebih Dahulu —</option>
                            @else
                                <option value="">— Pilih Siswa —</option>
                                @foreach($siswaList as $s)
                                    <option value="{{ $s->nis }}" {{ $nis == $s->nis ? 'selected' : '' }}>
                                        {{ $s->nama_siswa }} ({{ $s->nis }})
                                    </option>
                                @endforeach
                            @endif
                        </select>
                    </div>

                    {{-- Semester --}}
                    <div class="filter-group" style="flex:1.8; min-width:220px;">
                        <label class="filter-label">Semester</label>
                        <select name="id_semester" id="select-semester" class="form-control" required>
                            <option value="">— Pilih Semester —</option>
                            @foreach($semesterList as $sem)
                                <option value="{{ $sem->id_semester }}" {{ $id_semester == $sem->id_semester ? 'selected' : '' }}>
                                    Semester {{ ucfirst($sem->semester ?? '-') }}
                                    @if($sem->tahunAjaran) — TA {{ $sem->tahunAjaran->tahun }} @endif
                                    @if($sem->status === 'aktif') ★ Aktif @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Submit --}}
                    <div class="filter-group" style="justify-content:flex-end;">
                        <label class="filter-label" style="opacity:0;">Aksi</label>
                        <button type="submit" class="btn btn-primary" style="height:44px; padding:0 28px; white-space:nowrap;">
                            <i class="fa-solid fa-magnifying-glass"></i> Tampilkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if($siswa && $semester && !empty($stats))

    {{-- ══════════════════════════════════════
         HEADER SISWA + TOMBOL PDF
    ══════════════════════════════════════ --}}
    <div class="card mb-6">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
            <h2 class="card-title" style="margin:0;">
                <i class="fa-solid fa-user-graduate"></i> Informasi Siswa
            </h2>
            <a href="{{ route('presensi-siswa.rekap-siswa.pdf', ['id_kelas'=>$id_kelas,'nis'=>$nis,'id_semester'=>$id_semester]) }}"
               class="btn btn-sm"
               style="display:inline-flex;align-items:center;gap:8px;background:#dc2626;color:#fff;border:none;height:38px;padding:0 18px;border-radius:8px;font-weight:600;font-size:.85rem;text-decoration:none;">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </a>
        </div>
        <div class="card-body" style="padding-top:16px; padding-bottom:16px;">
            <div class="siswa-info-grid">
                <div class="siswa-info-cell">
                    <div class="info-cell-label">NIS</div>
                    <div class="info-cell-val">{{ $siswa->nis }}</div>
                </div>
                <div class="siswa-info-cell">
                    <div class="info-cell-label">Nama Siswa</div>
                    <div class="info-cell-val" style="font-size:1rem; font-weight:700;">{{ $siswa->nama_siswa }}</div>
                </div>
                <div class="siswa-info-cell">
                    <div class="info-cell-label">Kelas</div>
                    <div class="info-cell-val">
                        @if($siswa->kelas)
                            {{ $siswa->kelas->tingkat }} {{ $siswa->kelas->rombel }}
                            @if($siswa->kelas->jurusan)
                                <span style="color:var(--text-muted); font-size:.82rem; font-weight:400;">
                                    — {{ $siswa->kelas->jurusan->nama_jurusan }}
                                </span>
                            @endif
                        @else — @endif
                    </div>
                </div>
                <div class="siswa-info-cell">
                    <div class="info-cell-label">Wali Kelas</div>
                    <div class="info-cell-val">
                        {{ $siswa->kelas && $siswa->kelas->guru ? $siswa->kelas->guru->nama_guru : '—' }}
                    </div>
                </div>
                <div class="siswa-info-cell">
                    <div class="info-cell-label">Semester</div>
                    <div class="info-cell-val">
                        Semester {{ ucfirst($semester->semester ?? '-') }}
                        @if($semester->tahunAjaran)
                            <span style="color:var(--text-muted); font-size:.82rem; font-weight:400;"> — TA {{ $semester->tahunAjaran->tahun }}</span>
                        @endif
                    </div>
                </div>
                <div class="siswa-info-cell">
                    <div class="info-cell-label">Periode</div>
                    <div class="info-cell-val">
                        {{ \Carbon\Carbon::parse($semester->awal)->translatedFormat('d M Y') }}
                        <span style="color:var(--text-muted);">s.d.</span>
                        {{ \Carbon\Carbon::parse($semester->akhir)->translatedFormat('d M Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         STATISTIK
    ══════════════════════════════════════ --}}
    <div class="rekap-stats">
        <div class="stat-pill pill-hadir">
            <div class="stat-pill-num">{{ $stats['hadir'] }}</div>
            <div class="stat-pill-lbl">Hadir</div>
        </div>
        <div class="stat-pill pill-sakit">
            <div class="stat-pill-num">{{ $stats['sakit'] }}</div>
            <div class="stat-pill-lbl">Sakit</div>
        </div>
        <div class="stat-pill pill-izin">
            <div class="stat-pill-num">{{ $stats['izin'] }}</div>
            <div class="stat-pill-lbl">Izin</div>
        </div>
        <div class="stat-pill pill-alfa">
            <div class="stat-pill-num">{{ $stats['alfa'] }}</div>
            <div class="stat-pill-lbl">Alfa</div>
        </div>
        <div class="stat-pill pill-total">
            <div class="stat-pill-num">{{ $stats['total'] }}</div>
            <div class="stat-pill-lbl">Total Hari</div>
        </div>
        <div class="stat-pill pill-persen">
            <div class="stat-pill-num">{{ $stats['persentase'] }}%</div>
            <div class="stat-pill-lbl">Kehadiran</div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
         TABEL DETAIL PRESENSI
    ══════════════════════════════════════ --}}
    <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; gap:10px; flex-wrap:wrap;">
            <h2 class="card-title" style="margin:0;">
                <i class="fa-solid fa-table-list"></i> Detail Presensi Harian
            </h2>
            <span style="font-size:.82rem; color:var(--text-muted);">
                {{ $presensiList->count() }} record ditemukan
            </span>
        </div>

        @if($presensiList->isEmpty())
            <div class="card-body">
                <div class="empty-state">
                    <i class="fa-solid fa-calendar-xmark"></i>
                    <p>Belum ada data presensi pada semester ini.</p>
                </div>
            </div>
        @else
            <div class="card-body p-0" style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:52px; text-align:center;">No</th>
                            <th style="min-width:180px;">Hari &amp; Tanggal</th>
                            <th style="width:110px; text-align:center;">Status</th>
                            <th style="width:110px; text-align:center;">Jam Finger</th>
                            <th style="min-width:180px;">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($presensiList as $idx => $p)
                        @php
                            // Auto-label: ada jam tapi keterangan kosong dan status Hadir → Mesin Finger
                            $isHadir     = $p->status_label === 'Hadir';
                            $tampilJam   = $p->jam && $isHadir;
                            $ketDisplay  = $p->keterangan ?: ($tampilJam ? 'Mesin Finger' : null);
                            $ketIsMesin  = (!$p->keterangan && $tampilJam);
                        @endphp
                        <tr>
                            <td style="text-align:center;" class="empty-muted">{{ $idx + 1 }}</td>
                            <td>
                                <span class="hari-text">{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('l') }}</span>
                                <span class="tgl-text">, {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}</span>
                            </td>
                            <td style="text-align:center;">
                                <span class="badge {{ $p->status_badge }}"
                                      style="padding:4px 14px; border-radius:20px; font-size:.73rem; font-weight:700; display:inline-block; letter-spacing:.4px;">
                                    {{ $p->status_label }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                @if($tampilJam)
                                    <span class="jam-chip">{{ \Carbon\Carbon::parse($p->jam)->format('H:i') }}</span>
                                @else
                                    <span class="empty-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if($ketDisplay)
                                    <span style="{{ $ketIsMesin ? 'color:var(--text-muted); font-style:italic;' : '' }}">
                                        <i class="{{ $ketIsMesin ? 'fa-solid fa-fingerprint' : '' }}" style="{{ $ketIsMesin ? 'margin-right:4px; font-size:.8rem; color:#9ca3af;' : '' }}"></i>
                                        {{ $ketDisplay }}
                                    </span>
                                @else
                                    <span class="empty-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @elseif(request()->has('id_kelas') || request()->has('nis'))
    <div class="card">
        <div class="card-body" style="padding:40px 24px; text-align:center;">
            <i class="fa-solid fa-user-magnifying-glass" style="font-size:2.2rem; color:var(--text-muted); opacity:.5; display:block; margin-bottom:12px;"></i>
            <p style="color:var(--text-muted); margin:0;">
                Pilih <strong>Siswa</strong> dan <strong>Semester</strong> untuk menampilkan rekap presensi.
            </p>
        </div>
    </div>

    @else
    <div class="card">
        <div class="card-body" style="padding:56px 24px; text-align:center;">
            <i class="fa-solid fa-clipboard-user" style="font-size:3rem; color:var(--text-muted); opacity:.35; display:block; margin-bottom:16px;"></i>
            <p style="color:var(--text-muted); font-size:.95rem; margin:0; line-height:1.7;">
                Pilih <strong>Kelas</strong>, <strong>Siswa</strong>, dan <strong>Semester</strong>
                pada filter di atas<br>untuk menampilkan rekap presensi lengkap per siswa.
            </p>
        </div>
    </div>
    @endif
</div>

<script>
const routeSiswaByKelas = "{{ route('presensi-siswa.rekap-siswa.students', ':id') }}";
const selectedNis        = "{{ $nis ?? '' }}";

document.getElementById('select-kelas').addEventListener('change', function () {
    const idKelas  = this.value;
    const selSiswa = document.getElementById('select-siswa');

    selSiswa.innerHTML = '<option value="">Memuat data siswa…</option>';
    selSiswa.disabled  = true;

    if (!idKelas) {
        selSiswa.innerHTML = '<option value="">— Pilih Kelas Terlebih Dahulu —</option>';
        return;
    }

    fetch(routeSiswaByKelas.replace(':id', idKelas))
        .then(r => r.json())
        .then(data => {
            selSiswa.innerHTML = '<option value="">— Pilih Siswa —</option>';
            data.forEach(s => {
                const opt = document.createElement('option');
                opt.value       = s.nis;
                opt.textContent = s.nama_siswa + ' (' + s.nis + ')';
                if (String(s.nis) === String(selectedNis)) opt.selected = true;
                selSiswa.appendChild(opt);
            });
            selSiswa.disabled = false;
        })
        .catch(() => {
            selSiswa.innerHTML = '<option value="">Gagal memuat data siswa</option>';
            selSiswa.disabled  = false;
        });
});
</script>
@endsection
