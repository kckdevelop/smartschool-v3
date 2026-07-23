@extends('layouts.app')

@section('title', 'Rekap Pelanggaran Kelas — SmartSchool')
@section('header_title', 'Rekap Pelanggaran Kelas')
@section('header_subtitle', 'Ringkasan pelanggaran per siswa berdasarkan kelas')

@push('styles')
<style>
/* ── Filter card ── */
.rekap-filter {
    display: flex; flex-wrap: wrap; gap: 10px; align-items: center;
    padding: 16px 20px;
    background: var(--bg-card);
    border-radius: var(--radius-card);
    border: 1.5px solid var(--border-color);
    margin-bottom: 20px;
    box-shadow: var(--shadow-card);
}

/* ── Rekap header ── */
.rekap-header-bar {
    display: flex; justify-content: space-between; align-items: center;
    padding: 14px 20px;
    border-bottom: 1.5px solid var(--border-color);
    background: linear-gradient(135deg, rgba(239,68,68,.05), rgba(249,115,22,.05));
}

/* ── Siswa rank card ── */
.siswa-rank-badge {
    display: inline-flex; align-items: center; justify-content: center;
    width: 32px; height: 32px; border-radius: 50%;
    font-weight: 800; font-size: .85rem;
}
.rank-1 { background: #fef3c7; color: #b45309; }
.rank-2 { background: #e5e7eb; color: #374151; }
.rank-3 { background: #fee2e2; color: #b91c1c; }
.rank-other { background: var(--color-primary-light); color: var(--color-primary); }

/* ── Per-jenis pill ── */
.jenis-pill {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 2px 8px; border-radius: 20px;
    font-size: .72rem; font-weight: 700;
    background: rgba(239,68,68,.1); color: #b91c1c;
    border: 1px solid rgba(239,68,68,.2);
}
.jenis-pill-zero {
    background: var(--bg-muted, #f1f5f9);
    color: var(--text-muted);
    border-color: transparent;
}

/* ── Summary top banner ── */
.rekap-summary-row {
    display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px;
    margin-bottom: 20px;
}
.rekap-summary-card {
    padding: 16px 20px;
    background: var(--bg-card);
    border-radius: var(--radius-card);
    border: 1.5px solid var(--border-color);
    box-shadow: var(--shadow-card);
}
.rekap-summary-card .rs-num {
    font-size: 1.8rem; font-weight: 800; color: var(--text-primary); line-height: 1;
}
.rekap-summary-card .rs-lbl {
    font-size: .78rem; color: var(--text-muted); margin-top: 3px;
}

/* ── Jenis legend ── */
.jenis-legend {
    display: grid; grid-template-columns: repeat(2, 1fr); gap: 8px;
    margin-bottom: 20px;
}
.jenis-legend-item {
    display: flex; align-items: flex-start; gap: 8px;
    padding: 10px 12px;
    background: var(--bg-card);
    border: 1.5px solid var(--border-color);
    border-radius: 10px;
    font-size: .8rem;
}
.jenis-legend-num {
    width: 24px; height: 24px; border-radius: 50%;
    background: #fee2e2; color: #b91c1c;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: .72rem; flex-shrink: 0;
}

/* ── Progress bar ── */
.prog-bar-wrap {
    background: var(--border-color); border-radius: 99px;
    height: 6px; overflow: hidden; margin-top: 4px;
}
.prog-bar-fill {
    height: 100%; border-radius: 99px;
    background: linear-gradient(90deg, #ef4444, #f97316);
    transition: width .4s ease;
}

@media(max-width:640px) {
    .rekap-summary-row { grid-template-columns: 1fr; }
    .jenis-legend { grid-template-columns: 1fr; }
}
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- ── Filter kelas ── --}}
    <form method="GET" class="rekap-filter" id="filter-rekap">
        <label style="font-weight:600;white-space:nowrap;color:var(--text-primary);">
            <i class="fa-solid fa-chalkboard-user" style="color:var(--color-primary);"></i>
            Pilih Kelas:
        </label>
        <select name="id_kelas" class="form-control" style="min-width:200px;" onchange="this.form.submit()">
            <option value="">-- Pilih Kelas --</option>
            @foreach($kelasList as $kls)
                <option value="{{ $kls->id_kelas }}"
                    {{ $selectedKelasId == $kls->id_kelas ? 'selected' : '' }}>
                    {{ $kls->nama_kelas }}
                </option>
            @endforeach
        </select>

        <div style="flex:1;"></div>
        <a href="{{ route('guru-kelas.pelanggaran.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-list"></i> Kembali ke Daftar
        </a>
        <a href="{{ route('guru-kelas.pelanggaran.index', ['id_kelas' => $selectedKelasId]) }}"
           class="btn btn-danger btn-sm">
            <i class="fa-solid fa-plus"></i> Catat Pelanggaran
        </a>
    </form>

    @if(!$selectedKelasId)
        {{-- ── Tampilkan legend jenis pelanggaran sebelum kelas dipilih ── --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title">
                    <i class="fa-solid fa-list-ol" style="color:#ef4444;"></i>
                    Daftar Jenis Pelanggaran
                </h2>
            </div>
            <div class="card-body">
                <p style="color:var(--text-muted);margin-bottom:16px;font-size:.87rem;">
                    Pilih kelas di atas untuk melihat rekap pelanggaran. Berikut adalah daftar 8 jenis pelanggaran yang berlaku:
                </p>
                <div class="jenis-legend">
                    @foreach($daftarJenis as $no => $j)
                    <div class="jenis-legend-item">
                        <div class="jenis-legend-num">{{ $no }}</div>
                        <div>
                            <div style="font-weight:600;font-size:.83rem;">{{ $j['label'] }}</div>
                            <div style="font-size:.74rem;color:var(--text-muted);margin-top:2px;">
                                <i class="fa-solid fa-arrow-right" style="font-size:.6rem;"></i>
                                {{ $j['pembinaan'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @else
        {{-- ── Summary cards ── --}}
        @php
            $totalSiswa       = $rekapSiswa->count();
            $siswaMelanggar   = $rekapSiswa->where('total', '>', 0)->count();
            $totalPelanggaran = $rekapSiswa->sum('total');
        @endphp
        <div class="rekap-summary-row">
            <div class="rekap-summary-card">
                <div class="rs-num">{{ $totalSiswa }}</div>
                <div class="rs-lbl">Total Siswa di Kelas {{ $selectedKelas?->nama_kelas }}</div>
            </div>
            <div class="rekap-summary-card">
                <div class="rs-num" style="color:#ef4444;">{{ $siswaMelanggar }}</div>
                <div class="rs-lbl">Siswa Pernah Melanggar</div>
            </div>
            <div class="rekap-summary-card">
                <div class="rs-num" style="color:#f97316;">{{ $totalPelanggaran }}</div>
                <div class="rs-lbl">Total Catatan Pelanggaran</div>
            </div>
        </div>

        {{-- ── Rekap Table ── --}}
        <div class="card">
            <div class="rekap-header-bar">
                <h2 class="card-title" style="margin:0;">
                    <i class="fa-solid fa-users" style="color:#ef4444;"></i>
                    Rekap Siswa Kelas {{ $selectedKelas?->nama_kelas }}
                </h2>
                <span style="font-size:.82rem;color:var(--text-muted);">
                    Diurutkan berdasarkan jumlah pelanggaran terbanyak
                </span>
            </div>

            @if($rekapSiswa->isEmpty())
                <div class="card-body text-center py-6">
                    <i class="fa-solid fa-circle-check" style="font-size:3rem;color:#10b981;display:block;margin-bottom:12px;"></i>
                    <p style="font-weight:700;font-size:1.1rem;color:#10b981;">Kelas ini belum memiliki catatan pelanggaran!</p>
                    <p style="color:var(--text-muted);font-size:.87rem;">Semua siswa bersikap baik 🎉</p>
                </div>
            @else
            <div class="card-body p-0">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width:50px;">#</th>
                            <th>Nama Siswa</th>
                            <th style="width:80px;text-align:center;">Total</th>
                            {{-- header jenis 1-8 --}}
                            @for($j = 1; $j <= 8; $j++)
                            <th style="width:55px;text-align:center;font-size:.75rem;" title="{{ $daftarJenis[$j]['label'] }}">
                                P{{ $j }}
                            </th>
                            @endfor
                            <th style="width:110px;">Terakhir</th>
                            <th style="width:80px;text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rekapSiswa as $idx => $r)
                        @php
                            $rankClass = match($idx) {
                                0 => 'rank-1', 1 => 'rank-2', 2 => 'rank-3', default => 'rank-other'
                            };
                            $maxTotal = $rekapSiswa->first()['total'] ?? 1;
                        @endphp
                        <tr @if($r['total'] > 0) style="background:rgba(239,68,68,.018);" @endif>
                            <td>
                                <span class="siswa-rank-badge {{ $rankClass }}">{{ $idx + 1 }}</span>
                            </td>
                            <td>
                                <div style="font-weight:700;">{{ $r['siswa']->nama_siswa }}</div>
                                <div style="font-size:.74rem;color:var(--text-muted);">NIS: {{ $r['siswa']->nis }}</div>
                                @if($r['total'] > 0 && $maxTotal > 0)
                                <div class="prog-bar-wrap" style="width:120px;margin-top:5px;">
                                    <div class="prog-bar-fill" style="width:{{ min(100, ($r['total']/$maxTotal)*100) }}%;"></div>
                                </div>
                                @endif
                            </td>
                            <td style="text-align:center;">
                                @if($r['total'] > 0)
                                    <span class="badge badge-danger" style="font-size:.9rem;padding:4px 12px;">
                                        {{ $r['total'] }}
                                    </span>
                                @else
                                    <span style="color:var(--text-muted);font-size:.82rem;">—</span>
                                @endif
                            </td>
                            {{-- Count per jenis --}}
                            @for($j = 1; $j <= 8; $j++)
                            <td style="text-align:center;">
                                @if(($r['perJenis'][$j] ?? 0) > 0)
                                    <span class="jenis-pill">{{ $r['perJenis'][$j] }}x</span>
                                @else
                                    <span class="jenis-pill jenis-pill-zero">—</span>
                                @endif
                            </td>
                            @endfor
                            <td style="font-size:.78rem;color:var(--text-muted);">
                                @if($r['terbaru'])
                                    {{ \Carbon\Carbon::parse($r['terbaru']->tanggal)->format('d/m/Y') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td style="text-align:center;">
                                <a href="{{ route('guru-kelas.pelanggaran.index', ['id_kelas' => $selectedKelasId]) }}"
                                   class="btn-icon" title="Lihat pelanggaran kelas ini"
                                   style="text-decoration:none;">
                                    <i class="fa-solid fa-eye" style="color:var(--color-primary);"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endif

            {{-- ── Legend Jenis ── --}}
            <div class="card-body" style="border-top:1.5px solid var(--border-color);">
                <p style="font-weight:700;margin-bottom:12px;font-size:.85rem;color:var(--text-secondary);">
                    <i class="fa-solid fa-circle-info" style="color:#ef4444;"></i>
                    Keterangan Kolom P1–P8:
                </p>
                <div class="jenis-legend">
                    @foreach($daftarJenis as $no => $j)
                    <div class="jenis-legend-item">
                        <div class="jenis-legend-num">{{ $no }}</div>
                        <div>
                            <div style="font-weight:600;font-size:.8rem;">{{ $j['label'] }}</div>
                            <div style="font-size:.72rem;color:var(--text-muted);margin-top:1px;">
                                Pembinaan: {{ $j['pembinaan'] }}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
