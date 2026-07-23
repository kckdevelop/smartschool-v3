@extends('layouts.app')

@section('title', 'Periksa Jawaban Siswa — SmartSchool')
@section('header_title', 'Periksa Jawaban')
@section('header_subtitle', 'Tinjau lembar jawaban siswa dan perbarui status pengerjaan')

@section('content')
<div class="page-content">
    <div style="margin-bottom: 20px;">
        <a href="{{ route('lms.tagihan.index', ['id_tugas' => $tagihan->id_tugas]) }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Daftar Tagihan
        </a>
    </div>

    @include('partials.flash')

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; align-items: start;">
        
        {{-- Jawaban Siswa Card --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-file-signature"></i> Lembar Jawaban Siswa</h2>
            </div>
            <div class="card-body">
                <div style="display: flex; justify-content: space-between; align-items: center; background: var(--bg-body, #f8fafc); padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; flex-wrap: wrap; gap: 12px;">
                    <div>
                        <small style="display: block; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Nama Siswa</small>
                        <strong style="font-size: 1.05rem; color: var(--text-primary);">{{ $tagihan->siswa->nama_siswa ?? 'Siswa Terhapus' }}</strong>
                        <span class="text-muted" style="font-size: 0.82rem;">(NIS: {{ $tagihan->nis }}) — Kelas {{ $tagihan->siswa->kelas->tingkat ?? '' }} {{ $tagihan->siswa->kelas->rombel ?? '' }}</span>
                    </div>
                    <div style="text-align: right;">
                        <small style="display: block; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; font-weight: 700;">Status Pengerjaan</small>
                        @if($tagihan->status_tugas === 'cek')
                            <span class="badge badge-success" style="font-size: 0.85rem; padding: 6px 12px;"><i class="fa-solid fa-circle-check"></i> Selesai Dicek</span>
                        @elseif($tagihan->status_tugas === 'sudah')
                            <span class="badge" style="font-size: 0.85rem; padding: 6px 12px; background: #eab308; color: white;"><i class="fa-solid fa-circle-info"></i> Perlu Diperiksa</span>
                        @else
                            <span class="badge badge-muted" style="font-size: 0.85rem; padding: 6px 12px;"><i class="fa-solid fa-clock"></i> Belum Mengumpulkan</span>
                        @endif
                    </div>
                </div>

                {{-- Deskripsi Jawaban Teks --}}
                <div class="mb-5" style="margin-bottom: 24px;">
                    <h3 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; border-left: 3px solid var(--color-primary, #0d9488); padding-left: 8px;">
                        Teks Jawaban / Catatan Siswa:
                    </h3>
                    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px; min-height: 120px; font-size: 0.92rem; line-height: 1.6; color: var(--text-primary);">
                        @if($tagihan->deskripsi)
                            {!! nl2br(e($tagihan->deskripsi)) !!}
                        @else
                            <span class="text-muted"><em>Tidak ada jawaban berupa teks dari siswa.</em></span>
                        @endif
                    </div>
                </div>

                {{-- Berkas Lampiran / File Upload --}}
                <div>
                    <h3 style="font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin-bottom: 8px; border-left: 3px solid var(--color-primary, #0d9488); padding-left: 8px;">
                        File Upload / Lampiran Tugas:
                    </h3>
                    <div style="background: var(--bg-card); border: 1px solid var(--border-color); border-radius: 8px; padding: 16px; display: flex; align-items: center; gap: 12px; flex-wrap: wrap;">
                        @if($tagihan->upload_tugas)
                            <div style="background: rgba(13, 148, 136, 0.1); color: var(--color-primary, #0d9488); width: 48px; height: 48px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                                <i class="fa-regular fa-file"></i>
                            </div>
                            <div style="flex-grow: 1;">
                                <strong style="display: block; font-size: 0.9rem; color: var(--text-primary);">Berkas Tugas Siswa</strong>
                                <small class="text-muted" style="font-size: 0.78rem;">{{ basename($tagihan->upload_tugas) }}</small>
                            </div>
                            <div>
                                <a href="{{ asset('storage/' . $tagihan->upload_tugas) }}" target="_blank" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid fa-download"></i> Unduh / Buka File
                                </a>
                            </div>
                        @else
                            <div class="text-muted" style="font-size: 0.9rem; padding: 6px 0;">
                                <em>Tidak ada file berkas yang diunggah oleh siswa.</em>
                            </div>
                        @endif
                    </div>

                    @if($tagihan->upload_tugas)
                        @php
                            $extension = strtolower(pathinfo($tagihan->upload_tugas, PATHINFO_EXTENSION));
                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                            $isPdf = $extension === 'pdf';
                        @endphp
                        
                        <div style="margin-top: 16px; border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; background: #f8fafc; padding: 10px; display: flex; justify-content: center;">
                            @if($isImage)
                                <img src="{{ asset('storage/' . $tagihan->upload_tugas) }}" alt="Jawaban Siswa" style="max-width: 100%; height: auto; max-height: 600px; border-radius: 6px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
                            @elseif($isPdf)
                                <iframe src="{{ asset('storage/' . $tagihan->upload_tugas) }}" style="width: 100%; height: 600px; border: none; border-radius: 6px;" title="Preview PDF"></iframe>
                            @else
                                <div class="text-muted" style="font-size: 0.9rem; padding: 20px; text-align: center; width: 100%;">
                                    <i class="fa-solid fa-file-circle-question" style="font-size: 2rem; margin-bottom: 8px; display: block; color: #94a3b8;"></i>
                                    Format berkas <strong>.{{ $extension }}</strong> tidak mendukung pratinjau langsung. Silakan klik tombol <strong>Unduh / Buka File</strong> di atas.
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Panel Penilaian / Status --}}
        <div style="display: flex; flex-direction: column; gap: 20px;">
            
            {{-- Form Penilaian Card --}}
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-clipboard-check"></i> Menu Pemeriksaan</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('lms.tagihan.periksa', $tagihan->id_tagihan) }}" method="POST">
                        @csrf
                        <div class="form-group mb-4">
                            <label class="form-label" style="font-weight: 600;">Nilai (0 - 100):</label>
                            <input type="number" name="nilai" value="{{ $tagihan->nilai }}" class="form-control" min="0" max="100" placeholder="Masukkan nilai...">
                        </div>
                        <div class="form-group mb-4">
                            <label class="form-label" style="font-weight: 600;">Tentukan Status Jawaban:</label>
                            <select name="status_tugas" class="form-control" required>
                                <option value="belum" {{ $tagihan->status_tugas === 'belum' ? 'selected' : '' }}>Belum Kumpul</option>
                                <option value="sudah" {{ $tagihan->status_tugas === 'sudah' ? 'selected' : '' }}>Perlu Diperiksa</option>
                                <option value="cek" {{ $tagihan->status_tugas === 'cek' ? 'selected' : '' }}>Selesai Dicek (Selesai)</option>
                            </select>
                            <small class="text-muted" style="display: block; margin-top: 6px; line-height: 1.4;">
                                Pilih status <strong>Selesai Dicek</strong> untuk menandai tugas ini sudah diperiksa dan dinilai oleh Guru.
                            </small>
                        </div>
                        <button type="submit" class="btn btn-primary w-full" style="justify-content: center; gap: 6px; padding: 10px; width: 100%;">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Status & Nilai
                        </button>
                    </form>
                </div>
            </div>

            {{-- Informasi Soal Tugas --}}
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-circle-question"></i> Soal / Instruksi Tugas</h2>
                </div>
                <div class="card-body">
                    <strong style="display: block; font-size: 0.95rem; margin-bottom: 8px; color: var(--text-primary);">
                        {{ $tagihan->tugas->judul_tugas ?? 'Tugas Terhapus' }}
                    </strong>
                    <div class="text-muted" style="font-size: 0.82rem; margin-bottom: 12px; line-height: 1.4;">
                        Oleh: {{ $tagihan->tugas->guru->nama_guru ?? '-' }}<br>
                        Tenggat Tugas: {{ $tagihan->tugas->tenggat ? \Carbon\Carbon::parse($tagihan->tugas->tenggat)->translatedFormat('d M Y') : '-' }}
                    </div>
                    
                    <div style="border-top: 1.5px solid var(--border-color); padding-top: 12px; max-height: 250px; overflow-y: auto; font-size: 0.85rem; line-height: 1.5;">
                        {!! $tagihan->tugas->deskripsi ?? 'Tidak ada rincian soal.' !!}
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@endsection
