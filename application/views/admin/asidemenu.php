<?php
// ─── HELPER FUNCTIONS ────────────────────────────────────────────────
function hasPerm($keys, $session) {
    $perms = $session->userdata('perm_view');
    foreach ((array)$keys as $key) {
        if (in_array($key, $perms)) return true;
    }
    return false;
}

function menuItem($perm, $session, $page_now, $active_key, $url, $icon, $label, $badge = '') {
    if (!hasPerm($perm, $session)) return;
    $active = ($page_now == $active_key) ? ' m-menu__item--active ' : '';
    echo "
    <li class='m-menu__item {$active}' aria-haspopup='true'>
        <a href='{$url}' class='m-menu__link'>
            <i class='m-menu__link-icon {$icon}'></i>
            <span class='m-menu__link-title'>
                <span class='m-menu__link-wrap'>
                    <span class='m-menu__link-text'>{$label}</span>
                    {$badge}
                </span>
            </span>
        </a>
    </li>";
}

function subMenuItem($perm, $session, $url, $label) {
    if (!hasPerm($perm, $session)) return;
    echo "
    <li class='m-menu__item' aria-haspopup='true'>
        <a href='{$url}' class='m-menu__link'>
            <span class='m-menu__link-title'>
                <span class='m-menu__link-wrap'>
                    <span class='m-menu__link-text'>{$label}</span>
                </span>
            </span>
        </a>
    </li>";
}

function subMenuGroup($perms, $session, $page_now, $active_keys, $icon, $label, $items_callback) {
    if (!hasPerm($perms, $session)) return;
    $active_keys = (array) $active_keys; // support string maupun array
    $active = in_array($page_now, $active_keys) ? ' m-menu__item--active ' : '';
    echo "
    <li class='m-menu__item {$active} m-menu__item--submenu m-menu__item--rel' data-menu-submenu-toggle='click' aria-haspopup='true'>
        <a href='#' class='m-menu__link m-menu__toggle'>
            <i class='m-menu__link-icon {$icon}'></i>
            <span class='m-menu__link-text'>{$label}</span>
            <i class='m-menu__ver-arrow la la-angle-right'></i>
        </a>
        <div class='m-menu__submenu m-menu__submenu--classic m-menu__submenu--left'>
            <span class='m-menu__arrow m-menu__arrow--adjust'></span>
            <ul class='m-menu__subnav'>";
                $items_callback();
    echo "  </ul>
        </div>
    </li>";
}

$base   = base_url();
$s      = $this->session;
$perms  = $s->userdata('perm_view');
?>

<div id="m_aside_left" class="m-grid__item m-aside-left m-aside-left--skin-dark">
    <div id="m_ver_menu" class="m-aside-menu m-aside-menu--skin-dark m-aside-menu--submenu-skin-dark"
         data-menu-vertical="true" data-menu-scrollable="false" data-menu-dropdown-timeout="500">
        <ul class="m-menu__nav m-menu__nav--dropdown-submenu-arrow">

            <?php menuItem('dashboard', $s, $page_now, 'dashboard',
                "{$base}admin/dashboard", 'flaticon-line-graph', 'Dashboard'); ?>

            <?php subMenuGroup(
                ['manage_artikel','manage_pengumuman','manage_halaman','manage_menu',
                 'manage_media','manage_slide','manage_logo','manage_mediasosial','manage_kontak'],
                $s, $page_now,
                ['Konten Website', 'artikel', 'pengumuman', 'halaman', 'menu', 'media', 'slide', 'logo', 'mediasosial', 'manage_kontak'],
                'flaticon-browser', 'Konten Website',
                function() use ($s, $base) {
                    subMenuItem('manage_artikel',     $s, "{$base}admin/manage_artikel",     'Artikel');
                    subMenuItem('manage_pengumuman',  $s, "{$base}admin/manage_pengumuman",  'Pengumuman');
                    subMenuItem('manage_halaman',     $s, "{$base}admin/manage_halaman",     'Halaman');
                    subMenuItem('manage_menu',        $s, "{$base}admin/manage_menu",        'Menu');
                    subMenuItem('manage_media',       $s, "{$base}admin/manage_media",       'Media');
                    subMenuItem('manage_slide',       $s, "{$base}admin/manage_slide",       'Slide');
                    subMenuItem('manage_logo',        $s, "{$base}admin/manage_logo",        'Logo');
                    subMenuItem('manage_mediasosial', $s, "{$base}admin/manage_mediasosial", 'Media Sosial');
                    subMenuItem('manage_kontak',      $s, "{$base}admin/manage_kontak",      'Kontak WA');
                    subMenuItem('manage_buku_baru',         $s, "{$base}dir/manage_buku_baru",      'Buku Baru');
                    subMenuItem('manage_gambar_popup',         $s, "{$base}dir/manage_gambar_popup",      'Gambar Pop Up Beranda');
                    subMenuItem('manage_konfigurasi_web',   $s, "{$base}dir/manage_konfigurasi_web",      'Konfigurasi Halaman Beranda');
                }
            ); ?>
            <li class="m-menu__section">
                <h4 class="m-menu__section-text">Master</h4>
                <i class="m-menu__section-icon flaticon-more-v3"></i>
            </li>

            <?php subMenuGroup(
                ['data_anggota','data_anggota_luar','data_dosen','data_mahasiswa','manage_pegawai'],
                $s, $page_now,
                ['Data Induk'],
                'flaticon-users', 'Data Induk',
                function() use ($s, $base) {
                    subMenuItem('data_mahasiswa',    $s, "{$base}admin/data_mahasiswa",    'Data Mahasiswa');
                    //subMenuItem('data_dosen',        $s, "{$base}admin/data_dosen",        'Data Dosen');
                    subMenuItem('manage_pegawai',    $s, "{$base}dir/manage_pegawai",      'Data Pegawai');
                    subMenuItem('data_anggota_luar', $s, "{$base}admin/data_anggota_luar", 'Data Anggota Luar');
                    subMenuItem('data_anggota',      $s, "{$base}admin/data_anggota",      'Data Anggota');
                }
            ); ?>

            <?php subMenuGroup(
                ['manage_klasifikasi','kategori_buku','bahasa','asal_buku','penerbit','keperluan_sbppl','program_studi','matakuliah','manage_lokasi','manage_kartu_anggota'],
                $s, $page_now,
                ['Data Referensi'],
                'flaticon-book', 'Data Referensi',
                function() use ($s, $base) {
                    subMenuItem('manage_klasifikasi', $s, "{$base}dir/manage_klasifikasi", 'Klasifikasi Buku');
                    subMenuItem('kategori_buku',    $s, "{$base}admin/kategori_buku",    'Kategori Buku');
                    subMenuItem('bahasa',           $s, "{$base}admin/bahasa",           'Bahasa');
                    subMenuItem('asal_buku',        $s, "{$base}admin/asal_buku",        'Asal Buku');
                    subMenuItem('penerbit',         $s, "{$base}admin/penerbit",         'Penerbit');
                    subMenuItem('keperluan_sbppl',  $s, "{$base}admin/keperluan_sbppl",  'Keperluan SBPPL');
                    subMenuItem('program_studi',    $s, "{$base}admin/program_studi",    'Program Studi');
                    subMenuItem('matakuliah',       $s, "{$base}admin/matakuliah",       'Matakuliah');
                    subMenuItem('manage_lokasi',    $s, "{$base}dir/manage_lokasi",      'Lokasi');
                    subMenuItem('manage_kartu_anggota',    $s, "{$base}dir/manage_kartu_anggota", 'Kartu Anggota');
                }
            ); ?>

           <?php subMenuGroup(
                ['data_buku','inventaris','buku_prodi','import_data','manage_resensi'],
                $s, $page_now,
                ['Buku & Inventarisasi', 'resensi'],  // <-- 'resensi' adalah nilai $page_now saat buka resensi
                'flaticon-notes', 'Buku & Inventarisasi',
                function() use ($s, $base) {
                    subMenuItem('data_buku',      $s, "{$base}dir/manage_buku",      'Data Buku');
                    subMenuItem('inventaris',     $s, "{$base}dir/manage_inventaris",     'Inventaris');
                    subMenuItem('buku_prodi',     $s, "{$base}admin/buku_prodi",     'Buku Jurusan');
                    subMenuItem('import_data',    $s, "{$base}admin/import_data",    'Import Data');
                    subMenuItem('manage_resensi', $s, "{$base}admin/manage_resensi", 'Resensi');
                }
            ); ?>

            <?php subMenuGroup(
                ['hilang','transaksi','traninv','presensi','manage_bebas_pustaka'],
                $s, $page_now,
                ['Transaksi', 'Bebas Pustaka'],  // <-- 'Bebas Pustaka' adalah nilai $page_now saat buka bebas pustaka
                'flaticon-coins', 'Transaksi',
                function() use ($s, $base) {
                    subMenuItem('hilang',                   $s, "{$base}admin/hilang", 'Hilang/Rusak/Etc');
                    subMenuItem('transaksi',                $s, "{$base}admin/transaksi", 'Transaksi');
                    subMenuItem('transaksi',                $s, "{$base}admin/pengembalian", 'Pengembalian');
                    subMenuItem('traninv',                  $s, "{$base}admin/traninv", 'Pengembalian Buku Tanpa Kartu Anggota');
                    subMenuItem('presensi',                 $s, "{$base}admin/presensi", 'Presensi');
                    //subMenuItem('manage_bebas_pustaka',     $s, "{$base}admin/manage_bebas_pustaka", 'Bebas Pustaka');
                    subMenuItem('manage_opname',            $s, "{$base}dir/manage_opname", 'Opname');
                    subMenuItem('manage_penyiangan',        $s, "{$base}dir/manage_penyiangan", 'Penyiangan');
                    subMenuItem('manage_penghapusan',       $s, "{$base}dir/manage_penghapusan", 'Penghapusan');
                    subMenuItem('konfirmasi_penghapusan',   $s, "{$base}dir/konfirmasi_penghapusan", 'Konfirmasi Penghapusan');
                }
            ); ?>

            <?php
            // Live Chat dengan badge unread
            $badge = '<span class="m-menu__link-badge">
                        <span id="global-unread-badge" class="m-badge m-badge--danger" style="display:none;"></span>
                      </span>';
            menuItem('manage_chat', $s, $page_now, 'Live Chat',
                "{$base}admin/manage_chat", 'flaticon-chat-1', 'Live Chat', $badge);
            ?>
            
            <?php subMenuGroup(
                ['dashboard_jurnal','manage_jurnal_vendor','manage_jurnal_akun', 'manage_jurnal_log'],
                $s, $page_now,
                ['Jurnal Berlangganan'],
                'flaticon-interface-6', 'Jurnal Berlangganan',
                function() use ($s, $base) {
                    subMenuItem('dashboard_jurnal',      $s, "{$base}dir/dashboard_jurnal", 'Dasbor');
                    subMenuItem('manage_jurnal_vendor',     $s, "{$base}dir/manage_jurnal_vendor", 'Vendor Jurnal Berlangganan');
                    subMenuItem('manage_jurnal_akun',     $s, "{$base}dir/manage_jurnal_akun", 'Akun Jurnal');
                    subMenuItem('manage_jurnal_log',     $s, "{$base}dir/manage_jurnal_log", 'Log Aktifitas');
                }
            ); ?>
            
            <?php subMenuGroup(
                ['manage_syarat_bepus','manage_bepus_request','manage_bepus_prodi_no'],
                $s, $page_now,
                ['Request Bebas Pustaka'],
                'flaticon-list-2', 'Bebas Pustaka',
                function() use ($s, $base) {
                    subMenuItem('manage_bepus_prodi_no',    $s, "{$base}dir/manage_bepus_prodi_no", 'Format NO Surat');
                    subMenuItem('manage_syarat_bepus',      $s, "{$base}dir/manage_syarat_bepus", 'Syarat Bebas Pustaka');
                    subMenuItem('manage_bepus_request',     $s, "{$base}dir/manage_bepus_request", 'Request Bebas Pustaka');
                }
            ); ?>

            <script>
                function updateSidebarUnreadCount() {
                    $.get('<?= base_url("admin/manage_chat/unread_count_ajax"); ?>', function(data) {
                        var count = parseInt(data.total);
                        var badge = $('#global-unread-badge');
                        count > 0 ? badge.text(count).show() : badge.hide();
                    });
                }
                $(document).ready(function() {
                    updateSidebarUnreadCount();
                    setInterval(updateSidebarUnreadCount, 10000);
                });
            </script>

           <?php subMenuGroup(
                ['konfigurasi','konfigurasi_transaksi','hari_libur','hometxt'],
                $s, $page_now,
                ['Konfigurasi Transaksi'],
                'flaticon-interface-8', 'Konfigurasi Transaksi',
                function() use ($s, $base) {
                    subMenuItem('konfigurasi',           $s, "{$base}dir/manage_konfigurasi",      'Konfigurasi');
                    subMenuItem('konfigurasi_transaksi', $s, "{$base}admin/konfigurasi_transaksi", 'Konfigurasi Transaksi');
                    subMenuItem('hari_libur',            $s, "{$base}admin/hari_libur",           'Hari Libur');
                    subMenuItem('hometxt',               $s, "#",                                 'Konfigurasi Dashboard');
                }
            ); ?>

            <?php subMenuGroup(
                ['daftar_user','group_user','user_grant','import_data'],
                $s, $page_now,
                ['Administrator'],
                'flaticon-user-settings', 'Administrator',
                function() use ($s, $base) {
                    subMenuItem('daftar_user', $s, "{$base}admin/daftar_user", 'Daftar User');
                    subMenuItem('group_user',  $s, "{$base}admin/group_user",  'Group User');
                    subMenuItem('user_grant',  $s, "{$base}admin/user_grant",  'Grant User');
                }
            ); ?>

        </ul>
    </div>
</div>