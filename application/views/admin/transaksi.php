<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator"><?php echo $page_title; ?> </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?php echo base_url(); ?>admin/<?php echo $this->session->userdata('default'); ?>" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">-</li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text"> Transaksi </span>
                        </a>
                    </li>
                    <li class="m-nav__separator">-</li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link"> <span class="m-nav__link-text"> <?php echo $page_title; ?> </span> </a>
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
        <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert') ?> alert-dismissible fade" role="alert" id="alertbox" style="display:none">
            <div class="m-alert__icon">
                <i class="flaticon-exclamation-1"></i>
                <span></span>
            </div>
            <div class="m-alert__text">
                <?php echo $this->session->flashdata('flash_message') ?>
            </div>
        </div>
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__body">
                <!--begin: Search Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30">
                    <form action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/search/" method="post">
                        <div class="row align-items-center">
                            <div class="col-xl-6">
                                <div class="form-group m-form__group row align-items-center">
                                    <div class="col-md-6">
                                        <div class="m-input-icon m-input-icon--left">
                                            <input type="text" name="search" id="search" value="<?php if ($search) echo $search['nomor']; ?>" class="form-control m-input" placeholder="Masukkan NIM/NIP Atau Scan Barcode">
                                            <span class="m-input-icon__icon m-input-icon__icon--left">
                                                <span> <i class="la la-search"></i> </span>
                                            </span>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-success"> Submit </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <?php if ($search): ?>
                    <div class="row align-items-center">
                        <div class="col-xl-4">
                            <div class="m-scrollable" data-scrollable="true" data-max-height="200" data-scrollbar-shown="true">
                                <div class="m-scrollable" data-scrollable="true" data-max-height="200" data-scrollbar-shown="true">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th scope="row">
                                                    NO Anggota
                                                </th>
                                                <td>
                                                    : <?php echo $search['nomor']; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    Jenis Anggota
                                                </th>
                                                <td>
                                                    : <strong><?php echo $search['jenis_anggota']; ?></strong>
                                                </td>
                                            </tr>                                        
                                            <tr>
                                                <th scope="row">
                                                    Nama
                                                </th>
                                                <td>
                                                    : <?php echo $search['nama']; ?>
                                                </td>
                                            </tr>
                                            <?php 
                                            if($search['jenis_anggota']=='Mahasiswa'){
                                            ?>
                                            <tr>
                                                <th scope="row">
                                                    Program Studi
                                                </th>
                                                <td>
                                                    : <?php echo $search['kelas']; ?>
                                                </td>
                                            </tr>
                                            <?php
                                            }
                                            ?>
                                            <tr>
                                                <th scope="row">
                                                    Alamat
                                                </th>
                                                <td>
                                                    : <?php echo $search['alamat']; ?>
                                                </td>
                                            </tr>
                                            <tr>
                                                <th scope="row">
                                                    Telp
                                                </th>
                                                <td>
                                                    : <?php echo $search['telepon']; ?>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php if ($page_action == 'detail'): ?>
                            <?php
                            $Tgl = date('Y-m-d');
                            $estTglKembali = date('Y-m-d', strtotime("+" . $lama_pinjam . " days"));
                            $libur = $this->Md_siperpus_libur->haveLibur($Tgl, $estTglKembali);
                            $countlb = !empty($libur) ? count($libur) : 0;
                            $TglKembali = $countlb > 0 ? date('Y-m-d', strtotime("+" + $countlb . " days")) : $estTglKembali;
                            ?>
                            <div class="col-xl-8">
                                <form action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/submit/" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed" method="post">
                                    <input type="hidden" name="id1" id="id1" value="<?php if ($search) echo $search['nomor']; ?>">
                                    <div class="m-portlet__body">
                                        <div class="form-group m-form__group row">
                                            <label class="col-lg-3 col-form-label">
                                                Tanggal Pinjam <font color="red">*</font>
                                            </label>
                                            <div class="col-lg-3">
                                                <div class='input-group date' id='m_datepicker_pinjam'>
                                                    <input type='text' id="tanggalpinjam" name="tanggalpinjam" value="<?php echo $Tgl; ?>" class="form-control m-input" readonly />
                                                    <span class="input-group-addon">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <label class="col-lg-3 col-form-label">
                                                Tanggal Harus Kembali <font color="red">*</font>
                                            </label>
                                            <div class="col-lg-3">
                                                <div class='input-group date' id='m_datepicker_kembali'>
                                                    <input type='text' id="tanggalkembali" name="tanggalkembali" value="<?php echo $TglKembali; ?>"class="form-control m-input" readonly />
                                                    <span class="input-group-addon">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group row">
                                            <label class="col-lg-3 col-form-label">
                                                Buku yang dipinjam <font color="red">*</font>
                                            </label>
                                            <div class="col-lg-3">
                                                <input type='text' class="form-control m-input" id="inventaris" name="inventaris" placeholder="No. Barcode">
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
                            <script type="text/javascript">
                                var save_method; //for save method string
                                var table;
                                var value = document.getElementById("search").value;
                                
                                var Dtb = function () {
                                    var t = function () {
                                        var t = {
                                            data: {
                                                type: "remote", source: {
                                                    read: {
                                                        url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/fetch/" + value
                                                    }
                                                }
                                                ,
                                                saveState: {
                                                    cookie: !1, webstorage: !1
                                                }
                                                ,
                                                serverPaging: true,
                                                serverFiltering: true,
                                                serverSorting: true
                                            }
                                            , layout: {
                                                theme: "default", class: "", scroll: !1, footer: !1
                                            }
                                            ,
                                            sortable: true,
                                            filterable: false,
                                            pagination: true,
                                            searchDelay: 400,
                                            scroll: !0,
                                            columns: [{
                                                    field: "number", title: "No.", sortable: false, width: 40, selector: !1, textAlign: "center"
                                                }
                                                , {
                                                    field: "buku",
                                                    title: "Buku",
                                                    filterable: !1,
                                                    width: 400,
                                                    template: function (t) {
                                                        return "NO Inv : <a href='javascript:void(0)' onClick='show_detail(\"" + t.id + "\")'>" + t.noinv + " </a> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; NO Barcode : " + t.no_barcode + "<br/> " + t.buku
                                                    }
                                                }
                                                , {
                                                    field: "tgl", title: "Tanggal Pinjam", width: 95
                                                }
                                                , {
                                                    field: "batas", title: "Batas Kembali", width: 95
                                                }
                                                , {
                                                    field: "terlambat", title: "Terlambat", width: 80, sortable: false
                                                }
                                                , {
                                                    field: "denda", title: "Denda", width: 80, sortable: false
                                                }
                                                , {
                                                    field: "pinjam", title: "Pinjam Ke", width: 80, sortable: false
                                                }
                                                , {
                                                    field: "action", width: 80, title: "Aksi", sortable: false, overflow: "visible", template: function (t) {
                                                        return'\t\t\t\t\t\t<div class="dropdown ' + (t.getDatatable().getPageSize() - t.getIndex() <= 5 ? "dropup" : "") + '">\t\t\t\t\t\t\t<a href="#" class="btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown">                                <i class="la la-gear"></i>                            </a>\t\t\t\t\t\t  \t<div class="dropdown-menu dropdown-menu-right">\t\t\t\t\t\t    \t<a class="dropdown-item" href="javascript:void(0)" title="Kembali" onclick="kembali_peminjaman(\'' + t.id + '\',\'' + t.denda + '\')"><i class="la la-save"></i> Kembali</a>\t\t\t\t\t\t    \t<a class="dropdown-item" href="javascript:void(0)" title="Perpanjang" onclick="perpanjang_peminjaman(\'' + t.id + '\')"><i class="la la-save"></i> Perpanjang</a>\t\t\t\t\t\t    \t<a class="dropdown-item" href="javascript:void(0)" title="Hilang" onclick="hilang_peminjaman(\'' + t.id + '\',\'' + t.denda + '\')"><i class="la la-save"></i> Hilang</a>\t\t\t\t\t\t    \t<a class="dropdown-item" href="javascript:void(0)" title="Delete" onclick="batal_transaksi(\'' + t.id + '\')"><i class="la la-trash"></i> Batalkan Transaksi</a>\t\t\t\t\t\t    \t</div>\t\t\t\t\t\t</div>'
                                                    }
                                                }
                                            ],
                                            toolbar: {
                                                layout: ['pagination', 'info'],
                                                placement: ['bottom'], //'top', 'bottom'
                                                items: {
                                                    pagination: {
                                                        type: 'default',
                                                        pages: {
                                                            desktop: {
                                                                layout: 'default',
                                                                pagesNumber: 6
                                                            },
                                                            tablet: {
                                                                layout: 'default',
                                                                pagesNumber: 3
                                                            },
                                                            mobile: {
                                                                layout: 'compact'
                                                            }
                                                        },
                                                        navigation: {
                                                            prev: true,
                                                            next: true,
                                                            first: true,
                                                            last: true
                                                        },
                                                        pageSizeSelect: [10, 20, 30, 50, 100]
                                                    },
                                                    info: true
                                                }
                                            },
                                            translate: {
                                                records: {
                                                    processing: 'Please wait...',
                                                    noRecords: 'No records found'
                                                },
                                                toolbar: {
                                                    pagination: {
                                                        items: {
                                                            default: {
                                                                first: 'First',
                                                                prev: 'Previous',
                                                                next: 'Next',
                                                                last: 'Last',
                                                                more: 'More pages',
                                                                input: 'Page number',
                                                                select: 'Select page size'
                                                            },
                                                            info: 'Displaying {{start}} - {{end}} from {{total}} records'
                                                        }
                                                    }
                                                }
                                            }
                                        };
                                        
                                        e = $(".m_datatable").mDatatable(t);
                                        a = e.getDataSourceQuery();
                                        $("#m_form_search").on("keyup", function (t) {
                                            var a = e.getDataSourceQuery();
                                            a.generalSearch = $(this).val().toLowerCase(), e.setDataSourceQuery(a), e.load();
                                        }).val(a.generalSearch);
                                        
                                        $("#m_datepicker_pinjam").datepicker({
                                            format: 'yyyy-mm-dd', todayHighlight: !0, orientation: "bottom left", templates: {
                                                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
                                            }
                                        });
                                        
                                        $("#m_datepicker_kembali").datepicker({
                                            format: 'yyyy-mm-dd', todayHighlight: !0, orientation: "bottom left", templates: {
                                                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
                                            }
                                        });
                                        
                                        $("#m_datepicker_perpanjang").datepicker({
                                            format: 'yyyy-mm-dd', todayHighlight: !0, orientation: "bottom left", templates: {
                                                leftArrow: '<i class="la la-angle-left"></i>', rightArrow: '<i class="la la-angle-right"></i>'
                                            }
                                        });
                                        
                                        $('#m_datatable_reload').on('click', function () {
                                            //e.destroy()
                                            //e=$(".m_datatable").mDatatable(t)
                                            e.reload();
                                        });
                                    };
                                    e = function () {
                                        $("#modal_form").on("shown.bs.modal", function () {
                                            $("#inventaris").select2({
                                                placeholder: "Pilih Buku",
                                                allowClear: !0
                                            });
                                        });
                                    };
                                    return {
                                        init: function () {
                                            t(), e();
                                        }
                                    }
                                }();
                                
                                jQuery(document).ready(function () {
                                    Dtb.init();
                                });
                                
                                function kembali_peminjaman(id, denda)
                                {
                                    save_method = 'add';
                                    $('#form_kembali')[0].reset(); // reset form on modals
                                    $('#idk').val(id); // clear error class
                                    $('#denda').val(denda); // clear error class
                                    $('.form-group').removeClass('has-error'); // clear error class
                                    $('.help-block').empty(); // clear error string
                                    $('#modal_kembali').modal('show'); // show bootstrap modal
                                    $('.modal-title').text('Transaksi Buku'); // Set Title to Bootstrap modal title
                                }

                                function perpanjang_peminjaman(id)
                                {
                                    save_method = 'add';
                                    $('#form_perpanjang')[0].reset(); // reset form on modals
                                    $('#idp').val(id); // clear error class
                                    $('.form-group').removeClass('has-error'); // clear error class
                                    $('.help-block').empty(); // clear error string
                                    $('#modal_perpanjang').modal('show'); // show bootstrap modal
                                    $('.modal-title').text('Transaksi Buku'); // Set Title to Bootstrap modal title
                                }

                                function hilang_peminjaman(id, denda)
                                {
                                    save_method = 'add';
                                    $('#form_hilang')[0].reset(); // reset form on modals
                                    $('#idh').val(id); // clear error class
                                    $('#hilang').val(denda); // clear error class
                                    $('.form-group').removeClass('has-error'); // clear error class
                                    $('.help-block').empty(); // clear error string
                                    $('#modal_hilang').modal('show'); // show bootstrap modal
                                    $('.modal-title').text('Transaksi Buku'); // Set Title to Bootstrap modal title
                                }
                                function reload_table()
                                {
                                    $("#m_datatable_reload").trigger("click");
                                }
                                function sub(jenis)
                                {
                                    $('#btnSave' + jenis).text('saving...'); //change button text
                                    $('#btnSave' + jenis).attr('disabled', true); //set button disable 
                                    var url;
                                    if (jenis == 'kembali') {
                                        url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/kembali/";
                                    }
                                    if (jenis == 'perpanjang') {
                                        url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/perpanjang/";
                                    }
                                    if (jenis == 'hilang') {
                                        url = "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/hilang/";
                                    }
                                    // ajax adding data to database
                                    $.ajax({
                                        url: url,
                                        type: "POST",
                                        data: $('#form_' + jenis).serialize(),
                                        dataType: "JSON",
                                        success: function (data)
                                        {
                                            if (data.status) //if success close modal and reload ajax table
                                            {
                                                if (data.status = 'TRUE') {
                                                    document.getElementById('alertbox').style.display = 'block';
                                                    $('.alert').addClass('show ' + data.alert);
                                                    $('.alert').children('.m-alert__text').html(data.msg);
                                                    hidealert();
                                                    //if success reload ajax table
                                                    $('#modal_' + jenis).modal('hide');
                                                    reload_table();
                                                }
                                            } else
                                            {
                                                for (var i = 0; i < data.inputerror.length; i++)
                                                {
                                                    $('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                                                    $('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]); //select span help-block class set text error string
                                                }
                                            }

                                            $('#btnSave' + jenis).text('save'); //change button text
                                            $('#btnSave' + jenis).attr('disabled', false); //set button enable 
                                        },
                                        error: function (jqXHR, textStatus, errorThrown)
                                        {
                                            $('#btnSave' + jenis).text('save'); //change button text
                                            $('#btnSave' + jenis).attr('disabled', false); //set button enable 
                                            alert('Error adding / update data' + textStatus + errorThrown);
                                        }
                                    });
                                }

                                function hidealert() {
                                    setTimeout(function () {
                                        document.getElementById('alertbox').style.display = 'none';
                                    }, 3000);
                                }
                                function batal_transaksi(id)
                                {
                                    if (confirm('Are you sure Abort this Transaction?'))
                                    {
                                        // ajax delete data to database
                                        $.ajax({
                                            url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/hapus/" + id,
                                            type: "POST",
                                            dataType: "JSON",
                                            success: function (data)
                                            {
                                                if (data.status = 'TRUE') {
                                                    document.getElementById('alertbox').style.display = 'block';
                                                    $('.alert').addClass('show ' + data.alert);
                                                    $('.alert').children('.m-alert__text').html(data.msg);
                                                    hidealert();
                                                    //if success reload ajax table
                                                    $('#modal_form').modal('hide');
                                                    reload_table();
                                                }
                                            },
                                            error: function (jqXHR, textStatus, errorThrown)
                                            {
                                                document.getElementById('alertbox').style.display = 'block';
                                                if ($('.alert').hasClass('show')) {
                                                    $('.alert').addClass('show data-danger');
                                                } else {
                                                    $('.alert').addClass('data-danger');
                                                }
                                                $('.alert').children('.m-alert__text').html('Gagal Menghapus Transaksi');
                                                hidealert();
                                                //alert('Error deleting data'+errorThrown);
                                            }
                                        });
                                    }
                                }
                                function show_detail(id)
                                {
                                    //Ajax Load data from ajax
                                    $.ajax({
                                        url: "<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/detail/" + id,
                                        type: "GET",
                                        dataType: "JSON",
                                        success: function (data)
                                        {
                                            if (data.status = 'TRUE') {
                                                $('[name="detail_judul"]').html(data.judul);
                                                $('[name="detail_tajuk"]').html(data.tajuk);
                                                $('[name="detail_klas"]').html(data.klas);
                                                $('[name="detail_penulis"]').html(data.penulis);
                                                $('[name="detail_edisi"]').html(data.edisi);
                                                $('[name="detail_cetakan"]').html(data.cetakan);
                                                $('[name="detail_penerbit"]').html(data.penerbit);
                                                $('[name="detail_kota"]').html(data.kota);
                                                $('[name="detail_tahun"]').html(data.tahun);
                                                $('[name="detail_bahasa"]').html(data.bahasa);
                                                $('[name="detail_isbn"]').html(data.isbn);
                                                $('[name="detail_jumlah"]').html(data.jumlah);
                                                $('[name="detail_ukuran"]').html(data.ukuran);
                                                $('[name="detail_stok"]').html(data.stok);
                                                $('[name="detail_pinjam"]').html(data.pinjam);
                                                $('[name="detail_rak"]').html(data.rak);
                                                $('[name="detail_referensi"]').html(data.referensi);
                                                $('[name="detail_tanggal"]').html(data.tanggal);
                                                $('[name="detail_review"]').html(data.review);
                                                $('[name="detail_deskripsi"]').html(data.deskripsi);
                                                $('#modal_detail').modal('show'); // show bootstrap modal when complete loaded
                                            }
                                        },
                                        error: function (jqXHR, textStatus, errorThrown)
                                        {
                                            alert('Error get data from ajax' + textStatus + errorThrown);
                                        }
                                    });
                                }
                            </script>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php if ($page_action == 'detail'): ?>
            <button style="display:none" class="btn btn-default" id="m_datatable_reload">
                <i class="fa fa-refresh"></i> Reload</button>
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__body">
                    <!--begin: Search Form -->
                    <div class="m_datatable" id="ajax_data"></div>
                    <!--begin: Datatable -->
                    <!--end: Datatable -->
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<!-- end:: Body -->
<!--begin::Modal-->

<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog"aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="m-menu__link-icon flaticon-book"></i> Detail Buku
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tbody>
                        <tr><th scope="row">Judul Buku</th><td name="detail_judul">-</td></tr>
                        <tr><th scope="row">Tajuk</th><td name="detail_tajuk">-</td></tr>
                        <tr><th scope="row">No. Klasifikasi</th><td name="detail_klas">-</td></tr>
                        <tr><th scope="row">Penulis</th><td name="detail_penulis">-</td></tr>
                        <tr><th scope="row">Edisi</th><td name="detail_edisi">-</td></tr>
                        <tr><th scope="row">Cetakan</th><td name="detail_cetakan">-</td></tr>
                        <tr><th scope="row">Penerbit</th><td name="detail_penerbit">-</td></tr>
                        <tr><th scope="row">Kota Terbit</th><td name="detail_kota">-</td></tr>
                        <tr><th scope="row">Tahun Terbit</th><td name="detail_tahun">-</td></tr>
                        <tr><th scope="row">Bahasa</th><td name="detail_bahasa">-</td></tr>
                        <tr><th scope="row">ISBN</th><td name="detail_isbn">-</td></tr>
                        <tr><th scope="row">Jumlah Halaman</th><td name="detail_jumlah">-</td></tr>
                        <tr><th scope="row">Ukuran Fisik</th><td name="detail_ukuran">-</td></tr>
                        <tr><th scope="row">Jumlah Stok Buku</th><td name="detail_stok">-</td></tr>
                        <tr><th scope="row">Jumlah Buku Dipinjam</th><td name="detail_pinjam">-</td></tr>
                        <tr><th scope="row">Nomor Rak</th><td name="detail_rak">-</td></tr>
                        <tr><th scope="row">Referensi Matakuliah</th><td name="detail_referensi">-</td></tr>
                        <tr><th scope="row">Tanggal Input</th><td name="detail_tanggal">-</td></tr>
                        <tr><th scope="row">Komentar Review</th><td name="detail_review">-</td></tr>
                        <tr><th scope="row">Deskripsi</th><td name="detail_deskripsi">-</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_perpanjang" tabindex="-1" role="dialog"aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="m-menu__link-icon flaticon-add"></i> Perpanjang
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_perpanjang" action="#" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
                    <input type="hidden" name="idp" id="idp">
                    <div class="form-group m-form__group">
                        <label for="" id="text_sub">
                            Perpanjang
                        </label>
                        <div class='input-group date' id='m_datepicker_perpanjang'>
                            <input type='text' id="tanggalperpanjang" name="tanggalperpanjang" value="<?php echo date('Y-m-d', strtotime("+1 days")); ?>" class="form-control m-input" readonly  placeholder="Masukkan tanggal"/>
                            <span class="input-group-addon">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input id="btnSaveperpanjang" onclick="sub('perpanjang')" type="submit" class="btn btn-primary" value="Simpan">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_kembali" tabindex="-1" role="dialog"aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="m-menu__link-icon flaticon-add"></i> Kembali
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_kembali" action="#" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
                    <input type="hidden" name="idk" id="idk">
                    <div class="form-group m-form__group">
                        <label for="" id="text_sub">
                            Denda
                        </label>
                        <input type="text" id="denda" name="denda" value="<?php echo $denda; ?>" class="form-control m-input">
                        <span class="m-form__help">
                            Masukkan Denda
                        </span>

                    </div>
                    <div class="form-group m-form__group">
                        <label for="" id="text_sub">
                            Tanggal Kembali
                        </label>
                        <div class='input-group date' id='m_datepicker_perpanjang'>
                            <input type='text' id="tanggalperpanjang" name="tanggalperpanjang" value="<?php echo date('Y-m-d'); ?>" class="form-control m-input" readonly  placeholder="Masukkan tanggal"/>
                            <span class="input-group-addon">
                                <i class="la la-calendar-check-o"></i>
                            </span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input id="btnSavekembali" onclick="sub('kembali')" type="submit" class="btn btn-primary" value="Simpan">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="modal_hilang" tabindex="-1" role="dialog"aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="m-menu__link-icon flaticon-add"></i> Hilang
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">
                        &times;
                    </span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form_hilang" action="#" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">
                    <input type="hidden" name="idh" id="idh">
                    <div class="form-group m-form__group">
                        <label for="" id="text_sub">
                            Denda
                        </label>
                        <input type="text" id="hilang" name="hilang" value="<?php echo $denda; ?>" class="form-control m-input">
                        <span class="m-form__help">
                            Masukkan Denda
                        </span>
                    </div>
                    <div class="modal-footer">
                        <input id="btnSavehilang" onclick="sub('hilang')" type="submit" class="btn btn-primary" value="Simpan">
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!--end::Modal-->