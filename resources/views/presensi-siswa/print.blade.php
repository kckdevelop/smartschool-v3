<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Kehadiran Siswa — SmartSchool</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            background: #fff;
            margin: 0;
            padding: 20px;
            font-size: 11px;
            line-height: 1.4;
        }

        /* ─── KOP SURAT (Letterhead) ─── */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 3px double #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .kop-logo {
            width: 70px;
            height: 70px;
            object-fit: contain;
            margin-right: 20px;
        }
        .kop-detail {
            flex: 1;
            text-align: center;
        }
        .kop-sekolah {
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0 0 2px 0;
            letter-spacing: 0.5px;
        }
        .kop-npsn {
            font-size: 10px;
            font-weight: 500;
            color: #64748b;
            margin: 0 0 4px 0;
        }
        .kop-alamat {
            font-size: 9.5px;
            font-style: italic;
            margin: 0;
            color: #475569;
        }

        /* ─── TITLE & METADATA ─── */
        .report-title {
            text-align: center;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            margin: 0 0 15px 0;
            letter-spacing: 0.3px;
        }
        .metadata-grid {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 6px 16px;
            margin-bottom: 15px;
            background: #f8fafc;
            padding: 10px 14px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            max-width: 420px;
        }
        .metadata-label {
            font-weight: 600;
            color: #64748b;
        }
        .metadata-value {
            font-weight: 700;
        }

        /* ─── DATA TABLE ─── */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .report-table th {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            font-weight: 700;
            text-align: center;
            font-size: 10px;
            text-transform: uppercase;
        }
        .report-table td {
            border: 1px solid #cbd5e1;
            padding: 6px 10px;
            font-size: 10px;
        }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: 700; }

        /* ─── SIGNATURE BLOCK ─── */
        .signature-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 40px;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 200px;
        }
        .signature-date {
            margin-bottom: 5px;
        }
        .signature-title {
            margin-bottom: 50px;
        }
        .signature-name {
            font-weight: 700;
            text-decoration: underline;
        }
        .signature-nip {
            font-size: 9px;
            color: #475569;
        }

        /* ─── PRINT SPECIFIC ─── */
        @media print {
            body {
                padding: 0;
                margin: 0;
            }
            .metadata-grid {
                background: none;
                border: none;
                padding: 0;
            }
            @page {
                size: A4 landscape;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>

    {{-- Kop Surat --}}
    @include('partials.kop-surat')

    {{-- Title --}}
    <h2 class="report-title">Laporan Kehadiran Presensi Siswa</h2>

    {{-- Metadata --}}
    <div class="metadata-grid">
        <span class="metadata-label">Kelas</span>
        <span class="metadata-value">: {{ $kelas->tingkat }} {{ $kelas->rombel }}</span>
        
        <span class="metadata-label">Periode Laporan</span>
        <span class="metadata-value">: {{ \Carbon\Carbon::parse($tanggal_dari)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($tanggal_sampai)->translatedFormat('d F Y') }}</span>
        
        <span class="metadata-label">Wali Kelas</span>
        <span class="metadata-value">: {{ $kelas->guru ? $kelas->guru->nama_guru : 'Belum ditetapkan' }}</span>
    </div>

    {{-- Table --}}
    <table class="report-table">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th style="width: 90px;">NIS</th>
                <th>Nama Siswa</th>
                <th style="width: 60px;">Hadir</th>
                <th style="width: 60px;">Sakit</th>
                <th style="width: 60px;">Izin</th>
                <th style="width: 60px;">Alfa</th>
                <th style="width: 90px;">Hari Efektif</th>
                <th style="width: 90px;">% Kehadiran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporanData as $index => $row)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-center font-mono">{{ $row['nis'] }}</td>
                    <td class="font-bold">{{ $row['nama_siswa'] }}</td>
                    <td class="text-center">{{ $row['hadir'] }}</td>
                    <td class="text-center">{{ $row['sakit'] }}</td>
                    <td class="text-center">{{ $row['izin'] }}</td>
                    <td class="text-center">{{ $row['alfa'] }}</td>
                    <td class="text-center">{{ $row['total'] }}</td>
                    <td class="text-center font-bold">{{ $row['persentase'] }}%</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 20px; color: #64748b;">Tidak ada data laporan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Tanda Tangan --}}
    <div class="signature-container">
        <div class="signature-box">
            <div class="signature-date">
                {{ \Carbon\Carbon::today()->translatedFormat('d F Y') }}
            </div>
            <div class="signature-title">
                Wali Kelas
            </div>
            <div class="signature-name">
                {{ $kelas->guru ? $kelas->guru->nama_guru : '-' }}
            </div>
        </div>
    </div>

    <script>
        window.addEventListener('DOMContentLoaded', () => {
            // Auto trigger print dialog
            setTimeout(() => {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>
