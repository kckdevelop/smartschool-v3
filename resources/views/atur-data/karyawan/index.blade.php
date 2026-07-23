@extends('layouts.app')

@section('title', 'Data Karyawan — SmartSchool')
@section('header_title', 'Data Karyawan')
@section('header_subtitle', 'Kelola data karyawan untuk presensi pengajian dan kegiatan lainnya')

@push('styles')
<style>
/* Foto avatar di tabel */
.karyawan-avatar {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e5e7eb;
    transition: transform 0.2s;
}
.karyawan-avatar:hover { transform: scale(1.1); }

.avatar-initials {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: linear-gradient(135deg, #0ea5e9, #2563eb);
    color: #fff;
    font-size: 0.78rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* Modal foto preview */
#foto-preview-container {
    margin-top: 12px;
    display: none;
    text-align: center;
}
#foto-preview-container img {
    max-width: 160px;
    max-height: 160px;
    border-radius: 12px;
    object-fit: cover;
    border: 2px solid #e5e7eb;
    box-shadow: 0 4px 6px rgba(0,0,0,0.07);
}

.inline-toast {
    position: fixed;
    bottom: 24px;
    right: 24px;
    background: #0f172a;
    color: #ffffff;
    padding: 12px 20px;
    border-radius: 12px;
    font-size: 0.88rem;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
    z-index: 9999;
    transform: translateY(100px);
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.inline-toast.show {
    transform: translateY(0);
    opacity: 1;
}
.badge-pink {
    background-color: #fdf2f8;
    color: #db2777;
    border: 1px solid #fbcfe8;
}
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-id-badge"></i> Daftar Karyawan</h2>
            <div class="card-header-right">
                <form method="GET" class="search-form">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / No ID..." class="form-control form-control-sm" style="min-width:180px">
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="aktif"  {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
                        <option value="tidak"  {{ request('status')=='tidak'?'selected':'' }}>Non-Aktif</option>
                    </select>
                    <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                        @foreach([10, 20, 50, 100] as $opt)
                            <option value="{{ $opt }}" {{ (int)request('per_page', 20) === $opt ? 'selected' : '' }}>{{ $opt }} / hal</option>
                        @endforeach
                    </select>
                    <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-filter"></i></button>
                </form>
                <button type="button" class="btn btn-secondary btn-sm" onclick="openModal('modal-import')" id="btn-import-karyawan" style="display:inline-flex; align-items:center; gap:6px;">
                    <i class="fa-solid fa-file-excel"></i> Import Excel
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="openModal('modal-add')" id="btn-tambah-karyawan">
                    <i class="fa-solid fa-plus"></i> Tambah
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="btn-bulk-delete" style="display: none; background: #ef4444; border: none; font-weight: 600;" onclick="openBulkDeleteModal()">
                    <i class="fa-solid fa-trash-can"></i> Hapus Terpilih (<span id="bulk-select-count">0</span>)
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="data-table" id="karyawan-table">
                <thead>
                    <tr>
                        <th style="width: 40px; text-align: center;"><input type="checkbox" id="check-all" style="cursor: pointer;"></th>
                        <th>#</th><th>No ID</th><th>Foto</th><th>Nama Karyawan</th><th>L/P</th><th>Status</th><th style="text-align:center;">Petugas UKS</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($karyawanList as $i => $karyawan)
                    <tr id="karyawan-row-{{ $karyawan->id_karyawan }}">
                        <td style="text-align: center;"><input type="checkbox" name="ids[]" class="row-checkbox" value="{{ $karyawan->id_karyawan }}" style="cursor: pointer;"></td>
                        <td>{{ $karyawanList->firstItem() + $i }}</td>
                        <td class="font-mono">{{ $karyawan->no_id }}</td>
                        <td style="text-align:center;">
                            @if($karyawan->foto)
                                <img src="{{ asset('storage/'.$karyawan->foto) }}"
                                     alt="Foto {{ $karyawan->nama_karyawan }}"
                                     class="karyawan-avatar"
                                     id="foto-img-{{ $karyawan->id_karyawan }}"
                                     title="Klik untuk perbesar">
                            @else
                                <div class="avatar-initials" id="foto-img-{{ $karyawan->id_karyawan }}" title="Belum ada foto">
                                    {{ strtoupper(substr($karyawan->nama_karyawan, 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td><strong>{{ $karyawan->nama_karyawan }}</strong></td>
                        <td style="text-align: center;"><span class="badge {{ $karyawan->jenkel === 'L' ? 'badge-info' : 'badge-pink' }}">{{ $karyawan->jenkel }}</span></td>
                        <td><span class="badge {{ $karyawan->status === 'aktif' ? 'badge-success' : 'badge-muted' }}">{{ ucfirst($karyawan->status) }}</span></td>
                        <td style="text-align:center;">
                            @if($karyawan->petugas_uks === 'ya')
                                <span class="badge badge-success">Ya</span>
                            @else
                                <span class="badge badge-muted">Tidak</span>
                            @endif
                        </td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editKaryawan({{ $karyawan->id_karyawan }}, {{ $karyawan->no_id }}, '{{ addslashes($karyawan->nama_karyawan) }}', '{{ $karyawan->jenkel }}', '{{ $karyawan->status }}', '{{ $karyawan->petugas_uks }}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-warning" title="Reset Password"
                                onclick="resetPassword({{ $karyawan->id_karyawan }}, '{{ addslashes($karyawan->nama_karyawan) }}')">
                                <i class="fa-solid fa-key"></i>
                            </button>
                            <button type="button" class="btn-icon" title="Upload Foto"
                                style="background:#e0f2fe; color:#0284c7; border:none;"
                                onclick="openUploadFoto({{ $karyawan->id_karyawan }}, '{{ addslashes($karyawan->nama_karyawan) }}', '{{ $karyawan->foto ? asset('storage/'.$karyawan->foto) : '' }}')">
                                <i class="fa-solid fa-camera"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('atur-data.karyawan.destroy', $karyawan->id_karyawan) }}', 'Yakin hapus data karyawan ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-6">Belum ada data karyawan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $karyawanList->withQueryString()->links('pagination.presensi') }}
        </div>
    </div>
</div>

{{-- Form bulk delete berdiri sendiri di luar semua form lain --}}
<form id="bulk-delete-form" method="POST" action="{{ route('atur-data.karyawan.bulk-destroy') }}" style="display:none;">
    @csrf
    {{-- Hidden inputs ids[] akan di-inject via JavaScript --}}
</form>

{{-- Modal Import Karyawan --}}
<div class="modal-overlay" id="modal-import">
    <div class="modal">
        <div class="modal-header">
            <h3>Import Data Karyawan</h3>
            <button onclick="closeModal('modal-import')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('atur-data.karyawan.import-process') }}" method="POST" enctype="multipart/form-data" onsubmit="showLoading('Mengimpor Data Karyawan', 'Sedang memproses file Excel, mohon tunggu...')">
            @csrf
            <div class="modal-body">
                <div class="alert alert-info" style="display:flex; align-items:center; gap:12px; padding:14px 18px; border-radius:12px; margin-bottom:20px; font-size:0.9rem; font-weight:500; background-color:#f0fdfa; color:#0f766e; border:1px solid #ccfbf1;">
                    <i class="fa-solid fa-circle-info" style="font-size:1.2rem"></i>
                    <div>
                        Unduh template Excel terlebih dahulu, isi data karyawan (<strong>no_id</strong>, <strong>nama_karyawan</strong>), lalu unggah file di sini.<br>
                        <span style="font-size:0.82rem; color:#134e4a;">Password otomatis diisi sesuai No ID masing-masing karyawan.</span>
                    </div>
                </div>
                <div style="margin-bottom:20px;">
                    <a href="{{ route('atur-data.karyawan.import-template') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:8px;">
                        <i class="fa-solid fa-download"></i> Unduh Template Excel
                    </a>
                </div>
                <div class="form-group">
                    <label class="form-label">Pilih File Excel <span class="required">*</span></label>
                    <input type="file" name="file" class="form-control" accept=".xlsx" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-import')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Unggah &amp; Proses</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tambah/Edit Karyawan --}}
<div class="modal-overlay" id="modal-add">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modal-title">Tambah Karyawan</h3>
            <button onclick="closeModal('modal-add')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-karyawan" action="{{ route('atur-data.karyawan.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">No ID <span class="required">*</span></label>
                    <input type="number" name="no_id" id="k_no_id" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Karyawan <span class="required">*</span></label>
                    <input type="text" name="nama_karyawan" id="k_nama" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                    <select name="jenkel" id="k_jenkel" class="form-control" required>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="k_status" class="form-control">
                        <option value="aktif">Aktif</option>
                        <option value="tidak">Tidak Aktif</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Petugas UKS</label>
                    <select name="petugas_uks" id="k_petugas_uks" class="form-control">
                        <option value="tidak">Tidak</option>
                        <option value="ya">Ya</option>
                    </select>
                </div>
                <div class="form-group" id="password-group">
                    <label class="form-label">Password <span class="required">*</span></label>
                    <input type="text" name="password" class="form-control" placeholder="Min. 4 karakter">
                    <span class="form-hint">Kosongkan saat edit untuk tidak mengubah password</span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-add')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Reset Password --}}
<div class="modal-overlay" id="modal-reset">
    <div class="modal">
        <div class="modal-header">
            <h3>Reset Password Karyawan</h3>
            <button onclick="closeModal('modal-reset')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-reset" action="" method="POST">
            @csrf
            <div class="modal-body">
                <p class="text-muted mb-4">Reset password untuk: <strong id="reset-nama"></strong></p>
                <div class="form-group">
                    <label class="form-label">Password Baru <span class="required">*</span></label>
                    <input type="text" name="password" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-reset')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-warning"><i class="fa-solid fa-key"></i> Reset</button>
            </div>
        </form>
    </div>
</div>

<script>
let isEdit = false;

function editKaryawan(id, noId, nama, jenkel, status, petugasUks) {
    isEdit = true;
    document.getElementById('form-karyawan').action = `/atur-data/karyawan/${id}`;
    document.getElementById('k_no_id').value        = noId;
    document.getElementById('k_nama').value         = nama;
    document.getElementById('k_jenkel').value       = jenkel;
    document.getElementById('k_status').value       = status;
    document.getElementById('k_petugas_uks').value  = petugasUks || 'tidak';
    document.getElementById('password-group').style.display = 'none';
    document.getElementById('modal-title').textContent = 'Edit Karyawan';
    openModal('modal-add');
}

function resetPassword(id, nama) {
    document.getElementById('form-reset').action = `/atur-data/karyawan/${id}/reset-password`;
    document.getElementById('reset-nama').textContent = nama;
    openModal('modal-reset');
}

document.addEventListener('modalClosed', function(e) {
    if (e.detail.id === 'modal-add' && isEdit) {
        isEdit = false;
        document.getElementById('form-karyawan').action = '{{ route("atur-data.karyawan.store") }}';
        document.getElementById('form-karyawan').reset();
        document.getElementById('k_petugas_uks').value = 'tidak';
        document.getElementById('password-group').style.display = '';
        document.getElementById('modal-title').textContent = 'Tambah Karyawan';
    }
});

// ---- Bulk Delete Script Logic ----
document.addEventListener('DOMContentLoaded', function() {
    const checkAll = document.getElementById('check-all');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const btnBulkDelete = document.getElementById('btn-bulk-delete');
    const bulkSelectCount = document.getElementById('bulk-select-count');

    function updateBulkButton() {
        const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
        if (checkedCount > 0) {
            bulkSelectCount.textContent = checkedCount;
            btnBulkDelete.style.display = 'inline-flex';
        } else {
            btnBulkDelete.style.display = 'none';
        }
    }

    if (checkAll) {
        checkAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => { cb.checked = checkAll.checked; });
            updateBulkButton();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (!this.checked) {
                checkAll.checked = false;
            } else {
                const totalChecked = document.querySelectorAll('.row-checkbox:checked').length;
                if (totalChecked === rowCheckboxes.length) checkAll.checked = true;
            }
            updateBulkButton();
        });
    });

    const btnSubmit = document.getElementById('btn-submit-bulk-delete');
    if (btnSubmit) {
        btnSubmit.addEventListener('click', function() {
            const form = document.getElementById('bulk-delete-form');
            form.querySelectorAll('input[name="ids[]"][type="hidden"]').forEach(el => el.remove());
            document.querySelectorAll('.row-checkbox:checked').forEach(function(cb) {
                const hidden = document.createElement('input');
                hidden.type  = 'hidden';
                hidden.name  = 'ids[]';
                hidden.value = cb.value;
                form.appendChild(hidden);
            });
            form.submit();
        });
    }
});

function openBulkDeleteModal() {
    const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
    document.getElementById('bulk-confirm-count').textContent = checkedCount;
    openModal('modal-bulk-confirm');
}
</script>

{{-- Modal Konfirmasi Hapus Masal --}}
<div class="modal-overlay" id="modal-bulk-confirm">
    <div class="modal modal-md" style="max-width: 440px;">
        <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 18px 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="background: #fee2e2; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #ef4444; font-size: 1.1rem;"></i>
                </div>
                <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: var(--text-primary);">Hapus Masal Terpilih</h3>
            </div>
            <button onclick="closeModal('modal-bulk-confirm')" class="modal-close">&times;</button>
        </div>
        <div class="modal-body" style="padding: 20px 24px; text-align: center;">
            <p style="margin: 0 0 8px; font-size: 0.95rem; color: var(--text-primary);">Apakah Anda yakin ingin menghapus sebanyak</p>
            <p style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #ef4444;"><span id="bulk-confirm-count">0</span> Data Karyawan?</p>
            <p style="margin: 8px 0 0; font-size: 0.83rem; color: var(--text-secondary); line-height: 1.4;">Tindakan ini akan menghapus semua karyawan terpilih secara permanen dan tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer" style="padding: 14px 24px; display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" onclick="closeModal('modal-bulk-confirm')" class="btn btn-secondary" style="padding: 8px 18px; font-size: 0.88rem;">Batal</button>
            <button type="button" id="btn-submit-bulk-delete" class="btn" style="background: #ef4444; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-size: 0.88rem; font-weight: 600; display: flex; align-items: center; gap: 7px;">
                <i class="fa-solid fa-trash-can"></i> Ya, Hapus Semua
            </button>
        </div>
    </div>
</div>

{{-- Modal Upload Foto Karyawan --}}
<div class="modal-overlay" id="modal-upload-foto">
    <div class="modal" style="max-width: 420px;">
        <div class="modal-header">
            <h3 id="upload-foto-title">Upload Foto Karyawan</h3>
            <button onclick="closeModal('modal-upload-foto')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            {{-- Preview foto saat ini --}}
            <div id="current-foto-wrap" style="display:none; text-align:center; margin-bottom:16px;">
                <p style="font-size:0.82rem; color:var(--text-secondary); margin-bottom:8px;">Foto saat ini:</p>
                <img id="current-foto-preview" src="" alt="Foto saat ini"
                     style="width:90px; height:90px; border-radius:50%; object-fit:cover; border:2px solid #e5e7eb; box-shadow:0 2px 8px rgba(0,0,0,0.1);">
                <div style="margin-top:10px;">
                    <button type="button" id="btn-delete-foto"
                            style="background:#fee2e2; color:#ef4444; border:none; padding:6px 14px; border-radius:8px; font-size:0.82rem; font-weight:600; cursor:pointer; display:inline-flex; align-items:center; gap:6px;">
                        <i class="fa-solid fa-trash"></i> Hapus Foto
                    </button>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Pilih Foto Baru <span class="required">*</span></label>
                <input type="file" id="input-foto-karyawan" accept="image/jpg,image/jpeg,image/png,image/webp"
                       class="form-control" style="padding:8px;">
                <span class="form-hint">Format: JPG, PNG, WEBP. Maks. 2 MB.</span>
            </div>
            <div id="foto-preview-container">
                <img id="foto-new-preview" src="" alt="Preview">
                <p style="font-size:0.8rem; color:var(--text-secondary); margin-top:6px;">Preview foto baru</p>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeModal('modal-upload-foto')" class="btn btn-secondary">Batal</button>
            <button type="button" id="btn-save-foto" class="btn btn-primary">
                <i class="fa-solid fa-cloud-arrow-up"></i> Simpan Foto
            </button>
        </div>
    </div>
</div>

<script>
// ---- Upload Foto Karyawan ----
let currentUploadKaryawanId = null;

function openUploadFoto(id, nama, fotoUrl) {
    currentUploadKaryawanId = id;
    document.getElementById('upload-foto-title').textContent = 'Upload Foto — ' + nama;
    document.getElementById('input-foto-karyawan').value = '';
    document.getElementById('foto-preview-container').style.display = 'none';
    document.getElementById('foto-new-preview').src = '';

    const currentWrap = document.getElementById('current-foto-wrap');
    if (fotoUrl) {
        document.getElementById('current-foto-preview').src = fotoUrl;
        currentWrap.style.display = 'block';
    } else {
        currentWrap.style.display = 'none';
    }
    openModal('modal-upload-foto');
}

// Preview sebelum upload
document.getElementById('input-foto-karyawan').addEventListener('change', function() {
    const file = this.files[0];
    if (!file) return;
    const reader = new FileReader();
    reader.onload = (e) => {
        document.getElementById('foto-new-preview').src = e.target.result;
        document.getElementById('foto-preview-container').style.display = 'block';
    };
    reader.readAsDataURL(file);
});

// Simpan foto
document.getElementById('btn-save-foto').addEventListener('click', function() {
    const file = document.getElementById('input-foto-karyawan').files[0];
    if (!file) {
        alert('Pilih file foto terlebih dahulu.');
        return;
    }

    const btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengunggah...';

    const formData = new FormData();
    formData.append('foto', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

    fetch(`/atur-data/karyawan/${currentUploadKaryawanId}/upload-foto`, {
        method: 'POST',
        body: formData,
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Simpan Foto';
        if (res.success) {
            // Update tampilan avatar di tabel secara langsung
            const imgEl = document.getElementById('foto-img-' + currentUploadKaryawanId);
            if (imgEl) {
                const newImg = document.createElement('img');
                newImg.src = res.foto_url + '?t=' + Date.now();
                newImg.alt = 'Foto Karyawan';
                newImg.className = 'karyawan-avatar';
                newImg.id = 'foto-img-' + currentUploadKaryawanId;
                newImg.title = 'Klik untuk perbesar';
                imgEl.parentNode.replaceChild(newImg, imgEl);
            }
            // Update preview foto saat ini di modal
            document.getElementById('current-foto-preview').src = res.foto_url + '?t=' + Date.now();
            document.getElementById('current-foto-wrap').style.display = 'block';
            document.getElementById('input-foto-karyawan').value = '';
            document.getElementById('foto-preview-container').style.display = 'none';
            showInlineToast(res.message || 'Foto berhasil diunggah!');
        } else {
            alert(res.message || 'Gagal mengunggah foto.');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Simpan Foto';
        alert('Terjadi kesalahan jaringan. Silakan coba lagi.');
    });
});

// Hapus foto
document.getElementById('btn-delete-foto').addEventListener('click', function() {
    if (!confirm('Hapus foto karyawan ini?')) return;

    const btn = this;
    btn.disabled = true;

    fetch(`/atur-data/karyawan/${currentUploadKaryawanId}/foto`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        },
    })
    .then(r => r.json())
    .then(res => {
        btn.disabled = false;
        if (res.success) {
            // Ganti gambar di tabel dengan inisial
            const imgEl = document.getElementById('foto-img-' + currentUploadKaryawanId);
            if (imgEl) {
                const namaText = document.getElementById('upload-foto-title').textContent.replace('Upload Foto — ', '');
                const inisial = namaText.trim().charAt(0).toUpperCase();
                const div = document.createElement('div');
                div.className = 'avatar-initials';
                div.id = 'foto-img-' + currentUploadKaryawanId;
                div.title = 'Belum ada foto';
                div.textContent = inisial;
                imgEl.parentNode.replaceChild(div, imgEl);
            }
            document.getElementById('current-foto-wrap').style.display = 'none';
            showInlineToast(res.message || 'Foto berhasil dihapus!');
        } else {
            alert(res.message || 'Gagal menghapus foto.');
        }
    })
    .catch(() => {
        btn.disabled = false;
        alert('Terjadi kesalahan jaringan.');
    });
});

function showInlineToast(message) {
    let toast = document.getElementById('inline-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'inline-toast';
        toast.className = 'inline-toast';
        document.body.appendChild(toast);
    }
    toast.innerHTML = `<i class="fa-solid fa-circle-check" style="color: #10b981;"></i> <span>${message}</span>`;
    toast.classList.add('show');
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 3000);
}
</script>
@endsection
