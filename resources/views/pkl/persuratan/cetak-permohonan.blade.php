<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Permohonan PKL — {{ $surat->nomor_surat }}</title>
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

        /* Box Header Kanan Atas Dokumen */
        .doc-header-box {
            float: right;
            border: 1px solid #000;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 10px;
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
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-subtitle {
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .kop-aksara {
            font-size: 14pt;
            margin: 2px 0;
        }
        .kop-akreditasi {
            font-size: 10pt;
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
            margin-bottom: 16px;
            font-size: 11pt;
        }
        .surat-meta td {
            vertical-align: top;
            padding: 1px 0;
        }

        /* Tujuan Surat */
        .tujuan-block {
            margin-bottom: 16px;
            line-height: 1.4;
        }

        /* Isi Paragraph */
        .isi-p {
            text-align: justify;
            text-indent: 36px;
            line-height: 1.5;
            margin-bottom: 12px;
        }

        /* Tabel Surat Kesediaan (Halaman 2) */
        .form-header-table {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #000;
            margin-bottom: 16px;
        }
        .form-header-table td {
            border: 1px solid #000;
            padding: 4px 8px;
            vertical-align: middle;
            font-size: 9.5pt;
        }

        h2.judul-kesediaan {
            text-align: center;
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.3;
            margin: 16px 0;
        }

        .table-siswa-kesediaan {
            width: 100%;
            border-collapse: collapse;
            margin: 14px 0;
        }
        .table-siswa-kesediaan th, .table-siswa-kesediaan td {
            border: 1px solid #000;
            padding: 6px 8px;
            font-size: 10pt;
            vertical-align: middle;
        }
        .table-siswa-kesediaan th {
            background-color: #fef3c7;
            text-align: center;
            font-weight: bold;
        }

        /* Area TTD */
        .ttd-container {
            margin-top: 20px;
            width: 100%;
        }
        .ttd-box-right {
            float: right;
            width: 250px;
            text-align: left;
        }

        .print-btn { 
            position: fixed; 
            top: 20px; 
            right: 20px; 
            background: #4f46e5; 
            color: #fff; 
            border: none; 
            padding: 10px 20px; 
            border-radius: 8px; 
            font-size: 14px; 
            font-weight: 600;
            cursor: pointer; 
            font-family: sans-serif; 
            z-index: 9999; 
            box-shadow: 0 4px 12px rgba(79,70,229,0.4);
        }

        @media print {
            body { background: #fff; }
            .print-btn { display: none; }
            .page { 
                margin: 0; 
                box-shadow: none; 
                padding: 12mm 15mm 12mm 20mm;
                width: 100%;
                min-height: auto;
            }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨️ Cetak Surat (2 Halaman)</button>

{{-- ============================================================================ --}}
{{-- HALAMAN 1: SURAT PERMOHONAN TEMPAT PKL --}}
{{-- ============================================================================ --}}
<div class="page">
    {{-- Header Dokumen Kanan Atas --}}
    <div class="clearfix">
        <table class="doc-header-box">
            <tr>
                <td>No. Dokumen</td>
                <td>:</td>
                <td>HUM/PKL/FO-001</td>
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

    {{-- Meta Surat --}}
    <table class="surat-meta">
        <tr>
            <td style="width:80px;">Nomor</td>
            <td style="width:15px;">:</td>
            <td>{{ $surat->nomor_surat }}</td>
        </tr>
        <tr>
            <td>Lamp.</td>
            <td>:</td>
            <td>2 lembar</td>
        </tr>
        <tr>
            <td>Hal</td>
            <td>:</td>
            <td>Permohonan Tempat Praktik Kerja Lapangan (PKL)</td>
        </tr>
    </table>

    {{-- Tujuan Surat --}}
    <div class="tujuan-block">
        <div>Kepada Yth.</div>
        <div>Direktur/Pimpinan/Kepala :</div>
        <div><strong>{{ $surat->dudi?->nama_dudi ?? 'Bengkel Pak Giarto' }}</strong></div>
        <br>
        <div>Di Tempat</div>
    </div>

    {{-- Isi Surat Permohonan --}}
    <div class="isi-block">
        <p class="isi-p">
            Dalam rangka meningkatkan kualitas Lulusan Sekolah Menengah Kejuruan agar sesuai dengan tuntutan kebutuhan masyarakat, diperlukan upaya maksimal dalam mencapai tujuan kurikulum. Sebagai pola utama penyelenggaraan Kurikulum Sekolah Menengah Kejuruan (SMK) adalah dilaksanakannya Praktik Kerja di Dunia Usaha/ Dunia Industri dalam program Praktik Kerja Lapangan (PKL).
        </p>
        <p class="isi-p">
            Sehubungan dengan hal itu guna menunjang program PKL tersebut, dengan ini kami mohon dengan hormat bantuannya untuk berkenan menerima siswa-siswi kami dalam pelaksanaan praktik industri di perusahaan yang Bapak/Ibu Pimpin.
        </p>
        <p class="isi-p">
            Agar sekolah mendapatkan data dari Perusahaan atau Industri yang berkenan menerima peserta praktik, mohon agar pihak Perusahaan/Industri mengisi Surat Kesediaan menerima peserta praktik rangkap 2 seperti terlampir.
        </p>
        <p class="isi-p">
            Selanjutnya kami mohon agar dari pihak perusahaan juga dapat memberikan bimbingan langsung dan memberikan penilaian hasil praktik industri terhadap para siswa kami yang melaksanakan PKL.
        </p>
        <p class="isi-p" style="text-indent: 50px;">
            Demikian atas kerja sama yang baik diucapkan terima kasih.
        </p>
    </div>

    {{-- Area TTD Kepala Sekolah --}}
    <div class="ttd-container clearfix">
        <div class="ttd-box-right">
            <div>{{ $sekolah->kota ?? 'Bantul' }}, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</div>
            <div>Kepala {{ $sekolah->nama_sekolah ?? 'SMK Muhammadiyah 1 Bantul' }}</div>
            <div style="height: 60px;"></div>
            <div><strong>{{ $sekolah->kepala_sekolah ?? 'Harimawan, S.Pd.T., M.S.I.' }}</strong></div>
            <div>NBM. {{ $sekolah->nip ?? '907793' }}</div>
        </div>
    </div>
</div>

{{-- ============================================================================ --}}
{{-- HALAMAN 2: SURAT KESEDIAAN MENERIMA PESERTA PRAKTIK INDUSTRI --}}
{{-- ============================================================================ --}}
<div class="page">
    {{-- Header Dokumen Kanan Atas --}}
    <div class="clearfix">
        <table class="doc-header-box">
            <tr>
                <td>No. Dokumen</td>
                <td>:</td>
                <td>HUM/PKL/FO-001</td>
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

    {{-- Form Header Tabel --}}
    <table class="form-header-table">
        <tr>
            <td style="width:25%; text-align:center;">
                @if($sekolah && $sekolah->logo && file_exists(storage_path('app/public/' . $sekolah->logo)))
                    <img src="{{ asset('storage/' . $sekolah->logo) }}" style="max-height:50px;">
                @else
                    <div style="font-weight:bold; font-size:16pt; color:#1e3a8a;">MUSABA</div>
                @endif
            </td>
            <td style="width:40%; text-align:center; font-weight:bold;">
                <div>FORMULIR</div>
                <div style="margin-top:4px;">DAYA TAMPUNG DU/DI</div>
            </td>
            <td style="width:35%; padding:0;">
                <table style="width:100%; border-collapse:collapse; font-size:8.5pt;">
                    <tr>
                        <td style="border-bottom:1px solid #000; border-right:1px solid #000; padding:2px 4px; font-weight:bold;">Kode Dok.</td>
                        <td style="border-bottom:1px solid #000; padding:2px 4px;">HUM/PKL/FO-001</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; border-right:1px solid #000; padding:2px 4px; font-weight:bold;">No. Revisi</td>
                        <td style="border-bottom:1px solid #000; padding:2px 4px;">02</td>
                    </tr>
                    <tr>
                        <td style="border-bottom:1px solid #000; border-right:1px solid #000; padding:2px 4px; font-weight:bold;">Halaman</td>
                        <td style="border-bottom:1px solid #000; padding:2px 4px;">1 dari 1</td>
                    </tr>
                    <tr>
                        <td style="border-right:1px solid #000; padding:2px 4px; font-weight:bold;">Tanggal Berlaku</td>
                        <td style="padding:2px 4px;">02 Januari 2022</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    {{-- Meta Lampiran --}}
    <table class="surat-meta" style="margin-bottom:12px;">
        <tr>
            <td style="width:100px;">Lampiran 1</td>
            <td style="width:15px;">:</td>
            <td>Permohonan Tempat Praktik Kerja Lapangan (PKL)</td>
        </tr>
        <tr>
            <td>Nomor</td>
            <td>:</td>
            <td>{{ $surat->nomor_surat }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td>{{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</td>
        </tr>
    </table>

    {{-- Judul Surat Kesediaan --}}
    <h2 class="judul-kesediaan">
        SURAT KESEDIAAN<br>
        MENERIMA PESERTA PRAKTIK INDUSTRI<br>
        PRAKTIK KERJA LAPANGAN (PKL)<br>
        SISWA {{ strtoupper($sekolah->nama_sekolah ?? 'SMK MUHAMMADIYAH 1 BANTUL') }}
    </h2>

    {{-- Form Identitas DU/DI --}}
    <div style="line-height: 1.6; margin-bottom:12px;">
        <div>Yang bertanda tangan di bawah ini :</div>
        <table style="width:100%; margin-left:10px;">
            <tr>
                <td style="width:160px;">Nama</td>
                <td style="width:15px;">:</td>
                <td>...........................................................................................</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>...........................................................................................</td>
            </tr>
        </table>

        <div style="margin-top:8px;">Bertindak mewakili perusahaan/Instansi sebagai berikut :</div>
        <table style="width:100%; margin-left:10px;">
            <tr>
                <td style="width:160px;">Nama Perusahaan</td>
                <td style="width:15px;">:</td>
                <td><strong>{{ $surat->dudi?->nama_dudi ?? 'Bengkel Pak Giarto' }}</strong></td>
            </tr>
            <tr>
                <td>Alamat Perusahaan</td>
                <td>:</td>
                <td>{{ $surat->dudi?->alamat ?? 'Sudimoro RT 07 Timbulharjo Sewon Bantul' }}</td>
            </tr>
            <tr>
                <td>Nomor Telepon Perusahaan</td>
                <td>:</td>
                <td>{{ $surat->dudi?->no_telepon ?? '085102332811' }}</td>
            </tr>
        </table>
    </div>

    {{-- Pernyataan Kesediaan --}}
    <div style="text-align:justify; line-height:1.5; margin-bottom:10px;">
        Dengan ini menyatakan bahwa perusahaan kami bersedia menerima siswa - siswi SMK Muhammadiyah 1 Bantul untuk melaksanakan Praktik Kerja Lapangan dengan ketentuan sebagai berikut :
    </div>

    <div style="font-weight:bold; margin-bottom:8px;">
        Jumlah siswa : {{ count($penempatan) }} Siswa
    </div>

    {{-- Tabel Daftar Siswa Kesediaan --}}
    <table class="table-siswa-kesediaan">
        <thead>
            <tr>
                <th style="width:35px;">No</th>
                <th style="width:90px;">Kelas</th>
                <th style="width:75px;">NIS</th>
                <th>Nama</th>
                <th>Kompetensi Keahlian</th>
                <th style="width:130px;">Waktu Prakerin</th>
            </tr>
        </thead>
        <tbody>
            @php
                $tglMulai = $surat->gelombang?->tanggal_mulai ? \Carbon\Carbon::parse($surat->gelombang->tanggal_mulai)->translatedFormat('j F Y') : '1 Juli 2026';
                $tglSelesai = $surat->gelombang?->tanggal_selesai ? \Carbon\Carbon::parse($surat->gelombang->tanggal_selesai)->translatedFormat('j F Y') : '30 November 2026';
            @endphp
            @forelse($penempatan as $i => $p)
            @php
                $namaSiswa = $p->siswa?->nama_siswa ?? (is_object($p->siswa) ? $p->siswa->nama_siswa : $p->nis);
                $nisSiswa = $p->nis;
                $kelasSiswa = optional(optional($p->siswa)->kelas)->nama_kelas ?? (is_object($p->siswa) && isset($p->siswa->kelas) ? $p->siswa->kelas->nama_kelas : '-');
                $keahlianSiswa = optional(optional(optional($p->siswa)->kelas)->jurusan)->nama_jurusan ?? (is_object($p->siswa) && isset($p->siswa->kelas->jurusan) ? $p->siswa->kelas->jurusan->nama_jurusan : 'Teknik Kendaraan Ringan Otomotif');
            @endphp
            <tr>
                <td style="text-align:center;">{{ $i + 1 }}</td>
                <td style="text-align:center;">{{ $kelasSiswa }}</td>
                <td style="text-align:center;">{{ $nisSiswa }}</td>
                <td style="font-weight:600;">{{ $namaSiswa }}</td>
                <td>{{ $keahlianSiswa }}</td>
                <td style="text-align:center; font-size:9pt;">{{ $tglMulai }} s/d<br>{{ $tglSelesai }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; font-style:italic;">Belum ada data siswa terpilih</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top:10px;">
        Demikian surat kesediaan ini kami buat untuk dipergunakan seperlunya.
    </div>

    {{-- Area TTD DU / DI --}}
    <div class="ttd-container clearfix" style="margin-top:25px;">
        <div class="ttd-box-right" style="text-align:center;">
            <div>.......................,...................... 2026</div>
            <div style="margin-top:4px;">Kepala/Pimpinan</div>
            <div style="height: 75px;"></div>
            <div>................................................</div>
        </div>
    </div>
</div>

</body>
</html>
