@extends('layouts.app')

@section('title', 'Kategori Reward — SmartSchool')
@section('header_title', 'Kategori Reward')
@section('header_subtitle', 'Master data jenis/kategori reward dan prestasi siswa beserta poin penambahannya')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card" style="max-width:860px; margin:0 auto;">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-trophy"></i> Master Kategori Reward</h2>
            <div class="card-header-right">
                <button class="btn btn-primary btn-sm" id="btn-tambah-reward">
                    <i class="fa-solid fa-plus"></i> Tambah Kategori
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:60px;">#</th>
                        <th>Nama Reward / Prestasi</th>
                        <th style="width:150px;text-align:center;">Poin Penambahan</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $data->firstItem() + $i }}</td>
                        <td style="font-weight:600;">{{ $item->detail_reward }}</td>
                        <td style="text-align:center;">
                            <span class="badge badge-success" style="font-size:0.9rem; padding: 6px 14px;">
                                <i class="fa-solid fa-plus" style="font-size:0.7rem;"></i> {{ $item->skor }}
                            </span>
                        </td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon btn-edit" title="Edit" onclick="editReward({{ json_encode($item) }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('bk.kategori-reward.destroy', $item->id_reward) }}','Yakin hapus kategori reward ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-6">
                            <i class="fa-solid fa-trophy" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada kategori reward
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
<div class="modal-overlay" id="modal-reward">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3 id="modal-title-reward">Tambah Kategori Reward</h3>
            <button onclick="closeModal('modal-reward')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-reward" method="POST" action="{{ route('bk.kategori-reward.store') }}">
            @csrf
            <div id="method-field-reward"></div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama Reward / Prestasi <span class="required">*</span></label>
                    <input type="text" name="detail_reward" id="r_nama" class="form-control"
                        placeholder="cth: Juara Kelas, Hafal Juz 30, Atlet Berprestasi" required maxlength="100">
                </div>
                <div class="form-group">
                    <label class="form-label">Poin Penambahan <span class="required">*</span></label>
                    <div style="position:relative;">
                        <input type="number" name="skor" id="r_skor" class="form-control"
                            placeholder="cth: 50" required min="1" max="1000"
                            style="padding-right: 50px;">
                        <span style="position:absolute;right:12px;top:50%;transform:translateY(-50%);
                            color:var(--text-muted);font-size:0.8rem;pointer-events:none;">poin</span>
                    </div>
                    <div style="font-size:0.78rem;color:var(--text-muted);margin-top:4px;">
                        <i class="fa-solid fa-circle-info"></i>
                        Nilai poin yang ditambahkan ke total poin siswa saat mendapat reward/prestasi ini.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-reward')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('btn-tambah-reward').addEventListener('click', function() {
    document.getElementById('form-reward').action = '{{ route("bk.kategori-reward.store") }}';
    document.getElementById('method-field-reward').innerHTML = '';
    document.getElementById('modal-title-reward').textContent = 'Tambah Kategori Reward';
    document.getElementById('r_nama').value = '';
    document.getElementById('r_skor').value = '';
    openModal('modal-reward');
});

function editReward(data) {
    document.getElementById('form-reward').action = `/bk/kategori-reward/${data.id_reward}`;
    document.getElementById('method-field-reward').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-reward').textContent = 'Edit Kategori Reward';
    document.getElementById('r_nama').value = data.detail_reward;
    document.getElementById('r_skor').value = data.skor;
    openModal('modal-reward');
}
</script>
@endpush
@endsection
