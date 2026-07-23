@extends('layouts.app')

@section('title', 'Daftar Kursus — SmartSchool')
@section('header_title', 'Daftar Kursus')
@section('header_subtitle', 'Kelola program kursus dan materi pembelajaran')

@push('styles')
<style>
/* CSS styles for LMS Kursus Cards */
.kursus-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
    padding: 24px;
}
.kursus-card-container {
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.kursus-card {
    position: relative;
    border-radius: 20px;
    padding: 24px;
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    flex-direction: column;
    min-height: 180px;
    box-sizing: border-box;
}
.kursus-card:hover {
    transform: translateY(-6px);
}
.kursus-card.card-expanded {
    box-shadow: 0 16px 24px -10px rgba(0, 0, 0, 0.1) !important;
}

/* Gradients and color themes */
.kursus-theme-0 {
    background: linear-gradient(135deg, #eef2ff 0%, #e0e7ff 100%);
    border: 1.5px solid rgba(129, 140, 248, 0.3);
    color: #312e81;
}
.kursus-theme-0:hover {
    box-shadow: 0 16px 24px -10px rgba(79, 70, 229, 0.25);
}
.kursus-theme-0 .text-accent { color: #4f46e5; }
.kursus-theme-0 .badge-accent { background: #4f46e5; color: #fff; }
.kursus-theme-0 .decor-circle { background: rgba(79, 70, 229, 0.05); }

.kursus-theme-1 {
    background: linear-gradient(135deg, #f5f3ff 0%, #ede9fe 100%);
    border: 1.5px solid rgba(167, 139, 250, 0.3);
    color: #4c1d95;
}
.kursus-theme-1:hover {
    box-shadow: 0 16px 24px -10px rgba(124, 58, 237, 0.25);
}
.kursus-theme-1 .text-accent { color: #7c3aed; }
.kursus-theme-1 .badge-accent { background: #7c3aed; color: #fff; }
.kursus-theme-1 .decor-circle { background: rgba(124, 58, 237, 0.05); }

.kursus-theme-2 {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 1.5px solid rgba(56, 189, 248, 0.3);
    color: #0c4a6e;
}
.kursus-theme-2:hover {
    box-shadow: 0 16px 24px -10px rgba(14, 165, 233, 0.25);
}
.kursus-theme-2 .text-accent { color: #0ea5e9; }
.kursus-theme-2 .badge-accent { background: #0ea5e9; color: #fff; }
.kursus-theme-2 .decor-circle { background: rgba(14, 165, 233, 0.05); }

.kursus-theme-3 {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    border: 1.5px solid rgba(52, 211, 153, 0.3);
    color: #064e3b;
}
.kursus-theme-3:hover {
    box-shadow: 0 16px 24px -10px rgba(16, 185, 129, 0.25);
}
.kursus-theme-3 .text-accent { color: #10b981; }
.kursus-theme-3 .badge-accent { background: #10b981; color: #fff; }
.kursus-theme-3 .decor-circle { background: rgba(16, 185, 129, 0.05); }

.kursus-theme-4 {
    background: linear-gradient(135deg, #fffbeb 0%, #fef3c7 100%);
    border: 1.5px solid rgba(251, 191, 36, 0.3);
    color: #78350f;
}
.kursus-theme-4:hover {
    box-shadow: 0 16px 24px -10px rgba(245, 158, 11, 0.25);
}
.kursus-theme-4 .text-accent { color: #f59e0b; }
.kursus-theme-4 .badge-accent { background: #f59e0b; color: #fff; }
.kursus-theme-4 .decor-circle { background: rgba(245, 158, 11, 0.05); }

.kursus-theme-5 {
    background: linear-gradient(135deg, #fff1f2 0%, #ffe4e6 100%);
    border: 1.5px solid rgba(251, 113, 133, 0.3);
    color: #881337;
}
.kursus-theme-5:hover {
    box-shadow: 0 16px 24px -10px rgba(239, 68, 68, 0.25);
}
.kursus-theme-5 .text-accent { color: #ef4444; }
.kursus-theme-5 .badge-accent { background: #ef4444; color: #fff; }
.kursus-theme-5 .decor-circle { background: rgba(239, 68, 68, 0.05); }

.decor-circle {
    position: absolute;
    right: -20px;
    bottom: -20px;
    width: 100px;
    height: 100px;
    border-radius: 50%;
    z-index: 1;
    pointer-events: none;
}

.kursus-card-content {
    position: relative;
    z-index: 2;
    display: flex;
    flex-direction: column;
    height: 100%;
    width: 100%;
}

.kursus-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
}

.kursus-badge-kelas {
    padding: 5px 12px;
    font-size: 0.72rem;
    font-weight: 800;
    border-radius: 20px;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.kursus-actions {
    display: flex;
    gap: 8px;
    z-index: 10;
}

.kursus-btn-action {
    background: rgba(255, 255, 255, 0.65);
    border: none;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.85rem;
    transition: all 0.2s;
    color: inherit;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.04);
}

.kursus-btn-action:hover {
    background: #fff;
    transform: scale(1.1);
}

.kursus-btn-action.btn-delete:hover {
    color: #ef4444;
}

.kursus-title {
    font-size: 1.35rem;
    font-weight: 850;
    line-height: 1.3;
    margin: 16px 0 12px 0;
    letter-spacing: -0.4px;
}

.kursus-teacher {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.85rem;
    font-weight: 700;
    margin-top: auto;
    padding-top: 12px;
    opacity: 0.9;
}

.kursus-teacher i {
    font-size: 0.95rem;
}

.kursus-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 16px;
    padding-top: 14px;
    border-top: 1px dashed rgba(0, 0, 0, 0.08);
}

.kursus-tasks-count {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.85rem;
    font-weight: 800;
}

.kursus-expand-btn {
    font-size: 0.85rem;
    transition: transform 0.3s ease;
}

/* Expanded tasks section styles */
.kursus-tasks-wrapper {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    margin-top: 0;
}

.kursus-tasks-inner {
    padding: 4px 4px 16px 4px;
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.tugas-item {
    background: #fff;
    border: 1px solid rgba(0, 0, 0, 0.06);
    border-radius: 14px;
    padding: 14px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    transition: all 0.2s ease;
    text-decoration: none !important;
    color: var(--text-primary) !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}

.tugas-item:hover {
    border-color: rgba(0, 0, 0, 0.12);
    transform: translateX(6px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.04);
}

.tugas-info-left {
    display: flex;
    flex-direction: column;
    gap: 4px;
    max-width: 75%;
}

.tugas-title {
    font-weight: 750;
    font-size: 0.95rem;
    color: #1e293b;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.tugas-meta {
    font-size: 0.78rem;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 12px;
}

.tugas-status-badge {
    padding: 3px 10px;
    font-size: 0.72rem;
    font-weight: 800;
    border-radius: 20px;
    letter-spacing: 0.3px;
}

.tugas-status-badge.status-aktif {
    background: #ecfdf5;
    color: #059669;
    border: 1px solid #a7f3d0;
}

.tugas-status-badge.status-tidak {
    background: #f1f5f9;
    color: #64748b;
    border: 1px solid #cbd5e1;
}

.tugas-actions {
    display: flex;
    gap: 8px;
    z-index: 10;
}

.tugas-btn-action {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-size: 0.8rem;
    transition: all 0.2s;
    color: #64748b;
}

.tugas-btn-action:hover {
    background: #f1f5f9;
    color: #1e293b;
    transform: scale(1.08);
}

.tugas-btn-action.btn-delete-tugas:hover {
    background: #fef2f2;
    color: #ef4444;
    border-color: #fecaca;
}

.kursus-add-tugas-btn {
    border: 1.5px dashed rgba(0, 0, 0, 0.15);
    background: transparent;
    color: inherit;
    border-radius: 14px;
    padding: 14px;
    text-align: center;
    font-weight: 700;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.kursus-add-tugas-btn:hover {
    background: rgba(255, 255, 255, 0.4);
    border-color: rgba(0, 0, 0, 0.25);
}
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-book-open-reader"></i> Daftar Kursus</h2>
            <div class="card-header-right" style="display:flex;gap:10px;align-items:center;flex-wrap:wrap;">
                <form method="GET" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama kursus..." class="form-control form-control-sm" style="width:150px;">

                    <select name="id_kelas" class="form-control form-control-sm" onchange="this.form.submit()" style="width:140px;">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->tingkat }} {{ $k->rombel }}
                            </option>
                        @endforeach
                    </select>

                    <select name="id_guru" class="form-control form-control-sm" onchange="this.form.submit()" style="width:160px;">
                        <option value="">-- Semua Guru --</option>
                        @foreach($gurus as $g)
                            <option value="{{ $g->id_guru }}" {{ request('id_guru') == $g->id_guru ? 'selected' : '' }}>
                                {{ $g->nama_guru }}
                            </option>
                        @endforeach
                    </select>

                    <button class="btn btn-secondary btn-sm" type="submit"><i class="fa-solid fa-search"></i></button>

                    @if(request('search') || request('id_kelas') || request('id_guru'))
                        <a href="{{ route('lms.kursus.index') }}" class="btn btn-secondary btn-sm" title="Reset Filter">
                            <i class="fa-solid fa-arrow-rotate-left"></i>
                        </a>
                    @endif
                </form>

                <button class="btn btn-primary btn-sm" onclick="openAddModal()">
                    <i class="fa-solid fa-plus"></i> Tambah Kursus
                </button>
            </div>
        </div>

        <div class="card-body" style="padding: 24px; background: var(--bg-body, #f8fafc);">
            <div class="kursus-grid">
                @forelse($kursusList as $kursus)
                <div class="kursus-card-container">
                    <div class="kursus-card kursus-theme-{{ $loop->index % 6 }}" id="kursus-card-{{ $kursus->id_kursus }}" onclick="toggleKursusCard({{ $kursus->id_kursus }})">
                        <div class="decor-circle"></div>
                        <div class="kursus-card-content">
                            <div class="kursus-card-header">
                                <span class="kursus-badge-kelas badge-accent">
                                    {{ $kursus->kelas->tingkat }} {{ $kursus->kelas->rombel }}
                                </span>
                                <div class="kursus-actions">
                                    <button type="button" class="kursus-btn-action btn-edit" title="Edit Kursus"
                                        onclick="event.stopPropagation(); openEditModal(
                                            {{ $kursus->id_kursus }},
                                            '{{ addslashes($kursus->nama_kursus) }}',
                                            {{ $kursus->id_kelas }},
                                            {{ $kursus->id_guru }}
                                        )">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button type="button" class="kursus-btn-action btn-delete" title="Hapus Kursus"
                                        onclick="event.stopPropagation(); confirmDelete(
                                            '{{ route('lms.kursus.destroy', $kursus->id_kursus) }}',
                                            'Yakin ingin menghapus kursus &quot;{{ addslashes($kursus->nama_kursus) }}&quot; beserta tugas dan tagihan siswa di dalamnya?'
                                        )">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <h3 class="kursus-title">{{ $kursus->nama_kursus }}</h3>

                            <div class="kursus-teacher">
                                <i class="fa-solid fa-user-tie text-accent"></i>
                                <span>{{ $kursus->guru->nama_guru }}</span>
                            </div>

                            <div class="kursus-footer">
                                <div class="kursus-tasks-count">
                                    <i class="fa-solid fa-book-open"></i>
                                    <span>{{ $kursus->tugas->count() }} Tugas</span>
                                </div>
                                <div class="kursus-expand-btn" id="arrow-{{ $kursus->id_kursus }}">
                                    <i class="fa-solid fa-chevron-down"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Accordion panel for tugas --}}
                    <div class="kursus-tasks-wrapper" id="tasks-list-{{ $kursus->id_kursus }}">
                        <div class="kursus-tasks-inner">
                            @forelse($kursus->tugas as $tugas)
                                <div class="tugas-item-wrapper" style="position: relative; display: flex; align-items: center; width: 100%;">
                                    <a href="{{ route('lms.tugas.show', $tugas->id_tugas) }}" class="tugas-item" style="width: 100%; box-sizing: border-box; padding-right: 120px;">
                                        <div class="tugas-info-left">
                                            <div class="tugas-title">{{ $tugas->judul }}</div>
                                            <div class="tugas-meta">
                                                <span><i class="fa-regular fa-calendar"></i> {{ $tugas->tenggat ? \Carbon\Carbon::parse($tugas->tenggat)->translatedFormat('d M Y') : '-' }}</span>
                                                <span class="tugas-status-badge status-{{ $tugas->is_published ? 'aktif' : 'tidak' }}">
                                                    {{ $tugas->is_published ? 'Aktif' : 'Draft' }}
                                                </span>
                                            </div>
                                        </div>
                                    </a>
                                    
                                    {{-- Tugas Quick Actions absolute on the right --}}
                                    <div class="tugas-actions" style="position: absolute; right: 18px; display: flex; gap: 6px; align-items: center;">
                                        <a href="{{ route('lms.tagihan.index', ['id_tugas' => $tugas->id_tugas]) }}" class="tugas-btn-action" title="Lihat Nilai & Tagihan" style="color: #eab308; background: #fff;">
                                            <i class="fa-solid fa-list-check"></i>
                                        </a>
                                        <button type="button" class="tugas-btn-action btn-delete-tugas" title="Hapus Tugas" style="background: #fff;"
                                            onclick="confirmDelete('{{ route('lms.tugas.destroy', $tugas->id_tugas) }}', 'Yakin ingin menghapus tugas &quot;{{ addslashes($tugas->judul) }}&quot;? Semua lembar jawaban/tagihan siswa juga akan dihapus!')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div style="background: rgba(255, 255, 255, 0.4); border: 1px dashed rgba(0,0,0,0.1); border-radius: 14px; padding: 20px; text-align: center; font-size: 0.88rem; color: #64748b;">
                                    Belum ada tugas dibuat di kursus ini.
                                </div>
                            @endforelse

                            {{-- Button to redirect to Tugas Create with class/guru params --}}
                            <a href="{{ route('lms.tugas.index', ['id_kelas' => $kursus->id_kelas, 'id_guru' => $kursus->id_guru]) }}" class="kursus-add-tugas-btn">
                                <i class="fa-solid fa-plus"></i> Kelola & Tambah Tugas
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div style="grid-column: 1 / -1; background: #fff; padding: 60px; border-radius: 20px; text-align: center; border: 1.5px solid var(--border-color);">
                    <i class="fa-solid fa-book-open-reader" style="font-size: 4rem; color: #cbd5e1; margin-bottom: 20px; display: block;"></i>
                    <h3 style="color: var(--text-primary); font-weight: 700; margin-bottom: 8px;">Belum Ada Kursus</h3>
                    <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 24px;">Silakan buat kursus pembelajaran baru dengan mengklik tombol di bawah ini.</p>
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="fa-solid fa-plus"></i> Buat Kursus Sekarang
                    </button>
                </div>
                @endforelse
            </div>
        </div>

        @if($kursusList->hasPages())
        <div style="padding:14px 20px;border-top:1px solid var(--border-color);">
            {{ $kursusList->appends(request()->query())->links('pagination.presensi') }}
        </div>
        @endif
    </div>
</div>

{{-- ─── Modal Tambah / Edit Kursus ──────────────────────────── --}}
<div class="modal-overlay" id="modal-kursus">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3 id="modal-kursus-title">Tambah Kursus Baru</h3>
            <button onclick="closeModal('modal-kursus')" class="modal-close">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <form id="form-kursus" action="{{ route('lms.kursus.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                {{-- Nama --}}
                <div class="form-group mb-4">
                    <label class="form-label">Nama Kursus <span class="required">*</span></label>
                    <input type="text" name="nama_kursus" id="nama_kursus" class="form-control"
                           placeholder="Contoh: Matematika Peminatan X" required maxlength="100">
                </div>

                {{-- Kelas & Guru --}}
                <div class="form-group mb-4">
                    <label class="form-label">Kelas Peserta <span class="required">*</span></label>
                    <select name="id_kelas" id="kursus_id_kelas" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->rombel }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group mb-4">
                    <label class="form-label">Guru Pengampu <span class="required">*</span></label>
                    <select name="id_guru" id="kursus_id_guru" class="form-control" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($gurus as $g)
                            <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-kursus')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Kursus
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('form-kursus').action = "{{ route('lms.kursus.store') }}";
    document.getElementById('form-kursus').reset();
    document.getElementById('modal-kursus-title').textContent = 'Tambah Kursus Baru';
    openModal('modal-kursus');
}

function openEditModal(id, nama, idKelas, idGuru) {
    document.getElementById('form-kursus').action = `/lms/kursus/${id}`;
    document.getElementById('nama_kursus').value       = nama;
    document.getElementById('kursus_id_kelas').value   = idKelas;
    document.getElementById('kursus_id_guru').value    = idGuru;
    document.getElementById('modal-kursus-title').textContent = 'Edit Data Kursus';
    openModal('modal-kursus');
}

function toggleKursusCard(cardId) {
    const card = document.getElementById('kursus-card-' + cardId);
    const list = document.getElementById('tasks-list-' + cardId);
    const arrow = document.getElementById('arrow-' + cardId);
    if (!card || !list || !arrow) return;

    if (list.style.maxHeight === '0px' || list.style.maxHeight === '') {
        list.style.maxHeight = list.scrollHeight + 'px';
        arrow.style.transform = 'rotate(180deg)';
        card.classList.add('card-expanded');
    } else {
        list.style.maxHeight = '0px';
        arrow.style.transform = 'rotate(0deg)';
        card.classList.remove('card-expanded');
    }
}
</script>
@endsection
