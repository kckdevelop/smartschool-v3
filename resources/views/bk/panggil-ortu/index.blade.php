@extends('layouts.app')

@section('title', 'Panggil Orang Tua BK — SmartSchool')
@section('header_title', 'Panggil Orang Tua')
@section('header_subtitle', 'Modul pemanggilan orang tua/wali siswa ke sekolah')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Filter Card --}}
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('bk.panggil-ortu.index') }}" class="flex-row-wrap gap-4 align-items-end">
                <div class="form-group mb-0" style="min-width: 200px;">
                    <label class="form-label-sm">Filter Status Kehadiran</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="belum_hadir" {{ request('status') === 'belum_hadir' ? 'selected' : '' }}>Belum Hadir</option>
                        <option value="sudah_hadir" {{ request('status') === 'sudah_hadir' ? 'selected' : '' }}>Sudah Hadir</option>
                        <option value="tidak_hadir" {{ request('status') === 'tidak_hadir' ? 'selected' : '' }}>Tidak Hadir</option>
                    </select>
                </div>
                <div class="form-group mb-0" style="min-width: 180px;">
                    <label class="form-label-sm">Filter Kelas</label>
                    <select name="id_kelas" class="form-control form-control-sm">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}" {{ request('id_kelas') == $k->id_kelas ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group mb-0" style="min-width: 200px;">
                    <label class="form-label-sm">Cari NIS Siswa</label>
                    <input type="text" name="nis" value="{{ request('nis') }}" class="form-control form-control-sm" placeholder="Masukkan NIS">
                </div>
                <div class="flex-row gap-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filter</button>
                    <a href="{{ route('bk.panggil-ortu.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-users-rectangle" style="color:var(--color-primary);"></i> Daftar Pemanggilan Orang Tua</h2>
            <div class="card-header-right">
                <button class="btn btn-primary btn-sm" onclick="openAddModal()" id="btn-tambah-panggil">
                    <i class="fa-solid fa-plus"></i> Catat Panggilan Baru
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th style="width:130px;">Waktu Pertemuan</th>
                        <th>Jenis Panggilan / No. Surat</th>
                        <th>Siswa</th>
                        <th>Orang Tua / HP</th>
                        <th>Alasan Pemanggilan</th>
                        <th>Hasil Pertemuan</th>
                        <th style="width:110px;text-align:center;">Status</th>
                        <th>Guru BK</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $data->firstItem() + $i }}</td>
                        <td style="font-size:0.85rem;">
                            <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($item->tanggal_panggil)->format('d/m/Y') }}</div>
                            @if($item->waktu_pertemuan)
                                <div style="font-size:0.75rem;color:var(--text-muted);margin-top:2px;">
                                    <i class="fa-regular fa-clock" style="margin-right:2px;"></i> {{ \Carbon\Carbon::parse($item->waktu_pertemuan)->format('H:i') }} WIB
                                </div>
                            @endif
                        </td>
                        <td>
                            @if($item->jenis_panggilan === 'panggilan_biasa')
                                <span class="badge" style="background:#e0f2fe;color:#0369a1;margin-bottom:4px;">Panggilan Biasa</span>
                            @elseif($item->jenis_panggilan === 'sp_1')
                                <span class="badge" style="background:#fee2e2;color:#b91c1c;margin-bottom:4px;font-weight:bold;">SP 1</span>
                            @elseif($item->jenis_panggilan === 'sp_2')
                                <span class="badge" style="background:#fecaca;color:#991b1b;margin-bottom:4px;font-weight:bold;">SP 2</span>
                            @elseif($item->jenis_panggilan === 'sp_3')
                                <span class="badge" style="background:#fca5a5;color:#7f1d1d;margin-bottom:4px;font-weight:bold;">SP 3</span>
                            @endif
                            <div style="font-size:0.75rem;color:var(--text-muted);font-family:monospace;">{{ $item->no_surat ?? '-' }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $item->nis }}</div>
                            @if($item->siswa)
                                <div style="font-size:0.8rem;color:var(--text-muted);">{{ $item->siswa->nama_siswa }}</div>
                                @if($item->siswa->kelas)
                                    <div style="font-size:0.75rem;margin-top:2px;"><span class="badge" style="background:var(--color-primary-light);color:var(--color-primary);">{{ $item->siswa->kelas->nama_kelas }}</span></div>
                                @endif
                            @endif
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $item->nama_ortu ?? '-' }}</div>
                            <div style="font-size:0.8rem;color:var(--text-muted);">{{ $item->no_hp_ortu ?? '-' }}</div>
                        </td>
                        <td style="font-size:0.85rem; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->alasan_panggil }}">
                            {{ $item->alasan_panggil }}
                        </td>
                        <td style="font-size:0.85rem; max-width: 180px;">
                            <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->hasil_pertemuan }}">
                                {{ $item->hasil_pertemuan ?? '-' }}
                            </div>
                            @if($item->bukti_pertemuan || $item->surat_pernyataan)
                                <div style="display: flex; gap: 8px; margin-top: 4px; font-size: 0.75rem;">
                                    @if($item->bukti_pertemuan)
                                        <a href="{{ asset('storage/' . $item->bukti_pertemuan) }}" target="_blank" style="color: var(--color-primary); font-weight: 600;" title="Lihat Bukti Pertemuan">
                                            <i class="fa-solid fa-camera"></i> Bukti
                                        </a>
                                    @endif
                                    @if($item->surat_pernyataan)
                                        <a href="{{ asset('storage/' . $item->surat_pernyataan) }}" target="_blank" style="color: #10b981; font-weight: 600;" title="Lihat Surat Pernyataan">
                                            <i class="fa-solid fa-file-signature"></i> Surat Pernyataan
                                        </a>
                                    @endif
                                </div>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($item->status === 'sudah_hadir')
                                <span class="badge badge-success"><i class="fa-solid fa-circle-check"></i> Sudah Hadir</span>
                            @elseif($item->status === 'tidak_hadir')
                                <span class="badge badge-danger"><i class="fa-solid fa-circle-xmark"></i> Tidak Hadir</span>
                            @else
                                <span class="badge badge-warning"><i class="fa-solid fa-envelope-open-text"></i> Belum Hadir</span>
                            @endif
                        </td>
                        <td style="font-size:0.8rem;color:var(--text-muted);">{{ $item->guru->nama_guru ?? '-' }}</td>
                        <td class="action-cell" style="gap:6px;">
                            <button class="btn-icon" title="Lihat Detail" onclick="showDetailPanggil({{ json_encode($item) }})" style="background:#e0f2fe;color:#0284c7;border:1px solid #bae6fd;padding:5px 8px;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <a href="{{ route('bk.panggil-ortu.pdf', $item->id_panggil) }}" class="btn-icon btn-print" title="Cetak PDF" target="_blank" style="background:#fee2e2;color:#ef4444;border:1px solid #fecaca;padding:5px 8px;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;">
                                <i class="fa-solid fa-file-pdf"></i>
                            </a>
                            <button type="button" class="btn-icon btn-edit" title="Edit" onclick="editPanggil({{ json_encode($item) }})" style="padding:5px 8px;">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus" style="padding:5px 8px;"
                                onclick="confirmDelete('{{ route('bk.panggil-ortu.destroy', $item->id_panggil) }}','Yakin hapus data pemanggilan ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-6">
                            <i class="fa-solid fa-users-rectangle" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada catatan pemanggilan orang tua
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

{{-- MODAL TAMBAH/EDIT --}}
<div class="modal-overlay" id="modal-panggil">
    <div class="modal modal-lg" style="max-width: 800px;">
        <div class="modal-header">
            <h3 id="modal-title-panggil">Panggilan Orang Tua Baru</h3>
            <button onclick="closeModal('modal-panggil')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-panggil" method="POST" action="{{ route('bk.panggil-ortu.store') }}" enctype="multipart/form-data">
            @csrf
            <div id="method-field-panggil"></div>
            <div class="modal-body" style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding: 20px;">
                <!-- Kolom Kiri: Detil Pertemuan -->
                <div>
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 5px; color: var(--color-primary); font-size:0.95rem; font-weight:600;"><i class="fa-solid fa-envelope-open-text"></i> Detil Administrasi & Waktu</h4>
                    
                    <div class="form-group">
                        <label class="form-label">Jenis Panggilan <span class="required">*</span></label>
                        <select name="jenis_panggilan" id="po_jenis" class="form-control" required onchange="toggleNoSurat(this.value)">
                            <option value="panggilan_biasa">Panggilan Biasa (Undangan)</option>
                            <option value="sp_1">Surat Peringatan 1 (SP 1)</option>
                            <option value="sp_2">Surat Peringatan 2 (SP 2)</option>
                            <option value="sp_3">Surat Peringatan 3 (SP 3)</option>
                        </select>
                    </div>

                    <div class="form-group" id="no_surat_group" style="display:none;">
                        <label class="form-label">No. Surat Peringatan <span class="required">*</span></label>
                        <input type="text" name="no_surat" id="po_no_surat" class="form-control" placeholder="cth: 102/SP-1/BK/SMART/VII/2026" maxlength="100">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tanggal Pertemuan <span class="required">*</span></label>
                        <input type="date" name="tanggal_panggil" id="po_tgl" class="form-control" required value="{{ date('Y-m-d') }}">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Waktu Pertemuan <span class="required">*</span></label>
                        <input type="time" name="waktu_pertemuan" id="po_waktu" class="form-control" required value="08:00">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Lokasi Pertemuan <span class="required">*</span></label>
                        <input type="text" name="lokasi_pertemuan" id="po_lokasi" class="form-control" required value="Ruang Bimbingan Konseling (BK)" maxlength="255">
                    </div>

                    <div class="form-group" id="status-group" style="display:none;">
                        <label class="form-label">Status Kehadiran <span class="required">*</span></label>
                        <select name="status" id="po_status" class="form-control">
                            <option value="belum_hadir">Belum Hadir</option>
                            <option value="sudah_hadir">Sudah Hadir</option>
                            <option value="tidak_hadir">Tidak Hadir</option>
                        </select>
                    </div>
                </div>

                <!-- Kolom Kanan: Detil Siswa & Alasan -->
                <div>
                    <h4 style="margin-bottom: 15px; border-bottom: 1px solid var(--border-color); padding-bottom: 5px; color: var(--color-primary); font-size:0.95rem; font-weight:600;"><i class="fa-solid fa-user-graduate"></i> Detil Siswa & Alasan</h4>

                    <div class="form-group">
                        <label class="form-label">Pilih Kelas</label>
                        <select id="po_kelas" class="form-control" onchange="loadSiswa(this.value, 'po_nis')">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelas as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Siswa <span class="required">*</span></label>
                        <select name="nis" id="po_nis" class="form-control" required onchange="fetchSiswaDetail(this.value)">
                            <option value="">-- Pilih Kelas Terlebih Dahulu --</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nama Orang Tua/Wali</label>
                        <input type="text" name="nama_ortu" id="po_nama_ortu" class="form-control" placeholder="Akan terisi otomatis jika data wali ada" maxlength="100">
                    </div>

                    <div class="form-group">
                        <label class="form-label">No. HP/WA Orang Tua</label>
                        <input type="text" name="no_hp_ortu" id="po_hp_ortu" class="form-control" placeholder="Akan terisi otomatis jika data kontak ada" maxlength="20">
                    </div>

                    <div class="form-group">
                        <label class="form-label" id="po_alasan_label">Kendala Belajar <span class="required">*</span></label>
                        <select id="po_alasan_utama" class="form-control mb-2" onchange="updateAlasanText(this.value)">
                            <option value="">-- Pilih Kategori Kendala Belajar --</option>
                            <option value="Keterlambatan masuk sekolah yang berulang dan melebihi batas toleransi.">Keterlambatan Berulang</option>
                            <option value="Ketidakhadiran tanpa keterangan (membolos) selama beberapa hari pelajaran.">Absensi / Sering Membolos</option>
                            <option value="Penurunan hasil belajar secara signifikan pada beberapa mata pelajaran utama.">Penurunan Nilai Akademik</option>
                            <option value="Kurangnya motivasi dan partisipasi aktif dalam kegiatan pembelajaran di kelas.">Kurang Motivasi Belajar</option>
                            <option value="Lainnya">Lainnya (Ketik detail di bawah)</option>
                        </select>
                        <textarea name="alasan_panggil" id="po_alasan" class="form-control" placeholder="Jelaskan detail kendala belajar siswa di sini..." rows="2" required></textarea>
                    </div>
                </div>

                <!-- Hasil Pertemuan di bagian bawah modal -->
                <div style="grid-column: span 2;" id="hasil-pertemuan-container">
                    <div class="form-group mb-0">
                        <label class="form-label">Hasil Pertemuan / Kesepakatan (Diisi setelah pertemuan selesai)</label>
                        <textarea name="hasil_pertemuan" id="po_hasil" class="form-control" placeholder="Catatan kesepakatan atau tindak lanjut hasil pembinaan orang tua..." rows="2"></textarea>
                    </div>
                </div>

                <!-- File/Photo Uploads -->
                <div style="grid-column: span 2; display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 10px;" id="uploads-container">

                    {{-- Dropzone: Bukti Pertemuan --}}
                    <div class="form-group mb-0">
                        <label class="form-label" style="font-weight:600;font-size:0.8rem;text-transform:uppercase;letter-spacing:.04em;">
                            Foto Bukti Pertemuan
                            <span style="font-size:0.72rem;color:var(--text-muted);font-weight:400;text-transform:none;">(JPEG, PNG, PDF – max 2MB)</span>
                        </label>
                        <div class="po-dropzone" id="dz-bukti" tabindex="0" role="button" aria-label="Upload bukti pertemuan">
                            <input type="file" name="bukti_pertemuan" id="po_bukti" accept="image/jpeg,image/png,application/pdf" style="display:none;">
                            <div class="po-dz-idle" id="dz-bukti-idle">
                                <i class="fa-solid fa-cloud-arrow-up po-dz-icon"></i>
                                <span class="po-dz-text">Drag &amp; drop file di sini</span>
                                <span class="po-dz-hint">atau <u>klik untuk memilih</u></span>
                            </div>
                            <div class="po-dz-preview" id="dz-bukti-preview" style="display:none;">
                                <img id="dz-bukti-img" src="#" alt="Preview" class="po-dz-img" style="display:none;">
                                <div id="dz-bukti-pdf" class="po-dz-pdf-icon" style="display:none;">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <span id="dz-bukti-fname" class="po-dz-fname"></span>
                                </div>
                                <button type="button" class="po-dz-remove" id="dz-bukti-remove" title="Hapus file">&times;</button>
                            </div>
                            <div class="po-dz-existing" id="dz-bukti-existing" style="display:none;">
                                <a href="#" id="po_bukti_link" target="_blank" class="po-dz-existing-link">
                                    <i class="fa-solid fa-file-image"></i> Lihat Dokumen Tersimpan
                                </a>
                                <span class="po-dz-existing-hint">Upload baru untuk mengganti</span>
                            </div>
                        </div>
                    </div>

                    {{-- Dropzone: Surat Pernyataan --}}
                    <div class="form-group mb-0">
                        <label class="form-label" style="font-weight:600;font-size:0.8rem;text-transform:uppercase;letter-spacing:.04em;">
                            Foto / Scan Surat Pernyataan
                            <span style="font-size:0.72rem;color:var(--text-muted);font-weight:400;text-transform:none;">(JPEG, PNG, PDF – max 2MB)</span>
                        </label>
                        <div class="po-dropzone" id="dz-surat" tabindex="0" role="button" aria-label="Upload surat pernyataan">
                            <input type="file" name="surat_pernyataan" id="po_surat_pernyataan" accept="image/jpeg,image/png,application/pdf" style="display:none;">
                            <div class="po-dz-idle" id="dz-surat-idle">
                                <i class="fa-solid fa-cloud-arrow-up po-dz-icon"></i>
                                <span class="po-dz-text">Drag &amp; drop file di sini</span>
                                <span class="po-dz-hint">atau <u>klik untuk memilih</u></span>
                            </div>
                            <div class="po-dz-preview" id="dz-surat-preview" style="display:none;">
                                <img id="dz-surat-img" src="#" alt="Preview" class="po-dz-img" style="display:none;">
                                <div id="dz-surat-pdf" class="po-dz-pdf-icon" style="display:none;">
                                    <i class="fa-solid fa-file-pdf"></i>
                                    <span id="dz-surat-fname" class="po-dz-fname"></span>
                                </div>
                                <button type="button" class="po-dz-remove" id="dz-surat-remove" title="Hapus file">&times;</button>
                            </div>
                            <div class="po-dz-existing" id="dz-surat-existing" style="display:none;">
                                <a href="#" id="po_surat_link" target="_blank" class="po-dz-existing-link" style="color:#10b981;">
                                    <i class="fa-solid fa-file-signature"></i> Lihat Dokumen Tersimpan
                                </a>
                                <span class="po-dz-existing-hint">Upload baru untuk mengganti</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <div>
                    <button type="button" onclick="previewSurat()" class="btn btn-secondary" style="background:#e0e7ff; color:#4f46e5; border:1px solid #c7d2fe;"><i class="fa-solid fa-eye"></i> Pratinjau Surat</button>
                </div>
                <div style="display: flex; gap: 8px;">
                    <button type="button" onclick="closeModal('modal-panggil')" class="btn btn-secondary">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Data</button>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- MODAL DETAIL PEMANGGILAN --}}
<div class="modal-overlay" id="modal-detail" style="z-index: 1100;">
    <div class="modal modal-lg" style="max-width: 800px; width: 95%;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-circle-info" style="color:var(--color-primary);"></i> Detail Pemanggilan Orang Tua</h3>
            <button onclick="closeModal('modal-detail')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="padding: 20px;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <!-- Kolom Kiri: Informasi Administrasi -->
                <div>
                    <h4 style="margin-bottom: 12px; border-bottom: 1px solid var(--border-color); padding-bottom: 5px; color: var(--color-primary); font-size:0.95rem; font-weight:600;">
                        <i class="fa-solid fa-envelope-open-text"></i> Administrasi &amp; Waktu
                    </h4>
                    <table class="detail-info-table" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px 0; font-weight: 600; width: 130px; color: #4b5563;">Jenis Surat</td>
                            <td style="padding: 8px 0;" id="dt-jenis"></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px 0; font-weight: 600; color: #4b5563;">No. Surat</td>
                            <td style="padding: 8px 0; font-family: monospace; font-weight: 600;" id="dt-no-surat"></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px 0; font-weight: 600; color: #4b5563;">Waktu Pertemuan</td>
                            <td style="padding: 8px 0;" id="dt-waktu"></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px 0; font-weight: 600; color: #4b5563;">Lokasi</td>
                            <td style="padding: 8px 0;" id="dt-lokasi"></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px 0; font-weight: 600; color: #4b5563;">Status Kehadiran</td>
                            <td style="padding: 8px 0;" id="dt-status"></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px 0; font-weight: 600; color: #4b5563;">Guru BK</td>
                            <td style="padding: 8px 0;" id="dt-guru"></td>
                        </tr>
                    </table>
                </div>

                <!-- Kolom Kanan: Detail Siswa & Wali -->
                <div>
                    <h4 style="margin-bottom: 12px; border-bottom: 1px solid var(--border-color); padding-bottom: 5px; color: var(--color-primary); font-size:0.95rem; font-weight:600;">
                        <i class="fa-solid fa-user-graduate"></i> Siswa &amp; Orang Tua
                    </h4>
                    <table class="detail-info-table" style="width: 100%; border-collapse: collapse; margin-bottom: 15px;">
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px 0; font-weight: 600; width: 130px; color: #4b5563;">Siswa</td>
                            <td style="padding: 8px 0; font-weight: 600;" id="dt-siswa"></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px 0; font-weight: 600; color: #4b5563;">Kelas</td>
                            <td style="padding: 8px 0;" id="dt-kelas"></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px 0; font-weight: 600; color: #4b5563;">Orang Tua / Wali</td>
                            <td style="padding: 8px 0;" id="dt-ortu"></td>
                        </tr>
                        <tr style="border-bottom: 1px solid #f3f4f6;">
                            <td style="padding: 8px 0; font-weight: 600; color: #4b5563;">No. HP / WA</td>
                            <td style="padding: 8px 0;" id="dt-hp"></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Bagian Bawah: Alasan & Hasil -->
            <div style="margin-top: 10px;">
                <h4 style="margin-bottom: 10px; color: #374151; font-size: 0.9rem; font-weight: 600;" id="dt-alasan-title">Kendala Belajar / Alasan</h4>
                <div style="background: #f9fafb; border-left: 4px solid var(--color-primary); padding: 12px; border-radius: 4px; font-size: 0.88rem; line-height: 1.5; color: #374151; margin-bottom: 15px;" id="dt-alasan">
                </div>

                <h4 style="margin-bottom: 10px; color: #374151; font-size: 0.9rem; font-weight: 600;">Hasil Pertemuan &amp; Tindak Lanjut</h4>
                <div style="background: #f9fafb; border-left: 4px solid #10b981; padding: 12px; border-radius: 4px; font-size: 0.88rem; line-height: 1.5; color: #374151; min-height: 45px; margin-bottom: 20px;" id="dt-hasil">
                </div>
            </div>

            <!-- Bagian Lampiran Berkas -->
            <div>
                <h4 style="margin-bottom: 12px; border-bottom: 1px solid var(--border-color); padding-bottom: 5px; color: var(--color-primary); font-size:0.95rem; font-weight:600;">
                    <i class="fa-solid fa-paperclip"></i> Berkas Lampiran yang Dikirim
                </h4>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;" id="dt-files-container">
                    <!-- File Bukti -->
                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; background: #fff;">
                        <span style="display: block; font-size: 0.78rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 8px;">Bukti Pertemuan</span>
                        <div id="dt-file-bukti-box" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 120px; border: 1px dashed #d1d5db; border-radius: 6px; background: #f9fafb; overflow: hidden;">
                        </div>
                    </div>

                    <!-- File Surat Pernyataan -->
                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; background: #fff;">
                        <span style="display: block; font-size: 0.78rem; font-weight: 600; color: #6b7280; text-transform: uppercase; margin-bottom: 8px;">Surat Pernyataan</span>
                        <div id="dt-file-surat-box" style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 120px; border: 1px dashed #d1d5db; border-radius: 6px; background: #f9fafb; overflow: hidden;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 8px;">
            <a href="#" id="dt-btn-pdf" target="_blank" class="btn btn-secondary" style="background:#fee2e2; color:#ef4444; border:1px solid #fecaca;">
                <i class="fa-solid fa-file-pdf"></i> Cetak Surat PDF
            </a>
            <button type="button" onclick="closeModal('modal-detail')" class="btn btn-primary">Tutup</button>
        </div>
    </div>
</div>

{{-- MODAL PREVIEW SURAT (A4 ISOLATED ENGINE) --}}
<div class="modal-overlay" id="modal-preview" style="z-index: 1200;">
    <div class="modal modal-lg" style="max-width: 900px; width: 95%;">
        <div class="modal-header">
            <h3>Pratinjau Kertas Surat Panggilan</h3>
            <button onclick="closeModal('modal-preview')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="background: #525659; padding: 25px; display: flex; justify-content: center; align-items: flex-start; overflow-y: auto; max-height: calc(100vh - 180px);">
            <div id="preview-wrapper" style="background: white; width: 100%; max-width: 800px; box-shadow: 0 8px 24px rgba(0,0,0,0.3); border-radius: 4px; padding: 5px;">
                <div id="preview-container">
                    <div style="text-align: center; padding: 40px; color: #fff;"><i class="fa-solid fa-spinner fa-spin fa-2x"></i><br><span style="margin-top: 10px; display: inline-block;">Menyiapkan dokumen pratinjau...</span></div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeModal('modal-preview')" class="btn btn-secondary">Kembali ke Form</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleNoSurat(val) {
    const group = document.getElementById('no_surat_group');
    const input = document.getElementById('po_no_surat');
    if (val === 'panggilan_biasa') {
        group.style.display = 'none';
        input.removeAttribute('required');
        input.value = '';
    } else {
        group.style.display = 'block';
        input.setAttribute('required', 'required');
    }
    updateAlasanSection(val);
}

// Opsi untuk panggilan biasa (Kendala Belajar)
const OPSI_KENDALA = [
    { value: '', text: '-- Pilih Kategori Kendala Belajar --' },
    { value: 'Keterlambatan masuk sekolah yang berulang dan melebihi batas toleransi.', text: 'Keterlambatan Berulang' },
    { value: 'Ketidakhadiran tanpa keterangan (membolos) selama beberapa hari pelajaran.', text: 'Absensi / Sering Membolos' },
    { value: 'Penurunan hasil belajar secara signifikan pada beberapa mata pelajaran utama.', text: 'Penurunan Nilai Akademik' },
    { value: 'Kurangnya motivasi dan partisipasi aktif dalam kegiatan pembelajaran di kelas.', text: 'Kurang Motivasi Belajar' },
    { value: 'Lainnya', text: 'Lainnya (Ketik detail di bawah)' },
];

// Opsi untuk SP 1/2/3 (Bentuk Pelanggaran)
const OPSI_PELANGGARAN = [
    { value: '', text: '-- Pilih Jenis Pelanggaran --' },
    { value: 'Keterlambatan masuk sekolah secara berulang dan melebihi batas toleransi yang ditetapkan.', text: 'Keterlambatan Berulang' },
    { value: 'Ketidakhadiran tanpa keterangan (membolos) selama beberapa hari pelajaran.', text: 'Membolos / Absen Tidak Sah' },
    { value: 'Pelanggaran tata tertib dan disiplin sekolah yang bersifat serius.', text: 'Pelanggaran Tata Tertib Serius' },
    { value: 'Perundungan (bullying) terhadap sesama siswa di lingkungan sekolah.', text: 'Perundungan (Bullying)' },
    { value: 'Penggunaan atau pengedaran zat terlarang / benda berbahaya di lingkungan sekolah.', text: 'Zat Terlarang / Benda Berbahaya' },
    { value: 'Perusakan fasilitas/sarana prasarana milik sekolah.', text: 'Perusakan Fasilitas Sekolah' },
    { value: 'Lainnya', text: 'Lainnya (Ketik detail di bawah)' },
];

function updateAlasanSection(jenis) {
    const label    = document.getElementById('po_alasan_label');
    const select   = document.getElementById('po_alasan_utama');
    const textarea = document.getElementById('po_alasan');
    const isSP = (jenis !== 'panggilan_biasa');

    // Update label
    const labelText = isSP ? 'Bentuk Pelanggaran' : 'Kendala Belajar';
    label.innerHTML = `${labelText} <span class="required">*</span>`;

    // Update placeholder textarea
    textarea.placeholder = isSP
        ? 'Jelaskan bentuk pelanggaran yang dilakukan siswa secara detail...'
        : 'Jelaskan detail kendala belajar siswa di sini...';

    // Rebuild dropdown options
    const opsi = isSP ? OPSI_PELANGGARAN : OPSI_KENDALA;
    select.innerHTML = '';
    opsi.forEach(o => {
        const opt = document.createElement('option');
        opt.value = o.value;
        opt.textContent = o.text;
        select.appendChild(opt);
    });
    select.value = '';
}

function updateAlasanText(val) {
    const textarea = document.getElementById('po_alasan');
    if (val === 'Lainnya') {
        textarea.focus();
        return;
    }
    if (val) {
        textarea.value = val;
    }
}

function fetchSiswaDetail(nis) {
    if (!nis) return;
    fetch(`{{ route('bk.panggil-ortu.siswa-detail') }}?nis=${nis}`)
        .then(r => r.json())
        .then(data => {
            if (data.nama_ortu) {
                document.getElementById('po_nama_ortu').value = data.nama_ortu;
            } else {
                document.getElementById('po_nama_ortu').value = '';
            }
            if (data.no_hp_ortu) {
                document.getElementById('po_hp_ortu').value = data.no_hp_ortu;
            } else {
                document.getElementById('po_hp_ortu').value = '';
            }
        })
        .catch(err => console.error('Gagal mengambil data wali siswa:', err));
}

function previewSurat() {
    const nis = document.getElementById('po_nis').value;
    const tgl = document.getElementById('po_tgl').value;
    const waktu = document.getElementById('po_waktu').value;
    const lokasi = document.getElementById('po_lokasi').value;
    const jenis = document.getElementById('po_jenis').value;
    const noSurat = document.getElementById('po_no_surat').value;
    const alasan = document.getElementById('po_alasan').value;
    const namaOrtu = document.getElementById('po_nama_ortu').value;
    const noHpOrtu = document.getElementById('po_hp_ortu').value;

    if (!nis || !tgl || !waktu || !lokasi || !alasan) {
        alert('Mohon isi Siswa, Tanggal, Waktu, Lokasi, dan Alasan terlebih dahulu!');
        return;
    }
    if (jenis !== 'panggilan_biasa' && !noSurat) {
        alert('Nomor Surat wajib diisi untuk jenis Surat Peringatan!');
        return;
    }

    const previewContainer = document.getElementById('preview-container');
    previewContainer.innerHTML = '<div style="text-align: center; padding: 40px; color: #666;"><i class="fa-solid fa-spinner fa-spin fa-2x"></i><br><span style="margin-top: 10px; display: inline-block;">Menghasilkan lembar surat...</span></div>';
    openModal('modal-preview');

    fetch('{{ route("bk.panggil-ortu.preview") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            nis: nis,
            tanggal_panggil: tgl,
            waktu_pertemuan: waktu,
            lokasi_pertemuan: lokasi,
            jenis_panggilan: jenis,
            no_surat: noSurat,
            alasan_panggil: alasan,
            nama_ortu: namaOrtu,
            no_hp_ortu: noHpOrtu
        })
    })
    .then(r => {
        if (!r.ok) throw new Error('Gagal memuat preview.');
        return r.text();
    })
    .then(html => {
        previewContainer.innerHTML = '';
        const iframe = document.createElement('iframe');
        iframe.style.width = '100%';
        iframe.style.height = '620px';
        iframe.style.border = 'none';
        iframe.style.background = '#fff';
        previewContainer.appendChild(iframe);
        
        const doc = iframe.contentDocument || iframe.contentWindow.document;
        doc.open();
        doc.write(html);
        doc.close();
    })
    .catch(err => {
        previewContainer.innerHTML = '<div style="color: red; padding: 30px; text-align: center;">Gagal merender draf surat. Periksa kembali kelengkapan formulir Anda.</div>';
    });
}

function loadSiswa(idKelas, targetSelectId, selectedNis = '') {
    const select = document.getElementById(targetSelectId);
    if (!idKelas) {
        select.innerHTML = '<option value="">-- Pilih Kelas Terlebih Dahulu --</option>';
        return;
    }
    if (!selectedNis) {
        select.innerHTML = '<option value="">-- Loading... --</option>';
    }
    fetch(`/bk/catat-pelanggaran/siswa-by-kelas?id_kelas=${idKelas}`)
        .then(r => r.json())
        .then(list => {
            select.innerHTML = '<option value="">-- Pilih Siswa --</option>';
            list.forEach(s => {
                const opt = document.createElement('option');
                opt.value = s.nis;
                opt.textContent = `${s.nis} - ${s.nama_siswa}`;
                if (s.nis.toString() === selectedNis.toString()) {
                    opt.selected = true;
                }
                select.appendChild(opt);
            });
            if (selectedNis) {
                fetchSiswaDetail(selectedNis);
            }
        })
        .catch(err => {
            select.innerHTML = '<option value="">-- Gagal memuat data siswa --</option>';
        });
}

function openAddModal() {
    document.getElementById('form-panggil').action = '{{ route("bk.panggil-ortu.store") }}';
    document.getElementById('method-field-panggil').innerHTML = '';
    document.getElementById('modal-title-panggil').textContent = 'Panggilan Orang Tua Baru';
    document.getElementById('po_tgl').value = '{{ date("Y-m-d") }}';
    document.getElementById('po_kelas').value = '';
    document.getElementById('po_nis').innerHTML = '<option value="">-- Pilih Kelas Terlebih Dahulu --</option>';
    document.getElementById('po_nama_ortu').value = '';
    document.getElementById('po_hp_ortu').value = '';
    document.getElementById('po_alasan_utama').value = '';
    document.getElementById('po_alasan').value = '';
    document.getElementById('po_hasil').value = '';
    document.getElementById('po_waktu').value = '08:00';
    document.getElementById('po_lokasi').value = 'Ruang Bimbingan Konseling (BK)';
    
    document.getElementById('po_jenis').value = 'panggilan_biasa';
    toggleNoSurat('panggilan_biasa');
    
    resetDropzone('bukti');
    resetDropzone('surat');
    
    document.getElementById('status-group').style.display = 'none';
    document.getElementById('hasil-pertemuan-container').style.display = 'none';
    document.getElementById('uploads-container').style.display = 'none';
    
    openModal('modal-panggil');
}

function editPanggil(data) {
    document.getElementById('form-panggil').action = `/bk/panggil-ortu/${data.id_panggil}`;
    document.getElementById('method-field-panggil').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-panggil').textContent = 'Edit Data Panggilan Orang Tua';
    
    const dateVal = data.tanggal_panggil ? data.tanggal_panggil.substring(0, 10) : '';
    document.getElementById('po_tgl').value = dateVal;
    document.getElementById('po_waktu').value = data.waktu_pertemuan ? data.waktu_pertemuan.substring(0, 5) : '08:00';
    document.getElementById('po_lokasi').value = data.lokasi_pertemuan || 'Ruang Bimbingan Konseling (BK)';
    
    document.getElementById('po_jenis').value = data.jenis_panggilan || 'panggilan_biasa';
    toggleNoSurat(data.jenis_panggilan || 'panggilan_biasa');
    document.getElementById('po_no_surat').value = data.no_surat || '';
    
    if (data.siswa && data.siswa.id_kelas) {
        // Tampilkan nama sementara sambil loadSiswa berjalan async
        const namaDisplay = data.siswa.nama_siswa ? `${data.nis} - ${data.siswa.nama_siswa}` : data.nis;
        document.getElementById('po_kelas').value = data.siswa.id_kelas;
        document.getElementById('po_nis').innerHTML = `<option value="${data.nis}" selected>${namaDisplay}</option>`;
        loadSiswa(data.siswa.id_kelas, 'po_nis', data.nis);
    } else {
        document.getElementById('po_kelas').value = '';
        const namaDisplay = data.siswa && data.siswa.nama_siswa
            ? `${data.nis} - ${data.siswa.nama_siswa}`
            : data.nis;
        document.getElementById('po_nis').innerHTML = `<option value="${data.nis}" selected>${namaDisplay}</option>`;
    }
    
    document.getElementById('po_nama_ortu').value = data.nama_ortu || '';
    document.getElementById('po_hp_ortu').value = data.no_hp_ortu || '';
    document.getElementById('po_alasan').value = data.alasan_panggil;

    // Coba cocokkan alasan tersimpan dengan salah satu opsi dropdown
    const selectAlasan = document.getElementById('po_alasan_utama');
    const storedAlasan = (data.alasan_panggil || '').trim();
    let matched = false;
    Array.from(selectAlasan.options).forEach(opt => {
        if (opt.value && opt.value.trim() === storedAlasan) {
            opt.selected = true;
            matched = true;
        }
    });
    if (!matched) selectAlasan.value = '';

    document.getElementById('po_hasil').value = data.hasil_pertemuan || '';

    // Dropzone: bukti pertemuan
    resetDropzone('bukti');
    if (data.bukti_pertemuan) {
        document.getElementById('dz-bukti-existing').style.display = 'flex';
        document.getElementById('po_bukti_link').href = `/storage/${data.bukti_pertemuan}`;
    }

    // Dropzone: surat pernyataan
    resetDropzone('surat');
    if (data.surat_pernyataan) {
        document.getElementById('dz-surat-existing').style.display = 'flex';
        document.getElementById('po_surat_link').href = `/storage/${data.surat_pernyataan}`;
    }

    document.getElementById('status-group').style.display = 'block';
    document.getElementById('po_status').value = data.status;
    document.getElementById('hasil-pertemuan-container').style.display = 'block';
    document.getElementById('uploads-container').style.display = 'grid';
    
    openModal('modal-panggil');
}

function showDetailPanggil(data) {
    // 1. Jenis Surat Badge
    const elJenis = document.getElementById('dt-jenis');
    if (data.jenis_panggilan === 'panggilan_biasa') {
        elJenis.innerHTML = '<span class="badge" style="background:#e0f2fe;color:#0369a1;font-weight:600;font-size:0.85rem;padding:4px 8px;">Panggilan Biasa (Undangan)</span>';
        document.getElementById('dt-alasan-title').textContent = 'Kendala Belajar';
    } else {
        const labelSp = data.jenis_panggilan.replace('_', ' ').toUpperCase();
        elJenis.innerHTML = `<span class="badge" style="background:#fee2e2;color:#b91c1c;font-weight:bold;font-size:0.85rem;padding:4px 8px;">${labelSp}</span>`;
        document.getElementById('dt-alasan-title').textContent = 'Bentuk Pelanggaran';
    }

    // 2. No Surat
    document.getElementById('dt-no-surat').textContent = data.no_surat || '-';

    // 3. Waktu & Lokasi
    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
    const tgl = data.tanggal_panggil ? new Date(data.tanggal_panggil).toLocaleDateString('id-ID', options) : '-';
    const jam = data.waktu_pertemuan ? data.waktu_pertemuan.substring(0, 5) + ' WIB' : '-';
    document.getElementById('dt-waktu').textContent = `${tgl} @ ${jam}`;
    document.getElementById('dt-lokasi').textContent = data.lokasi_pertemuan || '-';

    // 4. Status Kehadiran Badge
    const elStatus = document.getElementById('dt-status');
    if (data.status === 'sudah_hadir') {
        elStatus.innerHTML = '<span class="badge badge-success" style="font-size:0.85rem;padding:4px 8px;"><i class="fa-solid fa-circle-check"></i> Sudah Hadir</span>';
    } else if (data.status === 'tidak_hadir') {
        elStatus.innerHTML = '<span class="badge badge-danger" style="font-size:0.85rem;padding:4px 8px;"><i class="fa-solid fa-circle-xmark"></i> Tidak Hadir</span>';
    } else {
        elStatus.innerHTML = '<span class="badge badge-warning" style="font-size:0.85rem;padding:4px 8px;"><i class="fa-solid fa-envelope-open-text"></i> Belum Hadir</span>';
    }

    // 5. Guru BK
    document.getElementById('dt-guru').textContent = data.guru ? data.guru.nama_guru : '-';

    // 6. Siswa
    const namaSiswa = data.siswa ? data.siswa.nama_siswa : '-';
    document.getElementById('dt-siswa').textContent = `${data.nis} - ${namaSiswa}`;
    document.getElementById('dt-kelas').textContent = (data.siswa && data.siswa.kelas) ? data.siswa.kelas.nama_kelas : '-';

    // 7. Orang Tua & HP
    document.getElementById('dt-ortu').textContent = data.nama_ortu || '-';
    document.getElementById('dt-hp').textContent = data.no_hp_ortu || '-';

    // 8. Alasan & Hasil
    document.getElementById('dt-alasan').textContent = data.alasan_panggil || '-';
    document.getElementById('dt-hasil').textContent = data.hasil_pertemuan || 'Belum ada hasil pertemuan dicatat.';

    // Helper to render file preview boxes
    function renderFilePreview(boxId, filePath) {
        const box = document.getElementById(boxId);
        box.innerHTML = '';
        if (!filePath) {
            box.innerHTML = `
                <div style="color:#9ca3af; font-size:0.82rem; text-align:center; padding:15px;">
                    <i class="fa-regular fa-folder-open" style="font-size:1.8rem; display:block; margin-bottom:4px; opacity:0.6;"></i>
                    Belum diunggah
                </div>
            `;
            return;
        }

        const url = `/storage/${filePath}`;
        const isPdf = filePath.toLowerCase().endsWith('.pdf');

        if (isPdf) {
            box.innerHTML = `
                <a href="${url}" target="_blank" style="display:flex; flex-direction:column; align-items:center; text-decoration:none; color:#dc2626; padding:10px; width:100%;">
                    <i class="fa-solid fa-file-pdf" style="font-size:2.8rem; margin-bottom:6px;"></i>
                    <span style="font-size:0.75rem; font-weight:600; text-align:center; color:#374151; max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">Lihat Dokumen PDF</span>
                </a>
            `;
        } else {
            box.innerHTML = `
                <a href="${url}" target="_blank" style="display:block; width:100%; height:120px; position:relative;">
                    <img src="${url}" style="width:100%; height:120px; object-fit:cover;" alt="Pratinjau berkas">
                    <div style="position:absolute; bottom:0; left:0; right:0; background:rgba(0,0,0,0.6); color:#fff; font-size:0.7rem; text-align:center; padding:4px 0;">
                        <i class="fa-solid fa-magnifying-glass-plus"></i> Perbesar Gambar
                    </div>
                </a>
            `;
        }
    }

    renderFilePreview('dt-file-bukti-box', data.bukti_pertemuan);
    renderFilePreview('dt-file-surat-box', data.surat_pernyataan);

    // 9. PDF Button
    document.getElementById('dt-btn-pdf').href = `/bk/panggil-ortu/${data.id_panggil}/pdf`;

    openModal('modal-detail');
}

// ── Dropzone helpers ─────────────────────────────────────────────────────────
function resetDropzone(key) {
    const input = document.getElementById(key === 'bukti' ? 'po_bukti' : 'po_surat_pernyataan');
    input.value = '';
    document.getElementById(`dz-${key}-idle`).style.display    = 'flex';
    document.getElementById(`dz-${key}-preview`).style.display  = 'none';
    document.getElementById(`dz-${key}-existing`).style.display = 'none';
    const img = document.getElementById(`dz-${key}-img`);
    if (img) { img.style.display = 'none'; img.src = '#'; }
    const pdf = document.getElementById(`dz-${key}-pdf`);
    if (pdf) pdf.style.display = 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    initDropzone('bukti',  'po_bukti');
    initDropzone('surat', 'po_surat_pernyataan');
});

function initDropzone(key, inputId) {
    const zone   = document.getElementById(`dz-${key}`);
    const input  = document.getElementById(inputId);
    const idle   = document.getElementById(`dz-${key}-idle`);
    const prev   = document.getElementById(`dz-${key}-preview`);
    const img    = document.getElementById(`dz-${key}-img`);
    const pdfBox = document.getElementById(`dz-${key}-pdf`);
    const fname  = document.getElementById(`dz-${key}-fname`);
    const rmBtn  = document.getElementById(`dz-${key}-remove`);
    const exist  = document.getElementById(`dz-${key}-existing`);

    // Click on zone → open file picker (not on remove button)
    zone.addEventListener('click', function (e) {
        if (rmBtn && (e.target === rmBtn || rmBtn.contains(e.target))) return;
        input.click();
    });
    zone.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); input.click(); }
    });

    // Drag events
    zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('po-dz-over'); });
    zone.addEventListener('dragleave', e => { zone.classList.remove('po-dz-over'); });
    zone.addEventListener('drop', e => {
        e.preventDefault();
        zone.classList.remove('po-dz-over');
        const file = e.dataTransfer.files[0];
        if (file) handleFile(file);
    });

    // Input change
    input.addEventListener('change', function () {
        if (this.files[0]) handleFile(this.files[0]);
    });

    // Remove button
    rmBtn.addEventListener('click', function (e) {
        e.stopPropagation();
        resetDropzone(key);
    });

    function handleFile(file) {
        const maxBytes = 2 * 1024 * 1024;
        if (file.size > maxBytes) {
            alert('Ukuran file terlalu besar. Maksimum 2MB.');
            return;
        }
        const allowed = ['image/jpeg','image/png','application/pdf'];
        if (!allowed.includes(file.type)) {
            alert('Format file tidak didukung. Gunakan JPEG, PNG, atau PDF.');
            return;
        }

        // Transfer file to hidden input
        const dt = new DataTransfer();
        dt.items.add(file);
        input.files = dt.files;

        // Show preview panel
        idle.style.display   = 'none';
        exist.style.display  = 'none';
        prev.style.display   = 'flex';

        if (file.type === 'application/pdf') {
            img.style.display    = 'none';
            pdfBox.style.display = 'flex';
            fname.textContent    = file.name;
        } else {
            pdfBox.style.display = 'none';
            img.style.display    = 'block';
            const reader = new FileReader();
            reader.onload = e => { img.src = e.target.result; };
            reader.readAsDataURL(file);
        }
    }
}
</script>

<style>
/* ── Panggil-Ortu Dropzone Styles ────────────────────────────────────────── */
.po-dropzone {
    border: 2px dashed var(--border-color, #d1d5db);
    border-radius: 10px;
    background: var(--bg-card, #fff);
    min-height: 110px;
    cursor: pointer;
    transition: border-color .2s, background .2s, box-shadow .2s;
    position: relative;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
    outline: none;
}
.po-dropzone:hover,
.po-dropzone:focus {
    border-color: var(--color-primary, #6366f1);
    background: color-mix(in srgb, var(--color-primary, #6366f1) 5%, transparent);
    box-shadow: 0 0 0 3px color-mix(in srgb, var(--color-primary, #6366f1) 15%, transparent);
}
.po-dropzone.po-dz-over {
    border-color: var(--color-primary, #6366f1);
    background: color-mix(in srgb, var(--color-primary, #6366f1) 10%, transparent);
    box-shadow: 0 0 0 4px color-mix(in srgb, var(--color-primary, #6366f1) 20%, transparent);
}
.po-dz-idle {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    padding: 18px 12px;
    pointer-events: none;
    text-align: center;
}
.po-dz-icon {
    font-size: 1.7rem;
    color: var(--color-primary, #6366f1);
    opacity: .75;
    margin-bottom: 2px;
}
.po-dz-text {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--text-main, #374151);
}
.po-dz-hint {
    font-size: 0.74rem;
    color: var(--text-muted, #9ca3af);
}
.po-dz-preview {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    position: relative;
    width: 100%;
}
.po-dz-img {
    max-height: 90px;
    max-width: 100%;
    border-radius: 6px;
    object-fit: contain;
    box-shadow: 0 2px 8px rgba(0,0,0,.12);
}
.po-dz-pdf-icon {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    color: #ef4444;
    pointer-events: none;
}
.po-dz-pdf-icon i {
    font-size: 2.2rem;
}
.po-dz-fname {
    font-size: 0.72rem;
    color: var(--text-muted, #6b7280);
    max-width: 130px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    text-align: center;
}
.po-dz-remove {
    position: absolute;
    top: 6px;
    right: 6px;
    background: #ef4444;
    color: #fff;
    border: none;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    font-size: 0.85rem;
    line-height: 1;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 1px 4px rgba(0,0,0,.2);
    transition: background .15s, transform .15s;
}
.po-dz-remove:hover {
    background: #b91c1c;
    transform: scale(1.1);
}
.po-dz-existing {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    padding: 14px 12px;
    pointer-events: none;
    text-align: center;
}
.po-dz-existing-link {
    font-size: 0.82rem;
    font-weight: 600;
    color: var(--color-primary, #6366f1);
    pointer-events: all;
    text-decoration: none;
}
.po-dz-existing-link:hover {
    text-decoration: underline;
}
.po-dz-existing-hint {
    font-size: 0.72rem;
    color: var(--text-muted, #9ca3af);
}
</style>
@endpush
@endsection
