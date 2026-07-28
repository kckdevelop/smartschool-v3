@extends('layouts.app')

@section('title', 'Laporan Kunjungan UKS — SmartSchool')
@section('header_title', 'Laporan Kunjungan UKS')
@section('header_subtitle', 'Analisis dan rekapitulasi data kunjungan UKS per semester')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Filter Card --}}
    <div class="card" style="margin-bottom:20px;">
        <div class="card-body">
            <form method="GET" action="{{ route('uks.laporan.index') }}" id="filterForm"
                  style="display:flex; gap:12px; align-items:flex-end; flex-wrap:wrap;">

                {{-- Tahun Ajaran --}}
                <div class="form-group" style="margin:0; min-width:180px;">
                    <label class="form-label" style="margin-bottom:4px; font-size:0.75rem;">Tahun Ajaran</label>
                    <select name="id_tahun" id="selTahun" class="form-control form-control-sm">
                        @foreach($tahunList as $ta)
                            <option value="{{ $ta->id_tahun }}" {{ $idTahun == $ta->id_tahun ? 'selected' : '' }}>
                                {{ $ta->tahun ?? $ta->id_tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Semester --}}
                <div class="form-group" style="margin:0; min-width:220px;">
                    <label class="form-label" style="margin-bottom:4px; font-size:0.75rem;">Semester</label>
                    <select name="id_semester" id="selSemester" class="form-control form-control-sm">
                        @foreach($semesterListForTahun as $sem)
                            <option value="{{ $sem->id_semester }}" {{ $idSemester == $sem->id_semester ? 'selected' : '' }}>
                                {{ $sem->semester === 'Ganjil' ? 'Semester 1 (Ganjil)' : 'Semester 2 (Genap)' }}
                                — {{ \Carbon\Carbon::parse($sem->awal)->format('Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Submit & Reset --}}
                <div style="display:flex; gap:8px;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('uks.laporan.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fa-solid fa-rotate-left"></i> Reset
                    </a>
                </div>
            </form>

            @if($semesterObj)
            <div style="margin-top:10px; font-size:0.82rem; color:var(--text-muted); display:flex; align-items:center; gap:6px;">
                <i class="fa-solid fa-calendar-days" style="color:var(--color-primary);"></i>
                <span>Periode aktif: <strong>{{ $semesterLabel }}</strong></span>
            </div>
            @endif
        </div>
    </div>

    {{-- Report Tabs Navigation --}}
    <div class="report-tabs-nav">
        <button type="button" class="report-tab-btn active" data-target="tab-kunjungan">
            <i class="fa-solid fa-notes-medical"></i> Kunjungan UKS Siswa
        </button>
        <button type="button" class="report-tab-btn" data-target="tab-kunjungan-gukar">
            <i class="fa-solid fa-user-nurse"></i> Kunjungan UKS Gukar
        </button>
        <button type="button" class="report-tab-btn" data-target="tab-imt">
            <i class="fa-solid fa-weight-scale"></i> IMT Siswa per Kelas
        </button>
        <button type="button" class="report-tab-btn" data-target="tab-gukar">
            <i class="fa-solid fa-user-doctor"></i> Check-Up Guru &amp; Karyawan
        </button>
    </div>

    {{-- TAB 1: Kunjungan UKS --}}
    <div class="tab-content-pane active" id="tab-kunjungan">


        {{-- Detail Rekap Kunjungan --}}
        <div class="card" style="margin-top:20px;">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-list"></i> Rincian Kunjungan Periode Terpilih</h2>
                <div class="card-header-right">
                    <a href="{{ route('uks.laporan.print', ['id_semester' => $idSemester]) }}"
                       target="_blank" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-file-pdf"></i> Cetak PDF Kunjungan
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Tanggal &amp; Jam</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Kelas</th>
                            <th>Keluhan</th>
                            <th>Diagnosa</th>
                            <th>Tindakan</th>
                            <th>Obat Diberikan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjunganList as $idx => $k)
                        <tr>
                            <td style="color:var(--text-muted);font-size:0.8rem;">{{ $kunjunganList->firstItem() + $loop->index }}</td>
                            <td>
                                <div style="font-weight:700;font-size:0.85rem;">
                                    {{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d M Y') }}
                                </div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">
                                    {{ \Carbon\Carbon::parse($k->jam)->format('H:i') }} WIB
                                </div>
                            </td>
                            <td><span class="badge badge-info">{{ $k->nis }}</span></td>
                            <td style="font-weight:600;">{{ $k->siswa?->nama_siswa ?? '-' }}</td>
                            <td>{{ $k->siswa?->kelas?->nama_kelas ?? '-' }}</td>
                            <td>{{ $k->keluhan }}</td>
                            <td><span class="badge badge-warning">{{ $k->diagnosa }}</span></td>
                            <td><span class="badge badge-success">{{ $k->tindakan }}</span></td>
                            <td>
                                @if($k->riwayatObat && $k->riwayatObat->isNotEmpty())
                                    <div style="display:flex; flex-direction:column; gap:4px;">
                                        @foreach($k->riwayatObat as $o)
                                            <span class="badge badge-muted" style="font-size:0.75rem; justify-content:flex-start;">
                                                <i class="fa-solid fa-pills" style="color:var(--color-primary); margin-right:4px;"></i>
                                                {{ $o->nama_obat }} ({{ $o->jumlah }} - {{ $o->dosis }})
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-6">Tidak ada data kunjungan pada periode ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $kunjunganList->links('pagination.presensi') }}
            </div>
        </div>
    </div>

    {{-- TAB 2: Kunjungan UKS Gukar --}}
    <div class="tab-content-pane" id="tab-kunjungan-gukar">
        <div class="card" style="margin-top:20px;">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-user-nurse"></i> Rincian Kunjungan UKS Guru &amp; Karyawan</h2>
                <div class="card-header-right">
                    <a href="{{ route('uks.laporan.print-kunjungan-gukar', ['id_semester' => $idSemester]) }}"
                       target="_blank" class="btn btn-primary btn-sm">
                        <i class="fa-solid fa-file-pdf"></i> Cetak PDF Kunjungan Gukar
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:60px;">#</th>
                            <th>Tanggal &amp; Jam</th>
                            <th style="width:110px; text-align:center;">Peran</th>
                            <th>Nama</th>
                            <th>NIP / No. ID</th>
                            <th>Keluhan</th>
                            <th>Diagnosa</th>
                            <th>Tindakan</th>
                            <th>Obat Diberikan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($kunjunganGukarList as $idx => $kg)
                        @php
                            $isGuru  = !is_null($kg->id_guru);
                            $namaGK  = $isGuru ? ($kg->guru?->nama_guru ?? '-') : ($kg->karyawan?->nama_karyawan ?? '-');
                            $noIdGK  = $isGuru ? ($kg->guru?->no_id ?? '-') : ($kg->karyawan?->no_id ?? '-');
                            $peranGK = $isGuru ? 'Guru' : 'Karyawan';
                        @endphp
                        <tr>
                            <td style="color:var(--text-muted);font-size:0.8rem;">{{ $kunjunganGukarList->firstItem() + $loop->index }}</td>
                            <td>
                                <div style="font-weight:700;font-size:0.85rem;">
                                    {{ \Carbon\Carbon::parse($kg->tanggal)->translatedFormat('d M Y') }}
                                </div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">
                                    {{ \Carbon\Carbon::parse($kg->jam)->format('H:i') }} WIB
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge {{ $isGuru ? 'badge-info' : 'badge-warning' }}">
                                    {{ $peranGK }}
                                </span>
                            </td>
                            <td style="font-weight:600;">{{ $namaGK }}</td>
                            <td><span class="badge badge-muted">{{ $noIdGK }}</span></td>
                            <td>{{ $kg->keluhan }}</td>
                            <td><span class="badge badge-warning">{{ $kg->diagnosa }}</span></td>
                            <td><span class="badge badge-success">{{ $kg->tindakan }}</span></td>
                            <td>
                                @if($kg->riwayatObat && $kg->riwayatObat->isNotEmpty())
                                    <div style="display:flex; flex-direction:column; gap:4px;">
                                        @foreach($kg->riwayatObat as $o)
                                            <span class="badge badge-muted" style="font-size:0.75rem; justify-content:flex-start;">
                                                <i class="fa-solid fa-pills" style="color:var(--color-primary); margin-right:4px;"></i>
                                                {{ $o->nama_obat }} ({{ $o->jumlah }} - {{ $o->dosis }})
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center text-muted py-6">Tidak ada data kunjungan guru &amp; karyawan pada periode ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $kunjunganGukarList->links('pagination.presensi') }}
            </div>
        </div>
    </div>

    {{-- TAB 3: IMT Siswa per Kelas --}}
    <div class="tab-content-pane" id="tab-imt">
        <div style="margin-bottom: 16px; display: flex; justify-content: flex-end;">
            <a href="{{ route('uks.laporan.print-imt', ['id_semester' => $idSemester]) }}"
               target="_blank" class="btn btn-primary btn-sm">
                <i class="fa-solid fa-file-pdf"></i> Cetak PDF Semua Kelas
            </a>
        </div>

        @forelse($imtPerKelas as $kelasGroup)
            @php
                $kelas     = $kelasGroup['kelas'];
                $siswaData = $kelasGroup['siswaData'];
                $kat       = $kelasGroup['kategoriCount'];
                $total     = $kelasGroup['totalSiswa'];
                $diperiksa = $kelasGroup['totalDiperiksa'];
            @endphp
            <div class="accordion-item card" style="margin-bottom: 16px;">
                <div class="accordion-header card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; cursor:pointer; user-select:none;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <i class="fa-solid fa-chevron-right accordion-caret" style="color:var(--color-primary); font-size:0.9rem; transition:transform 0.2s ease;"></i>
                        <div>
                            <h3 class="card-title" style="font-size:1.05rem; font-weight:700; margin:0;">
                                <i class="fa-solid fa-school" style="color:var(--color-primary); margin-right:6px;"></i>
                                Kelas {{ $kelas->nama_kelas }} @if($kelas->jurusan) — {{ $kelas->jurusan->nama_jurusan }} @endif
                            </h3>
                            <div style="font-size:0.8rem; color:var(--text-muted); margin-top:4px; display:flex; gap:12px; flex-wrap:wrap;">
                                <span>Total Siswa: <strong>{{ $total }}</strong></span>
                                <span>Diperiksa: <strong class="text-success">{{ $diperiksa }}</strong></span>
                                <span>Belum Diperiksa: <strong class="text-warning">{{ $total - $diperiksa }}</strong></span>
                            </div>
                        </div>
                    </div>
                    <div style="display:flex; align-items:center; gap:8px;">
                        <div class="accordion-header-pills" style="display:flex; gap:6px; flex-wrap:wrap; font-size:0.75rem; margin-right:8px;">
                            <span class="badge badge-warning">Kurus: {{ $kat['Kurus'] }}</span>
                            <span class="badge badge-success">Normal: {{ $kat['Normal'] }}</span>
                            <span class="badge badge-danger">Gemuk: {{ $kat['Gemuk'] }}</span>
                            <span class="badge badge-danger" style="background:#ef4444; color:#fff;">Obesitas: {{ $kat['Obesitas'] }}</span>
                        </div>
                        <a href="{{ route('uks.laporan.print-imt', ['id_semester' => $idSemester, 'id_kelas' => $kelas->id_kelas]) }}"
                           target="_blank" class="btn btn-outline-primary btn-sm btn-print-class">
                            <i class="fa-solid fa-print"></i> Cetak Kelas Ini
                        </a>
                    </div>
                </div>
                <div class="accordion-collapse">
                    <div class="card-body p-0" style="border-top:1px solid #e2e8f0;">
                        <div class="table-responsive">
                            <table class="data-table" style="margin:0;">
                                <thead>
                                    <tr>
                                        <th style="width:50px;">No</th>
                                        <th>Nama Siswa</th>
                                        <th style="width:90px; text-align:center;">TB (cm)</th>
                                        <th style="width:90px; text-align:center;">BB (kg)</th>
                                        <th style="width:80px; text-align:center;">IMT</th>
                                        <th style="width:125px; text-align:center;">Kategori</th>
                                        <th style="width:140px; text-align:center;">Tren IMT</th>
                                        <th style="width:120px; text-align:center;">Tgl Periksa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($siswaData as $i => $row)
                                    <tr>
                                        <td class="text-center">{{ $i + 1 }}</td>
                                        <td style="font-weight:600;">{{ $row['siswa']->nama_siswa }}</td>
                                        @if($row['current'])
                                            <td class="text-center">{{ $row['current']->tinggi_badan ?? '-' }}</td>
                                            <td class="text-center">{{ $row['current']->berat_badan ?? '-' }}</td>
                                            <td class="text-center" style="font-weight:700;">{{ number_format($row['current']->imt, 1) }}</td>
                                            <td class="text-center">
                                                @php
                                                    $katLower = strtolower($row['current']->kategori ?? '');
                                                    $katCls = str_contains($katLower, 'kurus') ? 'badge-warning'
                                                        : (str_contains($katLower, 'normal') ? 'badge-success'
                                                        : (str_contains($katLower, 'gemuk') ? 'badge-danger'
                                                        : (str_contains($katLower, 'obesitas') ? 'badge-danger' : 'badge-muted')));
                                                @endphp
                                                <span class="badge {{ $katCls }}">{{ $row['current']->kategori ?? '-' }}</span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    $trendCls = match($row['trend']) {
                                                        'naik' => 'badge-danger',
                                                        'turun' => 'badge-success',
                                                        'tetap' => 'badge-muted',
                                                        'baru' => 'badge-info',
                                                        default => 'badge-muted',
                                                    };
                                                    $trendText = match($row['trend']) {
                                                        'naik'  => '↑ ' . $row['trendLabel'],
                                                        'turun' => '↓ ' . $row['trendLabel'],
                                                        'tetap' => '— Tetap',
                                                        'baru'  => '★ Baru',
                                                        default => '—',
                                                    };
                                                @endphp
                                                <span class="badge {{ $trendCls }}">{{ $trendText }}</span>
                                                @if($row['prev'] && in_array($row['trend'], ['naik','turun','tetap']))
                                                    <div style="font-size:0.7rem; color:var(--text-muted); margin-top:2px;">
                                                        Sem. lalu: {{ number_format($row['prev']->imt, 1) }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="text-center" style="color:var(--text-muted); font-size:0.8rem;">
                                                {{ \Carbon\Carbon::parse($row['current']->tanggal)->translatedFormat('d M Y') }}
                                            </td>
                                        @else
                                            <td colspan="5" class="text-center text-muted">
                                                <span class="badge badge-muted">Belum Diperiksa</span>
                                            </td>
                                            <td></td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card text-center text-muted py-6">
                Tidak ada data kelas aktif yang tersedia.
            </div>
        @endforelse
    </div>

    {{-- TAB 3: Check-Up Guru & Karyawan --}}
    <div class="tab-content-pane" id="tab-gukar">
        <div class="card">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
                <h2 class="card-title">
                    <i class="fa-solid fa-user-doctor"></i>
                    Rekap Hasil Check-Up Guru &amp; Karyawan
                </h2>
                <a href="{{ route('uks.laporan.print-gukar', ['id_semester' => $idSemester]) }}"
                   target="_blank" class="btn btn-primary btn-sm">
                    <i class="fa-solid fa-file-pdf"></i> Cetak PDF Guru &amp; Karyawan
                </a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="data-table" style="margin:0;">
                        <thead>
                            <tr>
                                <th style="width:60px;">#</th>
                                <th>Nama Lengkap</th>
                                <th style="width:110px; text-align:center;">Peran</th>
                                <th style="width:120px; text-align:center;">TB / BB</th>
                                <th style="width:140px; text-align:center;">IMT (Kategori)</th>
                                <th style="width:120px; text-align:center;">Tek. Darah</th>
                                <th style="width:120px; text-align:center;">Kolesterol</th>
                                <th style="width:140px; text-align:center;">Gula Darah</th>
                                <th style="width:120px; text-align:center;">Asam Urat</th>
                                <th style="width:120px; text-align:center;">Tgl Periksa</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($gukarCheckupList as $index => $row)
                                @php
                                    $nama = '';
                                    $peran = '';
                                    if ($row->guru) {
                                        $nama = $row->guru->nama_guru;
                                        $peran = 'Guru';
                                    } elseif ($row->karyawan) {
                                        $nama = $row->karyawan->nama_karyawan;
                                        $peran = 'Karyawan';
                                    }
                                @endphp
                                <tr>
                                    <td class="text-center text-muted" style="font-size:0.8rem;">{{ $index + 1 }}</td>
                                    <td style="font-weight:600;">{{ $nama ?: '-' }}</td>
                                    <td class="text-center">
                                        <span class="badge {{ $peran === 'Guru' ? 'badge-info' : 'badge-success' }}">
                                            {{ $peran }}
                                        </span>
                                    </td>
                                    <td class="text-center">{{ $row->tinggi_badan ?? '-' }} cm / {{ $row->berat_badan ?? '-' }} kg</td>
                                    <td class="text-center">
                                        @if($row->imt)
                                            <span style="font-weight:700;">{{ number_format($row->imt, 1) }}</span>
                                            @if($row->kategori)
                                                @php
                                                    $katLower = strtolower($row->kategori ?? '');
                                                    $katCls = str_contains($katLower, 'kurus') ? 'badge-warning'
                                                        : (str_contains($katLower, 'normal') ? 'badge-success'
                                                        : (str_contains($katLower, 'gemuk') ? 'badge-danger'
                                                        : (str_contains($katLower, 'obesitas') ? 'badge-danger' : 'badge-muted')));
                                                @endphp
                                                <span class="badge {{ $katCls }}" style="font-size:0.75rem; margin-left:4px;">{{ $row->kategori }}</span>
                                            @endif
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $row->tekanan_darah ?? '-' }}</td>
                                    <td class="text-center">
                                        @if($row->kolesterol !== null)
                                            @php
                                                $cholKat = $row->kolesterol < 200 ? 'Normal' : 'Tinggi';
                                            @endphp
                                            <span style="font-weight:600;">{{ $row->kolesterol }}</span>
                                            <span class="badge {{ $cholKat === 'Normal' ? 'badge-success' : 'badge-danger' }}" style="font-size:0.7rem; margin-left:2px;">{{ $cholKat }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($row->gula_darah !== null)
                                            @php
                                                $glu = $row->gula_darah;
                                                $tipe = $row->tipe_gula_darah ?? 'sewaktu';
                                                $gluKat = '';
                                                $gluCls = 'badge-success';
                                                if ($tipe === 'puasa') {
                                                    if ($glu < 75) { $gluKat = 'Rendah'; $gluCls = 'badge-warning'; }
                                                    elseif ($glu <= 99) { $gluKat = 'Normal'; $gluCls = 'badge-success'; }
                                                    elseif ($glu <= 125) { $gluKat = 'Prediabetes'; $gluCls = 'badge-warning'; }
                                                    else { $gluKat = 'Diabetes'; $gluCls = 'badge-danger'; }
                                                } else {
                                                    if ($glu < 140) { $gluKat = 'Normal'; $gluCls = 'badge-success'; }
                                                    elseif ($glu <= 199) { $gluKat = 'Prediabetes'; $gluCls = 'badge-warning'; }
                                                    else { $gluKat = 'Diabetes'; $gluCls = 'badge-danger'; }
                                                }
                                            @endphp
                                            <span style="font-weight:600;">{{ $row->gula_darah }}</span>
                                            <span class="badge {{ $gluCls }}" style="font-size:0.7rem; margin-left:2px;">{{ $gluKat }} ({{ ucfirst($tipe) }})</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if($row->asam_urat !== null)
                                            @php
                                                $uric = $row->asam_urat;
                                                $gender = 'L';
                                                if ($row->guru) {
                                                    $gender = $row->guru->jenkel;
                                                } elseif ($row->karyawan) {
                                                    $gender = $row->karyawan->jenkel;
                                                }
                                                $uricKat = '';
                                                $uricCls = 'badge-success';
                                                if ($gender === 'P') {
                                                    if ($uric < 2.4) { $uricKat = 'Rendah'; $uricCls = 'badge-warning'; }
                                                    elseif ($uric <= 6.0) { $uricKat = 'Normal'; $uricCls = 'badge-success'; }
                                                    else { $uricKat = 'Tinggi'; $uricCls = 'badge-danger'; }
                                                } else {
                                                    if ($uric < 2.4) { $uricKat = 'Rendah'; $uricCls = 'badge-warning'; }
                                                    elseif ($uric <= 7.0) { $uricKat = 'Normal'; $uricCls = 'badge-success'; }
                                                    else { $uricKat = 'Tinggi'; $uricCls = 'badge-danger'; }
                                                }
                                            @endphp
                                            <span style="font-weight:600;">{{ $row->asam_urat }}</span>
                                            <span class="badge {{ $uricCls }}" style="font-size:0.7rem; margin-left:2px;">{{ $uricKat }}</span>
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="text-center" style="color:var(--text-muted); font-size:0.8rem;">
                                        {{ \Carbon\Carbon::parse($row->tanggal)->translatedFormat('d M Y') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-6">Tidak ada data check-up guru &amp; karyawan pada periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.laporan-stats-grid { display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px; }
.laporan-stat-card { display:flex; align-items:center; gap:20px; padding:24px; border-radius:var(--radius-card); color:#fff; box-shadow:0 6px 24px rgba(0,0,0,0.08); transition:var(--transition-smooth); }
.laporan-stat-card:hover { transform:translateY(-3px); box-shadow:0 12px 36px rgba(0,0,0,0.15); }
.laporan-stat-icon { width:54px; height:54px; background:rgba(255,255,255,0.2); border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.6rem; flex-shrink:0; }
.laporan-stat-info { display:flex; flex-direction:column; }
.laporan-stat-num { font-size:2.2rem; font-weight:800; line-height:1; }
.laporan-stat-lbl { font-size:0.83rem; opacity:0.9; margin-top:4px; font-weight:500; }
.laporan-row { display:grid; grid-template-columns:1.6fr 1fr; gap:20px; }
@media(max-width:992px) {
    .laporan-stats-grid { grid-template-columns:1fr; }
    .laporan-row { grid-template-columns:1fr; }
}

/* Tab Navigation Styles */
.report-tabs-nav {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    border-bottom: 2px solid #e2e8f0;
    padding-bottom: 1px;
    flex-wrap: wrap;
}
.report-tab-btn {
    background: none;
    border: none;
    padding: 10px 16px;
    font-weight: 700;
    font-size: 0.9rem;
    color: #64748b;
    border-bottom: 2px solid transparent;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}
.report-tab-btn:hover {
    color: var(--color-primary) !important;
}
.report-tab-btn.active {
    color: var(--color-primary) !important;
    border-bottom-color: var(--color-primary) !important;
}
.tab-content-pane {
    display: none;
    animation: fadeInTab 0.25s ease-out;
}
.tab-content-pane.active {
    display: block;
}
@keyframes fadeInTab {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Accordion Styles */
.accordion-header {
    cursor: pointer;
    transition: background-color 0.2s ease;
}
.accordion-header:hover {
    background-color: #f8fafc;
}
.accordion-collapse {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease-out;
}

/* Badge colors override/addition */
.badge-warning { background-color: #fef3c7; color: #b45309; }
.badge-success { background-color: #dcfce7; color: #15803d; }
.badge-danger { background-color: #fee2e2; color: #b91c1c; }
.badge-info { background-color: #e0f2fe; color: #0369a1; }
.badge-muted { background-color: #f1f5f9; color: #475569; }
.table-responsive { width: 100%; overflow-x: auto; -webkit-overflow-scrolling: touch; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// AJAX: reload semester dropdown when tahun ajaran changes
document.getElementById('selTahun').addEventListener('change', function () {
    const idTahun = this.value;
    const selSemester = document.getElementById('selSemester');

    fetch('/uks/laporan/semester-by-tahun?id_tahun=' + idTahun)
        .then(r => r.json())
        .then(data => {
            selSemester.innerHTML = '';
            if (data.length === 0) {
                selSemester.innerHTML = '<option value="">-- Tidak ada semester --</option>';
                return;
            }
            data.forEach(sem => {
                const label = sem.semester === 'Ganjil' ? 'Semester 1 (Ganjil)' : 'Semester 2 (Genap)';
                const year  = sem.awal ? sem.awal.substring(0, 4) : '';
                const opt   = document.createElement('option');
                opt.value   = sem.id_semester;
                opt.textContent = label + (year ? ' — ' + year : '');
                selSemester.appendChild(opt);
            });
            document.getElementById('filterForm').submit();
        })
        .catch(err => console.error('Gagal load semester:', err));
});

// Chart Kunjungan Bulanan
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('chartBulanan');
    if (ctx) {
        const monthsInRange = @json($monthsInRange);
        const rekapBulanan  = @json($rekapBulanan);

        const namaBulanShort = {
            1:'Jan', 2:'Feb', 3:'Mar', 4:'Apr', 5:'Mei', 6:'Jun',
            7:'Jul', 8:'Agu', 9:'Sep', 10:'Okt', 11:'Nov', 12:'Des'
        };

        const labels = monthsInRange.map(m => namaBulanShort[m]);
        const values = monthsInRange.map(m => rekapBulanan[m] ?? 0);

        const ctx2d = ctx.getContext('2d');
        const gradient = ctx2d.createLinearGradient(0, 0, 0, 240);
        gradient.addColorStop(0, 'rgba(13, 148, 136, 0.4)');
        gradient.addColorStop(1, 'rgba(13, 148, 136, 0.0)');

        new Chart(ctx2d, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Jumlah Kunjungan',
                    data: values,
                    borderColor: '#0d9488',
                    borderWidth: 3,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#0d9488',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(15,23,42,0.92)',
                        titleColor: '#f8fafc',
                        bodyColor: '#cbd5e1',
                        cornerRadius: 8,
                        padding: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#64748b', font: { family: 'inherit', size: 11 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        ticks: { color: '#64748b', font: { family: 'inherit', size: 11 } },
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // Tab Switcher Logic
    const tabButtons = document.querySelectorAll('.report-tab-btn');
    const tabPanes = document.querySelectorAll('.tab-content-pane');

    // Restore active tab from localStorage if available, else default to 'tab-kunjungan'
    const activeTabId = localStorage.getItem('active_uks_report_tab') || 'tab-kunjungan';
    
    // Activate restored or default tab
    const activeBtn = document.querySelector(`.report-tab-btn[data-target="${activeTabId}"]`);
    const activePane = document.getElementById(activeTabId);
    
    if (activeBtn && activePane) {
        tabButtons.forEach(btn => btn.classList.remove('active'));
        tabPanes.forEach(pane => pane.classList.remove('active'));
        activeBtn.classList.add('active');
        activePane.classList.add('active');
    }

    tabButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.dataset.target;
            
            tabButtons.forEach(b => b.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            this.classList.add('active');
            const targetPane = document.getElementById(target);
            if (targetPane) {
                targetPane.classList.add('active');
            }
            
            // Save selected tab in localStorage
            localStorage.setItem('active_uks_report_tab', target);
        });
    });

    // Accordion Toggle Logic
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            // Prevent toggling if print button or links inside are clicked
            if (e.target.closest('.btn-print-class') || e.target.closest('a')) {
                return;
            }

            const item = this.closest('.accordion-item');
            const collapse = item.querySelector('.accordion-collapse');
            const caret = this.querySelector('.accordion-caret');

            if (item.classList.contains('active')) {
                // If it was 'none', set it to scrollHeight first
                if (collapse.style.maxHeight === 'none' || !collapse.style.maxHeight) {
                    collapse.style.maxHeight = collapse.scrollHeight + 'px';
                    // Force repaint
                    collapse.offsetHeight; 
                }
                item.classList.remove('active');
                collapse.style.maxHeight = '0px';
                caret.style.transform = 'rotate(0deg)';
            } else {
                item.classList.add('active');
                collapse.style.maxHeight = collapse.scrollHeight + 'px';
                caret.style.transform = 'rotate(90deg)';
                setTimeout(() => {
                    if (item.classList.contains('active')) {
                        collapse.style.maxHeight = 'none';
                    }
                }, 300);
            }
        });
    });
});
</script>
@endpush
@endsection
