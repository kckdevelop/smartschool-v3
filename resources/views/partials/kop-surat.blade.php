@php
    $sekolah = $sekolah ?? \App\Models\Sekolah::first();
    $isPreviewMode = $isPreviewMode ?? (isset($isPreview) && $isPreview);
    $isPdf = $isPdf ?? (isset($pdf) || isset($dompdf) || request()->routeIs('*pdf*') || request()->is('*pdf*') || (isset($panggil) && !$isPreviewMode));
@endphp

@if($sekolah && $sekolah->kop)
    @php
        $showUploadedKop = false;
        $kopSrc = '';
        if ($isPdf) {
            $kopPath = storage_path('app/public/' . $sekolah->kop);
            if (file_exists($kopPath)) {
                $kopSrc = 'data:image/' . pathinfo($kopPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($kopPath));
                $showUploadedKop = true;
            }
        } else {
            $kopSrc = asset('storage/' . $sekolah->kop);
            $showUploadedKop = true;
        }
    @endphp

    @if($showUploadedKop)
        <div class="kop-surat-container kop-uploaded" style="width: 100%; text-align: center; margin-bottom: 20px;">
            <img src="{{ $kopSrc }}" alt="Kop Surat Sekolah" style="width: 100%; max-height: 120px; object-fit: contain; display: block; margin: 0 auto;">
        </div>
    @else
        @php $renderFallback = true; @endphp
    @endif
@else
    @php $renderFallback = true; @endphp
@endif

@if(isset($renderFallback) && $renderFallback)
    @php
        $showLogo = false;
        $logoSrc = '';
        if ($sekolah && $sekolah->logo) {
            if ($isPdf) {
                $logoPath = storage_path('app/public/' . $sekolah->logo);
                if (file_exists($logoPath)) {
                    $logoSrc = 'data:image/' . pathinfo($logoPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($logoPath));
                    $showLogo = true;
                }
            } else {
                $logoSrc = asset('storage/' . $sekolah->logo);
                $showLogo = true;
            }
        }
    @endphp
    <table class="kop-surat-table" style="width: 100%; border-collapse: collapse; margin-bottom: 15px; border: none;">
        <tr style="border: none;">
            @if($showLogo)
                <td style="width: 80px; text-align: center; vertical-align: middle; padding: 0 15px 10px 0; border: none;">
                    <img src="{{ $logoSrc }}" alt="Logo" style="max-width: 70px; max-height: 70px; object-fit: contain; display: block; margin: 0 auto;">
                </td>
            @endif
            <td style="text-align: center; vertical-align: middle; padding-bottom: 10px; border: none; {{ !$showLogo ? '' : 'padding-right: 80px;' }}">
                <div class="school-name" style="font-family: 'Times New Roman', Times, serif, 'Inter'; font-size: 16pt; font-weight: bold; text-transform: uppercase; margin: 0; line-height: 1.2; color: #000; text-align: center;">
                    {{ $sekolah->nama_sekolah ?? 'SMART SCHOOL ACADEMY' }}
                </div>
                <div class="school-subtitle" style="font-family: 'Times New Roman', Times, serif, 'Inter'; font-size: 9.5pt; margin: 4px 0 0 0; color: #333; line-height: 1.3; text-align: center;">
                    Alamat: {{ $sekolah->alamat_sekolah ?? 'Jl. Pendidikan No. 45, Kota Cerdas' }}<br>
                    NPSN: {{ $sekolah->npsn ?? '-' }} | Kota: {{ $sekolah->kota ?? '-' }}
                </div>
            </td>
        </tr>
    </table>
    <div class="kop-divider" style="border: none; border-top: 3px double #000; margin-top: 4px; margin-bottom: 15px; height: 0; width: 100%;"></div>
@endif
