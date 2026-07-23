@extends('layouts.app')

@section('title', 'Kategori Pelanggaran — SmartSchool')
@section('header_title', 'Kategori Pelanggaran')
@section('header_subtitle', 'Master data jenis/kategori pelanggaran siswa beserta poin pengurangannya')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card" style="max-width:860px; margin:0 auto;">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-triangle-exclamation"></i> Master Kategori Pelanggaran</h2>
            <div class="card-header-right">
                <button class="btn btn-primary btn-sm" id="btn-tambah-kategori">
                    <i class="fa-solid fa-plus"></i> Tambah Kategori
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>Nama Kategori Pelanggaran</th>
                        <th style="width:130px;text-align:center;">Poin Pengurangan</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $data->firstItem() + $i }}</td>
                        <td style="font-weight:600;">{{ $item->jenis_pelanggaran }}</td>
                        <td style="text-align:center;">
                            <span class="badge badge-danger" style="font-size:0.9rem; padding: 6px 14px;">
                                <i class="fa-solid fa-minus" style="font-size:0.7rem;"></i> {{ $item->poin }}
                            </span>
                        </td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon btn-edit" title="Edit" onclick="editKategori({{ json_encode($item) }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('bk.kategori-pelanggaran.destroy', $item->id_jenis_pelanggaran) }}','Yakin hapus kategori pelanggaran ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-6">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada kategori pelanggaran
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($data->hasPages())
        <div class="card-footer">{{ $data->links() }}</div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH/EDIT --}}
<div class="modal-overlay" id="modal-add-kategori">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3 id="modal-title-kategori">Tambah Kategori Pelanggaran</h3>
            <button onclick="closeModal('modal-add-kategori')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-kategori" method="POST" action="{{ route('bk.kategori-pelanggaran.store') }}">
            @csrf
            <div id="method-field-kategori"></div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Kategori Pelanggaran <span class="required">*</span></label>
                    <input type="text" name="jenis_pelanggaran" id="k_nama" class="form-control"
                        placeholder="cth: Terlambat, Tidak Berseragam, Membolos" required maxlength="100">
                </div>
                <div class="form-group">
                    <label class="form-label">Poin Pengurangan <span class="required">*</span></label>
                    <div style="position:relative;">
                        <input type="number" name="poin" id="k_poin" class="form-control"
                            placeholder="cth: 10" required min="1" max="100"
                            style="padding-right: 50px;">
                        <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                            color:var(--text-muted);font-size:0.8rem;pointer-events:none;">poin</span>
                    </div>
                    <div style="font-size:0.78rem;color:var(--text-muted);margin-top:4px;">
                        <i class="fa-solid fa-circle-info"></i>
                        Nilai poin yang dikurangkan dari total poin siswa saat melakukan pelanggaran ini.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-add-kategori')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('btn-tambah-kategori').addEventListener('click', function() {
    document.getElementById('form-kategori').action = '{{ route("bk.kategori-pelanggaran.store") }}';
    document.getElementById('method-field-kategori').innerHTML = '';
    document.getElementById('modal-title-kategori').textContent = 'Tambah Kategori Pelanggaran';
    document.getElementById('k_nama').value = '';
    document.getElementById('k_poin').value = '';
    openModal('modal-add-kategori');
});

function editKategori(data) {
    document.getElementById('form-kategori').action = `/bk/kategori-pelanggaran/${data.id_jenis_pelanggaran}`;
    document.getElementById('method-field-kategori').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-kategori').textContent = 'Edit Kategori Pelanggaran';
    document.getElementById('k_nama').value = data.jenis_pelanggaran;
    document.getElementById('k_poin').value = data.poin;
    openModal('modal-add-kategori');
}
</script>
@endpush
@endsection
