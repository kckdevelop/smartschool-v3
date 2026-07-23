@extends('layouts.app')

@section('title', 'Gelombang PKL — SmartSchool')
@section('header_title', 'Gelombang PKL')
@section('header_subtitle', 'Pengaturan periode dan gelombang Praktik Kerja Lapangan')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-wave-square" style="color:var(--color-primary);"></i> Daftar Gelombang PKL</h2>
            <button class="btn btn-primary btn-sm" onclick="openAddModal()">
                <i class="fa-solid fa-plus"></i> Tambah Gelombang
            </button>
        </div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Nama Gelombang</th>
                        <th>Tahun Ajaran</th>
                        <th>Periode</th>
                        <th style="min-width:160px;">Kelas</th>
                        <th style="width:80px;text-align:center;">Siswa</th>
                        <th style="width:110px;">Status</th>
                        <th style="width:110px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:.8rem;">{{ $data->firstItem() + $i }}</td>
                        <td style="font-weight:700;">{{ $item->nama_gelombang }}</td>
                        <td>{{ $item->tahun_ajaran }}</td>
                        <td style="font-size:.85rem;">
                            {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }}
                            <span style="color:var(--text-muted);">—</span>
                            {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                        </td>
                        <td>
                            @if($item->kelasGelombang->isEmpty())
                                <span style="color:#CBD5E1;font-size:.76rem;font-style:italic;">Belum dipilih</span>
                            @else
                                <div style="display:flex;flex-wrap:wrap;gap:4px;">
                                    @foreach($item->kelasGelombang as $kg)
                                        <span style="display:inline-block;padding:2px 8px;border-radius:12px;font-size:.72rem;font-weight:600;background:var(--color-primary-light,#EEF2FF);color:var(--color-primary,#4F46E5);white-space:nowrap;">{{ $kg->kelas->nama_kelas ?? $kg->id_kelas }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </td>
                        <td class="text-center"><span class="badge badge-info">{{ $item->siswa_count }}</span></td>
                        <td>
                            @if($item->status === 'aktif')
                                <span class="badge badge-success"><i class="fa-solid fa-circle" style="font-size:.45rem;"></i> Aktif</span>
                            @elseif($item->status === 'selesai')
                                <span class="badge badge-info">Selesai</span>
                            @else
                                <span class="badge badge-muted">Draft</span>
                            @endif
                        </td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editGelombang({{ json_encode($item) }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('pkl.gelombang.destroy', $item->id_gelombang) }}','Hapus gelombang {{ $item->nama_gelombang }}?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-6">
                            <i class="fa-solid fa-wave-square" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada data gelombang PKL
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

{{-- MODAL TAMBAH/EDIT GELOMBANG --}}
<div class="modal-overlay" id="modal-gelombang">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-title-gelombang">Tambah Gelombang PKL</h3>
            <button onclick="closeModal('modal-gelombang')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-gelombang" method="POST" action="{{ route('pkl.gelombang.store') }}">
            @csrf
            <div id="method-field-gelombang"></div>
            <div class="modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Nama Gelombang <span class="required">*</span></label>
                        <input type="text" name="nama_gelombang" id="g_nama" class="form-control"
                            placeholder="Contoh: Gelombang 1 Tahun 2025" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun Ajaran <span class="required">*</span></label>
                        <input type="text" name="tahun_ajaran" id="g_tahun" class="form-control"
                            placeholder="Contoh: 2025/2026" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Mulai <span class="required">*</span></label>
                        <input type="date" name="tanggal_mulai" id="g_mulai" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Selesai <span class="required">*</span></label>
                        <input type="date" name="tanggal_selesai" id="g_selesai" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Status <span class="required">*</span></label>
                    <select name="status" id="g_status" class="form-control" required>
                        <option value="draft">Draft</option>
                        <option value="aktif">Aktif</option>
                        <option value="selesai">Selesai</option>
                    </select>
                    <span class="form-hint">Hanya satu gelombang yang bisa berstatus "Aktif" pada satu waktu.</span>
                </div>
                <div class="form-group">
                    <label class="form-label">Kelas yang Ikut PKL</label>
                    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px;max-height:200px;overflow-y:auto;border:1.5px solid rgba(13,148,136,.15);border-radius:10px;padding:12px;">
                        @php
                            $kelasList = \App\Models\Kelas::where('status','aktif')->orderBy('tingkat')->orderBy('rombel')->get();
                        @endphp
                        @foreach($kelasList as $kls)
                        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:.85rem;font-weight:500;">
                            <input type="checkbox" name="id_kelas[]" value="{{ $kls->id_kelas }}"
                                class="kelas-checkbox" style="accent-color:var(--color-primary);">
                            {{ $kls->nama_kelas ?? ($kls->tingkat . ' ' . $kls->rombel) }}
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="g_keterangan" class="form-control" rows="2"
                        placeholder="Catatan atau informasi tambahan tentang gelombang ini..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-gelombang')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openAddModal() {
    document.getElementById('form-gelombang').action = '{{ route("pkl.gelombang.store") }}';
    document.getElementById('method-field-gelombang').innerHTML = '';
    document.getElementById('modal-title-gelombang').textContent = 'Tambah Gelombang PKL';
    document.getElementById('g_nama').value = '';
    document.getElementById('g_tahun').value = '';
    document.getElementById('g_mulai').value = '';
    document.getElementById('g_selesai').value = '';
    document.getElementById('g_status').value = 'draft';
    document.getElementById('g_keterangan').value = '';
    document.querySelectorAll('.kelas-checkbox').forEach(cb => cb.checked = false);
    openModal('modal-gelombang');
}

function editGelombang(data) {
    document.getElementById('form-gelombang').action = `/pkl/gelombang/${data.id_gelombang}`;
    document.getElementById('method-field-gelombang').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-gelombang').textContent = 'Edit Gelombang PKL';
    document.getElementById('g_nama').value = data.nama_gelombang || '';
    document.getElementById('g_tahun').value = data.tahun_ajaran || '';
    document.getElementById('g_mulai').value = data.tanggal_mulai ? data.tanggal_mulai.substring(0,10) : '';
    document.getElementById('g_selesai').value = data.tanggal_selesai ? data.tanggal_selesai.substring(0,10) : '';
    document.getElementById('g_status').value = data.status || 'draft';
    document.getElementById('g_keterangan').value = data.keterangan || '';

    // Load kelas yang sudah dipilih
    document.querySelectorAll('.kelas-checkbox').forEach(cb => cb.checked = false);
    fetch(`/pkl/gelombang/${data.id_gelombang}/kelas`)
        .then(r => r.json())
        .then(ids => {
            ids.forEach(id => {
                const cb = document.querySelector(`.kelas-checkbox[value="${id}"]`);
                if (cb) cb.checked = true;
            });
        });

    openModal('modal-gelombang');
}
</script>
@endpush
@endsection
