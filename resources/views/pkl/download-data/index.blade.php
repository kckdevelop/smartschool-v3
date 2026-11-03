@extends('layouts.app')

@section('title', 'Download Data PKL — SmartSchool')
@section('header_title', 'Download Data PKL')
@section('header_subtitle', 'Unduh data PKL dalam format Excel sesuai kebutuhan')

@push('styles')
<style>
    /* ── Dataset Cards ── */
    .dataset-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .dataset-card {
        border: 2.5px solid #e2e8f0;
        border-radius: 14px;
        padding: 20px;
        cursor: pointer;
        transition: all .22s cubic-bezier(.4,0,.2,1);
        background: #fff;
        position: relative;
        overflow: hidden;
        user-select: none;
    }
    .dataset-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: var(--ds-color, #6366f1);
        transform: scaleX(0);
        transition: transform .22s;
    }
    .dataset-card:hover {
        border-color: var(--ds-color, #6366f1);
        box-shadow: 0 6px 24px 0 rgba(99,102,241,.12);
        transform: translateY(-2px);
    }
    .dataset-card.selected {
        border-color: var(--ds-color, #6366f1);
        background: linear-gradient(135deg, #f8f7ff 0%, #faf9ff 100%);
        box-shadow: 0 4px 20px 0 rgba(99,102,241,.14);
    }
    .dataset-card.selected::before { transform: scaleX(1); }
    .dataset-card .ds-icon {
        width: 44px; height: 44px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; margin-bottom: 12px;
        background: color-mix(in srgb, var(--ds-color) 15%, transparent);
        color: var(--ds-color);
    }
    .dataset-card .ds-title { font-weight: 700; font-size: .9rem; color: var(--text-primary); }
    .dataset-card .ds-check {
        position: absolute; top: 14px; right: 14px;
        width: 22px; height: 22px; border-radius: 50%;
        background: var(--ds-color); color: #fff;
        display: none; align-items: center; justify-content: center;
        font-size: .75rem;
    }
    .dataset-card.selected .ds-check { display: flex; }

    /* ── Column Checkboxes ── */
    .col-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(210px, 1fr));
        gap: 10px;
        margin-top: 14px;
    }
    .col-item {
        display: flex; align-items: center; gap: 10px;
        padding: 10px 14px;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        cursor: pointer;
        transition: all .18s;
        background: #fff;
        font-size: .85rem; font-weight: 600;
        color: var(--text-primary);
    }
    .col-item:hover { border-color: #6366f1; background: #f8f7ff; }
    .col-item.checked {
        border-color: #6366f1;
        background: linear-gradient(135deg, #f0f0ff 0%, #f8f7ff 100%);
        color: #4338ca;
    }
    .col-item input[type="checkbox"] { width: 16px; height: 16px; accent-color: #6366f1; flex-shrink: 0; }

    /* ── Filter Panel ── */
    .filter-panel {
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        padding: 18px 20px;
        margin-top: 20px;
        display: none;
    }
    .filter-panel.show { display: block; }

    /* ── Aksi Pilih Semua ── */
    .col-actions { display: flex; gap: 10px; margin-bottom: 4px; flex-wrap: wrap; align-items: center; }
    .col-actions span { font-size: .78rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: .5px; }
    .btn-col-action { padding: 4px 12px; border-radius: 6px; font-size: .78rem; font-weight: 700; border: 1.5px solid; cursor: pointer; transition: all .16s; }
    .btn-col-action.select-all { border-color: #6366f1; color: #6366f1; background: transparent; }
    .btn-col-action.select-all:hover { background: #6366f1; color: #fff; }
    .btn-col-action.clear-all { border-color: #94a3b8; color: #64748b; background: transparent; }
    .btn-col-action.clear-all:hover { background: #94a3b8; color: #fff; }

    /* ── Download Button ── */
    .download-section {
        background: linear-gradient(135deg, #4338ca 0%, #6366f1 100%);
        border-radius: 16px;
        padding: 28px 32px;
        margin-top: 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        flex-wrap: wrap;
    }
    .download-info h3 { color: #fff; font-size: 1.05rem; font-weight: 800; margin: 0 0 4px; }
    .download-info p { color: rgba(255,255,255,.8); font-size: .83rem; margin: 0; }
    .btn-download-excel {
        background: #fff;
        color: #4338ca;
        border: none;
        border-radius: 10px;
        padding: 12px 28px;
        font-weight: 800;
        font-size: .95rem;
        cursor: pointer;
        display: flex; align-items: center; gap: 10px;
        transition: all .2s;
        white-space: nowrap;
    }
    .btn-download-excel:hover { background: #ede9fe; transform: scale(1.03); }
    .btn-download-excel:disabled { opacity: .6; cursor: not-allowed; transform: none; }

    /* ── Empty state ── */
    .empty-step {
        text-align: center; padding: 32px;
        color: var(--text-muted); font-size: .88rem;
    }
    .empty-step i { font-size: 2.5rem; opacity: .25; display: block; margin-bottom: 10px; }
</style>
@endpush

@section('content')
<div class="page-content">
    @include('partials.flash')

    <form method="POST" action="{{ route('pkl.download-data.download') }}" id="form-download">
        @csrf

        {{-- ══════════════════════════════════════
             LANGKAH 1: Pilih Dataset
        ═══════════════════════════════════════ --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header" style="padding:18px 24px; display:flex; align-items:center; gap:12px;">
                <div style="width:32px;height:32px;border-radius:8px;background:#ede9fe;color:#6366f1;display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:800;">1</div>
                <div>
                    <h2 class="card-title" style="margin:0;font-size:1rem;">Pilih Data yang Akan Diunduh</h2>
                    <p style="margin:0;font-size:.78rem;color:var(--text-muted);">Klik salah satu kartu di bawah untuk memilih jenis data</p>
                </div>
            </div>
            <div class="card-body" style="padding:20px 24px;">
                <input type="hidden" name="dataset" id="input-dataset" value="">
                <div class="dataset-grid">
                    @foreach($datasets as $key => $ds)
                    <div class="dataset-card" style="--ds-color:{{ $ds['color'] }};"
                         onclick="selectDataset('{{ $key }}')" id="card-{{ $key }}" data-key="{{ $key }}">
                        <div class="ds-icon"><i class="fa-solid {{ $ds['icon'] }}"></i></div>
                        <div class="ds-title">{{ $ds['label'] }}</div>
                        <div class="ds-check"><i class="fa-solid fa-check"></i></div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             LANGKAH 2: Filter Data
        ═══════════════════════════════════════ --}}
        <div class="card" style="margin-bottom:20px;" id="step-filter">
            <div class="card-header" style="padding:18px 24px; display:flex; align-items:center; gap:12px;">
                <div style="width:32px;height:32px;border-radius:8px;background:#dbeafe;color:#0369a1;display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:800;">2</div>
                <div>
                    <h2 class="card-title" style="margin:0;font-size:1rem;">Filter Data <span style="color:var(--text-muted);font-weight:400;font-size:.82rem;">(Opsional)</span></h2>
                    <p style="margin:0;font-size:.78rem;color:var(--text-muted);">Persempit data yang akan diunduh</p>
                </div>
            </div>
            <div class="card-body" style="padding:20px 24px;">

                {{-- Filter untuk dataset PENEMPATAN --}}
                <div class="filter-panel" id="filter-penempatan">
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
                        <div>
                            <label class="form-label-sm" style="font-weight:700;color:var(--text-secondary);font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Gelombang PKL</label>
                            <select name="id_gelombang" class="form-control form-control-sm">
                                <option value="">-- Semua Gelombang --</option>
                                @foreach($gelombangList as $g)
                                <option value="{{ $g->id_gelombang }}" {{ optional($gelombangAktif)->id_gelombang == $g->id_gelombang ? 'selected' : '' }}>
                                    {{ $g->nama_gelombang }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label-sm" style="font-weight:700;color:var(--text-secondary);font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">DUDI / Perusahaan</label>
                            <select name="filter_dudi" class="form-control form-control-sm">
                                <option value="">-- Semua DUDI --</option>
                                @foreach($dudis as $d)
                                <option value="{{ $d->id_dudi }}">{{ $d->nama_dudi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="form-label-sm" style="font-weight:700;color:var(--text-secondary);font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Status Siswa</label>
                            <select name="filter_status" class="form-control form-control-sm">
                                <option value="">-- Semua Status --</option>
                                <option value="aktif">Aktif</option>
                                <option value="selesai">Selesai</option>
                                <option value="ditarik">Ditarik</option>
                                <option value="batal">Batal</option>
                                <option value="pindah">Pindah</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Filter untuk dataset DUDI --}}
                <div class="filter-panel" id="filter-dudi">
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
                        <div>
                            <label class="form-label-sm" style="font-weight:700;color:var(--text-secondary);font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Status DUDI</label>
                            <select name="filter_status_dudi" class="form-control form-control-sm">
                                <option value="">-- Semua Status --</option>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Non-Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Filter untuk dataset PEMBIMBING --}}
                <div class="filter-panel" id="filter-pembimbing">
                    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;">
                        <div>
                            <label class="form-label-sm" style="font-weight:700;color:var(--text-secondary);font-size:.75rem;text-transform:uppercase;letter-spacing:.5px;">Gelombang PKL</label>
                            <select name="id_gelombang_pembimbing" class="form-control form-control-sm" onchange="syncGelombang(this.value)">
                                <option value="">-- Semua Gelombang --</option>
                                @foreach($gelombangList as $g)
                                <option value="{{ $g->id_gelombang }}">{{ $g->nama_gelombang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Empty state sebelum pilih dataset --}}
                <div class="empty-step" id="filter-empty">
                    <i class="fa-solid fa-filter"></i>
                    Pilih dataset terlebih dahulu untuk melihat opsi filter
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             LANGKAH 3: Pilih Kolom
        ═══════════════════════════════════════ --}}
        <div class="card" style="margin-bottom:20px;">
            <div class="card-header" style="padding:18px 24px; display:flex; align-items:center; gap:12px;">
                <div style="width:32px;height:32px;border-radius:8px;background:#d1fae5;color:#065f46;display:flex;align-items:center;justify-content:center;font-size:1rem;font-weight:800;">3</div>
                <div>
                    <h2 class="card-title" style="margin:0;font-size:1rem;">Pilih Kolom yang Diinginkan</h2>
                    <p style="margin:0;font-size:.78rem;color:var(--text-muted);">Centang kolom yang akan muncul di file Excel</p>
                </div>
            </div>
            <div class="card-body" style="padding:20px 24px;">

                {{-- Panel kolom per dataset --}}
                @foreach($datasets as $key => $ds)
                <div id="cols-{{ $key }}" style="display:none;">
                    <div class="col-actions">
                        <span>Pilih Kolom</span>
                        <button type="button" class="btn-col-action select-all" onclick="toggleAllCols('{{ $key }}', true)">
                            <i class="fa-solid fa-check-double"></i> Pilih Semua
                        </button>
                        <button type="button" class="btn-col-action clear-all" onclick="toggleAllCols('{{ $key }}', false)">
                            <i class="fa-solid fa-eraser"></i> Hapus Semua
                        </button>
                        <span id="count-{{ $key }}" style="margin-left:auto;font-size:.8rem;color:var(--text-muted);">0 kolom dipilih</span>
                    </div>
                    <div class="col-grid">
                        @foreach($ds['columns'] as $colKey => $colLabel)
                        @if($colKey !== 'no')
                        <label class="col-item" id="label-{{ $key }}-{{ $colKey }}" onclick="toggleColItem(this)">
                            <input type="checkbox" name="columns[]" value="{{ $colKey }}"
                                   class="col-check col-check-{{ $key }}" checked
                                   onchange="updateCount('{{ $key }}')">
                            <span>{{ $colLabel }}</span>
                        </label>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endforeach

                {{-- Empty state sebelum pilih dataset --}}
                <div class="empty-step" id="cols-empty">
                    <i class="fa-solid fa-table-columns"></i>
                    Pilih dataset terlebih dahulu untuk melihat daftar kolom
                </div>
            </div>
        </div>

        {{-- ══════════════════════════════════════
             DOWNLOAD SECTION
        ═══════════════════════════════════════ --}}
        <div class="download-section" id="download-section" style="display:none;">
            <div class="download-info">
                <h3><i class="fa-solid fa-file-excel" style="margin-right:8px;"></i>Siap Diunduh!</h3>
                <p id="download-desc">Klik tombol untuk mengunduh data dalam format Excel (.xlsx)</p>
            </div>
            <button type="submit" class="btn-download-excel" id="btn-download">
                <i class="fa-solid fa-download"></i>
                <span>Download Excel</span>
            </button>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
    const datasets = @json($datasets);
    let currentDataset = null;

    function selectDataset(key) {
        currentDataset = key;
        document.getElementById('input-dataset').value = key;

        // Update card states
        document.querySelectorAll('.dataset-card').forEach(c => c.classList.remove('selected'));
        document.getElementById('card-' + key).classList.add('selected');

        // Show filter panel
        document.querySelectorAll('.filter-panel').forEach(p => p.classList.remove('show'));
        document.getElementById('filter-empty').style.display = 'none';
        const fp = document.getElementById('filter-' + key);
        if (fp) fp.classList.add('show');

        // Show columns
        Object.keys(datasets).forEach(k => {
            const el = document.getElementById('cols-' + k);
            if (el) el.style.display = 'none';
        });
        document.getElementById('cols-empty').style.display = 'none';
        const colEl = document.getElementById('cols-' + key);
        if (colEl) colEl.style.display = 'block';

        // Re-check all by default
        toggleAllCols(key, true);
        updateCount(key);
        updateDownloadSection();
    }

    function toggleColItem(label) {
        const cb = label.querySelector('input[type="checkbox"]');
        // The click on label will naturally toggle the checkbox;
        // we just update the visual state after the event bubbles
        setTimeout(() => {
            if (cb.checked) {
                label.classList.add('checked');
            } else {
                label.classList.remove('checked');
            }
            updateCount(currentDataset);
            updateDownloadSection();
        }, 0);
    }

    function toggleAllCols(key, check) {
        document.querySelectorAll('.col-check-' + key).forEach(cb => {
            cb.checked = check;
            const lbl = document.getElementById('label-' + key + '-' + cb.value);
            if (lbl) lbl.classList.toggle('checked', check);
        });
        updateCount(key);
        updateDownloadSection();
    }

    function updateCount(key) {
        if (!key) return;
        const checked = document.querySelectorAll('.col-check-' + key + ':checked').length;
        const el = document.getElementById('count-' + key);
        if (el) el.textContent = checked + ' kolom dipilih';
    }

    function updateDownloadSection() {
        const section = document.getElementById('download-section');
        const btn     = document.getElementById('btn-download');
        const desc    = document.getElementById('download-desc');

        if (!currentDataset) {
            section.style.display = 'none';
            return;
        }

        const checkedCount = document.querySelectorAll('.col-check-' + currentDataset + ':checked').length;
        section.style.display = 'flex';

        if (checkedCount === 0) {
            btn.disabled = true;
            desc.textContent = '⚠️ Pilih minimal 1 kolom untuk mengunduh';
        } else {
            btn.disabled = false;
            const label = datasets[currentDataset].label;
            desc.textContent = `Dataset: ${label} · ${checkedCount} kolom dipilih`;
        }
    }

    // Sinkronkan select gelombang pembimbing ke field yang sama
    function syncGelombang(val) {
        const mainSel = document.querySelector('select[name="id_gelombang"]');
        if (mainSel) mainSel.value = val;
    }

    // Form submit guard
    document.getElementById('form-download').addEventListener('submit', function(e) {
        if (!currentDataset) {
            e.preventDefault();
            alert('Silakan pilih dataset terlebih dahulu!');
            return;
        }
        const checked = document.querySelectorAll('.col-check-' + currentDataset + ':checked').length;
        if (checked === 0) {
            e.preventDefault();
            alert('Pilih minimal 1 kolom!');
            return;
        }
        const btn = document.getElementById('btn-download');
        btn.disabled = true;
        btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> <span>Menyiapkan...</span>';
        setTimeout(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fa-solid fa-download"></i> <span>Download Excel</span>';
        }, 5000);
    });

    // Init — jika ada gelombang aktif, pre-select penempatan
    @if($gelombangAktif)
    selectDataset('penempatan');
    @endif
</script>
@endpush
