<style>
    .file-input-container { margin-bottom: 10px; }
    .file-input-container .btn-remove { margin-top: 1px;}
</style>
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    <?php echo $page_title; ?>
                    <small>
                        <i class="glyphicon glyphicon-refresh"></i>
                        <button style="display:none" class="btn btn-default" id="m_datatable_reload"><i class="fa fa-refresh"></i> Reload</button>
                    </small>
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?php echo base_url(); ?>admin/dashboard" class="m-nav__link m-nav__link--icon"><i class="m-nav__link-icon la la-home"></i></a>
                    </li>
                    <li class="m-nav__separator"> - </li>
                    <li class="m-nav__item">
                        <a href="<?php echo base_url(); ?>dir/manage_buku" class="m-nav__link"><span class="m-nav__link-text">Data Buku</span></a>
                    </li>
                    <li class="m-nav__separator"> - </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link"><span class="m-nav__link-text"><?php echo $page_title; ?></span></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->
    <div class="m-content">
        <?php if ($this->session->flashdata('alert')) { ?>
            <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert') ?> alert-dismissible fade" role="alert" id="alertbox" style="display:none">
                <div class="m-alert__icon">
                    <i class="flaticon-exclamation-1"></i>
                    <span></span>
                </div>
                <div class="m-alert__text">
                    <?php echo $this->session->flashdata('flash_message') ?>
                </div>
            </div>
        <?php } ?>
       
        <script type="text/javascript">
            var page_action = 'set_inv';
        </script>
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            <?php echo $page_title; ?>
                            <small>
                                <i class="glyphicon glyphicon-refresh"></i>
                                <button style="display:none" class="btn btn-default" id="m_datatable_reload">
                                    <i class="fa fa-refresh"></i> Reload</button>
                            </small>
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <button type="button" id="button_tambah" class="btn btn-primary m-btn m-btn--custom m-btn--icon m-btn--air m-btn--pill" data-toggle="modal" data-target="#modal_tambah_inv">
                        <span>
                            <i class="flaticon-add"></i>
                            <span>Tambah</span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="m-portlet__body">
                <table class="table table-borderless table-hover">
                    <tbody>
                        <tr>
                            <th scope="row" style="width:15%">No. Klasifikasi</th>
                            <td style="width:3%">:</td>
                            <td><?php echo $data[0]->no_klas; ?></td>
                        </tr>
                        <tr>
                            <th scope="row" style="width:15%">ISBN</th>
                            <td style="width:3%">:</td>
                            <td><?php echo $data[0]->ISBN; ?></td>
                        </tr>
                        <tr>
                            <th scope="row" style="width:15%">Judul Buku</th>
                            <td style="width:3%">:</td>
                            <td><?php echo $data[0]->judul; ?></td>
                        </tr>
                    </tbody>
                </table>
                <div class="m-demo-icon__preview">
                    <i class="la la-info-circle m--font-danger"></i>
                    <font class="m--font-danger"> Pilih/Centang Buku Untuk Cetak</font>
                </div>
                <!--begin: Selected Rows Group Action Form -->
                <div class="m-form m-form--label-align-right m--margin-top-20 m--margin-bottom-30 collapse" id="m_datatable_group_action_form">
                    <div class="row align-items-center">
                        <div class="col-xl-12">
                            <div class="m-form__group m-form__group--inline">
                                <div class="m-form__label m-form__label-no-wrap">
                                    <label class="m--font-bold m--font-danger-">
                                        Selected
                                        <span id="m_datatable_selected_number"></span>
                                        records:
                                    </label>
                                </div>
                                <div class="m-form__control">
                                    <div class="btn-toolbar">
                                        <button id="cetak_barcode" class="btn btn-sm btn-accent" type="button">
                                            Cetak Barcode
                                        </button>
                                        &nbsp;&nbsp;&nbsp;
                                        <button id="cetak_callnumber" class="btn btn-sm btn-accent" type="button">
                                            Cetak Call Number
                                        </button>
                                        <!-- &nbsp;&nbsp;&nbsp;
                                                <button class="btn btn-sm btn-accent" type="button">
                                                        Set Buku Diarsipkan
                                                </button> -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end: Selected Rows Group Action Form -->
                <div class="m--space-10"></div>
                <input type="hidden" id="isbn" value="<?php echo $isbn; ?>">
                <input type="hidden" id="no_klas" value="<?php echo $no_klas; ?>">
                <div class="m_datatable set_inv" id="set_inv"></div>


                <div class="modal fade" id="modal_tambah_inv" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                            <form class="m-form m-form--fit m-form--label-align-right" id="tambah_inv">

                                <div class="modal-header">
                                    <h5 class="modal-title">Tambah Inventaris</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">

                                <div class="m--space-10"></div>
                                <input name="no_klas" type="hidden" value="<?php echo $no_klas; ?>">
                                <input name="isbn" type="hidden" value="<?php echo $isbn; ?>">
                                <input name="status" type="hidden" value="A">

                                <div class="form-group m-form__group row">
                                    <label class="col-lg-3 col-form-label">
                                        No. Barcode:</label>
                                    <div class="col-lg-4">
                                        <input id="no_barcode" name="no_barcode" type="text" class="form-control m-input" placeholder="Masukkan Nomor Barcode">
                                    </div>
                                    <div>
                                        <a href="javascript:void(0)" class="btn btn-info m-btn m-btn--icon m-btn--icon-only m-btn--pill" onclick="get_barcode()">
                                            <i class="fa flaticon-refresh"></i>
                                        </a>
                                    </div>
                                    <div>
                                        <span class="m-form__help" style="font-size:8pt;"> Klik Untuk Mendapatkan Barcode</span>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-lg-3 col-form-label">
                                        No. Inventaris:</label>
                                    <div class="col-lg-4">
                                        <input name="no_inv" type="text" class="form-control m-input" placeholder="Masukkan Nomor Inventaris">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-lg-3 col-form-label">
                                        Tgl. Inventaris:
                                    </label>
                                    <div class="col-lg-4">
                                        <input name="tgl_inv" type="date" class="form-control m-input" placeholder="Masukkan  Tangga Inventaris">
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-lg-3 col-form-label">
                                        Asal Buku:
                                    </label>
                                    <div class="col-lg-4">
                                        <select class="form-control" name="asal">
                                            <?php
                                            $x = 0;
                                            foreach ($data['asal_buku'] as $i) {
                                                $x++;
                                            ?>
                                                <option value="<?php echo $i->id ?>"><?php echo $i->nama ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-lg-3 col-form-label">
                                        NO. Rak :
                                    </label>
                                    <div class="col-lg-8">
                                        <select class="form-control select2" name="lokasirak_id" style="width:100%">
                                            <option value="">-- Pilih Rak --</option>
                                            <?php foreach ($data['lokasi'] as $group => $raks): ?>
                                                <optgroup label="<?php echo $group; ?>">
                                                    <?php foreach ($raks as $rak): ?>
                                                        <option value="<?php echo $rak['value']; ?>">
                                                            <?php echo $rak['text']; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </optgroup>
                                            <?php endforeach; ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group row">
                                    <label class="col-lg-3 col-form-label">
                                        Keterangan:
                                    </label>
                                    <div class="col-lg-8">
                                        <!-- <input name="ket" type="text" class="form-control m-input" placeholder="Keterangan"> -->
                                        <textarea class="form-control m-input" name="ket" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 140px;"></textarea>
                                        <!-- <textarea name="ket" type="text" rows="8" cols="80"></textarea> -->
                                    </div>
                                </div>


                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>


                <div class="modal fade" id="modal_edit_inv" tabindex="-1">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form class="m-form m-form--fit m-form--label-align-right" id="edit_inv">
                                <div class="modal-header">
                                    <h5 class="modal-title">Edit Inventaris</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="m--space-10"></div>
                                    <input name="no_klas2" type="hidden" value="<?php echo $no_klas; ?>">
                                    <input name="isbn2" type="hidden" value="<?php echo $isbn; ?>">
                                    <input name="no_inv_exist" type="hidden" value="">
                                    <input name="status2" type="hidden" value="A">
                                    
                                    <div class="form-group m-form__group row">
                                        <label class="col-lg-3 col-form-label">
                                            No. Inventaris:</label>
                                        <div class="col-lg-4">
                                            <input name="no_inv2" type="text" class="form-control m-input" placeholder="Masukkan Nomor Inventaris">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-lg-3 col-form-label">
                                            No. Barcode:</label>
                                        <div class="col-lg-4">
                                            <input id="no_barcode2" name="no_barcode2" type="text" class="form-control m-input" placeholder="Masukkan Nomor Barcode">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-lg-3 col-form-label">
                                            Tgl. Inventaris:
                                        </label>
                                        <div class="col-lg-4">
                                            <input name="tgl_inv2" type="date" class="form-control m-input" placeholder="Masukkan  Tangga Inventaris">
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-lg-3 col-form-label">
                                            Asal Buku:
                                        </label>
                                        <div class="col-lg-4">
                                            <select class="form-control" name="asal2">
                                                <?php
                                                $x = 0;
                                                foreach ($data['asal_buku'] as $i) {
                                                    $x++; ?>
                                                    <option value="<?php echo $i->id ?>"><?php echo $i->nama ?></option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-lg-3 col-form-label">
                                            NO. Rak :
                                        </label>
                                        <div class="col-lg-8">
                                            <select class="form-control select2" name="lokasirak_id2" style="width:100%">
                                                <option value="">-- Pilih Rak --</option>
                                                <?php foreach ($data['lokasi'] as $group => $raks): ?>
                                                    <optgroup label="<?php echo $group; ?>">
                                                        <?php foreach ($raks as $rak): ?>
                                                            <option value="<?php echo $rak['value']; ?>">
                                                                <?php echo $rak['text']; ?>
                                                            </option>
                                                        <?php endforeach; ?>
                                                    </optgroup>
                                                <?php endforeach; ?>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group row">
                                        <label class="col-lg-3 col-form-label">
                                            Keterangan:
                                        </label>
                                        <div class="col-lg-8">
                                            <textarea class="form-control m-input" name="ket2" rows="3" style="margin-top: 0px; margin-bottom: 0px; height: 140px;"></textarea>
                                        </div>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-success">Update</button>
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- end:: Body -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script type="text/javascript">
    var save_method; //for save method string
    var table;
    var barcode = "<?php echo base_url(); ?>assets";
    var barcode2 = "<?php echo base_url(); ?>dir/";
    var base_site = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>";
    var submit = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/submit/";
    var update = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/update/";
    var tambah_inv = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/tambah_inv/";
    var edit_site_inv = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/edit_inv/";
    var site = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/list/";
    var simpan_ubah_rak = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/simpan_ubah_rak/";
    
    $('.select2').select2({
        placeholder: "-- Pilih Rak --",
        allowClear: true,
        width: '100%'
    });
    
    $('#modal_tambah_inv .select2').select2({
        dropdownParent: $('#modal_tambah_inv'),
        width: '100%'
    });

    $('#modal_edit_inv .select2').select2({
        dropdownParent: $('#modal_edit_inv'),
        width: '100%'
    });
   
    var idisbn = document.getElementById('isbn').value;
    var no_klas = document.getElementById('no_klas').value;
    var get_inv = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/get_inv/" + idisbn + "/" + no_klas;
    
    function edit_inv_page(isbn, no_klas) {
        url = base_site + "/edit/" + isbn + "/" + no_klas;
        window.location.href = url;
    }

    function edit_inv(barcode) {
        $('#modal_edit_inv').modal('show');

        //$('#modal_edit_inv')[0].reset();
        $.ajax({
            url: "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/get_data_inv/" + barcode,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('[name="no_inv_exist"]').val(data[0].no_inv);
                $('[name="no_barcode2"]').val(data[0].no_barcode);
                $('[name="no_inv2"]').val(data[0].no_inv);
                $('[name="tgl_inv2"]').val(data[0].tgl_inv);
                $('[name="asal2"]').val(data[0].asal);
                $('[name="ket2"]').val(data[0].ket);
                $('#modal_edit_inv [name="lokasirak_id2"]').val(data[0].lokasirak_id).trigger('change');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function pengalInputIni(id1, id2) {
        document.getElementById(id2).value = id1;
    }

    function view(id, no_klas) {
        var id1 = id.split('/').join('_');
        var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/get/" + id1 + "/" + no_klas;
        $.ajax({
            url: url,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#ISBN').text(data[0].ISBN);
                $('#judul').text(data[0].judul);
                $('#jml_buku').text(data[0].jml_buku);
                $('#penulis').text(data[0].penulis);
                $('#tajuksubyek').text(data[0].tajuksubyek);
                $('#no_klas').text(data[0].no_klas);
                $('#edisi').text(data[0].edisi);
                $('#cetakan').text(data[0].cetakan);
                $('#penerbit').text(data[0].nama_penerbit);
                $('#kota').text(data[0].kota);
                $('#thn_terbit').text(data[0].thn_terbit);
                if (data[0].bahasa == 'I') {
                    data[0].bahasa = "Bahasa Indonesia"
                } else if (data[0].bahasa == 'A') {
                    data[0].bahasa = "Bahasa Inggris"
                } else if (data[0].bahasa = "S") {
                    data[0].bahasa = "Bahasa Sunda"
                } else {
                    data[0].bahasa = "Bahasa Lainnya"
                }
                $('#bahasa').text(data[0].bahasa);
                $('#jml_hal').text(data[0].jml_hal);
                $('#ukuran_fisik').text(data[0].ukuran_fisik);
                $('#dipinjam').text(data[0].dipinjam);
                $('#no_rak').text(data[0].no_rak);
                $('#deskripsi').text(data[0].deskripsi);
                $('#tanggal').text(data[0].tanggal);
                if (data[0].review == 0) {
                    data[0].review = "-"
                }
                $('#review').text(data[0].review);
                $('#matakuliah').text(data[0].matakuliah);
                $('#m_Modal').modal('show'); // show bootstrap modal when complete loaded
                $('.modal-title').text('Lihat Data Buku'); // Set title to Bootstrap modal title
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });
    }

    function delete_inv(barcode, no_klas, isbn) {
        // tampilkan swal konfirmasi
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data inventaris akan dihapus!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // ajax delete data to database
                isbn = isbn.split('/').join('_');
                var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/hapus_inv/" + isbn + "/" + no_klas + "/" + barcode;

                $.ajax({
                    url: url,
                    type: "POST",
                    dataType: "JSON",
                    success: function(data) {
                        if (data.status == 'TRUE') {
                            Swal.fire({
                                title: 'Berhasil',
                                text: 'Data Inventaris Berhasil Dihapus',
                                icon: 'success',
                                showConfirmButton: false,
                                timer: 1500
                            });
                            reload_table();
                        } else {
                            Swal.fire('Gagal', data.msg || 'Terjadi kesalahan', 'error');
                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        Swal.fire({
                            title: 'Peringatan',
                            text: 'Data Inventaris Tidak Berhasil Dihapus',
                            icon: 'warning',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                });
            }
        });
    }


    function reload_table() {
        $("#m_datatable_reload").trigger("click");
    }

    function get_barcode() {
        var url = "<?php echo base_url(); ?>dir/<?php echo $page_name; ?>/get_barcode/";
        $.ajax({
            url: url,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $("input[name='no_barcode']").val(data.barcode);
                // document.getElementsByName('no_barcode').value=data.barcode;
            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
        })
    }
    
    
</script>
<script>
    $(document).ready(function() {
        var z=function() {
            var i= {
                data: {
                    type:"remote",
                    source: {
                        read: {
                            url: get_inv
                        }
                    },
                    saveState: {
                        cookie: !1, webstorage: !1
                    },
                    serverPaging:true, serverFiltering:true,serverSorting:true
                }, 
                layout: {
                    theme: "default", class: "", scroll: !0, height: 550, footer: !1
                }, 
                sortable: true,
                filterable: false,
                pagination: true,
                searchDelay: 5500,
                columns:[{
                    field:"no_barcode2", title:"", sortable:!1, width:10,
                    selector: {
                      class: "m-checkbox--solid m-checkbox--brand"
                    }
                },
                {
                    field:"no_inv", title:"No. Inventori", filterable:!1, width:100
                }, 
                {
                    field: "no_barcode", title: "No. Barcode", width:70, textAlign: "center"
                }, 
                {
                    field: "isbn", title: "ISBN", width:140
                }, 
                {
                    field: "tgl_inv", title: "Tgl Inventori", width:130
                }, 
                {
                    field: "asal", title: "Asal", width:90
                }, 
                {
                    field: "nama_kampus", title: "Kampus", width:160
                }, 
                {
                    field: "nama_gedung", title: "Gedung", width:150
                }, 
                {
                    field: "nama_rak", title: "Rak", width:140
                }, 
                {
                    field: "ket", title: "Keterangan", width:200, textAlign: "left"
                }, 
                {
                    field:"action", title:"Actions", sortable: false, width:100, overflow:"visible", template:function(i) {
                        return '\t\t\t\t\t\t<a href="javascript:void(0)" onclick="edit_inv(\''+i.no_barcode+'\')" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Edit">\t\t\t\t\t\t\t<i class="la la-edit"></i>\t\t\t\t\t\t</a>\t\t\t\t\t\t<a href="javascript:void(0)" onclick="delete_inv(\''+i.no_barcode+'\',\''+i.no_klas+'\',\''+i.isbn+'\')"  class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Hapus">\t\t\t\t\t\t\t<i class="la la-trash"></i>\t\t\t\t\t\t</a>\t\t\t\t\t'
                    }
                }
                ],
                toolbar: {
                  layout: ['pagination', 'info'],
                  placement: ['bottom'],  //'top', 'bottom'
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
            z = $("#set_inv").mDatatable(i);
            b = z.getDataSourceQuery();
            
            $('#m_datatable_reload').on('click', function () {
              z.reload();
            });
            
            $(".m_datatable").on("m-datatable--on-check", function(b, e) {
               var l=z.setSelectedRecords().getSelectedRecords().length;
               $("#m_datatable_selected_number").html(l), l>0&&$("#m_datatable_group_action_form").collapse("show")
             }).on("m-datatable--on-uncheck m-datatable--on-layout-updated", function(b, e) {
               var l=z.setSelectedRecords().getSelectedRecords().length;
               $("#m_datatable_selected_number").html(l), 0===l&&$("#m_datatable_group_action_form").collapse("hide")
            });
            
            $('#cetak_barcode').on('click', function () {
                var final = [];
                var i = 0;

                $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function () {
                    var values = $(this).val();
                    final[i++] = values;
                });

                var url = barcode2 + "manage_inventaris/cetak_barcode/";
                console.log(final);

                var newWin = window.open();

                $.ajax({
                    url: url + "/cetak_barcode/",
                    type: "POST",
                    data: { final: final },
                    dataType: "JSON",
                    success: function (data) {

                        var doc = '';
                        var open = true;
                        var kolom = 4;
                        var cur = 0;

                        doc += "<script src=\"<?php echo base_url(); ?>assets/JsBarcode.code39.min.js\"><\/script>";

                        doc += `
                            <style>
                                #barcodeView {
                                    width:170px !important;
                                    height:85px !important;
                                }

                                .inv-text {
                                    font-family: 'Courier New';
                                    font-size: 11px;
                                    margin-bottom: 0px;
                                }

                                .barcode-row {
                                    width:160px;
                                    margin:2px auto 0;
                                    display:flex;
                                    justify-content:space-between;
                                    font-family:'Courier New';
                                    font-size:11px;
                                }

                                .barcode-left {
                                    text-align:left;
                                }

                                .barcode-right {
                                    text-align:right;
                                }

                                .color-box {
                                    width:160px;
                                    height:10px;
                                    margin:2px auto 0;
                                }

                                @media print {
                                    * {
                                        -webkit-print-color-adjust: exact;
                                        print-color-adjust: exact;
                                    }
                                }
                            </style>
                        `;

                        doc += "<table border='1'>";

                        for (var i = 0; i < data.length; i++) {

                            if (open) {
                                doc += "<tr>";
                                open = false;
                            }

                            doc += "<td align='center'>";

                            // No Inv
                            doc += "<div class='inv-text'>No.Inv." + data[i].no_inv + "</div>";

                            // BARCODE TANPA TEXT
                            doc += "<img id=\"barcodeView\" class=\"barcode\" " +
                                    "jsbarcode-format=\"CODE39\" " +
                                    "jsbarcode-height=\"70\" " +
                                    "jsbarcode-fontSize=\"20\" " +
                                    "jsbarcode-textMargin=\"0\" " +
                                    "jsbarcode-displayValue=\"false\" " +
                                    "jsbarcode-value=\"" + data[i].no_barcode + "\" " +
                                    "jsbarcode-background=\"#FFFFFF\" " +
                                    "jsbarcode-lineColor=\"#000000\" />";

                            // TEXT BARCODE + NAMA RAK
                            doc += "<div class='barcode-row'>";
                            doc += "<div class='barcode-left'>" + data[i].no_barcode + "</div>";
                            doc += "<div class='barcode-right'>" +
                                    (data[i].nama_rak && data[i].nama_rak.trim() !== '' 
                                        ? data[i].nama_rak 
                                        : '-') +
                                   "</div>";
                            doc += "</div>";

                            // COLOR BOX (SVG)
                            doc += "<div class='color-box'>";
                            doc += "<svg width='160' height='10'>";
                            doc += "<rect width='160' height='10' fill='" + data[i].kode_warna + "'/>";
                            doc += "</svg>";
                            doc += "</div>";

                            doc += "</td>";

                            cur++;

                            if (cur == kolom) {
                                doc += "</tr>";
                                open = true;
                                cur = 0;
                            }
                        }

                        if (!open) {
                            doc += "</tr>";
                        }

                        doc += "</table>";
                        doc += "<script>JsBarcode('.barcode').init();<\/script>";

                        newWin.document.write(doc);
                        newWin.document.close();
                        newWin.focus();

                        setTimeout(function () {
                            newWin.print();
                        }, 300);
                    }
                });
            });
            
            $('#cetak_callnumber').on('click',function() {
              var final=[];
              var i= 0;
              $('.m-datatable__body .m-checkbox--single input:checkbox:checked').each(function(){
                  var values = $(this).val();
                  final[i++] = values;
              });
              // console.log(final);
              var url = barcode2+"manage_inventaris/";
              var newWin = window.open();
              $.ajax({
                 url : url+"/cetak_callnumber/",
                 type: "POST",
                 data: {final:final},
                 dataType: "JSON",
                 success: function(data)
                 {
                   var win = window.open('', '_blank');
                   win.document.write(`
                       <html><head><title>Cetak Callnumber</title>
                       </head><body onload="window.print(); setTimeout(window.close, 1000);">
                   `);

                   data.forEach(function (item) {
                       var n_klas = item.no_klas.split(" ");
                       n_klas[2] = (n_klas[2] !== undefined) ? n_klas[2] : '';
                       win.document.write(`
                           <div style="
                               display: inline-block;
                               width: 45mm; 
                               height: 31mm; 
                               margin: 2px; 
                               padding-top: 5px; 
                               padding-bottom: 5px; 
                               border: 1px solid #000; 
                               text-align: center; 
                               font-family: verdana; 
                               box-sizing: border-box; 
                               float: left;
                               -webkit-print-color-adjust: exact; 
                               print-color-adjust: exact;
                           ">
                               <div style="border-bottom: dashed 1px #000; height: 12mm; margin-bottom: 4px;">
                                   <div style="font-size: 9px; font-weight: bold; padding: 1px;">P E R P U S T A K A A N</div>
                                   <div style="font-size: 9px; font-weight: bold; padding: 1px;">POLITEKNIK KESEHATAN RIAU</div>
                                   <div style="font-size: 8px; font-weight: bold; padding: 1px;">PEKANBARU</div>
                               </div>

                               <div style="padding-top: 5px;background-color:${item.kode_warna || ''};">
                                   <span style="font-size: 11px; font-weight: bold; display: block; line-height: 1.2;">
                                       ${n_klas[0]}<br>
                                       ${n_klas[1]}<br>
                                       ${n_klas[2]}
                                   </span>
                                   <span style="font-size: 9px; font-weight: bold;">
                                       c.${item.no_inv || ''}
                                   </span>
                               </div>
                           </div>

                       `);
                   });

                   win.document.write('</body></html>');
                   win.print();
                   //win.document.close();
                 },
                 error: function (jqXHR, textStatus, errorThrown)
                 {
                 }
              });
            });
        };
        
        z();
           
           
        /* =========================
        VALIDASI + SUBMIT TAMBAH
        ========================= */
         $("#tambah_inv").validate({
             rules: {
                 no_barcode: { required: true },
                 no_inv: { required: true },
                 tgl_inv: { required: true },
                 asal: { required: true }
             },
             submitHandler: function(form) {

                 console.log("SUBMIT TAMBAH TERPANGGIL");

                 $.ajax({
                     url: tambah_inv,
                     type: "POST",
                     data: $(form).serialize(),
                     dataType: "JSON",
                     success: function(data) {

                         console.log("RESPONSE TAMBAH:", data);

                         if (data.status === true) {
                             swal({
                                 title: 'Berhasil',
                                 text: data.msg || 'Inventaris Berhasil Ditambah',
                                 type: 'success',
                                 showConfirmButton: false,
                                 timer: 1500
                             });

                             setTimeout(function() {
                                $('#modal_tambah_inv').modal('hide'); // tutup modal
                                $('#tambah_inv')[0].reset(); // reset form
                                reload_table(); // reload datatable saja
                            }, 500);

                         } else {
                             swal({
                                 title: 'Gagal',
                                 text: data.msg || 'Inventaris Gagal Ditambah',
                                 type: 'error'
                             });
                         }
                     },
                     error: function(jqXHR, textStatus, errorThrown) {

                         console.error("ERROR TAMBAH:", textStatus, errorThrown);

                         swal({
                             title: 'Error Server',
                             text: 'Terjadi kesalahan saat menghubungi server',
                             type: 'error'
                         });
                     }
                 });

                 return false; // penting supaya tidak submit normal
             }
         });


        /* =========================
            VALIDASI + SUBMIT EDIT
        ========================= */
         $("#edit_inv").validate({
             rules: {
                 no_barcode2: { required: true },
                 no_inv2: { required: true },
                 tgl_inv2: { required: true },
                 asal2: { required: true }
             },
             submitHandler: function(form) {

                 console.log("SUBMIT EDIT TERPANGGIL");

                 var barcode = $('#no_barcode2').val();
                 var url = edit_site_inv + barcode;

                 $.ajax({
                     url: url,
                     type: "POST",
                     data: $(form).serialize(),
                     dataType: "JSON",
                     success: function(data) {

                         console.log("RESPONSE EDIT:", data);

                         if (data.status === true) {
                             swal({
                                 title: 'Berhasil',
                                 text: data.msg || 'Inventaris Berhasil Diubah',
                                 type: 'success',
                                 showConfirmButton: false,
                                 timer: 1500
                             });

                            setTimeout(function() {
                                $('#modal_edit_inv').modal('hide'); // tutup modal
                                reload_table(); // reload datatable saja
                            }, 500);

                         } else {
                             swal({
                                 title: 'Gagal',
                                 text: data.msg || 'Inventaris Gagal Diubah',
                                 type: 'error'
                             });
                         }
                     },
                     error: function(jqXHR, textStatus, errorThrown) {

                         console.error("ERROR EDIT:", textStatus, errorThrown);

                         swal({
                             title: 'Error Server',
                             text: 'Terjadi kesalahan saat menghubungi server',
                             type: 'error'
                         });
                     }
                 });

                 return false;
             }
         });

         });

</script>

<!--<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/validasi.js"></script>-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/demo/default/custom/components/forms/widgets/select2.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.js"></script>
<link href="<?php echo base_url(); ?>assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
<!--<script type="text/javascript" src="<?php echo base_url(); ?>assets/admin/data-table-buku.js"></script>-->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/JsBarcode.code39.min.js"></script>
