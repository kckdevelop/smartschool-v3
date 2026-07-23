@extends('layouts.app')

@section('title', 'Kunjungan UKS — SmartSchool')
@section('header_title', 'Kunjungan UKS')
@section('header_subtitle', 'Pencatatan kunjungan siswa ke Unit Kesehatan Sekolah')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Stat Cards --}}
    <div class="uks-stats-row">
        <div class="uks-stat-card" style="background:linear-gradient(135deg,#0d9488,#14b8a6);">
            <div class="uks-stat-icon"><i class="fa-solid fa-hospital-user"></i></div>
            <div>
                <div class="uks-stat-num">{{ $hariIni }}</div>
                <div class="uks-stat-lbl">Kunjungan Hari Ini</div>
            </div>
        </div>
        <div class="uks-stat-card" style="background:linear-gradient(135deg,#7c3aed,#a855f7);">
            <div class="uks-stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
            <div>
                <div class="uks-stat-num">{{ $bulanIni }}</div>
                <div class="uks-stat-lbl">Kunjungan Bulan Ini</div>
            </div>
        </div>
        <div class="uks-stat-card" style="background:linear-gradient(135deg,#f97316,#ea580c);">
            <div class="uks-stat-icon"><i class="fa-solid fa-notes-medical"></i></div>
            <div>
                <div class="uks-stat-num">{{ $totalAll }}</div>
                <div class="uks-stat-lbl">Total Semua Kunjungan</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-heart-pulse"></i> Daftar Kunjungan UKS</h2>
            <div class="card-header-right">
                {{-- Filter Form --}}
                <form method="GET" class="search-form" style="gap:6px;">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama/NIS..." class="form-control form-control-sm" style="width:160px;">
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                           class="form-control form-control-sm" title="Dari tanggal">
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                           class="form-control form-control-sm" title="Sampai tanggal">
                    <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-filter"></i></button>
                    @if(request()->hasAny(['search','tanggal_dari','tanggal_sampai']))
                        <a href="{{ route('uks.kunjungan.index') }}" class="btn btn-secondary btn-sm" title="Reset">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </form>
                <button class="btn btn-primary btn-sm" onclick="openModal('modal-add')" id="btn-tambah-kunjungan">
                    <i class="fa-solid fa-plus"></i> Tambah Kunjungan
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal & Jam</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Keluhan</th>
                        <th>Diagnosa</th>
                        <th>Tindakan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kunjungan as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $kunjungan->firstItem() + $i }}</td>
                        <td>
                            <div style="font-weight:700;font-size:0.85rem;">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">
                                {{ \Carbon\Carbon::parse($item->jam)->format('H:i') }} WIB
                            </div>
                        </td>
                        <td><span class="badge badge-info">{{ $item->nis }}</span></td>
                        <td style="font-weight:600;">{{ $item->siswa?->nama_siswa ?? '-' }}</td>
                        <td>
                            @if($item->siswa?->kelas)
                                <span class="badge badge-muted">{{ $item->siswa->kelas->nama_kelas }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td style="max-width:180px;">
                            <span style="font-size:0.83rem;">{{ Str::limit($item->keluhan, 60) }}</span>
                        </td>
                        <td><span class="badge badge-warning">{{ $item->diagnosa }}</span></td>
                        <td><span class="badge badge-success">{{ $item->tindakan }}</span></td>
                        <td class="action-cell">
                            <a href="{{ route('uks.kunjungan.show', $item->id_kunjungan) }}"
                               class="btn-icon btn-info" title="Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editKunjungan({{ json_encode($item) }}, {{ json_encode($item->riwayatObat ?? []) }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('uks.kunjungan.destroy', $item->id_kunjungan) }}','Yakin hapus kunjungan ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-6">
                            <i class="fa-solid fa-heart-pulse" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada data kunjungan UKS
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kunjungan->hasPages())
        <div class="card-footer">
            {{ $kunjungan->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ═══════ MODAL TAMBAH/EDIT ═══════ --}}
<div class="modal-overlay" id="modal-add">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-title-kunjungan">Tambah Kunjungan UKS</h3>
            <button onclick="closeModal('modal-add')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-kunjungan" method="POST" action="{{ route('uks.kunjungan.store') }}">
            @csrf
            <div id="method-field"></div>
            <div class="modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Siswa <span class="required">*</span></label>
                        <select name="nis" id="k_nis" class="form-control" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswaDaftar as $s)
                                <option value="{{ $s->nis }}" data-nama="{{ $s->nama_siswa }}" data-kelas="{{ $s->kelas?->nama_kelas ?? '-' }}" data-nis="{{ $s->nis }}">
                                    {{ $s->nis }} — {{ $s->nama_siswa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal <span class="required">*</span></label>
                        <input type="date" name="tanggal" id="k_tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jam <span class="required">*</span></label>
                        <input type="time" name="jam" id="k_jam" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Diagnosa <span class="required">*</span></label>
                        <input type="text" name="diagnosa" id="k_diagnosa" class="form-control" placeholder="misal: Sakit kepala" required maxlength="100">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Keluhan <span class="required">*</span></label>
                    <textarea name="keluhan" id="k_keluhan" class="form-control" rows="2" placeholder="Keluhan yang disampaikan siswa..." required maxlength="500"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Tindakan <span class="required">*</span></label>
                    <input type="text" name="tindakan" id="k_tindakan" class="form-control" placeholder="misal: Diberi obat & istirahat" required maxlength="100">
                </div>

                {{-- Riwayat Obat --}}
                <div style="margin-top:16px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                        <label class="form-label" style="margin:0;">Obat yang Diberikan</label>
                        <button type="button" class="btn btn-secondary btn-xs" onclick="tambahRowObat()">
                            <i class="fa-solid fa-plus"></i> Tambah Obat
                        </button>
                    </div>
                    <div id="obat-list">
                        {{-- Row obat diisi JS --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-add')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.uks-stats-row { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px; }
.uks-stat-card { display:flex; align-items:center; gap:16px; padding:20px; border-radius:var(--radius-card); color:#fff; box-shadow:0 6px 24px rgba(0,0,0,0.12); transition:var(--transition-smooth); }
.uks-stat-card:hover { transform:translateY(-3px); box-shadow:0 12px 36px rgba(0,0,0,0.18); }
.uks-stat-icon { width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0; }
.uks-stat-num { font-size:2rem;font-weight:800;line-height:1; }
.uks-stat-lbl { font-size:0.78rem;opacity:.85;margin-top:2px; }
.obat-row { display:grid;grid-template-columns:2fr 1fr 80px 32px;gap:8px;margin-bottom:8px;align-items:center; }
@media(max-width:640px) { .uks-stats-row { grid-template-columns:1fr; } }

/* CSS Pencarian Siswa (Searchable Select) */
.ss-select-wrapper {
    position: relative;
    width: 100%;
}
.ss-select-trigger {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    background: #ffffff;
    border: 1.5px solid rgba(13, 148, 136, 0.15);
    padding: 11px 15px;
    border-radius: 10px;
    color: var(--text-primary);
    font-size: 0.9rem;
    cursor: pointer;
    transition: var(--transition-smooth);
    box-shadow: 0 2px 4px rgba(13, 148, 136, 0.01) inset;
    user-select: none;
}
.ss-select-trigger:hover {
    border-color: var(--color-primary);
}
.ss-select-wrapper.active .ss-select-trigger {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.12);
}
.ss-select-trigger-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 8px;
    display: flex;
    align-items: center;
}
.ss-select-arrow {
    font-size: 0.8rem;
    color: var(--text-muted);
    transition: transform 0.2s ease;
}
.ss-select-wrapper.active .ss-select-arrow {
    transform: rotate(180deg);
    color: var(--color-primary);
}
.ss-select-dropdown {
    position: absolute;
    top: calc(100% + 6px);
    left: 0;
    width: 100%;
    background: #ffffff;
    border: 1.5px solid rgba(13, 148, 136, 0.15);
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(13, 148, 136, 0.15);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transform: translateY(-8px);
    transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s;
    overflow: hidden;
}
.ss-select-wrapper.active .ss-select-dropdown {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}
.ss-select-search-container {
    position: relative;
    padding: 10px;
    border-bottom: 1px solid rgba(13, 148, 136, 0.08);
    background: #f8fafc;
}
.ss-select-search-input {
    width: 100%;
    padding: 8px 12px 8px 32px;
    border-radius: 8px;
    border: 1.5px solid rgba(13, 148, 136, 0.15);
    font-size: 0.85rem;
    outline: none;
    transition: var(--transition-smooth);
}
.ss-select-search-input:focus {
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(13, 148, 136, 0.08);
}
.ss-select-search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-muted);
    font-size: 0.8rem;
}
.ss-select-options-list {
    max-height: 240px;
    overflow-y: auto;
    padding: 6px;
}
.ss-select-option {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.15s ease;
    gap: 12px;
}
.ss-select-option:hover,
.ss-select-option.highlighted {
    background: #f0fdfa;
}
.ss-select-option.selected {
    background: var(--bg-primary);
}
.ss-select-option-left {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}
.ss-select-option-name {
    font-size: 0.88rem;
    font-weight: 600;
    color: var(--text-primary);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.ss-select-option-nis {
    font-size: 0.72rem;
    color: var(--text-muted);
}
.ss-select-option-class {
    flex-shrink: 0;
}
.ss-select-no-results {
    padding: 20px;
    text-align: center;
    color: var(--text-muted);
    font-size: 0.82rem;
}
</style>
@endpush

@push('scripts')
<script>
const today = '{{ now()->format("Y-m-d") }}';
const nowTime = '{{ now()->format("H:i") }}';

function resetModal() {
    document.getElementById('form-kunjungan').action = '{{ route("uks.kunjungan.store") }}';
    document.getElementById('method-field').innerHTML = '';
    document.getElementById('modal-title-kunjungan').textContent = 'Tambah Kunjungan UKS';
    document.getElementById('k_nis').value = '';
    document.getElementById('k_nis').dispatchEvent(new Event('change'));
    document.getElementById('k_tanggal').value = today;
    document.getElementById('k_jam').value = nowTime;
    document.getElementById('k_keluhan').value = '';
    document.getElementById('k_diagnosa').value = '';
    document.getElementById('k_tindakan').value = '';
    document.getElementById('obat-list').innerHTML = '';
    tambahRowObat();
}

document.getElementById('btn-tambah-kunjungan').addEventListener('click', function() {
    resetModal();
    openModal('modal-add');
});

function editKunjungan(data, obats) {
    document.getElementById('form-kunjungan').action = `/uks/kunjungan/${data.id_kunjungan}`;
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-kunjungan').textContent = 'Edit Kunjungan UKS';
    document.getElementById('k_nis').value = data.nis;
    document.getElementById('k_nis').dispatchEvent(new Event('change'));
    document.getElementById('k_tanggal').value = data.tanggal ? data.tanggal.substring(0,10) : '';
    document.getElementById('k_jam').value = data.jam ? data.jam.substring(0,5) : '';
    document.getElementById('k_keluhan').value = data.keluhan;
    document.getElementById('k_diagnosa').value = data.diagnosa;
    document.getElementById('k_tindakan').value = data.tindakan;

    document.getElementById('obat-list').innerHTML = '';
    if (obats && obats.length > 0) {
        obats.forEach(o => tambahRowObat(o.nama_obat, o.dosis, o.jumlah));
    } else {
        tambahRowObat();
    }
    openModal('modal-add');
}

function tambahRowObat(nama='', dosis='', jumlah=1) {
    const div = document.createElement('div');
    div.className = 'obat-row';
    div.innerHTML = `
        <input type="text" name="obat_nama[]" class="form-control form-control-sm" placeholder="Nama obat" value="${nama}">
        <input type="text" name="obat_dosis[]" class="form-control form-control-sm" placeholder="Dosis (3x1)" value="${dosis}">
        <input type="number" name="obat_jumlah[]" class="form-control form-control-sm" placeholder="Jml" value="${jumlah}" min="1">
        <button type="button" class="btn-icon btn-delete" onclick="this.parentElement.remove()" title="Hapus">
            <i class="fa-solid fa-trash"></i>
        </button>`;
    document.getElementById('obat-list').appendChild(div);
}

// Searchable Select Component
function initSearchableSelect(selectId) {
    const originalSelect = document.getElementById(selectId);
    if (!originalSelect) return;

    const wrapper = document.createElement('div');
    wrapper.className = 'ss-select-wrapper';
    wrapper.id = selectId + '-ss-wrapper';

    originalSelect.parentNode.insertBefore(wrapper, originalSelect);
    wrapper.appendChild(originalSelect);
    originalSelect.style.display = 'none';

    const trigger = document.createElement('div');
    trigger.className = 'ss-select-trigger';
    trigger.innerHTML = `
        <span class="ss-select-trigger-text">-- Pilih Siswa --</span>
        <i class="fa-solid fa-chevron-down ss-select-arrow"></i>
    `;
    wrapper.appendChild(trigger);

    const dropdown = document.createElement('div');
    dropdown.className = 'ss-select-dropdown';
    
    const searchContainer = document.createElement('div');
    searchContainer.className = 'ss-select-search-container';
    searchContainer.innerHTML = `
        <input type="text" class="ss-select-search-input" placeholder="Cari nama, NIS, atau kelas...">
        <i class="fa-solid fa-magnifying-glass ss-select-search-icon"></i>
    `;
    dropdown.appendChild(searchContainer);

    const optionsList = document.createElement('div');
    optionsList.className = 'ss-select-options-list';
    dropdown.appendChild(optionsList);
    wrapper.appendChild(dropdown);

    const searchInput = searchContainer.querySelector('.ss-select-search-input');
    const triggerText = trigger.querySelector('.ss-select-trigger-text');

    function buildOptions() {
        optionsList.innerHTML = '';
        const options = Array.from(originalSelect.options);
        let hasVisibleOptions = false;
        
        options.forEach((opt) => {
            if (opt.value === "") return;
            
            const nama = opt.getAttribute('data-nama') || opt.text;
            const kelas = opt.getAttribute('data-kelas') || '';
            const nis = opt.getAttribute('data-nis') || opt.value;
            
            const optionDiv = document.createElement('div');
            optionDiv.className = 'ss-select-option';
            if (originalSelect.value === opt.value) {
                optionDiv.classList.add('selected');
            }
            optionDiv.setAttribute('data-value', opt.value);
            optionDiv.setAttribute('data-nama', nama.toLowerCase());
            optionDiv.setAttribute('data-kelas', kelas.toLowerCase());
            optionDiv.setAttribute('data-nis', nis.toLowerCase());
            
            optionDiv.innerHTML = `
                <div class="ss-select-option-left">
                    <span class="ss-select-option-name">${nama}</span>
                    <span class="ss-select-option-nis">NIS: ${nis}</span>
                </div>
                ${kelas ? `<span class="badge badge-muted ss-select-option-class">${kelas}</span>` : ''}
            `;
            
            optionDiv.addEventListener('click', (e) => {
                e.stopPropagation();
                selectValue(opt.value);
                closeDropdown();
            });
            
            optionsList.appendChild(optionDiv);
            hasVisibleOptions = true;
        });

        if (!hasVisibleOptions) {
            optionsList.innerHTML = '<div class="ss-select-no-results">Tidak ada data siswa</div>';
        }
    }

    function selectValue(val) {
        originalSelect.value = val;
        
        const selectedOpt = Array.from(originalSelect.options).find(opt => opt.value === val);
        if (selectedOpt && val !== "") {
            const nama = selectedOpt.getAttribute('data-nama') || selectedOpt.text;
            const kelas = selectedOpt.getAttribute('data-kelas') || '';
            triggerText.innerHTML = `<span style="font-weight:600;">${nama}</span> ${kelas ? `<span class="badge badge-muted" style="margin-left:8px; font-size:0.75rem;">${kelas}</span>` : ''}`;
        } else {
            triggerText.textContent = '-- Pilih Siswa --';
        }
        
        const items = optionsList.querySelectorAll('.ss-select-option');
        items.forEach(item => {
            if (item.getAttribute('data-value') === val) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });
    }

    function filterOptions() {
        const query = searchInput.value.toLowerCase().trim();
        const items = optionsList.querySelectorAll('.ss-select-option');
        let visibleCount = 0;

        items.forEach(item => {
            const nama = item.getAttribute('data-nama');
            const kelas = item.getAttribute('data-kelas');
            const nis = item.getAttribute('data-nis');

            if (nama.includes(query) || kelas.includes(query) || nis.includes(query)) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        let noResultsMsg = optionsList.querySelector('.ss-select-no-results');
        if (visibleCount === 0) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.className = 'ss-select-no-results';
                noResultsMsg.textContent = 'Siswa tidak ditemukan';
                optionsList.appendChild(noResultsMsg);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }

    function toggleDropdown() {
        const isActive = wrapper.classList.contains('active');
        if (isActive) {
            closeDropdown();
        } else {
            openDropdown();
        }
    }

    function openDropdown() {
        document.querySelectorAll('.ss-select-wrapper.active').forEach(w => {
            if (w !== wrapper) w.classList.remove('active');
        });
        wrapper.classList.add('active');
        searchInput.value = '';
        filterOptions();
        searchInput.focus();
    }

    function closeDropdown() {
        wrapper.classList.remove('active');
    }

    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleDropdown();
    });

    searchInput.addEventListener('click', (e) => {
        e.stopPropagation();
    });

    searchInput.addEventListener('input', filterOptions);

    document.addEventListener('click', (e) => {
        if (!wrapper.contains(e.target)) {
            closeDropdown();
        }
    });

    originalSelect.addEventListener('change', () => {
        const val = originalSelect.value;
        const selectedOpt = Array.from(originalSelect.options).find(opt => opt.value === val);
        if (selectedOpt && val !== "") {
            const nama = selectedOpt.getAttribute('data-nama') || selectedOpt.text;
            const kelas = selectedOpt.getAttribute('data-kelas') || '';
            triggerText.innerHTML = `<span style="font-weight:600;">${nama}</span> ${kelas ? `<span class="badge badge-muted" style="margin-left:8px; font-size:0.75rem;">${kelas}</span>` : ''}`;
        } else {
            triggerText.textContent = '-- Pilih Siswa --';
        }

        const items = optionsList.querySelectorAll('.ss-select-option');
        items.forEach(item => {
            if (item.getAttribute('data-value') === val) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });
    });

    buildOptions();
    selectValue(originalSelect.value);
}

document.addEventListener('DOMContentLoaded', function() {
    initSearchableSelect('k_nis');
});
</script>
@endpush
@endsection
