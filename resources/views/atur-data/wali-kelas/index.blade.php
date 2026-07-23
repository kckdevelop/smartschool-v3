@extends('layouts.app')

@section('title', 'Data Wali Amanah — SmartSchool')
@section('header_title', 'Data Wali Amanah')
@section('header_subtitle', 'Penetapan wali kelas untuk setiap rombongan belajar')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-person-chalkboard"></i> Penetapan Wali Kelas</h2>
            <form method="GET" class="search-form">
                <select name="tingkat" class="form-control form-control-sm">
                    <option value="">Semua Tingkat</option>
                    <option value="10" {{ request('tingkat')=='10'?'selected':'' }}>Kelas 10</option>
                    <option value="11" {{ request('tingkat')=='11'?'selected':'' }}>Kelas 11</option>
                    <option value="12" {{ request('tingkat')=='12'?'selected':'' }}>Kelas 12</option>
                </select>
                <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-filter"></i></button>
            </form>
        </div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>Kelas</th><th>Jurusan</th><th>Status</th><th>Wali Kelas Saat Ini</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($kelasList as $kelas)
                    <tr>
                        <td>{{ $kelasList->firstItem() + $loop->index }}</td>
                        <td><strong>{{ $kelas->tingkat }} {{ $kelas->rombel }}</strong></td>
                        <td>{{ $kelas->jurusan->nama_jurusan ?? '-' }}</td>
                        <td><span class="badge {{ $kelas->status === 'aktif' ? 'badge-success' : 'badge-muted' }}">{{ ucfirst($kelas->status) }}</span></td>
                        <td>
                            @if($kelas->guru)
                                <div class="flex-row gap-2">
                                    <span class="avatar-xs">{{ strtoupper(substr($kelas->guru->nama_guru,0,1)) }}</span>
                                    {{ $kelas->guru->nama_guru }}
                                </div>
                            @else
                                <span class="text-muted italic">Belum ditetapkan</span>
                            @endif
                        </td>
                        <td class="action-cell">
                            <button class="btn btn-primary btn-xs" onclick="tetapkan({{ $kelas->id_kelas }},'{{ $kelas->tingkat }} {{ addslashes($kelas->rombel) }}',{{ $kelas->walikelas ?? 'null' }})">
                                <i class="fa-solid fa-user-tie"></i> Tetapkan
                            </button>
                            @if($kelas->walikelas)
                            <form action="{{ route('atur-data.wali-kelas.lepas', $kelas->id_kelas) }}" method="POST"
                                  onsubmit="return confirm('Lepas wali kelas dari kelas ini?')">
                                @csrf
                                <button class="btn btn-danger btn-xs"><i class="fa-solid fa-user-minus"></i> Lepas</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-muted py-6">Belum ada data kelas</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($kelasList->hasPages())
        <div style="padding: 14px 20px; border-top: 1px solid var(--border-color);">
            {{ $kelasList->links('pagination.presensi') }}
        </div>
        @endif
    </div>
</div>

{{-- Modal Tetapkan Wali --}}
<div class="modal-overlay" id="modal-tetapkan">
    <div class="modal">
        <div class="modal-header">
            <h3>Tetapkan Wali Kelas</h3>
            <button onclick="closeModal('modal-tetapkan')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-tetapkan" action="" method="POST">
            @csrf
            <div class="modal-body">
                <p class="text-muted mb-4">Kelas: <strong id="nama-kelas"></strong></p>
                <div class="form-group">
                    <label class="form-label">Pilih Guru <span class="required">*</span></label>
                    <select name="id_guru" id="sel_guru" class="form-control" required>
                        <option value="">-- Pilih Guru --</option>
                        @foreach($guruList as $g)
                            <option value="{{ $g->id_guru }}">{{ $g->nama_guru }} ({{ $g->no_id }})</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-tetapkan')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-check"></i> Tetapkan</button>
            </div>
        </form>
    </div>
</div>

<script>
function tetapkan(idKelas, namaKelas, currentGuru) {
    document.getElementById('form-tetapkan').action = `/atur-data/wali-kelas/${idKelas}/tetapkan`;
    document.getElementById('nama-kelas').textContent = namaKelas;
    if (currentGuru) document.getElementById('sel_guru').value = currentGuru;
    openModal('modal-tetapkan');
}
</script>
@endsection
