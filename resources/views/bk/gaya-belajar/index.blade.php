@extends('layouts.app')

@section('title', 'Gaya Belajar & Minat Siswa — BK SmartSchool')
@section('header_title', 'Gaya Belajar & Minat')
@section('header_subtitle', 'Profil preferensi belajar mandiri dan rencana setelah lulus siswa')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Filter --}}
    <div class="card mb-6">
        <div class="card-body">
            <form method="GET" action="{{ route('bk.gaya-belajar.index') }}" class="flex-row-wrap gap-4 align-items-end">
                <div class="form-group mb-0" style="min-width:180px;">
                    <label class="form-label-sm">Gaya Belajar</label>
                    <select name="gaya_belajar" class="form-control form-control-sm">
                        <option value="">— Semua —</option>
                        <option value="visual"     {{ request('gaya_belajar')==='visual'     ? 'selected':'' }}>Visual</option>
                        <option value="auditori"   {{ request('gaya_belajar')==='auditori'   ? 'selected':'' }}>Auditori</option>
                        <option value="kinestetik" {{ request('gaya_belajar')==='kinestetik' ? 'selected':'' }}>Kinestetik</option>
                    </select>
                </div>
                <div class="form-group mb-0" style="min-width:180px;">
                    <label class="form-label-sm">Kelas</label>
                    <select name="id_kelas" class="form-control form-control-sm">
                        <option value="">— Semua Kelas —</option>
                        @foreach($kelas as $k)
                        <option value="{{ $k->id_kelas }}" {{ request('id_kelas')==$k->id_kelas ? 'selected':'' }}>{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-row gap-2">
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-filter"></i> Filter</button>
                    <a href="{{ route('bk.gaya-belajar.index') }}" class="btn btn-secondary btn-sm"><i class="fa-solid fa-rotate"></i> Reset</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabel --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fa-solid fa-brain" style="color:var(--color-primary);"></i>
                Profil Gaya Belajar Siswa
            </h2>
            <div class="card-header-right" style="font-size:0.82rem;color:var(--text-muted);">
                Total: <strong>{{ $data->total() }}</strong> data
            </div>
        </div>
        <div class="card-body p-0">
            <div style="overflow-x:auto;">
            <table class="data-table" style="min-width:1100px;font-size:0.82rem;">
                <thead>
                    <tr>
                        <th style="width:38px;text-align:center;">#</th>
                        <th style="width:88px;">Tanggal</th>
                        <th style="min-width:150px;">Siswa</th>
                        <th style="width:85px;text-align:center;">Kelas</th>
                        <th style="width:105px;text-align:center;">Gaya Dominan</th>
                        <th style="width:68px;text-align:center;" title="Skor Visual (max 40)">👁<br>Visual</th>
                        <th style="width:68px;text-align:center;" title="Skor Auditori (max 40)">👂<br>Auditori</th>
                        <th style="width:68px;text-align:center;" title="Skor Kinestetik (max 40)">🤸<br>Kinestetik</th>
                        <th style="width:115px;text-align:center;">Minat / Rencana</th>
                        <th style="min-width:200px;">Catatan Guru BK</th>
                        <th style="width:100px;text-align:center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($data as $i => $item)
                @php
                    $dom = $item->gaya_belajar;
                    $sV  = $item->skor_visual     ?? null;
                    $sA  = $item->skor_auditori   ?? null;
                    $sK  = $item->skor_kinestetik ?? null;
                    $domStyle = match($dom) {
                        'visual'     => 'background:#EEF2FF;color:#4F46E5;border:1px solid #C7D2FE;',
                        'auditori'   => 'background:#ECFDF5;color:#059669;border:1px solid #A7F3D0;',
                        'kinestetik' => 'background:#FFF7ED;color:#D97706;border:1px solid #FED7AA;',
                        default      => 'background:#F1F5F9;color:#64748B;border:1px solid #E2E8F0;',
                    };
                    $domEmoji = match($dom) { 'visual' => '👁', 'auditori' => '👂', 'kinestetik' => '🤸', default => '🧠' };
                @endphp
                <tr>
                    <td style="text-align:center;color:var(--text-muted);">{{ $data->firstItem() + $i }}</td>
                    <td style="white-space:nowrap;color:var(--text-muted);">{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}</td>
                    <td>
                        <div style="font-weight:600;color:var(--text-primary);line-height:1.3;">{{ $item->siswa->nama_siswa ?? $item->nis }}</div>
                        <div style="font-size:0.76rem;color:var(--text-muted);">{{ $item->nis }}</div>
                    </td>
                    <td style="text-align:center;">
                        @if($item->siswa?->kelas)
                            <span style="display:inline-block;padding:2px 9px;border-radius:20px;font-size:0.74rem;font-weight:600;background:var(--color-primary-light);color:var(--color-primary);">{{ $item->siswa->kelas->nama_kelas }}</span>
                        @else <span style="color:#CBD5E1;">—</span> @endif
                    </td>
                    <td style="text-align:center;">
                        <span style="display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:20px;font-size:0.76rem;font-weight:700;{{ $domStyle }}">{{ $domEmoji }} {{ ucfirst($dom) }}</span>
                    </td>
                    <td style="text-align:center;font-weight:{{ $dom==='visual'?'700':'400' }};color:{{ $dom==='visual'?'#4F46E5':($sV!==null?'#374151':'#CBD5E1') }};">
                        @if($sV !== null)
                            {{ $sV }}<span style="font-size:0.68rem;font-weight:400;color:#94A3B8;">/40</span>
                            <div style="margin-top:3px;height:5px;border-radius:99px;background:#EEF2FF;overflow:hidden;width:80%;margin-inline:auto;"><div style="height:100%;width:{{ round(($sV/40)*100) }}%;background:#6366F1;border-radius:99px;"></div></div>
                        @else — @endif
                    </td>
                    <td style="text-align:center;font-weight:{{ $dom==='auditori'?'700':'400' }};color:{{ $dom==='auditori'?'#059669':($sA!==null?'#374151':'#CBD5E1') }};">
                        @if($sA !== null)
                            {{ $sA }}<span style="font-size:0.68rem;font-weight:400;color:#94A3B8;">/40</span>
                            <div style="margin-top:3px;height:5px;border-radius:99px;background:#ECFDF5;overflow:hidden;width:80%;margin-inline:auto;"><div style="height:100%;width:{{ round(($sA/40)*100) }}%;background:#10B981;border-radius:99px;"></div></div>
                        @else — @endif
                    </td>
                    <td style="text-align:center;font-weight:{{ $dom==='kinestetik'?'700':'400' }};color:{{ $dom==='kinestetik'?'#D97706':($sK!==null?'#374151':'#CBD5E1') }};">
                        @if($sK !== null)
                            {{ $sK }}<span style="font-size:0.68rem;font-weight:400;color:#94A3B8;">/40</span>
                            <div style="margin-top:3px;height:5px;border-radius:99px;background:#FFF7ED;overflow:hidden;width:80%;margin-inline:auto;"><div style="height:100%;width:{{ round(($sK/40)*100) }}%;background:#F59E0B;border-radius:99px;"></div></div>
                        @else — @endif
                    </td>
                    <td style="text-align:center;">
                        @if($item->minat)
                            <span style="display:inline-block;padding:3px 9px;border-radius:20px;font-size:0.74rem;font-weight:600;background:#F0FDF4;color:#065F46;border:1px solid #BBF7D0;white-space:nowrap;">{{ $item->minat }}</span>
                        @else <span style="color:#CBD5E1;font-size:0.78rem;">—</span> @endif
                    </td>
                    <td style="vertical-align:top;padding-top:9px;line-height:1.6;">
                        @if($item->catatan)
                            <div style="font-size:0.81rem;color:#374151;">{{ $item->catatan }}</div>
                            @if($item->guru)
                                <div style="font-size:0.71rem;color:var(--text-muted);margin-top:3px;"><i class="fa-solid fa-user-tie" style="font-size:0.65rem;"></i> {{ $item->guru->nama_guru ?? 'Guru BK' }}</div>
                            @endif
                        @else <span style="color:#CBD5E1;font-size:0.78rem;font-style:italic;">Belum ada catatan</span> @endif
                    </td>
                    <td style="text-align:center;vertical-align:middle;">
                        <div style="display:flex;gap:5px;justify-content:center;align-items:center;">
                            <button type="button" class="btn btn-primary btn-sm" style="font-size:0.72rem;padding:4px 9px;display:flex;align-items:center;gap:4px;border-radius:6px;" title="Edit Catatan Guru BK" onclick="openCatatanModal({{ $item->id_gaya_belajar }}, {{ json_encode($item->catatan ?? '') }})">
                                <i class="fa-solid fa-pen-to-square"></i> Catatan
                            </button>
                            <button type="button" class="btn-icon btn-delete" title="Hapus" onclick="confirmDelete('{{ route('bk.gaya-belajar.destroy', $item->id_gaya_belajar) }}', 'Yakin hapus data {{ addslashes($item->siswa->nama_siswa ?? $item->nis) }}?')">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="11" class="text-center text-muted py-6">
                        <i class="fa-solid fa-brain" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                        Belum ada data gaya belajar siswa.<br>
                        <small>Data muncul otomatis setelah siswa mengerjakan tes di aplikasi mobile.</small>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
            </div>
        </div>
        @if($data->hasPages())
        <div class="card-footer">{{ $data->links() }}</div>
        @endif
    </div>
</div>

{{-- Modal Edit Catatan --}}
<div class="modal-overlay" id="modal-catatan">
    <div class="modal" style="max-width:520px;width:100%;">
        <div class="modal-header">
            <h3 style="display:flex;align-items:center;gap:8px;"><i class="fa-solid fa-pen-to-square" style="color:var(--color-primary);"></i> Catatan &amp; Rekomendasi Guru BK</h3>
            <button onclick="closeCatatanModal()" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-catatan" method="POST" action="">
            @csrf
            @method('PATCH')
            <div class="modal-body">
                <p style="font-size:0.84rem;color:var(--text-muted);margin-bottom:12px;">Tuliskan rekomendasi cara belajar, pendekatan mengajar, atau catatan penting untuk siswa ini.</p>
                <div class="form-group mb-0">
                    <label class="form-label">Catatan / Rekomendasi</label>
                    <textarea name="catatan" id="catatan-textarea" class="form-control" rows="7" maxlength="2000" placeholder="Contoh: Siswa dominan visual — gunakan media gambar, diagram, mind-map, dan video..."></textarea>
                    <div style="font-size:0.74rem;color:var(--text-muted);margin-top:4px;text-align:right;"><span id="char-count">0</span>/2000 karakter</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeCatatanModal()" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-floppy-disk"></i> Simpan Catatan</button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
const gbBaseUrl = '{{ url("bk/gaya-belajar") }}';
function openCatatanModal(id, catatan) {
    document.getElementById('form-catatan').action = gbBaseUrl + '/' + id + '/catatan';
    const ta = document.getElementById('catatan-textarea');
    ta.value = catatan || '';
    updateCount(ta);
    openModal('modal-catatan');
    setTimeout(() => ta.focus(), 80);
}
function closeCatatanModal() { 
    closeModal('modal-catatan'); 
}
const taEl = document.getElementById('catatan-textarea');
taEl.addEventListener('input', () => updateCount(taEl));
function updateCount(el) {
    const n = el.value.length, c = document.getElementById('char-count');
    c.textContent = n; c.style.color = n > 1800 ? '#EF4444' : '';
}
</script>
@endpush
@endsection

