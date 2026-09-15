<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Preview Tampilan Form Syarat Bebas Pustaka</h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <a href="<?= base_url('dir/manage_syarat_bepus') ?>" class="btn btn-secondary m-btn ">
                        <i class="la la-arrow-left"></i> Kembali ke Manage
                    </a>
                </div>
            </div>
            <div class="m-portlet__body">

                <div class="alert alert-info">
                    <strong>Preview Form Bebas Pustaka</strong><br>
                    Berikut adalah tampilan form yang akan dilihat oleh user.
                </div>

                <div class="card">
                    <div class="card-body">

                        <h4 class="mb-4">Persyaratan Bebas Pustaka</h4>

                        <div class="syarat-form-preview">
                            <?php 
                            $no_induk = 1;
                            $current_parent = null;

                            foreach ($syarat_list as $row): 
                                if ($row->level == 1): 
                                    // Parent (Induk)
                                    $current_parent = $row;
                            ?>
                                <div class="parent-item mb-3">
                                    <div class="d-flex align-items-start">
                                        <?php if ($row->is_isian == 'Ya'): ?>
                                            <label class="m-checkbox m-checkbox--success">
                                                <input type="checkbox"> 
                                                <span></span>
                                            </label>
                                        <?php endif; ?>
                                        <strong class="mr-3"><?= $no_induk++ ?>.</strong>
                                        <div>
                                            <strong><?= $row->persyaratan ?></strong>
                                        </div>
                                    </div>
                                </div>
                            <?php else: 
                                // Child
                            ?>
                                <div class="child-item ml-5 mb-2">
                                    <div class="d-flex align-items-start">
                                        <?php if ($row->is_isian == 'Ya'): ?>
                                            <label class="m-checkbox m-checkbox--success">
                                                <input type="checkbox"> 
                                                <span></span>
                                            </label>
                                        <?php endif; ?>
                                        <div>
                                            <?= $row->persyaratan ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php endforeach; ?>

                            <?php if (empty($syarat_list)): ?>
                                <p class="text-muted">Belum ada data persyaratan yang aktif.</p>
                            <?php endif; ?>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<style>
.syarat-form-preview .parent-item {
    padding: 12px 15px;
    background: #f8f9fa;
    border-left: 4px solid #34bfa3;
    border-radius: 4px;
}

.syarat-form-preview .child-item {
    padding: 8px 15px;
    background: #fff;
    border-left: 3px solid #ccc;
}

.m-checkbox {
    display: inline-flex;
    align-items: center;
    cursor: pointer;
}
</style>