@extends('layouts.app')

@section('title', 'Data Penempatan PKL — SmartSchool')
@section('header_title', 'Data Penempatan PKL')
@section('header_subtitle', 'Mapping siswa ke DUDI per gelombang')

@push('styles')
<style>
.dudi-card {
    background: #fff;
    border: 1px solid var(--border-color, #e2e8f0);
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    margin-bottom: 20px;
    overflow: hidden;
    transition: box-shadow 0.2s;
}
.dudi-card:hover {
    box-shadow: 0 6px 16px rgba(0,0,0,0.08);
}
.dudi-card-header {
    background: #fafbff;
    border-bottom: 1.5px solid #f1f5f9;
    padding: 16px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.dudi-title {
    font-size: 1.05rem;
    font-weight: 700;
    color: var(--text-color, #1e293b);
    display: flex;
    align-items: center;
    gap: 8px;
}
.kuota-progress-bar {
    height: 6px;
    border-radius: 3px;
    background: #e2e8f0;
    overflow: hidden;
    width: 140px;
}
.kuota-progress-fill {
    height: 100%;
    background: var(--color-primary, #4f46e5);
    border-radius: 3px;
}

/* Modal Search & Multi-Select */
.siswa-item-check {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    background: #fff;
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #e2e8f0;
    font-size: 0.84rem;
}
.siswa-item-check:hover {
    background: #f8fafc;
}
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Banner Gelombang Referensi Aktif --}}
    <div class="card mb-4" style="margin-bottom: 20px;">
        <div class="card-body" style="padding:16px 24px;">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <div style="display:flex; align-items:center; gap:12px;">
                    <div style="width:42px; height:42px; border-radius:10px; background:rgba(79,70,229,0.1); color:var(--color-primary,#4f46e5); display:flex; align-items:center; justify-content:center; font-size:1.3rem;">
                        <i class="fa-solid fa-layer-group"></i>
                    </div>
                    <div>
                        <div style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-muted,#64748b); letter-spacing:0.5px;">Gelombang Referensi Aktif</div>
                        <div style="font-size:1.1rem; font-weight:700; color:var(--text-color,#1e293b);">
                            {{ $selectedGelombang->nama_gelombang ?? 'Gelombang PKL' }}
                            @if(optional($gelombangAktif)->id_gelombang == optional($selectedGelombang)->id_gelombang)
                                <span class="badge badge-success" style="font-size:0.7rem; margin-left:6px;">AKTIF SEKARANG</span>
                            @endif
                        </div>
                        @if($selectedGelombang)
                        <div style="font-size:0.78rem; color:var(--text-muted,#64748b); margin-top:2px;">
                            <i class="fa-regular fa-calendar"></i> Periode: {{ \Carbon\Carbon::parse($selectedGelombang->tanggal_mulai)->translatedFormat('d F Y') }} — {{ \Carbon\Carbon::parse($selectedGelombang->tanggal_selesai)->translatedFormat('d F Y') }}
                        </div>
                        @endif
                    </div>
                </div>

                <div style="display:flex; align-items:center; gap:10px;">
                    <form method="GET" action="{{ route('pkl.penempatan.index') }}" style="display:flex; align-items:center; gap:8px;">
                        <label style="font-size:0.8rem; font-weight:600; color:var(--text-secondary,#475569); white-space:nowrap;">Pilih Gelombang:</label>
                        <select name="id_gelombang" class="form-control form-control-sm" style="min-width:180px;" onchange="this.form.submit()">
                            @foreach($gelombangList as $g)
                            <option value="{{ $g->id_gelombang }}" {{ optional($selectedGelombang)->id_gelombang == $g->id_gelombang ? 'selected' : '' }}>
                                {{ $g->nama_gelombang }} {{ optional($gelombangAktif)->id_gelombang == $g->id_gelombang ? '(Aktif)' : '' }}
                            </option>
                            @endforeach
                        </select>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Filter Search & View Mode Switcher --}}
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:12px;">
        <div style="display:flex; align-items:center; gap:10px; flex:1; max-width:600px;">
            <form method="GET" action="{{ route('pkl.penempatan.index') }}" style="width:100%; display:flex; gap:10px; flex-wrap:wrap;">
                <input type="hidden" name="id_gelombang" value="{{ optional($selectedGelombang)->id_gelombang }}">
                <div style="position:relative; flex:1; min-width:200px;">
                    <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted,#94a3b8);"></i>
                    <input type="text" name="search_dudi" class="form-control form-control-sm"
                        placeholder="Cari DUDI / Perusahaan / Jurusan..."
                        value="{{ request('search_dudi') }}"
                        style="padding-left:36px;"
                        onchange="this.form.submit()">
                </div>
                @if(isset($jurusanList) && $jurusanList->isNotEmpty())
                <div style="min-width:180px;">
                    <select name="id_jurusan" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Jurusan --</option>
                        @foreach($jurusanList as $j)
                            <option value="{{ $j->id_jurusan }}" {{ request('id_jurusan') == $j->id_jurusan ? 'selected' : '' }}>
                                {{ $j->nama_jurusan }} ({{ $j->kode_jurusan }})
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </form>
        </div>

        <div style="display:flex; gap:8px;">
            <button type="button" onclick="switchView('dudi')" id="view-btn-dudi" class="btn btn-sm btn-primary" style="font-weight:600;">
                <i class="fa-solid fa-building"></i> Tampilan DUDI per Jurusan ({{ $dudisWithPenempatan->count() }})
            </button>
            <button type="button" onclick="switchView('flat')" id="view-btn-flat" class="btn btn-sm btn-secondary" style="font-weight:600;">
                <i class="fa-solid fa-table"></i> Tabel Flat DUDI ({{ $dudisWithPenempatan->count() }})
            </button>
        </div>
    </div>

    {{-- ============================================================================ --}}
    {{-- VIEW MODE 1: TAMPILAN DUDI KELOMPOK PER JURUSAN --}}
    {{-- ============================================================================ --}}
    <div id="view-panel-dudi" style="display:block;">
        @forelse($groupedDudis as $jurusanNama => $items)
        <div class="jurusan-section mb-6" style="margin-bottom:28px;">
            {{-- Header Kelompok Jurusan --}}
            <div style="background:linear-gradient(135deg, #1e293b, #334155); color:#fff; padding:12px 20px; border-radius:10px; font-size:0.95rem; font-weight:700; display:flex; justify-content:space-between; align-items:center; margin-bottom:14px; box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                <div style="display:flex; align-items:center; gap:10px;">
                    <i class="fa-solid fa-graduation-cap" style="color:#818cf8; font-size:1.1rem;"></i>
                    <span>JURUSAN: {{ strtoupper($jurusanNama) }}</span>
                </div>
                <span class="badge" style="background:rgba(255,255,255,0.18); color:#fff; font-size:0.78rem; padding:4px 10px; border-radius:20px;">
                    {{ $items->count() }} DUDI Terdaftar
                </span>
            </div>

            @foreach($items as $item)
            @php
                $dudi = $item->dudi;
                $penempatanList = $item->penempatanList;
                $terpakai = $item->terpakai;
                $sisaKuota = $item->sisa_kuota;
                $percent = $dudi->kuota_siswa > 0 ? min(100, round(($terpakai / $dudi->kuota_siswa) * 100)) : 0;
            @endphp
            <div class="dudi-card">
                <div class="dudi-card-header">
                    <div>
                        <div class="dudi-title">
                            <i class="fa-solid fa-building" style="color:var(--color-primary,#4f46e5);"></i>
                            {{ $dudi->nama_dudi }}
                            @if($dudi->jurusan)
                                <span class="badge badge-info" style="font-size:0.72rem; font-weight:normal;">{{ $dudi->jurusan->kode_jurusan ?? $dudi->jurusan->nama_jurusan }}</span>
                            @endif
                            @if($dudi->bidang_usaha)
                                <span class="badge badge-secondary" style="font-size:0.72rem; font-weight:normal;">{{ $dudi->bidang_usaha }}</span>
                            @endif
                        </div>
                        <div style="font-size:0.8rem; color:var(--text-muted,#64748b); margin-top:2px;">
                            <i class="fa-solid fa-location-dot"></i> {{ $dudi->alamat }} {{ $dudi->kota ? '('.$dudi->kota.')' : '' }}
                            @if($dudi->no_telepon) | <i class="fa-solid fa-phone"></i> {{ $dudi->no_telepon }} @endif
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:16px;">
                        {{-- Info Kuota DUDI Refresh --}}
                        <div style="text-align:right;">
                            <div style="font-size:0.75rem; text-transform:uppercase; font-weight:700; color:var(--text-muted,#64748b);">
                                Kuota Gelombang Ini
                            </div>
                            <div style="font-size:0.9rem; font-weight:700;">
                                <span style="color:var(--color-primary,#4f46e5);">{{ $terpakai }}</span> / {{ $dudi->kuota_siswa }} siswa
                                <span style="font-size:0.78rem; font-weight:normal; margin-left:4px;" class="{{ $sisaKuota > 0 ? 'text-success' : 'text-danger' }}">
                                    (Sisa: {{ $sisaKuota }})
                                </span>
                            </div>
                            <div class="kuota-progress-bar" style="margin-top:3px;">
                                <div class="kuota-progress-fill" style="width: {{ $percent }}%; background: {{ $percent >= 100 ? '#ef4444' : '#4f46e5' }};"></div>
                            </div>
                        </div>

                        {{-- Tombol Tambah Data Siswa untuk DUDI Ini --}}
                        <button class="btn btn-primary btn-sm" onclick="openAddModal({{ json_encode(['id_dudi' => $dudi->id_dudi, 'nama_dudi' => $dudi->nama_dudi, 'sisa_kuota' => $sisaKuota, 'kuota_siswa' => $dudi->kuota_siswa]) }})"
                            style="font-weight:600; white-space:nowrap; padding:8px 14px;"
                            {{ $sisaKuota <= 0 ? 'disabled' : '' }}>
                            <i class="fa-solid fa-user-plus"></i> + Tambah Data Siswa
                        </button>
                    </div>
                </div>

                {{-- Tabel Siswa di DUDI Ini --}}
                <div class="card-body p-0">
                    <table class="data-table">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th style="width:40px;">#</th>
                                <th>Nama Siswa</th>
                                <th>NIS</th>
                                <th>Kelas</th>
                                <th>Pembimbing Sekolah</th>
                                <th>Tgl Masuk</th>
                                <th>Status</th>
                                <th style="width:90px; text-align:center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penempatanList as $idx => $p)
                            <tr>
                                <td style="font-size:0.8rem; color:var(--text-muted);">{{ $idx + 1 }}</td>
                                <td>
                                    <strong>{{ $p->siswa?->nama_siswa ?? $p->nis }}</strong>
                                </td>
                                <td style="font-family:monospace; font-size:0.83rem;">{{ $p->nis }}</td>
                                <td>
                                    <span class="badge badge-info" style="font-size:0.72rem;">{{ optional(optional($p->siswa)->kelas)->nama_kelas ?? '-' }}</span>
                                </td>
                                <td style="font-size:0.83rem;">{{ optional(optional($p->pembimbing)->guru)->nama_guru ?? '-' }}</td>
                                <td style="font-size:0.83rem;">{{ $p->tanggal_masuk ? \Carbon\Carbon::parse($p->tanggal_masuk)->format('d/m/Y') : '-' }}</td>
                                <td>
                                    @php $colors = ['aktif'=>'success','selesai'=>'info','ditarik'=>'warning','batal'=>'danger']; @endphp
                                    <span class="badge badge-{{ $colors[$p->status] ?? 'muted' }}" style="font-size:0.72rem;">{{ ucfirst($p->status) }}</span>
                                </td>
                                <td class="action-cell" style="text-align:center;">
                                    <button type="button" class="btn-icon btn-edit" title="Edit" onclick="editPenempatan({{ json_encode($p) }})">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button type="button" class="btn-icon btn-delete" title="Hapus"
                                        onclick="confirmDelete('{{ route('pkl.penempatan.destroy', $p->id_penempatan) }}','Hapus data penempatan siswa ini?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4" style="font-size:0.84rem;">
                                    <em>Belum ada siswa yang ditempatkan di {{ $dudi->nama_dudi }} pada gelombang ini.</em>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
        </div>
        @empty
        <div class="card p-6 text-center text-muted">
            <i class="fa-solid fa-building-circle-xmark" style="font-size:2.5rem; opacity:.3; margin-bottom:10px;"></i>
            <div>Tidak ada DUDI aktif ditemukan.</div>
        </div>
        @endforelse
    </div>

    {{-- ============================================================================ --}}
    {{-- VIEW MODE 2: TABEL FLAT DUDI ALTERNATIF --}}
    {{-- ============================================================================ --}}
    <div id="view-panel-flat" style="display:none;">
        <div class="card">
            <div class="card-header" style="background:#fafbff; border-bottom:1px solid #f1f5f9;">
                <h3 class="card-title" style="font-size:0.95rem; font-weight:700; color:var(--text-color,#1e293b);">
                    <i class="fa-solid fa-table-list" style="color:var(--color-primary,#4f46e5);"></i>
                    Daftar Semua DUDI (Tabel Flat)
                </h3>
            </div>
            <div class="card-body p-0">
                <table class="data-table">
                    <thead>
                        <tr style="background:#f8fafc;">
                            <th style="width:40px;">#</th>
                            <th>Nama DUDI / Perusahaan</th>
                            <th>Jurusan</th>
                            <th>Bidang Usaha</th>
                            <th>Kota / Alamat</th>
                            <th>Kuota Gelombang Ini</th>
                            <th>Siswa Ditempatkan</th>
                            <th style="width:140px; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dudisWithPenempatan as $i => $item)
                        @php
                            $dudi = $item->dudi;
                            $penempatanList = $item->penempatanList;
                            $terpakai = $item->terpakai;
                            $sisaKuota = $item->sisa_kuota;
                            $percent = $dudi->kuota_siswa > 0 ? min(100, round(($terpakai / $dudi->kuota_siswa) * 100)) : 0;
                        @endphp
                        <tr>
                            <td style="color:var(--text-muted);font-size:.8rem;">{{ $i + 1 }}</td>
                            <td>
                                <div style="font-weight:700; color:var(--text-color,#1e293b);">{{ $dudi->nama_dudi }}</div>
                                <div style="font-size:.78rem; color:var(--text-muted);">{{ Str::limit($dudi->alamat, 50) }}</div>
                            </td>
                            <td>
                                @if($dudi->jurusan)
                                    <span class="badge badge-info" style="font-size:.72rem;">{{ $dudi->jurusan->kode_jurusan ?? $dudi->jurusan->nama_jurusan }}</span>
                                @else
                                    <span style="font-size:.75rem; color:var(--text-muted);">-</span>
                                @endif
                            </td>
                            <td style="font-size:.83rem;">{{ $dudi->bidang_usaha ?? '-' }}</td>
                            <td style="font-size:.83rem;">{{ $dudi->kota ?? '-' }}</td>
                            <td>
                                <div style="font-size:0.83rem; font-weight:700;">
                                    <span style="color:var(--color-primary,#4f46e5);">{{ $terpakai }}</span> / {{ $dudi->kuota_siswa }} siswa
                                    <span style="font-size:0.75rem; font-weight:normal; margin-left:2px;" class="{{ $sisaKuota > 0 ? 'text-success' : 'text-danger' }}">
                                        (Sisa: {{ $sisaKuota }})
                                    </span>
                                </div>
                                <div class="kuota-progress-bar" style="margin-top:3px; width:90px;">
                                    <div class="kuota-progress-fill" style="width: {{ $percent }}%; background: {{ $percent >= 100 ? '#ef4444' : '#4f46e5' }};"></div>
                                </div>
                            </td>
                            <td>
                                @if($penempatanList->isNotEmpty())
                                    <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                        @foreach($penempatanList as $p)
                                            <span class="badge badge-secondary" style="font-size:0.72rem;" title="Status: {{ ucfirst($p->status) }}">
                                                <i class="fa-solid fa-user-graduate"></i> {{ $p->siswa?->nama_siswa ?? $p->nis }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span style="font-size:0.78rem; color:var(--text-muted); font-style:italic;">Belum ada siswa</span>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <button class="btn btn-primary btn-sm" onclick="openAddModal({{ json_encode(['id_dudi' => $dudi->id_dudi, 'nama_dudi' => $dudi->nama_dudi, 'sisa_kuota' => $sisaKuota, 'kuota_siswa' => $dudi->kuota_siswa]) }})"
                                    style="font-weight:600; white-space:nowrap; padding:4px 10px; font-size:0.78rem;"
                                    {{ $sisaKuota <= 0 ? 'disabled' : '' }}>
                                    <i class="fa-solid fa-user-plus"></i> + Tambah Siswa
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-6">
                                <i class="fa-solid fa-building-circle-xmark" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                                Tidak ada data DUDI ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ============================================================================ --}}
{{-- MODAL TAMBAH DATA SISWA (MULTI-SISWA) / EDIT PENEMPATAN --}}
{{-- ============================================================================ --}}
<div class="modal-overlay" id="modal-penempatan">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-title-penempatan"><i class="fa-solid fa-user-plus" style="color:var(--color-primary,#4f46e5);"></i> Tambah Data Siswa Penempatan</h3>
            <button onclick="closeModal('modal-penempatan')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-penempatan" method="POST" action="{{ route('pkl.penempatan.store') }}">
            @csrf
            <div id="method-field-penempatan"></div>
            <div class="modal-body">
                
                <div class="form-group">
                    <label class="form-label">Gelombang PKL <span class="required">*</span></label>
                    <select name="id_gelombang" id="pn_gelombang" class="form-control" required onchange="loadDudiKuotaModal(); loadSiswaGelombangModal(); loadTanggalGelombang();">
                        <option value="">-- Pilih Gelombang --</option>
                        @foreach($gelombangList as $g)
                        <option value="{{ $g->id_gelombang }}" {{ optional($selectedGelombang)->id_gelombang == $g->id_gelombang ? 'selected' : '' }}>
                            {{ $g->nama_gelombang }} {{ optional($gelombangAktif)->id_gelombang == $g->id_gelombang ? '(Aktif)' : '' }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">DUDI / Perusahaan Tujuan <span class="required">*</span></label>
                    <select name="id_dudi" id="pn_dudi" class="form-control" required>
                        <option value="">-- Pilih Gelombang terlebih dahulu --</option>
                        @foreach($allDudis as $d)
                        <option value="{{ $d->id_dudi }}">{{ $d->nama_dudi }}</option>
                        @endforeach
                    </select>
                    <div id="kuota-info" style="font-size:.78rem; margin-top:4px; color:var(--text-muted);"></div>
                </div>

                {{-- SECTION PILIH SISWA (MULTI-SISWA BISA LEBIH DARI 1) --}}
                <div class="form-group mb-4" id="section-multi-siswa">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                        <label class="form-label mb-0" style="font-weight:600;">Daftar Nama Siswa (Bisa lebih dari 1 siswa) <span class="required">*</span></label>
                        <span class="badge badge-info" id="pn_siswa_count_badge" style="font-size:0.75rem;">0 Siswa Terpilih</span>
                    </div>
                    
                    {{-- AJAX Live Search Input --}}
                    <div style="position:relative; margin-bottom:10px;">
                        <div style="position:relative;">
                            <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--text-muted,#94a3b8); pointer-events:none; font-size:0.85rem;"></i>
                            <input type="text" id="modal_search_siswa" class="form-control form-control-sm"
                                placeholder="🔍 Cari nama atau NIS siswa untuk ditambahkan..."
                                autocomplete="off"
                                style="padding-left:32px; padding-right:28px;"
                                oninput="handleModalSiswaSearch(this.value)"
                                onfocus="handleModalSiswaSearch(this.value)">
                            <span id="btn_clear_modal_search" onclick="clearModalSiswaSearch()"
                                style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer; color:var(--text-muted,#94a3b8); display:none; font-size:0.85rem;">
                                <i class="fa-solid fa-xmark"></i>
                            </span>
                        </div>

                        {{-- Dropdown Hasil Pencarian Live AJAX --}}
                        <div id="modal-siswa-dropdown" style="
                            display:none; position:absolute; top:100%; left:0; right:0;
                            background:#fff; border:1.5px solid #cbd5e1;
                            border-radius:0 0 8px 8px;
                            box-shadow:0 8px 24px rgba(0,0,0,.15); z-index:1000;
                            max-height:220px; overflow-y:auto; margin-top:2px;">
                        </div>
                    </div>

                    {{-- Container List Siswa Terpilih --}}
                    <div id="container-siswa-selected" style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:10px; max-height:200px; overflow-y:auto;">
                        <div class="text-muted text-center py-2" style="font-size:0.82rem;" id="pn-siswa-empty-info">
                            <em>Silakan cari dan pilih siswa di atas.</em>
                        </div>
                        <div id="pn-siswa-list-container" style="display:flex; flex-direction:column; gap:6px;"></div>
                    </div>
                </div>

                {{-- Single Input Mode (Hanya untuk Mode Edit Single Penempatan) --}}
                <input type="hidden" name="nis" id="pn_nis_single">

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Tanggal Masuk</label>
                        <input type="date" name="tanggal_masuk" id="pn_masuk" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Keluar (Rencana)</label>
                        <input type="date" name="tanggal_keluar" id="pn_keluar" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select name="status" id="pn_status" class="form-control" required>
                        <option value="aktif">Aktif</option>
                        <option value="selesai">Selesai</option>
                        <option value="ditarik">Ditarik</option>
                        <option value="batal">Batal</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="pn_keterangan" class="form-control" rows="2" placeholder="Catatan tambahan..."></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-penempatan')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-submit-penempatan"><i class="fa-solid fa-floppy-disk"></i> Simpan Penempatan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let _allSiswaGelombang = [];

function switchView(viewMode) {
    const dudiPanel = document.getElementById('view-panel-dudi');
    const flatPanel = document.getElementById('view-panel-flat');
    const dudiBtn = document.getElementById('view-btn-dudi');
    const flatBtn = document.getElementById('view-btn-flat');

    if (viewMode === 'dudi') {
        dudiPanel.style.display = 'block';
        flatPanel.style.display = 'none';
        dudiBtn.className = 'btn btn-sm btn-primary';
        flatBtn.className = 'btn btn-sm btn-secondary';
    } else {
        dudiPanel.style.display = 'none';
        flatPanel.style.display = 'block';
        dudiBtn.className = 'btn btn-sm btn-secondary';
        flatBtn.className = 'btn btn-sm btn-primary';
    }
}

function loadDudiKuotaModal(selectedDudiId) {
    const idGelombang = document.getElementById('pn_gelombang').value;
    const select = document.getElementById('pn_dudi');
    const info = document.getElementById('kuota-info');

    if (!idGelombang) {
        select.innerHTML = '<option value="">-- Pilih Gelombang terlebih dahulu --</option>';
        if (info) info.textContent = '';
        return;
    }

    fetch(`/pkl/dudi/by-gelombang?id_gelombang=${idGelombang}`)
        .then(r => r.json())
        .then(list => {
            select.innerHTML = '<option value="">-- Pilih DUDI / Perusahaan --</option>';
            list.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id_dudi;
                opt.textContent = `${d.nama_dudi} (Sisa Kuota Gelombang Ini: ${d.sisa_kuota}/${d.kuota_siswa})`;
                if (d.sisa_kuota === 0) {
                    opt.style.color = '#ef4444';
                }
                if (selectedDudiId && d.id_dudi == selectedDudiId) {
                    opt.selected = true;
                }
                select.appendChild(opt);
            });
        });
}

function loadSiswaGelombangModal() {
    const idGelombang = document.getElementById('pn_gelombang').value;
    if (!idGelombang) {
        _allSiswaGelombang = [];
        return;
    }

    fetch(`/pkl/penempatan/siswa-by-gelombang?id_gelombang=${idGelombang}`)
        .then(r => r.json())
        .then(list => {
            _allSiswaGelombang = list || [];
        });
}

function handleModalSiswaSearch(query) {
    const dropdown = document.getElementById('modal-siswa-dropdown');
    const btnClear = document.getElementById('btn_clear_modal_search');
    const q = (query || '').trim().toLowerCase();

    if (q.length > 0) {
        if (btnClear) btnClear.style.display = 'block';
    } else {
        if (btnClear) btnClear.style.display = 'none';
    }

    if (!_allSiswaGelombang || _allSiswaGelombang.length === 0) {
        dropdown.innerHTML = '<div style="padding:10px; text-align:center; color:#64748b; font-size:0.8rem;"><em>Pilih Gelombang terlebih dahulu untuk memuat siswa.</em></div>';
        dropdown.style.display = 'block';
        return;
    }

    const matches = _allSiswaGelombang.filter(s => {
        const nama = (s.nama_siswa || '').toLowerCase();
        const nis = (s.nis || '').toString().toLowerCase();
        const kelas = (s.nama_kelas || '').toLowerCase();
        return q === '' || nama.includes(q) || nis.includes(q) || kelas.includes(q);
    });

    if (matches.length === 0) {
        dropdown.innerHTML = '<div style="padding:10px; text-align:center; color:#64748b; font-size:0.8rem;"><em>Siswa tidak ditemukan</em></div>';
        dropdown.style.display = 'block';
        return;
    }

    dropdown.innerHTML = '';
    matches.slice(0, 30).forEach(s => {
        const item = document.createElement('div');
        item.style.cssText = 'padding:8px 12px; border-bottom:1px solid #f1f5f9; cursor:pointer; font-size:0.83rem; display:flex; justify-content:space-between; align-items:center; transition:background 0.15s;';
        item.onmouseenter = () => item.style.background = '#f8fafc';
        item.onmouseleave = () => item.style.background = '#fff';

        const isDitempatkan = s.sudah_ditempatkan;

        item.innerHTML = `
            <div>
                <strong style="color:#1e293b;">${s.nama_siswa}</strong>
                <div style="font-size:0.75rem; color:#64748b;">${s.nama_kelas} | NIS: ${s.nis} ${isDitempatkan ? '<span style="color:#10b981;">(Sudah ada penempatan)</span>' : ''}</div>
            </div>
            <button type="button" class="btn btn-primary btn-xs" style="padding:2px 8px; font-size:0.75rem; border-radius:4px;">
                <i class="fa-solid fa-plus"></i> Pilih
            </button>
        `;

        item.onclick = () => {
            addSiswaToMultiList(s);
            clearModalSiswaSearch();
        };

        dropdown.appendChild(item);
    });

    dropdown.style.display = 'block';
}

function clearModalSiswaSearch() {
    const input = document.getElementById('modal_search_siswa');
    const dropdown = document.getElementById('modal-siswa-dropdown');
    const btnClear = document.getElementById('btn_clear_modal_search');

    if (input) input.value = '';
    if (btnClear) btnClear.style.display = 'none';
    if (dropdown) {
        dropdown.innerHTML = '';
        dropdown.style.display = 'none';
    }
}

function addSiswaToMultiList(siswa) {
    const container = document.getElementById('pn-siswa-list-container');
    const emptyInfo = document.getElementById('pn-siswa-empty-info');
    if (emptyInfo) emptyInfo.style.display = 'none';

    const existingInput = container.querySelector(`input[value="${siswa.nis}"]`);
    if (existingInput) {
        existingInput.checked = true;
        updateModalSiswaCount();
        return;
    }

    const itemDiv = document.createElement('div');
    itemDiv.className = 'siswa-item-check';

    itemDiv.innerHTML = `
        <label style="display:flex; align-items:center; gap:8px; width:100%; cursor:pointer; margin:0;">
            <input type="checkbox" name="nis_list[]" value="${siswa.nis}" checked onchange="updateModalSiswaCount()" style="width:16px; height:16px; accent-color:var(--color-primary,#4f46e5);">
            <div style="flex:1;">
                <strong>${siswa.nama_siswa}</strong>
                <span class="text-muted" style="font-size:0.76rem; display:block;">${siswa.nama_kelas} | NIS: ${siswa.nis}</span>
            </div>
        </label>
        <button type="button" onclick="this.closest('.siswa-item-check').remove(); updateModalSiswaCount();" style="border:none; background:transparent; color:#ef4444; cursor:pointer; padding:2px 6px;" title="Hapus">
            <i class="fa-solid fa-xmark"></i>
        </button>
    `;

    container.appendChild(itemDiv);
    updateModalSiswaCount();
}

function updateModalSiswaCount() {
    const checkboxes = document.querySelectorAll('#pn-siswa-list-container input[type="checkbox"]:checked');
    const countBadge = document.getElementById('pn_siswa_count_badge');
    const submitBtn = document.getElementById('btn-submit-penempatan');

    if (countBadge) {
        countBadge.textContent = `${checkboxes.length} Siswa Terpilih`;
    }
    if (submitBtn) {
        submitBtn.innerHTML = `<i class="fa-solid fa-floppy-disk"></i> Simpan Penempatan (${checkboxes.length} Siswa)`;
    }
}

function loadTanggalGelombang() {
    const idGelombang = document.getElementById('pn_gelombang').value;
    const masuk = document.getElementById('pn_masuk');
    const keluar = document.getElementById('pn_keluar');

    if (!idGelombang) return;

    fetch(`/pkl/gelombang/${idGelombang}/info`)
        .then(r => r.json())
        .then(g => {
            if (g.tanggal_mulai && !masuk.value) masuk.value = g.tanggal_mulai.substring(0, 10);
            if (g.tanggal_selesai && !keluar.value) keluar.value = g.tanggal_selesai.substring(0, 10);
        });
}

function openAddModal(dudiData) {
    document.getElementById('form-penempatan').action = '{{ route("pkl.penempatan.store") }}';
    document.getElementById('method-field-penempatan').innerHTML = '';
    document.getElementById('modal-title-penempatan').innerHTML = '<i class="fa-solid fa-user-plus" style="color:var(--color-primary,#4f46e5);"></i> Tambah Data Siswa Penempatan';

    document.getElementById('section-multi-siswa').style.display = 'block';
    document.getElementById('pn_nis_single').value = '';
    document.getElementById('pn-siswa-list-container').innerHTML = '';

    const emptyInfo = document.getElementById('pn-siswa-empty-info');
    if (emptyInfo) emptyInfo.style.display = 'block';

    const defaultGelombangId = '{{ optional($selectedGelombang)->id_gelombang ?? "" }}';
    document.getElementById('pn_gelombang').value = defaultGelombangId;

    loadDudiKuotaModal(dudiData ? dudiData.id_dudi : null);
    loadSiswaGelombangModal();
    loadTanggalGelombang();
    updateModalSiswaCount();

    openModal('modal-penempatan');
}

function editPenempatan(data) {
    document.getElementById('form-penempatan').action = `/pkl/penempatan/${data.id_penempatan}`;
    document.getElementById('method-field-penempatan').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-penempatan').innerHTML = '<i class="fa-solid fa-pen-to-square" style="color:var(--color-primary,#4f46e5);"></i> Edit Penempatan Siswa';

    document.getElementById('section-multi-siswa').style.display = 'none';
    document.getElementById('pn_nis_single').value = data.nis;

    document.getElementById('pn_gelombang').value = data.id_gelombang || '';
    document.getElementById('pn_masuk').value = data.tanggal_masuk ? data.tanggal_masuk.substring(0,10) : '';
    document.getElementById('pn_keluar').value = data.tanggal_keluar ? data.tanggal_keluar.substring(0,10) : '';
    document.getElementById('pn_status').value = data.status || 'aktif';
    document.getElementById('pn_keterangan').value = data.keterangan || '';

    loadDudiKuotaModal(data.id_dudi);

    const submitBtn = document.getElementById('btn-submit-penempatan');
    if (submitBtn) {
        submitBtn.innerHTML = '<i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan';
    }

    openModal('modal-penempatan');
}

// Close dropdown on click outside
document.addEventListener('click', (e) => {
    const searchBox = document.getElementById('modal_search_siswa');
    const dropdown = document.getElementById('modal-siswa-dropdown');
    if (dropdown && searchBox && !searchBox.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});
</script>
@endpush
@endsection
