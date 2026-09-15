<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">Manage Syarat Bebas Pustaka</h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">

                <div class="m-form m-form--label-align-right m--margin-bottom-30">
                    <div class="row align-items-center">
                        <div class="col-xl-8">
                            <a href="<?= base_url('dir/manage_syarat_bepus/preview_form') ?>" 
                                class="btn btn-success btn-sm m-btn m-btn--custom" target="_blank">
                                 <span><i class="la la-eye"></i> <span>Lihat Tampilan Form</span></span>
                             </a>
                        </div>
                        <div class="col-xl-4 m--align-right">
                            <a href="<?= base_url('dir/manage_syarat_bepus/form/add') ?>" 
                               class="btn btn-primary btn-sm m-btn m-btn--custom">
                                <span><i class="la la-plus"></i> <span>Tambah Syarat</span></span>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th width="40">#</th>
                                <th>Persyaratan</th>
                                <th width="80">Urutan</th>
                                <th width="80">Is Isian</th>
                                <th width="80">Is Aktif</th>
                                <th width="120">Author</th>
                                <th width="100">Tgl Last Update</th>
                                <th width="190">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($syarat_list)): ?>
                                <tr><td colspan="8" class="text-center">Belum ada data syarat</td></tr>
                            <?php else: 
                                $no = 1;
                                foreach ($syarat_list as $row): 
                                    $indent = ($row->level == 2) ? 'style="padding-left: 40px; font-style:italic;"' : '';
                            ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td <?= $indent ?> class="persyaratan-content">
                                        <?= ($row->level == 2) ? '↳ ' : '' ?>
                                        <?= $row->persyaratan ?>   <!-- DIRUBAH: Tanpa htmlspecialchars -->
                                    </td>
                                    <td class="text-center"><?= $row->urutan ?></td>
                                    <td class="text-center">
                                        <span class="m-badge m-badge--<?= $row->is_isian == 'Ya' ? 'success' : 'warning' ?>">
                                            <?= $row->is_isian ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span class="m-badge m-badge--<?= $row->is_aktif == 'Ya' ? 'success' : 'danger' ?>">
                                            <?= $row->is_aktif ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row->author) ?></td>
                                    <td><?= $row->tgl_last_update ? date('d/m/Y H:i', strtotime($row->tgl_last_update)) : '-' ?></td>
                                    <td>
                                        <a href="<?= base_url('dir/manage_syarat_bepus/form/view/') . encrypt($row->bepussyarat_id) ?>" 
                                           class="btn btn-sm btn-info btn-icon" title="Lihat"><i class="la la-eye"></i></a>
                                        <a href="<?= base_url('dir/manage_syarat_bepus/form/edit/') . encrypt($row->bepussyarat_id) ?>" 
                                           class="btn btn-sm btn-primary btn-icon" title="Edit"><i class="la la-edit"></i></a>
                                        
                                        <?php if ($row->is_aktif == 'Ya'): ?>
                                            <button class="btn btn-sm btn-warning btn-icon nonaktifkan" 
                                                    data-id="<?= encrypt($row->bepussyarat_id) ?>" title="Nonaktifkan">
                                                <i class="la la-ban"></i>
                                            </button>
                                        <?php endif; ?>

                                        <button class="btn btn-sm btn-danger btn-icon hapus" 
                                                data-id="<?= encrypt($row->bepussyarat_id) ?>" title="Hapus">
                                            <i class="la la-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .persyaratan-content p {
        margin-bottom: 8px;
    }
    .persyaratan-content ul, .persyaratan-content ol {
        padding-left: 20px;
        margin-bottom: 8px;
    }
    .persyaratan-content strong, .persyaratan-content b {
        font-weight: bold;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    
    // Nonaktifkan
    $(document).on('click', '.nonaktifkan', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Nonaktifkan syarat?',
            text: "Status akan diubah menjadi Tidak",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, nonaktifkan'
        }).then((result) => {
            if (result.value) {
                $.post("<?= base_url('dir/manage_syarat_bepus/nonaktifkan') ?>", {id: id}, function(res) {
                    if (res.status == 'success') {
                        Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                }, 'json');
            }
        });
    });

    // Hapus
    $(document).on('click', '.hapus', function() {
        var id = $(this).data('id');
        Swal.fire({
            title: 'Hapus syarat?',
            text: "Data akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus'
        }).then((result) => {
            if (result.value) {
                $.post("<?= base_url('dir/manage_syarat_bepus/delete') ?>", {id: id}, function(res) {
                    if (res.status == 'success') {
                        Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                }, 'json');
            }
        });
    });
});
</script>