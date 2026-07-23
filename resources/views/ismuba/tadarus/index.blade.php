@extends('layouts.app')

@section('title', 'Pantauan Tadarus Kelas — SmartSchool')
@section('header_title', 'Pantauan Tadarus Kelas')
@section('header_subtitle', 'Monitoring kegiatan tadarus Al-Qur\'an per kelas')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Stat Cards --}}
    <div class="ismuba-stats-row">
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#b45309,#f59e0b);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-quran"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $totalHariIni }}</div>
                <div class="ismuba-stat-lbl">Sesi Hari Ini</div>
            </div>
        </div>
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#0369a1,#0ea5e9);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $totalBulanIni }}</div>
                <div class="ismuba-stat-lbl">Sesi Bulan Ini</div>
            </div>
        </div>
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#047857,#10b981);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-school"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $totalAll }}</div>
                <div class="ismuba-stat-lbl">Total Sesi Tadarus</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-quran"></i> Data Pantauan Tadarus Kelas</h2>
            <div class="card-header-right">
                <div class="btaq-header-controls">
                    {{-- Filter Kelas --}}
                    @if($kelasList->isNotEmpty())
                    <form method="GET" class="search-form" id="form-kelas-select" style="gap:8px; margin:0;">
                        <input type="hidden" name="tanggal_dari" value="{{ request('tanggal_dari') }}">
                        <input type="hidden" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}">
                        <label class="form-label mb-0" style="white-space:nowrap; font-size:0.82rem;"><i class="fa-solid fa-chalkboard-user"></i> Kelas:</label>
                        <select name="id_kelas" class="form-control form-control-sm" id="select-kelas"
                                onchange="this.form.submit()" style="min-width:160px;">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id_kelas }}" {{ $selectedKelasId == $kelas->id_kelas ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </form>
                    @endif

                    {{-- Filter Date Range --}}
                    <form method="GET" class="search-form" style="gap:6px; margin:0;">
                        <input type="hidden" name="id_kelas" value="{{ $selectedKelasId }}">
                        <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                               class="form-control form-control-sm" title="Dari tanggal" style="width:130px;">
                        <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                               class="form-control form-control-sm" title="Sampai tanggal" style="width:130px;">
                        <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-filter"></i></button>
                        @if(request()->hasAny(['tanggal_dari','tanggal_sampai']))
                            <a href="{{ route('ismuba.tadarus.index', ['id_kelas' => $selectedKelasId]) }}" class="btn btn-secondary btn-sm" title="Reset">
                                <i class="fa-solid fa-rotate-left"></i>
                            </a>
                        @endif
                    </form>

                    <button class="btn btn-primary btn-sm" onclick="openAddTadarus()" id="btn-tambah-tadarus">
                        <i class="fa-solid fa-plus"></i> Tambah Data
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Tanggal</th>
                        <th>Materi Tadarus</th>
                        <th>Guru Pendamping</th>
                        <th style="width:100px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tadarusList as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $tadarusList->firstItem() + $i }}</td>
                        <td>
                            <div style="font-weight:700;font-size:0.85rem;">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </div>
                        </td>
                        <td>
                            @php
                                $isSameSurah = ($item->awal_surat === $item->akhir_surat);
                            @endphp
                            <div style="display:flex; flex-direction:column; gap:4px;">
                                @if($isSameSurah)
                                    <div>
                                        <span class="badge" style="background:rgba(180,83,9,0.1);color:#b45309;border:1px solid rgba(180,83,9,0.2); font-weight:700;">
                                            QS. {{ $item->awal_surat }}
                                        </span>
                                        <span style="font-weight:700; margin-left:4px; font-size:0.85rem;">
                                            Ayat {{ $item->awal_ayat }} - {{ $item->akhir_ayat }}
                                        </span>
                                    </div>
                                @else
                                    <div style="display:flex; align-items:center; gap:6px; flex-wrap:wrap;">
                                        <span class="badge" style="background:rgba(180,83,9,0.1);color:#b45309;border:1px solid rgba(180,83,9,0.2); font-weight:700;">
                                            QS. {{ $item->awal_surat }}: {{ $item->awal_ayat }}
                                        </span>
                                        <span class="text-muted" style="font-size:0.8rem; font-weight:600;">s/d</span>
                                        <span class="badge" style="background:rgba(4,120,87,0.1);color:#047857;border:1px solid rgba(4,120,87,0.2); font-weight:700;">
                                            QS. {{ $item->akhir_surat }}: {{ $item->akhir_ayat }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td style="font-size:0.83rem; font-weight:600; color:var(--text-secondary);">
                            <i class="fa-solid fa-user-tie" style="margin-right:4px; opacity:0.7;"></i>
                            {{ $item->guru?->nama_guru ?? '-' }}
                        </td>
                        <td class="action-cell" style="text-align:center;">
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editTadarus({{ json_encode($item) }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('ismuba.tadarus.destroy', $item->id_tadarus) }}','Yakin hapus data tadarus ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-6">
                            <i class="fa-solid fa-quran" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada data pantauan tadarus untuk kelas ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($tadarusList->hasPages())
        <div class="card-footer">
            {{ $tadarusList->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ═══════ MODAL TAMBAH/EDIT TADARUS ═══════ --}}
<div class="modal-overlay" id="modal-add-tadarus">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-title-tadarus"><i class="fa-solid fa-quran" style="color:var(--color-primary);"></i> Tambah Data Tadarus</h3>
            <button onclick="closeModal('modal-add-tadarus')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-tadarus" method="POST" action="{{ route('ismuba.tadarus.store') }}">
            @csrf
            <div id="tadarus-method-field"></div>
            <div class="modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Kelas <span class="required">*</span></label>
                        <select name="id_kelas" id="tdr_id_kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal <span class="required">*</span></label>
                        <input type="date" name="tanggal" id="tdr_tanggal" class="form-control" required>
                    </div>
                </div>

                <div id="tdr_start_info" class="alert alert-info py-2 px-3 my-3" style="font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-circle-info"></i>
                    <span>Pilih kelas dan tanggal untuk memuat info mulai tadarus.</span>
                </div>

                <div class="form-grid-2" style="margin-top:12px;">
                    <div class="form-group">
                        <label class="form-label">Surat <span class="required">*</span></label>
                        <select name="surat" id="tdr_surat" class="form-control" required>
                            <option value="">-- Pilih Surat --</option>
                            @foreach($surahList as $s)
                                <option value="{{ $s }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ayat <span class="required">*</span></label>
                        <select name="ayat" id="tdr_ayat" class="form-control" required>
                            <option value="">-- Pilih Ayat --</option>
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Guru Pendamping <span class="required">*</span></label>
                        <select name="id_guru" id="tdr_id_guru" class="form-control" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($guruList as $guru)
                                <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-add-tadarus')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.ismuba-stats-row { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px; }
.ismuba-stat-card { display:flex; align-items:center; gap:16px; padding:20px; border-radius:var(--radius-card); color:#fff; box-shadow:0 6px 24px rgba(0,0,0,0.12); transition:var(--transition-smooth); }
.ismuba-stat-card:hover { transform:translateY(-3px); box-shadow:0 12px 36px rgba(0,0,0,0.18); }
.ismuba-stat-icon { width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0; }
.ismuba-stat-num { font-size:2rem;font-weight:800;line-height:1; }
.ismuba-stat-lbl { font-size:0.78rem;opacity:.85;margin-top:2px; }

.btaq-header-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.btaq-header-controls .search-form {
    display: flex;
    align-items: center;
}

@media(max-width:640px) { .ismuba-stats-row { grid-template-columns:1fr; } }
</style>
@endpush

@push('scripts')
<script>
const tdrToday = '{{ now()->format("Y-m-d") }}';
const surahList = @json($surahList);
const surahAyatCounts = @json($surahAyatCounts);
const allTadarusRecords = @json($allTadarusRecords);

function populateAyatSelect(suratSelectId, ayatSelectId) {
    const suratVal = document.getElementById(suratSelectId).value;
    const ayatSelect = document.getElementById(ayatSelectId);
    
    ayatSelect.innerHTML = '<option value="">-- Pilih Ayat --</option>';
    
    if (suratVal && surahAyatCounts[suratVal]) {
        const totalAyat = surahAyatCounts[suratVal];
        for (let i = 1; i <= totalAyat; i++) {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = i;
            ayatSelect.appendChild(opt);
        }
    }
}

function getNextVerse(surat, ayat) {
    const totalAyat = surahAyatCounts[surat];
    if (ayat < totalAyat) {
        return { surat: surat, ayat: ayat + 1 };
    } else {
        const idx = surahList.indexOf(surat);
        if (idx !== -1 && idx < surahList.length - 1) {
            const nextSurat = surahList[idx + 1];
            return { surat: nextSurat, ayat: 1 };
        }
    }
    return { surat: 'Al-Fatihah', ayat: 1 };
}

function disableOption(opt, suffix = ' (Terlewati)') {
    opt.disabled = true;
    opt.style.color = '#94a3b8';
    opt.style.textDecoration = 'line-through';
    opt.style.backgroundColor = '#f8fafc';
    if (opt.value && !opt.textContent.endsWith(suffix)) {
        opt.textContent += suffix;
    }
}

function enableOption(opt, suffix = ' (Terlewati)') {
    opt.disabled = false;
    opt.style.color = '';
    opt.style.textDecoration = '';
    opt.style.backgroundColor = '';
    opt.textContent = opt.textContent.replace(suffix, '');
}

function resetRestrictions() {
    document.querySelectorAll('#tdr_surat option').forEach(opt => enableOption(opt));
    document.querySelectorAll('#tdr_ayat option').forEach(opt => enableOption(opt));
}

function updateTadarusStartVerse() {
    const classId = parseInt(document.getElementById('tdr_id_kelas').value);
    const tanggal = document.getElementById('tdr_tanggal').value;
    const currentAction = document.getElementById('form-tadarus').action;
    const currentId = currentAction.split('/').pop();
    const editingId = isNaN(parseInt(currentId)) ? 0 : parseInt(currentId);

    if (!classId || !tanggal) {
        document.getElementById('tdr_start_info').innerHTML = '<i class="fa-solid fa-circle-info"></i> <span>Pilih kelas dan tanggal untuk memuat info mulai tadarus.</span>';
        resetRestrictions();
        return;
    }

    let lastRecord = null;
    for (let i = allTadarusRecords.length - 1; i >= 0; i--) {
        const r = allTadarusRecords[i];
        if (r.id_kelas === classId && r.tanggal.substring(0, 10) <= tanggal && r.id_tadarus !== editingId) {
            lastRecord = r;
            break;
        }
    }

    let startSurat = 'Al-Fatihah';
    let startAyat = 1;

    if (lastRecord) {
        const next = getNextVerse(lastRecord.akhir_surat, parseInt(lastRecord.akhir_ayat));
        startSurat = next.surat;
        startAyat = next.ayat;
    }

    document.getElementById('tdr_start_info').innerHTML = `<i class="fa-solid fa-book-open"></i> <span>Tadarus dimulai dari: <strong class="text-primary" style="font-weight:700;">QS. ${startSurat}: ${startAyat}</strong></span>`;
    
    applyTadarusRestrictions(startSurat, startAyat);
}

function applyTadarusRestrictions(startSurat, startAyat) {
    resetRestrictions();
    
    const startSuratIdx = surahList.indexOf(startSurat);

    // 1. Disable surahs before startSurat
    document.querySelectorAll('#tdr_surat option').forEach(opt => {
        if (opt.value) {
            const sIdx = surahList.indexOf(opt.value);
            if (sIdx < startSuratIdx) {
                disableOption(opt);
            }
        }
    });

    // Reset selected surat if disabled
    const selectedSuratVal = document.getElementById('tdr_surat').value;
    const selectedSuratOpt = document.querySelector(`#tdr_surat option[value="${selectedSuratVal}"]`);
    if (selectedSuratOpt && selectedSuratOpt.disabled) {
        document.getElementById('tdr_surat').value = '';
        document.getElementById('tdr_ayat').innerHTML = '<option value="">-- Pilih Ayat --</option>';
    }

    // 2. Disable ayats before startAyat if selectedSurat is equal to startSurat
    const selectedSurat = document.getElementById('tdr_surat').value;
    if (selectedSurat === startSurat) {
        document.querySelectorAll('#tdr_ayat option').forEach(opt => {
            if (opt.value) {
                const a = parseInt(opt.value);
                if (a < startAyat) {
                    disableOption(opt);
                }
            }
        });

        // Reset selected ayat if disabled
        const selectedAyatVal = document.getElementById('tdr_ayat').value;
        const selectedAyatOpt = document.querySelector(`#tdr_ayat option[value="${selectedAyatVal}"]`);
        if (selectedAyatOpt && selectedAyatOpt.disabled) {
            document.getElementById('tdr_ayat').value = '';
        }
    }
}

document.getElementById('tdr_id_kelas').addEventListener('change', updateTadarusStartVerse);
document.getElementById('tdr_tanggal').addEventListener('change', updateTadarusStartVerse);
document.getElementById('tdr_surat').addEventListener('change', function() {
    populateAyatSelect('tdr_surat', 'tdr_ayat');
    
    // Re-apply restrictions
    const classId = parseInt(document.getElementById('tdr_id_kelas').value);
    const tanggal = document.getElementById('tdr_tanggal').value;
    const currentAction = document.getElementById('form-tadarus').action;
    const currentId = currentAction.split('/').pop();
    const editingId = isNaN(parseInt(currentId)) ? 0 : parseInt(currentId);

    let lastRecord = null;
    for (let i = allTadarusRecords.length - 1; i >= 0; i--) {
        const r = allTadarusRecords[i];
        if (r.id_kelas === classId && r.tanggal.substring(0, 10) <= tanggal && r.id_tadarus !== editingId) {
            lastRecord = r;
            break;
        }
    }

    let startSurat = 'Al-Fatihah';
    let startAyat = 1;

    if (lastRecord) {
        const next = getNextVerse(lastRecord.akhir_surat, parseInt(lastRecord.akhir_ayat));
        startSurat = next.surat;
        startAyat = next.ayat;
    }

    applyTadarusRestrictions(startSurat, startAyat);
});

function openAddTadarus() {
    document.getElementById('form-tadarus').action = '{{ route("ismuba.tadarus.store") }}';
    document.getElementById('tadarus-method-field').innerHTML = '';
    document.getElementById('modal-title-tadarus').innerHTML = '<i class="fa-solid fa-quran" style="color:var(--color-primary);"></i> Tambah Data Tadarus';
    document.getElementById('tdr_id_kelas').value = '{{ $selectedKelasId }}';
    document.getElementById('tdr_tanggal').value = tdrToday;
    document.getElementById('tdr_surat').value = '';
    document.getElementById('tdr_ayat').innerHTML = '<option value="">-- Pilih Ayat --</option>';
    document.getElementById('tdr_id_guru').value = '';
    
    updateTadarusStartVerse();
    openModal('modal-add-tadarus');
}

function editTadarus(data) {
    document.getElementById('form-tadarus').action = `/ismuba/tadarus/${data.id_tadarus}`;
    document.getElementById('tadarus-method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-tadarus').innerHTML = '<i class="fa-solid fa-pen" style="color:var(--color-primary);"></i> Edit Data Tadarus';
    document.getElementById('tdr_id_kelas').value = data.id_kelas;
    document.getElementById('tdr_tanggal').value = data.tanggal ? data.tanggal.substring(0,10) : '';
    
    // Set surat and populate/set ayat
    document.getElementById('tdr_surat').value = data.akhir_surat;
    populateAyatSelect('tdr_surat', 'tdr_ayat');
    document.getElementById('tdr_ayat').value = data.akhir_ayat;
    
    document.getElementById('tdr_id_guru').value = data.id_guru;

    updateTadarusStartVerse();
    openModal('modal-add-tadarus');
}
</script>
@endpush
@endsection
