@extends('layouts.app')

@section('title', 'Buku Konsultasi BK — SmartSchool')
@section('header_title', 'Buku Konsultasi')
@section('header_subtitle', 'Catatan sesi bimbingan konseling dan konsultasi siswa')

@push('styles')
<style>
/* ── Autocomplete dropdown ── */
.autocomplete-wrapper {
    position: relative;
}
.autocomplete-results {
    position: absolute;
    top: calc(100% + 4px);
    left: 0; right: 0;
    background: var(--card-bg, #fff);
    border: 1.5px solid var(--border-color, #e2e8f0);
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,.12);
    z-index: 999;
    max-height: 220px;
    overflow-y: auto;
    display: none;
}
.autocomplete-results.open { display: block; }
.autocomplete-item {
    padding: 10px 14px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 2px;
    transition: background .15s;
    border-bottom: 1px solid var(--border-color, #f1f5f9);
}
.autocomplete-item:last-child { border-bottom: none; }
.autocomplete-item:hover, .autocomplete-item.active {
    background: var(--color-primary-light, #ede9fe);
}
.autocomplete-item .item-main { font-weight: 600; font-size: 0.9rem; text-align: left; }
.autocomplete-item .item-sub  { font-size: 0.78rem; color: var(--text-muted, #94a3b8); text-align: left; }
.autocomplete-item .item-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background: var(--color-primary-light, #ede9fe);
    color: var(--color-primary, #7c3aed);
    font-size: 0.72rem; font-weight: 700;
    padding: 2px 8px; border-radius: 20px;
}
.no-results-item {
    padding: 12px 14px;
    color: var(--text-muted, #94a3b8);
    font-size: 0.85rem;
    text-align: center;
}

/* ── Selected siswa chip ── */
.selected-siswa-chip {
    display: none;
    align-items: center;
    gap: 10px;
    background: var(--color-primary-light, #ede9fe);
    border: 1.5px solid var(--color-primary, #7c3aed);
    border-radius: 10px;
    padding: 10px 14px;
    margin-top: 6px;
}
.selected-siswa-chip.show { display: flex; }
.selected-siswa-chip .chip-info { flex: 1; text-align: left; }
.selected-siswa-chip .chip-name { font-weight: 700; font-size: 0.92rem; }
.selected-siswa-chip .chip-meta { font-size: 0.78rem; color: var(--text-muted); }
.selected-siswa-chip .chip-clear {
    background: none; border: none;
    color: var(--color-primary); cursor: pointer;
    font-size: 1rem; padding: 0; line-height: 1;
}
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Filter Card --}}
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('bk.buku-konsultasi.index') }}" class="flex-row-wrap gap-4 align-items-end">
                <div class="form-group mb-0" style="min-width: 220px;">
                    <label class="form-label-sm">Filter Kelas</label>
                    <select name="id_kelas" class="form-control form-control-sm">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-row gap-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filter</button>
                    <a href="{{ route('bk.buku-konsultasi.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-comments" style="color:var(--color-primary);"></i> Daftar Buku Konsultasi</h2>
            <div class="card-header-right">
                <button class="btn btn-primary btn-sm" onclick="openAddModal()" id="btn-tambah-konsultasi">
                    <i class="fa-solid fa-plus"></i> Sesi Konsultasi Baru
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th style="width:110px;">Tanggal</th>
                        <th>Siswa</th>
                        <th>Jenis Masalah</th>
                        <th>Uraian Konsultasi</th>
                        <th>Tindak Lanjut</th>
                        <th style="width:100px;text-align:center;">Status</th>
                        <th>Guru BK</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $data->firstItem() + $i }}</td>
                        <td style="font-size:0.85rem;">{{ \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $item->nis }}</div>
                            @if($item->siswa)
                                <div style="font-size:0.8rem;color:var(--text-muted);">{{ $item->siswa->nama_siswa }}</div>
                                @if($item->siswa->kelas)
                                    <div style="font-size:0.75rem;"><span class="badge" style="background:var(--color-primary-light);color:var(--color-primary);">{{ $item->siswa->kelas->nama_kelas }}</span></div>
                                @endif
                            @endif
                        </td>
                        <td style="font-weight:600;">{{ $item->jenis_masalah }}</td>
                        <td style="font-size:0.85rem; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->uraian }}">
                            {{ $item->uraian }}
                        </td>
                        <td style="font-size:0.85rem; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->tindak_lanjut }}">
                            {{ $item->tindak_lanjut ?? '-' }}
                        </td>
                        <td style="text-align:center;">
                            @if($item->status === 'selesai')
                                <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                            @else
                                <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Proses</span>
                            @endif
                        </td>
                        <td style="font-size:0.8rem;color:var(--text-muted);">{{ $item->guru->nama_guru ?? '-' }}</td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon btn-edit" title="Edit" onclick="editKonsultasi({{ json_encode($item) }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('bk.buku-konsultasi.destroy', $item->id_bimbingan) }}','Yakin hapus data konsultasi ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-6">
                            <i class="fa-solid fa-comments" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada catatan bimbingan/konsultasi
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

{{-- MODAL TAMBAH/EDIT --}}
<div class="modal-overlay" id="modal-konsultasi">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3 id="modal-title-konsultasi">Sesi Konsultasi Baru</h3>
            <button onclick="closeModal('modal-konsultasi')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-konsultasi" method="POST" action="{{ route('bk.buku-konsultasi.store') }}">
            @csrf
            <div id="method-field-konsultasi"></div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Tanggal Konsultasi <span class="required">*</span></label>
                    <input type="date" name="tanggal" id="kon_tgl" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>
                {{-- ── Cari Siswa Autocomplete ── --}}
                <div class="form-group">
                    <label class="form-label">Cari Siswa (Nama / NIS) <span class="required">*</span></label>
                    <div class="autocomplete-wrapper">
                        <input type="text" id="kon_siswa_search" class="form-control"
                               placeholder="Ketik nama atau NIS siswa..."
                               autocomplete="off">
                        <div class="autocomplete-results" id="kon_siswa_dropdown"></div>
                    </div>
                    {{-- Hidden NIS Input --}}
                    <input type="hidden" name="nis" id="kon_nis_hidden">

                    {{-- Selected Siswa Chip --}}
                    <div class="selected-siswa-chip" id="kon_siswa_chip">
                        <i class="fa-solid fa-user-check" style="color:var(--color-primary);font-size:1.1rem;flex-shrink:0;"></i>
                        <div class="chip-info">
                            <div class="chip-name" id="kon_chip_nama"></div>
                            <div class="chip-meta" id="kon_chip_meta"></div>
                        </div>
                        <button type="button" class="chip-clear" onclick="clearSiswa()" title="Ganti siswa">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Masalah <span class="required">*</span></label>
                    <input type="text" name="jenis_masalah" id="kon_jenis" class="form-control" placeholder="cth: Masalah Belajar, Kedisiplinan, Sosial, Karir" required maxlength="100">
                </div>
                <div class="form-group">
                    <label class="form-label">Uraian Masalah/Konsultasi <span class="required">*</span></label>
                    <textarea name="uraian" id="kon_uraian" class="form-control" placeholder="Jelaskan secara ringkas hasil konsultasi" rows="4" required></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Tindak Lanjut</label>
                    <textarea name="tindak_lanjut" id="kon_tindak" class="form-control" placeholder="Tindak lanjut penyelesaian masalah siswa" rows="3"></textarea>
                </div>
                <div class="form-group" id="status-group" style="display:none;">
                    <label class="form-label">Status Kasus <span class="required">*</span></label>
                    <select name="status" id="kon_status" class="form-control">
                        <option value="proses">Proses</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-konsultasi')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" id="kon_submit_btn" disabled><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
/* ── Global State ── */
let selectedSiswa = null;
let siswaSearchTimer = null;

function updateSubmitState() {
    const btn = document.getElementById('kon_submit_btn');
    btn.disabled = !selectedSiswa;
}

function openAddModal() {
    document.getElementById('form-konsultasi').action = '{{ route("bk.buku-konsultasi.store") }}';
    document.getElementById('method-field-konsultasi').innerHTML = '';
    document.getElementById('modal-title-konsultasi').textContent = 'Sesi Konsultasi Baru';
    document.getElementById('kon_tgl').value = '{{ date("Y-m-d") }}';
    resetSiswaSearch();
    document.getElementById('kon_jenis').value = '';
    document.getElementById('kon_uraian').value = '';
    document.getElementById('kon_tindak').value = '';
    document.getElementById('status-group').style.display = 'none';
    openModal('modal-konsultasi');
}

function editKonsultasi(data) {
    document.getElementById('form-konsultasi').action = `/bk/buku-konsultasi/${data.id_bimbingan}`;
    document.getElementById('method-field-konsultasi').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-konsultasi').textContent = 'Edit Buku Konsultasi';
    
    const dateVal = data.tanggal ? data.tanggal.substring(0, 10) : '';
    document.getElementById('kon_tgl').value = dateVal;
    
    // Pre-fill student autocomplete
    const namaKelas = data.siswa && data.siswa.kelas 
        ? (data.siswa.kelas.nama_kelas || `${data.siswa.kelas.tingkat} ${data.siswa.kelas.rombel}`) 
        : '-';
    const namaSiswa = data.siswa ? data.siswa.nama_siswa : data.nis;
    selectSiswa({
        nis: data.nis,
        nama_siswa: namaSiswa,
        nama_kelas: namaKelas,
        tingkat: data.siswa && data.siswa.kelas ? data.siswa.kelas.tingkat : '',
    });
    
    document.getElementById('kon_jenis').value = data.jenis_masalah;
    document.getElementById('kon_uraian').value = data.uraian;
    document.getElementById('kon_tindak').value = data.tindak_lanjut || '';
    document.getElementById('status-group').style.display = 'block';
    document.getElementById('kon_status').value = data.status;
    openModal('modal-konsultasi');
}

/* ── Siswa Autocomplete ── */
document.getElementById('kon_siswa_search').addEventListener('input', function() {
    const q = this.value.trim();
    clearTimeout(siswaSearchTimer);
    if (q.length < 2) {
        closeSiswaDropdown();
        return;
    }
    siswaSearchTimer = setTimeout(() => fetchSiswa(q), 280);
});

document.getElementById('kon_siswa_search').addEventListener('keydown', function(e) {
    navigateDropdown(e, 'kon_siswa_dropdown', chooseSiswaItem);
});

function fetchSiswa(q) {
    fetch(`/bk/buku-kasus/search-siswa?q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(renderSiswaDropdown)
        .catch(() => {});
}

function renderSiswaDropdown(list) {
    const dd = document.getElementById('kon_siswa_dropdown');
    if (!list.length) {
        dd.innerHTML = '<div class="no-results-item"><i class="fa-solid fa-circle-xmark"></i> Siswa tidak ditemukan</div>';
    } else {
        dd.innerHTML = list.map((s, i) => `
            <div class="autocomplete-item" data-index="${i}" onclick="chooseSiswaItem(${i})" data-nis="${s.nis}"
                 data-nama="${s.nama_siswa.replace(/"/g,'&quot;')}" data-kelas="${s.nama_kelas}" data-tingkat="${s.tingkat}">
                <span class="item-main">${s.nama_siswa}</span>
                <span class="item-sub">
                    NIS: ${s.nis} &nbsp;·&nbsp;
                    <span class="item-badge"><i class="fa-solid fa-door-open" style="font-size:0.65rem;"></i> ${s.nama_kelas}</span>
                </span>
            </div>`).join('');
    }
    dd.classList.add('open');
    dd._data = list;
}

function chooseSiswaItem(index) {
    const dd = document.getElementById('kon_siswa_dropdown');
    const list = dd._data || [];
    if (!list[index]) return;
    selectSiswa(list[index]);
}

function selectSiswa(s) {
    selectedSiswa = s;
    document.getElementById('kon_nis_hidden').value = s.nis;
    document.getElementById('kon_chip_nama').textContent = s.nama_siswa;
    document.getElementById('kon_chip_meta').textContent = `NIS: ${s.nis}  ·  Kelas: ${s.nama_kelas}`;
    document.getElementById('kon_siswa_chip').classList.add('show');
    document.getElementById('kon_siswa_search').style.display = 'none';
    closeSiswaDropdown();
    updateSubmitState();
}

function clearSiswa() {
    selectedSiswa = null;
    document.getElementById('kon_nis_hidden').value = '';
    document.getElementById('kon_siswa_chip').classList.remove('show');
    const inp = document.getElementById('kon_siswa_search');
    inp.style.display = '';
    inp.value = '';
    inp.focus();
    updateSubmitState();
}

function resetSiswaSearch() {
    selectedSiswa = null;
    document.getElementById('kon_nis_hidden').value = '';
    document.getElementById('kon_siswa_chip').classList.remove('show');
    document.getElementById('kon_siswa_search').style.display = '';
    document.getElementById('kon_siswa_search').value = '';
    closeSiswaDropdown();
    updateSubmitState();
}

function closeSiswaDropdown() {
    document.getElementById('kon_siswa_dropdown').classList.remove('open');
}

function navigateDropdown(e, dropdownId, selectFn) {
    const dd = document.getElementById(dropdownId);
    const items = dd.querySelectorAll('.autocomplete-item');
    let current = dd.querySelector('.autocomplete-item.active');
    let idx = -1;
    if (current) {
        idx = parseInt(current.dataset.index);
        current.classList.remove('active');
    }
    if (e.key === 'ArrowDown') {
        e.preventDefault();
        idx = Math.min(idx + 1, items.length - 1);
        if (items[idx]) { items[idx].classList.add('active'); items[idx].scrollIntoView({block:'nearest'}); }
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        idx = Math.max(idx - 1, 0);
        if (items[idx]) { items[idx].classList.add('active'); items[idx].scrollIntoView({block:'nearest'}); }
    } else if (e.key === 'Enter') {
        e.preventDefault();
        if (idx >= 0) selectFn(idx);
    } else if (e.key === 'Escape') {
        dd.classList.remove('open');
    }
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.autocomplete-wrapper')) {
        closeSiswaDropdown();
    }
});
</script>
@endpush
@endsection
