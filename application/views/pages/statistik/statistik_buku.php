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
                <form action="<?php echo base_url('dir/statistik/buku'); ?>" method="post" class="m-form m-form--label-align-right m--margin-bottom-30">
                    <div class="row align-items-center">
                        <label class="col-md-2 m-label">Tanggal Inventarisasi :</label>
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
                        <div class="col-md-2">
                            <div class="form-group m-form__group">
                                <label for="Lihat Statistik">
                                    Status Buku
                                </label>
                                <select class="form-control m-input m-input--solid" id="status" name="status">
                                    <option value='' <?php if ($cur == '') echo 'selected'; ?>>Aktif</option>
                                    <option value='H' <?php if ($cur == 'H') echo 'selected'; ?>>Hilang</option>
                                    <option value='R' <?php if ($cur == 'R') echo 'selected'; ?>>Rusak</option>
                                    <option value='A' <?php if ($cur == 'A') echo 'selected'; ?>>Diarsipkan</option>
                                    <option value='L' <?php if ($cur == 'L') echo 'selected'; ?>>Dilelang</option>
                                </select>
                            </div>
                        </div>

                        
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>

              
                <div class="form-group m-form__group">
                    <div class="d-flex justify-content-start mb-3">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post" class="mr-2">
                            <input type="hidden" id="imagedata" name="imagedata">
                            <button type="submit" class="btn btn-primary"><i class="la la-print"></i> Printable version</button>
                        </form>
                        <a id="download_klas" class="btn btn-success text-white" download="Chart_Klasifikasi.png">
                            <i class="la la-download"></i> Download Image
                        </a>
                    </div>
                    <div id="chart_klas" style="height:700px;"></div>
                </div>

                <div class="form-group m-form__group">
                    <div class="d-flex justify-content-start mb-3">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post" class="mr-2">
                            <input type="hidden" id="imagedata2" name="imagedata2">
                            <button type="submit" class="btn btn-primary"><i class="la la-print"></i> Printable version</button>
                        </form>
                        <a id="download_kategori" class="btn btn-success text-white" download="Chart_Kategori.png">
                            <i class="la la-download"></i> Download Image
                        </a>
                    </div>
                    <div id="chart_kategori" style="height:700px;"></div>
                </div>

                <div class="form-group m-form__group">
                    <div class="d-flex justify-content-start mb-3">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post" class="mr-2">
                            <input type="hidden" id="imagedata3" name="imagedata3">
                            <button type="submit" class="btn btn-primary"><i class="la la-print"></i> Printable version</button>
                        </form>
                        <a id="download_asal" class="btn btn-success text-white" download="Chart_Asal.png">
                            <i class="la la-download"></i> Download Image
                        </a>
                    </div>
                    <div id="chart_asal" style="height:700px;"></div>
                </div>

                <div class="form-group m-form__group">
                    <div class="d-flex justify-content-start mb-3">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post" class="mr-2">
                            <input type="hidden" id="imagedata4" name="imagedata4">
                            <button type="submit" class="btn btn-primary"><i class="la la-print"></i> Printable version</button>
                        </form>
                        <a id="download_bahasa" class="btn btn-success text-white" download="Chart_Bahasa.png">
                            <i class="la la-download"></i> Download Image
                        </a>
                    </div>
                    <div id="chart_bahasa" style="height:700px;"></div>
                </div>

                <div class="form-group m-form__group">
                    <div class="d-flex justify-content-start mb-3">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post" class="mr-2">
                            <input type="hidden" id="imagedata5" name="imagedata5">
                            <button type="submit" class="btn btn-primary"><i class="la la-print"></i> Printable version</button>
                        </form>
                        <a id="download_pinjam" class="btn btn-success text-white" download="Chart_Peminjaman.png">
                            <i class="la la-download"></i> Download Image
                        </a>
                    </div>
                    <div id="chart_pinjam" style="height:700px;"></div>
                </div>
                
                
                <script>
                    var GoogleChartsDemo = function () {
                        var a = function () {
                            google.load("visualization", "1", {
                                packages: ["corechart", "bar"]
                            }), google.setOnLoadCallback(function () {
                                GoogleChartsDemo.runDemos()
                            })
                        },
                        e = function () {
                            var a = new google.visualization.DataTable;
                            a.addColumn("string", ""),
                            <?php foreach ($pxAxis as $x) : ?>
                                a.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>"),
                            <?php endforeach; ?>
                            a.addRows([
                                ['<?php echo $ptitle; ?>', <?php
                            $count = 0;
                            foreach ($pyAxis as $y) {
                                if ($count > 0)
                                    echo ',';
                                echo $y;
                                $count++;
                            }
                            ?>]
                            ]);
                            var e = {
                                title: "<?php echo $ptitle; ?>",
                                focusTarget: "category",
                                hAxis: {
                                    title: "Total: <?php echo number_format($ptotal); ?> Buku",
                                    textPosition: 'none'
                                },
                                vAxis: {
                                    title: "Buku"
                                }
                            },
                            o = new google.visualization.ColumnChart(document.getElementById("chart_klas"));
                            google.visualization.events.addListener(o, 'ready', function () {
                                var imgUri = o.getImageURI();
                                document.getElementById("imagedata").value = imgUri;
                                document.getElementById("download_klas").href = imgUri; // Masukkan URI ke button download
                            });
                            o.draw(a, e);
                        },
                        k = function () {
                            var a = new google.visualization.DataTable;
                            a.addColumn("string", ""),
                            <?php foreach ($kxAxis as $x) : ?>
                                a.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>"),
                            <?php endforeach; ?>
                            a.addRows([
                                ['<?php echo $ktitle; ?>', <?php
                                $count = 0;
                                foreach ($kyAxis as $y) {
                                    if ($count > 0)
                                        echo ',';
                                    echo $y;
                                    $count++;
                                }
                                ?>]
                            ]);
                            var e = {
                                title: "<?php echo $ktitle; ?>",
                                focusTarget: "category",
                                hAxis: {
                                    title: "Total: <?php echo number_format($ktotal); ?> Buku",
                                    textPosition: 'none'
                                },
                                vAxis: {
                                    title: "Buku"
                                }
                            },
                            o = new google.visualization.ColumnChart(document.getElementById("chart_kategori"));
                            google.visualization.events.addListener(o, 'ready', function () {
                                var imgUri = o.getImageURI();
                                document.getElementById("imagedata2").value = imgUri;
                                document.getElementById("download_kategori").href = imgUri;
                            });
                            o.draw(a, e);
                        },
                        m = function () {
                            var a = new google.visualization.DataTable;
                            a.addColumn("string", ""),
                            <?php foreach ($mxAxis as $x) : ?>
                                a.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>"),
                            <?php endforeach; ?>
                            a.addRows([
                                ['<?php echo $mtitle; ?>', <?php
                                $count = 0;
                                foreach ($myAxis as $y) {
                                    if ($count > 0)
                                        echo ',';
                                    echo $y;
                                    $count++;
                                }
                                ?>]
                            ]);
                            var e = {
                                title: "<?php echo $mtitle; ?>",
                                focusTarget: "category",
                                hAxis: {
                                    title: "Total: <?php echo number_format($mtotal); ?> Buku",
                                    textPosition: 'none'
                                },
                                vAxis: {
                                    title: "Buku"
                                }
                            },
                            o = new google.visualization.ColumnChart(document.getElementById("chart_asal"));
                            google.visualization.events.addListener(o, 'ready', function () {
                                var imgUri = o.getImageURI();
                                document.getElementById("imagedata3").value = imgUri;
                                document.getElementById("download_asal").href = imgUri;
                            });
                            o.draw(a, e);
                        },
                        n = function () {
                            var a = new google.visualization.DataTable;
                            a.addColumn("string", ""),
                            <?php foreach ($nxAxis as $x) : ?>
                                a.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>"),
                            <?php endforeach; ?>
                            a.addRows([
                                ['<?php echo $ntitle; ?>', <?php
                            $count = 0;
                            foreach ($nyAxis as $y) {
                                if ($count > 0)
                                    echo ',';
                                echo $y;
                                $count++;
                            }
                            ?>]
                            ]);
                            var e = {
                                title: "<?php echo $ntitle; ?>",
                                focusTarget: "category",
                                hAxis: {
                                    title: "Total: <?php echo number_format($ntotal); ?> Buku",
                                    textPosition: 'none'
                                },
                                vAxis: {
                                    title: "Buku"
                                }
                            },
                            o = new google.visualization.ColumnChart(document.getElementById("chart_bahasa"));
                            google.visualization.events.addListener(o, 'ready', function () {
                                var imgUri = o.getImageURI();
                                document.getElementById("imagedata4").value = imgUri;
                                document.getElementById("download_bahasa").href = imgUri;
                            });
                            o.draw(a, e);
                        },
                        q = function () {
                                    var a = new google.visualization.DataTable;
                                    a.addColumn("string", ""),
                                    <?php foreach ($qxAxis as $x) : ?>
                                        a.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>"),
                                    <?php endforeach; ?>
                                    a.addRows([
                                        ['<?php echo $qtitle; ?>', <?php
                                        $count = 0;
                                        foreach ($qyAxis as $y) {
                                            if ($count > 0)
                                                echo ',';
                                            echo $y;
                                            $count++;
                                        }
                                        ?>]
                                    ]);
                                    var e = {
                                        title: "<?php echo $qtitle; ?>",
                                        focusTarget: "category",
                                        hAxis: {
                                            title: "Total: <?php echo number_format($qtotal); ?> Buku",
                                            textPosition: 'none'
                                        },
                                        vAxis: {
                                            title: "Buku"
                                        }
                                    },
                                    o = new google.visualization.ColumnChart(document.getElementById("chart_pinjam"));
                                    google.visualization.events.addListener(o, 'ready', function () {
                                        var imgUri = o.getImageURI();
                                        document.getElementById("imagedata5").value = imgUri;
                                        document.getElementById("download_pinjam").href = imgUri;
                                    });
                                    o.draw(a, e);
                                }
                        return {
                            init: function () {
                                a()
                            },
                            runDemos: function () {
                                e(), k(), m(), n(), q()
                            }
                        }
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