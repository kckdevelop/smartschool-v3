@extends('layouts.app')

@section('title', 'Pindah Penempatan PKL — SmartSchool')
@section('header_title', 'Pindah Penempatan PKL')
@section('header_subtitle', 'Proses perpindahan siswa antar DUDI & riwayat histori perpindahan')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- ══════════════════════════════════
         FILTER BAR
    ══════════════════════════════════ --}}
    <div class="card" style="margin-bottom:20px;">
        <div style="padding:14px 24px; border-bottom:1.5px solid #f1f5f9; background:#fafbff;">
            <form method="GET" action="{{ route('pkl.pindah-penempatan.index') }}" class="flex-row gap-3" style="flex-wrap:wrap; align-items:center; width:100%;">
                <div style="flex:1; min-width:200px; display:flex; flex-direction:column; gap:4px;">
                    <label class="form-label-sm" style="font-weight:700; color:var(--text-secondary); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.5px;">Gelombang PKL</label>
                    <select name="id_gelombang" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">-- Pilih Gelombang --</option>
                        @foreach($gelombangList as $g)
                        <option value="{{ $g->id_gelombang }}" {{ optional($selectedGelombang)->id_gelombang == $g->id_gelombang ? 'selected' : '' }}>
                            {{ $g->nama_gelombang }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex; align-items:flex-end; height:38px; margin-top:auto;">
                    <a href="{{ route('pkl.pindah-penempatan.index') }}" class="btn btn-secondary btn-sm" style="height:35px; display:inline-flex; align-items:center;">
                        <i class="fa-solid fa-rotate"></i> Reset
                    </a>
                </div>
                @if($selectedGelombang)
                <div style="display:flex; align-items:flex-end; height:38px; margin-top:auto; margin-left:auto;">
                    <button type="button" class="btn btn-primary btn-sm" onclick="openModal('modal-form-pindah')"
                        style="height:35px; display:inline-flex; align-items:center; gap:6px;">
                        <i class="fa-solid fa-right-left"></i> Pindahkan Siswa
                    </button>
                </div>
                @endif
            </form>
        </div>

        {{-- Info gelombang aktif --}}
        @if($selectedGelombang)
        <div style="padding:10px 24px; background:#f0fdfa; border-bottom:1.5px solid #ccfbf1; display:flex; align-items:center; gap:12px; flex-wrap:wrap;">
            <span style="font-size:.82rem; font-weight:700; color:var(--color-primary);">
                <i class="fa-solid fa-circle-info"></i>
                {{ $selectedGelombang->nama_gelombang }}
            </span>
            <span style="font-size:.82rem; color:var(--text-muted);">
                {{ \Carbon\Carbon::parse($selectedGelombang->tanggal_mulai)->format('d/m/Y') }} —
                {{ \Carbon\Carbon::parse($selectedGelombang->tanggal_selesai)->format('d/m/Y') }}
            </span>
            <span class="badge {{ $selectedGelombang->status === 'aktif' ? 'badge-success' : 'badge-muted' }}">
                {{ ucfirst($selectedGelombang->status) }}
            </span>
            <span style="font-size:.82rem; color:var(--text-muted);">
                Siswa aktif: <strong>{{ $siswaAktif->count() }}</strong> |
                Total perpindahan: <strong>{{ $riwayat->total() }}</strong>
            </span>
        </div>
        @endif
    </div>

    {{-- ══════════════════════════════════
         TABEL RIWAYAT PERPINDAHAN
    ══════════════════════════════════ --}}
    <div class="card">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; padding:18px 24px;">
            <h2 class="card-title" style="margin:0;">
                <i class="fa-solid fa-clock-rotate-left" style="color:var(--color-primary);"></i>
                Riwayat Perpindahan Penempatan PKL
            </h2>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Siswa</th>
                        <th>DUDI Asal</th>
                        <th>DUDI Tujuan</th>
                        <th>Tanggal Pindah</th>
                        <th>Alasan</th>
                        <th style="width:90px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riwayat as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:.8rem;">{{ $riwayat->firstItem() + $i }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $item->nis }}</div>
                            @if($item->siswa)
                            <div style="font-size:.8rem;color:var(--text-muted);">{{ $item->siswa->nama_siswa }}</div>
                            <div style="font-size:.75rem;"><span class="badge badge-info" style="font-size:.68rem;">{{ optional(optional($item->siswa)->kelas)->nama_kelas ?? '-' }}</span></div>
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:600; color:var(--color-danger); font-size:.85rem;">
                                <i class="fa-solid fa-building" style="opacity:.6;"></i>
                                {{ optional(optional($item->penempatanLama)->dudi)->nama_dudi ?? '-' }}
                            </div>
                            <div style="font-size:.75rem; color:var(--text-muted);">#{{ $item->id_penempatan_lama }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600; color:var(--color-success); font-size:.85rem;">
                                <i class="fa-solid fa-building" style="opacity:.6;"></i>
                                {{ optional(optional($item->penempatanBaru)->dudi)->nama_dudi ?? '-' }}
                            </div>
                            <div style="font-size:.75rem; color:var(--text-muted);">#{{ $item->id_penempatan_baru }}</div>
                        </td>
                        <td style="font-size:.85rem; white-space:nowrap;">
                            <i class="fa-regular fa-calendar" style="color:var(--text-muted);"></i>
                            {{ $item->tanggal_pindah->format('d/m/Y') }}
                        </td>
                        <td style="font-size:.82rem; max-width:200px;">
                            {{ $item->alasan ? \Illuminate\Support\Str::limit($item->alasan, 60) : '-' }}
                        </td>
                        <td class="action-cell">
                            <button class="btn-icon" title="Lihat Riwayat Siswa"
                                style="color:var(--color-primary);"
                                onclick="showHistory('{{ $item->nis }}', '{{ optional($selectedGelombang)->id_gelombang ?? '' }}')">
                                <i class="fa-solid fa-timeline"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-6">
                            <i class="fa-solid fa-clock-rotate-left" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada riwayat perpindahan penempatan{{ $selectedGelombang ? ' untuk gelombang ini' : '' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($riwayat->hasPages())
        <div class="card-footer">{{ $riwayat->links() }}</div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     MODAL: FORM PINDAH PENEMPATAN
══════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-form-pindah">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3><i class="fa-solid fa-right-left" style="color:var(--color-primary);"></i> Pindah Penempatan Siswa</h3>
            <button onclick="closeModal('modal-form-pindah')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-pindah" method="POST" action="{{ route('pkl.pindah-penempatan.store') }}">
            @csrf
            <div class="modal-body">

                {{-- Hidden fields --}}
                <input type="hidden" name="id_gelombang"      value="{{ optional($selectedGelombang)->id_gelombang }}">
                <input type="hidden" name="id_penempatan_lama" id="hidden_id_penempatan_lama">
                <input type="hidden" name="nis_siswa"          id="hidden_nis_siswa">

                {{-- ── STEP 1: Cari Siswa ── --}}
                <div style="background:#f8fafc; border-radius:10px; padding:16px; margin-bottom:16px; border:1.5px solid #e2e8f0;">
                    <div style="font-size:.8rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.5px; margin-bottom:12px;">
                        <i class="fa-solid fa-1" style="background:var(--color-primary);color:#fff;border-radius:50%;width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;margin-right:6px;"></i>
                        Pilih Siswa yang Dipindah
                    </div>

                    {{-- Live Search Input --}}
                    <div class="form-group" style="margin-bottom:0; position:relative;">
                        <label class="form-label">Cari Siswa (Nama / NIS) <span class="required">*</span></label>
                        <div style="position:relative;">
                            <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:12px; top:50%; transform:translateY(-50%); color:var(--text-muted); pointer-events:none; z-index:1;"></i>
                            <input type="text" id="pindah_search_siswa" class="form-control"
                                placeholder="Ketik nama atau NIS siswa..."
                                autocomplete="off"
                                style="padding-left:36px;"
                                {{ $siswaAktif->isEmpty() ? 'disabled' : '' }}>
                            <div id="pindah_search_clear" onclick="clearSiswaSearch()"
                                style="position:absolute; right:12px; top:50%; transform:translateY(-50%); cursor:pointer; color:var(--text-muted); display:none; font-size:.9rem;">
                                <i class="fa-solid fa-xmark"></i>
                            </div>
                        </div>

                        {{-- Dropdown hasil pencarian --}}
                        <div id="siswa-dropdown" style="
                            display:none; position:absolute; top:100%; left:0; right:0;
                            background:#fff; border:1.5px solid #cbd5e1; border-top:none;
                            border-radius:0 0 10px 10px;
                            box-shadow:0 8px 24px rgba(0,0,0,.10); z-index:1000;
                            max-height:240px; overflow-y:auto;">
                        </div>

                        @if($siswaAktif->isEmpty())
                        <div style="font-size:.78rem; color:var(--color-warning); margin-top:6px;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            Tidak ada siswa aktif di gelombang ini.
                        </div>
                        @else
                        <div style="font-size:.75rem; color:var(--text-muted); margin-top:4px;">
                            <i class="fa-solid fa-circle-info"></i>
                            Total <strong>{{ $siswaAktif->count() }}</strong> siswa aktif tersedia.
                            Ketik nama atau NIS untuk mencari.
                        </div>
                        @endif
                    </div>

                    {{-- Chip siswa terpilih --}}
                    <div id="siswa-selected-chip" style="display:none; margin-top:10px; background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:8px; padding:10px 14px; align-items:center; gap:10px;">
                        <i class="fa-solid fa-circle-user" style="color:var(--color-primary); font-size:1.3rem; flex-shrink:0;"></i>
                        <div style="flex:1;">
                            <div style="font-weight:700; font-size:.9rem;" id="chip_nama_siswa">—</div>
                            <div style="font-size:.75rem; color:var(--text-muted);" id="chip_info_siswa">—</div>
                        </div>
                        <button type="button" onclick="clearSiswaSearch()" style="background:none; border:none; cursor:pointer; color:var(--text-muted); padding:4px 8px; white-space:nowrap; flex-shrink:0;">
                            <i class="fa-solid fa-xmark"></i> Ganti
                        </button>
                    </div>
                </div>

                {{-- ── Info penempatan saat ini ── --}}
                <div id="info-penempatan-lama" style="display:none; background:#fff8f0; border:1.5px solid #fed7aa; border-radius:10px; padding:14px; margin-bottom:16px;">
                    <div style="font-size:.8rem; font-weight:700; color:#9a3412; text-transform:uppercase; letter-spacing:.5px; margin-bottom:10px;">
                        <i class="fa-solid fa-building" style="color:#9a3412;"></i> Penempatan Saat Ini (Akan Ditutup)
                    </div>
                    <div class="form-grid-2">
                        <div>
                            <div style="font-size:.75rem; color:var(--text-muted);">DUDI / Perusahaan</div>
                            <div style="font-weight:700; font-size:.9rem;" id="lama_nama_dudi">—</div>
                            <div style="font-size:.78rem; color:var(--text-muted);" id="lama_kota_dudi"></div>
                        </div>
                        <div>
                            <div style="font-size:.75rem; color:var(--text-muted);">Pembimbing</div>
                            <div style="font-weight:600; font-size:.85rem;" id="lama_pembimbing">—</div>
                            <div style="font-size:.75rem; color:var(--text-muted);">Masuk: <span id="lama_tgl_masuk">—</span></div>
                        </div>
                    </div>
                </div>

                {{-- ── STEP 2: Penempatan Baru ── --}}
                <div style="background:#f0fdf4; border-radius:10px; padding:16px; margin-bottom:16px; border:1.5px solid #bbf7d0;">
                    <div style="font-size:.8rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.5px; margin-bottom:12px;">
                        <i class="fa-solid fa-2" style="background:var(--color-success);color:#fff;border-radius:50%;width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;margin-right:6px;"></i>
                        Penempatan Baru (Tujuan)
                    </div>
                    <div class="form-group">
                        <label class="form-label">DUDI / Perusahaan Tujuan <span class="required">*</span></label>
                        <select name="id_dudi_baru" id="pindah_dudi_baru" class="form-control" required onchange="loadPembimbingByDudi()">
                            <option value="">-- Pilih DUDI Tujuan --</option>
                            @foreach($dudis as $d)
                            <option value="{{ $d->id_dudi }}">{{ $d->nama_dudi }}{{ $d->kota ? ' — ' . $d->kota : '' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
                            Pembimbing di Tempat Baru
                            <span id="pembimbing-loading" style="display:none; font-size:.72rem; color:var(--text-muted); font-weight:400;">
                                <i class="fa-solid fa-spinner fa-spin"></i> memuat...
                            </span>
                            <span id="pembimbing-badge" style="display:none; font-size:.7rem; padding:2px 8px; border-radius:4px; font-weight:600;"></span>
                        </label>
                        <select name="id_pembimbing_baru" id="pindah_pembimbing_baru" class="form-control">
                            <option value="">-- Pilih DUDI terlebih dahulu --</option>
                        </select>
                        <div id="pembimbing-hint" style="font-size:.75rem; color:var(--color-success); margin-top:4px; display:none;">
                            <i class="fa-solid fa-circle-check"></i>
                            Pembimbing otomatis terisi berdasarkan DUDI tujuan yang dipilih. Anda bisa mengubahnya.
                        </div>
                    </div>
                    <div class="form-grid-2">
                        <div class="form-group">
                            <label class="form-label">Tanggal Mulai di Tempat Baru <span class="required">*</span></label>
                            <input type="date" name="tanggal_pindah" id="pindah_tanggal" class="form-control" required
                                value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Estimasi Tanggal Selesai</label>
                            <input type="date" name="tanggal_keluar_est" id="pindah_keluar_est" class="form-control"
                                value="{{ optional($selectedGelombang)?->tanggal_selesai }}">
                        </div>
                    </div>
                </div>

                {{-- ── STEP 3: Alasan ── --}}
                <div style="background:#f8fafc; border-radius:10px; padding:16px; border:1.5px solid #e2e8f0;">
                    <div style="font-size:.8rem; font-weight:700; color:var(--text-secondary); text-transform:uppercase; letter-spacing:.5px; margin-bottom:12px;">
                        <i class="fa-solid fa-3" style="background:var(--color-info);color:#fff;border-radius:50%;width:20px;height:20px;display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;margin-right:6px;"></i>
                        Keterangan Perpindahan
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Alasan Perpindahan</label>
                        <textarea name="alasan" id="pindah_alasan" class="form-control" rows="3"
                            placeholder="Contoh: Perusahaan tutup, kuota penuh, permintaan orang tua, dll..."></textarea>
                    </div>
                </div>

                {{-- Warning --}}
                <div style="background:#fef9c3; border:1.5px solid #fde047; border-radius:8px; padding:10px 14px; margin-top:14px; font-size:.8rem; color:#713f12;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <strong>Perhatian:</strong> Proses ini akan menutup penempatan siswa saat ini (status menjadi <em>Pindah</em>)
                    dan membuat penempatan baru di DUDI yang dipilih. Riwayat perpindahan akan tersimpan otomatis.
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-form-pindah')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-submit-pindah" disabled>
                    <i class="fa-solid fa-right-left"></i> Proses Pindah
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     MODAL: TIMELINE HISTORY SISWA
══════════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-history-siswa">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="history-modal-title"><i class="fa-solid fa-timeline" style="color:var(--color-primary);"></i> Riwayat Perpindahan Siswa</h3>
            <button onclick="closeModal('modal-history-siswa')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" id="history-modal-body">
            <div style="text-align:center; padding:40px; color:var(--text-muted);">
                <i class="fa-solid fa-spinner fa-spin" style="font-size:2rem;"></i>
                <div style="margin-top:8px;">Memuat data...</div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeModal('modal-history-siswa')" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</div>

@push('scripts')
<style>
/* ── Siswa dropdown items ── */
.siswa-option {
    padding: 10px 14px; cursor: pointer; border-bottom: 1px solid #f1f5f9;
    transition: background .12s; display: flex; align-items: center; gap: 10px;
}
.siswa-option:last-child { border-bottom: none; }
.siswa-option:hover, .siswa-option.focused { background: #f0f9ff; }
.siswa-option .siswa-avatar {
    width: 34px; height: 34px; border-radius: 50%;
    background: linear-gradient(135deg, var(--color-primary), #6366f1);
    color: #fff; display: flex; align-items: center; justify-content: center;
    font-size: .85rem; font-weight: 700; flex-shrink: 0;
}
.siswa-option .siswa-info { flex: 1; min-width: 0; }
.siswa-option .siswa-name { font-weight: 700; font-size: .85rem; }
.siswa-option .siswa-meta { font-size: .72rem; color: var(--text-muted); }
.siswa-option .siswa-dudi { font-size: .72rem; color: #d97706; font-weight: 600; }
.siswa-option mark { background: #fef08a; border-radius: 2px; padding: 0 1px; font-style: normal; }
.siswa-no-result { padding: 20px; text-align: center; color: var(--text-muted); font-size: .85rem; }
/* ── Timeline ── */
.timeline-wrapper { position: relative; padding: 8px 0; }
.timeline-item { display: flex; gap: 16px; margin-bottom: 24px; position: relative; }
.timeline-item:not(:last-child)::before {
    content: ''; position: absolute; left: 18px; top: 38px; bottom: -24px;
    width: 2px; background: linear-gradient(to bottom, var(--color-primary), #e2e8f0); z-index: 0;
}
.timeline-dot {
    flex-shrink: 0; width: 38px; height: 38px; border-radius: 50%;
    background: var(--color-primary); display: flex; align-items: center;
    justify-content: center; color: #fff; z-index: 1;
    box-shadow: 0 2px 8px rgba(0,0,0,.12);
}
.timeline-content { flex: 1; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 12px 16px; }
.timeline-date { font-size: .75rem; color: var(--text-muted); font-weight: 600; margin-bottom: 6px; }
.timeline-route { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 6px; }
.timeline-dudi { font-size: .85rem; font-weight: 700; padding: 4px 10px; border-radius: 6px; }
.timeline-dudi.lama { background: #fee2e2; color: #991b1b; }
.timeline-dudi.baru { background: #dcfce7; color: #166534; }
.timeline-alasan { font-size: .78rem; color: var(--text-muted); font-style: italic; margin-top: 4px; }
</style>

<script>
const GELOMBANG_ID = '{{ optional($selectedGelombang)->id_gelombang }}';

// ═══════════════════════════════════════════════════════
// LIVE SEARCH SISWA
// ═══════════════════════════════════════════════════════
let searchDebounce = null;
const searchInput = document.getElementById('pindah_search_siswa');
const dropdown    = document.getElementById('siswa-dropdown');
const clearBtn    = document.getElementById('pindah_search_clear');
const chipEl      = document.getElementById('siswa-selected-chip');

if (searchInput) {
    searchInput.addEventListener('input', function () {
        const val = this.value.trim();
        clearBtn.style.display = val ? 'block' : 'none';
        clearTimeout(searchDebounce);
        searchDebounce = setTimeout(() => fetchSiswa(val), 280);
    });

    searchInput.addEventListener('focus', function () {
        fetchSiswa(this.value.trim());
    });

    searchInput.addEventListener('keydown', function (e) {
        const items   = dropdown.querySelectorAll('.siswa-option');
        const focused = dropdown.querySelector('.siswa-option.focused');
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (!focused) { items[0]?.classList.add('focused'); }
            else {
                const next = focused.nextElementSibling;
                if (next?.classList.contains('siswa-option')) {
                    focused.classList.remove('focused'); next.classList.add('focused');
                    next.scrollIntoView({ block: 'nearest' });
                }
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (focused) {
                const prev = focused.previousElementSibling;
                focused.classList.remove('focused');
                if (prev?.classList.contains('siswa-option')) {
                    prev.classList.add('focused'); prev.scrollIntoView({ block: 'nearest' });
                }
            }
        } else if (e.key === 'Enter') {
            e.preventDefault(); if (focused) focused.click();
        } else if (e.key === 'Escape') {
            hideDropdown();
        }
    });
}

document.addEventListener('click', function (e) {
    if (!e.target.closest('#pindah_search_siswa') && !e.target.closest('#siswa-dropdown')) {
        hideDropdown();
    }
});

function fetchSiswa(keyword) {
    if (!GELOMBANG_ID) return;
    fetch(`/pkl/pindah-penempatan/search-siswa?id_gelombang=${GELOMBANG_ID}&q=${encodeURIComponent(keyword)}`)
        .then(r => r.json())
        .then(list => renderDropdown(list, keyword));
}

function renderDropdown(list, keyword) {
    dropdown.innerHTML = '';
    if (list.length === 0) {
        dropdown.innerHTML = `<div class="siswa-no-result"><i class="fa-solid fa-user-slash"></i> Tidak ada siswa aktif yang cocok</div>`;
    } else {
        list.forEach(s => {
            const div = document.createElement('div');
            div.className = 'siswa-option';
            const hl = str => keyword
                ? str.replace(new RegExp(`(${keyword.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')})`, 'gi'), '<mark>$1</mark>')
                : str;
            div.innerHTML = `
                <div class="siswa-avatar">${s.nama_siswa.charAt(0).toUpperCase()}</div>
                <div class="siswa-info">
                    <div class="siswa-name">${hl(s.nama_siswa)}</div>
                    <div class="siswa-meta">${hl(s.nis)} &middot; ${s.nama_kelas}</div>
                    <div class="siswa-dudi"><i class="fa-solid fa-building" style="font-size:.65rem;"></i> ${s.nama_dudi}</div>
                </div>`;
            div.addEventListener('mousedown', e => { e.preventDefault(); selectSiswa(s); });
            dropdown.appendChild(div);
        });
    }
    dropdown.style.display = 'block';
}

function hideDropdown() { dropdown.style.display = 'none'; }

function selectSiswa(s) {
    document.getElementById('hidden_nis_siswa').value  = s.nis;
    document.getElementById('chip_nama_siswa').textContent = s.nama_siswa;
    document.getElementById('chip_info_siswa').textContent =
        `NIS: ${s.nis} · Kelas: ${s.nama_kelas} · Saat ini di: ${s.nama_dudi}`;
    chipEl.style.display      = 'flex';
    searchInput.style.display = 'none';
    clearBtn.style.display    = 'none';
    hideDropdown();
    loadPenempatanAktif(s.nis);
}

function clearSiswaSearch() {
    document.getElementById('hidden_nis_siswa').value         = '';
    document.getElementById('hidden_id_penempatan_lama').value = '';
    chipEl.style.display      = 'none';
    searchInput.style.display = 'block';
    searchInput.value         = '';
    clearBtn.style.display    = 'none';
    document.getElementById('info-penempatan-lama').style.display = 'none';
    document.getElementById('btn-submit-pindah').disabled = true;
    searchInput.focus();
}

// ═══════════════════════════════════════════════════════
// LOAD PENEMPATAN AKTIF
// ═══════════════════════════════════════════════════════
function loadPenempatanAktif(nis) {
    const infoBox   = document.getElementById('info-penempatan-lama');
    const btnSubmit = document.getElementById('btn-submit-pindah');
    if (!nis || !GELOMBANG_ID) { infoBox.style.display = 'none'; btnSubmit.disabled = true; return; }

    fetch(`/pkl/pindah-penempatan/penempatan-aktif?nis=${encodeURIComponent(nis)}&id_gelombang=${GELOMBANG_ID}`)
        .then(r => r.json())
        .then(data => {
            if (!data) { infoBox.style.display = 'none'; btnSubmit.disabled = true; return; }
            document.getElementById('hidden_id_penempatan_lama').value = data.id_penempatan;
            document.getElementById('lama_nama_dudi').textContent      = data.nama_dudi || '—';
            document.getElementById('lama_kota_dudi').textContent      = data.kota_dudi || '';
            document.getElementById('lama_pembimbing').textContent     = data.nama_pembimbing || '— Tanpa pembimbing';
            document.getElementById('lama_tgl_masuk').textContent      = data.tanggal_masuk || '—';
            infoBox.style.display = 'block';
            btnSubmit.disabled    = false;
        })
        .catch(() => { infoBox.style.display = 'none'; btnSubmit.disabled = true; });
}

// ═══════════════════════════════════════════════════════
// AUTO-FILL PEMBIMBING SAAT PILIH DUDI
// ═══════════════════════════════════════════════════════
function loadPembimbingByDudi() {
    const idDudi    = document.getElementById('pindah_dudi_baru').value;
    const pbSelect  = document.getElementById('pindah_pembimbing_baru');
    const loadingEl = document.getElementById('pembimbing-loading');
    const badgeEl   = document.getElementById('pembimbing-badge');
    const hintEl    = document.getElementById('pembimbing-hint');

    pbSelect.innerHTML    = '<option value="">-- Tidak ada pembimbing --</option>';
    badgeEl.style.display = 'none';
    hintEl.style.display  = 'none';

    if (!idDudi || !GELOMBANG_ID) {
        pbSelect.innerHTML = '<option value="">-- Pilih DUDI terlebih dahulu --</option>';
        return;
    }

    loadingEl.style.display = 'inline';
    fetch(`/pkl/pindah-penempatan/pembimbing-by-dudi?id_dudi=${idDudi}&id_gelombang=${GELOMBANG_ID}`)
        .then(r => r.json())
        .then(list => {
            loadingEl.style.display = 'none';
            pbSelect.innerHTML = '<option value="">-- Tanpa Pembimbing --</option>';
            if (list.length === 0) {
                badgeEl.textContent      = 'Belum ada pembimbing untuk DUDI ini';
                badgeEl.style.background = '#fee2e2';
                badgeEl.style.color      = '#991b1b';
                badgeEl.style.display    = 'inline';
                return;
            }
            list.forEach((pb, idx) => {
                const opt      = document.createElement('option');
                opt.value      = pb.id_pembimbing;
                opt.textContent = pb.nama_guru;
                if (idx === 0) opt.selected = true; // auto-pilih pertama
                pbSelect.appendChild(opt);
            });
            badgeEl.textContent      = `${list.length} pembimbing tersedia`;
            badgeEl.style.background = '#dcfce7';
            badgeEl.style.color      = '#166534';
            badgeEl.style.display    = 'inline';
            hintEl.style.display     = 'block';
        })
        .catch(() => {
            loadingEl.style.display = 'none';
            pbSelect.innerHTML = '<option value="">-- Gagal memuat --</option>';
        });
}

// ═══════════════════════════════════════════════════════
// MODAL TIMELINE HISTORY
// ═══════════════════════════════════════════════════════
function showHistory(nis, idGelombang) {
    document.getElementById('history-modal-title').innerHTML =
        '<i class="fa-solid fa-timeline" style="color:var(--color-primary);"></i> Riwayat Perpindahan Siswa';
    document.getElementById('history-modal-body').innerHTML =
        '<div style="text-align:center;padding:40px;color:var(--text-muted);"><i class="fa-solid fa-spinner fa-spin" style="font-size:2rem;"></i><div style="margin-top:8px;">Memuat data...</div></div>';
    openModal('modal-history-siswa');

    fetch(`/pkl/pindah-penempatan/history/${encodeURIComponent(nis)}${idGelombang ? '?id_gelombang=' + idGelombang : ''}`)
        .then(r => r.json())
        .then(data => {
            if (data.siswa) {
                document.getElementById('history-modal-title').innerHTML =
                    `<i class="fa-solid fa-timeline" style="color:var(--color-primary);"></i>
                     Riwayat — ${data.siswa.nama_siswa}
                     <span style="font-size:.8rem;font-weight:400;">(${data.siswa.nis} · ${data.siswa.nama_kelas || '-'})</span>`;
            }
            const body = document.getElementById('history-modal-body');
            if (!data.history || data.history.length === 0) {
                body.innerHTML = `<div style="text-align:center;padding:40px;color:var(--text-muted);">
                    <i class="fa-solid fa-clock-rotate-left" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:12px;"></i>
                    Belum ada riwayat perpindahan untuk siswa ini.</div>`;
                return;
            }
            let html = `<div class="timeline-wrapper">`;
            data.history.forEach((item, idx) => {
                html += `
                <div class="timeline-item">
                    <div class="timeline-dot"><i class="fa-solid fa-right-left" style="font-size:.75rem;"></i></div>
                    <div class="timeline-content">
                        <div class="timeline-date">
                            <i class="fa-regular fa-calendar"></i> ${item.tanggal_pindah}
                            <span style="margin-left:8px;font-size:.7rem;background:#e0f2fe;color:#0369a1;padding:2px 7px;border-radius:4px;">
                                Perpindahan ke-${idx + 1}
                            </span>
                        </div>
                        <div class="timeline-route">
                            <div class="timeline-dudi lama"><i class="fa-solid fa-building"></i> ${item.dudi_lama}</div>
                            <div style="color:var(--text-muted);"><i class="fa-solid fa-arrow-right"></i></div>
                            <div class="timeline-dudi baru"><i class="fa-solid fa-building"></i> ${item.dudi_baru}</div>
                        </div>
                        ${item.alasan ? `<div class="timeline-alasan"><i class="fa-solid fa-quote-left" style="font-size:.65rem;"></i> ${item.alasan}</div>` : ''}
                        <div style="font-size:.72rem;color:var(--text-muted);margin-top:6px;">
                            Penempatan #${item.id_penempatan_lama} → #${item.id_penempatan_baru}
                        </div>
                    </div>
                </div>`;
            });
            html += '</div>';
            body.innerHTML = `
                <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:12px 16px;margin-bottom:16px;display:flex;align-items:center;gap:12px;">
                    <i class="fa-solid fa-circle-check" style="color:var(--color-success);font-size:1.2rem;"></i>
                    <div>
                        <div style="font-size:.85rem;font-weight:700;">Total ${data.history.length} kali perpindahan</div>
                        <div style="font-size:.75rem;color:var(--text-muted);">Dari ${data.history[0].dudi_lama} hingga terakhir di ${data.history[data.history.length-1].dudi_baru}</div>
                    </div>
                </div>` + html;
        })
        .catch(() => {
            document.getElementById('history-modal-body').innerHTML =
                '<div style="text-align:center;padding:40px;color:var(--color-danger);">Gagal memuat data riwayat.</div>';
        });
}

// Reset form saat tutup modal
function resetFormPindah() {
    clearSiswaSearch();
    document.getElementById('pindah_dudi_baru').value           = '';
    document.getElementById('pindah_pembimbing_baru').innerHTML = '<option value="">-- Pilih DUDI terlebih dahulu --</option>';
    document.getElementById('pindah_alasan').value              = '';
    document.getElementById('pembimbing-badge').style.display   = 'none';
    document.getElementById('pembimbing-hint').style.display    = 'none';
}
document.getElementById('modal-form-pindah').addEventListener('click', function (e) {
    if (e.target === this) resetFormPindah();
});
</script>
@endpush
@endsection
