@extends('layouts.app')

@section('title', 'Input Presensi Siswa — SmartSchool')
@section('header_title', 'Input Presensi')
@section('header_subtitle', 'Input manual kehadiran siswa harian')

@section('content')
<style>
/* ─── Status Options Styling ─── */
.status-options {
    display: flex;
    gap: 6px;
    justify-content: center;
}
.status-option {
    position: relative;
    cursor: pointer;
}
.status-option input {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}
.status-label {
    display: inline-block;
    padding: 5px 12px;
    font-size: 0.78rem;
    font-weight: 700;
    border-radius: 20px;
    border: 1.5px solid #cbd5e1;
    color: #64748b;
    background: #fff;
    transition: all 0.2s ease;
    user-select: none;
    text-align: center;
    min-width: 65px;
}
.status-option:hover .status-label {
    border-color: #94a3b8;
    color: #475569;
}
/* Hadir - Green */
.status-option-hadir input:checked + .status-label {
    background-color: #10b981;
    border-color: #10b981;
    color: #fff;
    box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2);
}
/* Sakit - Yellow */
.status-option-sakit input:checked + .status-label {
    background-color: #f59e0b;
    border-color: #f59e0b;
    color: #fff;
    box-shadow: 0 4px 10px rgba(245, 158, 11, 0.2);
}
/* Izin - Blue */
.status-option-izin input:checked + .status-label {
    background-color: #3b82f6;
    border-color: #3b82f6;
    color: #fff;
    box-shadow: 0 4px 10px rgba(59, 130, 246, 0.2);
}
/* Alfa - Red */
.status-option-alfa input:checked + .status-label {
    background-color: #ef4444;
    border-color: #ef4444;
    color: #fff;
    box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2);
}

.table-input-text {
    width: 100%;
    padding: 6px 10px;
    font-size: 0.85rem;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    transition: var(--transition-smooth);
}
.table-input-text:focus {
    border-color: var(--color-primary);
    outline: none;
    box-shadow: 0 0 0 3px rgba(13,148,136,0.1);
}

.table-input-file {
    font-size: 0.78rem;
    width: 100%;
}
</style>

<div class="page-content">
    @include('partials.flash')

    {{-- Filter Panel --}}
    <div class="card mb-6">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-filter"></i> Pilih Kelas & Tanggal</h2>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('presensi-siswa.input') }}" class="search-form" style="display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end;">
                <div class="form-group" style="margin-bottom:0; flex:1; min-width:200px;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:6px;">Tanggal Presensi</label>
                    <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" required max="{{ date('Y-m-d') }}">
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

    {{-- Data Input Form --}}
    @if($id_kelas)
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa-solid fa-clipboard-user"></i> 
                    Presensi Kelas: 
                    <strong>
                        @php 
                            $selKelas = $kelasList->firstWhere('id_kelas', $id_kelas); 
                        @endphp
                        {{ $selKelas ? $selKelas->tingkat.' '.$selKelas->rombel : '' }}
                    </strong> 
                    — Tanggal: <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</strong>
                </h2>
            </div>
            
            @if($siswaList->isEmpty())
                <div class="card-body">
                    <div class="empty-state">
                        <i class="fa-solid fa-users-slash"></i>
                        <p>Tidak ada data siswa aktif di kelas ini.</p>
                    </div>
                </div>
            @else
                <form action="{{ route('presensi-siswa.input.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="tanggal" value="{{ $tanggal }}">
                    <input type="hidden" name="id_kelas" value="{{ $id_kelas }}">
                    
                    <div class="card-body p-0" style="overflow-x:auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width:50px;">#</th>
                                    <th style="width:120px;">NIS</th>
                                    <th>Nama Siswa</th>
                                    <th style="width:340px; text-align:center;">Status Presensi</th>
                                    <th style="min-width:200px;">Keterangan / Alasan</th>
                                    <th style="width:250px;">Lampiran Berkas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($siswaList as $index => $siswa)
                                    @php
                                        $p = $existingPresensi->get($siswa->nis);
                                        // Normalize status for checkbox mapping
                                        $currStatus = '1'; // Default: Hadir
                                        if ($p) {
                                            $normName = match(strval($p->status)) {
                                                '1', 'Hadir' => '1',
                                                '2', 'Sakit' => '2',
                                                '3', 'Izin' => '3',
                                                '4', 'Alfa', 'Alpha' => '4',
                                                default => '1'
                                            };
                                            $currStatus = $normName;
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="font-mono">{{ $siswa->nis }}</td>
                                        <td>
                                            <strong>{{ $siswa->nama_siswa }}</strong>
                                            <div style="font-size:0.75rem; color:var(--text-muted);">{{ $siswa->jenkel == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                        </td>
                                        <td>
                                            <div class="status-options">
                                                <label class="status-option status-option-hadir">
                                                    <input type="radio" name="presensi[{{ $siswa->nis }}][status]" value="1" {{ $currStatus === '1' ? 'checked' : '' }}>
                                                    <span class="status-label">Hadir</span>
                                                </label>
                                                <label class="status-option status-option-sakit">
                                                    <input type="radio" name="presensi[{{ $siswa->nis }}][status]" value="2" {{ $currStatus === '2' ? 'checked' : '' }}>
                                                    <span class="status-label">Sakit</span>
                                                </label>
                                                <label class="status-option status-option-izin">
                                                    <input type="radio" name="presensi[{{ $siswa->nis }}][status]" value="3" {{ $currStatus === '3' ? 'checked' : '' }}>
                                                    <span class="status-label">Izin</span>
                                                </label>
                                                <label class="status-option status-option-alfa">
                                                    <input type="radio" name="presensi[{{ $siswa->nis }}][status]" value="4" {{ $currStatus === '4' ? 'checked' : '' }}>
                                                    <span class="status-label">Alfa</span>
                                                </label>
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" name="presensi[{{ $siswa->nis }}][keterangan]" 
                                                   value="{{ old("presensi.{$siswa->nis}.keterangan", $p->keterangan ?? '') }}" 
                                                   class="table-input-text" 
                                                   placeholder="Contoh: Sakit demam, Izin acara keluarga, dll.">
                                        </td>
                                        <td>
                                            <input type="file" name="presensi[{{ $siswa->nis }}][file]" class="table-input-file" accept="image/*,.pdf">
                                            @if($p && $p->file)
                                                <div style="display:flex; align-items:center; gap:8px; margin-top:6px; font-size:0.75rem; background:#f0fdfa; padding:4px 8px; border-radius:6px; border:1px solid #ccfbf1; color:#0f766e; width:fit-content;">
                                                    <a href="{{ asset('storage/' . $p->file) }}" target="_blank" style="color:#0d9488; text-decoration:none; font-weight:700;">
                                                        <i class="fa-solid fa-file-image"></i> Lihat File
                                                    </a>
                                                    <span style="color:#cbd5e1;">|</span>
                                                    <label style="margin:0; cursor:pointer; color:#b91c1c; display:flex; align-items:center; gap:3px;">
                                                        <input type="checkbox" name="presensi[{{ $siswa->nis }}][delete_file]" value="1" style="transform:scale(0.95);"> Hapus File
                                                    </label>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer" style="display:flex; justify-content:flex-end; gap:12px; padding:16px 24px; background:#f8fafc; border-top:1.5px solid #e2e8f0;">
                        <button type="submit" class="btn btn-primary" style="padding:0 28px; height:44px;"><i class="fa-solid fa-floppy-disk"></i> Simpan Data Presensi</button>
                    </div>
                </form>
            @endif
        </div>
    @else
        <div class="card">
            <div class="card-body py-12 text-center text-muted">
                <i class="fa-solid fa-clipboard-user" style="font-size:3.5rem; opacity:0.18; display:block; margin-bottom:14px;"></i>
                <p style="font-size:0.95rem; font-weight:500;">Silakan pilih tanggal dan kelas terlebih dahulu untuk mengisi presensi.</p>
            </div>
        </div>
    @endif
</div>
@endsection
