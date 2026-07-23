<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Penarikan Siswa PKL — {{ $surat->nomor_surat }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #000; background: #fff; }
        .page { width: 210mm; min-height: 297mm; margin: 0 auto; padding: 20mm 25mm 20mm 30mm; }
        .kop { display: flex; align-items: center; gap: 16px; border-bottom: 3px solid #000; padding-bottom: 8px; margin-bottom: 16px; }
        .kop-logo { width: 80px; height: 80px; object-fit: contain; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .nama-sekolah { font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .alamat { font-size: 9pt; margin-top: 3px; }
        .nomor-tabel td { vertical-align: top; font-size: 11pt; padding: 1px 0; }
        .nomor-tabel td:nth-child(2) { padding: 1px 8px; }
        h2.judul { text-align: center; font-size: 13pt; text-decoration: underline; margin: 16px 0 12px; text-transform: uppercase; }
        .isi p { text-align: justify; line-height: 1.8; margin-bottom: 8px; }
        .tabel-siswa { width: 100%; border-collapse: collapse; margin: 12px 0; }
        .tabel-siswa th, .tabel-siswa td { border: 1px solid #000; padding: 5px 8px; font-size: 10.5pt; }
        .tabel-siswa th { background: #f0f0f0; text-align: center; font-weight: bold; }
        .tabel-siswa td:first-child { text-align: center; }
        .ttd-block { margin-top: 24px; text-align: center; float: right; width: 220px; }
        .ttd-block .ttd-space { height: 80px; }
        .ttd-block .nama-ttd { font-weight: bold; text-decoration: underline; }
        .print-btn { position: fixed; top: 16px; right: 16px; background: #f97316; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-size: 13px; cursor: pointer; font-family: sans-serif; z-index: 999; }
        @media print { .print-btn { display: none; } }
    </style>
</head>
<body>
<button class="print-btn" onclick="window.print()">🖨️ Cetak Surat</button>
<div class="page">
    {{-- KOP SURAT --}}
    @include('partials.kop-surat')

    <table class="nomor-tabel" style="margin:12px 0;">
        <tr><td>Nomor</td><td>:</td><td>{{ $surat->nomor_surat }}</td></tr>
        <tr><td>Hal</td><td>:</td><td><strong>Penarikan Siswa Praktik Kerja Lapangan (PKL)</strong></td></tr>
    </table>

    <div style="margin: 12px 0;">
        <div>Kepada Yth.</div>
        <div>{{ $surat->dudi?->nama_pic ? 'Bapak/Ibu ' . $surat->dudi->nama_pic : 'Pimpinan' }}</div>
        <div><strong>{{ $surat->dudi?->nama_dudi }}</strong></div>
        <div>{{ $surat->dudi?->kota ?? '' }}</div>
    </div>

    <h2 class="judul">Surat Penarikan Siswa Praktik Kerja Lapangan</h2>

    <p style="margin-bottom:10px;">Assalamu'alaikum Wr. Wb.</p>
    <div class="isi">
        <p>Dengan hormat, sehubungan dengan telah berakhirnya masa Praktik Kerja Lapangan (PKL) <strong>{{ $surat->gelombang?->nama_gelombang }}</strong>, bersama surat ini kami bermaksud menarik kembali siswa-siswi kami yang telah melaksanakan PKL di <strong>{{ $surat->dudi?->nama_dudi }}</strong>:</p>

        <table class="tabel-siswa">
            <thead>
                <tr>
                    <th style="width:35px;">No</th>
                    <th>Nama Siswa</th>
                    <th style="width:100px;">NIS</th>
                    <th style="width:100px;">Kelas</th>
                    <th style="width:120px;">Tgl Keluar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($penempatan as $i => $p)
                <tr>
                    <td>{{ $i+1 }}</td>
                    <td>{{ $p->siswa?->nama_siswa ?? $p->nis }}</td>
                    <td>{{ $p->nis }}</td>
                    <td>{{ optional(optional($p->siswa)->kelas)->nama_kelas ?? '-' }}</td>
                    <td>{{ $p->tanggal_keluar ? \Carbon\Carbon::parse($p->tanggal_keluar)->format('d/m/Y') : \Carbon\Carbon::parse($surat->gelombang?->tanggal_selesai)->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center;font-style:italic;">Tidak ada data siswa</td></tr>
                @endforelse
            </tbody>
        </table>

        <p>Penarikan resmi dilaksanakan pada tanggal <strong>{{ \Carbon\Carbon::parse($surat->gelombang?->tanggal_selesai)->translatedFormat('d F Y') }}</strong>.</p>
        <p>Kami mengucapkan terima kasih yang sebesar-besarnya atas bimbingan, pembinaan, dan segala fasilitas yang telah Bapak/Ibu berikan kepada siswa-siswi kami selama melaksanakan PKL. Semoga kerja sama yang baik ini dapat terus berlanjut di masa mendatang.</p>
        <p>Wassalamu'alaikum Wr. Wb.</p>
    </div>

    <div style="margin-top:20px; overflow:hidden;">
        <div style="float:left; width:200px;">
            <div>{{ $surat->dudi?->kota ?? 'Kota' }}, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</div>
        </div>
        <div class="ttd-block">
            <div>Kepala Sekolah,</div>
            <div class="ttd-space"></div>
            <div class="nama-ttd">{{ $sekolah?->kepala_sekolah ?? '( __________________ )' }}</div>
            <div style="font-size:10pt;">NIP. {{ $sekolah?->nip_kepsek ?? '-' }}</div>
        </div>
        <div style="clear:both;"></div>
    </div>
</div>
</body>
</html>
