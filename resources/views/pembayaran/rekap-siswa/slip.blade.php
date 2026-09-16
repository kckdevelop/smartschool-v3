<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slip Tagihan VA - {{ $tagihan->nomor_va }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
            color: #1e293b;
        }
        .slip-container {
            max-width: 650px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            border: 1px solid #e2e8f0;
        }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header-logo img {
            max-height: 55px;
        }
        .header-info h2 {
            margin: 0;
            font-size: 18px;
            color: #1e293b;
            text-transform: uppercase;
        }
        .header-info p {
            margin: 4px 0 0 0;
            font-size: 12px;
            color: #64748b;
        }
        .title-badge {
            text-align: center;
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .title-badge h3 {
            margin: 0;
            font-size: 16px;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .va-card {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            color: #ffffff;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 8px 20px rgba(49, 46, 129, 0.25);
            position: relative;
            overflow: hidden;
        }
        .va-card::after {
            content: 'BPD DIY VA';
            position: absolute;
            right: -20px;
            bottom: -15px;
            font-size: 60px;
            font-weight: 900;
            color: rgba(255, 255, 255, 0.05);
            pointer-events: none;
        }
        .va-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #a5b4fc;
        }
        .va-number {
            font-family: 'Courier New', Courier, monospace;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: 2px;
            margin: 10px 0;
            color: #ffffff;
        }
        .va-meta {
            display: flex;
            justify-content: space-between;
            font-size: 13px;
            color: #cbd5e1;
            margin-top: 15px;
            border-top: 1px solid rgba(255,255,255,0.15);
            padding-top: 12px;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        .info-table td {
            padding: 10px 12px;
            font-size: 14px;
            border-bottom: 1px solid #f1f5f9;
        }
        .info-table td.label {
            width: 35%;
            color: #64748b;
            font-weight: 600;
        }
        .info-table td.value {
            color: #0f172a;
            font-weight: 700;
        }
        .footer-note {
            font-size: 12px;
            color: #64748b;
            line-height: 1.6;
            background: #f8fafc;
            padding: 14px;
            border-radius: 8px;
            border-left: 4px solid #4f46e5;
        }
        .btn-print {
            display: block;
            width: 100%;
            padding: 12px;
            background: #4f46e5;
            color: #ffffff;
            text-align: center;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            margin-top: 20px;
        }
        @media print {
            body {
                background: #ffffff;
                padding: 0;
            }
            .slip-container {
                box-shadow: none;
                border: none;
                padding: 0;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="slip-container">
    <div class="header">
        <div class="header-info">
            <h2>{{ $sekolah->nama_sekolah ?? 'SMARTSCHOOL' }}</h2>
            <p>{{ $sekolah->alamat_sekolah ?? 'Sistem Informasi Sekolah Digital' }}</p>
        </div>
        <div class="header-logo">
            @if(isset($sekolah) && $sekolah->logo)
                <img src="{{ asset('storage/' . $sekolah->logo) }}" alt="Logo">
            @endif
        </div>
    </div>

    <div class="title-badge">
        <h3>SLIP TAGIHAN VIRTUAL ACCOUNT BPD DIY</h3>
    </div>

    <div class="va-card">
        <div class="va-label">Nomor Virtual Account (BPD DIY)</div>
        <div class="va-number">{{ $tagihan->nomor_va }}</div>
        <div class="va-meta">
            <div>ID Institusi: {{ $setting->id_institusi ?? '1023' }}</div>
            <div>Status: <strong>{{ strtoupper($tagihan->status) }}</strong></div>
        </div>
    </div>

    <table class="info-table">
        <tr>
            <td class="label">Nama Siswa</td>
            <td class="value">{{ $tagihan->siswa->nama_siswa ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">NIS / Kelas</td>
            <td class="value">{{ $tagihan->nis }} / {{ $tagihan->kelas->nama_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Tagihan</td>
            <td class="value">{{ $tagihan->nama_tagihan }}</td>
        </tr>
        <tr>
            <td class="label">Jenis & Kode Tagihan</td>
            <td class="value">
                {{ strtoupper($tagihan->jenis_tagihan) }} (Kode {{ $tagihan->kode_tagihan }})
            </td>
        </tr>
        <tr>
            <td class="label">Nominal Tagihan</td>
            <td class="value" style="font-size: 18px; color: #4f46e5;">
                Rp {{ number_format($tagihan->nominal, 0, ',', '.') }}
            </td>
        </tr>
        <tr>
            <td class="label">Tanggal Tagihan</td>
            <td class="value">{{ \Carbon\Carbon::parse($tagihan->tanggal_tagihan)->translatedFormat('d F Y') }}</td>
        </tr>
        @if($tagihan->tanggal_jatuh_tempo)
        <tr>
            <td class="label">Jatuh Tempo</td>
            <td class="value" style="color: #dc2626;">{{ \Carbon\Carbon::parse($tagihan->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}</td>
        </tr>
        @endif
        @if($tagihan->tanggal_bayar)
        <tr>
            <td class="label">Tanggal Bayar</td>
            <td class="value" style="color: #16a34a;">{{ \Carbon\Carbon::parse($tagihan->tanggal_bayar)->translatedFormat('d F Y H:i') }}</td>
        </tr>
        @endif
    </table>

    <div class="footer-note">
        <strong>Petunjuk Pembayaran:</strong><br>
        1. Pembayaran dapat dilakukan melalui teller Bank BPD DIY, ATM BPD DIY, Mobile Banking BPD DIY (QUICK), atau Transfer Bank Asosiasi dengan memasukkan nomor Virtual Account di atas.<br>
        2. Pastikan nominal dan nama penerima sesuai sebelum melakukan konfirmasi pembayaran.
    </div>

    <button class="btn-print" onclick="window.print()">Cetak Slip Pembayaran</button>
</div>

</body>
</html>
