@extends('layouts.app')

@section('title', 'Data Mesin Finger — SmartSchool')
@section('header_title', 'Data Mesin Finger')
@section('header_subtitle', 'Kelola mesin absensi fingerprint')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-fingerprint"></i> Daftar Mesin Finger</h2>
            <div class="card-header-right">
                <form method="GET" class="search-form">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari mesin / SN..." class="form-control form-control-sm">
                    <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-search"></i></button>
                </form>
                <button class="btn btn-primary btn-sm" onclick="openModal('modal-add')" id="btn-tambah-mesin">
                    <i class="fa-solid fa-plus"></i> Tambah
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>Nama Mesin</th><th>Serial Number</th><th>Password</th><th>Last Update</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($mesinList as $i => $mesin)
                    <tr>
                        <td>{{ $mesinList->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $mesin->nama_mesin }}</strong></td>
                        <td class="font-mono text-xs">{{ $mesin->sn }}</td>
                        <td class="text-xs">
                            @if($mesin->password)
                                <span class="font-mono" style="letter-spacing:2px;">••••••</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td class="text-xs">{{ $mesin->last_update ? \Carbon\Carbon::parse($mesin->last_update)->format('d/m/Y H:i') : '-' }}</td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editMesin({{ $mesin->id_mesin }},'{{ addslashes($mesin->nama_mesin) }}','{{ $mesin->sn }}','{{ addslashes($mesin->password ?? '') }}')">

                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('atur-data.mesin-finger.destroy', $mesin->id_mesin) }}', 'Yakin hapus data mesin ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-6">Belum ada data mesin finger</td></tr>
                    @endforelse
                </tbody>
            </table>
            {{ $mesinList->links('pagination.presensi') }}
        </div>
    </div>
</div>

{{-- Modal --}}
<div class="modal-overlay" id="modal-add">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modal-title">Tambah Mesin Finger</h3>
            <button onclick="closeModal('modal-add')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-mesin" action="{{ route('atur-data.mesin-finger.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Mesin <span class="required">*</span></label>
                    <input type="text" name="nama_mesin" id="m_nama" class="form-control" placeholder="Mesin Gerbang Utama" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Serial Number (SN) <span class="required">*</span></label>
                    <input type="text" name="sn" id="m_sn" class="form-control" placeholder="BWXP233560696" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Password Mesin</label>
                    <input type="text" name="password" id="m_pass" class="form-control" placeholder="(kosongkan jika tidak ada)">
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
function editMesin(id, nama, sn, password) {
    document.getElementById('form-mesin').action = `/atur-data/mesin-finger/${id}`;
    document.getElementById('m_nama').value = nama;
    document.getElementById('m_sn').value   = sn;
    document.getElementById('m_pass').value = password;
    document.getElementById('modal-title').textContent = 'Edit Mesin Finger';
    openModal('modal-add');
}
</script>
@endsection
