@extends('layouts.app')

@section('title', 'WhatsApp Gateway Multi-API — SmartSchool')
@section('header_title', 'WhatsApp Gateway Multi-API')
@section('header_subtitle', 'Kelola daftar API WhatsApp Gateway dan rotasi pengiriman acak (random rotation)')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- ─── Info Banner Random Rotation ─── --}}
    <div class="card" style="margin-bottom: 20px; background: linear-gradient(135deg, rgba(13,148,136,0.08) 0%, rgba(99,102,241,0.08) 100%); border: 1.5px solid rgba(13,148,136,0.25);">
        <div class="card-body" style="padding: 18px 22px;">
            <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
                <div style="width: 46px; height: 46px; border-radius: 12px; background: linear-gradient(135deg, #0d9488, #6366f1); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(13,148,136,0.3);">
                    <i class="fa-solid fa-shuffle"></i>
                </div>
                <div style="flex: 1; min-width: 260px;">
                    <h3 style="font-size: 0.98rem; font-weight: 800; margin: 0 0 4px 0; color: var(--text-color, #1e293b);">
                        Multi-Gateway & Rotasi Pengiriman Otomatis (Random Rotation)
                    </h3>
                    <p style="font-size: 0.82rem; color: var(--text-muted, #64748b); margin: 0; line-height: 1.45;">
                        Pengiriman notifikasi <strong>WA Presensi Masal</strong> ke orang tua siswa akan otomatis <strong>mengacak nomor / token WA Gateway</strong> dari seluruh daftar gateway yang berstatus <span class="badge badge-success" style="font-size: 0.72rem; padding: 2px 7px;">Aktif</span>. Hal ini mencegah pengiriman menumpuk pada 1 nomor WhatsApp saja.
                    </p>
                </div>
                <div>
                    <button type="button" class="btn btn-primary btn-sm" onclick="openModalAddGateway()" style="font-weight: 700;">
                        <i class="fa-solid fa-plus"></i> Tambah WA Gateway
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ─── Stats Mini Cards ─── --}}
    <div class="wa-stats-grid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="padding: 16px 20px; display: flex; align-items: center; gap: 14px;">
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(59,130,246,0.12); color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fa-solid fa-server"></i>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 800; line-height: 1.1;">{{ $totalGateways }}</div>
                <div style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Total Gateway</div>
            </div>
        </div>
        <div class="card" style="padding: 16px 20px; display: flex; align-items: center; gap: 14px;">
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(16,185,129,0.12); color: #059669; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 800; line-height: 1.1; color: #059669;">{{ $activeGateways }}</div>
                <div style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Gateway Aktif</div>
            </div>
        </div>
        <div class="card" style="padding: 16px 20px; display: flex; align-items: center; gap: 14px;">
            <div style="width: 42px; height: 42px; border-radius: 10px; background: rgba(239,68,68,0.12); color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                <i class="fa-solid fa-circle-pause"></i>
            </div>
            <div>
                <div style="font-size: 1.5rem; font-weight: 800; line-height: 1.1; color: #dc2626;">{{ $inactiveGateways }}</div>
                <div style="font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-top: 2px;">Non-Aktif</div>
            </div>
        </div>
    </div>

    <div class="wa-layout">
        {{-- ─── KOLOM KIRI: DAFTAR GATEWAY ─── --}}
        <div class="wa-column">
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
                    <h2 class="card-title"><i class="fa-brands fa-whatsapp"></i> Daftar API WhatsApp Gateway</h2>
                    <button type="button" class="btn btn-primary btn-sm" onclick="openModalAddGateway()">
                        <i class="fa-solid fa-plus"></i> Tambah Gateway
                    </button>
                </div>
                <div class="card-body p-0">
                    <div style="overflow-x: auto;">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 45px;">#</th>
                                    <th>Nama Gateway / Keterangan</th>
                                    <th>Nomor WA</th>
                                    <th>Token API</th>
                                    <th>Provider</th>
                                    <th style="text-align: center;">Status</th>
                                    <th style="text-align: right; width: 140px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($gateways as $idx => $gw)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>
                                        <strong style="font-size: 0.9rem; color: var(--text-color, #1e293b);">{{ $gw->nama }}</strong>
                                        @if($gw->keterangan)
                                            <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 2px;">{{ $gw->keterangan }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        @if($gw->nomor_wa)
                                            <span style="font-family: monospace; font-size: 0.85rem; font-weight: 600; color: #0d9488;">
                                                <i class="fa-brands fa-whatsapp"></i> {{ $gw->nomor_wa }}
                                            </span>
                                        @else
                                            <span style="color: var(--text-muted); font-size: 0.8rem; font-style: italic;">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span style="font-family: monospace; font-size: 0.82rem; background: #f1f5f9; padding: 2px 7px; border-radius: 6px; border: 1px solid #cbd5e1;" title="{{ $gw->token }}">
                                            {{ $gw->masked_token }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-info" style="font-size: 0.72rem; text-transform: uppercase;">{{ $gw->provider }}</span>
                                    </td>
                                    <td style="text-align: center;">
                                        <button type="button" class="btn-toggle-status" onclick="toggleStatusGateway({{ $gw->id }}, '{{ addslashes($gw->nama) }}')" style="border: none; background: transparent; cursor: pointer; padding: 0;" title="Klik untuk ubah status">
                                            @if($gw->status === 'aktif')
                                                <span class="badge badge-success" style="background: #10b981; color: #fff; padding: 5px 10px; border-radius: 12px; font-weight: 700;">
                                                    <i class="fa-solid fa-circle-check"></i> Aktif
                                                </span>
                                            @else
                                                <span class="badge badge-danger" style="background: #ef4444; color: #fff; padding: 5px 10px; border-radius: 12px; font-weight: 700;">
                                                    <i class="fa-solid fa-circle-xmark"></i> Non-Aktif
                                                </span>
                                            @endif
                                        </button>
                                    </td>
                                    <td class="action-cell" style="text-align: right;">
                                        <button type="button" class="btn-icon btn-info" title="Cek Status Perangkat Live" onclick="openDeviceStatusModal({{ $gw->id }}, '{{ addslashes($gw->nama) }}')">
                                            <i class="fa-solid fa-mobile-screen-button"></i>
                                        </button>
                                        <button type="button" class="btn-icon btn-edit" title="Edit Gateway" onclick="editGateway({{ $gw->id }}, '{{ addslashes($gw->nama) }}', '{{ addslashes($gw->token) }}', '{{ addslashes($gw->nomor_wa ?? '') }}', '{{ addslashes($gw->provider) }}', '{{ addslashes($gw->keterangan ?? '') }}', '{{ $gw->status }}')">
                                            <i class="fa-solid fa-pen"></i>
                                        </button>
                                        <button type="button" class="btn-icon btn-delete" title="Hapus Gateway" onclick="confirmDeleteGateway({{ $gw->id }}, '{{ addslashes($gw->nama) }}')">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center py-6" style="color: var(--text-muted); padding: 32px;">
                                        <i class="fa-solid fa-server" style="font-size: 2.2rem; opacity: 0.3; margin-bottom: 8px; display: block;"></i>
                                        Belum ada API WA Gateway yang ditambahkan. Silakan klik tombol <strong>+ Tambah Gateway</strong> di atas.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- ─── KOLOM KANAN: UJI COBA PENGIRIMAN ─── --}}
        <div class="wa-column">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title"><i class="fa-solid fa-paper-plane"></i> Form Uji Coba Pengiriman WA</h2>
                </div>
                <div class="card-body">
                    <form id="form-test-wa" onsubmit="event.preventDefault();">
                        <div class="form-group">
                            <label class="form-label">Pilih WA Gateway Pengirim <span class="required">*</span></label>
                            <select id="test-gateway-id" class="form-control">
                                <option value="random">🎲 Acak / Random Rotation (Otomatis pilih Gateway Aktif)</option>
                                @foreach($gateways as $gw)
                                    <option value="{{ $gw->id }}" {{ $gw->status === 'nonaktif' ? 'disabled' : '' }}>
                                        {{ $gw->nama }} {{ $gw->nomor_wa ? '('.$gw->nomor_wa.')' : '' }} {{ $gw->status === 'nonaktif' ? '[Non-Aktif]' : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-hint" style="color: var(--text-muted); font-size: 0.78em; margin-top: 4px; display: block;">
                                Opsi 'Random Rotation' memicu sistem memilih 1 token aktif secara acak saat pengiriman.
                            </small>
                        </div>
                        <div class="form-group" style="margin-top: 14px;">
                            <label class="form-label">Nomor WhatsApp Penerima <span class="required">*</span></label>
                            <input type="text" id="test-target" class="form-control" placeholder="Contoh: 08123456789 atau 628123456789" required>
                        </div>
                        <div class="form-group" style="margin-top: 14px;">
                            <label class="form-label">Isi Pesan Uji Coba <span class="required">*</span></label>
                            <textarea id="test-message" class="form-control" rows="4" required>Halo! Ini adalah pesan uji coba dari menu WhatsApp Gateway SmartSchool Anda. Integrasi Multi-API Fonnte berhasil dengan sukses! 🎉</textarea>
                        </div>
                        <div id="test-alert" style="margin-top: 14px; display: none; padding: 12px; border-radius: 8px; font-size: 0.85rem;"></div>
                        <div class="form-actions" style="margin-top: 18px;">
                            <button type="button" id="btn-send-test" class="btn btn-primary" style="width: 100%; justify-content: center; font-weight: 700;">
                                <i class="fa-solid fa-paper-plane"></i> Kirim Pesan Uji Coba
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ─── MODAL TAMBAH / EDIT WA GATEWAY ─── --}}
<div class="modal-overlay" id="modal-gateway">
    <div class="modal modal-md" style="max-width: 500px;">
        <div class="modal-header">
            <h3 id="modal-gateway-title"><i class="fa-solid fa-server"></i> Tambah WA Gateway</h3>
            <button type="button" onclick="closeModal('modal-gateway')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-gateway" action="{{ route('atur-data.whatsapp-gateway.store') }}" method="POST">
            @csrf
            <div class="modal-body" style="padding: 20px;">
                <div class="form-group">
                    <label class="form-label">Nama / Label Gateway <span class="required">*</span></label>
                    <input type="text" name="nama" id="gw_nama" class="form-control" placeholder="Contoh: WA Admin Presensi 1" required>
                </div>
                <div class="form-group" style="margin-top: 14px;">
                    <label class="form-label">Token Fonnte API <span class="required">*</span></label>
                    <input type="text" name="token" id="gw_token" class="form-control" placeholder="Masukkan Token API Fonnte..." required>
                    <small class="form-hint" style="color: var(--text-muted); font-size: 0.78em; margin-top: 4px; display: block;">
                        Dapatkan token API resmi dari dashboard Fonnte.
                    </small>
                </div>
                <div class="form-row-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 14px;">
                    <div class="form-group">
                        <label class="form-label">Nomor WA <span style="font-weight: 400; color: var(--text-muted); font-size: 0.75rem;">(Opsional)</span></label>
                        <input type="text" name="nomor_wa" id="gw_nomor_wa" class="form-control" placeholder="Contoh: 08123456789">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Provider API <span class="required">*</span></label>
                        <select name="provider" id="gw_provider" class="form-control" required>
                            <option value="fonnte">Fonnte (Default)</option>
                        </select>
                    </div>
                </div>
                <div class="form-group" style="margin-top: 14px;">
                    <label class="form-label">Status Gateway <span class="required">*</span></label>
                    <select name="status" id="gw_status" class="form-control" required>
                        <option value="aktif">Aktif (Ikut Rotasi Acak)</option>
                        <option value="nonaktif">Non-Aktif (Diarsipkan)</option>
                    </select>
                </div>
                <div class="form-group" style="margin-top: 14px;">
                    <label class="form-label">Catatan / Keterangan <span style="font-weight: 400; color: var(--text-muted); font-size: 0.75rem;">(Opsional)</span></label>
                    <textarea name="keterangan" id="gw_keterangan" class="form-control" rows="2" placeholder="Contoh: Nomor utama untuk kirim presensi kelas 10..."></textarea>
                </div>
            </div>
            <div class="modal-footer" style="padding: 14px 20px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('modal-gateway')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Gateway</button>
            </div>
        </form>
    </div>
</div>

{{-- ─── MODAL HAPUS GATEWAY ─── --}}
<div class="modal-overlay" id="modal-delete-gateway">
    <div class="modal modal-sm" style="max-width: 400px;">
        <div class="modal-header">
            <h3>Konfirmasi Hapus Gateway</h3>
            <button type="button" onclick="closeModal('modal-delete-gateway')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-delete-gateway" action="" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-body" style="padding: 20px; text-align: center;">
                <p style="margin: 0 0 12px; font-size: 0.95rem;">Apakah Anda yakin ingin menghapus WA Gateway:</p>
                <strong id="delete-gw-name" style="font-size: 1.1rem; color: #dc2626; display: block; margin-bottom: 12px;"></strong>
                <p style="font-size: 0.8rem; color: var(--text-muted); margin: 0;">Gateway yang dihapus tidak akan lagi digunakan dalam rotasi pengiriman pesan.</p>
            </div>
            <div class="modal-footer" style="padding: 14px 20px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" onclick="closeModal('modal-delete-gateway')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-danger"><i class="fa-solid fa-trash"></i> Ya, Hapus</button>
            </div>
        </form>
    </div>
</div>

{{-- ─── MODAL CEK STATUS PERANGKAT LIVE ─── --}}
<div class="modal-overlay" id="modal-device-status">
    <div class="modal modal-md" style="max-width: 480px;">
        <div class="modal-header">
            <h3 id="device-modal-title"><i class="fa-solid fa-mobile-screen-button"></i> Status Perangkat Fonnte Live</h3>
            <button type="button" onclick="closeModal('modal-device-status')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="padding: 20px;">
            <div id="modal-device-loading" style="text-align: center; padding: 30px;">
                <i class="fa-solid fa-spinner fa-spin" style="font-size: 2rem; color: var(--color-primary, #0d9488); margin-bottom: 10px;"></i>
                <p style="color: var(--text-muted);">Memeriksa koneksi perangkat Fonnte secara live...</p>
            </div>
            <div id="modal-device-error" style="display: none; padding: 14px; background-color: #f8d7da; color: #721c24; border-radius: 8px; border: 1px solid #f5c6cb; font-size: 0.88rem;"></div>
            <div id="modal-device-success" style="display: none;">
                <table class="device-info-table" style="width: 100%; border-collapse: collapse;">
                    <tbody>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <th>Status Koneksi</th>
                            <td><span id="m-device-status" class="status-badge"></span></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <th>Nama Perangkat</th>
                            <td id="m-device-name">-</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <th>Nomor WA</th>
                            <td id="m-device-number">-</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <th>Paket Layanan</th>
                            <td><span id="m-device-package" style="font-weight: 600;">-</span></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <th>Masa Aktif</th>
                            <td id="m-device-expired">-</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <th>Sisa Kuota Pesan</th>
                            <td id="m-device-quota">-</td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <th>Maks. Perangkat</th>
                            <td id="m-device-max">-</td>
                        </tr>
                        <tr>
                            <th>Total Perangkat</th>
                            <td id="m-device-total">-</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer" style="padding: 14px 20px; background: #f8fafc; border-top: 1px solid #f1f5f9; display: flex; justify-content: flex-end;">
            <button type="button" onclick="closeModal('modal-device-status')" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</div>

<style>
.wa-layout {
    display: grid;
    grid-template-columns: 1.3fr 0.7fr;
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
    padding: 10px 8px;
    font-weight: 600;
    color: var(--text-muted, #64748b);
    width: 42%;
    font-size: 0.85rem;
}
.device-info-table td {
    padding: 10px 8px;
    color: var(--text-color, #1e293b);
    font-size: 0.88rem;
}
.status-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 0.78rem;
    font-weight: 700;
}
.status-badge-success { background-color: #10b981; color: #fff; }
.status-badge-danger { background-color: #ef4444; color: #fff; }
.status-badge-warning { background-color: #f59e0b; color: #fff; }
</style>

<script>
function openModalAddGateway() {
    document.getElementById('form-gateway').action = "{{ route('atur-data.whatsapp-gateway.store') }}";
    document.getElementById('modal-gateway-title').innerHTML = '<i class="fa-solid fa-server"></i> Tambah WA Gateway';
    document.getElementById('gw_nama').value = '';
    document.getElementById('gw_token').value = '';
    document.getElementById('gw_nomor_wa').value = '';
    document.getElementById('gw_provider').value = 'fonnte';
    document.getElementById('gw_status').value = 'aktif';
    document.getElementById('gw_keterangan').value = '';
    openModal('modal-gateway');
}

function editGateway(id, nama, token, nomorWa, provider, keterangan, status) {
    document.getElementById('form-gateway').action = `/atur-data/whatsapp-gateway/${id}/update`;
    document.getElementById('modal-gateway-title').innerHTML = '<i class="fa-solid fa-pen"></i> Edit WA Gateway';
    document.getElementById('gw_nama').value = nama;
    document.getElementById('gw_token').value = token;
    document.getElementById('gw_nomor_wa').value = nomorWa || '';
    document.getElementById('gw_provider').value = provider || 'fonnte';
    document.getElementById('gw_status').value = status || 'aktif';
    document.getElementById('gw_keterangan').value = keterangan || '';
    openModal('modal-gateway');
}

function confirmDeleteGateway(id, nama) {
    document.getElementById('form-delete-gateway').action = `/atur-data/whatsapp-gateway/${id}`;
    document.getElementById('delete-gw-name').textContent = nama;
    openModal('modal-delete-gateway');
}

function toggleStatusGateway(id, nama) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    fetch(`/atur-data/whatsapp-gateway/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            window.location.reload();
        } else {
            alert(data.message || 'Gagal mengubah status.');
        }
    })
    .catch(err => {
        alert('Terjadi kesalahan jaringan/server.');
    });
}

function openDeviceStatusModal(id, nama) {
    document.getElementById('device-modal-title').innerHTML = '<i class="fa-solid fa-mobile-screen-button"></i> Status Live — ' + nama;
    const loading = document.getElementById('modal-device-loading');
    const errorBox = document.getElementById('modal-device-error');
    const successBox = document.getElementById('modal-device-success');

    loading.style.display = 'block';
    errorBox.style.display = 'none';
    successBox.style.display = 'none';

    openModal('modal-device-status');

    fetch(`/atur-data/whatsapp-gateway/device-status/${id}`)
    .then(async res => {
        const data = await res.json();
        if (res.ok && data.success) {
            const dev = data.device;
            document.getElementById('m-device-name').innerText = dev.name || 'Perangkat Tanpa Nama';
            document.getElementById('m-device-number').innerText = dev.number || '-';
            document.getElementById('m-device-package').innerText = dev.package || 'Free';
            document.getElementById('m-device-expired').innerText = dev.expired || 'Never';
            document.getElementById('m-device-quota').innerText = (dev.messages ?? '-') + ' pesan tersisa';
            document.getElementById('m-device-max').innerText = dev.quota ? dev.quota + ' perangkat' : '-';
            document.getElementById('m-device-total').innerText = dev.total ? dev.total + ' perangkat' : '-';

            const badge = document.getElementById('m-device-status');
            const statusVal = (dev.device_status || '').toLowerCase();

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
            throw new Error(data.message || 'Gagal terhubung ke Fonnte.');
        }
    })
    .catch(err => {
        loading.style.display = 'none';
        errorBox.innerText = err.message || 'Gagal memeriksa koneksi perangkat.';
        errorBox.style.display = 'block';
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const btnSendTest = document.getElementById('btn-send-test');
    if (btnSendTest) {
        btnSendTest.addEventListener('click', function() {
            const target = document.getElementById('test-target').value.trim();
            const message = document.getElementById('test-message').value.trim();
            const gatewayId = document.getElementById('test-gateway-id').value;
            const alertBox = document.getElementById('test-alert');

            if (!target || !message) {
                showTestAlert('Silakan isi nomor penerima dan isi pesan uji coba!', 'danger');
                return;
            }

            btnSendTest.disabled = true;
            btnSendTest.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim Pesan...';
            alertBox.style.display = 'none';

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch("{{ route('atur-data.whatsapp-gateway.test') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({ target, message, gateway_id: gatewayId })
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

    function showTestAlert(message, type) {
        const alertBox = document.getElementById('test-alert');
        alertBox.style.display = 'block';
        alertBox.innerHTML = message;

        if (type === 'success') {
            alertBox.style.backgroundColor = '#d1fae5';
            alertBox.style.color = '#065f46';
            alertBox.style.border = '1px solid #a7f3d0';
        } else {
            alertBox.style.backgroundColor = '#fee2e2';
            alertBox.style.color = '#991b1b';
            alertBox.style.border = '1px solid #fca5a5';
        }
    }
});
</script>
@endsection
