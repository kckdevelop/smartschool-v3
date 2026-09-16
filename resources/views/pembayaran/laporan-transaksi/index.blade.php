@extends('layouts.app')

@section('title', 'Laporan Transaksi VA — SmartSchool')
@section('header_title', 'Laporan Transaksi VA')
@section('header_subtitle', 'Data tagihan dan riwayat transaksi Virtual Account yang telah tersimpan di database lokal')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- ══════════════════════════════════════════════════════════════
         BANNER INFORMASI WAKTU DATA DITARIK / DISINKRONKAN
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="card mb-4" style="border: 1px solid #e2e8f0; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
        <div style="padding: 18px 24px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 16px; background: linear-gradient(to right, #f8fafc, #eff6ff); border-bottom: 1px solid #e2e8f0;">
            <div style="display: flex; align-items: center; gap: 16px;">
                <div style="width: 48px; height: 48px; border-radius: 12px; background: #dbeafe; color: #1d4ed8; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; box-shadow: 0 2px 8px rgba(29,78,216,0.15);">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <span style="font-weight: 800; font-size: 1.05rem; color: #0f172a;">Sumber Data: Database SmartSchool</span>
                        <span class="badge" style="background: #10b981; color: #fff; font-size: 0.72rem; padding: 4px 10px; border-radius: 20px; font-weight: 700;">
                            <i class="fa-solid fa-circle-check"></i> Tersimpan Lokal
                        </span>
                    </div>
                    <div style="font-size: 0.88rem; color: #334155; margin-top: 4px; display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <span><i class="fa-regular fa-calendar-check" style="color: #2563eb;"></i> <strong>Data Terakhir Ditarik / Disinkronkan:</strong></span>
                        @if($lastPullTime)
                            <span class="badge" style="background: #e0f2fe; color: #0369a1; font-size: 0.85rem; padding: 4px 10px; border-radius: 6px; font-weight: 700; border: 1px solid #bae6fd;">
                                <i class="fa-regular fa-clock"></i> {{ $lastPullTime }}
                            </span>
                            <span style="font-size: 0.8rem; color: #64748b;">({{ $lastPullAgo }})</span>
                            @if($lastPullKet)
                                <span style="font-size: 0.78rem; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; color: #475569; border: 1px solid #e2e8f0;">
                                    {{ $lastPullKet }}
                                </span>
                            @endif
                        @else
                            <span class="badge bg-secondary" style="font-size: 0.8rem;">Belum pernah ditarik</span>
                        @endif
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmClearDatabase()" style="display: flex; align-items: center; gap: 6px; font-weight: 600; padding: 8px 14px; border-radius: 8px;" title="Kosongkan seluruh data tagihan & riwayat transaksi di database lokal">
                    <i class="fa-solid fa-trash-can"></i> <span>Kosongkan Tabel DB</span>
                </button>
                <a href="{{ route('pembayaran.tarik-data.index') }}" class="btn btn-sm btn-primary" style="display: flex; align-items: center; gap: 8px; font-weight: 700; padding: 9px 16px; background: #2563eb; border-color: #2563eb; border-radius: 8px; box-shadow: 0 2px 6px rgba(37,99,235,0.25);">
                    <i class="fa-solid fa-cloud-arrow-down"></i> <span>Tarik / Perbarui Data Baru</span>
                </a>
                <a href="{{ request()->fullUrlWithQuery(['export' => 'csv']) }}" class="btn btn-sm btn-outline-success" style="display: flex; align-items: center; gap: 6px; font-weight: 600; padding: 8px 14px; border-radius: 8px;">
                    <i class="fa-solid fa-file-excel"></i> <span>Export Excel (CSV)</span>
                </a>
                <button type="button" onclick="window.print()" class="btn btn-sm btn-outline-secondary" style="display: flex; align-items: center; gap: 6px; font-weight: 600; padding: 8px 14px; border-radius: 8px;">
                    <i class="fa-solid fa-print"></i> <span>Cetak</span>
                </button>
            </div>
        </div>
    </div>



    {{-- ══════════════════════════════════════════════════════════════
         FORM FILTER DATA
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="card mb-4" style="border: 1px solid #e2e8f0; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.03); overflow: hidden;">
        <div class="card-header" style="background: #ffffff; padding: 14px 20px; border-bottom: 1px solid #f1f5f9; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 8px; font-weight: 700; color: #1e293b; font-size: 0.95rem;">
                <i class="fa-solid fa-filter" style="color: #2563eb;"></i>
                <span>Filter Laporan Transaksi</span>
            </div>
            <span style="font-size: 0.8rem; color: #64748b;">Menampilkan <strong>{{ $tagihanList->total() }}</strong> baris data</span>
        </div>
        <div class="card-body" style="padding: 18px 20px;">
            <form method="GET" action="{{ route('pembayaran.laporan-transaksi.index') }}" id="filter-form">
                <div style="display: flex; flex-wrap: wrap; margin: -8px;">
                    <div style="flex: 1 1 180px; padding: 8px;">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; color: #334155;">Jenis Tagihan</label>
                        <select name="jenis_tagihan" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Jenis --</option>
                            <option value="spp" {{ $jenisTagihan === 'spp' ? 'selected' : '' }}>SPP (Kode {{ $setting->kode_spp ?? '00' }})</option>
                            <option value="non_spp" {{ $jenisTagihan === 'non_spp' ? 'selected' : '' }}>Non SPP (Kode {{ $setting->kode_non_spp ?? '01' }})</option>
                            <option value="tunggakan" {{ $jenisTagihan === 'tunggakan' ? 'selected' : '' }}>Tunggakan (Kode {{ $setting->kode_tunggakan ?? '02' }})</option>
                        </select>
                    </div>

                    <div style="flex: 1 1 180px; padding: 8px;">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; color: #334155;">Kelas / Rombel</label>
                        <select name="id_kelas" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($kelasList as $k)
                                <option value="{{ $k->id_kelas }}" {{ (string)$idKelas === (string)$k->id_kelas ? 'selected' : '' }}>
                                    {{ $k->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="flex: 1 1 160px; padding: 8px;">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; color: #334155;">Status Bayar</label>
                        <select name="status" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Status --</option>
                            <option value="lunas" {{ $status === 'lunas' ? 'selected' : '' }}>Lunas</option>
                            <option value="sebagian" {{ $status === 'sebagian' ? 'selected' : '' }}>Sebagian (Cicil)</option>
                            <option value="belum_bayar" {{ $status === 'belum_bayar' ? 'selected' : '' }}>Belum Bayar</option>
                        </select>
                    </div>

                    <div style="flex: 1 1 160px; padding: 8px;">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; color: #334155;">Tahun Ajaran</label>
                        <select name="id_tahun" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Tahun --</option>
                            @foreach($tahunList as $th)
                                <option value="{{ $th->id_tahun }}" {{ (string)$idTahun === (string)$th->id_tahun ? 'selected' : '' }}>
                                    {{ $th->tahun_ajaran }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="flex: 1 1 150px; padding: 8px;">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; color: #334155;">Dari Tanggal</label>
                        <input type="date" name="tgl_dari" class="form-control form-control-sm" value="{{ $tglDari }}">
                    </div>

                    <div style="flex: 1 1 150px; padding: 8px;">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; color: #334155;">Sampai Tanggal</label>
                        <input type="date" name="tgl_sampai" class="form-control form-control-sm" value="{{ $tglSampai }}">
                    </div>

                    <div style="flex: 1 1 220px; padding: 8px;">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; color: #334155;">Cari NIS / Siswa / VA / TRX</label>
                        <div class="input-group input-group-sm">
                            <input type="text" name="search" class="form-control" placeholder="Ketik kata kunci..." value="{{ $search }}">
                            <button class="btn btn-primary" type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>

                    <div style="flex: 1 1 110px; padding: 8px;">
                        <label class="form-label" style="font-weight: 600; font-size: 0.82rem; color: #334155;">Per Halaman</label>
                        <select name="per_page" class="form-control form-control-sm" onchange="this.form.submit()">
                            <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                            <option value="250" {{ $perPage == 250 ? 'selected' : '' }}>250</option>
                        </select>
                    </div>
                </div>

                <div style="margin-top: 12px; display: flex; justify-content: flex-end; gap: 8px;">
                    <a href="{{ route('pembayaran.laporan-transaksi.index') }}" class="btn btn-sm btn-secondary" style="font-weight: 600;">
                        <i class="fa-solid fa-rotate-left"></i> Reset Filter
                    </a>
                    <button type="submit" class="btn btn-sm btn-primary" style="font-weight: 700; background: #2563eb; border-color: #2563eb;">
                        <i class="fa-solid fa-filter"></i> Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
         TABEL LAPORAN TRANSAKSI VA
    ═══════════════════════════════════════════════════════════════ --}}
    <div class="card mb-4" style="border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
        <div class="card-header" style="background: #ffffff; padding: 16px 20px; border-bottom: 1px solid #edf2f7; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-table-list" style="color: #2563eb; font-size: 1.2rem;"></i>
                <div>
                    <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: #1e293b;">Daftar Transaksi Tagihan VA</h3>
                    <span style="font-size: 0.8rem; color: #64748b;">
                        Menampilkan {{ $tagihanList->firstItem() ?? 0 }} - {{ $tagihanList->lastItem() ?? 0 }} dari {{ number_format($tagihanList->total()) }} data
                    </span>
                </div>
            </div>
            <div style="display: flex; gap: 8px; align-items: center;">
                <span class="badge" style="background: #f1f5f9; color: #475569; font-size: 0.8rem; padding: 6px 12px; border: 1px solid #e2e8f0;">
                    <i class="fa-solid fa-database"></i> Database SmartSchool
                </span>
            </div>
        </div>

        <div class="card-body" style="padding: 0;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th style="width: 50px; text-align: center;">NO</th>
                            <th style="min-width: 170px;">TRX ID</th>
                            <th style="min-width: 220px;">SISWA & KELAS</th>
                            <th style="min-width: 200px;">TAGIHAN & NO. VA</th>
                            <th style="min-width: 130px; text-align: right;">NOMINAL</th>
                            <th style="min-width: 130px; text-align: right;">TERBAYAR</th>
                            <th style="min-width: 130px; text-align: right;">SISA</th>
                            <th style="min-width: 110px; text-align: center;">STATUS</th>
                            <th style="min-width: 140px;">TGL BAYAR</th>
                            <th style="min-width: 160px;">WAKTU DITARIK</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tagihanList as $index => $item)
                            @php
                                $sisa = $item->sisa_pembayaran;
                                $isLunas = $item->status === 'lunas';
                                $isSebagian = !$isLunas && (float)$item->nominal_terbayar > 0;
                                $namaSiswa = $item->siswa?->nama_siswa ?? $item->nama_tagihan;
                                $kelasNama = $item->kelas?->nama_kelas ?? ($item->siswa?->kelas?->nama_kelas ?? '-');
                            @endphp
                            <tr>
                                <td style="text-align: center; color: #64748b; font-weight: 600;">
                                    {{ $tagihanList->firstItem() + $index }}
                                </td>
                                <td>
                                    @if(!empty($item->trx_id))
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            <code style="background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-size: 0.8rem; color: #0f172a; font-weight: 700;">{{ $item->trx_id }}</code>
                                            <button type="button" onclick="navigator.clipboard.writeText('{{ $item->trx_id }}'); alert('TRX ID disalin!');" title="Salin TRX ID" style="border: none; background: transparent; cursor: pointer; color: #94a3b8; padding: 2px;">
                                                <i class="fa-regular fa-copy"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 700; color: #1e293b;">{{ $namaSiswa }}</div>
                                    <div style="font-size: 0.78rem; color: #64748b; display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                                        <span>NIS: <strong style="color: #334155;">{{ $item->nis }}</strong></span>
                                        <span>•</span>
                                        <span class="badge" style="background: #e2e8f0; color: #334155; font-size: 0.72rem; padding: 2px 6px;">{{ $kelasNama }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #1e293b;">{{ $item->nama_tagihan }}</div>
                                    <div style="display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                                        @if($item->jenis_tagihan === 'spp')
                                            <span class="badge" style="background: #e0f2fe; color: #0369a1; font-size: 0.72rem;">SPP</span>
                                        @elseif($item->jenis_tagihan === 'non_spp')
                                            <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 0.72rem;">Non SPP</span>
                                        @else
                                            <span class="badge" style="background: #fee2e2; color: #b91c1c; font-size: 0.72rem;">Tunggakan</span>
                                        @endif
                                        <span style="font-size: 0.8rem; font-family: monospace; color: #475569;">{{ $item->nomor_va }}</span>
                                    </div>
                                </td>
                                <td style="text-align: right; font-weight: 700; color: #1e293b;">
                                    Rp {{ number_format($item->nominal, 0, ',', '.') }}
                                </td>
                                <td style="text-align: right; font-weight: 700; color: {{ (float)$item->nominal_terbayar > 0 ? '#15803d' : '#94a3b8' }};">
                                    Rp {{ number_format($item->nominal_terbayar, 0, ',', '.') }}
                                </td>
                                <td style="text-align: right; font-weight: 700; color: {{ $sisa > 0 ? '#b91c1c' : '#15803d' }};">
                                    Rp {{ number_format($sisa, 0, ',', '.') }}
                                </td>
                                <td style="text-align: center;">
                                    @if($isLunas)
                                        <span class="badge" style="background: #dcfce7; color: #15803d; font-weight: 700; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem;">
                                            <i class="fa-solid fa-circle-check"></i> Lunas
                                        </span>
                                    @elseif($isSebagian)
                                        <span class="badge" style="background: #fef3c7; color: #92400e; font-weight: 700; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem;">
                                            <i class="fa-solid fa-clock"></i> Sebagian
                                        </span>
                                    @else
                                        <span class="badge" style="background: #fee2e2; color: #b91c1c; font-weight: 700; padding: 4px 10px; border-radius: 6px; font-size: 0.78rem;">
                                            <i class="fa-solid fa-circle-xmark"></i> Belum Bayar
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->tanggal_bayar)
                                        <div style="font-weight: 600; color: #15803d;">
                                            {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('d/m/Y') }}
                                        </div>
                                        <div style="font-size: 0.75rem; color: #64748b;">
                                            {{ \Carbon\Carbon::parse($item->tanggal_bayar)->format('H:i') }} WIB
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div style="font-weight: 600; color: #334155; font-size: 0.8rem;">
                                        <i class="fa-regular fa-clock" style="color: #64748b;"></i>
                                        {{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') : '-' }}
                                    </div>
                                    @if(!empty($item->keterangan))
                                        <div style="font-size: 0.74rem; color: #94a3b8; max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $item->keterangan }}">
                                            {{ $item->keterangan }}
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" style="text-align: center; padding: 48px 20px;">
                                    <div style="font-size: 2.5rem; color: #cbd5e1; margin-bottom: 12px;">
                                        <i class="fa-solid fa-folder-open"></i>
                                    </div>
                                    <h4 style="font-size: 1.05rem; font-weight: 700; color: #475569; margin-bottom: 6px;">Tidak Ada Data Tagihan Ditemukan</h4>
                                    <p style="font-size: 0.85rem; color: #94a3b8; max-width: 450px; margin: 0 auto 16px;">
                                        Belum ada data tagihan yang sesuai dengan filter atau database masih kosong. Anda dapat menarik data terbaru dari portal BPD DIY.
                                    </p>
                                    <a href="{{ route('pembayaran.tarik-data.index') }}" class="btn btn-sm btn-primary" style="font-weight: 600;">
                                        <i class="fa-solid fa-cloud-arrow-down"></i> Tarik Data dari Portal BPD DIY
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($tagihanList->hasPages())
            <div class="card-footer" style="background: #ffffff; padding: 14px 20px; border-top: 1px solid #edf2f7; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <div style="font-size: 0.82rem; color: #64748b;">
                    Halaman <strong>{{ $tagihanList->currentPage() }}</strong> dari <strong>{{ $tagihanList->lastPage() }}</strong>
                </div>
                <div>
                    {{ $tagihanList->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

{{-- ══════════════════════════════════════════════════════
     MODAL KONFIRMASI KOSONGKAN DATABASE
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalConfirmClearDb" tabindex="-1" aria-labelledby="modalConfirmClearDbLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div class="modal-header" style="background: linear-gradient(135deg, #b91c1c, #ef4444); color: #fff; padding: 16px 20px;">
                <h5 class="modal-title" id="modalConfirmClearDbLabel" style="font-weight: 700; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Konfirmasi Kosongkan Database Laporan</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 22px; text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 16px;">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #1e293b; margin-bottom: 8px;">
                    Kosongkan Seluruh Tabel Laporan Transaksi?
                </h4>
                <p style="font-size: 0.88rem; color: #64748b; margin-bottom: 16px; line-height: 1.5;">
                    Tindakan ini akan menghapus <strong>seluruh data tagihan dan riwayat transaksi</strong> yang tersimpan pada database lokal SmartSchool (saat ini terdapat <strong>{{ number_format($tagihanList->total()) }}</strong> data).
                </p>
                <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 8px; padding: 10px 14px; font-size: 0.82rem; color: #9f1239; font-weight: 600; text-align: left;">
                    <i class="fa-solid fa-circle-info"></i> Data pada server BPD DIY tetap aman. Anda dapat menarik ulang data tagihan kapan saja melalui menu Tarik Data.
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; padding: 14px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-weight: 600;">
                    Batal
                </button>
                <button type="button" id="btn-confirm-clear-db" onclick="executeClearDatabase()" class="btn btn-danger btn-sm" style="font-weight: 700; padding: 8px 18px;">
                    <i class="fa-solid fa-trash-can"></i> Ya, Kosongkan Sekarang
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmClearDatabase() {
        const modalEl = document.getElementById('modalConfirmClearDb');
        if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
            const modal = new bootstrap.Modal(modalEl);
            modal.show();
        } else if (typeof $ !== 'undefined' && $(modalEl).modal) {
            $(modalEl).modal('show');
        } else {
            if (confirm('Apakah Anda yakin ingin mengosongkan seluruh data laporan transaksi pada database lokal?')) {
                executeClearDatabase();
            }
        }
    }

    function executeClearDatabase() {
        const btn = document.getElementById('btn-confirm-clear-db');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sedang Mengosongkan...';
        }

        fetch("{{ route('pembayaran.laporan-transaksi.clear-database') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-trash-can"></i> Ya, Kosongkan Sekarang';
            }

            const modalEl = document.getElementById('modalConfirmClearDb');
            if (modalEl && typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            } else if (modalEl && typeof $ !== 'undefined' && $(modalEl).modal) {
                $(modalEl).modal('hide');
            }

            if (data.success) {
                alert('✅ ' + data.message);
                window.location.reload();
            } else {
                alert('❌ ' + (data.message || 'Gagal mengosongkan database lokal.'));
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fa-solid fa-trash-can"></i> Ya, Kosongkan Sekarang';
            }
            alert('❌ Terjadi kesalahan jaringan saat mengosongkan database: ' + err);
        });
    }
</script>
@endpush
