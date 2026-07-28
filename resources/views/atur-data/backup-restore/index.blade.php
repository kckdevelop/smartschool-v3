@extends('layouts.app')

@section('title', 'Backup & Restore Data & Media — SmartSchool')
@section('header_title', 'Backup & Restore Data & Media')
@section('header_subtitle', 'Kelola ekspor, impor, dan pemulihan database SQL serta file gambar & folder media')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="db-backup-layout" style="display: flex; flex-direction: column; gap: 24px;">

        {{-- ========================================================================= --}}
        {{-- SECTION 1: DATABASE BACKUP & RESTORE (.SQL) --}}
        {{-- ========================================================================= --}}
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-database" style="color: #3b82f6;"></i>
                <span>1. Backup &amp; Restore Database (SQL)</span>
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 24px;">

                <!-- CARD 1: EKSPOR SQL -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title" style="display: flex; align-items: center; gap: 10px; font-size: 1rem;">
                            <i class="fa-solid fa-file-export" style="color: #3b82f6;"></i>
                            <span>Ekspor Database (Backup SQL)</span>
                        </h2>
                    </div>
                    <div class="card-body" style="display: flex; flex-direction: column; gap: 16px;">
                        <p style="color: #64748b; font-size: 0.9rem; line-height: 1.6; margin: 0;">
                            Unduh atau simpan snapshot lengkap struktur dan data database SmartSchool V3 dalam format file SQL standar.
                        </p>

                        <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 8px;">
                            <a href="{{ route('atur-data.backup-restore.export') }}" class="btn btn-primary" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                                <i class="fa-solid fa-file-arrow-down"></i>
                                <span>Unduh SQL Direct</span>
                            </a>

                            <form action="{{ route('atur-data.backup-restore.store') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    <span>Simpan SQL ke Server</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: UPLOAD & RESTORE SQL -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title" style="display: flex; align-items: center; gap: 10px; font-size: 1rem;">
                            <i class="fa-solid fa-file-import" style="color: #ef4444;"></i>
                            <span>Upload &amp; Restore Database</span>
                        </h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('atur-data.backup-restore.upload-restore') }}" method="POST" enctype="multipart/form-data" id="form-upload-restore" onsubmit="return confirmRestoreUpload(event)">
                            @csrf
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label class="form-label" style="font-weight: 600;">Pilih File SQL (.sql) <span style="color: #ef4444;">*</span></label>
                                
                                <div class="drop-zone" id="drop-zone-sql" style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 20px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s ease;">
                                    <i class="fa-solid fa-cloud-arrow-up" style="font-size: 2rem; color: #94a3b8; margin-bottom: 6px;"></i>
                                    <p style="margin: 0 0 4px 0; font-weight: 500; color: #334155; font-size: 0.9rem;">Klik atau seret file `.sql` ke area ini</p>
                                    <small style="color: #64748b; font-size: 0.8rem;">Format: .sql | Maks: 100MB</small>
                                    <input type="file" name="sql_file" id="sql_file" accept=".sql" style="display: none;" required onchange="showSelectedFileName(this, 'file-name-sql')">
                                </div>
                                <div id="file-name-sql" style="margin-top: 8px; font-weight: 600; color: #0284c7; font-size: 0.88rem; display: none;"></div>
                            </div>

                            <button type="submit" class="btn btn-danger" style="display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>Upload &amp; Restore DB</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>


        {{-- ========================================================================= --}}
        {{-- SECTION 2: MEDIA & GAMBAR BACKUP & RESTORE (.ZIP) --}}
        {{-- ========================================================================= --}}
        <div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 12px; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-folder-tree" style="color: #10b981;"></i>
                <span>2. Backup &amp; Restore File Gambar &amp; Folder Media (ZIP)</span>
            </h3>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 24px;">

                <!-- CARD 1: EKSPOR MEDIA ZIP -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title" style="display: flex; align-items: center; gap: 10px; font-size: 1rem;">
                            <i class="fa-solid fa-file-zipper" style="color: #10b981;"></i>
                            <span>Ekspor File Gambar &amp; Folder Media (ZIP)</span>
                        </h2>
                    </div>
                    <div class="card-body" style="display: flex; flex-direction: column; gap: 16px;">
                        <p style="color: #64748b; font-size: 0.9rem; line-height: 1.6; margin: 0;">
                            Unduh atau simpan seluruh file gambar beserta struktur foldernya (foto siswa, guru, jurnal, home visit, logo sekolah, dll) dalam bentuk arsip ZIP.
                        </p>

                        <div style="display: flex; flex-wrap: wrap; gap: 12px; margin-top: 8px;">
                            <a href="{{ route('atur-data.backup-restore.export-media') }}" class="btn btn-success" style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none;">
                                <i class="fa-solid fa-file-zipper"></i>
                                <span>Unduh ZIP Media Direct</span>
                            </a>

                            <form action="{{ route('atur-data.backup-restore.store-media') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    <span>Simpan ZIP Media ke Server</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- CARD 2: UPLOAD & RESTORE MEDIA ZIP -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title" style="display: flex; align-items: center; gap: 10px; font-size: 1rem;">
                            <i class="fa-solid fa-folder-plus" style="color: #f59e0b;"></i>
                            <span>Upload &amp; Restore File Gambar &amp; Media</span>
                        </h2>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('atur-data.backup-restore.upload-restore-media') }}" method="POST" enctype="multipart/form-data" id="form-upload-media" onsubmit="return confirmRestoreMediaUpload(event)">
                            @csrf
                            <div class="form-group" style="margin-bottom: 16px;">
                                <label class="form-label" style="font-weight: 600;">Pilih File ZIP Media (.zip) <span style="color: #ef4444;">*</span></label>
                                
                                <div class="drop-zone" id="drop-zone-media" style="border: 2px dashed #cbd5e1; border-radius: 8px; padding: 20px; text-align: center; background: #f8fafc; cursor: pointer; transition: all 0.2s ease;">
                                    <i class="fa-solid fa-file-zipper" style="font-size: 2rem; color: #10b981; margin-bottom: 6px;"></i>
                                    <p style="margin: 0 0 4px 0; font-weight: 500; color: #334155; font-size: 0.9rem;">Klik atau seret file `.zip` media ke area ini</p>
                                    <small style="color: #64748b; font-size: 0.8rem;">Format: .zip | Maks: 500MB</small>
                                    <input type="file" name="media_file" id="media_file" accept=".zip" style="display: none;" required onchange="showSelectedFileName(this, 'file-name-media')">
                                </div>
                                <div id="file-name-media" style="margin-top: 8px; font-weight: 600; color: #10b981; font-size: 0.88rem; display: none;"></div>
                            </div>

                            <button type="submit" class="btn btn-warning" style="display: inline-flex; align-items: center; gap: 8px; color: #78350f;">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>Upload &amp; Restore Gambar Media</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>


        {{-- ========================================================================= --}}
        {{-- SECTION 3: TABEL RIWAYAT FILE BACKUP DI SERVER --}}
        {{-- ========================================================================= --}}
        <div class="card">
            <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
                <h2 class="card-title" style="display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-clock-rotate-left" style="color: #6366f1;"></i>
                    <span>Riwayat File Backup di Server</span>
                </h2>
                <span class="badge" style="background: #f1f5f9; color: #475569; padding: 6px 12px; border-radius: 20px; font-weight: 600; font-size: 0.85rem;">
                    Total: {{ count($backups) }} File
                </span>
            </div>
            <div class="card-body" style="padding: 0;">
                @if(count($backups) > 0)
                <div class="table-responsive">
                    <table class="table" style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #f8fafc; border-bottom: 1px solid #e2e8f0; text-align: left;">
                                <th style="padding: 14px 18px; font-weight: 600; color: #475569; font-size: 0.88rem;">#</th>
                                <th style="padding: 14px 18px; font-weight: 600; color: #475569; font-size: 0.88rem;">Nama File Backup</th>
                                <th style="padding: 14px 18px; font-weight: 600; color: #475569; font-size: 0.88rem; text-align: center;">Kategori</th>
                                <th style="padding: 14px 18px; font-weight: 600; color: #475569; font-size: 0.88rem;">Ukuran File</th>
                                <th style="padding: 14px 18px; font-weight: 600; color: #475569; font-size: 0.88rem;">Waktu Pembuatan</th>
                                <th style="padding: 14px 18px; font-weight: 600; color: #475569; font-size: 0.88rem; text-align: right;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($backups as $index => $item)
                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 14px 18px; font-size: 0.9rem; color: #64748b;">{{ $index + 1 }}</td>
                                <td style="padding: 14px 18px; font-size: 0.9rem; font-weight: 600; color: #1e293b;">
                                    @if(($item['type'] ?? '') === 'media')
                                        <i class="fa-solid fa-file-zipper" style="color: #10b981; margin-right: 8px;"></i>
                                    @else
                                        <i class="fa-solid fa-file-code" style="color: #0284c7; margin-right: 8px;"></i>
                                    @endif
                                    {{ $item['filename'] }}
                                </td>
                                <td style="padding: 14px 18px; text-align: center;">
                                    @if(($item['type'] ?? '') === 'media')
                                        <span class="badge" style="background: #d1fae5; color: #065f46; padding: 4px 8px; border-radius: 4px; font-size: 0.78rem;">ZIP Media / Gambar</span>
                                    @else
                                        <span class="badge" style="background: #e0f2fe; color: #075985; padding: 4px 8px; border-radius: 4px; font-size: 0.78rem;">Database SQL</span>
                                    @endif
                                </td>
                                <td style="padding: 14px 18px; font-size: 0.88rem; color: #475569;">
                                    <span style="background: #f1f5f9; padding: 4px 8px; border-radius: 4px; font-family: monospace;">{{ $item['size_formatted'] }}</span>
                                </td>
                                <td style="padding: 14px 18px; font-size: 0.88rem; color: #64748b;">
                                    {{ $item['created_at'] }}
                                </td>
                                <td style="padding: 14px 18px; text-align: right;">
                                    <div style="display: inline-flex; gap: 8px;">
                                        <!-- DOWNLOAD -->
                                        <a href="{{ route('atur-data.backup-restore.download-saved', $item['filename']) }}" 
                                           class="btn btn-light btn-sm" title="Unduh File Backup" style="color: #0284c7; border: 1px solid #e2e8f0;">
                                            <i class="fa-solid fa-download"></i> Unduh
                                        </a>

                                        <!-- RESTORE -->
                                        <form action="{{ route('atur-data.backup-restore.restore-saved', $item['filename']) }}" method="POST" style="margin: 0;" onsubmit="return confirmRestoreSaved('{{ $item['filename'] }}', event)">
                                            @csrf
                                            <button type="submit" class="btn btn-warning btn-sm" title="Restore Data dari File Ini">
                                                <i class="fa-solid fa-rotate-left"></i> Restore
                                            </button>
                                        </form>

                                        <!-- DELETE -->
                                        <form action="{{ route('atur-data.backup-restore.destroy', $item['filename']) }}" method="POST" style="margin: 0;" onsubmit="return confirm('Hapus file backup {{ $item['filename'] }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Hapus Backup">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div style="padding: 40px; text-align: center; color: #94a3b8;">
                    <i class="fa-solid fa-folder-open" style="font-size: 2.5rem; margin-bottom: 12px; color: #cbd5e1;"></i>
                    <p style="margin: 0; font-size: 0.95rem;">Belum ada file backup yang tersimpan di server.</p>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<script>
    function setupDropZone(dropZoneId, inputId, displayId) {
        const dropZone = document.getElementById(dropZoneId);
        const fileInput = document.getElementById(inputId);

        if (!dropZone || !fileInput) return;

        dropZone.addEventListener('click', () => fileInput.click());

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#0284c7';
            dropZone.style.background = '#f0f9ff';
        });

        dropZone.addEventListener('dragleave', () => {
            dropZone.style.borderColor = '#cbd5e1';
            dropZone.style.background = '#f8fafc';
        });

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#cbd5e1';
            dropZone.style.background = '#f8fafc';
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                showSelectedFileName(fileInput, displayId);
            }
        });
    }

    setupDropZone('drop-zone-sql', 'sql_file', 'file-name-sql');
    setupDropZone('drop-zone-media', 'media_file', 'file-name-media');

    function showSelectedFileName(input, displayId) {
        const nameDisplay = document.getElementById(displayId);
        if (input.files && input.files[0] && nameDisplay) {
            nameDisplay.innerHTML = '<i class="fa-solid fa-check-circle"></i> File Terpilih: ' + input.files[0].name + ' (' + (input.files[0].size / (1024 * 1024)).toFixed(2) + ' MB)';
            nameDisplay.style.display = 'block';
        }
    }

    function confirmRestoreUpload(e) {
        const input = document.getElementById('sql_file');
        if (!input.files || input.files.length === 0) {
            alert('Harap pilih file SQL terlebih dahulu!');
            e.preventDefault();
            return false;
        }
        return confirm('PERINGATAN KRUSIAL:\nApakah Anda yakin ingin melakukan RESTORE database?\n\nSeluruh data database saat ini akan DITIMPA dengan data dari file SQL ini!');
    }

    function confirmRestoreMediaUpload(e) {
        const input = document.getElementById('media_file');
        if (!input.files || input.files.length === 0) {
            alert('Harap pilih file ZIP media terlebih dahulu!');
            e.preventDefault();
            return false;
        }
        return confirm('KONFIRMASI RESTORE MEDIA:\nApakah Anda yakin ingin melakukan RESTORE file media & gambar?\n\nFile dan folder gambar yang ada akan diperbarui/ditimpa dari arsip ZIP ini.');
    }

    function confirmRestoreSaved(filename, e) {
        return confirm('PERINGATAN KRUSIAL:\nApakah Anda yakin ingin melakukan RESTORE dari file:\n' + filename + '?\n\nProses ini akan mengembalikan data/media!');
    }
</script>
@endsection
