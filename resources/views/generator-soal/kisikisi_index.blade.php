@extends('layouts.app')

@section('title', 'Generate Kisi-Kisi AI — SmartSchool')
@section('header_title', 'Generate Kisi-Kisi Penilaian AI')
@section('header_subtitle', 'Pembuat kisi-kisi penilaian otomatis berbasis Kecerdasan Buatan (LLM)')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- API KEY WARNING --}}
    @php
        $sekolah = \App\Models\Sekolah::first();
        $apiConfigured = $sekolah && (
            ($sekolah->gemini_status === 'aktif' && !empty($sekolah->gemini_key) && $sekolah->gemini_quota > 0) ||
            ($sekolah->groq_status === 'aktif' && !empty($sekolah->groq_key) && $sekolah->groq_quota > 0)
        );
    @endphp

    @if(!$apiConfigured)
    <div class="card" style="background: rgba(239,68,68,0.05); border: 2px dashed #ef4444; border-radius: 16px; margin-bottom: 24px;">
        <div class="card-body" style="display: flex; align-items: center; gap: 20px; padding: 24px;">
            <div style="background: #ef4444; color: #fff; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; flex-shrink: 0;">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <h3 style="margin: 0 0 6px 0; color: #b91c1c; font-weight: 700;">Konfigurasi LLM Tidak Siap / Kuota Habis!</h3>
                <p style="margin: 0; font-size: 0.92rem; color: var(--text-secondary); line-height: 1.5;">
                    Pastikan Anda memiliki setidaknya satu provider (Gemini/Groq) yang berstatus <strong>Aktif</strong> dengan API Key valid dan <strong>kuota sisa > 0</strong>. Silakan buka menu 
                    <a href="{{ route('generator-soal.pengaturan') }}" style="color: #0d9488; font-weight: 600; text-decoration: underline;">Pengaturan LLM</a> 
                    untuk memperbarui pengaturan atau menambah kuota.
                </p>
            </div>
        </div>
    </div>
    @endif

    {{-- MAIN GRID --}}
    <div style="display: grid; grid-template-columns: 1fr 1.6fr; gap: 24px; align-items: start;">

        {{-- LEFT: FORM --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-table-list"></i> Parameter Kisi-Kisi</h2>
            </div>
            <div class="card-body">
                <form id="form-generate-kisikisi" method="POST" action="{{ route('generator-soal.kisikisi.generate') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label class="form-label">Model LLM / AI <span class="required">*</span></label>
                        <select name="model" class="form-control" required {{ !$apiConfigured ? 'disabled' : '' }}>
                            @php
                                $geminiDisabled = ($sekolah->gemini_status !== 'aktif' || empty($sekolah->gemini_key) || $sekolah->gemini_quota <= 0);
                                $geminiText = $geminiDisabled ? ' (Kuota Habis / Nonaktif)' : ' (Sisa Kuota: ' . $sekolah->gemini_quota . ')';

                                $groqDisabled = ($sekolah->groq_status !== 'aktif' || empty($sekolah->groq_key) || $sekolah->groq_quota <= 0);
                                $groqText = $groqDisabled ? ' (Kuota Habis / Nonaktif)' : ' (Sisa Kuota: ' . $sekolah->groq_quota . ')';

                                $defaultModel = $sekolah->llm_model ?? 'gemini-2.5-flash';
                            @endphp
                            <optgroup label="Google Gemini">
                                <option value="gemini-2.5-flash" {{ $geminiDisabled ? 'disabled' : '' }} {{ $defaultModel == 'gemini-2.5-flash' ? 'selected' : '' }}>gemini-2.5-flash (Gemini 2.5 Flash Terbaru - Gratis){{ $geminiText }}</option>
                                <option value="gemini-1.5-flash" {{ $geminiDisabled ? 'disabled' : '' }} {{ $defaultModel == 'gemini-1.5-flash' ? 'selected' : '' }}>gemini-1.5-flash (Gemini 1.5 Flash - Gratis){{ $geminiText }}</option>
                                <option value="gemini-1.5-pro" {{ $geminiDisabled ? 'disabled' : '' }} {{ $defaultModel == 'gemini-1.5-pro' ? 'selected' : '' }}>gemini-1.5-pro (Gemini 1.5 Pro - Gratis){{ $geminiText }}</option>
                            </optgroup>
                            <optgroup label="Groq (LPU Inference — Gratis)">
                                <option value="llama-3.3-70b-versatile" {{ $groqDisabled ? 'disabled' : '' }} {{ $defaultModel == 'llama-3.3-70b-versatile' ? 'selected' : '' }}>llama-3.3-70b-versatile (Llama 3.3 70B — Terbaru) ✅{{ $groqText }}</option>
                                <option value="llama-3.1-8b-instant" {{ $groqDisabled ? 'disabled' : '' }} {{ $defaultModel == 'llama-3.1-8b-instant' ? 'selected' : '' }}>llama-3.1-8b-instant (Llama 3.1 8B — Cepat) ✅{{ $groqText }}</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Guru Pembuat <span class="required">*</span></label>
                        <select name="id_guru" class="form-control" required {{ !$apiConfigured ? 'disabled' : '' }}>
                            <option value="">-- Pilih Guru --</option>
                            @foreach($gurus as $g)
                                <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Mata Pelajaran <span class="required">*</span></label>
                        <select name="id_mapel" class="form-control" required {{ !$apiConfigured ? 'disabled' : '' }}>
                            <option value="">-- Pilih Mata Pelajaran --</option>
                            @foreach($mapels as $m)
                                <option value="{{ $m->id_mapel }}">{{ $m->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Kelas <span class="required">*</span></label>
                        <select name="id_kelas" class="form-control" required {{ !$apiConfigured ? 'disabled' : '' }}>
                            <option value="">-- Pilih Kelas --</option>
                            @for($i = 1; $i <= 12; $i++)
                                @php
                                    $kObj = $kelas->firstWhere('tingkat', $i);
                                @endphp
                                @if($kObj)
                                    <option value="{{ $kObj->id_kelas }}">Kelas {{ $i }}</option>
                                @endif
                            @endfor
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;" class="mb-3">
                        <div class="form-group">
                            <label class="form-label">Semester <span class="required">*</span></label>
                            <select name="semester" class="form-control" required {{ !$apiConfigured ? 'disabled' : '' }}>
                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Jenis Penilaian <span class="required">*</span></label>
                            <select name="jenis_penilaian" class="form-control" required {{ !$apiConfigured ? 'disabled' : '' }}>
                                <option value="PTS">PTS (Penilaian Tengah Semester)</option>
                                <option value="PAS">PAS (Penilaian Akhir Semester)</option>
                                <option value="UAS">UAS (Ujian Akhir Sekolah)</option>
                                <option value="UTS">UTS (Ujian Tengah Semester)</option>
                                <option value="Harian">Ulangan Harian</option>
                                <option value="Akhir Semester">Akhir Semester</option>
                            </select>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;" class="mb-3">
                        <div class="form-group">
                            <label class="form-label">Tahun Pelajaran <span class="required">*</span></label>
                            <input type="text" name="tahun_pelajaran" class="form-control" value="2026/2027" placeholder="2026/2027" required {{ !$apiConfigured ? 'disabled' : '' }}>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Alokasi Waktu (menit) <span class="required">*</span></label>
                            <input type="number" name="alokasi_waktu" class="form-control" min="15" max="240" value="90" required {{ !$apiConfigured ? 'disabled' : '' }}>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 12px;" class="mb-3">
                        <div class="form-group">
                            <label class="form-label">Jumlah Soal <span class="required">*</span></label>
                            <input type="number" name="jumlah_soal" class="form-control" min="5" max="100" value="20" required {{ !$apiConfigured ? 'disabled' : '' }}>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipe/Jenis Soal (Pilih minimal 1) <span class="required">*</span></label>
                            <div style="display: flex; flex-direction: column; gap: 6px; padding-top: 4px;">
                                <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; font-size: 0.88rem; cursor: pointer; color: var(--text-primary);">
                                    <input type="checkbox" name="tipe_soal[]" value="pilihan_ganda" checked {{ !$apiConfigured ? 'disabled' : '' }}>
                                    Pilihan Ganda
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; font-size: 0.88rem; cursor: pointer; color: var(--text-primary);">
                                    <input type="checkbox" name="tipe_soal[]" value="essay" {{ !$apiConfigured ? 'disabled' : '' }}>
                                    Uraian / Essay
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; font-size: 0.88rem; cursor: pointer; color: var(--text-primary);">
                                    <input type="checkbox" name="tipe_soal[]" value="benar_salah" {{ !$apiConfigured ? 'disabled' : '' }}>
                                    Benar-Salah
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Kurikulum <span class="required">*</span></label>
                        <select name="kurikulum" class="form-control" required {{ !$apiConfigured ? 'disabled' : '' }}>
                            <option value="Merdeka">Kurikulum Merdeka</option>
                            <option value="K13">Kurikulum 2013 (K13)</option>
                            <option value="KTSP">KTSP</option>
                        </select>
                    </div>

                    <button type="submit" id="btn-submit-kisikisi" class="btn btn-primary w-100"
                        style="width: 100%; justify-content: center; height: 42px; display: flex; align-items: center; gap: 8px;"
                        {{ !$apiConfigured ? 'disabled' : '' }}>
                        <i class="fa-solid fa-table-list"></i> Generate Kisi-Kisi AI
                    </button>
                </form>
            </div>
        </div>

        {{-- RIGHT: LOADING + RESULT --}}
        <div style="display: flex; flex-direction: column; gap: 24px;">

            {{-- LOADING CARD --}}
            <div class="card" id="card-loading-kk" style="display: none;">
                <div class="card-body" style="padding: 40px; text-align: center;">
                    <div style="width: 64px; height: 64px; border-radius: 50%; background: linear-gradient(135deg, #059669, #0d9488); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 1.8rem; color: white; animation: pulse 1.5s infinite;">
                        <i class="fa-solid fa-gears"></i>
                    </div>
                    <h3 style="margin: 0 0 8px; font-size: 1.1rem; font-weight: 700;">AI sedang menyusun kisi-kisi...</h3>
                    <p id="kk-loading-status" style="margin: 0; color: var(--text-secondary); font-size: 0.88rem;">Menghubungi Engine AI...</p>
                    <div style="margin-top: 20px; background: var(--border-color); border-radius: 4px; height: 4px; overflow: hidden;">
                        <div style="height: 100%; background: linear-gradient(90deg, #059669, #0d9488); animation: progress-bar 4s ease-in-out infinite;"></div>
                    </div>
                </div>
            </div>

            {{-- RESULT TABLE --}}
            <div class="card" id="card-result-kk" style="display: none;">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <h2 class="card-title" style="margin: 0;"><i class="fa-solid fa-circle-check text-success"></i> Kisi-Kisi Penilaian</h2>
                    <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                        <a id="btn-print-kk" href="#" target="_blank" class="btn btn-sm" style="background:#e0f2fe;color:#0369a1;border:none;padding:6px 12px;border-radius:6px;font-size:0.82rem;display:flex;align-items:center;gap:6px;text-decoration:none;">
                            <i class="fa-solid fa-print"></i> Cetak
                        </a>
                        <button id="btn-add-row-kk" type="button" class="btn btn-sm" style="background:#d1fae5;color:#065f46;border:none;padding:6px 12px;border-radius:6px;font-size:0.82rem;display:flex;align-items:center;gap:6px;">
                            <i class="fa-solid fa-plus"></i> Tambah Baris
                        </button>
                    </div>
                </div>
                <div class="card-body" style="padding: 0; overflow-x: auto;">
                    <table id="kk-table" style="width: 100%; border-collapse: collapse; font-size: 0.83rem;">
                        <thead>
                            <tr style="background: linear-gradient(135deg, #059669, #0d9488); color: white;">
                                <th style="padding: 12px 8px; text-align: center; width: 40px; white-space: nowrap;">No</th>
                                <th style="padding: 12px 8px; text-align: left; min-width: 180px;">Kompetensi Dasar</th>
                                <th style="padding: 12px 8px; text-align: left; min-width: 130px;">Materi Pokok</th>
                                <th style="padding: 12px 8px; text-align: left; min-width: 160px;">Indikator</th>
                                <th style="padding: 12px 8px; text-align: center; min-width: 80px;">Level Kognitif</th>
                                <th style="padding: 12px 8px; text-align: center; min-width: 60px;">No Soal</th>
                                <th style="padding: 12px 8px; text-align: center; min-width: 90px;">Bentuk Soal</th>
                                <th style="padding: 12px 8px; text-align: center; width: 50px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="kk-tbody">
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- HISTORY TABLE --}}
            <div class="card" id="card-history-kk">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Kisi-Kisi</h2>
                </div>
                <div class="card-body" style="padding: 0;">
                    @if($history->count() > 0)
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                        <thead>
                            <tr style="background: var(--bg-secondary); border-bottom: 2px solid var(--border-color);">
                                <th style="padding: 10px 14px; text-align: left;">Mata Pelajaran</th>
                                <th style="padding: 10px 14px; text-align: left;">Kelas</th>
                                <th style="padding: 10px 14px; text-align: left;">Jenis</th>
                                <th style="padding: 10px 14px; text-align: left;">Kurikulum</th>
                                <th style="padding: 10px 14px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($history as $h)
                            <tr style="border-bottom: 1px solid var(--border-color);" class="hover-row">
                                <td style="padding: 10px 14px; font-weight: 600;">{{ $h->mapel->nama_mapel ?? '-' }}</td>
                                <td style="padding: 10px 14px;">Kelas {{ $h->kelas->tingkat ?? '-' }}</td>
                                <td style="padding: 10px 14px;">
                                    <span style="background: #dbeafe; color: #1d4ed8; padding: 2px 10px; border-radius: 20px; font-size: 0.78rem; font-weight: 600;">{{ $h->jenis_penilaian }}</span>
                                </td>
                                <td style="padding: 10px 14px; font-size: 0.82rem; color: var(--text-secondary);">{{ $h->kurikulum }} · Sem {{ $h->semester }}</td>
                                <td style="padding: 10px 14px; text-align: center;">
                                    <div style="display: flex; gap: 6px; justify-content: center;">
                                        <a href="{{ route('generator-soal.kisikisi.show', $h->id_kisikisi) }}" class="btn btn-sm" style="background:#e0f2fe;color:#0369a1;border:none;padding:5px 10px;border-radius:6px;font-size:0.8rem;text-decoration:none;">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <form method="POST" action="{{ route('generator-soal.kisikisi.destroy', $h->id_kisikisi) }}" id="form-hapus-{{ $h->id_kisikisi }}">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm" onclick="openConfirmModal('form-hapus-{{ $h->id_kisikisi }}', '{{ $h->mapel->nama_mapel ?? 'kisi-kisi ini' }}')" style="background:#fee2e2;color:#b91c1c;border:none;padding:5px 10px;border-radius:6px;font-size:0.8rem;">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div style="padding: 12px 16px;">
                        {{ $history->links() }}
                    </div>
                    @else
                    <div style="padding: 40px; text-align: center; color: var(--text-secondary);">
                        <i class="fa-solid fa-table-list" style="font-size: 2rem; margin-bottom: 12px; display: block; opacity: 0.3;"></i>
                        <p>Belum ada kisi-kisi yang dibuat. Silakan generate kisi-kisi pertama Anda!</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL KONFIRMASI HAPUS -->
    <div class="modal-overlay" id="modal-konfirmasi-hapus">
        <div class="modal" style="max-width: 420px;">
            <div class="modal-header" style="border-bottom: 1px solid var(--border-color); padding: 18px 24px;">
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div style="background: #fee2e2; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                        <i class="fa-solid fa-triangle-exclamation" style="color: #b91c1c; font-size: 1rem;"></i>
                    </div>
                    <h3 style="margin: 0; font-size: 1rem; font-weight: 700; color: var(--text-primary);">Konfirmasi Hapus</h3>
                </div>
                <button type="button" class="modal-close" onclick="closeConfirmModal()">&times;</button>
            </div>
            <div class="modal-body" style="padding: 20px 24px; text-align: center;">
                <p style="margin: 0 0 6px; font-size: 0.95rem; color: var(--text-primary);">Yakin ingin menghapus kisi-kisi</p>
                <p id="confirm-hapus-label" style="margin: 0; font-size: 1rem; font-weight: 700; color: #b91c1c;"></p>
                <p style="margin: 8px 0 0; font-size: 0.82rem; color: var(--text-secondary);">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer" style="padding: 14px 24px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeConfirmModal()" class="btn btn-secondary" style="padding: 8px 18px; font-size: 0.88rem;">Batal</button>
                <button type="button" id="btn-konfirmasi-hapus" class="btn" style="background: #b91c1c; color: #fff; border: none; padding: 8px 18px; border-radius: 8px; font-size: 0.88rem; font-weight: 600; display: flex; align-items: center; gap: 7px;">
                    <i class="fa-solid fa-trash"></i> Ya, Hapus
                </button>
            </div>
        </div>
    </div>

    <!-- MODAL TAMBAH BARIS KISI-KISI -->
    <div class="modal-overlay" id="modal-tambah-baris">
        <div class="modal modal-lg">
            <div class="modal-header">
                <h3 style="margin:0;"><i class="fa-solid fa-plus-circle text-success" style="margin-right:6px;"></i> Tambah Baris Kisi-Kisi</h3>
                <button type="button" class="modal-close" onclick="closeRowModal()">&times;</button>
            </div>
            <form id="form-tambah-baris">
                <div class="modal-body" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; text-align: left;">
                    <div class="form-group mb-3" style="grid-column: span 2;">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; margin-bottom: 6px;">Kompetensi Dasar (KD) <span class="required">*</span></label>
                        <textarea id="modal-kd" class="form-control" rows="2" required placeholder="Masukkan Kompetensi Dasar..." style="font-size: 0.85rem; padding: 8px 12px;"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; margin-bottom: 6px;">Materi Pokok <span class="required">*</span></label>
                        <input type="text" id="modal-materi" class="form-control" required placeholder="Contoh: Turunan Fungsi" style="font-size: 0.85rem; padding: 8px 12px;">
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; margin-bottom: 6px;">Level Kognitif <span class="required">*</span></label>
                        <select id="modal-level" class="form-control" required style="font-size: 0.85rem; padding: 8px 12px;">
                            <option value="C1">C1 - Mengingat</option>
                            <option value="C2">C2 - Memahami</option>
                            <option value="C3">C3 - Menerapkan</option>
                            <option value="C4">C4 - Menganalisis</option>
                            <option value="C5">C5 - Mengevaluasi</option>
                            <option value="C6">C6 - Mencipta</option>
                        </select>
                    </div>
                    <div class="form-group mb-3" style="grid-column: span 2;">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; margin-bottom: 6px;">Indikator Soal <span class="required">*</span></label>
                        <textarea id="modal-indikator" class="form-control" rows="2" required placeholder="Masukkan Indikator Pencapaian Kompetensi..." style="font-size: 0.85rem; padding: 8px 12px;"></textarea>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; margin-bottom: 6px;">Bentuk Soal <span class="required">*</span></label>
                        <select id="modal-bentuk" class="form-control" required style="font-size: 0.85rem; padding: 8px 12px;">
                            <option value="Pilihan Ganda">Pilihan Ganda</option>
                            <option value="Essay">Essay / Uraian</option>
                            <option value="Benar-Salah">Benar-Salah</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; margin-bottom: 6px;">No Soal <span class="required">*</span></label>
                        <input type="text" id="modal-nosoal" class="form-control" required placeholder="Contoh: 1-3 atau 5" style="font-size: 0.85rem; padding: 8px 12px;">
                    </div>
                </div>
                <div class="modal-footer" style="padding: 14px 24px;">
                    <button type="button" class="btn btn-secondary" onclick="closeRowModal()" style="font-size: 0.85rem; padding: 7px 15px;">Batal</button>
                    <button type="submit" class="btn btn-success" style="font-size: 0.85rem; padding: 7px 15px; background: #0d9488; border: none; color: white;"><i class="fa-solid fa-save" style="margin-right: 5px;"></i> Tambahkan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@keyframes pulse { 0%,100%{transform:scale(1);opacity:1;} 50%{transform:scale(1.08);opacity:.8;} }
@keyframes progress-bar { 0%{transform:translateX(-100%);} 100%{transform:translateX(200%);} }
.kk-editable { outline: none; border-bottom: 1px dashed transparent; min-width: 60px; }
.kk-editable:focus { border-bottom: 1px dashed #059669; background: rgba(5,150,105,0.04); border-radius: 2px; }
tr:nth-child(even) td { background: rgba(0,0,0,0.015); }
.hover-row:hover td { background: rgba(5,150,105,0.04); }
</style>

<script>
    const kkLoadingSteps = [
        "Menghubungi Engine AI...",
        "Memuat silabus mata pelajaran...",
        "Menyesuaikan dengan kurikulum yang dipilih...",
        "Memetakan Kompetensi Dasar (KD)...",
        "Menentukan level kognitif Bloom...",
        "Menyusun indikator pencapaian...",
        "Memformat tabel kisi-kisi..."
    ];
    let kkStepIdx = 0, kkTimer = null, currentKisiKisiId = null;

    document.getElementById('form-generate-kisikisi').addEventListener('submit', function(e) {
        e.preventDefault();

        // Validate checkboxes
        const checkedTypes = document.querySelectorAll('input[name="tipe_soal[]"]:checked');
        if (checkedTypes.length === 0) {
            alert('Harap pilih minimal satu tipe/jenis soal!');
            return;
        }

        const form = this;
        const formData = new FormData(form);
        const submitBtn = document.getElementById('btn-submit-kisikisi');
        const loadingCard = document.getElementById('card-loading-kk');
        const resultCard = document.getElementById('card-result-kk');
        const statusText = document.getElementById('kk-loading-status');

        resultCard.style.display = 'none';
        loadingCard.style.display = 'block';
        submitBtn.disabled = true;

        kkStepIdx = 0;
        statusText.innerText = kkLoadingSteps[0];
        clearInterval(kkTimer);
        kkTimer = setInterval(() => {
            if (kkStepIdx < kkLoadingSteps.length - 1) {
                kkStepIdx++;
                statusText.innerText = kkLoadingSteps[kkStepIdx];
            }
        }, 3000);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json().then(d => ({ status: r.status, body: d })))
        .then(res => {
            clearInterval(kkTimer);
            loadingCard.style.display = 'none';
            submitBtn.disabled = false;

            if (res.status === 200 && res.body.success) {
                currentKisiKisiId = res.body.history_id;
                renderKisiKisi(res.body.data);
                resultCard.style.display = 'block';
                document.getElementById('btn-print-kk').href = `/generator-soal/kisi-kisi/history/${currentKisiKisiId}?print=1`;
                resultCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                alert(res.body.message || 'Terjadi kesalahan saat memproses permintaan.');
            }
        })
        .catch(() => {
            clearInterval(kkTimer);
            loadingCard.style.display = 'none';
            submitBtn.disabled = false;
            alert('Gagal menghubungi server. Silakan coba kembali.');
        });
    });

    function renderKisiKisi(data) {
        const tbody = document.getElementById('kk-tbody');
        tbody.innerHTML = '';
        data.forEach((row, idx) => addRow(row, idx + 1));
    }

    function addRow(row, no) {
        const tbody = document.getElementById('kk-tbody');
        const tr = document.createElement('tr');
        tr.style.borderBottom = '1px solid var(--border-color)';

        const kd      = row.kompetensi_dasar || '';
        const materi  = row.materi_pokok     || '';
        const ind     = row.indikator        || '';
        const level   = row.level_kognitif   || '';
        const noSoal  = row.no_soal          || '';
        const bentuk  = row.bentuk_soal      || '';
        const rowNo   = row.no ?? no;

        tr.innerHTML = `
            <td style="padding:8px;text-align:center;font-weight:700;color:#059669;">${rowNo}</td>
            <td style="padding:8px;"><span contenteditable="true" class="kk-editable">${kd}</span></td>
            <td style="padding:8px;"><span contenteditable="true" class="kk-editable">${materi}</span></td>
            <td style="padding:8px;"><span contenteditable="true" class="kk-editable">${ind}</span></td>
            <td style="padding:8px;text-align:center;">
                <span contenteditable="true" class="kk-editable" style="background:#fef9c3;color:#854d0e;padding:2px 8px;border-radius:12px;font-weight:600;font-size:0.78rem;">${level}</span>
            </td>
            <td style="padding:8px;text-align:center;"><span contenteditable="true" class="kk-editable" style="font-weight:700;">${noSoal}</span></td>
            <td style="padding:8px;text-align:center;"><span contenteditable="true" class="kk-editable" style="background:#dbeafe;color:#1e40af;padding:2px 8px;border-radius:12px;font-size:0.78rem;">${bentuk}</span></td>
            <td style="padding:8px;text-align:center;">
                <button onclick="this.closest('tr').remove()" style="background:#fee2e2;color:#b91c1c;border:none;padding:4px 8px;border-radius:6px;cursor:pointer;font-size:0.8rem;"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;
        tbody.appendChild(tr);
    }

    // Modal row helpers
    function openRowModal() {
        document.getElementById('modal-tambah-baris').classList.add('active');
        document.getElementById('form-tambah-baris').reset();
    }

    function closeRowModal() {
        document.getElementById('modal-tambah-baris').classList.remove('active');
    }

    document.getElementById('form-tambah-baris').addEventListener('submit', function(e) {
        e.preventDefault();
        const tbody = document.getElementById('kk-tbody');
        const newNo = tbody.querySelectorAll('tr').length + 1;
        
        const rowData = {
            kompetensi_dasar: document.getElementById('modal-kd').value,
            materi_pokok: document.getElementById('modal-materi').value,
            indikator: document.getElementById('modal-indikator').value,
            level_kognitif: document.getElementById('modal-level').value,
            no_soal: document.getElementById('modal-nosoal').value,
            bentuk_soal: document.getElementById('modal-bentuk').value
        };
        
        addRow(rowData, newNo);
        closeRowModal();
    });

    document.getElementById('btn-add-row-kk').addEventListener('click', function() {
        openRowModal();
    });

    // ---- Confirm Delete Modal ----
    let _confirmFormId = null;

    function openConfirmModal(formId, label) {
        _confirmFormId = formId;
        document.getElementById('confirm-hapus-label').textContent = label + '?';
        document.getElementById('modal-konfirmasi-hapus').classList.add('active');
    }

    function closeConfirmModal() {
        _confirmFormId = null;
        document.getElementById('modal-konfirmasi-hapus').classList.remove('active');
    }

    document.getElementById('btn-konfirmasi-hapus').addEventListener('click', function() {
        if (_confirmFormId) {
            document.getElementById(_confirmFormId).submit();
        }
    });

    // Close on overlay click
    document.getElementById('modal-konfirmasi-hapus').addEventListener('click', function(e) {
        if (e.target === this) closeConfirmModal();
    });
</script>
@endsection
