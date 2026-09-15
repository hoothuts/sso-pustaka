<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <!-- BEGIN: Subheader -->

    <script src="//www.google.com/jsapi" type="text/javascript"></script>
    <div class="m-subheader ">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    <?php echo $page_title; ?>
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?php echo base_url(); ?>admin/<?php echo $this->session->userdata('default'); ?>" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator">
                        -
                    </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">
                                Statistik
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
        <?php if ($this->session->flashdata('alert') != '') : ?>
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
                <form action="<?php echo base_url('dir/statistik/anggota'); ?>" method="post" class="m-form m-form--label-align-right m--margin-bottom-30">
                    <div class="row align-items-center">
                        <label class="col-md-2 m-label">Tanggal Terdaftar :</label>
                        <div class="col-md-2">
                            <div class='input-group date' id='m_datepicker_1'>
                                <input type='text' name="tanggalawal" id="tanggalawal" class="form-control" value="<?php echo $tanggalawal; ?>" readonly placeholder="Awal"/>
                                <span class="input-group-addon"><i class="la la-calendar"></i></span>
                            </div>
                        </div>
                        <label class="col-md-1 m-label text-center">S/D</label>
                        <div class="col-md-2">
                            <div class='input-group date' id='m_datepicker_2'>
                                <input type='text' name="tanggalakhir" id="tanggalakhir" class="form-control" value="<?php echo $tanggalakhir; ?>" readonly placeholder="Akhir"/>
                                <span class="input-group-addon"><i class="la la-calendar"></i></span>
                            </div>
                        </div>
                                
                        <div class="col-lg-3">
                            <div class="form-group m-form__group row align-items-center">
                                <div class="col-md-12 p-prodi">
                                    <div class="m-form__controlv">
                                        <select class="form-control" name="pilihprodi" id="pilihprodi">
                                            <option value="">Pilih Prodi</option>
                                            <?php foreach ($list_prodi as $key): ?>
                                                <option value="<?php echo $key->nmmspst ?>" <?php if ($pilihprodi == $key->nmmspst) echo 'selected'; ?>><?php echo $key->nmmspst ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
                <div class="form-group m-form__group">
                    <div class="row m--margin-bottom-10">
                        <div class="col-lg-12">
                            <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post" style="display:inline;">
                                <input type="hidden" id="imagedata" name="imagedata">
                                <button type="submit" class="btn m-btn--square btn-primary">
                                    <i class="la la-print"></i> Printable version
                                </button>
                            </form>

                            <a id="btn-download-chart" class="btn m-btn--square btn-success" download="statistik_anggota.png">
                                <i class="la la-download"></i> Download Chart
                            </a>
                        </div>
                    </div>

                    <div id="chart_anggota" style="height:700px; width: 100%;"></div>
                </div>

                <script>
                    var GoogleChartsDemo = function () {
                        var initChart = function () {
                            google.load("visualization", "1", {
                                packages: ["corechart", "bar"]
                            });
                            google.setOnLoadCallback(function () {
                                GoogleChartsDemo.runDemos();
                            });
                        };

                        var drawColumnChart = function () {
                            var data = new google.visualization.DataTable();
                            data.addColumn("string", "Kategori");

                            <?php foreach ($xAxis as $x) : ?>
                                data.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>");
                            <?php endforeach; ?>

                            data.addRows([
                                ['Statistik Anggota Berdasarkan Prodi',
                                    <?php
                                    $count = 0;
                                    foreach ($yAxis as $y) {
                                        if ($count > 0)
                                            echo ',';
                                        echo $y;
                                        $count++;
                                    }
                                    ?>
                                ]
                            ]);

                            var options = {
                                title: "Statistik Anggota Berdasarkan Prodi",
                                focusTarget: "category",
                                hAxis: {
                                    title: "Total: <?php echo $total; ?> Anggota",
                                    textPosition: 'none'
                                },
                                vAxis: {
                                    title: "Jumlah Anggota"
                                },
                                chartArea: {width: '65%', height: '70%'}
                            };

                            var chart = new google.visualization.ColumnChart(document.getElementById("chart_anggota"));

                            // Event saat chart selesai dirender
                            google.visualization.events.addListener(chart, 'ready', function () {
                                var imgUri = chart.getImageURI();

                                // 1. Masukkan ke hidden input untuk form cetak
                                document.getElementById("imagedata").value = imgUri;

                                // 2. Update link download gambar
                                var downloadBtn = document.getElementById("btn-download-chart");
                                downloadBtn.href = imgUri;
                            });

                            chart.draw(data, options);
                        };

                        return {
                            init: function () {
                                initChart();
                            },
                            runDemos: function () {
                                drawColumnChart();
                            }
                        };
                    }();

                    // Jalankan inisialisasi
                    jQuery(document).ready(function () {
                        $("#m_datepicker_1, #m_datepicker_2").datepicker({
                            format: 'yyyy-mm-dd', todayHighlight: !0, orientation: "bottom left", autoclose: !0
                        });
                        GoogleChartsDemo.init();
                    });
                </script>

            </div>
        </div>
    </div>
</div>