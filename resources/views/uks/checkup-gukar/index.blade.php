@extends('layouts.app')

@section('title', 'Data Check-Up Gukar — SmartSchool')
@section('header_title', 'Data Check-Up Guru & Karyawan')
@section('header_subtitle', 'Pemeriksaan kesehatan berkala Guru dan Karyawan')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Stat Cards --}}
    <div class="uks-stats-row">
        <div class="uks-stat-card" style="background:linear-gradient(135deg,#0284c7,#0ea5e9);">
            <div class="uks-stat-icon"><i class="fa-solid fa-heart-pulse"></i></div>
            <div>
                <div class="uks-stat-num">{{ $hariIni }}</div>
                <div class="uks-stat-lbl">Check-Up Hari Ini</div>
            </div>
        </div>
        <div class="uks-stat-card" style="background:linear-gradient(135deg,#4f46e5,#6366f1);">
            <div class="uks-stat-icon"><i class="fa-solid fa-file-medical"></i></div>
            <div>
                <div class="uks-stat-num">{{ $totalCheckup }}</div>
                <div class="uks-stat-lbl">Total Data Pemeriksaan</div>
            </div>
        </div>
    </div>

    {{-- ── Keterangan Nilai Rujukan ── --}}
    <div class="card" style="margin-bottom: 16px; border-left: 4px solid #0ea5e9;">
        <div class="card-body" style="padding: 16px 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 12px;">
                <i class="fa-solid fa-circle-info" style="color: #0ea5e9; font-size: 1rem;"></i>
                <strong style="font-size: 0.9rem; color: var(--text-primary);">Keterangan Nilai Rujukan Pemeriksaan Kesehatan</strong>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; font-size: 0.8rem;">
                {{-- Tekanan Darah --}}
                <div>
                    <div style="font-weight: 700; color: #ef4444; margin-bottom: 6px;"><i class="fa-solid fa-gauge-high"></i> Tekanan Darah (mmHg)</div>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td><span class="badge badge-warning" style="font-size:0.68rem;">Rendah</span></td><td style="color:var(--text-muted); padding: 2px 0;">Sistolik &lt; 90 atau Diastolik &lt; 60</td></tr>
                        <tr><td><span class="badge badge-success" style="font-size:0.68rem;">Normal</span></td><td style="color:var(--text-muted); padding: 2px 0;">Sistolik &lt; 120 dan Diastolik &lt; 80</td></tr>
                        <tr><td><span class="badge badge-info" style="font-size:0.68rem;">Normal Tinggi</span></td><td style="color:var(--text-muted); padding: 2px 0;">Sistolik 120–129 dan Diastolik &lt; 80</td></tr>
                        <tr><td><span class="badge badge-warning" style="font-size:0.68rem;">Tinggi (HT 1)</span></td><td style="color:var(--text-muted); padding: 2px 0;">Sistolik 130–139 atau Diastolik 80–89</td></tr>
                        <tr><td><span class="badge badge-danger" style="font-size:0.68rem;">Tinggi (HT 2)</span></td><td style="color:var(--text-muted); padding: 2px 0;">Sistolik ≥ 140 atau Diastolik ≥ 90</td></tr>
                    </table>
                </div>
                {{-- Kolesterol --}}
                <div>
                    <div style="font-weight: 700; color: #f59e0b; margin-bottom: 6px;"><i class="fa-solid fa-droplet"></i> Kolesterol (mg/dL)</div>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td><span class="badge badge-warning" style="font-size:0.68rem;">Rendah</span></td><td style="color:var(--text-muted); padding: 2px 0;">&lt; 120 mg/dL</td></tr>
                        <tr><td><span class="badge badge-success" style="font-size:0.68rem;">Normal</span></td><td style="color:var(--text-muted); padding: 2px 0;">120 – 199 mg/dL</td></tr>
                        <tr><td><span class="badge badge-warning" style="font-size:0.68rem;">Sedang</span></td><td style="color:var(--text-muted); padding: 2px 0;">200 – 239 mg/dL (Batas Tinggi)</td></tr>
                        <tr><td><span class="badge badge-danger" style="font-size:0.68rem;">Tinggi</span></td><td style="color:var(--text-muted); padding: 2px 0;">≥ 240 mg/dL</td></tr>
                    </table>
                </div>
                {{-- Gula Darah --}}
                <div>
                    <div style="font-weight: 700; color: #8b5cf6; margin-bottom: 6px;"><i class="fa-solid fa-vial"></i> Gula Darah Puasa (mg/dL)</div>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td><span class="badge badge-warning" style="font-size:0.68rem;">Rendah</span></td><td style="color:var(--text-muted); padding: 2px 0;">&lt; 75 mg/dL (Hipoglikemia)</td></tr>
                        <tr><td><span class="badge badge-success" style="font-size:0.68rem;">Normal</span></td><td style="color:var(--text-muted); padding: 2px 0;">75 – 99 mg/dL</td></tr>
                        <tr><td><span class="badge badge-info" style="font-size:0.68rem;">Prediabetes</span></td><td style="color:var(--text-muted); padding: 2px 0;">100 – 125 mg/dL</td></tr>
                        <tr><td><span class="badge badge-danger" style="font-size:0.68rem;">Tinggi</span></td><td style="color:var(--text-muted); padding: 2px 0;">≥ 126 mg/dL (Diabetes)</td></tr>
                    </table>
                </div>
                {{-- Asam Urat --}}
                <div>
                    <div style="font-weight: 700; color: #10b981; margin-bottom: 6px;"><i class="fa-solid fa-flask-vial"></i> Asam Urat (mg/dL)</div>
                    <table style="width: 100%; border-collapse: collapse;">
                        <tr><td><span class="badge badge-warning" style="font-size:0.68rem;">Rendah</span></td><td style="color:var(--text-muted); padding: 2px 0;">&lt; 2.4 mg/dL</td></tr>
                        <tr><td><span class="badge badge-success" style="font-size:0.68rem;">Normal ♂</span></td><td style="color:var(--text-muted); padding: 2px 0;">2,4 – 7,0 mg/dL (Laki-laki)</td></tr>
                        <tr><td><span class="badge badge-success" style="font-size:0.68rem;">Normal ♀</span></td><td style="color:var(--text-muted); padding: 2px 0;">2,4 – 6,0 mg/dL (Perempuan)</td></tr>
                        <tr><td><span class="badge badge-danger" style="font-size:0.68rem;">Tinggi ♂</span></td><td style="color:var(--text-muted); padding: 2px 0;">&gt; 7,0 mg/dL (Hiperurisemia)</td></tr>
                        <tr><td><span class="badge badge-danger" style="font-size:0.68rem;">Tinggi ♀</span></td><td style="color:var(--text-muted); padding: 2px 0;">&gt; 6,0 mg/dL (Hiperurisemia)</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card">

        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-notes-medical"></i> Data Check-Up Guru & Karyawan</h2>
            <div class="card-header-right">
                {{-- Filter Form --}}
                <form method="GET" class="search-form" style="gap:6px; flex-wrap:wrap;">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama/NIP..." class="form-control form-control-sm" style="width:150px;">
                    <select name="role" class="form-control form-control-sm" style="width:140px;">
                        <option value="">-- Semua Peran --</option>
                        <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                        <option value="karyawan" {{ request('role') == 'karyawan' ? 'selected' : '' }}>Karyawan</option>
                    </select>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                           class="form-control form-control-sm" title="Dari tanggal">
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                           class="form-control form-control-sm" title="Sampai tanggal">
                    <button class="btn btn-secondary btn-sm" title="Filter"><i class="fa-solid fa-filter"></i></button>
                    @if(request()->hasAny(['search','role','tanggal_dari','tanggal_sampai']))
                        <a href="{{ route('uks.checkup-gukar.index') }}" class="btn btn-secondary btn-sm" title="Reset">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </form>
                <button class="btn btn-secondary btn-sm" onclick="openModal('modal-import')" id="btn-import-checkup" style="display:inline-flex; align-items:center; gap:6px;">
                    <i class="fa-solid fa-file-excel"></i> Import Excel
                </button>
                <button class="btn btn-primary btn-sm" onclick="resetAndOpenAddModal()" id="btn-tambah-checkup">
                    <i class="fa-solid fa-plus"></i> Tambah Check-Up Gukar
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Tanggal</th>
                            <th>Peran</th>
                            <th>Nama / NIP</th>
                            <th>TB (cm)</th>
                            <th>BB (kg)</th>
                            <th>IMT (Kategori)</th>
                            <th>Tekanan Darah</th>
                            <th>Kolesterol</th>
                            <th>Gula Darah</th>
                            <th>Asam Urat</th>
                            <th style="width: 100px;" class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checkups as $i => $item)
                        <tr>
                            <td style="color:var(--text-muted);font-size:0.85rem;" class="text-center">{{ $checkups->firstItem() + $i }}</td>
                            <td>
                                <div style="font-weight:600;font-size:0.88rem;">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            <td>
                                @if($item->id_guru)
                                    <span class="badge badge-info">Guru</span>
                                @else
                                    <span class="badge badge-success">Karyawan</span>
                                @endif
                            </td>
                            <td>
                                <div style="font-weight:600;">
                                    {{ $item->id_guru ? ($item->guru?->nama_guru ?? '-') : ($item->karyawan?->nama_karyawan ?? '-') }}
                                </div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">
                                    ID/NIP: {{ $item->id_guru ? ($item->guru?->no_id ?? '-') : ($item->karyawan?->no_id ?? '-') }}
                                </div>
                            </td>
                            <td>{{ $item->tinggi_badan ?? '-' }}</td>
                            <td>{{ $item->berat_badan ?? '-' }}</td>
                            <td>
                                @if($item->imt)
                                    <strong>{{ number_format($item->imt, 1) }}</strong>
                                    @if($item->kategori)
                                        @php
                                            $kat = strtolower($item->kategori);
                                            $badgeClass = 'badge-secondary';
                                            if (str_contains($kat, 'kurus')) {
                                                $badgeClass = 'badge-warning';
                                            } elseif (str_contains($kat, 'normal')) {
                                                $badgeClass = 'badge-success';
                                            } elseif (str_contains($kat, 'gemuk')) {
                                                $badgeClass = 'badge-warning';
                                            } elseif (str_contains($kat, 'obesitas')) {
                                                $badgeClass = 'badge-danger';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}" style="font-size:0.75rem;padding:2px 6px;">{{ $item->kategori }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->tekanan_darah)
                                    @php
                                        $bp = $item->tekanan_darah;
                                        $bpKat = '';
                                        $bpBadge = 'badge-secondary';
                                        $parts = explode('/', $bp);
                                        if (count($parts) === 2) {
                                            $sys = (int) trim($parts[0]);
                                            $dia = (int) trim($parts[1]);
                                            if ($sys > 0 && $dia > 0) {
                                                if ($sys < 90 || $dia < 60) {
                                                    $bpKat = 'Rendah';
                                                    $bpBadge = 'badge-warning';
                                                } elseif ($sys < 120 && $dia < 80) {
                                                    $bpKat = 'Normal';
                                                    $bpBadge = 'badge-success';
                                                } elseif (($sys >= 120 && $sys <= 129) && $dia < 80) {
                                                    $bpKat = 'Normal Tinggi';
                                                    $bpBadge = 'badge-info';
                                                } elseif (($sys >= 130 && $sys <= 139) || ($dia >= 80 && $dia <= 89)) {
                                                    $bpKat = 'Tinggi (HT 1)';
                                                    $bpBadge = 'badge-warning';
                                                } else {
                                                    $bpKat = 'Tinggi (HT 2)';
                                                    $bpBadge = 'badge-danger';
                                                }
                                            }
                                        }
                                    @endphp
                                    <strong>{{ $item->tekanan_darah }}</strong>
                                    @if($bpKat)
                                        <br><span class="badge {{ $bpBadge }}" style="font-size:0.7rem; padding:2px 4px; margin-top:2px;">{{ $bpKat }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->kolesterol)
                                    @php
                                        $chol = $item->kolesterol;
                                        $cholKat = '';
                                        $cholBadge = 'badge-secondary';
                                        if ($chol < 200) {
                                            $cholKat = 'Normal';
                                            $cholBadge = 'badge-success';
                                        } else {
                                            $cholKat = 'Tinggi';
                                            $cholBadge = 'badge-danger';
                                        }
                                    @endphp
                                    <strong>{{ $item->kolesterol }}</strong> <small class="text-muted">mg/dL</small>
                                    @if($cholKat)
                                        <br><span class="badge {{ $cholBadge }}" style="font-size:0.7rem; padding:2px 4px; margin-top:2px;">{{ $cholKat }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->gula_darah)
                                    @php
                                        $glu = $item->gula_darah;
                                        $tipe = $item->tipe_gula_darah ?? 'sewaktu';
                                        $gluKat = '';
                                        $gluBadge = 'badge-secondary';
                                        if ($tipe === 'puasa') {
                                            if ($glu < 75) {
                                                $gluKat = 'Rendah';
                                                $gluBadge = 'badge-warning';
                                            } elseif ($glu <= 99) {
                                                $gluKat = 'Normal';
                                                $gluBadge = 'badge-success';
                                            } elseif ($glu <= 125) {
                                                $gluKat = 'Prediabetes';
                                                $gluBadge = 'badge-info';
                                            } else {
                                                $gluKat = 'Diabetes';
                                                $gluBadge = 'badge-danger';
                                            }
                                        } else {
                                            if ($glu < 140) {
                                                $gluKat = 'Normal';
                                                $gluBadge = 'badge-success';
                                            } elseif ($glu <= 199) {
                                                $gluKat = 'Prediabetes';
                                                $gluBadge = 'badge-info';
                                            } else {
                                                $gluKat = 'Diabetes';
                                                $gluBadge = 'badge-danger';
                                            }
                                        }
                                    @endphp
                                    <strong>{{ $item->gula_darah }}</strong> <small class="text-muted">mg/dL</small>
                                    <span class="text-muted" style="font-size: 0.72rem; display: block; margin-top: -2px;">({{ ucfirst($tipe) }})</span>
                                    @if($gluKat)
                                        <span class="badge {{ $gluBadge }}" style="font-size:0.7rem; padding:2px 4px; margin-top:2px;">{{ $gluKat }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($item->asam_urat)
                                    @php
                                        $uric = $item->asam_urat;
                                        $gender = 'L';
                                        if ($item->guru) {
                                            $gender = $item->guru->jenkel;
                                        } elseif ($item->karyawan) {
                                            $gender = $item->karyawan->jenkel;
                                        }
                                        $uricKat = '';
                                        $uricBadge = 'badge-secondary';
                                        if ($gender === 'P') {
                                            if ($uric < 2.4) {
                                                $uricKat = 'Rendah';
                                                $uricBadge = 'badge-warning';
                                            } elseif ($uric <= 6.0) {
                                                $uricKat = 'Normal';
                                                $uricBadge = 'badge-success';
                                            } else {
                                                $uricKat = 'Tinggi';
                                                $uricBadge = 'badge-danger';
                                            }
                                        } else {
                                            if ($uric < 2.4) {
                                                $uricKat = 'Rendah';
                                                $uricBadge = 'badge-warning';
                                            } elseif ($uric <= 7.0) {
                                                $uricKat = 'Normal';
                                                $uricBadge = 'badge-success';
                                            } else {
                                                $uricKat = 'Tinggi';
                                                $uricBadge = 'badge-danger';
                                            }
                                        }
                                    @endphp
                                    <strong>{{ $item->asam_urat }}</strong> <small class="text-muted">mg/dL</small>
                                    @if($uricKat)
                                        <br><span class="badge {{ $uricBadge }}" style="font-size:0.7rem; padding:2px 4px; margin-top:2px;">{{ $uricKat }}</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="action-cell text-center">
                                <button type="button" class="btn-icon btn-edit" title="Edit"
                                    onclick="editCheckup({{ json_encode($item) }})">
                                    <i class="fa-solid fa-pen"></i>
                                </button>
                                <button type="button" class="btn-icon btn-delete" title="Hapus"
                                    onclick="confirmDelete('{{ route('uks.checkup-gukar.destroy', $item->id_checkup) }}','Yakin hapus data check-up ini?')">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="text-center text-muted py-6">
                                <i class="fa-solid fa-file-medical" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                                Belum ada data check-up Guru & Karyawan
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($checkups->hasPages())
        <div class="card-footer">
            {{ $checkups->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ═══════ MODAL TAMBAH / EDIT ═══════ --}}
<div class="modal-overlay" id="modal-add-checkup">
    <div class="modal modal-md" style="max-width: 550px;">
        <div class="modal-header">
            <h3 id="modal-title-checkup">Tambah Data Check-Up Gukar</h3>
            <button onclick="closeModal('modal-add-checkup')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form id="form-checkup" method="POST" action="{{ route('uks.checkup-gukar.store') }}">
            @csrf
            <div id="method-field"></div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="form-label">Guru / Karyawan <span class="required">*</span></label>
                    <select name="gukar_id" id="c_gukar_id" class="form-control" required style="display:none;">
                        <option value="">-- Pilih Guru / Karyawan --</option>
                        @foreach($gurus as $g)
                            <option value="guru_{{ $g->id_guru }}" data-nama="{{ $g->nama_guru }}" data-role="Guru" data-no-id="{{ $g->no_id }}">
                                [GURU] {{ $g->nama_guru }} (NIP: {{ $g->no_id }})
                            </option>
                        @endforeach
                        @foreach($karyawans as $k)
                            <option value="karyawan_{{ $k->id_karyawan }}" data-nama="{{ $k->nama_karyawan }}" data-role="Karyawan" data-no-id="{{ $k->no_id }}">
                                [KARYAWAN] {{ $k->nama_karyawan }} (ID: {{ $k->no_id }})
                            </option>
                        @endforeach
                    </select>
                    <div id="ss-select-wrapper-c_gukar_id" class="ss-select-wrapper"></div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Tanggal Periksa <span class="required">*</span></label>
                    <input type="date" name="tanggal" id="c_tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Tinggi Badan (TB) <span class="text-muted">(cm)</span></label>
                        <input type="number" step="0.1" name="tinggi_badan" id="c_tb" class="form-control" placeholder="Contoh: 168" oninput="calculatePreviewImt()">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Berat Badan (BB) <span class="text-muted">(kg)</span></label>
                        <input type="number" step="0.1" name="berat_badan" id="c_bb" class="form-control" placeholder="Contoh: 65" oninput="calculatePreviewImt()">
                    </div>
                </div>

                {{-- Live Preview IMT & Kategori --}}
                <div id="preview-imt-box" style="margin-top: 12px; margin-bottom: 16px; padding: 12px; background: #f8fafc; border-radius: 8px; border: 1px dashed #cbd5e1; display: flex; justify-content: space-around; align-items: center;">
                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: #64748b; display: block; font-weight: 600;">IMT (Kalkulasi)</span>
                        <strong id="preview-imt-val" style="font-size: 1.2rem; color: #0284c7;">-</strong>
                    </div>
                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: #64748b; display: block; font-weight: 600;">Kategori Status Gizi</span>
                        <span id="preview-kategori-val" class="badge badge-secondary">-</span>
                    </div>
                </div>

                <hr style="border: 0; border-top: 1px solid #f1f5f9; margin: 16px 0;">

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Tekanan Darah <span class="text-muted">(mmHg)</span></label>
                        <input type="text" name="tekanan_darah" id="c_tekanan_darah" class="form-control" placeholder="Contoh: 120/80">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Kolesterol <span class="text-muted">(mg/dL)</span></label>
                        <input type="number" step="1" name="kolesterol" id="c_kolesterol" class="form-control" placeholder="Contoh: 180">
                    </div>
                </div>

                <div class="form-grid-2">
                    <div class="form-group">
                        <label class="form-label">Gula Darah <span class="text-muted">(mg/dL)</span></label>
                        <div style="display: flex; gap: 8px;">
                            <input type="number" step="1" name="gula_darah" id="c_gula_darah" class="form-control" placeholder="Contoh: 95" style="flex: 1;">
                            <select name="tipe_gula_darah" id="c_tipe_gula_darah" class="form-control" style="width: 120px;">
                                <option value="sewaktu">Sewaktu</option>
                                <option value="puasa">Puasa</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Asam Urat <span class="text-muted">(mg/dL)</span></label>
                        <input type="number" step="0.1" name="asam_urat" id="c_asam_urat" class="form-control" placeholder="Contoh: 5.8">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-add-checkup')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-save"></i> Simpan Data</button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════ MODAL IMPORT EXCEL ═══════ --}}
<div class="modal-overlay" id="modal-import">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3>Import Data Check-Up Gukar</h3>
            <button onclick="closeModal('modal-import')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <form action="{{ route('uks.checkup-gukar.import') }}" method="POST" enctype="multipart/form-data" onsubmit="showLoading('Mengimpor Data Check-Up', 'Sedang memproses file Excel, mohon tunggu...')">
            @csrf
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 20px;">
                    <label class="form-label" style="display: block;">Template Excel</label>
                    <a href="{{ route('uks.checkup-gukar.template') }}" id="btn-download-template" class="btn btn-secondary btn-sm" style="display: inline-flex; align-items: center; gap: 8px;">
                        <i class="fa-solid fa-download"></i> Download Template Excel
                    </a>
                    <small class="text-muted" style="display: block; margin-top: 4px;">Unduh template Excel yang berisi daftar Guru & Karyawan aktif.</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Tanggal Pemeriksaan <span class="text-muted">(Opsional, default hari ini jika di Excel kosong)</span></label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                </div>

                <div class="form-group" style="margin-top: 12px;">
                    <label class="form-label">Pilih File Excel <span class="required">*</span></label>
                    <input type="file" name="file" class="form-control" accept=".xlsx, .xls" required>
                    <small class="text-muted">Format yang didukung: .xlsx, .xls</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" onclick="closeModal('modal-import')" class="btn btn-secondary">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="fa-solid fa-upload"></i> Mulai Import</button>
            </div>
        </form>
    </div>
</div>

<style>
.uks-stats-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 20px;
}
.uks-stat-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px 20px;
    border-radius: 12px;
    color: #fff;
    box-shadow: 0 4px 12px rgba(0,0,0,0.06);
}
.uks-stat-icon {
    width: 48px;
    height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
}
.uks-stat-num {
    font-size: 1.6rem;
    font-weight: 700;
    line-height: 1.2;
}
.uks-stat-lbl {
    font-size: 0.85rem;
    opacity: 0.9;
}

/* CSS Searchable Select Dropdown */
.ss-select-wrapper {
    position: relative;
    width: 100%;
}
.ss-select-trigger {
    display: flex;
    justify-content: space-between;
    align-items: center;
    width: 100%;
    background: #ffffff;
    border: 1.5px solid var(--border-color, #cbd5e1);
    padding: 10px 14px;
    border-radius: 8px;
    color: var(--text-primary, #1e293b);
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
}
.ss-select-trigger:hover {
    border-color: var(--color-primary, #0ea5e9);
}
.ss-select-wrapper.active .ss-select-trigger {
    border-color: var(--color-primary, #0ea5e9);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.15);
}
.ss-select-trigger-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 8px;
    display: flex;
    align-items: center;
}
.ss-select-arrow {
    font-size: 0.8rem;
    color: #94a3b8;
    transition: transform 0.2s ease;
}
.ss-select-wrapper.active .ss-select-arrow {
    transform: rotate(180deg);
    color: var(--color-primary, #0ea5e9);
}
.ss-select-dropdown {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    width: 100%;
    background: #ffffff;
    border: 1.5px solid var(--border-color, #cbd5e1);
    border-radius: 10px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
    z-index: 999;
    display: none;
    overflow: hidden;
}
.ss-select-wrapper.active .ss-select-dropdown {
    display: block;
}
.ss-select-search-container {
    position: relative;
    padding: 10px;
    border-bottom: 1px solid #f1f5f9;
    background: #f8fafc;
}
.ss-select-search-input {
    width: 100%;
    padding: 8px 12px 8px 32px;
    border-radius: 6px;
    border: 1.5px solid #cbd5e1;
    font-size: 0.85rem;
    outline: none;
}
.ss-select-search-input:focus {
    border-color: var(--color-primary, #0ea5e9);
    box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}
.ss-select-search-icon {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    color: #94a3b8;
    font-size: 0.8rem;
}
.ss-select-options-list {
    max-height: 220px;
    overflow-y: auto;
    padding: 4px;
}
.ss-select-option {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 12px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.15s ease;
    gap: 12px;
}
.ss-select-option:hover {
    background: #f1f5f9;
}
.ss-select-option.selected {
    background: #e0f2fe;
    color: #0369a1;
    font-weight: 600;
}
.ss-select-option-left {
    display: flex;
    flex-direction: column;
}
.ss-select-option-name {
    font-size: 0.88rem;
}
.ss-select-option-no-id {
    font-size: 0.75rem;
    color: #64748b;
}
.ss-select-no-results {
    padding: 16px;
    text-align: center;
    color: #94a3b8;
    font-size: 0.85rem;
}
</style>

<script>
let customGukarSelectObj = null;

function initCustomGukarSelect() {
    const originalSelect = document.getElementById('c_gukar_id');
    const wrapper = document.getElementById('ss-select-wrapper-c_gukar_id');
    if (!originalSelect || !wrapper) return;

    wrapper.innerHTML = '';

    const trigger = document.createElement('div');
    trigger.className = 'ss-select-trigger';
    trigger.innerHTML = `
        <span class="ss-select-trigger-text">-- Pilih Guru / Karyawan --</span>
        <i class="fa-solid fa-chevron-down ss-select-arrow"></i>
    `;
    wrapper.appendChild(trigger);

    const dropdown = document.createElement('div');
    dropdown.className = 'ss-select-dropdown';

    const searchContainer = document.createElement('div');
    searchContainer.className = 'ss-select-search-container';
    searchContainer.innerHTML = `
        <input type="text" class="ss-select-search-input" placeholder="🔍 Cari nama, peran, atau ID/NIP...">
        <i class="fa-solid fa-magnifying-glass ss-select-search-icon"></i>
    `;
    dropdown.appendChild(searchContainer);

    const optionsList = document.createElement('div');
    optionsList.className = 'ss-select-options-list';
    dropdown.appendChild(optionsList);
    wrapper.appendChild(dropdown);

    const searchInput = searchContainer.querySelector('.ss-select-search-input');
    const triggerText = trigger.querySelector('.ss-select-trigger-text');

    function buildOptions() {
        optionsList.innerHTML = '';
        const options = Array.from(originalSelect.options);
        
        options.forEach((opt) => {
            if (opt.value === "") return;
            
            const nama = opt.getAttribute('data-nama') || opt.text;
            const role = opt.getAttribute('data-role') || '';
            const noId = opt.getAttribute('data-no-id') || '';
            
            const optionDiv = document.createElement('div');
            optionDiv.className = 'ss-select-option';
            if (originalSelect.value === opt.value) {
                optionDiv.classList.add('selected');
            }
            optionDiv.setAttribute('data-value', opt.value);
            optionDiv.setAttribute('data-nama', nama.toLowerCase());
            optionDiv.setAttribute('data-role', role.toLowerCase());
            optionDiv.setAttribute('data-no-id', noId.toLowerCase());
            
            const badgeClass = role === 'Guru' ? 'badge-info' : 'badge-success';
            
            optionDiv.innerHTML = `
                <div class="ss-select-option-left">
                    <span class="ss-select-option-name">${nama}</span>
                    <span class="ss-select-option-no-id">${role === 'Guru' ? 'NIP' : 'ID'}: ${noId}</span>
                </div>
                <span class="badge ${badgeClass}">${role}</span>
            `;
            
            optionDiv.addEventListener('click', (e) => {
                e.stopPropagation();
                selectValue(opt.value);
                closeDropdown();
            });
            
            optionsList.appendChild(optionDiv);
        });
    }

    function selectValue(val) {
        originalSelect.value = val;
        
        const selectedOpt = Array.from(originalSelect.options).find(opt => opt.value === val);
        if (selectedOpt && val !== "") {
            const nama = selectedOpt.getAttribute('data-nama') || selectedOpt.text;
            const role = selectedOpt.getAttribute('data-role') || '';
            const badgeClass = role === 'Guru' ? 'badge-info' : 'badge-success';
            triggerText.innerHTML = `<span style="font-weight:600;">${nama}</span> <span class="badge ${badgeClass}" style="margin-left:8px; font-size:0.75rem;">${role}</span>`;
        } else {
            triggerText.textContent = '-- Pilih Guru / Karyawan --';
        }
        
        const items = optionsList.querySelectorAll('.ss-select-option');
        items.forEach(item => {
            if (item.getAttribute('data-value') === val) {
                item.classList.add('selected');
            } else {
                item.classList.remove('selected');
            }
        });
    }

    function filterOptions() {
        const query = searchInput.value.toLowerCase().trim();
        const items = optionsList.querySelectorAll('.ss-select-option');
        let visibleCount = 0;

        items.forEach(item => {
            const nama = item.getAttribute('data-nama');
            const role = item.getAttribute('data-role');
            const noId = item.getAttribute('data-no-id');

            if (nama.includes(query) || role.includes(query) || noId.includes(query)) {
                item.style.display = 'flex';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        let noResultsMsg = optionsList.querySelector('.ss-select-no-results');
        if (visibleCount === 0) {
            if (!noResultsMsg) {
                noResultsMsg = document.createElement('div');
                noResultsMsg.className = 'ss-select-no-results';
                noResultsMsg.textContent = 'Guru / Karyawan tidak ditemukan';
                optionsList.appendChild(noResultsMsg);
            }
        } else if (noResultsMsg) {
            noResultsMsg.remove();
        }
    }

    function toggleDropdown() {
        if (wrapper.classList.contains('active')) {
            closeDropdown();
        } else {
            openDropdown();
        }
    }

    function openDropdown() {
        wrapper.classList.add('active');
        searchInput.value = '';
        filterOptions();
        setTimeout(() => searchInput.focus(), 50);
    }

    function closeDropdown() {
        wrapper.classList.remove('active');
    }

    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        toggleDropdown();
    });

    searchInput.addEventListener('click', (e) => {
        e.stopPropagation();
    });

    searchInput.addEventListener('input', filterOptions);

    document.addEventListener('click', (e) => {
        if (!wrapper.contains(e.target)) {
            closeDropdown();
        }
    });

    buildOptions();

    customGukarSelectObj = {
        setValue: selectValue,
        reset: () => selectValue('')
    };
}

document.addEventListener('DOMContentLoaded', function() {
    initCustomGukarSelect();
});

function calculatePreviewImt() {
    const tb = parseFloat(document.getElementById('c_tb').value);
    const bb = parseFloat(document.getElementById('c_bb').value);
    const imtValEl = document.getElementById('preview-imt-val');
    const katValEl = document.getElementById('preview-kategori-val');

    if (tb > 0 && bb > 0) {
        const tb_m = tb / 100;
        const imt = (bb / (tb_m * tb_m)).toFixed(1);
        imtValEl.innerText = imt;

        let kategori = 'Kurus';
        let badgeClass = 'badge-warning';

        if (imt < 18.5) {
            kategori = 'Kurus';
            badgeClass = 'badge-warning';
        } else if (imt <= 25.0) {
            kategori = 'Normal';
            badgeClass = 'badge-success';
        } else if (imt <= 27.0) {
            kategori = 'Gemuk';
            badgeClass = 'badge-warning';
        } else {
            kategori = 'Obesitas';
            badgeClass = 'badge-danger';
        }

        katValEl.innerText = kategori;
        katValEl.className = 'badge ' + badgeClass;
    } else {
        imtValEl.innerText = '-';
        katValEl.innerText = '-';
        katValEl.className = 'badge badge-secondary';
    }
}

function resetAndOpenAddModal() {
    document.getElementById('form-checkup').reset();
    document.getElementById('method-field').innerHTML = '';
    document.getElementById('form-checkup').action = '{{ route("uks.checkup-gukar.store") }}';
    document.getElementById('modal-title-checkup').textContent = 'Tambah Data Check-Up Gukar';
    document.getElementById('c_tanggal').value = '{{ date("Y-m-d") }}';
    document.getElementById('c_tipe_gula_darah').value = 'sewaktu';
    if (customGukarSelectObj) customGukarSelectObj.reset();
    calculatePreviewImt();
    openModal('modal-add-checkup');
}

function editCheckup(data) {
    document.getElementById('form-checkup').action = `/uks/checkup-gukar/${data.id_checkup}`;
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-checkup').textContent = 'Edit Data Check-Up Gukar';

    const gukarValue = data.id_guru ? `guru_${data.id_guru}` : `karyawan_${data.id_karyawan}`;
    if (customGukarSelectObj) customGukarSelectObj.setValue(gukarValue);
    
    if (data.tanggal) {
        const d = new Date(data.tanggal);
        const yyyy = d.getFullYear();
        const mm = String(d.getMonth() + 1).padStart(2, '0');
        const dd = String(d.getDate()).padStart(2, '0');
        document.getElementById('c_tanggal').value = `${yyyy}-${mm}-${dd}`;
    }

    document.getElementById('c_tb').value = data.tinggi_badan || '';
    document.getElementById('c_bb').value = data.berat_badan || '';
    document.getElementById('c_tekanan_darah').value = data.tekanan_darah || '';
    document.getElementById('c_kolesterol').value = data.kolesterol || '';
    document.getElementById('c_gula_darah').value = data.gula_darah || '';
    document.getElementById('c_tipe_gula_darah').value = data.tipe_gula_darah || 'sewaktu';
    document.getElementById('c_asam_urat').value = data.asam_urat || '';

    calculatePreviewImt();
    openModal('modal-add-checkup');
}
</script>
@endsection
