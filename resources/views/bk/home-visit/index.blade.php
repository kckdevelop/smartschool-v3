@extends('layouts.app')

@section('title', 'Home Visit BK — SmartSchool')
@section('header_title', 'Home Visit')
@section('header_subtitle', 'Modul kunjungan rumah untuk pembinaan siswa lebih dekat')

@push('styles')
<style>
/* ── Autocomplete dropdown ── */
.autocomplete-wrapper {
    position: relative;
}
.autocomplete-results {
    position: absolute;
    top: calc(100% + 4px);
    left: 0; right: 0;
    background: var(--card-bg, #fff);
    border: 1.5px solid var(--border-color, #e2e8f0);
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,.12);
    z-index: 1050;
    max-height: 220px;
    overflow-y: auto;
    display: none;
}
.autocomplete-results.open { display: block; }
.autocomplete-item {
    padding: 10px 14px;
    cursor: pointer;
    display: flex;
    flex-direction: column;
    gap: 2px;
    transition: background .15s;
    border-bottom: 1px solid var(--border-color, #f1f5f9);
}
.autocomplete-item:last-child { border-bottom: none; }
.autocomplete-item:hover, .autocomplete-item.active {
    background: var(--color-primary-light, #ede9fe);
}
.autocomplete-item .item-main { font-weight: 600; font-size: 0.9rem; }
.autocomplete-item .item-sub  { font-size: 0.78rem; color: var(--text-muted, #94a3b8); }
.autocomplete-item .item-badge {
    display: inline-flex; align-items: center; gap: 4px;
    background: var(--color-primary-light, #ede9fe);
    color: var(--color-primary, #7c3aed);
    font-size: 0.72rem; font-weight: 700;
    padding: 2px 8px; border-radius: 20px;
}
.no-results-item {
    padding: 12px 14px;
    color: var(--text-muted, #94a3b8);
    font-size: 0.85rem;
    text-align: center;
}

/* ── Selected siswa chip ── */
.selected-siswa-chip {
    display: none;
    align-items: center;
    gap: 10px;
    background: var(--color-primary-light, #ede9fe);
    border: 1.5px solid var(--color-primary, #7c3aed);
    border-radius: 10px;
    padding: 10px 14px;
    margin-top: 6px;
}
.selected-siswa-chip.show { display: flex; }
.selected-siswa-chip .chip-info { flex: 1; }
.selected-siswa-chip .chip-name { font-weight: 700; font-size: 0.92rem; }
.selected-siswa-chip .chip-meta { font-size: 0.78rem; color: var(--text-muted); }
.selected-siswa-chip .chip-clear {
    background: none; border: none;
    color: var(--color-primary); cursor: pointer;
    font-size: 1rem; padding: 0; line-height: 1;
}

/* ── Drag & Drop Upload Area ── */
.dropzone-area {
    border: 2.5px dashed var(--border-color, #cbd5e1);
    border-radius: 12px;
    padding: 28px 20px;
    text-align: center;
    cursor: pointer;
    transition: border-color .2s, background .2s;
    background: var(--input-bg, #f8fafc);
    position: relative;
}
.dropzone-area:hover,
.dropzone-area.drag-over {
    border-color: var(--color-primary, #7c3aed);
    background: var(--color-primary-light, #ede9fe);
}
.dropzone-area input[type="file"] {
    position: absolute;
    inset: 0;
    opacity: 0;
    cursor: pointer;
    width: 100%;
    height: 100%;
}
.dropzone-icon {
    font-size: 2.4rem;
    color: var(--text-muted, #94a3b8);
    margin-bottom: 8px;
    display: block;
    transition: color .2s;
}
.dropzone-area:hover .dropzone-icon,
.dropzone-area.drag-over .dropzone-icon {
    color: var(--color-primary, #7c3aed);
}
.dropzone-text {
    font-size: 0.88rem;
    color: var(--text-muted, #94a3b8);
    line-height: 1.5;
}
.dropzone-text span {
    color: var(--color-primary, #7c3aed);
    font-weight: 600;
}

/* ── Photo Preview ── */
.foto-preview-wrap {
    display: none;
    position: relative;
    border-radius: 12px;
    overflow: hidden;
    border: 2px solid var(--color-primary, #7c3aed);
    background: #000;
    max-height: 200px;
}
.foto-preview-wrap.show { display: block; }
.foto-preview-wrap img {
    width: 100%;
    max-height: 200px;
    object-fit: contain;
    display: block;
}
.foto-preview-remove {
    position: absolute;
    top: 8px; right: 8px;
    background: rgba(239,68,68,.85);
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 30px; height: 30px;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    font-size: 0.85rem;
    transition: background .15s;
    z-index: 5;
}
.foto-preview-remove:hover { background: #dc2626; }

/* ── Foto thumbnail in table ── */
.foto-thumb {
    width: 44px; height: 44px;
    object-fit: cover;
    border-radius: 8px;
    border: 1.5px solid var(--border-color, #e2e8f0);
    cursor: pointer;
    transition: opacity .15s, transform .15s;
}
.foto-thumb:hover { opacity: .8; transform: scale(1.08); }
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Filter Card (Kelas Only) --}}
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('bk.home-visit.index') }}" class="flex-row-wrap gap-4 align-items-end">
                <div class="form-group mb-0" style="min-width: 220px;">
                    <label class="form-label-sm">Filter Kelas</label>
                    <select name="id_kelas" class="form-control form-control-sm">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-row gap-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filter</button>
                    <a href="{{ route('bk.home-visit.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-house-chimney-user" style="color:var(--color-primary);"></i> Daftar Kunjungan Rumah (Home Visit)</h2>
            <div class="card-header-right">
                <button class="btn btn-primary btn-sm" onclick="openAddModal()" id="btn-tambah-visit">
                    <i class="fa-solid fa-plus"></i> Jadwalkan Home Visit
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th style="width:110px;">Tanggal Visit</th>
                        <th>Siswa</th>
                        <th>Alamat</th>
                        <th>Tujuan Kunjungan</th>
                        <th>Hasil Kunjungan</th>
                        <th>Tindak Lanjut</th>
                        <th style="width:110px;text-align:center;">Status</th>
                        <th>Guru BK</th>
                        <th style="width:70px;text-align:center;">Foto</th>
                        <th style="width:120px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $data->firstItem() + $i }}</td>
                        <td style="font-size:0.85rem;">{{ \Carbon\Carbon::parse($item->tanggal_visit)->format('d/m/Y') }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $item->nis }}</div>
                            @if($item->siswa)
                                <div style="font-size:0.8rem;color:var(--text-muted);">{{ $item->siswa->nama_siswa }}</div>
                                @if($item->siswa->kelas)
                                    <div style="font-size:0.75rem;"><span class="badge" style="background:var(--color-primary-light);color:var(--color-primary);">{{ $item->siswa->kelas->nama_kelas }}</span></div>
                                @endif
                            @endif
                        </td>
                        <td style="font-size:0.85rem; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->alamat }}">
                            {{ $item->alamat ?? '-' }}
                        </td>
                        <td style="font-size:0.85rem; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->tujuan_kunjungan }}">
                            {{ $item->tujuan_kunjungan }}
                        </td>
                        <td style="font-size:0.85rem; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->hasil_kunjungan }}">
                            {{ $item->hasil_kunjungan ?? '-' }}
                        </td>
                        <td style="font-size:0.85rem; max-width: 150px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->tindak_lanjut }}">
                            {{ $item->tindak_lanjut ?? '-' }}
                        </td>
                        <td style="text-align:center;">
                            @if($item->status === 'selesai')
                                <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Selesai</span>
                            @elseif($item->status === 'batal')
                                <span class="badge badge-danger"><i class="fa-solid fa-circle-xmark"></i> Batal</span>
                            @else
                                <span class="badge badge-info"><i class="fa-solid fa-calendar-day"></i> Dijadwalkan</span>
                            @endif
                        </td>
                        <td style="font-size:0.8rem;color:var(--text-muted);">{{ $item->guru->nama_guru ?? '-' }}</td>
                        <td style="text-align:center;">
                            @if($item->foto_bukti)
                                <a href="{{ asset('storage/' . $item->foto_bukti) }}" target="_blank" title="Lihat foto bukti kunjungan">
                                    <img src="{{ asset('storage/' . $item->foto_bukti) }}"
                                         class="foto-thumb"
                                         alt="Foto Bukti Kunjungan">
                                </a>
                            @else
                                <span style="font-size:0.75rem;color:var(--text-muted);">–</span>
                            @endif
                        </td>
                        <td class="action-cell">
                            <button class="btn-icon btn-info" title="Preview"
                                onclick="previewVisit({{ json_encode(array_merge($item->toArray(), [
                                    'siswa_nama'  => $item->siswa?->nama_siswa ?? '',
                                    'siswa_kelas' => $item->siswa?->kelas?->nama_kelas ?? '',
                                    'guru_nama'   => $item->guru?->nama_guru ?? '-',
                                ])) }})">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editVisit({{ json_encode(array_merge($item->toArray(), [
                                    'siswa_nama'  => $item->siswa?->nama_siswa ?? '',
                                    'siswa_kelas' => $item->siswa?->kelas?->nama_kelas ?? '',
                                ])) }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('bk.home-visit.destroy', $item->id_home_visit) }}','Yakin hapus data home visit ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center text-muted py-6">
                            <i class="fa-solid fa-house-chimney-user" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada catatan home visit
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($data->hasPages())
        <div class="card-footer">{{ $data->links() }}</div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL TAMBAH / EDIT HOME VISIT
══════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-visit">
    <div class="modal modal-md" style="max-width:600px;">
        <div class="modal-header">
            <h3 id="modal-title-visit">Jadwal Home Visit Baru</h3>
            <button onclick="closeVisitModal()" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-visit" method="POST" action="{{ route('bk.home-visit.store') }}" enctype="multipart/form-data">
            @csrf
            <div id="method-field-visit"></div>
            <input type="hidden" name="nis" id="v_nis_hidden">
            <input type="hidden" name="remove_foto_bukti" id="v_remove_foto" value="0">

            <div class="modal-body" style="max-height:75vh;overflow-y:auto;">

                {{-- ── Tanggal ── --}}
                <div class="form-group">
                    <label class="form-label">Tanggal Kunjungan <span class="required">*</span></label>
                    <input type="date" name="tanggal_visit" id="v_tgl" class="form-control" required value="{{ date('Y-m-d') }}">
                </div>

                {{-- ── Cari Siswa (AJAX Autocomplete) ── --}}
                <div class="form-group">
                    <label class="form-label">Cari Siswa (Nama / NIS) <span class="required">*</span></label>
                    <div class="autocomplete-wrapper">
                        <input type="text" id="v_siswa_search" class="form-control"
                               placeholder="Ketik nama atau NIS siswa..."
                               autocomplete="off">
                        <div class="autocomplete-results" id="v_siswa_dropdown"></div>
                    </div>
                    {{-- Chip tampil setelah siswa dipilih --}}
                    <div class="selected-siswa-chip" id="v_siswa_chip">
                        <i class="fa-solid fa-user-check" style="color:var(--color-primary);font-size:1.1rem;flex-shrink:0;"></i>
                        <div class="chip-info">
                            <div class="chip-name" id="v_chip_nama"></div>
                            <div class="chip-meta" id="v_chip_meta"></div>
                        </div>
                        <button type="button" class="chip-clear" onclick="clearVisitSiswa()" title="Ganti siswa">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                </div>

                {{-- ── Alamat ── --}}
                <div class="form-group">
                    <label class="form-label">Alamat Kunjungan</label>
                    <input type="text" name="alamat" id="v_alamat" class="form-control" placeholder="Masukkan alamat lengkap rumah siswa" maxlength="255">
                </div>

                {{-- ── Tujuan ── --}}
                <div class="form-group">
                    <label class="form-label">Tujuan Kunjungan <span class="required">*</span></label>
                    <textarea name="tujuan_kunjungan" id="v_tujuan" class="form-control" placeholder="Tujuan dilakukannya home visit" rows="3" required></textarea>
                </div>

                {{-- ── Hasil ── --}}
                <div class="form-group">
                    <label class="form-label">Hasil Kunjungan</label>
                    <textarea name="hasil_kunjungan" id="v_hasil" class="form-control" placeholder="Catatan hasil pertemuan di rumah" rows="3"></textarea>
                </div>

                {{-- ── Tindak Lanjut ── --}}
                <div class="form-group">
                    <label class="form-label">Tindak Lanjut</label>
                    <textarea name="tindak_lanjut" id="v_tindak" class="form-control" placeholder="Tindak lanjut yang harus dipenuhi" rows="2"></textarea>
                </div>

                {{-- ── Status (Edit only) ── --}}
                <div class="form-group" id="status-group" style="display:none;">
                    <label class="form-label">Status Kunjungan <span class="required">*</span></label>
                    <select name="status" id="v_status" class="form-control">
                        <option value="dijadwalkan">Dijadwalkan</option>
                        <option value="selesai">Selesai</option>
                        <option value="batal">Batal</option>
                    </select>
                </div>

                {{-- ── Foto Bukti (Drag & Drop) ── --}}
                <div class="form-group">
                    <label class="form-label">
                        <i class="fa-solid fa-camera" style="color:var(--color-primary);"></i>
                        Foto Bukti Kunjungan
                        <span style="font-size:0.78rem;color:var(--text-muted);font-weight:400;"> (maks. 2MB, JPG/PNG)</span>
                    </label>

                    {{-- Preview --}}
                    <div class="foto-preview-wrap" id="v_foto_preview_wrap">
                        <img id="v_foto_preview_img" src="" alt="Preview Foto Bukti">
                        <button type="button" class="foto-preview-remove" onclick="removeVisitFoto()" title="Hapus foto">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    {{-- Drop Zone (hidden when preview shown) --}}
                    <div class="dropzone-area" id="v_dropzone"
                         ondragover="handleDragOver(event,'v_dropzone')"
                         ondragleave="handleDragLeave(event,'v_dropzone')"
                         ondrop="handleDropVisit(event)">
                        <input type="file" name="foto_bukti" id="v_foto_input"
                               accept="image/*"
                               onchange="handleVisitFileChange(this)">
                        <i class="fa-solid fa-cloud-arrow-up dropzone-icon"></i>
                        <div class="dropzone-text">
                            <span>Klik untuk pilih</span> atau seret & lepas gambar ke sini<br>
                            <small>Format: JPG, PNG, WEBP &bull; Maks 2 MB</small>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeVisitModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" id="v_submit_btn">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ══════════════════════════════════════════════
     MODAL PREVIEW HOME VISIT
══════════════════════════════════════════════ --}}
<div class="modal-overlay" id="modal-preview-visit">
    <div class="modal modal-md" style="max-width: 650px;">
        <div class="modal-header" style="border-bottom: 1.5px solid var(--border-color); padding: 16px 20px;">
            <h3><i class="fa-solid fa-house-chimney-user" style="color: var(--color-primary);"></i> Detail Home Visit</h3>
            <button onclick="closeModal('modal-preview-visit')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="padding: 24px; max-height: 70vh; overflow-y: auto;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Tanggal Kunjungan</div>
                    <div id="pv_tanggal" style="font-weight: 600; font-size: 0.95rem; margin-top: 4px; color: var(--text-primary);"></div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Status Kunjungan</div>
                    <div id="pv_status_badge" style="margin-top: 4px;"></div>
                </div>
            </div>

            <div style="border-top: 1.5px solid var(--border-color); padding-top: 16px; margin-bottom: 20px; display: grid; grid-template-columns: 1fr 1fr; gap: 16px;">
                <div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Siswa</div>
                    <div id="pv_siswa_nama" style="font-weight: 700; font-size: 1rem; margin-top: 4px; color: var(--color-primary);"></div>
                    <div id="pv_siswa_meta" style="font-size: 0.8rem; color: var(--text-muted); margin-top: 2px;"></div>
                </div>
                <div>
                    <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Guru Pendamping</div>
                    <div id="pv_guru_nama" style="font-weight: 600; font-size: 0.95rem; margin-top: 4px; color: var(--text-primary);"></div>
                </div>
            </div>

            <div style="border-top: 1.5px solid var(--border-color); padding-top: 16px; margin-bottom: 20px;">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Alamat Kunjungan</div>
                <div id="pv_alamat" style="font-size: 0.9rem; margin-top: 4px; color: var(--text-primary); line-height: 1.5; font-weight: 500;"></div>
            </div>

            <div style="border-top: 1.5px solid var(--border-color); padding-top: 16px; margin-bottom: 20px;">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Tujuan Kunjungan</div>
                <div id="pv_tujuan" style="font-size: 0.9rem; margin-top: 4px; color: var(--text-primary); line-height: 1.5; background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); white-space: pre-wrap;"></div>
            </div>

            <div style="border-top: 1.5px solid var(--border-color); padding-top: 16px; margin-bottom: 20px;">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Hasil Kunjungan</div>
                <div id="pv_hasil" style="font-size: 0.9rem; margin-top: 4px; color: var(--text-primary); line-height: 1.5; background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); white-space: pre-wrap;"></div>
            </div>

            <div style="border-top: 1.5px solid var(--border-color); padding-top: 16px; margin-bottom: 20px;">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px;">Tindak Lanjut</div>
                <div id="pv_tindak" style="font-size: 0.9rem; margin-top: 4px; color: var(--text-primary); line-height: 1.5; background: #f8fafc; padding: 12px; border-radius: 8px; border: 1px solid var(--border-color); white-space: pre-wrap;"></div>
            </div>

            <div style="border-top: 1.5px solid var(--border-color); padding-top: 16px;">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; letter-spacing: 0.5px; margin-bottom: 8px;">Foto Bukti Kunjungan</div>
                <div id="pv_foto_wrap" style="text-align: center; background: #f1f5f9; padding: 16px; border-radius: 12px; border: 1.5px dashed var(--border-color); display: flex; align-items: center; justify-content: center; min-height: 80px;">
                    <img id="pv_foto" src="" alt="Foto Bukti Kunjungan" style="max-width: 100%; max-height: 350px; border-radius: 8px; display: none; margin: 0 auto; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                    <span id="pv_no_foto" style="font-size: 0.85rem; color: var(--text-muted);"><i class="fa-solid fa-image-slash" style="margin-right: 6px;"></i> Belum ada foto bukti kunjungan</span>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="background: #f8fafc; border-top: 1.5px solid var(--border-color); padding: 12px 20px; border-radius: 0 0 12px 12px; display: flex; justify-content: flex-end;">
            <button type="button" onclick="closeModal('modal-preview-visit')" class="btn btn-secondary">Tutup</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════════
   STATE
═══════════════════════════════════════════════════════════ */
let visitSelectedSiswa = null;  // { nis, nama_siswa, nama_kelas }
let visitSiswaTimer    = null;
let visitFotoFile      = null;  // currently staged File object

/* ═══════════════════════════════════════════════════════════
   MODAL OPEN / CLOSE
═══════════════════════════════════════════════════════════ */
function openAddModal() {
    document.getElementById('form-visit').action = '{{ route("bk.home-visit.store") }}';
    document.getElementById('method-field-visit').innerHTML = '';
    document.getElementById('modal-title-visit').textContent = 'Jadwal Home Visit Baru';
    document.getElementById('v_tgl').value = '{{ date("Y-m-d") }}';
    document.getElementById('v_alamat').value = '';
    document.getElementById('v_tujuan').value = '';
    document.getElementById('v_hasil').value = '';
    document.getElementById('v_tindak').value = '';
    document.getElementById('status-group').style.display = 'none';
    resetVisitSiswaSearch();
    resetVisitFoto();
    openModal('modal-visit');
}

function editVisit(data) {
    document.getElementById('form-visit').action = `/bk/home-visit/${data.id_home_visit}`;
    document.getElementById('method-field-visit').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-visit').textContent = 'Edit Data Home Visit';

    const dateVal = data.tanggal_visit ? data.tanggal_visit.substring(0, 10) : '';
    document.getElementById('v_tgl').value = dateVal;
    document.getElementById('v_alamat').value = data.alamat || '';
    document.getElementById('v_tujuan').value = data.tujuan_kunjungan || '';
    document.getElementById('v_hasil').value = data.hasil_kunjungan || '';
    document.getElementById('v_tindak').value = data.tindak_lanjut || '';
    document.getElementById('status-group').style.display = 'block';
    document.getElementById('v_status').value = data.status || 'dijadwalkan';

    // Pre-fill siswa chip
    resetVisitSiswaSearch();
    selectVisitSiswa({
        nis:        data.nis,
        nama_siswa: data.siswa_nama || data.nis,
        nama_kelas: data.siswa_kelas || '',
    }, true);

    // Pre-fill foto preview (existing from server)
    resetVisitFoto();
    if (data.foto_bukti) {
        const imgUrl = `/storage/${data.foto_bukti}`;
        showVisitPreview(imgUrl, false); // false = existing file, no new File object
    }

    openModal('modal-visit');
}

function closeVisitModal() {
    closeModal('modal-visit');
    resetVisitFoto();
}

/* ═══════════════════════════════════════════════════════════
   SISWA AUTOCOMPLETE
═══════════════════════════════════════════════════════════ */
document.getElementById('v_siswa_search').addEventListener('input', function() {
    const q = this.value.trim();
    clearTimeout(visitSiswaTimer);
    if (q.length < 2) { closeVisitSiswaDropdown(); return; }
    visitSiswaTimer = setTimeout(() => fetchVisitSiswa(q), 280);
});

document.getElementById('v_siswa_search').addEventListener('keydown', function(e) {
    navigateVisitDropdown(e, 'v_siswa_dropdown', chooseVisitSiswaItem);
});

function fetchVisitSiswa(q) {
    fetch(`/bk/catat-pelanggaran/search-siswa?q=${encodeURIComponent(q)}`)
        .then(r => r.json())
        .then(renderVisitSiswaDropdown)
        .catch(() => {});
}

function renderVisitSiswaDropdown(list) {
    const dd = document.getElementById('v_siswa_dropdown');
    if (!list.length) {
        dd.innerHTML = '<div class="no-results-item"><i class="fa-solid fa-circle-xmark"></i> Siswa tidak ditemukan</div>';
    } else {
        dd.innerHTML = list.map((s, i) => `
            <div class="autocomplete-item" data-index="${i}" onclick="chooseVisitSiswaItem(${i})"
                 data-nis="${s.nis}" data-nama="${s.nama_siswa.replace(/"/g,'&quot;')}" data-kelas="${s.nama_kelas}">
                <span class="item-main">${s.nama_siswa}</span>
                <span class="item-sub">
                    NIS: ${s.nis} &nbsp;·&nbsp;
                    <span class="item-badge"><i class="fa-solid fa-door-open" style="font-size:0.65rem;"></i> ${s.nama_kelas}</span>
                </span>
            </div>`).join('');
    }
    dd.classList.add('open');
    dd._data = list;
}

function chooseVisitSiswaItem(index) {
    const dd   = document.getElementById('v_siswa_dropdown');
    const list = dd._data || [];
    if (!list[index]) return;
    selectVisitSiswa(list[index]);
}

function selectVisitSiswa(s, skipFetchAddress = false) {
    visitSelectedSiswa = s;
    document.getElementById('v_nis_hidden').value       = s.nis;
    document.getElementById('v_chip_nama').textContent  = s.nama_siswa;
    document.getElementById('v_chip_meta').textContent  = `NIS: ${s.nis}  ·  Kelas: ${s.nama_kelas}`;
    document.getElementById('v_siswa_chip').classList.add('show');
    document.getElementById('v_siswa_search').style.display = 'none';
    closeVisitSiswaDropdown();

    if (!skipFetchAddress) {
        fetch(`/bk/panggil-ortu/siswa-detail?nis=${s.nis}`)
            .then(r => r.json())
            .then(detail => {
                if (detail.alamat) {
                    document.getElementById('v_alamat').value = detail.alamat;
                }
            })
            .catch(err => console.error('Gagal mengambil alamat siswa:', err));
    }
}

function clearVisitSiswa() {
    visitSelectedSiswa = null;
    document.getElementById('v_nis_hidden').value = '';
    document.getElementById('v_siswa_chip').classList.remove('show');
    const inp = document.getElementById('v_siswa_search');
    inp.style.display = '';
    inp.value = '';
    inp.focus();
}

function resetVisitSiswaSearch() {
    visitSelectedSiswa = null;
    document.getElementById('v_nis_hidden').value = '';
    document.getElementById('v_siswa_chip').classList.remove('show');
    document.getElementById('v_siswa_search').style.display = '';
    document.getElementById('v_siswa_search').value = '';
    closeVisitSiswaDropdown();
}

function closeVisitSiswaDropdown() {
    document.getElementById('v_siswa_dropdown').classList.remove('open');
}

/* ═══════════════════════════════════════════════════════════
   KEYBOARD NAVIGATION
═══════════════════════════════════════════════════════════ */
function navigateVisitDropdown(e, dropdownId, selectFn) {
    const dd    = document.getElementById(dropdownId);
    const items = dd.querySelectorAll('.autocomplete-item');
    let current = dd.querySelector('.autocomplete-item.active');
    let idx = -1;
    if (current) { idx = parseInt(current.dataset.index); current.classList.remove('active'); }
    if      (e.key === 'ArrowDown')  { e.preventDefault(); idx = Math.min(idx + 1, items.length - 1); }
    else if (e.key === 'ArrowUp')    { e.preventDefault(); idx = Math.max(idx - 1, 0); }
    else if (e.key === 'Enter')      { e.preventDefault(); if (idx >= 0) selectFn(idx); return; }
    else if (e.key === 'Escape')     { dd.classList.remove('open'); return; }
    if (items[idx]) { items[idx].classList.add('active'); items[idx].scrollIntoView({ block: 'nearest' }); }
}

/* ═══════════════════════════════════════════════════════════
   CLOSE DROPDOWN ON OUTSIDE CLICK
═══════════════════════════════════════════════════════════ */
document.addEventListener('click', function(e) {
    if (!e.target.closest('.autocomplete-wrapper')) {
        closeVisitSiswaDropdown();
    }
});

/* ═══════════════════════════════════════════════════════════
   DRAG & DROP / FILE UPLOAD
═══════════════════════════════════════════════════════════ */
function handleDragOver(event, zoneId) {
    event.preventDefault();
    document.getElementById(zoneId).classList.add('drag-over');
}

function handleDragLeave(event, zoneId) {
    document.getElementById(zoneId).classList.remove('drag-over');
}

function handleDropVisit(event) {
    event.preventDefault();
    document.getElementById('v_dropzone').classList.remove('drag-over');
    const files = event.dataTransfer.files;
    if (!files.length) return;
    const file = files[0];
    if (!file.type.startsWith('image/')) {
        alert('File harus berupa gambar (JPG, PNG, WEBP, dll.)');
        return;
    }
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran gambar maksimal 2 MB.');
        return;
    }
    stageVisitFile(file);
}

function handleVisitFileChange(input) {
    if (!input.files.length) return;
    const file = input.files[0];
    if (!file.type.startsWith('image/')) {
        alert('File harus berupa gambar (JPG, PNG, WEBP, dll.)');
        input.value = '';
        return;
    }
    if (file.size > 2 * 1024 * 1024) {
        alert('Ukuran gambar maksimal 2 MB.');
        input.value = '';
        return;
    }
    stageVisitFile(file);
}

function stageVisitFile(file) {
    visitFotoFile = file;
    // Inject the file into the real file input using DataTransfer
    const dt  = new DataTransfer();
    dt.items.add(file);
    document.getElementById('v_foto_input').files = dt.files;
    // Read and show preview
    const reader = new FileReader();
    reader.onload = e => showVisitPreview(e.target.result, true);
    reader.readAsDataURL(file);
}

function showVisitPreview(src, isNew) {
    document.getElementById('v_foto_preview_img').src = src;
    document.getElementById('v_foto_preview_wrap').classList.add('show');
    document.getElementById('v_dropzone').style.display = 'none';
    if (!isNew) {
        // Existing server file – mark remove as 0 (not removing)
        document.getElementById('v_remove_foto').value = '0';
    }
}

function removeVisitFoto() {
    visitFotoFile = null;
    // Clear the file input
    const inp = document.getElementById('v_foto_input');
    inp.value = '';
    // If the preview was from existing server file, signal removal
    document.getElementById('v_remove_foto').value = '1';
    // Hide preview, show dropzone
    document.getElementById('v_foto_preview_wrap').classList.remove('show');
    document.getElementById('v_foto_preview_img').src = '';
    document.getElementById('v_dropzone').style.display = '';
}

function resetVisitFoto() {
    visitFotoFile = null;
    const inp = document.getElementById('v_foto_input');
    inp.value = '';
    document.getElementById('v_remove_foto').value = '0';
    document.getElementById('v_foto_preview_wrap').classList.remove('show');
    document.getElementById('v_foto_preview_img').src = '';
    document.getElementById('v_dropzone').style.display = '';
    document.getElementById('v_dropzone').classList.remove('drag-over');
}

/* ═══════════════════════════════════════════════════════════
   PREVIEW DETAIL MODAL
═══════════════════════════════════════════════════════════ */
function previewVisit(data) {
    let formattedDate = '-';
    if (data.tanggal_visit) {
        let d = new Date(data.tanggal_visit);
        let day = String(d.getDate()).padStart(2, '0');
        let month = String(d.getMonth() + 1).padStart(2, '0');
        let year = d.getFullYear();
        formattedDate = `${day}/${month}/${year}`;
    }

    document.getElementById('pv_tanggal').textContent = formattedDate;

    let badgeHtml = '';
    if (data.status === 'selesai') {
        badgeHtml = '<span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Selesai</span>';
    } else if (data.status === 'batal') {
        badgeHtml = '<span class="badge badge-danger"><i class="fa-solid fa-circle-xmark"></i> Batal</span>';
    } else {
        badgeHtml = '<span class="badge badge-info"><i class="fa-solid fa-calendar-day"></i> Dijadwalkan</span>';
    }
    document.getElementById('pv_status_badge').innerHTML = badgeHtml;

    document.getElementById('pv_siswa_nama').textContent = data.siswa_nama || data.nis;
    document.getElementById('pv_siswa_meta').textContent = `NIS: ${data.nis} ${data.siswa_kelas ? ' · Kelas: ' + data.siswa_kelas : ''}`;
    document.getElementById('pv_guru_nama').textContent = data.guru_nama || '-';
    document.getElementById('pv_alamat').textContent = data.alamat || '-';
    document.getElementById('pv_tujuan').textContent = data.tujuan_kunjungan || '-';
    document.getElementById('pv_hasil').textContent = data.hasil_kunjungan || '-';
    document.getElementById('pv_tindak').textContent = data.tindak_lanjut || '-';

    const imgEl = document.getElementById('pv_foto');
    const noImgEl = document.getElementById('pv_no_foto');
    const wrapEl = document.getElementById('pv_foto_wrap');

    if (data.foto_bukti) {
        imgEl.src = `/storage/${data.foto_bukti}`;
        imgEl.style.display = 'block';
        noImgEl.style.display = 'none';
        wrapEl.style.background = 'none';
        wrapEl.style.borderStyle = 'solid';
    } else {
        imgEl.src = '';
        imgEl.style.display = 'none';
        noImgEl.style.display = 'inline';
        wrapEl.style.background = '#f1f5f9';
        wrapEl.style.borderStyle = 'dashed';
    }

    openModal('modal-preview-visit');
}
</script>
@endpush
@endsection
