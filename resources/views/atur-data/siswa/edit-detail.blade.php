@extends('layouts.app')

@section('title', 'Edit Detail Siswa — SmartSchool')
@section('header_title', 'Edit Detail Siswa')
@section('header_subtitle', 'Perbarui data lengkap siswa')

@section('content')
<style>
/* ─── Hero Bar ─── */
.edit-hero {
    background: linear-gradient(135deg, #0d9488 0%, #6366f1 100%);
    border-radius: var(--radius-card) var(--radius-card) 0 0;
    padding: 24px 28px;
    display: flex; align-items: center; gap: 18px; color: #fff;
}
.edit-hero-avatar {
    width: 56px; height: 56px; border-radius: 14px;
    background: rgba(255,255,255,0.2); border: 2px solid rgba(255,255,255,0.4);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem; font-weight: 800; color: #fff; flex-shrink: 0;
}
.edit-hero-name { font-size: 1.1rem; font-weight: 800; margin-bottom: 2px; }
.edit-hero-sub  { font-size: 0.8rem; opacity: 0.8; }
.edit-hero-actions { margin-left:auto; }

/* ─── Section header ─── */
.edit-section-title {
    font-size: 0.78rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.7px;
    color: var(--color-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;
    padding-bottom: 8px; border-bottom: 1.5px solid rgba(13,148,136,0.12);
}

/* ─── Form Grid ─── */
.form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }
.form-row-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
@media(max-width:768px) {
    .form-row-3 { grid-template-columns: 1fr 1fr; }
    .form-row-2 { grid-template-columns: 1fr; }
    .edit-hero { flex-wrap: wrap; }
    .edit-hero-actions { margin-left: 0; }
}
@media(max-width:500px) {
    .form-row-3 { grid-template-columns: 1fr; }
}

/* ─── Section block ─── */
.edit-section { margin-bottom: 28px; }

/* ─── Parent icon ─── */
.parent-icon {
    width: 36px; height: 36px; border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}
</style>

<div class="page-content">
    @include('partials.flash')

    <div class="card" style="overflow:visible;">
        {{-- Hero --}}
        <div class="edit-hero">
            <div class="edit-hero-avatar">{{ strtoupper(substr($siswa->nama_siswa, 0, 1)) }}</div>
            <div>
                <div class="edit-hero-name">{{ $siswa->nama_siswa }}</div>
                <div class="edit-hero-sub">NIS: {{ $siswa->nis }} &bull; {{ $siswa->kelas ? $siswa->kelas->tingkat.' '.$siswa->kelas->rombel : 'Tanpa Kelas' }}</div>
            </div>
            <div class="edit-hero-actions">
                <a href="{{ route('atur-data.siswa.show', $siswa->nis) }}" class="btn btn-sm" style="background:rgba(255,255,255,0.2);color:#fff;border:1.5px solid rgba(255,255,255,0.4);">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Detail
                </a>
            </div>
        </div>

        {{-- Form --}}
        <form action="{{ route('atur-data.siswa.update-detail', $siswa->nis) }}" method="POST" enctype="multipart/form-data" id="form-edit-siswa">
            @csrf
            <div class="card-body" style="padding:28px;">

                {{-- ─ Foto Siswa ─ --}}
                <div class="edit-section">
                    <div class="edit-section-title">
                        <i class="fa-solid fa-camera"></i> Foto Siswa
                    </div>
                    <div style="background:linear-gradient(135deg,rgba(13,148,136,0.06),rgba(13,148,136,0.02)); border:1.5px solid rgba(13,148,136,0.2); border-radius:12px; padding:20px 22px; margin-bottom:24px;">
                        <div style="display:flex; align-items:center; gap:20px; flex-wrap:wrap;">
                            <div class="dropzone-area" id="dropzone-foto" style="width:120px; height:120px; border-radius:18px; border:2px dashed #0d9488; display:flex; flex-direction:column; align-items:center; justify-content:center; cursor:pointer; position:relative; overflow:hidden; flex-shrink:0; background:#fff; min-height:auto; padding:0;">
                                <div class="dropzone-icon" style="font-size:1.5rem; color:#0d9488; margin-bottom:4px;">
                                    <i class="fa-solid fa-cloud-arrow-up"></i>
                                </div>
                                <div class="dropzone-text" style="font-size:0.65rem; text-align:center; color:var(--text-muted); padding:0 8px;">Upload Foto</div>
                                <input type="file" name="foto" id="file-foto" class="dropzone-input" accept=".jpg,.jpeg,.png" style="display:none">
                                
                                <div class="dropzone-preview" id="preview-foto" style="position:absolute; top:0; left:0; width:100%; height:100%; display:{{ ($siswa->detail && $siswa->detail->foto) ? 'flex' : 'none' }}; align-items:center; justify-content:center; background:#fff; z-index:5; padding:0;">
                                    <img src="{{ ($siswa->detail && $siswa->detail->foto) ? asset('storage/'.$siswa->detail->foto) : '#' }}" alt="Foto Preview" style="width:100%; height:100%; object-fit:cover; border-radius:0; max-height:none;">
                                    <button type="button" class="btn-remove-preview" id="btn-remove-foto" style="position:absolute; top:6px; right:6px; width:22px; height:22px; border-radius:50%; background:rgba(0,0,0,0.6); color:#fff; border:none; display:flex; align-items:center; justify-content:center; font-size:0.8rem; cursor:pointer; z-index:10;">&times;</button>
                                </div>
                            </div>
                            <div>
                                <h4 style="font-size:0.95rem; font-weight:700; color:#0d9488; margin-bottom:4px;">Foto Profil Siswa</h4>
                                <p style="font-size:0.75rem; color:var(--text-muted); line-height:1.4; max-width:400px; margin-bottom:0;">
                                    Format gambar yang diperbolehkan: JPG, JPEG, PNG. Ukuran maksimal 2MB. Gambar akan otomatis dipotong (crop) dalam rasio persegi 1:1.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ─ Data Tambahan Siswa ─ --}}
                <div class="edit-section">
                    <div class="edit-section-title">
                        <i class="fa-solid fa-id-card"></i> Data Tambahan Siswa
                    </div>
                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">Agama</label>
                            <select name="agama" class="form-control">
                                <option value="">-- Pilih Agama --</option>
                                @foreach(['Islam','Kristen','Katolik','Hindu','Buddha','Konghucu'] as $agama)
                                    <option value="{{ $agama }}" {{ old('agama', $siswa->detail->agama ?? '') == $agama ? 'selected' : '' }}>{{ $agama }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Golongan Darah</label>
                            <select name="golongan_darah" class="form-control">
                                <option value="">-- Pilih --</option>
                                @foreach(['A','B','AB','O','A+','A-','B+','B-','AB+','AB-','O+','O-'] as $gol)
                                    <option value="{{ $gol }}" {{ old('golongan_darah', $siswa->detail->golongan_darah ?? '') == $gol ? 'selected' : '' }}>{{ $gol }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group" style="grid-column: 1 / -1;">
                            {{-- placeholder so layout doesnt break when no full span on row-3 --}}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap siswa...">{{ old('alamat', $siswa->detail->alamat ?? '') }}</textarea>
                    </div>
                </div>

                {{-- ─ Data Ayah ─ --}}
                <div class="edit-section">
                    <div class="edit-section-title" style="color:#0d9488;">
                        <i class="fa-solid fa-person"></i> Data Ayah
                    </div>
                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">Nama Ayah</label>
                            <input type="text" name="nama_ayah" class="form-control" value="{{ old('nama_ayah', $siswa->detail->nama_ayah ?? '') }}" placeholder="Nama lengkap ayah">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pekerjaan Ayah</label>
                            <input type="text" name="pekerjaan_ayah" class="form-control" value="{{ old('pekerjaan_ayah', $siswa->detail->pekerjaan_ayah ?? '') }}" placeholder="Contoh: Wiraswasta">
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Telepon Ayah</label>
                            <input type="text" name="no_telp_ayah" class="form-control" value="{{ old('no_telp_ayah', $siswa->detail->no_telp_ayah ?? '') }}" placeholder="08xx-xxxx-xxxx">
                        </div>
                    </div>
                </div>

                {{-- ─ Data Ibu ─ --}}
                <div class="edit-section">
                    <div class="edit-section-title" style="color:#6366f1;">
                        <i class="fa-solid fa-person-dress"></i> Data Ibu
                    </div>
                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">Nama Ibu</label>
                            <input type="text" name="nama_ibu" class="form-control" value="{{ old('nama_ibu', $siswa->detail->nama_ibu ?? '') }}" placeholder="Nama lengkap ibu">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pekerjaan Ibu</label>
                            <input type="text" name="pekerjaan_ibu" class="form-control" value="{{ old('pekerjaan_ibu', $siswa->detail->pekerjaan_ibu ?? '') }}" placeholder="Contoh: Ibu Rumah Tangga">
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Telepon Ibu</label>
                            <input type="text" name="no_telp_ibu" class="form-control" value="{{ old('no_telp_ibu', $siswa->detail->no_telp_ibu ?? '') }}" placeholder="08xx-xxxx-xxxx">
                        </div>
                    </div>
                </div>

                {{-- ─ Data Wali ─ --}}
                <div class="edit-section">
                    <div class="edit-section-title" style="color:#8b5cf6;">
                        <i class="fa-solid fa-user-shield"></i> Data Wali <span style="font-weight:400;color:var(--text-muted);font-size:0.72rem;text-transform:none;letter-spacing:0;">(Opsional, jika berbeda dengan orang tua)</span>
                    </div>
                    <div class="form-row-3">
                        <div class="form-group">
                            <label class="form-label">Nama Wali</label>
                            <input type="text" name="nama_wali" class="form-control" value="{{ old('nama_wali', $siswa->detail->nama_wali ?? '') }}" placeholder="Nama lengkap wali">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Pekerjaan Wali</label>
                            <input type="text" name="pekerjaan_wali" class="form-control" value="{{ old('pekerjaan_wali', $siswa->detail->pekerjaan_wali ?? '') }}" placeholder="Pekerjaan wali">
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Telepon Wali</label>
                            <input type="text" name="no_telp_wali" class="form-control" value="{{ old('no_telp_wali', $siswa->detail->no_telp_wali ?? '') }}" placeholder="08xx-xxxx-xxxx">
                        </div>
                    </div>
                </div>

                {{-- ─ WhatsApp Presensi ─ --}}
                <div class="edit-section" style="margin-bottom:8px;">
                    <div class="edit-section-title" style="color:#10b981;">
                        <i class="fa-brands fa-whatsapp"></i> WhatsApp Penerima Presensi
                    </div>
                    <div style="background:linear-gradient(135deg,rgba(16,185,129,0.06),rgba(16,185,129,0.02)); border:1.5px solid rgba(16,185,129,0.2); border-radius:12px; padding:20px 22px;">
                        <div class="form-group" style="margin-bottom:0;">
                            <label class="form-label" style="color:#059669;">Nomor WhatsApp</label>
                            <div style="position:relative; max-width:400px;">
                                <span style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#25D366;font-size:1.1rem;"><i class="fa-brands fa-whatsapp"></i></span>
                                <input type="text" name="no_wa_presensi" class="form-control"
                                    style="padding-left:38px; border-color:rgba(16,185,129,0.3);"
                                    value="{{ old('no_wa_presensi', $siswa->detail->no_wa_presensi ?? '') }}"
                                    placeholder="Contoh: 081234567890 atau 6281234567890"
                                    id="input-wa-presensi">
                            </div>
                            <span class="form-hint" style="margin-top:8px;"><i class="fa-solid fa-circle-info"></i> Bisa menggunakan format nomor HP biasa (08xx...) atau format kode negara (628xx...). Nomor ini digunakan khusus untuk menerima notifikasi presensi otomatis.</span>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="card-footer" style="border-top: 1.5px solid #f1f5f9; background: #f8fafc; padding: 16px 28px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                <p style="font-size:0.8rem;color:var(--text-muted);margin:0;">
                    <i class="fa-solid fa-circle-info"></i> Semua perubahan akan langsung tersimpan ke database.
                </p>
                <div style="display:flex;gap:10px;">
                    <a href="{{ route('atur-data.siswa.show', $siswa->nis) }}" class="btn btn-secondary">
                        <i class="fa-solid fa-xmark"></i> Batal
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    setupDropzoneWithCrop('dropzone-foto', 'file-foto', 'preview-foto', 'btn-remove-foto');

    function setupDropzoneWithCrop(areaId, inputId, previewId, removeId) {
        const area = document.getElementById(areaId);
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        const previewImg = preview.querySelector('img');
        const removeBtn = document.getElementById(removeId);

        // Click to trigger input file selection
        area.addEventListener('click', function(e) {
            if (e.target !== removeBtn && !removeBtn.contains(e.target)) {
                input.click();
            }
        });

        // Drag & Drop event listeners
        ['dragenter', 'dragover'].forEach(eventName => {
            area.addEventListener(eventName, preventDefaults, false);
            area.addEventListener(eventName, () => area.classList.add('drag-over'), false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            area.addEventListener(eventName, preventDefaults, false);
            area.addEventListener(eventName, () => area.classList.remove('drag-over'), false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        area.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length) {
                handleFile(files[0]);
            }
        }

        input.addEventListener('change', function() {
            if (this.files.length) {
                handleFile(this.files[0]);
            }
        });

        function handleFile(file) {
            if (!file.type.match('image.*')) {
                alert('Silakan upload file gambar (PNG, JPG, JPEG)');
                return;
            }
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onloadend = function() {
                // Open global crop modal (ratio 1:1 for student avatar)
                openCropModal(reader.result, 1, (croppedBlob) => {
                    // Create a new File from the blob
                    const croppedFile = new File([croppedBlob], file.name, { type: 'image/jpeg' });
                    
                    // Set file input files
                    const dt = new DataTransfer();
                    dt.items.add(croppedFile);
                    input.files = dt.files;
                    
                    // Update preview image
                    previewImg.src = URL.createObjectURL(croppedBlob);
                    preview.style.display = 'flex';
                    
                    // Remove delete_foto marker if exists
                    const deleteMarker = area.querySelector('input[name="delete_foto"]');
                    if (deleteMarker) deleteMarker.remove();
                }, () => {
                    // Cancel handler: reset file input if no file was already selected
                    if (!input.files.length) {
                        input.value = '';
                    }
                });
            };
        }

        removeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            input.value = '';
            previewImg.src = '#';
            preview.style.display = 'none';
            
            // Create hidden input to signal backend to delete the file
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = 'delete_foto';
            deleteInput.value = '1';
            area.appendChild(deleteInput);
        });
    }
});
</script>
@endpush
