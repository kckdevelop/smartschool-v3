@extends('layouts.app')

@section('title', 'Laporan & Rekap PKL — SmartSchool')
@section('header_title', 'Laporan & Rekap PKL')
@section('header_subtitle', 'Rekapitulasi dan laporan penempatan Praktik Kerja Lapangan')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; padding:18px 24px;">
            <h2 class="card-title" style="margin:0;"><i class="fa-solid fa-chart-line" style="color:var(--color-primary);"></i> Rekapitulasi Data PKL</h2>
            @if($selectedGelombang)
            <a href="{{ route('pkl.laporan.print', [
                'id_gelombang' => $selectedGelombang->id_gelombang,
                'id_dudi'      => request('id_dudi'),
                'status'       => request('status')
            ]) }}" target="_blank" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-print"></i> Cetak Laporan
            </a>
            @endif
        </div>

        {{-- Horizontal Filter Bar --}}
        <div style="padding:14px 24px; border-bottom:1.5px solid #f1f5f9; background:#fafbff;">
            <form method="GET" action="{{ route('pkl.laporan.index') }}" class="flex-row gap-3" style="flex-wrap:wrap; align-items:center; width:100%;">
                <div style="flex:1; min-width:200px; display:flex; flex-direction:column; gap:4px;">
                    <label class="form-label-sm" style="font-weight:700; color:var(--text-secondary); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">Gelombang PKL</label>
                    <select name="id_gelombang" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">-- Pilih Gelombang --</option>
                        @foreach($gelombangList as $g)
                        <option value="{{ $g->id_gelombang }}" {{ optional($selectedGelombang)->id_gelombang == $g->id_gelombang ? 'selected' : '' }}>
                            {{ $g->nama_gelombang }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex:1; min-width:180px; display:flex; flex-direction:column; gap:4px;">
                    <label class="form-label-sm" style="font-weight:700; color:var(--text-secondary); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">DUDI / Perusahaan</label>
                    <select name="id_dudi" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">-- Semua DUDI --</option>
                        @foreach($dudis as $d)
                        <option value="{{ $d->id_dudi }}" {{ request('id_dudi') == $d->id_dudi ? 'selected' : '' }}>{{ $d->nama_dudi }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="flex:1; min-width:150px; display:flex; flex-direction:column; gap:4px;">
                    <label class="form-label-sm" style="font-weight:700; color:var(--text-secondary); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">Status Siswa</label>
                    <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">-- Semua Status --</option>
                        <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="ditarik" {{ request('status') === 'ditarik' ? 'selected' : '' }}>Ditarik</option>
                    </select>
                </div>
                <div style="display:flex; align-items:flex-end; height:38px; margin-top:auto;">
                    <a href="{{ route('pkl.laporan.index') }}" class="btn btn-secondary btn-sm" style="height:35px; display:inline-flex; align-items:center;"><i class="fa-solid fa-rotate"></i> Reset</a>
                </div>
            </form>
        </div>

        @if($selectedGelombang)
        {{-- Stats Overview --}}
        <div style="padding:20px 24px; background:#f8fafc; display:grid; grid-template-columns: repeat(4, 1fr); gap:16px; border-bottom:1.5px solid #f1f5f9;">
            <div style="background:#fff; padding:16px; border-radius:12px; border:1.5px solid #e2e8f0; display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; border-radius:10px; background:#e0f2fe; color:#0369a1; display:flex; align-items:center; justify-content:center; font-size:1.2rem;"><i class="fa-solid fa-users"></i></div>
                <div>
                    <div style="font-size:1.35rem; font-weight:800; color:var(--text-primary);">{{ $penempatanAll->count() }}</div>
                    <div style="font-size:.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px;">Total Siswa PKL</div>
                </div>
            </div>
            <div style="background:#fff; padding:16px; border-radius:12px; border:1.5px solid #e2e8f0; display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; border-radius:10px; background:#d1fae5; color:#065f46; display:flex; align-items:center; justify-content:center; font-size:1.2rem;"><i class="fa-solid fa-user-check"></i></div>
                <div>
                    <div style="font-size:1.35rem; font-weight:800; color:var(--text-primary);">{{ $penempatanAll->where('status', 'aktif')->count() }}</div>
                    <div style="font-size:.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px;">Status Aktif</div>
                </div>
            </div>
            <div style="background:#fff; padding:16px; border-radius:12px; border:1.5px solid #e2e8f0; display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; border-radius:10px; background:#e0f2fe; color:#0369a1; display:flex; align-items:center; justify-content:center; font-size:1.2rem;"><i class="fa-solid fa-graduation-cap"></i></div>
                <div>
                    <div style="font-size:1.35rem; font-weight:800; color:var(--text-primary);">{{ $penempatanAll->where('status', 'selesai')->count() }}</div>
                    <div style="font-size:.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px;">Status Selesai</div>
                </div>
            </div>
            <div style="background:#fff; padding:16px; border-radius:12px; border:1.5px solid #e2e8f0; display:flex; align-items:center; gap:12px;">
                <div style="width:40px; height:40px; border-radius:10px; background:#fef3c7; color:#92400e; display:flex; align-items:center; justify-content:center; font-size:1.2rem;"><i class="fa-solid fa-user-minus"></i></div>
                <div>
                    <div style="font-size:1.35rem; font-weight:800; color:var(--text-primary);">{{ $penempatanAll->where('status', 'ditarik')->count() }}</div>
                    <div style="font-size:.72rem; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:.5px;">Status Ditarik</div>
                </div>
            </div>
        </div>

        <div class="card-body" style="padding:24px; display:grid; grid-template-columns:1fr; gap:24px;">

            {{-- 1. Rekapitulasi per DUDI --}}
            @if($rekapDudi->isNotEmpty() && !request()->filled('id_dudi'))
            <div>
                <h3 style="font-size:.9rem; font-weight:700; margin-bottom:12px; display:flex; align-items:center; gap:8px; color:var(--text-primary);">
                    <i class="fa-solid fa-building" style="color:var(--color-primary);"></i>
                    Rekapitulasi per DUDI
                </h3>
                <div style="overflow-x:auto; border:1.5px solid #e2e8f0; border-radius:12px;">
                    <table class="data-table" style="margin:0;">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th>Nama DUDI / Mitra</th>
                                <th style="width:100px;text-align:center;">Aktif</th>
                                <th style="width:100px;text-align:center;">Selesai</th>
                                <th style="width:100px;text-align:center;">Ditarik</th>
                                <th style="width:120px;text-align:center;background:#ede9fe;color:#5b21b6;font-weight:800;">Total Siswa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rekapDudi as $rd)
                            <tr>
                                <td style="font-weight:700;">{{ $rd->nama_dudi }}</td>
                                <td class="text-center"><span class="badge badge-success">{{ $rd->aktif_siswa }}</span></td>
                                <td class="text-center"><span class="badge badge-info">{{ $rd->selesai_siswa }}</span></td>
                                <td class="text-center"><span class="badge badge-warning">{{ $rd->ditarik_siswa }}</span></td>
                                <td class="text-center" style="font-weight:800; background:#fdfcff; color:var(--color-primary);">{{ $rd->total_siswa }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- 2. Daftar Detail Siswa --}}
            <div>
                <h3 style="font-size:.9rem; font-weight:700; margin-bottom:12px; display:flex; align-items:center; gap:8px; color:var(--text-primary);">
                    <i class="fa-solid fa-users" style="color:var(--color-primary);"></i>
                    Daftar Siswa PKL (Detail)
                </h3>
                <div style="overflow-x:auto; border:1.5px solid #e2e8f0; border-radius:12px;">
                    <table class="data-table" style="margin:0;">
                        <thead>
                            <tr style="background:#f8fafc;">
                                <th style="width:50px;">#</th>
                                <th>Siswa</th>
                                <th>DUDI Tempat PKL</th>
                                <th>Guru Pembimbing</th>
                                <th>Tanggal PKL</th>
                                <th style="width:100px;text-align:center;">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penempatan as $i => $p)
                            <tr>
                                <td style="color:var(--text-muted);font-size:.8rem;text-align:center;">{{ $penempatan->firstItem() + $loop->index }}</td>
                                <td>
                                    <div style="font-weight:700;">{{ $p->nis }}</div>
                                    <div style="font-size:.8rem;color:var(--text-muted);">{{ optional($p->siswa)->nama_siswa ?? '-' }}</div>
                                    <div style="font-size:.75rem;margin-top:2px;"><span class="badge badge-info" style="font-size:.68rem;">{{ optional(optional($p->siswa)->kelas)->nama_kelas ?? '-' }}</span></div>
                                </td>
                                <td>
                                    <div style="font-weight:600;">{{ optional($p->dudi)->nama_dudi ?? '-' }}</div>
                                    <div style="font-size:.78rem;color:var(--text-muted);">{{ optional($p->dudi)->kota ?? '' }}</div>
                                </td>
                                <td style="font-size:.85rem;">{{ optional(optional($p->pembimbing)->guru)->nama_guru ?? '-' }}</td>
                                <td style="font-size:.85rem;">
                                    <div>Masuk: {{ $p->tanggal_masuk ? \Carbon\Carbon::parse($p->tanggal_masuk)->format('d/m/Y') : '-' }}</div>
                                    <div style="color:var(--text-muted);margin-top:2px;">Selesai: {{ $p->tanggal_keluar ? \Carbon\Carbon::parse($p->tanggal_keluar)->format('d/m/Y') : '-' }}</div>
                                </td>
                                <td class="text-center">
                                    @php $colors = ['aktif'=>'success','selesai'=>'info','ditarik'=>'warning','batal'=>'danger']; @endphp
                                    <span class="badge badge-{{ $colors[$p->status] ?? 'muted' }}">{{ ucfirst($p->status) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-6">
                                    <i class="fa-solid fa-file-circle-xmark" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                                    Tidak ada data penempatan PKL untuk filter yang dipilih
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="margin-top: 15px;">
                    {{ $penempatan->links('pagination.presensi') }}
                </div>
            </div>

        </div>
        @else
        <div class="text-center text-muted py-8" style="padding:48px;">
            <i class="fa-solid fa-chart-pie" style="font-size:3rem;opacity:.25;display:block;margin-bottom:12px;"></i>
            Silakan pilih gelombang PKL terlebih dahulu untuk melihat rekapitulasi data.
        </div>
        @endif
    </div>
</div>
@endsection
