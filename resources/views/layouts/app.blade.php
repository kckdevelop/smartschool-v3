<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'SmartSchool Dashboard')</title>
    <meta name="description" content="SmartSchool — Sistem Informasi Sekolah Digital yang lengkap dan modern.">

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    {{-- Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Cropper.js --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">

    @stack('styles')
</head>
<body>
<div class="dashboard-wrapper">

    {{-- ══════════════════════ SIDEBAR ══════════════════════ --}}
    <aside class="sidebar" id="sidebar">

        {{-- Brand --}}
        <div class="sidebar-brand" style="display: flex; align-items: center; gap: 12px; padding: 20px 24px;">
            <div class="sidebar-brand-icon" style="background: transparent; flex-shrink: 0; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                @if(isset($sekolah) && $sekolah->logo)
                    <img src="{{ asset('storage/' . $sekolah->logo) }}" alt="Logo Sekolah" style="max-height: 40px; max-width: 40px; border-radius: 6px; object-fit: contain;">
                @else
                    <i class="fa-solid fa-graduation-cap" style="font-size: 24px; color: #fff;"></i>
                @endif
            </div>
            <div style="display: flex; flex-direction: column; line-height: 1.2;">
                <span class="sidebar-brand-text" style="font-weight: 800; font-size: 16px; letter-spacing: 0.5px; color: #fff;">SMARTSCHOOL</span>
                @if(isset($sekolah) && $sekolah->nama_sekolah)
                    <span style="font-size: 11px; opacity: 0.8; color: #f1f5f9; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 140px;" title="{{ $sekolah->nama_sekolah }}">
                        {{ $sekolah->nama_sekolah }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Close button (mobile) --}}
        <button class="sidebar-close-btn" id="sidebar-close-btn" aria-label="Tutup Menu">
            <i class="fa-solid fa-xmark"></i>
        </button>

        {{-- Scrollable menu --}}
        <nav class="sidebar-nav">
            <ul class="sidebar-menu">

                {{-- ── Dashboard ── --}}
                <li>
                    <a href="{{ route('dashboard') }}"
                       class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       id="nav-dashboard">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- ── Layar Display ── --}}
                <li>
                    <a href="{{ route('display.index') }}"
                       class="sidebar-link {{ request()->routeIs('display.index') ? 'active' : '' }}"
                       id="nav-display" target="_blank">
                        <i class="fa-solid fa-desktop"></i>
                        <span>Layar Display</span>
                    </a>
                </li>

                {{-- ══════════════════════════════════════
                     ATUR DATA
                ═══════════════════════════════════════ --}}
                <li class="nav-group {{ request()->is('atur-data*') ? 'active' : '' }}" id="group-aturdata">
                    <button class="nav-group-toggle" data-target="submenu-aturdata" aria-expanded="{{ request()->is('atur-data*') ? 'true' : 'false' }}">
                        <span class="nav-group-left">
                            <i class="fa-solid fa-gear"></i>
                            <span>Atur Data</span>
                        </span>
                        <i class="fa-solid {{ request()->is('atur-data*') ? 'fa-minus' : 'fa-plus' }} nav-group-arrow"></i>
                    </button>
                    <ul class="nav-submenu {{ request()->is('atur-data*') ? 'open' : '' }}" id="submenu-aturdata">
                        <li><a href="{{ route('atur-data.sekolah') }}" class="nav-sub-link {{ request()->routeIs('atur-data.sekolah') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Data Sekolah</a></li>
                        <li><a href="{{ route('atur-data.tahun-semester') }}" class="nav-sub-link {{ request()->routeIs('atur-data.tahun-semester') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Tahun dan Semester</a></li>
                        <li><a href="{{ route('atur-data.jurusan') }}" class="nav-sub-link {{ request()->routeIs('atur-data.jurusan') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Data Jurusan</a></li>
                        <li><a href="{{ route('atur-data.kelas') }}" class="nav-sub-link {{ request()->routeIs('atur-data.kelas') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Data Kelas</a></li>
                        <li><a href="{{ route('atur-data.siswa') }}" class="nav-sub-link {{ request()->routeIs('atur-data.siswa') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Data Siswa</a></li>
                        <li><a href="{{ route('atur-data.guru') }}" class="nav-sub-link {{ request()->routeIs('atur-data.guru') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Data Guru</a></li>
                        <li><a href="{{ route('atur-data.karyawan') }}" class="nav-sub-link {{ request()->routeIs('atur-data.karyawan') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Data Karyawan</a></li>
                        <li><a href="{{ route('atur-data.mapel') }}" class="nav-sub-link {{ request()->routeIs('atur-data.mapel') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Data Mata Pelajaran</a></li>
                        <li><a href="{{ route('atur-data.wali-kelas') }}" class="nav-sub-link {{ request()->routeIs('atur-data.wali-kelas') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Data Wali Amanah</a></li>
                        <li><a href="{{ route('atur-data.mesin-finger') }}" class="nav-sub-link {{ request()->routeIs('atur-data.mesin-finger') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Data Mesin Finger</a></li>
                        <li><a href="{{ route('atur-data.tarik-finger') }}" class="nav-sub-link {{ request()->routeIs('atur-data.tarik-finger') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Tarik Data Finger</a></li>
                        <li><a href="{{ route('atur-data.whatsapp-gateway') }}" class="nav-sub-link {{ request()->routeIs('atur-data.whatsapp-gateway*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> WhatsApp Gateway</a></li>
                        <li><a href="{{ route('atur-data.backup-restore') }}" class="nav-sub-link {{ request()->routeIs('atur-data.backup-restore*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Backup & Restore DB</a></li>
                    </ul>
                </li>

                {{-- ══════════════════════════════════════
                     PRESENSI SISWA
                ═══════════════════════════════════════ --}}
                <li class="nav-group {{ request()->is('presensi-siswa*') ? 'active' : '' }}" id="group-presensi">
                    <button class="nav-group-toggle" data-target="submenu-presensi" aria-expanded="{{ request()->is('presensi-siswa*') ? 'true' : 'false' }}">
                        <span class="nav-group-left">
                            <i class="fa-solid fa-clipboard-user"></i>
                            <span>Presensi Siswa</span>
                        </span>
                        <i class="fa-solid {{ request()->is('presensi-siswa*') ? 'fa-minus' : 'fa-plus' }} nav-group-arrow"></i>
                    </button>
                    <ul class="nav-submenu {{ request()->is('presensi-siswa*') ? 'open' : '' }}" id="submenu-presensi">
                        <li><a href="{{ route('presensi-siswa.input') }}" class="nav-sub-link {{ request()->routeIs('presensi-siswa.input') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Input Presensi</a></li>
                        <li><a href="{{ route('presensi-siswa.rekap') }}" class="nav-sub-link {{ request()->routeIs('presensi-siswa.rekap') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Rekap Presensi</a></li>
                        <li><a href="{{ route('presensi-siswa.laporan') }}" class="nav-sub-link {{ request()->routeIs('presensi-siswa.laporan') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Laporan Presensi</a></li>
                    </ul>
                </li>

                {{-- ══════════════════════════════════════
                     MENU GURU
                ═══════════════════════════════════════ --}}
                <li class="nav-group {{ (request()->is('jurnal-guru*') || request()->is('atur-jam*') || request()->is('jadwal-mengajar*')) ? 'active' : '' }}" id="group-guru">
                    <button class="nav-group-toggle" data-target="submenu-guru" aria-expanded="{{ (request()->is('jurnal-guru*') || request()->is('atur-jam*') || request()->is('jadwal-mengajar*')) ? 'true' : 'false' }}">
                        <span class="nav-group-left">
                            <i class="fa-regular fa-file-lines"></i>
                            <span>Menu Guru</span>
                        </span>
                        <i class="fa-solid {{ (request()->is('jurnal-guru*') || request()->is('atur-jam*') || request()->is('jadwal-mengajar*')) ? 'fa-minus' : 'fa-plus' }} nav-group-arrow"></i>
                    </button>
                    <ul class="nav-submenu {{ (request()->is('jurnal-guru*') || request()->is('atur-jam*') || request()->is('jadwal-mengajar*')) ? 'open' : '' }}" id="submenu-guru">
                        <li><a href="{{ route('atur-jam.index') }}" class="nav-sub-link {{ request()->routeIs('atur-jam.index') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Atur Jam</a></li>
                        <li><a href="{{ route('jadwal-mengajar.index') }}" class="nav-sub-link {{ request()->is('jadwal-mengajar*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Jadwal Mengajar</a></li>
                        <li><a href="{{ route('jurnal-guru.index') }}" class="nav-sub-link {{ request()->routeIs('jurnal-guru.index') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Jurnal Guru</a></li>
                        <li><a href="{{ route('jurnal-guru.laporan') }}" class="nav-sub-link {{ request()->routeIs('jurnal-guru.laporan') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Laporan Jurnal</a></li>
                    </ul>
                </li>

                {{-- ══════════════════════════════════════
                     E - ADMIN GURU
                ═══════════════════════════════════════ --}}
                <li class="nav-group {{ request()->is('generator-soal*') ? 'active' : '' }}" id="group-admin-guru">
                    <button class="nav-group-toggle" data-target="submenu-admin-guru" aria-expanded="{{ request()->is('generator-soal*') ? 'true' : 'false' }}">
                        <span class="nav-group-left">
                            <i class="fa-solid fa-robot"></i>
                            <span>E - Admin Guru</span>
                        </span>
                        <i class="fa-solid {{ request()->is('generator-soal*') ? 'fa-minus' : 'fa-plus' }} nav-group-arrow"></i>
                    </button>
                    <ul class="nav-submenu {{ request()->is('generator-soal*') ? 'open' : '' }}" id="submenu-admin-guru">
                        <li><a href="{{ route('generator-soal.index') }}" class="nav-sub-link {{ (request()->is('generator-soal') || request()->is('generator-soal/history*')) ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Generate Soal AI</a></li>
                        <li><a href="{{ route('generator-soal.from-kisikisi.index') }}" class="nav-sub-link {{ request()->is('generator-soal/from-kisikisi*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Soal dari Kisi-Kisi</a></li>
                        <li><a href="{{ route('generator-soal.kisikisi.index') }}" class="nav-sub-link {{ request()->is('generator-soal/kisi-kisi*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Generate Kisi-Kisi AI</a></li>
                        <li><a href="{{ route('generator-soal.pengaturan') }}" class="nav-sub-link {{ request()->routeIs('generator-soal.pengaturan') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Pengaturan LLM</a></li>
                    </ul>
                </li>

                {{-- ══════════════════════════════════════
                     MENU LMS (Learning Management System)
                ═══════════════════════════════════════ --}}
                <li class="nav-group {{ request()->is('lms*') ? 'active' : '' }}" id="group-lms">
                    <button class="nav-group-toggle" data-target="submenu-lms" aria-expanded="{{ request()->is('lms*') ? 'true' : 'false' }}">
                        <span class="nav-group-left">
                            <i class="fa-solid fa-graduation-cap"></i>
                            <span>Learning Management</span>
                        </span>
                        <i class="fa-solid {{ request()->is('lms*') ? 'fa-minus' : 'fa-plus' }} nav-group-arrow"></i>
                    </button>
                    <ul class="nav-submenu {{ request()->is('lms*') ? 'open' : '' }}" id="submenu-lms">
                        <li><a href="{{ route('lms.kursus.index') }}" class="nav-sub-link {{ request()->routeIs('lms.kursus.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Kursus</a></li>
                        <li><a href="{{ route('lms.tugas.index') }}" class="nav-sub-link {{ request()->routeIs('lms.tugas.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Tugas</a></li>
                        <li><a href="{{ route('lms.tagihan.index') }}" class="nav-sub-link {{ request()->routeIs('lms.tagihan.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Tagihan / Pengumpulan</a></li>
                    </ul>
                </li>

                {{-- ══════════════════════════════════════
                     MENU GURU KELAS
                ═══════════════════════════════════════ --}}
                <li class="nav-group {{ request()->is('guru-kelas*') ? 'active' : '' }}" id="group-guru-kelas">
                    <button class="nav-group-toggle" data-target="submenu-guru-kelas" aria-expanded="{{ request()->is('guru-kelas*') ? 'true' : 'false' }}">
                        <span class="nav-group-left">
                            <i class="fa-solid fa-chalkboard-user"></i>
                            <span>Guru Kelas</span>
                        </span>
                        <i class="fa-solid {{ request()->is('guru-kelas*') ? 'fa-minus' : 'fa-plus' }} nav-group-arrow"></i>
                    </button>
                    <ul class="nav-submenu {{ request()->is('guru-kelas*') ? 'open' : '' }}" id="submenu-guru-kelas">
                        <li><a href="{{ route('guru-kelas.pelanggaran.index') }}" class="nav-sub-link {{ request()->routeIs('guru-kelas.pelanggaran.index') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Pelanggaran Kelas</a></li>
                        <li><a href="{{ route('guru-kelas.pelanggaran.rekap') }}" class="nav-sub-link {{ request()->routeIs('guru-kelas.pelanggaran.rekap') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Rekap Pelanggaran</a></li>
                    </ul>
                </li>

                {{-- ══════════════════════════════════════
                     MENU BK
                ═══════════════════════════════════════ --}}
                <li class="nav-group {{ request()->is('bk*') ? 'active' : '' }}" id="group-bk">
                    <button class="nav-group-toggle" data-target="submenu-bk" aria-expanded="{{ request()->is('bk*') ? 'true' : 'false' }}">
                        <span class="nav-group-left">
                            <i class="fa-regular fa-circle-user"></i>
                            <span>Menu BK</span>
                        </span>
                        <i class="fa-solid {{ request()->is('bk*') ? 'fa-minus' : 'fa-plus' }} nav-group-arrow"></i>
                    </button>
                    <ul class="nav-submenu {{ request()->is('bk*') ? 'open' : '' }}" id="submenu-bk">
                        <li><a href="{{ route('bk.dashboard') }}" class="nav-sub-link {{ request()->routeIs('bk.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Dashboard BK</a></li>
                        <li><a href="{{ route('bk.kategori-pelanggaran.index') }}" class="nav-sub-link {{ request()->routeIs('bk.kategori-pelanggaran.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Kategori Pelanggaran</a></li>
                        <li><a href="{{ route('bk.kategori-reward.index') }}" class="nav-sub-link {{ request()->routeIs('bk.kategori-reward.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Kategori Reward</a></li>
                        <li><a href="{{ route('bk.catat-pelanggaran.index') }}" class="nav-sub-link {{ request()->routeIs('bk.catat-pelanggaran.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Catat Pelanggaran</a></li>
                        <li><a href="{{ route('bk.catat-reward.index') }}" class="nav-sub-link {{ request()->routeIs('bk.catat-reward.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Catat Reward</a></li>
                        <li><a href="{{ route('bk.buku-kasus.index') }}" class="nav-sub-link {{ request()->routeIs('bk.buku-kasus.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Buku Kasus</a></li>
                        <li><a href="{{ route('bk.buku-konsultasi.index') }}" class="nav-sub-link {{ request()->routeIs('bk.buku-konsultasi.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Buku Konsultasi</a></li>
                        <li><a href="{{ route('bk.home-visit.index') }}" class="nav-sub-link {{ request()->routeIs('bk.home-visit.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Home Visit</a></li>
                        <li><a href="{{ route('bk.panggil-ortu.index') }}" class="nav-sub-link {{ request()->routeIs('bk.panggil-ortu.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Panggil Orang Tua</a></li>
                        <li><a href="{{ route('bk.laporan.index') }}" class="nav-sub-link {{ request()->routeIs('bk.laporan.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Laporan Pelanggaran & Reward</a></li>
                        <li><a href="{{ route('bk.gaya-belajar.index') }}" class="nav-sub-link {{ request()->routeIs('bk.gaya-belajar.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Gaya Belajar & Minat</a></li>
                    </ul>
                </li>

                {{-- ══════════════════════════════════════
                     MENU UKS
                ═══════════════════════════════════════ --}}
                <li class="nav-group {{ request()->is('uks*') ? 'active' : '' }}" id="group-uks">
                    <button class="nav-group-toggle" data-target="submenu-uks" aria-expanded="{{ request()->is('uks*') ? 'true' : 'false' }}">
                        <span class="nav-group-left">
                            <i class="fa-regular fa-heart"></i>
                            <span>Menu UKS</span>
                        </span>
                        <i class="fa-solid {{ request()->is('uks*') ? 'fa-minus' : 'fa-plus' }} nav-group-arrow"></i>
                    </button>
                    <ul class="nav-submenu {{ request()->is('uks*') ? 'open' : '' }}" id="submenu-uks">
                        <li><a href="{{ route('uks.dashboard') }}" class="nav-sub-link {{ request()->routeIs('uks.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Dashboard UKS</a></li>
                        <li><a href="{{ route('uks.checkup.index') }}" class="nav-sub-link {{ request()->routeIs('uks.checkup.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Data Check-Up Siswa</a></li>
                        <li><a href="{{ route('uks.checkup-gukar.index') }}" class="nav-sub-link {{ request()->routeIs('uks.checkup-gukar.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Data Check-Up Gukar</a></li>
                        <li><a href="{{ route('uks.kunjungan.index') }}" class="nav-sub-link {{ request()->routeIs('uks.kunjungan.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Kunjungan UKS Siswa</a></li>
                        <li><a href="{{ route('uks.kunjungan-gukar.index') }}" class="nav-sub-link {{ request()->routeIs('uks.kunjungan-gukar.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Kunjungan UKS Gukar</a></li>
                        <li><a href="{{ route('uks.laporan.index') }}" class="nav-sub-link {{ request()->routeIs('uks.laporan.index') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Laporan UKS</a></li>
                    </ul>
                </li>

                {{-- ══════════════════════════════════════
                     MENU ISMUBA
                ═══════════════════════════════════════ --}}
                <li class="nav-group {{ request()->is('ismuba*') ? 'active' : '' }}" id="group-ismuba">
                    <button class="nav-group-toggle" data-target="submenu-ismuba" aria-expanded="{{ request()->is('ismuba*') ? 'true' : 'false' }}">
                        <span class="nav-group-left">
                            <i class="fa-solid fa-book-quran"></i>
                            <span>Menu ISMUBA</span>
                        </span>
                        <i class="fa-solid {{ request()->is('ismuba*') ? 'fa-minus' : 'fa-plus' }} nav-group-arrow"></i>
                    </button>
                    <ul class="nav-submenu {{ request()->is('ismuba*') ? 'open' : '' }}" id="submenu-ismuba">
                        <li><a href="{{ route('ismuba.dashboard') }}" class="nav-sub-link {{ request()->routeIs('ismuba.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Dashboard ISMUBA</a></li>
                        <li><a href="{{ route('ismuba.btaq.index') }}" class="nav-sub-link {{ request()->routeIs('ismuba.btaq.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Pantau BTAQ</a></li>
                        <li><a href="{{ route('ismuba.tadarus.index') }}" class="nav-sub-link {{ request()->routeIs('ismuba.tadarus.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Pantau Tadarus</a></li>
                        <li><a href="{{ route('ismuba.ibadah.index') }}" class="nav-sub-link {{ request()->routeIs('ismuba.ibadah.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Pantau Ibadah</a></li>
                        <li><a href="{{ route('ismuba.laporan.index') }}" class="nav-sub-link {{ request()->routeIs('ismuba.laporan.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Laporan ISMUBA</a></li>
                        <li><a href="{{ route('ismuba.jadwal-pengajian.index') }}" class="nav-sub-link {{ request()->routeIs('ismuba.jadwal-pengajian.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Jadwal Pengajian</a></li>
                    </ul>
                </li>

                {{-- ══════════════════════════════════════
                     MENU PKL
                ═══════════════════════════════════════ --}}
                <li class="nav-group {{ request()->is('pkl*') ? 'active' : '' }}" id="group-pkl">
                    <button class="nav-group-toggle" data-target="submenu-pkl" aria-expanded="{{ request()->is('pkl*') ? 'true' : 'false' }}">
                        <span class="nav-group-left">
                            <i class="fa-solid fa-building-user"></i>
                            <span>Menu PKL</span>
                        </span>
                        <i class="fa-solid {{ request()->is('pkl*') ? 'fa-minus' : 'fa-plus' }} nav-group-arrow"></i>
                    </button>
                    <ul class="nav-submenu {{ request()->is('pkl*') ? 'open' : '' }}" id="submenu-pkl">
                        <li><a href="{{ route('pkl.dashboard') }}" class="nav-sub-link {{ request()->routeIs('pkl.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Dashboard PKL</a></li>
                        <li><a href="{{ route('pkl.gelombang.index') }}" class="nav-sub-link {{ request()->routeIs('pkl.gelombang.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Gelombang PKL</a></li>
                        <li><a href="{{ route('pkl.dudi.index') }}" class="nav-sub-link {{ request()->routeIs('pkl.dudi.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Data DUDI</a></li>
                        <li><a href="{{ route('pkl.penempatan.index') }}" class="nav-sub-link {{ request()->routeIs('pkl.penempatan.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Data Penempatan</a></li>
                        <li><a href="{{ route('pkl.pindah-penempatan.index') }}" class="nav-sub-link {{ request()->routeIs('pkl.pindah-penempatan.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Pindah Penempatan</a></li>
                        <li><a href="{{ route('pkl.nomor-surat.index') }}" class="nav-sub-link {{ request()->routeIs('pkl.nomor-surat.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Nomor Surat</a></li>
                        <li><a href="{{ route('pkl.persuratan.index') }}" class="nav-sub-link {{ request()->routeIs('pkl.persuratan.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Persuratan</a></li>
                        <li><a href="{{ route('pkl.pembimbing.index') }}" class="nav-sub-link {{ request()->routeIs('pkl.pembimbing.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Pembimbing PKL</a></li>
                        <li><a href="{{ route('pkl.laporan.index') }}" class="nav-sub-link {{ request()->routeIs('pkl.laporan.*') ? 'active' : '' }}"><i class="fa-solid fa-chevron-right"></i> Laporan & Rekap</a></li>
                    </ul>
                </li>

                {{-- ══════════════════════════════════════
                     ATUR USER
                ═══════════════════════════════════════ --}}
                <li>
                    <a href="{{ route('atur-data.user') }}"
                       class="sidebar-link {{ request()->routeIs('atur-data.user*') ? 'active' : '' }}"
                       id="nav-atur-user">
                        <i class="fa-solid fa-user-shield"></i>
                        <span>Atur User</span>
                    </a>
                </li>

            </ul>
        </nav>

        {{-- Footer logout --}}
        <div class="sidebar-footer">
            <form action="{{ route('logout') }}" method="POST" id="logout-form" style="display: none;">
                @csrf
            </form>
            <button type="button" class="btn-logout" id="logout-button" onclick="openModal('modal-logout-confirm')">
                <i class="fa-solid fa-right-from-bracket"></i>
                <span>Keluar</span>
            </button>
        </div>
    </aside>

    {{-- Sidebar overlay for mobile --}}
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    {{-- ══════════════════════ MAIN CONTENT ══════════════════════ --}}
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-title" style="display:flex;align-items:center;gap:12px;">
                <button class="hamburger-btn" id="hamburger-btn" aria-label="Toggle Menu">
                    <i class="fa-solid fa-bars"></i>
                </button>
                <div>
                    <h1>@yield('header_title', 'Dashboard')</h1>
                    <p>@yield('header_subtitle', 'Ringkasan aktivitas sekolah')</p>
                </div>
            </div>

            <div class="topbar-right">
                <div class="date-badge">
                    <i class="fa-regular fa-calendar"></i>
                    {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                </div>
                <div class="user-profile">
                    <div class="user-avatar">
                        {{ strtoupper(substr(Auth::user()->nama_lengkap ?? 'A', 0, 1)) }}
                    </div>
                    <div class="user-info">
                        <span class="user-name">{{ Auth::user()->nama_lengkap ?? 'Administrator' }}</span>
                        <span class="user-role">{{ Auth::user()->role_label ?? Auth::user()->level ?? 'Admin' }}</span>
                    </div>
                </div>
            </div>
        </header>

        @yield('content')
    </main>
</div>

<!-- Global Delete Confirmation Modal -->
<div class="modal-overlay" id="modal-delete-global">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3>Konfirmasi Hapus</h3>
            <button onclick="closeModal('modal-delete-global')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body text-center">
            <i class="fa-solid fa-triangle-exclamation text-danger mb-3" style="font-size: 3rem;"></i>
            <p id="delete-message" class="mb-4">Apakah Anda yakin ingin menghapus data ini? Semua data terkait juga akan dihapus secara permanen.</p>
            <form id="form-delete-global" method="POST" action="">
                @csrf
                @method('DELETE')
                <div class="form-actions justify-content-center">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('modal-delete-global')">Batal</button>
                    <button type="submit" class="btn btn-danger">Ya, Hapus Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Global Logout Confirmation Modal -->
<div class="modal-overlay" id="modal-logout-confirm">
    <div class="modal modal-md">
        <div class="modal-header">
            <h3>Konfirmasi Keluar</h3>
            <button onclick="closeModal('modal-logout-confirm')" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body text-center">
            <i class="fa-solid fa-right-from-bracket text-danger mb-3" style="font-size: 3rem; display: block; margin: 0 auto 16px;"></i>
            <p class="mb-4" style="color: var(--text-primary); font-size: 0.95rem; font-weight: 500;">Apakah Anda yakin ingin keluar dari sistem SmartSchool?</p>
            <div class="form-actions justify-content-center" style="display: flex; gap: 10px; justify-content: center;">
                <button type="button" class="btn btn-secondary" onclick="closeModal('modal-logout-confirm')">Batal</button>
                <button type="button" class="btn btn-danger" onclick="document.getElementById('logout-form').submit();">Ya, Keluar</button>
            </div>
        </div>
    </div>
</div>

<!-- Global Loading Overlay -->
<div class="modal-overlay" id="loading-overlay" style="z-index: 10000; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(5px);">
    <div style="background: var(--bg-card); padding: 30px 40px; border-radius: 20px; box-shadow: var(--shadow-hover); display: flex; flex-direction: column; align-items: center; gap: 20px; text-align: center; border: 1.5px solid var(--border-color); max-width: 340px; width: 90%;">
        <div class="loading-spinner"></div>
        <div>
            <h4 style="margin: 0 0 6px 0; font-size: 1.05rem; font-weight: 700; color: var(--text-primary);" id="loading-title">Memproses Data</h4>
            <p style="margin: 0; font-size: 0.83rem; color: var(--text-muted); line-height: 1.4;" id="loading-desc">Harap tunggu, proses ini mungkin memakan waktu beberapa saat...</p>
        </div>
    </div>
</div>

<script>
function confirmDelete(url, message) {
    document.getElementById('form-delete-global').action = url;
    if(message) {
        document.getElementById('delete-message').innerText = message;
    } else {
        document.getElementById('delete-message').innerText = 'Apakah Anda yakin ingin menghapus data ini? Semua data terkait juga akan dihapus secara permanen.';
    }
    openModal('modal-delete-global');
}

document.addEventListener('DOMContentLoaded', function () {
    // ── Accordion ──
    const toggles = document.querySelectorAll('.nav-group-toggle');
    toggles.forEach(function (btn) {
        const targetId = btn.getAttribute('data-target');
        const submenu  = document.getElementById(targetId);
        const arrow    = btn.querySelector('.nav-group-arrow');
        const isOpen   = btn.getAttribute('aria-expanded') === 'true';
        if (!isOpen) {
            submenu.style.maxHeight = '0';
            arrow.classList.replace('fa-minus','fa-plus');
        } else {
            submenu.style.maxHeight = submenu.scrollHeight + 'px';
        }
        btn.addEventListener('click', function () {
            const expanded = btn.getAttribute('aria-expanded') === 'true';
            if (expanded) {
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                requestAnimationFrame(() => { submenu.style.maxHeight = '0'; });
                btn.setAttribute('aria-expanded','false');
                arrow.classList.replace('fa-minus','fa-plus');
                submenu.classList.remove('open');
            } else {
                submenu.style.maxHeight = submenu.scrollHeight + 'px';
                btn.setAttribute('aria-expanded','true');
                arrow.classList.replace('fa-plus','fa-minus');
                submenu.classList.add('open');
            }
        });
    });

    // ── Close modal on overlay click ──
    document.querySelectorAll('.modal-overlay').forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === this) closeModal(this.id);
        });
    });

    // ── Close modal on Escape ──
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay.active').forEach(m => closeModal(m.id));
        }
    });
});

// ── Global Modal Functions ──
function openModal(id) {
    const el = document.getElementById(id);
    if (el) { el.classList.add('active'); document.body.style.overflow = 'hidden'; }
}
function closeModal(id) {
    const el = document.getElementById(id);
    if (el) {
        el.classList.remove('active');
        document.body.style.overflow = '';
        document.dispatchEvent(new CustomEvent('modalClosed', { detail: { id: id } }));
    }
}

// ── Global Loading Functions ──
function showLoading(title = 'Memproses Data', desc = 'Harap tunggu, proses ini mungkin memakan waktu beberapa saat...') {
    const elTitle = document.getElementById('loading-title');
    const elDesc = document.getElementById('loading-desc');
    if (elTitle) elTitle.textContent = title;
    if (elDesc) elDesc.textContent = desc;
    openModal('loading-overlay');
}
function hideLoading() {
    closeModal('loading-overlay');
}

// ── Responsive Sidebar Toggle ──
(function() {
    const sidebar      = document.getElementById('sidebar');
    const overlay      = document.getElementById('sidebar-overlay');
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const closeBtn     = document.getElementById('sidebar-close-btn');
    const mainContent  = document.querySelector('.main-content');
    const BREAKPOINT   = 1024;

    function isMobile() { return window.innerWidth <= BREAKPOINT; }

    function openSidebar() {
        if (isMobile()) {
            sidebar.classList.add('mobile-open');
            overlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        } else {
            sidebar.classList.remove('collapsed');
            mainContent.style.marginLeft = '260px';
            mainContent.style.width = 'calc(100% - 260px)';
        }
    }

    function closeSidebar() {
        if (isMobile()) {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
        } else {
            sidebar.classList.add('collapsed');
            mainContent.style.marginLeft = '0';
            mainContent.style.width = '100%';
        }
    }

    function toggleSidebar() {
        if (isMobile()) {
            sidebar.classList.contains('mobile-open') ? closeSidebar() : openSidebar();
        } else {
            sidebar.classList.contains('collapsed') ? openSidebar() : closeSidebar();
        }
    }

    if (hamburgerBtn) hamburgerBtn.addEventListener('click', toggleSidebar);
    if (closeBtn)     closeBtn.addEventListener('click', closeSidebar);
    if (overlay)      overlay.addEventListener('click', closeSidebar);

    // On resize: reset inline styles
    window.addEventListener('resize', function() {
        if (!isMobile()) {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
            document.body.style.overflow = '';
            if (!sidebar.classList.contains('collapsed')) {
                mainContent.style.marginLeft = '260px';
                mainContent.style.width = 'calc(100% - 260px)';
            } else {
                mainContent.style.marginLeft = '0';
                mainContent.style.width = '100%';
            }
        } else {
            mainContent.style.marginLeft = '';
            mainContent.style.width = '';
        }
    });
})();
</script>

<!-- Global Crop Modal -->
<div class="modal-overlay" id="modal-crop-image" style="z-index: 9999;">
    <div class="modal modal-lg" style="max-width: 600px; width: 90%;">
        <div class="modal-header" style="border-bottom: 1.5px solid #f1f5f9; padding: 14px 20px;">
            <h3><i class="fa-solid fa-crop-simple" style="color: var(--color-primary);"></i> Sesuaikan Gambar</h3>
            <button type="button" onclick="closeCropModal()" class="modal-close"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="modal-body" style="padding: 20px; background: #f8fafc; display: flex; flex-direction: column; align-items: center; gap: 16px;">
            <div style="width: 100%; max-height: 400px; overflow: hidden; border-radius: 12px; background: #000; display: flex; align-items: center; justify-content: center;">
                <img id="cropper-target" src="" style="max-width: 100%; max-height: 380px; display: block;">
            </div>
            <div style="display: flex; gap: 8px; width: 100%; justify-content: center; flex-wrap: wrap;">
                <button type="button" class="btn btn-secondary btn-sm" onclick="cropperRotate(-90)" title="Rotate Left">
                    <i class="fa-solid fa-rotate-left"></i> Putar Kiri
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="cropperRotate(90)" title="Rotate Right">
                    <i class="fa-solid fa-rotate-right"></i> Putar Kanan
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="cropperZoom(0.1)" title="Zoom In">
                    <i class="fa-solid fa-magnifying-glass-plus"></i> Perbesar
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="cropperZoom(-0.1)" title="Zoom Out">
                    <i class="fa-solid fa-magnifying-glass-minus"></i> Perkecil
                </button>
                <button type="button" class="btn btn-secondary btn-sm" onclick="cropperReset()" title="Reset">
                    <i class="fa-solid fa-arrow-rotate-left"></i> Reset
                </button>
            </div>
        </div>
        <div class="modal-footer" style="border-top: 1.5px solid #f1f5f9; padding: 14px 20px; display: flex; justify-content: flex-end; gap: 10px; background: #f8fafc; border-radius: 0 0 var(--radius-card) var(--radius-card);">
            <button type="button" class="btn btn-secondary" onclick="closeCropModal()">Batal</button>
            <button type="button" class="btn btn-primary" onclick="applyCrop()">
                <i class="fa-solid fa-check"></i> Selesai & Terapkan
            </button>
        </div>
    </div>
</div>

{{-- Cropper.js --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
let currentCropper = null;
let currentCropCallback = null;
let currentCropCancelCallback = null;

function openCropModal(imageSrc, aspectRatio, callback, cancelCallback = null) {
    const target = document.getElementById('cropper-target');
    target.src = imageSrc;
    currentCropCallback = callback;
    currentCropCancelCallback = cancelCallback;
    
    openModal('modal-crop-image');
    
    // Initialize cropper after modal is shown and layout is computed
    setTimeout(() => {
        if (currentCropper) {
            currentCropper.destroy();
        }
        currentCropper = new Cropper(target, {
            aspectRatio: aspectRatio,
            viewMode: 1,
            background: true,
            responsive: true,
            autoCropArea: 0.9,
            movable: true,
            zoomable: true,
            rotatable: true,
            scalable: true
        });
    }, 200);
}

function cropperRotate(deg) {
    if (currentCropper) currentCropper.rotate(deg);
}

function cropperZoom(val) {
    if (currentCropper) currentCropper.zoom(val);
}

function cropperReset() {
    if (currentCropper) currentCropper.reset();
}

function closeCropModal() {
    closeModal('modal-crop-image');
    if (currentCropper) {
        currentCropper.destroy();
        currentCropper = null;
    }
    if (currentCropCancelCallback) {
        currentCropCancelCallback();
    }
}

function applyCrop() {
    if (!currentCropper) return;
    
    // Get cropped canvas
    const canvas = currentCropper.getCroppedCanvas({
        imageSmoothingEnabled: true,
        imageSmoothingQuality: 'high',
    });
    
    if (canvas) {
        canvas.toBlob((blob) => {
            if (currentCropCallback) {
                currentCropCallback(blob);
            }
            closeCropModal();
        }, 'image/jpeg', 0.9);
    }
}
</script>

@stack('scripts')

</body>
</html>
