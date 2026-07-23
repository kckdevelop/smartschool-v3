@extends('layouts.app')

@section('title', 'Pantauan Ibadah Siswa — SmartSchool')
@section('header_title', 'Pantauan Ibadah Siswa')
@section('header_subtitle', 'Monitoring bacaan sholat, sholat jenazah & gerakan wudhu siswa')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Stat Cards --}}
    <div class="ismuba-stats-row" style="grid-template-columns:repeat(3,1fr);">
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#1d4ed8,#3b82f6);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-person-praying"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $countFardu }}</div>
                <div class="ismuba-stat-lbl">Bacaan Sholat Fardu</div>
            </div>
        </div>
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#7c3aed,#a855f7);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-hands-praying"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $countJenazah }}</div>
                <div class="ismuba-stat-lbl">Sholat Jenazah</div>
            </div>
        </div>
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#0369a1,#0ea5e9);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-droplet"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $countWudhu }}</div>
                <div class="ismuba-stat-lbl">Gerakan Wudhu</div>
            </div>
        </div>
    </div>

    {{-- Second row stats --}}
    <div class="ismuba-stats-row" style="grid-template-columns:repeat(3,1fr); margin-top:-4px;">
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#dc2626,#f97316);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-star"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $totalHariIni }}</div>
                <div class="ismuba-stat-lbl">Penilaian Hari Ini</div>
            </div>
        </div>
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#047857,#10b981);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-calendar-check"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $totalBulanIni }}</div>
                <div class="ismuba-stat-lbl">Penilaian Bulan Ini</div>
            </div>
        </div>
        <div class="ismuba-stat-card" style="background:linear-gradient(135deg,#b45309,#f59e0b);">
            <div class="ismuba-stat-icon"><i class="fa-solid fa-clipboard-list"></i></div>
            <div>
                <div class="ismuba-stat-num">{{ $totalAll }}</div>
                <div class="ismuba-stat-lbl">Total Semua Penilaian</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-person-praying"></i> Data Pantauan Ibadah Siswa</h2>
            <div class="card-header-right">
                <form method="GET" class="search-form" style="gap:6px;">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama/NIS..." class="form-control form-control-sm" style="width:140px;">
                    <select name="jenis_ibadah" class="form-control form-control-sm" style="width:160px;">
                        <option value="">Semua Jenis</option>
                        <option value="sholat_fardu" {{ request('jenis_ibadah') === 'sholat_fardu' ? 'selected' : '' }}>Bacaan Sholat Fardu</option>
                        <option value="sholat_jenazah" {{ request('jenis_ibadah') === 'sholat_jenazah' ? 'selected' : '' }}>Sholat Jenazah</option>
                        <option value="gerakan_wudhu" {{ request('jenis_ibadah') === 'gerakan_wudhu' ? 'selected' : '' }}>Gerakan Wudhu</option>
                    </select>
                    <select name="id_kelas" class="form-control form-control-sm" style="width:120px;">
                        <option value="">Semua Kelas</option>
                        @foreach($kelasList as $kelas)
                            <option value="{{ $kelas->id_kelas }}" {{ request('id_kelas') == $kelas->id_kelas ? 'selected' : '' }}>
                                {{ $kelas->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                           class="form-control form-control-sm" title="Dari tanggal">
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                           class="form-control form-control-sm" title="Sampai tanggal">
                    <button class="btn btn-secondary btn-sm"><i class="fa-solid fa-filter"></i></button>
                    @if(request()->hasAny(['search','jenis_ibadah','id_kelas','tanggal_dari','tanggal_sampai']))
                        <a href="{{ route('ismuba.ibadah.index') }}" class="btn btn-secondary btn-sm" title="Reset">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </form>
                <button class="btn btn-primary btn-sm" onclick="openAddIbadah()" id="btn-tambah-ibadah">
                    <i class="fa-solid fa-plus"></i> Tambah Penilaian
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Tanggal</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Jenis Ibadah</th>
                        <th>Nilai</th>
                        <th>Catatan</th>
                        <th>Guru Penilai</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ibadahList as $i => $item)
                    <tr>
                        <td style="color:var(--text-muted);font-size:0.8rem;">{{ $ibadahList->firstItem() + $i }}</td>
                        <td>
                            <div style="font-weight:700;font-size:0.85rem;">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </div>
                        </td>
                        <td>
                            <div style="font-weight:600;">{{ $item->siswa?->nama_siswa ?? '-' }}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">NIS: {{ $item->nis }}</div>
                        </td>
                        <td>
                            @if($item->kelas)
                                <span class="badge badge-muted">{{ $item->kelas->nama_kelas }}</span>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $jenisColors = [
                                    'sholat_fardu'   => ['bg' => 'rgba(29,78,216,0.1)', 'color' => '#1d4ed8', 'border' => 'rgba(29,78,216,0.2)'],
                                    'sholat_jenazah' => ['bg' => 'rgba(124,58,237,0.1)', 'color' => '#7c3aed', 'border' => 'rgba(124,58,237,0.2)'],
                                    'gerakan_wudhu'  => ['bg' => 'rgba(3,105,161,0.1)', 'color' => '#0369a1', 'border' => 'rgba(3,105,161,0.2)'],
                                ];
                                $jColors = $jenisColors[$item->jenis_ibadah] ?? ['bg'=>'#f1f5f9','color'=>'#64748b','border'=>'#e2e8f0'];
                            @endphp
                            <span class="badge" style="background:{{ $jColors['bg'] }};color:{{ $jColors['color'] }};border:1px solid {{ $jColors['border'] }};font-size:0.75rem;">
                                {{ $item->label_jenis }}
                            </span>
                        </td>
                        <td>
                            @php
                                $nilaiColors = ['A' => '#047857', 'B' => '#0369a1', 'C' => '#b45309', 'D' => '#dc2626'];
                                $nc = $nilaiColors[$item->nilai] ?? '#64748b';
                            @endphp
                            <span class="badge" style="background:{{ $nc }};color:#fff;font-size:0.9rem;font-weight:800;min-width:32px;text-align:center;">
                                {{ $item->nilai }}
                            </span>
                        </td>
                        <td style="font-size:0.82rem;max-width:150px;">
                            {{ $item->catatan ? \Str::limit($item->catatan, 50) : '-' }}
                        </td>
                        <td style="font-size:0.83rem;">{{ $item->guru?->nama_guru ?? '-' }}</td>
                        <td class="action-cell">
                            <button type="button" class="btn-icon btn-edit" title="Edit"
                                onclick="editIbadah({{ json_encode($item) }})">
                                <i class="fa-solid fa-pen"></i>
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus"
                                onclick="confirmDelete('{{ route('ismuba.ibadah.destroy', $item->id_ibadah) }}','Yakin hapus data penilaian ibadah ini?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center text-muted py-6">
                            <i class="fa-solid fa-person-praying" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                            Belum ada data pantauan ibadah
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($ibadahList->hasPages())
        <div class="card-footer">
            {{ $ibadahList->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ═══════ MODAL TAMBAH/EDIT IBADAH ═══════ --}}
<div class="modal-overlay" id="modal-add-ibadah">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-title-ibadah"><i class="fa-solid fa-person-praying" style="color:var(--color-primary);"></i> Tambah Penilaian Ibadah</h3>
            <button onclick="closeModal('modal-add-ibadah')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-ibadah" method="POST" action="{{ route('ismuba.ibadah.store') }}">
            @csrf
            <div id="ibadah-method-field"></div>
            <div class="modal-body">
                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Siswa <span class="required">*</span></label>
                        <select name="nis" id="ib_nis" class="form-control" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach($siswaDaftar as $s)
                                <option value="{{ $s->nis }}"
                                    data-nama="{{ $s->nama_siswa }}"
                                    data-kelas="{{ $s->kelas?->nama_kelas ?? '-' }}"
                                    data-kelas-id="{{ $s->id_kelas }}"
                                    data-nis="{{ $s->nis }}">
                                    {{ $s->nis }} — {{ $s->nama_siswa }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kelas <span class="required">*</span></label>
                        <select name="id_kelas" id="ib_id_kelas" class="form-control" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($kelasList as $kelas)
                                <option value="{{ $kelas->id_kelas }}">{{ $kelas->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tanggal <span class="required">*</span></label>
                        <input type="date" name="tanggal" id="ib_tanggal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jenis Ibadah <span class="required">*</span></label>
                        <select name="jenis_ibadah" id="ib_jenis" class="form-control" required>
                            <option value="">-- Pilih Jenis --</option>
                            <option value="sholat_fardu">Bacaan Sholat Fardu</option>
                            <option value="sholat_jenazah">Sholat Jenazah</option>
                            <option value="gerakan_wudhu">Gerakan Wudhu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Nilai <span class="required">*</span></label>
                        <select name="nilai" id="ib_nilai" class="form-control" required>
                            <option value="">-- Pilih Nilai --</option>
                            <option value="A">A — Sangat Baik</option>
                            <option value="B">B — Baik</option>
                            <option value="C">C — Cukup</option>
                            <option value="D">D — Perlu Bimbingan</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Guru Penilai <span class="required">*</span></label>
                        <select name="id_guru" id="ib_id_guru" class="form-control" required>
                            <option value="">-- Pilih Guru ISMUBA --</option>
                            @foreach($guruIsmuba as $guru)
                                <option value="{{ $guru->id_guru }}">{{ $guru->nama_guru }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group" style="grid-column:1/-1;">
                        <label class="form-label">Catatan</label>
                        <textarea name="catatan" id="ib_catatan" class="form-control" rows="2"
                                  placeholder="Catatan tambahan (opsional)..." maxlength="500"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-add-ibadah')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>
            </div>
        </form>
    </div>
</div>

@push('styles')
<style>
.ismuba-stats-row { display:grid; gap:16px; margin-bottom:16px; }
.ismuba-stat-card { display:flex; align-items:center; gap:16px; padding:20px; border-radius:var(--radius-card); color:#fff; box-shadow:0 6px 24px rgba(0,0,0,0.12); transition:var(--transition-smooth); }
.ismuba-stat-card:hover { transform:translateY(-3px); box-shadow:0 12px 36px rgba(0,0,0,0.18); }
.ismuba-stat-icon { width:48px;height:48px;background:rgba(255,255,255,0.2);border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;flex-shrink:0; }
.ismuba-stat-num { font-size:2rem;font-weight:800;line-height:1; }
.ismuba-stat-lbl { font-size:0.78rem;opacity:.85;margin-top:2px; }
@media(max-width:640px) { .ismuba-stats-row { grid-template-columns:1fr !important; } }
</style>
@endpush

@push('scripts')
<script>
const ibToday = '{{ now()->format("Y-m-d") }}';

function openAddIbadah() {
    document.getElementById('form-ibadah').action = '{{ route("ismuba.ibadah.store") }}';
    document.getElementById('ibadah-method-field').innerHTML = '';
    document.getElementById('modal-title-ibadah').innerHTML = '<i class="fa-solid fa-person-praying" style="color:var(--color-primary);"></i> Tambah Penilaian Ibadah';
    document.getElementById('ib_nis').value = '';
    document.getElementById('ib_id_kelas').value = '';
    document.getElementById('ib_tanggal').value = ibToday;
    document.getElementById('ib_jenis').value = '';
    document.getElementById('ib_nilai').value = '';
    document.getElementById('ib_id_guru').value = '';
    document.getElementById('ib_catatan').value = '';
    openModal('modal-add-ibadah');
}

function editIbadah(data) {
    document.getElementById('form-ibadah').action = `/ismuba/ibadah/${data.id_ibadah}`;
    document.getElementById('ibadah-method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-ibadah').innerHTML = '<i class="fa-solid fa-pen" style="color:var(--color-primary);"></i> Edit Penilaian Ibadah';
    document.getElementById('ib_nis').value = data.nis;
    document.getElementById('ib_id_kelas').value = data.id_kelas;
    document.getElementById('ib_tanggal').value = data.tanggal ? data.tanggal.substring(0,10) : '';
    document.getElementById('ib_jenis').value = data.jenis_ibadah;
    document.getElementById('ib_nilai').value = data.nilai;
    document.getElementById('ib_id_guru').value = data.id_guru;
    document.getElementById('ib_catatan').value = data.catatan ?? '';
    openModal('modal-add-ibadah');
}

// Auto-fill kelas dari siswa
document.getElementById('ib_nis').addEventListener('change', function() {
    const opt = this.options[this.selectedIndex];
    const kelasId = opt ? opt.getAttribute('data-kelas-id') : '';
    if (kelasId) {
        document.getElementById('ib_id_kelas').value = kelasId;
    }
});
</script>
@endpush
@endsection
