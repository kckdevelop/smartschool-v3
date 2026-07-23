@extends('layouts.app')

@section('title', 'Laporan Kehadiran Siswa — SmartSchool')
@section('header_title', 'Laporan Presensi')
@section('header_subtitle', 'Tinjau dan cetak laporan kehadiran siswa berdasarkan periode')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Filter Panel --}}
    <div class="card mb-6">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-filter"></i> Filter Laporan</h2>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('presensi-siswa.laporan') }}" class="search-form" style="display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end;">
                <div class="form-group" style="margin-bottom:0; flex:1.5; min-width:240px;">
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
                <div class="form-group" style="margin-bottom:0; flex:1; min-width:180px;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:6px;">Tanggal Mulai</label>
                    <input type="date" name="tanggal_dari" value="{{ $tanggal_dari }}" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom:0; flex:1; min-width:180px;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:6px;">Tanggal Selesai</label>
                    <input type="date" name="tanggal_sampai" value="{{ $tanggal_sampai }}" class="form-control" required>
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <button type="submit" class="btn btn-primary" style="height:44px; padding:0 24px;"><i class="fa-solid fa-magnifying-glass"></i> Filter</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Laporan Table --}}
    @if($id_kelas)
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
                <h2 class="card-title">
                    <i class="fa-solid fa-file-invoice"></i> 
                    Ringkasan Kehadiran Kelas: 
                    <strong>
                        @php 
                            $selKelas = $kelasList->firstWhere('id_kelas', $id_kelas); 
                        @endphp
                        {{ $selKelas ? $selKelas->tingkat.' '.$selKelas->rombel : '' }}
                    </strong> 
                    — Periode: <strong>{{ \Carbon\Carbon::parse($tanggal_dari)->translatedFormat('d M Y') }} s.d {{ \Carbon\Carbon::parse($tanggal_sampai)->translatedFormat('d M Y') }}</strong>
                </h2>
                
                @if(!empty($laporanData))
                    <div class="card-header-right" style="display:flex; gap:8px;">
                        <a href="{{ route('presensi-siswa.laporan.export-excel', ['id_kelas' => $id_kelas, 'tanggal_dari' => $tanggal_dari, 'tanggal_sampai' => $tanggal_sampai]) }}" 
                           class="btn btn-secondary btn-sm" 
                           style="background-color:#10b981; color:#fff; border-color:#10b981;">
                            <i class="fa-solid fa-file-excel"></i> Ekspor Excel
                        </a>
                        <a href="{{ route('presensi-siswa.laporan.print', ['id_kelas' => $id_kelas, 'tanggal_dari' => $tanggal_dari, 'tanggal_sampai' => $tanggal_sampai]) }}" 
                           target="_blank" 
                           class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-print"></i> Cetak Laporan
                        </a>
                    </div>
                @endif
            </div>

            @if(empty($laporanData))
                <div class="card-body">
                    <div class="empty-state">
                        <i class="fa-solid fa-users-slash"></i>
                        <p>Tidak ada data siswa aktif di kelas ini.</p>
                    </div>
                </div>
            @else
                <div class="card-body p-0" style="overflow-x:auto;">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width:60px; text-align:center;">No</th>
                                <th style="width:140px;">NIS</th>
                                <th>Nama Lengkap</th>
                                <th style="width:100px; text-align:center;">Hadir</th>
                                <th style="width:100px; text-align:center;">Sakit</th>
                                <th style="width:100px; text-align:center;">Izin</th>
                                <th style="width:100px; text-align:center;">Alfa</th>
                                <th style="width:140px; text-align:center;">Hari Efektif</th>
                                <th style="width:160px; text-align:center;">Persentase Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($laporanData as $index => $row)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td class="font-mono">{{ $row['nis'] }}</td>
                                    <td><strong>{{ $row['nama_siswa'] }}</strong></td>
                                    <td class="text-center" style="color:#059669; font-weight:700;">{{ $row['hadir'] }}</td>
                                    <td class="text-center" style="color:#d97706; font-weight:700;">{{ $row['sakit'] }}</td>
                                    <td class="text-center" style="color:#0284c7; font-weight:700;">{{ $row['izin'] }}</td>
                                    <td class="text-center" style="color:#dc2626; font-weight:700;">{{ $row['alfa'] }}</td>
                                    <td class="text-center" style="font-weight:600; color:var(--text-secondary);">{{ $row['total'] }}</td>
                                    <td class="text-center">
                                        @php
                                            $p = $row['persentase'];
                                            $barBg = $p >= 90 ? 'badge-success' : ($p >= 75 ? 'badge-info' : ($p >= 50 ? 'badge-warning' : 'badge-danger'));
                                        @endphp
                                        <span class="badge {{ $barBg }}" style="font-size:0.82rem; padding:4px 10px; font-weight:700;">{{ $p }}%</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @else
        <div class="card">
            <div class="card-body py-12 text-center text-muted">
                <i class="fa-solid fa-file-lines" style="font-size:3.5rem; opacity:0.18; display:block; margin-bottom:14px;"></i>
                <p style="font-size:0.95rem; font-weight:500;">Silakan filter kelas dan tanggal terlebih dahulu untuk meninjau laporan.</p>
            </div>
        </div>
    @endif
</div>
@endsection
