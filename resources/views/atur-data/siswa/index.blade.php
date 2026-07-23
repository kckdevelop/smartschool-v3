@extends('layouts.app')

@section('title', 'Data Siswa — SmartSchool')
@section('header_title', 'Data Siswa')
@section('header_subtitle', 'Kelola data siswa aktif dan non-aktif')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- ─── Panel Akses Edit Detail Siswa (Aplikasi Mobile) ─── --}}
    <div class="card" style="margin-bottom: 16px;">
        <div class="card-body" style="padding: 16px 20px;">
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <div>
                    <h3 style="font-size: 0.95rem; font-weight: 700; margin: 0 0 4px 0; color: var(--text-color, #1e293b);">
                        <i class="fa-solid fa-mobile-screen-button" style="color: var(--primary, #3b82f6); margin-right: 6px;"></i>
                        Akses Edit Profil Siswa (Aplikasi Mobile)
                    </h3>
                    <p style="font-size: 0.82rem; color: var(--text-muted, #64748b); margin: 0;">
                        Atur apakah siswa diizinkan mengedit detail pribadi, orang tua, & koordinat alamat di aplikasi mobile.
                    </p>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <span style="font-size: 0.82rem; font-weight: 700; color: {{ ($sekolah?->edit_detail_siswa) ? '#16a34a' : '#dc2626' }};">
                        @if($sekolah?->edit_detail_siswa)
                            <span class="badge badge-success" style="background-color: #10b981; color: white; padding: 4px 8px; border-radius: 4px;"><i class="fa-solid fa-circle-check"></i> AKTIF</span>
                        @else
                            <span class="badge badge-danger" style="background-color: #ef4444; color: white; padding: 4px 8px; border-radius: 4px;"><i class="fa-solid fa-circle-xmark"></i> NONAKTIF</span>
                        @endif
                    </span>
                    <form action="{{ route('atur-data.siswa.toggle-edit-akses') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn {{ ($sekolah?->edit_detail_siswa) ? 'btn-secondary' : 'btn-primary' }} btn-sm" style="font-weight: 600;">
                            @if($sekolah?->edit_detail_siswa)
                                <i class="fa-solid fa-lock"></i> Tutup Akses
                            @else
                                <i class="fa-solid fa-lock-open"></i> Buka Akses
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-users"></i> Daftar Siswa</h2>
            <div class="card-header-right">
                <form method="GET" class="search-form">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIS..." class="form-control form-control-sm" style="min-width:180px">
                    <select name="id_kelas" class="form-control form-control-sm">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ request('id_kelas')==$k->id_kelas?'selected':'' }}>
                                {{ $k->tingkat }} {{ $k->rombel }}
                            </option>
                        @endforeach
                    </select>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">Semua Status</option>
                        <option value="aktif"  {{ request('status')=='aktif'?'selected':'' }}>Aktif</option>
                        <option value="tidak"  {{ request('status')=='tidak'?'selected':'' }}>Tidak Aktif</option>
                        <option value="keluar" {{ request('status')=='keluar'?'selected':'' }}>Keluar</option>
                    </select>
                    <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-filter"></i></button>
                </form>
                <a href="{{ route('atur-data.siswa.import-pilih-kelas') }}" class="btn btn-secondary btn-sm" id="btn-import-siswa">
                    <i class="fa-solid fa-file-excel"></i> Import Excel
                </a>
                <button class="btn btn-primary btn-sm" onclick="openModal('modal-add')" id="btn-tambah-siswa">
                    <i class="fa-solid fa-plus"></i> Tambah
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="btn-bulk-delete" style="display: none; background: #ef4444; border: none; font-weight: 600;" onclick="openBulkDeleteModal()">
                    <i class="fa-solid fa-trash-can"></i> Hapus Terpilih (<span id="bulk-select-count">0</span>)
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <form id="bulk-delete-form" method="POST" action="{{ route('atur-data.siswa.bulk-destroy') }}">
                @csrf
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;"><input type="checkbox" id="check-all" style="cursor: pointer;"></th>
                            <th>#</th><th>NIS</th><th>NISN</th><th>NIK</th><th>Nama Siswa</th><th>Kelas</th><th>L/P</th><th>Status</th><th>Aksi</th>
                        </tr>
                    </thead>
                <tbody>
                    @forelse($siswaList as $i => $siswa)
                    <tr>
                        <td style="text-align: center;"><input type="checkbox" name="ids[]" class="row-checkbox" value="{{ $siswa->nis }}" style="cursor: pointer;"></td>
                        <td>{{ $siswaList->firstItem() + $i }}</td>
                        <td class="font-mono">{{ $siswa->nis }}</td>
                        <td class="font-mono">{{ $siswa->nisn ?? '—' }}</td>
                        <td class="font-mono">{{ $siswa->nik ?? '—' }}</td>
                        <td><strong>{{ $siswa->nama_siswa }}</strong></td>
                        <td>{{ $siswa->kelas ? $siswa->kelas->tingkat.' '.$siswa->kelas->rombel : '-' }}</td>
                        <td class="text-center">
                            <span class="badge {{ $siswa->jenkel === 'L' ? 'badge-info' : 'badge-pink' }}">{{ $siswa->jenkel }}</span>
                        </td>
                        <td>
                            @if($siswa->status === 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @elseif($siswa->status === 'tidak')
                                <span class="badge badge-muted">Non-Aktif</span>
                            @elseif($siswa->status === 'keluar')
                                <span class="badge badge-danger">Keluar</span>
                            @else
                                <span class="badge badge-muted">{{ ucfirst($siswa->status) }}</span>
                            @endif
                        </td>
                        <td class="action-cell">
                            <a href="{{ route('atur-data.siswa.show', $siswa->nis) }}" class="btn-icon btn-info" title="Detail">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editSiswa({{ $siswa->nis }},{{ $siswa->id_kelas }},'{{ addslashes($siswa->nama_siswa) }}','{{ $siswa->nisn }}','{{ $siswa->nik }}','{{ $siswa->jenkel }}','{{ $siswa->tempat_lahir }}','{{ $siswa->tgl_lahir }}','{{ $siswa->status }}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-warning" title="Reset Password"
                                onclick="resetPassword({{ $siswa->nis }},'{{ addslashes($siswa->nama_siswa) }}')">
                                <i class="fa-solid fa-key"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDeleteSiswa({{ $siswa->nis }}, '{{ addslashes($siswa->nama_siswa) }}')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="10" class="text-center text-muted py-6">Belum ada data siswa</td></tr>
                    @endforelse
                </tbody>
            </table>
            </form>
        </div>
        @if($siswaList->hasPages())
        <div style="padding: 14px 20px; border-top: 1px solid var(--border-color);">
            {{ $siswaList->links('pagination.presensi') }}
        </div>
        @endif
    </div>
</div>

{{-- Modal Tambah/Edit Siswa --}}
<div class="modal-overlay" id="modal-add">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-title">Tambah Siswa</h3>
            <button onclick="closeModal('modal-add')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-siswa" action="{{ route('atur-data.siswa.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-grid-2">
                    <div class="form-group" id="nis-group">
                        <label class="form-label">NIS <span class="required">*</span></label>
                        <input type="number" name="nis" id="s_nis" class="form-control" required>
                    </div>
                    <div class="form-group" id="password-group">
                        <label class="form-label">Password <span class="required">*</span></label>
                        <input type="text" name="password" id="s_password" class="form-control" placeholder="Min. 4 karakter">
                    </div>
                    <div class="form-group" style="grid-column:1/-1">
                        <label class="form-label">Nama Siswa <span class="required">*</span></label>
                        <input type="text" name="nama_siswa" id="s_nama" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">NISN</label>
                        <input type="text" name="nisn" id="s_nisn" class="form-control" placeholder="Nomor Induk Siswa Nasional" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label class="form-label">NIK</label>
                        <input type="text" name="nik" id="s_nik" class="form-control" placeholder="Nomor Induk Kependudukan" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kelas <span class="required">*</span></label>
                        <select name="id_kelas" id="s_kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id_kelas }}">{{ $k->tingkat }} {{ $k->rombel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin <span class="required">*</span></label>
                        <select name="jenkel" id="s_jenkel" class="form-control" required>
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="s_tempat_lahir" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" id="s_tgl_lahir" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" id="s_status" class="form-control">
                            <option value="aktif">Aktif</option>
                            <option value="tidak">Tidak Aktif</option>
                            <option value="keluar">Keluar</option>
                        </select>
                    </div>
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
            <h3>Reset Password Siswa</h3>
            <button onclick="closeModal('modal-reset')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-reset" action="" method="POST">
            @csrf
            <div class="modal-body">
                <p class="text-muted mb-4">Reset password untuk: <strong id="reset-nama"></strong></p>
                <div class="form-group">
                    <label class="form-label">Password Baru <span class="required">*</span></label>
                    <input type="text" name="password" class="form-control" placeholder="Min. 4 karakter" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-reset')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-warning"><i class="fa-solid fa-key"></i> Reset</button>
            </div>
        </form>
    </div>
</div>



{{-- Modal Konfirmasi Hapus Siswa --}}
<div class="modal-overlay" id="modal-confirm-delete">
    <div class="modal">
        <div class="modal-header">
            <h3>Konfirmasi Hapus Siswa</h3>
            <button onclick="closeModal('modal-confirm-delete')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-confirm-delete" action="" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body">
                <div class="alert alert-danger" style="display:flex; align-items:center; gap:12px; padding:14px 18px; border-radius:12px; margin-bottom:20px; font-size:0.9rem; font-weight:500; background-color:#fef2f2; color:#b91c1c; border:1px solid #fee2e2;">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size:1.2rem"></i>
                    <div>
                        <strong>Peringatan!</strong> Tindakan ini akan menghapus siswa secara permanen beserta seluruh data yang berhubungan (presensi, rekam medis, poin, dll). Tindakan ini tidak dapat dibatalkan.
                    </div>
                </div>
                <p class="text-muted mb-4">Untuk mengonfirmasi penghapusan siswa <strong id="delete-siswa-name"></strong>, silakan ketik kata kunci <strong>HAPUS</strong> di bawah ini:</p>
                <div class="form-group">
                    <input type="text" id="confirm_delete_text" class="form-control" placeholder="Ketik HAPUS" autocomplete="off" oninput="validateConfirmDelete()">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-confirm-delete')" class="btn btn-secondary">Batal</button>
                <button type="submit" id="btn-submit-delete" class="btn btn-danger" disabled>
                    <i class="fa-solid fa-trash"></i> Hapus Permanen
                </button>
            </div>
        </form>
    </div>
</div>

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
            <p style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #ef4444;"><span id="bulk-confirm-count">0</span> Data Siswa?</p>
            <p style="margin: 8px 0 0; font-size: 0.83rem; color: var(--text-secondary); line-height: 1.4;">Tindakan ini akan menghapus semua siswa terpilih beserta seluruh data yang terkait secara permanen dan tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer" style="padding: 14px 24px; display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" onclick="closeModal('modal-bulk-confirm')" class="btn btn-secondary" style="padding: 8px 18px; font-size: 0.88rem;">Batal</button>
            <button type="button" id="btn-submit-bulk-delete" class="btn" style="background: #ef4444; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-size: 0.88rem; font-weight: 600; display: flex; align-items: center; gap: 7px;">
                <i class="fa-solid fa-trash-can"></i> Ya, Hapus Semua
            </button>
        </div>
    </div>
</div>

<script>
function confirmDeleteSiswa(nis, nama) {
    document.getElementById('form-confirm-delete').action = `/atur-data/siswa/${nis}`;
    document.getElementById('delete-siswa-name').textContent = nama;
    document.getElementById('confirm_delete_text').value = '';
    document.getElementById('btn-submit-delete').disabled = true;
    openModal('modal-confirm-delete');
}

function validateConfirmDelete() {
    const val = document.getElementById('confirm_delete_text').value;
    const btn = document.getElementById('btn-submit-delete');
    if (val === 'HAPUS') {
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
}

let isEdit = false;
function editSiswa(nis, idKelas, nama, nisn, nik, jenkel, tempatLahir, tglLahir, status) {
    isEdit = true;
    document.getElementById('form-siswa').action = `/atur-data/siswa/${nis}`;
    document.getElementById('s_nis').value         = nis;
    document.getElementById('s_nis').readOnly      = true;
    document.getElementById('s_kelas').value       = idKelas;
    document.getElementById('s_nama').value        = nama;
    document.getElementById('s_nisn').value        = nisn || '';
    document.getElementById('s_nik').value         = nik || '';
    document.getElementById('s_jenkel').value      = jenkel;
    document.getElementById('s_tempat_lahir').value = tempatLahir || '';
    document.getElementById('s_tgl_lahir').value   = tglLahir || '';
    document.getElementById('s_status').value      = status;
    document.getElementById('modal-title').textContent = 'Edit Siswa';
    document.getElementById('password-group').style.display = 'none';
    openModal('modal-add');
}
function resetPassword(nis, nama) {
    document.getElementById('form-reset').action = `/atur-data/siswa/${nis}/reset-password`;
    document.getElementById('reset-nama').textContent = nama;
    openModal('modal-reset');
}

// Reset form saat modal edit ditutup
document.addEventListener('modalClosed', function(e) {
    if (e.detail && e.detail.id === 'modal-add' && isEdit) {
        isEdit = false;
        document.getElementById('form-siswa').action = '{{ route("atur-data.siswa.store") }}';
        document.getElementById('form-siswa').reset();
        document.getElementById('s_nis').readOnly = false;
        document.getElementById('s_nisn').value = '';
        document.getElementById('s_nik').value = '';
        document.getElementById('password-group').style.display = '';
        document.getElementById('modal-title').textContent = 'Tambah Siswa';
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
            rowCheckboxes.forEach(cb => {
                cb.checked = checkAll.checked;
            });
            updateBulkButton();
        });
    }

    rowCheckboxes.forEach(cb => {
        cb.addEventListener('change', function() {
            if (!this.checked) {
                checkAll.checked = false;
            } else {
                const totalChecked = document.querySelectorAll('.row-checkbox:checked').length;
                if (totalChecked === rowCheckboxes.length) {
                    checkAll.checked = true;
                }
            }
            updateBulkButton();
        });
    });

    document.getElementById('btn-submit-bulk-delete').addEventListener('click', function() {
        document.getElementById('bulk-delete-form').submit();
    });
});

function openBulkDeleteModal() {
    const checkedCount = document.querySelectorAll('.row-checkbox:checked').length;
    document.getElementById('bulk-confirm-count').textContent = checkedCount;
    openModal('modal-bulk-confirm');
}
</script>
@endsection
