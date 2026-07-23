@extends('layouts.app')

@section('title', 'Catat Pelanggaran — SmartSchool')
@section('header_title', 'Catat Pelanggaran')
@section('header_subtitle', 'Rekam riwayat pelanggaran dan pengurangan poin siswa')

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
.autocomplete-item .item-main { font-weight: 600; font-size: 0.9rem; }
.autocomplete-item .item-sub  { font-size: 0.78rem; color: var(--text-muted, #94a3b8); }
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
.selected-siswa-chip .chip-info { flex: 1; }
.selected-siswa-chip .chip-name { font-weight: 700; font-size: 0.92rem; }
.selected-siswa-chip .chip-meta { font-size: 0.78rem; color: var(--text-muted); }
.selected-siswa-chip .chip-clear {
    background: none; border: none;
    color: var(--color-primary); cursor: pointer;
    font-size: 1rem; padding: 0; line-height: 1;
}

/* ── Violation selected chip ── */
.selected-violation-chip {
    display: none;
    align-items: center;
    gap: 10px;
    background: #fef2f2;
    border: 1.5px solid #ef4444;
    border-radius: 10px;
    padding: 10px 14px;
    margin-top: 6px;
}
.selected-violation-chip.show { display: flex; }
.selected-violation-chip .chip-info { flex: 1; }
.selected-violation-chip .chip-name { font-weight: 700; font-size: 0.92rem; }
.selected-violation-chip .chip-badge {
    background: #ef4444; color: #fff;
    font-size: 0.82rem; font-weight: 700;
    padding: 3px 10px; border-radius: 20px;
}
.selected-violation-chip .chip-clear {
    background: none; border: none;
    color: #ef4444; cursor: pointer;
    font-size: 1rem; padding: 0; line-height: 1;
}
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-circle-minus" style="color:#ef4444;"></i> Riwayat Pelanggaran Siswa</h2>
            <div class="card-header-right">
                <button class="btn btn-danger btn-sm" onclick="openAddPelanggaran()" id="btn-catat-pelanggaran">
                    <i class="fa-solid fa-plus"></i> Catat Pelanggaran
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th style="width:110px;">Tanggal</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Jenis Pelanggaran</th>
                        <th style="width:110px;text-align:center;">Poin Dikurangi</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $data->firstItem() + $i }}</td>
                        <td style="font-size:0.85rem;">{{ \Carbon\Carbon::parse($item->tgl_input)->format('d/m/Y') }}</td>
                        <td>
                            <div style="font-weight:600;">
                                @if($item->siswa) {{ $item->siswa->nama_siswa }} @else {{ $item->nis }} @endif
                            </div>
                            <div style="font-size:0.78rem;color:var(--text-muted);">NIS: {{ $item->nis }}</div>
                        </td>
                        <td>
                            @if($item->siswa && $item->siswa->kelas)
                                <span class="badge" style="background:var(--color-primary-light);color:var(--color-primary);">
                                    {{ $item->siswa->kelas->nama_kelas }}
                                </span>
                            @else
                                <span style="font-size:0.8rem;color:var(--text-muted);">Tingkat {{ $item->tingkat }}</span>
                            @endif
                        </td>
                        <td style="font-weight:600;">{{ $item->pelanggaran }}</td>
                        <td style="text-align:center;">
                            <span class="badge badge-danger" style="font-size:0.9rem;padding:5px 12px;">
                                <i class="fa-solid fa-minus" style="font-size:0.7rem;"></i> {{ $item->poin }}
                            </span>
                        </td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon btn-edit" title="Edit" onclick="editPelanggaran({{ json_encode($item->load('siswa.kelas')) }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('bk.catat-pelanggaran.destroy', $item->id_poin) }}','Yakin hapus catatan pelanggaran ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-6">
                            <i class="fa-solid fa-circle-minus" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada catatan pelanggaran
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

{{-- ══════════════════════ MODAL CATAT / EDIT PELANGGARAN ══════════════════════ --}}
<div class="modal-overlay" id="modal-pelanggaran">
    <div class="modal" style="max-width: 540px; width: 100%;">
        <div class="modal-header">
            <h3 id="modal-title-pelanggaran">Catat Pelanggaran Siswa</h3>
            <button onclick="closeModal('modal-pelanggaran')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-pelanggaran" method="POST" action="{{ route('bk.catat-pelanggaran.store') }}">
            @csrf
            <div id="method-field-pelanggaran"></div>
            {{-- hidden fields --}}
            <input type="hidden" name="nis"     id="p_nis_hidden">
            <input type="hidden" name="tingkat" id="p_tingkat_hidden">
            <input type="hidden" name="poin"    id="p_poin_hidden">

            <div class="modal-body">

                {{-- ── Tanggal ── --}}
                <div class="form-group">
                    <label class="form-label">Tanggal <span class="required">*</span></label>
                    <input type="date" name="tgl_input" id="p_tgl" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>

                {{-- ── Cari Siswa ── --}}
                <div class="form-group">
                    <label class="form-label">Cari Siswa (Nama / NIS) <span class="required">*</span></label>
                    <div class="autocomplete-wrapper">
                        <input type="text" id="p_siswa_search" class="form-control"
                               placeholder="Ketik nama atau NIS siswa..."
                               autocomplete="off">
                        <div class="autocomplete-results" id="p_siswa_dropdown"></div>
                    </div>
                    {{-- Chip tampil setelah siswa dipilih --}}
                    <div class="selected-siswa-chip" id="p_siswa_chip">
                        <i class="fa-solid fa-user-check" style="color:var(--color-primary);font-size:1.1rem;flex-shrink:0;"></i>
                        <div class="chip-info">
                            <div class="chip-name" id="p_chip_nama"></div>
                            <div class="chip-meta" id="p_chip_meta"></div>
                        </div>
                        <button type="button" class="chip-clear" onclick="clearSiswa()" title="Ganti siswa">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                {{-- ── Cari Jenis Pelanggaran ── --}}
                <div class="form-group">
                    <label class="form-label">Jenis Pelanggaran <span class="required">*</span></label>
                    <div class="autocomplete-wrapper">
                        <input type="text" id="p_violation_search" class="form-control"
                               placeholder="Ketik nama pelanggaran..."
                               autocomplete="off">
                        <div class="autocomplete-results" id="p_violation_dropdown"></div>
                    </div>
                    {{-- hidden field untuk nama pelanggaran yang dikirim ke controller --}}
                    <input type="hidden" name="pelanggaran" id="p_pelanggaran_hidden">
                    {{-- Chip tampil setelah pelanggaran dipilih --}}
                    <div class="selected-violation-chip" id="p_violation_chip">
                        <i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;font-size:1.1rem;flex-shrink:0;"></i>
                        <div class="chip-info">
                            <div class="chip-name" id="p_chip_violation"></div>
                        </div>
                        <span class="chip-badge" id="p_chip_poin_label"></span>
                        <button type="button" class="chip-clear" onclick="clearViolation()" title="Ganti pelanggaran">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                {{-- ── Poin (readonly, auto-filled dari kategori) ── --}}
                <div class="form-group">
                    <label class="form-label">Poin Pengurangan <span class="required">*</span></label>
                    <div style="display:flex;gap:8px;align-items:center;">
                        <input type="number" id="p_poin_display" class="form-control"
                               placeholder="Otomatis terisi dari kategori"
                               min="1" style="background:var(--input-bg,#f8fafc);flex:1;"
                               readonly tabindex="-1">
                        <span style="font-size:0.8rem;color:var(--text-muted);white-space:nowrap;">poin</span>
                    </div>
                    <div style="font-size:0.76rem;color:var(--text-muted);margin-top:4px;">
                        <i class="fa-solid fa-circle-info"></i>
                        Poin terisi otomatis berdasarkan kategori pelanggaran yang dipilih.
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-pelanggaran')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-danger" id="p_submit_btn" disabled>
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
/* ════════════════════════════════════════════════════════
   GLOBAL STATE
════════════════════════════════════════════════════════ */
let selectedSiswa     = null;   // { nis, nama_siswa, nama_kelas, tingkat }
let selectedViolation = null;   // { id, nama, poin }
let siswaSearchTimer  = null;
let violationTimer    = null;

/* ════════════════════════════════════════════════════════
   HELPER: update Submit button state
════════════════════════════════════════════════════════ */
function updateSubmitState() {
    const btn = document.getElementById('p_submit_btn');
    btn.disabled = !(selectedSiswa && selectedViolation);
}

/* ════════════════════════════════════════════════════════
   OPEN / RESET MODAL
════════════════════════════════════════════════════════ */
function openAddPelanggaran() {
    document.getElementById('form-pelanggaran').action = '{{ route("bk.catat-pelanggaran.store") }}';
    document.getElementById('method-field-pelanggaran').innerHTML = '';
    document.getElementById('modal-title-pelanggaran').textContent = 'Catat Pelanggaran Siswa';
    document.getElementById('p_tgl').value = '{{ date("Y-m-d") }}';
    resetSiswaSearch();
    resetViolationSearch();
    openModal('modal-pelanggaran');
}

function editPelanggaran(data) {
    document.getElementById('form-pelanggaran').action = `/bk/catat-pelanggaran/${data.id_poin}`;
    document.getElementById('method-field-pelanggaran').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-pelanggaran').textContent = 'Edit Catatan Pelanggaran';
    document.getElementById('p_tgl').value = data.tgl_input ? data.tgl_input.substring(0,10) : '';

    // Pre-fill siswa
    const namaKelas = data.siswa && data.siswa.kelas 
        ? (data.siswa.kelas.nama_kelas || `${data.siswa.kelas.tingkat} ${data.siswa.kelas.rombel}`) 
        : `Tingkat ${data.tingkat}`;
    const namaSiswa = data.siswa ? data.siswa.nama_siswa : data.nis;
    selectSiswa({
        nis: data.nis,
        nama_siswa: namaSiswa,
        nama_kelas: namaKelas,
        tingkat: data.tingkat,
    });

    // Pre-fill pelanggaran
    selectViolation({
        id: null,
        nama: data.pelanggaran,
        poin: data.poin,
    });

    openModal('modal-pelanggaran');
}

/* ════════════════════════════════════════════════════════
   SISWA AUTOCOMPLETE
════════════════════════════════════════════════════════ */
document.getElementById('p_siswa_search').addEventListener('input', function() {
    const q = this.value.trim();
    clearTimeout(siswaSearchTimer);
    if (q.length < 2) {
        closeSiswaDropdown();
        return;
    }
    siswaSearchTimer = setTimeout(() => fetchSiswa(q), 280);
});

document.getElementById('p_siswa_search').addEventListener('keydown', function(e) {
    navigateDropdown(e, 'p_siswa_dropdown', chooseSiswaItem);
});

function fetchSiswa(q) {
    fetch(`/bk/catat-pelanggaran/search-siswa?q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(renderSiswaDropdown)
        .catch(() => {});
}

function renderSiswaDropdown(list) {
    const dd = document.getElementById('p_siswa_dropdown');
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
    const dd = document.getElementById('p_siswa_dropdown');
    const list = dd._data || [];
    if (!list[index]) return;
    selectSiswa(list[index]);
}

function selectSiswa(s) {
    selectedSiswa = s;
    document.getElementById('p_nis_hidden').value     = s.nis;
    document.getElementById('p_tingkat_hidden').value = s.tingkat;
    document.getElementById('p_chip_nama').textContent = s.nama_siswa;
    document.getElementById('p_chip_meta').textContent = `NIS: ${s.nis}  ·  Kelas: ${s.nama_kelas}`;
    document.getElementById('p_siswa_chip').classList.add('show');
    document.getElementById('p_siswa_search').style.display = 'none';
    closeSiswaDropdown();
    updateSubmitState();
}

function clearSiswa() {
    selectedSiswa = null;
    document.getElementById('p_nis_hidden').value     = '';
    document.getElementById('p_tingkat_hidden').value = '';
    document.getElementById('p_siswa_chip').classList.remove('show');
    const inp = document.getElementById('p_siswa_search');
    inp.style.display = '';
    inp.value = '';
    inp.focus();
    updateSubmitState();
}

function resetSiswaSearch() {
    selectedSiswa = null;
    document.getElementById('p_nis_hidden').value     = '';
    document.getElementById('p_tingkat_hidden').value = '';
    document.getElementById('p_siswa_chip').classList.remove('show');
    document.getElementById('p_siswa_search').style.display = '';
    document.getElementById('p_siswa_search').value = '';
    closeSiswaDropdown();
}

function closeSiswaDropdown() {
    document.getElementById('p_siswa_dropdown').classList.remove('open');
}

/* ════════════════════════════════════════════════════════
   VIOLATION AUTOCOMPLETE
════════════════════════════════════════════════════════ */
document.getElementById('p_violation_search').addEventListener('input', function() {
    const q = this.value.trim();
    clearTimeout(violationTimer);
    if (q.length === 0) {
        // Show all when empty (first focus)
        violationTimer = setTimeout(() => fetchViolations(''), 0);
        return;
    }
    violationTimer = setTimeout(() => fetchViolations(q), 250);
});

document.getElementById('p_violation_search').addEventListener('focus', function() {
    if (!this.value.trim()) fetchViolations('');
});

document.getElementById('p_violation_search').addEventListener('keydown', function(e) {
    navigateDropdown(e, 'p_violation_dropdown', chooseViolationItem);
});

function fetchViolations(q) {
    fetch(`/bk/kategori-pelanggaran/search?q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(renderViolationDropdown)
        .catch(() => {});
}

function renderViolationDropdown(list) {
    const dd = document.getElementById('p_violation_dropdown');
    if (!list.length) {
        dd.innerHTML = '<div class="no-results-item"><i class="fa-solid fa-circle-xmark"></i> Kategori tidak ditemukan. Tambahkan terlebih dahulu di menu Kategori Pelanggaran.</div>';
    } else {
        dd.innerHTML = list.map((v, i) => `
            <div class="autocomplete-item" data-index="${i}" onclick="chooseViolationItem(${i})"
                 data-id="${v.id_jenis_pelanggaran}" data-nama="${v.jenis_pelanggaran.replace(/"/g,'&quot;')}" data-poin="${v.poin}">
                <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
                    <span class="item-main">${v.jenis_pelanggaran}</span>
                    <span style="background:#ef4444;color:#fff;font-size:0.78rem;font-weight:700;
                                 padding:2px 10px;border-radius:20px;white-space:nowrap;flex-shrink:0;">
                        &minus; ${v.poin} poin
                    </span>
                </div>
            </div>`).join('');
    }
    dd.classList.add('open');
    dd._data = list;
}

function chooseViolationItem(index) {
    const dd = document.getElementById('p_violation_dropdown');
    const list = dd._data || [];
    if (!list[index]) return;
    const v = list[index];
    selectViolation({ id: v.id_jenis_pelanggaran, nama: v.jenis_pelanggaran, poin: v.poin });
}

function selectViolation(v) {
    selectedViolation = v;
    document.getElementById('p_pelanggaran_hidden').value = v.nama;
    document.getElementById('p_poin_hidden').value         = v.poin;
    document.getElementById('p_poin_display').value        = v.poin;
    document.getElementById('p_chip_violation').textContent = v.nama;
    document.getElementById('p_chip_poin_label').textContent = `\u2212 ${v.poin} poin`;
    document.getElementById('p_violation_chip').classList.add('show');
    document.getElementById('p_violation_search').style.display = 'none';
    closeViolationDropdown();
    updateSubmitState();
}

function clearViolation() {
    selectedViolation = null;
    document.getElementById('p_pelanggaran_hidden').value = '';
    document.getElementById('p_poin_hidden').value        = '';
    document.getElementById('p_poin_display').value       = '';
    document.getElementById('p_violation_chip').classList.remove('show');
    const inp = document.getElementById('p_violation_search');
    inp.style.display = '';
    inp.value = '';
    inp.focus();
    updateSubmitState();
}

function resetViolationSearch() {
    selectedViolation = null;
    document.getElementById('p_pelanggaran_hidden').value = '';
    document.getElementById('p_poin_hidden').value        = '';
    document.getElementById('p_poin_display').value       = '';
    document.getElementById('p_violation_chip').classList.remove('show');
    document.getElementById('p_violation_search').style.display = '';
    document.getElementById('p_violation_search').value = '';
    closeViolationDropdown();
}

function closeViolationDropdown() {
    document.getElementById('p_violation_dropdown').classList.remove('open');
}

/* ════════════════════════════════════════════════════════
   KEYBOARD NAVIGATION HELPER
════════════════════════════════════════════════════════ */
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

/* ════════════════════════════════════════════════════════
   CLOSE DROPDOWNS ON OUTSIDE CLICK
════════════════════════════════════════════════════════ */
document.addEventListener('click', function(e) {
    if (!e.target.closest('.autocomplete-wrapper')) {
        closeSiswaDropdown();
        closeViolationDropdown();
    }
});
</script>
@endpush
@endsection
