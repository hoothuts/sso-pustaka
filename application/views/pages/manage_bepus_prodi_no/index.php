<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--info m-portlet--head-solid-bg m-portlet--bordered">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Format Nomor Bebas Pustaka</h3>
                    </div>
                </div>
            </div>

            <div class="m-portlet__body">

                <div class="m-form m-form--label-align-right m--margin-bottom-30">
                    <div class="row align-items-center">

                        <div class="col-xl-8 order-2 order-xl-1">
                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="m-input-icon m-input-icon--left">
                                        <input type="text"
                                               id="generalSearch"
                                               class="form-control"
                                               placeholder="Cari...">

                                        <span class="m-input-icon__icon m-input-icon__icon--left">
                                            <span>
                                                <i class="la la-search"></i>
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 order-1 order-xl-2 m--align-right">
                            <button class="btn btn-primary" id="btn_tambah">
                                <i class="la la-plus"></i> Tambah
                            </button>
                        </div>

                    </div>
                </div>

                <div class="m_datatable" id="datatable_bepus_prodi_no"></div>

            </div>
        </div>
    </div>
</div>


<!-- ================= MODAL ================= -->
<div class="modal fade" id="modal_prodi" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <form id="form_bepus_prodi_no">

                <div class="modal-header m--bg-brand">
                    <h5 class="modal-title m--font-light" id="modal_title">
                        Form Format Nomor Bebas Pustaka
                    </h5>

                    <button type="button"
                            class="close"
                            data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <div class="modal-body">

                    <input type="hidden"
                           name="id_for_edit"
                           id="id_for_edit">

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">
                            Program Studi
                        </label>

                        <div class="col-lg-9">
                            <select class="form-control m-select2"
                                    id="prodi_select_ajax"
                                    name="kdpst">
                            </select>

                            <span class="m-form__help">
                                Ketik nama program studi
                            </span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">
                            Tahun
                        </label>

                        <div class="col-lg-9">
                            <input type="number"
                                   class="form-control"
                                   name="tahun"
                                   id="tahun"
                                   min="2020"
                                   max="2100"
                                   value="<?= date('Y') ?>">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-lg-3 col-form-label">
                            Prefix Nomor
                        </label>

                        <div class="col-lg-9">
                            <input type="text" class="form-control" name="kode_nomor" id="kode_nomor" maxlength="30" placeholder="Contoh : BP/PKR">

                            <span class="m-form__help">
                                Maksimal 30 karakter
                            </span>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="submit"
                            class="btn btn-primary">
                        Simpan
                    </button>

                    <button type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                        Batal
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

<style>
.select2-container{
    width:100% !important;
    z-index:9999;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

var datatable;

$(document).ready(function(){

    datatable = $("#datatable_bepus_prodi_no").mDatatable({

        data:{
            type:"remote",
            source:{
                read:{
                    url:"<?= base_url('dir/manage_bepus_prodi_no/fetch') ?>",
                    method:"POST"
                }
            },
            pageSize:10,
            serverPaging:true,
            serverFiltering:true,
            serverSorting:true
        },

        search:{
            input:$('#generalSearch')
        },

        columns:[

            {
                field:"number",
                title:"#",
                width:40
            },

            {
                field:"jenjang",
                title:"Jenjang",
                width:80
            },

            {
                field:"nama_prodi",
                title:"Program Studi",
                width:250
            },

            {
                field:"tahun",
                title:"Tahun",
                width:80
            },

            {
                field:"kode_nomor",
                title:"Prefix Nomor",
                width:240
            },

            {
                field:"author",
                title:"Author",
                width:90
            },

            {
                field:"tgl_post",
                title:"Tanggal",
                width:130
            },

            {
                field:"action",
                title:"Aksi",
                width:120,
                sortable:false,

                template:function(row){

                    return `
                        <span style="width:120px">

                            <button
                                class="btn btn-sm btn-info btn-icon edit"
                                data-id="${row.id_enc}"
                                title="Edit">
                                <i class="la la-edit"></i>
                            </button>

                            <button
                                class="btn btn-sm btn-danger btn-icon hapus"
                                data-id="${row.id_enc}"
                                title="Hapus">
                                <i class="la la-trash"></i>
                            </button>

                        </span>
                    `;
                }
            }

        ]
    });


    $('#prodi_select_ajax').select2({

        placeholder:"Cari Program Studi",

        allowClear:true,

        dropdownParent:$('#modal_prodi'),

        ajax:{

            url:"<?= base_url('dir/manage_bepus_prodi_no/search_prodi') ?>",

            dataType:'json',

            delay:250,

            data:function(params){

                return {
                    searchtext:params.term
                };
            },

            processResults:function(data){

                return {
                    results:data.items
                };
            }
        }
    });


    $('#btn_tambah').click(function(){

        $('#form_bepus_prodi_no')[0].reset();

        $('#id_for_edit').val('');

        $('#prodi_select_ajax')
            .val(null)
            .trigger('change');

        $('#tahun').val('<?= date('Y') ?>');

        $('#modal_title').text(
            'Tambah Format Nomor Bebas Pustaka'
        );

        $('#modal_prodi').modal('show');
    });


    $(document).on('click','.edit',function(){

        let id = $(this).data('id');

        $.post(
            "<?= base_url('dir/manage_bepus_prodi_no/get_detail') ?>",
            {
                id:id
            },
            function(res){

                if(res.status=='success')
                {
                    let d = res.data;

                    $('#id_for_edit').val(id);

                    $('#tahun').val(d.tahun);

                    $('#kode_nomor').val(d.kode_nomor);

                    $.ajax({

                        url:"<?= base_url('dir/manage_bepus_prodi_no/search_prodi') ?>",

                        data:{
                            searchtext:d.kdpst
                        },

                        dataType:'json',

                        success:function(prodi){

                            if(prodi.items.length > 0)
                            {
                                let item = prodi.items[0];

                                let option = new Option(
                                    item.text,
                                    item.id,
                                    true,
                                    true
                                );

                                $('#prodi_select_ajax')
                                    .append(option)
                                    .trigger('change');
                            }
                        }
                    });

                    $('#modal_title').text(
                        'Edit Format Nomor Bebas Pustaka'
                    );

                    $('#modal_prodi').modal('show');
                }
                else
                {
                    Swal.fire(
                        'Error',
                        'Data tidak ditemukan',
                        'error'
                    );
                }

            },
            'json'
        );

    });


    $('#form_bepus_prodi_no').submit(function(e){

        e.preventDefault();

        $.ajax({

            url:"<?= base_url('dir/manage_bepus_prodi_no/save') ?>",

            type:"POST",

            data:$(this).serialize(),

            dataType:"json",

            success:function(res){

                if(res.status=='success')
                {
                    $('#modal_prodi').modal('hide');

                    Swal.fire({
                        title:'Berhasil',
                        text:res.message,
                        icon:'success',
                        timer:2000,
                        showConfirmButton:false
                    }).then(function(){

                        datatable.reload();
                    });
                }
                else
                {
                    Swal.fire(
                        'Gagal',
                        res.message,
                        'error'
                    );
                }
            },

            error:function(){

                Swal.fire(
                    'Error',
                    'Gagal terhubung ke server',
                    'error'
                );
            }

        });

    });


    $(document).on('click','.hapus',function(){

        let id = $(this).data('id');

        Swal.fire({

            title:'Hapus data ?',

            text:'Data akan dinonaktifkan',

            icon:'warning',

            showCancelButton:true,

            confirmButtonText:'Ya, hapus',

            cancelButtonText:'Batal'

        }).then((result)=>{

            if(result.isConfirmed)
            {
                $.post(
                    "<?= base_url('dir/manage_bepus_prodi_no/delete') ?>",
                    {
                        id:id
                    },
                    function(res){

                        if(res.status=='success')
                        {
                            Swal.fire(
                                'Berhasil',
                                res.message,
                                'success'
                            );

                            datatable.reload();
                        }
                        else
                        {
                            Swal.fire(
                                'Gagal',
                                res.message,
                                'error'
                            );
                        }

                    },
                    'json'
                );
            }

        });

    });

});
</script>