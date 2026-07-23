<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Masal Surat PKL — {{ $gelombang->nama_gelombang }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; color: #000; background: #fff; }

        /* === Setiap surat dimulai di halaman baru === */
        .surat-page {
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm 25mm 20mm 30mm;
            page-break-after: always;
        }
        .surat-page:last-child { page-break-after: avoid; }

        .kop {
            display: flex;
            align-items: center;
            gap: 16px;
            border-bottom: 3px solid #000;
            padding-bottom: 8px;
            margin-bottom: 16px;
        }
        .kop-logo { width: 80px; height: 80px; object-fit: contain; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .nama-sekolah { font-size: 16pt; font-weight: bold; text-transform: uppercase; letter-spacing: 1px; }
        .kop-text .alamat { font-size: 9pt; margin-top: 3px; }

        .nomor-tabel td { vertical-align: top; font-size: 11pt; padding: 1px 0; }
        .nomor-tabel td:nth-child(2) { padding: 1px 8px; }

        h2.judul {
            text-align: center;
            font-size: 13pt;
            text-decoration: underline;
            margin: 16px 0 12px;
            text-transform: uppercase;
        }

        .isi p { text-align: justify; line-height: 1.8; margin-bottom: 8px; }

        .tabel-siswa { width: 100%; border-collapse: collapse; margin: 12px 0; }
        .tabel-siswa th, .tabel-siswa td { border: 1px solid #000; padding: 5px 8px; font-size: 10.5pt; }
        .tabel-siswa th { background: #f0f0f0; text-align: center; font-weight: bold; }
        .tabel-siswa td:first-child, .tabel-siswa td.tc { text-align: center; }

        .ttd-block { margin-top: 24px; text-align: center; float: right; width: 220px; }
        .ttd-block .ttd-space { height: 80px; }
        .ttd-block .nama-ttd { font-weight: bold; text-decoration: underline; }
        .ttd-block .nip { font-size: 10pt; }

        /* === Tombol Cetak (tidak ikut cetak) === */
        .no-print {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .no-print button, .no-print a {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            cursor: pointer;
            text-decoration: none;
            display: block;
            text-align: center;
        }
        .btn-print { background: #0d9488; color: #fff; }
        .btn-back  { background: #f1f5f9; color: #334155; border: 1.5px solid #e2e8f0 !important; }

        @media print {
            .no-print { display: none; }
            .surat-page { padding: 15mm 20mm 15mm 25mm; }
        }
    </style>
</head>
<body>

<div class="no-print">
    <button class="btn-print" onclick="window.print()">🖨️ Cetak Semua Surat ({{ count($dataCetak) }})</button>
    <a class="btn-back" href="{{ route('pkl.persuratan.index') }}">← Kembali</a>
</div>

@foreach($dataCetak as $item)
@php
    $surat     = $item['surat'];
    $penempatan = $item['penempatan'];
    $romawi    = ['','I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
    $bln       = $romawi[(int)\Carbon\Carbon::parse($surat->tanggal_surat)->format('n')];
@endphp

<div class="surat-page">

    {{-- KOP SURAT --}}
    @include('partials.kop-surat')

    {{-- Nomor Surat --}}
    <table class="nomor-tabel" style="margin: 12px 0;">
        <tr><td>Nomor</td><td>:</td><td>{{ $surat->nomor_surat }}</td></tr>
        <tr><td>Lampiran</td><td>:</td><td>1 Lembar</td></tr>
        <tr>
            <td>Hal</td><td>:</td>
            <td><strong>{{ $surat->hal }}</strong></td>
        </tr>
    </table>

    {{-- Tujuan --}}
    <div style="margin: 12px 0;">
        <div>Kepada Yth.</div>
        <div>{{ $surat->dudi?->nama_pic ? 'Bapak/Ibu ' . $surat->dudi->nama_pic : 'Pimpinan / HRD' }}</div>
        <div><strong>{{ $surat->dudi?->nama_dudi }}</strong></div>
        <div>{{ $surat->dudi?->alamat }}</div>
        <div>{{ $surat->dudi?->kota }}</div>
    </div>

    <div style="margin-bottom: 10px;">di tempat</div>

    {{-- Judul berdasarkan jenis surat --}}
    @if($jenisSurat === 'permohonan')
        <h2 class="judul">Surat Permohonan Praktik Kerja Lapangan</h2>
    @elseif($jenisSurat === 'penempatan')
        <h2 class="judul">Surat Pengantar Penempatan Siswa PKL</h2>
    @else
        <h2 class="judul">Surat Penarikan Siswa Praktik Kerja Lapangan</h2>
    @endif

    <p style="margin-bottom:10px;">Assalamu'alaikum Wr. Wb.</p>

    <div class="isi">
        @if($jenisSurat === 'permohonan')
        <p>Dengan hormat, sehubungan dengan program Praktik Kerja Lapangan (PKL) yang merupakan bagian dari kurikulum pendidikan kejuruan di <strong>{{ $sekolah?->nama_sekolah ?? 'SMK SmartSchool' }}</strong>, kami bermaksud mengajukan permohonan kepada Bapak/Ibu untuk kiranya dapat menerima siswa-siswi kami dalam program PKL:</p>
        @elseif($jenisSurat === 'penempatan')
        <p>Dengan hormat, menyusul surat permohonan kami sebelumnya, dengan ini kami sampaikan bahwa siswa-siswi kami berikut telah siap untuk melaksanakan Praktik Kerja Lapangan (PKL) di <strong>{{ $surat->dudi?->nama_dudi }}</strong>:</p>
        @else
        <p>Dengan hormat, sehubungan dengan telah berakhirnya masa Praktik Kerja Lapangan (PKL) <strong>{{ $gelombang->nama_gelombang }}</strong>, bersama surat ini kami bermaksud menarik kembali siswa-siswi kami yang telah melaksanakan PKL di <strong>{{ $surat->dudi?->nama_dudi }}</strong>:</p>
        @endif

        {{-- Tabel Siswa --}}
        <table class="tabel-siswa">
            <thead>
                <tr>
                    <th style="width:35px;">No</th>
                    <th>Nama Siswa</th>
                    <th style="width:100px;">NIS</th>
                    <th style="width:100px;">Kelas</th>
                    @if($jenisSurat === 'penempatan')
                    <th style="width:120px;">Tgl Masuk</th>
                    @elseif($jenisSurat === 'penarikan')
                    <th style="width:120px;">Tgl Keluar</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($penempatan as $i => $p)
                <tr>
                    <td class="tc">{{ $i + 1 }}</td>
                    <td>{{ $p->siswa?->nama_siswa ?? $p->nis }}</td>
                    <td class="tc">{{ $p->nis }}</td>
                    <td class="tc">{{ optional(optional($p->siswa)->kelas)->nama_kelas ?? '-' }}</td>
                    @if($jenisSurat === 'penempatan')
                    <td class="tc">{{ $p->tanggal_masuk ? \Carbon\Carbon::parse($p->tanggal_masuk)->format('d/m/Y') : '-' }}</td>
                    @elseif($jenisSurat === 'penarikan')
                    <td class="tc">{{ $p->tanggal_keluar ? \Carbon\Carbon::parse($p->tanggal_keluar)->format('d/m/Y') : \Carbon\Carbon::parse($gelombang->tanggal_selesai)->format('d/m/Y') }}</td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="5" style="text-align:center; font-style:italic;">Tidak ada data siswa</td></tr>
                @endforelse
            </tbody>
        </table>

        @if($jenisSurat === 'permohonan')
        <p>Pelaksanaan PKL direncanakan pada tanggal <strong>{{ \Carbon\Carbon::parse($gelombang->tanggal_mulai)->translatedFormat('d F Y') }}</strong> sampai dengan <strong>{{ \Carbon\Carbon::parse($gelombang->tanggal_selesai)->translatedFormat('d F Y') }}</strong>.</p>
        <p>Atas perhatian dan kesediaan Bapak/Ibu untuk menerima siswa-siswi kami, kami ucapkan terima kasih yang sebesar-besarnya.</p>
        @elseif($jenisSurat === 'penempatan')
        <p>Pelaksanaan PKL berlangsung dari <strong>{{ \Carbon\Carbon::parse($gelombang->tanggal_mulai)->translatedFormat('d F Y') }}</strong> sampai <strong>{{ \Carbon\Carbon::parse($gelombang->tanggal_selesai)->translatedFormat('d F Y') }}</strong>.</p>
        <p>Demikian surat pengantar ini kami sampaikan. Atas kerja sama dan bimbingan Bapak/Ibu, kami ucapkan terima kasih.</p>
        @else
        <p>Penarikan resmi dilaksanakan pada tanggal <strong>{{ \Carbon\Carbon::parse($gelombang->tanggal_selesai)->translatedFormat('d F Y') }}</strong>.</p>
        <p>Kami mengucapkan terima kasih yang sebesar-besarnya atas bimbingan dan fasilitas yang telah Bapak/Ibu berikan kepada siswa-siswi kami selama melaksanakan PKL. Semoga kerja sama yang baik ini dapat terus berlanjut di masa mendatang.</p>
        @endif

        <p>Wassalamu'alaikum Wr. Wb.</p>
    </div>

    {{-- TTD --}}
    <div style="margin-top: 20px; overflow: hidden;">
        <div style="float: left; width: 200px;">
            <div>{{ $surat->dudi?->kota ?? 'Kota' }}, {{ \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') }}</div>
        </div>
        <div class="ttd-block">
            <div>Kepala Sekolah,</div>
            <div class="ttd-space"></div>
            <div class="nama-ttd">{{ $sekolah?->kepala_sekolah ?? '( ____________________ )' }}</div>
            <div class="nip">NIP. {{ $sekolah?->nip_kepsek ?? '-' }}</div>
        </div>
        <div style="clear:both;"></div>
    </div>

</div>
@endforeach

</body>
</html>
