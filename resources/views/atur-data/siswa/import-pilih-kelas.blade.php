@extends('layouts.app')

@section('title', 'Pilih Kelas Import Siswa — SmartSchool')
@section('header_title', 'Import Data Siswa')
@section('header_subtitle', 'Pilih kelas terlebih dahulu sebelum mengunggah file data siswa')

@section('content')
<div class="page-content">
    @include('partials.flash')

    <div class="card" style="max-width: 600px; margin: 0 auto;">
        <div class="card-header">
            <h2 class="card-title"><i class="fa-solid fa-graduation-cap"></i> Pilih Kelas Tujuan</h2>
        </div>
        <form action="{{ route('atur-data.siswa.import') }}" method="GET">
            <div class="card-body">
                <p class="text-muted mb-4">Silakan pilih kelas aktif tujuan yang akan diisikan data siswanya melalui file template Excel/CSV.</p>
                
                <div class="form-group">
                    <label class="form-label" style="font-weight: 600;">Kelas Tujuan <span class="required">*</span></label>
                    <select name="id_kelas" class="form-control" required style="padding: 10px 14px; border-radius: 8px;">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelasList as $k)
                            <option value="{{ $k->id_kelas }}">
                                Kelas {{ $k->tingkat }} {{ $k->rombel }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="card-footer" style="display: flex; justify-content: space-between; align-items: center; background: #fafafa;">
                <a href="{{ route('atur-data.siswa') }}" class="btn btn-secondary">
                    <i class="fa-solid fa-arrow-left"></i> Kembali
                </a>
                <button type="submit" class="btn btn-primary">
                    Lanjut ke Form Upload <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
