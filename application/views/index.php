<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$pages_minimize = [
    'manage_inventaris',
    'manage_buku',
    'laporan_inventaris',
    'statistik',
    'manage_buku_baru',
    'laporan_peminjaman',
    'laporan_pengembalian'
];

$mode_left_menu = in_array($page_name, $pages_minimize) ? " m-aside-left--minimize m-brand--minimize " : " m-aside-left--enabled ";
?>
<!DOCTYPE html>
<html lang="en" >
    <head>
        <?php if ($page_name == "manage_media" || $page_name == "manage_artikel" || $page_name == "manage_slide") { ?>
            <link href="<?php echo base_url() ?>assets/global/plugins/cubeportfolio/css/cubeportfolio.css" rel="stylesheet" type="text/css" />
            <link href="<?php echo base_url() ?>assets/pages/css/portfolio.min.css" rel="stylesheet" type="text/css" />
            <link href="<?php echo base_url() ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet" type="text/css" />
            <link href="<?php echo base_url() ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css" rel="stylesheet"  />
        <?php } ?>
        <?php $this->load->helper('header'); ?>
    </head>
    <body class="m-page--fluid m--skin- m-content--skin-light2 m-header--fixed m-header--fixed-mobile <?php echo $mode_left_menu;?>   m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default"  >
        <!-- begin:: Page -->
        <div class="m-grid m-grid--hor m-grid--root m-page">	
            <?php include $this->session->userdata('login_type') . '/header.php'; ?>
            <div class="m-grid__item m-grid__item--fluid m-grid m-grid--ver-desktop m-grid--desktop m-body">
                <button class="m-aside-left-close  m-aside-left-close--skin-dark " id="m_aside_left_close_btn">
                    <i class="la la-close"></i>
                </button>
                <?php include $this->session->userdata('login_type') . '/asidemenu.php'; ?>
                <?php
                if (isset($page_dir)) {
                    include 'pages/' . $page_dir . '/' . $page_file . '.php';
                } else {
                    include $this->session->userdata('login_type') . '/' . $page_name . '.php';
                }
                ?>
            </div>
            <?php if ($page_name == "manage_media" || $page_name == "manage_artikel" || $page_name == "manage_slide") { ?>
                <script src="<?php echo base_url() ?>assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js" type="text/javascript"></script>
                <script src="<?php echo base_url() ?>assets/global/plugins/cubeportfolio/js/jquery.cubeportfolio.js" type="text/javascript"></script>
                <script src="<?php echo base_url() ?>assets/pages/scripts/portfolio-1.js" type="text/javascript"></script>
                <script src="<?php echo base_url() ?>assets/demo/default/custom/components/forms/widgets/dropzone.js" type="text/javascript"></script>
            <?php } ?>
            <?php $this->load->helper('footer'); ?>
        </div>
        <div class="m-scroll-top m-scroll-top--skin-top" data-toggle="m-scroll-top" data-scroll-offset="500" data-scroll-speed="300">
            <i class="la la-arrow-up"></i>
        </div>
        <?php $this->load->helper('basescript'); ?>
    </body>
</html>