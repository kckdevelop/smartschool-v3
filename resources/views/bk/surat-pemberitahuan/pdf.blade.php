<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Pemberitahuan Orang Tua</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.0cm 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.45;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .meta-table td {
            vertical-align: top;
            padding: 1px 0;
            border: none;
        }
        .content {
            text-align: justify;
            margin-bottom: 12px;
        }
        .content p {
            margin-top: 0;
            margin-bottom: 8px;
            text-indent: 30px;
        }
        .details-table {
            width: 90%;
            margin-left: 20px;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .details-table td {
            vertical-align: top;
            padding: 2.5px 5px;
            border: none;
        }
        .details-table td.label {
            width: 170px;
        }
        .details-table td.separator {
            width: 10px;
            text-align: center;
        }
        .sp-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin-bottom: 4px;
            margin-top: 10px;
        }
        .sp-subtitle {
            text-align: center;
            font-size: 10.5pt;
            margin-bottom: 15px;
        }
        .reason-box {
            background-color: #f9f9f9;
            padding: 10px 15px;
            border-left: 3px solid #333;
            margin: 10px 20px 15px 20px;
            font-style: italic;
        }
        .sign-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }
        .sign-table td {
            text-align: center;
            vertical-align: top;
            border: none;
            padding: 5px;
        }
        .sign-title {
            margin-bottom: 55px;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .sign-nip {
            font-size: 9.5pt;
            color: #333;
        }
        .warning-box {
            border: 1px dashed #d97706;
            background-color: #fffbeb;
            color: #92400e;
            padding: 8px 12px;
            margin: 10px 0;
            font-size: 10pt;
            border-radius: 4px;
        }
    </style>
</head>
<body>

    @php
        $canRenderImage = extension_loaded('gd');
        $isPreviewMode = isset($isPreview) && $isPreview;
        $isPdf = !$isPreviewMode;
    @endphp

    {{-- KOP SURAT --}}
    @include('partials.kop-surat')

    <div class="sp-title">SURAT PEMBERITAHUAN KEPADA ORANG TUA / WALI SISWA</div>
    <div class="sp-subtitle">Nomor: {{ $surat->no_surat ?? '-' }}</div>

    <table class="meta-table">
        <tr>
            <td style="width: 80px;">Perihal</td>
            <td style="width: 10px;">:</td>
            <td style="font-weight: bold;">Pemberitahuan Indisipliner Siswa (Pra-SP 1)</td>
            <td style="text-align: right; width: 200px;">
                {{ $surat->tanggal_surat ? \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') : '-' }}
            </td>
        </tr>
    </table>

    <div class="content">
        <p>Kepada Yth.<br>
        Bapak/Ibu Orang Tua / Wali dari <strong>{{ $surat->siswa?->nama_siswa ?? '-' }}</strong><br>
        di Tempat</p>

        <p style="margin-top: 15px;">Dengan hormat,</p>
        <p>Melalui surat ini, kami pihak sekolah menyampaikan pemberitahuan mengenai perkembangan sikap dan kedisiplinan putra/putri Bapak/Ibu di sekolah sebagai berikut:</p>
        
        <table class="details-table">
            <tr>
                <td class="label">Nama Siswa</td>
                <td class="separator">:</td>
                <td style="font-weight: bold;">{{ $surat->siswa?->nama_siswa ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">NIS / NISN</td>
                <td class="separator">:</td>
                <td>{{ $surat->nis }}</td>
            </tr>
            <tr>
                <td class="label">Kelas</td>
                <td class="separator">:</td>
                <td>{{ $surat->siswa?->kelas?->nama_kelas ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Wali Kelas</td>
                <td class="separator">:</td>
                <td>{{ $surat->siswa?->kelas?->guru?->nama_guru ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nama Orang Tua / Wali</td>
                <td class="separator">:</td>
                <td>{{ !empty($surat->nama_ortu) ? $surat->nama_ortu : ($surat->siswa?->detail?->nama_wali ?? $surat->siswa?->detail?->nama_ayah ?? $surat->siswa?->detail?->nama_ibu ?? '-') }}</td>
            </tr>
        </table>

        <p>Berdasarkan catatan kedisiplinan dan absensi di sekolah, siswa yang bersangkutan telah melakukan pelanggaran/kendala kedisiplinan berupa:</p>
        
        <div class="reason-box">
            "{{ $surat->alasan_pemberitahuan }}"
        </div>

        @if($surat->tindakan_sekolah)
            <p>Tindakan/imbauan yang disarankan oleh pihak sekolah:</p>
            <div style="margin-left: 20px; margin-bottom: 10px;">
                {!! nl2br(e($surat->tindakan_sekolah)) !!}
            </div>
        @endif

        <p>Surat Pemberitahuan ini kami sampaikan sebagai bentuk keterbukaan serta koordinasi awal antara pihak sekolah dengan orang tua/wali siswa <strong>sebelum diterbitkannya Surat Peringatan 1 (SP 1)</strong>. Kami sangat berharap Bapak/Ibu dapat memberikan perhatian khusus, pengarahan, serta bimbingan kepada putra/putri Bapak/Ibu di rumah.</p>

        <p><strong>Perhatian:</strong> Apabila setelah diterbitkannya Surat Pemberitahuan ini masalah indisipliner/ketidakhadiran siswa masih terus berulang, maka pihak sekolah akan menindaklanjuti proses ini ke tahap penerbitan <strong>Surat Peringatan 1 (SP 1)</strong> dan prosedur penanganan lebih lanjut sesuai tata tertib sekolah.</p>

        <p>Demikian surat pemberitahuan ini kami sampaikan. Atas perhatian, pengertian, dan kerja sama Bapak/Ibu Orang Tua/Wali Siswa, kami ucapkan terima kasih.</p>
    </div>

    <!-- TANDA TANGAN (Orang Tua & Wali Kelas & Guru BK & Kepala Sekolah) -->
    <table class="sign-table">
        <tr>
            <td style="width: 33%;">
                <div class="sign-title">
                    Orang Tua / Wali Siswa,
                </div>
                <div class="sign-name">{{ !empty($surat->nama_ortu) ? $surat->nama_ortu : ($surat->siswa?->detail?->nama_wali ?? $surat->siswa?->detail?->nama_ayah ?? $surat->siswa?->detail?->nama_ibu ?? '(..........................................)') }}</div>
                <div class="sign-nip">&nbsp;</div>
            </td>
            <td style="width: 34%;">
                <div class="sign-title">
                    Wali Kelas,
                </div>
                <div class="sign-name">{{ $surat->siswa?->kelas?->guru?->nama_guru ?? 'Wali Kelas' }}</div>
                <div class="sign-nip">NIP. {{ $surat->siswa?->kelas?->guru?->no_id ?? '-' }}</div>
            </td>
            <td style="width: 33%;">
                <div class="sign-title">
                    {{ $sekolah?->kota ?? 'Kota' }}, {{ $surat->tanggal_surat ? \Carbon\Carbon::parse($surat->tanggal_surat)->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                    Guru BK,
                </div>
                <div class="sign-name">{{ $surat->guru?->nama_guru ?? 'Guru Bimbingan Konseling' }}</div>
                <div class="sign-nip">NIP. {{ $surat->guru?->no_id ?? '-' }}</div>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="text-align: center; padding-top: 20px;">
                <div class="sign-title" style="margin-bottom: 45px;">
                    Mengetahui,<br>
                    Kepala Sekolah
                </div>
                <div class="sign-name">{{ $sekolah?->kepala_sekolah ?? 'Kepala Sekolah Smart School' }}</div>
                <div class="sign-nip">NIP. {{ $sekolah?->nip ?? '-' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
