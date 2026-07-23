@extends('layouts.app')

@section('title', 'WhatsApp Gateway — SmartSchool')
@section('header_title', 'WhatsApp Gateway')
@section('header_subtitle', 'Kelola pengaturan integrasi WhatsApp Fonnte')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="wa-layout">
        <!-- KOLOM KIRI: SETTINGS & UJI COBA -->
        <div class="wa-column">
            <!-- CARD 1: PENGATURAN -->
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-gears"></i> Pengaturan Gateway</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('atur-data.whatsapp-gateway.update') }}" method="POST">
                        @csrf
                        <div class="form-grid-2">
                            <div class="form-group">
                                <label class="form-label">Token Fonnte <span class="required">*</span></label>
                                <input type="password" name="wa_token" class="form-control @error('wa_token') is-invalid @enderror"
                                       value="{{ old('wa_token', $sekolah->wa_token ?? '') }}" placeholder="Masukkan Token Fonnte Anda">
                                <small class="form-hint" style="color: var(--text-muted, #888); font-size: 0.82em; margin-top: 4px; display: block;">
                                    Dapatkan token Anda dari dashboard resmi Fonnte.
                                </small>
                                @error('wa_token')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status WhatsApp Gateway <span class="required">*</span></label>
                                <select name="wa_status" class="form-control @error('wa_status') is-invalid @enderror">
                                    <option value="nonaktif" {{ old('wa_status', $sekolah->wa_status ?? 'nonaktif') === 'nonaktif' ? 'selected' : '' }}>Non-Aktif (Mati)</option>
                                    <option value="aktif" {{ old('wa_status', $sekolah->wa_status ?? '') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                </select>
                                <small class="form-hint" style="color: var(--text-muted, #888); font-size: 0.82em; margin-top: 4px; display: block;">
                                    Aktifkan untuk mengizinkan sistem mengirim notifikasi otomatis.
                                </small>
                                @error('wa_status')<span class="form-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="form-actions" style="margin-top: 20px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- CARD 2: UJI COBA PENGIRIMAN -->
            <div class="card" style="margin-top: 24px;">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-paper-plane"></i> Form Uji Coba Pengiriman</h2>
                </div>
                <div class="card-body">
                    @if($sekolah && !empty($sekolah->wa_token))
                        <form id="form-test-wa" onsubmit="event.preventDefault();">
                            <div class="form-group">
                                <label class="form-label">Nomor WhatsApp Penerima <span class="required">*</span></label>
                                <input type="text" id="test-target" class="form-control" placeholder="Contoh: 08123456789 atau 628123456789" required>
                                <small class="form-hint" style="color: var(--text-muted, #888); font-size: 0.82em; margin-top: 4px; display: block;">
                                    Gunakan nomor aktif untuk melihat hasil pengiriman.
                                </small>
                            </div>
                            <div class="form-group" style="margin-top: 15px;">
                                <label class="form-label">Isi Pesan <span class="required">*</span></label>
                                <textarea id="test-message" class="form-control" rows="4" required>Halo! Ini adalah pesan uji coba dari menu WhatsApp Gateway SmartSchool Anda. Integrasi Fonnte berhasil dengan sukses! 🎉</textarea>
                            </div>
                            <div id="test-alert" style="margin-top: 15px; display: none; padding: 12px; border-radius: 4px;"></div>
                            <div class="form-actions" style="margin-top: 20px;">
                                <button type="button" id="btn-send-test" class="btn btn-secondary">
                                    <i class="fa-solid fa-paper-plane"></i> Kirim Pesan Uji Coba
                                </button>
                            </div>
                        </form>
                    @else
                        <div style="text-align: center; padding: 20px; color: var(--text-muted, #888);">
                            <i class="fa-solid fa-lock" style="font-size: 2.5rem; margin-bottom: 12px; display: block; color: #ccc;"></i>
                            Silakan konfigurasi dan simpan Token Fonnte terlebih dahulu untuk mengaktifkan form uji coba.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: STATUS PERANGKAT LIVE -->
        <div class="wa-column">
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 class="card-title"><i class="fa-solid fa-mobile-screen-button"></i> Status Perangkat</h2>
                    @if($sekolah && !empty($sekolah->wa_token))
                        <button type="button" id="btn-refresh-device" class="btn btn-xs btn-secondary" style="padding: 4px 8px; font-size: 0.75rem;">
                            <i class="fa-solid fa-arrows-rotate"></i> Refresh
                        </button>
                    @endif
                </div>
                <div class="card-body">
                    @if($sekolah && !empty($sekolah->wa_token))
                        <div id="device-loading" style="text-align: center; padding: 30px;">
                            <i class="fa-solid fa-spinner fa-spin" style="font-size: 2rem; color: var(--color-primary, #007bff); margin-bottom: 10px;"></i>
                            <p style="color: var(--text-muted, #888);">Mengambil info perangkat dari Fonnte...</p>
                        </div>
                        <div id="device-error" style="display: none; padding: 15px; background-color: #f8d7da; color: #721c24; border-radius: 4px; border: 1px solid #f5c6cb;">
                            Gagal menghubungkan ke Fonnte. Periksa token Anda dan pastikan server terhubung ke internet.
                        </div>
                        <div id="device-success" style="display: none;">
                            <table class="device-info-table" style="width: 100%; border-collapse: collapse;">
                                <tbody>
                                    <tr style="border-bottom: 1px solid var(--border-color, #eee);">
                                        <th>Status Koneksi</th>
                                        <td><span id="device-status" class="status-badge"></span></td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid var(--border-color, #eee);">
                                        <th>Nama Perangkat</th>
                                        <td id="device-name">-</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid var(--border-color, #eee);">
                                        <th>Nomor WA</th>
                                        <td id="device-number">-</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid var(--border-color, #eee);">
                                        <th>Paket Layanan</th>
                                        <td><span id="device-package" style="font-weight: 600;">-</span></td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid var(--border-color, #eee);">
                                        <th>Masa Aktif</th>
                                        <td id="device-expired">-</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid var(--border-color, #eee);">
                                        <th>Sisa Kuota Pesan</th>
                                        <td id="device-quota">-</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid var(--border-color, #eee);">
                                        <th>Maks. Perangkat</th>
                                        <td id="device-max">-</td>
                                    </tr>
                                    <tr>
                                        <th>Perangkat Aktif</th>
                                        <td id="device-total">-</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div style="text-align: center; padding: 20px; color: var(--text-muted, #888);">
                            <i class="fa-solid fa-circle-info" style="font-size: 2.5rem; margin-bottom: 12px; display: block; color: #ccc;"></i>
                            Token Fonnte belum diatur. Status koneksi perangkat tidak dapat diperiksa.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.wa-layout {
    display: grid;
    grid-template-columns: 1.2fr 0.8fr;
    gap: 24px;
    align-items: start;
}
@media (max-width: 992px) {
    .wa-layout {
        grid-template-columns: 1fr;
    }
}
.device-info-table th {
    text-align: left;
    padding: 12px 8px;
    font-weight: 600;
    color: var(--text-muted, #666);
    width: 40%;
}
.device-info-table td {
    padding: 12px 8px;
    color: var(--text-color, #333);
}
.status-badge {
    display: inline-block;
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.8em;
    font-weight: 600;
    text-transform: uppercase;
}
.status-badge-success {
    background-color: #d4edda;
    color: #155724;
}
.status-badge-danger {
    background-color: #f8d7da;
    color: #721c24;
}
.status-badge-warning {
    background-color: #fff3cd;
    color: #856404;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($sekolah && !empty($sekolah->wa_token))
        loadDeviceStatus();

        const btnRefresh = document.getElementById('btn-refresh-device');
        if (btnRefresh) {
            btnRefresh.addEventListener('click', function() {
                loadDeviceStatus();
            });
        }

        const btnSendTest = document.getElementById('btn-send-test');
        if (btnSendTest) {
            btnSendTest.addEventListener('click', function() {
                const target = document.getElementById('test-target').value.trim();
                const message = document.getElementById('test-message').value.trim();
                const alertBox = document.getElementById('test-alert');

                if (!target || !message) {
                    showTestAlert('Silakan isi nomor penerima dan isi pesan!', 'danger');
                    return;
                }

                btnSendTest.disabled = true;
                btnSendTest.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim...';
                alertBox.style.display = 'none';

                fetch("{{ route('atur-data.whatsapp-gateway.test') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ target, message })
                })
                .then(async response => {
                    const data = await response.json();
                    if (response.ok) {
                        showTestAlert(data.message || 'Pesan uji coba berhasil dikirim!', 'success');
                    } else {
                        showTestAlert(data.message || 'Gagal mengirim pesan uji coba.', 'danger');
                    }
                })
                .catch(error => {
                    console.error(error);
                    showTestAlert('Terjadi kesalahan jaringan atau server.', 'danger');
                })
                .finally(() => {
                    btnSendTest.disabled = false;
                    btnSendTest.innerHTML = '<i class="fa-solid fa-paper-plane"></i> Kirim Pesan Uji Coba';
                });
            });
        }

        function loadDeviceStatus() {
            const loading = document.getElementById('device-loading');
            const errorBox = document.getElementById('device-error');
            const successBox = document.getElementById('device-success');

            loading.style.display = 'block';
            errorBox.style.display = 'none';
            successBox.style.display = 'none';

            fetch("{{ route('atur-data.whatsapp-gateway.device-status') }}")
            .then(async response => {
                const data = await response.json();
                if (response.ok && data.success) {
                    const dev = data.device;
                    
                    // Update views
                    document.getElementById('device-name').innerText = dev.name || 'Perangkat Tanpa Nama';
                    document.getElementById('device-number').innerText = dev.number || '-';
                    document.getElementById('device-package').innerText = dev.package || 'Free';
                    document.getElementById('device-expired').innerText = dev.expired || 'Never';
                    document.getElementById('device-quota').innerText = (dev.messages ?? '-') + ' pesan tersisa';
                    document.getElementById('device-max').innerText = dev.quota ? dev.quota + ' perangkat' : '-';
                    document.getElementById('device-total').innerText = dev.total ? dev.total + ' perangkat' : '-';

                    const badge = document.getElementById('device-status');
                    const statusVal = (dev.device_status || '').toLowerCase();
                    
                    // Normalize Fonnte's status values: 'connect' → Terhubung, 'disconnect' → Terputus
                    badge.className = 'status-badge';
                    if (statusVal === 'connect' || statusVal === 'connected') {
                        badge.innerText = '✓ Terhubung';
                        badge.classList.add('status-badge-success');
                    } else if (statusVal === 'disconnect' || statusVal === 'disconnected') {
                        badge.innerText = '✗ Terputus';
                        badge.classList.add('status-badge-danger');
                    } else {
                        badge.innerText = dev.device_status || 'Tidak Diketahui';
                        badge.classList.add('status-badge-warning');
                    }

                    loading.style.display = 'none';
                    successBox.style.display = 'block';
                } else {
                    throw new Error(data.message || 'Gagal terhubung.');
                }
            })
            .catch(err => {
                console.error(err);
                loading.style.display = 'none';
                errorBox.innerText = err.message || 'Gagal menghubungkan ke Fonnte. Periksa token Anda.';
                errorBox.style.display = 'block';
            });
        }

        function showTestAlert(message, type) {
            const alertBox = document.getElementById('test-alert');
            alertBox.style.display = 'block';
            alertBox.innerHTML = message;
            
            if (type === 'success') {
                alertBox.style.backgroundColor = '#d4edda';
                alertBox.style.color = '#155724';
                alertBox.style.border = '1px solid #c3e6cb';
            } else {
                alertBox.style.backgroundColor = '#f8d7da';
                alertBox.style.color = '#721c24';
                alertBox.style.border = '1px solid #f5c6cb';
            }
        }
    @endif
});
</script>
@endsection
