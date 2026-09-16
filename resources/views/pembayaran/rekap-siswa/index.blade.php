@extends('layouts.app')

@section('title', 'Rekap Tagihan Per Siswa — SmartSchool')
@section('header_title', 'Rekap Tagihan Per Siswa')
@section('header_subtitle', 'Daftar siswa dengan kode VA dan ringkasan tagihan SPP, Non SPP, Tunggakan')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- ═══════════════════════════════════════════════════
         SECTION 1: FILTER SISWA
    ══════════════════════════════════════════════════════ --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('pembayaran.rekap-siswa.index') }}" method="GET"
                  style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">

                <div style="flex: 2; min-width: 240px;">
                    <label class="form-label" style="font-size: 0.85rem; font-weight: 600;">
                        <i class="fa-solid fa-magnifying-glass"></i> Cari Siswa (NIS / Nama)
                    </label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Ketik NIS atau nama siswa..."
                           value="{{ request('search') }}">
                </div>

                <div style="flex: 1; min-width: 160px;">
                    <label class="form-label" style="font-size: 0.85rem; font-weight: 600;">Kelas</label>
                    <select name="id_kelas" class="form-control">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass"></i> Cari
                    </button>
                    <a href="{{ route('pembayaran.rekap-siswa.index') }}" class="btn btn-secondary" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                    <a href="{{ route('pembayaran.rekap-siswa.cetak-kelas', ['id_kelas' => request('id_kelas')]) }}"
                       target="_blank"
                       class="btn btn-success"
                       title="Cetak Rekap PDF per Kelas"
                       style="font-weight: 700;">
                        <i class="fa-solid fa-print"></i> Cetak Rekap PDF
                    </a>
                </div>
            </form>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════
         SECTION 2: STATISTIKA RINGKASAN GLOBAL
    ══════════════════════════════════════════════════════ --}}

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="border-left: 4px solid #3b82f6;">
            <div class="card-body" style="padding: 16px;">
                <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Siswa</div>
                <div style="font-size: 1.6rem; font-weight: 800; color: #3b82f6; margin-top: 2px;">
                    {{ $siswaListPaginated->total() }} Siswa
                </div>
            </div>
        </div>
        <div class="card" style="border-left: 4px solid #10b981;">
            <div class="card-body" style="padding: 16px;">
                <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Tagihan</div>
                <div style="font-size: 1.3rem; font-weight: 800; color: #10b981; margin-top: 2px;">
                    Rp {{ number_format($totalNominalAll, 0, ',', '.') }}
                </div>
            </div>
        </div>
        <div class="card" style="border-left: 4px solid #8b5cf6;">
            <div class="card-body" style="padding: 16px;">
                <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Total Terbayar</div>
                <div style="font-size: 1.3rem; font-weight: 800; color: #8b5cf6; margin-top: 2px;">
                    Rp {{ number_format($totalTerbayarAll, 0, ',', '.') }}
                </div>
            </div>
        </div>
        <div class="card" style="border-left: 4px solid #ef4444;">
            <div class="card-body" style="padding: 16px;">
                <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Sisa Belum Bayar</div>
                <div style="font-size: 1.3rem; font-weight: 800; color: #ef4444; margin-top: 2px;">
                    Rp {{ number_format($totalBelumBayarAll, 0, ',', '.') }}
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         SECTION 3: DAFTAR SISWA & REKAP STATUS TAGIHAN
    ══════════════════════════════════════════════════════ --}}
    <div class="card mb-4">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <h3 class="card-title">
                <i class="fa-solid fa-users"></i> Rekap Tagihan Per Siswa
            </h3>
            <div style="font-size: 0.82rem; color: var(--text-muted);">
                Status pembayaran tagihan SPP, Non-SPP, Tunggakan, dan Sisa Tagihan aktif per siswa
            </div>
        </div>
        <div class="card-body p-0" style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                <thead>
                    <tr style="background: var(--bg-hover, #f8fafc); text-transform: uppercase; font-size: 0.75rem; color: var(--text-secondary); letter-spacing: 0.5px;">
                        <th style="padding: 12px 14px; white-space: nowrap;">#</th>
                        <th style="padding: 12px 14px; white-space: nowrap;">NIS / Nama Siswa</th>
                        <th style="padding: 12px 14px; white-space: nowrap;">Kelas</th>
                        <th style="padding: 12px 14px; text-align: right; white-space: nowrap;">SPP (Tagihan / Bayar)</th>
                        <th style="padding: 12px 14px; text-align: right; white-space: nowrap;">Non SPP (Tagihan / Bayar)</th>
                        <th style="padding: 12px 14px; text-align: right; white-space: nowrap;">Tunggakan (Tagihan / Bayar)</th>
                        <th style="padding: 12px 14px; text-align: right; white-space: nowrap;">Total Tagihan</th>
                        <th style="padding: 12px 14px; text-align: right; white-space: nowrap;">Total Terbayar</th>
                        <th style="padding: 12px 14px; text-align: right; white-space: nowrap; background: #fee2e2; color: #991b1b;">
                            <i class="fa-solid fa-clock-rotate-left"></i> Sisa Tagihan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapSiswa as $index => $row)
                        @php
                            $siswa = $row['siswa'];
                        @endphp
                        <tr style="border-bottom: 1px solid var(--border-color, #e2e8f0);">
                            {{-- Nomor urut --}}
                            <td style="padding: 12px 14px; color: var(--text-muted); font-size: 0.8rem;">
                                {{ ($siswaListPaginated->currentPage() - 1) * $siswaListPaginated->perPage() + $index + 1 }}
                            </td>

                            {{-- NIS + Nama Siswa --}}
                            <td style="padding: 12px 14px;">
                                <div style="font-weight: 700; color: var(--text-primary);">{{ strtoupper($siswa->nama_siswa) }}</div>
                                <div style="font-size: 0.78rem; color: var(--text-muted); font-family: monospace;">NIS: {{ $siswa->nis }}</div>
                            </td>

                            {{-- Kelas --}}
                            <td style="padding: 12px 14px; font-weight: 600; white-space: nowrap;">
                                {{ $siswa->kelas->nama_kelas ?? '-' }}
                            </td>

                            {{-- SPP Nominal / Bayar --}}
                            <td style="padding: 12px 14px; text-align: right;">
                                @if($row['spp_nominal'] > 0)
                                    <div style="font-weight: 700; color: #1e293b;">Rp {{ number_format($row['spp_nominal'], 0, ',', '.') }}</div>
                                    <div style="font-size: 0.78rem; color: {{ $row['spp_bayar'] >= $row['spp_nominal'] ? '#10b981' : '#ef4444' }};">
                                        Bayar: Rp {{ number_format($row['spp_bayar'], 0, ',', '.') }}
                                    </div>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.82rem;">—</span>
                                @endif
                            </td>

                            {{-- Non SPP Nominal / Bayar --}}
                            <td style="padding: 12px 14px; text-align: right;">
                                @if($row['non_spp_nominal'] > 0)
                                    <div style="font-weight: 700; color: #1e293b;">Rp {{ number_format($row['non_spp_nominal'], 0, ',', '.') }}</div>
                                    <div style="font-size: 0.78rem; color: {{ $row['non_spp_bayar'] >= $row['non_spp_nominal'] ? '#10b981' : '#ef4444' }};">
                                        Bayar: Rp {{ number_format($row['non_spp_bayar'], 0, ',', '.') }}
                                    </div>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.82rem;">—</span>
                                @endif
                            </td>

                            {{-- Tunggakan Nominal / Bayar --}}
                            <td style="padding: 12px 14px; text-align: right;">
                                @if($row['tunggakan_nominal'] > 0)
                                    <div style="font-weight: 700; color: #1e293b;">Rp {{ number_format($row['tunggakan_nominal'], 0, ',', '.') }}</div>
                                    <div style="font-size: 0.78rem; color: {{ $row['tunggakan_bayar'] >= $row['tunggakan_nominal'] ? '#10b981' : '#ef4444' }};">
                                        Bayar: Rp {{ number_format($row['tunggakan_bayar'], 0, ',', '.') }}
                                    </div>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.82rem;">—</span>
                                @endif
                            </td>

                            {{-- Total Tagihan --}}
                            <td style="padding: 12px 14px; text-align: right;">
                                @if($row['total_nominal'] > 0)
                                    <div style="font-weight: 800; color: #1e293b;">Rp {{ number_format($row['total_nominal'], 0, ',', '.') }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">{{ $row['tagihan_count'] }} item tagihan</div>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.82rem;">Rp 0</span>
                                @endif
                            </td>

                            {{-- Total Terbayar --}}
                            <td style="padding: 12px 14px; text-align: right;">
                                @if($row['total_bayar'] > 0)
                                    <div style="font-weight: 700; color: #15803d;">Rp {{ number_format($row['total_bayar'], 0, ',', '.') }}</div>
                                @else
                                    <span style="color: var(--text-muted); font-size: 0.82rem;">Rp 0</span>
                                @endif
                            </td>

                            {{-- Sisa Tagihan --}}
                            <td style="padding: 12px 14px; text-align: right; background: rgba(254,226,226,0.25);">
                                @if($row['total_nominal'] <= 0)
                                    <span style="color: var(--text-muted); font-size: 0.8rem; font-style: italic;">Tidak Ada Tagihan</span>
                                @elseif($row['sisa_pembayaran'] <= 0)
                                    <span class="badge" style="background: #dcfce7; color: #15803d; font-weight: 700; font-size: 0.8rem; padding: 4px 8px; border-radius: 6px;">
                                        <i class="fa-solid fa-circle-check"></i> Lunas
                                    </span>
                                @else
                                    <div style="font-weight: 800; color: #dc2626; font-size: 0.92rem;">
                                        Rp {{ number_format($row['sisa_pembayaran'], 0, ',', '.') }}
                                    </div>
                                    <div style="font-size: 0.72rem; color: #b91c1c; font-weight: 600;">Belum Lunas</div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 40px; color: var(--text-muted);">
                                <i class="fa-solid fa-users-slash" style="font-size: 2rem; margin-bottom: 8px; display: block;"></i>
                                Tidak ada data siswa ditemukan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="card-footer" style="padding: 14px 16px;">
            {{ $siswaListPaginated->withQueryString()->links() }}
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════════════════════
     MODAL: TAMBAH TAGIHAN SISWA
═══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-tambah-tagihan" style="display: none;">
    <div class="modal modal-md" style="max-width: 560px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-plus-circle"></i> Tambah Tagihan Siswa</h3>
            <button type="button" onclick="closeModal('modal-tambah-tagihan')" class="modal-close">&times;</button>
        </div>
        <form action="{{ route('pembayaran.rekap-siswa.store') }}" method="POST">
            @csrf
            <div class="modal-body" style="display: flex; flex-direction: column; gap: 14px;">

                {{-- Info siswa terpilih --}}
                <div id="info_siswa_selected" style="display: none; background: #ede9fe; padding: 10px 14px; border-radius: 8px; border-left: 3px solid #7c3aed;">
                    <div style="font-size: 0.8rem; color: #5b21b6; font-weight: 600;">Siswa Dipilih:</div>
                    <div id="info_siswa_nama" style="font-weight: 700; color: #3730a3; font-size: 1rem;"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Pilih Siswa <span class="text-danger">*</span></label>
                    <select name="nis" id="select_nis_modal" class="form-control" required onchange="onSiswaChange(this)">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($allSiswaSelect as $s)
                            <option value="{{ $s->nis }}" data-nama="{{ strtoupper($s->nama_siswa) }}">
                                {{ strtoupper($s->nama_siswa) }} — NIS: {{ $s->nis }} ({{ $s->kelas->nama_kelas ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Tagihan <span class="text-danger">*</span></label>
                    <select name="jenis_tagihan" class="form-control" required>
                        <option value="spp">SPP (Kode 00)</option>
                        <option value="non_spp">Non SPP (Kode 01)</option>
                        <option value="tunggakan">Tunggakan (Kode 02)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama / Keterangan Tagihan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_tagihan" class="form-control"
                           placeholder="Contoh: SPP September 2026" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Nominal Tagihan (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="nominal" class="form-control"
                           placeholder="Contoh: 250000" min="0" step="1000" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Tanggal Tagihan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_tagihan" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jatuh Tempo</label>
                        <input type="date" name="tanggal_jatuh_tempo" class="form-control">
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Catatan</label>
                    <textarea name="keterangan" class="form-control" rows="2"
                              placeholder="Catatan tambahan (opsional)"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-tambah-tagihan')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan & Generate VA
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL: EDIT TAGIHAN
═══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-edit-tagihan" style="display: none;">
    <div class="modal modal-md" style="max-width: 540px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-pen-to-square"></i> Edit Tagihan Siswa</h3>
            <button type="button" onclick="closeModal('modal-edit-tagihan')" class="modal-close">&times;</button>
        </div>
        <form id="form-edit-tagihan" action="" method="POST">
            @csrf
            <div class="modal-body" style="display: flex; flex-direction: column; gap: 14px;">
                <div class="form-group">
                    <label class="form-label">Nama Tagihan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_tagihan" id="edit_nama_tagihan" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="nominal" id="edit_nominal" class="form-control" min="0" required>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Tanggal Tagihan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_tagihan" id="edit_tanggal_tagihan" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jatuh Tempo</label>
                        <input type="date" name="tanggal_jatuh_tempo" id="edit_tanggal_jatuh_tempo" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="edit_keterangan" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer" style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-edit-tagihan')">Batal</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Update Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL: UBAH STATUS PEMBAYARAN
═══════════════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-status-tagihan" style="display: none;">
    <div class="modal modal-md" style="max-width: 480px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-money-check-dollar"></i> Update Status Pembayaran</h3>
            <button type="button" onclick="closeModal('modal-status-tagihan')" class="modal-close">&times;</button>
        </div>
        <form id="form-status-tagihan" action="" method="POST">
            @csrf
            <div class="modal-body" style="display: flex; flex-direction: column; gap: 14px;">
                <div style="background: #ede9fe; padding: 12px 16px; border-radius: 8px; text-align: center;">
                    <div style="font-size: 0.8rem; color: #5b21b6; margin-bottom: 4px;">Nomor Virtual Account</div>
                    <div id="status_va_preview" style="font-family: monospace; font-size: 1.3rem; font-weight: 900; color: #4f46e5; letter-spacing: 1px;"></div>
                </div>

                <div class="form-group">
                    <label class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                    <select name="status" id="status_select" class="form-control" required onchange="toggleNominalInput()">
                        <option value="belum_bayar">Belum Bayar</option>
                        <option value="lunas">Lunas</option>
                        <option value="batal">Batal</option>
                    </select>
                </div>

                <div id="group_nominal_terbayar" class="form-group">
                    <label class="form-label">Nominal Terbayar (Rp)</label>
                    <input type="number" name="nominal_terbayar" id="status_nominal_terbayar" class="form-control" min="0">
                </div>

                <div id="group_tanggal_bayar" class="form-group">
                    <label class="form-label">Waktu Pembayaran</label>
                    <input type="datetime-local" name="tanggal_bayar" id="status_tanggal_bayar" class="form-control" value="{{ date('Y-m-d\TH:i') }}">
                </div>
            </div>
            <div class="modal-footer" style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-status-tagihan')">Batal</button>
                <button type="submit" class="btn btn-success">
                    <i class="fa-solid fa-check-double"></i> Simpan Status
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// ─── Copy to clipboard ─────────────────────────────
function copyToClipboard(text) {
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(text).then(() => {
            showCopyToast(text);
        });
    } else {
        const el = document.createElement('textarea');
        el.value = text;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        showCopyToast(text);
    }
}

function showCopyToast(text) {
    const toast = document.createElement('div');
    toast.textContent = '✓ VA ' + text + ' berhasil disalin!';
    toast.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#1e293b;color:#fff;padding:12px 18px;border-radius:8px;font-size:0.875rem;z-index:9999;box-shadow:0 4px 12px rgba(0,0,0,0.2);';
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2500);
}

// ─── Modal helpers ─────────────────────────────────
function openModal(id)  { document.getElementById(id).style.display = 'flex'; }
function closeModal(id) { document.getElementById(id).style.display = 'none'; }

// ─── Buka modal tambah dengan siswa terpilih ───────
function openTambahTagihan(nis, nama) {
    const sel = document.getElementById('select_nis_modal');
    sel.value = nis;
    document.getElementById('info_siswa_selected').style.display = 'block';
    document.getElementById('info_siswa_nama').textContent = nama + ' (NIS: ' + nis + ')';
    openModal('modal-tambah-tagihan');
}

function onSiswaChange(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (sel.value) {
        document.getElementById('info_siswa_selected').style.display = 'block';
        document.getElementById('info_siswa_nama').textContent = opt.dataset.nama + ' (NIS: ' + sel.value + ')';
    } else {
        document.getElementById('info_siswa_selected').style.display = 'none';
    }
}

// ─── Modal edit ────────────────────────────────────
function openEditModal(id, nama, nominal, tglTagihan, tglJatuhTempo, ket) {
    document.getElementById('form-edit-tagihan').action = "{{ url('/pembayaran/rekap-siswa') }}/" + id;
    document.getElementById('edit_nama_tagihan').value   = nama;
    document.getElementById('edit_nominal').value        = nominal;
    document.getElementById('edit_tanggal_tagihan').value = tglTagihan;
    document.getElementById('edit_tanggal_jatuh_tempo').value = tglJatuhTempo;
    document.getElementById('edit_keterangan').value    = ket;
    openModal('modal-edit-tagihan');
}

// ─── Modal status ──────────────────────────────────
function openStatusModal(id, vaNumber, status, nominal) {
    document.getElementById('form-status-tagihan').action = "{{ url('/pembayaran/rekap-siswa') }}/" + id + "/status";
    document.getElementById('status_va_preview').textContent = vaNumber;
    document.getElementById('status_select').value = status;
    document.getElementById('status_nominal_terbayar').value = nominal;
    toggleNominalInput();
    openModal('modal-status-tagihan');
}

function toggleNominalInput() {
    const val = document.getElementById('status_select').value;
    const showField = val === 'lunas';
    document.getElementById('group_nominal_terbayar').style.display = showField ? 'block' : 'none';
    document.getElementById('group_tanggal_bayar').style.display    = showField ? 'block' : 'none';
}

// Init toggle
toggleNominalInput();

</script>
@endsection
