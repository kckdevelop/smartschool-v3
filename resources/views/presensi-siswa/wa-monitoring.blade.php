@extends('layouts.app')

@section('title', 'Monitoring Pengiriman WA Presensi')
@section('header_title', 'Kirim WA Presensi Siswa')
@section('header_subtitle', 'Kelola dan pantau proses pengiriman pesan WhatsApp presensi harian ke orang tua siswa')

@push('styles')
<style>
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: var(--bg-card, #fff);
        border: 1px solid var(--border-color, #e2e8f0);
        border-radius: 12px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
    }
    .stat-icon.primary { background: rgba(79, 70, 229, 0.1); color: #4f46e5; }
    .stat-icon.success { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .stat-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
    .stat-icon.danger  { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
    .stat-icon.info    { background: rgba(14, 165, 233, 0.1); color: #0ea5e9; }
    .stat-icon.secondary { background: rgba(100, 116, 139, 0.1); color: #64748b; }
    
    .stat-info .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary, #0f172a);
        line-height: 1.2;
    }
    .stat-info .stat-label {
        font-size: 0.82rem;
        color: var(--text-muted, #64748b);
        margin-top: 2px;
    }

    .badge-wa-terkirim  { background: rgba(16, 185, 129, 0.15); color: #047857; border: 1px solid rgba(16, 185, 129, 0.3); }
    .badge-wa-gagal     { background: rgba(239, 68, 68, 0.15); color: #b91c1c; border: 1px solid rgba(239, 68, 68, 0.3); }
    .badge-wa-pending   { background: rgba(245, 158, 11, 0.15); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.3); }
    .badge-wa-dilompati { background: rgba(100, 116, 139, 0.15); color: #475569; border: 1px solid rgba(100, 116, 139, 0.3); }

    .tag-btn {
        background: #f1f5f9;
        border: 1px solid #cbd5e1;
        color: #334155;
        font-size: 0.78rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .tag-btn:hover {
        background: #e2e8f0;
        color: #0f172a;
        border-color: #94a3b8;
    }
</style>
@endpush

@section('content')
<div class="content-body" style="padding: 24px;">

    @include('partials.flash')

    {{-- Header Banner & Action --}}
    <div class="card mb-4" style="border-radius: 14px; border: 1px solid var(--border-color, #e2e8f0); background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); color: #fff; padding: 24px;">
        <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px;">
            <div style="max-width: 600px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
                    <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #34d399; font-size: 0.78rem; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(52, 211, 153, 0.3);">
                        <i class="fa-solid fa-clock" style="margin-right: 4px;"></i> Otomatis Jam 09:00 WIB (Senin - Jumat)
                    </span>
                    @if(($sekolah->wa_status ?? '') === 'aktif')
                        <span class="badge" style="background: rgba(16, 185, 129, 0.2); color: #34d399; font-size: 0.78rem; padding: 4px 10px; border-radius: 20px;">
                            <i class="fa-solid fa-circle-check"></i> Gateway WA Aktif
                        </span>
                    @else
                        <span class="badge" style="background: rgba(239, 68, 68, 0.2); color: #f87171; font-size: 0.78rem; padding: 4px 10px; border-radius: 20px;">
                            <i class="fa-solid fa-triangle-exclamation"></i> Gateway WA Nonaktif
                        </span>
                    @endif
                </div>
                <h2 style="font-size: 1.35rem; font-weight: 700; margin: 0 0 6px 0; color: #fff;">Pengiriman WA Presensi Harian Siswa</h2>
                <p style="font-size: 0.88rem; opacity: 0.85; margin: 0; line-height: 1.5;">
                    Pengiriman laporan presensi ke nomor WhatsApp orang tua siswa (`no_wa_presensi`). Bila siswa hadir via fingerprint, jam kehadiran akan otomatis dicantumkan.
                </p>
            </div>

            <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                <button type="button" class="btn btn-secondary" onclick="openModal('modal-template-wa')" style="background: rgba(255,255,255,0.15); color: #fff; border: 1px solid rgba(255,255,255,0.25);">
                    <i class="fa-solid fa-sliders" style="margin-right: 6px;"></i> Template WA
                </button>
                <button type="button" class="btn btn-success" id="btn-kirim-masal" onclick="triggerKirimMasal()" style="background: #10b981; border: none; font-weight: 600;">
                    <i class="fa-solid fa-paper-plane" style="margin-right: 6px;"></i> Kirim WA Masal Sekarang
                </button>
            </div>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon primary">
                <i class="fa-solid fa-users"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['total_siswa']) }}</div>
                <div class="stat-label">Total Siswa</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon success">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['terkirim']) }}</div>
                <div class="stat-label">WA Terkirim</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon danger">
                <i class="fa-solid fa-circle-xmark"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['gagal']) }}</div>
                <div class="stat-label">WA Gagal</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon warning">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['pending']) }}</div>
                <div class="stat-label">Belum Dikirim</div>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon secondary">
                <i class="fa-solid fa-user-slash"></i>
            </div>
            <div class="stat-info">
                <div class="stat-value">{{ number_format($stats['dilompati']) }}</div>
                <div class="stat-label">Dilompati (Tanpa No WA)</div>
            </div>
        </div>
    </div>

    {{-- Filter Card --}}
    <div class="card mb-4" style="border-radius: 12px; padding: 20px; border: 1px solid var(--border-color, #e2e8f0);">
        <form method="GET" action="{{ route('presensi-siswa.wa-monitoring') }}" style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
            
            <div style="flex: 1; min-width: 180px;">
                <label style="font-size: 0.82rem; font-weight: 600; margin-bottom: 6px; display: block; color: var(--text-primary);">Tanggal Presensi</label>
                <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control" style="border-radius: 8px;">
            </div>

            <div style="flex: 1; min-width: 220px;">
                <label style="font-size: 0.82rem; font-weight: 600; margin-bottom: 6px; display: block; color: var(--text-primary);">Filter Kelas</label>
                <select name="id_kelas" class="form-select" style="border-radius: 8px;">
                    <option value="">-- Semua Kelas --</option>
                    @foreach($kelasList as $k)
                        <option value="{{ $k->id_kelas }}" {{ strval($id_kelas) === strval($k->id_kelas) ? 'selected' : '' }}>
                            {{ $k->tingkat }} {{ $k->rombel }} ({{ $k->jurusan->nama_jurusan ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1; min-width: 180px;">
                <label style="font-size: 0.82rem; font-weight: 600; margin-bottom: 6px; display: block; color: var(--text-primary);">Status Pengiriman WA</label>
                <select name="status_wa" class="form-select" style="border-radius: 8px;">
                    <option value="all" {{ $status_filter === 'all' || !$status_filter ? 'selected' : '' }}>-- Semua Status WA --</option>
                    <option value="pending" {{ $status_filter === 'pending' ? 'selected' : '' }}>Belum Dikirim (Pending)</option>
                    <option value="terkirim" {{ $status_filter === 'terkirim' ? 'selected' : '' }}>Terkirim</option>
                    <option value="gagal" {{ $status_filter === 'gagal' ? 'selected' : '' }}>Gagal</option>
                    <option value="dilompati" {{ $status_filter === 'dilompati' ? 'selected' : '' }}>Dilompati (Tanpa No WA)</option>
                </select>
            </div>

            <div style="display: flex; gap: 8px;">
                <button type="submit" class="btn btn-primary" style="border-radius: 8px; font-weight: 600;">
                    <i class="fa-solid fa-filter"></i> Filter
                </button>
                <a href="{{ route('presensi-siswa.wa-monitoring') }}" class="btn btn-secondary" style="border-radius: 8px;">
                    <i class="fa-solid fa-rotate-left"></i> Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Main Table Card --}}
    <div class="card" style="border-radius: 12px; border: 1px solid var(--border-color, #e2e8f0); overflow: hidden;">
        <div class="card-header" style="padding: 16px 20px; background: var(--bg-card, #fff); border-bottom: 1px solid var(--border-color, #e2e8f0); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: var(--text-primary);">
                    <i class="fa-solid fa-list-check" style="color: #4f46e5; margin-right: 6px;"></i> Daftar Monitoring WA Presensi ({{ Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }})
                </h3>
                <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 0.78rem;">{{ count($monitoringData) }} Siswa</span>
            </div>

            <div style="display: flex; gap: 8px; align-items: center;">
                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-select-all" onclick="toggleSelectAll()">
                    <i class="fa-regular fa-square-check"></i> Pilih Semua
                </button>
                <button type="button" class="btn btn-sm btn-success" id="btn-kirim-terpilih" onclick="triggerKirimTerpilih()" style="display: none;">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Terpilih (<span id="selected-count">0</span>)
                </button>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table" style="width: 100%; margin-bottom: 0; vertical-align: middle;">
                <thead>
                    <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; font-size: 0.82rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                        <th style="width: 40px; text-align: center;">
                            <input type="checkbox" id="check-all" onclick="handleCheckAll(this)" style="cursor: pointer;">
                        </th>
                        <th style="width: 50px; text-align: center;">No</th>
                        <th>Siswa & Kelas</th>
                        <th>No WA Presensi</th>
                        <th style="text-align: center;">Status Presensi</th>
                        <th style="text-align: center;">Jam Fingerprint</th>
                        <th style="text-align: center;">Status WA</th>
                        <th>Waktu & Respons</th>
                        <th style="text-align: center; width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($monitoringData as $index => $item)
                        @php
                            $siswa = $item['siswa'];
                            $log = $item['log_wa'];
                            $stPresensi = $item['status_presensi'];
                            $stWa = $item['status_wa'];
                        @endphp
                        <tr id="row-siswa-{{ $siswa->nis }}" style="border-bottom: 1px solid var(--border-color, #f1f5f9); font-size: 0.88rem;">
                            <td style="text-align: center;">
                                <input type="checkbox" class="check-siswa" value="{{ $siswa->nis }}" onchange="updateSelectedCount()" style="cursor: pointer;">
                            </td>
                            <td style="text-align: center; color: var(--text-muted);">{{ $index + 1 }}</td>
                            <td>
                                <div style="font-weight: 600; color: var(--text-primary);">{{ $siswa->nama_siswa }}</div>
                                <div style="font-size: 0.78rem; color: var(--text-muted);">
                                    NIS: {{ $siswa->nis }} &bull; {{ $siswa->kelas ? ($siswa->kelas->tingkat . ' ' . $siswa->kelas->rombel) : '-' }}
                                </div>
                            </td>
                            <td>
                                @if(!empty($item['no_wa']))
                                    <span style="font-family: monospace; font-weight: 600; color: var(--text-primary);">
                                        <i class="fa-brands fa-whatsapp text-success" style="margin-right: 4px;"></i> {{ $item['no_wa'] }}
                                    </span>
                                @else
                                    <span style="font-size: 0.78rem; color: #94a3b8; font-style: italic;">
                                        <i class="fa-solid fa-circle-exclamation text-warning" style="margin-right: 4px;"></i> Belum diisi
                                    </span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($stPresensi === 'Hadir')
                                    <span class="badge badge-success" style="padding: 5px 10px; border-radius: 6px;"><i class="fa-solid fa-check"></i> Hadir</span>
                                @elseif($stPresensi === 'Sakit')
                                    <span class="badge badge-warning" style="padding: 5px 10px; border-radius: 6px;"><i class="fa-solid fa-notes-medical"></i> Sakit</span>
                                @elseif($stPresensi === 'Izin')
                                    <span class="badge badge-info" style="padding: 5px 10px; border-radius: 6px;"><i class="fa-solid fa-envelope-open-text"></i> Izin</span>
                                @elseif($stPresensi === 'Alfa')
                                    <span class="badge badge-danger" style="padding: 5px 10px; border-radius: 6px;"><i class="fa-solid fa-xmark"></i> Alfa</span>
                                @else
                                    <span class="badge" style="background: #f1f5f9; color: #64748b; padding: 5px 10px; border-radius: 6px;"><i class="fa-regular fa-clock"></i> Belum Presensi</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($item['jam_presensi'] !== '-')
                                    <span class="badge" style="background: rgba(14, 165, 233, 0.1); color: #0284c7; border: 1px solid rgba(14, 165, 233, 0.3); font-family: monospace; font-size: 0.82rem; padding: 4px 8px;">
                                        <i class="fa-solid fa-fingerprint" style="margin-right: 4px;"></i> {{ $item['jam_presensi'] }}
                                    </span>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($stWa === 'terkirim')
                                    <span class="badge badge-wa-terkirim" style="padding: 5px 10px; border-radius: 20px; font-weight: 600;">
                                        <i class="fa-solid fa-check-double"></i> Terkirim
                                    </span>
                                @elseif($stWa === 'gagal')
                                    <span class="badge badge-wa-gagal" style="padding: 5px 10px; border-radius: 20px; font-weight: 600;">
                                        <i class="fa-solid fa-circle-exclamation"></i> Gagal
                                    </span>
                                @elseif($stWa === 'dilompati')
                                    <span class="badge badge-wa-dilompati" style="padding: 5px 10px; border-radius: 20px; font-weight: 500;">
                                        <i class="fa-solid fa-forward"></i> Dilompati
                                    </span>
                                @else
                                    <span class="badge badge-wa-pending" style="padding: 5px 10px; border-radius: 20px; font-weight: 500;">
                                        <i class="fa-regular fa-clock"></i> Pending
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($log && $log->sent_at)
                                    <div style="font-size: 0.78rem; color: var(--text-primary); font-weight: 500;">
                                        {{ $log->sent_at->format('d/m/Y H:i:s') }}
                                    </div>
                                @elseif($log && $log->response)
                                    @php
                                        $respObj = json_decode($log->response, true);
                                        $reason = $respObj['reason'] ?? ($respObj['message'] ?? 'Gagal dikirim');
                                    @endphp
                                    <div style="font-size: 0.76rem; color: #ef4444;" title="{{ $log->response }}">
                                        {{ Str::limit($reason, 35) }}
                                    </div>
                                @else
                                    <span style="font-size: 0.78rem; color: #94a3b8;">Belum diproses</span>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                <button type="button" class="btn btn-sm btn-primary btn-retry-single" 
                                        onclick="kirimSingleWA({{ $siswa->nis }})" 
                                        title="Kirim / Ulangi Kirim WA Siswa Ini" 
                                        style="border-radius: 6px; padding: 4px 8px;">
                                    <i class="fa-solid fa-paper-plane"></i> Kirim
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center" style="padding: 40px; color: var(--text-muted);">
                                <i class="fa-solid fa-inbox mb-2" style="font-size: 2.5rem; display: block; opacity: 0.5;"></i>
                                Tidak ada data siswa untuk filter ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Pengaturan Template WA --}}
<div class="modal-overlay" id="modal-template-wa">
    <div class="modal modal-lg" style="max-width: 680px; width: 92%;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-sliders" style="color: #4f46e5; margin-right: 8px;"></i> Pengaturan Template WA Presensi</h3>
            <button onclick="closeModal('modal-template-wa')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('presensi-siswa.wa-monitoring.update-template') }}" method="POST">
            @csrf
            <div class="modal-body" style="padding: 20px;">
                <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 12px; line-height: 1.4;">
                    Sesuaikan isi pesan WhatsApp yang akan dikirim secara otomatis ke orang tua siswa. Klik tombol tag di bawah untuk menyisipkan variabel otomatis ke pesan.
                </p>

                <div style="margin-bottom: 12px; display: flex; flex-wrap: wrap; gap: 6px; align-items: center;">
                    <span style="font-size: 0.78rem; font-weight: 600; color: #475569; margin-right: 4px;">Variabel Tersedia:</span>
                    <button type="button" class="tag-btn" onclick="insertTag('{nama_siswa}')">+{nama_siswa}</button>
                    <button type="button" class="tag-btn" onclick="insertTag('{nis}')">+{nis}</button>
                    <button type="button" class="tag-btn" onclick="insertTag('{kelas}')">+{kelas}</button>
                    <button type="button" class="tag-btn" onclick="insertTag('{tanggal}')">+{tanggal}</button>
                    <button type="button" class="tag-btn" onclick="insertTag('{status}')">+{status}</button>
                    <button type="button" class="tag-btn" onclick="insertTag('{jam_presensi}')">+{jam_presensi}</button>
                    <button type="button" class="tag-btn" onclick="insertTag('{keterangan}')">+{keterangan}</button>
                    <button type="button" class="tag-btn" onclick="insertTag('{nama_sekolah}')">+{nama_sekolah}</button>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label style="font-weight: 600; font-size: 0.85rem; margin-bottom: 6px; display: block;">Isi Format Pesan WhatsApp</label>
                    <textarea name="wa_template_presensi" id="wa_template_presensi" class="form-control" rows="10" style="font-family: monospace; font-size: 0.88rem; border-radius: 8px; line-height: 1.5;" required>{{ $sekolah->wa_template_presensi ?? '' }}</textarea>
                </div>

                <div style="background: #f8fafc; border: 1px dashed #cbd5e1; padding: 12px 16px; border-radius: 8px; font-size: 0.8rem; color: #475569;">
                    <i class="fa-solid fa-lightbulb text-warning" style="margin-right: 4px;"></i> <strong>Catatan:</strong> Jika status presensi siswa adalah <strong>Hadir</strong> (termasuk via fingerprint), variabel <code>{jam_presensi}</code> akan otomatis terisi dengan jam presensi siswa (contoh: <em>07:05:12 WIB</em>).
                </div>
            </div>
            <div class="modal-footer" style="padding: 14px 20px; display: flex; justify-content: flex-end; gap: 10px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-template-wa')">Batal</button>
                <button type="submit" class="btn btn-primary" style="font-weight: 600;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Template
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
function insertTag(tag) {
    const textarea = document.getElementById('wa_template_presensi');
    if (!textarea) return;
    const start = textarea.selectionStart;
    const end = textarea.selectionEnd;
    const text = textarea.value;
    textarea.value = text.substring(0, start) + tag + text.substring(end);
    textarea.selectionStart = textarea.selectionEnd = start + tag.length;
    textarea.focus();
}

function handleCheckAll(master) {
    const checkboxes = document.querySelectorAll('.check-siswa');
    checkboxes.forEach(cb => cb.checked = master.checked);
    updateSelectedCount();
}

function toggleSelectAll() {
    const master = document.getElementById('check-all');
    master.checked = !master.checked;
    handleCheckAll(master);
}

function updateSelectedCount() {
    const checked = document.querySelectorAll('.check-siswa:checked');
    const count = checked.length;
    const btnContainer = document.getElementById('btn-kirim-terpilih');
    const countSpan = document.getElementById('selected-count');
    
    if (countSpan) countSpan.textContent = count;
    if (btnContainer) {
        btnContainer.style.display = count > 0 ? 'inline-flex' : 'none';
    }
}

// ── Trigger Kirim WA Masal ──
function triggerKirimMasal() {
    if (!confirm('Apakah Anda yakin ingin memproses pengiriman WA presensi harian untuk seluruh siswa pada tanggal {{ $tanggal }}?')) {
        return;
    }

    showLoading('Memproses WA Presensi Masal', 'Sedang mengirim pesan WhatsApp ke orang tua siswa. Harap tunggu sebentar...');

    fetch("{{ route('presensi-siswa.wa-monitoring.send-masal') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            tanggal: "{{ $tanggal }}",
            id_kelas: "{{ $id_kelas }}"
        })
    })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert("Gagal: " + data.message);
        }
    })
    .catch(err => {
        hideLoading();
        alert("Terjadi kesalahan koneksi atau server: " + err.message);
    });
}

// ── Trigger Kirim WA Siswa Terpilih ──
function triggerKirimTerpilih() {
    const checked = document.querySelectorAll('.check-siswa:checked');
    const nisList = Array.from(checked).map(cb => cb.value);

    if (nisList.length === 0) {
        alert('Silakan pilih minimal 1 siswa.');
        return;
    }

    if (!confirm(`Kirim WA presensi ke ${nisList.length} siswa terpilih?`)) {
        return;
    }

    showLoading('Memproses WA Siswa Terpilih', `Sedang mengirim WA presensi ke ${nisList.length} siswa terpilih...`);

    fetch("{{ route('presensi-siswa.wa-monitoring.send-masal') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            tanggal: "{{ $tanggal }}",
            nis_list: nisList
        })
    })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            alert(data.message);
            window.location.reload();
        } else {
            alert("Gagal: " + data.message);
        }
    })
    .catch(err => {
        hideLoading();
        alert("Terjadi kesalahan koneksi: " + err.message);
    });
}

// ── Kirim Single WA (AJAX) ──
function kirimSingleWA(nis) {
    showLoading('Mengirim Pesan WA', 'Sedang mengirim pesan presensi...');

    fetch("{{ route('presensi-siswa.wa-monitoring.send-single') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({
            nis: nis,
            tanggal: "{{ $tanggal }}"
        })
    })
    .then(res => res.json())
    .then(data => {
        hideLoading();
        if (data.success) {
            alert("Berhasil: " + data.message);
            window.location.reload();
        } else {
            alert("Informasi: " + data.message);
            window.location.reload();
        }
    })
    .catch(err => {
        hideLoading();
        alert("Terjadi kesalahan: " + err.message);
    });
}
</script>
@endpush
