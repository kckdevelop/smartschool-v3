<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekap Presensi Siswa — SmartSchool</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            color: #0f172a;
            background: #fff;
            margin: 0;
            padding: 0;
            font-size: 8px; /* Compact base size to fit landscape */
            line-height: 1.3;
        }

        /* ─── FLOATING PREVIEW BAR ─── */
        .no-print-bar {
            background-color: #0f172a;
            color: #fff;
            padding: 10px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            font-weight: 500;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .bar-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .bar-title {
            font-weight: 700;
            font-size: 13px;
        }
        .bar-desc {
            font-size: 11px;
            color: #94a3b8;
        }
        .bar-actions {
            display: flex;
            gap: 8px;
        }
        .btn-action {
            padding: 6px 14px;
            font-size: 11px;
            font-weight: 700;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            transition: all 0.15s ease;
        }
        .btn-print {
            background-color: #ef4444;
            color: #fff;
        }
        .btn-print:hover {
            background-color: #dc2626;
        }
        .btn-close {
            background-color: #334155;
            color: #cbd5e1;
        }
        .btn-close:hover {
            background-color: #475569;
            color: #fff;
        }

        /* ─── PRINT WRAPPER ─── */
        .print-container {
            padding: 24px 30px;
        }

        /* ─── KOP SURAT (Letterhead) ─── */
        .kop-surat {
            display: flex;
            align-items: center;
            border-bottom: 2.5px double #000;
            padding-bottom: 12px;
            margin-bottom: 15px;
        }
        .kop-logo {
            width: 54px;
            height: 54px;
            object-fit: contain;
            margin-right: 18px;
        }
        .kop-detail {
            flex: 1;
            text-align: center;
        }
        .kop-sekolah {
            font-size: 15px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0 0 2px 0;
            letter-spacing: 0.5px;
        }
        .kop-npsn {
            font-size: 9px;
            font-weight: 500;
            color: #475569;
            margin: 0 0 4px 0;
        }
        .kop-alamat {
            font-size: 9px;
            font-style: italic;
            margin: 0;
            color: #334155;
        }

        /* ─── TITLE & METADATA ─── */
        .report-title {
            text-align: center;
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0 0 10px 0;
            letter-spacing: 0.3px;
        }
        .metadata-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 8.5px;
            font-weight: 600;
            background: #f8fafc;
            padding: 6px 12px;
            border-radius: 6px;
            border: 1px solid #e2e8f0;
        }
        .metadata-item span {
            font-weight: 700;
        }

        /* ─── GRID TABLE ─── */
        .grid-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .grid-table th {
            background-color: #f8fafc;
            border: 1px solid #94a3b8;
            padding: 4px 2px;
            font-weight: 700;
            text-align: center;
            font-size: 7.5px;
            text-transform: uppercase;
        }
        .grid-table td {
            border: 1px solid #94a3b8;
            padding: 3px 2px;
            font-size: 7.5px;
        }
        .col-day {
            text-align: center;
            width: 18px;
            font-weight: 700;
        }
        .weekend-col {
            background-color: #ef4444 !important;
            color: #ffffff !important;
        }
        .weekend-header {
            background-color: #dc2626 !important;
            color: #ffffff !important;
        }
        .text-center { text-align: center; }
        .font-mono { font-family: monospace; }
        .font-bold { font-weight: 700; }
        
        /* Cell status initial characters */
        .char-h { color: #10b981; } /* Green */
        .char-s { color: #f59e0b; } /* Orange */
        .char-i { color: #3b82f6; } /* Blue */
        .char-a { color: #ef4444; } /* Red */
        .char-none { color: #cbd5e1; font-weight: 400; }

        /* Summary column */
        .col-summary-head {
            background-color: #f1f5f9;
            font-weight: 800;
            font-size: 7.5px;
            width: 16px;
        }
        .col-summary-val {
            text-align: center;
            font-weight: 700;
        }
        .col-summary-pct {
            background-color: #f8fafc;
            font-weight: 800;
            text-align: center;
            width: 28px;
        }

        /* ─── FOOTER LEGENDA ─── */
        .footer-info {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-top: 10px;
            font-size: 7.5px;
            font-weight: 600;
            page-break-inside: avoid;
        }
        .legenda-list {
            display: flex;
            gap: 12px;
        }
        .legenda-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .legenda-symbol {
            font-weight: 800;
            border: 1px solid #cbd5e1;
            padding: 1px 4px;
            border-radius: 3px;
            background: #f8fafc;
        }

        /* ─── SIGNATURE BLOCK ─── */
        .signature-container {
            display: flex;
            justify-content: flex-end;
            margin-top: 25px;
            page-break-inside: avoid;
        }
        .signature-box {
            text-align: center;
            width: 180px;
        }
        .signature-date {
            margin-bottom: 4px;
        }
        .signature-title {
            margin-bottom: 45px;
        }
        .signature-name {
            font-weight: 700;
            text-decoration: underline;
        }
        .signature-nip {
            font-size: 7.5px;
            color: #475569;
        }

        /* ─── PRINT RULES ─── */
        @media print {
            .no-print-bar {
                display: none !important;
            }
            body {
                padding: 0;
                margin: 0;
            }
            .print-container {
                padding: 0;
            }
            .metadata-row {
                background: none;
                border: 1px solid #94a3b8;
            }
            @page {
                size: A4 landscape;
                margin: 0.8cm;
            }
        }
    </style>
</head>
<body>

    {{-- Floating Action Bar --}}
    <div class="no-print-bar">
        <div class="bar-left">
            <i class="fa-solid fa-file-pdf" style="color: #ef4444; font-size: 16px;"></i>
            <div>
                <span class="bar-title">Pratinjau Rekap Presensi Bulanan</span>
                <span class="bar-desc"> (A4 Landscape)</span>
            </div>
        </div>
        <div class="bar-actions">
            <button onclick="window.print()" class="btn-action btn-print">
                <i class="fa-solid fa-print"></i> Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" class="btn-action btn-close">
                <i class="fa-solid fa-xmark"></i> Tutup
            </button>
        </div>
    </div>

    {{-- Print Container --}}
    <div class="print-container">
        {{-- Kop Surat --}}
        @include('partials.kop-surat')

        {{-- Title --}}
        <h2 class="report-title">Rekapitulasi Kehadiran Bulanan Siswa</h2>

        {{-- Metadata --}}
        <div class="metadata-row">
            <div class="metadata-item">
                Kelas: <span>{{ $kelas->tingkat }} {{ $kelas->rombel }}</span>
            </div>
            <div class="metadata-item">
                Bulan: <span>{{ \Carbon\Carbon::parse($bulan.'-01')->translatedFormat('F Y') }}</span>
            </div>
            <div class="metadata-item">
                Wali Kelas: <span>{{ $kelas->guru ? $kelas->guru->nama_guru : 'Belum ditetapkan' }}</span>
            </div>
        </div>

        {{-- Grid Table --}}
        <table class="grid-table">
            <thead>
                <tr>
                    <th style="width: 25px; vertical-align: middle;" rowspan="2">No</th>
                    <th style="width: 65px; vertical-align: middle;" rowspan="2">NIS</th>
                    <th style="vertical-align: middle;" rowspan="2">Nama Siswa</th>
                    <th colspan="{{ $daysInMonth }}" style="background-color: #f8fafc; font-size: 7px; padding: 2px;">Tanggal</th>
                    <th colspan="5" style="background-color: #f1f5f9; border-left: 1.5px solid #64748b; padding: 2px;">Rekap</th>
                </tr>
                <tr>
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        @php
                            $isWeekend = \Carbon\Carbon::create($year, $month, $d)->isWeekend();
                        @endphp
                        <th class="col-day {{ $isWeekend ? 'weekend-header weekend-col' : '' }}">{{ $d }}</th>
                    @endfor
                    <th class="col-summary-head" style="color: #10b981; border-left: 1.5px solid #64748b;">H</th>
                    <th class="col-summary-head" style="color: #f59e0b;">S</th>
                    <th class="col-summary-head" style="color: #3b82f6;">I</th>
                    <th class="col-summary-head" style="color: #ef4444;">A</th>
                    <th class="col-summary-head" style="background-color: #cbd5e1; color: #0f766e; width: 22px;">%</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rekapData as $index => $row)
                    <tr>
                        <td class="text-center font-bold">{{ $index + 1 }}</td>
                        <td class="text-center font-mono">{{ $row['siswa']->nis }}</td>
                        <td class="font-bold">{{ $row['siswa']->nama_siswa }}</td>
                        
                        {{-- Harian --}}
                        @for($d = 1; $d <= $daysInMonth; $d++)
                            @php
                                $isWeekend = \Carbon\Carbon::create($year, $month, $d)->isWeekend();
                                $val = $row['grid'][$d];
                            @endphp
                            <td class="col-day {{ $isWeekend ? 'weekend-col' : '' }}">
                            @if($val === 'H')
                                    <span class="char-h">H</span>
                                @elseif($val === 'S')
                                    <span class="char-s">S</span>
                                @elseif($val === 'I')
                                    <span class="char-i">I</span>
                                @elseif($val === 'A')
                                    <span class="char-a">A</span>
                                @elseif($val === 'W')
                                    {{-- Weekend: tampilkan sel kosong --}}
                                @else
                                    <span class="char-none">-</span>
                                @endif
                            </td>
                        @endfor
                        
                        {{-- Rekap --}}
                        <td class="col-summary-val" style="color: #10b981; border-left: 1.5px solid #64748b;">{{ $row['hadir'] }}</td>
                        <td class="col-summary-val" style="color: #f59e0b;">{{ $row['sakit'] }}</td>
                        <td class="col-summary-val" style="color: #3b82f6;">{{ $row['izin'] }}</td>
                        <td class="col-summary-val" style="color: #ef4444;">{{ $row['alfa'] }}</td>
                        <td class="col-summary-pct font-bold" style="color: #0d9488;">{{ $row['persentase'] }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ $daysInMonth + 8 }}" class="text-center" style="padding: 15px; color: #64748b;">Tidak ada data rekap.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Footer Legenda & Libur --}}
        <div class="footer-info">
            <div class="legenda-list">
                <div class="legenda-item">
                    <span class="legenda-symbol" style="color: #10b981;">H</span> Hadir
                </div>
                <div class="legenda-item">
                    <span class="legenda-symbol" style="color: #f59e0b;">S</span> Sakit
                </div>
                <div class="legenda-item">
                    <span class="legenda-symbol" style="color: #3b82f6;">I</span> Izin
                </div>
                <div class="legenda-item">
                    <span class="legenda-symbol" style="color: #ef4444;">A</span> Alfa (Tanpa Keterangan)
                </div>
                <div class="legenda-item">
                    <span class="legenda-symbol" style="color: #cbd5e1;">-</span> Libur / Belum Diisi
                </div>
            </div>
            <div style="font-weight: 700; color: #475569;">
                * Kolom berwarna kelabu menunjukkan akhir pekan (Sabtu & Minggu)
            </div>
        </div>

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
                    {{ $waliKelas ?? '-' }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Trigger dialog print browser setelah 500ms
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
