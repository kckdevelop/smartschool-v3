@extends('layouts.app')

@section('title', 'Generate Soal AI — SmartSchool')
@section('header_title', 'Generate Soal AI')
@section('header_subtitle', 'Pembuat soal ujian otomatis berbasis Kecerdasan Buatan (LLM)')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div style="display: grid; grid-template-columns: 1fr; gap: 24px; margin-bottom: 24px;">
        <!-- INFO CARD JIKA API KEY KOSONG -->
        @php
            $sekolah = \App\Models\Sekolah::first();
            $apiConfigured = $sekolah && (
                ($sekolah->gemini_status === 'aktif' && !empty($sekolah->gemini_key) && $sekolah->gemini_quota > 0) ||
                ($sekolah->groq_status === 'aktif' && !empty($sekolah->groq_key) && $sekolah->groq_quota > 0)
            );
        @endphp

        @if(!$apiConfigured)
            <div class="card" style="background: rgba(239, 68, 68, 0.05); border: 2px dashed #ef4444; border-radius: 16px;">
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
    </div>

    <!-- MAIN GRID LAYOUT -->
    <div style="display: grid; grid-template-columns: 1.2fr 1.8fr; gap: 24px; align-items: start;" id="main-grid">
        
        <!-- LEFT PANEL: FORM PARAMETER -->
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-wand-magic-sparkles"></i> Parameter Soal</h2>
            </div>
            <div class="card-body">
                <form id="form-generate-soal" method="POST" action="{{ route('generator-soal.generate') }}">
                    @csrf

                    <div class="form-group mb-3">
                        <label class="form-label">Model LLM / AI <span class="required">*</span></label>
                        <select name="model" id="model-select" class="form-control" required {{ !$apiConfigured ? 'disabled' : '' }}>
                            @php
                                $geminiDisabled = ($sekolah->gemini_status !== 'aktif' || empty($sekolah->gemini_key) || $sekolah->gemini_quota <= 0);
                                $geminiText = $geminiDisabled ? ' (Kuota Habis / Nonaktif)' : ' (Sisa Kuota: ' . $sekolah->gemini_quota . ')';

                                $groqDisabled = ($sekolah->groq_status !== 'aktif' || empty($sekolah->groq_key) || $sekolah->groq_quota <= 0);
                                $groqText = $groqDisabled ? ' (Kuota Habis / Nonaktif)' : ' (Sisa Kuota: ' . $sekolah->groq_quota . ')';

                                $defaultModel = $sekolah->llm_model ?? 'gemini-2.5-flash';
                            @endphp
                             <optgroup label="Google Gemini" id="optgroup-gemini">
                                <option value="gemini-2.5-flash" data-provider="gemini" data-original-disabled="{{ $geminiDisabled ? 'true' : 'false' }}" {{ $geminiDisabled ? 'disabled' : '' }} {{ $defaultModel == 'gemini-2.5-flash' ? 'selected' : '' }}>gemini-2.5-flash (Gemini 2.5 Flash Terbaru - Gratis){{ $geminiText }}</option>
                                <option value="gemini-1.5-flash" data-provider="gemini" data-original-disabled="{{ $geminiDisabled ? 'true' : 'false' }}" {{ $geminiDisabled ? 'disabled' : '' }} {{ $defaultModel == 'gemini-1.5-flash' ? 'selected' : '' }}>gemini-1.5-flash (Gemini 1.5 Flash - Gratis){{ $geminiText }}</option>
                                <option value="gemini-1.5-pro" data-provider="gemini" data-original-disabled="{{ $geminiDisabled ? 'true' : 'false' }}" {{ $geminiDisabled ? 'disabled' : '' }} {{ $defaultModel == 'gemini-1.5-pro' ? 'selected' : '' }}>gemini-1.5-pro (Gemini 1.5 Pro - Gratis){{ $geminiText }}</option>
                            </optgroup>
                            <optgroup label="Groq" id="optgroup-groq">
                                <option value="llama-3.3-70b-versatile" data-provider="groq" data-original-disabled="{{ $groqDisabled ? 'true' : 'false' }}" {{ $groqDisabled ? 'disabled' : '' }} {{ $defaultModel == 'llama-3.3-70b-versatile' ? 'selected' : '' }}>llama-3.3-70b-versatile (Llama 3.3 70B — Terbaru) ✅{{ $groqText }}</option>
                                <option value="llama-3.1-8b-instant" data-provider="groq" data-original-disabled="{{ $groqDisabled ? 'true' : 'false' }}" {{ $groqDisabled ? 'disabled' : '' }} {{ $defaultModel == 'llama-3.1-8b-instant' ? 'selected' : '' }}>llama-3.1-8b-instant (Llama 3.1 8B — Cepat) ✅{{ $groqText }}</option>
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
                        <label class="form-label">Tingkat Sasaran <span class="required">*</span></label>
                        <select name="tingkat" class="form-control" required {{ !$apiConfigured ? 'disabled' : '' }}>
                            <option value="">-- Pilih Tingkat --</option>
                            <optgroup label="SD">
                                <option value="1">Kelas 1 (SD)</option>
                                <option value="2">Kelas 2 (SD)</option>
                                <option value="3">Kelas 3 (SD)</option>
                                <option value="4">Kelas 4 (SD)</option>
                                <option value="5">Kelas 5 (SD)</option>
                                <option value="6">Kelas 6 (SD)</option>
                            </optgroup>
                            <optgroup label="SMP">
                                <option value="7">Kelas 7 (SMP)</option>
                                <option value="8">Kelas 8 (SMP)</option>
                                <option value="9">Kelas 9 (SMP)</option>
                            </optgroup>
                            <optgroup label="SMK / SMA">
                                <option value="10">Kelas 10 (SMK/SMA)</option>
                                <option value="11">Kelas 11 (SMK/SMA)</option>
                                <option value="12">Kelas 12 (SMK/SMA)</option>
                            </optgroup>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Topik / Materi Spesifik <span class="required">*</span></label>
                        <input type="text" name="topik" class="form-control" placeholder="Contoh: Fotosintesis, Persamaan Linear" required {{ !$apiConfigured ? 'disabled' : '' }}>
                    </div>

                    <div class="form-grid-2 mb-3" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label class="form-label">Semester <span class="required">*</span></label>
                            <select name="semester" class="form-control" required {{ !$apiConfigured ? 'disabled' : '' }}>
                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tingkat Kesulitan / Level Kognitif <span class="required">*</span></label>
                            <select name="kesulitan" class="form-control" required {{ !$apiConfigured ? 'disabled' : '' }}>
                                <option value="lots">LOTS (C1-C2: Mengingat &amp; Memahami)</option>
                                <option value="mots" selected>MOTS (C3-C4: Menerapkan &amp; Menganalisis)</option>
                                <option value="hots">HOTS (C5-C6: Mengevaluasi &amp; Mencipta)</option>
                                <option value="mudah">Mudah (LOTS)</option>
                                <option value="sedang">Sedang (MOTS)</option>
                                <option value="sulit">Sulit (HOTS)</option>
                                <option value="campuran">Campuran (LOTS + MOTS + HOTS)</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-grid-2 mb-3" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                        <div class="form-group">
                            <label class="form-label">Jumlah Soal <span class="required">*</span></label>
                            <input type="number" name="jumlah_soal" class="form-control" min="1" max="50" value="5" required {{ !$apiConfigured ? 'disabled' : '' }}>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tipe Soal <span class="required">*</span> <span style="font-size:0.75em;color:var(--text-secondary)">(pilih minimal 1)</span></label>
                            <div style="display: flex; flex-direction: column; gap: 8px; padding-top: 6px;">
                                <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; font-size: 0.88rem; cursor: pointer;">
                                    <input type="checkbox" name="tipe_soal[]" value="pilihan_ganda" checked {{ !$apiConfigured ? 'disabled' : '' }}
                                        style="width:16px;height:16px;accent-color:#0d9488;cursor:pointer;">
                                    <span>Pilihan Ganda (A–E)</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; font-size: 0.88rem; cursor: pointer;">
                                    <input type="checkbox" name="tipe_soal[]" value="essay" {{ !$apiConfigured ? 'disabled' : '' }}
                                        style="width:16px;height:16px;accent-color:#0d9488;cursor:pointer;">
                                    <span>Essay / Uraian</span>
                                </label>
                                <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; font-size: 0.88rem; cursor: pointer;">
                                    <input type="checkbox" name="tipe_soal[]" value="benar_salah" {{ !$apiConfigured ? 'disabled' : '' }}
                                        style="width:16px;height:16px;accent-color:#0d9488;cursor:pointer;">
                                    <span>Benar – Salah</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="form-label">Kompetensi Dasar (KD) <span style="font-size:0.8em;color:var(--text-secondary)">(opsional)</span></label>
                        <textarea name="kompetensi_dasar" class="form-control" rows="2" placeholder="Contoh: 3.4 Menganalisis hubungan antara struktur jaringan penyusun organ ..." {{ !$apiConfigured ? 'disabled' : '' }}></textarea>
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label">Indikator Pencapaian Kompetensi (IPK) <span style="font-size:0.8em;color:var(--text-secondary)">(opsional)</span></label>
                        <textarea name="indikator" class="form-control" rows="2" placeholder="Contoh: Siswa dapat menjelaskan proses fotosintesis dan faktor-faktor yang mempengaruhinya ..." {{ !$apiConfigured ? 'disabled' : '' }}></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100" id="btn-submit-generate" style="width: 100%; justify-content: center; height: 42px; display: flex; align-items: center; gap: 8px;" {{ !$apiConfigured ? 'disabled' : '' }}>
                        <i class="fa-solid fa-paper-plane"></i> Mulai Generate Soal
                    </button>
                </form>
            </div>
        </div>

        <!-- RIGHT PANEL: GENERATION RESULT OR HISTORY LIST -->
        <div style="display: flex; flex-direction: column; gap: 24px;">
            
            <!-- AJAX RESULT CONTAINER (DYNAMIC) -->
            <div class="card" id="card-result" style="display: none;">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                    <h2 class="card-title" style="margin: 0;"><i class="fa-solid fa-circle-check text-success"></i> Hasil Generate AI</h2>
                    <div style="display: flex; gap: 8px;">
                        <button type="button" class="btn btn-secondary btn-sm" id="btn-copy-result" style="font-size: 0.8rem; padding: 6px 12px;">
                            <i class="fa-solid fa-copy"></i> Salin
                        </button>
                        <a href="#" target="_blank" class="btn btn-secondary btn-sm" id="btn-print-result" style="font-size: 0.8rem; padding: 6px 12px;">
                            <i class="fa-solid fa-print"></i> Cetak
                        </a>
                        <button type="button" class="btn btn-primary btn-sm" id="btn-tugas-result" style="font-size: 0.8rem; padding: 6px 12px; background: #0d9488;">
                            <i class="fa-solid fa-file-signature"></i> Jadikan Tugas
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div id="questions-render-area" style="display: flex; flex-direction: column; gap: 16px;">
                        <!-- Rendered Questions Go Here -->
                    </div>
                </div>
            </div>

            <!-- LOADING SKELETON -->
            <div class="card" id="card-loading" style="display: none;">
                <div class="card-body text-center" style="padding: 60px 24px;">
                    <div style="margin-bottom: 24px; position: relative; width: 64px; height: 64px; margin: 0 auto 20px;">
                        <div style="box-sizing: border-box; display: block; position: absolute; width: 64px; height: 64px; border: 6px solid #0d9488; border-radius: 50%; animation: lds-ring 1.2s cubic-bezier(0.5, 0, 0.5, 1) infinite; border-color: #0d9488 transparent transparent transparent;"></div>
                    </div>
                    <h3 id="loading-status-text" style="font-size: 1.15rem; font-weight: 700; margin-bottom: 8px; color: var(--text-primary);">Menghubungi Engine AI...</h3>
                    <p style="color: var(--text-muted, #888); font-size: 0.88rem; margin: 0;">Proses ini membutuhkan waktu sekitar 10-30 detik tergantung kompleksitas soal.</p>
                </div>
            </div>

            <!-- BANK SOAL / RIWAYAT GENERATE TABLE -->
            <div class="card" id="card-history">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-database"></i> Bank Soal AI (Riwayat)</h2>
                </div>
                <div class="card-body" style="padding: 0;">
                    <div class="table-responsive">
                        <table class="table" style="width: 100%; margin-bottom: 0; border-collapse: collapse;">
                            <thead>
                                <tr style="background: rgba(0,0,0,0.02); text-align: left; border-bottom: 2px solid #e2e8f0;">
                                    <th style="padding: 14px 18px; font-weight: 700; font-size: 0.85rem; color: var(--text-secondary);">Tanggal</th>
                                    <th style="padding: 14px 18px; font-weight: 700; font-size: 0.85rem; color: var(--text-secondary);">Mapel & Topik</th>
                                    <th style="padding: 14px 18px; font-weight: 700; font-size: 0.85rem; color: var(--text-secondary);">Detail</th>
                                    <th style="padding: 14px 18px; font-weight: 700; font-size: 0.85rem; color: var(--text-secondary); text-align: center;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($history as $item)
                                    <tr style="border-bottom: 1px solid #edf2f7; vertical-align: middle;">
                                        <td style="padding: 14px 18px; font-size: 0.85rem; color: var(--text-secondary);">
                                            {{ $item->created_at->format('d/m/Y') }}
                                            <span style="font-size: 0.72rem; display: block; opacity: 0.7;">{{ $item->created_at->format('H:i') }}</span>
                                        </td>
                                        <td style="padding: 14px 18px;">
                                            <div style="font-weight: 700; font-size: 0.88rem; color: var(--text-primary);">
                                                {{ $item->mapel->nama_mapel ?? 'Semua Mapel' }}
                                            </div>
                                            <div style="font-size: 0.82rem; color: var(--text-secondary); max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $item->topik }}">
                                                {{ $item->topik }}
                                            </div>
                                        </td>
                                        <td style="padding: 14px 18px; font-size: 0.82rem;">
                                            <span style="font-weight: 600; color: #3b82f6;">Kelas {{ $item->kelas->tingkat ?? '—' }}</span>
                                            <div style="display: flex; gap: 6px; margin-top: 4px; flex-wrap: wrap;">
                                                <span class="badge" style="background: rgba(13,148,136,0.1); color: #0d9488; font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; font-weight: 600;">
                                                    {{ $item->tipe_soal === 'pilihan_ganda' ? 'PilGan' : 'Essay' }}
                                                </span>
                                                <span class="badge" style="background: rgba(234,179,8,0.1); color: #854d0e; font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; font-weight: 600;">
                                                     {{ in_array(strtolower($item->kesulitan), ['lots', 'mots', 'hots']) ? strtoupper($item->kesulitan) : ucfirst($item->kesulitan) }}
                                                </span>
                                                <span class="badge" style="background: rgba(59,130,246,0.1); color: #1d4ed8; font-size: 0.7rem; padding: 2px 6px; border-radius: 4px; font-weight: 600;">
                                                    {{ $item->jumlah_soal }} Soal
                                                </span>
                                            </div>
                                        </td>
                                        <td style="padding: 14px 18px; text-align: center;">
                                            <div style="display: flex; gap: 6px; justify-content: center;">
                                                <a href="{{ route('generator-soal.show', $item->id_riwayat) }}" class="btn btn-secondary btn-sm" style="padding: 6px 10px; font-size: 0.75rem;" title="Lihat Soal">
                                                    <i class="fa-solid fa-eye"></i>
                                                </a>
                                                <button type="button" class="btn btn-danger btn-sm" style="padding: 6px 10px; font-size: 0.75rem;" onclick="confirmDeleteHistory('{{ route('generator-soal.destroy', $item->id_riwayat) }}')" title="Hapus Riwayat">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center" style="padding: 40px 18px; text-align: center; color: var(--text-muted, #888); font-size: 0.9rem;">
                                            <i class="fa-regular fa-folder-open" style="font-size: 2.5rem; display: block; margin-bottom: 12px; opacity: 0.5;"></i>
                                            Belum ada riwayat generate soal.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($history->hasPages())
                        <div style="padding: 16px;">
                            {{ $history->links('pagination::bootstrap-5') }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<!-- POP-UP MODAL: JADIKAN TUGAS BARU -->
<div class="modal-overlay" id="modal-buat-tugas" style="z-index: 1050;">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3>Jadikan Tugas Baru</h3>
            <button onclick="closeModal('modal-buat-tugas')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body">
            <form id="form-buat-tugas">
                @csrf
                <input type="hidden" name="id_riwayat" id="modal_id_riwayat">
                
                <div class="form-group mb-4">
                    <label class="form-label">Judul Tugas <span class="required">*</span></label>
                    <input type="text" name="judul_tugas" id="modal_judul_tugas" class="form-control" placeholder="Contoh: Tugas Fotosintesis Bab 2" required max="50">
                    <small class="form-hint" style="color: var(--text-muted, #888); font-size: 0.82em; display: block; margin-top: 4px;">
                        Maksimum 50 karakter. Deskripsi tugas akan diisi dengan soal yang telah digenerate secara otomatis.
                    </small>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label">Pasangkan ke Kelas <span class="required">*</span></label>
                    <select name="id_kelas" id="modal_id_kelas" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}" data-tingkat="{{ $k->tingkat }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-actions" style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modal-buat-tugas')">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btn-save-tugas" style="background: #0d9488; border-color: #0d9488;">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Sebagai Tugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* loading ring */
@keyframes lds-ring {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

/* Collapsible Card Question Styles */
.q-card {
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #fff;
    overflow: hidden;
}
.q-card-header {
    background: #f8fafc;
    padding: 12px 16px;
    font-weight: 700;
    font-size: 0.9rem;
    color: var(--text-primary);
    cursor: pointer;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #edf2f7;
}
.q-card-body {
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}
.q-pilihan-list {
    list-style-type: none;
    padding-left: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 8px;
}
.q-pilihan-item {
    display: flex;
    gap: 8px;
    padding: 8px 12px;
    background: #f1f5f9;
    border-radius: 8px;
    font-size: 0.88rem;
    color: var(--text-secondary);
}
.q-pilihan-badge {
    background: #e2e8f0;
    color: var(--text-primary);
    font-weight: 800;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    font-size: 0.8rem;
}
.toggle-panel-btn {
    background: transparent;
    border: none;
    cursor: pointer;
    font-weight: 700;
    font-size: 0.8rem;
    color: #3b82f6;
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid rgba(59,130,246,0.3);
    align-self: flex-start;
}
.toggle-panel-btn:hover {
    background: rgba(59,130,246,0.05);
}
.answer-panel {
    background: rgba(16,185,129,0.04);
    border-left: 4px solid #10b981;
    padding: 12px 16px;
    border-radius: 0 8px 8px 0;
    font-size: 0.88rem;
}
</style>

<script>
    // Gambar visual disabled.


    let generatedQuestions = [];
    let currentHistoryId = null;
    let loadingTimer = null;
    let loadingSteps = [
        "Menghubungi Engine AI...",
        "Mengirim konteks materi pelajaran...",
        "Menyusun butir-butir soal...",
        "Memformulasikan opsi pilihan jawaban...",
        "Menentukan kunci jawaban...",
        "Menyusun pembahasan soal...",
        "Memformat keluaran JSON..."
    ];
    let stepIndex = 0;

    // Form generate submit
    document.getElementById('form-generate-soal').addEventListener('submit', function(e) {
        e.preventDefault();

        // Validasi minimal 1 tipe soal dipilih
        const tipeSoalChecked = document.querySelectorAll('input[name="tipe_soal[]"]:checked');
        if (tipeSoalChecked.length === 0) {
            alert('Pilih minimal satu tipe soal!');
            return;
        }

        const form = this;
        const formData = new FormData(form);
        const submitBtn = document.getElementById('btn-submit-generate');
        const loadingCard = document.getElementById('card-loading');
        const resultCard = document.getElementById('card-result');
        const historyCard = document.getElementById('card-history');
        const statusText = document.getElementById('loading-status-text');

        // Toggle visibility
        resultCard.style.display = 'none';
        loadingCard.style.display = 'block';
        submitBtn.disabled = true;
        
        // Reset loading step indicator
        stepIndex = 0;
        statusText.innerText = loadingSteps[0];
        clearInterval(loadingTimer);
        loadingTimer = setInterval(() => {
            if (stepIndex < loadingSteps.length - 1) {
                stepIndex++;
                statusText.innerText = loadingSteps[stepIndex];
            }
        }, 3000);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            clearInterval(loadingTimer);
            loadingCard.style.display = 'none';
            submitBtn.disabled = false;

            if (res.status === 200 && res.body.success) {
                generatedQuestions = res.body.data;
                currentHistoryId = res.body.history_id;
                // Gunakan array tipe soal yang dipilih
                const selectedTypes = Array.from(tipeSoalChecked).map(cb => cb.value);
                renderQuestions(res.body.data, selectedTypes.length === 1 ? selectedTypes[0] : 'campuran');
                
                // Show result card
                resultCard.style.display = 'block';

                // Configure action buttons
                document.getElementById('btn-print-result').href = `/generator-soal/history/${currentHistoryId}?print=1`;
                
                // Show success alert
                alert('Soal berhasil digenerate oleh AI dan disimpan di Bank Soal!');
                
                // Refresh page after a while to reload history or just use page reload to keep it simple, 
                // but wait, let's keep the screen open so the teacher can see it first. 
                // The teacher can manually reload to see it in history, or we can reload when they close/finish.
            } else {
                alert(res.body.message || 'Terjadi kesalahan saat memproses permintaan.');
            }
        })
        .catch(err => {
            clearInterval(loadingTimer);
            loadingCard.style.display = 'none';
            submitBtn.disabled = false;
            console.error('Error generating questions:', err);
            alert('Gagal menghubungi server. Silakan coba kembali.');
        });
    });

    // Render questions inside the result card
    function renderQuestions(questions, type) {
        const area = document.getElementById('questions-render-area');
        area.innerHTML = '';

        questions.forEach((q, index) => {
            const card = document.createElement('div');
            card.className = 'q-card';
            
            // Header
            const header = document.createElement('div');
            header.className = 'q-card-header';
            header.innerHTML = `<span>Soal #${q.no || (index + 1)}</span> <i class="fa-solid fa-chevron-down"></i>`;
            
            // Body
            const body = document.createElement('div');
            body.className = 'q-card-body';
            
            // Question text
            const qText = document.createElement('p');
            qText.style.fontWeight = '600';
            qText.style.fontSize = '0.95rem';
            qText.style.margin = '0 0 10px 0';
            qText.innerText = q.pertanyaan;
            body.appendChild(qText);

            // Rendering visual gambar dihapus.


            // If multiple choice
            if (type === 'pilihan_ganda' && q.pilihan) {
                const list = document.createElement('ul');
                list.className = 'q-pilihan-list';
                
                Object.keys(q.pilihan).forEach(key => {
                    const li = document.createElement('li');
                    li.className = 'q-pilihan-item';
                    
                    const badge = document.createElement('span');
                    badge.className = 'q-pilihan-badge';
                    badge.innerText = key;

                    const txt = document.createElement('span');
                    txt.innerText = q.pilihan[key];

                    li.appendChild(badge);
                    li.appendChild(txt);
                    list.appendChild(li);
                });
                body.appendChild(list);
            }

            // If benar-salah type — show badge options
            if (type === 'benar_salah') {
                const optionDiv = document.createElement('div');
                optionDiv.style.cssText = 'display:flex;gap:10px;margin-bottom:8px;';
                optionDiv.innerHTML = `
                    <span style="background:#dcfce7;color:#15803d;padding:4px 14px;border-radius:20px;font-weight:600;font-size:0.88rem;border:1px solid #86efac;">✓ Benar</span>
                    <span style="background:#fee2e2;color:#b91c1c;padding:4px 14px;border-radius:20px;font-weight:600;font-size:0.88rem;border:1px solid #fca5a5;">✗ Salah</span>
                `;
                body.appendChild(optionDiv);
            }

            // Key answer toggle button
            const toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'toggle-panel-btn';
            toggleBtn.innerHTML = `<i class="fa-regular fa-lightbulb"></i> Tampilkan Kunci & Pembahasan`;
            body.appendChild(toggleBtn);

            // Answer & explanation panel (hidden by default)
            const answerPanel = document.createElement('div');
            answerPanel.className = 'answer-panel';
            answerPanel.style.display = 'none';
            
            let keyText = q.kunci_jawaban || '-';
            let discussText = q.pembahasan || 'Tidak ada pembahasan.';
            
            answerPanel.innerHTML = `
                <div style="font-weight: 700; margin-bottom: 6px; color: #0f766e;">Kunci Jawaban: <span style="background: #10b981; color:#fff; padding: 2px 8px; border-radius: 4px; font-weight:800;">${keyText}</span></div>
                <div style="line-height: 1.5; color: var(--text-secondary);"><strong style="color:var(--text-primary);">Pembahasan:</strong> ${discussText}</div>
            `;
            body.appendChild(answerPanel);

            // Click listener for key toggle
            toggleBtn.addEventListener('click', function() {
                if (answerPanel.style.display === 'none') {
                    answerPanel.style.display = 'block';
                    toggleBtn.innerHTML = `<i class="fa-solid fa-lightbulb"></i> Sembunyikan Kunci & Pembahasan`;
                } else {
                    answerPanel.style.display = 'none';
                    toggleBtn.innerHTML = `<i class="fa-regular fa-lightbulb"></i> Tampilkan Kunci & Pembahasan`;
                }
            });

            card.appendChild(header);
            card.appendChild(body);
            area.appendChild(card);
        });
    }

    // Copy to clipboard
    document.getElementById('btn-copy-result').addEventListener('click', function() {
        if (generatedQuestions.length === 0) return;

        let textOutput = "";
        generatedQuestions.forEach((q, idx) => {
            textOutput += `Soal #${q.no || (idx + 1)}\n`;
            textOutput += `${q.pertanyaan}\n`;
            
            if (q.pilihan) {
                Object.keys(q.pilihan).forEach(key => {
                    textOutput += `${key}. ${q.pilihan[key]}\n`;
                });
            }
            
            textOutput += `Kunci Jawaban: ${q.kunci_jawaban || '-'}\n`;
            textOutput += `Pembahasan: ${q.pembahasan || '-'}\n\n`;
        });

        navigator.clipboard.writeText(textOutput)
            .then(() => alert('Soal berhasil disalin ke clipboard!'))
            .catch(err => alert('Gagal menyalin soal: ' . err));
    });

    // Make task trigger
    document.getElementById('btn-tugas-result').addEventListener('click', function() {
        if (!currentHistoryId) return;
        
        document.getElementById('modal_id_riwayat').value = currentHistoryId;
        
        // Auto fill task title
        const mapelSelect = document.querySelector('select[name="id_mapel"]');
        const selectedMapel = mapelSelect.options[mapelSelect.selectedIndex].text;
        const topicInput = document.querySelector('input[name="topik"]').value;
        const tingkatSelect = document.querySelector('select[name="tingkat"]');
        const selectedTingkat = tingkatSelect.value;
        
        document.getElementById('modal_judul_tugas').value = `Tugas ${selectedMapel} - ${topicInput}`.substring(0, 50);

        // Filter kelas dropdown options by selectedTingkat
        const selectKelas = document.getElementById('modal_id_kelas');
        Array.from(selectKelas.options).forEach(opt => {
            if (opt.value === "") {
                opt.style.display = "block";
            } else {
                const t = opt.getAttribute('data-tingkat');
                if (t === selectedTingkat) {
                    opt.style.display = "block";
                } else {
                    opt.style.display = "none";
                }
            }
        });
        selectKelas.value = ""; // Reset selection
        
        openModal('modal-buat-tugas');
    });

    // Submit task form
    document.getElementById('form-buat-tugas').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const submitBtn = document.getElementById('btn-save-tugas');
        submitBtn.disabled = true;

        fetch('{{ route("generator-soal.buat-tugas") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json().then(data => ({ status: response.status, body: data })))
        .then(res => {
            submitBtn.disabled = false;
            closeModal('modal-buat-tugas');
            
            if (res.status === 200 && res.body.success) {
                alert(res.body.message);
            } else {
                alert(res.body.message || 'Gagal menyimpan tugas.');
            }
        })
        .catch(err => {
            submitBtn.disabled = false;
            closeModal('modal-buat-tugas');
            console.error('Error saving task:', err);
            alert('Terjadi kesalahan koneksi.');
        });
    });

    // Delete riwayat
    function confirmDeleteHistory(deleteUrl) {
        confirmDelete(deleteUrl, 'Apakah Anda yakin ingin menghapus riwayat generate soal ini?');
    }

    // Modal Control functions
    function openModal(id) {
        document.getElementById(id).classList.add('open');
    }
    
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
    }
</script>
@endsection
