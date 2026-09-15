<div class="m-grid__item m-grid__item--fluid m-wrapper">

    <!-- BEGIN: Subheader -->
    <div class="m-subheader">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    <?= $page_title; ?>
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?= base_url(); ?>admin/<?= $this->session->userdata('default'); ?>" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">-</li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">Konfigurasi Transaksi</span>
                        </a>
                    </li>
                    <li class="m-nav__separator">-</li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text"><?= $page_title; ?></span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- END: Subheader -->

    <div class="m-content">

        <div class="m-alert m-alert--icon m-alert--icon-solid m-alert--outline alert <?= $this->session->flashdata('alert'); ?> alert-dismissible fade"
             role="alert" id="alertbox" style="display:none">
            <div class="m-alert__icon">
                <i class="flaticon-exclamation-1"></i>
                <span></span>
            </div>
            <div class="m-alert__text">
                <?= $this->session->flashdata('flash_message'); ?>
            </div>
        </div>

        <?php if ($page_action == 'list' && $data) : ?>
            <div class="m-portlet m-portlet--mobile">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <h3 class="m-portlet__head-text"><?= $page_title; ?></h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="m-portlet">
                        <div class="m-portlet__head">
                            <div class="m-portlet__head-caption">
                                <div class="m-portlet__head-title">
                                    <span class="m-portlet__head-icon m--hide">
                                        <i class="la la-gear"></i>
                                    </span>
                                    <h3 class="m-portlet__head-text">Konfigurasi</h3>
                                </div>
                            </div>
                        </div>

                        <form method="post" action="<?= base_url(); ?>dir/<?= $page_dir; ?>/update/"
                              class="m-form m-form--fit m-form--label-align-right m-form--group-seperator-dashed">

                            <!-- Hidden inputs: generate otomatis dari loop -->
                            <?php foreach ($data as $i => $row) : ?>
                                <input type="hidden" name="setting<?= $i + 1; ?>kode" value="<?= $row->kodesetting; ?>">
                            <?php endforeach; ?>

                            <div class="m-portlet__body">

                                <!-- Setting 1–7: input text biasa -->
                                <?php foreach (array_slice($data, 0, 7) as $i => $row) : ?>
                                    <div class="form-group m-form__group row">
                                        <label class="col-lg-5 col-form-label"><?= $row->nmsetting; ?></label>
                                        <div class="col-lg-5">
                                            <input type="text"
                                                   id="setting<?= $i + 1; ?>"
                                                   name="setting<?= $i + 1; ?>"
                                                   class="form-control m-input"
                                                   value="<?= $row->valsetting; ?>">
                                            <span class="m-form__help"></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                                <!-- Setting 8 & 9: select dropdown -->
                                <?php foreach ([7, 8] as $i) : ?>
                                    <div class="form-group m-form__group row">
                                        <label class="col-lg-5 col-form-label"><?= $data[$i]->nmsetting; ?></label>
                                        <div class="col-lg-5">
                                            <?php if ($data_kategori) : ?>
                                                <select name="setting<?= $i + 1; ?>" id="setting<?= $i + 1; ?>" class="form-control m-input">
                                                    <?php foreach ($data_kategori as $row) : ?>
                                                        <option value="<?= $row->idkategori; ?>"
                                                            <?= $row->idkategori == $data[$i]->valsetting ? 'selected' : ''; ?>>
                                                            <?= $row->nmkategori; ?> [<?= $row->idkategori; ?>]
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            <?php endif; ?>
                                            <span class="m-form__help"></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>

                            </div>

                            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                                <div class="m-form__actions m-form__actions--solid">
                                    <div class="row">
                                        <div class="col-lg-2"></div>
                                        <div class="col-lg-10">
                                            <?php if (in_array($page_name, $this->session->userdata('perm_add')) || in_array($page_name, $this->session->userdata('perm_edit'))) : ?>
                                                <button type="submit" class="btn btn-success">Submit</button>
                                            <?php endif; ?>
                                            <button type="reset" class="btn btn-secondary">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>