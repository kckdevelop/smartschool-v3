@extends('layouts.app')

@section('title', 'Data DUDI PKL — SmartSchool')
@section('header_title', 'Data DUDI')
@section('header_subtitle', 'Dunia Usaha & Dunia Industri mitra PKL')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Filter --}}
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('pkl.dudi.index') }}" class="flex-row-wrap gap-4 align-items-end">
                <div class="form-group mb-0" style="min-width:220px;">
                    <label class="form-label-sm">Cari Nama / Kota</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Nama DUDI atau kota...">
                </div>
                <div class="form-group mb-0" style="min-width:150px;">
                    <label class="form-label-sm">Status</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">-- Semua --</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                </div>
                <div class="flex-row gap-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filter</button>
                    <a href="{{ route('pkl.dudi.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fa-solid fa-building-user" style="color:var(--color-primary);"></i>
                Daftar DUDI Mitra PKL
                <span class="badge badge-info" style="margin-left:8px;font-size:.75rem;">{{ $data->total() }} DUDI</span>
            </h2>
            <div style="display:flex;align-items:center;gap:12px;">
                <span style="font-size:.78rem;color:var(--text-muted);">
                    Menampilkan {{ $data->firstItem() }}–{{ $data->lastItem() }} dari {{ $data->total() }} data
                </span>
                <button class="btn btn-primary btn-sm" onclick="openAddModal()"><i class="fa-solid fa-plus"></i> Tambah DUDI</button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Nama DUDI</th>
                        <th>Bidang Usaha</th>
                        <th>Kota</th>
                        <th>PIC / Kontak</th>
                        <th style="width:80px;text-align:center;">Kuota</th>
                        <th style="width:90px;">Status</th>
                        <th style="width:110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:.8rem;">{{ $data->firstItem() + $i }}</td>
                        <td>
                            <div style="font-weight:700;">{{ $item->nama_dudi }}</div>
                            @if($item->alamat)
                            <div style="font-size:.78rem;color:var(--text-muted);margin-top:2px;">{{ Str::limit($item->alamat, 60) }}</div>
                            @endif
                        </td>
                        <td style="font-size:.85rem;">{{ $item->bidang_usaha ?? '-' }}</td>
                        <td style="font-size:.85rem;">{{ $item->kota ?? '-' }}</td>
                        <td>
                            @if($item->nama_pic)
                            <div style="font-weight:600;font-size:.85rem;">{{ $item->nama_pic }}</div>
                            <div style="font-size:.78rem;color:var(--text-muted);">{{ $item->jabatan_pic ?? '' }} {{ $item->no_hp_pic ? '| '.$item->no_hp_pic : '' }}</div>
                            @else
                            <span style="color:var(--text-muted);">-</span>
                            @endif
                        </td>
                        <td class="text-center"><span class="badge badge-info">{{ $item->kuota_siswa }}</span></td>
                        <td>
                            @if($item->status === 'aktif')
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon btn-edit" title="Edit" onclick="editDudi({{ json_encode($item) }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('pkl.dudi.destroy', $item->id_dudi) }}','Hapus DUDI {{ $item->nama_dudi }}?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-6">
                            <i class="fa-solid fa-building" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada data DUDI
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

{{-- MODAL TAMBAH/EDIT DUDI --}}
<div class="modal-overlay" id="modal-dudi">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-title-dudi">Tambah Data DUDI</h3>
            <button onclick="closeModal('modal-dudi')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-dudi" method="POST" action="{{ route('pkl.dudi.store') }}">
            @csrf
            <div id="method-field-dudi"></div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Nama DUDI / Perusahaan <span class="required">*</span></label>
                    <input type="text" name="nama_dudi" id="d_nama" class="form-control" placeholder="Nama lengkap perusahaan/industri" required>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Bidang Usaha</label>
                        <input type="text" name="bidang_usaha" id="d_bidang" class="form-control" placeholder="Contoh: Teknologi Informasi, Otomotif...">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kota</label>
                        <input type="text" name="kota" id="d_kota" class="form-control" placeholder="Nama kota">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Alamat Lengkap <span class="required">*</span></label>
                    <textarea name="alamat" id="d_alamat" class="form-control" rows="2" placeholder="Alamat lengkap perusahaan" required></textarea>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">No. Telepon Kantor</label>
                        <input type="text" name="no_telepon" id="d_telp" class="form-control" placeholder="021-xxxxxxx">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" id="d_email" class="form-control" placeholder="email@perusahaan.com">
                    </div>
                </div>
                <div style="border:1.5px solid rgba(13,148,136,.12);border-radius:10px;padding:14px 16px;margin-bottom:16px;">
                    <div style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.6px;color:var(--text-secondary);margin-bottom:12px;">
                        <i class="fa-solid fa-user-tie" style="color:var(--color-primary);"></i> Data PIC / Narahubung
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group mb-0">
                            <label class="form-label">Nama PIC</label>
                            <input type="text" name="nama_pic" id="d_pic" class="form-control" placeholder="Nama penanggung jawab">
                        </div>
                        <div class="form-group mb-0">
                            <label class="form-label">Jabatan PIC</label>
                            <input type="text" name="jabatan_pic" id="d_jabatan" class="form-control" placeholder="Direktur / HRD Manager...">
                        </div>
                    </div>
                    <div class="form-group mt-2 mb-0">
                        <label class="form-label">No. HP / WhatsApp PIC</label>
                        <input type="text" name="no_hp_pic" id="d_hp" class="form-control" placeholder="08xxxxxxxxxx">
                    </div>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Kuota Siswa <span class="required">*</span></label>
                        <input type="number" name="kuota_siswa" id="d_kuota" class="form-control" min="1" max="100" value="5" required>
                        <span class="form-hint">Jumlah maksimal siswa yang bisa ditempatkan di DUDI ini per gelombang</span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" id="d_status" class="form-control" required>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Nonaktif</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-dudi')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openAddModal() {
    document.getElementById('form-dudi').action = '{{ route("pkl.dudi.store") }}';
    document.getElementById('method-field-dudi').innerHTML = '';
    document.getElementById('modal-title-dudi').textContent = 'Tambah Data DUDI';
    ['d_nama','d_bidang','d_kota','d_alamat','d_telp','d_email','d_pic','d_jabatan','d_hp'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.value = '';
    });
    document.getElementById('d_kuota').value = 5;
    document.getElementById('d_status').value = 'aktif';
    openModal('modal-dudi');
}

function editDudi(data) {
    document.getElementById('form-dudi').action = `/pkl/dudi/${data.id_dudi}`;
    document.getElementById('method-field-dudi').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-dudi').textContent = 'Edit Data DUDI';
    document.getElementById('d_nama').value    = data.nama_dudi || '';
    document.getElementById('d_bidang').value  = data.bidang_usaha || '';
    document.getElementById('d_kota').value    = data.kota || '';
    document.getElementById('d_alamat').value  = data.alamat || '';
    document.getElementById('d_telp').value    = data.no_telepon || '';
    document.getElementById('d_email').value   = data.email || '';
    document.getElementById('d_pic').value     = data.nama_pic || '';
    document.getElementById('d_jabatan').value = data.jabatan_pic || '';
    document.getElementById('d_hp').value      = data.no_hp_pic || '';
    document.getElementById('d_kuota').value   = data.kuota_siswa || 5;
    document.getElementById('d_status').value  = data.status || 'aktif';
    openModal('modal-dudi');
}
</script>
@endpush
@endsection
