<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $panggil->jenis_panggilan === 'panggilan_biasa' ? 'Surat Undangan Orang Tua' : 'Surat Peringatan' }}</title>
    <style>
        @page {
            margin: 1.2cm 1.5cm 1.0cm 1.5cm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
        }
        .header-table td {
            vertical-align: middle;
            border: none;
        }
        .logo-cell {
            width: 80px;
            text-align: center;
        }
        .logo-img {
            max-width: 65px;
            max-height: 65px;
        }
        .school-info-cell {
            text-align: center;
            padding-right: 40px; /* Offset for centering due to logo */
        }
        .school-name {
            font-size: 15pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 0;
            line-height: 1.2;
        }
        .school-subtitle {
            font-size: 9.5pt;
            margin: 2px 0 0 0;
            font-style: italic;
            color: #333;
        }
        .divider {
            border: none;
            border-top: 3px double #000;
            margin-top: 4px;
            margin-bottom: 12px;
            height: 0;
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
            width: 85%;
            margin-left: 30px;
            border-collapse: collapse;
            margin-bottom: 12px;
        }
        .details-table td {
            vertical-align: top;
            padding: 2.5px 5px;
            border: none;
        }
        .details-table td.label {
            width: 150px;
        }
        .details-table td.separator {
            width: 10px;
            text-align: center;
        }
        .sign-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .sign-table td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            border: none;
        }
        .sign-title {
            margin-bottom: 45px;
        }
        .sign-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .sign-nip {
            font-size: 9.5pt;
            color: #333;
        }
        .sp-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            text-transform: uppercase;
            margin-bottom: 12px;
        }
        .sp-subtitle {
            text-align: center;
            font-size: 10.5pt;
            margin-top: -10px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

    @php
        $canRenderImage = extension_loaded('gd');
        $isPreviewMode = isset($isPreview) && $isPreview;
    @endphp

    {{-- KOP SURAT --}}
    @php $isPdf = !$isPreviewMode; @endphp
    @include('partials.kop-surat')

    @if($panggil->jenis_panggilan === 'panggilan_biasa')
        <!-- TIPE: PANGGILAN BIASA (UNDANGAN) -->
        <table class="meta-table">
            <tr>
                <td style="width: 80px;">Nomor</td>
                <td style="width: 10px;">:</td>
                <td>{{ $panggil->no_surat ?? '-' }}</td>
                <td style="text-align: right; width: 200px;">{{ \Carbon\Carbon::parse($panggil->tanggal_panggil)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>-</td>
                <td></td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td style="font-weight: bold;">Undangan Pertemuan Wali Murid</td>
                <td></td>
            </tr>
        </table>

        <div class="content" style="margin-top: 15px;">
            <p>Kepada Yth.<br>
            Bapak/Ibu Orang Tua / Wali dari <strong>{{ $panggil->siswa->nama_siswa ?? '-' }}</strong><br>
            di Tempat</p>

            <p style="margin-top: 20px;">Dengan hormat,</p>
            <p>Sehubungan dengan adanya beberapa hal penting yang perlu didiskusikan bersama mengenai proses pembelajaran siswa di sekolah, kami mengharapkan kehadiran Bapak/Ibu Orang Tua/Wali dari:</p>
            
            <table class="details-table">
                <tr>
                    <td class="label">Nama Siswa</td>
                    <td class="separator">:</td>
                    <td style="font-weight: bold;">{{ $panggil->siswa->nama_siswa ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">NIS</td>
                    <td class="separator">:</td>
                    <td>{{ $panggil->nis }}</td>
                </tr>
                <tr>
                    <td class="label">Kelas</td>
                    <td class="separator">:</td>
                    <td>{{ $panggil->siswa->kelas->nama_kelas ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Kendala Belajar</td>
                    <td class="separator">:</td>
                    <td>{{ $panggil->alasan_panggil }}</td>
                </tr>
            </table>

            <p>Untuk menghadiri pertemuan konsultasi yang akan diselenggarakan pada:</p>

            <table class="details-table">
                <tr>
                    <td class="label">Hari, Tanggal</td>
                    <td class="separator">:</td>
                    <td>{{ \Carbon\Carbon::parse($panggil->tanggal_panggil)->translatedFormat('l, d F Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Waktu</td>
                    <td class="separator">:</td>
                    <td>{{ \Carbon\Carbon::parse($panggil->waktu_pertemuan)->format('H:i') }} WIB s.d Selesai</td>
                </tr>
                <tr>
                    <td class="label">Tempat</td>
                    <td class="separator">:</td>
                    <td>{{ $panggil->lokasi_pertemuan }}</td>
                </tr>
                <tr>
                    <td class="label">Agenda</td>
                    <td class="separator">:</td>
                    <td>Konsultasi perkembangan belajar & penyelesaian kendala siswa</td>
                </tr>
                <tr>
                    <td class="label">Menemui</td>
                    <td class="separator">:</td>
                    <td>{{ $panggil->guru->nama_guru ?? '-' }} (Guru Bimbingan Konseling / Wali Kelas)</td>
                </tr>
            </table>

            <p>Mengingat pentingnya pertemuan ini demi kebaikan dan keberlanjutan proses belajar putra/putri Bapak/Ibu, kami sangat mengharapkan kehadiran Bapak/Ibu tepat pada waktunya.</p>
            <p>Demikian surat undangan ini kami sampaikan. Atas perhatian dan kerja sama Bapak/Ibu, kami ucapkan terima kasih.</p>
        </div>
    @else
        <!-- TIPE: SURAT PERINGATAN (SP 1 / 2 / 3) -->
        <div class="sp-title">
            SURAT PERINGATAN 
            @if($panggil->jenis_panggilan === 'sp_1')
                I (SP 1)
            @elseif($panggil->jenis_panggilan === 'sp_2')
                II (SP 2)
            @elseif($panggil->jenis_panggilan === 'sp_3')
                III (SP 3)
            @endif
        </div>
        <div class="sp-subtitle">Nomor: {{ $panggil->no_surat ?? '-' }}</div>

        <div class="content">
            <p>Surat Peringatan ini diberikan kepada siswa yang tertera di bawah ini:</p>
            
            <table class="details-table" style="margin-bottom: 25px;">
                <tr>
                    <td class="label">Nama Siswa</td>
                    <td class="separator">:</td>
                    <td style="font-weight: bold;">{{ $panggil->siswa->nama_siswa ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">NIS</td>
                    <td class="separator">:</td>
                    <td>{{ $panggil->nis }}</td>
                </tr>
                <tr>
                    <td class="label">Kelas</td>
                    <td class="separator">:</td>
                    <td>{{ $panggil->siswa->kelas->nama_kelas ?? '-' }}</td>
                </tr>
            </table>

            <p>Berdasarkan data catatan kedisiplinan dan hasil evaluasi perkembangan belajar di Smart School, siswa tersebut di atas telah melakukan pelanggaran berupa:</p>
            
            <div style="background-color: #f9f9f9; padding: 10px 15px; border-left: 3px solid #000; margin-left: 30px; margin-right: 30px; margin-bottom: 20px; font-style: italic;">
                "{{ $panggil->alasan_panggil }}"
            </div>

            <p>Tindakan tersebut telah melanggar tata tertib dan peraturan sekolah. Surat Peringatan ini diterbitkan dengan tujuan agar siswa yang bersangkutan melakukan perubahan perilaku ke arah yang positif, menaati peraturan sekolah, serta bersungguh-sungguh dalam menempuh pendidikannya.</p>

            <p>Sehubungan dengan hal tersebut, kami mengharapkan kehadiran Bapak/Ibu Orang Tua/Wali dari siswa bersangkutan untuk hadir di sekolah guna melakukan koordinasi dan pembinaan bersama pada:</p>

            <table class="details-table">
                <tr>
                    <td class="label">Hari, Tanggal</td>
                    <td class="separator">:</td>
                    <td>{{ \Carbon\Carbon::parse($panggil->tanggal_panggil)->translatedFormat('l, d F Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Waktu</td>
                    <td class="separator">:</td>
                    <td>{{ \Carbon\Carbon::parse($panggil->waktu_pertemuan)->format('H:i') }} WIB</td>
                </tr>
                <tr>
                    <td class="label">Tempat</td>
                    <td class="separator">:</td>
                    <td>{{ $panggil->lokasi_pertemuan }}</td>
                </tr>
                <tr>
                    <td class="label">Agenda</td>
                    <td class="separator">:</td>
                    <td>Penandatanganan surat pernyataan bersama & pembinaan khusus</td>
                </tr>
                <tr>
                    <td class="label">Menemui</td>
                    <td class="separator">:</td>
                    <td>{{ $panggil->guru->nama_guru ?? '-' }} (Guru BK) & Kepala Sekolah</td>
                </tr>
            </table>

            <p>Apabila setelah diterbitkannya Surat Peringatan ini siswa tidak menunjukkan perbaikan sikap, atau kembali mengulangi pelanggaran serupa, maka sekolah akan mengambil tindakan disiplin yang lebih tegas sesuai dengan aturan yang berlaku di Smart School.</p>
            <p>Demikian surat peringatan ini disampaikan untuk menjadi perhatian dan dilaksanakan dengan penuh tanggung jawab.</p>
        </div>
    @endif

    <!-- TANDA TANGAN -->
    <table class="sign-table">
        <tr>
            <td>
                <div class="sign-title">
                    Mengetahui,<br>
                    Kepala Sekolah
                </div>
                <div class="sign-name">{{ $sekolah->kepala_sekolah ?? 'Kepala Sekolah Smart School' }}</div>
                <div class="sign-nip">NIP. {{ $sekolah->nip ?? '-' }}</div>
            </td>
            <td>
                <div class="sign-title">
                    {{ $sekolah->kota ?? 'Kota' }}, {{ \Carbon\Carbon::parse($panggil->created_at ?? now())->translatedFormat('d F Y') }}<br>
                    Guru BK / Wali Kelas
                </div>
                <div class="sign-name">{{ $panggil->guru->nama_guru ?? 'Guru Bimbingan Konseling' }}</div>
                <div class="sign-nip">NIP. {{ $panggil->guru->nip ?? '-' }}</div>
            </td>
        </tr>
    </table>

</body>
</html>
