@extends('layouts.app')

@section('title', 'Detail Kunjungan UKS — SmartSchool')
@section('header_title', 'Detail Kunjungan UKS')
@section('header_subtitle', 'Detail data kunjungan siswa ke Unit Kesehatan Sekolah')

@section('content')
<div class="page-content">
    <div style="margin-bottom:20px;">
        <a href="{{ route('uks.kunjungan.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    <div class="card" style="overflow:hidden;">
        {{-- Profile/Visit Hero --}}
        <div class="visit-hero">
            <div class="visit-icon">
                <i class="fa-solid fa-hospital-user"></i>
            </div>
            <div>
                <h2 class="visit-title">{{ $kunjungan->siswa?->nama_siswa ?? 'Siswa Tidak Ditemukan' }}</h2>
                <div class="visit-meta">
                    <span><i class="fa-solid fa-id-card"></i> NIS: {{ $kunjungan->nis }}</span>
                    <span><i class="fa-solid fa-graduation-cap"></i> Kelas: {{ $kunjungan->siswa?->kelas?->nama_kelas ?? '-' }}</span>
                    <span><i class="fa-solid fa-calendar-day"></i> {{ \Carbon\Carbon::parse($kunjungan->tanggal)->translatedFormat('d F Y') }}</span>
                    <span><i class="fa-solid fa-clock"></i> {{ \Carbon\Carbon::parse($kunjungan->jam)->format('H:i') }} WIB</span>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="detail-section">
                <h3 class="detail-section-title"><i class="fa-solid fa-notes-medical"></i> Catatan Medis</h3>
                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label">Keluhan</span>
                        <div class="info-value" style="background:#f8fafc; padding:12px; border-radius:8px; border:1px solid #e2e8f0; min-height:60px;">
                            {{ $kunjungan->keluhan }}
                        </div>
                    </div>
                    <div class="info-grid-inner">
                        <div class="info-item" style="margin-bottom:16px;">
                            <span class="info-label">Diagnosa</span>
                            <span class="badge badge-warning" style="font-size:0.95rem; padding:6px 12px; display:inline-block;">{{ $kunjungan->diagnosa }}</span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Tindakan</span>
                            <span class="badge badge-success" style="font-size:0.95rem; padding:6px 12px; display:inline-block;">{{ $kunjungan->tindakan }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="detail-section" style="margin-top:30px;">
                <h3 class="detail-section-title"><i class="fa-solid fa-pills"></i> Pemberian Obat</h3>
                @if($kunjungan->riwayatObat && $kunjungan->riwayatObat->count() > 0)
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nama Obat</th>
                                <th>Dosis / Aturan Pakai</th>
                                <th>Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kunjungan->riwayatObat as $idx => $obat)
                            <tr>
                                <td style="width:50px; color:var(--text-muted);">{{ $idx + 1 }}</td>
                                <td style="font-weight:600;"><i class="fa-solid fa-prescription-bottle-medical" style="color:var(--color-primary); margin-right:6px;"></i> {{ $obat->nama_obat }}</td>
                                <td><span class="badge badge-info">{{ $obat->dosis }}</span></td>
                                <td><strong>{{ $obat->jumlah }}</strong> Pcs / Tablet</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="empty-state">
                        <i class="fa-solid fa-pills" style="font-size:2rem; opacity:.2; display:block; margin-bottom:8px;"></i>
                        <p class="text-muted">Tidak ada pemberian obat pada kunjungan ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.visit-hero {
    background: linear-gradient(135deg, #0d9488 0%, #0f766e 100%);
    padding: 28px;
    display: flex;
    align-items: center;
    gap: 24px;
    color: #fff;
}
.visit-icon {
    width: 64px;
    height: 64px;
    border-radius: 16px;
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.4);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.8rem;
    flex-shrink: 0;
}
.visit-title {
    font-size: 1.35rem;
    font-weight: 800;
    margin: 0 0 6px 0;
}
.visit-meta {
    font-size: 0.82rem;
    opacity: 0.9;
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}
.visit-meta span {
    display: flex;
    align-items: center;
    gap: 6px;
    background: rgba(255, 255, 255, 0.15);
    padding: 4px 10px;
    border-radius: 20px;
}
.detail-section-title {
    font-size: 0.78rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.7px;
    color: var(--color-primary);
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 8px;
    border-bottom: 1.5px solid rgba(13, 148, 136, 0.1);
}
.info-grid {
    display: grid;
    grid-template-columns: 1.5fr 1fr;
    gap: 24px;
}
.info-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
}
.info-label {
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    color: var(--text-muted);
}
.empty-state {
    text-align: center;
    padding: 24px;
    background: #f8fafc;
    border: 1px dashed #e2e8f0;
    border-radius: 8px;
}
@media(max-width:768px) {
    .info-grid {
        grid-template-columns: 1fr;
    }
    .visit-hero {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }
}
</style>
@endpush
@endsection
