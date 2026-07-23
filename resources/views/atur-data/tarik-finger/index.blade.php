@extends('layouts.app')

@section('title', 'Tarik Data Finger — SmartSchool')
@section('header_title', 'Tarik Data Finger')
@section('header_subtitle', 'Lihat log absensi mesin finger dan sinkronkan ke presensi')

@push('styles')
<style>
.switch-toggle {
    position: relative;
    display: inline-block;
    width: 48px;
    height: 24px;
}
.switch-toggle input {
    opacity: 0;
    width: 0;
    height: 0;
}
.switch-slider {
    position: absolute;
    cursor: pointer;
    top: 0; left: 0; right: 0; bottom: 0;
    background-color: #cbd5e1;
    transition: .3s;
    border-radius: 24px;
}
.switch-slider:before {
    position: absolute;
    content: "";
    height: 18px;
    width: 18px;
    left: 3px;
    bottom: 3px;
    background-color: white;
    transition: .3s;
    border-radius: 50%;
    box-shadow: 0 1px 3px rgba(0,0,0,0.15);
}
input:checked + .switch-slider {
    background-color: #10b981;
}
input:checked + .switch-slider:before {
    transform: translateX(24px);
}
.divider-v {
    width: 1px;
    align-self: stretch;
    background-color: #e2e8f0;
}
@media (max-width: 768px) {
    .divider-v {
        width: 100%;
        height: 1px;
        margin: 12px 0;
        align-self: auto;
    }
}
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')


    {{-- Config Panel --}}
    <div class="card mb-6">
        <div class="card-header" style="border-bottom: 1px solid #f1f5f9; padding: 16px 24px;">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 8px; margin: 0; font-size: 1.1rem; color: #1e293b;">
                <i class="fa-solid fa-gears" style="color: var(--color-primary)"></i> Pengaturan & Aksi Sinkronisasi Mesin
            </h3>
        </div>
        <div class="card-body" style="padding: 24px;">
            <div style="display: flex; gap: 24px; flex-wrap: wrap; justify-content: space-between; align-items: stretch;">
                
                {{-- Left: Auto Sync Scheduling Form --}}
                <form action="{{ route('atur-data.tarik-finger.update-schedule') }}" method="POST" style="flex: 1; min-width: 300px; margin: 0; display: flex; flex-direction: column; justify-content: space-between; gap: 16px;">
                    @csrf
                    <div>
                        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 16px;">
                            <label class="switch-toggle">
                                <input type="checkbox" name="sync_otomatis" id="sync_otomatis" value="1" {{ ($sekolah && $sekolah->sync_otomatis) ? 'checked' : '' }}>
                                <span class="switch-slider"></span>
                            </label>
                            <div>
                                <label for="sync_otomatis" style="font-weight: 600; color: #1e293b; font-size: 0.95rem; cursor: pointer; display: block; margin-bottom: 2px;">
                                    Aktifkan Sinkronisasi Otomatis
                                </label>
                                <span style="font-size: 0.8rem; color: #64748b;">
                                    Jalankan penarikan data dari semua mesin finger secara berkala.
                                </span>
                            </div>
                        </div>

                        <div id="schedule_settings" style="display: {{ ($sekolah && $sekolah->sync_otomatis) ? 'block' : 'none' }}; background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 16px;">
                            <div class="form-grid-2" style="margin-bottom: 0; gap: 16px;">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label class="form-label" style="font-weight: 500; font-size: 0.85rem; margin-bottom: 6px;">Interval / Jadwal</label>
                                    <select name="sync_interval" id="sync_interval" class="form-control form-control-sm" style="height: 38px;">
                                        <option value="15" {{ ($sekolah && $sekolah->sync_interval == '15') ? 'selected' : '' }}>Setiap 15 Menit</option>
                                        <option value="30" {{ ($sekolah && ($sekolah->sync_interval == '30' || !$sekolah->sync_interval)) ? 'selected' : '' }}>Setiap 30 Menit</option>
                                        <option value="60" {{ ($sekolah && $sekolah->sync_interval == '60') ? 'selected' : '' }}>Setiap 1 Jam</option>
                                        <option value="120" {{ ($sekolah && $sekolah->sync_interval == '120') ? 'selected' : '' }}>Setiap 2 Jam</option>
                                        <option value="daily" {{ ($sekolah && $sekolah->sync_interval == 'daily') ? 'selected' : '' }}>Setiap Hari (Jam Tertentu)</option>
                                    </select>
                                </div>
                                <div class="form-group" id="sync_time_group" style="display: {{ ($sekolah && $sekolah->sync_interval == 'daily') ? 'block' : 'none' }}; margin-bottom: 0;">
                                    <label class="form-label" style="font-weight: 500; font-size: 0.85rem; margin-bottom: 6px;">Waktu Eksekusi (WIB)</label>
                                    <input type="time" name="sync_time" class="form-control form-control-sm" style="height: 38px;" value="{{ $sekolah ? $sekolah->sync_time : '00:00' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Pengaturan Jadwal
                        </button>
                    </div>
                </form>

                {{-- Vertical divider --}}
                <div class="divider-v"></div>

                {{-- Right: Manual Sync & Control Actions --}}
                <div style="flex: 1; min-width: 300px; display: flex; flex-direction: column; justify-content: space-between; gap: 16px;">
                    <div>
                        <h4 style="font-weight: 600; color: #1e293b; font-size: 0.95rem; margin: 0 0 4px 0;">Aksi Manual Mesin</h4>
                        <p style="font-size: 0.8rem; color: #64748b; margin: 0 0 12px 0;">Jalankan penarikan data secara manual atau hapus seluruh data presensi langsung di semua mesin finger.</p>
                    </div>
                    <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: auto;">
                        <form id="form-tarik-data" action="{{ route('atur-data.tarik-finger.tarik-proses') }}" method="POST" style="margin: 0; flex: 1; min-width: 140px;">
                            @csrf
                            <button type="button" id="btn-tarik-data" class="btn btn-primary btn-sm w-full" style="justify-content: center; height: 38px;">
                                <i class="fa-solid fa-download"></i> Tarik Data Sekarang
                            </button>
                        </form>

                        <form id="form-hapus-mesin" action="{{ route('atur-data.tarik-finger.hapus-mesin') }}" method="POST" style="margin: 0; flex: 1; min-width: 140px;">
                            @csrf
                            <button type="button" class="btn btn-danger btn-sm w-full" style="justify-content: center; height: 38px;" onclick="openModal('modal-confirm-hapus')">
                                <i class="fa-solid fa-trash-can"></i> Hapus Data di Semua Mesin
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Mesin List & Deletion Status Panel --}}
    <div class="card mb-6">
        <div class="card-header" style="border-bottom: 1px solid #f1f5f9; padding: 16px 24px;">
            <h3 class="card-title" style="display: flex; align-items: center; gap: 8px; margin: 0; font-size: 1.1rem; color: #1e293b;">
                <i class="fa-solid fa-server" style="color: var(--color-danger)"></i> Status Hapus & Monitoring Mesin Finger
            </h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table" style="width: 100%; border-collapse: collapse; text-align: left; margin: 0;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1.5px solid #e2e8f0; font-weight: 600; color: #475569; font-size: 0.85rem;">
                            <th style="padding: 14px 24px;">Nama Mesin</th>
                            <th style="padding: 14px 24px;">Serial Number (SN)</th>
                            <th style="padding: 14px 24px; text-align: center;">Jumlah Log Terakhir</th>
                            <th style="padding: 14px 24px;">Status Penghapusan</th>
                            <th style="padding: 14px 24px;">Pembaruan Terakhir</th>
                            <th style="padding: 14px 24px; text-align: center; width: 150px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 0.9rem; color: #334155;">
                        @forelse($mesinList as $mesin)
                        <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.15s;">
                            <td style="padding: 14px 24px; font-weight: 500; color: #1e293b;">{{ $mesin->nama_mesin }}</td>
                            <td style="padding: 14px 24px; font-family: monospace; font-size: 0.85rem; color: #64748b;">{{ $mesin->sn }}</td>
                            <td style="padding: 14px 24px; text-align: center;">
                                <span class="badge" style="background: #f1f5f9; color: #334155; padding: 4px 8px; border-radius: 6px; font-weight: 600;">{{ $mesin->data }} log</span>
                            </td>
                            <td style="padding: 14px 24px;">
                                @if($mesin->data == 0)
                                    <span class="badge" style="background: #d1fae5; color: #065f46; padding: 4px 10px; border-radius: 9999px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-circle-check"></i> Terhapus / Kosong
                                    </span>
                                @else
                                    <span class="badge" style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 9999px; font-size: 0.8rem; font-weight: 600; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-circle-exclamation"></i> Ada Data ({{ $mesin->data }})
                                    </span>
                                @endif
                            </td>
                            <td style="padding: 14px 24px; color: #64748b; font-size: 0.85rem;">
                                {{ $mesin->last_update ? \Carbon\Carbon::parse($mesin->last_update)->translatedFormat('d M Y H:i:s') : 'Belum pernah' }}
                            </td>
                            <td style="padding: 14px 24px; text-align: center;">
                                <form action="{{ route('atur-data.tarik-finger.hapus-mesin-single', $mesin->id_mesin) }}" method="POST" style="margin:0;" onsubmit="return confirmHapusSingle('{{ $mesin->nama_mesin }}', this)">
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-xs" style="padding: 4px 10px; font-size: 0.75rem; border-radius: 6px; display: inline-flex; align-items: center; gap: 4px;">
                                        <i class="fa-solid fa-trash-can"></i> Hapus Data
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="padding: 24px; text-align: center; color: #94a3b8;">Tidak ada mesin finger terdaftar</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Action Panel --}}
    <div class="grid-2-col mb-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fa-solid fa-rotate"></i> Sinkronkan ke Presensi</h3>
            </div>
            <form action="{{ route('atur-data.tarik-finger.sinkronkan') }}" method="POST" class="card-body" onsubmit="return confirm('Sinkronkan data log ke presensi?')">
                @csrf
                <p class="text-muted mb-4 text-sm">Pindahkan data log absensi ke tabel presensi resmi berdasarkan rentang tanggal.</p>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" class="form-control" value="{{ date('Y-m-01') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-full">
                    <i class="fa-solid fa-rotate"></i> Jalankan Sinkronisasi
                </button>
            </form>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title" style="color:var(--color-danger)"><i class="fa-solid fa-trash"></i> Hapus Log Absensi</h3>
            </div>
            <form action="{{ route('atur-data.tarik-finger.hapus') }}" method="POST" class="card-body" onsubmit="return confirm('Yakin hapus data log pada rentang tanggal ini? Tindakan tidak dapat dibatalkan!')">
                @csrf
                <p class="text-muted mb-4 text-sm">Hapus data log absensi dari mesin berdasarkan rentang tanggal.</p>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" name="tanggal_dari" class="form-control" value="{{ date('Y-m-01') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" name="tanggal_sampai" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-danger w-full">
                    <i class="fa-solid fa-trash"></i> Hapus Data Log
                </button>
            </form>
        </div>
    </div>

    {{-- Sync Status Summary --}}
    <div style="display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 16px;">
        @php
            $summaryItems = [
                ['label' => 'Tersinkron',       'key' => 'Tersinkron',       'color' => '#10b981', 'bg' => '#d1fae5', 'icon' => 'fa-circle-check'],
                ['label' => 'Belum Tersinkron', 'key' => 'Belum Tersinkron', 'color' => '#f59e0b', 'bg' => '#fef3c7', 'icon' => 'fa-clock'],
                ['label' => 'Data Sudah Ada',   'key' => 'Data sudah ada',   'color' => '#3b82f6', 'bg' => '#dbeafe', 'icon' => 'fa-circle-info'],
                ['label' => 'Gagal',            'key' => 'Gagal',            'color' => '#ef4444', 'bg' => '#fee2e2', 'icon' => 'fa-circle-xmark'],
                ['label' => 'Tidak Terdaftar',  'key' => 'Tidak Terdaftar',  'color' => '#94a3b8', 'bg' => '#f1f5f9', 'icon' => 'fa-user-xmark'],
            ];
        @endphp
        @foreach($summaryItems as $item)
            @php $count = $syncSummary[$item['key']] ?? 0; @endphp
            <a href="{{ route('atur-data.tarik-finger') }}?keterangan={{ urlencode($item['key']) }}"
               style="display: inline-flex; align-items: center; gap: 8px; background: {{ $item['bg'] }}; border: 1px solid {{ $item['color'] }}30; color: {{ $item['color'] }}; border-radius: 8px; padding: 8px 14px; font-size: 0.85rem; font-weight: 600; text-decoration: none; transition: all 0.2s; {{ request('keterangan') == $item['key'] ? 'box-shadow: 0 0 0 2px '.$item['color'].';' : '' }}">
                <i class="fa-solid {{ $item['icon'] }}"></i>
                {{ $item['label'] }}
                <span style="background: {{ $item['color'] }}; color: white; border-radius: 999px; padding: 1px 8px; font-size: 0.78rem; min-width: 22px; text-align: center;">{{ $count }}</span>
            </a>
        @endforeach
        @if(request()->filled('keterangan'))
            <a href="{{ route('atur-data.tarik-finger') }}" style="display: inline-flex; align-items: center; gap: 6px; color: #64748b; font-size: 0.85rem; padding: 8px 12px; text-decoration: none; border-radius: 8px; border: 1px solid #e2e8f0; background: white;">
                <i class="fa-solid fa-xmark"></i> Semua
            </a>
        @endif
    </div>

    {{-- Log Table --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-list-check"></i> Log Absensi Finger</h2>
            <form method="GET" class="search-form">
                <input type="date" name="tanggal" value="{{ request('tanggal') }}" class="form-control form-control-sm">
                <input type="number" name="nis" value="{{ request('nis') }}" placeholder="NIS..." class="form-control form-control-sm" style="width:120px">
                <select name="status" class="form-control form-control-sm">
                    <option value="">Semua Status</option>
                    <option value="Hadir" {{ request('status')=='Hadir'?'selected':'' }}>Hadir</option>
                    <option value="Izin"  {{ request('status')=='Izin'?'selected':'' }}>Izin</option>
                    <option value="Sakit" {{ request('status')=='Sakit'?'selected':'' }}>Sakit</option>
                    <option value="Alfa"  {{ request('status')=='Alfa'?'selected':'' }}>Alfa</option>
                </select>
                <select name="keterangan" class="form-control form-control-sm">
                    <option value="">Semua Keterangan</option>
                    <option value="Tersinkron"       {{ request('keterangan')=='Tersinkron'?'selected':'' }}>Tersinkron</option>
                    <option value="Belum Tersinkron"  {{ request('keterangan')=='Belum Tersinkron'?'selected':'' }}>Belum Tersinkron</option>
                    <option value="Data sudah ada"    {{ request('keterangan')=='Data sudah ada'?'selected':'' }}>Data Sudah Ada</option>
                    <option value="Gagal"             {{ request('keterangan')=='Gagal'?'selected':'' }}>Gagal</option>
                    <option value="Tidak Terdaftar"   {{ request('keterangan')=='Tidak Terdaftar'?'selected':'' }}>Tidak Terdaftar</option>
                </select>
                <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-filter"></i></button>
                @if(request()->anyFilled(['tanggal','nis','status','keterangan']))
                    <a href="{{ route('atur-data.tarik-finger') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-xmark"></i></a>
                @endif
            </form>
        </div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>NIS</th><th>Nama Siswa</th><th>Kelas</th><th>Tanggal</th><th>Jam</th><th>Status</th><th>Status Sinkronisasi</th></tr>
                </thead>
                <tbody>
                    @forelse($logList as $i => $log)
                    <tr>
                        <td>{{ $logList->firstItem() + $i }}</td>
                        <td class="font-mono">{{ $log->nis }}</td>
                        <td>
                            @if($log->siswa)
                                {{ $log->siswa->nama_siswa }}
                            @else
                                <span class="text-muted italic">Tidak dikenal</span>
                            @endif
                        </td>
                        <td>
                            @if($log->siswa && $log->siswa->kelas)
                                {{ $log->siswa->kelas->nama_kelas }}
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ \Carbon\Carbon::parse($log->tanggal)->format('d/m/Y') }}</td>
                        <td class="font-mono">{{ $log->jam }}</td>
                        <td>
                            @php $statusColor = ['Hadir'=>'badge-success','Izin'=>'badge-warning','Sakit'=>'badge-info','Alfa'=>'badge-danger']; @endphp
                            <span class="badge {{ $statusColor[$log->status] ?? 'badge-muted' }}">{{ $log->status }}</span>
                        </td>
                        <td>
                            @php
                                $ket = $log->keterangan;
                                $ketClass = match($ket) {
                                    'Tersinkron'      => 'badge-success',
                                    'Belum Tersinkron' => 'badge-warning',
                                    'Data sudah ada'  => 'badge-info',
                                    'Gagal'           => 'badge-danger',
                                    'Tidak Terdaftar' => 'badge-muted',
                                    default           => 'badge-muted',
                                };
                                $ketIcon = match($ket) {
                                    'Tersinkron'      => 'fa-circle-check',
                                    'Belum Tersinkron' => 'fa-clock',
                                    'Data sudah ada'  => 'fa-circle-info',
                                    'Gagal'           => 'fa-circle-xmark',
                                    'Tidak Terdaftar' => 'fa-user-xmark',
                                    default           => 'fa-minus',
                                };
                            @endphp
                            @if($ket)
                                <span class="badge {{ $ketClass }}" style="display: inline-flex; align-items: center; gap: 4px;">
                                    <i class="fa-solid {{ $ketIcon }}"></i> {{ $ket }}
                                </span>
                            @else
                                <span class="text-muted" style="font-size: 0.8rem;">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center text-muted py-6">Belum ada data log absensi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if(!$logList->isEmpty())
        <div class="card-footer" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 16px; background: #f8fafc; padding: 14px 24px; border-top: 1.5px solid #f1f5f9;">
            {{-- Left Side: Per Page Dropdown --}}
            <form method="GET" action="" style="margin: 0; display: flex; align-items: center; gap: 8px;">
                @if(request('tanggal')) <input type="hidden" name="tanggal" value="{{ request('tanggal') }}"> @endif
                @if(request('nis')) <input type="hidden" name="nis" value="{{ request('nis') }}"> @endif
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                
                <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">Tampilkan:</span>
                <select name="per_page" class="form-control form-control-sm" style="width: 80px; padding: 5px 10px; border-radius: 6px;" onchange="this.form.submit()">
                    <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('per_page') == 25 || !request('per_page') ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
                <span style="font-size: 0.85rem; color: #64748b; font-weight: 500;">baris</span>
            </form>

            {{-- Right Side: Pagination Links --}}
            <div style="margin-left: auto;">
                {{ $logList->links('pagination::bootstrap-4') }}
            </div>
        </div>
        @endif
    </div>
</div>

{{-- Modal Konfirmasi Hapus Data --}}
<div class="modal-overlay" id="modal-confirm-hapus">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3 style="color:var(--color-danger); display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-triangle-exclamation"></i> Konfirmasi Hapus Data
            </h3>
            <button onclick="closeModal('modal-confirm-hapus')" class="modal-close" type="button">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="modal-body" style="padding-top: 10px;">
            <p style="font-size: 0.9rem; color: #475569; line-height: 1.6; margin-bottom: 20px;">
                Apakah Anda yakin ingin menghapus seluruh data presensi di semua mesin finger? Tindakan ini akan mengosongkan log yang tersimpan di dalam mesin finger secara permanen!
            </p>
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" onclick="closeModal('modal-confirm-hapus')" class="btn btn-secondary btn-sm" style="border-radius: 8px; font-weight: 500;">Batal</button>
                <button type="button" id="btn-konfirmasi-submit-hapus" class="btn btn-danger btn-sm" style="border-radius: 8px; font-weight: 500; background-color: var(--color-danger);">Ya, Hapus Semua Data</button>
            </div>
        </div>
    </div>
</div>

{{-- Loading Overlay --}}
<div id="loading-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 99999; justify-content: center; align-items: center; flex-direction: column; color: white;">
    <div style="background: rgba(30, 41, 59, 0.95); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 16px; padding: 32px 40px; text-align: center; max-width: 420px; box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.5);">
        <i class="fa-solid fa-spinner fa-spin fa-3x" style="color: #ef4444; margin-bottom: 20px;"></i>
        <h3 style="font-weight: 600; margin: 0 0 8px 0; font-size: 1.20rem; color: #ffffff;">Menghapus Data Mesin Finger</h3>
        <p style="font-size: 0.85rem; color: #94a3b8; margin: 0; line-height: 1.5;">Sedang menghubungi semua mesin finger untuk menghapus log absensi. Mohon jangan menutup atau memuat ulang halaman ini.</p>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const syncOtomatis = document.getElementById('sync_otomatis');
    const scheduleSettings = document.getElementById('schedule_settings');
    const syncInterval = document.getElementById('sync_interval');
    const syncTimeGroup = document.getElementById('sync_time_group');

    if (syncOtomatis) {
        syncOtomatis.addEventListener('change', function() {
            scheduleSettings.style.display = this.checked ? 'block' : 'none';
        });
    }

    if (syncInterval) {
        syncInterval.addEventListener('change', function() {
            syncTimeGroup.style.display = this.value === 'daily' ? 'block' : 'none';
        });
    }

    const btnKonfirmasiSubmitHapus = document.getElementById('btn-konfirmasi-submit-hapus');
    if (btnKonfirmasiSubmitHapus) {
        btnKonfirmasiSubmitHapus.addEventListener('click', function() {
            closeModal('modal-confirm-hapus');
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                const loadingIcon = loadingOverlay.querySelector('.fa-spinner');
                const loadingTitle = loadingOverlay.querySelector('h3');
                const loadingText = loadingOverlay.querySelector('p');
                if (loadingIcon) loadingIcon.style.color = '#ef4444'; // Red spinner for clear
                if (loadingTitle) loadingTitle.textContent = 'Menghapus Data Mesin Finger';
                if (loadingText) loadingText.textContent = 'Sedang menghubungi semua mesin finger untuk menghapus log absensi. Mohon jangan menutup atau memuat ulang halaman ini.';
                loadingOverlay.style.display = 'flex';
            }
            document.getElementById('form-hapus-mesin').submit();
        });
    }

    const btnTarikData = document.getElementById('btn-tarik-data');
    if (btnTarikData) {
        btnTarikData.addEventListener('click', function() {
            const loadingOverlay = document.getElementById('loading-overlay');
            if (loadingOverlay) {
                const loadingIcon = loadingOverlay.querySelector('.fa-spinner');
                const loadingTitle = loadingOverlay.querySelector('h3');
                const loadingText = loadingOverlay.querySelector('p');
                if (loadingIcon) loadingIcon.style.color = '#3b82f6'; // Blue spinner for pull
                if (loadingTitle) loadingTitle.textContent = 'Menarik Data Mesin Finger';
                if (loadingText) loadingText.textContent = 'Sedang menghubungi semua mesin finger untuk menarik data presensi terbaru. Mohon jangan menutup atau memuat ulang halaman ini.';
                loadingOverlay.style.display = 'flex';
            }
            document.getElementById('form-tarik-data').submit();
        });
    }
});

function confirmHapusSingle(namaMesin, form) {
    if (confirm(`Apakah Anda yakin ingin menghapus data presensi pada mesin "${namaMesin}"? Tindakan ini akan mengosongkan log yang tersimpan di dalam mesin tersebut secara permanen!`)) {
        const loadingOverlay = document.getElementById('loading-overlay');
        if (loadingOverlay) {
            const loadingTitle = loadingOverlay.querySelector('h3');
            const loadingText = loadingOverlay.querySelector('p');
            if (loadingTitle) loadingTitle.textContent = `Mengosongkan Mesin: ${namaMesin}`;
            if (loadingText) loadingText.textContent = 'Sedang mengirim perintah hapus dan melakukan verifikasi data log. Mohon tunggu...';
            loadingOverlay.style.display = 'flex';
        }
        return true;
    }
    return false;
}
</script>
@endpush
