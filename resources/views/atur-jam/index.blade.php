@extends('layouts.app')

@section('title', 'Atur Jam Pelajaran — SmartSchool')
@section('header_title', 'Atur Jam Pelajaran')
@section('header_subtitle', 'Kelola jam pelajaran untuk berbagai skema jadwal sekolah')

@section('content')
<style>
/* ─── Active Schedule Cards ─── */
.schedule-options {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
@media (max-width: 768px) {
    .schedule-options { grid-template-columns: 1fr; }
}

.option-card {
    background: #fff;
    border: 2px solid #e2e8f0;
    border-radius: 14px;
    padding: 20px;
    position: relative;
    cursor: pointer;
    transition: all 0.25s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}
.option-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}
.option-card.active {
    border-color: #10b981;
    background-color: rgba(16,185,129,0.02);
}
.option-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.option-title {
    font-size: 1rem;
    font-weight: 700;
    color: var(--text-primary);
}
.option-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
}
.option-desc {
    font-size: 0.82rem;
    color: var(--text-muted);
    line-height: 1.5;
    margin-bottom: 20px;
}
.option-action-btn {
    width: 100%;
    text-align: center;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 0.82rem;
    font-weight: 700;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}
.option-card.active .option-action-btn {
    background-color: #10b981;
    color: #fff;
    cursor: default;
}
.option-card:not(.active) .option-action-btn {
    background-color: #f1f5f9;
    color: #64748b;
}
.option-card:not(.active) .option-action-btn:hover {
    background-color: #e2e8f0;
    color: var(--text-primary);
}

/* ─── Table Columns Highlighting ─── */
.highlight-column {
    background-color: rgba(16,185,129,0.04) !important;
    border-left: 1.5px dashed rgba(16,185,129,0.2);
    border-right: 1.5px dashed rgba(16,185,129,0.2);
}
th.highlight-column-header {
    background-color: rgba(16,185,129,0.08) !important;
    color: #0f766e !important;
    font-weight: 800 !important;
    border-left: 1.5px dashed rgba(16,185,129,0.3);
    border-right: 1.5px dashed rgba(16,185,129,0.3);
}
</style>

<div class="page-content">
    @include('partials.flash')

    {{-- ─── Section 1: Active Schedule Selector ─── --}}
    <div class="card" style="margin-bottom: 20px;">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-clock"></i> Pengaturan Jadwal Aktif Saat Ini</h2>
        </div>
        <div class="card-body">
            <p style="font-size:0.85rem; color:var(--text-muted); margin-bottom:20px; line-height:1.5;">
                Pilih skema waktu jam pelajaran yang saat ini sedang berlaku di sekolah. Sistem absensi, rekap jurnal guru, dan jadwal harian KBM akan secara dinamis mengikuti skema waktu yang aktif di bawah ini.
            </p>

            <div class="schedule-options">
                {{-- Jadwal Normal --}}
                <div class="option-card {{ $jadwalAktif == 'normal' ? 'active' : '' }}" onclick="submitActiveSchedule('normal')">
                    <div>
                        <div class="option-header">
                            <span class="option-title">Jadwal Normal</span>
                            <div class="option-icon" style="background:rgba(59,130,246,0.1); color:#3b82f6;"><i class="fa-solid fa-calendar-check"></i></div>
                        </div>
                        <div class="option-desc">
                            Jam KBM standar sekolah di hari biasa (Senin - Jumat) tanpa upacara atau penyesuaian khusus.
                        </div>
                    </div>
                    <button class="option-action-btn">
                        @if($jadwalAktif == 'normal')
                            <i class="fa-solid fa-circle-check"></i> Aktif Saat Ini
                        @else
                            Aktifkan Skema
                        @endif
                    </button>
                </div>

                {{-- Jadwal Upacara --}}
                <div class="option-card {{ $jadwalAktif == 'upacara' ? 'active' : '' }}" onclick="submitActiveSchedule('upacara')">
                    <div>
                        <div class="option-header">
                            <span class="option-title">Jadwal Hari Upacara</span>
                            <div class="option-icon" style="background:rgba(245,158,11,0.1); color:#f59e0b;"><i class="fa-solid fa-flag"></i></div>
                        </div>
                        <div class="option-desc">
                            Digunakan pada hari Senin atau hari besar lainnya. Jam ke-1 digeser mundur untuk pelaksanaan upacara bendera.
                        </div>
                    </div>
                    <button class="option-action-btn">
                        @if($jadwalAktif == 'upacara')
                            <i class="fa-solid fa-circle-check"></i> Aktif Saat Ini
                        @else
                            Aktifkan Skema
                        @endif
                    </button>
                </div>

                {{-- Jadwal Puasa --}}
                <div class="option-card {{ $jadwalAktif == 'puasa' ? 'active' : '' }}" onclick="submitActiveSchedule('puasa')">
                    <div>
                        <div class="option-header">
                            <span class="option-title">Jadwal Bulan Puasa</span>
                            <div class="option-icon" style="background:rgba(139,92,246,0.1); color:#8b5cf6;"><i class="fa-solid fa-moon"></i></div>
                        </div>
                        <div class="option-desc">
                            Penyesuaian jam KBM selama bulan suci Ramadan. Durasi per jam pelajaran biasanya dikurangi agar pulang lebih awal.
                        </div>
                    </div>
                    <button class="option-action-btn">
                        @if($jadwalAktif == 'puasa')
                            <i class="fa-solid fa-circle-check"></i> Aktif Saat Ini
                        @else
                            Aktifkan Skema
                        @endif
                    </button>
                </div>
            </div>

            {{-- Hidden Form for Changing Active Schedule --}}
            <form id="form-update-aktif" action="{{ route('atur-jam.update-aktif') }}" method="POST" style="display:none;">
                @csrf
                <input type="hidden" name="jadwal_aktif" id="input_jadwal_aktif">
            </form>
        </div>
    </div>

    {{-- ─── Section 2: Table of Hours ─── --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-list-ol"></i> Konfigurasi Jam Pelajaran</h2>
            <div class="card-header-right">
                <button class="btn btn-primary btn-sm" onclick="openTambahModal()">
                    <i class="fa-solid fa-plus"></i> Tambah Jam Pelajaran
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">Jam Ke</th>
                        <th class="{{ $jadwalAktif == 'normal' ? 'highlight-column-header' : '' }} text-center">
                            @if($jadwalAktif == 'normal') <i class="fa-solid fa-circle-play" style="font-size:0.75rem;"></i> @endif
                            Jadwal Normal
                        </th>
                        <th class="{{ $jadwalAktif == 'upacara' ? 'highlight-column-header' : '' }} text-center">
                            @if($jadwalAktif == 'upacara') <i class="fa-solid fa-circle-play" style="font-size:0.75rem;"></i> @endif
                            Jadwal Upacara
                        </th>
                        <th class="{{ $jadwalAktif == 'puasa' ? 'highlight-column-header' : '' }} text-center">
                            @if($jadwalAktif == 'puasa') <i class="fa-solid fa-circle-play" style="font-size:0.75rem;"></i> @endif
                            Jadwal Puasa
                        </th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jamList as $j)
                    <tr>
                        <td class="text-center" style="font-weight: 700; font-size: 1.1rem; color: var(--color-primary);">
                            {{ $j->jam_ke }}
                        </td>
                        <td class="{{ $jadwalAktif == 'normal' ? 'highlight-column' : '' }} text-center" style="font-weight: 600;">
                            {{ substr($j->normal_mulai, 0, 5) }} – {{ substr($j->normal_selesai, 0, 5) }}
                        </td>
                        <td class="{{ $jadwalAktif == 'upacara' ? 'highlight-column' : '' }} text-center" style="font-weight: 600;">
                            {{ substr($j->upacara_mulai, 0, 5) }} – {{ substr($j->upacara_selesai, 0, 5) }}
                        </td>
                        <td class="{{ $jadwalAktif == 'puasa' ? 'highlight-column' : '' }} text-center" style="font-weight: 600;">
                            {{ substr($j->puasa_mulai, 0, 5) }} – {{ substr($j->puasa_selesai, 0, 5) }}
                        </td>
                        <td class="action-cell text-center">
                            <button type="button" class="btn-icon btn-edit" title="Edit Jam Ke-{{ $j->jam_ke }}"
                                onclick="editJamRow(
                                    {{ $j->id_jam }},
                                    {{ $j->jam_ke }},
                                    '{{ substr($j->normal_mulai, 0, 5) }}',
                                    '{{ substr($j->normal_selesai, 0, 5) }}',
                                    '{{ substr($j->upacara_mulai, 0, 5) }}',
                                    '{{ substr($j->upacara_selesai, 0, 5) }}',
                                    '{{ substr($j->puasa_mulai, 0, 5) }}',
                                    '{{ substr($j->puasa_selesai, 0, 5) }}'
                                )">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('atur-jam.destroy', $j->id_jam) }}', 'Hapus jam pelajaran ke-{{ $j->jam_ke }}?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center" style="padding:40px 20px; color:var(--text-muted);">
                            <i class="fa-solid fa-clock" style="font-size:2.5rem; opacity:0.2; display:block; margin-bottom:10px;"></i>
                            Belum ada konfigurasi jam pelajaran. Klik <strong>Tambah Jam Pelajaran</strong> untuk membuat.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ════════════════════════════════════════
     MODAL TAMBAH / EDIT JAM PELAJARAN
════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-jam">
    <div class="modal modal-md" style="max-width:550px;">
        <div class="modal-header">
            <h3 id="modal-jam-title"><i class="fa-solid fa-clock" style="color:var(--color-primary);"></i> Tambah Jam Pelajaran</h3>
            <button onclick="closeJamModal()" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-jam" action="{{ route('atur-jam.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                {{-- Jam Ke --}}
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label">Jam Pelajaran Ke- <span class="required">*</span></label>
                    <input type="number" name="jam_ke" id="j_jam_ke" class="form-control" min="1" placeholder="Contoh: 1, 2, 3" required>
                </div>

                {{-- Waktu Mulai & Selesai Normal --}}
                <div style="border-left: 3px solid #3b82f6; padding-left: 10px; margin-bottom: 16px;">
                    <div style="font-size: 0.85rem; font-weight:700; color:#3b82f6; margin-bottom:8px;">1. JADWAL NORMAL</div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Mulai <span class="required">*</span></label>
                            <input type="time" name="normal_mulai" id="j_normal_mulai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Selesai <span class="required">*</span></label>
                            <input type="time" name="normal_selesai" id="j_normal_selesai" class="form-control" required>
                        </div>
                    </div>
                </div>

                {{-- Waktu Mulai & Selesai Upacara --}}
                <div style="border-left: 3px solid #f59e0b; padding-left: 10px; margin-bottom: 16px;">
                    <div style="font-size: 0.85rem; font-weight:700; color:#f59e0b; margin-bottom:8px;">2. JADWAL UPACARA</div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Mulai <span class="required">*</span></label>
                            <input type="time" name="upacara_mulai" id="j_upacara_mulai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Selesai <span class="required">*</span></label>
                            <input type="time" name="upacara_selesai" id="j_upacara_selesai" class="form-control" required>
                        </div>
                    </div>
                </div>

                {{-- Waktu Mulai & Selesai Puasa --}}
                <div style="border-left: 3px solid #8b5cf6; padding-left: 10px;">
                    <div style="font-size: 0.85rem; font-weight:700; color:#8b5cf6; margin-bottom:8px;">3. JADWAL PUASA</div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Mulai <span class="required">*</span></label>
                            <input type="time" name="puasa_mulai" id="j_puasa_mulai" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Selesai <span class="required">*</span></label>
                            <input type="time" name="puasa_selesai" id="j_puasa_selesai" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeJamModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Jam</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function submitActiveSchedule(skema) {
    if (skema === '{{ $jadwalAktif }}') return; // already active
    
    const labelMap = {
        'normal': 'Jadwal Normal',
        'upacara': 'Jadwal Hari Upacara',
        'puasa': 'Jadwal Bulan Puasa'
    };

    if (confirm(`Apakah Anda yakin ingin mengganti jadwal aktif saat ini ke skema: ${labelMap[skema]}?`)) {
        document.getElementById('input_jadwal_aktif').value = skema;
        document.getElementById('form-update-aktif').submit();
    }
}

let isEditMode = false;

function openTambahModal() {
    isEditMode = false;
    document.getElementById('form-jam').reset();
    document.getElementById('form-jam').action = '{{ route('atur-jam.store') }}';
    document.getElementById('modal-jam-title').innerHTML = '<i class="fa-solid fa-clock" style="color:var(--color-primary);"></i> Tambah Jam Pelajaran';
    document.getElementById('j_jam_ke').readOnly = false;
    document.getElementById('j_jam_ke').style.backgroundColor = '';
    openModal('modal-jam');
}

function editJamRow(id, jamKe, normalMulai, normalSelesai, upacaraMulai, upacaraSelesai, puasaMulai, puasaSelesai) {
    isEditMode = true;
    const form = document.getElementById('form-jam');
    form.action = `/atur-jam/${id}`;
    
    document.getElementById('modal-jam-title').innerHTML = '<i class="fa-solid fa-pen-to-square" style="color:var(--color-primary);"></i> Edit Jam Pelajaran ke-' + jamKe;
    document.getElementById('j_jam_ke').value = jamKe;
    document.getElementById('j_jam_ke').readOnly = true;
    document.getElementById('j_jam_ke').style.backgroundColor = '#f1f5f9'; // read-only styling
    
    document.getElementById('j_normal_mulai').value = normalMulai;
    document.getElementById('j_normal_selesai').value = normalSelesai;
    document.getElementById('j_upacara_mulai').value = upacaraMulai;
    document.getElementById('j_upacara_selesai').value = upacaraSelesai;
    document.getElementById('j_puasa_mulai').value = puasaMulai;
    document.getElementById('j_puasa_selesai').value = puasaSelesai;

    openModal('modal-jam');
}

function closeJamModal() {
    closeModal('modal-jam');
}

// Reset form on modal close by clicking overlay
document.getElementById('modal-jam').addEventListener('click', function(e) {
    if (e.target === this) closeJamModal();
});
</script>
@endpush
@endsection
