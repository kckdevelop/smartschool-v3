@extends('layouts.app')

@section('title', 'Persuratan PKL — SmartSchool')
@section('header_title', 'Persuratan PKL')
@section('header_subtitle', 'Generate dan arsip surat-surat PKL')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Navigasi Tab Utama --}}
    <div style="display:flex; gap:12px; margin-bottom:24px; border-bottom:2px solid #e2e8f0; padding-bottom:12px;">
        <button type="button" onclick="switchTab('permohonan')" id="tab-btn-permohonan" class="tab-nav-btn"
            style="padding:10px 22px; font-weight:600; border-radius:10px; border:1.5px solid var(--color-primary, #4f46e5); background:var(--color-primary, #4f46e5); color:#fff; cursor:pointer; display:inline-flex; align-items:center; gap:8px; font-size:0.92rem; transition:all 0.2s;">
            <i class="fa-solid fa-file-signature"></i> 📄 Surat Permohonan PKL
        </button>
        <button type="button" onclick="switchTab('lainnya')" id="tab-btn-lainnya" class="tab-nav-btn"
            style="padding:10px 22px; font-weight:600; border-radius:10px; border:1.5px solid #cbd5e1; background:#f8fafc; color:#475569; cursor:pointer; display:inline-flex; align-items:center; gap:8px; font-size:0.92rem; transition:all 0.2s;">
            <i class="fa-solid fa-folder-open"></i> 📋 Surat Penempatan & Penarikan
        </button>
    </div>

    {{-- ============================================================================ --}}
    {{-- TAB 1: SURAT PERMOHONAN PKL --}}
    {{-- ============================================================================ --}}
    <div id="tab-content-permohonan" class="tab-content-panel" style="display:grid; grid-template-columns:1fr 1.5fr; gap:20px; align-items:start;">
        
        {{-- Form Buat Surat Permohonan --}}
        <div class="card" style="margin-bottom:0;">
            <div class="card-header" style="background:linear-gradient(135deg, rgba(79,70,229,.05), rgba(13,148,136,.05));">
                <h2 class="card-title" style="color:var(--color-primary); font-size:1.1rem;">
                    <i class="fa-solid fa-file-circle-plus"></i> Buat Surat Permohonan PKL
                </h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('pkl.persuratan.generate') }}">
                    @csrf
                    <input type="hidden" name="jenis_surat" value="permohonan">

                    <div class="form-group">
                        <label class="form-label">Gelombang PKL <span class="required">*</span></label>
                        <select name="id_gelombang" id="js_gelombang_permohonan" class="form-control" required onchange="loadDudiPermohonan()">
                            <option value="">-- Pilih Gelombang --</option>
                            @foreach($gelombangList as $g)
                            <option value="{{ $g->id_gelombang }}" {{ optional($gelombangAktif)->id_gelombang == $g->id_gelombang ? 'selected' : '' }}>
                                {{ $g->nama_gelombang }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tujuan DUDI <span class="required">*</span></label>
                        <select name="id_dudi" id="js_dudi_permohonan" class="form-control" required onchange="loadSiswaPermohonan()">
                            <option value="">-- Pilih Gelombang dulu --</option>
                            @foreach($dudis as $d)
                            <option value="{{ $d->id_dudi }}">{{ $d->nama_dudi }}{{ $d->kecamatan ? ' (Kec. ' . $d->kecamatan . ')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Input Daftar Nama Siswa (Khusus Permohonan) --}}
                    <div class="form-group mb-4">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px;">
                            <label class="form-label mb-0" style="font-weight:600;">Daftar Nama Siswa <span class="required">*</span></label>
                            <span class="badge badge-info" id="siswa_count_badge" style="font-size:0.75rem;">0 Siswa Terpilih</span>
                        </div>
                        <small class="text-muted" style="display:block; margin-bottom:8px; font-size:0.78rem;">
                            Pilih siswa terdaftar di DUDI / Gelombang, atau tambah siswa manual (bisa lebih dari 1 siswa).
                        </small>

                        {{-- List Checkbox Siswa Terdaftar --}}
                        <div id="container-siswa-dudi" style="background:var(--bg-card,#f9fafb); border:1px solid var(--border-color,#e5e7eb); border-radius:8px; padding:10px; max-height:200px; overflow-y:auto; margin-bottom:10px;">
                            <div class="text-muted text-center py-2" style="font-size:0.82rem;" id="siswa-empty-info">
                                <em>Pilih DUDI & Gelombang untuk menampilkan siswa.</em>
                            </div>
                            <div id="siswa-checkbox-list" style="display:flex; flex-direction:column; gap:6px;"></div>
                        </div>

                        {{-- AJAX Live Search Cari & Tambah Siswa --}}
                        <div style="position:relative; margin-bottom:10px;">
                            <div style="position:relative;">
                                <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:10px; top:50%; transform:translateY(-50%); color:var(--text-muted,#94a3b8); pointer-events:none; font-size:0.85rem;"></i>
                                <input type="text" id="ajax_search_siswa" class="form-control form-control-sm"
                                    placeholder="🔍 Cari nama / NIS siswa (AJAX live search)..."
                                    autocomplete="off"
                                    style="padding-left:32px; padding-right:28px;"
                                    oninput="handleSiswaLiveSearch(this.value)"
                                    onfocus="handleSiswaLiveSearch(this.value)">
                                <span id="btn_clear_search_siswa" onclick="clearAjaxSiswaSearch()"
                                    style="position:absolute; right:10px; top:50%; transform:translateY(-50%); cursor:pointer; color:var(--text-muted,#94a3b8); display:none; font-size:0.85rem;">
                                    <i class="fa-solid fa-xmark"></i>
                                </span>
                            </div>

                            <div id="siswa-live-dropdown" style="
                                display:none; position:absolute; top:100%; left:0; right:0;
                                background:#fff; border:1.5px solid #cbd5e1;
                                border-radius:0 0 8px 8px;
                                box-shadow:0 8px 24px rgba(0,0,0,.15); z-index:1000;
                                max-height:220px; overflow-y:auto; margin-top:2px;">
                            </div>
                        </div>

                        {{-- Input Manual Siswa Custom --}}
                        <div style="background:rgba(99,102,241,0.04); border:1px dashed #cbd5e1; border-radius:8px; padding:10px;">
                            <div style="font-size:0.78rem; font-weight:600; color:var(--color-primary,#4f46e5); margin-bottom:6px;">
                                <i class="fa-solid fa-pen-to-square"></i> Tambah Siswa Custom / Manual
                            </div>
                            <div style="display:grid; grid-template-columns:1.5fr 1fr 1fr auto; gap:6px; align-items:center;">
                                <input type="text" id="manual_nama" class="form-control form-control-sm" placeholder="Nama Siswa">
                                <input type="text" id="manual_nis" class="form-control form-control-sm" placeholder="NIS">
                                <input type="text" id="manual_kelas" class="form-control form-control-sm" placeholder="Kelas (ex: XII TKR 3)">
                                <button type="button" class="btn btn-primary btn-sm" onclick="addManualSiswa()">
                                    <i class="fa-solid fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Surat <span class="required">*</span></label>
                        <input type="date" name="tanggal_surat" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Opsi Tanda Tangan <span class="required">*</span></label>
                        <div style="display:flex; gap:16px; align-items:center; margin-top:4px;">
                            <label style="display:inline-flex; align-items:center; gap:6px; font-weight:normal; cursor:pointer; font-size:0.88rem;">
                                <input type="radio" name="ttd" value="1" checked> ✍️ Dengan Tanda Tangan
                            </label>
                            <label style="display:inline-flex; align-items:center; gap:6px; font-weight:normal; cursor:pointer; font-size:0.88rem;">
                                <input type="radio" name="ttd" value="0"> 📄 Tanpa Tanda Tangan
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-full" style="padding:10px 0; font-weight:600;">
                        <i class="fa-solid fa-file-signature"></i> Generate Surat Permohonan & Cetak
                    </button>
                </form>
            </div>
        </div>

        {{-- Arsip Surat Permohonan PKL --}}
        <div>
            <div class="card mb-4" style="margin-bottom:16px;">
                <div class="card-body" style="padding:12px 18px;">
                    <form method="GET" action="{{ route('pkl.persuratan.index') }}" class="flex-row-wrap gap-3 align-items-end">
                        <input type="hidden" name="tab" value="permohonan">
                        <div class="form-group mb-0">
                            <label class="form-label-sm">Filter Gelombang</label>
                            <select name="id_gelombang" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="">-- Semua Gelombang --</option>
                                @foreach($gelombangList as $g)
                                <option value="{{ $g->id_gelombang }}" {{ request('id_gelombang') == $g->id_gelombang ? 'selected' : '' }}>
                                    {{ $g->nama_gelombang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{ route('pkl.persuratan.index', ['tab' => 'permohonan']) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate"></i> Reset</a>
                    </form>
                </div>
            </div>

            <div class="card" style="margin-bottom:0;">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fa-solid fa-archive" style="color:var(--color-primary);"></i> Arsip Surat Permohonan ({{ $dataPermohonan->total() }})
                    </h2>
                </div>
                <div class="card-body p-0">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nomor Surat</th>
                                <th>DUDI Tujuan</th>
                                <th>Tgl Surat</th>
                                <th style="width:90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataPermohonan as $s)
                            <tr>
                                <td style="font-family:monospace;font-size:.82rem;font-weight:600;">{{ $s->nomor_surat }}</td>
                                <td style="font-size:.85rem;">{{ optional($s->dudi)->nama_dudi ?? '-' }}</td>
                                <td style="font-size:.85rem;">{{ \Carbon\Carbon::parse($s->tanggal_surat)->format('d/m/Y') }}</td>
                                <td class="action-cell">
                                    <a href="{{ route('pkl.persuratan.cetak', $s->id_surat) }}" target="_blank"
                                        class="btn-icon btn-info" title="Cetak Surat">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <button type="button" class="btn-icon btn-delete" title="Hapus"
                                        onclick="confirmDelete('{{ route('pkl.persuratan.destroy', $s->id_surat) }}','Hapus arsip surat {{ $s->nomor_surat }}?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-6">
                                    <i class="fa-solid fa-file-circle-xmark" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                                    Belum ada arsip Surat Permohonan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($dataPermohonan->hasPages())
                <div class="card-footer">{{ $dataPermohonan->links() }}</div>
                @endif
            </div>
        </div>

    </div>

    {{-- ============================================================================ --}}
    {{-- TAB 2: SURAT PENEMPATAN & PENARIKAN --}}
    {{-- ============================================================================ --}}
    <div id="tab-content-lainnya" class="tab-content-panel" style="display:none; grid-template-columns:1fr 1.5fr; gap:20px; align-items:start;">
        
        <div style="display:flex; flex-direction:column; gap:20px;">
            {{-- Panel Buat Surat Penempatan/Penarikan --}}
            <div class="card" style="margin-bottom:0;">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-file-signature" style="color:var(--color-primary);"></i> Buat Surat Baru</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pkl.persuratan.generate') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Jenis Surat <span class="required">*</span></label>
                            <select name="jenis_surat" class="form-control" required>
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="penempatan">📋 Surat Pengantar Penempatan</option>
                                <option value="penarikan">📤 Surat Penarikan Siswa</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Gelombang PKL <span class="required">*</span></label>
                            <select name="id_gelombang" id="js_gelombang_lainnya" class="form-control" required onchange="loadDudiLainnya()">
                                <option value="">-- Pilih Gelombang --</option>
                                @foreach($gelombangList as $g)
                                <option value="{{ $g->id_gelombang }}" {{ optional($gelombangAktif)->id_gelombang == $g->id_gelombang ? 'selected' : '' }}>
                                    {{ $g->nama_gelombang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tujuan DUDI <span class="required">*</span></label>
                            <select name="id_dudi" id="js_dudi_lainnya" class="form-control" required>
                                <option value="">-- Pilih Gelombang dulu --</option>
                                @foreach($dudis as $d)
                                <option value="{{ $d->id_dudi }}">{{ $d->nama_dudi }}{{ $d->kecamatan ? ' (Kec. ' . $d->kecamatan . ')' : '' }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Surat <span class="required">*</span></label>
                            <input type="date" name="tanggal_surat" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Opsi Tanda Tangan <span class="required">*</span></label>
                            <div style="display:flex; gap:16px; align-items:center; margin-top:4px;">
                                <label style="display:inline-flex; align-items:center; gap:6px; font-weight:normal; cursor:pointer; font-size:0.88rem;">
                                    <input type="radio" name="ttd" value="1" checked> ✍️ Dengan Tanda Tangan
                                </label>
                                <label style="display:inline-flex; align-items:center; gap:6px; font-weight:normal; cursor:pointer; font-size:0.88rem;">
                                    <input type="radio" name="ttd" value="0"> 📄 Tanpa Tanda Tangan
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">
                            <i class="fa-solid fa-file-signature"></i> Generate Surat & Cetak
                        </button>
                    </form>
                </div>
            </div>

            {{-- Panel Generate Masal (Khusus Penempatan & Penarikan) --}}
            <div class="card" style="margin-bottom:0;">
                <div class="card-header" style="background:linear-gradient(135deg, rgba(13,148,136,.06), rgba(99,102,241,.04));">
                    <h2 class="card-title" style="color:var(--color-primary);"><i class="fa-solid fa-copy"></i> Cetak & Generate Masal</h2>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('pkl.persuratan.generate-masal') }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label">Jenis Surat <span class="required">*</span></label>
                            <select name="jenis_surat" class="form-control" required>
                                <option value="">-- Pilih Jenis Surat --</option>
                                <option value="penempatan">📋 Surat Pengantar Penempatan</option>
                                <option value="penarikan">📤 Surat Penarikan Siswa</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Gelombang PKL <span class="required">*</span></label>
                            <select name="id_gelombang" class="form-control" required>
                                <option value="">-- Pilih Gelombang --</option>
                                @foreach($gelombangList as $g)
                                <option value="{{ $g->id_gelombang }}" {{ optional($gelombangAktif)->id_gelombang == $g->id_gelombang ? 'selected' : '' }}>
                                    {{ $g->nama_gelombang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Surat <span class="required">*</span></label>
                            <input type="date" name="tanggal_surat" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Opsi Tanda Tangan <span class="required">*</span></label>
                            <div style="display:flex; gap:16px; align-items:center; margin-top:4px;">
                                <label style="display:inline-flex; align-items:center; gap:6px; font-weight:normal; cursor:pointer; font-size:0.88rem;">
                                    <input type="radio" name="ttd" value="1" checked> ✍️ Dengan Tanda Tangan
                                </label>
                                <label style="display:inline-flex; align-items:center; gap:6px; font-weight:normal; cursor:pointer; font-size:0.88rem;">
                                    <input type="radio" name="ttd" value="0"> 📄 Tanpa Tanda Tangan
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary w-full" style="background:#4f46e5; color:#fff; border:none;">
                            <i class="fa-solid fa-print"></i> Generate & Cetak Masal
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Arsip Surat Penempatan & Penarikan --}}
        <div>
            <div class="card mb-4" style="margin-bottom:16px;">
                <div class="card-body" style="padding:12px 18px;">
                    <form method="GET" action="{{ route('pkl.persuratan.index') }}" class="flex-row-wrap gap-3 align-items-end">
                        <input type="hidden" name="tab" value="lainnya">
                        <div class="form-group mb-0">
                            <label class="form-label-sm">Jenis Surat</label>
                            <select name="jenis_surat_lainnya" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="">-- Semua Jenis --</option>
                                <option value="penempatan" {{ request('jenis_surat_lainnya') === 'penempatan' ? 'selected' : '' }}>Penempatan</option>
                                <option value="penarikan" {{ request('jenis_surat_lainnya') === 'penarikan' ? 'selected' : '' }}>Penarikan</option>
                            </select>
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label-sm">Gelombang</label>
                            <select name="id_gelombang" class="form-control form-control-sm" onchange="this.form.submit()">
                                <option value="">-- Semua Gelombang --</option>
                                @foreach($gelombangList as $g)
                                <option value="{{ $g->id_gelombang }}" {{ request('id_gelombang') == $g->id_gelombang ? 'selected' : '' }}>{{ $g->nama_gelombang }}</option>
                                @endforeach
                            </select>
                        </div>
                        <a href="{{ route('pkl.persuratan.index', ['tab' => 'lainnya']) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate"></i> Reset</a>
                    </form>
                </div>
            </div>

            <div class="card" style="margin-bottom:0;">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-archive" style="color:var(--color-primary);"></i> Arsip Penempatan & Penarikan ({{ $dataLainnya->total() }})</h2>
                </div>
                <div class="card-body p-0">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Nomor Surat</th>
                                <th>Jenis</th>
                                <th>DUDI Tujuan</th>
                                <th>Tgl Surat</th>
                                <th style="width:90px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dataLainnya as $s)
                            <tr>
                                <td style="font-family:monospace;font-size:.82rem;font-weight:600;">{{ $s->nomor_surat }}</td>
                                <td>
                                    @if($s->jenis_surat === 'penempatan') <span class="badge badge-success">Penempatan</span>
                                    @else <span class="badge badge-warning">Penarikan</span>
                                    @endif
                                </td>
                                <td style="font-size:.85rem;">{{ optional($s->dudi)->nama_dudi ?? '-' }}</td>
                                <td style="font-size:.85rem;">{{ \Carbon\Carbon::parse($s->tanggal_surat)->format('d/m/Y') }}</td>
                                <td class="action-cell">
                                    <a href="{{ route('pkl.persuratan.cetak', $s->id_surat) }}" target="_blank"
                                        class="btn-icon btn-info" title="Cetak Surat">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                    <button type="button" class="btn-icon btn-delete" title="Hapus"
                                        onclick="confirmDelete('{{ route('pkl.persuratan.destroy', $s->id_surat) }}','Hapus arsip surat {{ $s->nomor_surat }}?')">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-6">
                                    <i class="fa-solid fa-file-circle-xmark" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                                    Belum ada arsip surat Penempatan / Penarikan
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($dataLainnya->hasPages())
                <div class="card-footer">{{ $dataLainnya->links() }}</div>
                @endif
            </div>
        </div>

    </div>
</div>

@push('scripts')
<script>
let currentAllSiswa = [];

function switchTab(tabName) {
    document.querySelectorAll('.tab-content-panel').forEach(el => el.style.display = 'none');
    document.querySelectorAll('.tab-nav-btn').forEach(el => {
        el.style.background = '#f8fafc';
        el.style.color = '#475569';
        el.style.borderColor = '#cbd5e1';
    });

    const activeContent = document.getElementById('tab-content-' + tabName);
    const activeBtn = document.getElementById('tab-btn-' + tabName);

    if (activeContent) activeContent.style.display = 'grid';
    if (activeBtn) {
        activeBtn.style.background = 'var(--color-primary, #4f46e5)';
        activeBtn.style.color = '#ffffff';
        activeBtn.style.borderColor = 'var(--color-primary, #4f46e5)';
    }

    const url = new URL(window.location);
    url.searchParams.set('tab', tabName);
    window.history.replaceState({}, '', url);
}

function loadDudiPermohonan() {
    const idGelombang = document.getElementById('js_gelombang_permohonan').value;
    const select = document.getElementById('js_dudi_permohonan');
    if (!idGelombang) return;

    fetch(`/pkl/dudi/by-gelombang?id_gelombang=${idGelombang}`)
        .then(r => r.json())
        .then(list => {
            const currentVal = select.value;
            select.innerHTML = '<option value="">-- Pilih DUDI --</option>';
            list.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id_dudi;
                opt.textContent = `${d.nama_dudi} (${d.terpakai} siswa)`;
                if (d.id_dudi == currentVal) opt.selected = true;
                select.appendChild(opt);
            });

            loadSiswaPermohonan();
        });
}

function loadDudiLainnya() {
    const idGelombang = document.getElementById('js_gelombang_lainnya').value;
    const select = document.getElementById('js_dudi_lainnya');
    if (!idGelombang) return;

    fetch(`/pkl/dudi/by-gelombang?id_gelombang=${idGelombang}`)
        .then(r => r.json())
        .then(list => {
            const currentVal = select.value;
            select.innerHTML = '<option value="">-- Pilih DUDI --</option>';
            list.forEach(d => {
                const opt = document.createElement('option');
                opt.value = d.id_dudi;
                opt.textContent = `${d.nama_dudi} (${d.terpakai} siswa)`;
                if (d.id_dudi == currentVal) opt.selected = true;
                select.appendChild(opt);
            });
        });
}

function loadSiswaPermohonan() {
    const idGelombang = document.getElementById('js_gelombang_permohonan').value;
    const idDudi = document.getElementById('js_dudi_permohonan').value;
    const container = document.getElementById('siswa-checkbox-list');
    const emptyInfo = document.getElementById('siswa-empty-info');

    if (!idGelombang) {
        emptyInfo.style.display = 'block';
        emptyInfo.innerHTML = '<em>Pilih Gelombang terlebih dahulu.</em>';
        container.innerHTML = '';
        currentAllSiswa = [];
        updateSiswaCount();
        return;
    }

    fetch(`/pkl/persuratan/siswa-by-dudi?id_gelombang=${idGelombang}&id_dudi=${idDudi || ''}`)
        .then(r => r.json())
        .then(res => {
            currentAllSiswa = res.all_siswa || [];
            container.innerHTML = '';

            if (res.dudi_siswa && res.dudi_siswa.length > 0) {
                emptyInfo.style.display = 'none';
                res.dudi_siswa.forEach(s => {
                    addSiswaItemToContainer(s, true);
                });
            } else {
                emptyInfo.style.display = 'block';
                emptyInfo.innerHTML = '<em>Tidak ada siswa penempatan otomatis di DUDI ini. Silakan cari atau tambah siswa di bawah.</em>';
            }

            updateSiswaCount();
        })
        .catch(err => console.error(err));
}

function handleSiswaLiveSearch(query) {
    const dropdown = document.getElementById('siswa-live-dropdown');
    const btnClear = document.getElementById('btn_clear_search_siswa');
    const q = (query || '').trim().toLowerCase();

    if (q.length > 0) {
        if (btnClear) btnClear.style.display = 'block';
    } else {
        if (btnClear) btnClear.style.display = 'none';
    }

    if (!currentAllSiswa || currentAllSiswa.length === 0) {
        dropdown.innerHTML = '<div style="padding:10px; text-align:center; color:var(--text-muted,#64748b); font-size:0.8rem;"><em>Pilih Gelombang terlebih dahulu untuk memuat data siswa.</em></div>';
        dropdown.style.display = 'block';
        return;
    }

    const matches = currentAllSiswa.filter(s => {
        const nama = (s.nama_siswa || '').toLowerCase();
        const nis = (s.nis || '').toString().toLowerCase();
        const kelas = (s.nama_kelas || '').toLowerCase();
        return q === '' || nama.includes(q) || nis.includes(q) || kelas.includes(q);
    });

    if (matches.length === 0) {
        dropdown.innerHTML = '<div style="padding:10px; text-align:center; color:var(--text-muted,#64748b); font-size:0.8rem;"><em>Siswa tidak ditemukan</em></div>';
        dropdown.style.display = 'block';
        return;
    }

    dropdown.innerHTML = '';
    matches.slice(0, 30).forEach(s => {
        const item = document.createElement('div');
        item.style.cssText = 'padding:8px 12px; border-bottom:1px solid #f1f5f9; cursor:pointer; font-size:0.83rem; display:flex; justify-content:space-between; align-items:center; transition:background 0.15s;';
        item.onmouseenter = () => item.style.background = '#f8fafc';
        item.onmouseleave = () => item.style.background = '#fff';

        item.innerHTML = `
            <div>
                <strong style="color:#1e293b;">${highlightMatch(s.nama_siswa, q)}</strong>
                <div style="font-size:0.75rem; color:#64748b;">${s.nama_kelas} | NIS: ${highlightMatch(String(s.nis), q)}</div>
            </div>
            <button type="button" class="btn btn-primary btn-xs" style="padding:2px 8px; font-size:0.75rem; border-radius:4px;">
                <i class="fa-solid fa-plus"></i> Tambah
            </button>
        `;

        item.onclick = () => {
            addSiswaItemToContainer(s, true);
            clearAjaxSiswaSearch();
        };

        dropdown.appendChild(item);
    });

    dropdown.style.display = 'block';
}

function highlightMatch(text, q) {
    if (!q) return text;
    const regex = new RegExp(`(${q.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
    return text.replace(regex, '<mark style="background:#fef08a; padding:0 2px; border-radius:2px;">$1</mark>');
}

function clearAjaxSiswaSearch() {
    const input = document.getElementById('ajax_search_siswa');
    const dropdown = document.getElementById('siswa-live-dropdown');
    const btnClear = document.getElementById('btn_clear_search_siswa');

    if (input) input.value = '';
    if (btnClear) btnClear.style.display = 'none';
    if (dropdown) {
        dropdown.innerHTML = '';
        dropdown.style.display = 'none';
    }
}

function addSiswaItemToContainer(siswa, checked = true) {
    const container = document.getElementById('siswa-checkbox-list');
    const emptyInfo = document.getElementById('siswa-empty-info');
    if (emptyInfo) emptyInfo.style.display = 'none';

    const existingInput = container.querySelector(`input[data-nis="${siswa.nis}"]`);
    if (existingInput) {
        existingInput.checked = true;
        updateSiswaCount();
        return;
    }

    const itemDiv = document.createElement('div');
    itemDiv.className = 'siswa-item-row';
    itemDiv.style.cssText = 'display:flex; align-items:center; justify-content:space-between; gap:8px; background:#fff; padding:6px 10px; border-radius:6px; border:1px solid #e2e8f0; font-size:0.83rem;';

    const jsonVal = JSON.stringify({
        nis: siswa.nis || '-',
        nama_siswa: siswa.nama_siswa || '',
        nama_kelas: siswa.nama_kelas || '',
        keahlian: siswa.keahlian || ''
    });

    const badgeHtml = siswa.is_dudi
        ? '<span class="badge badge-success" style="font-size:0.7rem;">DUDI</span>'
        : '<span class="badge badge-secondary" style="font-size:0.7rem;">Tambahan</span>';

    itemDiv.innerHTML = `
        <label style="display:flex; align-items:center; gap:8px; width:100%; cursor:pointer; margin:0;">
            <input type="checkbox" name="siswa_list[]" value='${jsonVal.replace(/'/g, "&apos;")}' data-nis="${siswa.nis}" ${checked ? 'checked' : ''} onchange="updateSiswaCount()" style="width:16px; height:16px; accent-color:var(--color-primary);">
            <div style="flex:1;">
                <strong>${siswa.nama_siswa}</strong>
                <span class="text-muted" style="font-size:0.76rem; display:block;">${siswa.nama_kelas} | NIS: ${siswa.nis}</span>
            </div>
            ${badgeHtml}
        </label>
        <button type="button" onclick="this.closest('.siswa-item-row').remove(); updateSiswaCount();" style="border:none; background:transparent; color:#ef4444; cursor:pointer; padding:2px 6px;" title="Hapus">
            <i class="fa-solid fa-xmark"></i>
        </button>
    `;

    container.appendChild(itemDiv);
    updateSiswaCount();
}

function addManualSiswa() {
    const namaInput = document.getElementById('manual_nama');
    const nisInput = document.getElementById('manual_nis');
    const kelasInput = document.getElementById('manual_kelas');

    const nama = namaInput.value.trim();
    if (!nama) {
        alert('Nama siswa harus diisi');
        return;
    }

    const siswa = {
        nis: nisInput.value.trim() || '-',
        nama_siswa: nama,
        nama_kelas: kelasInput.value.trim() || '-',
        keahlian: '-',
        is_dudi: false
    };

    addSiswaItemToContainer(siswa, true);

    namaInput.value = '';
    nisInput.value = '';
    kelasInput.value = '';
}

function updateSiswaCount() {
    const checkboxes = document.querySelectorAll('#siswa-checkbox-list input[type="checkbox"]:checked');
    const countBadge = document.getElementById('siswa_count_badge');
    if (countBadge) {
        countBadge.textContent = `${checkboxes.length} Siswa Terpilih`;
    }
}

// Close dropdown on click outside
document.addEventListener('click', (e) => {
    const searchBox = document.getElementById('ajax_search_siswa');
    const dropdown = document.getElementById('siswa-live-dropdown');
    if (dropdown && searchBox && !searchBox.contains(e.target) && !dropdown.contains(e.target)) {
        dropdown.style.display = 'none';
    }
});

// Auto-load on page ready
document.addEventListener('DOMContentLoaded', () => {
    const initialTab = '{{ $activeTab }}';
    switchTab(initialTab);

    if (document.getElementById('js_gelombang_permohonan')?.value) {
        loadDudiPermohonan();
    }
    if (document.getElementById('js_gelombang_lainnya')?.value) {
        loadDudiLainnya();
    }
});
</script>
@endpush
@endsection
