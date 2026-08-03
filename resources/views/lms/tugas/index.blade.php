@extends('layouts.app')

@section('title', 'Daftar Tugas — SmartSchool')
@section('header_title', 'Daftar Tugas')
@section('header_subtitle', 'Kelola tugas kelas dan instruksi KBM')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-graduation-cap"></i> Daftar Tugas</h2>
            <div class="card-header-right" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <form method="GET" class="search-form" style="display: flex; gap: 8px; align-items: center;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tugas..." class="form-control form-control-sm" style="width: 150px;">
                    
                    <select name="id_kelas" class="form-control form-control-sm" onchange="this.form.submit()" style="width: 140px;">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->tingkat }} {{ $k->rombel }}</option>
                        @endforeach
                    </select>

                    <select name="id_guru" class="form-control form-control-sm" onchange="this.form.submit()" style="width: 150px;">
                        <option value="">-- Semua Guru --</option>
                        @foreach($gurus as $g)
                            <option value="{{ $g->id_guru }}" {{ request('id_guru') == $g->id_guru ? 'selected' : '' }}>{{ $g->nama_guru }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-secondary btn-sm" type="submit"><i class="fa-solid fa-search"></i></button>
                    @if(request('search') || request('id_kelas') || request('id_guru'))
                        <a href="{{ route('lms.tugas.index') }}" class="btn btn-secondary btn-sm" title="Reset Filter"><i class="fa-solid fa-arrow-rotate-left"></i></a>
                    @endif
                </form>
                
                <a href="{{ route('lms.tugas.upload-kuis') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fa-solid fa-file-word"></i> Upload Kuis (.docx)
                </a>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()">
                    <i class="fa-solid fa-plus"></i> Tambah Tugas
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">#</th>
                        <th style="width: 120px;">Tenggat</th>
                        <th>Judul Tugas</th>
                        <th>Kelas</th>
                        <th>Guru Pengampu</th>
                        <th style="width: 100px; text-align: center;">Status</th>
                        <th style="width: 180px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tugasList as $tugas)
                    <tr>
                        <td style="text-align: center;">{{ $tugasList->firstItem() + $loop->index }}</td>
                        <td>{{ $tugas->tenggat ? \Carbon\Carbon::parse($tugas->tenggat)->translatedFormat('d M Y') : '-' }}</td>
                        <td>
                            <a href="{{ route('lms.tugas.show', $tugas->id_tugas) }}" style="color: var(--color-primary, #0d9488); font-weight: 600;">
                                {{ $tugas->judul }}
                            </a>
                        </td>
                        <td><span class="badge badge-info">{{ $tugas->kursus->kelas->tingkat ?? '-' }} {{ $tugas->kursus->kelas->rombel ?? '' }}</span></td>
                        <td><strong>{{ $tugas->kursus->guru->nama_guru ?? '-' }}</strong></td>
                        <td style="text-align: center;">
                            <span class="badge {{ $tugas->is_published ? 'badge-success' : 'badge-muted' }}">
                                {{ $tugas->is_published ? 'Aktif' : 'Draft' }}
                            </span>
                        </td>
                        <td class="action-cell" style="text-align: center;">
                            <a href="{{ route('lms.tugas.show', $tugas->id_tugas) }}" class="btn-icon" title="Detail & Status Siswa" style="color: var(--color-primary, #0d9488);">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="{{ route('lms.tagihan.index', ['id_tugas' => $tugas->id_tugas]) }}" class="btn-icon" title="Lihat Tagihan Pekerjaan" style="color: #eab308;">
                                <i class="fa-solid fa-list-check"></i>
                            </a>
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="openEditModal({{ $tugas->id_tugas }}, '{{ addslashes($tugas->judul) }}', {{ $tugas->kursus?->id_kelas ?? 'null' }}, {{ $tugas->kursus?->id_guru ?? 'null' }}, '{{ addslashes(str_replace(["\r", "\n"], ["", "\\n"], $tugas->deskripsi)) }}', '{{ $tugas->is_published ? 'aktif' : 'tidak' }}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('lms.tugas.destroy', $tugas->id_tugas) }}', 'Yakin ingin menghapus tugas ini? Semua lembar jawaban siswa terkait tugas ini juga akan terhapus secara permanen!')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-6">Belum ada data tugas kelas. Silakan klik Tambah Tugas atau buat melalui E-Admin Guru AI.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tugasList->hasPages())
        <div style="padding: 14px 20px; border-top: 1px solid var(--border-color);">
            {{ $tugasList->appends(request()->query())->links('pagination.presensi') }}
        </div>
        @endif
    </div>
</div>

{{-- Modal Tambah / Edit Tugas --}}
<div class="modal-overlay" id="modal-tugas">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-title">Tambah Tugas Baru</h3>
            <button onclick="closeModal('modal-tugas')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-tugas" action="{{ route('lms.tugas.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="alert alert-info mb-4" style="background: rgba(13, 148, 136, 0.08); border: 1px solid rgba(13, 148, 136, 0.2); color: #0f766e; border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
                        <div>
                            <strong><i class="fa-solid fa-lightbulb"></i> Ingin membuat Kuis Interaktif?</strong>
                            <p class="mb-0" style="font-size: 0.85rem;">Unggah kuis lengkap dengan gambar & pilihan ganda melalui file Template Word (.docx).</p>
                        </div>
                        <a href="{{ route('lms.tugas.upload-kuis') }}" class="btn btn-sm btn-primary" style="white-space: nowrap;">
                            <i class="fa-solid fa-file-word"></i> Buat Kuis Word
                        </a>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Jenis Konten <span class="required">*</span></label>
                    <select name="tipe_konten" id="tipe_konten" class="form-control" onchange="toggleContentTipe(this.value)">
                        <option value="tugas">Tugas / Instruksi Manual</option>
                        <option value="kuis">Kuis Online (Template Word)</option>
                    </select>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Judul Tugas <span class="required">*</span></label>
                    <input type="text" name="judul_tugas" id="judul_tugas" class="form-control" placeholder="Contoh: Tugas Mandiri Bab 1 Aljabar" required max="50">
                    <small class="text-muted">Maksimal 50 karakter.</small>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;" class="mb-4">
                    <div class="form-group">
                        <label class="form-label">Kelas Penerima Tugas <span class="required">*</span></label>
                        <select name="id_kelas" id="id_kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->rombel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Guru Pengampu <span class="required">*</span></label>
                        <select name="id_guru" id="id_guru" class="form-control" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $g)
                                <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Deskripsi Tugas / Soal-Soal <span class="required">*</span></label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="8" placeholder="Tuliskan petunjuk tugas dan soal di sini..." required style="resize: vertical; font-family: inherit; font-size: 0.9rem; line-height: 1.5;"></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Status Penayangan</label>
                    <select name="status" id="status" class="form-control" style="width: 200px;">
                        <option value="aktif">Aktif (Tayang Sekarang)</option>
                        <option value="tidak">Tidak Aktif (Draft)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-tugas')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Tugas</button>
            </div>
        </form>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('form-tugas').action = "{{ route('lms.tugas.store') }}";
    document.getElementById('form-tugas').reset();
    
    // Attempt to auto-select current teacher if user matches a teacher name
    const currentUserName = "{{ Auth::user()->nama_lengkap ?? '' }}";
    const guruSelect = document.getElementById('id_guru');
    for (let i = 0; i < guruSelect.options.length; i++) {
        if (guruSelect.options[i].text.toLowerCase().includes(currentUserName.toLowerCase())) {
            guruSelect.selectedIndex = i;
            break;
        }
    }

    document.getElementById('modal-title').textContent = 'Tambah Tugas Baru';
    openModal('modal-tugas');
}

function openEditModal(id, judul, idKelas, idGuru, deskripsi, status) {
    document.getElementById('form-tugas').action = `/lms/tugas/${id}`;
    document.getElementById('judul_tugas').value = judul;
    document.getElementById('id_kelas').value = idKelas;
    document.getElementById('id_guru').value = idGuru;
    document.getElementById('deskripsi').value = deskripsi;
    document.getElementById('status').value = status;

    document.getElementById('modal-title').textContent = 'Edit Tugas';
    openModal('modal-tugas');
}
</script>
@endsection
