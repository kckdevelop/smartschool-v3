@extends('layouts.app')

@section('title', 'Data Check-Up Siswa — SmartSchool')
@section('header_title', 'Data Check-Up Siswa')
@section('header_subtitle', 'Pemeriksaan kesehatan berkala siswa (TB, BB, IMT & Kategori)')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Stat Cards --}}
    <div class="uks-stats-row">
        <div class="uks-stat-card" style="background: #0284c7;">
            <div class="uks-stat-icon"><i class="fa-solid fa-heart-pulse"></i></div>
            <div>
                <div class="uks-stat-num">{{ $hariIni }}</div>
                <div class="uks-stat-lbl">Check-Up Hari Ini</div>
            </div>
        </div>
        <div class="uks-stat-card" style="background: #6366f1;">
            <div class="uks-stat-icon"><i class="fa-solid fa-file-medical"></i></div>
            <div>
                <div class="uks-stat-num">{{ $totalCheckup }}</div>
                <div class="uks-stat-lbl">Total Data Pemeriksaan</div>
            </div>
        </div>
    </div>

    {{-- ── Keterangan Kategori IMT ── --}}
    <div class="card" style="margin-bottom: 20px; border-left: 4px solid #0284c7; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.04);">
        <div class="card-body" style="padding: 16px 20px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 14px;">
                <i class="fa-solid fa-circle-info" style="color: #0284c7; font-size: 1rem;"></i>
                <strong style="font-size: 0.9rem; color: var(--text-primary, #0f172a);">Keterangan Kategori Status Gizi (IMT)</strong>
                <span style="font-size: 0.8rem; color: #64748b; font-weight: 400;">— Indeks Massa Tubuh = Berat Badan (kg) ÷ [Tinggi Badan (m)]²</span>
            </div>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px;">
                <div style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #fffdf0; border-radius: 10px; border: 1px solid #fef08a;">
                    <span style="background: #fef9c3; color: #854d0e; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 0.78rem; white-space: nowrap;">Kurus</span>
                    <div>
                        <div style="font-weight: 700; color: #854d0e; font-size: 0.85rem;">IMT &lt; 18.5</div>
                        <div style="color: #78350f; font-size: 0.72rem;">Berat badan kurang dari ideal</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #f0fdf4; border-radius: 10px; border: 1px solid #bbf7d0;">
                    <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 0.78rem; white-space: nowrap;">Normal</span>
                    <div>
                        <div style="font-weight: 700; color: #166534; font-size: 0.85rem;">IMT 18.5 – 25.0</div>
                        <div style="color: #14532d; font-size: 0.72rem;">Berat badan ideal / sehat</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #fff7ed; border-radius: 10px; border: 1px solid #fed7aa;">
                    <span style="background: #ffedd5; color: #9a3412; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 0.78rem; white-space: nowrap;">Gemuk</span>
                    <div>
                        <div style="font-weight: 700; color: #9a3412; font-size: 0.85rem;">IMT 25.1 – 27.0</div>
                        <div style="color: #7c2d12; font-size: 0.72rem;">Kelebihan berat badan</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; gap: 12px; padding: 10px 14px; background: #fef2f2; border-radius: 10px; border: 1px solid #fecaca;">
                    <span style="background: #fee2e2; color: #991b1b; padding: 4px 10px; border-radius: 12px; font-weight: 700; font-size: 0.78rem; white-space: nowrap;">Obesitas</span>
                    <div>
                        <div style="font-weight: 700; color: #991b1b; font-size: 0.85rem;">IMT &gt; 27.0</div>
                        <div style="color: #7f1d1d; font-size: 0.72rem;">Obesitas, perlu perhatian medis</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card" style="border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.04); overflow: hidden;">

        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; padding: 16px 20px; flex-wrap: wrap; gap: 12px;">
            <h2 class="card-title" style="margin: 0; font-size: 1rem; font-weight: 700; color: #0f766e; display: flex; align-items: center; gap: 8px;">
                <i class="fa-solid fa-notes-medical" style="color: #0f766e;"></i> Data Check-Up Siswa
            </h2>
            <div class="card-header-right" style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                {{-- Filter Form --}}
                <form method="GET" class="search-form" style="display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari nama/NIS..." class="form-control form-control-sm" style="width: 140px; border-radius: 8px; border: 1.5px solid #cbd5e1;">
                    <select name="kelas_id" class="form-control form-control-sm" style="width: 140px; border-radius: 8px; border: 1.5px solid #cbd5e1;">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelases as $k)
                            <option value="{{ $k->id_kelas }}" {{ request('kelas_id') == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                           class="form-control form-control-sm" style="border-radius: 8px; border: 1.5px solid #cbd5e1;" title="Dari tanggal">
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                           class="form-control form-control-sm" style="border-radius: 8px; border: 1.5px solid #cbd5e1;" title="Sampai tanggal">
                    <button class="btn btn-secondary btn-sm" style="border-radius: 8px; border: 1.5px solid #cbd5e1; background: #ffffff; color: #64748b;" title="Filter"><i class="fa-solid fa-filter"></i></button>
                    @if(request()->hasAny(['search','kelas_id','tanggal_dari','tanggal_sampai']))
                        <a href="{{ route('uks.checkup.index') }}" class="btn btn-secondary btn-sm" style="border-radius: 8px;" title="Reset">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </form>
                <button class="btn btn-outline-success btn-sm" onclick="openModal('modal-import')" id="btn-import-checkup" style="display:inline-flex; align-items:center; gap:6px; border:1.5px solid #10b981; color:#10b981; background:#ffffff; font-weight:600; border-radius:8px; padding:6px 14px;">
                    <i class="fa-solid fa-file-excel"></i> Import Excel
                </button>
                <button class="btn btn-primary btn-sm" onclick="resetAndOpenAddModal()" id="btn-tambah-checkup" style="display:inline-flex; align-items:center; gap:6px; background:#6366f1; color:#ffffff; border:none; font-weight:600; border-radius:8px; padding:7px 16px; box-shadow:0 4px 12px rgba(99, 102, 241, 0.25);">
                    <i class="fa-solid fa-plus"></i> Tambah Check-Up Siswa
                </button>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; border-bottom: 1.5px solid #e2e8f0;">
                            <th style="width: 40px; text-align: center; color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 12px 10px;">#</th>
                            <th style="color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 12px 10px;">TANGGAL PERIKSA</th>
                            <th style="color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 12px 10px;">NIS</th>
                            <th style="color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 12px 10px;">NAMA</th>
                            <th style="color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 12px 10px;">KELAS</th>
                            <th style="color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 12px 10px;">TB (CM)</th>
                            <th style="color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 12px 10px;">BB (KG)</th>
                            <th style="color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 12px 10px;">IMT</th>
                            <th style="color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 12px 10px;">KATEGORI</th>
                            <th style="color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 12px 10px;">TEKANAN DARAH</th>
                            <th style="color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 12px 10px;">MEROKOK</th>
                            <th style="width: 90px; text-align: center; color: #64748b; font-size: 0.72rem; font-weight: 700; text-transform: uppercase; padding: 12px 10px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checkups as $i => $item)
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="color: #94a3b8; font-size: 0.85rem; text-align: center; padding: 12px 10px;">{{ $checkups->firstItem() + $i }}</td>
                            <td style="padding: 12px 10px;">
                                <div style="font-weight: 600; font-size: 0.88rem; color: #1e293b;">
                                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                                </div>
                            </td>
                            <td style="padding: 12px 10px;">
                                <span style="background: #e0f2fe; color: #0284c7; font-weight: 600; border-radius: 12px; padding: 3px 10px; font-size: 0.78rem; display: inline-block;">{{ $item->nis }}</span>
                            </td>
                            <td style="font-weight: 600; color: #0f172a; padding: 12px 10px; font-size: 0.88rem;">{{ $item->siswa?->nama_siswa ?? '-' }}</td>
                            <td style="padding: 12px 10px;">
                                @if($item->siswa?->kelas)
                                    <span style="background: #f1f5f9; color: #64748b; font-weight: 500; border-radius: 12px; padding: 3px 10px; font-size: 0.78rem; display: inline-block;">{{ $item->siswa->kelas->nama_kelas }}</span>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td style="padding: 12px 10px; font-size: 0.88rem;">
                                @if($item->tinggi_badan)
                                    <strong style="color: #0f172a;">{{ $item->tinggi_badan }}</strong> <small style="color: #64748b;">cm</small>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td style="padding: 12px 10px; font-size: 0.88rem;">
                                @if($item->berat_badan)
                                    <strong style="color: #0f172a;">{{ $item->berat_badan }}</strong> <small style="color: #64748b;">kg</small>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td style="padding: 12px 10px; font-size: 0.88rem;">
                                @if($item->imt)
                                    <strong style="color: #0284c7;">{{ number_format($item->imt, 1) }}</strong>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td style="padding: 12px 10px;">
                                @if($item->kategori)
                                    @php
                                        $kat = strtolower($item->kategori);
                                        $bg = '#f1f5f9'; $fg = '#64748b';
                                        if (str_contains($kat, 'kurus')) {
                                            $bg = '#fef9c3'; $fg = '#854d0e';
                                        } elseif (str_contains($kat, 'normal')) {
                                            $bg = '#dcfce7'; $fg = '#166534';
                                        } elseif (str_contains($kat, 'gemuk')) {
                                            $bg = '#ffedd5'; $fg = '#9a3412';
                                        } elseif (str_contains($kat, 'obesitas')) {
                                            $bg = '#fee2e2'; $fg = '#991b1b';
                                        }
                                    @endphp
                                    <span style="background: {{ $bg }}; color: {{ $fg }}; padding: 4px 12px; border-radius: 12px; font-weight: 600; font-size: 0.75rem; display: inline-block;">{{ $item->kategori }}</span>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td style="padding: 12px 10px; font-size: 0.88rem;">
                                @if($item->tekanan_darah)
                                    <strong style="color: #334155;">{{ $item->tekanan_darah }}</strong>
                                @else
                                    <span style="color: #94a3b8;">-</span>
                                @endif
                            </td>
                            <td style="padding: 12px 10px;">
                                @if($item->is_merokok == 'Ya')
                                    <span style="background: #fee2e2; color: #dc2626; padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 4px;"><i class="fa-solid fa-smoking"></i> Ya</span>
                                @else
                                    <span style="background: #dcfce7; color: #166534; padding: 4px 10px; border-radius: 12px; font-weight: 600; font-size: 0.75rem; display: inline-flex; align-items: center; gap: 4px;"><i class="fa-solid fa-ban-smoking"></i> Tidak</span>
                                @endif
                            </td>
                            <td style="padding: 12px 10px; text-align: center;">
                                <div style="display: inline-flex; gap: 6px; align-items: center; justify-content: center;">
                                    <button type="button" title="Edit" onclick="editCheckup({{ json_encode($item) }})" style="background: #e0e7ff; color: #6366f1; border: none; border-radius: 8px; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;">
                                        <i class="fa-solid fa-pen" style="font-size: 0.85rem;"></i>
                                    </button>
                                    <button type="button" title="Hapus" onclick="confirmDelete('{{ route('uks.checkup.destroy', $item->id_checkup) }}','Yakin hapus data check-up ini?')" style="background: #fee2e2; color: #ef4444; border: none; border-radius: 8px; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;">
                                        <i class="fa-solid fa-trash" style="font-size: 0.85rem;"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" style="text-align: center; color: #94a3b8; padding: 40px 10px;">
                                <i class="fa-solid fa-file-medical" style="font-size: 2.2rem; opacity: .3; display: block; margin-bottom: 8px;"></i>
                                Belum ada data check-up siswa
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($checkups->hasPages())
        <div class="card-footer" style="padding: 12px 20px; border-top: 1px solid #f1f5f9;">
            {{ $checkups->links() }}
        </div>
        @endif
    </div>
</div>

{{-- ═══════ MODAL TAMBAH / EDIT ═══════ --}}
<div class="modal-overlay" id="modal-add-checkup">
    <div class="modal modal-md" style="border-radius: 16px; overflow: hidden; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid #f1f5f9;">
            <h3 id="modal-title-checkup" style="color: #0f766e; font-weight: 700; font-size: 1.15rem; margin: 0;">Tambah Data Check-Up Siswa</h3>
            <button onclick="closeModal('modal-add-checkup')" class="modal-close" style="background: #f1f5f9; color: #0f766e; border-radius: 8px; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border: none; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form id="form-checkup" method="POST" action="{{ route('uks.checkup.store') }}">
            @csrf
            <div id="method-field"></div>
            <div class="modal-body" style="padding: 20px 24px;">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="color: #0f766e; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.03em; display: block; margin-bottom: 6px;">
                        SISWA <span style="color: #ef4444;">*</span>
                    </label>
                    <select name="nis" id="c_nis" class="form-control" required style="display:none;">
                        <option value="">-- Pilih Siswa --</option>
                        @foreach($siswaDaftar as $s)
                            <option value="{{ $s->nis }}" data-nama="{{ $s->nama_siswa }}" data-kelas="{{ $s->kelas?->nama_kelas ?? '-' }}" data-nis="{{ $s->nis }}">
                                {{ $s->nis }} — {{ $s->nama_siswa }} ({{ $s->kelas?->nama_kelas ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                    <div id="ss-select-wrapper-c_nis" class="ss-select-wrapper"></div>
                </div>
                
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="color: #0f766e; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.03em; display: block; margin-bottom: 6px;">
                        TANGGAL PERIKSA <span style="color: #ef4444;">*</span>
                    </label>
                    <div style="position: relative;">
                        <input type="date" name="tanggal" id="c_tanggal" class="form-control" value="{{ date('Y-m-d') }}" required style="border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 10px 14px; width: 100%;">
                    </div>
                </div>

                <div class="form-grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label class="form-label" style="color: #0f766e; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.03em; display: block; margin-bottom: 6px;">
                            TINGGI BADAN (TB) <span style="color: #64748b; font-weight: 600;">(CM)</span>
                        </label>
                        <input type="number" step="0.1" name="tinggi_badan" id="c_tb" class="form-control" placeholder="Contoh: 165" oninput="calculatePreviewImt()" style="border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 10px 14px; width: 100%;">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="color: #0f766e; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.03em; display: block; margin-bottom: 6px;">
                            BERAT BADAN (BB) <span style="color: #64748b; font-weight: 600;">(KG)</span>
                        </label>
                        <input type="number" step="0.1" name="berat_badan" id="c_bb" class="form-control" placeholder="Contoh: 55" oninput="calculatePreviewImt()" style="border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 10px 14px; width: 100%;">
                    </div>
                </div>

                <div class="form-grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-bottom: 16px;">
                    <div class="form-group">
                        <label class="form-label" style="color: #0f766e; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.03em; display: block; margin-bottom: 6px;">
                            TEKANAN DARAH <span style="color: #64748b; font-weight: 600;">(MMHG)</span>
                        </label>
                        <input type="text" name="tekanan_darah" id="c_tekanan_darah" class="form-control" placeholder="Contoh: 120/80" style="border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 10px 14px; width: 100%;">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="color: #0f766e; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.03em; display: block; margin-bottom: 6px;">
                            STATUS MEROKOK
                        </label>
                        <select name="is_merokok" id="c_is_merokok" class="form-control" style="border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 10px 14px; width: 100%;">
                            <option value="Tidak">Tidak</option>
                            <option value="Ya">Ya (Merokok)</option>
                        </select>
                    </div>
                </div>

                {{-- Live Preview IMT & Kategori --}}
                <div id="preview-imt-box" style="margin-top: 18px; padding: 16px; background: #ffffff; border-radius: 10px; border: 1.5px dashed #cbd5e1; display: flex; justify-content: space-around; align-items: center;">
                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: #64748b; display: block; font-weight: 600;">IMT (Kalkulasi)</span>
                        <strong id="preview-imt-val" style="font-size: 1.3rem; color: #0284c7; display: block; margin-top: 2px;">-</strong>
                    </div>
                    <div style="text-align: center;">
                        <span style="font-size: 0.75rem; color: #64748b; display: block; font-weight: 600;">Kategori Status Gizi</span>
                        <span id="preview-kategori-val" style="font-weight: 600; font-size: 0.95rem; color: #64748b; display: inline-block; margin-top: 2px;">-</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px; padding: 16px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9;">
                <button type="button" onclick="closeModal('modal-add-checkup')" class="btn" style="background: #f1f5f9; color: #0f766e; border-radius: 8px; font-weight: 600; padding: 9px 20px; border: none; font-size: 0.9rem;">Batal</button>
                <button type="submit" class="btn" style="background: #6366f1; color: #ffffff; border-radius: 8px; font-weight: 600; padding: 9px 22px; border: none; font-size: 0.9rem; box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35); display: inline-flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-floppy-disk"></i> Simpan Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ═══════ MODAL IMPORT EXCEL ═══════ --}}
<div class="modal-overlay" id="modal-import">
    <div class="modal modal-md" style="border-radius: 16px; overflow: hidden; max-width: 500px;">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; padding: 18px 24px; border-bottom: 1px solid #f1f5f9;">
            <h3 style="color: #0f766e; font-weight: 700; font-size: 1.15rem; margin: 0;">Import Data Check-Up Siswa</h3>
            <button onclick="closeModal('modal-import')" class="modal-close" style="background: #f1f5f9; color: #0f766e; border-radius: 8px; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; border: none; cursor: pointer;">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <form action="{{ route('uks.checkup.import') }}" method="POST" enctype="multipart/form-data" onsubmit="showLoading('Mengimpor Data Check-Up', 'Sedang memproses file Excel, mohon tunggu...')">
            @csrf
            <div class="modal-body" style="padding: 20px 24px;">
                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="color: #0f766e; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; display: block; margin-bottom: 6px;">Pilih Kelas <span style="color: #ef4444;">*</span></label>
                    <select name="id_kelas" id="import_id_kelas" class="form-control" required onchange="updateTemplateLink()" style="border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 10px 14px; width: 100%;">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelases as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="display: block; color: #0f766e; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; margin-bottom: 6px;">Template Excel</label>
                    <a href="#" id="btn-download-template" class="btn btn-secondary btn-sm disabled" style="display: inline-flex; align-items: center; gap: 8px; pointer-events: none; opacity: 0.5; border-radius: 8px;">
                        <i class="fa-solid fa-download"></i> Download Template Excel
                    </a>
                    <small class="text-muted" style="display: block; margin-top: 4px; font-size: 0.75rem;">Pilih kelas terlebih dahulu untuk mengunduh template yang sesuai.</small>
                </div>

                <div class="form-group" style="margin-bottom: 16px;">
                    <label class="form-label" style="color: #0f766e; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; display: block; margin-bottom: 6px;">Tanggal Pemeriksaan <span style="color: #64748b; font-weight: 400; text-transform: none;">(Opsional, default hari ini jika di Excel kosong)</span></label>
                    <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" style="border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 10px 14px; width: 100%;">
                </div>

                <div class="form-group">
                    <label class="form-label" style="color: #0f766e; font-weight: 700; font-size: 0.78rem; text-transform: uppercase; display: block; margin-bottom: 6px;">Pilih File Excel <span style="color: #ef4444;">*</span></label>
                    <input type="file" name="file" class="form-control" accept=".xlsx, .xls" required style="border-radius: 10px; border: 1.5px solid #cbd5e1; padding: 8px 12px; width: 100%;">
                    <small class="text-muted" style="font-size: 0.75rem;">Format yang didukung: .xlsx, .xls</small>
                </div>
            </div>
            <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px; padding: 16px 24px; background: #f8fafc; border-top: 1px solid #f1f5f9;">
                <button type="button" onclick="closeModal('modal-import')" class="btn" style="background: #f1f5f9; color: #0f766e; border-radius: 8px; font-weight: 600; padding: 9px 20px; border: none; font-size: 0.9rem;">Batal</button>
                <button type="submit" class="btn" style="background: #6366f1; color: #ffffff; border-radius: 8px; font-weight: 600; padding: 9px 22px; border: none; font-size: 0.9rem; box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35); display: inline-flex; align-items: center; gap: 8px;"><i class="fa-solid fa-upload"></i> Mulai Import</button>
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

/* Custom Select Dropdown */
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
    border: 1.5px solid #cbd5e1;
    border-radius: 10px;
    padding: 10px 14px;
    color: var(--text-primary, #1e293b);
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.2s ease;
    user-select: none;
}
.ss-select-trigger:hover {
    border-color: #0284c7;
}
.ss-select-wrapper.active .ss-select-trigger {
    border-color: #0284c7;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.15);
}
.ss-select-trigger-text {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-right: 8px;
    display: flex;
    align-items: center;
    color: #94a3b8;
}
.ss-select-arrow {
    font-size: 0.8rem;
    color: #94a3b8;
    transition: transform 0.2s ease;
}
.ss-select-wrapper.active .ss-select-arrow {
    transform: rotate(180deg);
    color: #0284c7;
}
.ss-select-dropdown {
    position: absolute;
    top: calc(100% + 4px);
    left: 0;
    width: 100%;
    background: #ffffff;
    border: 1.5px solid #cbd5e1;
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
    border-color: #0284c7;
    box-shadow: 0 0 0 3px rgba(2, 132, 199, 0.1);
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
.ss-select-option-nis {
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
let customSiswaSelectObj = null;

function initCustomSiswaSelect() {
    const originalSelect = document.getElementById('c_nis');
    const wrapper = document.getElementById('ss-select-wrapper-c_nis');
    if (!originalSelect || !wrapper) return;

    wrapper.innerHTML = '';

    const trigger = document.createElement('div');
    trigger.className = 'ss-select-trigger';
    trigger.innerHTML = `
        <span class="ss-select-trigger-text">-- Pilih Siswa --</span>
        <i class="fa-solid fa-chevron-down ss-select-arrow"></i>
    `;
    wrapper.appendChild(trigger);

    const dropdown = document.createElement('div');
    dropdown.className = 'ss-select-dropdown';

    const searchContainer = document.createElement('div');
    searchContainer.className = 'ss-select-search-container';
    searchContainer.innerHTML = `
        <input type="text" class="ss-select-search-input" placeholder="🔍 Cari nama, NIS, atau kelas...">
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
            const kelas = opt.getAttribute('data-kelas') || '';
            const nis = opt.getAttribute('data-nis') || opt.value;
            
            const optionDiv = document.createElement('div');
            optionDiv.className = 'ss-select-option';
            if (originalSelect.value === opt.value) {
                optionDiv.classList.add('selected');
            }
            optionDiv.setAttribute('data-value', opt.value);
            optionDiv.setAttribute('data-nama', nama.toLowerCase());
            optionDiv.setAttribute('data-kelas', kelas.toLowerCase());
            optionDiv.setAttribute('data-nis', nis.toLowerCase());
            
            optionDiv.innerHTML = `
                <div class="ss-select-option-left">
                    <span class="ss-select-option-name">${nama}</span>
                    <span class="ss-select-option-nis">NIS: ${nis}</span>
                </div>
                ${kelas && kelas !== '-' ? `<span style="background: #f1f5f9; color: #64748b; font-size:0.75rem; padding: 2px 8px; border-radius:10px;">${kelas}</span>` : ''}
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
            const kelas = selectedOpt.getAttribute('data-kelas') || '';
            triggerText.innerHTML = `<span style="font-weight:600; color:#1e293b;">${nama}</span> ${kelas && kelas !== '-' ? `<span style="background:#f1f5f9; color:#64748b; margin-left:8px; font-size:0.75rem; padding:2px 8px; border-radius:10px;">${kelas}</span>` : ''}`;
        } else {
            triggerText.innerHTML = '<span style="color:#94a3b8;">-- Pilih Siswa --</span>';
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
            const kelas = item.getAttribute('data-kelas');
            const nis = item.getAttribute('data-nis');

            if (nama.includes(query) || kelas.includes(query) || nis.includes(query)) {
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
                noResultsMsg.textContent = 'Siswa tidak ditemukan';
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

    customSiswaSelectObj = {
        setValue: selectValue,
        reset: () => selectValue('')
    };
}

document.addEventListener('DOMContentLoaded', function() {
    initCustomSiswaSelect();
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

        let kategori = 'Normal';
        let bg = '#dcfce7';
        let fg = '#166534';

        if (imt < 18.5) {
            kategori = 'Kurus';
            bg = '#fef9c3'; fg = '#854d0e';
        } else if (imt <= 25.0) {
            kategori = 'Normal';
            bg = '#dcfce7'; fg = '#166534';
        } else if (imt <= 27.0) {
            kategori = 'Gemuk';
            bg = '#ffedd5'; fg = '#9a3412';
        } else {
            kategori = 'Obesitas';
            bg = '#fee2e2'; fg = '#991b1b';
        }

        katValEl.innerText = kategori;
        katValEl.style.color = fg;
    } else {
        imtValEl.innerText = '-';
        katValEl.innerText = '-';
        katValEl.style.color = '#64748b';
    }
}

function resetAndOpenAddModal() {
    document.getElementById('form-checkup').reset();
    document.getElementById('method-field').innerHTML = '';
    document.getElementById('form-checkup').action = '{{ route("uks.checkup.store") }}';
    document.getElementById('modal-title-checkup').textContent = 'Tambah Data Check-Up Siswa';
    document.getElementById('c_tanggal').value = '{{ date("Y-m-d") }}';
    document.getElementById('c_tekanan_darah').value = '';
    document.getElementById('c_is_merokok').value = 'Tidak';
    if (customSiswaSelectObj) customSiswaSelectObj.reset();
    calculatePreviewImt();
    openModal('modal-add-checkup');
}

function editCheckup(data) {
    document.getElementById('form-checkup').action = `/uks/checkup/${data.id_checkup}`;
    document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
    document.getElementById('modal-title-checkup').textContent = 'Edit Data Check-Up Siswa';

    if (customSiswaSelectObj) customSiswaSelectObj.setValue(String(data.nis));
    
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
    document.getElementById('c_is_merokok').value = data.is_merokok || 'Tidak';

    calculatePreviewImt();
    openModal('modal-add-checkup');
}

function updateTemplateLink() {
    const classId = document.getElementById('import_id_kelas').value;
    const downloadBtn = document.getElementById('btn-download-template');
    if (classId) {
        downloadBtn.href = `/uks/checkup/template/${classId}`;
        downloadBtn.classList.remove('disabled');
        downloadBtn.style.pointerEvents = 'auto';
        downloadBtn.style.opacity = '1';
    } else {
        downloadBtn.href = '#';
        downloadBtn.classList.add('disabled');
        downloadBtn.style.pointerEvents = 'none';
        downloadBtn.style.opacity = '0.5';
    }
}
</script>
@endsection
