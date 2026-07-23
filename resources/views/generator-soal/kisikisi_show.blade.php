@extends('layouts.app')

@section('title', 'Detail Kisi-Kisi — SmartSchool')
@section('header_title', 'Detail Kisi-Kisi Penilaian')
@section('header_subtitle', 'Pratinjau dan Cetak Kisi-Kisi Penilaian')

@section('content')
<div class="page-content">

    {{-- ACTION BAR --}}
    <div style="display: flex; gap: 10px; margin-bottom: 20px; flex-wrap: wrap;">
        <a href="{{ route('generator-soal.kisikisi.index') }}" class="btn" style="background:var(--bg-secondary);color:var(--text-primary);border:1px solid var(--border-color);padding:8px 16px;border-radius:8px;text-decoration:none;display:flex;align-items:center;gap:6px;">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <button onclick="window.print()" class="btn btn-primary" style="padding:8px 16px;border-radius:8px;display:flex;align-items:center;gap:6px;">
            <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
        </button>
        <form method="POST" action="{{ route('generator-soal.kisikisi.destroy', $riwayat->id_kisikisi) }}" id="form-hapus-show" style="display:inline;">
            @csrf @method('DELETE')
            <button type="button" onclick="openConfirmHapus()" class="btn" style="background:#fee2e2;color:#b91c1c;border:none;padding:8px 16px;border-radius:8px;display:flex;align-items:center;gap:6px;">
                <i class="fa-solid fa-trash"></i> Hapus
            </button>
        </form>
    </div>

    <div class="card" id="print-area">

        {{-- PRINT HEADER --}}
        <div id="kop-surat" style="display: none; width: 100%;">
            @include('partials.kop-surat')
        </div>

        {{-- KISI-KISI TITLE --}}
        <div class="card-header" style="text-align: center; border-bottom: 2px solid var(--border-color); padding: 24px;">
            <h2 style="margin: 0 0 6px; font-size: 1.25rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px;">Kisi-Kisi Soal {{ $riwayat->jenis_penilaian }}</h2>
            <p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem;">Tahun Pelajaran {{ $riwayat->tahun_pelajaran }}</p>
        </div>

        {{-- META INFO --}}
        <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; padding: 20px 24px; border-bottom: 1px solid var(--border-color);">
            <div>
                <p style="margin:0;font-size:0.78rem;color:var(--text-secondary);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Mata Pelajaran</p>
                <p style="margin:0;font-weight:700;font-size:0.95rem;">{{ $riwayat->mapel->nama_mapel ?? '-' }}</p>
            </div>
            <div>
                <p style="margin:0;font-size:0.78rem;color:var(--text-secondary);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Kelas / Semester</p>
                <p style="margin:0;font-weight:700;font-size:0.95rem;">Kelas {{ $riwayat->kelas->tingkat ?? '-' }} / Semester {{ $riwayat->semester }}</p>
            </div>
            <div>
                <p style="margin:0;font-size:0.78rem;color:var(--text-secondary);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Kurikulum</p>
                <p style="margin:0;font-weight:700;font-size:0.95rem;">{{ $riwayat->kurikulum }}</p>
            </div>
            <div>
                <p style="margin:0;font-size:0.78rem;color:var(--text-secondary);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Alokasi Waktu</p>
                <p style="margin:0;font-weight:700;font-size:0.95rem;">{{ $riwayat->alokasi_waktu }} Menit</p>
            </div>
            <div>
                <p style="margin:0;font-size:0.78rem;color:var(--text-secondary);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Guru Penyusun</p>
                <p style="margin:0;font-weight:700;font-size:0.95rem;">{{ $riwayat->guru->nama_guru ?? '-' }}</p>
            </div>
            <div>
                <p style="margin:0;font-size:0.78rem;color:var(--text-secondary);font-weight:600;text-transform:uppercase;letter-spacing:.5px;">Tanggal Dibuat</p>
                <p style="margin:0;font-weight:700;font-size:0.95rem;">{{ $riwayat->created_at->format('d M Y') }}</p>
            </div>
        </div>

        {{-- KISI-KISI TABLE --}}
        <div style="padding: 0; overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.83rem;">
                <thead>
                    <tr style="background: linear-gradient(135deg, #059669, #0d9488); color: white;">
                        <th style="padding: 12px 8px; text-align: center; width: 40px;">No</th>
                        <th style="padding: 12px 8px; text-align: left; min-width: 180px;">Kompetensi Dasar</th>
                        <th style="padding: 12px 8px; text-align: left; min-width: 130px;">Materi Pokok</th>
                        <th style="padding: 12px 8px; text-align: left; min-width: 160px;">Indikator</th>
                        <th style="padding: 12px 8px; text-align: center; min-width: 80px;">Level Kognitif</th>
                        <th style="padding: 12px 8px; text-align: center; min-width: 60px;">No Soal</th>
                        <th style="padding: 12px 8px; text-align: center; min-width: 90px;">Bentuk Soal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($riwayat->hasil_json as $index => $row)
                    <tr style="border-bottom: 1px solid var(--border-color); {{ $index % 2 === 0 ? '' : 'background: rgba(0,0,0,0.02);' }}">
                        <td style="padding: 10px 8px; text-align: center; font-weight: 700; color: #059669;">{{ $row['no'] ?? ($index + 1) }}</td>
                        <td style="padding: 10px 8px; line-height: 1.5;">{{ $row['kompetensi_dasar'] ?? '-' }}</td>
                        <td style="padding: 10px 8px; font-weight: 600;">{{ $row['materi_pokok'] ?? '-' }}</td>
                        <td style="padding: 10px 8px; line-height: 1.5;">{{ $row['indikator'] ?? '-' }}</td>
                        <td style="padding: 10px 8px; text-align: center;">
                            <span style="background: #fef9c3; color: #854d0e; padding: 2px 8px; border-radius: 12px; font-weight: 700; font-size: 0.78rem;">{{ $row['level_kognitif'] ?? '-' }}</span>
                        </td>
                        <td style="padding: 10px 8px; text-align: center; font-weight: 700;">{{ $row['no_soal'] ?? '-' }}</td>
                        <td style="padding: 10px 8px; text-align: center;">
                            <span style="background: #dbeafe; color: #1e40af; padding: 2px 8px; border-radius: 12px; font-size: 0.78rem;">{{ $row['bentuk_soal'] ?? '-' }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- SIGNATURE BLOCK FOR PRINT --}}
        <div id="ttd-block" style="display: none; padding: 30px 24px 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-top: 10px; text-align: center;">
                <div>
                    <p style="margin: 0 0 60px; font-size: 0.85rem;">Mengetahui,<br><strong>Kepala Sekolah</strong></p>
                    <p style="margin: 0; font-size: 0.85rem; border-top: 1px solid #374151; padding-top: 4px;">(..................................)</p>
                </div>
                <div>
                    <p style="margin: 0 0 60px; font-size: 0.85rem;">Guru Mata Pelajaran,</p>
                    <p style="margin: 0; font-size: 0.85rem; border-top: 1px solid #374151; padding-top: 4px;">{{ $riwayat->guru->nama_guru ?? '(..................................)' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Show KOP & TTD for print */
    #kop-surat, #ttd-block { display: block !important; }
    /* Hide nav, sidebar, action bar */
    .sidebar, .topbar, .page-header, .btn, form[method="POST"] { display: none !important; }
    body, .main-content, .page-content { background: #fff !important; padding: 0 !important; margin: 0 !important; }
    .card { box-shadow: none !important; border: none !important; }
    table { page-break-inside: auto; }
    tr { page-break-inside: avoid; page-break-after: auto; }
    thead { display: table-header-group; }
}
</style>

<!-- MODAL KONFIRMASI HAPUS -->
<div class="modal-overlay" id="modal-konfirmasi-hapus-show">
    <div class="modal" style="max-width: 420px;">
        <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 18px 24px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="background: #fee2e2; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i class="fa-solid fa-triangle-exclamation" style="color: #b91c1c; font-size: 1rem;"></i>
                </div>
                <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--text-primary);">Konfirmasi Hapus</h3>
            </div>
            <button type="button" class="modal-close" onclick="closeConfirmHapus()">&times;</button>
        </div>
        <div class="modal-body" style="padding: 20px 24px; text-align: center;">
            <p style="margin: 0 0 6px; font-size: 0.95rem; color: var(--text-primary);">Yakin ingin menghapus kisi-kisi</p>
            <p style="margin: 0; font-size: 1rem; font-weight: 700; color: #b91c1c;">{{ $riwayat->mapel->nama_mapel ?? 'ini' }}?</p>
            <p style="margin: 8px 0 0; font-size: 0.82rem; color: var(--text-secondary);">Tindakan ini tidak dapat dibatalkan.</p>
        </div>
        <div class="modal-footer" style="padding: 14px 24px; display: flex; justify-content: flex-end; gap: 10px;">
            <button type="button" onclick="closeConfirmHapus()" class="btn btn-secondary" style="padding: 8px 18px; font-size: 0.88rem;">Batal</button>
            <button type="button" onclick="document.getElementById('form-hapus-show').submit()" class="btn" style="background: #b91c1c; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-size: 0.88rem; font-weight: 600; display: flex; align-items: center; gap: 7px;">
                <i class="fa-solid fa-trash"></i> Ya, Hapus
            </button>
        </div>
    </div>
</div>

<script>
    function openConfirmHapus() {
        document.getElementById('modal-konfirmasi-hapus-show').classList.add('active');
    }
    function closeConfirmHapus() {
        document.getElementById('modal-konfirmasi-hapus-show').classList.remove('active');
    }
    document.getElementById('modal-konfirmasi-hapus-show').addEventListener('click', function(e) {
        if (e.target === this) closeConfirmHapus();
    });
</script>
@endsection
