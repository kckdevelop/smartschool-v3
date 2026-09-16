@extends('layouts.app')

@section('title', 'Setting Konfigurasi Pembayaran — SmartSchool')
@section('header_title', 'Setting Konfigurasi Pembayaran')
@section('header_subtitle', 'Pengaturan API Virtual Account & Tagihan BPD DIY')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card mb-4" style="border-left: 4px solid var(--primary-color, #4f46e5);">
        <div class="card-body">
            <div style="display: flex; gap: 16px; align-items: flex-start;">
                <div style="background: rgba(79, 70, 229, 0.1); color: #4f46e5; width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0;">
                    <i class="fa-solid fa-credit-card"></i>
                </div>
                <div>
                    <h4 style="margin: 0 0 6px 0; font-weight: 700; color: var(--text-primary);">Integrasi Virtual Account BPD DIY</h4>
                    <p style="margin: 0; color: var(--text-secondary); font-size: 0.9rem; line-height: 1.5;">
                        Konfigurasi ini digunakan untuk pembuatan dan validasi nomor Virtual Account (VA) tagihan sekolah.<br>
                        <strong>Rumus Format VA:</strong> <code style="background: var(--bg-body, #f1f5f9); padding: 2px 8px; border-radius: 6px; color: #4f46e5; font-weight: 700;">id_institusi + tahun + nis + kode_tagihan</code>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Panel Session Cookie BPD DIY --}}
    <div class="card mb-4" style="border: 2px solid #6366f1;">
        <div class="card-header" style="background: linear-gradient(135deg, #4f46e5, #6366f1); color: #fff; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-cookie-bite" style="font-size: 1.2rem;"></i>
                <div>
                    <h4 style="margin: 0; font-weight: 700; color: #fff; font-size: 1rem;">Session Cookie Portal BPD DIY</h4>
                    <span style="font-size: 0.8rem; opacity: 0.85;">Dibutuhkan agar sistem dapat mengakses data tagihan langsung dari portal BPD DIY</span>
                </div>
            </div>
            @if(!empty($setting->session_cookie))
                <span class="badge" style="background: #10b981; color: #fff; padding: 6px 12px; font-size: 0.8rem; border-radius: 20px;">
                    <i class="fa-solid fa-check-circle"></i> Cookie Tersimpan {{ $setting->cookie_updated_at ? '(' . $setting->cookie_updated_at->diffForHumans() . ')' : '' }}
                </span>
            @else
                <span class="badge" style="background: #f59e0b; color: #fff; padding: 6px 12px; font-size: 0.8rem; border-radius: 20px;">
                    <i class="fa-solid fa-triangle-exclamation"></i> Cookie Belum Diisi
                </span>
            @endif
        </div>
        <div class="card-body">
            <form action="{{ route('pembayaran.konfigurasi.save-cookie') }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label class="form-label" style="font-weight: 700;">
                        Isi Cookie Sesi BPD DIY (Cookie Header / ci_session / PHPSESSID / dll.)
                    </label>
                    <textarea name="session_cookie" class="form-control" rows="3" required
                              placeholder="Contoh: ci_session=a8f9c...; csrf_cookie_name=... atau salin dari EditThisCookie / DevTools"
                              style="font-family: monospace; font-size: 0.85rem;">{{ old('session_cookie', $setting->session_cookie) }}</textarea>
                    <small class="form-hint" style="color: var(--text-muted); font-size: 0.82rem; margin-top: 6px; display: block; line-height: 1.5;">
                        <strong>Cara Mendapatkan Cookie:</strong><br>
                        1. Buka dan login ke portal <a href="https://va.bpddiy.co.id/admin/dashboard" target="_blank" style="color: #4f46e5; text-decoration: underline;">va.bpddiy.co.id</a> di browser.<br>
                        2. Gunakan ekstensi <strong>EditThisCookie</strong> (klik icon extension &rarr; Export) atau buka <strong>F12 (DevTools) &rarr; Tab Application &rarr; Cookies &rarr; https://va.bpddiy.co.id</strong>.<br>
                        3. Salin nilai cookie (misal: <code>ci_session=...</code> atau seluruh header Cookie) lalu paste di kotak ini dan klik Simpan.
                    </small>
                </div>
                <div style="display: flex; gap: 10px; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary" style="background: #4f46e5; border-color: #4f46e5;">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Session Cookie
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h3 class="card-title"><i class="fa-solid fa-gears"></i> Form Konfigurasi API Tagihan</h3>
            <button type="button" class="btn btn-secondary btn-sm" id="btn-test-koneksi">
                <i class="fa-solid fa-plug-circle-check"></i> Uji Koneksi API
            </button>
        </div>
        <div class="card-body">
            <form action="{{ route('pembayaran.konfigurasi.update') }}" method="POST" id="form-konfigurasi">
                @csrf

                <div class="form-grid-2">
                    <!-- ID Institusi -->
                    <div class="form-group">
                        <label class="form-label">ID Institusi / Instansi <span class="text-danger">*</span></label>
                        <input type="text" name="id_institusi" class="form-control @error('id_institusi') is-invalid @enderror"
                               value="{{ old('id_institusi', $setting->id_institusi ?? '1023') }}" required
                               placeholder="Contoh: 8844 / 1023">
                        <small class="form-hint" style="color: var(--text-muted); font-size: 0.82rem; margin-top: 4px; display: block;">
                            Kode identitas institusi sekolah pada sistem BPD DIY.
                        </small>
                        @error('id_institusi')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <!-- URL Report Tagihan VA -->
                    <div class="form-group">
                        <label class="form-label">URL Report Tagihan VA BPD DIY <span class="text-danger">*</span></label>
                        <input type="url" name="url_report_va" class="form-control @error('url_report_va') is-invalid @enderror"
                               value="{{ old('url_report_va', $setting->url_report_va ?? 'https://va.bpddiy.co.id/admin/report/tagihan_va') }}" required>
                        <small class="form-hint" style="color: var(--text-muted); font-size: 0.82rem; margin-top: 4px; display: block;">
                            Default: <code>https://va.bpddiy.co.id/admin/report/tagihan_va</code>
                        </small>
                        @error('url_report_va')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <!-- Username Maker -->
                    <div class="form-group">
                        <label class="form-label">Username User (Maker) <span class="text-danger">*</span></label>
                        <input type="text" name="username_maker" class="form-control @error('username_maker') is-invalid @enderror"
                               value="{{ old('username_maker', $setting->username_maker ?? 'USERMAKER') }}" required>
                        @error('username_maker')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <!-- Password Maker -->
                    <div class="form-group">
                        <label class="form-label">Password User (Maker) <span class="text-danger">*</span></label>
                        <div style="position: relative;">
                            <input type="password" name="password_maker" id="password_maker" class="form-control @error('password_maker') is-invalid @enderror"
                                   value="{{ old('password_maker', $setting->password_maker ?? '#Musaba888') }}" required>
                            <button type="button" onclick="togglePasswordVisibility('password_maker')" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; cursor: pointer; color: var(--text-muted);">
                                <i class="fa-solid fa-eye" id="eye-icon"></i>
                            </button>
                        </div>
                        @error('password_maker')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <!-- Mode API -->
                    <div class="form-group">
                        <label class="form-label">Mode API <span class="text-danger">*</span></label>
                        <select name="mode_api" class="form-control @error('mode_api') is-invalid @enderror" required>
                            <option value="production" {{ old('mode_api', $setting->mode_api ?? '') === 'production' ? 'selected' : '' }}>Production (Live BPD DIY)</option>
                            <option value="sandbox" {{ old('mode_api', $setting->mode_api ?? '') === 'sandbox' ? 'selected' : '' }}>Sandbox (Development)</option>
                            <option value="simulasi" {{ old('mode_api', $setting->mode_api ?? '') === 'simulasi' ? 'selected' : '' }}>Simulasi Local</option>
                        </select>
                        @error('mode_api')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <!-- Format Tahun -->
                    <div class="form-group">
                        <label class="form-label">Format Tahun pada VA <span class="text-danger">*</span></label>
                        <select name="format_tahun" class="form-control @error('format_tahun') is-invalid @enderror" required>
                            <option value="YYYY" {{ old('format_tahun', $setting->format_tahun ?? '') === 'YYYY' ? 'selected' : '' }}>4 Digit YYYY (Contoh: 2026)</option>
                            <option value="YY" {{ old('format_tahun', $setting->format_tahun ?? '') === 'YY' ? 'selected' : '' }}>2 Digit YY (Contoh: 26)</option>
                        </select>
                        @error('format_tahun')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <!-- Status API -->
                    <div class="form-group">
                        <label class="form-label">Status Integrasi API <span class="text-danger">*</span></label>
                        <select name="status_api" class="form-control @error('status_api') is-invalid @enderror" required>
                            <option value="aktif" {{ old('status_api', $setting->status_api ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status_api', $setting->status_api ?? '') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                        </select>
                        @error('status_api')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <hr style="margin: 24px 0; border: none; border-top: 1px dashed var(--border-color, #e2e8f0);">

                <h4 style="margin: 0 0 16px 0; font-weight: 700; color: var(--text-primary);">Pemetaan Kode Tagihan</h4>

                <div class="form-grid-3" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">
                    <!-- Kode Tagihan SPP -->
                    <div class="form-group">
                        <label class="form-label">Kode Tagihan SPP <span class="text-danger">*</span></label>
                        <input type="text" name="kode_spp" class="form-control @error('kode_spp') is-invalid @enderror"
                               value="{{ old('kode_spp', $setting->kode_spp ?? '00') }}" required placeholder="00">
                        <small style="color: var(--text-muted); font-size: 0.8rem;">Kode <code>00</code> (Tagihan SPP)</small>
                        @error('kode_spp')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <!-- Kode Tagihan Non SPP -->
                    <div class="form-group">
                        <label class="form-label">Kode Tagihan Non SPP <span class="text-danger">*</span></label>
                        <input type="text" name="kode_non_spp" class="form-control @error('kode_non_spp') is-invalid @enderror"
                               value="{{ old('kode_non_spp', $setting->kode_non_spp ?? '01') }}" required placeholder="01">
                        <small style="color: var(--text-muted); font-size: 0.8rem;">Kode <code>01</code> (Tagihan Non SPP)</small>
                        @error('kode_non_spp')<span class="form-error">{{ $message }}</span>@enderror
                    </div>

                    <!-- Kode Tagihan Tunggakan -->
                    <div class="form-group">
                        <label class="form-label">Kode Tagihan Tunggakan <span class="text-danger">*</span></label>
                        <input type="text" name="kode_tunggakan" class="form-control @error('kode_tunggakan') is-invalid @enderror"
                               value="{{ old('kode_tunggakan', $setting->kode_tunggakan ?? '02') }}" required placeholder="02">
                        <small style="color: var(--text-muted); font-size: 0.8rem;">Kode <code>02</code> (Tunggakan)</small>
                        @error('kode_tunggakan')<span class="form-error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-actions mt-4" style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Konfigurasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    const icon = document.getElementById('eye-icon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const testBtn = document.getElementById('btn-test-koneksi');
    if (testBtn) {
        testBtn.addEventListener('click', function() {
            testBtn.disabled = true;
            testBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menguji...';
            
            fetch("{{ route('pembayaran.konfigurasi.test') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(res => res.json())
            .then(data => {
                testBtn.disabled = false;
                testBtn.innerHTML = '<i class="fa-solid fa-plug-circle-check"></i> Uji Koneksi API';
                alert(data.message);
            })
            .catch(err => {
                testBtn.disabled = false;
                testBtn.innerHTML = '<i class="fa-solid fa-plug-circle-check"></i> Uji Koneksi API';
                alert('Gagal menguji koneksi: ' + err.message);
            });
        });
    }
});
</script>
@endsection
