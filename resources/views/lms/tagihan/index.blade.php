@extends('layouts.app')

@section('title', 'Tagihan Tugas Siswa — SmartSchool')
@section('header_title', 'Tagihan Tugas')
@section('header_subtitle', 'Pantau pengumpulan tugas dan hasil pengerjaan siswa')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-list-check"></i> Monitoring Tagihan Tugas</h2>
            <div class="card-header-right" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <form method="GET" class="search-form" style="display: flex; gap: 8px; align-items: center;">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nama / NIS siswa..." class="form-control form-control-sm" style="width: 150px;">
                    
                    <select name="id_tugas" class="form-control form-control-sm" onchange="this.form.submit()" style="width: 160px;">
                        <option value="">-- Semua Tugas --</option>
                        @foreach($tugas as $t)
                            <option value="{{ $t->id_tugas }}" {{ request('id_tugas') == $t->id_tugas ? 'selected' : '' }}>{{ $t->judul }}</option>
                        @endforeach
                    </select>

                    <select name="id_kelas" class="form-control form-control-sm" onchange="this.form.submit()" style="width: 130px;">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->tingkat }} {{ $k->rombel }}</option>
                        @endforeach
                    </select>

                    <select name="status_tugas" class="form-control form-control-sm" onchange="this.form.submit()" style="width: 130px;">
                        <option value="">-- Semua Status --</option>
                        <option value="belum" {{ request('status_tugas') === 'belum' ? 'selected' : '' }}>Belum Kumpul</option>
                        <option value="sudah" {{ request('status_tugas') === 'sudah' ? 'selected' : '' }}>Perlu Diperiksa</option>
                        <option value="cek" {{ request('status_tugas') === 'cek' ? 'selected' : '' }}>Selesai Dicek</option>
                    </select>
                    <button class="btn btn-secondary btn-sm" type="submit"><i class="fa-solid fa-search"></i></button>
                    @if(request('search') || request('id_tugas') || request('id_kelas') || request('status_tugas'))
                        <a href="{{ route('lms.tagihan.index') }}" class="btn btn-secondary btn-sm" title="Reset Filter"><i class="fa-solid fa-arrow-rotate-left"></i></a>
                    @endif
                </form>
            </div>
        </div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">#</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Tugas</th>
                        <th>Guru Pengampu</th>
                        <th style="width: 140px; text-align: center;">Status</th>
                        <th style="width: 120px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tagihanList as $tagihan)
                    <tr>
                        <td style="text-align: center;">{{ $tagihanList->firstItem() + $loop->index }}</td>
                        <td>
                            <strong style="color: var(--text-primary); font-size: 0.88rem;">{{ $tagihan->siswa->nama_siswa ?? 'Siswa Terhapus' }}</strong>
                            <div class="text-muted" style="font-size: 0.75rem;">NIS: {{ $tagihan->nis }}</div>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ $tagihan->siswa->kelas->tingkat ?? '' }} {{ $tagihan->siswa->kelas->rombel ?? '' }}</span>
                        </td>
                        <td>
                            <a href="{{ route('lms.tugas.show', $tagihan->id_tugas) }}" style="font-weight: 600; color: var(--color-primary, #0d9488);">
                                {{ $tagihan->tugas->judul_tugas ?? 'Tugas Terhapus' }}
                            </a>
                        </td>
                        <td>{{ $tagihan->tugas->guru->nama_guru ?? '-' }}</td>
                        <td style="text-align: center;">
                            @if($tagihan->status_tugas === 'cek')
                                <span class="badge badge-success" style="font-size: 0.75rem; padding: 4px 8px;"><i class="fa-solid fa-circle-check"></i> Selesai Dicek</span>
                            @elseif($tagihan->status_tugas === 'sudah')
                                <span class="badge" style="font-size: 0.75rem; padding: 4px 8px; background: #eab308; color: white;"><i class="fa-solid fa-circle-info"></i> Perlu Diperiksa</span>
                            @else
                                <span class="badge badge-muted" style="font-size: 0.75rem; padding: 4px 8px;"><i class="fa-solid fa-clock"></i> Belum Kumpul</span>
                            @endif
                        </td>
                        <td class="action-cell" style="text-align: center; gap: 8px;">
                            <a href="{{ route('lms.tagihan.show', $tagihan->id_tagihan) }}" class="btn btn-primary btn-sm" style="font-size: 0.78rem; padding: 4px 8px; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="fa-solid fa-file-signature"></i> Periksa
                            </a>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('lms.tagihan.destroy', $tagihan->id_tagihan) }}', 'Yakin ingin menghapus record tagihan tugas siswa ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-6">Belum ada data tagihan tugas siswa yang sesuai dengan filter.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($tagihanList->hasPages())
        <div style="padding: 14px 20px; border-top: 1px solid var(--border-color);">
            {{ $tagihanList->appends(request()->query())->links('pagination.presensi') }}
        </div>
        @endif
    </div>
</div>
@endsection
