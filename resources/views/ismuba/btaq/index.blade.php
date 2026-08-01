@extends('layouts.app')

@section('title', 'Pantauan BTAQ Siswa — SmartSchool')
@section('header_title', 'Pantauan BTAQ Siswa')
@section('header_subtitle', 'Monitoring Baca Tulis Al-Qur\'an per Siswa')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Stat Cards --}}
    <div class="ismuba-stats-row">
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#047857,#10b981);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-book-quran"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $totalHariIni }}</div>
                <div class="ismuba-stat-lbl">Sesi Hari Ini</div>
            </div>
        </div>
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#0369a1,#0ea5e9);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $totalBulanIni }}</div>
                <div class="ismuba-stat-lbl">Sesi Bulan Ini</div>
            </div>
        </div>
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#7c3aed,#a855f7);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-users"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $totalAll }}</div>
                <div class="ismuba-stat-lbl">Total Semua Sesi</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-book-open-reader"></i> Data Pantauan BTAQ</h2>
            <div class="card-header-right">
                <div class="btaq-header-controls">
                {{-- Filter Kelas --}}
                @if($kelasList->isNotEmpty())
                <form method="GET" class="search-form" id="form-kelas-select" style="gap:8px; margin:0;">
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <label class="form-label mb-0" style="white-space:nowrap; font-size:0.82rem;"><i class="fa-solid fa-chalkboard-user"></i> Kelas:</label>
                    <select name="id_kelas" class="form-control form-control-sm" id="select-kelas"
                            onchange="this.form.submit()" style="min-width:160px;">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id_kelas }}" {{ $selectedKelasId == $kelas->id_kelas ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </form>
                @endif

                {{-- Cari Siswa --}}
                <form method="GET" class="search-form" style="gap:6px; margin:0;">
                    <input type="hidden" name="id_kelas" value="{{ $selectedKelasId }}">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama/NIS..." class="form-control form-control-sm" style="width:160px;">
                    <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-magnifying-glass"></i></button>
                    @if(request('search'))
                        <a href="{{ route('ismuba.btaq.index', ['id_kelas' => $selectedKelasId]) }}" class="btn btn-secondary btn-sm" title="Reset">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </form>

                <button class="btn btn-primary btn-sm" onclick="openModal('modal-add-btaq')" id="btn-tambah-btaq">
                    <i class="fa-solid fa-plus"></i> Tambah Data
                </button>
            </div>{{-- /.btaq-header-controls --}}
            </div>{{-- /.card-header-right --}}
        </div>{{-- /.card-header --}}

        @php
            $todayStr    = \Carbon\Carbon::today()->format('Y-m-d');
            // Warna default untuk hari biasa (bergilir)
            $weekdayColors = [
                ['bg'=>'linear-gradient(135deg,#0d9488,#115e59)','text'=>'#fff','cellBg'=>'rgba(13,148,136,0.04)','accent'=>'#0d9488','badge'=>'#ccfbf1','badgeText'=>'#115e59'],
                ['bg'=>'linear-gradient(135deg,#2563eb,#1e40af)','text'=>'#fff','cellBg'=>'rgba(37,99,235,0.04)','accent'=>'#2563eb','badge'=>'#dbeafe','badgeText'=>'#1e40af'],
                ['bg'=>'linear-gradient(135deg,#7c3aed,#5b21b6)','text'=>'#fff','cellBg'=>'rgba(124,58,237,0.04)','accent'=>'#7c3aed','badge'=>'#f3e8ff','badgeText'=>'#5b21b6'],
                ['bg'=>'linear-gradient(135deg,#d97706,#92400e)','text'=>'#fff','cellBg'=>'rgba(217,119,6,0.04)','accent'=>'#d97706','badge'=>'#fef3c7','badgeText'=>'#92400e'],
                ['bg'=>'linear-gradient(135deg,#0369a1,#0ea5e9)','text'=>'#fff','cellBg'=>'rgba(3,105,161,0.04)','accent'=>'#0369a1','badge'=>'#e0f2fe','badgeText'=>'#0369a1'],
            ];
            // Warna khusus akhir pekan (Sabtu=6, Minggu=0)
            $weekendColor = ['bg'=>'linear-gradient(135deg,#dc2626,#991b1b)','text'=>'#fff','cellBg'=>'rgba(220,38,38,0.05)','accent'=>'#dc2626','badge'=>'#fee2e2','badgeText'=>'#991b1b'];
            $weekdayCounter = 0;
        @endphp
        <div class="card-body p-0" style="overflow-x: auto;">
            <table class="data-table btaq-grid-table">
                <thead>
                    <tr>
                        <th style="width:44px; text-align:center;">#</th>
                        <th style="min-width:200px; position:sticky; left:0; z-index:2; background:#f8fafc;">Siswa</th>
                        @foreach($calendarDates as $idx => $dateString)
                            @php
                                $carbonDate = \Carbon\Carbon::parse($dateString);
                                $dayOfWeek  = $carbonDate->dayOfWeek; // 0=Minggu, 6=Sabtu
                                $isWeekend  = ($dayOfWeek === 0 || $dayOfWeek === 6);
                                $isToday    = ($dateString === $todayStr);
                                if ($isWeekend) {
                                    $cs = $weekendColor;
                                } else {
                                    $cs = $weekdayColors[$weekdayCounter % count($weekdayColors)];
                                    $weekdayCounter++;
                                }
                            @endphp
                            <th class="date-header {{ $isWeekend ? 'weekend-col-header' : '' }} {{ $isToday ? 'today-col-header' : '' }}"
                                style="background:{{ $cs['bg'] }}; color:{{ $cs['text'] }}; min-width:150px;">
                                <div class="date-title">
                                    <i class="fa-solid fa-{{ $isToday ? 'calendar-day' : ($isWeekend ? 'moon' : 'calendar-check') }}"></i>
                                    {{ $carbonDate->translatedFormat('D') }}
                                </div>
                                <div class="date-sub">{{ $carbonDate->translatedFormat('d M') }}</div>
                                @if($isToday)
                                    <div class="date-today-badge">Hari Ini</div>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaList as $idxSiswa => $siswa)
                        @php $weekdayCounter2 = 0; @endphp
                        <tr class="btaq-student-row">
                            <td class="text-muted" style="font-size:0.78rem; text-align:center; vertical-align:middle; font-weight:600;">{{ $idxSiswa + 1 }}</td>
                            <td class="btaq-siswa-cell" style="position:sticky; left:0; z-index:1; background:#fff;">
                                <div class="btaq-siswa-name">{{ $siswa->nama_siswa }}</div>
                                <div class="btaq-siswa-nis">NIS: {{ $siswa->nis }}</div>
                            </td>
                            @foreach($calendarDates as $idxDate => $dateString)
                                @php
                                    $carbonDate2 = \Carbon\Carbon::parse($dateString);
                                    $dow2 = $carbonDate2->dayOfWeek;
                                    $isWE2 = ($dow2 === 0 || $dow2 === 6);
                                    if ($isWE2) {
                                        $cs = $weekendColor;
                                    } else {
                                        $cs = $weekdayColors[$weekdayCounter2 % count($weekdayColors)];
                                        $weekdayCounter2++;
                                    }
                                    $entry = ($btaqEntries[$siswa->nis] ?? collect())->get($dateString)?->first() ?? null;
                                @endphp
                                <td class="{{ $isWE2 ? 'weekend-col-cell' : '' }}"
                                    style="background:{{ $cs['cellBg'] }}; vertical-align:middle; padding:8px 10px;">
                                    @if($entry)
                                        <div class="btaq-card" style="border-left:3px solid {{ $cs['accent'] }};">
                                            <div class="btaq-card-header">
                                                <span class="badge-btaq-level" style="background:{{ $cs['badge'] }}; color:{{ $cs['badgeText'] }};">
                                                    {{ $entry->level }}
                                                </span>
                                            </div>
                                            <div class="btaq-card-body">
                                                @php
                                                    $displayRange = '';
                                                    $isIqro = (stripos($entry->level, 'Iqra') !== false || stripos($entry->level, 'Iqro') !== false);
                                                    if ($isIqro) {
                                                        if ($entry->iqroAwal) {
                                                            $barisText = !empty($entry->iqroAwal->baris) ? ", Baris {$entry->iqroAwal->baris}" : '';
                                                            $displayRange = "Jilid {$entry->iqroAwal->jilid}: Hal. {$entry->iqroAwal->halaman}{$barisText}";
                                                        } else {
                                                            $displayRange = $entry->awal;
                                                        }
                                                    } else {
                                                        if ($entry->alquranAwal) {
                                                            $displayRange = "QS. {$entry->alquranAwal->surat}: {$entry->alquranAwal->ayat}";
                                                        } else {
                                                            $displayRange = $entry->awal;
                                                        }
                                                    }
                                                @endphp
                                                <div class="btaq-range"><i class="fa-solid fa-book-open"></i> {{ $displayRange }}</div>
                                                <div class="btaq-guru" title="Guru: {{ $entry->guru?->nama_guru ?? '-' }}">
                                                    <i class="fa-solid fa-chalkboard-user"></i> {{ $entry->guru?->nama_guru ?? '-' }}
                                                </div>
                                            </div>
                                            <div class="btaq-card-actions">
                                                <button type="button" class="btn-action-mini btn-edit-mini" title="Edit"
                                                        onclick="editBtaq({{ json_encode($entry) }})">
                                                    <i class="fa-solid fa-pen"></i>
                                                </button>
                                                <button type="button" class="btn-action-mini btn-delete-mini" title="Hapus"
                                                        onclick="confirmDelete('{{ route('ismuba.btaq.destroy', $entry->id_btaq) }}','Yakin hapus data BTAQ ini?')">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="btaq-empty-cell">
                                            @if(!$isWE2)
                                                <button type="button" class="btn-quick-add"
                                                        title="Tambah data untuk {{ $siswa->nama_siswa }} — {{ $carbonDate2->translatedFormat('d M Y') }}"
                                                        onclick="quickAddBtaq({{ $siswa->nis }}, '{{ addslashes($siswa->nama_siswa) }}', {{ $selectedKelasId }}, '{{ $dateString }}')"
                                                        style="color:{{ $cs['accent'] }};">
                                                    <i class="fa-solid fa-plus-circle"></i>
                                                </button>
                                            @else
                                                <span class="weekend-empty-dash">—</span>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ count($calendarDates) + 2 }}" style="text-align:center; padding:48px 20px; color:var(--text-muted);">
                                <i class="fa-solid fa-users" style="font-size:2.5rem; opacity:0.25; display:block; margin-bottom:10px;"></i>
                                <div style="font-weight:600; font-size:0.9rem;">Tidak ada siswa aktif ditemukan di kelas ini</div>
                                @if(request('search'))
                                    <div style="font-size:0.8rem; margin-top:4px;">Coba ganti kata kunci pencarian</div>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ═══════ MODAL TAMBAH/EDIT BTAQ ═══════ --}}
<div class="modal-overlay" id="modal-add-btaq">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-title-btaq"><i class="fa-solid fa-book-quran" style="color:var(--color-primary);"></i> Tambah Data BTAQ</h3>
            <button onclick="closeModal('modal-add-btaq')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-btaq" method="POST" action="{{ route('ismuba.btaq.store') }}">
            @csrf
            <div id="btaq-method-field"></div>
            <div class="modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Kelas <span class="required">*</span></label>
                        <select name="id_kelas" id="btaq_id_kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Siswa <span class="required">*</span></label>
                        <select name="nis" id="btaq_nis" class="form-control" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswaDaftar as $s)
                                <option value="{{ $s->nis }}" data-nama="{{ $s->nama_siswa }}"
                                    data-kelas="{{ $s->kelas?->nama_kelas ?? '-' }}"
                                    data-kelas-id="{{ $s->id_kelas }}"
                                    data-nis="{{ $s->nis }}">
                                    {{ $s->nis }} — {{ $s->nama_siswa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal <span class="required">*</span></label>
                        <input type="date" name="tanggal" id="btaq_tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Level BTAQ <span class="required">*</span></label>
                        <select name="level" id="btaq_level" class="form-control" required>
                            <option value="">-- Pilih Level --</option>
                            <option value="Iqro">Iqro</option>
                            <option value="Al-Qur'an">Al-Qur'an</option>
                        </select>
                    </div>
                    {{-- Dynamic Iqro / Al-Qur'an inputs based on level category --}}
                    <div id="container-iqro-inputs" class="form-group" style="grid-column: 1/-1; display: none;">
                        {{-- Auto-jilid badge --}}
                        <div id="iqro-jilid-badge" style="display:none; margin-bottom:10px;" class="iqro-range-info">
                            <i class="fa-solid fa-bookmark"></i>
                            <span id="iqro-jilid-badge-text"></span>
                        </div>
                        <div class="form-grid-2" style="display:grid; grid-template-columns:repeat(2,1fr); gap:12px; margin:0; padding:0;">
                            <div class="form-group mb-0">
                                <label class="form-label">Halaman <span class="required">*</span>
                                    <small style="font-weight:400; color:var(--text-muted);">(1–55)</small>
                                </label>
                                <select name="halaman" id="btaq_halaman" class="form-control">
                                    <option value="">-- Pilih Halaman --</option>
                                    @for($h = 1; $h <= 55; $h++)
                                        <option value="{{ $h }}">Halaman {{ $h }}</option>
                                    @endfor
                                </select>
                                <div id="iqro-halaman-info" class="iqro-range-info" style="display:none;"></div>
                            </div>
                            <div class="form-group mb-0">
                                <label class="form-label">Baris <span class="required">*</span></label>
                                <select name="baris" id="btaq_baris" class="form-control">
                                    <option value="">-- Pilih Halaman Dulu --</option>
                                </select>
                                <div id="iqro-baris-info" class="iqro-range-info" style="display:none;"></div>
                            </div>
                        </div>
                    </div>

                    <div id="container-alquran-inputs" class="form-group" style="grid-column: 1/-1; display: none;">
                        <div class="form-grid-2" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; margin: 0; padding: 0;">
                            <div class="form-group mb-0">
                                <label class="form-label">Surat <span class="required">*</span></label>
                                <select name="surat" id="btaq_surat" class="form-control">
                                    <option value="">-- Pilih Surat --</option>
                                    @foreach($surahList as $s)
                                        <option value="{{ $s }}">{{ $s }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-0">
                                <label class="form-label">Ayat <span class="required">*</span></label>
                                <select name="ayat" id="btaq_ayat" class="form-control">
                                    <option value="">-- Pilih Ayat --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="grid-column: 1/-1;">
                        <label class="form-label">Guru Penguji <span class="required">*</span></label>
                        <select name="id_guru" id="btaq_id_guru" class="form-control" required>
                            <option value="">-- Pilih Guru--</option>
                            @foreach($guruIsmuba as $guru)
                                <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-add-btaq')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.ismuba-stats-row { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; margin-bottom:20px; }
.ismuba-stat-card { display:flex; align-items:center; gap:16px; padding:20px; border-radius:var(--radius-card); color:#fff; box-shadow:0 6px 24px rgba(0,0,0,0.12); transition:var(--transition-smooth); }
.ismuba-stat-card:hover { transform:translateY(-3px); box-shadow:0 12px 36px rgba(0,0,0,0.18); }
.ismuba-stat-icon { width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0; }
.ismuba-stat-num { font-size:2rem;font-weight:800;line-height:1; }
.ismuba-stat-lbl { font-size:0.78rem;opacity:.85;margin-top:2px; }

/* Header controls (kelas select + search) */
.btaq-header-controls {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-wrap: wrap;
}
.btaq-header-controls .search-form {
    display: flex;
    align-items: center;
}

/* BTAQ Card inside grid cell */
.btaq-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 10px;
    position: relative;
    box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    transition: all 0.2s ease-in-out;
}
.btaq-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(0,0,0,0.08);
}
.btaq-card-header {
    margin-bottom: 6px;
}
.badge-btaq-level {
    display: inline-block;
    padding: 2px 6px;
    font-size: 0.72rem;
    font-weight: 700;
    border-radius: 4px;
}
.btaq-card-body {
    font-size: 0.78rem;
    color: var(--text-secondary);
    line-height: 1.4;
}
.btaq-range {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.btaq-range i, .btaq-guru i {
    font-size: 0.75rem;
    margin-right: 4px;
    opacity: 0.7;
}
.btaq-guru {
    font-size: 0.72rem;
    color: var(--text-muted);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Card hover actions */
.btaq-card-actions {
    position: absolute;
    top: 4px;
    right: 4px;
    display: flex;
    gap: 3px;
    opacity: 0;
    transition: opacity 0.2s ease;
    background: rgba(255, 255, 255, 0.95);
    padding: 2px;
    border-radius: 4px;
}
.btaq-card:hover .btaq-card-actions {
    opacity: 1;
}
.btn-action-mini {
    width: 20px;
    height: 20px;
    border-radius: 4px;
    border: none;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.68rem;
    cursor: pointer;
    transition: background 0.15s ease;
}
.btn-edit-mini {
    background: #f1f5f9;
    color: #475569;
}
.btn-edit-mini:hover {
    background: #e2e8f0;
    color: #0f172a;
}
.btn-delete-mini {
    background: #fee2e2;
    color: #ef4444;
}
.btn-delete-mini:hover {
    background: #fecaca;
    color: #b91c1c;
}

/* Empty cell quick add button */
.btaq-empty-cell {
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 48px;
}
.btn-quick-add {
    background: none;
    border: none;
    color: #cbd5e1;
    font-size: 1.25rem;
    cursor: pointer;
    transition: all 0.2s ease;
    padding: 4px;
}
.btn-quick-add:hover {
    color: var(--color-primary);
    transform: scale(1.15);
}

/* Date Header styling */
.date-header {
    padding: 8px 10px !important;
    text-align: center;
    font-size: 0.78rem;
    font-weight: 700;
    vertical-align: middle;
}
.date-title {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    white-space: nowrap;
    font-size: 0.82rem;
    font-weight: 700;
}
.date-sub {
    font-size: 0.75rem;
    opacity: 0.9;
    margin-top: 2px;
    font-weight: 600;
}
.date-today-badge {
    display: inline-block;
    font-size: 0.6rem;
    font-weight: 700;
    background: rgba(255,255,255,0.25);
    border: 1px solid rgba(255,255,255,0.5);
    border-radius: 20px;
    padding: 1px 6px;
    margin-top: 3px;
    letter-spacing: 0.03em;
    text-transform: uppercase;
}
/* Akhir pekan */
.weekend-col-header {
    position: relative;
}
.weekend-col-cell {
    position: relative;
}
.today-col-header {
    outline: 3px solid rgba(255,255,255,0.55);
    outline-offset: -3px;
}
.weekend-empty-dash {
    font-size: 0.9rem;
    color: rgba(220,38,38,0.25);
    font-weight: 600;
    letter-spacing: 2px;
}

/* Sticky student name column */
.btaq-siswa-cell {
    padding: 10px 14px !important;
    border-right: 1.5px solid #e2e8f0;
    box-shadow: 2px 0 6px rgba(0,0,0,0.04);
}
.btaq-siswa-name {
    font-weight: 700;
    font-size: 0.88rem;
    color: var(--text-primary);
    line-height: 1.3;
}
.btaq-siswa-nis {
    font-size: 0.73rem;
    color: var(--text-muted);
    margin-top: 2px;
    font-weight: 500;
}
.btaq-student-row:hover .btaq-siswa-cell {
    background: #f0fdfa !important;
}
.btaq-student-row:hover td {
    background-color: rgba(13,148,136,0.025) !important;
}
.btaq-grid-table tbody tr:nth-child(even) .btaq-siswa-cell {
    background: #fafcff !important;
}

@media(max-width:640px) { .ismuba-stats-row { grid-template-columns:1fr; } }

/* Iqro range info badge */
.iqro-range-info {
    margin-top: 5px;
    padding: 5px 10px;
    background: linear-gradient(135deg, #ecfdf5, #d1fae5);
    border: 1px solid #6ee7b7;
    border-radius: 6px;
    font-size: 0.75rem;
    color: #065f46;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 5px;
}
.iqro-range-info i {
    color: #059669;
    font-size: 0.8rem;
}
.iqro-range-error {
    background: linear-gradient(135deg, #fef2f2, #fee2e2);
    border-color: #fca5a5;
    color: #991b1b;
}
.iqro-range-error i {
    color: #dc2626;
}
</style>
@endpush

@push('scripts')
<script>
const btaqToday = '{{ now()->format("Y-m-d") }}';
const surahAyatCounts = @json($surahAyatCounts);
const latestBtaqMap = @json($latestBtaqMap);
const surahOrder = @json($surahList->toArray());

// ─── Data Iqro: range jilid & baris per halaman (dari server) ──────────────────
const jilidRanges      = @json($jilidRanges);        // { 1:{min:1,max:16}, ... }
const iqroBarisByHalaman = @json($iqroBarisByHalaman); // { 1:[1,2,...,15], 2:[...], ... }
const maxHalamanIqro   = {{ $maxHalamanIqro }};      // 55

function getJilidFromHalaman(halaman) {
    for (const [jilid, range] of Object.entries(jilidRanges)) {
        if (halaman >= range.min && halaman <= range.max) return parseInt(jilid);
    }
    return 6;
}

let currentStudentLastProgress = null;

// Store all student options for filtering
const originalSiswaOptions = [];
document.querySelectorAll('#btaq_nis option').forEach(opt => {
    if (opt.value) {
        originalSiswaOptions.push({
            value: opt.value,
            text: opt.textContent.trim(),
            kelasId: opt.getAttribute('data-kelas-id'),
            nama: opt.getAttribute('data-nama'),
            kelas: opt.getAttribute('data-kelas'),
            nis: opt.getAttribute('data-nis')
        });
    }
});

function updateSiswaDropdown(kelasId, selectedNis = '') {
    const siswaSelect = document.getElementById('btaq_nis');
    
    // Clear all options
    siswaSelect.innerHTML = '';
    
    // Create default option
    const defaultOpt = document.createElement('option');
    defaultOpt.value = '';
    
    if (!kelasId) {
        defaultOpt.textContent = '-- Pilih Kelas Terlebih Dahulu --';
        siswaSelect.appendChild(defaultOpt);
        siswaSelect.disabled = true;
        return;
    }
    
    defaultOpt.textContent = '-- Pilih Siswa --';
    siswaSelect.appendChild(defaultOpt);
    siswaSelect.disabled = false;
    
    // Filter and append student options
    originalSiswaOptions.forEach(s => {
        if (s.kelasId == kelasId) {
            const opt = document.createElement('option');
            opt.value = s.value;
            opt.textContent = s.text;
            opt.setAttribute('data-nama', s.nama);
            opt.setAttribute('data-kelas', s.kelas);
            opt.setAttribute('data-kelas-id', s.kelasId);
            opt.setAttribute('data-nis', s.nis);
            if (s.value == selectedNis) {
                opt.selected = true;
            }
            siswaSelect.appendChild(opt);
        }
    });
}

document.getElementById('btaq_id_kelas').addEventListener('change', function() {
    updateSiswaDropdown(this.value);
    currentStudentLastProgress = null;
    resetRestrictions();
    applyProgressRestrictions();
});

document.getElementById('btn-tambah-btaq').addEventListener('click', function() {
    resetBtaqModal();
    openModal('modal-add-btaq');
});

// Dynamic field showing and validations
function handleLevelChange() {
    const levelVal = document.getElementById('btaq_level').value;
    const iqroContainer    = document.getElementById('container-iqro-inputs');
    const alquranContainer = document.getElementById('container-alquran-inputs');

    const isIqro   = levelVal.toLowerCase().includes('iqra') || levelVal.toLowerCase().includes('iqro');
    const isAlquran = levelVal === "Al-Qur'an" || levelVal === "Hafalan";

    const iqroSelects    = iqroContainer.querySelectorAll('select');
    const alquranSelects = alquranContainer.querySelectorAll('select');

    if (isIqro) {
        iqroContainer.style.display = 'block';
        alquranContainer.style.display = 'none';
        iqroSelects.forEach(s => s.setAttribute('required', 'required'));
        alquranSelects.forEach(s => s.removeAttribute('required'));
    } else if (isAlquran) {
        iqroContainer.style.display = 'none';
        alquranContainer.style.display = 'block';
        iqroSelects.forEach(s => s.removeAttribute('required'));
        alquranSelects.forEach(s => s.setAttribute('required', 'required'));
    } else {
        iqroContainer.style.display = 'none';
        alquranContainer.style.display = 'none';
        iqroSelects.forEach(s => s.removeAttribute('required'));
        alquranSelects.forEach(s => s.removeAttribute('required'));
    }

    applyProgressRestrictions();
}

document.getElementById('btaq_level').addEventListener('change', handleLevelChange);

// Populate ayat selects based on selected surah
function populateAyatSelect(suratSelectId, ayatSelectId) {
    const suratVal = document.getElementById(suratSelectId).value;
    const ayatSelect = document.getElementById(ayatSelectId);
    
    // Clear previous options
    ayatSelect.innerHTML = '<option value="">-- Pilih Ayat --</option>';
    
    if (suratVal && surahAyatCounts[suratVal]) {
        const totalAyat = surahAyatCounts[suratVal];
        for (let i = 1; i <= totalAyat; i++) {
            const opt = document.createElement('option');
            opt.value = i;
            opt.textContent = i;
            ayatSelect.appendChild(opt);
        }
    }
}

document.getElementById('btaq_surat').addEventListener('change', function() {
    populateAyatSelect('btaq_surat', 'btaq_ayat');
    applyProgressRestrictions();
});

document.getElementById('btaq_halaman').addEventListener('change', function() {
    populateBarisDropdown();
    updateJilidBadge();
    applyProgressRestrictions();
});

document.getElementById('btaq_baris').addEventListener('change', function() {
    applyProgressRestrictions();
});

// ─── Update badge jilid otomatis ─────────────────────────────────────────────
function updateJilidBadge() {
    const halamanVal = parseInt(document.getElementById('btaq_halaman').value);
    const badge      = document.getElementById('iqro-jilid-badge');
    const badgeText  = document.getElementById('iqro-jilid-badge-text');
    const halamanInfo = document.getElementById('iqro-halaman-info');

    if (!halamanVal) {
        badge.style.display = 'none';
        halamanInfo.style.display = 'none';
        return;
    }

    const jilid = getJilidFromHalaman(halamanVal);
    const range = jilidRanges[jilid];
    badge.style.display = 'flex';
    badgeText.textContent = `Jilid ${jilid}  •  Halaman ${range.min}–${range.max}`;

    halamanInfo.style.display = 'flex';
    halamanInfo.className = 'iqro-range-info';
    halamanInfo.innerHTML = `<i class="fa-solid fa-circle-info"></i> Jilid ${jilid}: Halaman ${range.min}–${range.max}`;
}

// ─── Isi dropdown baris berdasarkan halaman yang dipilih ─────────────────────
function populateBarisDropdown(preserveValue = false) {
    const halamanVal = parseInt(document.getElementById('btaq_halaman').value);
    const barisSelect = document.getElementById('btaq_baris');
    const barisInfo   = document.getElementById('iqro-baris-info');
    const prevBaris   = preserveValue ? barisSelect.value : '';

    barisSelect.innerHTML = '';

    if (!halamanVal) {
        barisSelect.innerHTML = '<option value="">-- Pilih Halaman Dulu --</option>';
        barisInfo.style.display = 'none';
        return;
    }

    const barisList = iqroBarisByHalaman[halamanVal] || Array.from({length: 15}, (_, i) => i + 1);

    const defaultOpt = document.createElement('option');
    defaultOpt.value = '';
    defaultOpt.textContent = '-- Pilih Baris --';
    barisSelect.appendChild(defaultOpt);

    barisList.forEach(b => {
        const opt = document.createElement('option');
        opt.value = b;
        opt.textContent = `Baris ${b}`;
        if (String(b) === String(prevBaris)) opt.selected = true;
        barisSelect.appendChild(opt);
    });

    barisInfo.style.display = 'flex';
    barisInfo.className = 'iqro-range-info';
    barisInfo.innerHTML = `<i class="fa-solid fa-list"></i> ${barisList.length} baris tersedia`;
}

// Update restrictions when selected student changes
function updateStudentProgress() {
    const nis = document.getElementById('btaq_nis').value;
    currentStudentLastProgress = latestBtaqMap[nis] || null;
    
    resetRestrictions();
    applyProgressRestrictions();
}

document.getElementById('btaq_nis').addEventListener('change', updateStudentProgress);

function disableOption(opt, suffix = ' (Terlewati)') {
    opt.disabled = true;
    opt.style.color = '#94a3b8';
    opt.style.textDecoration = 'line-through';
    opt.style.backgroundColor = '#f8fafc';
    if (opt.value && !opt.textContent.endsWith(suffix)) {
        opt.textContent += suffix;
    }
}

function enableOption(opt, suffix = ' (Terlewati)') {
    opt.disabled = false;
    opt.style.color = '';
    opt.style.textDecoration = '';
    opt.style.backgroundColor = '';
    opt.textContent = opt.textContent.replace(suffix, '');
}

function resetRestrictions() {
    document.querySelectorAll('#btaq_halaman option').forEach(opt => enableOption(opt));
    document.querySelectorAll('#btaq_baris option').forEach(opt => enableOption(opt));
    document.querySelectorAll('#btaq_surat option').forEach(opt => enableOption(opt));
    document.querySelectorAll('#btaq_ayat option').forEach(opt => enableOption(opt));
}

function applyProgressRestrictions() {
    resetRestrictions();

    if (!currentStudentLastProgress) return;

    const levelVal  = document.getElementById('btaq_level').value;
    const isIqro    = levelVal.toLowerCase().includes('iqra') || levelVal.toLowerCase().includes('iqro');
    const isAlquran = levelVal === "Al-Qur'an" || levelVal === "Hafalan";

    if (isIqro && currentStudentLastProgress.is_iqro) {
        const lastHalaman = parseInt(currentStudentLastProgress.halaman);
        const lastBaris   = parseInt(currentStudentLastProgress.baris) || 0;

        // Disable halaman < lastHalaman
        document.querySelectorAll('#btaq_halaman option').forEach(opt => {
            if (opt.value) {
                const h = parseInt(opt.value);
                if (h < lastHalaman) disableOption(opt);
            }
        });

        // Jika halaman yang dipilih = lastHalaman, disable baris <= lastBaris
        const selectedHalaman = parseInt(document.getElementById('btaq_halaman').value);
        if (selectedHalaman === lastHalaman) {
            document.querySelectorAll('#btaq_baris option').forEach(opt => {
                if (opt.value) {
                    const b = parseInt(opt.value);
                    if (b <= lastBaris) disableOption(opt);
                }
            });
            const selectedBarisVal = document.getElementById('btaq_baris').value;
            const selectedBarisOpt = document.querySelector(`#btaq_baris option[value="${selectedBarisVal}"]`);
            if (selectedBarisOpt && selectedBarisOpt.disabled) document.getElementById('btaq_baris').value = '';
        }

        // Reset invalid halaman pilihan
        const selectedHalamanVal = document.getElementById('btaq_halaman').value;
        const selectedHalamanOpt = document.querySelector(`#btaq_halaman option[value="${selectedHalamanVal}"]`);
        if (selectedHalamanOpt && selectedHalamanOpt.disabled) {
            document.getElementById('btaq_halaman').value = '';
            document.getElementById('btaq_baris').innerHTML = '<option value="">-- Pilih Halaman Dulu --</option>';
        }
    } else if (isIqro && !currentStudentLastProgress.is_iqro) {
        // Siswa sudah Al-Qur'an, semua halaman Iqro dinonaktifkan
        document.querySelectorAll('#btaq_halaman option').forEach(opt => {
            if (opt.value) disableOption(opt, " (Siswa sudah Al-Qur'an)");
        });
        document.getElementById('btaq_halaman').value = '';
        document.getElementById('btaq_baris').innerHTML = '<option value="">-- Pilih Halaman Dulu --</option>';
    } else if (isAlquran && !currentStudentLastProgress.is_iqro) {
        const lastSurat      = currentStudentLastProgress.surat;
        const lastAyat       = parseInt(currentStudentLastProgress.ayat);
        const lastSuratIndex = surahOrder.indexOf(lastSurat);

        document.querySelectorAll('#btaq_surat option').forEach(opt => {
            if (opt.value) {
                const sIdx = surahOrder.indexOf(opt.value);
                if (sIdx < lastSuratIndex) disableOption(opt);
            }
        });

        const selectedSuratVal = document.getElementById('btaq_surat').value;
        const selectedSuratOpt = document.querySelector(`#btaq_surat option[value="${selectedSuratVal}"]`);
        if (selectedSuratOpt && selectedSuratOpt.disabled) {
            document.getElementById('btaq_surat').value = '';
            document.getElementById('btaq_ayat').innerHTML = '<option value="">-- Pilih Ayat --</option>';
        }

        const selectedSurat = document.getElementById('btaq_surat').value;
        if (selectedSurat === lastSurat) {
            document.querySelectorAll('#btaq_ayat option').forEach(opt => {
                if (opt.value) {
                    const a = parseInt(opt.value);
                    if (a <= lastAyat) disableOption(opt);
                }
            });
            const selectedAyatVal = document.getElementById('btaq_ayat').value;
            const selectedAyatOpt = document.querySelector(`#btaq_ayat option[value="${selectedAyatVal}"]`);
            if (selectedAyatOpt && selectedAyatOpt.disabled) document.getElementById('btaq_ayat').value = '';
        }
    }
}

function resetBtaqModal() {
    document.getElementById('form-btaq').action = '{{ route("ismuba.btaq.store") }}';
    document.getElementById('btaq-method-field').innerHTML = '';
    document.getElementById('modal-title-btaq').innerHTML = '<i class="fa-solid fa-book-quran" style="color:var(--color-primary);"></i> Tambah Data BTAQ';

    document.getElementById('btaq_id_kelas').value = '';
    updateSiswaDropdown('');

    document.getElementById('btaq_tanggal').value = btaqToday;
    document.getElementById('btaq_level').value   = '';
    document.getElementById('btaq_id_guru').value  = '';

    // Reset Iqro fields
    document.getElementById('btaq_halaman').value = '';
    document.getElementById('btaq_baris').innerHTML = '<option value="">-- Pilih Halaman Dulu --</option>';
    document.getElementById('iqro-jilid-badge').style.display = 'none';
    document.getElementById('iqro-halaman-info').style.display = 'none';
    document.getElementById('iqro-baris-info').style.display = 'none';

    // Reset Al-Qur'an fields
    document.getElementById('btaq_surat').value = '';
    document.getElementById('btaq_ayat').innerHTML = '<option value="">-- Pilih Ayat --</option>';

    currentStudentLastProgress = null;
    resetRestrictions();
    handleLevelChange();
}

function editBtaq(data) {
    document.getElementById('form-btaq').action = `/ismuba/btaq/${data.id_btaq}`;
    document.getElementById('btaq-method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-btaq').innerHTML = '<i class="fa-solid fa-pen" style="color:var(--color-primary);"></i> Edit Data BTAQ';
    
    document.getElementById('btaq_id_kelas').value = data.id_kelas;
    updateSiswaDropdown(data.id_kelas, data.nis);
    
    document.getElementById('btaq_tanggal').value = data.tanggal ? data.tanggal.substring(0,10) : '';
    document.getElementById('btaq_level').value = data.level;
    document.getElementById('btaq_id_guru').value = data.id_guru;

    // Load student's progress
    currentStudentLastProgress = latestBtaqMap[data.nis] || null;

    handleLevelChange();

    if (data.iqro_awal) {
        document.getElementById('btaq_halaman').value = data.iqro_awal.halaman;
        populateBarisDropdown(true);
        updateJilidBadge();
        document.getElementById('btaq_baris').value = data.iqro_awal.baris;
    }
    if (data.alquran_awal) {
        document.getElementById('btaq_surat').value = data.alquran_awal.surat;
        populateAyatSelect('btaq_surat', 'btaq_ayat');
        document.getElementById('btaq_ayat').value = data.alquran_awal.ayat;
    }
    
    // Apply restrictions
    applyProgressRestrictions();
    
    openModal('modal-add-btaq');
}

function quickAddBtaq(nis, nama, kelasId, tanggal) {
    resetBtaqModal();
    
    document.getElementById('btaq_id_kelas').value = kelasId;
    updateSiswaDropdown(kelasId, nis);
    
    document.getElementById('btaq_tanggal').value = tanggal;
    
    updateStudentProgress();
    openModal('modal-add-btaq');
}
</script>
@endpush
@endsection
