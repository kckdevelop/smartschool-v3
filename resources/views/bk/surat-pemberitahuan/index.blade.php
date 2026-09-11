@extends('layouts.app')

@section('title', 'Surat Pemberitahuan Ortu BK — SmartSchool')
@section('header_title', 'Surat Pemberitahuan Ortu (Pra-SP 1)')
@section('header_subtitle', 'Modul pembuatan & pengiriman surat pemberitahuan ke orang tua sebelum dilakukan SP 1')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Filter Card --}}
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('bk.surat-pemberitahuan.index') }}" class="flex-row-wrap gap-4 align-items-end">
                <div class="form-group mb-0" style="min-width: 180px;">
                    <label class="form-label-sm">Filter Status</label>
                    <select name="status" class="form-control form-control-sm">
                        <option value="">-- Semua Status --</option>
                        <option value="diterbitkan" {{ request('status') === 'diterbitkan' ? 'selected' : '' }}>Diterbitkan</option>
                        <option value="disampaikan" {{ request('status') === 'disampaikan' ? 'selected' : '' }}>Disampaikan</option>
                        <option value="lanjut_sp1" {{ request('status') === 'lanjut_sp1' ? 'selected' : '' }}>Dilanjutkan SP 1</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
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
                    <a href="{{ route('bk.surat-pemberitahuan.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-file-circle-exclamation" style="color:var(--color-primary);"></i> Daftar Surat Pemberitahuan ke Orang Tua</h2>
            <div class="card-header-right">
                <button class="btn btn-primary btn-sm" onclick="openAddModal()" id="btn-tambah-surat">
                    <i class="fa-solid fa-plus"></i> Buat Surat Pemberitahuan
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th style="width:130px;">Tgl & No. Surat</th>
                        <th>Siswa & Kelas</th>
                        <th>Wali Kelas</th>
                        <th>Orang Tua / HP</th>
                        <th>Alasan Pemberitahuan (Pra-SP 1)</th>
                        <th style="width:120px;text-align:center;">Status</th>
                        <th>Guru BK</th>
                        <th style="width:170px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $data->firstItem() + $i }}</td>
                        <td style="font-size:0.85rem;">
                            <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($item->tanggal_surat)->format('d/m/Y') }}</div>
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
                            <div style="font-size:0.85rem;font-weight:600;">{{ $item->siswa->kelas->guru->nama_guru ?? '-' }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">NIP: {{ $item->siswa->kelas->guru->no_id ?? '-' }}</div>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $item->nama_ortu ?? ($item->siswa->detail->nama_wali ?? $item->siswa->detail->nama_ayah ?? '-') }}</div>
                            <div style="font-size:0.8rem;color:var(--text-muted);">{{ $item->no_hp_ortu ?? '-' }}</div>
                        </td>
                        <td style="font-size:0.85rem; max-width: 220px;">
                            <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->alasan_pemberitahuan }}">
                                {{ $item->alasan_pemberitahuan }}
                            </div>
                            @if($item->tindakan_sekolah)
                                <div style="font-size:0.75rem;color:var(--text-muted);margin-top:2px;">
                                    <i class="fa-solid fa-list-check"></i> {{ Str::limit($item->tindakan_sekolah, 30) }}
                                </div>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            @if($item->status === 'lanjut_sp1')
                                <span class="badge badge-danger" style="background:#fee2e2;color:#b91c1c;border:1px solid #fca5a5;">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Lanjut SP 1
                                </span>
                            @elseif($item->status === 'disampaikan')
                                <span class="badge badge-info" style="background:#e0f2fe;color:#0369a1;">
                                    <i class="fa-solid fa-paper-plane"></i> Disampaikan
                                </span>
                            @elseif($item->status === 'selesai')
                                <span class="badge badge-success">
                                    <i class="fa-solid fa-circle-check"></i> Selesai
                                </span>
                            @else
                                <span class="badge badge-warning">
                                    <i class="fa-solid fa-file-circle-check"></i> Diterbitkan
                                </span>
                            @endif
                        </td>
                        <td style="font-size:0.8rem;color:var(--text-muted);">{{ $item->guru->nama_guru ?? '-' }}</td>
                        <td class="action-cell" style="gap:4px;">
                            <button class="btn-icon" title="Lihat Detail" onclick="showDetailSurat({{ json_encode($item) }})" style="background:#e0f2fe;color:#0284c7;border:1px solid #bae6fd;padding:5px 8px;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                            <a href="{{ route('bk.surat-pemberitahuan.pdf', $item->id_pemberitahuan) }}" class="btn-icon btn-print" title="Cetak PDF" target="_blank" style="background:#fee2e2;color:#ef4444;border:1px solid #fecaca;padding:5px 8px;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;">
                                <i class="fa-solid fa-file-pdf"></i>
                            </a>
                            @if($item->status !== 'lanjut_sp1')
                                <button type="button" class="btn-icon" title="Lanjutkan ke SP 1" onclick="openEscalateModal({{ json_encode($item) }})" style="background:#fef3c7;color:#b45309;border:1px solid #fde68a;padding:5px 8px;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;">
                                    <i class="fa-solid fa-triangle-exclamation"></i>
                                </button>
                            @else
                                <a href="{{ route('bk.panggil-ortu.index') }}?nis={{ $item->nis }}" class="btn-icon" title="Lihat Rekam SP 1" style="background:#fee2e2;color:#b91c1c;border:1px solid #fca5a5;padding:5px 8px;border-radius:4px;display:inline-flex;align-items:center;justify-content:center;">
                                    <i class="fa-solid fa-link"></i>
                                </a>
                            @endif
                            <button type="button" class="btn-icon btn-edit" title="Edit" onclick="editSurat({{ json_encode($item) }})" style="padding:5px 8px;">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus" style="padding:5px 8px;"
                                onclick="confirmDelete('{{ route('bk.surat-pemberitahuan.destroy', $item->id_pemberitahuan) }}','Yakin hapus surat pemberitahuan ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-6">
                            <i class="fa-solid fa-file-circle-exclamation" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada catatan surat pemberitahuan ke orang tua
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($data->hasPages())
        <div class="card-footer" style="padding:12px 20px;">
            {{ $data->links() }}
        </div>
        @endif
    </div>
</div>

{{-- MODAL TAMBAH / EDIT SURAT PEMBERITAHUAN --}}
<div class="modal-backdrop" id="modal-surat" style="display:none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="modal-title-surat"><i class="fa-solid fa-file-circle-exclamation" style="color:var(--color-primary);"></i> Buat Surat Pemberitahuan Ortu</h3>
                <button type="button" class="modal-close" onclick="closeSuratModal()">&times;</button>
            </div>
            <form id="form-surat" method="POST" action="{{ route('bk.surat-pemberitahuan.store') }}">
                @csrf
                <input type="hidden" name="_method" id="form-method-surat" value="POST">
                <div class="modal-body" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label required">Nomor Surat</label>
                            <input type="text" name="no_surat" id="surat-no_surat" class="form-control" placeholder="Contoh: 015/BK/SMART/IX/2026">
                            <small class="form-text text-muted">Opsional, bisa dikosongkan jika belum ada nomor resmi.</small>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label required">Tanggal Surat</label>
                            <input type="date" name="tanggal_surat" id="surat-tanggal_surat" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="card p-3 mb-3" style="background:#f8fafc; border: 1px solid #e2e8f0;">
                        <h4 style="font-size:0.9rem; font-weight:700; margin-bottom:10px; color:#334155;"><i class="fa-solid fa-user-graduate"></i> Data Siswa & Wali Kelas</h4>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="form-label required">NIS Siswa</label>
                                <div class="input-group">
                                    <input type="text" name="nis" id="surat-nis" class="form-control" placeholder="Ketik NIS Siswa..." required>
                                    <button type="button" class="btn btn-secondary" onclick="checkSiswaDetail()"><i class="fa-solid fa-magnifying-glass"></i> Cari</button>
                                </div>
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-label">Nama Siswa / Kelas</label>
                                <input type="text" id="surat-info-siswa" class="form-control" readonly placeholder="Otomatis terisi setelah NIS dicari..." style="background:#e2e8f0;">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label class="form-label">Wali Kelas Siswa</label>
                                <input type="text" id="surat-info-walikelas" class="form-control" readonly placeholder="Otomatis terisi..." style="background:#e2e8f0;">
                            </div>
                            <div class="col-md-6 form-group">
                                <label class="form-label">Nama Orang Tua / Wali</label>
                                <input type="text" name="nama_ortu" id="surat-nama_ortu" class="form-control" placeholder="Nama Orang Tua / Wali">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 form-group mb-0">
                                <label class="form-label">No. HP Orang Tua</label>
                                <input type="text" name="no_hp_ortu" id="surat-no_hp_ortu" class="form-control" placeholder="No. HP Orang Tua / WA">
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Alasan Pemberitahuan (Pra-SP 1)</label>
                        <div class="mb-2">
                            <span class="badge" style="cursor:pointer; background:#e0f2fe; color:#0369a1; border:1px solid #bae6fd; margin-right:4px;" onclick="addQuickReason('Sering membolos dan tidak masuk kelas tanpa keterangan')">+ Sering Membolos / Tanpa Keterangan</span>
                            <span class="badge" style="cursor:pointer; background:#fef3c7; color:#92400e; border:1px solid #fde68a; margin-right:4px;" onclick="addQuickReason('Sering terlambat masuk sekolah lebih dari 3x')">+ Sering Terlambat</span>
                            <span class="badge" style="cursor:pointer; background:#fee2e2; color:#991b1b; border:1px solid #fca5a5;" onclick="addQuickReason('Sering tidak mengikuti jam pelajaran kelas (cabut)')">+ Cabut Jam Pelajaran</span>
                        </div>
                        <textarea name="alasan_pemberitahuan" id="surat-alasan_pemberitahuan" class="form-control" rows="3" placeholder="Jelaskan alasan pemberitahuan, misal: Anak sudah tidak masuk sekolah tanpa keterangan selama 3 hari berturut-turut..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tindakan / Imbauan dari Sekolah (Opsional)</label>
                        <textarea name="tindakan_sekolah" id="surat-tindakan_sekolah" class="form-control" rows="2" placeholder="Saran/tindakan yang diharapkan dari orang tua untuk membimbing siswa di rumah..."></textarea>
                    </div>

                    <div class="form-group id-status-group" id="group-status-edit" style="display:none;">
                        <label class="form-label required">Status Surat</label>
                        <select name="status" id="surat-status" class="form-control">
                            <option value="diterbitkan">Diterbitkan</option>
                            <option value="disampaikan">Disampaikan ke Ortu</option>
                            <option value="lanjut_sp1">Dilanjutkan ke SP 1</option>
                            <option value="selesai">Selesai / Perbaikan Sikap</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="display:flex; justify-size:space-between; align-items:center; gap:8px;">
                    <button type="button" class="btn btn-info btn-sm" onclick="previewPdfForm()"><i class="fa-solid fa-eye"></i> Preview Surat</button>
                    <div style="margin-left:auto; display:flex; gap:8px;">
                        <button type="button" class="btn btn-secondary" onclick="closeSuratModal()">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Surat</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL DETAIL SURAT PEMBERITAHUAN --}}
<div class="modal-backdrop" id="modal-detail-surat" style="display:none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fa-solid fa-circle-info" style="color:var(--color-primary);"></i> Detail Surat Pemberitahuan Ortu</h3>
                <button type="button" class="modal-close" onclick="closeDetailModal()">&times;</button>
            </div>
            <div class="modal-body" id="detail-surat-body">
                {{-- Dynamic via JS --}}
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeDetailModal()">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL ESKALASI KE SP 1 --}}
<div class="modal-backdrop" id="modal-escalate" style="display:none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:#fffbeb; border-bottom:1px solid #fde68a;">
                <h3 class="modal-title" style="color:#b45309;"><i class="fa-solid fa-triangle-exclamation"></i> Lanjutkan Menjadi Surat Peringatan 1 (SP 1)</h3>
                <button type="button" class="modal-close" onclick="closeEscalateModal()">&times;</button>
            </div>
            <form id="form-escalate" method="POST" action="">
                @csrf
                <div class="modal-body">
                    <div class="alert alert-warning mb-3" style="font-size:0.85rem;">
                        <i class="fa-solid fa-circle-exclamation"></i> Pengeskalasian ini akan secara otomatis menerbitkan data pemanggilan <strong>Surat Peringatan 1 (SP 1)</strong> pada menu Panggil Orang Tua dan mengubah status surat pemberitahuan ini menjadi <strong>Dilanjutkan SP 1</strong>.
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor Surat SP 1</label>
                        <input type="text" name="no_surat_sp1" class="form-control" placeholder="Contoh: 016/SP1/BK/SMART/IX/2026">
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label required">Tanggal Pemanggilan SP 1</label>
                            <input type="date" name="tanggal_panggil" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label required">Waktu Pertemuan</label>
                            <input type="time" name="waktu_pertemuan" class="form-control" value="09:00" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label required">Lokasi Pertemuan</label>
                        <input type="text" name="lokasi_pertemuan" class="form-control" value="Ruang Bimbingan Konseling (BK)" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Catatan Tambahan untuk SP 1</label>
                        <textarea name="catatan_tambahan" class="form-control" rows="2" placeholder="Siswa tetap membolos setelah surat pemberitahuan diterbitkan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeEscalateModal()">Batal</button>
                    <button type="submit" class="btn btn-danger"><i class="fa-solid fa-paper-plane"></i> Terbitkan SP 1</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- MODAL PREVIEW PDF --}}
<div class="modal-backdrop" id="modal-preview-pdf" style="display:none;">
    <div class="modal-dialog modal-xl" style="max-width:900px;">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><i class="fa-solid fa-file-pdf" style="color:#ef4444;"></i> Preview Surat Pemberitahuan</h3>
                <button type="button" class="modal-close" onclick="closePreviewPdf()">&times;</button>
            </div>
            <div class="modal-body p-0" style="height:600px;">
                <iframe id="iframe-pdf-preview" style="width:100%; height:100%; border:none;"></iframe>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closePreviewPdf()">Tutup Preview</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById('form-surat').reset();
        document.getElementById('form-method-surat').value = 'POST';
        document.getElementById('form-surat').action = '{{ route("bk.surat-pemberitahuan.store") }}';
        document.getElementById('modal-title-surat').innerHTML = '<i class="fa-solid fa-file-circle-exclamation" style="color:var(--color-primary);"></i> Buat Surat Pemberitahuan Ortu';
        document.getElementById('group-status-edit').style.display = 'none';
        document.getElementById('surat-info-siswa').value = '';
        document.getElementById('surat-info-walikelas').value = '';
        document.getElementById('modal-surat').style.display = 'flex';
    }

    function closeSuratModal() {
        document.getElementById('modal-surat').style.display = 'none';
    }

    function editSurat(item) {
        openAddModal();
        document.getElementById('form-method-surat').value = 'PUT';
        document.getElementById('form-surat').action = '{{ url("bk/surat-pemberitahuan") }}/' + item.id_pemberitahuan;
        document.getElementById('modal-title-surat').innerHTML = '<i class="fa-solid fa-pen" style="color:var(--color-primary);"></i> Edit Surat Pemberitahuan';
        document.getElementById('group-status-edit').style.display = 'block';

        document.getElementById('surat-no_surat').value = item.no_surat || '';
        document.getElementById('surat-tanggal_surat').value = item.tanggal_surat ? item.tanggal_surat.substring(0,10) : '';
        document.getElementById('surat-nis').value = item.nis || '';
        document.getElementById('surat-nama_ortu').value = item.nama_ortu || '';
        document.getElementById('surat-no_hp_ortu').value = item.no_hp_ortu || '';
        document.getElementById('surat-alasan_pemberitahuan').value = item.alasan_pemberitahuan || '';
        document.getElementById('surat-tindakan_sekolah').value = item.tindakan_sekolah || '';
        document.getElementById('surat-status').value = item.status || 'diterbitkan';

        checkSiswaDetail();
    }

    function addQuickReason(text) {
        const textarea = document.getElementById('surat-alasan_pemberitahuan');
        if (textarea.value) {
            textarea.value += ' ' + text;
        } else {
            textarea.value = text;
        }
    }

    function checkSiswaDetail() {
        const nis = document.getElementById('surat-nis').value.trim();
        if (!nis) return;

        fetch(`{{ route("bk.surat-pemberitahuan.siswa-detail") }}?nis=${nis}`)
            .then(res => res.json())
            .then(data => {
                if (data.nama_siswa) {
                    document.getElementById('surat-info-siswa').value = `${data.nama_siswa} (${data.nama_kelas})`;
                    document.getElementById('surat-info-walikelas').value = `${data.nama_wali_kelas} (NIP: ${data.nip_wali_kelas})`;
                    if (!document.getElementById('surat-nama_ortu').value) {
                        document.getElementById('surat-nama_ortu').value = data.nama_ortu || '';
                    }
                    if (!document.getElementById('surat-no_hp_ortu').value) {
                        document.getElementById('surat-no_hp_ortu').value = data.no_hp_ortu || '';
                    }
                } else {
                    document.getElementById('surat-info-siswa').value = 'Siswa tidak ditemukan';
                    document.getElementById('surat-info-walikelas').value = '-';
                }
            })
            .catch(() => {
                document.getElementById('surat-info-siswa').value = 'Gagal mengambil data siswa';
                document.getElementById('surat-info-walikelas').value = '-';
            });
    }

    function showDetailSurat(item) {
        const modalBody = document.getElementById('detail-surat-body');
        const namaSiswa = item.siswa ? item.siswa.nama_siswa : '-';
        const namaKelas = item.siswa && item.siswa.kelas ? item.siswa.kelas.nama_kelas : '-';
        const namaWaliKelas = item.siswa && item.siswa.kelas && item.siswa.kelas.guru ? item.siswa.kelas.guru.nama_guru : '-';
        const nipWaliKelas = item.siswa && item.siswa.kelas && item.siswa.kelas.guru ? (item.siswa.kelas.guru.no_id || '-') : '-';
        const guruBk = item.guru ? item.guru.nama_guru : '-';

        let statusBadge = '';
        if (item.status === 'lanjut_sp1') {
            statusBadge = '<span class="badge badge-danger">Dilanjutkan ke SP 1</span>';
        } else if (item.status === 'disampaikan') {
            statusBadge = '<span class="badge badge-info">Disampaikan ke Ortu</span>';
        } else if (item.status === 'selesai') {
            statusBadge = '<span class="badge badge-success">Selesai</span>';
        } else {
            statusBadge = '<span class="badge badge-warning">Diterbitkan</span>';
        }

        modalBody.innerHTML = `
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1 text-muted" style="font-size:0.8rem;">No. Surat & Tanggal</p>
                    <p style="font-weight:700;">${item.no_surat || '-'} <span style="font-size:0.85rem; font-weight:normal;">(${item.tanggal_surat})</span></p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted" style="font-size:0.8rem;">Status Surat</p>
                    <div>${statusBadge}</div>
                </div>
            </div>
            <hr class="my-3">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1 text-muted" style="font-size:0.8rem;">Data Siswa</p>
                    <p style="font-weight:700; margin-bottom:2px;">${namaSiswa} (${item.nis})</p>
                    <p style="font-size:0.85rem; color:var(--text-muted);">Kelas: ${namaKelas}</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted" style="font-size:0.8rem;">Wali Kelas</p>
                    <p style="font-weight:600; margin-bottom:2px;">${namaWaliKelas}</p>
                    <p style="font-size:0.85rem; color:var(--text-muted);">NIP: ${nipWaliKelas}</p>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="mb-1 text-muted" style="font-size:0.8rem;">Orang Tua / Wali</p>
                    <p style="font-weight:600;">${item.nama_ortu || '-'} (${item.no_hp_ortu || '-'})</p>
                </div>
                <div class="col-md-6">
                    <p class="mb-1 text-muted" style="font-size:0.8rem;">Guru BK Pembuat</p>
                    <p style="font-weight:600;">${guruBk}</p>
                </div>
            </div>
            <div class="form-group mb-3">
                <label class="form-label" style="font-weight:700;">Alasan Pemberitahuan (Pra-SP 1)</label>
                <div class="p-3" style="background:#f8fafc; border-left:3px solid var(--color-primary); border-radius:4px; font-style:italic;">
                    "${item.alasan_pemberitahuan || '-'}"
                </div>
            </div>
            ${item.tindakan_sekolah ? `
            <div class="form-group mb-3">
                <label class="form-label" style="font-weight:700;">Imbauan / Tindakan Sekolah</label>
                <div class="p-3" style="background:#f1f5f9; border-radius:4px;">
                    ${item.tindakan_sekolah}
                </div>
            </div>
            ` : ''}
        `;
        document.getElementById('modal-detail-surat').style.display = 'flex';
    }

    function closeDetailModal() {
        document.getElementById('modal-detail-surat').style.display = 'none';
    }

    function openEscalateModal(item) {
        document.getElementById('form-escalate').action = '{{ url("bk/surat-pemberitahuan") }}/' + item.id_pemberitahuan + '/escalate-sp1';
        document.getElementById('modal-escalate').style.display = 'flex';
    }

    function closeEscalateModal() {
        document.getElementById('modal-escalate').style.display = 'none';
    }

    function previewPdfForm() {
        const formData = new FormData(document.getElementById('form-surat'));
        
        fetch('{{ route("bk.surat-pemberitahuan.preview") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(res => res.text())
        .then(html => {
            const iframe = document.getElementById('iframe-pdf-preview');
            iframe.contentWindow.document.open();
            iframe.contentWindow.document.write(html);
            iframe.contentWindow.document.close();
            document.getElementById('modal-preview-pdf').style.display = 'flex';
        })
        .catch(err => {
            alert('Gagal membuat preview surat. Pastikan NIS dan alasan terisi.');
        });
    }

    function closePreviewPdf() {
        document.getElementById('modal-preview-pdf').style.display = 'none';
    }
</script>
@endpush
@endsection
