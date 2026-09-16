<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Rekap Tagihan Per Kelas</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Times New Roman', Times, serif; font-size: 11pt; background: #f0f0f0; color: #000; }

        /* Control bar */
        .no-print {
            background: #1e293b; color: #fff;
            padding: 10px 18px;
            display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
        }
        .no-print h2 { font-size: 14px; font-weight: 700; flex: 1; }
        .btn-act {
            padding: 7px 16px; border-radius: 6px; border: none; cursor: pointer;
            font-size: 13px; font-weight: 700; text-decoration: none;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .btn-print { background: #4f46e5; color: #fff; }
        .btn-back  { background: #475569; color: #fff; }
        .no-print select { padding: 6px 10px; border-radius: 5px; border: 1px solid #475569; background: #334155; color: #fff; font-size: 12px; }
        .btn-filter { background: #4f46e5; color: #fff; padding: 7px 14px; border-radius: 5px; border: none; font-weight: 700; cursor: pointer; font-size: 12px; }

        /* Page */
        .page { width: 297mm; min-height: 210mm; background: #fff; margin: 16px auto 40px; padding: 12mm 13mm 15mm 13mm; box-shadow: 0 2px 16px rgba(0,0,0,0.12); }

        /* KOP */
        .kop-wrapper { border-bottom: 3px double #000; padding-bottom: 7px; margin-bottom: 7px; }
        .kop-has-image img { width: 100%; max-height: 88px; object-fit: contain; }
        .kop-manual { display: flex; align-items: center; gap: 12px; }
        .kop-logo img { height: 68px; width: 68px; object-fit: contain; }
        .kop-text { flex: 1; text-align: center; }
        .kop-text .nama-sekolah { font-size: 15pt; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; }
        .kop-text .kop-detail { font-size: 9pt; color: #334155; margin-top: 2px; line-height: 1.6; }

        /* Judul */
        .judul-cetak { text-align: center; margin: 7px 0 9px; }
        .judul-cetak h2 { font-size: 12.5pt; font-weight: 900; text-transform: uppercase; }
        .judul-cetak .sub { font-size: 9.5pt; color: #334155; margin-top: 2px; }

        /* Info strip */
        .info-strip { display: flex; justify-content: space-between; font-size: 9pt; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px; padding: 4px 9px; margin-bottom: 8px; }

        /* Summary */
        .summary-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 7px; margin-bottom: 9px; }
        .sum-box { border: 1px solid #e2e8f0; border-radius: 4px; padding: 6px 9px; text-align: center; }
        .sum-box .s-label { font-size: 7.5pt; color: #64748b; font-weight: 700; text-transform: uppercase; }
        .sum-box .s-val   { font-size: 10.5pt; font-weight: 900; margin-top: 2px; }
        .sum-box.tagihan .s-val { color: #1d4ed8; }
        .sum-box.bayar   .s-val { color: #15803d; }
        .sum-box.sisa    .s-val { color: #dc2626; }

        /* Table */
        .rekap-table { width: 100%; border-collapse: collapse; font-size: 8.5pt; margin-bottom: 12px; }
        .rekap-table thead tr { background: #1e293b; color: #fff; }
        .rekap-table th { padding: 5px 6px; text-align: center; font-weight: 700; white-space: nowrap; border: 1px solid #334155; }
        .rekap-table td { padding: 4px 6px; border: 1px solid #cbd5e1; vertical-align: middle; }
        .rekap-table tbody tr:nth-child(even) { background: #f8fafc; }
        .text-right  { text-align: right; }
        .text-center { text-align: center; }
        .text-red    { color: #dc2626; font-weight: 700; }
        .text-green  { color: #15803d; font-weight: 700; }
        .text-muted  { color: #94a3b8; font-style: italic; }
        .font-mono   { font-family: 'Courier New', monospace; }
        .badge-lunas    { background:#d1fae5; color:#065f46; padding:1px 4px; border-radius:3px; font-size:7.5pt; font-weight:700; }
        .badge-belum    { background:#fee2e2; color:#991b1b; padding:1px 4px; border-radius:3px; font-size:7.5pt; }
        .badge-sebagian { background:#fef3c7; color:#92400e; padding:1px 4px; border-radius:3px; font-size:7.5pt; }

        /* TTD */
        .ttd-section { display: flex; justify-content: flex-end; margin-top: 14px; }
        .ttd-box { text-align: center; width: 195px; }
        .ttd-box .ttd-label { font-size: 9pt; margin-bottom: 46px; line-height: 1.5; }
        .ttd-box .ttd-name  { font-weight: 900; font-size: 9.5pt; border-top: 1px solid #000; padding-top: 3px; }
        .ttd-box .ttd-nip   { font-size: 8pt; color: #475569; }

        @media print {
            body { background: #fff; }
            .no-print { display: none !important; }
            .page { margin: 0; padding: 9mm 11mm 12mm 11mm; width: 100%; box-shadow: none; }
            @page { size: A4 landscape; margin: 0; }
        }
    </style>
</head>
<body>

{{-- Control Bar --}}
<div class="no-print">
    <h2>🖨 Cetak Rekap Tagihan Per Kelas</h2>
    <form method="GET" action="{{ route('pembayaran.rekap-siswa.cetak-kelas') }}"
          style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
        <label style="font-size:12px;">Kelas:</label>
        <select name="id_kelas" style="min-width:150px;">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelasList as $k)
                <option value="{{ $k->id_kelas }}" {{ $selectedKelas == $k->id_kelas ? 'selected' : '' }}>
                    {{ $k->nama_kelas }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn-filter">Tampilkan</button>
    </form>
    <button class="btn-act btn-print" onclick="window.print()">🖨 Cetak / Simpan PDF</button>
    <a href="{{ route('pembayaran.rekap-siswa.index') }}" class="btn-act btn-back">← Kembali</a>
</div>

{{-- HALAMAN CETAK --}}
<div class="page">

    {{-- KOP SEKOLAH --}}
    <div class="kop-wrapper">
        @if(isset($sekolah) && $sekolah->kop)
            <div class="kop-has-image">
                <img src="{{ asset('storage/' . $sekolah->kop) }}" alt="Kop Sekolah">
            </div>
        @else
            <div class="kop-manual">
                @if(isset($sekolah) && $sekolah->logo)
                    <div class="kop-logo">
                        <img src="{{ asset('storage/' . $sekolah->logo) }}" alt="Logo">
                    </div>
                @endif
                <div class="kop-text">
                    <div class="nama-sekolah">{{ $sekolah->nama_sekolah ?? 'NAMA SEKOLAH' }}</div>
                    <div class="kop-detail">
                        {{ $sekolah->alamat_sekolah ?? '' }}
                        @if(isset($sekolah) && $sekolah->kota) &bull; {{ $sekolah->kota }} @endif
                        @if(isset($sekolah) && $sekolah->npsn) &bull; NPSN: {{ $sekolah->npsn }} @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

    {{-- JUDUL --}}
    <div class="judul-cetak">
        <h2>Rekap Tagihan Pembayaran Per Siswa</h2>
        <div class="sub">
            Kelas: <strong>{{ $kelasObj->nama_kelas ?? 'Semua Kelas' }}</strong>
            &nbsp;&bull;&nbsp;
            Tahun Ajaran: <strong>{{ $tahunAjaranObj->tahun ?? (date('Y').'/'. (date('Y')+1)) }}</strong>
        </div>
    </div>

    {{-- INFO STRIP --}}
    <div class="info-strip">
        <span>Dicetak: {{ auth()->user()->name ?? 'Admin' }}</span>
        <span>{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</span>
        <span>Jumlah Siswa: <strong>{{ count($rekapSiswa) }}</strong></span>
    </div>

    {{-- SUMMARY --}}
    <div class="summary-grid">
        <div class="sum-box tagihan">
            <div class="s-label">Total Tagihan</div>
            <div class="s-val">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
        </div>
        <div class="sum-box bayar">
            <div class="s-label">Total Terbayar</div>
            <div class="s-val">Rp {{ number_format($grandBayar, 0, ',', '.') }}</div>
        </div>
        <div class="sum-box sisa">
            <div class="s-label">Sisa Belum Bayar</div>
            <div class="s-val">Rp {{ number_format($grandSisa, 0, ',', '.') }}</div>
        </div>
    </div>

    {{-- TABEL --}}
    <table class="rekap-table">
        <thead>
            <tr>
                <th style="width:24px;">#</th>
                <th style="text-align:left; min-width:140px;">Nama Siswa</th>
                <th>NIS</th>
                <th style="text-align:right;">SPP Tag.</th>
                <th style="text-align:right;">SPP Bayar</th>
                <th style="text-align:right;">Non SPP Tag.</th>
                <th style="text-align:right;">Non SPP Bayar</th>
                <th style="text-align:right;">Tunggakan Tag.</th>
                <th style="text-align:right;">Tunggakan Bayar</th>
                <th style="text-align:right;">Total Tag.</th>
                <th style="text-align:right;">Total Bayar</th>
                <th style="text-align:right;">Sisa</th>
            </tr>
        </thead>
        <tbody>
            @forelse($rekapSiswa as $idx => $row)
                @php
                    $s = $row['siswa'];
                @endphp
                <tr>
                    <td class="text-center">{{ $idx + 1 }}</td>
                    <td><strong>{{ strtoupper($s->nama_siswa) }}</strong></td>
                    <td class="text-center font-mono">{{ $s->nis }}</td>

                    <td class="text-right">@if($row['spp_nominal'] > 0) Rp {{ number_format($row['spp_nominal'], 0, ',', '.') }} @else <span class="text-muted">—</span> @endif</td>
                    <td class="text-right {{ ($row['spp_bayar'] >= $row['spp_nominal'] && $row['spp_nominal'] > 0) ? 'text-green' : '' }}">
                        @if($row['spp_nominal'] > 0) Rp {{ number_format($row['spp_bayar'], 0, ',', '.') }} @else <span class="text-muted">—</span> @endif
                    </td>

                    <td class="text-right">@if($row['non_spp_nominal'] > 0) Rp {{ number_format($row['non_spp_nominal'], 0, ',', '.') }} @else <span class="text-muted">—</span> @endif</td>
                    <td class="text-right {{ ($row['non_spp_bayar'] >= $row['non_spp_nominal'] && $row['non_spp_nominal'] > 0) ? 'text-green' : '' }}">
                        @if($row['non_spp_nominal'] > 0) Rp {{ number_format($row['non_spp_bayar'], 0, ',', '.') }} @else <span class="text-muted">—</span> @endif
                    </td>

                    <td class="text-right">@if($row['tunggakan_nominal'] > 0) Rp {{ number_format($row['tunggakan_nominal'], 0, ',', '.') }} @else <span class="text-muted">—</span> @endif</td>
                    <td class="text-right {{ ($row['tunggakan_bayar'] >= $row['tunggakan_nominal'] && $row['tunggakan_nominal'] > 0) ? 'text-green' : '' }}">
                        @if($row['tunggakan_nominal'] > 0) Rp {{ number_format($row['tunggakan_bayar'], 0, ',', '.') }} @else <span class="text-muted">—</span> @endif
                    </td>

                    <td class="text-right" style="font-weight:800;">
                        @if($row['total_nominal'] > 0) Rp {{ number_format($row['total_nominal'], 0, ',', '.') }} @else <span class="text-muted">Rp 0</span> @endif
                    </td>
                    <td class="text-right text-green" style="font-weight:800;">
                        Rp {{ number_format($row['total_bayar'], 0, ',', '.') }}
                    </td>
                    <td class="text-right {{ $row['sisa_pembayaran'] > 0 ? 'text-red' : 'text-green' }}" style="font-weight:800;">
                        Rp {{ number_format($row['sisa_pembayaran'], 0, ',', '.') }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="14" class="text-center text-muted" style="padding:18px;">
                        Tidak ada data siswa.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(count($rekapSiswa) > 0)
        <tfoot>
            <tr style="background:#1e293b; color:#fff; font-weight:900;">
                <td colspan="3" class="text-center" style="padding:6px; border:1px solid #334155;">
                    TOTAL &mdash; {{ count($rekapSiswa) }} Siswa
                </td>
                <td colspan="6" style="border:1px solid #334155;"></td>
                <td class="text-right" style="padding:6px; border:1px solid #334155;">
                    Rp {{ number_format($grandTotal, 0, ',', '.') }}
                </td>
                <td class="text-right" style="padding:6px; border:1px solid #334155; color:#86efac;">
                    Rp {{ number_format($grandBayar, 0, ',', '.') }}
                </td>
                <td class="text-right" style="padding:6px; border:1px solid #334155; color:#fca5a5;">
                    Rp {{ number_format($grandSisa, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
    {{-- KETERANGAN SUMBER DATA --}}
    <div style="margin-top: 8px; padding: 6px 10px; border: 1px solid #e2e8f0; border-radius: 4px;
                background: #f8fafc; font-size: 8pt; color: #475569; display: flex; justify-content: space-between; align-items: center;">
        <span>
            <strong>Sumber Data:</strong> Ditarik dari Server Portal BPD DIY
            &nbsp;&bull;&nbsp;
            @if($lastSyncAt)
                Sinkronisasi terakhir:
                <strong>{{ \Carbon\Carbon::parse($lastSyncAt)->translatedFormat('d F Y') }}</strong>
                pukul
                <strong>{{ \Carbon\Carbon::parse($lastSyncAt)->format('H:i') }} WIB</strong>
            @else
                <em>Belum ada data yang disinkronkan dari BPD DIY</em>
            @endif
        </span>
        <span style="color: #94a3b8;">* Data diambil otomatis dari portal va.bpddiy.co.id</span>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="ttd-section">
        <div class="ttd-box">
            <div class="ttd-label">
                {{ $sekolah->kota ?? 'Yogyakarta' }},
                {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Kepala Sekolah,
            </div>
            @if(isset($sekolah) && $sekolah->ttd_kepala_sekolah)
                <img src="{{ asset('storage/' . $sekolah->ttd_kepala_sekolah) }}"
                     alt="TTD" style="height:46px; display:block; margin:0 auto 3px;">
            @endif
            <div class="ttd-name">{{ $sekolah->kepala_sekolah ?? '...........................' }}</div>
            @if(isset($sekolah) && $sekolah->nip)
                <div class="ttd-nip">NIP. {{ $sekolah->nip }}</div>
            @endif
        </div>
    </div>

</div>

</body>
</html>
