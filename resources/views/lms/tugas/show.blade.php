@extends('layouts.app')

@section('title', 'Detail Tugas — SmartSchool')
@section('header_title', 'Detail Tugas')
@section('header_subtitle', 'Pantau detail instruksi tugas dan status pengerjaan siswa')

@section('content')
<div class="page-content">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('lms.tugas.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Tugas
        </a>
    </div>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: start;">
        
        {{-- Detail Tugas Card --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-file-invoice"></i> Informasi Tugas</h2>
                <div class="card-header-right">
                    <span class="badge {{ $tugas->is_published ? 'badge-success' : 'badge-muted' }}">
                        {{ $tugas->is_published ? 'Aktif / Tayang' : 'Draft / Nonaktif' }}
                    </span>
                </div>
            </div>
            <div class="card-body">
                <h1 style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary); margin: 0 0 10px 0;">
                    {{ $tugas->judul }}
                </h1>
                
                <div style="display: flex; gap: 20px; font-size: 0.85rem; color: var(--text-muted); margin-bottom: 20px; border-bottom: 1.5px solid var(--border-color); padding-bottom: 12px; flex-wrap: wrap;">
                    <div><i class="fa-regular fa-calendar"></i> <strong>Tenggat:</strong> {{ $tugas->tenggat ? \Carbon\Carbon::parse($tugas->tenggat)->translatedFormat('d F Y') : '-' }}</div>
                    <div><i class="fa-solid fa-graduation-cap"></i> <strong>Kelas:</strong> {{ $tugas->kursus->kelas->tingkat ?? '-' }} {{ $tugas->kursus->kelas->rombel ?? '' }}</div>
                    <div><i class="fa-solid fa-user-tie"></i> <strong>Guru:</strong> {{ $tugas->kursus->guru->nama_guru ?? '-' }}</div>
                </div>

                <div class="tugas-deskripsi" style="font-size: 0.95rem; line-height: 1.6; color: var(--text-primary);">
                    {!! $tugas->deskripsi !!}
                </div>
                
                @if($tugas->file_path)
                    <div style="margin-top: 20px; padding: 12px 16px; background: var(--bg-body, #f8fafc); border-radius: 8px; display: inline-flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-paperclip" style="color: var(--color-primary, #0d9488);"></i>
                        <span style="font-size: 0.88rem; font-weight: 600;">Lampiran Tugas:</span>
                        <a href="{{ asset('storage/' . $tugas->file_path) }}" target="_blank" class="btn btn-primary btn-sm" style="padding: 4px 8px; font-size: 0.78rem;">
                            <i class="fa-solid fa-download"></i> Unduh File
                        </a>
                    </div>
                @endif
            </div>
        </div>

        {{-- Status Penyelesaian Card --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-chart-simple"></i> Status Pengerjaan</h2>
            </div>
            <div class="card-body p-0">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Siswa</th>
                            <th style="width: 110px; text-align: center;">Status</th>
                            <th style="width: 60px; text-align: center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tagihanList as $tagihan)
                        <tr>
                            <td>
                                <strong style="display: block; color: var(--text-primary); font-size: 0.88rem;">{{ $tagihan->nama_siswa }}</strong>
                                <small class="text-muted" style="font-size: 0.75rem;">NIS: {{ $tagihan->nis }}</small>
                            </td>
                            <td style="text-align: center;">
                                @if($tagihan->status_tugas === 'cek')
                                    <span class="badge badge-success" style="font-size: 0.72rem; padding: 3px 6px;"><i class="fa-solid fa-circle-check"></i> Selesai Dicek</span>
                                @elseif($tagihan->status_tugas === 'sudah')
                                    <span class="badge" style="font-size: 0.72rem; padding: 3px 6px; background: #eab308; color: white;"><i class="fa-solid fa-circle-info"></i> Butuh Cek</span>
                                @else
                                    <span class="badge badge-muted" style="font-size: 0.72rem; padding: 3px 6px;"><i class="fa-solid fa-clock"></i> Belum</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($tagihan->id_pengumpulan)
                                    <a href="{{ route('lms.tagihan.show', $tagihan->id_pengumpulan) }}" class="btn-icon" title="Periksa Jawaban" style="color: var(--color-primary, #0d9488);">
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </a>
                                @else
                                    <span class="text-muted" style="font-size: 0.8rem;">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">Belum ada siswa yang terdaftar di kelas ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
