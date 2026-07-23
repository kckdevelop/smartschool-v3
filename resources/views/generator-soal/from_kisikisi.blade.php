@extends('layouts.app')

@section('title', 'Generate Soal dari Kisi-Kisi — SmartSchool')
@section('header_title', 'Generate Soal dari Kisi-Kisi')
@section('header_subtitle', 'Buat soal ujian otomatis berbasis kisi-kisi yang sudah dibuat sebelumnya')

@section('content')
<div class="page-content">
    @include('partials.flash')

    @php
        $apiConfigured = $sekolah && (
            ($sekolah->gemini_status === 'aktif' && !empty($sekolah->gemini_key) && $sekolah->gemini_quota > 0) ||
            ($sekolah->groq_status === 'aktif' && !empty($sekolah->groq_key) && $sekolah->groq_quota > 0)
        );
        $geminiDisabled = !($sekolah && $sekolah->gemini_status === 'aktif' && !empty($sekolah->gemini_key) && $sekolah->gemini_quota > 0);
        $groqDisabled = !($sekolah && $sekolah->groq_status === 'aktif' && !empty($sekolah->groq_key) && $sekolah->groq_quota > 0);
        $geminiText = $geminiDisabled ? ' (Nonaktif)' : ' (Sisa: ' . ($sekolah->gemini_quota ?? 0) . ')';
        $groqText = $groqDisabled ? ' (Nonaktif)' : ' (Sisa: ' . ($sekolah->groq_quota ?? 0) . ')';
    @endphp

    @if(!$apiConfigured)
    <div style="background:rgba(239,68,68,.07);border:1.5px solid #fca5a5;border-radius:10px;padding:14px 18px;margin-bottom:20px;display:flex;align-items:center;gap:12px;">
        <i class="fa-solid fa-triangle-exclamation" style="color:#ef4444;font-size:1.1rem;"></i>
        <span style="font-size:.88rem;color:#b91c1c;">API Key LLM belum aktif / kuota habis. Silakan buka
            <a href="{{ route('generator-soal.pengaturan') }}" style="color:#0d9488;font-weight:600;text-decoration:underline;">Pengaturan LLM</a>.</span>
    </div>
    @endif

    <div style="display:grid;grid-template-columns:260px 1fr;gap:20px;align-items:start;">

        {{-- ===== KOLOM KIRI: FILTER ===== --}}
        <div class="card" style="position:sticky;top:80px;">
            <div class="card-header">
                <h2 class="card-title" style="font-size:.9rem;"><i class="fa-solid fa-filter"></i> Filter</h2>
            </div>
            <div class="card-body" style="padding:16px;">

                <div class="form-group" style="margin-bottom:14px;">
                    <label class="form-label" style="font-size:.8rem;font-weight:600;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.4px;">Mata Pelajaran</label>
                    <select id="sel-mapel" class="form-control" style="font-size:.88rem;">
                        <option value="">— Semua / Pilih Mapel —</option>
                        @foreach($mapels as $m)
                        <option value="{{ $m->id_mapel }}">{{ $m->nama_mapel }}</option>
                        @endforeach
                    </select>
                </div>

                <hr style="border:none;border-top:1px solid var(--border-color);margin:14px 0;">

                <p style="font-size:.78rem;color:var(--text-secondary);margin:0 0 6px;">
                    <i class="fa-solid fa-circle-info"></i>
                    Pilih mata pelajaran lalu klik salah satu kisi-kisi di daftar sebelah kanan.
                </p>
            </div>
        </div>

        {{-- ===== KOLOM KANAN ===== --}}
        <div>

            {{-- LIST KISI-KISI --}}
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                    <h2 class="card-title" style="font-size:.9rem;"><i class="fa-solid fa-list-check"></i> Daftar Kisi-Kisi</h2>
                    <span id="count-label" style="font-size:.78rem;color:var(--text-secondary);"></span>
                </div>
                <div id="kisi-list" style="padding:0;">
                    {{-- Placeholder --}}
                    <div style="text-align:center;padding:36px 20px;color:var(--text-secondary);">
                        <i class="fa-solid fa-arrow-left" style="font-size:1.4rem;margin-bottom:10px;opacity:.35;display:block;"></i>
                        <p style="margin:0;font-size:.88rem;">Pilih mata pelajaran untuk melihat kisi-kisi.</p>
                    </div>
                </div>
            </div>

            {{-- FORM GENERATE (muncul setelah pilih kisi-kisi) --}}
            <div id="form-wrap" style="display:none;">
                <div class="card">
                    <div class="card-header" style="background:linear-gradient(135deg,#d97706,#b45309);border-radius:12px 12px 0 0;">
                        <h2 class="card-title" style="color:#fff;margin:0;font-size:.9rem;">
                            <i class="fa-solid fa-wand-magic-sparkles"></i> Pengaturan Generate Soal
                        </h2>
                    </div>
                    <div class="card-body" style="padding:20px;">

                        {{-- Info kisi-kisi terpilih --}}
                        <div id="selected-info" style="background:var(--bg-secondary);border:1px solid var(--border-color);border-radius:8px;padding:12px 16px;margin-bottom:18px;display:grid;grid-template-columns:repeat(3,1fr);gap:10px;"></div>

                        <form id="form-gen">
                            @csrf
                            <input type="hidden" name="id_kisikisi" id="h-id-kisikisi">

                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">

                                <div class="form-group">
                                    <label class="form-label" style="font-size:.8rem;">Guru Penyusun *</label>
                                    <select name="id_guru" class="form-control" required style="font-size:.88rem;" {{ !$apiConfigured ? 'disabled' : '' }}>
                                        <option value="">-- Pilih Guru --</option>
                                        @foreach($gurus as $g)
                                        <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" style="font-size:.8rem;">Tingkat *</label>
                                    <select name="tingkat" id="sel-kelas" class="form-control" required style="font-size:.88rem;" {{ !$apiConfigured ? 'disabled' : '' }}>
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
                                <div class="form-group">
                                    <label class="form-label" style="font-size:.8rem;">Model AI *</label>
                                    <select name="model" id="model-select" class="form-control" required style="font-size:.88rem;" {{ !$apiConfigured ? 'disabled' : '' }}>
                                        @php
                                            $geminiDisabled = ($sekolah->gemini_status !== 'aktif' || empty($sekolah->gemini_key) || $sekolah->gemini_quota <= 0);
                                            $groqDisabled = ($sekolah->groq_status !== 'aktif' || empty($sekolah->groq_key) || $sekolah->groq_quota <= 0);
                                            $defaultModel = $sekolah->llm_model ?? 'gemini-2.5-flash';
                                        @endphp
                                        <optgroup label="Google Gemini" id="optgroup-gemini">
                                            <option value="gemini-2.5-flash" data-provider="gemini" data-original-disabled="{{ $geminiDisabled ? 'true' : 'false' }}" {{ $geminiDisabled ? 'disabled' : '' }} {{ $defaultModel == 'gemini-2.5-flash' ? 'selected' : '' }}>gemini-2.5-flash (Gemini 2.5 Flash Terbaru - Gratis)</option>
                                            <option value="gemini-1.5-flash" data-provider="gemini" data-original-disabled="{{ $geminiDisabled ? 'true' : 'false' }}" {{ $geminiDisabled ? 'disabled' : '' }} {{ $defaultModel == 'gemini-1.5-flash' ? 'selected' : '' }}>gemini-1.5-flash (Gemini 1.5 Flash - Gratis)</option>
                                            <option value="gemini-1.5-pro" data-provider="gemini" data-original-disabled="{{ $geminiDisabled ? 'true' : 'false' }}" {{ $geminiDisabled ? 'disabled' : '' }} {{ $defaultModel == 'gemini-1.5-pro' ? 'selected' : '' }}>gemini-1.5-pro (Gemini 1.5 Pro - Gratis)</option>
                                        </optgroup>
                                        <optgroup label="Groq" id="optgroup-groq">
                                            <option value="llama-3.3-70b-versatile" data-provider="groq" data-original-disabled="{{ $groqDisabled ? 'true' : 'false' }}" {{ $groqDisabled ? 'disabled' : '' }} {{ $defaultModel == 'llama-3.3-70b-versatile' ? 'selected' : '' }}>llama-3.3-70b-versatile (Llama 3.3 70B — Terbaru) ✅</option>
                                            <option value="llama-3.1-8b-instant" data-provider="groq" data-original-disabled="{{ $groqDisabled ? 'true' : 'false' }}" {{ $groqDisabled ? 'disabled' : '' }} {{ $defaultModel == 'llama-3.1-8b-instant' ? 'selected' : '' }}>llama-3.1-8b-instant (Llama 3.1 8B — Cepat) ✅</option>
                                        </optgroup>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" style="font-size:.8rem;">Tipe/Jenis Soal (Pilih minimal 1) *</label>
                                    <div style="display: flex; flex-direction: column; gap: 6px; padding-top: 4px;">
                                        <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; font-size: 0.85rem; cursor: pointer; color: var(--text-primary);">
                                            <input type="checkbox" name="tipe_soal[]" value="pilihan_ganda" checked {{ !$apiConfigured ? 'disabled' : '' }}>
                                            Pilihan Ganda
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; font-size: 0.85rem; cursor: pointer; color: var(--text-primary);">
                                            <input type="checkbox" name="tipe_soal[]" value="essay" {{ !$apiConfigured ? 'disabled' : '' }}>
                                            Uraian / Essay
                                        </label>
                                        <label style="display: flex; align-items: center; gap: 8px; font-weight: normal; font-size: 0.85rem; cursor: pointer; color: var(--text-primary);">
                                            <input type="checkbox" name="tipe_soal[]" value="benar_salah" {{ !$apiConfigured ? 'disabled' : '' }}>
                                            Benar-Salah
                                        </label>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" style="font-size:.8rem;">Jumlah Soal *</label>
                                    <input type="number" name="jumlah_soal" class="form-control" value="20" min="5" max="50" required style="font-size:.88rem;" {{ !$apiConfigured ? 'disabled' : '' }}>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" style="font-size:.8rem;">Tingkat Kesulitan / Level Kognitif *</label>
                                    <select name="kesulitan" class="form-control" required style="font-size:.88rem;" {{ !$apiConfigured ? 'disabled' : '' }}>
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

                            <button type="submit" id="btn-gen" class="btn btn-primary"
                                style="width:100%;padding:12px;font-size:.92rem;font-weight:700;border-radius:8px;display:flex;align-items:center;justify-content:center;gap:8px;background:linear-gradient(135deg,#d97706,#b45309);border:none;"
                                {{ !$apiConfigured ? 'disabled' : '' }}>
                                <i class="fa-solid fa-wand-magic-sparkles"></i> Generate Soal dari Kisi-Kisi
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- HASIL --}}
            <div id="result-area" style="display:none;margin-top:20px;"></div>

        </div>{{-- /kolom kanan --}}
    </div>
</div>

{{-- Loading overlay --}}
<div id="loading-overlay" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;padding:36px 48px;text-align:center;box-shadow:0 20px 60px rgba(0,0,0,.25);">
        <div style="width:52px;height:52px;border:4px solid #e5e7eb;border-top-color:#d97706;border-radius:50%;animation:spin 1s linear infinite;margin:0 auto 16px;"></div>
        <p style="margin:0;font-weight:700;color:#111;">AI sedang membuat soal…</p>
        <p style="margin:6px 0 0;font-size:.82rem;color:#6b7280;">Mohon tunggu 15–60 detik.</p>
    </div>
</div>

<style>
@keyframes spin { to { transform:rotate(360deg); } }

.kisi-row {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: 12px;
    padding: 12px 16px;
    border-bottom: 1px solid var(--border-color);
    cursor: pointer;
    transition: background .15s;
}
.kisi-row:last-child { border-bottom: none; }
.kisi-row:hover { background: rgba(13,148,136,.04); }
.kisi-row.selected { background: rgba(13,148,136,.08); border-left: 3px solid #0d9488; padding-left: 13px; }
.kisi-row.selected .kisi-pick-btn { background: #0d9488; color: #fff; }

.kisi-pick-btn {
    background: var(--bg-secondary);
    border: 1px solid var(--border-color);
    color: var(--text-secondary);
    padding: 5px 14px;
    border-radius: 20px;
    font-size: .75rem;
    font-weight: 700;
    cursor: pointer;
    transition: all .15s;
    white-space: nowrap;
}

.soal-item {
    border: 1px solid var(--border-color);
    border-radius: 8px;
    padding: 12px 14px;
    margin-bottom: 8px;
    background: var(--bg-secondary);
}
.pill { display:inline-block;padding:2px 10px;border-radius:20px;font-size:.73rem;font-weight:700; }
.pill-green  { background:#dcfce7;color:#15803d; }
.pill-yellow { background:#fef9c3;color:#854d0e; }
.pill-blue   { background:#dbeafe;color:#1e40af; }
</style>

<script>
const CSRF = '{{ csrf_token() }}';
let selectedKisiId = null;

document.addEventListener('DOMContentLoaded', function() {
    // Gambar visual disabled.
});

/* ── Filter Mapel ────────────────────────────────────── */
document.getElementById('sel-mapel').addEventListener('change', function () {
    const id = this.value;
    if (!id) {
        showPlaceholder();
        return;
    }
    loadKisiKisi(id);
});

function showPlaceholder() {
    document.getElementById('kisi-list').innerHTML = `
        <div style="text-align:center;padding:36px 20px;color:var(--text-secondary);">
            <i class="fa-solid fa-arrow-left" style="font-size:1.4rem;margin-bottom:10px;opacity:.35;display:block;"></i>
            <p style="margin:0;font-size:.88rem;">Pilih mata pelajaran untuk melihat kisi-kisi.</p>
        </div>`;
    document.getElementById('count-label').textContent = '';
    hideForm();
}

function loadKisiKisi(mapelId) {
    const container = document.getElementById('kisi-list');
    container.innerHTML = `<div style="text-align:center;padding:28px;color:var(--text-secondary);">
        <i class="fa-solid fa-spinner fa-spin" style="font-size:1.3rem;color:#0d9488;"></i>
        <p style="margin:8px 0 0;font-size:.85rem;">Memuat...</p>
    </div>`;
    document.getElementById('count-label').textContent = '';
    hideForm();

    fetch(`{{ route('generator-soal.from-kisikisi.list') }}?id_mapel=${mapelId}`, {
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF }
    })
    .then(r => r.json())
    .then(res => {
        if (!res.success || res.data.length === 0) {
            container.innerHTML = `<div style="text-align:center;padding:36px 20px;color:var(--text-secondary);">
                <i class="fa-solid fa-folder-open" style="font-size:1.8rem;margin-bottom:10px;opacity:.35;display:block;"></i>
                <p style="margin:0;font-size:.88rem;font-weight:600;">Belum ada kisi-kisi untuk mapel ini.</p>
                <a href="{{ route('generator-soal.kisikisi.index') }}" style="font-size:.82rem;color:#0d9488;text-decoration:underline;">
                    Buat Kisi-Kisi terlebih dahulu →
                </a>
            </div>`;
            return;
        }

        document.getElementById('count-label').textContent = res.data.length + ' kisi-kisi ditemukan';

        let html = '';
        res.data.forEach(k => {
            html += `<div class="kisi-row" id="row-${k.id}" onclick="selectKisi(${k.id}, this)" data-kisi='${JSON.stringify(k).replace(/'/g,"&#39;")}'>
                <div>
                    <p style="margin:0 0 3px;font-weight:700;font-size:.88rem;">${k.jenis_penilaian} &mdash; ${k.tahun_pelajaran}</p>
                    <p style="margin:0;font-size:.78rem;color:var(--text-secondary);">
                        ${k.kelas} &bull; Semester ${k.semester} &bull; Kurikulum ${k.kurikulum} &bull;
                        <strong>${k.jumlah_butir}</strong> butir &bull; ${k.guru}
                    </p>
                </div>
                <button class="kisi-pick-btn" type="button">Pilih</button>
            </div>`;
        });
        container.innerHTML = html;
    })
    .catch(() => {
        container.innerHTML = `<div style="text-align:center;padding:28px;color:#ef4444;font-size:.88rem;">
            <i class="fa-solid fa-circle-xmark"></i> Gagal memuat data.
        </div>`;
    });
}

/* ── Pilih Kisi-Kisi ─────────────────────────────────── */
function selectKisi(id, rowEl) {
    // Highlight
    document.querySelectorAll('.kisi-row').forEach(r => r.classList.remove('selected'));
    rowEl.classList.add('selected');

    selectedKisiId = id;
    const kisi = JSON.parse(rowEl.getAttribute('data-kisi').replace(/&#39;/g, "'"));

    // Isi hidden input
    document.getElementById('h-id-kisikisi').value = id;

    // Isi info preview
    document.getElementById('selected-info').innerHTML = `
        <div>
            <p style="margin:0;font-size:.72rem;color:var(--text-secondary);font-weight:700;text-transform:uppercase;letter-spacing:.4px;">Jenis Penilaian</p>
            <p style="margin:2px 0 0;font-weight:700;font-size:.85rem;">${kisi.jenis_penilaian}</p>
        </div>
        <div>
            <p style="margin:0;font-size:.72rem;color:var(--text-secondary);font-weight:700;text-transform:uppercase;letter-spacing:.4px;">Kelas / Semester</p>
            <p style="margin:2px 0 0;font-weight:700;font-size:.85rem;">${kisi.kelas} / Sem. ${kisi.semester}</p>
        </div>
        <div>
            <p style="margin:0;font-size:.72rem;color:var(--text-secondary);font-weight:700;text-transform:uppercase;letter-spacing:.4px;">Tahun Pelajaran</p>
            <p style="margin:2px 0 0;font-weight:700;font-size:.85rem;">${kisi.tahun_pelajaran}</p>
        </div>
        <div>
            <p style="margin:0;font-size:.72rem;color:var(--text-secondary);font-weight:700;text-transform:uppercase;letter-spacing:.4px;">Kurikulum</p>
            <p style="margin:2px 0 0;font-weight:700;font-size:.85rem;">${kisi.kurikulum}</p>
        </div>
        <div>
            <p style="margin:0;font-size:.72rem;color:var(--text-secondary);font-weight:700;text-transform:uppercase;letter-spacing:.4px;">Jumlah Butir</p>
            <p style="margin:2px 0 0;font-weight:700;font-size:.85rem;">${kisi.jumlah_butir} butir</p>
        </div>
        <div>
            <p style="margin:0;font-size:.72rem;color:var(--text-secondary);font-weight:700;text-transform:uppercase;letter-spacing:.4px;">Guru Penyusun</p>
            <p style="margin:2px 0 0;font-weight:700;font-size:.85rem;">${kisi.guru}</p>
        </div>`;

    // Pre-select kelas by tingkat
    const selKelas = document.getElementById('sel-kelas');
    if (kisi.tingkat && selKelas) {
        Array.from(selKelas.options).forEach(opt => {
            opt.selected = (opt.getAttribute('data-tingkat') == kisi.tingkat);
        });
    }

    // Pre-select guru
    const selGuru = document.querySelector('select[name="id_guru"]');
    if (kisi.id_guru && selGuru) {
        selGuru.value = kisi.id_guru;
    }

    // Pre-fill jumlah soal
    const inputJumlah = document.querySelector('input[name="jumlah_soal"]');
    if (kisi.jumlah_butir && inputJumlah) {
        inputJumlah.value = kisi.jumlah_butir;
    }

    // Pre-check tipe soal checkboxes
    const checkboxesTipe = document.querySelectorAll('input[name="tipe_soal[]"]');
    if (checkboxesTipe.length) {
        // Uncheck all first
        checkboxesTipe.forEach(cb => cb.checked = false);

        if (kisi.tipe_soal) {
            // Normalize: map stored values to checkbox values
            const normalize = v => {
                v = v.trim().toLowerCase();
                if (v === 'uraian' || v === 'essay') return 'essay';
                if (v === 'pilihan_ganda' || v === 'pilihan ganda') return 'pilihan_ganda';
                if (v === 'benar_salah' || v === 'benar-salah' || v === 'benar salah') return 'benar_salah';
                return v;
            };
            const tipeSoalValues = kisi.tipe_soal.split(',').map(normalize);
            checkboxesTipe.forEach(cb => {
                cb.checked = tipeSoalValues.includes(cb.value);
            });
        }

        // Fallback: if none matched, default ke pilihan_ganda
        const anyChecked = Array.from(checkboxesTipe).some(cb => cb.checked);
        if (!anyChecked) checkboxesTipe[0].checked = true;
    }

    // Tampilkan form
    document.getElementById('form-wrap').style.display = '';
    document.getElementById('form-wrap').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function hideForm() {
    document.getElementById('form-wrap').style.display = 'none';
    document.getElementById('result-area').style.display = 'none';
    selectedKisiId = null;
}

/* ── Submit Generate ─────────────────────────────────── */
document.getElementById('form-gen').addEventListener('submit', function (e) {
    e.preventDefault();
    if (!selectedKisiId) return;

    // Validasi: minimal 1 tipe soal diceklis
    const checkedTipes = document.querySelectorAll('input[name="tipe_soal[]"]:checked');
    if (checkedTipes.length === 0) {
        alert('Pilih minimal satu jenis/tipe soal.');
        return;
    }

    const btn = document.getElementById('btn-gen');
    const overlay = document.getElementById('loading-overlay');
    btn.disabled = true;
    overlay.style.display = 'flex';

    const tipeSoalChecked = Array.from(checkedTipes).map(cb => cb.value).join(', ');

    fetch('{{ route("generator-soal.from-kisikisi.generate") }}', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' },
        body: new FormData(this),
    })
    .then(r => r.json())
    .then(res => {
        overlay.style.display = 'none';
        btn.disabled = false;
        if (!res.success) {
            showError(res.message || 'Terjadi kesalahan.');
            return;
        }
        renderResults(res.data, res.history_id, tipeSoalChecked);
    })
    .catch(() => {
        overlay.style.display = 'none';
        btn.disabled = false;
        showError('Gagal terhubung ke server. Silakan coba lagi.');
    });
});

function showError(msg) {
    const area = document.getElementById('result-area');
    area.style.display = '';
    area.innerHTML = `<div style="background:rgba(239,68,68,.07);border:1.5px solid #fca5a5;border-radius:10px;padding:14px 18px;display:flex;align-items:center;gap:12px;">
        <i class="fa-solid fa-circle-xmark" style="color:#ef4444;font-size:1.1rem;"></i>
        <div><strong style="color:#b91c1c;">Gagal Generate Soal</strong><br>
        <span style="font-size:.85rem;color:var(--text-secondary);">${msg}</span></div>
    </div>`;
    area.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function renderResults(soalList, historyId, tipeSoal) {
    const area = document.getElementById('result-area');
    area.style.display = '';

    let html = `<div class="card">
        <div class="card-header" style="background:linear-gradient(135deg,#059669,#0d9488);border-radius:12px 12px 0 0;display:flex;align-items:center;justify-content:space-between;">
            <h2 class="card-title" style="color:#fff;margin:0;font-size:.9rem;">
                <i class="fa-solid fa-check-circle"></i> ${soalList.length} Soal Berhasil Dibuat
            </h2>
            <div style="display:flex;gap:8px;">
                <a href="/generator-soal/history/${historyId}?print=1" target="_blank"
                   style="background:rgba(255,255,255,.18);color:#fff;padding:5px 14px;border-radius:20px;text-decoration:none;font-size:.78rem;font-weight:700;display:flex;align-items:center;gap:5px;">
                   <i class="fa-solid fa-print"></i> Cetak
                </a>
                <a href="/generator-soal/history/${historyId}"
                   style="background:rgba(255,255,255,.18);color:#fff;padding:5px 14px;border-radius:20px;text-decoration:none;font-size:.78rem;font-weight:700;display:flex;align-items:center;gap:5px;">
                   <i class="fa-solid fa-eye"></i> Detail
                </a>
            </div>
        </div>
        <div class="card-body" style="padding:16px;">`;

    // Helper untuk escape HTML agar tag seperti <body> tampil sebagai teks
    const esc = str => String(str ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');

    soalList.forEach((soal, idx) => {
        const no = soal.no || (idx + 1);
        // detect actual type per soal: from soal.tipe field OR from presence of pilihan object
        const isPilihanGanda = (soal.tipe === 'pilihan_ganda') || (!soal.tipe && soal.pilihan && typeof soal.pilihan === 'object');
        const isBenarSalah  = (soal.tipe === 'benar_salah');

        html += `<div class="soal-item">
            <div style="display:flex;gap:10px;align-items:flex-start;">
                <span style="min-width:26px;height:26px;border-radius:50%;background:linear-gradient(135deg,#d97706,#b45309);color:#fff;display:flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:800;">${no}</span>
                <div style="flex:1;">
                    <p style="margin:0 0 8px;font-weight:600;font-size:.88rem;line-height:1.5;">${esc(soal.pertanyaan)}</p>`;

        // Rendering visual gambar dihapus.


        if (isPilihanGanda && soal.pilihan && typeof soal.pilihan === 'object') {
            Object.entries(soal.pilihan).forEach(([key, val]) => {
                const isJwb = (soal.jawaban || '').toString().toUpperCase() === key.toString().toUpperCase();
                html += `<div style="display:flex;gap:8px;align-items:flex-start;padding:4px 0;${isJwb ? 'background:rgba(21,128,61,.06);border-radius:5px;padding:4px 6px;' : ''}">
                    <span style="font-weight:700;color:#d97706;min-width:20px;font-size:.82rem;">${esc(key)}.</span>
                    <span style="font-size:.82rem;">${esc(val)}</span>
                    ${isJwb ? '<span class="pill pill-green" style="margin-left:auto;">✓ Kunci</span>' : ''}
                </div>`;
            });
        }

        if (soal.jawaban && !isPilihanGanda) {
            const label = isBenarSalah ? 'Jawaban' : 'Kunci Jawaban';
            html += `<p style="margin:6px 0;font-size:.8rem;"><span class="pill pill-green">${label}: ${esc(soal.jawaban)}</span></p>`;
        }
        if (soal.pembahasan) {
            html += `<div style="margin-top:8px;padding:7px 10px;background:rgba(13,148,136,.05);border-left:3px solid #0d9488;border-radius:0 6px 6px 0;font-size:.8rem;color:var(--text-secondary);line-height:1.5;">
                <strong>Pembahasan:</strong> ${esc(soal.pembahasan)}
            </div>`;
        }

        html += `</div></div></div>`;
    });

    html += `</div></div>`;
    area.innerHTML = html;
    area.scrollIntoView({ behavior: 'smooth', block: 'start' });
}
</script>
@endsection
