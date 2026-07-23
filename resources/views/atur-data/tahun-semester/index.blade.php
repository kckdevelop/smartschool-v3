@extends('layouts.app')

@section('title', 'Tahun Ajaran & Semester — SmartSchool')
@section('header_title', 'Tahun Ajaran & Semester')
@section('header_subtitle', 'Kelola tahun ajaran dan periode semester aktif')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="grid-2-col">
        {{-- ── Tahun Ajaran ── --}}
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-calendar-days"></i> Tahun Ajaran</h2>
                <button class="btn btn-primary btn-sm" onclick="openAddTahun()" id="btn-add-tahun">
                    <i class="fa-solid fa-plus"></i> Tambah
                </button>
            </div>
            <div class="card-body p-0">
                <table class="data-table">
                    <thead><tr><th>#</th><th>Tahun</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($tahunList as $i => $tahun)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td><strong>{{ $tahun->tahun }}</strong></td>
                            <td><span class="badge {{ $tahun->status === 'aktif' ? 'badge-success' : 'badge-muted' }}">{{ ucfirst($tahun->status) }}</span></td>
                            <td class="action-cell">
                                <button type="button" class="btn-icon btn-edit" title="Edit"
                                    onclick="editTahun({{ $tahun->id_tahun }},'{{ $tahun->tahun }}','{{ $tahun->status }}')">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button type="button" class="btn-icon btn-delete" title="Hapus"
                                    onclick="confirmDelete('{{ route('atur-data.tahun-semester.tahun.destroy', $tahun->id_tahun) }}', 'Yakin hapus tahun ajaran ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center text-muted">Belum ada data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ── Semester ── --}}
        <div class="card">
            <div class="card-header">
                <div>
                    <h2 class="card-title"><i class="fa-solid fa-layer-group"></i> Semester</h2>
                    @if($tahunAktif)
                        <span style="font-size:.78rem;color:var(--text-muted);margin-top:2px;display:block;">
                            <i class="fa-solid fa-filter" style="color:var(--color-primary)"></i>
                            Menampilkan semester untuk tahun aktif: <strong style="color:var(--color-primary)">{{ $tahunAktif->tahun }}</strong>
                        </span>
                    @else
                        <span style="font-size:.78rem;color:var(--color-danger);margin-top:2px;display:block;">
                            <i class="fa-solid fa-triangle-exclamation"></i> Belum ada tahun ajaran aktif
                        </span>
                    @endif
                </div>
                <button class="btn btn-primary btn-sm" onclick="openAddSemester()" id="btn-add-semester">
                    <i class="fa-solid fa-plus"></i> Tambah
                </button>
            </div>
            <div class="card-body p-0">
                <table class="data-table">
                    <thead><tr><th>#</th><th>Tahun</th><th>Semester</th><th>Periode</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @forelse($semesterList as $i => $sem)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $sem->tahunAjaran->tahun ?? '-' }}</td>
                            <td>Semester {{ $sem->semester }}</td>
                            <td class="text-xs">{{ \Carbon\Carbon::parse($sem->awal)->format('d/m/Y') }} – {{ \Carbon\Carbon::parse($sem->akhir)->format('d/m/Y') }}</td>
                            <td><span class="badge {{ $sem->status === 'aktif' ? 'badge-success' : 'badge-muted' }}">{{ ucfirst($sem->status) }}</span></td>
                            <td class="action-cell">
                                <button type="button" class="btn-icon btn-edit" title="Edit"
                                    onclick="editSemester(
                                        {{ $sem->id_semester }},
                                        {{ $sem->id_tahun }},
                                        '{{ $sem->semester }}',
                                        '{{ \Carbon\Carbon::parse($sem->awal)->format('Y-m-d') }}',
                                        '{{ \Carbon\Carbon::parse($sem->akhir)->format('Y-m-d') }}',
                                        '{{ $sem->status }}'
                                    )">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button type="button" class="btn-icon btn-delete" title="Hapus"
                                    onclick="confirmDelete('{{ route('atur-data.tahun-semester.semester.destroy', $sem->id_semester) }}', 'Yakin hapus semester ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted">
                            {{ $tahunAktif ? 'Belum ada semester untuk tahun ' . $tahunAktif->tahun : 'Belum ada tahun ajaran aktif' }}
                        </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Tahun --}}
<div class="modal-overlay" id="modal-add-tahun">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modal-tahun-title">Tambah Tahun Ajaran</h3>
            <button onclick="closeModal('modal-add-tahun')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-tahun" action="{{ route('atur-data.tahun-semester.tahun.store') }}" method="POST">
            @csrf
            <input type="hidden" name="_method_override" id="tahun_method">
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Tahun Ajaran <span class="required">*</span></label>
                    <input type="text" name="tahun" id="tahun_input" class="form-control" placeholder="Contoh: 2024/2025" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="tahun_status" class="form-control">
                        <option value="tidak">Tidak Aktif</option>
                        <option value="aktif">Aktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-add-tahun')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Tambah Semester --}}
<div class="modal-overlay" id="modal-add-semester">
    <div class="modal">
        <div class="modal-header">
            <h3 id="modal-semester-title">Tambah Semester</h3>
            <button onclick="closeModal('modal-add-semester')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-semester" action="{{ route('atur-data.tahun-semester.semester.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Tahun Ajaran <span class="required">*</span></label>
                    <select name="id_tahun" id="sem_id_tahun" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        @foreach($tahunList as $t)
                            <option value="{{ $t->id_tahun }}"
                                {{ ($tahunAktif && $t->id_tahun == $tahunAktif->id_tahun) ? 'selected' : '' }}>
                                {{ $t->tahun }}{{ $t->status === 'aktif' ? ' ✓ (Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Semester <span class="required">*</span></label>
                    <select name="semester" id="sem_semester" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="Ganjil">Semester 1 (Ganjil)</option>
                        <option value="Genap">Semester 2 (Genap)</option>
                    </select>
                </div>
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Tanggal Awal <span class="required">*</span></label>
                        <input type="date" name="awal" id="sem_awal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal Akhir <span class="required">*</span></label>
                        <input type="date" name="akhir" id="sem_akhir" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Status</label>
                    <select name="status" id="sem_status" class="form-control">
                        <option value="tidak">Tidak Aktif</option>
                        <option value="aktif">Aktif</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-add-semester')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
const TAHUN_AKTIF_ID = {{ $tahunAktif ? $tahunAktif->id_tahun : 'null' }};
const SEMESTER_STORE_URL = "{{ route('atur-data.tahun-semester.semester.store') }}";
const TAHUN_STORE_URL    = "{{ route('atur-data.tahun-semester.tahun.store') }}";

function openAddTahun() {
    document.getElementById('form-tahun').action = TAHUN_STORE_URL;
    document.getElementById('tahun_input').value = '';
    document.getElementById('tahun_status').value = 'tidak';
    document.getElementById('modal-tahun-title').textContent = 'Tambah Tahun Ajaran';
    openModal('modal-add-tahun');
}

function editTahun(id, tahun, status) {
    document.getElementById('form-tahun').action = `/atur-data/tahun-semester/tahun/${id}`;
    document.getElementById('tahun_input').value = tahun;
    document.getElementById('tahun_status').value = status;
    document.getElementById('modal-tahun-title').textContent = 'Edit Tahun Ajaran';
    openModal('modal-add-tahun');
}

function openAddSemester() {
    document.getElementById('form-semester').action = SEMESTER_STORE_URL;
    document.getElementById('modal-semester-title').textContent = 'Tambah Semester';
    // Reset ke tahun aktif
    if (TAHUN_AKTIF_ID) document.getElementById('sem_id_tahun').value = TAHUN_AKTIF_ID;
    document.getElementById('sem_semester').value = 'Ganjil';
    document.getElementById('sem_awal').value     = '';
    document.getElementById('sem_akhir').value    = '';
    document.getElementById('sem_status').value   = 'tidak';
    openModal('modal-add-semester');
}

// Helper: set select value using loose comparison (handles int vs string from DB)
function setSelectValue(id, val) {
    var sel = document.getElementById(id);
    if (!sel) return;
    var strVal = String(val);
    for (var i = 0; i < sel.options.length; i++) {
        if (sel.options[i].value == strVal) {
            sel.selectedIndex = i;
            return;
        }
    }
    // Fallback: blank option if exists
    sel.selectedIndex = 0;
}

function editSemester(id, id_tahun, semester, awal, akhir, status) {
    document.getElementById('form-semester').action = `/atur-data/tahun-semester/semester/${id}`;
    setSelectValue('sem_id_tahun',  id_tahun);
    setSelectValue('sem_semester',  semester);
    setSelectValue('sem_status',    status);
    document.getElementById('sem_awal').value  = awal;
    document.getElementById('sem_akhir').value = akhir;
    document.getElementById('modal-semester-title').textContent = 'Edit Semester';
    openModal('modal-add-semester');
}
</script>
@endsection
