@extends('layouts.app')

@section('title', 'Catat Pelanggaran Kelas — SmartSchool')
@section('header_title', 'Catat Pelanggaran Kelas')
@section('header_subtitle', 'Pencatatan pelanggaran siswa di dalam kelas oleh guru')

@push('styles')
<style>
/* ── Stat cards ── */
.pk-stats-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 20px;
}
.pk-stat-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    border-radius: var(--radius-card);
    color: #fff;
    box-shadow: 0 6px 24px rgba(0,0,0,.12);
    transition: var(--transition-smooth);
}
.pk-stat-card:hover { transform: translateY(-3px); box-shadow: 0 12px 36px rgba(0,0,0,.18); }
.pk-stat-icon {
    width: 48px; height: 48px;
    background: rgba(255,255,255,.2);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0;
}
.pk-stat-num { font-size: 2rem; font-weight: 800; line-height: 1; }
.pk-stat-lbl { font-size: .78rem; opacity: .85; margin-top: 2px; }

/* ── Violation badges ── */
.badge-violation {
    display: inline-flex; align-items: center; gap: 4px;
    background: rgba(239,68,68,.08);
    color: #b91c1c;
    border: 1px solid rgba(239,68,68,.2);
    border-radius: 8px;
    padding: 3px 10px;
    font-size: .78rem; font-weight: 600;
}

/* ── Filter bar ── */
.filter-bar {
    display: flex; flex-wrap: wrap; gap: 8px; align-items: center;
    padding: 14px 16px;
    background: var(--bg-card);
    border-bottom: 1px solid var(--border-color);
}
.filter-bar .form-control-sm { height: 34px; font-size: .85rem; }

/* ── Pelanggaran list in modal ── */
.jenis-list { display: flex; flex-direction: column; gap: 6px; }
.jenis-item {
    display: flex; align-items: flex-start; gap: 10px;
    padding: 10px 12px;
    border: 1.5px solid var(--border-color);
    border-radius: 10px;
    cursor: pointer;
    transition: all .18s;
}
.jenis-item:hover, .jenis-item.selected {
    border-color: #ef4444;
    background: #fef2f2;
}
.jenis-item.selected { box-shadow: 0 0 0 3px rgba(239,68,68,.15); }
.jenis-num {
    width: 28px; height: 28px; border-radius: 50%;
    background: #fee2e2; color: #b91c1c;
    display: flex; align-items: center; justify-content: center;
    font-size: .8rem; font-weight: 800; flex-shrink: 0;
}
.jenis-item.selected .jenis-num { background: #ef4444; color: #fff; }
.jenis-label { font-size: .85rem; font-weight: 600; color: var(--text-primary); line-height: 1.3; }
.jenis-pembinaan { font-size: .75rem; color: var(--text-muted); margin-top: 2px; }

@media(max-width:640px) { .pk-stats-row { grid-template-columns: 1fr; } }
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- ── Stat Cards ── --}}
    <div class="pk-stats-row">
        <div class="pk-stat-card" style="background:linear-gradient(135deg,#b91c1c,#ef4444);">
            <div class="pk-stat-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
            <div>
                <div class="pk-stat-num">{{ $totalHariIni }}</div>
                <div class="pk-stat-lbl">Pelanggaran Hari Ini</div>
            </div>
        </div>
        <div class="pk-stat-card" style="background:linear-gradient(135deg,#c2410c,#f97316);">
            <div class="pk-stat-icon"><i class="fa-solid fa-calendar-week"></i></div>
            <div>
                <div class="pk-stat-num">{{ $totalBulanIni }}</div>
                <div class="pk-stat-lbl">Pelanggaran Bulan Ini</div>
            </div>
        </div>
        <div class="pk-stat-card" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);">
            <div class="pk-stat-icon"><i class="fa-solid fa-clipboard-list"></i></div>
            <div>
                <div class="pk-stat-num">{{ $totalAll }}</div>
                <div class="pk-stat-lbl">Total Semua Catatan</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;"></i>
                Daftar Pelanggaran Kelas
            </h2>
            <div class="card-header-right">
                <a href="{{ route('guru-kelas.pelanggaran.rekap') }}" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-chart-bar"></i> Rekap Siswa
                </a>
                <button class="btn btn-danger btn-sm" onclick="openAddModal()">
                    <i class="fa-solid fa-plus"></i> Catat Pelanggaran
                </button>
            </div>
        </div>

        {{-- ── Filter Bar ── --}}
        <form method="GET" class="filter-bar" id="filter-form">
            <select name="id_kelas" class="form-control form-control-sm" style="min-width:150px;"
                    onchange="this.form.submit()">
                <option value="">-- Semua Kelas --</option>
                @foreach($kelasList as $kls)
                    <option value="{{ $kls->id_kelas }}"
                        {{ request('id_kelas') == $kls->id_kelas ? 'selected' : '' }}>
                        {{ $kls->nama_kelas }}
                    </option>
                @endforeach
            </select>

            <select name="jenis" class="form-control form-control-sm" style="min-width:200px;"
                    onchange="this.form.submit()">
                <option value="">-- Semua Jenis --</option>
                @foreach($daftarJenis as $no => $j)
                    <option value="{{ $no }}" {{ request('jenis') == $no ? 'selected' : '' }}>
                        {{ $no }}. {{ Str::limit($j['label'], 40) }}
                    </option>
                @endforeach
            </select>

            <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                   class="form-control form-control-sm" style="width:135px;" title="Dari tanggal">
            <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                   class="form-control form-control-sm" style="width:135px;" title="Sampai tanggal">

            <button class="btn btn-secondary btn-sm" type="submit">
                <i class="fa-solid fa-filter"></i>
            </button>
            @if(request()->hasAny(['id_kelas','jenis','tanggal_dari','tanggal_sampai']))
                <a href="{{ route('guru-kelas.pelanggaran.index') }}" class="btn btn-secondary btn-sm" title="Reset filter">
                    <i class="fa-solid fa-rotate-left"></i>
                </a>
            @endif
        </form>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th style="width:100px;">Tanggal</th>
                        <th>Siswa</th>
                        <th style="width:110px;">Kelas</th>
                        <th>Jenis Pelanggaran</th>
                        <th>Pembinaan</th>
                        <th style="width:100px;">Dicatat oleh</th>
                        <th style="width:90px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $item)
                    @php
                        $jenisInfo = $daftarJenis[$item->jenis_pelanggaran] ?? ['label' => '-', 'pembinaan' => '-'];
                    @endphp
                    <tr>
                        <td style="color:var(--text-muted);font-size:.8rem;">{{ $data->firstItem() + $i }}</td>
                        <td style="font-size:.85rem;font-weight:600;">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}
                        </td>
                        <td>
                            <div style="font-weight:700;">
                                {{ $item->siswa?->nama_siswa ?? $item->nis }}
                            </div>
                            <div style="font-size:.75rem;color:var(--text-muted);">NIS: {{ $item->nis }}</div>
                        </td>
                        <td>
                            <span class="badge badge-muted" style="font-size:.75rem;">
                                {{ $item->siswa?->kelas?->nama_kelas ?? $item->kelas?->nama_kelas ?? '-' }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-violation">
                                <i class="fa-solid fa-circle" style="font-size:.45rem;"></i>
                                {{ $item->jenis_pelanggaran }}. {{ $jenisInfo['label'] }}
                            </span>
                            @if($item->keterangan)
                                <div style="font-size:.73rem;color:var(--text-muted);margin-top:3px;">
                                    <i class="fa-solid fa-note-sticky"></i> {{ $item->keterangan }}
                                </div>
                            @endif
                        </td>
                        <td style="font-size:.78rem;color:var(--text-muted);">
                            {{ $jenisInfo['pembinaan'] }}
                        </td>
                        <td style="font-size:.78rem;">
                            {{ $item->guru?->nama_guru ?? '-' }}
                        </td>
                        <td class="action-cell" style="text-align:center;">
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editPelanggaran({{ json_encode($item) }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('guru-kelas.pelanggaran.destroy', $item->id_pelanggaran_kelas) }}','Yakin hapus catatan pelanggaran ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-6">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada catatan pelanggaran
                            @if(request()->hasAny(['id_kelas','jenis','tanggal_dari','tanggal_sampai']))
                                — coba ubah filter
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($data->hasPages())
            <div class="card-footer">{{ $data->links() }}</div>
        @endif
    </div>
</div>

{{-- ════════════ MODAL TAMBAH / EDIT ════════════ --}}
<div class="modal-overlay" id="modal-pk">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-pk-title">
                <i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;"></i>
                Catat Pelanggaran Siswa
            </h3>
            <button onclick="closeModal('modal-pk')" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="form-pk" method="POST" action="{{ route('guru-kelas.pelanggaran.store') }}">
            @csrf
            <div id="method-pk"></div>
            <div class="modal-body">

                {{-- ── Baris 1: Tanggal & Kelas ── --}}
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Tanggal <span class="required">*</span></label>
                        <input type="date" name="tanggal" id="pk_tanggal" class="form-control"
                               required value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kelas <span class="required">*</span></label>
                        <select name="id_kelas" id="pk_kelas" class="form-control" required
                                onchange="loadSiswa(this.value)">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kls)
                                <option value="{{ $kls->id_kelas }}">{{ $kls->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- ── Siswa ── --}}
                <div class="form-group">
                    <label class="form-label">Siswa <span class="required">*</span></label>
                    <select name="nis" id="pk_siswa" class="form-control" required disabled>
                        <option value="">-- Pilih kelas terlebih dahulu --</option>
                    </select>
                </div>

                {{-- ── Jenis Pelanggaran ── --}}
                <div class="form-group">
                    <label class="form-label">Jenis Pelanggaran <span class="required">*</span></label>
                    <input type="hidden" name="jenis_pelanggaran" id="pk_jenis_hidden">
                    <div class="jenis-list" id="jenis-list">
                        @foreach($daftarJenis as $no => $j)
                        <div class="jenis-item" data-no="{{ $no }}" onclick="pilihJenis({{ $no }})">
                            <div class="jenis-num">{{ $no }}</div>
                            <div>
                                <div class="jenis-label">{{ $j['label'] }}</div>
                                <div class="jenis-pembinaan">
                                    <i class="fa-solid fa-arrow-right" style="font-size:.65rem;"></i>
                                    Pembinaan: {{ $j['pembinaan'] }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- ── Keterangan ── --}}
                <div class="form-group">
                    <label class="form-label">Keterangan Tambahan</label>
                    <textarea name="keterangan" id="pk_keterangan" class="form-control" rows="2"
                              placeholder="Catatan tambahan (opsional)..." maxlength="500"></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-pk')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-danger" id="pk_submit" disabled>
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
/* ── State ── */
let selectedJenis = null;

/* ── Open Modal: Add ── */
function openAddModal() {
    document.getElementById('form-pk').action = '{{ route("guru-kelas.pelanggaran.store") }}';
    document.getElementById('method-pk').innerHTML = '';
    document.getElementById('modal-pk-title').innerHTML =
        '<i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;"></i> Catat Pelanggaran Siswa';
    document.getElementById('pk_tanggal').value  = '{{ date("Y-m-d") }}';
    document.getElementById('pk_kelas').value    = '';
    resetSiswaDropdown();
    resetJenis();
    document.getElementById('pk_keterangan').value = '';
    openModal('modal-pk');
}

/* ── Open Modal: Edit ── */
function editPelanggaran(data) {
    document.getElementById('form-pk').action = `/guru-kelas/pelanggaran/${data.id_pelanggaran_kelas}`;
    document.getElementById('method-pk').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-pk-title').innerHTML =
        '<i class="fa-solid fa-pen" style="color:#ef4444;"></i> Edit Catatan Pelanggaran';
    document.getElementById('pk_tanggal').value = data.tanggal ? data.tanggal.substring(0,10) : '';

    // Set kelas & load siswa
    document.getElementById('pk_kelas').value = data.id_kelas;
    loadSiswa(data.id_kelas, data.nis);

    // Set jenis
    selectedJenis = data.jenis_pelanggaran;
    document.getElementById('pk_jenis_hidden').value = selectedJenis;
    document.querySelectorAll('.jenis-item').forEach(el => {
        if (parseInt(el.dataset.no) === selectedJenis) {
            el.classList.add('selected');
        } else {
            el.classList.remove('selected');
        }
    });

    document.getElementById('pk_keterangan').value = data.keterangan || '';
    updateSubmit();
    openModal('modal-pk');
}

/* ── Load siswa by kelas via AJAX ── */
function loadSiswa(idKelas, selectedNis = null) {
    const sel = document.getElementById('pk_siswa');
    if (!idKelas) {
        resetSiswaDropdown();
        return;
    }
    sel.disabled = false;
    sel.innerHTML = '<option value="">⏳ Memuat siswa...</option>';

    fetch(`/guru-kelas/pelanggaran/siswa-by-kelas?id_kelas=${idKelas}`)
        .then(r => r.json())
        .then(list => {
            sel.innerHTML = '<option value="">-- Pilih Siswa --</option>';
            list.forEach(s => {
                const opt = document.createElement('option');
                opt.value       = s.nis;
                opt.textContent = `${s.nama_siswa} (${s.nis})`;
                if (selectedNis && String(s.nis) === String(selectedNis)) {
                    opt.selected = true;
                }
                sel.appendChild(opt);
            });
            if (!list.length) {
                sel.innerHTML = '<option value="">Tidak ada siswa aktif di kelas ini</option>';
            }
            updateSubmit();
        })
        .catch(() => {
            sel.innerHTML = '<option value="">Gagal memuat siswa</option>';
        });
}

function resetSiswaDropdown() {
    const sel = document.getElementById('pk_siswa');
    sel.disabled = true;
    sel.innerHTML = '<option value="">-- Pilih kelas terlebih dahulu --</option>';
    updateSubmit();
}

/* ── Pilih jenis pelanggaran ── */
function pilihJenis(no) {
    selectedJenis = no;
    document.getElementById('pk_jenis_hidden').value = no;
    document.querySelectorAll('.jenis-item').forEach(el => {
        el.classList.toggle('selected', parseInt(el.dataset.no) === no);
    });
    updateSubmit();
}

function resetJenis() {
    selectedJenis = null;
    document.getElementById('pk_jenis_hidden').value = '';
    document.querySelectorAll('.jenis-item').forEach(el => el.classList.remove('selected'));
    updateSubmit();
}

/* ── Submit state ── */
function updateSubmit() {
    const nis   = document.getElementById('pk_siswa').value;
    const kelas = document.getElementById('pk_kelas').value;
    document.getElementById('pk_submit').disabled = !(nis && kelas && selectedJenis);
}

document.getElementById('pk_siswa').addEventListener('change', updateSubmit);
document.getElementById('pk_kelas').addEventListener('change', updateSubmit);
</script>
@endpush
@endsection
