@extends('layouts.app')

@section('title', 'Upload Kuis LMS — SmartSchool')
@section('header_title', 'Upload Kuis (Template Word)')
@section('header_subtitle', 'Impor kuis interaktif dengan gambar dan berbagai tipe soal dari file .docx')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card mb-4" style="border-top: 4px solid var(--color-primary, #0d9488);">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div>
                <h2 class="card-title"><i class="fa-solid fa-file-word text-primary"></i> Upload Kuis via Template Word</h2>
                <p class="text-muted mb-0" style="font-size: 0.85rem;">Unggah file .docx berformat tabel untuk mengimpor soal pilihan ganda, benar salah, dan pilihan ganda komplek secara otomatis.</p>
            </div>
            <div>
                <a href="{{ route('lms.tugas.download-template') }}" class="btn btn-outline-primary btn-md" style="display: inline-flex; align-items: center; gap: 8px; font-weight: 600;">
                    <i class="fa-solid fa-download" style="font-size: 1.1rem;"></i> Unduh Template Kuis (.docx)
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="alert alert-info" style="border-radius: 8px; background: rgba(13, 148, 136, 0.08); border: 1px solid rgba(13, 148, 136, 0.2); color: #0f766e;">
                <div style="display: flex; gap: 12px; align-items: flex-start;">
                    <i class="fa-solid fa-circle-info" style="font-size: 1.4rem; margin-top: 2px;"></i>
                    <div>
                        <strong>Petunjuk Pembuatan Kuis Word:</strong>
                        <ul class="mb-0 mt-1" style="padding-left: 20px; line-height: 1.6;">
                            <li>Gunakan <strong>Template Kuis (.docx)</strong> yang telah disediakan di atas.</li>
                            <li><strong>Pilihan Ganda</strong>: Kunci jawaban diisi huruf pilihan (contoh: <code>A</code>, <code>B</code>, <code>C</code>, <code>D</code>, atau <code>E</code>).</li>
                            <li><strong>Benar Salah</strong>: Kunci jawaban diisi <code>Benar</code> atau <code>Salah</code>.</li>
                            <li><strong>Pilihan Ganda Komplek</strong>: Kunci jawaban dapat diisi lebih dari satu dipisahkan koma (contoh: <code>A, C, E</code>).</li>
                            <li><strong>Gambar Soal & Jawaban</strong>: Anda dapat langsung menyisipkan (Copy-Paste / Insert Image) gambar di dalam sel tabel Word.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <form action="{{ route('lms.tugas.process-upload-kuis') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;" class="mb-4">
                    <div class="form-group">
                        <label class="form-label">Judul Kuis <span class="text-danger">*</span></label>
                        <input type="text" name="judul_tugas" class="form-control" placeholder="Contoh: Kuis Bab 3 Fisika Gelombang" required max="150" value="{{ old('judul_tugas') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tenggat Waktu Pengerjaan <span class="text-danger">*</span></label>
                        <input type="datetime-local" name="tenggat" class="form-control" required value="{{ old('tenggat', now()->addDays(7)->format('Y-m-d\TH:i')) }}">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;" class="mb-4">
                    <div class="form-group">
                        <label class="form-label">Kelas Penerima <span class="text-danger">*</span></label>
                        <select name="id_kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id_kelas }}" {{ old('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->tingkat }} {{ $k->rombel }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Guru Pengampu <span class="text-danger">*</span></label>
                        <select name="id_guru" class="form-control" required>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $g)
                                <option value="{{ $g->id_guru }}" {{ old('id_guru') == $g->id_guru ? 'selected' : '' }}>
                                    {{ $g->nama_guru }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status Penayangan <span class="text-danger">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif (Langsung Tayang)</option>
                            <option value="tidak" {{ old('status') == 'tidak' ? 'selected' : '' }}>Draft (Simpan Dahulu)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Instruksi / Catatan Kuis</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Contoh: Bacalah petunjuk pengerjaan dengan seksama sebelum menjawab..." style="resize: vertical;">{{ old('deskripsi', 'Kerjakan kuis online berikut dengan jujur dan teliti sebelum tenggat waktu berakhir.') }}</textarea>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Upload File Template Word (.docx) <span class="text-danger">*</span></label>
                    <div class="upload-dropzone p-4 text-center" style="border: 2px dashed var(--border-color, #cbd5e1); border-radius: 12px; background: var(--bg-surface, #f8fafc); cursor: pointer;" onclick="document.getElementById('file_word').click()">
                        <i class="fa-solid fa-cloud-arrow-up text-primary" style="font-size: 2.5rem; margin-bottom: 8px;"></i>
                        <h5 class="mb-1" style="font-weight: 600;">Klik atau seret file Word (.docx) ke sini</h5>
                        <p class="text-muted mb-2" style="font-size: 0.85rem;">Hanya mendukung format file Microsoft Word (.docx) maksimal 20 MB</p>
                        <span class="badge badge-info" id="file-name-display" style="font-size: 0.85rem; padding: 6px 14px; display: none;"></span>
                    </div>
                    <input type="file" name="file_word" id="file_word" accept=".docx" required style="display: none;" onchange="showFileName(this)">
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <a href="{{ route('lms.tugas.index') }}" class="btn btn-secondary">
                        <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Tugas
                    </a>
                    <button type="submit" class="btn btn-primary btn-lg" style="padding: 10px 24px; font-weight: 600;">
                        <i class="fa-solid fa-file-export"></i> Process & Simpan Kuis
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showFileName(input) {
    const display = document.getElementById('file-name-display');
    if (input.files && input.files[0]) {
        display.textContent = '📄 ' + input.files[0].name;
        display.style.display = 'inline-block';
    } else {
        display.style.display = 'none';
    }
}
</script>
@endsection
