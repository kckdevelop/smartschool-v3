@extends('layouts.app')

@section('title', 'Rekap Presensi Per Siswa — SmartSchool')
@section('header_title', 'Rekap Presensi Per Siswa')
@section('header_subtitle', 'Riwayat kehadiran lengkap seorang siswa berdasarkan semester terpilih')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- ═══════════════════════════════════════
         FILTER PANEL
    ════════════════════════════════════════ --}}
    <div class="card mb-6">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-filter"></i> Filter Rekap Per Siswa</h2>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('presensi-siswa.rekap-siswa') }}" id="form-filter"
                  style="display:flex; flex-wrap:wrap; gap:16px; align-items:flex-end;">

                {{-- Pilih Kelas --}}
                <div class="form-group" style="margin-bottom:0; flex:1.5; min-width:220px;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:6px;">Kelas</label>
                    <select name="id_kelas" id="select-kelas" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ $id_kelas == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->tingkat }} {{ $k->rombel }}
                                @if($k->jurusan) — {{ $k->jurusan->nama_jurusan }} @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Pilih Siswa (dinamis via AJAX) --}}
                <div class="form-group" style="margin-bottom:0; flex:2; min-width:240px;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:6px;">Siswa</label>
                    <select name="nis" id="select-siswa" class="form-control" required>
                        <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                        @foreach($siswaList as $s)
                            <option value="{{ $s->nis }}" {{ $nis == $s->nis ? 'selected' : '' }}>
                                {{ $s->nama_siswa }} ({{ $s->nis }})
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Pilih Semester --}}
                <div class="form-group" style="margin-bottom:0; flex:1.8; min-width:220px;">
                    <label class="form-label" style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:6px;">Semester</label>
                    <select name="id_semester" id="select-semester" class="form-control" required>
                        <option value="">-- Pilih Semester --</option>
                        @foreach($semesterList as $sem)
                            <option value="{{ $sem->id_semester }}"
                                {{ $id_semester == $sem->id_semester ? 'selected' : '' }}>
                                Semester {{ ucfirst($sem->semester ?? '-') }}
                                @if($sem->tahunAjaran) — TA {{ $sem->tahunAjaran->tahun }} @endif
                                @if($sem->status === 'aktif') ✦ Aktif @endif
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol Filter --}}
                <div class="form-group" style="margin-bottom:0;">
                    <button type="submit" class="btn btn-primary" style="height:44px; padding:0 24px;">
                        <i class="fa-solid fa-magnifying-glass"></i> Tampilkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($siswa && $semester && !empty($stats))
    {{-- ═══════════════════════════════════════
         PROFIL SISWA
    ════════════════════════════════════════ --}}
    <div class="card mb-6">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <h2 class="card-title">
                <i class="fa-solid fa-user-graduate"></i> Profil Siswa
            </h2>
            <a href="{{ route('presensi-siswa.rekap-siswa.pdf', ['id_kelas'=>$id_kelas,'nis'=>$nis,'id_semester'=>$id_semester]) }}"
               class="btn btn-primary btn-sm"
               style="display:inline-flex; align-items:center; gap:8px; background:linear-gradient(135deg,#ef4444,#dc2626); border:none; height:40px; padding:0 20px; border-radius:8px;">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </a>
        </div>
        <div class="card-body">
            <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(200px,1fr)); gap:16px;">
                <div>
                    <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:4px;">NIS</div>
                    <div style="font-weight:600; color:var(--text-primary);">{{ $siswa->nis }}</div>
                </div>
                <div>
                    <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:4px;">Nama Siswa</div>
                    <div style="font-weight:700; color:var(--text-primary); font-size:1rem;">{{ $siswa->nama_siswa }}</div>
                </div>
                <div>
                    <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:4px;">Kelas</div>
                    <div style="font-weight:600; color:var(--text-primary);">
                        @if($siswa->kelas)
                            {{ $siswa->kelas->tingkat }} {{ $siswa->kelas->rombel }}
                            @if($siswa->kelas->jurusan) — {{ $siswa->kelas->jurusan->nama_jurusan }} @endif
                        @else
                            -
                        @endif
                    </div>
                </div>
                <div>
                    <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:4px;">Wali Kelas</div>
                    <div style="font-weight:600; color:var(--text-primary);">
                        {{ $siswa->kelas && $siswa->kelas->guru ? $siswa->kelas->guru->nama_guru : '-' }}
                    </div>
                </div>
                <div>
                    <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:4px;">Semester</div>
                    <div style="font-weight:600; color:var(--text-primary);">
                        Semester {{ ucfirst($semester->semester ?? '-') }}
                        @if($semester->tahunAjaran) — TA {{ $semester->tahunAjaran->tahun }} @endif
                    </div>
                </div>
                <div>
                    <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; color:var(--text-muted); margin-bottom:4px;">Periode</div>
                    <div style="font-weight:600; color:var(--text-primary);">
                        {{ \Carbon\Carbon::parse($semester->awal)->translatedFormat('d M Y') }}
                        s.d
                        {{ \Carbon\Carbon::parse($semester->akhir)->translatedFormat('d M Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         STAT CARDS
    ════════════════════════════════════════ --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(160px,1fr)); gap:16px; margin-bottom:24px;">
        <div class="stat-card" style="background:linear-gradient(135deg,#d1fae5,#a7f3d0); border-radius:12px; padding:20px; text-align:center; box-shadow:0 2px 8px rgba(16,185,129,.15);">
            <div style="font-size:2rem; font-weight:800; color:#065f46;">{{ $stats['hadir'] }}</div>
            <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:#047857; margin-top:4px;">Hadir</div>
        </div>
        <div class="stat-card" style="background:linear-gradient(135deg,#fef9c3,#fde68a); border-radius:12px; padding:20px; text-align:center; box-shadow:0 2px 8px rgba(245,158,11,.15);">
            <div style="font-size:2rem; font-weight:800; color:#92400e;">{{ $stats['sakit'] }}</div>
            <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:#b45309; margin-top:4px;">Sakit</div>
        </div>
        <div class="stat-card" style="background:linear-gradient(135deg,#dbeafe,#bfdbfe); border-radius:12px; padding:20px; text-align:center; box-shadow:0 2px 8px rgba(59,130,246,.15);">
            <div style="font-size:2rem; font-weight:800; color:#1e40af;">{{ $stats['izin'] }}</div>
            <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:#1d4ed8; margin-top:4px;">Izin</div>
        </div>
        <div class="stat-card" style="background:linear-gradient(135deg,#fee2e2,#fecaca); border-radius:12px; padding:20px; text-align:center; box-shadow:0 2px 8px rgba(239,68,68,.15);">
            <div style="font-size:2rem; font-weight:800; color:#7f1d1d;">{{ $stats['alfa'] }}</div>
            <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:#b91c1c; margin-top:4px;">Alfa</div>
        </div>
        <div class="stat-card" style="background:linear-gradient(135deg,#f3f4f6,#e5e7eb); border-radius:12px; padding:20px; text-align:center; box-shadow:0 2px 8px rgba(107,114,128,.12);">
            <div style="font-size:2rem; font-weight:800; color:#111827;">{{ $stats['total'] }}</div>
            <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:#374151; margin-top:4px;">Total Hari</div>
        </div>
        <div class="stat-card" style="background:linear-gradient(135deg,#ede9fe,#ddd6fe); border-radius:12px; padding:20px; text-align:center; box-shadow:0 2px 8px rgba(139,92,246,.15);">
            <div style="font-size:2rem; font-weight:800; color:#4c1d95;">{{ $stats['persentase'] }}%</div>
            <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; color:#6d28d9; margin-top:4px;">% Kehadiran</div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         TABEL DETAIL PRESENSI
    ════════════════════════════════════════ --}}
    <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px;">
            <h2 class="card-title">
                <i class="fa-solid fa-table-list"></i> Detail Presensi Harian
                <span style="font-size:0.8rem; font-weight:400; color:var(--text-muted);">
                    ({{ $presensiList->count() }} data ditemukan)
                </span>
            </h2>
        </div>

        @if($presensiList->isEmpty())
            <div class="card-body">
                <div class="empty-state">
                    <i class="fa-solid fa-calendar-xmark"></i>
                    <p>Belum ada data presensi pada semester ini.</p>
                </div>
            </div>
        @else
            <div class="card-body p-0" style="overflow-x:auto;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:50px; text-align:center;">No</th>
                            <th style="min-width:180px;">Tanggal</th>
                            <th style="width:130px; text-align:center;">Status</th>
                            <th style="width:130px; text-align:center;">Jam Finger</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($presensiList as $index => $p)
                        <tr>
                            <td style="text-align:center; color:var(--text-muted);">{{ $index + 1 }}</td>
                            <td>
                                <span style="font-weight:600; color:var(--text-primary);">
                                    {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('l') }}
                                </span>
                                <span style="color:var(--text-muted); margin-left:4px; font-size:0.85rem;">
                                    {{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                <span class="badge {{ $p->status_badge }}"
                                      style="display:inline-block; padding:4px 14px; border-radius:20px; font-size:0.75rem; font-weight:700; letter-spacing:.5px;">
                                    {{ $p->status_label }}
                                </span>
                            </td>
                            <td style="text-align:center;">
                                @if($p->jam)
                                    <span style="font-family:monospace; font-weight:600; color:var(--text-primary); background:var(--bg-card-hover,#f3f4f6); padding:3px 10px; border-radius:6px;">
                                        {{ \Carbon\Carbon::parse($p->jam)->format('H:i') }}
                                    </span>
                                @else
                                    <span style="color:var(--text-muted);">—</span>
                                @endif
                            </td>
                            <td>
                                @if($p->keterangan)
                                    <span style="color:var(--text-primary);">{{ $p->keterangan }}</span>
                                @else
                                    <span style="color:var(--text-muted); font-style:italic;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @elseif(request()->has('id_kelas'))
    {{-- Ada kelas dipilih tapi data belum lengkap --}}
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <i class="fa-solid fa-user-magnifying-glass" style="font-size:2.5rem; color:var(--text-muted); margin-bottom:12px;"></i>
                <p>Pilih <strong>Siswa</strong> dan <strong>Semester</strong> untuk menampilkan rekap presensi.</p>
            </div>
        </div>
    </div>
    @else
    {{-- Belum ada filter dipilih --}}
    <div class="card">
        <div class="card-body" style="padding:48px 24px; text-align:center;">
            <i class="fa-solid fa-clipboard-user" style="font-size:3rem; color:var(--text-muted); opacity:.5; display:block; margin-bottom:16px;"></i>
            <p style="color:var(--text-muted); font-size:1rem; margin:0;">
                Pilih <strong>Kelas</strong>, <strong>Siswa</strong>, dan <strong>Semester</strong> pada filter di atas<br>untuk menampilkan rekap presensi detail per siswa.
            </p>
        </div>
    </div>
    @endif
</div>

<script>
const routeSiswaByKelas = "{{ route('presensi-siswa.rekap-siswa.students', ':id_kelas') }}";
const selectedNis        = "{{ $nis ?? '' }}";

document.getElementById('select-kelas').addEventListener('change', function () {
    const idKelas   = this.value;
    const selSiswa  = document.getElementById('select-siswa');

    selSiswa.innerHTML = '<option value="">Memuat data siswa…</option>';
    selSiswa.disabled  = true;

    if (!idKelas) {
        selSiswa.innerHTML = '<option value="">-- Pilih Kelas Terlebih Dahulu --</option>';
        return;
    }

    const url = routeSiswaByKelas.replace(':id_kelas', idKelas);

    fetch(url)
        .then(r => r.json())
        .then(data => {
            selSiswa.innerHTML = '<option value="">-- Pilih Siswa --</option>';
            data.forEach(s => {
                const opt = document.createElement('option');
                opt.value       = s.nis;
                opt.textContent = s.nama_siswa + ' (' + s.nis + ')';
                if (String(s.nis) === String(selectedNis)) opt.selected = true;
                selSiswa.appendChild(opt);
            });
            selSiswa.disabled = false;
        })
        .catch(() => {
            selSiswa.innerHTML = '<option value="">Gagal memuat siswa</option>';
            selSiswa.disabled  = false;
        });
});

// Aktifkan select siswa jika kelas sudah dipilih (dari query string)
(function () {
    const selKelas = document.getElementById('select-kelas');
    if (selKelas.value) {
        document.getElementById('select-siswa').disabled = false;
    }
})();
</script>
@endsection
