<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Poin Kelas {{ $selectedKelasLabel }} — SmartSchool</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #0f172a;
            background: #f1f5f9;
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.5;
        }

        /* ── Pre-print Toolbar ── */
        .preprint-bar {
            position: sticky;
            top: 0;
            z-index: 999;
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);
            color: #fff;
            padding: 12px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.25);
            flex-wrap: wrap;
        }

        .preprint-bar-left {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .preprint-bar-left .bar-title {
            font-size: 0.9rem;
            font-weight: 800;
            letter-spacing: 0.3px;
        }

        .preprint-bar-left .bar-subtitle {
            font-size: 0.72rem;
            color: #a5b4fc;
            font-weight: 500;
        }

        .preprint-bar-center {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,0.08);
            border: 1px solid rgba(255,255,255,0.18);
            border-radius: 12px;
            padding: 8px 16px;
        }

        .preprint-bar-center label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #c7d2fe;
            white-space: nowrap;
        }

        .guru-bk-select {
            background: rgba(255,255,255,0.12);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.3);
            border-radius: 8px;
            padding: 6px 12px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.8rem;
            font-weight: 600;
            min-width: 220px;
            cursor: pointer;
            outline: none;
            transition: background 0.2s, border-color 0.2s;
        }

        .guru-bk-select option {
            background: #1e1b4b;
            color: #fff;
        }

        .guru-bk-select:focus {
            background: rgba(255,255,255,0.2);
            border-color: #818cf8;
        }

        .preprint-bar-right {
            display: flex;
            gap: 10px;
        }

        .btn-do-print {
            background: linear-gradient(135deg, #4ade80, #22c55e);
            color: #fff;
            border: none;
            padding: 9px 20px;
            font-size: 0.82rem;
            font-weight: 800;
            border-radius: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            box-shadow: 0 4px 12px rgba(34,197,94,0.35);
            transition: transform 0.15s, box-shadow 0.15s;
        }

        .btn-do-print:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(34,197,94,0.45);
        }

        .btn-close-tab {
            background: rgba(255,255,255,0.12);
            color: #c7d2fe;
            border: 1px solid rgba(255,255,255,0.25);
            padding: 9px 16px;
            font-size: 0.82rem;
            font-weight: 700;
            border-radius: 10px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 7px;
            transition: background 0.15s;
        }

        .btn-close-tab:hover {
            background: rgba(255,255,255,0.2);
        }

        /* ── Document wrapper ── */
        .doc-wrapper {
            max-width: 860px;
            margin: 28px auto;
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0,0,0,0.10);
            overflow: hidden;
            padding: 36px 40px 40px;
        }

        /* ── Kop Surat ── */
        .kop-area { margin-bottom: 18px; }

        /* ── Report Header ── */
        .report-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 2px solid #1e1b4b;
        }

        .report-title {
            font-size: 13.5px;
            font-weight: 800;
            text-transform: uppercase;
            margin: 0 0 4px 0;
            letter-spacing: 0.6px;
            color: #1e293b;
        }

        .report-subtitle {
            font-size: 10.5px;
            color: #475569;
            margin: 0;
            font-weight: 500;
        }

        /* ── Metadata table ── */
        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 6px 24px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 12px 18px;
            margin-bottom: 22px;
            font-size: 10px;
        }

        .meta-row {
            display: flex;
            gap: 6px;
        }

        .meta-label {
            font-weight: 700;
            color: #475569;
            min-width: 90px;
        }

        .meta-value {
            font-weight: 600;
            color: #0f172a;
        }

        /* ── Data Table ── */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
            font-size: 10px;
        }

        .report-table thead th {
            background: #1e1b4b;
            color: #fff;
            padding: 10px 8px;
            font-weight: 700;
            text-align: center;
            font-size: 9.5px;
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .report-table thead th.left {
            text-align: left;
            padding-left: 12px;
        }

        .report-table tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .report-table tbody tr:hover {
            background: #eff6ff;
        }

        .report-table td {
            border-bottom: 1px solid #e2e8f0;
            padding: 9px 8px;
            font-size: 10px;
            color: #0f172a;
        }

        .report-table td.left {
            padding-left: 12px;
        }

        .text-center { text-align: center; }
        .font-mono   { font-family: 'Courier New', monospace; }
        .font-bold   { font-weight: 700; }

        .badge-score {
            display: inline-block;
            padding: 3px 9px;
            border-radius: 6px;
            font-weight: 800;
            font-size: 9px;
        }

        .badge-danger  { background: #fef2f2; color: #dc2626; }
        .badge-success { background: #f0fdf4; color: #16a34a; }
        .badge-neutral { background: #f1f5f9; color: #475569; }

        /* ── Signature row ── */
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 52px;
            page-break-inside: avoid;
        }

        .signature-block {
            text-align: center;
            width: 220px;
        }

        .sig-date {
            font-size: 9.5px;
            color: #64748b;
            margin-bottom: 3px;
        }

        .sig-title {
            font-size: 10.5px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 68px;
            line-height: 1.4;
        }

        .sig-name {
            font-weight: 800;
            text-decoration: underline;
            font-size: 10.5px;
            color: #0f172a;
        }

        .sig-nip {
            font-size: 9.5px;
            color: #475569;
            margin-top: 2px;
        }

        /* ── Print overrides ── */
        @media print {
            body { background: #fff; }

            .preprint-bar { display: none !important; }

            .doc-wrapper {
                box-shadow: none;
                border-radius: 0;
                margin: 0;
                padding: 0;
                max-width: 100%;
            }

            .report-table tbody tr:nth-child(even) { background: #f8fafc !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .report-table thead th { background: #1e1b4b !important; color: #fff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }

            @page {
                size: A4 portrait;
                margin: 1.5cm;
            }
        }
    </style>
</head>
<body>

    {{-- ── Pre-print Toolbar (hidden on print) ── --}}
    <div class="preprint-bar" id="preprint-bar">
        <div class="preprint-bar-left">
            <div class="bar-title">
                <i class="fa-solid fa-print" style="margin-right:6px; color:#818cf8;"></i>
                Laporan Poin — Kelas {{ $selectedKelasLabel }}
            </div>
            <div class="bar-subtitle">
                Semester {{ $selectedSemester->semester }} &nbsp;|&nbsp; T.A. {{ $selectedSemester->tahunAjaran->tahun }}
                &nbsp;|&nbsp; Pilih Guru BK lalu klik <strong style="color:#a5b4fc;">Cetak Dokumen</strong>
            </div>
        </div>

        <div class="preprint-bar-center">
            <label for="select-guru-bk"><i class="fa-solid fa-user-tie" style="color:#818cf8; margin-right:4px;"></i> Guru BK Penandatangan:</label>
            <select id="select-guru-bk" class="guru-bk-select" onchange="applyGuruBk()">
                @foreach($guruBkList as $guru)
                    <option value="{{ $guru->id_guru }}"
                            data-nama="{{ $guru->nama_guru }}"
                            data-nip="{{ $guru->no_id ?? '—' }}">
                        {{ $guru->nama_guru }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="preprint-bar-right">
            <button class="btn-do-print" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Cetak Dokumen
            </button>
            <button class="btn-close-tab" onclick="window.close()">
                <i class="fa-solid fa-xmark"></i> Tutup
            </button>
        </div>
    </div>

    {{-- ── Document ── --}}
    <div class="doc-wrapper">

        {{-- Kop Surat --}}
        <div class="kop-area">
            @include('partials.kop-surat')
        </div>

        {{-- Report Title --}}
        <div class="report-header">
            <h2 class="report-title">Laporan Akumulasi Poin Pelanggaran &amp; Reward</h2>
            <div class="report-subtitle">Unit Pelayanan Bimbingan Konseling (BK)</div>
        </div>

        {{-- Metadata --}}
        <div class="meta-grid">
            <div class="meta-row">
                <span class="meta-label">Kelas</span>
                <span class="meta-value">: {{ $selectedKelasLabel }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Semester</span>
                <span class="meta-value">: {{ $selectedSemester->semester }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Wali Kelas</span>
                <span class="meta-value">: {{ $waliKelasName }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Tahun Ajaran</span>
                <span class="meta-value">: {{ $selectedSemester->tahunAjaran->tahun }}</span>
            </div>
            <div class="meta-row">
                <span class="meta-label">Tanggal Cetak</span>
                <span class="meta-value">: {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</span>
            </div>
        </div>

        {{-- Student Table --}}
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width:32px;">No</th>
                    <th style="width:72px;">NIS</th>
                    <th class="left">Nama Lengkap Siswa</th>
                    <th style="width:110px;">Poin Pelanggaran</th>
                    <th style="width:110px;">Poin Reward</th>
                    <th style="width:110px;">Total Poin (Net)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reportData as $index => $row)
                    @php
                        $netPoin    = $row->total_net;
                        $badgeClass = $netPoin < 0 ? 'badge-danger' : ($netPoin > 0 ? 'badge-success' : 'badge-neutral');
                    @endphp
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td class="text-center font-mono">{{ $row->nis }}</td>
                        <td class="left font-bold">{{ $row->nama_siswa }}</td>
                        <td class="text-center font-bold" style="color:#dc2626;">{{ $row->total_poin }}</td>
                        <td class="text-center font-bold" style="color:#16a34a;">+{{ $row->total_reward }}</td>
                        <td class="text-center">
                            <span class="badge-score {{ $badgeClass }}">{{ $netPoin > 0 ? '+' : '' }}{{ $netPoin }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding:30px; color:#64748b;">
                            Belum ada data siswa atau poin untuk kelas ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Signatures --}}
        <div class="signature-section">
            {{-- Left: Wali Kelas --}}
            <div class="signature-block">
                <div class="sig-title">Mengetahui,<br>Wali Kelas {{ $selectedKelasLabel }}</div>
                <div class="sig-name">{{ $waliKelasName }}</div>
                <div class="sig-nip">NIP. {{ $waliKelasNip }}</div>
            </div>

            {{-- Right: Guru BK (dynamic) --}}
            <div class="signature-block">
                <div class="sig-date">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div class="sig-title">Guru Bimbingan Konseling (BK)</div>
                <div class="sig-name" id="sig-guru-nama">_____________________</div>
                <div class="sig-nip"  id="sig-guru-nip">NIP. _____________________</div>
            </div>
        </div>

    </div>{{-- end .doc-wrapper --}}

    <script>
        // Apply currently selected Guru BK to signature
        function applyGuruBk() {
            const sel = document.getElementById('select-guru-bk');
            const opt = sel.options[sel.selectedIndex];
            document.getElementById('sig-guru-nama').textContent = opt.dataset.nama || '_____________________';
            document.getElementById('sig-guru-nip').textContent  = 'NIP. ' + (opt.dataset.nip || '_____________________');
        }

        // Run on page load to set the first teacher
        document.addEventListener('DOMContentLoaded', applyGuruBk);
    </script>
</body>
</html>
