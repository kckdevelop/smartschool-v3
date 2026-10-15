@extends('layouts.app')

@section('title', 'Preview Raport — {{ $siswa->nama_siswa }}')

@push('styles')
<style>
    /* ── Wrapper ─────────────────────────────────────────────── */
    .preview-wrapper { max-width: 820px; margin: 0 auto; }
    .preview-toolbar {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 20px; gap: 12px; flex-wrap: wrap;
    }
    .preview-toolbar a, .preview-toolbar button {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 10px;
        font-size: .875rem; font-weight: 600; cursor: pointer;
        text-decoration: none; transition: all .2s; border: none;
    }
    .btn-back { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }
    .btn-back:hover { background: #e2e8f0; color: #1e293b; text-decoration: none; }
    .btn-pdf  { background: linear-gradient(135deg, #1e3a5f, #2d6a9f); color: #fff; }
    .btn-pdf:hover { opacity: .87; color: #fff; text-decoration: none; }
    .btn-print { background: linear-gradient(135deg, #065f46, #059669); color: #fff; }
    .btn-print:hover { opacity: .87; color: #fff; }

    /* ── Raport Card ─────────────────────────────────────────── */
    .raport-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 16px;
        box-shadow: 0 4px 24px rgba(30,58,95,.08);
        overflow: hidden;
    }
    .raport-body { padding: 32px 36px; }

    /* ── Section headers ─────────────────────────────────────── */
    .section-title {
        font-size: .95rem; font-weight: 800; color: #1e3a5f;
        text-transform: uppercase; letter-spacing: .05em;
        border-left: 4px solid #1abc9c; padding-left: 12px;
        margin: 28px 0 16px;
    }

    /* ── Data Siswa ──────────────────────────────────────────── */
    .siswa-info-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 10px 24px;
    }
    .info-row { display: flex; flex-direction: column; padding: 8px 0; border-bottom: 1px dashed #f1f5f9; }
    .info-label { font-size: .75rem; color: #94a3b8; text-transform: uppercase; letter-spacing: .04em; }
    .info-value { font-size: .9rem; font-weight: 600; color: #1e293b; margin-top: 2px; }

    /* ── Presensi ────────────────────────────────────────────── */
    .presensi-stats { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 16px; }
    .prs-badge {
        flex: 1; min-width: 90px; border-radius: 12px;
        padding: 14px 16px; text-align: center;
    }
    .prs-badge .val { font-size: 1.6rem; font-weight: 800; line-height: 1; }
    .prs-badge .lbl { font-size: .75rem; font-weight: 600; opacity: .85; margin-top: 4px; }
    .prs-h { background: #dcfce7; color: #166534; }
    .prs-i { background: #dbeafe; color: #1d4ed8; }
    .prs-s { background: #fef9c3; color: #854d0e; }
    .prs-a { background: #fee2e2; color: #991b1b; }

    /* ── Tables ──────────────────────────────────────────────── */
    .mini-table { width: 100%; border-collapse: collapse; font-size: .83rem; }
    .mini-table th {
        background: #f8fafc; color: #64748b;
        font-size: .75rem; font-weight: 700; text-transform: uppercase; letter-spacing: .04em;
        padding: 9px 12px; border: 1px solid #e5e7eb; text-align: left;
    }
    .mini-table td { padding: 8px 12px; border: 1px solid #f1f5f9; color: #374151; vertical-align: top; }
    .mini-table tbody tr:nth-child(even) { background: #f9fafb; }
    .mini-table .tc { text-align: center; }

    /* ── Empty ───────────────────────────────────────────────── */
    .empty-mini { text-align:center; padding: 20px; color:#94a3b8; font-style: italic; font-size: .85rem; }

    @media print {
        .preview-toolbar { display: none !important; }
        .raport-card { box-shadow: none; border: none; border-radius: 0; }
    }
</style>
@endpush

@section('content')
<div class="preview-wrapper">

    {{-- Toolbar --}}
    <div class="preview-toolbar">
        <a href="{{ url()->previous() }}" class="btn-back">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
        <div style="display:flex;gap:10px;flex-wrap:wrap;">
            <button class="btn-print" onclick="window.print()">
                <i class="fa-solid fa-print"></i> Cetak
            </button>
            <a href="{{ route('wali-kelas.cetak-hasil-belajar.download', $siswa->nis) }}" class="btn-pdf">
                <i class="fa-solid fa-file-pdf"></i> Download PDF
            </a>
        </div>
    </div>

    {{-- Raport Card --}}
    <div class="raport-card">
        <div class="raport-body">

            {{-- KOP SEKOLAH --}}
            @include('partials.kop-surat', ['isPdf' => false, 'isPreview' => true])

            {{-- Judul Raport --}}
            <div style="text-align:center; margin: 10px 0 24px;">
                <div style="font-size:1.05rem; font-weight:800; text-transform:uppercase; letter-spacing:.06em; color:#1e3a5f;">
                    Laporan Hasil Belajar Siswa
                </div>
                <div style="font-size:.85rem; color:#64748b; margin-top:4px;">
                    Semester {{ $semester?->semester ?? '—' }}
                    &mdash; Tahun Ajaran {{ optional($semester?->tahunAjaran)->tahun ?? '—' }}
                </div>
                <div style="width:60px; height:3px; background:linear-gradient(90deg,#1e3a5f,#1abc9c); border-radius:4px; margin:10px auto 0;"></div>
            </div>

            {{-- DATA SISWA ──────────────────────────────────────────── --}}
            <div class="section-title"><i class="fa-solid fa-id-card fa-sm"></i> Data Siswa</div>
            <div class="siswa-info-grid">
                <div class="info-row">
                    <span class="info-label">Nama Lengkap</span>
                    <span class="info-value">{{ $siswa->nama_siswa }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">NIS / NISN</span>
                    <span class="info-value">{{ $siswa->nis }} / {{ $siswa->nisn ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Kelas</span>
                    <span class="info-value">{{ optional($siswa->kelas)->nama_kelas ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jurusan</span>
                    <span class="info-value">{{ optional(optional($siswa->kelas)->jurusan)->nama_jurusan ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Jenis Kelamin</span>
                    <span class="info-value">{{ $siswa->jenkel === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tempat, Tanggal Lahir</span>
                    <span class="info-value">
                        {{ $siswa->tempat_lahir ?? '—' }},
                        {{ $siswa->tgl_lahir ? \Carbon\Carbon::parse($siswa->tgl_lahir)->translatedFormat('d F Y') : '—' }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Wali Kelas</span>
                    <span class="info-value">{{ optional(optional($siswa->kelas)->guru)->nama_guru ?? '—' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Alamat</span>
                    <span class="info-value">{{ optional($siswa->detail)->alamat ?? '—' }}</span>
                </div>
            </div>

            {{-- PRESENSI ─────────────────────────────────────────────── --}}
            <div class="section-title"><i class="fa-solid fa-calendar-check fa-sm"></i> Data Presensi</div>
            <div class="presensi-stats">
                <div class="prs-badge prs-h">
                    <div class="val">{{ $presensi['hadir'] }}</div>
                    <div class="lbl">Hadir</div>
                </div>
                <div class="prs-badge prs-i">
                    <div class="val">{{ $presensi['izin'] }}</div>
                    <div class="lbl">Izin</div>
                </div>
                <div class="prs-badge prs-s">
                    <div class="val">{{ $presensi['sakit'] }}</div>
                    <div class="lbl">Sakit</div>
                </div>
                <div class="prs-badge prs-a">
                    <div class="val">{{ $presensi['alpha'] }}</div>
                    <div class="lbl">Alpha</div>
                </div>
                <div class="prs-badge" style="background:#f3f4f6; color:#374151;">
                    <div class="val">{{ $presensi['total'] }}</div>
                    <div class="lbl">Total</div>
                </div>
            </div>

            @if($presensi['riwayat']->isNotEmpty())
            <table class="mini-table">
                <thead>
                    <tr>
                        <th style="width:30px;" class="tc">No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th class="tc">Status</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($presensi['riwayat'] as $idx => $p)
                    @php
                        $statusMap = ['H'=>['Hadir','#166534','#dcfce7'], 'I'=>['Izin','#1d4ed8','#dbeafe'],
                                      'S'=>['Sakit','#854d0e','#fef9c3'], 'A'=>['Alpha','#991b1b','#fee2e2']];
                        [$label, $clr, $bg] = $statusMap[$p->status] ?? [$p->status,'#374151','#f3f4f6'];
                    @endphp
                    <tr>
                        <td class="tc">{{ $idx + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>{{ $p->jam ?? '—' }}</td>
                        <td class="tc">
                            <span style="background:{{ $bg }}; color:{{ $clr }}; border-radius:12px; padding:2px 10px; font-size:.75rem; font-weight:700;">
                                {{ $label }}
                            </span>
                        </td>
                        <td>{{ $p->keterangan ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="empty-mini">Tidak ada data presensi pada periode ini.</div>
            @endif

            {{-- BTAQ ────────────────────────────────────────────────── --}}
            <div class="section-title"><i class="fa-solid fa-book-open fa-sm"></i> Riwayat BTAQ</div>
            <div style="display:flex; gap:14px; margin-bottom:14px; flex-wrap:wrap;">
                <div style="background:#f0fdf4; border:1px solid #bbf7d0; border-radius:10px; padding:12px 18px; text-align:center; min-width:90px;">
                    <div style="font-size:1.4rem; font-weight:800; color:#166534;">{{ $btaq['total'] }}</div>
                    <div style="font-size:.75rem; color:#166534; font-weight:600;">Total Sesi</div>
                </div>
                <div style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:10px; padding:12px 18px; text-align:center; min-width:90px;">
                    <div style="font-size:1.4rem; font-weight:800; color:#1d4ed8;">{{ $btaq['level_iqro'] }}</div>
                    <div style="font-size:.75rem; color:#1d4ed8; font-weight:600;">Level Iqro</div>
                </div>
                <div style="background:#fefce8; border:1px solid #fde68a; border-radius:10px; padding:12px 18px; text-align:center; min-width:90px;">
                    <div style="font-size:1.4rem; font-weight:800; color:#854d0e;">{{ $btaq['level_quran'] }}</div>
                    <div style="font-size:.75rem; color:#854d0e; font-weight:600;">Level Al-Qur'an</div>
                </div>
            </div>

            @if($btaq['riwayat']->isNotEmpty())
            <table class="mini-table">
                <thead>
                    <tr>
                        <th style="width:30px;" class="tc">No</th>
                        <th>Tanggal</th>
                        <th>Level</th>
                        <th>Awal</th>
                        <th>Akhir</th>
                        <th>Guru</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($btaq['riwayat'] as $idx => $b)
                    <tr>
                        <td class="tc">{{ $idx + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($b->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>
                            <span style="background:{{ $b->level==='iqro'?'#dbeafe':'#fef9c3' }}; color:{{ $b->level==='iqro'?'#1d4ed8':'#854d0e' }}; border-radius:12px; padding:2px 10px; font-size:.75rem; font-weight:700; text-transform:capitalize;">
                                {{ $b->level }}
                            </span>
                        </td>
                        <td>{{ $b->awal ?? '—' }}</td>
                        <td>{{ $b->akhir ?? '—' }}</td>
                        <td>{{ optional($b->guru)->nama_guru ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="empty-mini">Tidak ada data BTAQ pada periode ini.</div>
            @endif

            {{-- RIWAYAT KESEHATAN UKS ──────────────────────────────── --}}
            <div class="section-title"><i class="fa-solid fa-kit-medical fa-sm"></i> Riwayat Kunjungan UKS</div>

            @if($kunjunganUks['riwayat']->isNotEmpty())
            <div style="background:#fff1f2; border:1px solid #fecdd3; border-radius:10px; padding:10px 16px; margin-bottom:12px; font-size:.85rem; color:#9f1239;">
                <i class="fa-solid fa-notes-medical"></i>
                <strong>{{ $kunjunganUks['total'] }} kali</strong> kunjungan UKS selama periode ini.
            </div>
            <table class="mini-table">
                <thead>
                    <tr>
                        <th style="width:30px;" class="tc">No</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>Keluhan</th>
                        <th>Diagnosa</th>
                        <th>Tindakan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kunjunganUks['riwayat'] as $idx => $k)
                    <tr>
                        <td class="tc">{{ $idx + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($k->tanggal)->translatedFormat('d F Y') }}</td>
                        <td>{{ $k->jam ?? '—' }}</td>
                        <td>{{ $k->keluhan ?? '—' }}</td>
                        <td>{{ $k->diagnosa ?? '—' }}</td>
                        <td>{{ $k->tindakan ?? '—' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                <div class="empty-mini">Tidak ada data kunjungan UKS pada periode ini.</div>
            @endif

            {{-- TANDA TANGAN ─────────────────────────────────────────── --}}
            <div style="margin-top:40px; display:flex; justify-content:flex-end;">
                <div style="text-align:center; min-width:200px;">
                    <div style="font-size:.85rem;">{{ optional($sekolah)->kota ?? 'Kota' }}, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                    <div style="font-size:.85rem;">Wali Kelas,</div>
                    <div style="height:70px;"></div>
                    <div style="font-weight:800; text-decoration:underline; font-size:.9rem;">
                        {{ optional(optional($siswa->kelas)->guru)->nama_guru ?? '(________________________)' }}
                    </div>
                </div>
            </div>

        </div>{{-- /raport-body --}}
    </div>{{-- /raport-card --}}
</div>
@endsection
