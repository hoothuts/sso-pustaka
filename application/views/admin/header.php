<style>
    .m-header-menu .m-menu__nav > .m-menu__item > .m-menu__link {
        padding: 23px;
    }
</style>
<header class="m-grid__item    m-header " data-minimize-offset="200" data-minimize-mobile-offset="200">
    <div class="m-container m-container--fluid m-container--full-height">
        <div class="m-stack m-stack--ver m-stack--desktop">
            <!-- BEGIN: Brand -->
            <div class="m-stack__item m-brand  m-brand--skin-dark ">
                <div class="m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-stack__item--middle m-brand__logo">
                        <a href="<?php echo base_url(); ?>admin/<?php echo $this->session->userdata('default'); ?>" class="m-brand__logo-wrapper">
                            <img alt="" src="<?php echo base_url(); ?>assets/demo/demo2/media/img/logo/logo.png" />
                        </a>
                    </div>
                    <div class="m-stack__item m-stack__item--middle m-brand__tools">
                        <!-- BEGIN: Left Aside Minimize Toggle -->
                        <a href="javascript:;" id="m_aside_left_minimize_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-desktop-inline-block  ">
                            <span></span>
                        </a>
                        <!-- END -->
                        <!-- BEGIN: Responsive Aside Left Menu Toggler -->
                        <a href="javascript:;" id="m_aside_left_offcanvas_toggle" class="m-brand__icon m-brand__toggler m-brand__toggler--left m--visible-tablet-and-mobile-inline-block">
                            <span></span>
                        </a>
                        <!-- END -->
                        <!-- BEGIN: Responsive Header Menu Toggler -->
                        <a id="m_aside_header_menu_mobile_toggle" href="javascript:;" class="m-brand__icon m-brand__toggler m--visible-tablet-and-mobile-inline-block">
                            <span></span>
                        </a>
                        <!-- END -->
                        <!-- BEGIN: Topbar Toggler -->
                        <a id="m_aside_header_topbar_mobile_toggle" href="javascript:;" class="m-brand__icon m--visible-tablet-and-mobile-inline-block">
                            <i class="flaticon-more"></i>
                        </a>
                        <!-- BEGIN: Topbar Toggler -->
                    </div>
                </div>
            </div>
            <!-- END: Brand -->
            <div class="m-stack__item m-stack__item--fluid m-header-head" id="m_header_nav">
                <!-- BEGIN: Horizontal Menu -->
                <button class="m-aside-header-menu-mobile-close  m-aside-header-menu-mobile-close--skin-dark " id="m_aside_header_menu_mobile_close_btn">
                    <i class="la la-close"></i>
                </button>
                <div id="m_header_menu" class="m-header-menu m-aside-header-menu-mobile m-aside-header-menu-mobile--offcanvas  m-header-menu--skin-light m-header-menu--submenu-skin-light m-aside-header-menu-mobile--skin-dark m-aside-header-menu-mobile--submenu-skin-dark ">
                    <ul class="m-menu__nav  m-menu__nav--submenu-arrow ">

                        <?php if (in_array('statistik', $this->session->userdata('perm_view'))) : ?>
                            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" data-menu-submenu-toggle="click" aria-haspopup="true">
                                <a href="<?php echo base_url(); ?>admin/statistik" class="m-menu__link m-menu__toggle">
                                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                                    <span class="m-menu__link-text">
                                        Statistik
                                    </span>
                                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu  m-menu__submenu--fixed m-menu__submenu--left" style="width:1000px">
                                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <div class="m-menu__subnav">
                                        <ul class="m-menu__content">
                                            <li class="m-menu__item">
                                                <h3 class="m-menu__heading m-menu__toggle">
                                                    <span class="m-menu__link-text">
                                                        Statistik Anggota
                                                    </span>
                                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                                </h3>
                                                <ul class="m-menu__inner">
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/statistik/anggota" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Anggota
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="m-menu__item">
                                                <h3 class="m-menu__heading m-menu__toggle">
                                                    <span class="m-menu__link-text">
                                                        Statistik Buku
                                                    </span>
                                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                                </h3>
                                                <ul class="m-menu__inner">
                                                    <!-- Buku -->
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/statistik/buku" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                                            <span class="m-menu__link-text">Buku</span>
                                                        </a>
                                                    </li>
                                                    <!-- 	Buku Berdasar Judul -->
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/statistik/buku_judul" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                                            <span class="m-menu__link-text">Buku Berdasar Judul</span>
                                                        </a>
                                                    </li>
                                                    <!-- 	Buku Tahun Terbit (Berdasar Judul) -->
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/statistik/buku_tahun_judul" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                                            <span class="m-menu__link-text">Buku Tahun Terbit (Berdasar Judul)</span>
                                                        </a>
                                                    </li>
                                                    <!-- 	Buku Tahun Terbit (Berdasar Jumlah) -->
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>admin/statistik/bukubythn_jml" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                                            <span class="m-menu__link-text">Buku Tahun Terbit (Berdasar Jumlah)</span>
                                                        </a>
                                                    </li>
                                                    <!-- Buku Referensi Jurusan -->
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/statistik/buku_prodi" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot"><span></span></i>
                                                            <span class="m-menu__link-text">Buku Referensi Program Studi</span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="m-menu__item">
                                                <h3 class="m-menu__heading m-menu__toggle">
                                                    <span class="m-menu__link-text">
                                                        Statistik Peminjaman
                                                    </span>
                                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                                </h3>
                                                <ul class="m-menu__inner">
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/statistik/peminjaman" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Peminjaman
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/statistik/denda" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Denda
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="m-menu__item">
                                                <h3 class="m-menu__heading m-menu__toggle">
                                                    <span class="m-menu__link-text">
                                                        Statistik Lainnya
                                                    </span>
                                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                                </h3>
                                                <ul class="m-menu__inner">
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/statistik/presensi" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Presensi Kunjungan
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/statistik/kunjungan_baca_buku" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Kunjungan Baca Buku
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/statistik/pengunjung_web" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Kunjungan Web
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>admin/statistik/periodik" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Periodik
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>
                        <?php if (in_array('laporan', $this->session->userdata('perm_view'))) : ?>
                            <li class="m-menu__item  m-menu__item--submenu m-menu__item--rel" data-menu-submenu-toggle="click" aria-haspopup="true">
                                <a href="#" class="m-menu__link m-menu__toggle">
                                    <i class="m-menu__link-icon flaticon-line-graph"></i>
                                    <span class="m-menu__link-text">
                                        Laporan
                                    </span>
                                    <i class="m-menu__hor-arrow la la-angle-down"></i>
                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                </a>
                                <div class="m-menu__submenu  m-menu__submenu--fixed m-menu__submenu--left" style="width:1000px">
                                    <span class="m-menu__arrow m-menu__arrow--adjust"></span>
                                    <div class="m-menu__subnav">
                                        <ul class="m-menu__content">
                                            <li class="m-menu__item">
                                                <h3 class="m-menu__heading m-menu__toggle">
                                                    <span class="m-menu__link-text">
                                                        Laporan Anggota
                                                    </span>
                                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                                </h3>
                                                <ul class="m-menu__inner">
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>admin/laporan_anggota" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Daftar Anggota
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/laporan_anggota_teraktif" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Daftar Anggota Teraktif
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="m-menu__item">
                                                <h3 class="m-menu__heading m-menu__toggle">
                                                    <span class="m-menu__link-text">
                                                        Laporan Buku
                                                    </span>
                                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                                </h3>
                                                <ul class="m-menu__inner">
                                                    <!-- <li class="m-menu__item "  data-redirect="true" aria-haspopup="true">
                                                            <a  href="header/actions.html" class="m-menu__link ">
                                                                    <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                            <span></span>
                                                                    </i>
                                                                    <span class="m-menu__link-text">
                                                                            Daftar Buku Referensi Program Studi
                                                                    </span>
                                                            </a>
                                                    </li> -->
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/laporan_inventaris" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Daftar Inventaris Buku
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>admin/laporan_kataloginventaris" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Daftar Katalog Inventaris Buku
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>admin/laporan_hilang" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Daftar Buku Hilang/Rusak
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/laporan_buku_populer" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Daftar Buku Populer
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="m-menu__item">
                                                <h3 class="m-menu__heading m-menu__toggle">
                                                    <span class="m-menu__link-text">
                                                        Laporan Denda:
                                                    </span>
                                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                                </h3>
                                                <ul class="m-menu__inner">
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>admin/laporan_denda" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Daftar Denda Per Periode
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="m-menu__item">
                                                <h3 class="m-menu__heading m-menu__toggle">
                                                    <span class="m-menu__link-text">
                                                        Laporan Peminjaman
                                                    </span>
                                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                                </h3>
                                                <ul class="m-menu__inner">
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/laporan_peminjaman" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Daftar Peminjaman
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>dir/laporan_pengembalian" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Daftar Pengembalian
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>admin/laporan_belumkembali" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Daftar Buku Yang Belum Dikembalikan
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                            <li class="m-menu__item">
                                                <h3 class="m-menu__heading m-menu__toggle">
                                                    <span class="m-menu__link-text">
                                                        Lain - lain:
                                                    </span>
                                                    <i class="m-menu__ver-arrow la la-angle-right"></i>
                                                </h3>
                                                <ul class="m-menu__inner">
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>admin/laporan_presensi" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Daftar Pengunjung
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>admin/laporan_pengunjung" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Pengunjung Tamu
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-menu__item " data-redirect="true" aria-haspopup="true">
                                                        <a href="<?php echo base_url(); ?>admin/laporan_log" class="m-menu__link ">
                                                            <i class="m-menu__link-bullet m-menu__link-bullet--dot">
                                                                <span></span>
                                                            </i>
                                                            <span class="m-menu__link-text">
                                                                Log Aktifitas
                                                            </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </div>

                <!-- END: Horizontal Menu -->
                <!-- BEGIN: Topbar -->
                <div id="m_header_topbar" class="m-topbar  m-stack m-stack--ver m-stack--general">
                    <div class="m-stack__item m-topbar__nav-wrapper">
                        <ul class="m-topbar__nav m-nav m-nav--inline">
                            <li class="m-nav__item m-topbar__user-profile m-topbar__user-profile--img  m-dropdown m-dropdown--medium m-dropdown--arrow m-dropdown--header-bg-fill m-dropdown--align-right m-dropdown--mobile-full-width m-dropdown--skin-light" data-dropdown-toggle="click">
                                <a href="#" class="m-nav__link m-dropdown__toggle">
                                    <span class="m-topbar__userpic">
                                        <img src="<?php echo base_url(); ?>uploads/profile/<?php echo $this->session->userdata('avatar'); ?>" class="m--img-rounded m--marginless m--img-centered" alt="" />
                                    </span>
                                    <span class="m-topbar__username m--hide">
                                        <?php echo $this->session->userdata('username'); ?>
                                    </span>
                                </a>
                                <div class="m-dropdown__wrapper">
                                    <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust"></span>
                                    <div class="m-dropdown__inner">
                                        <div class="m-dropdown__header m--align-center" style="background: url(<?php echo base_url(); ?>assets/app/media/img/misc/user_profile_bg.jpg); background-size: cover;">
                                            <div class="m-card-user m-card-user--skin-dark">
                                                <div class="m-card-user__pic">

                                                    <img src="<?php echo base_url(); ?>uploads/profile/<?php echo $this->session->userdata('avatar'); ?>" class="m--img-rounded m--marginless" alt="" />
                                                </div>
                                                <div class="m-card-user__details">
                                                    <span class="m-card-user__name m--font-weight-500">
                                                        <?php echo $this->session->userdata('username'); ?>
                                                    </span>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="m-dropdown__body">
                                            <div class="m-dropdown__content">
                                                <ul class="m-nav m-nav--skin-light">
                                                    <li class="m-nav__item">
                                                        <a href="<?php echo base_url(); ?>admin/ganti_password" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-user-settings"></i>
                                                            <span class="m-nav__link-text">
                                                                Change Password
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="#" class="m-nav__link" data-toggle="modal" data-target="#m_modal_4">
                                                            <i class="m-nav__link-icon flaticon-profile"></i>
                                                            <span class="m-nav__link-text">
                                                                Change Picture
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__item">
                                                        <a href="<?php echo base_url(); ?>dir/profile" class="m-nav__link">
                                                            <i class="m-nav__link-icon flaticon-user"></i>
                                                            <span class="m-nav__link-text">
                                                                Change Profile
                                                            </span>
                                                        </a>
                                                    </li>
                                                    <li class="m-nav__separator m-nav__separator--fit"></li>
                                                    <li class="m-nav__item">
                                                        <a href="<?php echo base_url(); ?>admin/logout" class="btn m-btn--pill    btn-secondary m-btn m-btn--custom m-btn--label-brand m-btn--bolder">
                                                            Logout
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- END: Topbar -->
            </div>
        </div>
    </div>

</header>
<!--begin::Modal-->
<div class="modal fade" id="m_modal_4" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">
                    Change Profile's Picture
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <!-- <div class="m-portlet m-portlet--tabs"> -->
                <div class="m-portlet__head">
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs-line m-tabs-line--success m-tabs-line--2x" role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#dropzone" role="tab" aria-expanded="false">
                                    <i class="la la-cog"></i>
                                    Upload
                                </a>
                            </li>
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#default" role="tab" aria-expanded="false">
                                    <i class="la la-briefcase"></i>
                                    Default Pictures
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <form method="post" action="<?php echo base_url('admin/ganti_pic'); ?>" enctype="multipart/form-data">
                        <div class="tab-content">
                            <div class="tab-pane active" id="dropzone" role="tabpanel">

                                <div class="fileinput fileinput-new" data-provides="fileinput">
                                    <span class="btn btn-default btn-file"><span class="fileinput-new">Select file</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file" name="profile_pic"></span>
                                    <span class="fileinput-filename"></span>
                                    <a href="#" class="close fileinput-exists" data-dismiss="fileinput" style="float: none">&times;</a>
                                </div>
                            </div>
                            <div class="tab-pane" id="default" role="tabpanel">
                                <select id="selectImage" class="image-picker show-labels show-html" name="pic_def">
                                    <!-- <option value="0">None</option> -->
                                    <option data-img-src="<?php echo base_url('uploads/profile/Male-1.png'); ?>" value="Male-1.png">Male 1</option>
                                    <option data-img-src="<?php echo base_url('uploads/profile/Male-2.png'); ?>" value="Male-2.png">Male 2</option>
                                    <option data-img-src="<?php echo base_url('uploads/profile/Female-1.png'); ?>" value="Female-1.png">Female 1</option>
                                    <option data-img-src="<?php echo base_url('uploads/profile/Female-2.png'); ?>" value="Female-2.png">Female 2</option>
                                </select>
                            </div>
                        </div>

                </div>
                <!-- </div> -->
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">
                    Confirm
                </button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
            </div>
            </form>
        </div>
    </div>
</div>
<!--end::Modal-->
<script>
    $(document).ready(function () {
        $("#selectImage").imagepicker({
            hide_select: true
        });
    });
</script>