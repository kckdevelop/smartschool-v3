<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rekapitulasi Laporan PKL — {{ optional($selectedGelombang)->nama_gelombang ?? 'Semua' }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; color: #000; background: #fff; line-height: 1.4; }
        .page { width: 297mm; min-height: 210mm; margin: 0 auto; padding: 15mm 20mm; } /* Landscape layout */
        .kop { display: flex; align-items: center; gap: 16px; border-bottom: 3px solid #000; padding-bottom: 8px; margin-bottom: 16px; }
        .kop-logo { width: 80px; height: 80px; object-fit: contain; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .nama-sekolah { font-size: 16pt; font-weight: bold; text-transform: uppercase; }
        .kop-text .alamat { font-size: 9pt; margin-top: 3px; }
        h2.judul { text-align: center; font-size: 13pt; text-decoration: underline; margin: 12px 0 6px; text-transform: uppercase; }
        h3.sub-judul { text-align: center; font-size: 11pt; margin-bottom: 20px; font-weight: normal; }
        .tabel-data { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .tabel-data th, .tabel-data td { border: 1px solid #000; padding: 6px 8px; font-size: 9.5pt; }
        .tabel-data th { background: #f0f0f0; text-align: center; font-weight: bold; }
        .tabel-data td:first-child, .tabel-data td.text-center { text-align: center; }
        .ttd-block { margin-top: 32px; text-align: center; float: right; width: 220px; }
        .ttd-block .ttd-space { height: 70px; }
        .ttd-block .nama-ttd { font-weight: bold; text-decoration: underline; }
        .print-btn { position: fixed; top: 16px; right: 16px; background: #0d9488; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-size: 13px; cursor: pointer; font-family: sans-serif; z-index: 999; }
        @media print {
            .print-btn { display: none; }
            .page { padding: 10mm 15mm; }
        }
    </style>
</head>
<body>
<button class="print-btn" onclick="window.print()">🖨️ Cetak Rekapitulasi</button>
<div class="page">
    {{-- KOP SURAT --}}
    @include('partials.kop-surat')

    <h2 class="judul">Laporan Rekapitulasi Penempatan Siswa PKL</h2>
    <h3 class="sub-judul">
        Gelombang: <strong>{{ optional($selectedGelombang)->nama_gelombang ?? 'Semua Gelombang' }}</strong>
        @if($selectedGelombang)
        (Periode: {{ \Carbon\Carbon::parse($selectedGelombang->tanggal_mulai)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($selectedGelombang->tanggal_selesai)->format('d/m/Y') }})
        @endif
    </h3>

    <table class="tabel-data">
        <thead>
            <tr>
                <th style="width:40px;">No</th>
                <th style="width:100px;">NIS</th>
                <th style="width:200px;">Nama Siswa</th>
                <th style="width:90px;">Kelas</th>
                <th>DUDI Tempat PKL</th>
                <th>Guru Pembimbing</th>
                <th style="width:180px;">Periode PKL</th>
                <th style="width:90px;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penempatan as $i => $p)
            <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $p->nis }}</td>
                <td style="font-weight:bold;">{{ optional($p->siswa)->nama_siswa ?? '-' }}</td>
                <td>{{ optional(optional($p->siswa)->kelas)->nama_kelas ?? '-' }}</td>
                <td>
                    <div style="font-weight:bold;">{{ optional($p->dudi)->nama_dudi ?? '-' }}</div>
                    <div style="font-size:8pt;color:#555;">{{ optional($p->dudi)->kota ?? '' }}</div>
                </td>
                <td>{{ optional(optional($p->pembimbing)->guru)->nama_guru ?? '-' }}</td>
                <td>
                    {{ $p->tanggal_masuk ? \Carbon\Carbon::parse($p->tanggal_masuk)->format('d/m/Y') : '-' }}
                    s/d
                    {{ $p->tanggal_keluar ? \Carbon\Carbon::parse($p->tanggal_keluar)->format('d/m/Y') : '-' }}
                </td>
                <td class="text-center" style="font-weight:bold;">
                    {{ strtoupper($p->status) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center; font-style:italic; padding:20px;">
                    Tidak ada data penempatan siswa
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Footer signatures --}}
    <div style="margin-top:40px; overflow:hidden;">
        <div style="float:left; width:220px; text-align:center;">
            <div>Mengetahui,</div>
            <div>Koordinator PKL SMK</div>
            <div style="height:70px;"></div>
            <div>( ________________________ )</div>
        </div>
        <div class="ttd-block">
            <div>{{ $sekolah?->kota ?? 'Wonosoobo' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div>Kepala Sekolah,</div>
            <div class="ttd-space"></div>
            <div class="nama-ttd">{{ $sekolah?->kepala_sekolah ?? '( ________________________ )' }}</div>
            <div style="font-size:9pt; margin-top:2px;">NIP. {{ $sekolah?->nip_kepsek ?? '-' }}</div>
        </div>
    </div>
</div>
</body>
</html>
