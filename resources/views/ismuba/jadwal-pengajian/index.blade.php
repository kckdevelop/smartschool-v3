@extends('layouts.app')

@section('title', 'Jadwal Pengajian — SmartSchool')
@section('header_title', 'Jadwal Pengajian')
@section('header_subtitle', 'Data Kegiatan Pengajian & Kehadiran Guru Karyawan')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- ═══ STAT CARDS ═══ --}}
    <div class="pengajian-stats-row">
        <div class="pengajian-stat-card" style="background:linear-gradient(135deg,#059669,#10b981);">
            <div class="pengajian-stat-icon"><i class="fa-solid fa-mosque"></i></div>
            <div>
                <div class="pengajian-stat-num">{{ $totalKegiatan }}</div>
                <div class="pengajian-stat-lbl">Total Kegiatan</div>
            </div>
        </div>
        <div class="pengajian-stat-card" style="background:linear-gradient(135deg,#2563eb,#3b82f6);">
            <div class="pengajian-stat-icon"><i class="fa-solid fa-calendar"></i></div>
            <div>
                <div class="pengajian-stat-num">{{ $kegiatanTahunIni }}</div>
                <div class="pengajian-stat-lbl">Kegiatan Tahun Ini</div>
            </div>
        </div>
        <div class="pengajian-stat-card" style="background:linear-gradient(135deg,#d97706,#f59e0b);">
            <div class="pengajian-stat-icon"><i class="fa-regular fa-calendar-days"></i></div>
            <div>
                <div class="pengajian-stat-num">{{ $kegiatanBulanIni }}</div>
                <div class="pengajian-stat-lbl">Kegiatan Bulan Ini</div>
            </div>
        </div>
        <div class="pengajian-stat-card" style="background:linear-gradient(135deg,#7c3aed,#9333ea);">
            <div class="pengajian-stat-icon"><i class="fa-solid fa-percent"></i></div>
            <div>
                <div class="pengajian-stat-num">{{ $rataRataKehadiran }}%</div>
                <div class="pengajian-stat-lbl">Rata-rata Kehadiran</div>
            </div>
        </div>
    </div>

    {{-- ═══ TAB NAVIGATION ═══ --}}
    <div class="card">
        <div class="tab-nav" id="pengajian-tabs">
            <button class="tab-btn active" data-tab="tab-daftar" id="btn-tab-daftar">
                <i class="fa-solid fa-list-ul"></i> Daftar Kegiatan
            </button>
            <button class="tab-btn" data-tab="tab-rekap" id="btn-tab-rekap">
                <i class="fa-solid fa-chart-bar"></i> Rekap Kehadiran Guru
            </button>
            <button class="tab-btn" data-tab="tab-cetak" id="btn-tab-cetak">
                <i class="fa-solid fa-print"></i> Cetak Laporan
            </button>
        </div>

        {{-- ═══════════ TAB 1: DAFTAR KEGIATAN ═══════════ --}}
        <div class="tab-content active" id="tab-daftar">
            <div class="card-header" style="border-top: 1px solid var(--border-color);">
                <h2 class="card-title"><i class="fa-solid fa-calendar-days"></i> Daftar Jadwal Pengajian</h2>
                <div class="card-header-right">
                    <button class="btn btn-primary btn-sm" onclick="openModal('modal-add-pengajian')" id="btn-tambah-pengajian">
                        <i class="fa-solid fa-plus"></i> Tambah Data
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                @if($jadwalList->isEmpty())
                    <div style="text-align:center; padding:60px 20px; color:var(--text-muted);">
                        <i class="fa-solid fa-mosque" style="font-size:3rem; opacity:0.2; display:block; margin-bottom:14px;"></i>
                        <div style="font-weight:600; font-size:0.95rem;">Belum ada data jadwal pengajian</div>
                        <div style="font-size:0.82rem; margin-top:4px;">Klik tombol "Tambah Data" untuk menambahkan kegiatan</div>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width:44px; text-align:center;">#</th>
                                    <th>Nama Kegiatan / Pengajian</th>
                                    <th>Tanggal</th>
                                    <th>Tempat</th>
                                    <th style="text-align:center;">Hadir</th>
                                    <th style="text-align:center;">Ijin</th>
                                    <th style="text-align:center;">Alpha</th>
                                    <th style="text-align:center;">Total</th>
                                    <th style="text-align:center;">% Hadir</th>
                                    <th style="text-align:center; width:180px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($jadwalList as $idx => $jadwal)
                                    @php
                                        $hadirCount = $jadwal->hadir_count;
                                        $ijinCount = $jadwal->ijin_count;
                                        $alphaCount = $jadwal->alpha_count;
                                        $total = $jadwal->total;
                                        $persen = $jadwal->persen_hadir;
                                    @endphp
                                    <tr>
                                        <td style="text-align:center; font-size:0.78rem; color:var(--text-muted); font-weight:600;">{{ $jadwalList->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div style="font-weight:700; font-size:0.9rem; color:var(--text-primary);">
                                                {{ $jadwal->nama_kegiatan }}
                                            </div>
                                            @if($jadwal->keterangan)
                                                <div style="font-size:0.75rem; color:var(--text-secondary); max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                                    {{ $jadwal->keterangan }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="font-weight:600; font-size:0.85rem; color:var(--text-primary);">
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}
                                            </div>
                                            <div style="font-size:0.75rem; color:var(--text-muted);">
                                                {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('l') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div style="font-weight:600; font-size:0.85rem;">{{ $jadwal->tempat }}</div>
                                            @if($jadwal->lokasi_gmaps)
                                                <a href="{{ $jadwal->lokasi_gmaps }}" target="_blank" rel="noopener noreferrer"
                                                   class="gmaps-link" title="Buka di Google Maps">
                                                    <i class="fa-solid fa-location-dot"></i> Lihat Peta
                                                </a>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            <span class="kehadiran-badge hadir">{{ $hadirCount }}</span>
                                        </td>
                                        <td style="text-align:center;">
                                            <span class="kehadiran-badge ijin">{{ $ijinCount }}</span>
                                        </td>
                                        <td style="text-align:center;">
                                            <span class="kehadiran-badge alpha">{{ $alphaCount }}</span>
                                        </td>
                                        <td style="text-align:center; font-weight:700; font-size:0.9rem;">{{ $total }}</td>
                                        <td style="text-align:center;">
                                            <div class="persen-wrap">
                                                <div class="persen-bar-outer">
                                                    <div class="persen-bar-inner" style="width:{{ $persen }}%;"></div>
                                                </div>
                                                <span class="persen-text {{ $persen >= 75 ? 'good' : ($persen >= 50 ? 'warn' : 'bad') }}">{{ $persen }}%</span>
                                            </div>
                                        </td>
                                        <td style="text-align:center;">
                                            <div style="display:flex; gap:4px; justify-content:center;">
                                                <button type="button" class="btn btn-primary btn-sm btn-icon"
                                                        title="Detail & Kehadiran"
                                                        onclick="previewKehadiran({{ $jadwal->id_jadwal }})">
                                                    <i class="fa-solid fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm btn-icon"
                                                        title="Input Kehadiran"
                                                        onclick="inputKehadiran({{ $jadwal->id_jadwal }})">
                                                    <i class="fa-solid fa-clipboard-user"></i>
                                                </button>
                                                <button type="button" class="btn btn-secondary btn-sm btn-icon"
                                                        title="Edit Kegiatan"
                                                        onclick="editPengajian({{ json_encode($jadwal) }})">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm btn-icon"
                                                        title="Hapus"
                                                        onclick="confirmDelete('{{ route('ismuba.jadwal-pengajian.destroy', $jadwal->id_jadwal) }}', 'Yakin ingin menghapus jadwal pengajian ini? Semua data kehadiran guru terkait juga akan terhapus.')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $jadwalList->links('pagination.presensi') }}
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══════════ TAB 2: REKAP KEHADIRAN GURU ═══════════ --}}
        <div class="tab-content" id="tab-rekap" style="display:none;">
            <div class="card-header" style="border-top: 1px solid var(--border-color);">
                <h2 class="card-title"><i class="fa-solid fa-chart-bar"></i> Rekap Kehadiran per Guru Karyawan</h2>
            </div>
            <div class="card-body p-0">
                @if($rekapPenerima->isEmpty())
                    <div style="text-align:center; padding:60px 20px; color:var(--text-muted);">
                        <i class="fa-solid fa-chart-bar" style="font-size:3rem; opacity:0.2; display:block; margin-bottom:14px;"></i>
                        <div style="font-weight:600;">Belum ada data guru</div>
                    </div>
                @else
                    <div style="overflow-x:auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width:44px; text-align:center;">#</th>
                                    <th>Nama Guru / Karyawan</th>
                                    <th style="text-align:center;">Jumlah Kegiatan</th>
                                    <th style="text-align:center;">Hadir</th>
                                    <th style="text-align:center;">Ijin</th>
                                    <th style="text-align:center;">Alpha</th>
                                    <th style="text-align:center;">Total Jadwal</th>
                                    <th style="min-width:180px;">% Kehadiran</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($rekapPenerima as $idx => $rekap)
                                    <tr>
                                        <td style="text-align:center; font-size:0.78rem; color:var(--text-muted); font-weight:600;">{{ $idx + 1 }}</td>
                                        <td>
                                            <div style="display:flex; align-items:center; gap:10px;">
                                                <div class="rekap-avatar">
                                                    {{ strtoupper(substr($rekap->nama_tampil ?? '-', 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div style="font-weight:600; font-size:0.9rem;">{{ $rekap->nama_tampil }}</div>
                                                    @if(($rekap->tipe ?? 'guru') === 'karyawan')
                                                        <span style="font-size:0.68rem; font-weight:700; background:#fef3c7; color:#92400e; padding:1px 6px; border-radius:4px;">Karyawan</span>
                                                    @else
                                                        <span style="font-size:0.68rem; font-weight:700; background:#d1fae5; color:#065f46; padding:1px 6px; border-radius:4px;">Guru</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td style="text-align:center;">
                                            <span style="font-weight:700; background:#e0f2fe; color:#0369a1; padding:3px 10px; border-radius:20px; font-size:0.85rem;">
                                                {{ $rekap->total_kegiatan }}x
                                            </span>
                                        </td>
                                        <td style="text-align:center;"><span class="kehadiran-badge hadir">{{ $rekap->total_hadir }}</span></td>
                                        <td style="text-align:center;"><span class="kehadiran-badge ijin">{{ $rekap->total_ijin }}</span></td>
                                        <td style="text-align:center;"><span class="kehadiran-badge alpha">{{ $rekap->total_alpha }}</span></td>
                                        <td style="text-align:center; font-weight:700;">{{ $rekap->total_peserta }}</td>
                                        <td>
                                            <div style="display:flex; align-items:center; gap:10px;">
                                                <div class="rekap-bar-outer">
                                                    <div class="rekap-bar-inner {{ $rekap->persen_hadir >= 75 ? 'good' : ($rekap->persen_hadir >= 50 ? 'warn' : 'bad') }}"
                                                         style="width:{{ $rekap->persen_hadir }}%;"></div>
                                                </div>
                                                <span style="font-weight:700; font-size:0.88rem; min-width:42px;
                                                             color: {{ $rekap->persen_hadir >= 75 ? '#059669' : ($rekap->persen_hadir >= 50 ? '#d97706' : '#dc2626') }};">
                                                    {{ $rekap->persen_hadir }}%
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- ═══════════ TAB 3: CETAK LAPORAN ═══════════ --}}
        <div class="tab-content" id="tab-cetak" style="display:none;">
            <div class="card-header" style="border-top: 1px solid var(--border-color);">
                <h2 class="card-title"><i class="fa-solid fa-print"></i> Cetak Laporan Pengajian</h2>
            </div>
            <div class="card-body">
                <div style="max-width:520px; margin:0 auto;">
                    <div class="print-options-card">
                        <div style="text-align:center; margin-bottom:24px;">
                            <div style="width:64px; height:64px; background:linear-gradient(135deg,#059669,#10b981); border-radius:16px; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                                <i class="fa-solid fa-file-pdf" style="font-size:1.8rem; color:#fff;"></i>
                            </div>
                            <h3 style="font-weight:700; font-size:1rem; color:var(--text-primary); margin:0;">Cetak Laporan Kehadiran Pengajian</h3>
                            <p style="font-size:0.83rem; color:var(--text-muted); margin-top:4px;">Pilih periode laporan yang ingin dicetak</p>
                        </div>

                        <form action="{{ route('ismuba.jadwal-pengajian.print') }}" method="GET" target="_blank">
                            <div class="form-group">
                                <label class="form-label"><i class="fa-regular fa-calendar"></i> Tanggal Mulai</label>
                                <input type="date" name="tanggal_mulai" id="print-tanggal-mulai"
                                       class="form-control"
                                       value="{{ now()->startOfMonth()->format('Y-m-d') }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label"><i class="fa-regular fa-calendar-days"></i> Tanggal Akhir</label>
                                <input type="date" name="tanggal_akhir" id="print-tanggal-akhir"
                                       class="form-control"
                                       value="{{ now()->endOfMonth()->format('Y-m-d') }}">
                            </div>
                            <div style="display:flex; gap:10px; margin-top:20px;">
                                <button type="submit" class="btn btn-primary" style="flex:1;">
                                    <i class="fa-solid fa-print"></i> Cetak Laporan
                                </button>
                                <a href="{{ route('ismuba.jadwal-pengajian.print') }}" target="_blank" class="btn btn-secondary" style="flex:1; text-align:center;">
                                    <i class="fa-solid fa-file-lines"></i> Cetak Semua
                                </a>
                            </div>
                        </form>
                    </div>

                    {{-- Preview info --}}
                    <div class="print-info-box">
                        <i class="fa-solid fa-circle-info" style="color:#0369a1; font-size:1rem;"></i>
                        <div>
                            <div style="font-weight:600; font-size:0.88rem; color:var(--text-primary);">Informasi Laporan</div>
                            <ul style="font-size:0.82rem; color:var(--text-secondary); margin:6px 0 0 16px; padding:0; line-height:1.8;">
                                <li>Laporan memuat daftar kegiatan pengajian, tempat, guru yang terdaftar, serta rekap kehadiran detail</li>
                                <li>Pilih tanggal mulai dan tanggal akhir untuk filter data per rentang periode</li>
                                <li>Pilih "Cetak Semua" untuk laporan keseluruhan tanpa filter</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══════ MODAL TAMBAH / EDIT KEGIATAN ═══════ --}}
<div class="modal-overlay" id="modal-add-pengajian">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-title-pengajian">
                <i class="fa-solid fa-mosque" style="color:var(--color-primary);"></i>
                Tambah Jadwal Pengajian
            </h3>
            <button onclick="closeModal('modal-add-pengajian')" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="form-pengajian" method="POST" action="{{ route('ismuba.jadwal-pengajian.store') }}">
            @csrf
            <div id="pengajian-method-field"></div>
            <div class="modal-body">
                <div class="form-grid-2">
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label"><i class="fa-solid fa-heading"></i> Nama Kegiatan / Judul Pengajian <span class="required">*</span></label>
                        <input type="text" name="nama_kegiatan" id="pj_nama" class="form-control"
                               placeholder="Contoh: Pengajian Bulanan Ahad Pagi, Kajian Tafsir..." required maxlength="200">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fa-regular fa-calendar"></i> Tanggal <span class="required">*</span></label>
                        <input type="date" name="tanggal" id="pj_tanggal" class="form-control" required
                               value="{{ now()->format('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fa-solid fa-building"></i> Tempat <span class="required">*</span></label>
                        <input type="text" name="tempat" id="pj_tempat" class="form-control"
                               placeholder="Contoh: Masjid Al-Hikmah, Aula Utama..." required maxlength="200">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fa-regular fa-clock"></i> Jam Mulai</label>
                        <input type="time" name="jam_mulai" id="pj_jam_mulai" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fa-regular fa-clock"></i> Jam Selesai</label>
                        <input type="time" name="jam_selesai" id="pj_jam_selesai" class="form-control">
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label"><i class="fa-solid fa-location-dot"></i> Link Google Maps Lokasi</label>
                        <div style="position:relative;">
                            <input type="text" name="lokasi_gmaps" id="pj_lokasi" class="form-control"
                                   placeholder="Paste link Google Maps (panjang atau pendek)..." maxlength="2000"
                                   style="padding-right:120px;"
                                   oninput="onMapsLinkChange()">
                            <button type="button" id="btn-preview-map" class="btn btn-secondary btn-sm"
                                    style="position:absolute; right:4px; top:50%; transform:translateY(-50%);"
                                    onclick="previewMap()">
                                <i class="fa-solid fa-map-location-dot"></i> Preview
                            </button>
                        </div>
                        <div id="pj_coords_info" style="display:none; margin-top:6px; font-size:0.8rem; color:#059669; font-weight:600;">
                            <i class="fa-solid fa-check-circle"></i> <span id="pj_coords_text"></span>
                        </div>
                        <div id="pj_coords_manual" style="display:none; margin-top:4px; font-size:0.78rem; color:#6b7280;">
                            Koordinat dari link: <span id="pj_lat_display">—</span>, <span id="pj_lng_display">—</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fa-solid fa-circle-dot"></i> Radius Absensi (Meter)</label>
                        <input type="number" name="radius_meter" id="pj_radius" class="form-control"
                               placeholder="Contoh: 500" value="500" min="1" max="10000">
                        <small style="color:var(--text-muted);">Jarak maksimal dari titik lokasi untuk absensi mobile. Default: 500 m.</small>
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label"><i class="fa-regular fa-comment-dots"></i> Keterangan</label>
                        <textarea name="keterangan" id="pj_keterangan" class="form-control" rows="2"
                                  placeholder="Catatan tambahan kegiatan pengajian (opsional)..."
                                  maxlength="1000"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-add-pengajian')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════ MODAL PREVIEW DETAIL KEHADIRAN GURU ═══════ --}}
<div class="modal-overlay" id="modal-preview-kehadiran">
    <div class="modal modal-xl" style="max-width:90%; width:960px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-clipboard-user" style="color:var(--color-primary);"></i> Detail Kehadiran Guru & Karyawan</h3>
            <button onclick="closeModal('modal-preview-kehadiran')" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body">
            <div id="preview-kehadiran-loading" style="text-align:center; padding:40px;">
                <i class="fa-solid fa-circle-notch fa-spin" style="font-size:2.5rem; color:var(--color-primary);"></i>
                <p style="margin-top:10px; color:var(--text-secondary);">Memuat detail kehadiran...</p>
            </div>
            <div id="preview-kehadiran-content" style="display:none;">
                <div class="detail-kegiatan-header" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:16px; margin-bottom:20px; display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px;">
                    <div>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:600; text-transform:uppercase;">Nama Kegiatan</div>
                        <div id="prev_nama" style="font-weight:700; font-size:1rem; color:var(--text-primary);"></div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:600; text-transform:uppercase;">Tanggal</div>
                        <div id="prev_tanggal" style="font-weight:600; font-size:0.9rem;"></div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:600; text-transform:uppercase;">Tempat</div>
                        <div id="prev_tempat" style="font-weight:600; font-size:0.9rem;"></div>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; color:var(--text-muted); font-weight:600; text-transform:uppercase;">Link Lokasi</div>
                        <div id="prev_gmaps_wrap"></div>
                    </div>
                </div>

                <div style="overflow-x:auto;">
                    <table class="data-table" id="table-detail-kehadiran">
                        <thead>
                            <tr>
                                <th style="width:44px; text-align:center;">#</th>
                                <th>Nama Guru / Karyawan</th>
                                <th style="text-align:center; width:120px;">Status</th>
                                <th style="text-align:center; width:100px;">Jam Absen</th>
                                <th style="text-align:center; width:120px;">Foto Bukti</th>
                                <th>Lokasi Absen</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody id="prev_tbody">
                            {{-- Generated by JS --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeModal('modal-preview-kehadiran')" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</div>

{{-- ═══════ MODAL INPUT KEHADIRAN ═══════ --}}
<div class="modal-overlay" id="modal-input-kehadiran">
    <div class="modal modal-xl" style="max-width:95%; width:1080px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-user-pen" style="color:var(--color-primary);"></i> Input & Edit Kehadiran Guru</h3>
            <button onclick="closeModal('modal-input-kehadiran')" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="form-input-kehadiran" method="POST" action="" enctype="multipart/form-data">
            @csrf
            <div class="modal-body" style="max-height: calc(100vh - 250px); overflow-y: auto;">
                <div id="input-kehadiran-loading" style="text-align:center; padding:40px;">
                    <i class="fa-solid fa-circle-notch fa-spin" style="font-size:2.5rem; color:var(--color-primary);"></i>
                    <p style="margin-top:10px; color:var(--text-secondary);">Memuat daftar guru...</p>
                </div>
                <div id="input-kehadiran-content" style="display:none;">
                    <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; padding:12px; margin-bottom:16px; font-size:0.83rem; color:#166534; display:flex; align-items:center; gap:8px;">
                        <i class="fa-solid fa-circle-info"></i>
                        <span>Anda dapat memperbarui status kehadiran, menambahkan keterangan ijin/alpha, lokasi maps, serta mengunggah bukti foto guru.</span>
                    </div>

                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width:44px; text-align:center;">#</th>
                                <th>Nama Guru / Karyawan</th>
                                <th style="width:140px;">Status Kehadiran</th>
                                <th style="width:180px;">Foto Bukti (Upload)</th>
                                <th>Keterangan / Alasan</th>
                                <th>Lokasi Absen (Google Maps Link)</th>
                            </tr>
                        </thead>
                        <tbody id="input_tbody">
                            {{-- Generated by JS --}}
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-input-kehadiran')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Kehadiran</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
/* ── Stat Cards ── */
.pengajian-stats-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}
.pengajian-stat-card {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px 20px;
    border-radius: var(--radius-card);
    color: #fff;
    box-shadow: 0 6px 24px rgba(0,0,0,0.12);
    transition: var(--transition-smooth);
}
.pengajian-stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 36px rgba(0,0,0,0.18); }
.pengajian-stat-icon {
    width: 46px; height: 46px;
    background: rgba(255,255,255,0.2);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem; flex-shrink: 0;
}
.pengajian-stat-num { font-size: 1.9rem; font-weight: 800; line-height: 1; }
.pengajian-stat-lbl { font-size: 0.76rem; opacity: .85; margin-top: 2px; }

/* ── Tab Navigation ── */
.tab-nav {
    display: flex;
    gap: 4px;
    padding: 16px 20px 0;
    border-bottom: 2px solid var(--border-color);
    background: var(--card-bg);
    border-radius: var(--radius-card) var(--radius-card) 0 0;
}
.tab-btn {
    background: none;
    border: none;
    border-bottom: 3px solid transparent;
    padding: 10px 20px;
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-muted);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 7px;
    border-radius: 8px 8px 0 0;
    transition: all 0.2s ease;
    margin-bottom: -2px;
}
.tab-btn:hover { color: var(--color-primary); background: rgba(var(--color-primary-rgb, 16,185,129), 0.06); }
.tab-btn.active { color: var(--color-primary); border-bottom-color: var(--color-primary); background: rgba(var(--color-primary-rgb, 16,185,129), 0.06); }

/* ── Badges ── */
.kehadiran-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    padding: 3px 8px;
    border-radius: 20px;
    font-size: 0.82rem;
    font-weight: 700;
}
.kehadiran-badge.hadir  { background: #d1fae5; color: #065f46; }
.kehadiran-badge.ijin   { background: #fef3c7; color: #92400e; }
.kehadiran-badge.alpha  { background: #fee2e2; color: #991b1b; }

/* ── Progress bar (table) ── */
.persen-wrap { display: flex; align-items: center; gap: 8px; justify-content: center; }
.persen-bar-outer {
    flex: 1;
    max-width: 80px;
    height: 7px;
    background: #e2e8f0;
    border-radius: 20px;
    overflow: hidden;
}
.persen-bar-inner {
    height: 100%;
    border-radius: 20px;
    background: linear-gradient(90deg, #059669, #10b981);
    transition: width 0.5s ease;
}
.persen-text { font-size: 0.8rem; font-weight: 700; min-width: 40px; }
.persen-text.good { color: #059669; }
.persen-text.warn { color: #d97706; }
.persen-text.bad  { color: #dc2626; }

/* ── Rekap bar ── */
.rekap-avatar {
    width: 34px; height: 34px;
    background: linear-gradient(135deg, #059669, #10b981);
    border-radius: 50%;
    color: #fff;
    font-size: 0.85rem;
    font-weight: 800;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.rekap-bar-outer {
    flex: 1;
    height: 10px;
    background: #e2e8f0;
    border-radius: 20px;
    overflow: hidden;
    max-width: 120px;
}
.rekap-bar-inner {
    height: 100%;
    border-radius: 20px;
    transition: width 0.6s ease;
}
.rekap-bar-inner.good { background: linear-gradient(90deg, #059669, #10b981); }
.rekap-bar-inner.warn { background: linear-gradient(90deg, #d97706, #f59e0b); }
.rekap-bar-inner.bad  { background: linear-gradient(90deg, #dc2626, #f87171); }

/* ── GMaps link ── */
.gmaps-link {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.75rem;
    color: #0369a1;
    font-weight: 600;
    text-decoration: none;
    margin-top: 3px;
    transition: color 0.2s;
}
.gmaps-link:hover { color: #1d4ed8; text-decoration: underline; }

/* ── Print card ── */
.print-options-card {
    background: var(--card-bg);
    border: 1.5px solid var(--border-color);
    border-radius: var(--radius-card);
    padding: 28px;
    margin-bottom: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.06);
}
.print-info-box {
    display: flex;
    gap: 12px;
    align-items: flex-start;
    background: #e0f2fe;
    border: 1px solid #bae6fd;
    border-radius: 12px;
    padding: 14px 16px;
}

/* ── Btn icon ── */
.btn-icon { width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center; }

@media(max-width:768px) {
    .pengajian-stats-row { grid-template-columns: repeat(2, 1fr); }
}
@media(max-width:480px) {
    .pengajian-stats-row { grid-template-columns: 1fr; }
    .tab-btn { padding: 8px 12px; font-size: 0.8rem; }
}
</style>
@endpush

@push('scripts')
<script>
// ── Tab switching ──
document.querySelectorAll('.tab-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        const target = this.dataset.tab;
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
        this.classList.add('active');
        document.getElementById(target).style.display = 'block';
    });
});

// ── Preview Google Maps ──
function previewMap() {
    const url = document.getElementById('pj_lokasi').value.trim();
    if (!url) {
        alert('Silakan masukkan URL Google Maps terlebih dahulu.');
        return;
    }
    if (!url.startsWith('http')) {
        alert('URL tidak valid. Pastikan URL dimulai dengan http:// atau https://');
        return;
    }
    window.open(url, '_blank', 'noopener,noreferrer');
}

// ── Ekstrak koordinat dari URL Google Maps di sisi client (preview saja) ──
function extractCoordsFromUrl(url) {
    if (!url) return null;
    // Format /@lat,lng
    let m = url.match(/@(-?\d+\.\d+),(-?\d+\.\d+)\//);
    if (m) return { lat: parseFloat(m[1]), lng: parseFloat(m[2]) };
    // Format !3d...!4d...
    m = url.match(/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/);
    if (m) return { lat: parseFloat(m[1]), lng: parseFloat(m[2]) };
    // Format ?q=lat,lng atau &ll=lat,lng
    m = url.match(/[?&](?:q|ll)=(-?\d+\.\d+),(-?\d+\.\d+)/);
    if (m) return { lat: parseFloat(m[1]), lng: parseFloat(m[2]) };
    return null;
}

function onMapsLinkChange() {
    const url   = document.getElementById('pj_lokasi').value.trim();
    const info  = document.getElementById('pj_coords_info');
    const text  = document.getElementById('pj_coords_text');
    const manualDiv = document.getElementById('pj_coords_manual');
    const latEl = document.getElementById('pj_lat_display');
    const lngEl = document.getElementById('pj_lng_display');

    const coords = extractCoordsFromUrl(url);
    if (coords) {
        info.style.display   = 'block';
        manualDiv.style.display = 'block';
        text.textContent  = 'Koordinat terdeteksi dan akan disimpan otomatis';
        latEl.textContent = coords.lat.toFixed(6);
        lngEl.textContent = coords.lng.toFixed(6);
    } else if (url && (url.includes('maps.app.goo.gl') || url.includes('goo.gl/maps'))) {
        info.style.display   = 'block';
        manualDiv.style.display = 'none';
        text.textContent  = 'Link pendek terdeteksi — koordinat akan diekstrak saat disimpan';
    } else {
        info.style.display  = 'none';
        manualDiv.style.display = 'none';
    }
}

// ── Reset form pengajian ──
function resetPengajianModal() {
    document.getElementById('form-pengajian').action  = '{{ route("ismuba.jadwal-pengajian.store") }}';
    document.getElementById('pengajian-method-field').innerHTML = '';
    document.getElementById('modal-title-pengajian').innerHTML  =
        '<i class="fa-solid fa-mosque" style="color:var(--color-primary);"></i> Tambah Jadwal Pengajian';
    document.getElementById('pj_tanggal').value    = '{{ now()->format("Y-m-d") }}';
    document.getElementById('pj_tempat').value     = '';
    document.getElementById('pj_nama').value       = '';
    document.getElementById('pj_lokasi').value     = '';
    document.getElementById('pj_keterangan').value = '';
    document.getElementById('pj_jam_mulai').value  = '';
    document.getElementById('pj_jam_selesai').value = '';
    document.getElementById('pj_radius').value     = '500';
    document.getElementById('pj_coords_info').style.display  = 'none';
    document.getElementById('pj_coords_manual').style.display = 'none';
}

// ── Open modal for adding ──
document.getElementById('btn-tambah-pengajian').addEventListener('click', function() {
    resetPengajianModal();
    openModal('modal-add-pengajian');
});

// ── Open modal for editing ──
function editPengajian(data) {
    document.getElementById('form-pengajian').action =
        `/ismuba/jadwal-pengajian/${data.id_jadwal}`;
    document.getElementById('pengajian-method-field').innerHTML =
        '<input type="hidden" name="_method" value="POST">';
    document.getElementById('modal-title-pengajian').innerHTML =
        '<i class="fa-solid fa-pen" style="color:var(--color-primary);"></i> Edit Jadwal Pengajian';

    document.getElementById('pj_tanggal').value    = data.tanggal ? data.tanggal.substring(0, 10) : '';
    document.getElementById('pj_tempat').value     = data.tempat  || '';
    document.getElementById('pj_nama').value       = data.nama_kegiatan || '';
    document.getElementById('pj_lokasi').value     = data.lokasi_gmaps || '';
    document.getElementById('pj_keterangan').value = data.keterangan || '';
    document.getElementById('pj_jam_mulai').value  = data.jam_mulai  ? data.jam_mulai.substring(0,5)  : '';
    document.getElementById('pj_jam_selesai').value = data.jam_selesai ? data.jam_selesai.substring(0,5) : '';
    document.getElementById('pj_radius').value     = data.radius_meter || '500';

    // Tampilkan info koordinat jika sudah tersimpan
    const infoDiv  = document.getElementById('pj_coords_info');
    const textEl   = document.getElementById('pj_coords_text');
    const manualDiv = document.getElementById('pj_coords_manual');
    const latEl    = document.getElementById('pj_lat_display');
    const lngEl    = document.getElementById('pj_lng_display');
    if (data.latitude && data.longitude) {
        infoDiv.style.display   = 'block';
        manualDiv.style.display = 'block';
        textEl.textContent  = 'Koordinat tersimpan';
        latEl.textContent   = parseFloat(data.latitude).toFixed(6);
        lngEl.textContent   = parseFloat(data.longitude).toFixed(6);
    } else {
        onMapsLinkChange();
    }

    openModal('modal-add-pengajian');
}

// ── Preview Kehadiran Detail ──
function previewKehadiran(id) {
    openModal('modal-preview-kehadiran');
    document.getElementById('preview-kehadiran-loading').style.display = 'block';
    document.getElementById('preview-kehadiran-content').style.display = 'none';

    fetch(`/ismuba/jadwal-pengajian/${id}/detail`)
        .then(res => res.json())
        .then(data => {
            document.getElementById('prev_nama').textContent = data.jadwal.nama_kegiatan;
            
            // Format tanggal
            const dateObj = new Date(data.jadwal.tanggal);
            const opt = { day: 'numeric', month: 'long', year: 'numeric' };
            document.getElementById('prev_tanggal').textContent = dateObj.toLocaleDateString('id-ID', opt);
            
            document.getElementById('prev_tempat').textContent = data.jadwal.tempat;

            const mapWrap = document.getElementById('prev_gmaps_wrap');
            if (data.jadwal.lokasi_gmaps) {
                mapWrap.innerHTML = `<a href="${data.jadwal.lokasi_gmaps}" target="_blank" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:6px;"><i class="fa-solid fa-map-location-dot"></i> Lihat Peta</a>`;
            } else {
                mapWrap.innerHTML = `<span style="color:var(--text-muted); font-size:0.85rem;">Tidak ada link peta</span>`;
            }

            const tbody = document.getElementById('prev_tbody');
            tbody.innerHTML = '';

            if (data.kehadiran.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:20px; color:var(--text-muted);">Belum ada data guru terdaftar</td></tr>`;
            } else {
                data.kehadiran.forEach((item, index) => {
                    let statusBadge = '';
                    if (item.status === 'hadir') statusBadge = '<span class="kehadiran-badge hadir">Hadir</span>';
                    else if (item.status === 'ijin') statusBadge = '<span class="kehadiran-badge ijin">Ijin</span>';
                    else statusBadge = '<span class="kehadiran-badge alpha">Alpha</span>';

                    let fotoCell = '<span style="color:var(--text-muted); font-size:0.75rem;">—</span>';
                    if (item.foto) {
                        fotoCell = `<a href="${item.foto}" target="_blank" title="Lihat Bukti Foto"><img src="${item.foto}" style="max-height:40px; max-width:60px; object-fit:cover; border-radius:4px; border:1px solid #cbd5e1;"></a>`;
                    }

                    let lokasiCell = '<span style="color:var(--text-muted); font-size:0.75rem;">—</span>';
                    if (item.lokasi_gmaps) {
                        lokasiCell = `<a href="${item.lokasi_gmaps}" target="_blank" class="gmaps-link"><i class="fa-solid fa-location-dot"></i> Buka Maps</a>`;
                    }

                    const tipeBadge = item.tipe === 'karyawan'
                        ? '<span style="font-size:0.65rem; font-weight:700; background:#fef3c7; color:#92400e; padding:1px 6px; border-radius:4px;">Karyawan</span>'
                        : '<span style="font-size:0.65rem; font-weight:700; background:#d1fae5; color:#065f46; padding:1px 6px; border-radius:4px;">Guru</span>';

                    const row = `
                        <tr>
                            <td style="text-align:center; font-weight:600; color:var(--text-muted);">${index + 1}</td>
                            <td>
                                <div style="font-weight:600; color:var(--text-primary);">${item.nama_guru}</div>
                                ${tipeBadge}
                            </td>
                            <td style="text-align:center;">${statusBadge}</td>
                            <td style="text-align:center; font-size:0.8rem; font-weight:500;">${item.jam_absen || '—'}</td>
                            <td style="text-align:center;">${fotoCell}</td>
                            <td>${lokasiCell}</td>
                            <td style="font-size:0.8rem; color:var(--text-secondary);">${item.keterangan || '—'}</td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            }

            document.getElementById('preview-kehadiran-loading').style.display = 'none';
            document.getElementById('preview-kehadiran-content').style.display = 'block';
        })
        .catch(err => {
            console.error(err);
            alert('Gagal mengambil data detail kehadiran.');
            closeModal('modal-preview-kehadiran');
        });
}

// ── Input Kehadiran Form ──
function inputKehadiran(id) {
    openModal('modal-input-kehadiran');
    document.getElementById('input-kehadiran-loading').style.display = 'block';
    document.getElementById('input-kehadiran-content').style.display = 'none';
    document.getElementById('form-input-kehadiran').action = `/ismuba/jadwal-pengajian/${id}/kehadiran`;

    fetch(`/ismuba/jadwal-pengajian/${id}/detail`)
        .then(res => res.json())
        .then(data => {
            const tbody = document.getElementById('input_tbody');
            tbody.innerHTML = '';

            if (data.kehadiran.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" style="text-align:center; padding:20px; color:var(--text-muted);">Belum ada data guru terdaftar</td></tr>`;
            } else {
                data.kehadiran.forEach((item, index) => {
                    const tipeBadge = item.tipe === 'karyawan'
                        ? '<span style="font-size:0.65rem; font-weight:700; background:#fef3c7; color:#92400e; padding:1px 6px; border-radius:4px;">Karyawan</span>'
                        : '<span style="font-size:0.65rem; font-weight:700; background:#d1fae5; color:#065f46; padding:1px 6px; border-radius:4px;">Guru</span>';

                    const row = `
                        <tr>
                            <td style="text-align:center; font-weight:600; color:var(--text-muted);">${index + 1}</td>
                            <td>
                                <div style="font-weight:600; color:var(--text-primary);">${item.nama_guru}</div>
                                ${tipeBadge}
                            </td>
                            <td>
                                <select name="kehadiran[${item.id}][status]" class="form-control form-control-sm" required style="font-weight:600;">
                                    <option value="hadir" ${item.status === 'hadir' ? 'selected' : ''}>Hadir</option>
                                    <option value="ijin" ${item.status === 'ijin' ? 'selected' : ''}>Ijin</option>
                                    <option value="alpha" ${item.status === 'alpha' ? 'selected' : ''}>Alpha</option>
                                </select>
                            </td>
                            <td>
                                <div style="display:flex; flex-direction:column; gap:4px;">
                                    ${item.foto ? `<span style="font-size:0.7rem; color:var(--color-primary); font-weight:600;"><i class="fa-solid fa-image"></i> Sudah Ada Foto</span>` : ''}
                                    <input type="file" name="kehadiran[${item.id}][foto]" class="form-control form-control-sm" accept="image/*" style="font-size:0.75rem;">
                                </div>
                            </td>
                            <td>
                                <input type="text" name="kehadiran[${item.id}][keterangan]" value="${item.keterangan || ''}" class="form-control form-control-sm" placeholder="Alasan jika ijin/catatan..." maxlength="255">
                            </td>
                            <td>
                                <input type="url" name="kehadiran[${item.id}][lokasi_gmaps]" value="${item.lokasi_gmaps || ''}" class="form-control form-control-sm" placeholder="https://maps.google.com/..." maxlength="2000">
                            </td>
                        </tr>
                    `;
                    tbody.insertAdjacentHTML('beforeend', row);
                });
            }

            document.getElementById('input-kehadiran-loading').style.display = 'none';
            document.getElementById('input-kehadiran-content').style.display = 'block';
        })
        .catch(err => {
            console.error(err);
            alert('Gagal mengambil data guru.');
            closeModal('modal-input-kehadiran');
        });
}
</script>
@endpush
@endsection
