@extends('layouts.app')

@section('title', 'Detail Tugas — SmartSchool')
@section('header_title', 'Detail Tugas')
@section('header_subtitle', 'Pantau detail instruksi tugas dan status pengerjaan siswa')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div style="margin-bottom: 20px;">
        <a href="{{ route('lms.tugas.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Tugas
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: start;">
        
        {{-- Detail Tugas Card --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-file-invoice"></i> Informasi Tugas</h2>
                <div class="card-header-right">
                    <span class="badge {{ $tugas->is_published ? 'badge-success' : 'badge-muted' }}">
                        {{ $tugas->is_published ? 'Aktif / Tayang' : 'Draft / Nonaktif' }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin: 0 0 10px 0;">
                    {{ $tugas->judul }}
                </h1>
                
                <div style="display: flex; gap: 20px; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 20px; border-bottom: 1.5px solid var(--border-color); padding-bottom: 12px; flex-wrap: wrap;">
                    <div><i class="fa-regular fa-calendar"></i> <strong>Tenggat:</strong> {{ $tugas->tenggat ? \Carbon\Carbon::parse($tugas->tenggat)->translatedFormat('d F Y') : '-' }}</div>
                    <div><i class="fa-solid fa-graduation-cap"></i> <strong>Kelas:</strong> {{ $tugas->kursus->kelas->tingkat ?? '-' }} {{ $tugas->kursus->kelas->rombel ?? '' }}</div>
                    <div><i class="fa-solid fa-user-tie"></i> <strong>Guru:</strong> {{ $tugas->kursus->guru->nama_guru ?? '-' }}</div>
                </div>

                <div class="tugas-deskripsi mb-3" style="font-size: 0.95rem; line-height: 1.6; color: var(--text-primary);">
                    {!! $tugas->deskripsi !!}
                </div>

                @if($tugas->tipe === 'kuis' && $tugas->soal->count() > 0)
                    <div class="mt-4 pt-3" style="border-top: 1.5px solid var(--border-color, #e2e8f0);">
                        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-list-check text-primary"></i> Daftar Soal Kuis ({{ $tugas->soal->count() }} Soal)
                        </h3>

                        <div class="soal-list">
                            @foreach($tugas->soal as $soal)
                                <div class="soal-item mb-4 p-3" style="background: var(--bg-surface, #f8fafc); border: 1px solid var(--border-color, #cbd5e1); border-radius: 10px;">
                                    {{-- Header soal: nomor, badge jenis, tombol aksi --}}
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; gap: 8px;">
                                        <div style="display: flex; align-items: center; gap: 10px;">
                                            <strong style="font-size: 1rem; color: var(--text-primary);">Soal No. {{ $soal->nomor_soal }}</strong>
                                            <span class="badge {{ $soal->jenis_soal === 'pilihan_ganda_komplek' ? 'badge-warning' : ($soal->jenis_soal === 'benar_salah' ? 'badge-info' : 'badge-primary') }}" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                                {{ str_replace('_', ' ', $soal->jenis_soal) }}
                                            </span>
                                        </div>
                                        <div style="display: flex; gap: 6px; flex-shrink: 0;">
                                            <button type="button" class="btn-icon btn-edit"
                                                title="Edit Soal No. {{ $soal->nomor_soal }}"
                                                onclick="openEditSoalModal({{ $soal->id_soal }}, {{ $soal->nomor_soal }}, {{ json_encode($soal->jenis_soal) }}, {{ json_encode($soal->pertanyaan) }}, {{ json_encode($soal->kunci_jawaban) }}, {{ $soal->pilihan->toJson() }})">
                                                <i class="fa-solid fa-pen" style="font-size: 0.8rem;"></i>
                                            </button>
                                            <button type="button" class="btn-icon btn-delete"
                                                title="Hapus Soal No. {{ $soal->nomor_soal }}"
                                                onclick="openDeleteSoalModal({{ $soal->id_soal }}, {{ $soal->nomor_soal }})">
                                                <i class="fa-solid fa-trash" style="font-size: 0.8rem;"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <div class="pertanyaan-body mb-3" style="font-size: 0.95rem; line-height: 1.5;">
                                        {!! $soal->pertanyaan !!}
                                    </div>

                                    @if($soal->gambar)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $soal->gambar) }}" class="img-fluid rounded" style="max-height: 250px;">
                                        </div>
                                    @endif

                                    <div class="pilihan-list" style="display: grid; gap: 8px;">
                                        @foreach($soal->pilihan as $p)
                                            <div class="pilihan-item p-2 rounded" style="display: flex; align-items: center; gap: 10px; font-size: 0.9rem; background: {{ $p->is_kunci ? 'rgba(34, 197, 94, 0.12)' : 'white' }}; border: 1px solid {{ $p->is_kunci ? '#22c55e' : '#e2e8f0' }};">
                                                <span class="badge" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; background: {{ $p->is_kunci ? '#22c55e' : '#64748b' }}; color: white; font-weight: bold; border-radius: 50%;">
                                                    {{ $p->kunci }}
                                                </span>
                                                <div style="flex: 1;">
                                                    {!! $p->teks !!}
                                                </div>
                                                @if($p->is_kunci)
                                                    <span class="text-success" style="font-size: 0.8rem; font-weight: bold;"><i class="fa-solid fa-check"></i> Kunci</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if($tugas->file_path)
                    <div style="margin-top: 20px; padding: 12px 16px; background: var(--bg-body, #f8fafc); border-radius: 8px; display: inline-flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-paperclip" style="color: var(--color-primary, #0d9488);"></i>
                        <span style="font-size: 0.88rem; font-weight: 600;">File Lampiran Word / PDF:</span>
                        <a href="{{ asset('storage/' . $tugas->file_path) }}" target="_blank" class="btn btn-primary btn-sm" style="padding: 4px 8px; font-size: 0.78rem;">
                            <i class="fa-solid fa-download"></i> Unduh File
                        </a>
                    </div>
                @endif

                {{-- Tombol Aksi Edit & Hapus --}}
                <div style="margin-top: 24px; padding-top: 16px; border-top: 1.5px solid var(--border-color); display: flex; gap: 10px; flex-wrap: wrap;">
                    <button type="button" class="btn btn-primary btn-sm" onclick="openEditModal()">
                        <i class="fa-solid fa-pen-to-square"></i> Edit Tugas
                    </button>
                    <button type="button" class="btn btn-sm" style="background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5;" onclick="openDeleteModal()">
                        <i class="fa-solid fa-trash"></i> Hapus Tugas
                    </button>
                </div>
            </div>
        </div>

        {{-- Status Penyelesaian Card --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-chart-simple"></i> Status Pengerjaan</h2>
            </div>
            <div class="card-body p-0">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th style="width: 110px; text-align: center;">Status</th>
                            <th style="width: 60px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tagihanList as $tagihan)
                        <tr>
                            <td>
                                <strong style="display: block; color: var(--text-primary); font-size: 0.88rem;">{{ $tagihan->nama_siswa }}</strong>
                                <small class="text-muted" style="font-size: 0.75rem;">NIS: {{ $tagihan->nis }}</small>
                            </td>
                            <td style="text-align: center;">
                                @if($tagihan->status_tugas === 'cek')
                                    <span class="badge badge-success" style="font-size: 0.72rem; padding: 3px 6px;"><i class="fa-solid fa-circle-check"></i> Selesai Dicek</span>
                                @elseif($tagihan->status_tugas === 'sudah')
                                    <span class="badge" style="font-size: 0.72rem; padding: 3px 6px; background: #eab308; color: white;"><i class="fa-solid fa-circle-info"></i> Butuh Cek</span>
                                @else
                                    <span class="badge badge-muted" style="font-size: 0.72rem; padding: 3px 6px;"><i class="fa-solid fa-clock"></i> Belum</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($tagihan->id_pengumpulan)
                                    <a href="{{ route('lms.tagihan.show', $tagihan->id_pengumpulan) }}" class="btn-icon" title="Periksa Jawaban" style="color: var(--color-primary, #0d9488);">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size: 0.8rem;">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Belum ada siswa yang terdaftar di kelas ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

{{-- Modal Edit Tugas --}}
<div class="modal-overlay" id="modal-edit-tugas">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen-to-square"></i> Edit Tugas</h3>
            <button onclick="closeModal('modal-edit-tugas')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('lms.tugas.update', $tugas->id_tugas) }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group mb-4">
                    <label class="form-label">Judul Tugas <span class="required">*</span></label>
                    <input type="text" name="judul_tugas" class="form-control"
                        value="{{ old('judul_tugas', $tugas->judul) }}" required maxlength="150">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;" class="mb-4">
                    <div class="form-group">
                        <label class="form-label">Kelas Penerima <span class="required">*</span></label>
                        <select name="id_kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ $tugas->kursus?->id_kelas == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->tingkat }} {{ $k->rombel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Guru Pengampu <span class="required">*</span></label>
                        <select name="id_guru" class="form-control" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $g)
                                <option value="{{ $g->id_guru }}" {{ $tugas->kursus?->id_guru == $g->id_guru ? 'selected' : '' }}>
                                    {{ $g->nama_guru }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Tenggat Waktu <span class="required">*</span></label>
                    <input type="datetime-local" name="tenggat" class="form-control"
                        value="{{ old('tenggat', $tugas->tenggat ? \Carbon\Carbon::parse($tugas->tenggat)->format('Y-m-d\TH:i') : '') }}" required>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Deskripsi / Instruksi <span class="required">*</span></label>
                    <textarea name="deskripsi" class="form-control" rows="6"
                        required style="resize: vertical; font-family: inherit; font-size: 0.9rem; line-height: 1.5;">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                </div>

                <div class="form-group">
                    <label class="form-label">Status Penayangan</label>
                    <select name="status" class="form-control" style="width: 220px;">
                        <option value="aktif" {{ $tugas->is_published ? 'selected' : '' }}>Aktif (Langsung Tayang)</option>
                        <option value="tidak" {{ !$tugas->is_published ? 'selected' : '' }}>Tidak Aktif (Draft)</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-edit-tugas')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Konfirmasi Hapus --}}
<div class="modal-overlay" id="modal-hapus-tugas">
    <div class="modal" style="max-width: 440px;">
        <div class="modal-header" style="border-bottom: 1px solid #fee2e2;">
            <h3 style="color: #dc2626;"><i class="fa-solid fa-triangle-exclamation"></i> Konfirmasi Hapus</h3>
            <button onclick="closeModal('modal-hapus-tugas')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom: 8px; font-size: 0.95rem;">Anda akan menghapus tugas:</p>
            <p style="font-weight: 700; font-size: 1rem; color: var(--text-primary); margin-bottom: 12px;">
                "{{ $tugas->judul }}"
            </p>
            <div style="background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 12px; font-size: 0.88rem; color: #7f1d1d;">
                <i class="fa-solid fa-circle-exclamation"></i>
                <strong>Peringatan:</strong> Semua soal, pilihan jawaban, dan lembar jawaban siswa terkait tugas ini akan <strong>terhapus permanen</strong> dan tidak dapat dipulihkan.
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeModal('modal-hapus-tugas')" class="btn btn-secondary">Batal</button>
            <form action="{{ route('lms.tugas.destroy', $tugas->id_tugas) }}" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background: #dc2626; color: white; padding: 8px 18px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;">
                    <i class="fa-solid fa-trash"></i> Ya, Hapus Sekarang
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function openEditModal() {
    openModal('modal-edit-tugas');
}
function openDeleteModal() {
    openModal('modal-hapus-tugas');
}
</script>

{{-- ═══════════════════════════════════════════
     MODAL EDIT SOAL (dinamis via JavaScript)
═══════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-edit-soal">
    <div class="modal modal-lg" style="max-width: 720px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen-to-square"></i> Edit Soal No. <span id="edit_soal_nomor">–</span></h3>
            <button onclick="closeModal('modal-edit-soal')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-edit-soal" method="POST">
            @csrf

            <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">

                {{-- Jenis Soal --}}
                <div class="form-group mb-4">
                    <label class="form-label">Jenis Soal <span class="required">*</span></label>
                    <select name="jenis_soal" id="edit_jenis_soal" class="form-control" required
                        onchange="onJenisSoalChange(this.value)">
                        <option value="pilihan_ganda">Pilihan Ganda</option>
                        <option value="benar_salah">Benar / Salah</option>
                        <option value="pilihan_ganda_komplek">Pilihan Ganda Kompleks</option>
                    </select>
                </div>

                {{-- Pertanyaan (Rich Editor) --}}
                <div class="form-group mb-4">
                    <label class="form-label">Teks Pertanyaan <span class="required">*</span></label>

                    {{-- Toolbar --}}
                    <div class="editor-toolbar" id="editor-toolbar-pertanyaan" style="display:flex; gap:4px; flex-wrap:wrap; padding:6px 8px; background:#f8fafc; border:1.5px solid var(--border-color,#cbd5e1); border-bottom:none; border-radius:8px 8px 0 0; align-items:center;">
                        <button type="button" onclick="execFmt('bold')" title="Bold" class="editor-tool-btn"><b>B</b></button>
                        <button type="button" onclick="execFmt('italic')" title="Italic" class="editor-tool-btn"><i>I</i></button>
                        <button type="button" onclick="execFmt('underline')" title="Garis Bawah" class="editor-tool-btn"><u>U</u></button>
                        <span style="width:1px; height:20px; background:#e2e8f0; margin:0 4px;"></span>
                        <button type="button" onclick="execFmt('insertUnorderedList')" title="Daftar Poin" class="editor-tool-btn"><i class="fa-solid fa-list-ul" style="font-size:0.8rem;"></i></button>
                        <button type="button" onclick="execFmt('insertOrderedList')" title="Daftar Nomor" class="editor-tool-btn"><i class="fa-solid fa-list-ol" style="font-size:0.8rem;"></i></button>
                        <span style="width:1px; height:20px; background:#e2e8f0; margin:0 4px;"></span>
                        <label class="editor-tool-btn" title="Upload Gambar" style="cursor:pointer; margin:0;">
                            <i class="fa-solid fa-image" style="font-size:0.8rem;"></i>
                            <input type="file" accept="image/*" style="display:none;" onchange="uploadImageFromFile(this, 'edit_pertanyaan_editor')">
                        </label>
                        <span style="font-size:0.75rem; color:#94a3b8; margin-left:4px;">atau Ctrl+V untuk paste gambar</span>
                    </div>

                    {{-- Editor contenteditable --}}
                    <div id="edit_pertanyaan_editor"
                        class="soal-rich-editor"
                        contenteditable="true"
                        data-placeholder="Ketik pertanyaan di sini, atau paste gambar langsung..."
                        style="border:1.5px solid var(--border-color,#cbd5e1); border-top:none; border-radius:0 0 8px 8px; min-height:130px; max-height:320px; overflow-y:auto; padding:14px 16px; font-family:'Segoe UI',sans-serif; font-size:0.95rem; line-height:1.7; background:white; outline:none; color:var(--text-primary);"
                    ></div>

                    {{-- Hidden input untuk submit --}}
                    <input type="hidden" name="pertanyaan" id="edit_pertanyaan_hidden">
                    <small class="text-muted mt-1" style="display:block;"><i class="fa-solid fa-circle-info"></i> Gunakan toolbar atau <strong>Ctrl+V / Copy-Paste</strong> untuk menyisipkan gambar langsung.</small>
                </div>

                {{-- Kunci Jawaban --}}
                <div class="form-group mb-4">
                    <label class="form-label">Kunci Jawaban <span class="required">*</span></label>
                    <input type="text" name="kunci_jawaban" id="edit_kunci_jawaban" class="form-control"
                        required placeholder="Contoh: A atau A,C (pisah koma untuk kompleks)"
                        style="text-transform: uppercase;"
                        oninput="highlightKunci()">
                    <small class="text-muted">Untuk Pilihan Ganda Kompleks, pisahkan dengan koma. Contoh: <strong>A,C</strong></small>
                </div>

                {{-- Daftar Pilihan Jawaban --}}
                <div class="form-group mb-2">
                    <label class="form-label">Pilihan Jawaban <span class="required">*</span></label>
                    <div id="edit_pilihan_container" style="display: grid; gap: 8px;"></div>
                </div>
                <button type="button" class="btn btn-secondary btn-sm mt-2" onclick="addPilihanRow()" id="btn-add-pilihan">
                    <i class="fa-solid fa-plus"></i> Tambah Pilihan
                </button>

            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-edit-soal')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Soal</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════════════════════════════════════════
     MODAL HAPUS SOAL
═══════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-hapus-soal">
    <div class="modal" style="max-width: 430px;">
        <div class="modal-header" style="border-bottom: 1px solid #fee2e2;">
            <h3 style="color: #dc2626;"><i class="fa-solid fa-triangle-exclamation"></i> Hapus Soal</h3>
            <button onclick="closeModal('modal-hapus-soal')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <p style="font-size: 0.95rem; margin-bottom: 12px;">
                Anda akan menghapus <strong>Soal No. <span id="hapus_soal_nomor">–</span></strong>.
            </p>
            <div style="background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px; padding: 12px; font-size: 0.87rem; color: #7f1d1d;">
                <i class="fa-solid fa-circle-exclamation"></i>
                <strong>Peringatan:</strong> Soal dan semua pilihan jawabannya akan <strong>terhapus permanen</strong>.
                Nomor soal yang tersisa akan diperbarui otomatis.
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeModal('modal-hapus-soal')" class="btn btn-secondary">Batal</button>
            <form id="form-hapus-soal" method="POST" style="display: inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm" style="background: #dc2626; color: white; padding: 8px 18px; border-radius: 6px; font-weight: 600; border: none; cursor: pointer;">
                    <i class="fa-solid fa-trash"></i> Ya, Hapus
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* ── Rich Editor Styles ── */
.soal-rich-editor:empty:before {
    content: attr(data-placeholder);
    color: #94a3b8;
    pointer-events: none;
}
.soal-rich-editor:focus {
    border-color: var(--color-primary, #0d9488) !important;
    box-shadow: 0 0 0 3px rgba(13,148,136,0.12);
}
.soal-rich-editor img {
    max-height: 250px;
    max-width: 100%;
    border-radius: 6px;
    display: block;
    margin: 8px 0;
}
.editor-tool-btn {
    background: white;
    border: 1px solid #e2e8f0;
    border-radius: 5px;
    padding: 3px 9px;
    font-size: 0.85rem;
    cursor: pointer;
    color: #334155;
    transition: all 0.15s;
    line-height: 1.4;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}
.editor-tool-btn:hover {
    background: #f0fdf4;
    border-color: var(--color-primary, #0d9488);
    color: var(--color-primary, #0d9488);
}
.pilihan-teks-editor {
    flex: 1;
    min-height: 38px;
    max-height: 100px;
    overflow-y: auto;
    padding: 7px 10px;
    font-size: 0.9rem;
    line-height: 1.5;
    border: 1.5px solid var(--border-color, #cbd5e1);
    border-radius: 7px;
    background: white;
    outline: none;
    font-family: inherit;
}
.pilihan-teks-editor:empty:before {
    content: attr(data-placeholder);
    color: #94a3b8;
    pointer-events: none;
}
.pilihan-teks-editor:focus {
    border-color: var(--color-primary, #0d9488);
    box-shadow: 0 0 0 2px rgba(13,148,136,0.1);
}
.pilihan-teks-editor img {
    max-height: 80px;
    max-width: 100%;
    border-radius: 4px;
    display: inline-block;
    margin: 2px 4px;
}
.img-upload-loading {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #94a3b8;
    font-size: 0.85rem;
    padding: 4px 8px;
    background: #f8fafc;
    border-radius: 4px;
    border: 1px dashed #cbd5e1;
}
</style>

<script>
/* ════════════════════════════════════════════════════
   RICH EDITOR — Edit Soal Kuis
   - Contenteditable untuk pertanyaan & pilihan
   - Paste gambar → upload via AJAX → insert <img>
   - Toolbar: Bold, Italic, Underline, List, Upload
════════════════════════════════════════════════════ */

const CSRF_TOKEN   = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';
const IMG_UPLOAD_URL = '{{ route("lms.soal.upload-image") }}';

// ── Buka modal Edit Soal ──────────────────────────────
function openEditSoalModal(idSoal, nomor, jenisSoal, pertanyaan, kunciJawaban, pilihanJson) {
    const pilihan = typeof pilihanJson === 'string' ? JSON.parse(pilihanJson) : pilihanJson;

    document.getElementById('edit_soal_nomor').textContent = nomor;
    document.getElementById('form-edit-soal').action = `/lms/soal/${idSoal}`;
    document.getElementById('edit_jenis_soal').value = jenisSoal;
    document.getElementById('edit_kunci_jawaban').value = kunciJawaban;

    // Set rich editor pertanyaan (innerHTML agar HTML ter-render)
    const pertEditor = document.getElementById('edit_pertanyaan_editor');
    pertEditor.innerHTML = pertanyaan || '';
    addPasteImageHandler(pertEditor);
    addFocusHandler(pertEditor);

    renderPilihanRows(pilihan);
    onJenisSoalChange(jenisSoal);
    openModal('modal-edit-soal');
}

// ── Buka modal Hapus Soal ─────────────────────────────
function openDeleteSoalModal(idSoal, nomor) {
    document.getElementById('hapus_soal_nomor').textContent = nomor;
    document.getElementById('form-hapus-soal').action = `/lms/soal/${idSoal}`;
    openModal('modal-hapus-soal');
}

// ── Intercept form submit → sync editor → hidden inputs ──
document.getElementById('form-edit-soal').addEventListener('submit', function (e) {
    // Sync pertanyaan
    const pertEditor = document.getElementById('edit_pertanyaan_editor');
    document.getElementById('edit_pertanyaan_hidden').value = pertEditor.innerHTML;

    // Sync pilihan teks
    document.querySelectorAll('#edit_pilihan_container .pilihan-row').forEach(row => {
        const editor = row.querySelector('.pilihan-teks-editor');
        const hidden = row.querySelector('input.pilihan-teks-hidden');
        if (editor && hidden) hidden.value = editor.innerHTML;
    });

    // Validasi tidak kosong
    if (!pertEditor.textContent.trim() && !pertEditor.querySelector('img')) {
        e.preventDefault();
        pertEditor.focus();
        pertEditor.style.borderColor = '#ef4444';
        return;
    }
});

// ── Toolbar: execCommand wrapper ─────────────────────
function execFmt(cmd) {
    document.getElementById('edit_pertanyaan_editor').focus();
    document.execCommand(cmd, false, null);
}

// ── Upload gambar dari file input ─────────────────────
function uploadImageFromFile(input, editorId) {
    const file = input.files[0];
    if (!file) return;
    const editor = document.getElementById(editorId);
    editor.focus();
    uploadImageFile(file, editor);
    input.value = '';
}

// ── Paste image handler (dipasang ke tiap contenteditable) ──
function addPasteImageHandler(editorEl) {
    // Cegah duplikasi listener
    if (editorEl._pasteHandlerAdded) return;
    editorEl._pasteHandlerAdded = true;

    editorEl.addEventListener('paste', async function (e) {
        const items = Array.from(e.clipboardData?.items || []);
        const imgItem = items.find(it => it.type.startsWith('image/'));
        if (!imgItem) return; // Biarkan paste teks biasa berjalan normal

        e.preventDefault();
        const file = imgItem.getAsFile();
        uploadImageFile(file, editorEl);
    });
}

// ── Upload file gambar → insert ke editor ─────────────
async function uploadImageFile(file, editorEl) {
    editorEl.focus();

    // Tampilkan loading placeholder
    const loadId = 'img-load-' + Date.now();
    document.execCommand('insertHTML', false,
        `<span id="${loadId}" class="img-upload-loading"><i class="fa-solid fa-spinner fa-spin"></i> Mengunggah gambar...</span>`);

    try {
        const fd = new FormData();
        fd.append('image', file);
        fd.append('_token', CSRF_TOKEN);

        const resp = await fetch(IMG_UPLOAD_URL, { method: 'POST', body: fd });
        if (!resp.ok) throw new Error('Upload gagal');
        const data = await resp.json();

        const placeholder = document.getElementById(loadId);
        if (placeholder) {
            placeholder.outerHTML = `<img src="${data.url}" class="img-fluid rounded my-2" style="max-height:250px; display:block;" alt="gambar soal" /><br>`;
        }
    } catch (err) {
        const placeholder = document.getElementById(loadId);
        if (placeholder) placeholder.outerHTML = '<span style="color:#ef4444;font-size:0.85rem;"><i class="fa-solid fa-circle-exclamation"></i> Gagal mengunggah gambar</span>';
        console.error('Upload image error:', err);
    }
}

// ── Focus border style ────────────────────────────────
function addFocusHandler(el) {
    el.addEventListener('focus', () => el.style.borderColor = 'var(--color-primary, #0d9488)');
    el.addEventListener('blur',  () => el.style.borderColor = 'var(--border-color, #cbd5e1)');
}

// ── Render semua baris pilihan ────────────────────────
function renderPilihanRows(pilihanArr) {
    const container = document.getElementById('edit_pilihan_container');
    container.innerHTML = '';
    pilihanArr.forEach((p, idx) => {
        container.appendChild(buildPilihanRow(idx, p.kunci, p.teks));
    });
    highlightKunci();
}

// ── Buat satu baris pilihan (dengan contenteditable) ──
function buildPilihanRow(idx, kunci, teks) {
    const row = document.createElement('div');
    row.className = 'pilihan-row';
    row.dataset.idx = idx;
    row.style.cssText = 'display:flex; align-items:stretch; gap:8px; margin-bottom:4px;';

    // Kunci input
    const kunciInp = document.createElement('input');
    kunciInp.type = 'text';
    kunciInp.name = `pilihan[${idx}][kunci]`;
    kunciInp.value = kunci;
    kunciInp.className = 'form-control pilihan-kunci-input';
    kunciInp.style.cssText = 'width:50px; flex-shrink:0; text-transform:uppercase; font-weight:700; text-align:center; align-self:center; height:auto;';
    kunciInp.maxLength = 5;
    kunciInp.placeholder = 'A';
    kunciInp.required = true;
    kunciInp.addEventListener('input', highlightKunci);

    // Teks editor (contenteditable — render HTML)
    const teksEd = document.createElement('div');
    teksEd.className = 'pilihan-teks-editor';
    teksEd.contentEditable = 'true';
    teksEd.innerHTML = teks || '';
    teksEd.dataset.placeholder = 'Teks pilihan jawaban...';
    addPasteImageHandler(teksEd);
    addFocusHandler(teksEd);

    // Hidden input untuk nilai teks (disync saat submit)
    const teksHidden = document.createElement('input');
    teksHidden.type = 'hidden';
    teksHidden.name = `pilihan[${idx}][teks]`;
    teksHidden.className = 'pilihan-teks-hidden';
    teksHidden.value = teks || '';

    // Tombol hapus
    const delBtn = document.createElement('button');
    delBtn.type = 'button';
    delBtn.innerHTML = '<i class="fa-solid fa-circle-minus"></i>';
    delBtn.style.cssText = 'background:none; border:none; color:#ef4444; cursor:pointer; padding:4px 2px; font-size:1.1rem; flex-shrink:0; align-self:center;';
    delBtn.title = 'Hapus pilihan ini';
    delBtn.addEventListener('click', () => removePilihanRow(delBtn));

    row.appendChild(kunciInp);
    row.appendChild(teksEd);
    row.appendChild(teksHidden);
    row.appendChild(delBtn);
    return row;
}

// ── Tambah baris pilihan baru ─────────────────────────
function addPilihanRow() {
    const container = document.getElementById('edit_pilihan_container');
    const rows = container.querySelectorAll('.pilihan-row');
    const nextIdx = rows.length;
    const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const nextKunci = letters[nextIdx] ?? (nextIdx + 1).toString();
    container.appendChild(buildPilihanRow(nextIdx, nextKunci, ''));
    reindexPilihanRows();
}

// ── Hapus baris pilihan ───────────────────────────────
function removePilihanRow(btn) {
    const row = btn.closest('.pilihan-row');
    const container = document.getElementById('edit_pilihan_container');
    if (container.querySelectorAll('.pilihan-row').length <= 2) {
        alert('Minimal harus ada 2 pilihan jawaban.');
        return;
    }
    row.remove();
    reindexPilihanRows();
    highlightKunci();
}

// ── Re-index name attributes ──────────────────────────
function reindexPilihanRows() {
    const container = document.getElementById('edit_pilihan_container');
    container.querySelectorAll('.pilihan-row').forEach((row, idx) => {
        row.dataset.idx = idx;
        row.querySelectorAll('input[name]').forEach(inp => {
            inp.name = inp.name.replace(/pilihan\[\d+\]/, `pilihan[${idx}]`);
        });
    });
}

// ── Highlight baris pilihan sesuai kunci ──────────────
function highlightKunci() {
    const kunciRaw = (document.getElementById('edit_kunci_jawaban')?.value || '').toUpperCase();
    const kunciList = kunciRaw.split(',').map(k => k.trim()).filter(k => k);
    const container = document.getElementById('edit_pilihan_container');
    if (!container) return;
    container.querySelectorAll('.pilihan-row').forEach(row => {
        const kunciVal = row.querySelector('.pilihan-kunci-input')?.value.trim().toUpperCase() || '';
        const isKunci  = kunciList.includes(kunciVal);
        row.style.background    = isKunci ? 'rgba(34, 197, 94, 0.07)' : '';
        row.style.borderRadius  = isKunci ? '8px' : '';
        row.style.padding       = isKunci ? '4px 6px' : '2px 0';
        row.style.border        = isKunci ? '1.5px solid #22c55e' : '1.5px solid transparent';
    });
}

// ── Toggle btn tambah pilihan (benar/salah = max 2) ───
function onJenisSoalChange(value) {
    const btnAdd = document.getElementById('btn-add-pilihan');
    if (!btnAdd) return;
    if (value === 'benar_salah') {
        btnAdd.style.display = 'none';
        const container = document.getElementById('edit_pilihan_container');
        if (container) {
            container.querySelectorAll('.pilihan-row').forEach((row, idx) => { if (idx >= 2) row.remove(); });
        }
    } else {
        btnAdd.style.display = '';
    }
}

// ── Helper: escape HTML attr ──────────────────────────
function escHtml(str) {
    if (str == null) return '';
    return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#039;');
}
</script>
@endsection
