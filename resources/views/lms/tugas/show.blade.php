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

                <div class="tugas-deskripsi mb-3" style="font-size: 0.95rem; line-height: 1.6; color: var(--text-primary);">
                    {!! $tugas->deskripsi !!}
                </div>

                @if($tugas->tipe === 'kuis' && $tugas->soal->count() > 0)
                    <div class="mt-4 pt-3" style="border-top: 1.5px solid var(--border-color, #e2e8f0);">
                        <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 16px; display: flex; align-items: center; gap: 8px;">
                            <i class="fa-solid fa-list-check text-primary"></i> Daftar Soal Kuis ({{ $tugas->soal->count() }} Soal)
                        </h3>

                        <div class="soal-list">
                            @foreach($tugas->soal as $soal)
                                <div class="soal-item mb-4 p-3" style="background: var(--bg-surface, #f8fafc); border: 1px solid var(--border-color, #cbd5e1); border-radius: 10px;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <strong style="font-size: 1rem; color: var(--text-primary);">Soal No. {{ $soal->nomor_soal }}</strong>
                                        <span class="badge {{ $soal->jenis_soal === 'pilihan_ganda_komplek' ? 'badge-warning' : ($soal->jenis_soal === 'benar_salah' ? 'badge-info' : 'badge-primary') }}" style="font-size: 0.75rem; text-transform: uppercase;">
                                            {{ str_replace('_', ' ', $soal->jenis_soal) }}
                                        </span>
                                    </div>

                                    <div class="pertanyaan-body mb-3" style="font-size: 0.95rem; line-height: 1.5;">
                                        {!! $soal->pertanyaan !!}
                                    </div>

                                    @if($soal->gambar)
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $soal->gambar) }}" class="img-fluid rounded" style="max-height: 250px;">
                                        </div>
                                    @endif

                                    <div class="pilihan-list" style="display: grid; gap: 8px;">
                                        @foreach($soal->pilihan as $p)
                                            <div class="pilihan-item p-2 rounded" style="display: flex; align-items: center; gap: 10px; font-size: 0.9rem; background: {{ $p->is_kunci ? 'rgba(34, 197, 94, 0.12)' : 'white' }}; border: 1px solid {{ $p->is_kunci ? '#22c55e' : '#e2e8f0' }};">
                                                <span class="badge" style="width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center; background: {{ $p->is_kunci ? '#22c55e' : '#64748b' }}; color: white; font-weight: bold; border-radius: 50%;">
                                                    {{ $p->kunci }}
                                                </span>
                                                <div style="flex: 1;">
                                                    {!! $p->teks !!}
                                                </div>
                                                @if($p->is_kunci)
                                                    <span class="text-success" style="font-size: 0.8rem; font-weight: bold;"><i class="fa-solid fa-check"></i> Kunci</span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
                
                @if($tugas->file_path)
                    <div style="margin-top: 20px; padding: 12px 16px; background: var(--bg-body, #f8fafc); border-radius: 8px; display: inline-flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-paperclip" style="color: var(--color-primary, #0d9488);"></i>
                        <span style="font-size: 0.88rem; font-weight: 600;">File Lampiran Word / PDF:</span>
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
