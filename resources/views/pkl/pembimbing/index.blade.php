@extends('layouts.app')

@section('title', 'Pembimbing PKL — SmartSchool')
@section('header_title', 'Pembimbing PKL')
@section('header_subtitle', 'Mapping guru pembimbing ke DUDI per gelombang')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Info Gelombang Aktif --}}
    <div style="background: var(--bg-card); padding: 16px 24px; border-radius: var(--radius-card); border-left: 4px solid var(--color-primary); margin-bottom: 20px; display: flex; align-items: center; justify-content: space-between; box-shadow: var(--shadow-sm);">
        <div style="display: flex; align-items: center; gap: 12px;">
            <i class="fa-solid fa-circle-info" style="color: var(--color-primary); font-size: 1.25rem;"></i>
            <div>
                <div style="font-weight: 700; color: var(--text-primary);">Informasi Gelombang Aktif</div>
                <div style="font-size: 0.85rem; color: var(--text-muted);">
                    Gelombang Aktif Saat Ini: <strong>{{ $gelombangAktif->nama_gelombang ?? 'Tidak ada gelombang aktif' }}</strong> (Tahun Ajaran: {{ $gelombangAktif->tahun_ajaran ?? '-' }})
                </div>
            </div>
        </div>
        @if($gelombangAktif)
        <span class="badge badge-success">Aktif</span>
        @endif
    </div>

    <div class="card">
        <div class="card-header" style="flex-wrap: wrap; gap: 15px;">
            <h2 class="card-title"><i class="fa-solid fa-building-user" style="color:var(--color-primary);"></i> Data DUDI & Pembimbing PKL</h2>
            <div class="card-header-right" style="width: 100%; display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap;">
                {{-- Form Pencarian & Filter Gelombang --}}
                <form method="GET" action="{{ route('pkl.pembimbing.index') }}" style="display: flex; gap: 10px; width: 100%; justify-content: flex-end; align-items: center;">
                    <div style="position: relative; max-width: 250px; width: 100%;">
                        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama DUDI..." value="{{ request('search') }}" style="padding-right: 30px;">
                        <i class="fa-solid fa-magnifying-glass" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 0.8rem;"></i>
                    </div>
                    <select name="id_gelombang" class="form-control form-control-sm" onchange="this.form.submit()" style="min-width:200px; max-width: 250px;">
                        <option value="">-- Semua Gelombang --</option>
                        @foreach($gelombangList as $g)
                        <option value="{{ $g->id_gelombang }}" {{ $selectedId == $g->id_gelombang ? 'selected' : '' }}>
                            {{ $g->nama_gelombang }}
                        </option>
                        @endforeach
                    </select>
                    @if(request('search'))
                    <a href="{{ route('pkl.pembimbing.index', ['id_gelombang' => $selectedId]) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-arrows-rotate"></i> Reset</a>
                    @endif
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filter</button>
                </form>
                {{-- Tombol Cetak PDF --}}
                <a href="{{ route('pkl.pembimbing.cetak', $selectedId ? ['id_gelombang' => $selectedId] : []) }}"
                   target="_blank"
                   class="btn btn-sm"
                   style="background: #dc2626; color: #fff; display: flex; align-items: center; gap: 6px; white-space: nowrap; padding: 6px 14px; border-radius: 6px; font-weight: 600; text-decoration: none;">
                    <i class="fa-solid fa-file-pdf"></i> Cetak PDF
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>DUDI / Perusahaan</th>
                        <th>Bidang Usaha</th>
                        <th>Alamat & Kota</th>
                        <th style="min-width: 250px;">Guru Pembimbing</th>
                        <th>Siswa Dibimbing</th>
                        <th style="width:100px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $item)
                    @php
                        $pembimbingRecord = $item->pembimbing->first();
                        $currentGuru = $pembimbingRecord ? $pembimbingRecord->guru : null;
                        $siswaCount = $selectedId && $pembimbingRecord 
                            ? $pembimbingRecord->penempatan->count() 
                            : $item->penempatan()->where('id_gelombang', $selectedId)->whereIn('status', ['aktif', 'selesai'])->count();
                    @endphp
                    <tr>
                        <td style="color:var(--text-muted);font-size:.8rem;">{{ $data->firstItem() + $i }}</td>
                        <td>
                            <div style="font-weight:700; color: var(--text-primary);">{{ $item->nama_dudi }}{{ $item->kecamatan ? ' (Kec. ' . $item->kecamatan . ')' : '' }}</div>
                        </td>
                        <td>
                            <span style="font-size: 0.85rem; color: var(--text-muted);">{{ $item->bidang_usaha ?? '-' }}</span>
                        </td>
                        <td>
                            <div style="font-size: 0.85rem; max-width: 250px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->alamat }}">
                                {{ $item->alamat }}
                            </div>
                            <span style="font-size: 0.78rem; font-weight: 600; color: var(--text-muted);">{{ $item->kota ?? '-' }}</span>
                        </td>
                        <td>
                            @if($currentGuru)
                                <div style="font-weight:600; color: var(--color-success); margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-solid fa-circle-check"></i> {{ $currentGuru->nama_guru }}{{ $currentGuru->kecamatan ? ' (Kec. ' . $currentGuru->kecamatan . ')' : '' }}
                                </div>
                                <button class="btn btn-outline-primary btn-sm" style="padding: 2px 8px; font-size: 0.75rem; border-radius: 4px;" onclick="openSettingModal({{ json_encode($item) }}, {{ $currentGuru->id_guru }})">
                                    <i class="fa-solid fa-pen-to-square"></i> Setting Pembimbing
                                </button>
                            @else
                                <div style="color:var(--text-muted); font-style:italic; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                                    <i class="fa-regular fa-circle-dot"></i> Belum diset
                                </div>
                                <button class="btn btn-outline-primary btn-sm" style="padding: 2px 8px; font-size: 0.75rem; border-radius: 4px;" onclick="openSettingModal({{ json_encode($item) }}, null)">
                                    <i class="fa-solid fa-plus"></i> Setting Pembimbing
                                </button>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge badge-info">{{ $siswaCount }} siswa</span>
                        </td>
                        <td class="action-cell" style="text-align: center;">
                            <button type="button" class="btn-icon btn-edit" title="Setting Pembimbing" onclick="openSettingModal({{ json_encode($item) }}, {{ $currentGuru ? $currentGuru->id_guru : 'null' }})">
                                <i class="fa-solid fa-gear"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-6">
                            <i class="fa-solid fa-building-circle-exclamation" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Tidak ditemukan data DUDI.
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

{{-- MODAL SETTING PEMBIMBING --}}
<div class="modal-overlay" id="modal-pembimbing">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3 id="modal-title-pembimbing">Setting Pembimbing PKL</h3>
            <button onclick="closeModal('modal-pembimbing')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-pembimbing" method="POST" action="{{ route('pkl.pembimbing.store') }}">
            @csrf
            <input type="hidden" name="id_gelombang" value="{{ $selectedId }}">
            <input type="hidden" name="id_dudi" id="pb_dudi_id">
            
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">DUDI / Perusahaan</label>
                    <input type="text" id="pb_dudi_name" class="form-control" readonly style="background: var(--bg-body); font-weight: 600;">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Guru Pembimbing <span class="required">*</span></label>
                    <select name="id_guru" id="pb_guru" class="form-control">
                        <option value="">-- Tanpa Pembimbing --</option>
                        @foreach($guru as $g)
                        <option value="{{ $g->id_guru }}" data-original-text="{{ $g->nama_guru }}">
                            {{ $g->nama_guru }}{{ $g->kecamatan ? ' (Kec. ' . $g->kecamatan . ')' : '' }}
                        </option>
                        @endforeach
                    </select>
                    <small style="display: block; margin-top: 6px; color: var(--text-muted); font-size: 0.78rem;">
                        Note: Guru pembimbing dapat mendampingi beberapa DUDI.
                    </small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-pembimbing')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openSettingModal(dudi, currentGuruId) {
    const dudiKec = dudi.kecamatan ? ' (Kec. ' + dudi.kecamatan + ')' : '';
    document.getElementById('modal-title-pembimbing').textContent = 'Setting Pembimbing — ' + dudi.nama_dudi + dudiKec;
    document.getElementById('pb_dudi_id').value = dudi.id_dudi;
    document.getElementById('pb_dudi_name').value = dudi.nama_dudi + dudiKec;
    
    const selectGuru = document.getElementById('pb_guru');
    selectGuru.value = currentGuruId || '';
    
    openModal('modal-pembimbing');
}
</script>
@endpush
@endsection
