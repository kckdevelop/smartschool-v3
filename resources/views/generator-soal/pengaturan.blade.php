@extends('layouts.app')

@section('title', 'Pengaturan LLM — SmartSchool')
@section('header_title', 'Pengaturan')
@section('header_subtitle', 'Profil sekolah & konfigurasi')

@section('content')
<div class="page-content">
    @include('partials.flash')

    @php
        $sekolah = \App\Models\Sekolah::first();
        
        $groqKey    = old('groq_key', $sekolah->groq_key ?? '');
        $groqStatus = old('groq_status', $sekolah->groq_status ?? 'nonaktif');
        $groqModel  = old('groq_model', $sekolah->groq_model ?? 'llama-3.3-70b-versatile');
        $groqQuota  = old('groq_quota', $sekolah->groq_quota ?? 100);

        $geminiKey    = old('gemini_key', $sekolah->gemini_key ?? '');
        $geminiStatus = old('gemini_status', $sekolah->gemini_status ?? 'nonaktif');
        $geminiModel  = old('gemini_model', $sekolah->gemini_model ?? 'gemini-1.5-flash');
        $geminiQuota  = old('gemini_quota', $sekolah->gemini_quota ?? 100);
    @endphp

    <div style="max-width: 800px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px;">

        {{-- INFO BANNER --}}
        <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 10px; padding: 12px 16px; display: flex; align-items: flex-start; gap: 10px;">
            <i class="fa-solid fa-circle-info" style="color: #3b82f6; margin-top: 2px; flex-shrink: 0;"></i>
            <p style="margin: 0; font-size: 0.85rem; color: #1e40af; line-height: 1.6;">
                API key disimpan di database server dan digunakan saat generate soal. Jika kosong, sistem akan menggunakan Template Lokal.
            </p>
        </div>        <form action="{{ route('generator-soal.pengaturan.store') }}" method="POST" id="llm-form">
            @csrf

            <div style="display: flex; flex-direction: column; gap: 20px;">

                {{-- ══════ GOOGLE GEMINI CARD ══════ --}}
                <div class="llm-card {{ $geminiStatus === 'aktif' ? 'active-border' : '' }}" id="card-gemini">
                    <div class="llm-card-header">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            {{-- Gemini logo --}}
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #4285F4, #EA4335, #FBBC04, #34A853); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fa-solid fa-star" style="color: #fff; font-size: 1.15rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 700; font-size: 0.95rem;">Google Gemini</div>
                                <div style="font-size: 0.78rem; color: var(--text-secondary);">Gemini 2.5 Flash Terbaru</div>
                            </div>
                        </div>
                        <div>
                            <span class="status-badge {{ $geminiStatus === 'aktif' ? 'active-badge' : 'inactive-badge' }}" id="badge-gemini">
                                {{ $geminiStatus === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <div class="llm-card-body">
                        {{-- API Key --}}
                        <div class="api-key-wrapper mb-3">
                            <input type="password" name="gemini_key" class="api-key-input" id="key-gemini"
                                placeholder="Masukkan API Key Gemini (AIzaSy...)"
                                value="{{ $geminiKey }}"
                                autocomplete="off">
                            <button type="button" class="eye-btn" onclick="toggleEye('key-gemini', this)">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>

                        {{-- Details --}}
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;" class="mb-3">
                            <div>
                                <label class="field-label">Status</label>
                                <select name="gemini_status" class="llm-select status-select" id="status-gemini" data-target="gemini">
                                    <option value="aktif" {{ $geminiStatus === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ $geminiStatus === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                            <div>
                                <label class="field-label">Kuota Generate (Sisa)</label>
                                <input type="number" name="gemini_quota" class="api-key-input" value="{{ $geminiQuota }}" min="0">
                            </div>
                        </div>

                        <div>
                            <label class="field-label">Model Default (saat Generate)</label>
                            <select name="gemini_model" class="llm-select">
                                <option value="gemini-2.5-flash" {{ $geminiModel == 'gemini-2.5-flash' ? 'selected' : '' }}>gemini-2.5-flash (Gemini 2.5 Flash Terbaru - Gratis)</option>
                                <option value="gemini-1.5-flash" {{ $geminiModel == 'gemini-1.5-flash' ? 'selected' : '' }}>gemini-1.5-flash (Gemini 1.5 Flash - Gratis)</option>
                                <option value="gemini-1.5-pro" {{ $geminiModel == 'gemini-1.5-pro' ? 'selected' : '' }}>gemini-1.5-pro (Gemini 1.5 Pro - Gratis)</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- ══════ GROQ CARD ══════ --}}
                <div class="llm-card {{ $groqStatus === 'aktif' ? 'active-border' : '' }}" id="card-groq">
                    <div class="llm-card-header">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            {{-- Groq logo style --}}
                            <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg, #f55036, #ff6b35); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                <i class="fa-solid fa-bolt" style="color: #fff; font-size: 1.15rem;"></i>
                            </div>
                            <div>
                                <div style="font-weight: 700; font-size: 0.95rem;">Groq (LPU Inference)</div>
                                <div style="font-size: 0.78rem; color: var(--text-secondary);">Llama 3.3 · Mixtral · Gemma — Gratis</div>
                            </div>
                        </div>
                        <div>
                            <span class="status-badge {{ $groqStatus === 'aktif' ? 'active-badge' : 'inactive-badge' }}" id="badge-groq">
                                {{ $groqStatus === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </div>
                    </div>

                    <div class="llm-card-body">
                        {{-- API Key --}}
                        <div class="api-key-wrapper mb-3">
                            <input type="password" name="groq_key" class="api-key-input" id="key-groq"
                                placeholder="Masukkan API Key Groq (gsk_...)"
                                value="{{ $groqKey }}"
                                autocomplete="off">
                            <button type="button" class="eye-btn" onclick="toggleEye('key-groq', this)">
                                <i class="fa-regular fa-eye"></i>
                            </button>
                        </div>

                        {{-- Info Groq --}}
                        <div style="background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px; padding: 10px 14px; margin-bottom: 14px; font-size: 0.8rem; color: #9a3412;">
                            <i class="fa-solid fa-circle-info" style="margin-right: 6px;"></i>
                            Dapatkan API Key gratis di <a href="https://console.groq.com" target="_blank" style="color: #c2410c; font-weight: 600;">console.groq.com</a>. Groq menyediakan inferensi LLM sangat cepat secara gratis.
                        </div>

                        {{-- Details --}}
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px;" class="mb-3">
                            <div>
                                <label class="field-label">Status</label>
                                <select name="groq_status" class="llm-select status-select" id="status-groq" data-target="groq">
                                    <option value="aktif" {{ $groqStatus === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ $groqStatus === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                                </select>
                            </div>
                            <div>
                                <label class="field-label">Kuota Generate (Sisa)</label>
                                <input type="number" name="groq_quota" class="api-key-input" value="{{ $groqQuota }}" min="0">
                            </div>
                        </div>

                        <div>
                            <label class="field-label">Model Default (saat Generate)</label>
                            <select name="groq_model" class="llm-select">
                                <option value="llama-3.3-70b-versatile" {{ $groqModel === 'llama-3.3-70b-versatile' ? 'selected' : '' }}>llama-3.3-70b-versatile (Llama 3.3 70B — Terbaru &amp; Terbaik) ✅</option>
                                <option value="llama-3.1-8b-instant" {{ $groqModel === 'llama-3.1-8b-instant' ? 'selected' : '' }}>llama-3.1-8b-instant (Llama 3.1 8B — Ringan &amp; Cepat) ✅</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>

            {{-- SAVE BUTTON --}}
            <div style="margin-top: 24px; display: flex; gap: 12px; align-items: center;">
                <button type="submit" class="btn btn-primary" style="display: flex; align-items: center; gap: 8px; padding: 10px 24px; border-radius: 8px;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Konfigurasi
                </button>
                <a href="{{ route('generator-soal.index') }}"
                   style="font-size: 0.85rem; color: var(--text-secondary); text-decoration: none;">
                    ← Kembali ke Generate Soal
                </a>
            </div>
        </form>
    </div>
</div>

<style>
.llm-card {
    border: 1.5px solid var(--border-color);
    border-radius: 14px;
    background: var(--bg-primary, #fff);
    transition: border-color .25s, box-shadow .25s;
    overflow: hidden;
}
.llm-card.active-border {
    border-color: #a855f7; /* Purple active border matching reference */
    box-shadow: 0 0 0 3px rgba(168, 85, 247, 0.1);
}

.llm-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border-color);
}
.llm-card-body {
    padding: 18px 20px;
    background: var(--bg-secondary, #fafafa);
}

.status-badge {
    font-size: 0.75rem;
    font-weight: 600;
    padding: 4px 12px;
    border-radius: 20px;
}
.active-badge { background: #d1fae5; color: #065f46; }
.inactive-badge { background: #f3f4f6; color: #6b7280; border: 1px solid var(--border-color); }

.field-label {
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 6px;
    display: block;
    color: var(--text-primary);
}

.api-key-wrapper {
    position: relative;
    display: flex;
    align-items: center;
}
.api-key-input {
    width: 100%;
    padding: 10px 42px 10px 14px;
    border: 1.5px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.88rem;
    font-family: monospace;
    background: var(--bg-primary, #fff);
    color: var(--text-primary);
    outline: none;
    transition: border-color .2s;
    box-sizing: border-box;
}
.api-key-input:focus { border-color: #a855f7; }
.eye-btn {
    position: absolute;
    right: 12px;
    background: none;
    border: none;
    cursor: pointer;
    color: var(--text-secondary);
    font-size: 1rem;
    padding: 4px;
    line-height: 1;
}
.eye-btn:hover { color: var(--text-primary); }

.llm-select {
    width: 100%;
    padding: 10px 12px;
    border: 1.5px solid var(--border-color);
    border-radius: 8px;
    font-size: 0.88rem;
    background: var(--bg-primary, #fff);
    color: var(--text-primary);
    outline: none;
    cursor: pointer;
    box-sizing: border-box;
}
.llm-select:focus { border-color: #a855f7; }

</style>

<script>
// Toggle API Key visibility
function toggleEye(inputId, btn) {
    const input = document.getElementById(inputId);
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}

document.querySelectorAll('.status-select').forEach(select => {
    select.addEventListener('change', function() {
        const target = this.getAttribute('data-target');
        const isAktif = this.value === 'aktif';
        
        if (isAktif) {
            // Deactivate the other
            const otherTarget = target === 'gemini' ? 'groq' : 'gemini';
            const otherSelect = document.getElementById(`status-${otherTarget}`);
            otherSelect.value = 'nonaktif';
            
            // Trigger style update
            updateStatusStyle(otherTarget, false);
            updateStatusStyle(target, true);
        } else {
            updateStatusStyle(target, false);
        }
    });
});

function updateStatusStyle(target, isAktif) {
    const card = document.getElementById(`card-${target}`);
    const badge = document.getElementById(`badge-${target}`);
    const keyInput = document.getElementById(`key-${target}`);
    
    if (isAktif) {
        card.classList.add('active-border');
        badge.className = 'status-badge active-badge';
        badge.innerText = 'Aktif';
        keyInput.required = true;
    } else {
        card.classList.remove('active-border');
        badge.className = 'status-badge inactive-badge';
        badge.innerText = 'Nonaktif';
        keyInput.required = false;
    }
}

// Initial key input required triggers
document.addEventListener('DOMContentLoaded', () => {
    updateStatusStyle('gemini', document.getElementById('status-gemini').value === 'aktif');
    updateStatusStyle('groq', document.getElementById('status-groq').value === 'aktif');
});
</script>
@endsection
