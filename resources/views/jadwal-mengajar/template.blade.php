@extends('layouts.app')

@section('title', 'Template Jadwal Guru — SmartSchool')
@section('header_title', 'Template Jadwal Guru')
@section('header_subtitle', 'Kelola template mengajar guru dengan grid interaktif 10 hari')

@section('content')
<style>
/* ─── Layout & Navigation ─── */
.nav-header-custom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}
.btn-back-custom {
    background-color: #f1f5f9;
    color: #475569;
    font-weight: 700;
    padding: 8px 16px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s ease;
    text-decoration: none;
    font-size: 0.82rem;
    border: 1px solid #cbd5e1;
}
.btn-back-custom:hover { background-color: #e2e8f0; color: #1e293b; }

/* ─── Teacher Selection Header ─── */
.teacher-selector-card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.02);
    margin-bottom: 24px;
    border: 1px solid #e2e8f0;
}
.selector-form-group {
    display: flex;
    align-items: center;
    gap: 16px;
    max-width: 500px;
}

/* ─── Grid Container & Layout ─── */
.grid-container-card {
    background: #fff;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.02);
    border: 1px solid #e2e8f0;
    min-height: 400px;
    position: relative;
}

/* ─── Scheduler Grid Table ─── */
.schedule-grid-wrapper {
    width: 100%;
    overflow-x: auto;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    margin-top: 15px;
}
.schedule-grid-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
    user-select: none; /* Disable text select during drag */
}
.schedule-grid-table th, 
.schedule-grid-table td {
    border: 1px solid #e2e8f0;
    padding: 0;
    text-align: center;
    vertical-align: middle;
}
.schedule-grid-table th {
    background-color: #f8fafc;
    color: #475569;
    font-weight: 700;
    font-size: 0.85rem;
    padding: 12px 6px;
}
.schedule-grid-table th.day-col {
    width: 80px;
    background-color: #f1f5f9;
}
.schedule-grid-table td.day-row-header {
    background-color: #f8fafc;
    color: #334155;
    font-weight: 800;
    font-size: 0.9rem;
    padding: 16px 8px;
    width: 80px;
    border-right: 2px solid #cbd5e1;
}

/* ─── Cells Styling ─── */
.grid-cell {
    height: 90px;
    position: relative;
    transition: all 0.15s ease;
    box-sizing: border-box;
}

/* -- Empty State Cell -- */
.grid-cell.empty-cell {
    background-color: #fff;
    cursor: pointer;
}
.grid-cell.empty-cell::after {
    content: '+';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 1.4rem;
    color: #cbd5e1;
    opacity: 0;
    transition: opacity 0.15s ease;
}
.grid-cell.empty-cell:hover {
    background-color: #f0fdfa;
}
.grid-cell.empty-cell:hover::after {
    opacity: 1;
    color: var(--color-primary, #0d9488);
}

/* -- Selected State Cell -- */
.grid-cell.empty-cell.selected-cell {
    background-color: #ccfbf1 !important;
    border: 2px dashed #0d9488 !important;
}

/* -- Filled State Cell -- */
.grid-cell.filled-cell {
    background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);
    color: #ffffff;
    padding: 8px;
    text-align: center;
    box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1);
    animation: fadeInCell 0.25s ease-out;
}
.grid-cell.filled-cell:hover {
    transform: scale(0.98);
    box-shadow: 0 4px 10px rgba(3,105,161,0.25);
}
.cell-mapel {
    font-weight: 800;
    font-size: 0.92rem;
    letter-spacing: 0.02em;
    display: block;
    line-height: 1.2;
}
.cell-kelas {
    font-size: 0.72rem;
    opacity: 0.9;
    margin: 4px 0;
    display: block;
    font-weight: 600;
    background: rgba(255,255,255,0.15);
    border-radius: 4px;
    padding: 1px 4px;
    display: inline-block;
}
.cell-ruang {
    font-size: 0.72rem;
    font-weight: 700;
    color: #e0f2fe;
    display: block;
    margin-top: 2px;
}

/* ─── Delete Cell Trigger ─── */
.btn-delete-cell {
    position: absolute;
    top: 4px;
    right: 4px;
    width: 20px;
    height: 20px;
    background: rgba(239, 68, 68, 0.9);
    color: #fff;
    border: none;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.7rem;
    cursor: pointer;
    opacity: 0;
    transition: opacity 0.15s ease, background 0.15s ease;
    z-index: 10;
}
.grid-cell.filled-cell:hover .btn-delete-cell {
    opacity: 1;
}
.btn-delete-cell:hover {
    background: #dc2626;
}

/* ─── Floating Action Bar ─── */
.floating-action-bar {
    position: fixed;
    bottom: 24px;
    left: 50%;
    transform: translateX(-50%) translateY(100px);
    background: rgba(15, 23, 42, 0.9);
    backdrop-filter: blur(8px);
    color: #fff;
    padding: 12px 24px;
    border-radius: 50px;
    box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);
    display: flex;
    align-items: center;
    gap: 16px;
    z-index: 999;
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    border: 1px solid rgba(255,255,255,0.1);
}
.floating-action-bar.active {
    transform: translateX(-50%) translateY(0);
}
.floating-bar-text {
    font-size: 0.88rem;
    font-weight: 600;
}
.floating-bar-text span {
    background: var(--color-primary, #0d9488);
    padding: 2px 8px;
    border-radius: 12px;
    margin-right: 4px;
}

/* ─── Empty State grid ─── */
.grid-empty-state {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 80px 20px;
    color: #64748b;
    text-align: center;
}
.grid-empty-state i {
    font-size: 3.5rem;
    color: #cbd5e1;
    margin-bottom: 16px;
}

/* ─── Animations ─── */
@keyframes fadeInCell {
    from { opacity: 0; transform: scale(0.92); }
    to { opacity: 1; transform: scale(1); }
}

/* ─── Responsive Adjustments ─── */
@media (max-width: 768px) {
    .floating-action-bar {
        width: 90vw;
        border-radius: 16px;
        flex-direction: column;
        gap: 10px;
        text-align: center;
    }
}
</style>

<div class="page-content">
    @include('partials.flash')

    <div class="nav-header-custom">
        <a href="{{ route('jadwal-mengajar.index') }}" class="btn-back-custom">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Jadwal Harian
        </a>
    </div>

    {{-- Teacher Selector Card --}}
    <div class="teacher-selector-card">
        <h3 class="card-title" style="margin-bottom:12px;"><i class="fa-solid fa-chalkboard-teacher"></i> Pilih Guru Terlebih Dahulu</h3>
        <div class="selector-form-group">
            <select id="select-guru-grid" class="form-control">
                <option value="">-- Pilih Guru Pengajar --</option>
                @foreach($guruList as $g)
                    <option value="{{ $g->id_guru }}">{{ $g->nama_guru }}</option>
                @endforeach
            </select>
            <div id="grid-loader" style="display:none; color: var(--color-primary);">
                <i class="fa-solid fa-circle-notch fa-spin"></i> Loading...
            </div>
        </div>
    </div>

    {{-- Grid Content Card --}}
    <div class="grid-container-card" id="grid-container-card">
        {{-- Empty State (Shown before teacher selected) --}}
        <div class="grid-empty-state" id="grid-initial-state">
            <i class="fa-solid fa-calendar-days"></i>
            <h3>Kelola Jadwal dengan Grid Interaktif</h3>
            <p>Silakan pilih data guru pengajar di atas untuk mulai memuat dan menyusun template jadwal mengajar.</p>
        </div>

        {{-- Main Grid Scheduler (Hidden by default) --}}
        <div id="grid-scheduler-section" style="display:none;">
            <div class="card-header" style="padding: 0 0 15px 0; border-bottom: 1px solid #e2e8f0; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                <div>
                    <h2 class="card-title" id="teacher-grid-title" style="font-size:1.3rem; font-weight:800; color:#1e293b;">Jadwal Mengajar Guru</h2>
                    <p style="font-size:0.8rem; color:#64748b; margin-top:2px;">
                        Klik & geser (drag) atau klik beberapa sel kosong untuk memblok jam mengajar, kemudian isi datanya.
                    </p>
                </div>
                <div style="display:flex; gap:8px;">
                    <button class="btn btn-danger btn-sm" onclick="clearTeacherGrid()" title="Hapus semua template jadwal untuk guru ini" style="background:#ef4444; border-color:#ef4444; color:#fff; font-weight:700;">
                        <i class="fa-solid fa-trash-can"></i> Hapus Semua Template
                    </button>
                    <button class="btn btn-secondary btn-sm" onclick="reloadTeacherGrid()" title="Reload Data Grid">
                        <i class="fa-solid fa-arrows-rotate"></i> Refresh Grid
                    </button>
                </div>
            </div>

            <div class="schedule-grid-wrapper">
                <table class="schedule-grid-table" id="schedule-grid-table">
                    <thead>
                        <tr>
                            <th class="day-col">Hari</th>
                            @for($j = 1; $j <= 10; $j++)
                                <th>Jam ke-{{ $j }}</th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(['D1','D2','D3','D4','D5','D6','D7','D8','D9','D10'] as $d)
                            <tr>
                                <td class="day-row-header">{{ $d }}</td>
                                @for($j = 1; $j <= 10; $j++)
                                    <td class="grid-cell empty-cell" 
                                        id="cell_{{ $d }}_{{ $j }}" 
                                        data-cell="{{ $d }}_{{ $j }}"
                                        data-hari="{{ $d }}"
                                        data-jam="{{ $j }}">
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- ─── Floating Action Bar (Shown when cells are selected) ─── --}}
<div class="floating-action-bar" id="floating-action-bar">
    <div class="floating-bar-text">
        <span id="selected-cells-count">0</span> sel terpilih
    </div>
    <div style="display:flex; gap:8px;">
        <button type="button" class="btn btn-secondary btn-sm" onclick="clearCellSelection()" style="background:#475569; color:#fff; border:none; padding:6px 14px; border-radius:30px;">
            Batal
        </button>
        <button type="button" class="btn btn-primary btn-sm" onclick="openFillModal()" style="padding:6px 16px; border-radius:30px;">
            <i class="fa-solid fa-edit"></i> Isi Jadwal
        </button>
    </div>
</div>

{{-- ─── Modal Form Pengisian Data ─── --}}
<div class="modal-overlay" id="modal-grid-fill">
    <div class="modal modal-md" style="max-width:480px; width:95vw;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-calendar-plus" style="color:var(--color-primary, #0d9488);"></i> Isi Data Jadwal Mengajar</h3>
            <button onclick="closeFillModal()" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-grid-fill" onsubmit="saveGridSelection(event)">
            <div class="modal-body">
                <div class="alert alert-info" id="selected-cells-desc" style="font-size:0.8rem; line-height:1.4; padding:8px 12px; margin-bottom:15px; border-radius:6px; background:#f0fdfa; border:1px solid #ccfbf1; color:#0d9488;">
                    {{-- Populated dynamically --}}
                </div>
                
                {{-- Kelas Dropdown --}}
                <div class="form-group" style="margin-bottom:14px;">
                    <label class="form-label">Pilih Kelas <span class="required">*</span></label>
                    <select name="id_kelas" id="f_id_kelas" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}">
                                {{ $k->tingkat }} {{ $k->rombel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Mapel Dropdown --}}
                <div class="form-group" style="margin-bottom:14px;">
                    <label class="form-label">Mata Pelajaran <span class="required">*</span></label>
                    <select name="id_mapel" id="f_id_mapel" class="form-control" required>
                        <option value="">-- Pilih Mata Pelajaran --</option>
                        @foreach($mapelList as $m)
                            <option value="{{ $m->id_mapel }}">{{ $m->nama_mapel }} ({{ $m->kode_mapel }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Ruang Text --}}
                <div class="form-group" style="margin-bottom:5px;">
                    <label class="form-label">Ruang Kelas / Laboratorium</label>
                    <input type="text" name="ruang" id="f_ruang" class="form-control" placeholder="Contoh: R11, Lab RPL, dll" maxlength="50">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeFillModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary" id="btn-save-grid">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// ─── CSRF Token ───
const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// ─── API Endpoints ───
const ENDPOINT_GET    = '{{ route("jadwal-mengajar.template.teacher-grid") }}';
const ENDPOINT_SAVE   = '{{ route("jadwal-mengajar.template.save-grid") }}';
const ENDPOINT_DELETE = '{{ route("jadwal-mengajar.template.delete-grid") }}';
const ENDPOINT_CLEAR  = '{{ route("jadwal-mengajar.template.clear-grid") }}';

// ─── State Variables ───
let selectedCells = new Set();
let isDragging = false;
let currentTeacherId = '';
let currentTeacherName = '';
let gridData = {}; // Stores formatted existing schedules: { cell_key: data }

document.addEventListener('DOMContentLoaded', function () {
    const selectGuru = document.getElementById('select-guru-grid');
    const table = document.getElementById('schedule-grid-table');

    // ─── Handle Teacher Dropdown Change ───
    selectGuru.addEventListener('change', function () {
        currentTeacherId = this.value;
        currentTeacherName = this.options[this.selectedIndex].text;
        
        if (!currentTeacherId) {
            document.getElementById('grid-scheduler-section').style.display = 'none';
            document.getElementById('grid-initial-state').style.display = 'flex';
            clearCellSelection();
            return;
        }

        document.getElementById('grid-initial-state').style.display = 'none';
        document.getElementById('grid-scheduler-section').style.display = 'block';
        document.getElementById('teacher-grid-title').innerHTML = `<i class="fa-solid fa-calendar-check"></i> Jadwal Mengajar: <span style="color:var(--color-primary);">${currentTeacherName}</span>`;
        
        loadTeacherGrid();
    });

    // ─── Drag-to-Select Mechanics ───
    if (table) {
        // Start dragging
        table.addEventListener('mousedown', function (e) {
            const cell = e.target.closest('.grid-cell.empty-cell');
            if (!cell) return;

            e.preventDefault(); // Prevent text selection highlight
            isDragging = true;
            toggleCellSelection(cell);
        });

        // Dragging over cells
        table.addEventListener('mouseenter', function (e) {
            if (!isDragging) return;
            const cell = e.target.closest('.grid-cell.empty-cell');
            if (cell) {
                toggleCellSelection(cell);
            }
        }, true);

        // Stop dragging
        document.addEventListener('mouseup', function () {
            if (isDragging) {
                isDragging = false;
                updateFloatingBar();
            }
        });
    }
});

// ─── Load Teacher Grid Data ───
function loadTeacherGrid() {
    if (!currentTeacherId) return;

    // Show loaders
    document.getElementById('grid-loader').style.display = 'inline-block';
    clearCellSelection();

    fetch(`${ENDPOINT_GET}?id_guru=${currentTeacherId}`)
        .then(response => response.json())
        .then(res => {
            document.getElementById('grid-loader').style.display = 'none';
            if (res.success) {
                gridData = res.schedules;
                renderGridCells();
            } else {
                alert('Gagal memuat jadwal: ' + res.message);
            }
        })
        .catch(err => {
            document.getElementById('grid-loader').style.display = 'none';
            console.error('Error fetching grid:', err);
            alert('Terjadi kesalahan koneksi saat memuat grid.');
        });
}

function reloadTeacherGrid() {
    loadTeacherGrid();
}

// ─── Render Cells Based on Grid Data ───
function renderGridCells() {
    // Clear all cells to empty first
    const cells = document.querySelectorAll('.grid-cell');
    cells.forEach(cell => {
        cell.className = 'grid-cell empty-cell';
        cell.style = '';
        cell.innerHTML = '';
    });

    // Populate filled cells
    Object.keys(gridData).forEach(key => {
        const cellId = `cell_${key}`;
        const cell = document.getElementById(cellId);
        if (cell) {
            const data = gridData[key];
            cell.className = 'grid-cell filled-cell';
            
            // Build cell contents
            cell.innerHTML = `
                <button type="button" class="btn-delete-cell" onclick="deleteCellSchedule(event, ${data.id_template}, '${key}')" title="Hapus jadwal ini">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <span class="cell-mapel">${data.mapel_name}</span>
                <span class="cell-kelas">${data.kelas_name}</span>
                ${data.ruang ? `<span class="cell-ruang"><i class="fa-solid fa-location-dot" style="font-size:0.6rem; opacity:0.8;"></i> ${data.ruang}</span>` : ''}
            `;
        }
    });
}

// ─── Toggle Cell Selection ───
function toggleCellSelection(cell) {
    const cellKey = cell.getAttribute('data-cell');
    
    // Toggle state
    if (selectedCells.has(cellKey)) {
        selectedCells.delete(cellKey);
        cell.classList.remove('selected-cell');
    } else {
        selectedCells.add(cellKey);
        cell.classList.add('selected-cell');
    }

    updateFloatingBar();
}

// ─── Update Selection Bar State ───
function updateFloatingBar() {
    const bar = document.getElementById('floating-action-bar');
    const countSpan = document.getElementById('selected-cells-count');
    
    if (selectedCells.size > 0) {
        countSpan.textContent = selectedCells.size;
        bar.classList.add('active');
    } else {
        bar.classList.remove('active');
    }
}

// ─── Clear All Current Selections ───
function clearCellSelection() {
    selectedCells.clear();
    const cells = document.querySelectorAll('.grid-cell.selected-cell');
    cells.forEach(c => c.classList.remove('selected-cell'));
    updateFloatingBar();
}

// ─── Open Data Fill Modal ───
function openFillModal() {
    if (selectedCells.size === 0) return;

    // Reset Form
    document.getElementById('form-grid-fill').reset();

    // Populate Info
    const sortedCells = Array.from(selectedCells).sort();
    const cellLabels = sortedCells.map(c => {
        const [hari, jam] = c.split('_');
        return `<strong>${hari} Jam ${jam}</strong>`;
    }).join(', ');

    document.getElementById('selected-cells-desc').innerHTML = `
        <i class="fa-solid fa-info-circle"></i> Mengisi jadwal untuk ${selectedCells.size} slot pelajaran terpilih: ${cellLabels}.
    `;

    openModal('modal-grid-fill');
}

function closeFillModal() {
    closeModal('modal-grid-fill');
}

// ─── Save Schedule Entry via AJAX ───
function saveGridSelection(e) {
    e.preventDefault();

    const idKelas = document.getElementById('f_id_kelas').value;
    const idMapel = document.getElementById('f_id_mapel').value;
    const ruang = document.getElementById('f_ruang').value;

    if (!currentTeacherId || !idKelas || !idMapel || selectedCells.size === 0) {
        alert('Mohon lengkapi seluruh data wajib.');
        return;
    }

    const payload = {
        id_guru: parseInt(currentTeacherId),
        id_kelas: parseInt(idKelas),
        id_mapel: parseInt(idMapel),
        ruang: ruang,
        cells: Array.from(selectedCells)
    };

    // Disable button to prevent double click
    const btn = document.getElementById('btn-save-grid');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> Menyimpan...';

    fetch(ENDPOINT_SAVE, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = originalText;

        if (res.success) {
            closeFillModal();
            clearCellSelection();
            loadTeacherGrid();
            
            if (res.errors && res.errors.length > 0) {
                // Partial success with warnings
                alert('Tersimpan dengan catatan konflik:\n' + res.errors.join('\n'));
            }
        } else {
            alert('Gagal menyimpan:\n' + (res.errors ? res.errors.join('\n') : res.message));
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        console.error('Error saving scheduler:', err);
        alert('Terjadi kesalahan koneksi saat menyimpan jadwal.');
    });
}

// ─── Delete Single Cell Entry via AJAX ───
function deleteCellSchedule(e, idTemplate, cellKey) {
    e.stopPropagation(); // Avoid triggering click-to-select on the cell container

    const [hari, jam] = cellKey.split('_');
    if (!confirm(`Apakah Anda yakin ingin menghapus jadwal mengajar pada Hari ${hari} Jam ke-${jam}?`)) {
        return;
    }

    const payload = {
        id_template: idTemplate
    };

    fetch(ENDPOINT_DELETE, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(res => {
        if (res.success) {
            loadTeacherGrid();
        } else {
            alert('Gagal menghapus jadwal: ' + res.message);
        }
    })
    .catch(err => {
        console.error('Error deleting cell schedule:', err);
        alert('Terjadi kesalahan koneksi saat menghapus jadwal.');
    });
}

// ─── Delete All Template Entries for Teacher via AJAX ───
function clearTeacherGrid() {
    if (!currentTeacherId) {
        alert('Silakan pilih guru pengajar terlebih dahulu.');
        return;
    }

    if (!confirm(`Apakah Anda yakin ingin menghapus SELURUH template jadwal mengajar untuk guru ${currentTeacherName}? Tindakan ini tidak dapat dibatalkan.`)) {
        return;
    }

    const payload = {
        id_guru: parseInt(currentTeacherId)
    };

    fetch(ENDPOINT_CLEAR, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(res => {
        if (res.success) {
            alert('Semua template jadwal untuk guru ini berhasil dihapus.');
            loadTeacherGrid();
        } else {
            alert('Gagal menghapus template jadwal: ' + res.message);
        }
    })
    .catch(err => {
        console.error('Error clearing teacher grid:', err);
        alert('Terjadi kesalahan koneksi saat menghapus template jadwal.');
    });
}
</script>
@endpush
@endsection
