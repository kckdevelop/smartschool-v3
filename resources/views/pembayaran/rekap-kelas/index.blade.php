@extends('layouts.app')

@section('title', 'Rekap Data Tagihan Per Kelas — SmartSchool')
@section('header_title', 'Rekap Tagihan Per Kelas')
@section('header_subtitle', 'Ringkasan tagihan & generasi Virtual Account (VA) massal per kelas')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Filter Header --}}
    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('pembayaran.rekap-kelas.index') }}" method="GET" style="display: flex; flex-wrap: wrap; gap: 16px; align-items: flex-end;">
                <div style="flex: 1; min-width: 180px;">
                    <label class="form-label" style="font-size: 0.85rem; font-weight: 600;">Tahun Ajaran</label>
                    <select name="id_tahun" class="form-control">
                        <option value="">-- Semua Tahun --</option>
                        @foreach($tahunList as $t)
                            <option value="{{ $t->id_tahun }}" {{ $selectedTahun == $t->id_tahun ? 'selected' : '' }}>
                                {{ $t->tahun_ajaran }} {{ $t->status === 'aktif' ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="flex: 1; min-width: 160px;">
                    <label class="form-label" style="font-size: 0.85rem; font-weight: 600;">Semester</label>
                    <select name="id_semester" class="form-control">
                        <option value="">-- Semua Semester --</option>
                        @foreach($semesterList as $s)
                            <option value="{{ $s->id_semester }}" {{ $selectedSemester == $s->id_semester ? 'selected' : '' }}>
                                {{ $s->semester }} {{ $s->status === 'aktif' ? '(Aktif)' : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="flex: 1; min-width: 180px;">
                    <label class="form-label" style="font-size: 0.85rem; font-weight: 600;">Kelas</label>
                    <select name="id_kelas" class="form-control">
                        <option value="">-- Semua Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}" {{ $selectedKelas == $k->id_kelas ? 'selected' : '' }}>
                                {{ $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div style="flex: 1; min-width: 160px;">
                    <label class="form-label" style="font-size: 0.85rem; font-weight: 600;">Jenis Tagihan</label>
                    <select name="jenis_tagihan" class="form-control">
                        <option value="">-- Semua Jenis --</option>
                        <option value="spp" {{ $selectedJenis === 'spp' ? 'selected' : '' }}>SPP (Kode 00)</option>
                        <option value="non_spp" {{ $selectedJenis === 'non_spp' ? 'selected' : '' }}>Non SPP (Kode 01)</option>
                        <option value="tunggakan" {{ $selectedJenis === 'tunggakan' ? 'selected' : '' }}>Tunggakan (Kode 02)</option>
                    </select>
                </div>

                <div style="display: flex; gap: 8px;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('pembayaran.rekap-kelas.index') }}" class="btn btn-secondary" title="Reset Filter">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Ringkasan Statistika --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; margin-bottom: 24px;">
        <div class="card" style="border-left: 4px solid #3b82f6;">
            <div class="card-body" style="padding: 18px;">
                <div style="font-size: 0.82rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Total Nominal Tagihan</div>
                <div style="font-size: 1.4rem; font-weight: 800; color: #3b82f6; margin-top: 4px;">
                    Rp {{ number_format($totalNominalAll, 0, ',', '.') }}
                </div>
                <div style="font-size: 0.8rem; color: var(--text-muted); margin-top: 4px;">{{ $totalSiswaAll }} Siswa Aktif</div>
            </div>
        </div>

        <div class="card" style="border-left: 4px solid #10b981;">
            <div class="card-body" style="padding: 18px;">
                <div style="font-size: 0.82rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Total Lunas</div>
                <div style="font-size: 1.4rem; font-weight: 800; color: #10b981; margin-top: 4px;">
                    Rp {{ number_format($totalLunasAll, 0, ',', '.') }}
                </div>
                <div style="font-size: 0.8rem; color: #10b981; margin-top: 4px;">Terbayar</div>
            </div>
        </div>

        <div class="card" style="border-left: 4px solid #ef4444;">
            <div class="card-body" style="padding: 18px;">
                <div style="font-size: 0.82rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Belum Lunas</div>
                <div style="font-size: 1.4rem; font-weight: 800; color: #ef4444; margin-top: 4px;">
                    Rp {{ number_format($totalBelumLunasAll, 0, ',', '.') }}
                </div>
                <div style="font-size: 0.8rem; color: #ef4444; margin-top: 4px;">Tunggakan / Piutang</div>
            </div>
        </div>

        <div class="card" style="border-left: 4px solid #8b5cf6;">
            <div class="card-body" style="padding: 18px;">
                <div style="font-size: 0.82rem; color: var(--text-muted); font-weight: 600; text-transform: uppercase;">Format VA Institusi</div>
                <div style="font-size: 1.2rem; font-weight: 800; color: #8b5cf6; margin-top: 4px;">
                    ID: {{ $setting->id_institusi ?? '1023' }}
                </div>
                <div style="font-size: 0.78rem; color: var(--text-muted); margin-top: 4px;">Kode: SPP(00), Non(01), Tunggakan(02)</div>
            </div>
        </div>
    </div>

    {{-- Tabel Rekap Data Per Kelas --}}
    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 12px;">
            <h3 class="card-title"><i class="fa-solid fa-list-check"></i> Rekap Tagihan Per Kelas</h3>
            <button type="button" class="btn btn-success" onclick="openModal('modal-generate-kelas')">
                <i class="fa-solid fa-plus-circle"></i> Generate VA Massal Per Kelas
            </button>
        </div>
        <div class="card-body p-0" style="overflow-x: auto;">
            <table class="table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--bg-hover, #f8fafc); text-align: left;">
                        <th style="padding: 12px 16px;">No</th>
                        <th style="padding: 12px 16px;">Nama Kelas</th>
                        <th style="padding: 12px 16px; text-align: center;">Jumlah Siswa</th>
                        <th style="padding: 12px 16px; text-align: right;">SPP (00)</th>
                        <th style="padding: 12px 16px; text-align: right;">Non SPP (01)</th>
                        <th style="padding: 12px 16px; text-align: right;">Tunggakan (02)</th>
                        <th style="padding: 12px 16px; text-align: right;">Total Tagihan</th>
                        <th style="padding: 12px 16px; text-align: right;">Total Lunas</th>
                        <th style="padding: 12px 16px; text-align: right;">Sisa Belum Bayar</th>
                        <th style="padding: 12px 16px; text-align: center;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rekapData as $index => $row)
                        <tr style="border-bottom: 1px solid var(--border-color, #e2e8f0);">
                            <td style="padding: 12px 16px;">{{ $index + 1 }}</td>
                            <td style="padding: 12px 16px; font-weight: 700; color: var(--text-primary);">
                                {{ $row['kelas']->nama_kelas }}
                            </td>
                            <td style="padding: 12px 16px; text-align: center;">
                                <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: #3b82f6; padding: 4px 10px; border-radius: 20px; font-weight: 700;">
                                    {{ $row['jumlah_siswa'] }} Siswa
                                </span>
                            </td>
                            <td style="padding: 12px 16px; text-align: right;">
                                Rp {{ number_format($row['total_spp'], 0, ',', '.') }}
                            </td>
                            <td style="padding: 12px 16px; text-align: right;">
                                Rp {{ number_format($row['total_non_spp'], 0, ',', '.') }}
                            </td>
                            <td style="padding: 12px 16px; text-align: right;">
                                Rp {{ number_format($row['total_tunggakan'], 0, ',', '.') }}
                            </td>
                            <td style="padding: 12px 16px; text-align: right; font-weight: 700;">
                                Rp {{ number_format($row['total_nominal'], 0, ',', '.') }}
                            </td>
                            <td style="padding: 12px 16px; text-align: right; color: #10b981; font-weight: 700;">
                                Rp {{ number_format($row['total_lunas'], 0, ',', '.') }}
                            </td>
                            <td style="padding: 12px 16px; text-align: right; color: #ef4444; font-weight: 700;">
                                Rp {{ number_format($row['total_belum_lunas'], 0, ',', '.') }}
                            </td>
                            <td style="padding: 12px 16px; text-align: center;">
                                <div style="display: flex; gap: 6px; justify-content: center;">
                                    <button type="button" class="btn btn-sm btn-primary" 
                                            onclick="openGenerateModal('{{ $row['kelas']->id_kelas }}', '{{ $row['kelas']->nama_kelas }}')"
                                            title="Generate Tagihan VA">
                                        <i class="fa-solid fa-wand-magic-sparkles"></i> Generate VA
                                    </button>
                                    <a href="{{ route('pembayaran.rekap-siswa.index', ['id_kelas' => $row['kelas']->id_kelas]) }}" 
                                       class="btn btn-sm btn-info" title="Lihat Detail Tagihan Siswa">
                                        <i class="fa-solid fa-users-line"></i> Detail
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" style="text-align: center; padding: 24px; color: var(--text-muted);">
                                Belum ada data kelas atau tagihan tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal Generate VA Massal Per Kelas --}}
<div class="modal-overlay" id="modal-generate-kelas" style="display: none;">
    <div class="modal modal-md" style="max-width: 540px;">
        <div class="modal-header">
            <h3><i class="fa-solid fa-wand-magic-sparkles"></i> Generate Tagihan & VA Massal</h3>
            <button type="button" onclick="closeModal('modal-generate-kelas')" class="modal-close">&times;</button>
        </div>
        <form action="{{ route('pembayaran.rekap-kelas.generate') }}" method="POST">
            @csrf
            <div class="modal-body" style="display: flex; flex-direction: column; gap: 16px;">
                <div class="form-group">
                    <label class="form-label">Pilih Kelas <span class="text-danger">*</span></label>
                    <select name="id_kelas" id="generate_id_kelas" class="form-control" required>
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Jenis Tagihan <span class="text-danger">*</span></label>
                    <select name="jenis_tagihan" class="form-control" required>
                        <option value="spp">SPP (Kode 00)</option>
                        <option value="non_spp">Non SPP (Kode 01)</option>
                        <option value="tunggakan">Tunggakan (Kode 02)</option>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Nama Tagihan <span class="text-danger">*</span></label>
                    <input type="text" name="nama_tagihan" class="form-control" placeholder="Contoh: SPP Bulan September 2026" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Nominal (Rp) <span class="text-danger">*</span></label>
                    <input type="number" name="nominal" class="form-control" placeholder="Contoh: 250000" min="0" step="1000" required>
                </div>

                <div class="form-grid-2" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <div class="form-group">
                        <label class="form-label">Tanggal Tagihan <span class="text-danger">*</span></label>
                        <input type="date" name="tanggal_tagihan" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Jatuh Tempo</label>
                        <input type="date" name="tanggal_jatuh_tempo" class="form-control">
                    </div>
                </div>

                <div style="background: var(--bg-hover, #f8fafc); padding: 12px; border-radius: 8px; font-size: 0.83rem; color: var(--text-secondary);">
                    <i class="fa-solid fa-circle-info" style="color: #3b82f6;"></i> Nomor Virtual Account (VA) akan digenerate otomatis untuk seluruh siswa di kelas sesuai rumus:<br>
                    <code>{{ $setting->id_institusi ?? '1023' }} + [Tahun] + [NIS] + [Kode Tagihan]</code>
                </div>
            </div>
            <div class="modal-footer" style="display: flex; gap: 10px; justify-content: flex-end; padding: 16px 20px; border-top: 1px solid var(--border-color, #e2e8f0);">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-generate-kelas')">Batal</button>
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-check"></i> Proses Generate VA</button>
            </div>
        </form>
    </div>
</div>

<script>
function openGenerateModal(idKelas, namaKelas) {
    document.getElementById('generate_id_kelas').value = idKelas;
    openModal('modal-generate-kelas');
}
function openModal(id) {
    document.getElementById(id).style.display = 'flex';
}
function closeModal(id) {
    document.getElementById(id).style.display = 'none';
}
</script>
@endsection
