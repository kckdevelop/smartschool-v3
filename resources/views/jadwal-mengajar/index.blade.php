@extends('layouts.app')

@section('title', 'Jadwal Mengajar Harian — SmartSchool')
@section('header_title', 'Jadwal Mengajar')
@section('header_subtitle', 'Jadwal mengajar harian guru ter-generate dari siklus')

@section('content')
<style>
/* ─── Control Grid ─── */
.control-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 20px;
    margin-bottom: 20px;
}
@media (max-width: 992px) {
    .control-grid { grid-template-columns: 1fr; }
}

/* ─── Calendar Day Callout ─── */
.cycle-callout {
    background: #f0fdf4;
    border: 1px solid #bbf7d0;
    border-radius: 12px;
    padding: 16px;
    margin-bottom: 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 12px;
}
.cycle-callout.holiday {
    background: #fef2f2;
    border-color: #fca5a5;
}
.cycle-callout-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.cycle-callout-icon {
    width: 42px;
    height: 42px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.3rem;
}
.cycle-callout-info {
    display: flex;
    flex-direction: column;
}
.cycle-callout-title {
    font-weight: 800;
    font-size: 0.95rem;
    color: var(--text-primary);
}
.cycle-callout-desc {
    font-size: 0.8rem;
    color: var(--text-muted);
    margin-top: 2px;
}

/* ─── Action Button Group ─── */
.btn-nav-template {
    background-color: var(--color-primary);
    color: #fff;
    font-weight: 700;
    padding: 10px 18px;
    border-radius: 10px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    border: none;
    text-decoration: none;
    font-size: 0.88rem;
    box-shadow: 0 4px 12px rgba(13,148,136,0.15);
}
.btn-nav-template:hover {
    background-color: #0f766e;
    color: #fff;
    transform: translateY(-1px);
}
</style>

<div class="page-content">
    @include('partials.flash')

    {{-- Main Top Callout: Dynamic Cycle Status for Selected Date --}}
    @php
        $formattedDate = \Carbon\Carbon::parse($tanggal)->translatedFormat('l, d F Y');
        $isLibur = !$siklusHari || $siklusHari->status == 'Libur' || $siklusHari->hari_ke == 'Off';
    @endphp

    <div class="cycle-callout {{ $isLibur ? 'holiday' : '' }}">
        <div class="cycle-callout-left">
            <div class="cycle-callout-icon" style="background: {{ $isLibur ? 'rgba(239,68,68,0.1)' : 'rgba(16,185,129,0.1)' }}; color: {{ $isLibur ? '#ef4444' : '#10b981' }};">
                <i class="fa-solid {{ $isLibur ? 'fa-calendar-minus' : 'fa-calendar-check' }}"></i>
            </div>
            <div class="cycle-callout-info">
                <div class="cycle-callout-title">
                    Tanggal: {{ $formattedDate }}
                </div>
                <div class="cycle-callout-desc">
                    @if($siklusHari)
                        Status Kalender Siklus: <strong>{{ $siklusHari->status }}</strong>
                        @if($siklusHari->keterangan) — {{ $siklusHari->keterangan }} @endif
                    @else
                        <span style="color:#ef4444;"><i class="fa-solid fa-triangle-exclamation"></i> Kalender siklus belum ter-generate untuk tanggal ini.</span>
                    @endif
                </div>
            </div>
        </div>
        <div>
            <a href="{{ route('jadwal-mengajar.template') }}" class="btn-nav-template">
                <i class="fa-solid fa-list-check"></i> Kelola Template Jadwal Siklus
            </a>
        </div>
    </div>

    {{-- Control Panels --}}
    <div class="control-grid">
        {{-- Generate Schedules --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-gears"></i> Generate Jadwal Harian dari Template</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('jadwal-mengajar.generate') }}" method="POST">
                    @csrf
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-bottom: 12px;">
                        <div class="form-group">
                            <label class="form-label">Tanggal Mulai <span class="required">*</span></label>
                            <input type="date" name="tanggal_mulai" id="gen_mulai" class="form-control" required value="{{ $tanggal }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Tanggal Selesai <span class="required">*</span></label>
                            <input type="date" name="tanggal_selesai" id="gen_selesai" class="form-control" required value="{{ date('Y-m-d', strtotime($tanggal . ' +14 days')) }}">
                        </div>
                    </div>
                    <div style="font-size:0.76rem;color:var(--text-muted);margin-bottom:4px;display:flex;align-items:center;gap:6px;">
                        <i class="fa-solid fa-circle-info" style="color:#0ea5e9;"></i>
                        Tanggal mulai otomatis diset ke <strong>Senin terdekat</strong> (D1). Selesai ke <strong>Jumat minggu berikutnya</strong> (D10 = 2 pekan siklus).
                    </div>
                    <div style="text-align: right; margin-top: 16px;">
                        <button type="submit" class="btn btn-primary" style="padding: 10px 20px;">
                            <i class="fa-solid fa-circle-play"></i> Generate Jadwal Mengajar
                        </button>
                    </div>
                </form>

            </div>
        </div>

        {{-- Clear Schedules --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title" style="color:#dc2626;"><i class="fa-solid fa-eraser"></i> Kosongkan Jadwal</h2>
            </div>
            <div class="card-body">
                <form action="{{ route('jadwal-mengajar.clear') }}" method="POST" id="form-clear-jadwal" onsubmit="return confirmClearJadwal(event)">
                    @csrf
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:12px; margin-bottom: 12px;">
                        <div class="form-group">
                            <label class="form-label">Dari <span class="required">*</span></label>
                            <input type="date" name="clear_start_date" id="clear_start_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Sampai <span class="required">*</span></label>
                            <input type="date" name="clear_end_date" id="clear_end_date" class="form-control" required>
                        </div>
                    </div>
                    <div style="text-align: right; margin-top: 16px;">
                        <button type="submit" class="btn btn-danger" style="background:#dc2626; border-color:#dc2626; padding: 10px 20px; width:100%;">
                            <i class="fa-solid fa-trash-can"></i> Hapus Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ─── Daily Schedule Table Card ─── --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-chalkboard-user"></i> Jadwal Mengajar Tanggal {{ \Carbon\Carbon::parse($tanggal)->format('d/m/Y') }}</h2>
            <div class="card-header-right">
                <form method="GET" class="search-form" style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                    <input type="date" name="tanggal" value="{{ $tanggal }}" class="form-control form-control-sm" style="width:145px;" onchange="this.form.submit()">
                    <select name="id_guru" class="form-control form-control-sm" style="min-width:150px;">
                        <option value="">Semua Guru</option>
                        @foreach($guruList as $g)
                            <option value="{{ $g->id_guru }}" {{ request('id_guru') == $g->id_guru ? 'selected' : '' }}>{{ $g->nama_guru }}</option>
                        @endforeach
                    </select>
                    <select name="id_kelas" class="form-control form-control-sm" style="min-width:130px;">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->tingkat }} {{ $k->rombel }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="btn btn-secondary btn-sm"><i class="fa-solid fa-filter"></i></button>
                    @if(request()->hasAny(['tanggal','id_guru','id_kelas']) && request('tanggal') != date('Y-m-d'))
                        <a href="{{ route('jadwal-mengajar.index') }}" class="btn btn-secondary btn-sm" style="background:#f1f5f9; color:#64748b;" title="Reset Filter"><i class="fa-solid fa-xmark"></i></a>
                    @endif
                </form>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 80px; text-align: center;">Jam Ke</th>
                        <th>Guru Pengajar</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Keterangan / Status</th>
                        <th style="width: 220px; text-align: center;">Jurnal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($harianList as $h)
                    <tr>
                        <td class="text-center" style="font-weight: 700; color: var(--color-primary); font-size: 1.05rem;">
                            {{ $h->jam_ke }}
                        </td>
                        <td>
                            <strong>{{ $h->guru->nama_guru ?? '—' }}</strong>
                        </td>
                        <td style="font-size: 0.88rem;">{{ $h->mapel->nama_mapel ?? '—' }}</td>
                        <td>
                            @if($h->kelas)
                                <span class="badge badge-info" style="font-size:0.75rem;">
                                    {{ $h->kelas->tingkat }} {{ $h->kelas->rombel }}
                                </span>
                            @else —
                            @endif
                        </td>
                        <td>
                            @if($h->status == 'KBM')
                                <span class="badge badge-success" style="font-size:0.7rem; padding:3px 6px;"><i class="fa-solid fa-graduation-cap"></i> KBM</span>
                            @else
                                <span class="badge badge-danger" style="font-size:0.7rem; padding:3px 6px;">{{ $h->status }}</span>
                            @endif
                            <span style="font-size: 0.78rem; color:var(--text-muted); margin-left: 6px;">{{ $h->keterangan }}</span>
                        </td>
                        <td style="text-align: center; vertical-align: middle; padding: 10px 6px;">
                            @if($h->status == 'KBM')
                                @if($h->jurnal)
                                    <div style="display:flex; flex-direction:column; gap:4px; align-items:center; justify-content:center;">
                                        <span class="badge badge-success" style="font-size:0.7rem; padding:3px 6px; background-color:#10b981; color:#fff;"><i class="fa-solid fa-file-signature"></i> Sudah Mengisi</span>
                                        @if($h->jurnal->status_approval === 'approved')
                                            <span class="badge badge-success" style="font-size:0.65rem; padding:2px 4px; background:#10b981; color:#fff; border: 1px solid #059669;"><i class="fa-solid fa-circle-check"></i> Disetujui</span>
                                        @elseif($h->jurnal->status_approval === 'rejected')
                                            <span class="badge badge-danger" style="font-size:0.65rem; padding:2px 4px; background:#ef4444; color:#fff; border: 1px solid #dc2626;"><i class="fa-solid fa-circle-xmark"></i> Ditolak</span>
                                        @else
                                            <span class="badge badge-warning" style="font-size:0.65rem; padding:2px 4px; color:#fff; background-color:#f59e0b; border: 1px solid #d97706;"><i class="fa-solid fa-clock"></i> Pending</span>
                                        @endif
                                    </div>
                                @else
                                    <span class="badge badge-secondary" style="font-size:0.7rem; padding:3px 6px; background-color:#94a3b8; color:#fff;"><i class="fa-solid fa-circle-minus"></i> Belum Mengisi</span>
                                @endif
                            @else
                                <span style="color:var(--text-muted); font-size:0.8rem;">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center" style="padding:40px 20px; color:var(--text-muted);">
                            <i class="fa-solid fa-chalkboard" style="font-size:2.5rem; opacity:0.2; display:block; margin-bottom:10px;"></i>
                            @if($isLibur)
                                Hari ini merupakan Hari Libur / Non-KBM. Tidak ada jadwal mengajar.
                            @else
                                Belum ada data jadwal mengajar harian ter-generate untuk tanggal ini. Klik <strong>Generate Jadwal Harian</strong> di atas untuk membuat.
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ─── Modal Cek Jurnal ─── --}}
<div class="modal-overlay" id="modal-cek-jurnal">
    <div class="modal modal-md" style="max-width:520px; width:95vw;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-book-open" style="color:var(--color-primary, #0d9488);"></i> Detail Jurnal Guru</h3>
            <button onclick="closeCekJurnalModal()" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="padding-top:10px;">
            <table class="detail-table" style="width:100%; border-collapse:collapse; font-size:0.9rem; margin-bottom:15px;">
                <tr>
                    <td style="padding:8px 0; font-weight:700; width:140px; color:var(--text-muted); border-bottom:1px solid #f1f5f9;">Guru Pengajar</td>
                    <td style="padding:8px 0; border-bottom:1px solid #f1f5f9;" id="view-jurnal-guru"></td>
                </tr>
                <tr>
                    <td style="padding:8px 0; font-weight:700; color:var(--text-muted); border-bottom:1px solid #f1f5f9;">Mata Pelajaran</td>
                    <td style="padding:8px 0; border-bottom:1px solid #f1f5f9;" id="view-jurnal-mapel"></td>
                </tr>
                <tr>
                    <td style="padding:8px 0; font-weight:700; color:var(--text-muted); border-bottom:1px solid #f1f5f9;">Kelas</td>
                    <td style="padding:8px 0; border-bottom:1px solid #f1f5f9;" id="view-jurnal-kelas"></td>
                </tr>
                <tr>
                    <td style="padding:8px 0; font-weight:700; color:var(--text-muted); border-bottom:1px solid #f1f5f9;">Tanggal & Jam</td>
                    <td style="padding:8px 0; border-bottom:1px solid #f1f5f9;" id="view-jurnal-waktu"></td>
                </tr>
                <tr>
                    <td style="padding:8px 0; font-weight:700; color:var(--text-muted); border-bottom:1px solid #f1f5f9;">Materi Pelajaran</td>
                    <td style="padding:8px 0; font-weight:700; color:var(--text-primary); border-bottom:1px solid #f1f5f9;" id="view-jurnal-materi"></td>
                </tr>
                <tr>
                    <td style="padding:8px 0; font-weight:700; color:var(--text-muted); border-bottom:1px solid #f1f5f9;">Siswa Hadir</td>
                    <td style="padding:8px 0; border-bottom:1px solid #f1f5f9;" id="view-jurnal-hadir"></td>
                </tr>
                <tr>
                    <td style="padding:8px 0; font-weight:700; color:var(--text-muted); border-bottom:1px solid #f1f5f9;">Absensi Siswa</td>
                    <td style="padding:8px 0; border-bottom:1px solid #f1f5f9;" id="view-jurnal-absen"></td>
                </tr>
                <tr>
                    <td style="padding:8px 0; font-weight:700; color:var(--text-muted); border-bottom:1px solid #f1f5f9;">Catatan / Keterangan</td>
                    <td style="padding:8px 0; border-bottom:1px solid #f1f5f9; color:var(--text-muted);" id="view-jurnal-keterangan"></td>
                </tr>
                <tr>
                    <td style="padding:8px 0; font-weight:700; color:var(--text-muted);">Status Persetujuan</td>
                    <td style="padding:8px 0;" id="view-jurnal-status"></td>
                </tr>
            </table>

            <div id="jurnal-action-buttons" style="display:flex; gap:10px; margin-top:20px; justify-content:flex-end; border-top:1px solid #e2e8f0; padding-top:15px;">
                <form id="form-approve-jurnal" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="background:#10b981; border-color:#10b981; padding:8px 16px;">
                        <i class="fa-solid fa-check"></i> Setujui (Approve)
                    </button>
                </form>
                <form id="form-reject-jurnal" method="POST" style="display:inline;">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="background:#ef4444; border-color:#ef4444; padding:8px 16px;">
                        <i class="fa-solid fa-xmark"></i> Tolak (Reject)
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmClearJadwal(e) {
    e.preventDefault();
    const start = document.getElementById('clear_start_date').value;
    const end = document.getElementById('clear_end_date').value;
    if (!start || !end) {
        alert('Mohon tentukan tanggal mulai dan tanggal selesai pengosongan.');
        return false;
    }
    
    if (confirm(`Apakah Anda yakin ingin menghapus data jadwal mengajar harian dari tanggal ${start} sampai dengan ${end}?`)) {
        document.getElementById('form-clear-jadwal').submit();
    }
}

// ─── Auto-set tanggal_mulai ke Senin terdekat, tanggal_selesai ke Jumat 2 minggu --
document.addEventListener('DOMContentLoaded', function () {
    const mulaiInput   = document.getElementById('gen_mulai');
    const selesaiInput = document.getElementById('gen_selesai');
    if (!mulaiInput || !selesaiInput) return;

    function getNearestMonday(fromDate) {
        const d = new Date(fromDate);
        d.setHours(0, 0, 0, 0);
        const day = d.getDay(); // 0=Sun, 1=Mon ... 6=Sat
        if (day === 0) {
            d.setDate(d.getDate() + 1); // Sunday → next Monday
        } else if (day !== 1) {
            d.setDate(d.getDate() + (8 - day)); // Any other day → next Monday
        }
        // day === 1 → already Monday, no change
        return d;
    }

    function toYMD(d) {
        const y = d.getFullYear();
        const m = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        return `${y}-${m}-${dd}`;
    }

    function syncSelesai() {
        const mulaiVal = mulaiInput.value;
        if (!mulaiVal) return;
        const monday = getNearestMonday(mulaiVal + 'T00:00:00');
        // D1 = monday (week 1), D6 = monday + 7 days, D10 = monday + 11 days (Fri week 2)
        const friday2 = new Date(monday);
        friday2.setDate(monday.getDate() + 11); // Monday + 11 = Friday of week 2
        // Also snap mulai to nearest monday
        mulaiInput.value = toYMD(monday);
        selesaiInput.value = toYMD(friday2);
    }

    // Only auto-set if the value hasn't been manually customised
    // (i.e. only run on page load if mulai == today or is already a Monday)
    const initialDate = new Date(mulaiInput.value + 'T00:00:00');
    const isMonday    = initialDate.getDay() === 1;
    if (!isMonday) {
        syncSelesai();
    }

    mulaiInput.addEventListener('change', syncSelesai);
});

function openCekJurnalModal(guru, mapel, kelas, waktu, materi, hadir, absen, keterangan, status, id_kemajuan) {
    document.getElementById('view-jurnal-guru').textContent = guru;
    document.getElementById('view-jurnal-mapel').textContent = mapel;
    document.getElementById('view-jurnal-kelas').textContent = kelas;
    document.getElementById('view-jurnal-waktu').textContent = waktu;
    document.getElementById('view-jurnal-materi').textContent = materi;
    document.getElementById('view-jurnal-hadir').textContent = hadir + ' siswa';
    document.getElementById('view-jurnal-absen').textContent = absen || 'Tidak ada';
    document.getElementById('view-jurnal-keterangan').textContent = keterangan || 'Tidak ada';
    
    // Status Badge
    let statusHtml = '';
    if (status === 'approved') {
        statusHtml = '<span class="badge badge-success" style="font-size:0.8rem; padding:4px 8px; background-color:#10b981; color:#fff;"><i class="fa-solid fa-circle-check"></i> Disetujui</span>';
        document.getElementById('jurnal-action-buttons').style.display = 'none'; // Hide if approved
    } else if (status === 'rejected') {
        statusHtml = '<span class="badge badge-danger" style="font-size:0.8rem; padding:4px 8px; background-color:#ef4444; color:#fff;"><i class="fa-solid fa-circle-xmark"></i> Ditolak</span>';
        document.getElementById('jurnal-action-buttons').style.display = 'flex'; // Show if rejected/pending
    } else {
        statusHtml = '<span class="badge badge-warning" style="font-size:0.8rem; padding:4px 8px; color:#fff; background-color:#f59e0b;"><i class="fa-solid fa-clock"></i> Pending</span>';
        document.getElementById('jurnal-action-buttons').style.display = 'flex'; // Show if pending
    }
    document.getElementById('view-jurnal-status').innerHTML = statusHtml;

    // Set form actions dynamically
    document.getElementById('form-approve-jurnal').action = `/jurnal-guru/${id_kemajuan}/approve`;
    document.getElementById('form-reject-jurnal').action = `/jurnal-guru/${id_kemajuan}/reject`;

    openModal('modal-cek-jurnal');
}

function closeCekJurnalModal() {
    closeModal('modal-cek-jurnal');
}
</script>
@endpush
@endsection
