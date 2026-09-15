<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    <?php echo $page_title; ?>
                </h3>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->
    <div class="m-content">
        <?php if ($this->session->flashdata('alert') != ''): ?>
            <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?php echo $this->session->flashdata('alert') ?> alert-dismissible fade show">
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
                <!--begin: Datatable -->
                <div class="m-portlet">

                    <!--begin::Form-->
                    <form method="post" action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/update" class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed"  enctype="multipart/form-data">
                        <div class="m-portlet__body">
                            <div class="form-group m-form__group row">
                                <label class="col-lg-3 col-form-label" align="left">
                                    Password Lama
                                </label>
                                <div class="col-lg-5">
                                    <input type="password" name="password" required >
                                    <span class="m-form__help">

                                    </span>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-lg-3 col-form-label" align="left">
                                    Password Baru
                                </label>
                                <div class="col-lg-5">
                                    <input type="password" name="passwordbaru" required >
                                    <span class="m-form__help">

                                    </span>
                                </div>
                            </div>
                            <div class="form-group m-form__group row">
                                <label class="col-lg-3 col-form-label" align="left">
                                    Confirm Password Baru
                                </label>
                                <div class="col-lg-5">
                                    <input type="password" name="passwordbaruconfirm" required >
                                    <span class="m-form__help">

                                    </span>
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
                                        <button type="reset" class="btn btn-secondary">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <!--end::Form-->
                </div>
                <!--end: Datatable -->
            </div>
        </div>

    </div>
</div>
<!-- end:: Body -->