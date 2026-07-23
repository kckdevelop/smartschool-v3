@extends('layouts.app')

@section('title', 'Nomor Surat PKL — SmartSchool')
@section('header_title', 'Nomor Surat PKL')
@section('header_subtitle', 'Konfigurasi format dan counter nomor surat otomatis')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div style="display:grid; grid-template-columns: repeat(3, 1fr); gap:20px;">
        @foreach($records as $jenis => $record)
        @php
            $icons  = ['permohonan'=>'fa-paper-plane','penempatan'=>'fa-location-dot','penarikan'=>'fa-arrow-right-from-bracket'];
            $colors = ['permohonan'=>'#6366f1','penempatan'=>'#10b981','penarikan'=>'#f97316'];
            $labels = ['permohonan'=>'Surat Permohonan PKL','penempatan'=>'Surat Pengantar Penempatan','penarikan'=>'Surat Penarikan Siswa'];
        @endphp
        <div class="card" style="margin-bottom:0;">
            <div class="card-header" style="gap:10px;">
                <h2 class="card-title">
                    <i class="fa-solid {{ $icons[$jenis] }}" style="color:{{ $colors[$jenis] }};"></i>
                    {{ $labels[$jenis] }}
                </h2>
            </div>
            <div class="card-body">
                {{-- Current counter display --}}
                <div style="text-align:center; margin-bottom:20px; padding:16px; background:linear-gradient(135deg, {{ $colors[$jenis] }}15, {{ $colors[$jenis] }}08); border-radius:12px; border:1.5px solid {{ $colors[$jenis] }}30;">
                    <div style="font-size:.75rem; font-weight:700; text-transform:uppercase; letter-spacing:.6px; color:{{ $colors[$jenis] }}; margin-bottom:6px;">Counter Saat Ini</div>
                    <div style="font-size:2.5rem; font-weight:900; color:{{ $colors[$jenis] }}; line-height:1;">{{ str_pad($record->counter_terakhir, 3, '0', STR_PAD_LEFT) }}</div>
                    <div style="font-size:.78rem; color:var(--text-muted); margin-top:4px;">Surat terakhir: {{ $record->updated_at ? \Carbon\Carbon::parse($record->updated_at)->format('d/m/Y H:i') : '-' }}</div>
                </div>

                {{-- Contoh nomor --}}
                @php
                    $romawi = ['', 'I','II','III','IV','V','VI','VII','VIII','IX','X','XI','XII'];
                    $bln = $romawi[(int)date('n')];
                    $kodes = ['permohonan'=>'PM','penempatan'=>'PP','penarikan'=>'PT'];
                    $contoh = str_replace(['{NO}','{KODE}','{BULAN-ROMAWI}','{TAHUN}'],
                        [str_pad($record->counter_terakhir+1, 3,'0',STR_PAD_LEFT), $kodes[$jenis], $bln, date('Y')],
                        $record->format_nomor);
                    if ($record->prefix) $contoh = $record->prefix . '/' . $contoh;
                @endphp
                <div style="font-size:.78rem; color:var(--text-muted); text-align:center; margin-bottom:16px;">
                    Nomor berikutnya: <strong style="font-family:monospace; color:var(--text-primary);">{{ $contoh }}</strong>
                </div>

                {{-- Edit Form --}}
                <form method="POST" action="{{ route('pkl.nomor-surat.update', $jenis) }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Format Nomor</label>
                        <input type="text" name="format_nomor" class="form-control form-control-sm"
                            value="{{ $record->format_nomor }}"
                            placeholder="{NO}/PKL/{BULAN-ROMAWI}/{TAHUN}">
                        <span class="form-hint">Variabel: <code>{NO}</code> <code>{KODE}</code> <code>{BULAN-ROMAWI}</code> <code>{TAHUN}</code></span>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Prefix (opsional)</label>
                        <input type="text" name="prefix" class="form-control form-control-sm"
                            value="{{ $record->prefix }}" placeholder="Contoh: SMKN1-WONOSOBO">
                    </div>
                    <div class="form-actions" style="gap:8px;">
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fa-solid fa-floppy-disk"></i> Simpan Format</button>
                        <form method="POST" action="{{ route('pkl.nomor-surat.reset', $jenis) }}" style="display:inline;" onsubmit="return confirm('Reset counter {{ $labels[$jenis] }} ke 0?')">
                            @csrf
                            <button type="submit" class="btn btn-sm" style="background:#fef3c7;color:#92400e;"><i class="fa-solid fa-rotate-left"></i> Reset Counter</button>
                        </form>
                    </div>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Help Card --}}
    <div class="card" style="margin-top:20px;">
        <div class="card-body">
            <h3 style="font-size:.95rem; font-weight:700; margin-bottom:12px; color:var(--text-primary);">
                <i class="fa-solid fa-circle-question" style="color:var(--color-primary);"></i> Panduan Variabel Format
            </h3>
            <div style="display:grid; grid-template-columns: repeat(4,1fr); gap:12px;">
                @foreach(['{NO}'=>'Nomor urut (001, 002...)', '{KODE}'=>'Kode jenis (PM/PP/PT)', '{BULAN-ROMAWI}'=>'Bulan dalam angka Romawi', '{TAHUN}'=>'Tahun 4 digit (2025)'] as $var => $desc)
                <div style="background:#f8fafc; border-radius:8px; padding:10px 12px; border:1.5px solid #e2e8f0;">
                    <code style="font-size:.85rem; color:var(--color-primary); font-weight:700;">{{ $var }}</code>
                    <div style="font-size:.78rem; color:var(--text-muted); margin-top:4px;">{{ $desc }}</div>
                </div>
                @endforeach
            </div>
            <div style="margin-top:12px; padding:10px 14px; background:#ede9fe; border-radius:8px; font-size:.82rem; color:#5b21b6;">
                <strong>Contoh format lengkap:</strong>
                <code style="margin-left:8px;">001/PM/PKL/SMKN1/VII/2025</code>
                → Prefix: <code>SMKN1</code>, Format: <code>{NO}/PM/PKL/{BULAN-ROMAWI}/{TAHUN}</code>
            </div>
        </div>
    </div>
</div>
@endsection
