@extends('layouts.app')

@section('title', 'Data Sekolah — SmartSchool')
@section('header_title', 'Data Sekolah')
@section('header_subtitle', 'Kelola informasi profil sekolah')

@section('content')
<div class="page-content">
    @include('partials.flash')

    @php $sekolah = $sekolah ?? null; @endphp

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-school"></i> Profil Sekolah</h2>
        </div>
        <div class="card-body">
            <form action="{{ $sekolah ? route('atur-data.sekolah.update', $sekolah->id_sekolah) : route('atur-data.sekolah.store') }}"
                  method="POST" enctype="multipart/form-data" id="form-sekolah">
                @csrf
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">NPSN <span class="required">*</span></label>
                        <input type="number" name="npsn" class="form-control @error('npsn') is-invalid @enderror"
                               value="{{ old('npsn', $sekolah->npsn ?? '') }}" required>
                        @error('npsn')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                            <option value="negeri" {{ old('status', $sekolah->status ?? '') === 'negeri' ? 'selected' : '' }}>Negeri</option>
                            <option value="swasta" {{ old('status', $sekolah->status ?? '') === 'swasta' ? 'selected' : '' }}>Swasta</option>
                        </select>
                        @error('status')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Nama Sekolah <span class="required">*</span></label>
                        <input type="text" name="nama_sekolah" class="form-control @error('nama_sekolah') is-invalid @enderror"
                               value="{{ old('nama_sekolah', $sekolah->nama_sekolah ?? '') }}" required>
                        @error('nama_sekolah')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kepala Sekolah <span class="required">*</span></label>
                        <input type="text" name="kepala_sekolah" class="form-control @error('kepala_sekolah') is-invalid @enderror"
                               value="{{ old('kepala_sekolah', $sekolah->kepala_sekolah ?? '') }}" required>
                        @error('kepala_sekolah')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">NBM Kepala Sekolah</label>
                        <input type="text" name="nip" class="form-control"
                               value="{{ old('nip', $sekolah->nip ?? '') }}">
                    </div>
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Alamat Sekolah</label>
                        <textarea name="alamat_sekolah" class="form-control" rows="3">{{ old('alamat_sekolah', $sekolah->alamat_sekolah ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kota / Kabupaten</label>
                        <input type="text" name="kota" class="form-control @error('kota') is-invalid @enderror"
                               value="{{ old('kota', $sekolah->kota ?? '') }}"
                               placeholder="Contoh: Yogyakarta">
                        <small class="form-hint" style="color: var(--text-muted, #888); font-size: 0.82em; margin-top: 4px; display: block;">
                            Digunakan pada tanda tangan surat panggilan orang tua.
                        </small>
                        @error('kota')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label class="form-label">Logo Sekolah</label>
                        <div class="dropzone-area" id="dropzone-logo">
                            <div class="dropzone-icon">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div class="dropzone-text">Drag & drop file logo di sini atau klik untuk memilih</div>
                            <input type="file" name="logo" id="file-logo" class="dropzone-input" accept=".jpg,.jpeg,.png" style="display:none">
                            
                            <div class="dropzone-preview" id="preview-logo" style="{{ ($sekolah && $sekolah->logo) ? 'display:flex;' : 'display:none;' }}">
                                <img src="{{ ($sekolah && $sekolah->logo) ? asset('storage/'.$sekolah->logo) : '#' }}" alt="Logo Preview">
                                <button type="button" class="btn-remove-preview" id="btn-remove-logo">&times;</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kop Surat</label>
                        <div class="dropzone-area" id="dropzone-kop">
                            <div class="dropzone-icon">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div class="dropzone-text">Drag & drop file kop di sini atau klik untuk memilih</div>
                            <input type="file" name="kop" id="file-kop" class="dropzone-input" accept=".jpg,.jpeg,.png" style="display:none">
                            
                            <div class="dropzone-preview" id="preview-kop" style="{{ ($sekolah && $sekolah->kop) ? 'display:flex;' : 'display:none;' }}">
                                <img src="{{ ($sekolah && $sekolah->kop) ? asset('storage/'.$sekolah->kop) : '#' }}" alt="Kop Preview">
                                <button type="button" class="btn-remove-preview" id="btn-remove-kop">&times;</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">TTD Kepala Sekolah</label>
                        <div class="dropzone-area" id="dropzone-ttd">
                            <div class="dropzone-icon">
                                <i class="fa-solid fa-cloud-arrow-up"></i>
                            </div>
                            <div class="dropzone-text">Drag & drop file TTD Kepala Sekolah di sini atau klik untuk memilih</div>
                            <input type="file" name="ttd_kepala_sekolah" id="file-ttd" class="dropzone-input" accept=".jpg,.jpeg,.png" style="display:none">
                            
                            <div class="dropzone-preview" id="preview-ttd" style="{{ ($sekolah && $sekolah->ttd_kepala_sekolah) ? 'display:flex;' : 'display:none;' }}">
                                <img src="{{ ($sekolah && $sekolah->ttd_kepala_sekolah) ? asset('storage/'.$sekolah->ttd_kepala_sekolah) : '#' }}" alt="TTD Preview">
                                <button type="button" class="btn-remove-preview" id="btn-remove-ttd">&times;</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="btn-simpan-sekolah">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    setupDropzone('dropzone-logo', 'file-logo', 'preview-logo', 'btn-remove-logo');
    setupDropzone('dropzone-kop', 'file-kop', 'preview-kop', 'btn-remove-kop');
    setupDropzone('dropzone-ttd', 'file-ttd', 'preview-ttd', 'btn-remove-ttd');

    function setupDropzone(areaId, inputId, previewId, removeId) {
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

        area.addEventListener('drop', handleDrop, false);

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

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
            reader.onloadend = function() {
                if (inputId === 'file-kop' || inputId === 'file-ttd') {
                    // Use actual size of the uploaded Kop / TTD file directly without cropping
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;
                    
                    previewImg.src = reader.result;
                    preview.style.display = 'flex';
                    
                    // Remove any delete marker input if we upload a new file
                    const oldMarker = area.querySelector(`input[name="delete_${input.name}"]`);
                    if (oldMarker) {
                        oldMarker.remove();
                    }
                    return;
                }

                let aspect = 1;
                openCropModal(reader.result, aspect, (croppedBlob) => {
                    // Create cropped file
                    const croppedFile = new File([croppedBlob], file.name, { type: 'image/jpeg' });
                    
                    const dt = new DataTransfer();
                    dt.items.add(croppedFile);
                    input.files = dt.files;
                    
                    previewImg.src = URL.createObjectURL(croppedBlob);
                    preview.style.display = 'flex';
                    
                    // Remove any delete marker input if we upload a new file
                    const oldMarker = area.querySelector(`input[name="delete_${input.name}"]`);
                    if (oldMarker) {
                        oldMarker.remove();
                    }
                }, () => {
                    if (!input.files.length) {
                        input.value = '';
                    }
                });
            };
            reader.readAsDataURL(file);
        }

        removeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            input.value = '';
            previewImg.src = '#';
            preview.style.display = 'none';
            
            // Create hidden input to signal backend to delete the file
            const fieldName = input.name;
            const deleteInput = document.createElement('input');
            deleteInput.type = 'hidden';
            deleteInput.name = `delete_${fieldName}`;
            deleteInput.value = '1';
            area.appendChild(deleteInput);
        });
    }
});
</script>
@endsection
