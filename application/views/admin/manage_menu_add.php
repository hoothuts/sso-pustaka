<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    Manage menu
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?= base_url(); ?><?= $page_access; ?>/<?= $page_name; ?>" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon flaticon-chat-1"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">
                        -
                    </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">
                                Manage menu
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->


    <div class="m-content">
        <div class="row">
            <div class="col-md-12">
                <!--begin::Portlet-->
                <div class="m-portlet">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon m--hide">
                                    <i class="la la-gear"></i>
                                </span>
                                <h3 class="m-portlet__head-text">
                                    <?= $page_title ?>
                                </h3>
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form method="post" action="<?= base_url('admin/manage_menu/save') ?>" class="m-form m-form--fit m-form--label-align-right" autocomplete="off">
                        <div class="m-portlet__body">
                            <?php if ($this->session->flashdata('alert') != '') : ?>
                                <div class="form-group m-form__group">
                                    <?php $alert =  $this->session->flashdata('alert') ?>
                                    <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-<?= $alert['type'] ?> alert-dismissible fade show" role="alert">
                                        <div class="m-alert__icon">
                                            <i class="flaticon-exclamation-1"></i>
                                            <span></span>
                                        </div>
                                        <div class="m-alert__text">
                                            <?= $alert['msg'] ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if (isset($_pesan)) : ?>
                                <div class="form-group m-form__group">
                                    <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert alert-warning alert-dismissible fade show" role="alert">
                                        <div class="m-alert__icon">
                                            <i class="flaticon-exclamation-1"></i>
                                            <span></span>
                                        </div>
                                        <div class="m-alert__text">
                                            <?= $_pesan ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="form-group m-form__group">
                                <label>Nama Menu<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="nama_menu" max="200" placeholder="Nama Menu">
                                <span class="m-form__help">Max 200 characters.</span>
                            </div>
                            <div class="form-group m-form__group">
                                <div class="row">
                                    <div class="col-md-6">

                                        <label>Level<span class="text-danger">*</span></label>
                                        <select class="form-control m-input" name="level" onchange="show_parent_field(this)">
                                            <option value="1">Level 1</option>
                                            <option value="2">Level 2</option>
                                            <option value="3">Level 3</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label>Parent Menu</label>
                                        <select class="form-control m-input" name="menuparent_id" disabled>
                                            <option value="">Pilih Menu</option>
                                            <?php foreach ($menus as $key => $value) : ?>
                                                <option value="<?= $value->menu_id ?>"><?= $value->nama_menu ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="m-form__help">Pilih parent menu jika menu akan berada pada level 2</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label>Link<span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="link" placeholder="link">
                                <span class="m-form__help">ex: lib.pkr.ac.id</span>
                            </div>
                            <div class="form-group m-form__group">
                                 <div class="row">
                                     <div class="col-md-3">
                                        <label>Urutan Ke</label>
                                        <input type="number" class="form-control" name="urutan" min="0" max="20" value="1" placeholder="urutan">
                                     </div>
                                     <div class="col-md-6">
                                         <label for="">Open in new tab</label>
                                        <div class="m-checkbox-list">
                                            <label class="m-checkbox">
                                                <input type="checkbox" name="is_new_tab" value="1"> Yes
                                                <span></span>
                                            </label>
                                        </div>
                                     </div>
                                 </div>
                                
                            </div>
                            
                        </div>
                        <div class="m-portlet__foot m-portlet__foot--fit">
                            <div class="m-form__actions">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" onclick="history.back()" class="btn btn-secondary">Cancel</button>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Portlet-->
            </div>
        </div>
    </div>
</div>

<script>
    function show_parent_field(e) {
        $('[name="menuparent_id"]').attr('disabled', true);
        if (e.value != 1) {
            $('[name="menuparent_id"]').attr('disabled', false);
        }
    }
</script>