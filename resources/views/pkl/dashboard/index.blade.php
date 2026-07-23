@extends('layouts.app')

@section('title', 'Dashboard PKL — SmartSchool')
@section('header_title', 'Dashboard PKL')
@section('header_subtitle', 'Ringkasan aktivitas Praktik Kerja Lapangan')

@section('content')
<div class="page-content">
    @include('partials.flash')

    {{-- Gelombang Aktif Banner --}}
    @if($gelombangAktif)
    <div style="background: linear-gradient(135deg, #0d9488, #6366f1); border-radius: 16px; padding: 20px 28px; color: #fff; margin-bottom: 24px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px;">
        <div>
            <div style="font-size:.8rem; opacity:.8; font-weight:600; text-transform:uppercase; letter-spacing:.6px;">Gelombang Aktif</div>
            <div style="font-size:1.4rem; font-weight:800; margin-top:4px;">{{ $gelombangAktif->nama_gelombang }}</div>
            <div style="font-size:.88rem; opacity:.85; margin-top:4px;">
                {{ \Carbon\Carbon::parse($gelombangAktif->tanggal_mulai)->translatedFormat('d F Y') }}
                &nbsp;—&nbsp;
                {{ \Carbon\Carbon::parse($gelombangAktif->tanggal_selesai)->translatedFormat('d F Y') }}
            </div>
        </div>
        <div style="display:flex; gap:12px; flex-wrap:wrap;">
            <a href="{{ route('pkl.penempatan.index') }}" class="btn btn-sm" style="background:#ffffff30; color:#fff; border:1.5px solid #ffffff50;">
                <i class="fa-solid fa-users"></i> Lihat Penempatan
            </a>
            <a href="{{ route('pkl.persuratan.index') }}" class="btn btn-sm" style="background:#ffffff30; color:#fff; border:1.5px solid #ffffff50;">
                <i class="fa-solid fa-file-signature"></i> Buat Surat
            </a>
        </div>
    </div>
    @else
    <div class="alert alert-error">
        <i class="fa-solid fa-triangle-exclamation"></i>
        Belum ada gelombang PKL yang aktif. <a href="{{ route('pkl.gelombang.index') }}" style="font-weight:700;">Buat Gelombang PKL</a>
    </div>
    @endif

    {{-- Stat Cards --}}
    <div style="display:grid; grid-template-columns: repeat(5, 1fr); gap:18px; margin-bottom:24px;">

        {{-- Card 1: Total Gelombang --}}
        <a href="{{ route('pkl.gelombang.index') }}" style="text-decoration:none;">
        <div class="card" style="margin-bottom:0; padding:20px 20px 14px; background:linear-gradient(135deg,#0d9488,#0f766e); color:#fff; transition:transform .15s,box-shadow .15s; cursor:pointer;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 30px rgba(13,148,136,.35)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
                <div style="width:48px;height:48px;border-radius:12px;background:#ffffff25;display:flex;align-items:center;justify-content:center;font-size:1.4rem;"><i class="fa-solid fa-wave-square"></i></div>
                <div>
                    <div style="font-size:1.8rem;font-weight:800;line-height:1;">{{ $totalGelombang }}</div>
                    <div style="font-size:.78rem;font-weight:600;opacity:.85;text-transform:uppercase;letter-spacing:.5px;">Total Gelombang</div>
                </div>
            </div>
            <div style="border-top:1px solid #ffffff30;padding-top:10px;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:.75rem;opacity:.8;">Lihat semua gelombang</span>
                <span style="font-size:.75rem;font-weight:700;background:#ffffff25;padding:3px 10px;border-radius:20px;">Detail <i class="fa-solid fa-arrow-right" style="font-size:.65rem;"></i></span>
            </div>
        </div>
        </a>

        {{-- Card 2: DUDI Aktif --}}
        <a href="{{ route('pkl.dudi.index') }}" style="text-decoration:none;">
        <div class="card" style="margin-bottom:0; padding:20px 20px 14px; background:linear-gradient(135deg,#6366f1,#4f46e5); color:#fff; transition:transform .15s,box-shadow .15s; cursor:pointer;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 30px rgba(99,102,241,.35)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
                <div style="width:48px;height:48px;border-radius:12px;background:#ffffff25;display:flex;align-items:center;justify-content:center;font-size:1.4rem;"><i class="fa-solid fa-building-user"></i></div>
                <div>
                    <div style="font-size:1.8rem;font-weight:800;line-height:1;">{{ $totalDudi }}</div>
                    <div style="font-size:.78rem;font-weight:600;opacity:.85;text-transform:uppercase;letter-spacing:.5px;">DUDI Aktif</div>
                </div>
            </div>
            <div style="border-top:1px solid #ffffff30;padding-top:10px;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:.75rem;opacity:.8;">Kelola mitra industri</span>
                <span style="font-size:.75rem;font-weight:700;background:#ffffff25;padding:3px 10px;border-radius:20px;">Detail <i class="fa-solid fa-arrow-right" style="font-size:.65rem;"></i></span>
            </div>
        </div>
        </a>

        {{-- Card 3: Siswa Ditempatkan --}}
        <a href="{{ route('pkl.penempatan.index', $gelombangAktif ? ['id_gelombang' => $gelombangAktif->id_gelombang] : []) }}" style="text-decoration:none;">
        <div class="card" style="margin-bottom:0; padding:20px 20px 14px; background:linear-gradient(135deg,#10b981,#059669); color:#fff; transition:transform .15s,box-shadow .15s; cursor:pointer;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 30px rgba(16,185,129,.35)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
                <div style="width:48px;height:48px;border-radius:12px;background:#ffffff25;display:flex;align-items:center;justify-content:center;font-size:1.4rem;"><i class="fa-solid fa-users"></i></div>
                <div>
                    <div style="font-size:1.8rem;font-weight:800;line-height:1;">{{ $totalPenempatan }}</div>
                    <div style="font-size:.78rem;font-weight:600;opacity:.85;text-transform:uppercase;letter-spacing:.5px;">Siswa Ditempatkan</div>
                </div>
            </div>
            <div style="border-top:1px solid #ffffff30;padding-top:10px;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:.75rem;opacity:.8;">Data penempatan PKL</span>
                <span style="font-size:.75rem;font-weight:700;background:#ffffff25;padding:3px 10px;border-radius:20px;">Detail <i class="fa-solid fa-arrow-right" style="font-size:.65rem;"></i></span>
            </div>
        </div>
        </a>

        {{-- Card 4: Surat Dikeluarkan --}}
        <a href="{{ route('pkl.persuratan.index') }}" style="text-decoration:none;">
        <div class="card" style="margin-bottom:0; padding:20px 20px 14px; background:linear-gradient(135deg,#f97316,#ea580c); color:#fff; transition:transform .15s,box-shadow .15s; cursor:pointer;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 30px rgba(249,115,22,.35)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
                <div style="width:48px;height:48px;border-radius:12px;background:#ffffff25;display:flex;align-items:center;justify-content:center;font-size:1.4rem;"><i class="fa-solid fa-file-lines"></i></div>
                <div>
                    <div style="font-size:1.8rem;font-weight:800;line-height:1;">{{ $recentSurat->count() }}</div>
                    <div style="font-size:.78rem;font-weight:600;opacity:.85;text-transform:uppercase;letter-spacing:.5px;">Surat Dikeluarkan</div>
                </div>
            </div>
            <div style="border-top:1px solid #ffffff30;padding-top:10px;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:.75rem;opacity:.8;">Kelola persuratan PKL</span>
                <span style="font-size:.75rem;font-weight:700;background:#ffffff25;padding:3px 10px;border-radius:20px;">Detail <i class="fa-solid fa-arrow-right" style="font-size:.65rem;"></i></span>
            </div>
        </div>
        </a>

        {{-- Card 5: Belum Ditempatkan --}}
        <a href="{{ route('pkl.penempatan.belum-ditempatkan', $gelombangAktif ? ['id_gelombang' => $gelombangAktif->id_gelombang] : []) }}" style="text-decoration:none;">
        <div class="card" style="margin-bottom:0; padding:20px 20px 14px; background:linear-gradient(135deg,#e11d48,#be123c); color:#fff; transition:transform .15s,box-shadow .15s; cursor:pointer;" onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 10px 30px rgba(225,29,72,.35)'" onmouseout="this.style.transform='';this.style.boxShadow=''">
            <div style="display:flex;align-items:center;gap:14px;margin-bottom:14px;">
                <div style="width:48px;height:48px;border-radius:12px;background:#ffffff25;display:flex;align-items:center;justify-content:center;font-size:1.4rem;"><i class="fa-solid fa-user-clock"></i></div>
                <div>
                    <div style="font-size:1.8rem;font-weight:800;line-height:1;">{{ $totalBelumDitempatkan }}</div>
                    <div style="font-size:.78rem;font-weight:600;opacity:.85;text-transform:uppercase;letter-spacing:.5px;">Belum Ditempatkan</div>
                </div>
            </div>
            <div style="border-top:1px solid #ffffff30;padding-top:10px;display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:.75rem;opacity:.8;">Siswa perlu penempatan</span>
                <span style="font-size:.75rem;font-weight:700;background:#ffffff25;padding:3px 10px;border-radius:20px;">Detail <i class="fa-solid fa-arrow-right" style="font-size:.65rem;"></i></span>
            </div>
        </div>
        </a>

    </div>


    {{-- Chart Row --}}
    @if($gelombangAktif && count($jurusanLabels) > 0)
    <div class="card mb-6">
        <div class="card-header" style="display:flex; justify-content:space-between; align-items:center;">
            <h2 class="card-title"><i class="fa-solid fa-chart-column" style="color:var(--color-primary);"></i> Statistik Penempatan Siswa per Jurusan</h2>
            <span class="badge badge-info">Gelombang Aktif</span>
        </div>
        <div class="card-body" style="padding:20px 24px;">
            <div style="position:relative; height:240px; width:100%;">
                <canvas id="chartJurusanPkl"></canvas>
            </div>
        </div>
    </div>
    @endif

    <div style="display:grid; grid-template-columns:1.4fr 1fr; gap:20px;">
        {{-- DUDI Stat --}}
        <div class="card" style="margin-bottom:0;">
            <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:10px;">
                <h2 class="card-title" style="margin:0;"><i class="fa-solid fa-chart-bar" style="color:var(--color-primary);"></i> Distribusi Siswa per DUDI (Gelombang Aktif)</h2>
                <form method="GET" action="{{ route('pkl.dashboard') }}" style="display:flex; align-items:center; gap:8px;">
                    <select name="id_jurusan" class="form-control form-control-sm" onchange="this.form.submit()" style="min-width:160px; font-weight:600; font-size:0.83rem; border-radius:8px; padding:4px 10px; cursor:pointer;">
                        <option value="">Semua Jurusan</option>
                        @foreach($jurusanList as $jur)
                            <option value="{{ $jur->id_jurusan }}" {{ request('id_jurusan') == $jur->id_jurusan ? 'selected' : '' }}>
                                {{ $jur->nama_jurusan }} ({{ $jur->kode_jurusan }})
                            </option>
                        @endforeach
                    </select>
                    @if(request('id_jurusan'))
                        <a href="{{ route('pkl.dashboard') }}" class="btn btn-light btn-sm" title="Reset Filter" style="padding:4px 10px; font-size:0.8rem;">
                            <i class="fa-solid fa-rotate-left"></i>
                        </a>
                    @endif
                </form>
            </div>
            <div class="card-body p-0">
                @if($statDudi->isEmpty())
                <div class="text-center text-muted py-6" style="padding:32px;">
                    <i class="fa-solid fa-building-circle-xmark" style="font-size:2rem;opacity:.3;display:block;margin-bottom:8px;"></i>
                    @if(request('id_jurusan'))
                        Belum ada penempatan siswa jurusan ini di gelombang aktif
                    @else
                        Belum ada penempatan di gelombang aktif
                    @endif
                </div>
                @else
                <table class="data-table">
                    <thead><tr>
                        <th>NAMA DUDI</th>
                        <th style="width:100px;text-align:center;">KUOTA</th>
                        <th style="width:120px;text-align:center;">TERISI</th>
                        <th style="width:160px;">PROGRESS</th>
                    </tr></thead>
                    <tbody>
                    @foreach($statDudi as $d)
                    <tr>
                        <td style="font-weight:600;">{{ $d->nama_dudi }}</td>
                        <td class="text-center">{{ $d->kuota_siswa }}</td>
                        <td class="text-center"><span class="badge badge-success">{{ $d->jumlah_siswa }}</span></td>
                        <td>
                            @php $pct = $d->kuota_siswa > 0 ? round(($d->jumlah_siswa/$d->kuota_siswa)*100) : 0; @endphp
                            <div style="background:#f1f5f9;border-radius:20px;height:8px;overflow:hidden;">
                                <div style="background:var(--color-primary);height:100%;width:{{ min(100, $pct) }}%;border-radius:20px;transition:width 1s;"></div>
                            </div>
                            <div style="font-size:.72rem;color:var(--text-muted);margin-top:3px;">{{ $pct }}% terisi</div>
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>

        {{-- Gelombang List --}}
        <div class="card" style="margin-bottom:0;">
            <div class="card-header">
                <h2 class="card-title"><i class="fa-solid fa-list-check" style="color:var(--color-primary);"></i> Daftar Gelombang</h2>
                <a href="{{ route('pkl.gelombang.index') }}" class="btn btn-secondary btn-sm">Kelola</a>
            </div>
            <div class="card-body p-0">
                <table class="data-table">
                    <thead><tr><th>Nama Gelombang</th><th>Tahun</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($gelombangList as $g)
                    <tr>
                        <td style="font-weight:600;">{{ $g->nama_gelombang }}</td>
                        <td style="font-size:.85rem;">{{ $g->tahun_ajaran }}</td>
                        <td>
                            @if($g->status === 'aktif')
                                <span class="badge badge-success"><i class="fa-solid fa-circle" style="font-size:.5rem;"></i> Aktif</span>
                            @elseif($g->status === 'selesai')
                                <span class="badge badge-info">Selesai</span>
                            @else
                                <span class="badge badge-muted">Draft</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted py-6">Belum ada gelombang</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Recent Surat --}}
    @if($recentSurat->isNotEmpty())
    <div class="card" style="margin-top:20px;">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-file-signature" style="color:var(--color-primary);"></i> Surat Terbaru</h2>
            <a href="{{ route('pkl.persuratan.index') }}" class="btn btn-secondary btn-sm">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <table class="data-table">
                <thead><tr><th>Nomor Surat</th><th>Jenis</th><th>DUDI</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                @foreach($recentSurat as $s)
                <tr>
                    <td style="font-family:monospace;font-size:.85rem;font-weight:600;">{{ $s->nomor_surat }}</td>
                    <td>
                        @if($s->jenis_surat === 'permohonan') <span class="badge badge-info">Permohonan</span>
                        @elseif($s->jenis_surat === 'penempatan') <span class="badge badge-success">Penempatan</span>
                        @else <span class="badge badge-warning">Penarikan</span>
                        @endif
                    </td>
                    <td>{{ optional($s->dudi)->nama_dudi ?? '-' }}</td>
                    <td style="font-size:.85rem;">{{ \Carbon\Carbon::parse($s->tanggal_surat)->format('d/m/Y') }}</td>
                    <td><a href="{{ route('pkl.persuratan.cetak', $s->id_surat) }}" class="btn-icon btn-info" title="Cetak" target="_blank"><i class="fa-solid fa-print"></i></a></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

@if($gelombangAktif && count($jurusanLabels) > 0)
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.3/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const ctx = document.getElementById('chartJurusanPkl').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($jurusanLabels),
            datasets: [
                {
                    label: 'Sudah Memperoleh DUDI',
                    data: @json($jurusanSudahData),
                    backgroundColor: 'rgba(16, 185, 129, 0.85)', // Teal/Green
                    borderColor: 'rgb(16, 185, 129)',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false
                },
                {
                    label: 'Belum Memperoleh DUDI',
                    data: @json($jurusanBelumData),
                    backgroundColor: 'rgba(249, 115, 22, 0.85)', // Orange/Amber
                    borderColor: 'rgb(249, 115, 22)',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { family: "'Plus Jakarta Sans', sans-serif", size: 12 },
                        padding: 18,
                        usePointStyle: true,
                        pointStyleWidth: 10,
                        boxHeight: 8
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.92)',
                    titleColor: '#f8fafc',
                    bodyColor: '#cbd5e1',
                    padding: 12,
                    cornerRadius: 10,
                    boxPadding: 4
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { font: { family: "'Plus Jakarta Sans', sans-serif" } }
                },
                y: {
                    grid: { color: 'rgba(0, 0, 0, 0.05)' },
                    ticks: {
                        stepSize: 1,
                        font: { family: "'Plus Jakarta Sans', sans-serif" }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endif
@endsection
