@if ($paginator->hasPages())
@php
    $currentPage = $paginator->currentPage();
    $lastPage    = $paginator->lastPage();
    $window      = 2;

    $pages = [];
    for ($i = 1; $i <= $lastPage; $i++) {
        if (
            $i === 1 ||
            $i === $lastPage ||
            ($i >= $currentPage - $window && $i <= $currentPage + $window)
        ) {
            $pages[] = $i;
        }
    }

    $rendered = [];
    $prev     = null;
    foreach ($pages as $pg) {
        if ($prev !== null && $pg - $prev > 1) {
            $rendered[] = null;
        }
        $rendered[] = $pg;
        $prev = $pg;
    }

    $currentPerPage = request('per_page', 20);
@endphp

<div class="presensi-paging-wrap">
    {{-- Kiri: dropdown per-page + info --}}
    <div class="presensi-paging-left">
        <div class="pp-perpage-wrap">
            <label class="pp-perpage-label" for="pp-perpage-select">Tampil</label>
            <select id="pp-perpage-select" class="pp-perpage-select" onchange="changePerPage(this.value)">
                @foreach ([10, 20, 50, 100] as $opt)
                    <option value="{{ $opt }}" {{ (int)$currentPerPage === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                @endforeach
            </select>
            <span class="pp-perpage-label">data per halaman</span>
        </div>
        <span class="presensi-paging-info">
            Menampilkan <strong>{{ $paginator->firstItem() }}</strong>–<strong>{{ $paginator->lastItem() }}</strong>
            dari <strong>{{ $paginator->total() }}</strong> data
        </span>
    </div>

    {{-- Kanan: navigasi halaman --}}
    <nav class="presensi-paging-nav" aria-label="Navigasi halaman">

        @if ($paginator->onFirstPage())
            <span class="pp-btn pp-btn-nav pp-disabled">
                <i class="fa-solid fa-chevron-left"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" class="pp-btn pp-btn-nav">
                <i class="fa-solid fa-chevron-left"></i>
            </a>
        @endif

        @foreach ($rendered as $item)
            @if ($item === null)
                <span class="pp-ellipsis">…</span>
            @elseif ($item == $currentPage)
                <span class="pp-btn pp-active" aria-current="page">{{ $item }}</span>
            @else
                <a href="{{ $paginator->url($item) }}" class="pp-btn">{{ $item }}</a>
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" class="pp-btn pp-btn-nav">
                <i class="fa-solid fa-chevron-right"></i>
            </a>
        @else
            <span class="pp-btn pp-btn-nav pp-disabled">
                <i class="fa-solid fa-chevron-right"></i>
            </span>
        @endif

    </nav>
</div>

<script>
function changePerPage(val) {
    const url = new URL(window.location.href);
    url.searchParams.set('per_page', val);
    url.searchParams.set('page', 1); // reset ke halaman 1
    window.location.href = url.toString();
}
</script>

<style>
.presensi-paging-wrap {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 10px;
    padding: 14px 4px 6px;
}
.presensi-paging-left {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
}
.pp-perpage-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
}
.pp-perpage-label {
    font-size: 0.8rem;
    color: var(--text-muted, #64748b);
    white-space: nowrap;
}
.pp-perpage-select {
    height: 32px;
    padding: 0 8px;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-secondary, #475569);
    background: #fff;
    cursor: pointer;
    outline: none;
    transition: border-color 0.15s;
}
.pp-perpage-select:focus {
    border-color: var(--color-primary, #0d9488);
}
.presensi-paging-info {
    font-size: 0.8rem;
    color: var(--text-muted, #64748b);
}
.presensi-paging-nav {
    display: flex;
    align-items: center;
    gap: 4px;
    flex-wrap: wrap;
}
.pp-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 5px;
    min-width: 34px;
    height: 32px;
    padding: 0 10px;
    border-radius: 8px;
    font-size: 0.8rem;
    font-weight: 600;
    color: var(--text-secondary, #475569);
    background: #fff;
    border: 1.5px solid #e2e8f0;
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.15s ease, border-color 0.15s ease, color 0.15s ease;
}
a.pp-btn:hover {
    background: rgba(13,148,136,0.08);
    border-color: rgba(13,148,136,0.35);
    color: var(--color-primary, #0d9488);
}
.pp-btn.pp-active {
    background: var(--color-primary, #0d9488);
    border-color: var(--color-primary, #0d9488);
    color: #fff;
    cursor: default;
    pointer-events: none;
}
.pp-btn.pp-btn-nav {
    padding: 0 13px;
}
.pp-btn.pp-disabled {
    opacity: 0.38;
    cursor: not-allowed;
    pointer-events: none;
}
.pp-ellipsis {
    font-size: 0.85rem;
    color: var(--text-muted, #94a3b8);
    padding: 0 4px;
    user-select: none;
}
</style>
@endif

