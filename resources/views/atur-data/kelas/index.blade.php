@extends('layouts.app')

@section('title', 'Data Kelas — SmartSchool')
@section('header_title', 'Data Kelas')
@section('header_subtitle')
    @if(!empty($isInactive) && $isInactive)
        Kelola rombongan belajar yang tidak aktif
    @else
        Kelola rombongan belajar yang sedang aktif
    @endif
@endsection

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-chalkboard-user"></i> Daftar Kelas</h2>
            <div class="card-header-right">
                <form method="GET" class="search-form">
                    <select name="tingkat" class="form-control form-control-sm">
                        <option value="">Semua Tingkat</option>
                        <option value="10" {{ request('tingkat')=='10'?'selected':'' }}>Kelas 10</option>
                        <option value="11" {{ request('tingkat')=='11'?'selected':'' }}>Kelas 11</option>
                        <option value="12" {{ request('tingkat')=='12'?'selected':'' }}>Kelas 12</option>
                    </select>
                    <select name="id_jurusan" class="form-control form-control-sm">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusanList as $j)
                            <option value="{{ $j->id_jurusan }}" {{ request('id_jurusan')==$j->id_jurusan?'selected':'' }}>{{ $j->kode_jurusan }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-filter"></i></button>
                </form>
                @if(!empty($isInactive) && $isInactive)
                    <a href="{{ route('atur-data.kelas') }}" class="btn btn-outline-primary btn-sm" title="Lihat Aktif">
                        <i class="fa-solid fa-circle-check"></i> Lihat Aktif
                    </a>
                @else
                    <a href="{{ route('atur-data.kelas.tidak-aktif') }}" class="btn btn-outline-secondary btn-sm" title="Lihat Tidak Aktif">
                        <i class="fa-solid fa-eye-slash"></i> Lihat Tidak Aktif
                    </a>
                @endif
                <button type="button" class="btn btn-warning btn-sm" onclick="openConfirmNaikModal()" id="btn-naik-tingkat">
                    <i class="fa-solid fa-graduation-cap"></i> Naik Tingkat
                </button>
                <button class="btn btn-primary btn-sm" onclick="openModal('modal-add')" id="btn-tambah-kelas">
                    <i class="fa-solid fa-plus"></i> Tambah
                </button>
                <button type="button" class="btn btn-danger btn-sm" id="btn-bulk-delete" style="display: none; background: #ef4444; border: none; font-weight: 600;" onclick="openBulkDeleteModal()">
                    <i class="fa-solid fa-trash-can"></i> Hapus Terpilih (<span id="bulk-select-count">0</span>)
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <form id="bulk-delete-form" method="POST" action="{{ route('atur-data.kelas.bulk-destroy') }}">
                @csrf
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 40px; text-align: center;"><input type="checkbox" id="check-all" style="cursor: pointer;"></th>
                            <th>#</th><th>Rombel</th><th>Tingkat</th><th>Jurusan</th><th>Wali Kelas</th><th>Siswa</th><th>Aksi</th>
                        </tr>
                    </thead>
                <tbody>
                    @forelse($kelasList as $kelas)
                    <tr>
                        <td style="text-align: center;"><input type="checkbox" name="ids[]" class="row-checkbox" value="{{ $kelas->id_kelas }}" style="cursor: pointer;"></td>
                        <td>{{ $kelasList->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $kelas->rombel }}</strong></td>
                        <td class="text-center">{{ $kelas->tingkat }}</td>
                        <td>{{ $kelas->jurusan->nama_jurusan ?? '-' }}</td>
                        <td>
                            @if($kelas->guru)
                                {{ $kelas->guru->nama_guru }}
                            @else
                                <span class="text-muted italic">Belum ditetapkan</span>
                            @endif
                        </td>
                        <td class="text-center">{{ $kelas->siswa()->count() }}</td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editKelas({{ $kelas->id_kelas }},'{{ $kelas->tahun_masuk }}',{{ $kelas->tingkat }},{{ $kelas->id_jurusan }},'{{ addslashes($kelas->rombel) }}',{{ $kelas->walikelas ?? 'null' }},'{{ $kelas->status }}')">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('atur-data.kelas.destroy', $kelas->id_kelas) }}', 'Yakin ingin menghapus kelas ini? Tindakan ini akan menghapus semua siswa, presensi, tugas, dan seluruh data yang berhubungan secara permanen!')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-6">
                            @if(!empty($isInactive) && $isInactive)
                                Belum ada data kelas tidak aktif
                            @else
                                Belum ada data kelas aktif
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            </form>
        </div>
        @if($kelasList->hasPages())
        <div style="padding: 14px 20px; border-top: 1px solid var(--border-color);">
            {{ $kelasList->links('pagination.presensi') }}
        </div>
        @endif
    </div>
</div>

{{-- Modal Kelas --}}
<div class="modal-overlay" id="modal-add">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-title">Tambah Kelas</h3>
            <button onclick="closeModal('modal-add')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-kelas" action="{{ route('atur-data.kelas.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Tahun Masuk <span class="required">*</span></label>
                        <input type="text" name="tahun_masuk" id="tahun_masuk" class="form-control" placeholder="2024" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tingkat <span class="required">*</span></label>
                        <select name="tingkat" id="k_tingkat" class="form-control" required>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jurusan <span class="required">*</span></label>
                        <select name="id_jurusan" id="k_jurusan" class="form-control" required>
                            <option value="">-- Pilih Jurusan --</option>
                            @foreach($jurusanList as $j)
                                <option value="{{ $j->id_jurusan }}">{{ $j->nama_jurusan }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nama Rombel <span class="required">*</span></label>
                        <input type="text" name="rombel" id="k_rombel" class="form-control" placeholder="RPL 1" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Wali Kelas</label>
                        <select name="walikelas" id="k_walikelas" class="form-control">
                            <option value="">-- Belum Ditetapkan --</option>
                            @foreach($guruList as $g)
                                <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select name="status" id="k_status" class="form-control">
                            <option value="aktif">Aktif</option>
                            <option value="tidak">Tidak Aktif</option>
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

{{-- Modal Konfirmasi Naik Tingkat --}}
<div class="modal-overlay" id="modal-confirm-naik">
    <div class="modal">
        <div class="modal-header">
            <h3>Konfirmasi Naik Tingkat</h3>
            <button onclick="closeModal('modal-confirm-naik')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('atur-data.kelas.naik-tingkat') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="alert alert-danger" style="display:flex; align-items:center; gap:12px; padding:14px 18px; border-radius:12px; margin-bottom:20px; font-size:0.9rem; font-weight:500; background-color:#fef2f2; color:#b91c1c; border:1px solid #fee2e2;">
                    <i class="fa-solid fa-triangle-exclamation" style="font-size:1.2rem"></i>
                    <div>
                        <strong>Peringatan!</strong> Tindakan ini akan menaikkan tingkat semua kelas aktif sebanyak 1 tingkat. Siswa yang berada di kelas dengan tingkat melebihi 12 (> 12) akan otomatis diubah statusnya menjadi <strong>Tidak Aktif</strong>.
                    </div>
                </div>
                <p class="text-muted mb-4">Untuk melanjutkan, silakan ketik kata kunci <strong>NAIK TINGKAT</strong> di bawah ini:</p>
                <div class="form-group">
                    <input type="text" id="confirm_naik_text" class="form-control" placeholder="Ketik NAIK TINGKAT" autocomplete="off" oninput="validateConfirmNaik()">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-confirm-naik')" class="btn btn-secondary">Batal</button>
                <button type="submit" id="btn-submit-naik" class="btn btn-warning" disabled>
                    <i class="fa-solid fa-graduation-cap"></i> Proses Naik Tingkat
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function editKelas(id, tahunMasuk, tingkat, idJurusan, rombel, walikelas, status) {
    document.getElementById('form-kelas').action = `/atur-data/kelas/${id}`;
    document.getElementById('tahun_masuk').value  = tahunMasuk;
    document.getElementById('k_tingkat').value    = tingkat;
    document.getElementById('k_jurusan').value    = idJurusan;
    document.getElementById('k_rombel').value     = rombel;
    document.getElementById('k_walikelas').value  = walikelas || '';
    document.getElementById('k_status').value     = status;
    document.getElementById('modal-title').textContent = 'Edit Kelas';
    openModal('modal-add');
}

function openConfirmNaikModal() {
    document.getElementById('confirm_naik_text').value = '';
    document.getElementById('btn-submit-naik').disabled = true;
    openModal('modal-confirm-naik');
}

function validateConfirmNaik() {
    const val = document.getElementById('confirm_naik_text').value;
    const btn = document.getElementById('btn-submit-naik');
    if (val === 'NAIK TINGKAT') {
        btn.disabled = false;
    } else {
        btn.disabled = true;
    }
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
            <p style="margin: 0; font-size: 1.5rem; font-weight: 800; color: #ef4444;"><span id="bulk-confirm-count">0</span> Data Kelas?</p>
            <p style="margin: 8px 0 0; font-size: 0.83rem; color: var(--text-secondary); line-height: 1.4;">Tindakan ini akan menghapus semua kelas terpilih beserta seluruh data terkait (siswa, presensi, tugas, dll) secara permanen dan tidak dapat dibatalkan.</p>
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
