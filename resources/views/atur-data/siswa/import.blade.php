@extends('layouts.app')

@section('title', 'Upload Excel Siswa — SmartSchool')
@section('header_title', 'Upload Data Siswa')
@section('header_subtitle', 'Unggah file data siswa untuk kelas terpilih')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card" style="max-width: 700px; margin: 0 auto;">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="card-title">
                <i class="fa-solid fa-file-excel"></i> Upload Siswa: 
                <strong>Kelas {{ $kelas->tingkat }} {{ $kelas->rombel }}</strong>
            </h2>
            <span class="badge badge-info" style="font-size: 0.85rem; padding: 6px 12px; border-radius: 20px;">
                {{ $kelas->jurusan->kode_jurusan ?? '' }}
            </span>
        </div>
        
        <div class="card-body">
            <!-- Download Template Section -->
            <div style="background: #f8fafc; border-radius: 12px; padding: 20px; border: 1px solid #e2e8f0; margin-bottom: 24px; display: flex; align-items: center; justify-content: space-between; gap: 16px;">
                <div>
                    <h4 style="margin: 0 0 6px 0; color: #1e293b; font-weight: 600;">Unduh Template Excel</h4>
                    <p style="margin: 0; font-size: 0.85rem; color: #64748b; line-height: 1.4;">Gunakan file template resmi ini untuk menghindari kesalahan format pengunggahan data siswa kelas ini.</p>
                </div>
                <a href="{{ route('atur-data.siswa.import-template', ['id_kelas' => $kelas->id_kelas]) }}" class="btn btn-secondary btn-sm" style="flex-shrink: 0; white-space: nowrap; display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-download"></i> Download Template
                </a>
            </div>

            <!-- Rules / Guide -->
            <div style="margin-bottom: 24px;">
                <h4 style="margin: 0 0 10px 0; color: #1e293b; font-weight: 600; font-size: 0.95rem;">Panduan Pengisian:</h4>
                <ul style="margin: 0; padding-left: 20px; font-size: 0.85rem; color: #64748b; line-height: 1.6;">
                    <li>Kolom <strong style="color: #334155;">NIS</strong> wajib diisi dan harus berupa angka unik (belum terdaftar di sekolah).</li>
                    <li>Kolom <strong style="color: #334155;">Nama Siswa</strong> wajib diisi.</li>
                    <li>Kolom <strong style="color: #334155;">Jenis Kelamin (Jenkel)</strong> diisi dengan <strong style="color: #334155;">L</strong> untuk Laki-laki atau <strong style="color: #334155;">P</strong> untuk Perempuan.</li>
                    <li>Kolom <strong style="color: #334155;">Tempat Lahir</strong> dan <strong style="color: #334155;">Tanggal Lahir</strong> bersifat opsional. Format tanggal lahir di Excel: <strong style="color: #334155;">YYYY-MM-DD</strong> (contoh: 2008-12-31).</li>
                    <li>Password default siswa dan wali adalah NIS masing-masing siswa yang bersangkutan.</li>
                </ul>
            </div>

            <!-- Form Upload -->
            <form action="{{ route('atur-data.siswa.import-process') }}" method="POST" enctype="multipart/form-data" onsubmit="showLoading('Mengimpor Data Siswa', 'Sedang memproses file Excel, mohon tunggu...')">
                @csrf
                <input type="hidden" name="id_kelas" value="{{ $kelas->id_kelas }}">

                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label" style="font-weight: 600; margin-bottom: 8px;">Pilih File Excel <span class="required">*</span></label>
                    <div style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 30px 20px; text-align: center; background: #fafafa; cursor: pointer; position: relative;" onclick="document.getElementById('excel_file').click();" ondragover="event.preventDefault(); this.style.borderColor='var(--color-primary)';" ondragleave="this.style.borderColor='#cbd5e1';" ondrop="event.preventDefault(); document.getElementById('excel_file').files = event.dataTransfer.files; updateFileName(); this.style.borderColor='#cbd5e1';">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size: 3rem; color: #94a3b8; margin-bottom: 12px;"></i>
                        <p style="margin: 0 0 6px 0; font-size: 0.95rem; font-weight: 500; color: #334155;" id="file-label-text">Tarik & Lepas file di sini atau klik untuk mencari</p>
                        <p style="margin: 0; font-size: 0.8rem; color: #94a3b8;">Format didukung: .xlsx, .xls (Maks. 2MB)</p>
                        <input type="file" name="file" id="excel_file" accept=".xlsx,.xls" required style="display: none;" onchange="updateFileName()">
                    </div>
                </div>

                <div id="file-info-box" style="display: none; align-items: center; gap: 10px; margin-top: 14px; background: #f0fdf4; border: 1px solid #bbf7d0; padding: 10px 14px; border-radius: 8px;">
                    <i class="fa-solid fa-circle-check" style="color: #16a34a; font-size: 1.1rem;"></i>
                    <span id="file-name-display" style="font-size: 0.85rem; font-weight: 500; color: #166534;">NamaFile.xlsx</span>
                </div>
        </div>

        <div class="card-footer" style="display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
            <a href="{{ route('atur-data.siswa.import-pilih-kelas') }}" class="btn btn-secondary">
                <i class="fa-solid fa-arrow-left"></i> Ganti Kelas
            </a>
            <button type="submit" class="btn btn-primary" id="btn-upload-submit">
                <i class="fa-solid fa-upload"></i> Mulai Import
            </button>
        </div>
        </form>
    </div>
</div>

<script>
function updateFileName() {
    const fileInput = document.getElementById('excel_file');
    const labelText = document.getElementById('file-label-text');
    const infoBox = document.getElementById('file-info-box');
    const nameDisplay = document.getElementById('file-name-display');

    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        nameDisplay.textContent = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
        infoBox.style.display = 'flex';
        labelText.textContent = "File siap diunggah!";
    } else {
        infoBox.style.display = 'none';
        labelText.textContent = "Tarik & Lepas file di sini atau klik untuk mencari";
    }
}
</script>
@endsection
