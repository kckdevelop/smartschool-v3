<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembimbing PKL — {{ optional($selectedGelombang)->nama_gelombang ?? 'Semua Gelombang' }}</title>
    <style>
        /* =============================================
           GLOBAL RESET & PRINT-SAFE VARS
        ============================================= */
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary:   #1e40af;
            --accent:    #3b82f6;
            --success:   #16a34a;
            --muted:     #4b5563;
            --border:    #374151;
            --bg-head:   #eff6ff;
            --text:      #111827;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 10pt;
            color: var(--text);
            background: #f3f4f6;
            line-height: 1.4;
        }

        /* =============================================
           TOOLBAR (ONLY ON SCREEN, HIDDEN IN PRINT)
        ============================================= */
        #toolbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            z-index: 9999;
            background: #1e293b;
            padding: 10px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 2px 8px rgba(0,0,0,.3);
        }
        #toolbar .toolbar-title {
            flex: 1;
            color: #f1f5f9;
            font-family: 'Segoe UI', sans-serif;
            font-size: 14px;
            font-weight: 600;
        }
        #toolbar .toolbar-title span {
            color: #93c5fd;
            font-weight: 400;
        }
        .btn-toolbar {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 18px;
            border: none;
            border-radius: 7px;
            font-size: 13px;
            font-family: 'Segoe UI', sans-serif;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: opacity .15s;
        }
        .btn-toolbar:hover { opacity: .85; }
        .btn-print  { background: #dc2626; color: #fff; }
        .btn-back   { background: #334155; color: #e2e8f0; }

        /* Filter gelombang inside toolbar */
        #filter-form { display: flex; align-items: center; gap: 8px; }
        #filter-form select {
            padding: 6px 10px;
            border-radius: 6px;
            border: 1px solid #475569;
            background: #0f172a;
            color: #e2e8f0;
            font-size: 13px;
            font-family: 'Segoe UI', sans-serif;
            cursor: pointer;
        }
        #filter-form label {
            color: #94a3b8;
            font-size: 12px;
            font-family: 'Segoe UI', sans-serif;
            white-space: nowrap;
        }

        /* =============================================
           DOCUMENT WRAPPER
        ============================================= */
        #doc-wrapper {
            margin-top: 60px; /* space for toolbar */
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* =============================================
           PAGE (A4 LANDSCAPE)
        ============================================= */
        .page {
            width: 297mm;
            min-height: 210mm;
            background: #fff;
            padding: 12mm 15mm;
            box-shadow: 0 4px 24px rgba(0,0,0,.15);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* =============================================
           KOP SURAT
        ============================================= */
        .kop {
            display: flex;
            align-items: center;
            gap: 18px;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 12px;
        }
        .kop-logo { width: 70px; height: 70px; object-fit: contain; flex-shrink: 0; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .instansi { font-size: 9pt; text-transform: uppercase; font-weight: bold; }
        .kop-text .nama-sekolah { font-size: 14pt; font-weight: bold; text-transform: uppercase; letter-spacing: .5px; }
        .kop-text .alamat { font-size: 8pt; color: #374151; margin-top: 2px; }

        /* =============================================
           JUDUL LAPORAN
        ============================================= */
        .report-title {
            text-align: center;
            margin: 6px 0 12px;
        }
        .report-title h1 {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }
        .report-title .subtitle {
            font-size: 9.5pt;
            color: #111827;
            margin-top: 3px;
        }

        /* =============================================
           MAIN REPORT TABLE
        ============================================= */
        .report-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9pt;
            margin-bottom: 15px;
        }
        .report-table th {
            border: 1px solid #000;
            padding: 6px 8px;
            font-weight: bold;
            text-align: center;
            background-color: #f3f4f6;
            text-transform: uppercase;
        }
        .report-table td {
            border: 1px solid #000;
            padding: 6px 8px;
            vertical-align: top;
        }
        .report-table tr {
            page-break-inside: avoid;
        }
        .text-center { text-align: center; }

        /* List styling inside cells */
        .student-list {
            margin: 0;
            padding-left: 14px;
        }
        .student-list li {
            margin-bottom: 2px;
        }

        /* =============================================
           SIGNATURE FOOTER
        ============================================= */
        .ttd-section {
            margin-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            page-break-inside: avoid;
        }
        .ttd-left { width: 250px; font-size: 9.5pt; }
        .ttd-right { width: 280px; text-align: center; font-size: 9.5pt; }
        .ttd-space { height: 60px; }
        .ttd-name { font-weight: bold; text-decoration: underline; }
        .ttd-nip { font-size: 8.5pt; margin-top: 2px; color: #374151; }

        /* =============================================
           PRINT MEDIA
        ============================================= */
        @media print {
            body { background: #fff; }
            #toolbar { display: none !important; }
            #doc-wrapper { margin-top: 0; padding: 0; }
            .page {
                box-shadow: none;
                margin-bottom: 0;
                padding: 10mm;
                width: 100%;
                height: 100%;
            }
        }

        @page {
            size: A4 landscape;
            margin: 0;
        }
    </style>
</head>
<body>

{{-- TOOLBAR (Layar Saja) --}}
<div id="toolbar">
    <div class="toolbar-title">
        📋 Laporan Pembimbing PKL (Landscape)
        <span>— {{ optional($selectedGelombang)->nama_gelombang ?? 'Semua Gelombang' }}</span>
    </div>

    {{-- Filter Gelombang --}}
    <form id="filter-form" method="GET" action="{{ route('pkl.pembimbing.cetak') }}">
        <label for="sel-gelombang">Gelombang:</label>
        <select id="sel-gelombang" name="id_gelombang" onchange="this.form.submit()">
            <option value="">Semua Gelombang</option>
            @foreach($gelombangList as $g)
            <option value="{{ $g->id_gelombang }}" {{ $selectedId == $g->id_gelombang ? 'selected' : '' }}>
                {{ $g->nama_gelombang }}
            </option>
            @endforeach
        </select>
    </form>

    <button class="btn-toolbar btn-print" onclick="window.print()">
        🖨️ Cetak / Save PDF
    </button>
    <a class="btn-toolbar btn-back"
       href="{{ route('pkl.pembimbing.index', $selectedId ? ['id_gelombang' => $selectedId] : []) }}">
        ← Kembali
    </a>
</div>

{{-- DOKUMEN --}}
<div id="doc-wrapper">
<div class="page">
    <div>
        {{-- KOP --}}
        @include('partials.kop-surat')

        {{-- JUDUL --}}
        <div class="report-title">
            <h1>Laporan Guru Pembimbing Praktik Kerja Lapangan (PKL)</h1>
            <div class="subtitle">
                Gelombang: <strong>{{ optional($selectedGelombang)->nama_gelombang ?? 'Semua Gelombang' }}</strong>
                @if($selectedGelombang)
                &nbsp;|&nbsp; Periode:
                <strong>{{ \Carbon\Carbon::parse($selectedGelombang->tanggal_mulai)->translatedFormat('d F Y') }}</strong>
                s/d
                <strong>{{ \Carbon\Carbon::parse($selectedGelombang->tanggal_selesai)->translatedFormat('d F Y') }}</strong>
                @endif
            </div>
        </div>

        {{-- TABEL LAPORAN --}}
        <table class="report-table">
            <thead>
                <tr>
                    <th style="width: 4%; text-align: center;">No</th>
                    <th style="width: 20%;">Nama Guru</th>
                    <th style="width: 18%;">DUDI</th>
                    <th style="width: 22%;">Alamat DUDI</th>
                    <th style="width: 14%;">Pimpinan DUDI</th>
                    <th style="width: 12%;">No Telp DUDI</th>
                    <th style="width: 10%;">Daftar Siswa</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pembimbings as $idx => $pb)
                @php
                    $guru = $pb->guru;
                    $dudi = $pb->dudi;
                    // Filter siswa penempatan berdasarkan gelombang dan DUDI
                    $siswaList = $pb->penempatan;
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td style="font-weight: bold;">{{ $guru?->nama_guru ?? '(Belum diset)' }}</td>
                    <td style="font-weight: 600;">{{ $dudi?->nama_dudi ?? '-' }}</td>
                    <td>
                        {{ $dudi?->alamat ?? '-' }}
                        @if($dudi?->kota)
                            <br><span style="font-size: 8pt; font-weight: bold; color: #4b5563;">Kota: {{ $dudi->kota }}</span>
                        @endif
                    </td>
                    <td>
                        {{ $dudi?->nama_pic ?? '-' }}
                        @if($dudi?->jabatan_pic)
                            <br><span style="font-size: 8pt; color: #6b7280; font-style: italic;">({{ $dudi->jabatan_pic }})</span>
                        @endif
                    </td>
                    <td>
                        @if($dudi?->no_hp_pic)
                            {{ $dudi->no_hp_pic }} (PIC)
                        @endif
                        @if($dudi?->no_telepon)
                            @if($dudi?->no_hp_pic)<br>@endif
                            {{ $dudi->no_telepon }} (Kantor)
                        @endif
                        @if(!$dudi?->no_hp_pic && !$dudi?->no_telepon)
                            -
                        @endif
                    </td>
                    <td>
                        @if($siswaList && $siswaList->count() > 0)
                        <ol class="student-list">
                            @foreach($siswaList as $s)
                            <li>{{ optional($s->siswa)->nama_siswa ?? $s->nis }} <span style="font-size: 8pt; color: #4b5563;">({{ optional(optional($s->siswa)->kelas)->nama_kelas ?? '-' }})</span></li>
                            @endforeach
                        </ol>
                        @else
                        <span style="font-style: italic; color: #9ca3af;">Belum ada siswa</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 20px; font-style: italic; color: #6b7280;">
                        Belum ada data pembimbing untuk gelombang ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- TANDA TANGAN --}}
    @if($pembimbings->count() > 0)
    <div class="ttd-section">
        <div class="ttd-left">
            <div>Mengetahui,</div>
            <div>Koordinator PKL</div>
            <div class="ttd-space"></div>
            <div>( ______________________ )</div>
        </div>
        <div class="ttd-right">
            <div>{{ $sekolah?->kota ?? 'Wonosobo' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div>Kepala Sekolah,</div>
            <div class="ttd-space"></div>
            <div class="ttd-name">{{ $sekolah?->kepala_sekolah ?? '( ______________________ )' }}</div>
            <div class="ttd-nip">NIP. {{ $sekolah?->nip ?? '-' }}</div>
        </div>
    </div>
    @endif

</div>
</div>

</body>
</html>
