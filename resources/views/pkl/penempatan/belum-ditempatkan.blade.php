@extends('layouts.app')

@section('title', 'Siswa Belum Ditempatkan PKL — SmartSchool')
@section('header_title', 'Siswa Belum Ditempatkan')
@section('header_subtitle', 'Daftar siswa yang belum mendapatkan penempatan PKL')

@push('styles')
<style>
.student-card {
    background: var(--card-bg, #fff);
    border: 1.5px solid var(--border-color, #e2e8f0);
    border-radius: 12px;
    padding: 16px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    transition: box-shadow .18s, transform .18s;
}
.student-card:hover {
    box-shadow: 0 6px 24px rgba(79,70,229,.1);
    transform: translateY(-2px);
}
.avatar-circle {
    width: 44px; height: 44px;
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-weight: 800; font-size: 1rem;
    flex-shrink: 0;
}
.dudi-option {
    padding: 10px 14px;
    border: 1.5px solid #e2e8f0;
    border-radius: 10px;
    cursor: pointer;
    transition: all .15s;
    margin-bottom: 8px;
}
.dudi-option:hover { border-color: #6366f1; background: #EEF2FF; }
.dudi-option.selected { border-color: #6366f1; background: #EEF2FF; }
.dudi-option.penuh { opacity: .5; cursor: not-allowed; }
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Header bar --}}
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:20px; flex-wrap:wrap;">
        <a href="{{ route('pkl.penempatan.index') }}" class="btn btn-secondary btn-sm">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke Penempatan
        </a>
        <h2 style="margin:0; font-size:1.05rem; font-weight:700; color:var(--text-primary);">
            <i class="fa-solid fa-user-clock" style="color:#e11d48;"></i>
            Siswa Belum Mendapatkan Penempatan PKL
        </h2>
    </div>

    <div class="card">
        {{-- Filter bar --}}
        <div style="padding:14px 24px; border-bottom:1.5px solid #f1f5f9; background:#fafbff;">
            <form method="GET" action="{{ route('pkl.penempatan.belum-ditempatkan') }}" style="display:flex; gap:12px; flex-wrap:wrap; align-items:flex-end;">
                <div style="flex:1; min-width:200px;">
                    <label style="font-size:.74rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.5px;display:block;margin-bottom:4px;">Gelombang PKL</label>
                    <select name="id_gelombang" class="form-control form-control-sm" onchange="this.form.submit()">
                        <option value="">-- Pilih Gelombang --</option>
                        @foreach($gelombangList as $g)
                        <option value="{{ $g->id_gelombang }}" {{ optional($selectedGelombang)->id_gelombang == $g->id_gelombang ? 'selected' : '' }}>
                            {{ $g->nama_gelombang }} ({{ $g->tahun_ajaran }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="flex:2; min-width:200px;">
                    <label style="font-size:.74rem;font-weight:700;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.5px;display:block;margin-bottom:4px;">Cari Siswa</label>
                    <div style="display:flex;gap:8px;">
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Nama atau NIS..." style="flex:1;">
                        @if($selectedGelombang)
                        <input type="hidden" name="id_gelombang" value="{{ $selectedGelombang->id_gelombang }}">
                        @endif
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-search"></i></button>
                        <a href="{{ route('pkl.penempatan.belum-ditempatkan', $selectedGelombang ? ['id_gelombang'=>$selectedGelombang->id_gelombang] : []) }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate"></i></a>
                    </div>
                </div>
            </form>
        </div>

        @if($selectedGelombang)
        {{-- Gelombang info strip --}}
        <div style="padding:10px 24px; background:#fef2f2; border-bottom:1.5px solid #fecaca; display:flex; align-items:center; gap:16px; flex-wrap:wrap;">
            <span style="font-size:.82rem;font-weight:700;color:#e11d48;"><i class="fa-solid fa-circle-info"></i> {{ $selectedGelombang->nama_gelombang }}</span>
            <span style="font-size:.82rem;color:var(--text-muted);">
                {{ \Carbon\Carbon::parse($selectedGelombang->tanggal_mulai)->format('d/m/Y') }} —
                {{ \Carbon\Carbon::parse($selectedGelombang->tanggal_selesai)->format('d/m/Y') }}
            </span>
            <span class="badge badge-{{ $selectedGelombang->status === 'aktif' ? 'success' : 'muted' }}">{{ ucfirst($selectedGelombang->status) }}</span>
            <span style="font-size:.82rem;color:var(--text-muted);">
                <i class="fa-solid fa-user-clock" style="color:#e11d48;"></i>
                <strong>{{ $siswaList->total() }}</strong> siswa belum ditempatkan
            </span>
        </div>

        <div class="card-body p-0">
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width:50px;">#</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Jurusan</th>
                        <th style="width:160px; text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaList as $i => $siswa)
                    @php
                        $jurusan = optional(optional($siswa->kelas)->jurusan);
                        $colors = ['#6366f1','#0d9488','#f97316','#e11d48','#8b5cf6','#14b8a6'];
                        $color  = $colors[$i % count($colors)];
                    @endphp
                    <tr>
                        <td style="color:var(--text-muted);font-size:.8rem;">{{ $siswaList->firstItem() + $i }}</td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div class="avatar-circle" style="background:{{ $color }}20;color:{{ $color }};">
                                    {{ strtoupper(substr($siswa->nama_siswa,0,1)) }}
                                </div>
                                <div>
                                    <div style="font-weight:600;font-size:.9rem;">{{ $siswa->nama_siswa }}</div>
                                    <div style="font-size:.78rem;color:var(--text-muted);">NIS: {{ $siswa->nis }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge badge-info" style="font-size:.74rem;">{{ optional($siswa->kelas)->nama_kelas ?? '-' }}</span></td>
                        <td style="font-size:.85rem;color:var(--text-muted);">{{ $jurusan->nama_jurusan ?? '-' }}</td>
                        <td class="text-center">
                            <button type="button"
                                class="btn btn-primary btn-sm"
                                onclick="openPlaceModal({
                                    nis: '{{ $siswa->nis }}',
                                    nama: '{{ addslashes($siswa->nama_siswa) }}',
                                    kelas: '{{ addslashes(optional($siswa->kelas)->nama_kelas ?? '-') }}',
                                    id_jurusan: '{{ $jurusan->id_jurusan ?? '' }}',
                                    nama_jurusan: '{{ addslashes($jurusan->nama_jurusan ?? '') }}'
                                })">
                                <i class="fa-solid fa-building-user"></i> Pilih DUDI
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-6">
                            <i class="fa-solid fa-circle-check" style="font-size:2rem;color:#10b981;opacity:.5;display:block;margin-bottom:8px;"></i>
                            Semua siswa sudah mendapatkan penempatan PKL!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($siswaList->hasPages())
        <div class="card-footer">{{ $siswaList->links() }}</div>
        @endif

        @else
        <div style="padding:60px;text-align:center;color:var(--text-muted);">
            <i class="fa-solid fa-wave-square" style="font-size:2.5rem;opacity:.3;display:block;margin-bottom:12px;"></i>
            Pilih gelombang PKL terlebih dahulu
        </div>
        @endif
    </div>
</div>

{{-- Modal Pilih DUDI --}}
<div class="modal-overlay" id="modal-place">
    <div class="modal modal-lg">
        <div class="modal-header">
            <h3 id="modal-place-title">Pilih DUDI untuk Penempatan</h3>
            <button onclick="closeModal('modal-place')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-quick-place" method="POST" action="{{ route('pkl.penempatan.quick-place') }}">
            @csrf
            <input type="hidden" name="id_gelombang" value="{{ optional($selectedGelombang)->id_gelombang }}">
            <input type="hidden" name="nis" id="place_nis">
            <input type="hidden" name="id_dudi" id="place_id_dudi">

            <div class="modal-body">
                {{-- Info siswa terpilih --}}
                <div id="place_siswa_info" style="padding:12px 16px; background:#F0FDF4; border:1.5px solid #BBF7D0; border-radius:10px; margin-bottom:18px; display:flex; align-items:center; gap:10px;">
                    <i class="fa-solid fa-user-check" style="color:#059669; font-size:1.1rem;"></i>
                    <div>
                        <div id="place_siswa_name" style="font-weight:700; font-size:.9rem;"></div>
                        <div id="place_siswa_kelas" style="font-size:.78rem; color:#64748B;"></div>
                    </div>
                </div>

                {{-- Filter & DUDI list --}}
                <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:12px; flex-wrap:wrap; gap:8px;">
                    <div>
                        <span style="font-size:.82rem; font-weight:700; color:var(--text-secondary);">DUDI Tersedia</span>
                        <span id="place_jurusan_badge" style="font-size:.72rem; background:#EEF2FF; color:#4F46E5; padding:2px 10px; border-radius:20px; font-weight:600; margin-left:6px;"></span>
                    </div>
                    <label style="display:flex;align-items:center;gap:6px;font-size:.8rem;cursor:pointer;">
                        <input type="checkbox" id="toggle_semua_dudi" onchange="loadDudiList()"> Tampilkan semua DUDI
                    </label>
                </div>

                <div style="margin-bottom:10px;">
                    <input type="text" id="dudi_search" class="form-control form-control-sm" placeholder="Cari DUDI..." onkeyup="filterDudiList(this.value)">
                </div>

                <div id="dudi_list_container" style="max-height:320px; overflow-y:auto; display:grid; gap:6px;">
                    <div style="text-align:center; padding:24px; color:var(--text-muted);">
                        <i class="fa-solid fa-spinner fa-spin"></i> Memuat data DUDI...
                    </div>
                </div>

                <div id="dudi_selected_info" style="display:none; margin-top:14px; padding:10px 14px; background:#EEF2FF; border:1.5px solid #6366f1; border-radius:10px; font-size:.85rem;">
                    <i class="fa-solid fa-building-user" style="color:#4F46E5;"></i>
                    DUDI terpilih: <strong id="dudi_selected_name"></strong>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-place')" class="btn btn-secondary">Batal</button>
                <button type="submit" id="btn-place-submit" class="btn btn-primary" disabled>
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Penempatan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let _currentJurusan = { id: '', nama: '' };
let _allDudiList = [];
let _idGelombang = '{{ optional($selectedGelombang)->id_gelombang }}';

function openPlaceModal(data) {
    document.getElementById('place_nis').value = data.nis;
    document.getElementById('place_siswa_name').textContent = data.nama;
    document.getElementById('place_siswa_kelas').textContent = 'Kelas ' + data.kelas;
    document.getElementById('modal-place-title').textContent = 'Pilih DUDI untuk ' + data.nama;
    document.getElementById('place_id_dudi').value = '';
    document.getElementById('dudi_selected_info').style.display = 'none';
    document.getElementById('btn-place-submit').disabled = true;
    document.getElementById('dudi_search').value = '';
    document.getElementById('toggle_semua_dudi').checked = false;

    _currentJurusan = { id: data.id_jurusan, nama: data.nama_jurusan };

    const badge = document.getElementById('place_jurusan_badge');
    badge.textContent = data.nama_jurusan ? 'Jurusan: ' + data.nama_jurusan : '';

    loadDudiList();
    openModal('modal-place');
}

function loadDudiList() {
    const tampilSemua = document.getElementById('toggle_semua_dudi').checked;
    const idJurusan   = tampilSemua ? '' : (_currentJurusan.id || '');
    const container   = document.getElementById('dudi_list_container');
    container.innerHTML = '<div style="text-align:center;padding:24px;color:var(--text-muted);"><i class="fa-solid fa-spinner fa-spin"></i> Memuat...</div>';

    fetch(`/pkl/penempatan/dudi-by-jurusan?id_jurusan=${idJurusan}&id_gelombang=${_idGelombang}`)
        .then(r => r.json())
        .then(list => {
            _allDudiList = list;
            renderDudiList(list);
        });
}

function renderDudiList(list) {
    const container = document.getElementById('dudi_list_container');
    if (list.length === 0) {
        container.innerHTML = '<div style="text-align:center;padding:24px;color:#94A3B8;">Tidak ada DUDI tersedia</div>';
        return;
    }

    container.innerHTML = '';
    list.forEach(d => {
        const div = document.createElement('div');
        div.className = 'dudi-option' + (d.sisa_kuota === 0 ? ' penuh' : '');
        div.dataset.id = d.id_dudi;
        div.innerHTML = `
            <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;">
                <div>
                    <div style="font-weight:700;font-size:.88rem;">${d.nama_dudi}</div>
                    <div style="font-size:.76rem;color:#64748B;">${d.bidang_usaha ?? ''} ${d.kota ? '· ' + d.kota : ''}</div>
                </div>
                <div style="text-align:right;flex-shrink:0;">
                    ${d.sisa_kuota > 0
                        ? `<span style="font-size:.76rem;font-weight:700;background:#DCFCE7;color:#15803D;padding:2px 10px;border-radius:20px;">Sisa ${d.sisa_kuota}/${d.kuota_siswa}</span>`
                        : `<span style="font-size:.76rem;font-weight:700;background:#FEE2E2;color:#B91C1C;padding:2px 10px;border-radius:20px;">Penuh</span>`
                    }
                </div>
            </div>`;

        if (d.sisa_kuota > 0) {
            div.onclick = () => selectDudi(d);
        }
        container.appendChild(div);
    });
}

function filterDudiList(q) {
    const filtered = q.trim().length === 0
        ? _allDudiList
        : _allDudiList.filter(d =>
            d.nama_dudi.toLowerCase().includes(q.toLowerCase()) ||
            (d.bidang_usaha ?? '').toLowerCase().includes(q.toLowerCase()) ||
            (d.kota ?? '').toLowerCase().includes(q.toLowerCase())
        );
    renderDudiList(filtered);
}

function selectDudi(d) {
    // Reset semua
    document.querySelectorAll('.dudi-option').forEach(el => el.classList.remove('selected'));
    // Pilih yang diklik
    const el = document.querySelector(`.dudi-option[data-id="${d.id_dudi}"]`);
    if (el) el.classList.add('selected');

    document.getElementById('place_id_dudi').value = d.id_dudi;
    document.getElementById('dudi_selected_name').textContent = d.nama_dudi;
    document.getElementById('dudi_selected_info').style.display = 'block';
    document.getElementById('btn-place-submit').disabled = false;
}
</script>
@endpush
@endsection
