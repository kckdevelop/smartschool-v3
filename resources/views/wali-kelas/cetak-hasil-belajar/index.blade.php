@extends('layouts.app')

@section('title', 'Wali Kelas - Cetak Hasil Belajar')

@push('styles')
<style>
    .wk-hero {
        background: linear-gradient(135deg, #1e3a5f 0%, #2d6a9f 50%, #1abc9c 100%);
        border-radius: 16px;
        padding: 32px 36px;
        margin-bottom: 28px;
        color: #fff;
        position: relative;
        overflow: hidden;
    }
    .wk-hero::before {
        content: '';
        position: absolute;
        top: -40px; right: -40px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,.08);
    }
    .wk-hero::after {
        content: '';
        position: absolute;
        bottom: -60px; left: -30px;
        width: 260px; height: 260px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
    }
    .wk-hero h1 { font-size: 1.75rem; font-weight: 800; margin-bottom: 6px; }
    .wk-hero p  { opacity: .85; font-size: .95rem; }
    .wk-badge   { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,.18);
                  border: 1px solid rgba(255,255,255,.3); border-radius: 20px;
                  padding: 4px 14px; font-size: .8rem; font-weight: 600; margin-top: 12px; }

    .info-semester {
        background: #f0f9ff; border: 1px solid #bae6fd;
        border-radius: 12px; padding: 14px 20px;
        display: flex; align-items: center; gap: 10px;
        margin-bottom: 24px; font-size: .9rem; color: #0369a1;
    }

    .kelas-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(270px, 1fr));
        gap: 20px;
    }
    .kelas-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 22px;
        text-decoration: none;
        color: inherit;
        transition: all .25s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    .kelas-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 32px rgba(30,58,95,.15);
        border-color: #2d6a9f;
        color: inherit;
        text-decoration: none;
    }
    .kelas-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, #1e3a5f, #1abc9c);
        border-radius: 14px 14px 0 0;
    }
    .kelas-icon {
        width: 52px; height: 52px;
        border-radius: 12px;
        background: linear-gradient(135deg, #1e3a5f, #2d6a9f);
        display: flex; align-items: center; justify-content: center;
        margin-bottom: 14px;
        font-size: 1.5rem; color: #fff;
    }
    .kelas-name  { font-size: 1.25rem; font-weight: 800; color: #1e293b; }
    .kelas-wali  { font-size: .82rem; color: #64748b; margin-top: 4px; }
    .kelas-jurusan { font-size: .78rem; color: #94a3b8; margin-top: 2px; }
    .kelas-meta {
        display: flex; align-items: center; justify-content: space-between;
        margin-top: 16px; padding-top: 14px;
        border-top: 1px solid #f1f5f9;
    }
    .kelas-count { font-size: .85rem; color: #475569; }
    .kelas-count span { font-weight: 700; color: #1e3a5f; }
    .kelas-btn {
        background: linear-gradient(135deg, #1e3a5f, #2d6a9f);
        color: #fff; border: none; border-radius: 8px;
        padding: 6px 14px; font-size: .8rem; font-weight: 600;
        cursor: pointer; transition: opacity .2s;
    }
    .kelas-btn:hover { opacity: .85; }

    .empty-state { text-align: center; padding: 60px 20px; color: #94a3b8; }
    .empty-state i { font-size: 3rem; margin-bottom: 12px; display: block; }
</style>
@endpush

@section('content')

{{-- Hero --}}
<div class="wk-hero">
    <div style="position:relative;z-index:1;">
        <h1><i class="fa-solid fa-chalkboard-teacher" style="font-size:1.4rem; margin-right:10px;"></i> Wali Kelas</h1>
        <p>Cetak Hasil Belajar Siswa — Pilih kelas untuk melihat daftar siswa</p>
        <div class="wk-badge">
            <i class="fa-solid fa-school"></i>
            {{ $semester ? 'Semester ' . $semester->semester . ' — ' . optional($semester->tahunAjaran)->tahun : 'Semester Aktif' }}
        </div>
    </div>
</div>

{{-- Info Semester --}}
@if($semester)
<div class="info-semester">
    <i class="fa-solid fa-circle-info fa-lg"></i>
    <div>
        <strong>Periode Aktif:</strong>
        Semester {{ $semester->semester }}
        &mdash;
        {{ \Carbon\Carbon::parse($semester->awal)->translatedFormat('d F Y') }}
        s/d
        {{ \Carbon\Carbon::parse($semester->akhir)->translatedFormat('d F Y') }}
    </div>
</div>
@endif

{{-- Grid Kelas --}}
@if($kelasList->isEmpty())
    <div class="empty-state">
        <i class="fa-solid fa-school-flag"></i>
        <p>Belum ada data kelas aktif.</p>
    </div>
@else
<div class="kelas-grid">
    @foreach($kelasList as $kelas)
    @php $jumlahSiswa = $kelas->siswa()->where('status','aktif')->count(); @endphp
    <a href="{{ route('wali-kelas.cetak-hasil-belajar.siswa', $kelas->id_kelas) }}" class="kelas-card">
        <div class="kelas-icon">
            <i class="fa-solid fa-door-open"></i>
        </div>
        <div class="kelas-name">Kelas {{ $kelas->nama_kelas }}</div>
        <div class="kelas-wali">
            <i class="fa-solid fa-user-tie fa-xs"></i>
            Wali Kelas: {{ optional($kelas->guru)->nama_guru ?? '—' }}
        </div>
        <div class="kelas-jurusan">
            <i class="fa-solid fa-layer-group fa-xs"></i>
            Jurusan: {{ optional($kelas->jurusan)->nama_jurusan ?? '—' }}
        </div>
        <div class="kelas-meta">
            <div class="kelas-count">
                <span>{{ $jumlahSiswa }}</span> Siswa Aktif
            </div>
            <button class="kelas-btn" onclick="event.preventDefault(); window.location.href='{{ route('wali-kelas.cetak-hasil-belajar.siswa', $kelas->id_kelas) }}'">
                Lihat <i class="fa-solid fa-arrow-right fa-xs"></i>
            </button>
        </div>
    </a>
    @endforeach
</div>
@endif

@endsection
