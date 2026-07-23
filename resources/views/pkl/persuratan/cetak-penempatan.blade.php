<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Penerjunan Peserta PKL — {{ $surat->nomor_surat }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Times New Roman', Times, serif; 
            font-size: 11pt; 
            color: #000; 
            background: #e2e8f0; 
        }
        
        .page { 
            width: 210mm; 
            min-height: 297mm; 
            margin: 20px auto; 
            padding: 15mm 20mm 15mm 25mm; 
            background: #fff;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            position: relative;
            page-break-after: always;
        }

        .page:last-child {
            page-break-after: avoid;
        }

        /* Box Header Kanan Atas Dokumen ISO */
        .doc-header-box {
            float: right;
            border: 1px solid #000;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 8px;
        }
        .doc-header-box td {
            border: 1px solid #000;
            padding: 2px 6px;
        }

        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }

        /* Kop Surat Styling */
        .kop-wrapper {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 4px;
            margin-bottom: 16px;
            position: relative;
        }
        .kop-table {
            width: 100%;
            border-collapse: collapse;
        }
        .kop-table td {
            vertical-align: middle;
        }
        .kop-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-subtitle {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-akreditasi {
            font-size: 9.5pt;
            font-weight: bold;
        }
        .kop-alamat {
            font-size: 8pt;
            margin-top: 3px;
        }

        /* Tabel Data Surat (Nomor, Lamp, Hal) */
        .surat-meta {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 20px;
            font-size: 11pt;
        }
        .surat-meta td {
            vertical-align: top;
            padding: 1px 0;
        }

        /* Tujuan Surat */
        .tujuan-block {
            margin-bottom: 24px;
            font-size: 11pt;
            line-height: 1.5;
        }

        /* Isi Paragraph */
        .isi-surat {
            font-size: 11pt;
            line-height: 1.6;
        }
        .isi-p {
            text-indent: 40px;
            text-align: justify;
            margin-bottom: 12px;
        }

        /* Area TTD */
        .ttd-container {
            margin-top: 30px;
            width: 100%;
        }
        .ttd-box-right {
            float: right;
            width: 260px;
            text-align: center;
            font-size: 11pt;
        }

        /* Table Lampiran */
        .lampiran-meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 11pt;
            line-height: 1.6;
        }
        .lampiran-meta td {
            vertical-align: top;
            padding: 2px 0;
        }

        .tabel-siswa-lampiran {
            width: 100%;
            border-collapse: collapse;
            margin-top: 12px;
            font-size: 10pt;
        }
        .tabel-siswa-lampiran th, .tabel-siswa-lampiran td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: middle;
        }
        .tabel-siswa-lampiran th {
            background-color: #b4c6e7;
            text-align: center;
            font-weight: bold;
        }

        .no-print-bar { 
            position: fixed; 
            top: 20px; 
            right: 20px; 
            z-index: 9999; 
            display: flex; 
            flex-direction: column; 
            gap: 8px; 
            background: #ffffff; 
            padding: 12px 16px; 
            border-radius: 12px; 
            box-shadow: 0 8px 24px rgba(0,0,0,0.18); 
            border: 1px solid #cbd5e1; 
            font-family: sans-serif; 
        }

        @media print {
            body { background: none; }
            .page { 
                margin: 0; 
                box-shadow: none; 
                padding: 15mm 20mm 15mm 25mm;
            }
            .no-print-bar, .print-btn { display: none !important; }
        }
    </style>
</head>
<body>

<div class="no-print-bar">
    <button type="button" onclick="window.print()" style="background:#10b981; color:#fff; border:none; padding:10px 18px; border-radius:8px; font-size:14px; font-weight:600; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:8px;">
        🖨️ Cetak Surat Penempatan
    </button>
    <div style="font-size:11px; font-weight:700; color:#475569; margin-top:2px; text-transform:uppercase; letter-spacing:0.5px;">Opsi Tanda Tangan:</div>
    <div style="display:flex; flex-direction:column; gap:6px; font-size:13px; color:#1e293b;">
        <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
            <input type="radio" name="ttd_toggle" value="1" {{ ($denganTtd ?? true) ? 'checked' : '' }} onchange="toggleTtd(true)">
            ✍️ Dengan Tanda Tangan
        </label>
        <label style="display:flex; align-items:center; gap:6px; cursor:pointer;">
            <input type="radio" name="ttd_toggle" value="0" {{ !($denganTtd ?? true) ? 'checked' : '' }} onchange="toggleTtd(false)">
            📄 Tanpa Tanda Tangan
        </label>
    </div>
</div>

{{-- ============================================================================ --}}
{{-- HALAMAN 1: SURAT PENERJUNAN PESERTA PKL --}}
{{-- ============================================================================ --}}
<div class="page">
    {{-- Header Dokumen Kanan Atas ISO --}}
    <div class="clearfix">
        <table class="doc-header-box">
            <tr>
                <td>No. Dokumen</td>
                <td>:</td>
                <td>HUM/PKL/FO-014</td>
            </tr>
            <tr>
                <td>No. Revisi</td>
                <td>:</td>
                <td>02</td>
            </tr>
            <tr>
                <td>Tanggal Berlaku</td>
                <td>:</td>
                <td>02 Januari 2022</td>
            </tr>
        </table>
    </div>

    {{-- KOP SURAT --}}
    @if($sekolah && $sekolah->kop && file_exists(storage_path('app/public/' . $sekolah->kop)))
        <div style="width:100%; text-align:center; margin-bottom:12px;">
            <img src="{{ asset('storage/' . $sekolah->kop) }}" style="width:100%; max-height:130px; object-fit:contain;">
        </div>
    @else
        <div class="kop-wrapper">
            <table class="kop-table">
                <tr>
                    <td style="width:15%; text-align:left;">
                        @if($sekolah && $sekolah->logo && file_exists(storage_path('app/public/' . $sekolah->logo)))
                            <img src="{{ asset('storage/' . $sekolah->logo) }}" style="width:75px; height:auto;">
                        @else
                            <div style="font-weight:bold; font-size:24pt; color:#1e3a8a;">⚙️</div>
                        @endif
                    </td>
                    <td style="width:70%; text-align:center;">
                        <div class="kop-title">MAJLIS PENDIDIKAN DASAR DAN MENENGAH PIMPINAN DAERAH MUHAMMADIYAH KABUPATEN BANTUL</div>
                        <div class="kop-subtitle">{{ $sekolah->nama_sekolah ?? 'SMK MUHAMMADIYAH 1 BANTUL' }}</div>
                        <div class="kop-akreditasi">Terakreditasi: A</div>
                        <div class="kop-alamat">{{ $sekolah->alamat_sekolah ?? 'Jl. Parangtritis KM.12, Manding, Trirenggo, Bantul D.I Yogyakarta 55714' }}</div>
                    </td>
                    <td style="width:15%; text-align:right;">
                        <div style="font-weight:bold; font-size:24pt; color:#16a34a;">☀️</div>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    {{-- METADATA SURAT --}}
    <table class="surat-meta">
        <tr>
            <td style="width: 80px;">No.</td>
            <td style="width: 15px;">:</td>
            <td>{{ $surat->nomor_surat }}</td>
            <td style="text-align: right;">{{ $sekolah->kota ?? 'Bantul' }}, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Lampiran</td>
            <td>:</td>
            <td colspan="2">1</td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>:</td>
            <td colspan="2"><strong>Penerjunan Peserta Praktik Kerja Lapangan – PKL</strong></td>
        </tr>
    </table>

    {{-- TUJUAN SURAT --}}
    <div class="tujuan-block">
        <div>Kepada Yth,</div>
        <div>Bapak / ibu Pembimbing PKL</div>
        <div><strong>{{ $surat->dudi?->nama_dudi }}</strong></div>
        <br>
        <div>Di Tempat</div>
    </div>

    {{-- ISI SURAT --}}
    <div class="isi-surat">
        <p style="margin-bottom: 12px; font-style: italic;">Assalamu’alaikum Wr. Wb</p>
        
        <p class="isi-p">
            Sesuai dengan kesepakatan tentang kesediaan menerima peserta Praktik Kerja Lapangan (PKL) dari {{ $sekolah->nama_sekolah ?? 'SMK Muhammadiyah 1 Bantul' }}, maka kepada perusahaan / bengkel ini, kami menerjunkan / mengirimkan siswa peserta Praktik Kerja Lapangan - PKL, seperti yang tercantum pada lampiran.
        </p>
        <p class="isi-p">
            Selanjutnya kami mohon bantuannya agar pihak perusahaan/ bengkel berkenan memberikan bimbingan langsung dan memberikan penilaian hasil praktik terhadap siswa kami yang melaksanakan Praktik Kerja Lapangan, bersama dengan Pembimbing dari Sekolah.
        </p>
        <p class="isi-p">
            Demikian surat ini dibuat, atas perhatian dan kerjasamanya kami sampaikan terima kasih yang sebesar-besarnya.
        </p>

        <p style="margin-top: 12px; margin-bottom: 24px; font-style: italic;">.Wassalamu’alaikum Wr. Wb</p>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="ttd-container">
        <div class="ttd-box-right">
            <div>Kepala Sekolah</div>
            <div style="height: 70px; position: relative; display: flex; align-items: center; justify-content: center; margin: 4px 0;">
                @if(!empty($sekolah->ttd_kepala_sekolah) && file_exists(storage_path('app/public/' . $sekolah->ttd_kepala_sekolah)))
                    <img src="{{ asset('storage/' . $sekolah->ttd_kepala_sekolah) }}" class="img-ttd-digital" style="max-height: 80px; width: auto; object-fit: contain; {{ ($denganTtd ?? true) ? '' : 'display:none;' }}">
                @endif
            </div>
            <div style="font-weight: bold;">{{ $sekolah->kepala_sekolah ?? 'Harimawan,S.Pd.T.,M.S.I.' }}</div>
            <div>{{ $sekolah->nip_kepsek ? (str_contains($sekolah->nip_kepsek, 'NBM') ? $sekolah->nip_kepsek : 'NBM. ' . $sekolah->nip_kepsek) : 'NBM. 907793' }}</div>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>


{{-- ============================================================================ --}}
{{-- HALAMAN 2: LAMPIRAN DAFTAR SISWA --}}
{{-- ============================================================================ --}}
<div class="page">
    {{-- Header Dokumen Kanan Atas ISO --}}
    <div class="clearfix">
        <table class="doc-header-box">
            <tr>
                <td>No. Dokumen</td>
                <td>:</td>
                <td>HUM/PKL/FO-014</td>
            </tr>
            <tr>
                <td>No. Revisi</td>
                <td>:</td>
                <td>02</td>
            </tr>
            <tr>
                <td>Tanggal Berlaku</td>
                <td>:</td>
                <td>02 Januari 2022</td>
            </tr>
        </table>
    </div>

    {{-- KOP SURAT --}}
    @if($sekolah && $sekolah->kop && file_exists(storage_path('app/public/' . $sekolah->kop)))
        <div style="width:100%; text-align:center; margin-bottom:12px;">
            <img src="{{ asset('storage/' . $sekolah->kop) }}" style="width:100%; max-height:130px; object-fit:contain;">
        </div>
    @else
        <div class="kop-wrapper">
            <table class="kop-table">
                <tr>
                    <td style="width:15%; text-align:left;">
                        @if($sekolah && $sekolah->logo && file_exists(storage_path('app/public/' . $sekolah->logo)))
                            <img src="{{ asset('storage/' . $sekolah->logo) }}" style="width:75px; height:auto;">
                        @else
                            <div style="font-weight:bold; font-size:24pt; color:#1e3a8a;">⚙️</div>
                        @endif
                    </td>
                    <td style="width:70%; text-align:center;">
                        <div class="kop-title">MAJLIS PENDIDIKAN DASAR DAN MENENGAH PIMPINAN DAERAH MUHAMMADIYAH KABUPATEN BANTUL</div>
                        <div class="kop-subtitle">{{ $sekolah->nama_sekolah ?? 'SMK MUHAMMADIYAH 1 BANTUL' }}</div>
                        <div class="kop-akreditasi">Terakreditasi: A</div>
                        <div class="kop-alamat">{{ $sekolah->alamat_sekolah ?? 'Jl. Parangtritis KM.12, Manding, Trirenggo, Bantul D.I Yogyakarta 55714' }}</div>
                    </td>
                    <td style="width:15%; text-align:right;">
                        <div style="font-weight:bold; font-size:24pt; color:#16a34a;">☀️</div>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    {{-- JUDUL LAMPIRAN --}}
    <div style="font-weight: bold; font-size: 11pt; margin-bottom: 14px;">LAMPIRAN</div>

    {{-- DETAIL LAMPIRAN --}}
    <table class="lampiran-meta">
        <tr>
            <td style="width: 160px;">No</td>
            <td style="width: 15px;">:</td>
            <td>{{ $surat->nomor_surat }}</td>
        </tr>
        <tr>
            <td>Nama DuDi</td>
            <td>:</td>
            <td><strong>{{ $surat->dudi?->nama_dudi }}</strong></td>
        </tr>
        <tr>
            <td>Alamat DuDi</td>
            <td>:</td>
            <td>{{ trim(($surat->dudi?->alamat ?? '') . ' ' . ($surat->dudi?->kota ?? '')) }}</td>
        </tr>
        <tr>
            <td>Nama Pembimbing</td>
            <td>:</td>
            <td></td>
        </tr>
        <tr>
            <td>Waktu PKL</td>
            <td>:</td>
            <td>
                @if($surat->gelombang?->tanggal_mulai && $surat->gelombang?->tanggal_selesai)
                    {{ \Carbon\Carbon::parse($surat->gelombang->tanggal_mulai)->translatedFormat('d F Y') }} s/d {{ \Carbon\Carbon::parse($surat->gelombang->tanggal_selesai)->translatedFormat('d F Y') }}
                @else
                    -
                @endif
            </td>
        </tr>
    </table>

    {{-- SUB-JUDUL DAFTAR SISWA --}}
    <h3 style="text-align: center; font-size: 12pt; font-weight: bold; margin-top: 20px; margin-bottom: 16px; text-transform: uppercase;">DAFTAR NAMA SISWA PESERTA PKL</h3>

    {{-- TABEL DAFTAR SISWA --}}
    <table class="tabel-siswa-lampiran">
        <thead>
            <tr>
                <th style="width: 45px;">NO</th>
                <th style="width: 110px;">KELAS</th>
                <th style="width: 110px;">NIS</th>
                <th>NAMA</th>
                <th>KOMPETENSI KEAHLIAN</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penempatan as $i => $p)
            @php
                $namaSiswa = is_object($p->siswa) ? ($p->siswa->nama_siswa ?? $p->nis) : ($p->nama_siswa ?? $p->nis);
                $nisSiswa = $p->nis ?? (is_object($p->siswa) ? $p->siswa->nis : '-');
                $namaKelas = is_object($p->siswa) ? (optional($p->siswa->kelas)->nama_kelas ?? '-') : ($p->nama_kelas ?? '-');
                $keahlian = is_object($p->siswa) ? (optional(optional($p->siswa->kelas)->jurusan)->nama_jurusan ?? '-') : ($p->keahlian ?? '-');
            @endphp
            <tr>
                <td style="text-align: center;">{{ $i + 1 }}</td>
                <td style="text-align: center;">{{ $namaKelas }}</td>
                <td style="text-align: center;">{{ $nisSiswa }}</td>
                <td>{{ $namaSiswa }}</td>
                <td>{{ $keahlian }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align: center; font-style: italic;">Tidak ada data siswa</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
function toggleTtd(show) {
    document.querySelectorAll('.img-ttd-digital').forEach(el => {
        el.style.display = show ? 'block' : 'none';
    });
}
</script>
</body>
</html>
