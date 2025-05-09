<?php

use function Livewire\Volt\{state};

?>

<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div class="logo-src"></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>    
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            @persist('sidebar')
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">{{ __('Umum') }}</li>
                <li>
                    <a href="/dashboard" wire:current="mm-active" wire:navigate.hover>
                        <i class="metismenu-icon pe-7s-home">
                        </i>
                        {{ __('Beranda') }}
                    </a>
                </li>
                <li>
                    <a href="/email" wire:current="mm-active" wire:navigate.hover>
                        <i class="metismenu-icon pe-7s-mail">
                        </i>
                        {{ __('Email') }}
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-users">
                        </i>
                        {{ __('Daftar Warga') }}
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-id">
                        </i>
                        {{ __('Daftar Pengguna') }}
                    </a>
                </li>
                <li class="app-sidebar__heading">{{ __('Kelola Iuran & Biaya') }}</li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-box1">
                        </i>
                        {{ __('Daftar Iuran') }}
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-note2">
                        </i>
                        {{ __('Tambah Iuran Bulanan') }}
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-credit">
                        </i>
                        {{ __('Tambah Biaya Pengeluaran') }}
                    </a>
                </li>
                <li class="app-sidebar__heading">{{ __('Riwayat') }}</li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-folder">
                        </i>
                        {{ __('Riwayat Iuran Bulanan') }}
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-cash">
                        </i>
                        {{ __('Riwayat Transaksi') }}
                    </a>
                </li>
                {{-- <li class="app-sidebar__heading">{{  __('Laporan') }}</li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-notebook">
                        </i>
                        {{ __('Laporan Iuran') }}
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-wallet">
                        </i>
                        {{ __('Laporan Keuangan') }}
                    </a>
                </li> --}}
                <li class="app-sidebar__heading">{{  __('Pengaturan') }}</li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-user">
                        </i>
                        {{ __('Profil') }}
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-network">
                        </i>
                        {{ __('IMAP') }}
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="metismenu-icon pe-7s-mail-open-file">
                        </i>
                        {{ __('Template Teks') }}
                    </a>
                </li>
            </ul>
            @endpersist
        </div>
    </div>
</div>
