@extends('layouts.app')

@section('title', 'Kunjungan UKS Gukar — SmartSchool')
@section('header_title', 'Kunjungan UKS Gukar')
@section('header_subtitle', 'Pencatatan kunjungan Guru & Karyawan ke Unit Kesehatan Sekolah')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Stat Cards --}}
    <div class="uks-stats-row">
        <div class="uks-stat-card" style="background:linear-gradient(135deg,#0d9488,#14b8a6);">
            <div class="uks-stat-icon"><i class="fa-solid fa-user-nurse"></i></div>
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
            <h2 class="card-title"><i class="fa-solid fa-heart-pulse"></i> Daftar Kunjungan UKS Gukar</h2>
            <div class="card-header-right">
                {{-- Filter Form --}}
                <form method="GET" class="search-form" style="gap:6px;">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama/NIP..." class="form-control form-control-sm" style="width:160px;">
                    <select name="role" class="form-control form-control-sm" style="width:120px;">
                        <option value="">Semua</option>
                        <option value="guru" {{ request('role') === 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="karyawan" {{ request('role') === 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                    </select>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                           class="form-control form-control-sm" title="Dari tanggal">
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                           class="form-control form-control-sm" title="Sampai tanggal">
                    <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-filter"></i></button>
                    @if(request()->hasAny(['search','role','tanggal_dari','tanggal_sampai']))
                        <a href="{{ route('uks.kunjungan-gukar.index') }}" class="btn btn-secondary btn-sm" title="Reset">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </form>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()" id="btn-tambah-kunjungan-gukar">
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
                        <th>Peran</th>
                        <th>Nama</th>
                        <th>NIP / No. ID</th>
                        <th>Keluhan</th>
                        <th>Diagnosa</th>
                        <th>Tindakan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kunjungan as $i => $item)
                    @php
                        $isGuru  = !is_null($item->id_guru);
                        $nama    = $isGuru ? ($item->guru?->nama_guru ?? '-') : ($item->karyawan?->nama_karyawan ?? '-');
                        $noId    = $isGuru ? ($item->guru?->no_id ?? '-') : ($item->karyawan?->no_id ?? '-');
                        $gukarId = $isGuru ? "guru_{$item->id_guru}" : "karyawan_{$item->id_karyawan}";
                    @endphp
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
                        <td>
                            <span class="badge {{ $isGuru ? 'badge-info' : 'badge-warning' }}">
                                {{ $isGuru ? 'Guru' : 'Karyawan' }}
                            </span>
                        </td>
                        <td style="font-weight:600;">{{ $nama }}</td>
                        <td style="font-size:0.82rem;color:var(--text-muted);">{{ $noId }}</td>
                        <td style="max-width:180px;">
                            <span style="font-size:0.83rem;">{{ Str::limit($item->keluhan, 60) }}</span>
                        </td>
                        <td><span class="badge badge-warning">{{ $item->diagnosa }}</span></td>
                        <td><span class="badge badge-success">{{ $item->tindakan }}</span></td>
                        <td class="action-cell">
                            <a href="{{ route('uks.kunjungan-gukar.show', $item->id_kunjungan) }}"
                               class="btn-icon btn-info" title="Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editKunjungan(
                                    {{ $item->id_kunjungan }},
                                    '{{ $gukarId }}',
                                    '{{ $item->tanggal ? $item->tanggal->format('Y-m-d') : '' }}',
                                    '{{ substr($item->jam, 0, 5) }}',
                                    {{ json_encode($item->keluhan) }},
                                    {{ json_encode($item->diagnosa) }},
                                    {{ json_encode($item->tindakan) }},
                                    {{ json_encode($item->riwayatObat ?? []) }}
                                )">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('uks.kunjungan-gukar.destroy', $item->id_kunjungan) }}','Yakin hapus kunjungan ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-6">
                            <i class="fa-solid fa-heart-pulse" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada data kunjungan UKS Gukar
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
<div class="modal-overlay" id="modal-kunjungan-gukar">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-title-kunjungan-gukar">Tambah Kunjungan UKS Gukar</h3>
            <button onclick="closeModal('modal-kunjungan-gukar')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-kunjungan-gukar" method="POST" action="{{ route('uks.kunjungan-gukar.store') }}">
            @csrf
            <div id="method-field-gukar"></div>
            <div class="modal-body">
                <div class="form-grid-2">
                    {{-- Searchable Gukar Select --}}
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="form-label">Guru / Karyawan <span class="required">*</span></label>
                        <select name="gukar_id" id="gk_gukar_id" class="form-control" required>
                            <option value="">-- Pilih Guru / Karyawan --</option>
                            <optgroup label="Guru">
                                @foreach($gurus as $g)
                                    <option value="guru_{{ $g->id_guru }}"
                                        data-nama="{{ $g->nama_guru }}"
                                        data-noid="{{ $g->no_id }}"
                                        data-peran="Guru">
                                        {{ $g->nama_guru }} — {{ $g->no_id }}
                                    </option>
                                @endforeach
                            </optgroup>
                            <optgroup label="Karyawan">
                                @foreach($karyawans as $k)
                                    <option value="karyawan_{{ $k->id_karyawan }}"
                                        data-nama="{{ $k->nama_karyawan }}"
                                        data-noid="{{ $k->no_id }}"
                                        data-peran="Karyawan">
                                        {{ $k->nama_karyawan }} — {{ $k->no_id }}
                                    </option>
                                @endforeach
                            </optgroup>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal <span class="required">*</span></label>
                        <input type="date" name="tanggal" id="gk_tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jam <span class="required">*</span></label>
                        <input type="time" name="jam" id="gk_jam" class="form-control" required>
                    </div>
                    <div class="form-group" style="grid-column:span 2;">
                        <label class="form-label">Diagnosa <span class="required">*</span></label>
                        <input type="text" name="diagnosa" id="gk_diagnosa" class="form-control"
                               placeholder="misal: Sakit kepala, hipertensi" required maxlength="100">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Keluhan <span class="required">*</span></label>
                    <textarea name="keluhan" id="gk_keluhan" class="form-control" rows="2"
                              placeholder="Keluhan yang disampaikan..." required maxlength="500"></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Tindakan <span class="required">*</span></label>
                    <input type="text" name="tindakan" id="gk_tindakan" class="form-control"
                           placeholder="misal: Diberi obat & istirahat" required maxlength="100">
                </div>

                {{-- Riwayat Obat --}}
                <div style="margin-top:16px;">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:10px;">
                        <label class="form-label" style="margin:0;">Obat yang Diberikan</label>
                        <button type="button" class="btn btn-secondary btn-xs" onclick="tambahRowObatGukar()">
                            <i class="fa-solid fa-plus"></i> Tambah Obat
                        </button>
                    </div>
                    <div id="obat-list-gukar">
                        {{-- Row obat diisi JS --}}
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-kunjungan-gukar')" class="btn btn-secondary">Batal</button>
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
.obat-row-gukar { display:grid;grid-template-columns:2fr 1fr 80px 32px;gap:8px;margin-bottom:8px;align-items:center; }
@media(max-width:640px) { .uks-stats-row { grid-template-columns:1fr; } }

/* Searchable Select */
.ss-select-wrapper { position:relative; width:100%; }
.ss-select-trigger { display:flex; justify-content:space-between; align-items:center; width:100%; background:#ffffff; border:1.5px solid rgba(13,148,136,0.15); padding:11px 15px; border-radius:10px; color:var(--text-primary); font-size:0.9rem; cursor:pointer; transition:var(--transition-smooth); user-select:none; }
.ss-select-trigger:hover { border-color:var(--color-primary); }
.ss-select-wrapper.active .ss-select-trigger { border-color:var(--color-primary); box-shadow:0 0 0 4px rgba(13,148,136,0.12); }
.ss-select-trigger-text { white-space:nowrap; overflow:hidden; text-overflow:ellipsis; padding-right:8px; display:flex; align-items:center; gap:8px; }
.ss-select-arrow { font-size:0.8rem; color:var(--text-muted); transition:transform 0.2s ease; }
.ss-select-wrapper.active .ss-select-arrow { transform:rotate(180deg); color:var(--color-primary); }
.ss-select-dropdown { position:absolute; top:calc(100% + 6px); left:0; width:100%; background:#ffffff; border:1.5px solid rgba(13,148,136,0.15); border-radius:12px; box-shadow:0 10px 30px rgba(13,148,136,0.15); z-index:999; opacity:0; visibility:hidden; transform:translateY(-8px); transition:opacity 0.2s ease,transform 0.2s ease,visibility 0.2s; overflow:hidden; }
.ss-select-wrapper.active .ss-select-dropdown { opacity:1; visibility:visible; transform:translateY(0); }
.ss-select-search-container { position:relative; padding:10px; border-bottom:1px solid rgba(13,148,136,0.08); background:#f8fafc; }
.ss-select-search-input { width:100%; padding:8px 12px 8px 32px; border-radius:8px; border:1.5px solid rgba(13,148,136,0.15); font-size:0.85rem; outline:none; }
.ss-select-search-input:focus { border-color:var(--color-primary); box-shadow:0 0 0 3px rgba(13,148,136,0.08); }
.ss-select-search-icon { position:absolute; left:20px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:0.8rem; }
.ss-select-options-list { max-height:260px; overflow-y:auto; padding:6px; }
.ss-select-group-label { padding:6px 12px 4px; font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.6px; color:var(--color-primary); }
.ss-select-option { display:flex; justify-content:space-between; align-items:center; padding:8px 12px; border-radius:8px; cursor:pointer; transition:background 0.15s ease; gap:12px; }
.ss-select-option:hover,.ss-select-option.highlighted { background:#f0fdfa; }
.ss-select-option.selected { background:var(--bg-primary); }
.ss-select-option-left { display:flex; flex-direction:column; gap:2px; min-width:0; }
.ss-select-option-name { font-size:0.88rem; font-weight:600; color:var(--text-primary); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
.ss-select-option-noid { font-size:0.72rem; color:var(--text-muted); }
.ss-select-no-results { padding:20px; text-align:center; color:var(--text-muted); font-size:0.82rem; }
</style>
@endpush

@push('scripts')
<script>
const today    = '{{ now()->format("Y-m-d") }}';
const nowTime  = '{{ now()->format("H:i") }}';

function openAddModal() {
    document.getElementById('form-kunjungan-gukar').action = '{{ route("uks.kunjungan-gukar.store") }}';
    document.getElementById('method-field-gukar').innerHTML = '';
    document.getElementById('modal-title-kunjungan-gukar').textContent = 'Tambah Kunjungan UKS Gukar';
    document.getElementById('gk_gukar_id').value = '';
    document.getElementById('gk_gukar_id').dispatchEvent(new Event('change'));
    document.getElementById('gk_tanggal').value = today;
    document.getElementById('gk_jam').value = nowTime;
    document.getElementById('gk_keluhan').value = '';
    document.getElementById('gk_diagnosa').value = '';
    document.getElementById('gk_tindakan').value = '';
    document.getElementById('obat-list-gukar').innerHTML = '';
    tambahRowObatGukar();
    openModal('modal-kunjungan-gukar');
}

function editKunjungan(id, gukarId, tanggal, jam, keluhan, diagnosa, tindakan, obats) {
    document.getElementById('form-kunjungan-gukar').action = `/uks/kunjungan-gukar/${id}`;
    document.getElementById('method-field-gukar').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-kunjungan-gukar').textContent = 'Edit Kunjungan UKS Gukar';
    document.getElementById('gk_gukar_id').value = gukarId;
    document.getElementById('gk_gukar_id').dispatchEvent(new Event('change'));
    document.getElementById('gk_tanggal').value = tanggal;
    document.getElementById('gk_jam').value = jam;
    document.getElementById('gk_keluhan').value = keluhan;
    document.getElementById('gk_diagnosa').value = diagnosa;
    document.getElementById('gk_tindakan').value = tindakan;

    document.getElementById('obat-list-gukar').innerHTML = '';
    if (obats && obats.length > 0) {
        obats.forEach(o => tambahRowObatGukar(o.nama_obat, o.dosis, o.jumlah));
    } else {
        tambahRowObatGukar();
    }
    openModal('modal-kunjungan-gukar');
}

function tambahRowObatGukar(nama = '', dosis = '', jumlah = 1) {
    const div = document.createElement('div');
    div.className = 'obat-row-gukar';
    div.innerHTML = `
        <input type="text" name="obat_nama[]" class="form-control form-control-sm" placeholder="Nama obat" value="${nama}">
        <input type="text" name="obat_dosis[]" class="form-control form-control-sm" placeholder="Dosis (3x1)" value="${dosis}">
        <input type="number" name="obat_jumlah[]" class="form-control form-control-sm" placeholder="Jml" value="${jumlah}" min="1">
        <button type="button" class="btn-icon btn-delete" onclick="this.parentElement.remove()" title="Hapus">
            <i class="fa-solid fa-trash"></i>
        </button>`;
    document.getElementById('obat-list-gukar').appendChild(div);
}

// Searchable Select Component for Gukar
function initGukarSearchableSelect(selectId) {
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
        <span class="ss-select-trigger-text">-- Pilih Guru / Karyawan --</span>
        <i class="fa-solid fa-chevron-down ss-select-arrow"></i>
    `;
    wrapper.appendChild(trigger);

    const dropdown = document.createElement('div');
    dropdown.className = 'ss-select-dropdown';

    const searchContainer = document.createElement('div');
    searchContainer.className = 'ss-select-search-container';
    searchContainer.innerHTML = `
        <input type="text" class="ss-select-search-input" placeholder="Cari nama atau NIP...">
        <i class="fa-solid fa-magnifying-glass ss-select-search-icon"></i>
    `;
    dropdown.appendChild(searchContainer);

    const optionsList = document.createElement('div');
    optionsList.className = 'ss-select-options-list';
    dropdown.appendChild(optionsList);
    wrapper.appendChild(dropdown);

    const searchInput  = searchContainer.querySelector('.ss-select-search-input');
    const triggerText  = trigger.querySelector('.ss-select-trigger-text');

    function buildOptions() {
        optionsList.innerHTML = '';
        // Build dari semua option di select (termasuk yang di optgroup)
        const allOptions = Array.from(originalSelect.options);
        const optgroups  = Array.from(originalSelect.querySelectorAll('optgroup'));

        if (optgroups.length > 0) {
            optgroups.forEach(group => {
                const groupItems = Array.from(group.querySelectorAll('option')).filter(o => o.value);
                if (groupItems.length === 0) return; // skip group kosong

                const groupLabel = document.createElement('div');
                groupLabel.className = 'ss-select-group-label';
                groupLabel.setAttribute('data-group', group.label);
                groupLabel.textContent = group.label;
                optionsList.appendChild(groupLabel);

                groupItems.forEach(opt => {
                    const nama  = opt.getAttribute('data-nama') || opt.text;
                    const noId  = String(opt.getAttribute('data-noid') || '');
                    const peran = opt.getAttribute('data-peran') || '';

                    const optDiv = document.createElement('div');
                    optDiv.className = 'ss-select-option';
                    optDiv.setAttribute('data-group', group.label);
                    if (originalSelect.value === opt.value) optDiv.classList.add('selected');
                    optDiv.setAttribute('data-value', opt.value);
                    optDiv.setAttribute('data-nama', nama.toLowerCase());
                    optDiv.setAttribute('data-noid', noId.toLowerCase());

                    optDiv.innerHTML = `
                        <div class="ss-select-option-left">
                            <span class="ss-select-option-name">${nama}</span>
                            <span class="ss-select-option-noid">NIP/ID: ${noId}</span>
                        </div>
                        <span class="badge ${peran === 'Guru' ? 'badge-info' : 'badge-warning'}" style="font-size:0.7rem;">${peran}</span>
                    `;
                    optDiv.addEventListener('click', (e) => {
                        e.stopPropagation();
                        selectValue(opt.value);
                        closeDropdown();
                    });
                    optionsList.appendChild(optDiv);
                });
            });
        } else {
            // Fallback: tanpa optgroup
            allOptions.forEach(opt => {
                if (!opt.value) return;
                const nama  = opt.getAttribute('data-nama') || opt.text;
                const noId  = String(opt.getAttribute('data-noid') || '');

                const optDiv = document.createElement('div');
                optDiv.className = 'ss-select-option';
                if (originalSelect.value === opt.value) optDiv.classList.add('selected');
                optDiv.setAttribute('data-value', opt.value);
                optDiv.setAttribute('data-nama', nama.toLowerCase());
                optDiv.setAttribute('data-noid', noId.toLowerCase());
                optDiv.innerHTML = `<div class="ss-select-option-left"><span class="ss-select-option-name">${nama}</span><span class="ss-select-option-noid">NIP/ID: ${noId}</span></div>`;
                optDiv.addEventListener('click', (e) => { e.stopPropagation(); selectValue(opt.value); closeDropdown(); });
                optionsList.appendChild(optDiv);
            });
        }
    }

    function selectValue(val) {
        originalSelect.value = val;
        const selectedOpt = Array.from(originalSelect.options).find(opt => opt.value === val);
        if (selectedOpt && val !== '') {
            const nama  = selectedOpt.getAttribute('data-nama') || selectedOpt.text;
            const peran = selectedOpt.getAttribute('data-peran') || '';
            triggerText.innerHTML = `<span class="badge ${peran === 'Guru' ? 'badge-info' : 'badge-warning'}" style="font-size:0.7rem;">${peran}</span><span style="font-weight:600;">${nama}</span>`;
        } else {
            triggerText.textContent = '-- Pilih Guru / Karyawan --';
        }
        optionsList.querySelectorAll('.ss-select-option').forEach(item => {
            item.classList.toggle('selected', item.getAttribute('data-value') === val);
        });
    }

    function filterOptions() {
        const query = searchInput.value.toLowerCase().trim();
        let totalVisible = 0;

        // Filter setiap opsi
        optionsList.querySelectorAll('.ss-select-option').forEach(item => {
            const nameVal = item.getAttribute('data-nama') || '';
            const noidVal = item.getAttribute('data-noid') || '';
            const match = nameVal.includes(query) || noidVal.includes(query);
            item.style.display = match ? 'flex' : 'none';
            if (match) totalVisible++;
        });

        // Sembunyikan group label jika semua item di group tersebut disembunyikan
        optionsList.querySelectorAll('.ss-select-group-label').forEach(label => {
            const groupName = label.getAttribute('data-group');
            if (!groupName) return;
            const groupItems = optionsList.querySelectorAll(`.ss-select-option[data-group="${groupName}"]`);
            const anyVisible = Array.from(groupItems).some(el => el.style.display !== 'none');
            label.style.display = anyVisible ? '' : 'none';
        });

        // Pesan tidak ditemukan
        let noResultsMsg = optionsList.querySelector('.ss-select-no-results');
        if (totalVisible === 0) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.className = 'ss-select-no-results';
                noResultsMsg.textContent = 'Tidak ditemukan';
                optionsList.appendChild(noResultsMsg);
            }
            noResultsMsg.style.display = '';
        } else {
            if (noResultsMsg) noResultsMsg.style.display = 'none';
        }
    }

    function openDropdown() {
        document.querySelectorAll('.ss-select-wrapper.active').forEach(w => { if (w !== wrapper) w.classList.remove('active'); });
        wrapper.classList.add('active');
        searchInput.value = '';
        filterOptions();
        searchInput.focus();
    }

    function closeDropdown() { wrapper.classList.remove('active'); }

    trigger.addEventListener('click', (e) => { e.stopPropagation(); wrapper.classList.contains('active') ? closeDropdown() : openDropdown(); });
    searchInput.addEventListener('click', (e) => e.stopPropagation());
    searchInput.addEventListener('input', filterOptions);
    document.addEventListener('click', (e) => { if (!wrapper.contains(e.target)) closeDropdown(); });
    originalSelect.addEventListener('change', () => selectValue(originalSelect.value));

    buildOptions();
    selectValue(originalSelect.value);
}

document.addEventListener('DOMContentLoaded', function () {
    initGukarSearchableSelect('gk_gukar_id');
});
</script>
@endpush
@endsection
