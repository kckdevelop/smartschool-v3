@extends('layouts.app')

@section('title', 'Rekap Presensi Siswa — SmartSchool')
@section('header_title', 'Rekap Presensi')
@section('header_subtitle', 'Tinjau rekapitulasi kehadiran bulanan per kelas')

@section('content')
<style>
/* ─── Grid Cell Styling ─── */
.rekap-cell {
    font-size: 0.78rem;
    font-weight: 700;
    text-align: center;
    padding: 6px 3px !important;
    min-width: 32px;
}
.rekap-header-day {
    text-align: center;
    padding: 6px 3px !important;
    min-width: 32px;
    font-size: 0.7rem;
}
.weekend-col {
    background-color: #ef4444 !important;
    color: #ffffff !important;
}
.weekend-header {
    background-color: #dc2626 !important;
    color: #ffffff !important;
}

/* Badge styles in cell */
.badge-cell {
    display: inline-block;
    width: 22px;
    height: 22px;
    line-height: 22px;
    border-radius: 50%;
    text-align: center;
    font-weight: 700;
    font-size: 0.72rem;
}
.badge-cell-h { background-color: #ecfdf5; color: #10b981; border: 1px solid #a7f3d0; }
.badge-cell-s { background-color: #fffbeb; color: #f59e0b; border: 1px solid #fde68a; }
.badge-cell-i { background-color: #eff6ff; color: #3b82f6; border: 1px solid #bfdbfe; }
.badge-cell-a { background-color: #fef2f2; color: #ef4444; border: 1px solid #fca5a5; }
.badge-cell-none { color: #cbd5e1; font-weight: 400; }

/* Summary headers */
.summary-header {
    background: #f8fafc;
    text-align: center;
    font-weight: 700;
    font-size: 0.75rem;
    border-left: 1.5px solid #e2e8f0;
}
.summary-cell {
    text-align: center;
    font-weight: 600;
    font-size: 0.82rem;
    border-left: 1px solid #f1f5f9;
}
</style>

<div class="page-content">
    @include('partials.flash')

    {{-- Filter Panel --}}
    <div class="card mb-6">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-filter"></i> Filter Rekapitulasi</h2>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('presensi-siswa.rekap') }}" class="search-form" style="display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end;">
                <div class="form-group" style="margin-bottom:0; flex:1; min-width:200px;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:6px;">Pilih Bulan</label>
                    <input type="month" name="bulan" value="{{ $bulan }}" class="form-control" required max="{{ date('Y-m') }}">
                </div>
                <div class="form-group" style="margin-bottom:0; flex:1; min-width:240px;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:6px;">Kelas</label>
                    <select name="id_kelas" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ $id_kelas == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->tingkat }} {{ $k->rombel }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <button type="submit" class="btn btn-primary" style="height:44px; padding:0 24px;"><i class="fa-solid fa-magnifying-glass"></i> Tampilkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Rekapitulasi Grid --}}
    @if($id_kelas && $bulan)
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title">
                    <i class="fa-solid fa-calendar-days"></i> 
                    Rekap Presensi Kelas: 
                    <strong>
                        @php 
                            $selKelas = $kelasList->firstWhere('id_kelas', $id_kelas); 
                        @endphp
                        {{ $selKelas ? $selKelas->tingkat.' '.$selKelas->rombel : '' }}
                    </strong> 
                    — Periode: <strong>{{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}</strong>
                </h2>
                <div class="card-header-right" style="display:flex; gap:8px;">
                    <a href="{{ route('presensi-siswa.rekap.print', ['id_kelas' => $id_kelas, 'bulan' => $bulan]) }}" target="_blank" class="btn btn-secondary btn-sm" style="background-color:#ef4444; border-color:#ef4444; color:#fff;">
                        <i class="fa-solid fa-file-pdf"></i> Cetak PDF
                    </a>
                    <a href="{{ route('presensi-siswa.laporan', ['id_kelas' => $id_kelas, 'tanggal_dari' => $bulan.'-01', 'tanggal_sampai' => \Carbon\Carbon::create($year, $month)->endOfMonth()->toDateString()]) }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-file-invoice"></i> Lihat Laporan Lanjutan
                    </a>
                </div>
            </div>

            @if($siswaList->isEmpty())
                <div class="card-body">
                    <div class="empty-state">
                        <i class="fa-solid fa-users-slash"></i>
                        <p>Tidak ada data siswa aktif di kelas ini.</p>
                    </div>
                </div>
            @else
                <div class="card-body p-0" style="overflow-x:auto;">
                    <table class="data-table" style="margin:0; width:100%;">
                        <thead>
                            <tr>
                                <th style="width:40px; text-align:center; vertical-align:middle;" rowspan="2">#</th>
                                <th style="width:100px; vertical-align:middle;" rowspan="2">NIS</th>
                                <th style="min-width:180px; vertical-align:middle;" rowspan="2">Nama Siswa</th>
                                <th colspan="{{ $daysInMonth }}" style="text-align:center; font-size:0.75rem; background:#f8fafc;">Tanggal</th>
                                <th colspan="5" style="text-align:center; background:#f1f5f9; border-left:1.5px solid #e2e8f0;">Rekap Absensi</th>
                            </tr>
                            <tr>
                                @for($d = 1; $d <= $daysInMonth; $d++)
                                    @php
                                        $isWeekend = \Carbon\Carbon::create($year, $month, $d)->isWeekend();
                                    @endphp
                                    <th class="rekap-header-day {{ $isWeekend ? 'weekend-header weekend-col' : '' }}">
                                        {{ $d }}
                                    </th>
                                @endfor
                                <th class="summary-header" style="color:#10b981; border-left:1.5px solid #cbd5e1;">H</th>
                                <th class="summary-header" style="color:#f59e0b;">S</th>
                                <th class="summary-header" style="color:#3b82f6;">I</th>
                                <th class="summary-header" style="color:#ef4444;">A</th>
                                <th class="summary-header" style="background:#e2e8f0; color:#0f766e;">%</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($rekapData as $index => $data)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="font-mono" style="font-size:0.8rem;">{{ $data['siswa']->nis }}</td>
                                    <td>
                                        <strong>{{ $data['siswa']->nama_siswa }}</strong>
                                    </td>
                                    
                                    {{-- Grid Kehadiran Harian --}}
                                    @for($d = 1; $d <= $daysInMonth; $d++)
                                        @php
                                            $isWeekend = \Carbon\Carbon::create($year, $month, $d)->isWeekend();
                                            $val = $data['grid'][$d];
                                        @endphp
                                        <td class="rekap-cell {{ $isWeekend ? 'weekend-col' : '' }}">
                                            @if($val === 'H')
                                                <span class="badge-cell badge-cell-h" title="Hadir">H</span>
                                            @elseif($val === 'S')
                                                <span class="badge-cell badge-cell-s" title="Sakit">S</span>
                                            @elseif($val === 'I')
                                                <span class="badge-cell badge-cell-i" title="Izin">I</span>
                                            @elseif($val === 'A')
                                                <span class="badge-cell badge-cell-a" title="Alfa">A</span>
                                            @elseif($val === 'W')
                                                {{-- Weekend: tampilkan sel kosong --}}
                                            @else
                                                <span class="badge-cell-none">-</span>
                                            @endif
                                        </td>
                                    @endfor
                                    
                                    {{-- Rekap --}}
                                    <td class="summary-cell" style="color:#10b981; border-left:1.5px solid #cbd5e1;">{{ $data['hadir'] }}</td>
                                    <td class="summary-cell" style="color:#f59e0b;">{{ $data['sakit'] }}</td>
                                    <td class="summary-cell" style="color:#3b82f6;">{{ $data['izin'] }}</td>
                                    <td class="summary-cell" style="color:#ef4444;">{{ $data['alfa'] }}</td>
                                    <td class="summary-cell" style="background:rgba(13,148,136,0.05); color:#0d9488; font-weight:800;">
                                        {{ $data['persentase'] }}%
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                {{-- Legenda --}}
                <div class="card-footer" style="padding:16px 24px; background:#f8fafc; border-top:1.5px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
                    <div style="display:flex; gap:16px; align-items:center; flex-wrap:wrap; font-size:0.8rem; font-weight:600;">
                        <span style="color:var(--text-muted); font-size:0.75rem; text-transform:uppercase;">Keterangan Legenda:</span>
                        <span style="display:flex; align-items:center; gap:6px;"><span class="badge-cell badge-cell-h">H</span> Hadir</span>
                        <span style="display:flex; align-items:center; gap:6px;"><span class="badge-cell badge-cell-s">S</span> Sakit</span>
                        <span style="display:flex; align-items:center; gap:6px;"><span class="badge-cell badge-cell-i">I</span> Izin</span>
                        <span style="display:flex; align-items:center; gap:6px;"><span class="badge-cell badge-cell-a">A</span> Alfa (Tanpa Keterangan)</span>
                        <span style="display:flex; align-items:center; gap:6px;"><span class="badge-cell-none">-</span> Belum diisi / Libur</span>
                    </div>
                    <div style="font-size:0.75rem; font-weight:700; color:var(--text-muted); display:flex; align-items:center; gap:6px;">
                        <div style="width:16px; height:16px; background:#f1f5f9; border:1px solid #cbd5e1; border-radius:4px;"></div> Weekend / Hari Libur
                    </div>
                </div>
            @endif
        </div>
    @else
        <div class="card">
            <div class="card-body py-12 text-center text-muted">
                <i class="fa-solid fa-calendar-days" style="font-size:3.5rem; opacity:0.18; display:block; margin-bottom:14px;"></i>
                <p style="font-size:0.95rem; font-weight:500;">Silakan pilih bulan dan kelas terlebih dahulu untuk melihat rekapitulasi presensi.</p>
            </div>
        </div>
    @endif
</div>
@endsection
