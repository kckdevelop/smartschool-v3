@extends('layouts.app')

@section('title', 'Laporan ISMUBA — SmartSchool')
@section('header_title', 'Laporan ISMUBA')
@section('header_subtitle', 'Rekap kegiatan keagamaan BTAQ, Tadarus, dan Penilaian Ibadah')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Filter Bulan --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body" style="padding:16px 20px;">
            <form method="GET" class="search-form" style="gap:10px;flex-wrap:wrap;">
                <label style="font-weight:600;color:var(--text-primary);white-space:nowrap;">
                    <i class="fa-solid fa-calendar-days" style="color:var(--color-primary);"></i> Filter Periode:
                </label>
                <input type="month" name="bulan" value="{{ $bulan }}"
                       class="form-control form-control-sm" style="width:160px;">
                <select name="id_kelas" class="form-control form-control-sm" style="width:140px;">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id_kelas }}" {{ request('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
                <button class="btn btn-primary btn-sm"><i class="fa-solid fa-magnifying-glass"></i> Tampilkan</button>
                <a href="{{ route('ismuba.laporan.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            </form>
        </div>
    </div>

    {{-- Summary stat cards --}}
    <div class="ismuba-stats-row">
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#047857,#10b981);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-book-quran"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $statBtaq }}</div>
                <div class="ismuba-stat-lbl">Sesi BTAQ Bulan Ini</div>
            </div>
        </div>
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#b45309,#f59e0b);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-quran"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $statTadarus }}</div>
                <div class="ismuba-stat-lbl">Sesi Tadarus Bulan Ini</div>
            </div>
        </div>
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-person-praying"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $statIbadah }}</div>
                <div class="ismuba-stat-lbl">Penilaian Ibadah Bulan Ini</div>
            </div>
        </div>
    </div>

    {{-- Rekap BTAQ --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
            <h2 class="card-title" style="color:#047857;">
                <i class="fa-solid fa-book-quran"></i> Rekap BTAQ per Kelas
            </h2>
        </div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kelas</th>
                        <th>Total Sesi</th>
                        <th>Total Siswa Dievaluasi</th>
                        <th>Rata-rata Sesi/Siswa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapBtaq as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $i + 1 }}</td>
                        <td>
                            @if($item->kelas)
                                <span class="badge badge-muted">{{ $item->kelas->nama_kelas }}</span>
                            @else
                                <span class="text-muted">Kelas #{{ $item->id_kelas }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge" style="background:rgba(4,120,87,0.1);color:#047857;border:1px solid rgba(4,120,87,0.2);">
                                {{ $item->total_sesi }} sesi
                            </span>
                        </td>
                        <td style="font-weight:600;">{{ $item->total_siswa }} siswa</td>
                        <td>
                            @php $avg = $item->total_siswa > 0 ? round($item->total_sesi / $item->total_siswa, 1) : 0; @endphp
                            <span style="font-weight:700;color:#047857;">{{ $avg }}x</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Belum ada data BTAQ pada periode ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Rekap Tadarus --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-header">
            <h2 class="card-title" style="color:#b45309;">
                <i class="fa-solid fa-quran"></i> Rekap Tadarus per Kelas
            </h2>
        </div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kelas</th>
                        <th>Total Sesi Tadarus</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapTadarus as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $i + 1 }}</td>
                        <td>
                            @if($item->kelas)
                                <span class="badge badge-muted">{{ $item->kelas->nama_kelas }}</span>
                            @else
                                <span class="text-muted">Kelas #{{ $item->id_kelas }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge" style="background:rgba(180,83,9,0.1);color:#b45309;border:1px solid rgba(180,83,9,0.2);">
                                {{ $item->total_sesi }} sesi
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">
                            Belum ada data tadarus pada periode ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Rekap Ibadah --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title" style="color:#1d4ed8;">
                <i class="fa-solid fa-person-praying"></i> Rekap Penilaian Ibadah per Kelas
            </h2>
        </div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Kelas</th>
                        <th>Jenis Ibadah</th>
                        <th>Total Penilaian</th>
                        <th>Jumlah Siswa Dinilai</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapIbadah as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);">{{ $i + 1 }}</td>
                        <td>
                            @if($item->kelas)
                                <span class="badge badge-muted">{{ $item->kelas->nama_kelas }}</span>
                            @else
                                <span class="text-muted">Kelas #{{ $item->id_kelas }}</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $jenisMap = [
                                    'sholat_fardu'   => ['label' => 'Bacaan Sholat Fardu',   'color' => '#1d4ed8'],
                                    'sholat_jenazah' => ['label' => 'Sholat Jenazah',         'color' => '#7c3aed'],
                                    'gerakan_wudhu'  => ['label' => 'Gerakan Wudhu',           'color' => '#0369a1'],
                                ];
                                $jenisInfo = $jenisMap[$item->jenis_ibadah] ?? ['label' => $item->jenis_ibadah, 'color' => '#64748b'];
                            @endphp
                            <span style="font-weight:600;color:{{ $jenisInfo['color'] }};">
                                <i class="fa-solid fa-circle" style="font-size:0.5rem;vertical-align:middle;margin-right:4px;"></i>
                                {{ $jenisInfo['label'] }}
                            </span>
                        </td>
                        <td>
                            <span class="badge" style="background:rgba(29,78,216,0.1);color:#1d4ed8;border:1px solid rgba(29,78,216,0.2);">
                                {{ $item->total }} penilaian
                            </span>
                        </td>
                        <td style="font-weight:600;">{{ $item->total_siswa }} siswa</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            Belum ada data penilaian ibadah pada periode ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
.ismuba-stats-row { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px; }
.ismuba-stat-card { display:flex; align-items:center; gap:16px; padding:20px; border-radius:var(--radius-card); color:#fff; box-shadow:0 6px 24px rgba(0,0,0,0.12); transition:var(--transition-smooth); }
.ismuba-stat-card:hover { transform:translateY(-3px); box-shadow:0 12px 36px rgba(0,0,0,0.18); }
.ismuba-stat-icon { width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0; }
.ismuba-stat-num { font-size:2rem;font-weight:800;line-height:1; }
.ismuba-stat-lbl { font-size:0.78rem;opacity:.85;margin-top:2px; }
@media(max-width:640px) { .ismuba-stats-row { grid-template-columns:1fr; } }
</style>
@endpush
@endsection
