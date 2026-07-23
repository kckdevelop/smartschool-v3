@extends('layouts.app')

@section('title', 'Jenis Check-Up — SmartSchool')
@section('header_title', 'Jenis Check-Up')
@section('header_subtitle', 'Master data jenis pemeriksaan kesehatan')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card" style="max-width:800px; margin:0 auto;">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-list-check"></i> Master Jenis Check-Up</h2>
            <div class="card-header-right">
                <button class="btn btn-primary btn-sm" onclick="openModal('modal-add-jenis')" id="btn-tambah-jenis">
                    <i class="fa-solid fa-plus"></i> Tambah Jenis
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:70px;">#</th>
                        <th>Jenis Pemeriksaan</th>
                        <th style="width:150px;">Status</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($jenis as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $jenis->firstItem() + $i }}</td>
                        <td style="font-weight:600;">{{ $item->jenis_checkup }}</td>
                        <td>
                            @if($item->status == 'aktif')
                                <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Aktif</span>
                            @else
                                <span class="badge badge-danger"><i class="fa-solid fa-circle-xmark"></i> Tidak Aktif</span>
                            @endif
                        </td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editJenis({{ json_encode($item) }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('uks.jenis-checkup.destroy', $item->id_checkup) }}','Yakin hapus jenis pemeriksaan ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-6">
                            <i class="fa-solid fa-list-check" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada master data jenis check-up
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($jenis->hasPages())
        <div class="card-footer">
            {{ $jenis->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ═══════ MODAL TAMBAH/EDIT ═══════ --}}
<div class="modal-overlay" id="modal-add-jenis">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3 id="modal-title-jenis">Tambah Jenis Check-Up</h3>
            <button onclick="closeModal('modal-add-jenis')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-jenis" method="POST" action="{{ route('uks.jenis-checkup.store') }}">
            @csrf
            <div id="method-field"></div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Pemeriksaan <span class="required">*</span></label>
                    <input type="text" name="jenis_checkup" id="j_jenis" class="form-control" placeholder="misal: Tinggi Badan, Berat Badan, Tensi" required maxlength="100">
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select name="status" id="j_status" class="form-control" required>
                        <option value="aktif">Aktif</option>
                        <option value="tidak">Tidak Aktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-add-jenis')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function resetJenisModal() {
    document.getElementById('form-jenis').action = '{{ route("uks.jenis-checkup.store") }}';
    document.getElementById('method-field').innerHTML = '';
    document.getElementById('modal-title-jenis').textContent = 'Tambah Jenis Check-Up';
    document.getElementById('j_jenis').value = '';
    document.getElementById('j_status').value = 'aktif';
}

document.getElementById('btn-tambah-jenis').addEventListener('click', function() {
    resetJenisModal();
    openModal('modal-add-jenis');
});

function editJenis(data) {
    document.getElementById('form-jenis').action = `/uks/jenis-checkup/${data.id_checkup}`;
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-jenis').textContent = 'Edit Jenis Check-Up';
    document.getElementById('j_jenis').value = data.jenis_checkup;
    document.getElementById('j_status').value = data.status || 'aktif';
    openModal('modal-add-jenis');
}
</script>
@endpush
@endsection
