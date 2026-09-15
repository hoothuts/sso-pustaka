<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    <?php echo $page_title; ?>
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?php echo base_url(); ?>admin/<?php echo $this->session->userdata('default'); ?>" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon flaticon-users"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">
                        -
                    </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">
                                Transaksi
                            </span>
                        </a>
                    </li>
                    <li class="m-nav__separator">
                        -
                    </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">
                                <?php echo $page_title; ?>
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->
    <div class="m-content">
        <?php if ($this->session->flashdata('alert') != ''): ?>
            <script>
                $(document).ready(function () {
                    setTimeout(function () {
                        document.getElementById('flashmsg').style.display = 'none';
                    }, 3000);
                });

            </script>
            <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert') ?> alert-dismissible fade show" id='flashmsg'>
                <div class="m-alert__icon">
                    <i class="flaticon-exclamation-1"></i>
                    <span></span>
                </div>
                <div class="m-alert__text">
                    <?php echo $this->session->flashdata('flash_message') ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__body">
                <!--begin: Search Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                    <form action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/search/" method="post">
                        <div class="row align-items-center">
                            <div class="col-xl-8 ">
                                <div class="form-group m-form__group row align-items-center">
                                    <div class="col-md-8">
                                        <div class="m-input-icon m-input-icon--left">
                                            <input type="text" name="search" id="search" class="form-control m-input" placeholder="<?php
                                            if ($page_action != 'list') {
                                                echo $page_action;
                                            } else {
                                                echo 'Scan Barcode';
                                            }
                                            ?>">
                                            <span class="m-input-icon__icon m-input-icon__icon--left">
                                                <span>
                                                    <i class="la la-search"></i>
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success">
                                        Submit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <?php if ($search): ?>
                    <div class="row align-items-center">
                        <div class="col-xl-6">
                            <div class="m-scrollable" data-scrollable="true" data-max-height="600" data-scrollbar-shown="true">
                                <div class="m-scrollable" data-scrollable="true" data-max-height="600" data-scrollbar-shown="true">
                                    <table class="table">
                                        <tbody>
                                            <?php if ($detail): ?>
                                                <tr class="table-inverse">
                                                    <th scope="row" colspan="2">
                                                        <label style="text-decoration: underline;font-weight: bold;">Detail Peminjaman</label>
                                                        </td>
                                                </tr>
                                                <tr>
                                                    <td >
                                                        Tanggal Peminjaman : &nbsp;&nbsp; <?php echo $detail[0]->tgl_pinjam; ?>
                                                    </td>

                                                    <td >
                                                        Batas Pengembalian : &nbsp;&nbsp;<?php echo $detail[0]->batas; ?>
                                                    </td>

                                                </tr>

                                            <?php endif; ?>
                                            <?php if ($buku): ?>
                                                <tr class="table-inverse">
                                                    <th scope="row" colspan="2">
                                                        <label style="text-decoration: underline;font-weight: bold;">Detail Buku</label>
                                                        </td>
                                                </tr>
                                                <tr>
                                                    <td width="310">
                                                        NO Inventaris Buku : &nbsp;&nbsp; <?php echo $search['nomor']; ?>
                                                    </td>
                                                    
                                                    <td >
                                                        NO Barcode : &nbsp;&nbsp; <label style="font-weight: bold;text-decoration: underline;"><?php echo $search['no_barcode']; ?></label>
                                                    </td>
                                                   
                                                </tr>
                                                <tr>
                                                    <td scope="row">
                                                        ISBN
                                                    </td>
                                                    <td>
                                                        <?php echo $buku[0]->ISBN; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">
                                                        NO Klas
                                                    </td>
                                                    <td>
                                                        <?php echo $buku[0]->no_klas; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td scope="row">
                                                        Judul Buku
                                                    </td>
                                                    <td>
                                                        <?php echo $buku[0]->judul; ?>
                                                    </td>
                                                </tr>

                                            <?php endif; ?>
                                            <tr class="table-inverse">
                                                <th scope="row" colspan="2">
                                                    <label style="text-decoration: underline;font-weight: bold;">Detail Anggota</label> 
                                                    </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">
                                                    NO Anggota
                                                </td>
                                                <td>
                                                    <?php echo $search['noanggota']; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">
                                                    Jenis Anggota
                                                </td>
                                                <td>
                                                    <strong><?php echo $search['jenis_anggota']; ?></strong>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">
                                                    Nama
                                                </td>
                                                <td>
                                                    <?php echo $search['nama']; ?>
                                                </td>
                                            </tr>
                                            <?php 
                                            if($search['jenis_anggota']=='Mahasiswa'){?>
                                            <tr>
                                                <td scope="row">
                                                    Program Studi
                                                </td>
                                                <td>
                                                    <?php echo $search['kelas']; ?>
                                                </td>
                                            </tr>    
                                            <?php
                                            }
                                            ?>
                                            <tr>
                                                <td scope="row">
                                                    Alamat
                                                </td>
                                                <td>
                                                    <?php echo $search['alamat']; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td scope="row">
                                                    Telp
                                                </td>
                                                <td>
                                                    <?php echo $search['telepon']; ?>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6">
                            <form action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/kembali/" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed" method="post">
                                <input type="hidden" name="idk" id="idk" value="<?php if ($detail) echo $detail[0]->tid; ?>">
                                <div class="m-portlet__body">
                                    <div class="form-group m-form__group row">
                                        <label class="col-lg-2 col-form-label">
                                            Denda*
                                        </label>
                                        <div class="col-lg-3">
                                            <input type='text' id="denda" name="denda" value="<?php echo number_format($dendabuku); ?>" class="form-control m-input" readonly />
                                        </div>
                                        <label class="col-lg-3 col-form-label">
                                            Tanggal Kembali*
                                        </label>
                                        <div class="col-lg-3">
                                            <div class='input-group date' id='m_datepicker_kembali'>
                                                <input type='text' id="tanggalkembali" name="tanggalkembali" value="<?php echo $tglkembali; ?>"class="form-control m-input" readonly />
                                                <span class="input-group-addon">
                                                    <i class="la la-calendar-check-o"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                                    <div class="m-form__actions m-form__actions--solid">
                                        <div class="row">
                                            <div class="col-lg-2"></div>
                                            <div class="col-lg-10">
                                                <button type="submit" class="btn btn-success">
                                                    Submit
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <!--end::Form-->

                        </div>
                    </div>

                    <script type="text/javascript">
                        var Dtb = function () {
                            e = function () {
                                $("#m_datepicker_kembali").datepicker({
                                    format: 'yyyy-mm-dd', todayHighlight: !0, orientation: "bottom left", templates: {
                                        leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
                                    }
                                })
                            };
                            return {
                                init: function () {
                                    e()
                                }
                            }
                        }

                        ();
                        jQuery(document).ready(function () {
                            Dtb.init()
                        }

                        );
                    </script>

                <?php endif; ?>
            </div>
        </div>

    </div>
</div>