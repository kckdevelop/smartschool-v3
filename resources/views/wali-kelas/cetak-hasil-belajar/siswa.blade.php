@extends('layouts.app')

@section('title', 'Daftar Siswa — Kelas {{ $kelas->nama_kelas }}')

@push('styles')
<style>
    .page-header {
        display: flex; align-items: center; gap: 14px;
        margin-bottom: 24px;
    }
    .page-header-back {
        background: #f1f5f9; border: 1px solid #e2e8f0;
        border-radius: 10px; padding: 8px 14px;
        color: #475569; font-size: .85rem; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px;
        transition: background .2s;
    }
    .page-header-back:hover { background: #e2e8f0; color: #1e293b; text-decoration: none; }

    .kelas-info-bar {
        background: linear-gradient(135deg, #1e3a5f 0%, #2d6a9f 100%);
        border-radius: 14px; padding: 20px 28px;
        display: flex; align-items: center; justify-content: space-between;
        color: #fff; margin-bottom: 24px; gap: 16px; flex-wrap: wrap;
    }
    .kelas-info-bar h2 { font-size: 1.35rem; font-weight: 800; margin: 0; }
    .kelas-info-bar p  { margin: 0; opacity: .85; font-size: .88rem; }
    .kelas-stats { display: flex; gap: 24px; flex-wrap: wrap; }
    .kelas-stat  { text-align: center; }
    .kelas-stat .val { font-size: 1.5rem; font-weight: 800; line-height: 1; }
    .kelas-stat .lbl { font-size: .75rem; opacity: .8; }

    /* Table */
    .table-card {
        background: #fff; border: 1px solid #e5e7eb;
        border-radius: 14px; overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,.04);
    }
    .table-toolbar {
        padding: 16px 20px; border-bottom: 1px solid #f1f5f9;
        display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap;
    }
    .table-toolbar h3 { margin: 0; font-size: 1rem; font-weight: 700; color: #1e293b; }
    .search-box {
        border: 1px solid #e2e8f0; border-radius: 8px;
        padding: 7px 14px; font-size: .875rem; outline: none;
        width: 220px; transition: border-color .2s;
    }
    .search-box:focus { border-color: #2d6a9f; }

    .data-table { width: 100%; border-collapse: collapse; }
    .data-table thead tr { background: #f8fafc; }
    .data-table th {
        padding: 12px 16px; font-size: .8rem; font-weight: 700;
        color: #64748b; text-transform: uppercase; letter-spacing: .04em;
        border-bottom: 2px solid #e5e7eb; text-align: left; white-space: nowrap;
    }
    .data-table td {
        padding: 13px 16px; font-size: .875rem; color: #374151;
        border-bottom: 1px solid #f1f5f9; vertical-align: middle;
    }
    .data-table tbody tr:hover { background: #f8fafc; }
    .data-table tbody tr:last-child td { border-bottom: none; }

    .avatar {
        width: 36px; height: 36px; border-radius: 50%;
        background: linear-gradient(135deg, #1e3a5f, #2d6a9f);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-weight: 700; font-size: .85rem;
        flex-shrink: 0;
    }
    .student-info { display: flex; align-items: center; gap: 10px; }
    .student-name { font-weight: 600; color: #1e293b; }
    .student-nis  { font-size: .78rem; color: #94a3b8; }

    .badge-jk {
        display: inline-block; padding: 2px 10px; border-radius: 20px;
        font-size: .75rem; font-weight: 600;
    }
    .badge-l { background: #dbeafe; color: #1d4ed8; }
    .badge-p { background: #fce7f3; color: #be185d; }

    .btn-preview {
        background: #f0f9ff; border: 1px solid #bae6fd;
        color: #0369a1; border-radius: 8px;
        padding: 6px 12px; font-size: .8rem; font-weight: 600;
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 5px;
        transition: all .2s;
    }
    .btn-preview:hover { background: #e0f2fe; color: #0369a1; text-decoration: none; }
    .btn-download {
        background: linear-gradient(135deg, #1e3a5f, #2d6a9f);
        color: #fff; border: none; border-radius: 8px;
        padding: 6px 12px; font-size: .8rem; font-weight: 600;
        cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 5px;
        transition: opacity .2s;
    }
    .btn-download:hover { opacity: .85; color: #fff; text-decoration: none; }
    .btn-group-actions { display: flex; gap: 6px; }

    .empty-row td { text-align: center; padding: 40px !important; color: #94a3b8; font-style: italic; }
</style>
@endpush

@section('content')

{{-- Back + Header --}}
<div class="page-header">
    <a href="{{ route('wali-kelas.cetak-hasil-belajar.index') }}" class="page-header-back">
        <i class="fa-solid fa-arrow-left fa-xs"></i> Kembali
    </a>
    <nav style="font-size:.85rem; color:#94a3b8;">
        <a href="{{ route('wali-kelas.cetak-hasil-belajar.index') }}" style="color:#64748b; text-decoration:none;">Wali Kelas</a>
        <i class="fa-solid fa-chevron-right fa-xs" style="margin:0 6px;"></i>
        <span style="color:#1e3a5f; font-weight:600;">Kelas {{ $kelas->nama_kelas }}</span>
    </nav>
</div>

{{-- Kelas Info Bar --}}
<div class="kelas-info-bar">
    <div>
        <h2><i class="fa-solid fa-door-open"></i> Kelas {{ $kelas->nama_kelas }}</h2>
        <p>
            Jurusan: {{ optional($kelas->jurusan)->nama_jurusan ?? '—' }}
            &nbsp;|&nbsp;
            Wali Kelas: {{ optional($kelas->guru)->nama_guru ?? '—' }}
        </p>
    </div>
    <div class="kelas-stats">
        <div class="kelas-stat">
            <div class="val">{{ $siswaList->count() }}</div>
            <div class="lbl">Total Siswa</div>
        </div>
        <div class="kelas-stat">
            <div class="val">{{ $siswaList->where('jenkel','L')->count() }}</div>
            <div class="lbl">Laki-laki</div>
        </div>
        <div class="kelas-stat">
            <div class="val">{{ $siswaList->where('jenkel','P')->count() }}</div>
            <div class="lbl">Perempuan</div>
        </div>
        <div class="kelas-stat">
            <div class="val">{{ $semester ? 'Smt ' . $semester->semester : '—' }}</div>
            <div class="lbl">Semester Aktif</div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="table-card">
    <div class="table-toolbar">
        <h3><i class="fa-solid fa-users fa-sm" style="color:#2d6a9f;"></i> Daftar Siswa</h3>
        <input type="text" class="search-box" id="searchSiswa" placeholder="🔍 Cari nama / NIS..." oninput="filterSiswa(this.value)">
    </div>
    <table class="data-table" id="tabelSiswa">
        <thead>
            <tr>
                <th style="width:42px;">No</th>
                <th>Nama Siswa</th>
                <th>NIS / NISN</th>
                <th>Jenis Kelamin</th>
                <th>Tempat, Tanggal Lahir</th>
                <th style="text-align:center; width:200px;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswaList as $i => $siswa)
            <tr class="siswa-row" data-name="{{ strtolower($siswa->nama_siswa) }}" data-nis="{{ $siswa->nis }}">
                <td style="text-align:center; color:#94a3b8; font-size:.8rem;">{{ $i + 1 }}</td>
                <td>
                    <div class="student-info">
                        <div class="avatar">{{ strtoupper(substr($siswa->nama_siswa, 0, 2)) }}</div>
                        <div>
                            <div class="student-name">{{ $siswa->nama_siswa }}</div>
                            <div class="student-nis">NIS: {{ $siswa->nis }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <div style="font-weight:600; font-family:monospace;">{{ $siswa->nis }}</div>
                    <div style="font-size:.78rem; color:#94a3b8;">NISN: {{ $siswa->nisn ?? '—' }}</div>
                </td>
                <td>
                    <span class="badge-jk {{ $siswa->jenkel === 'L' ? 'badge-l' : 'badge-p' }}">
                        {{ $siswa->jenkel === 'L' ? '♂ Laki-laki' : '♀ Perempuan' }}
                    </span>
                </td>
                <td>
                    {{ $siswa->tempat_lahir ?? '—' }},
                    {{ $siswa->tgl_lahir ? \Carbon\Carbon::parse($siswa->tgl_lahir)->translatedFormat('d F Y') : '—' }}
                </td>
                <td>
                    <div class="btn-group-actions" style="justify-content:center;">
                        <a href="{{ route('wali-kelas.cetak-hasil-belajar.preview', $siswa->nis) }}"
                           class="btn-preview" target="_blank" title="Preview Raport">
                            <i class="fa-solid fa-eye"></i> Preview
                        </a>
                        <a href="{{ route('wali-kelas.cetak-hasil-belajar.download', $siswa->nis) }}"
                           class="btn-download" title="Download PDF Raport">
                            <i class="fa-solid fa-file-pdf"></i> PDF
                        </a>
                    </div>
                </td>
            </tr>
            @empty
            <tr class="empty-row"><td colspan="6"><i class="fa-solid fa-user-slash fa-lg" style="display:block; margin-bottom:8px;"></i> Tidak ada data siswa aktif di kelas ini</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@push('scripts')
<script>
function filterSiswa(q) {
    q = q.toLowerCase();
    document.querySelectorAll('#tabelSiswa .siswa-row').forEach(row => {
        const name = row.dataset.name || '';
        const nis  = String(row.dataset.nis || '');
        row.style.display = (name.includes(q) || nis.includes(q)) ? '' : 'none';
    });
}
</script>
@endpush
