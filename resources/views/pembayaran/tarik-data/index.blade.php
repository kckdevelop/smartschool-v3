@extends('layouts.app')

@section('title', 'Tarik Data Tagihan VA BPD DIY — SmartSchool')
@section('header_title', 'Tarik Data VA BPD DIY')
@section('header_subtitle', 'Penarikan data tagihan Virtual Account langsung dari portal BPD DIY (https://va.bpddiy.co.id/admin/report/tagihan_va)')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- ═══════════════════════════════════════════════════
         BANNER STATUS KONEKSI & COOKIE BPD DIY
    ══════════════════════════════════════════════════════ --}}
    <div class="card mb-4" style="border: 1px solid #e2e8f0; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.03);">
        <div style="padding: 14px 20px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; background: #f8fafc; border-bottom: 1px solid #edf2f7;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <div style="width: 40px; height: 40px; border-radius: 10px; background: {{ $hasCookie ? '#dcfce7' : '#fee2e2' }}; color: {{ $hasCookie ? '#15803d' : '#b91c1c' }}; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                    <i class="fa-solid {{ $hasCookie ? 'fa-shield-check' : 'fa-triangle-exclamation' }}"></i>
                </div>
                <div>
                    <div style="font-weight: 700; font-size: 0.95rem; color: #1e293b; display: flex; align-items: center; gap: 8px;">
                        <span>Portal BPD DIY Report Tagihan VA</span>
                        @if($hasCookie)
                            <span class="badge" style="background: #22c55e; color: #fff; font-size: 0.72rem; padding: 3px 8px; border-radius: 20px;">
                                <i class="fa-solid fa-circle-check"></i> Cookie Tersedia
                            </span>
                        @else
                            <span class="badge" style="background: #ef4444; color: #fff; font-size: 0.72rem; padding: 3px 8px; border-radius: 20px;">
                                <i class="fa-solid fa-circle-xmark"></i> Cookie Belum Diisi
                            </span>
                        @endif
                    </div>
                    <div style="font-size: 0.8rem; color: #64748b;">
                        Target URL: <code style="background: #e2e8f0; padding: 2px 6px; border-radius: 4px; color: #0f172a;">{{ $setting->url_report_va ?? 'https://va.bpddiy.co.id/admin/report/tagihan_va' }}</code>
                        @if($cookieUpdated)
                            • <span title="{{ $cookieUpdated->format('d/m/Y H:i:s') }}">Diperbarui {{ $cookieAgeText }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
                <button type="button" class="btn btn-sm btn-primary" onclick="confirmAutoPullReplace()" style="display: flex; align-items: center; gap: 6px; font-weight: 700; background: linear-gradient(135deg, #1e40af, #2563eb); border-color: #1e40af; box-shadow: 0 2px 8px rgba(37,99,235,0.3);" title="Tarik data otomatis dari BPD DIY, hapus data lama di database, dan masukkan data baru">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    <span>⚡ Tarik Otomatis & Ganti DB</span>
                </button>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="confirmClearDatabase()" style="display: flex; align-items: center; gap: 6px; font-weight: 600;" title="Kosongkan seluruh data tagihan di database lokal">
                    <i class="fa-solid fa-trash-can"></i>
                    <span>Kosongkan DB</span>
                </button>
                <button type="button" class="btn btn-sm btn-success" onclick="openUploadExcelModal()" style="display: flex; align-items: center; gap: 6px; font-weight: 600;">
                    <i class="fa-solid fa-file-excel"></i>
                    <span>Upload Excel BPD</span>
                </button>
                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-auto-login-top" onclick="triggerAutoLogin()" style="display: flex; align-items: center; gap: 6px; font-weight: 600;">
                    <i class="fa-solid fa-bolt"></i>
                    <span>Tes Auto-Login</span>
                </button>
                <button type="button" class="btn btn-sm btn-secondary" onclick="openCookieModal()" style="display: flex; align-items: center; gap: 6px; font-weight: 600;">
                    <i class="fa-solid fa-cookie-bite" style="color: #f59e0b;"></i>
                    <span>{{ $hasCookie ? 'Kelola Cookie' : 'Input Cookie' }}</span>
                </button>
                <a href="{{ url('https://va.bpddiy.co.id/admin/report/tagihan_va') }}" target="_blank" class="btn btn-sm btn-outline-secondary" style="display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                    <span>Portal BPD</span>
                </a>
            </div>
        </div>
    </div>

    @php
        $institusiRaw = $setting->id_institusi ?? '9990029';
        $instPrefix   = str_starts_with($institusiRaw, '999') ? $institusiRaw : ('999' . $institusiRaw);
        $currentTahun = date('Y');
    @endphp

    {{-- ═══════════════════════════════════════════════════
         PANEL FORM FILTER (PERSIS SEPERTI PORTAL BPD DIY)
    ══════════════════════════════════════════════════════ --}}
    <div class="card mb-4" style="border: 1px solid #3b82f6; border-radius: 12px; box-shadow: 0 4px 20px rgba(59,130,246,0.08); overflow: hidden;">
        <div class="card-header" style="background: linear-gradient(135deg, #1e40af, #3b82f6); color: #ffffff; padding: 16px 20px; display: flex; justify-content: space-between; align-items: center;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-filter" style="font-size: 1.25rem;"></i>
                <div>
                    <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: #fff;">Filter & Tarik Data Tagihan VA</h3>
                    <p style="font-size: 0.8rem; margin: 0; opacity: 0.9;">Tentukan filter pencarian, lalu klik Filter untuk mengambil data dari portal BPD DIY</p>
                </div>
            </div>
            <div>
                <span class="badge" style="background: rgba(255,255,255,0.2); color: #fff; font-weight: 600; font-size: 0.8rem; padding: 6px 12px; border-radius: 6px;">
                    <i class="fa-solid fa-building-columns"></i> Mitra: {{ $setting->id_institusi ?? '0029' }}
                </span>
            </div>
        </div>

        <div class="card-body" style="padding: 22px;">
            <form id="form-filter-bpd" onsubmit="event.preventDefault(); triggerFetchBpd();">
                <div class="row" style="display: flex; flex-wrap: wrap; margin: -10px;">

                    {{-- Mitra --}}
                    <div style="flex: 1 1 260px; padding: 10px;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: #334155;">
                            Silakan Pilih Mitra Terlebih Dahulu
                        </label>
                        <input type="text" id="filter_mitra" class="form-control"
                               value="{{ $setting->id_institusi ? $setting->id_institusi . ' - VA ' . ($sekolah->nama_sekolah ?? 'SMK') : '0029 - VA SMK MUHAMMADIYAH 1 BANTUL' }}"
                               readonly
                               style="background-color: #f1f5f9; cursor: not-allowed; font-weight: 600; color: #475569;">
                    </div>

                    {{-- Filter Tahun VA (Awalan VA) --}}
                    <div style="flex: 1 1 200px; padding: 10px;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: #334155;">
                            <i class="fa-solid fa-calendar-check" style="color: #3b82f6;"></i> Tahun VA
                        </label>
                        <input type="number" id="filter_tahun" class="form-control"
                               value="{{ $currentTahun }}" placeholder="{{ $currentTahun }}"
                               oninput="updatePrefixInfo()"
                               style="font-weight: 700; color: #1e40af;">
                        <small id="prefix_info_badge" style="font-size: 0.73rem; color: #64748b; margin-top: 4px; display: block;">
                            Awalan VA: <strong id="current_prefix_text" style="color: #2563eb; font-family: monospace;">{{ $instPrefix . $currentTahun }}</strong>
                        </small>
                    </div>

                    {{-- Parent Virtual Account --}}
                    <div style="flex: 1 1 220px; padding: 10px;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: #334155;">
                            Pilih Parent Virtual Account
                        </label>
                        <select id="filter_parent_va" class="form-control" style="font-weight: 500;">
                            <option value="">Choose One (Semua Parent)</option>
                            <option value="SPP">SPP (Kode {{ $setting->kode_spp ?? '00' }})</option>
                            <option value="NON_SPP">Non SPP (Kode {{ $setting->kode_non_spp ?? '01' }})</option>
                            <option value="TUNGGAKAN">Tunggakan (Kode {{ $setting->kode_tunggakan ?? '02' }})</option>
                        </select>
                    </div>

                    {{-- Periode Waktu --}}
                    <div style="flex: 1 1 220px; padding: 10px;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: #334155;">
                            <i class="fa-regular fa-calendar-days" style="color: #3b82f6;"></i> Periode Waktu
                        </label>
                        <input type="text" id="filter_periode" class="form-control"
                               placeholder="YYYY-MM-DD atau Rentang Tanggal"
                               style="font-family: inherit;">
                    </div>

                    {{-- Status Transaksi --}}
                    <div style="flex: 1 1 180px; padding: 10px;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: #334155;">
                            Status Transaksi
                        </label>
                        <select id="filter_status" class="form-control" style="font-weight: 600;">
                            <option value="semua" selected>Semua</option>
                            <option value="lunas">Lunas (Paid)</option>
                            <option value="belum_bayar">Belum Lunas (Unpaid)</option>
                        </select>
                    </div>

                    {{-- Cari NIS / Nama Siswa --}}
                    <div style="flex: 1 1 220px; padding: 10px;">
                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: #334155;">
                            <i class="fa-solid fa-magnifying-glass" style="color: #3b82f6;"></i> Kata Kunci (NIS/Nama/No VA)
                        </label>
                        <input type="text" id="filter_search" class="form-control"
                               placeholder="Ketik NIS atau nama siswa...">
                    </div>

                </div>

                {{-- Action Buttons --}}
                <div style="margin-top: 16px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center; justify-content: space-between; border-top: 1px solid #f1f5f9; padding-top: 16px;">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <button type="button" id="btn-submit-filter" onclick="triggerFetchBpd()" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700; display: flex; align-items: center; gap: 8px; font-size: 0.95rem; box-shadow: 0 4px 12px rgba(37,99,235,0.25);">
                            <i class="fa-solid fa-filter"></i>
                            <span>Filter & Tarik Data</span>
                        </button>

                        <button type="button" onclick="resetFilterBpd()" class="btn btn-secondary" style="padding: 10px 18px; font-weight: 600; display: flex; align-items: center; gap: 6px;">
                            <i class="fa-solid fa-rotate-left"></i>
                            <span>Reset</span>
                        </button>
                    </div>

                    <div style="font-size: 0.82rem; color: #64748b;">
                        <i class="fa-solid fa-circle-info" style="color: #3b82f6;"></i>
                        Hanya menarik data VA tahun <strong id="filter_tahun_hint" style="color: #1e40af;">{{ $currentTahun }}</strong> dengan awalan <code id="filter_prefix_hint" style="color: #2563eb;">{{ $instPrefix . $currentTahun }}</code>.
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         LOADING ANIMATION & STATUS CONTAINER
    ══════════════════════════════════════════════════════ --}}
    <div id="loading-container" style="display: none; margin-bottom: 24px;">
        <div class="card" style="border: 2px dashed #3b82f6; background: #f8fafc; text-align: center; padding: 36px 20px; border-radius: 12px;">
            <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem; margin-bottom: 16px;">
                <span class="visually-hidden">Loading...</span>
            </div>
            <h4 style="font-size: 1.15rem; font-weight: 800; color: #1e3a8a; margin-bottom: 6px;">
                Sedang Menarik Data dari Portal BPD DIY...
            </h4>
            <p style="font-size: 0.88rem; color: #64748b; max-width: 580px; margin: 0 auto 10px;">
                Sistem mengambil data tagihan VA dari <code style="color:#2563eb;">va.bpddiy.co.id</code> secara
                bertahap (500 record/halaman) hingga <strong>semua data</strong> terkumpul.
                Proses ini mungkin membutuhkan waktu 30–120 detik tergantung jumlah data.
            </p>
            <div style="font-size: 0.8rem; color: #94a3b8; margin-top: 8px;">
                <i class="fa-solid fa-circle-notch fa-spin"></i>
                Mohon tunggu, jangan tutup halaman ini...
            </div>
        </div>
    </div>


    {{-- Error Alert Banner --}}
    <div id="error-banner" style="display: none; margin-bottom: 24px;">
        <div class="alert alert-danger" style="border-radius: 10px; display: flex; justify-content: space-between; align-items: center; padding: 14px 20px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <i class="fa-solid fa-circle-exclamation" style="font-size: 1.4rem;"></i>
                <div>
                    <div style="font-weight: 700; font-size: 0.95rem;" id="error-title">Gagal Menarik Data</div>
                    <div style="font-size: 0.85rem;" id="error-message">Terjadi kesalahan saat menghubungi server BPD DIY.</div>
                </div>
            </div>
            <div>
                <button type="button" class="btn btn-sm btn-outline-danger" onclick="openCookieModal()">
                    <i class="fa-solid fa-key"></i> Perbarui Cookie
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         SUMMARY STATISTIK (MUNCUL SETELAH DATA DITARIK)
    ══════════════════════════════════════════════════════ --}}
    <div id="summary-container" style="display: none; margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px;">

            {{-- Total Tagihan --}}
            <div class="card" style="border-left: 4px solid #3b82f6; border-radius: 10px; padding: 16px 20px;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Data Tagihan</div>
                <div style="display: flex; align-items: baseline; justify-content: space-between; margin-top: 6px;">
                    <div style="font-size: 1.8rem; font-weight: 800; color: #1e293b;" id="sum_total_records">0</div>
                    <span class="badge" style="background: #eff6ff; color: #2563eb; font-weight: 700;">Record</span>
                </div>
                <div style="font-size: 0.78rem; color: #64748b; margin-top: 4px;" id="sum_status_breakdown">
                    Lunas: 0 • Belum: 0
                </div>
            </div>

            {{-- Total Nilai Tagihan --}}
            <div class="card" style="border-left: 4px solid #8b5cf6; border-radius: 10px; padding: 16px 20px;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Total Nilai Tagihan</div>
                <div style="font-size: 1.45rem; font-weight: 800; color: #6d28d9; margin-top: 6px;" id="sum_total_nilai">Rp 0</div>
                <div style="font-size: 0.78rem; color: #64748b; margin-top: 4px;">Akumulasi total tagihan terdaftar</div>
            </div>

            {{-- Total Telah Dibayar --}}
            <div class="card" style="border-left: 4px solid #10b981; border-radius: 10px; padding: 16px 20px;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Nominal Telah Dibayar</div>
                <div style="font-size: 1.45rem; font-weight: 800; color: #047857; margin-top: 6px;" id="sum_total_terbayar">Rp 0</div>
                <div style="font-size: 0.78rem; color: #10b981; font-weight: 600; margin-top: 4px;" id="sum_persen_bayar">0% terbayar</div>
            </div>

            {{-- Total Sisa Pembayaran --}}
            <div class="card" style="border-left: 4px solid #ef4444; border-radius: 10px; padding: 16px 20px;">
                <div style="font-size: 0.8rem; font-weight: 700; color: #64748b; text-transform: uppercase;">Sisa Pembayaran (Tunggakan)</div>
                <div style="font-size: 1.45rem; font-weight: 800; color: #b91c1c; margin-top: 6px;" id="sum_total_sisa">Rp 0</div>
                <div style="font-size: 0.78rem; color: #64748b; margin-top: 4px;">Sisa tagihan yang belum terbayar</div>
            </div>

        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         TABEL HASIL PENARIKAN DATA BPD DIY
    ══════════════════════════════════════════════════════ --}}
    <div class="card mb-4" id="table-card" style="display: none; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0;">
        <div class="card-header" style="background: #ffffff; padding: 16px 20px; border-bottom: 1px solid #edf2f7; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px;">
            <div style="display: flex; align-items: center; gap: 10px;">
                <i class="fa-solid fa-table-list" style="color: #3b82f6; font-size: 1.2rem;"></i>
                <div>
                    <h3 style="font-size: 1.05rem; font-weight: 700; margin: 0; color: #1e293b;">
                        Hasil Penarikan Data Tagihan VA
                    </h3>
                    <span style="font-size: 0.8rem; color: #64748b;" id="table-subtitle">
                        Menampilkan 0 baris data dari portal BPD DIY
                    </span>
                </div>
            </div>

            {{-- Table Action Buttons --}}
            <div style="display: flex; flex-wrap: wrap; gap: 8px; align-items: center;">
                {{-- Filter Lokal Cepat --}}
                <div style="position: relative;">
                    <input type="text" id="local_table_search" onkeyup="filterLocalTable()" class="form-control form-control-sm"
                           placeholder="Filter cepat tabel..." style="padding-left: 28px; width: 180px; font-size: 0.82rem;">
                    <i class="fa-solid fa-magnifying-glass" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); font-size: 0.75rem; color: #94a3b8;"></i>
                </div>

                {{-- Export Excel --}}
                <button type="button" onclick="exportToCsv()" class="btn btn-sm btn-outline-success" title="Export ke file Excel / CSV">
                    <i class="fa-solid fa-file-excel"></i> Export Excel
                </button>

                {{-- Cetak / Print --}}
                <button type="button" onclick="printTableResults()" class="btn btn-sm btn-outline-secondary" title="Cetak halaman">
                    <i class="fa-solid fa-print"></i> Cetak
                </button>

                {{-- Sinkronkan ke Database SmartSchool --}}
                <button type="button" id="btn-sync-all" onclick="syncSelectedToDatabase()" class="btn btn-sm btn-success" style="font-weight: 700; display: flex; align-items: center; gap: 6px;">
                    <i class="fa-solid fa-cloud-arrow-down"></i>
                    <span>Simpan ke Database</span>
                </button>
            </div>
        </div>

        <div class="card-body" style="padding: 0;">
            <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                <table class="table table-hover align-middle mb-0" id="bpd-results-table" style="font-size: 0.85rem;">
                    <thead style="background: #f8fafc; position: sticky; top: 0; z-index: 10; border-bottom: 2px solid #e2e8f0;">
                        <tr>
                            <th style="width: 40px; text-align: center; padding: 12px 8px;">
                                <input type="checkbox" id="check-all" onchange="toggleCheckAll(this)" style="cursor: pointer;">
                            </th>
                            <th style="width: 50px; text-align: center;">NO</th>
                            <th style="min-width: 170px;">TRX ID</th>
                            <th style="min-width: 180px;">NO VA</th>
                            <th style="min-width: 220px;">NAMA VA</th>
                            <th style="min-width: 120px;">NOMOR IDENTITAS (NIS)</th>
                            <th style="min-width: 140px; text-align: right;">TOTAL TAGIHAN</th>
                            <th style="min-width: 140px; text-align: right;">TELAH DIBAYAR</th>
                            <th style="min-width: 140px; text-align: right;">SISA PEMBAYARAN</th>
                            <th style="min-width: 150px;">TANGGAL DIBAYAR</th>
                            <th style="min-width: 110px; text-align: center;">STATUS</th>
                            <th style="min-width: 110px; text-align: center;">STATUS DB</th>
                        </tr>
                    </thead>
                    <tbody id="bpd-table-tbody">
                        {{-- Data rows inserted dynamically via JavaScript --}}
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card-footer" style="background: #f8fafc; padding: 12px 20px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid #edf2f7; font-size: 0.82rem; color: #64748b;">
            <div id="table-footer-selected">
                <span id="selected-count">0</span> dari <span id="total-count">0</span> baris dipilih
            </div>
            <div style="display: flex; gap: 10px;">
                <button type="button" onclick="selectAllVisible(true)" class="btn btn-link btn-sm p-0" style="text-decoration: none; font-size: 0.82rem;">Pilih Semua</button>
                <span>•</span>
                <button type="button" onclick="selectAllVisible(false)" class="btn btn-link btn-sm p-0" style="text-decoration: none; font-size: 0.82rem;">Batal Pilih</button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════
         INITIAL EMPTY STATE
    ══════════════════════════════════════════════════════ --}}
    <div id="empty-state-container">
        <div class="card" style="text-align: center; padding: 50px 20px; border-radius: 12px; border: 1px dashed #cbd5e1; background: #ffffff;">
            <div style="width: 72px; height: 72px; border-radius: 50%; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; font-size: 2rem; margin: 0 auto 18px;">
                <i class="fa-solid fa-cloud-arrow-down"></i>
            </div>
            <h4 style="font-weight: 800; color: #1e293b; font-size: 1.15rem; margin-bottom: 8px;">
                Belum Ada Data yang Ditarik
            </h4>
            <p style="color: #64748b; font-size: 0.88rem; max-width: 500px; margin: 0 auto 20px;">
                Silakan atur filter pencarian (Periode Waktu, Status Transaksi, Parent VA) pada form di atas, lalu klik tombol <strong>"Filter & Tarik Data"</strong> untuk mengambil data langsung dari portal BPD DIY.
            </p>
            <div>
                <button type="button" onclick="triggerFetchBpd()" class="btn btn-primary" style="padding: 10px 24px; font-weight: 700;">
                    <i class="fa-solid fa-play"></i> Mulai Tarik Data Sekarang
                </button>
            </div>
        </div>
    </div>

</div>

{{-- ═══════════════════════════════════════════════════
     MODAL UPLOAD FILE EXCEL / CSV DARI PORTAL BPD DIY
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalUploadExcelBpd" tabindex="-1" aria-labelledby="modalUploadExcelBpdLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div class="modal-header" style="background: linear-gradient(135deg, #15803d, #22c55e); color: #fff; padding: 16px 20px;">
                <h5 class="modal-title" id="modalUploadExcelBpdLabel" style="font-weight: 700; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-file-excel"></i>
                    <span>Unggah File Excel Export BPD DIY</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="form-upload-excel-bpd" onsubmit="event.preventDefault(); submitUploadExcel();" enctype="multipart/form-data">
                <div class="modal-body" style="padding: 22px;">
                    <div style="background: #f0fdf4; border: 1px dashed #86efac; border-radius: 10px; padding: 16px; margin-bottom: 18px;">
                        <div style="font-size: 0.85rem; color: #166534; line-height: 1.5;">
                            <strong>💡 Solusi Anti Expired / Multi-Layer Protection:</strong><br>
                            Anda cukup klik tombol <strong>"Export Excel"</strong> pada portal BPD DIY (<a href="https://va.bpddiy.co.id/admin/report/tagihan_va" target="_blank" style="color: #15803d; font-weight: 700; text-decoration: underline;">va.bpddiy.co.id</a>), lalu unggah file hasil download (.xlsx / .xls / .csv) di bawah ini.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 700; font-size: 0.85rem; color: #1e293b;">
                            Pilih File Excel / CSV Hasil Export:
                        </label>
                        <input type="file" id="file_excel_input" name="file_excel" class="form-control" accept=".xlsx,.xls,.csv" required style="padding: 10px; font-size: 0.9rem;">
                        <small class="text-muted" style="font-size: 0.75rem;">Mendukung format .xlsx, .xls, .csv hingga 20 MB.</small>
                    </div>

                    <div id="modal-upload-feedback" style="display: none; padding: 10px 14px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; margin-bottom: 10px;"></div>
                </div>
                <div class="modal-footer" style="background: #f8fafc; padding: 14px 20px; border-top: 1px solid #e2e8f0;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" id="btn-submit-upload-excel" class="btn btn-success btn-sm" style="font-weight: 700;">
                        <i class="fa-solid fa-cloud-arrow-up"></i> Unggah & Muat Data
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     MODAL KELOLA / INPUT SESSION COOKIE BPD DIY
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalCookieBpd" tabindex="-1" aria-labelledby="modalCookieBpdLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e40af, #3b82f6); color: #fff; padding: 16px 20px;">
                <h5 class="modal-title" id="modalCookieBpdLabel" style="font-weight: 700; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-cookie-bite" style="color: #f59e0b;"></i>
                    <span>Solusi Akses & Session Cookie BPD DIY</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 22px;">

                {{-- NAV TABS --}}
                <ul class="nav nav-pills mb-3" id="cookieTabs" role="tablist" style="gap: 8px;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active btn-sm" id="tab-auto-tab" data-bs-toggle="pill" data-bs-target="#tab-auto" type="button" role="tab" style="font-weight: 700; border-radius: 8px;">
                            <i class="fa-solid fa-bolt"></i> 1. Auto-Login
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link btn-sm" id="tab-bookmarklet-tab" data-bs-toggle="pill" data-bs-target="#tab-bookmarklet" type="button" role="tab" style="font-weight: 700; border-radius: 8px;">
                            <i class="fa-solid fa-bookmark" style="color: #f59e0b;"></i> 2. Bookmarklet 1-Klik
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link btn-sm" id="tab-manual-tab" data-bs-toggle="pill" data-bs-target="#tab-manual" type="button" role="tab" style="font-weight: 700; border-radius: 8px;">
                            <i class="fa-solid fa-keyboard"></i> 3. Input Manual
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="cookieTabsContent">
                    {{-- TAB 1: AUTO LOGIN --}}
                    <div class="tab-pane fade show active" id="tab-auto" role="tabpanel">
                        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 16px; margin-bottom: 14px;">
                            <div style="font-weight: 700; color: #1e293b; margin-bottom: 6px;">
                                <i class="fa-solid fa-robot" style="color: #3b82f6;"></i> Login Programatik Otomatis di Background
                            </div>
                            <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 12px;">
                                Sistem akan mencoba login secara mandiri menggunakan kredensial <strong>Username Maker & Password</strong> yang tersimpan di Setting Konfigurasi.
                            </p>
                            <div style="font-size: 0.82rem; color: #334155; margin-bottom: 12px;">
                                Akun Maker saat ini: <code>{{ $setting->username_maker ?: '(Belum diatur)' }}</code>
                            </div>
                            <button type="button" id="btn-modal-auto-login" onclick="triggerAutoLoginModal()" class="btn btn-primary btn-sm" style="font-weight: 700; padding: 8px 18px;">
                                <i class="fa-solid fa-bolt"></i> Jalankan Auto-Login Sekarang
                            </button>
                        </div>
                    </div>

                    {{-- TAB 2: BOOKMARKLET 1-KLIK --}}
                    <div class="tab-pane fade" id="tab-bookmarklet" role="tabpanel">
                        <div style="background: #fffbeb; border: 1px solid #fef3c7; border-radius: 10px; padding: 16px; margin-bottom: 14px;">
                            <div style="font-weight: 700; color: #92400e; margin-bottom: 6px;">
                                <i class="fa-solid fa-star" style="color: #f59e0b;"></i> Cara Paling Praktis (Tarik Bookmarklet):
                            </div>
                            <ol style="font-size: 0.85rem; color: #78350f; padding-left: 20px; line-height: 1.6; margin-bottom: 12px;">
                                <li>Tarik (Drag & Drop) tombol kuning <strong>"Sync BPD DIY"</strong> di bawah ini ke <strong>Bookmarks Bar</strong> browser Anda.</li>
                                <li>Buka tab portal <a href="https://va.bpddiy.co.id/admin/report/tagihan_va" target="_blank" style="font-weight: 700; text-decoration: underline;">va.bpddiy.co.id</a> yang sedang login.</li>
                                <li>Klik bookmark <strong>"Sync BPD DIY"</strong> tersebut. Cookie akan langsung terkirim & tersimpan otomatis ke SmartSchool!</li>
                            </ol>
                            <div style="text-align: center; padding: 10px;">
                                <a id="bookmarklet-link" href="#" class="btn btn-warning" style="font-weight: 800; cursor: move; box-shadow: 0 4px 12px rgba(245,158,11,0.3); border-radius: 8px;" onclick="event.preventDefault(); alert('Tarik tombol ini ke Bookmarks Bar browser Anda (tekan Ctrl+Shift+B jika baris bookmark belum muncul).');">
                                    ⭐ Tarik Saya: Sync BPD DIY
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- TAB 3: INPUT MANUAL --}}
                    <div class="tab-pane fade" id="tab-manual" role="tabpanel">
                        <form id="form-save-cookie-quick" onsubmit="event.preventDefault(); submitQuickCookie();">
                            <div class="mb-3">
                                <label class="form-label" style="font-weight: 700; font-size: 0.85rem;">
                                    Paste String Cookie BPD DIY:
                                </label>
                                <textarea id="modal_session_cookie" class="form-control" rows="4"
                                          placeholder="ci_session=xxxxxxxxx; csrf_cookie_name=xxxxxxxxx..."
                                          style="font-family: monospace; font-size: 0.82rem;" required>{{ $setting->session_cookie }}</textarea>
                            </div>
                            <button type="submit" id="btn-save-cookie-modal" class="btn btn-primary btn-sm" style="font-weight: 700;">
                                <i class="fa-solid fa-save"></i> Simpan Cookie Manual
                            </button>
                        </form>
                    </div>
                </div>

                <div id="modal-cookie-feedback" style="display: none; padding: 10px 14px; border-radius: 8px; font-size: 0.85rem; font-weight: 600; margin-top: 14px;"></div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; padding: 12px 20px; border-top: 1px solid #e2e8f0;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     MODAL KONFIRMASI TARIK OTOMATIS & TIMPA DATABASE
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalConfirmAutoPullReplace" tabindex="-1" aria-labelledby="modalConfirmAutoPullReplaceLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div class="modal-header" style="background: linear-gradient(135deg, #1e40af, #2563eb); color: #fff; padding: 16px 20px;">
                <h5 class="modal-title" id="modalConfirmAutoPullReplaceLabel" style="font-weight: 700; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-arrows-rotate"></i>
                    <span>Tarik Otomatis & Timpa Database</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 22px;">
                <div style="text-align: center; margin-bottom: 16px;">
                    <div style="width: 60px; height: 60px; border-radius: 50%; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 12px;">
                        <i class="fa-solid fa-cloud-arrow-down"></i>
                    </div>
                    <h4 style="font-size: 1.15rem; font-weight: 800; color: #1e293b; margin-bottom: 6px;">
                        Tarik & Ganti Seluruh Data Database?
                    </h4>
                    <p style="font-size: 0.85rem; color: #64748b; margin-bottom: 0;">
                        Sistem akan menjalankan alur otomatis berikut:
                    </p>
                </div>

                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 10px; padding: 14px 16px; margin-bottom: 16px; font-size: 0.84rem;">
                    <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px;">
                        <span class="badge" style="background: #2563eb; color: #fff; font-size: 0.72rem; border-radius: 50%; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; padding: 0;">1</span>
                        <div><strong>Mengambil data terbaru</strong> langsung dari portal portal BPD DIY (seluruh data tagihan VA).</div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 10px; margin-bottom: 10px;">
                        <span class="badge" style="background: #ef4444; color: #fff; font-size: 0.72rem; border-radius: 50%; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; padding: 0;">2</span>
                        <div><strong style="color: #b91c1c;">Menghapus seluruh data lama</strong> di database tagihan SmartSchool.</div>
                    </div>
                    <div style="display: flex; align-items: flex-start; gap: 10px;">
                        <span class="badge" style="background: #16a34a; color: #fff; font-size: 0.72rem; border-radius: 50%; width: 20px; height: 20px; display: inline-flex; align-items: center; justify-content: center; padding: 0;">3</span>
                        <div><strong>Memasukkan seluruh data baru</strong> yang berhasil ditarik dari BPD DIY ke database.</div>
                    </div>
                </div>

                <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 10px 14px; font-size: 0.8rem; color: #92400e; margin-bottom: 14px;">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <strong>Catatan:</strong> Jika gagal terhubung ke BPD DIY, data lama Anda <strong>tidak akan dihapus</strong> (transaksi dibatalkan secara aman).
                </div>

                <div style="font-size: 0.82rem; color: #475569; display: flex; justify-content: space-between; align-items: center; background: #f1f5f9; padding: 8px 14px; border-radius: 8px;">
                    <span>Tahun VA yang ditarik:</span>
                    <strong id="modal_auto_pull_tahun_text" style="color: #1e40af; font-family: monospace; font-size: 0.9rem;">{{ $currentTahun }}</strong>
                </div>

                <div id="auto-pull-progress-box" style="display: none; margin-top: 14px; padding: 12px; background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; text-align: center;">
                    <div class="spinner-border spinner-border-sm text-primary" role="status" style="margin-right: 6px;"></div>
                    <span id="auto-pull-progress-text" style="font-size: 0.84rem; font-weight: 600; color: #1e40af;">Sedang memproses penarikan & penggantian database...</span>
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; padding: 14px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" id="btn-cancel-auto-pull" style="font-weight: 600;">
                    Batal
                </button>
                <button type="button" id="btn-confirm-auto-pull" onclick="executeAutoPullAndReplace()" class="btn btn-primary btn-sm" style="font-weight: 700; padding: 8px 18px; background: #2563eb; border-color: #2563eb;">
                    <i class="fa-solid fa-bolt"></i> Ya, Tarik & Ganti Database
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     MODAL KONFIRMASI KOSONGKAN DATA TAGIHAN DB LOKAL
══════════════════════════════════════════════════════ --}}
<div class="modal fade" id="modalConfirmClearDb" tabindex="-1" aria-labelledby="modalConfirmClearDbLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 14px; overflow: hidden; border: none; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <div class="modal-header" style="background: linear-gradient(135deg, #b91c1c, #ef4444); color: #fff; padding: 16px 20px;">
                <h5 class="modal-title" id="modalConfirmClearDbLabel" style="font-weight: 700; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Konfirmasi Kosongkan Database Tagihan</span>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" style="padding: 22px; text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: #fee2e2; color: #dc2626; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; margin: 0 auto 16px;">
                    <i class="fa-solid fa-trash-can"></i>
                </div>
                <h4 style="font-size: 1.15rem; font-weight: 800; color: #1e293b; margin-bottom: 8px;">
                    Kosongkan Seluruh Data Tagihan Lokal?
                </h4>
                <p style="font-size: 0.88rem; color: #64748b; margin-bottom: 16px; line-height: 1.5;">
                    Tindakan ini akan menghapus <strong>seluruh data tagihan pembayaran</strong> pada database lokal SmartSchool. Data yang belum disinkronkan atau riwayat tagihan di aplikasi akan dikosongkan.
                </p>
                <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 8px; padding: 10px 14px; font-size: 0.82rem; color: #9f1239; font-weight: 600; text-align: left;">
                    <i class="fa-solid fa-circle-info"></i> Data pada portal resmi BPD DIY tidak akan terhapus, Anda dapat menarik ulang data tagihan kapan saja.
                </div>
            </div>
            <div class="modal-footer" style="background: #f8fafc; padding: 14px 20px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between;">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" style="font-weight: 600;">
                    Batal
                </button>
                <button type="button" id="btn-confirm-clear-db" onclick="executeClearDatabase()" class="btn btn-danger btn-sm" style="font-weight: 700; padding: 8px 18px;">
                    <i class="fa-solid fa-trash-can"></i> Ya, Kosongkan Data Sekarang
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════════════════
     JAVASCRIPT LOGIC
══════════════════════════════════════════════════════ --}}
@push('scripts')
<script>
    // State data penarikan BPD DIY
    let rawBpdData = [];
    let isFetching = false;

    // Inisialisasi halaman
    document.addEventListener('DOMContentLoaded', function () {
        initBookmarklet();
    });

    function updatePrefixInfo() {
        const basePrefix = "{{ $instPrefix }}";
        const tahun = document.getElementById('filter_tahun').value.trim();
        const fullPrefix = basePrefix + tahun;
        const badge = document.getElementById('current_prefix_text');
        const hintTahun = document.getElementById('filter_tahun_hint');
        const hintPrefix = document.getElementById('filter_prefix_hint');
        if (badge) badge.innerText = fullPrefix;
        if (hintTahun) hintTahun.innerText = tahun || '(Semua)';
        if (hintPrefix) hintPrefix.innerText = fullPrefix || '(Semua)';
    }

    function initBookmarklet() {
        const syncUrl = "{{ route('pembayaran.tarik-data.save-cookie') }}";
        const csrfToken = "{{ csrf_token() }}";
        const code = `javascript:(function(){var c=document.cookie;if(!c){alert('⚠️ Tidak ada cookie pada tab ini. Pastikan Anda berada di halaman va.bpddiy.co.id');return;}var f=new FormData();f.append('session_cookie',c);f.append('_token','${csrfToken}');fetch('${syncUrl}',{method:'POST',body:f}).then(function(r){return r.json();}).then(function(d){alert('SmartSchool: '+(d.message||'Cookie BPD DIY berhasil disinkronkan!'));}).catch(function(e){alert('Gagal kirim cookie: '+e);});})();`;
        const bLink = document.getElementById('bookmarklet-link');
        if (bLink) {
            bLink.setAttribute('href', code);
        }
    }

    function confirmAutoPullReplace() {
        const tahun = document.getElementById('filter_tahun') ? document.getElementById('filter_tahun').value.trim() : '{{ $currentTahun }}';
        const txtTahun = document.getElementById('modal_auto_pull_tahun_text');
        if (txtTahun) txtTahun.innerText = tahun || '(Semua)';

        const progressBox = document.getElementById('auto-pull-progress-box');
        if (progressBox) progressBox.style.display = 'none';

        const btnConfirm = document.getElementById('btn-confirm-auto-pull');
        if (btnConfirm) {
            btnConfirm.disabled = false;
            btnConfirm.innerHTML = '<i class="fa-solid fa-bolt"></i> Ya, Tarik & Ganti Database';
        }

        const modalEl = document.getElementById('modalConfirmAutoPullReplace');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    function executeAutoPullAndReplace() {
        const tahun     = document.getElementById('filter_tahun') ? document.getElementById('filter_tahun').value.trim() : '{{ $currentTahun }}';
        const status    = document.getElementById('filter_status') ? document.getElementById('filter_status').value : 'semua';
        const parentVa  = document.getElementById('filter_parent_va') ? document.getElementById('filter_parent_va').value : '';
        const search    = document.getElementById('filter_search') ? document.getElementById('filter_search').value.trim() : '';

        const btnConfirm   = document.getElementById('btn-confirm-auto-pull');
        const btnCancel    = document.getElementById('btn-cancel-auto-pull');
        const progressBox  = document.getElementById('auto-pull-progress-box');
        const progressText = document.getElementById('auto-pull-progress-text');

        btnConfirm.disabled = true;
        btnCancel.disabled = true;
        btnConfirm.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sedang Memproses...';
        progressBox.style.display = 'block';
        progressText.innerText = 'Sedang menghubungi portal BPD DIY & menarik data tagihan...';

        fetch("{{ route('pembayaran.tarik-data.auto-pull-replace') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                tahun: tahun,
                status_transaksi: status,
                parent_va: parentVa,
                search: search
            })
        })
        .then(res => res.json())
        .then(data => {
            btnConfirm.disabled = false;
            btnCancel.disabled = false;
            btnConfirm.innerHTML = '<i class="fa-solid fa-bolt"></i> Ya, Tarik & Ganti Database';
            progressBox.style.display = 'none';

            const modalEl = document.getElementById('modalConfirmAutoPullReplace');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            if (data.success) {
                alert('✅ ' + data.message);
                // Refresh data tabel dengan memicu fetch
                triggerFetchBpd();
            } else {
                alert('❌ ' + (data.message || 'Gagal menarik data & memperbarui database.'));
                if (data.need_cookie) {
                    openCookieModal();
                }
            }
        })
        .catch(err => {
            btnConfirm.disabled = false;
            btnCancel.disabled = false;
            btnConfirm.innerHTML = '<i class="fa-solid fa-bolt"></i> Ya, Tarik & Ganti Database';
            progressBox.style.display = 'none';
            alert('Error koneksi: ' + err.message);
        });
    }

    function confirmClearDatabase() {
        const modalEl = document.getElementById('modalConfirmClearDb');
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }

    function executeClearDatabase() {
        const btn = document.getElementById('btn-confirm-clear-db');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Sedang Mengosongkan...';

        fetch("{{ route('pembayaran.tarik-data.clear-database') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-trash-can"></i> Ya, Kosongkan Data Sekarang';

            const modalEl = document.getElementById('modalConfirmClearDb');
            const modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();

            if (data.success) {
                alert('✅ ' + data.message);
                // Perbarui status database pada data yang sedang tampil di tabel
                if (rawBpdData && rawBpdData.length > 0) {
                    rawBpdData.forEach(r => {
                        r.in_db = false;
                        r.db_id = null;
                    });
                    renderTable(rawBpdData);
                }
            } else {
                alert('❌ ' + (data.message || 'Gagal mengosongkan database lokal.'));
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-trash-can"></i> Ya, Kosongkan Data Sekarang';
            alert('Error: ' + err.message);
        });
    }

    function openCookieModal() {
        const modal = new bootstrap.Modal(document.getElementById('modalCookieBpd'));
        document.getElementById('modal-cookie-feedback').style.display = 'none';
        modal.show();
    }

    function openUploadExcelModal() {
        const modal = new bootstrap.Modal(document.getElementById('modalUploadExcelBpd'));
        document.getElementById('modal-upload-feedback').style.display = 'none';
        document.getElementById('form-upload-excel-bpd').reset();
        modal.show();
    }

    function submitUploadExcel() {
        const fileInput = document.getElementById('file_excel_input');
        const feedback  = document.getElementById('modal-upload-feedback');
        const btnSubmit = document.getElementById('btn-submit-upload-excel');
        const tahun     = document.getElementById('filter_tahun') ? document.getElementById('filter_tahun').value.trim() : '{{ $currentTahun }}';

        if (!fileInput.files || fileInput.files.length === 0) {
            feedback.style.display = 'block';
            feedback.style.background = '#fee2e2';
            feedback.style.color = '#b91c1c';
            feedback.innerText = 'Silakan pilih file Excel terlebih dahulu!';
            return;
        }

        const formData = new FormData();
        formData.append('file_excel', fileInput.files[0]);
        formData.append('tahun', tahun);
        formData.append('_token', '{{ csrf_token() }}');

        btnSubmit.disabled = true;
        btnSubmit.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses File...';

        fetch("{{ route('pembayaran.tarik-data.upload-excel') }}", {
            method: "POST",
            headers: {
                "Accept": "application/json"
            },
            body: formData
        })
        .then(res => res.json())
        .then(response => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Unggah & Muat Data';

            if (!response.success) {
                feedback.style.display = 'block';
                feedback.style.background = '#fee2e2';
                feedback.style.color = '#b91c1c';
                feedback.innerText = response.message || 'Gagal memproses file Excel.';
                return;
            }

            feedback.style.display = 'block';
            feedback.style.background = '#dcfce7';
            feedback.style.color = '#15803d';
            feedback.innerText = response.message;

            setTimeout(() => {
                const modalEl = document.getElementById('modalUploadExcelBpd');
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();

                rawBpdData = response.data || [];
                renderSummary(response.summary);
                renderTable(rawBpdData);
            }, 800);
        })
        .catch(err => {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = '<i class="fa-solid fa-cloud-arrow-up"></i> Unggah & Muat Data';
            feedback.style.display = 'block';
            feedback.style.background = '#fee2e2';
            feedback.style.color = '#b91c1c';
            feedback.innerText = 'Error: ' + err.message;
        });
    }

    function triggerAutoLogin() {
        const btn = document.getElementById('btn-auto-login-top');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mencoba Login...';

        fetch("{{ route('pembayaran.tarik-data.auto-login') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-bolt"></i> Tes Auto-Login';
            alert((data.success ? '✅ ' : '❌ ') + data.message);
            if (data.success) {
                location.reload();
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-bolt"></i> Tes Auto-Login';
            alert('Error: ' + err.message);
        });
    }

    function triggerAutoLoginModal() {
        const btn = document.getElementById('btn-modal-auto-login');
        const feedback = document.getElementById('modal-cookie-feedback');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mencoba Login ke Portal BPD...';

        fetch("{{ route('pembayaran.tarik-data.auto-login') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-bolt"></i> Jalankan Auto-Login Sekarang';

            feedback.style.display = 'block';
            if (data.success) {
                feedback.style.background = '#dcfce7';
                feedback.style.color = '#15803d';
                feedback.innerText = data.message;
                setTimeout(() => { location.reload(); }, 1200);
            } else {
                feedback.style.background = '#fee2e2';
                feedback.style.color = '#b91c1c';
                feedback.innerText = data.message;
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-bolt"></i> Jalankan Auto-Login Sekarang';
            feedback.style.display = 'block';
            feedback.style.background = '#fee2e2';
            feedback.style.color = '#b91c1c';
            feedback.innerText = 'Error: ' + err.message;
        });
    }

    function submitQuickCookie() {
        const cookieVal = document.getElementById('modal_session_cookie').value.trim();
        const feedback  = document.getElementById('modal-cookie-feedback');
        const btnSave   = document.getElementById('btn-save-cookie-modal');

        if (!cookieVal) {
            feedback.style.display = 'block';
            feedback.style.background = '#fee2e2';
            feedback.style.color = '#b91c1c';
            feedback.innerText = 'Cookie tidak boleh kosong!';
            return;
        }

        btnSave.disabled = true;
        btnSave.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

        fetch("{{ route('pembayaran.tarik-data.save-cookie') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({ session_cookie: cookieVal })
        })
        .then(res => res.json())
        .then(data => {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="fa-solid fa-save"></i> Simpan Cookie';

            if (data.success) {
                feedback.style.display = 'block';
                feedback.style.background = '#dcfce7';
                feedback.style.color = '#15803d';
                feedback.innerText = data.message;

                setTimeout(() => {
                    const modalEl = document.getElementById('modalCookieBpd');
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                    location.reload();
                }, 1000);
            } else {
                feedback.style.display = 'block';
                feedback.style.background = '#fee2e2';
                feedback.style.color = '#b91c1c';
                feedback.innerText = data.message || 'Gagal menyimpan cookie.';
            }
        })
        .catch(err => {
            btnSave.disabled = false;
            btnSave.innerHTML = '<i class="fa-solid fa-save"></i> Simpan Cookie';
            feedback.style.display = 'block';
            feedback.style.background = '#fee2e2';
            feedback.style.color = '#b91c1c';
            feedback.innerText = 'Error: ' + err.message;
        });
    }

    function resetFilterBpd() {
        document.getElementById('filter_parent_va').value = '';
        document.getElementById('filter_periode').value = '';
        document.getElementById('filter_status').value = 'semua';
        document.getElementById('filter_search').value = '';
        if (document.getElementById('filter_tahun')) {
            document.getElementById('filter_tahun').value = '{{ $currentTahun }}';
        }
        updatePrefixInfo();
    }

    function triggerFetchBpd() {
        if (isFetching) return;

        const periode = document.getElementById('filter_periode').value.trim();
        const status  = document.getElementById('filter_status').value;
        const parentVa= document.getElementById('filter_parent_va').value;
        const search  = document.getElementById('filter_search').value.trim();
        const tahun   = document.getElementById('filter_tahun') ? document.getElementById('filter_tahun').value.trim() : '{{ $currentTahun }}';

        // UI states
        isFetching = true;
        document.getElementById('btn-submit-filter').disabled = true;
        document.getElementById('btn-submit-filter').innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menarik Data...';

        document.getElementById('loading-container').style.display = 'block';
        document.getElementById('error-banner').style.display = 'none';
        document.getElementById('empty-state-container').style.display = 'none';
        document.getElementById('summary-container').style.display = 'none';
        document.getElementById('table-card').style.display = 'none';

        // Timeout 3 menit untuk multi-page fetch (9000+ record butuh waktu)
        const controller = new AbortController();
        const timeoutId  = setTimeout(() => controller.abort(), 180000);

        fetch("{{ route('pembayaran.tarik-data.fetch') }}", {
            method: "POST",
            signal: controller.signal,
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({
                periode_waktu: periode,
                status_transaksi: status,
                parent_va: parentVa,
                search: search,
                tahun: tahun,
                length: 0  // 0 = ambil semua data
            })
        })
        .then(res => res.json())
        .then(response => {
            clearTimeout(timeoutId);
            isFetching = false;
            document.getElementById('btn-submit-filter').disabled = false;
            document.getElementById('btn-submit-filter').innerHTML = '<i class="fa-solid fa-filter"></i> Filter & Tarik Data';
            document.getElementById('loading-container').style.display = 'none';

            if (!response.success) {
                document.getElementById('error-banner').style.display = 'block';
                document.getElementById('error-title').innerText = 'Gagal Mengambil Data BPD DIY';
                document.getElementById('error-message').innerText = response.message;
                document.getElementById('empty-state-container').style.display = 'block';

                if (response.need_cookie) {
                    openCookieModal();
                }
                return;
            }

            rawBpdData = response.data || [];
            renderSummary(response.summary);
            renderTable(rawBpdData);
        })
        .catch(err => {
            clearTimeout(timeoutId);
            isFetching = false;
            document.getElementById('btn-submit-filter').disabled = false;
            document.getElementById('btn-submit-filter').innerHTML = '<i class="fa-solid fa-filter"></i> Filter & Tarik Data';
            document.getElementById('loading-container').style.display = 'none';
            document.getElementById('error-banner').style.display = 'block';
            document.getElementById('error-title').innerText = err.name === 'AbortError' ? 'Timeout — Data Terlalu Besar' : 'Error Koneksi / Script';

            document.getElementById('error-message').innerText = err.message;
            document.getElementById('empty-state-container').style.display = 'block';
        });
    }

    function formatRupiah(number) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(number);
    }

    function renderSummary(summary) {
        if (!summary) return;

        document.getElementById('summary-container').style.display = 'block';
        document.getElementById('sum_total_records').innerText = summary.total_records.toLocaleString('id-ID');
        document.getElementById('sum_total_nilai').innerText = formatRupiah(summary.total_nilai);
        document.getElementById('sum_total_terbayar').innerText = formatRupiah(summary.total_terbayar);
        document.getElementById('sum_total_sisa').innerText = formatRupiah(summary.total_sisa);

        const persen = summary.total_nilai > 0 ? Math.round((summary.total_terbayar / summary.total_nilai) * 100) : 0;
        document.getElementById('sum_persen_bayar').innerText = persen + '% terbayar';

        document.getElementById('sum_status_breakdown').innerText =
            `Lunas: ${summary.count_lunas} • Sebagian: ${summary.count_sebagian} • Belum: ${summary.count_belum}`;
    }

    function renderTable(data) {
        const tbody = document.getElementById('bpd-table-tbody');
        tbody.innerHTML = '';

        if (!data || data.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="12" style="text-align: center; padding: 40px 20px; color: #64748b;">
                        <i class="fa-solid fa-folder-open" style="font-size: 2rem; margin-bottom: 8px; display: block; opacity: 0.5;"></i>
                        Tidak ada data tagihan yang ditemukan untuk filter yang dipilih.
                    </td>
                </tr>
            `;
            document.getElementById('table-card').style.display = 'block';
            document.getElementById('table-subtitle').innerText = '0 baris data ditemukan';
            document.getElementById('total-count').innerText = '0';
            document.getElementById('selected-count').innerText = '0';
            return;
        }

        data.forEach((row, idx) => {
            const tr = document.createElement('tr');
            tr.id = `row-bpd-${idx}`;
            tr.setAttribute('data-search', `${row.trx_id} ${row.nomor_va} ${row.nama_va} ${row.nomor_identitas} ${row.kelas}`.toLowerCase());

            // Badge Status
            let statusBadge = '';
            if (row.status === 'lunas') {
                statusBadge = '<span class="badge" style="background: #22c55e; color: #fff; font-size: 0.75rem; padding: 4px 8px; border-radius: 4px;"><i class="fa-solid fa-check"></i> Lunas</span>';
            } else if (row.status === 'sebagian') {
                statusBadge = '<span class="badge" style="background: #f59e0b; color: #fff; font-size: 0.75rem; padding: 4px 8px; border-radius: 4px;"><i class="fa-solid fa-clock-rotate-left"></i> Sebagian</span>';
            } else {
                statusBadge = '<span class="badge" style="background: #ef4444; color: #fff; font-size: 0.75rem; padding: 4px 8px; border-radius: 4px;"><i class="fa-solid fa-xmark"></i> Belum Bayar</span>';
            }

            // Badge DB
            let dbBadge = '';
            if (row.in_db) {
                dbBadge = '<span class="badge" style="background: #dcfce7; color: #166534; font-size: 0.72rem; border: 1px solid #bbf7d0;" title="Sudah ada di database"><i class="fa-solid fa-database"></i> Di DB</span>';
            } else {
                dbBadge = '<span class="badge" style="background: #f1f5f9; color: #64748b; font-size: 0.72rem; border: 1px solid #e2e8f0;" title="Belum disimpan ke database"><i class="fa-solid fa-plus"></i> Baru</span>';
            }

            // Jenis tagihan badge
            let jenisTagihanLabel = 'SPP';
            if (row.jenis_tagihan === 'non_spp') jenisTagihanLabel = 'Non SPP';
            if (row.jenis_tagihan === 'tunggakan') jenisTagihanLabel = 'Tunggakan';

            tr.innerHTML = `
                <td style="text-align: center; padding: 10px 8px;">
                    <input type="checkbox" class="row-checkbox" data-index="${idx}" onchange="updateSelectedCount()" checked style="cursor: pointer;">
                </td>
                <td style="text-align: center; color: #64748b; font-weight: 600;">${idx + 1}</td>
                <td>
                    <span style="font-family: monospace; font-size: 0.8rem; font-weight: 700; color: #334155;">${row.trx_id || '-'}</span>
                </td>
                <td>
                    <div style="font-family: monospace; font-size: 0.85rem; font-weight: 800; color: #1e40af; letter-spacing: 0.3px;">${row.nomor_va}</div>
                    <div style="font-size: 0.72rem; color: #64748b;">${jenisTagihanLabel} (Kode: ${row.kode_tagihan})</div>
                </td>
                <td>
                    <div style="font-weight: 700; color: #0f172a; font-size: 0.88rem;">${row.nama_siswa || row.nama_va}</div>
                    <div style="font-size: 0.75rem; color: #64748b; display: flex; align-items: center; gap: 6px; margin-top: 2px;">
                        <span class="badge" style="background: #f1f5f9; color: #475569; font-weight: 600;">Kelas: ${row.kelas || '-'}</span>
                        ${row.siswa_terdaftar ? '<span style="color: #16a34a; font-size: 0.7rem;"><i class="fa-solid fa-check-circle"></i> Siswa Terdaftar</span>' : '<span style="color: #94a3b8; font-size: 0.7rem;"><i class="fa-solid fa-circle-question"></i> Siswa Luar</span>'}
                    </div>
                </td>
                <td>
                    <span style="font-family: monospace; font-weight: 700; font-size: 0.88rem; color: #475569; background: #f8fafc; padding: 2px 6px; border-radius: 4px; border: 1px solid #e2e8f0;">
                        ${row.nis_terdaftar ? row.nis_terdaftar : (row.nomor_identitas || '-')}
                    </span>
                    ${row.nis_terdaftar && row.nomor_identitas && String(row.nis_terdaftar) !== String(row.nomor_identitas) ? `<div style="font-size: 0.68rem; color: #94a3b8; margin-top: 2px;" title="Nomor Identitas di Portal BPD DIY">BPD: ${row.nomor_identitas}</div>` : ''}
                </td>
                <td style="text-align: right; font-weight: 700; color: #1e293b;">
                    ${formatRupiah(row.total_nilai_tagihan)}
                </td>
                <td style="text-align: right; font-weight: 700; color: #059669;">
                    ${formatRupiah(row.nominal_telah_dibayar)}
                </td>
                <td style="text-align: right; font-weight: 700; color: ${row.sisa_pembayaran > 0 ? '#dc2626' : '#64748b'};">
                    ${formatRupiah(row.sisa_pembayaran)}
                </td>
                <td style="font-size: 0.8rem; color: #475569;">
                    ${row.tanggal_dibayar ? `<i class="fa-regular fa-clock" style="color:#64748b;"></i> ${row.tanggal_dibayar}` : '<span style="color:#94a3b8;">-</span>'}
                </td>
                <td style="text-align: center;">${statusBadge}</td>
                <td style="text-align: center;">${dbBadge}</td>
            `;

            tbody.appendChild(tr);
        });

        document.getElementById('table-card').style.display = 'block';
        document.getElementById('table-subtitle').innerText = `Menampilkan ${data.length} baris data dari portal BPD DIY`;
        document.getElementById('total-count').innerText = data.length;
        updateSelectedCount();
    }

    function toggleCheckAll(master) {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => {
            // Hanya toggle yang barisnya terlihat (jika sedang difilter lokal)
            const tr = cb.closest('tr');
            if (tr && tr.style.display !== 'none') {
                cb.checked = master.checked;
            }
        });
        updateSelectedCount();
    }

    function selectAllVisible(status) {
        document.getElementById('check-all').checked = status;
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => {
            const tr = cb.closest('tr');
            if (tr && tr.style.display !== 'none') {
                cb.checked = status;
            }
        });
        updateSelectedCount();
    }

    function updateSelectedCount() {
        const checked = document.querySelectorAll('.row-checkbox:checked');
        document.getElementById('selected-count').innerText = checked.length;
    }

    function filterLocalTable() {
        const query = document.getElementById('local_table_search').value.toLowerCase().trim();
        const rows  = document.querySelectorAll('#bpd-table-tbody tr');

        let visibleCount = 0;
        rows.forEach(tr => {
            const searchData = tr.getAttribute('data-search') || '';
            if (searchData.includes(query)) {
                tr.style.display = '';
                visibleCount++;
            } else {
                tr.style.display = 'none';
            }
        });

        document.getElementById('table-subtitle').innerText = query
            ? `Menampilkan ${visibleCount} dari ${rawBpdData.length} baris hasil filter pencarian lokal`
            : `Menampilkan ${rawBpdData.length} baris data dari portal BPD DIY`;
    }

    function getSelectedRowsData() {
        const selected = [];
        const checkboxes = document.querySelectorAll('.row-checkbox:checked');
        checkboxes.forEach(cb => {
            const idx = parseInt(cb.getAttribute('data-index'), 10);
            if (!isNaN(idx) && rawBpdData[idx]) {
                selected.push(rawBpdData[idx]);
            }
        });
        return selected;
    }

    function syncSelectedToDatabase() {
        const selected = getSelectedRowsData();
        if (selected.length === 0) {
            alert('Silakan pilih minimal satu baris data tagihan untuk disimpan ke database.');
            return;
        }

        if (!confirm(`Perhatian: Menyimpan data ini akan mengosongkan seluruh data lama di tabel tagihan_pembayaran dan menggantinya dengan ${selected.length} data tagihan terbaru dari portal BPD DIY.\n\nApakah Anda yakin ingin melanjutkan?`)) {
            return;
        }

        const btnSync = document.getElementById('btn-sync-all');
        btnSync.disabled = true;
        btnSync.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan...';

        fetch("{{ route('pembayaran.tarik-data.sync') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                "Accept": "application/json"
            },
            body: JSON.stringify({ rows: selected })
        })
        .then(res => res.json())
        .then(data => {
            btnSync.disabled = false;
            btnSync.innerHTML = '<i class="fa-solid fa-cloud-arrow-down"></i> Simpan ke Database';

            if (data.success) {
                alert('✅ ' + data.message);
                // Update label in_db pada raw data
                selected.forEach(s => {
                    s.in_db = true;
                });
                renderTable(rawBpdData);
            } else {
                alert('❌ ' + (data.message || 'Gagal menyimpan data ke database.'));
            }
        })
        .catch(err => {
            btnSync.disabled = false;
            btnSync.innerHTML = '<i class="fa-solid fa-cloud-arrow-down"></i> Simpan ke Database';
            alert('Error: ' + err.message);
        });
    }

    function exportToCsv() {
        if (!rawBpdData || rawBpdData.length === 0) {
            alert('Tidak ada data untuk diexport.');
            return;
        }

        const headers = ['NO', 'TRX_ID', 'NO_VA', 'NAMA_VA', 'NAMA_SISWA', 'KELAS', 'NIS', 'TOTAL_TAGIHAN', 'TELAH_DIBAYAR', 'SISA_PEMBAYARAN', 'TANGGAL_DIBAYAR', 'STATUS'];
        const csvRows = [];
        csvRows.push(headers.join(','));

        rawBpdData.forEach((row, i) => {
            const values = [
                i + 1,
                `"${row.trx_id || ''}"`,
                `"${row.nomor_va || ''}"`,
                `"${(row.nama_va || '').replace(/"/g, '""')}"`,
                `"${(row.nama_siswa || '').replace(/"/g, '""')}"`,
                `"${row.kelas || ''}"`,
                `"${row.nomor_identitas || ''}"`,
                row.total_nilai_tagihan,
                row.nominal_telah_dibayar,
                row.sisa_pembayaran,
                `"${row.tanggal_dibayar || ''}"`,
                `"${row.status || ''}"`
            ];
            csvRows.push(values.join(','));
        });

        const csvString = '\uFEFF' + csvRows.join('\n');
        const blob = new Blob([csvString], { type: 'text/csv;charset=utf-8;' });
        const url  = URL.createObjectURL(blob);
        const a    = document.createElement('a');
        a.href     = url;
        a.download = `Tagihan_VA_BPD_DIY_${new Date().toISOString().slice(0,10)}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
    }

    function printTableResults() {
        window.print();
    }
</script>
@endpush
@endsection
