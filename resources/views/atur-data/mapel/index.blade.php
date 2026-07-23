@extends('layouts.app')

@section('title', 'Data Mata Pelajaran — SmartSchool')
@section('header_title', 'Data Mata Pelajaran')
@section('header_subtitle', 'Kelola daftar mata pelajaran')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-book-open"></i> Daftar Mata Pelajaran</h2>
            <div class="card-header-right">
                <form method="GET" class="search-form">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari mapel..." class="form-control form-control-sm">
                    <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-search"></i></button>
                </form>
                <button type="button" class="btn btn-secondary btn-sm" onclick="openModal('modal-import')" id="btn-import-mapel" style="display:inline-flex; align-items:center; gap:6px;">
                    <i class="fa-solid fa-file-excel"></i> Import Excel
                </button>
                <button class="btn btn-primary btn-sm" onclick="openModal('modal-add')" id="btn-tambah-mapel">
                    <i class="fa-solid fa-plus"></i> Tambah
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="btn-bulk-delete" style="display: none; background: #ef4444; border: none; font-weight: 600;" onclick="openBulkDeleteModal()">
                    <i class="fa-solid fa-trash-can"></i> Hapus Terpilih (<span id="bulk-select-count">0</span>)
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <form id="bulk-delete-form" method="POST" action="{{ route('atur-data.mapel.bulk-destroy') }}">
                @csrf
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;"><input type="checkbox" id="check-all" style="cursor: pointer;"></th>
                            <th>#</th><th>Kode</th><th>Nama Mata Pelajaran</th><th>Aksi</th>
                        </tr>
                    </thead>
                <tbody>

                    @forelse($mapelList as $mapel)
                    <tr>
                        <td style="text-align: center;"><input type="checkbox" name="ids[]" class="row-checkbox" value="{{ $mapel->id_mapel }}" style="cursor: pointer;"></td>
                        <td>{{ $mapelList->firstItem() + $loop->index }}</td>
                        <td><span class="badge badge-info">{{ $mapel->kode_mapel }}</span></td>
                        <td><strong>{{ $mapel->nama_mapel }}</strong></td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editMapel({{ $mapel->id_mapel }},'{{ $mapel->kode_mapel }}','{{ addslashes($mapel->nama_mapel) }}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('atur-data.mapel.destroy', $mapel->id_mapel) }}', 'Yakin hapus mata pelajaran ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-muted py-6">Belum ada data mata pelajaran</td></tr>
                    @endforelse
                </tbody>
            </table>
            </form>
        </div>
        @if($mapelList->hasPages())
        <div style="padding: 14px 20px; border-top: 1px solid var(--border-color);">
            {{ $mapelList->links('pagination.presensi') }}
        </div>
        @endif
    </div>
</div>

{{-- Modal --}}
<div class="modal-overlay" id="modal-add">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modal-title">Tambah Mata Pelajaran</h3>
            <button onclick="closeModal('modal-add')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-mapel" action="{{ route('atur-data.mapel.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Kode Mapel <span class="required">*</span></label>
                    <input type="text" name="kode_mapel" id="m_kode" class="form-control" placeholder="MTK" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nama Mata Pelajaran <span class="required">*</span></label>
                    <input type="text" name="nama_mapel" id="m_nama" class="form-control" placeholder="Matematika" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-add')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Import Mata Pelajaran --}}
<div class="modal-overlay" id="modal-import">
    <div class="modal">
        <div class="modal-header">
            <h3>Import Data Mata Pelajaran</h3>
            <button onclick="closeModal('modal-import')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('atur-data.mapel.import-process') }}" method="POST" enctype="multipart/form-data" onsubmit="showLoading('Mengimpor Data Mapel', 'Sedang memproses file Excel, mohon tunggu...')">
            @csrf
            <div class="modal-body">
                <div class="alert alert-info" style="display:flex; align-items:center; gap:12px; padding:14px 18px; border-radius:12px; margin-bottom:20px; font-size:0.9rem; font-weight:500; background-color:#f0fdfa; color:#0f766e; border:1px solid #ccfbf1;">
                    <i class="fa-solid fa-circle-info" style="font-size:1.2rem"></i>
                    <div>
                        Unduh template Excel terlebih dahulu, isi data mata pelajaran (<strong>kode_mapel</strong>, <strong>nama_mapel</strong>), lalu unggah file di sini.
                    </div>
                </div>
                <div style="margin-bottom:20px;">
                    <a href="{{ route('atur-data.mapel.import-template') }}" class="btn btn-secondary btn-sm" style="display:inline-flex; align-items:center; gap:8px;">
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

<script>
function editMapel(id, kode, nama) {
    document.getElementById('form-mapel').action = `/atur-data/mapel/${id}`;
    document.getElementById('m_kode').value = kode;
    document.getElementById('m_nama').value = nama;
    document.getElementById('modal-title').textContent = 'Edit Mata Pelajaran';
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
            <p style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #ef4444;"><span id="bulk-confirm-count">0</span> Data Mapel?</p>
            <p style="margin: 8px 0 0; font-size: 0.83rem; color: var(--text-secondary); line-height: 1.4;">Tindakan ini akan menghapus semua mata pelajaran terpilih secara permanen dan tidak dapat dibatalkan.</p>
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
