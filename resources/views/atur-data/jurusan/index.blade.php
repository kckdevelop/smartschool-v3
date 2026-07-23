@extends('layouts.app')

@section('title', 'Data Jurusan — SmartSchool')
@section('header_title', 'Data Jurusan')
@section('header_subtitle', 'Kelola data program keahlian / jurusan')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-sitemap"></i> Daftar Jurusan</h2>
            <div class="card-header-right">
                <form method="GET" class="search-form">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari jurusan..." class="form-control form-control-sm">
                    <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-search"></i></button>
                </form>
                <button class="btn btn-primary btn-sm" onclick="openModal('modal-add')" id="btn-tambah-jurusan">
                    <i class="fa-solid fa-plus"></i> Tambah
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="btn-bulk-delete" style="display: none; background: #ef4444; border: none; font-weight: 600;" onclick="openBulkDeleteModal()">
                    <i class="fa-solid fa-trash-can"></i> Hapus Terpilih (<span id="bulk-select-count">0</span>)
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <form id="bulk-delete-form" method="POST" action="{{ route('atur-data.jurusan.bulk-destroy') }}">
                @csrf
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;"><input type="checkbox" id="check-all" style="cursor: pointer;"></th>
                            <th>#</th><th>Kode</th><th>Nama Jurusan</th><th>Status</th><th>Jumlah Kelas</th><th>Aksi</th>
                        </tr>
                    </thead>
                <tbody>

                    @forelse($jurusanList as $jurusan)
                    <tr>
                        <td style="text-align: center;"><input type="checkbox" name="ids[]" class="row-checkbox" value="{{ $jurusan->id_jurusan }}" style="cursor: pointer;"></td>
                        <td>{{ $jurusanList->firstItem() + $loop->index }}</td>
                        <td><span class="badge badge-info">{{ $jurusan->kode_jurusan }}</span></td>
                        <td><strong>{{ $jurusan->nama_jurusan }}</strong></td>
                        <td><span class="badge {{ $jurusan->status === 'aktif' ? 'badge-success' : 'badge-muted' }}">{{ ucfirst($jurusan->status) }}</span></td>
                        <td class="text-center">{{ $jurusan->kelas_count ?? $jurusan->kelas()->count() }}</td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editJurusan({{ $jurusan->id_jurusan }},'{{ $jurusan->kode_jurusan }}','{{ addslashes($jurusan->nama_jurusan) }}','{{ $jurusan->status }}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('atur-data.jurusan.destroy', $jurusan->id_jurusan) }}', 'Yakin hapus jurusan ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-muted py-6">Belum ada data jurusan</td></tr>
                    @endforelse
                </tbody>
            </table>
            </form>
        </div>
        @if($jurusanList->hasPages())
        <div style="padding: 14px 20px; border-top: 1px solid var(--border-color);">
            {{ $jurusanList->links('pagination.presensi') }}
        </div>
        @endif
    </div>
</div>

{{-- Modal Tambah / Edit Jurusan --}}
<div class="modal-overlay" id="modal-add">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modal-title">Tambah Jurusan</h3>
            <button onclick="closeModal('modal-add')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-jurusan" action="{{ route('atur-data.jurusan.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Kode Jurusan <span class="required">*</span></label>
                    <input type="text" name="kode_jurusan" id="kode_jurusan" class="form-control" placeholder="Contoh: RPL" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Jurusan <span class="required">*</span></label>
                    <input type="text" name="nama_jurusan" id="nama_jurusan" class="form-control" placeholder="Rekayasa Perangkat Lunak" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="jurusan_status" class="form-control">
                        <option value="aktif">Aktif</option>
                        <option value="tidak">Tidak Aktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-add')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function editJurusan(id, kode, nama, status) {
    document.getElementById('form-jurusan').action = `/atur-data/jurusan/${id}`;
    document.getElementById('kode_jurusan').value  = kode;
    document.getElementById('nama_jurusan').value  = nama;
    if (status) document.getElementById('jurusan_status').value = status;
    document.getElementById('modal-title').textContent = 'Edit Jurusan';
    openModal('modal-add');
}

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
            <p style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #ef4444;"><span id="bulk-confirm-count">0</span> Data Jurusan?</p>
            <p style="margin: 8px 0 0; font-size: 0.83rem; color: var(--text-secondary); line-height: 1.4;">Tindakan ini akan menghapus semua jurusan terpilih secara permanen dan tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer" style="padding: 14px 24px; display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" onclick="closeModal('modal-bulk-confirm')" class="btn btn-secondary" style="padding: 8px 18px; font-size: 0.88rem;">Batal</button>
            <button type="button" id="btn-submit-bulk-delete" class="btn" style="background: #ef4444; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-size: 0.88rem; font-weight: 600; display: flex; align-items: center; gap: 7px;">
                <i class="fa-solid fa-trash-can"></i> Ya, Hapus Semua
            </button>
        </div>
    </div>
</div>
@endsection
