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
                <?php if ($page_action == 'anggota') : ?>
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
                                    ['Statistik Anggota Berdasarkan Prodi', <?php
                                    $count = 0;
                                    foreach ($yAxis as $y) {
                                        if ($count > 0) echo ',';
                                        echo $y;
                                        $count++;
                                    }
                                    ?>]
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
                                    chartArea: { width: '75%', height: '70%' }
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
                        jQuery(document).ready(function() {
                            GoogleChartsDemo.init();
                        });
                    </script>

                <?php endif; ?>
                <?php if ($page_action == 'denda') : ?>
                    <script src="<?php echo base_url(); ?>assets/demo/default/custom/components/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
                    <div class="m-form m-form--label-align-right  m--margin-bottom-30">
                        <form action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/denda" method="post">
                            <div class="row align-items-center">
                                <div class="col-xl-8 order-2 order-xl-1">
                                    <div class="form-group m-form__group row align-items-center">
                                        <div class="m-form__label">
                                            <label class="m-label m-label--single">
                                                Tanggal:
                                            </label>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                        <div class="col-md-4">
                                            <div class="m-form__control">
                                                <div class='input-group date' id='m_datepicker_1'>
                                                    <input type='text' id="tanggalawal" name="tanggalawal" class="form-control m-input" value="<?php if ($tanggalawal) echo $tanggalawal; ?>" readonly placeholder="Masukkan tanggal" />
                                                    <span class="input-group-addon">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-md-none m--margin-bottom-10"></div>
                                        </div>
                                        S/D
                                        <div class="col-md-4">
                                            <div class="m-form__control">
                                                <div class='input-group date' id='m_datepicker_2'>
                                                    <input type='text' id="tanggalakhir" name="tanggalakhir" class="form-control m-input" value="<?php if ($tanggalakhir) echo $tanggalakhir; ?>" readonly placeholder="Masukkan tanggal" />
                                                    <span class="input-group-addon">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-md-none m--margin-bottom-10"></div>
                                        </div>
                                        <script>
                                            function cleardate() {
                                                $('#tanggalawal').datepicker('setDate', null);
                                                $('#tanggalakhir').datepicker('setDate', null);
                                            }
                                        </script>
                                        <div class="m-form__label">
                                            <input type="submit" class="btn btn-primary" value="Submit">
                                            <a href="#" class="btn btn-secondary" onClick="cleardate()">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata" name="imagedata"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_anggota" style="height:700px;"></div>
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
                                        <?php foreach ($xAxis as $x) : ?>
                                            a.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>"),
                                        <?php endforeach; ?>
                                        a.addRows([
                                            ['Statistik Denda Berdasarkan Prodi', <?php
                                            $count = 0;
                                            foreach ($yAxis as $y) {
                                                if ($count > 0)
                                                    echo ',';
                                                echo $y;
                                                $count++;
                                            }
                                            ?>]
                                        ]);
                                        var e = {
                                            title: "Statistik Denda (Rata-rata) Berdasarkan Prodi",
                                            focusTarget: "category",
                                            hAxis: {
                                                title: "Total: Rp.<?php echo number_format($total); ?>",
                                                textPosition: 'none'
                                            },
                                            vAxis: {
                                                title: "Rupiah"
                                            }
                                        },
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_anggota"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata").value = o.getImageURI();
                                        });
                                        o.draw(a, e);
                                    }
                            return {
                                init: function () {
                                    a()
                                },
                                runDemos: function () {
                                    e()
                                }
                            }
                        }();
                        GoogleChartsDemo.init();
                    </script>

                <?php endif; ?>
                <?php if ($page_action == 'presensi') : ?>
                    <script src="<?php echo base_url(); ?>assets/demo/default/custom/components/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
                    <div class="m-form m-form--label-align-right  m--margin-bottom-30">
                        <form action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/presensi" method="post">
                            <div class="row align-items-center">
                                <div class="col-xl-8 order-2 order-xl-1">
                                    <div class="form-group m-form__group row align-items-center">
                                        <div class="m-form__label">
                                            <label class="m-label m-label--single">
                                                Tanggal:
                                            </label>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                        <div class="col-md-4">
                                            <div class="m-form__control">
                                                <div class='input-group date' id='m_datepicker_1'>
                                                    <input type='text' id="tanggalawal" name="tanggalawal" class="form-control m-input" value="<?php if ($tanggalawal) echo $tanggalawal; ?>" readonly placeholder="Masukkan tanggal" />
                                                    <span class="input-group-addon">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-md-none m--margin-bottom-10"></div>
                                        </div>
                                        S/D
                                        <div class="col-md-4">
                                            <div class="m-form__control">
                                                <div class='input-group date' id='m_datepicker_2'>
                                                    <input type='text' id="tanggalakhir" name="tanggalakhir" class="form-control m-input" value="<?php if ($tanggalakhir) echo $tanggalakhir; ?>" readonly placeholder="Masukkan tanggal" />
                                                    <span class="input-group-addon">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-md-none m--margin-bottom-10"></div>
                                        </div>
                                        <script>
                                        function cleardate() {
                                            $('#tanggalawal').datepicker('setDate', null);
                                            $('#tanggalakhir').datepicker('setDate', null);
                                        }
                                        </script>
                                        <div class="m-form__label">
                                            <input type="submit" class="btn btn-primary" value="Submit">
                                            <a href="#" class="btn btn-secondary" onClick="cleardate()">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata" name="imagedata"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_anggota" style="height:700px;"></div>
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
                                        <?php foreach ($xAxis as $x) : ?>
                                            a.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>"),
                                        <?php endforeach; ?>
                                        a.addRows([
                                            ['Statistik Kunjungan Berdasarkan Prodi', <?php
                                        $count = 0;
                                        foreach ($yAxis as $y) {
                                            if ($count > 0)
                                                echo ',';
                                            echo $y;
                                            $count++;
                                        }
                                        ?>]
                                        ]);
                                        var e = {
                                            title: "Statistik Presensi Kunjungan Berdasarkan Prodi",
                                            focusTarget: "category",
                                            hAxis: {
                                                title: "Total: <?php echo number_format($total); ?> Orang",
                                                textPosition: 'none'
                                            },
                                            vAxis: {
                                                title: "Orang"
                                            }
                                        },
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_anggota"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata").value = o.getImageURI();
                                        });
                                        o.draw(a, e);
                                    }
                            return {
                                init: function () {
                                    a()
                                },
                                runDemos: function () {
                                    e()
                                }
                            }
                        }();
                        GoogleChartsDemo.init();
                    </script>

                <?php endif; ?>
                <?php if ($page_action == 'kunjungan_baca_buku') : ?>
                    <script src="<?php echo base_url(); ?>assets/demo/default/custom/components/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
                    <div class="m-form m-form--label-align-right  m--margin-bottom-30">
                        <form action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/kunjungan_baca_buku" method="post">
                            <div class="row align-items-center">
                                <div class="col-xl-8 order-2 order-xl-1">
                                    <div class="form-group m-form__group row align-items-center">
                                        <div class="m-form__label">
                                            <label class="m-label m-label--single">
                                                Tanggal:
                                            </label>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                        <div class="col-md-4">
                                            <div class="m-form__control">
                                                <div class='input-group date' id='m_datepicker_1'>
                                                    <input type='text' id="tanggalawal" name="tanggalawal" class="form-control m-input" value="<?php if ($tanggalawal) echo $tanggalawal; ?>" readonly placeholder="Masukkan tanggal" />
                                                    <span class="input-group-addon">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-md-none m--margin-bottom-10"></div>
                                        </div>
                                        S/D
                                        <div class="col-md-4">
                                            <div class="m-form__control">
                                                <div class='input-group date' id='m_datepicker_2'>
                                                    <input type='text' id="tanggalakhir" name="tanggalakhir" class="form-control m-input" value="<?php if ($tanggalakhir) echo $tanggalakhir; ?>" readonly placeholder="Masukkan tanggal" />
                                                    <span class="input-group-addon">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-md-none m--margin-bottom-10"></div>
                                        </div>
                                        <script>
                                        function cleardate() {
                                            $('#tanggalawal').datepicker('setDate', null);
                                            $('#tanggalakhir').datepicker('setDate', null);
                                        }
                                        </script>
                                        <div class="m-form__label">
                                            <input type="submit" class="btn btn-primary" value="Submit">
                                            <a href="#" class="btn btn-secondary" onClick="cleardate()">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata" name="imagedata"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_anggota" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata2" name="imagedata2"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_anggota2" style="height:700px;"></div>
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
                                        <?php foreach ($xAxis as $x) : ?>
                                            a.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>"),
                                        <?php endforeach; ?>
                                        a.addRows([
                                            ['Statistik Total Pembaca Berdasarkan Kategori Buku', <?php
                                        $count = 0;
                                        foreach ($yAxis as $y) {
                                            if ($count > 0)
                                                echo ',';
                                            echo $y;
                                            $count++;
                                        }
                                        ?>]
                                        ]);
                                        var e = {
                                            title: "Statistik Total Pembaca Berdasarkan Kategori Buku",
                                            focusTarget: "category",
                                            hAxis: {
                                                title: "Total: <?php echo number_format($total); ?> Pembaca",
                                                textPosition: 'none'
                                            },
                                            vAxis: {
                                                title: "Pembaca"
                                            }
                                        },
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_anggota"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata").value = o.getImageURI();
                                        });
                                        o.draw(a, e);
                                    },
                                    k = function () {
                                        var a = new google.visualization.DataTable;
                                        a.addColumn("string", ""),
                                        <?php foreach ($xAxis2 as $x) : ?>
                                            a.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>"),
                                        <?php endforeach; ?>
                                        a.addRows([
                                            ['Statistik <?php echo $top; ?> Buku dengan Pembaca Terbanyak', <?php
                                        $count = 0;
                                        foreach ($yAxis2 as $y) {
                                            if ($count > 0)
                                                echo ',';
                                            echo $y;
                                            $count++;
                                        }
                                        ?>]
                                        ]);
                                        var e = {
                                            title: "Statistik <?php echo $top; ?> Buku dengan Pembaca Terbanyak",
                                            focusTarget: "category",
                                            hAxis: {
                                                title: "Total: <?php echo number_format($total2); ?> Pembaca",
                                                textPosition: 'none'
                                            },
                                            vAxis: {
                                                title: "Pembaca"
                                            }
                                        },
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_anggota2"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata2").value = o.getImageURI();
                                        });
                                        o.draw(a, e);
                                    }
                            return {
                                init: function () {
                                    a()
                                },
                                runDemos: function () {
                                    e(), k()
                                }
                            }
                        }();
                        GoogleChartsDemo.init();
                    </script>

                <?php endif; ?>
                <?php if ($page_action == 'pengunjung_web') : ?>
                    <script src="<?php echo base_url(); ?>assets/demo/default/custom/components/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
                    <div class="m-form m-form--label-align-right  m--margin-bottom-30">
                        <form action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/pengunjung_web" method="post">
                            <div class="row align-items-center">
                                <div class="col-xl-8 order-2 order-xl-1">
                                    <div class="form-group m-form__group row align-items-center">
                                        <div class="m-form__label">
                                            <label class="m-label m-label--single">
                                                Tanggal:
                                            </label>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                        <div class="col-md-4">
                                            <div class="m-form__control">
                                                <div class='input-group date' id='m_datepicker_1'>
                                                    <input type='text' id="tanggalawal" name="tanggalawal" class="form-control m-input" value="<?php if ($tanggalawal) echo $tanggalawal; ?>" readonly placeholder="Masukkan tanggal" />
                                                    <span class="input-group-addon">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-md-none m--margin-bottom-10"></div>
                                        </div>
                                        S/D
                                        <div class="col-md-4">
                                            <div class="m-form__control">
                                                <div class='input-group date' id='m_datepicker_2'>
                                                    <input type='text' id="tanggalakhir" name="tanggalakhir" class="form-control m-input" value="<?php if ($tanggalakhir) echo $tanggalakhir; ?>" readonly placeholder="Masukkan tanggal" />
                                                    <span class="input-group-addon">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-md-none m--margin-bottom-10"></div>
                                        </div>
                                        <script>
                                            function cleardate() {
                                                $('#tanggalawal').datepicker('setDate', null);
                                                $('#tanggalakhir').datepicker('setDate', null);
                                            }
                                        </script>
                                        <div class="m-form__label">
                                            <input type="submit" class="btn btn-primary" value="Submit">
                                            <a href="#" class="btn btn-secondary" onClick="cleardate()">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata" name="imagedata"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_anggota" style="height:700px;"></div>
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
                                        <?php foreach ($xAxis as $x) : ?>
                                            a.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>"),
                                        <?php endforeach; ?>
                                        a.addRows([
                                            ['Statistik Total Kunjungan Web Berdasarkan Tanggal', 
                                            <?php
                                            $count = 0;
                                            foreach ($yAxis as $y) {
                                                if ($count > 0)
                                                    echo ',';
                                                echo $y;
                                                $count++;
                                            }
                                            ?>]
                                        ]);
                                        var e = {
                                            title: "Statistik Total Kunjungan Web Berdasarkan Tanggal",
                                            focusTarget: "category",
                                            hAxis: {
                                                title: "Total: <?php echo number_format($total); ?> Pengunjung",
                                                textPosition: 'none'
                                            },
                                            vAxis: {
                                                title: "Pengunjung"
                                            }
                                        },
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_anggota"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata").value = o.getImageURI();
                                        });
                                        o.draw(a, e);
                                    }

                            return {
                                init: function () {
                                    a()
                                },
                                runDemos: function () {
                                    e()
                                }
                            }
                        }();
                        GoogleChartsDemo.init();
                    </script>

                <?php endif; ?>
                <?php if ($page_action == 'bukureferensi') : ?>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="type" name="type" value="table"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <table class="table table-hovered">
                            <?php
                            if (count($klas) > 0 && count($prodi) > 0) {
                                echo '<thead class="thead-default">';
                                echo '<tr>';
                                echo '<th>Klasifikasi</th>';
                                foreach ($prodi as $p) {
                                    ?><th><?php echo $p->nmmspst; ?></th><?php
                                }
                                echo '</tr>';
                                echo '</thead>';
                                foreach ($klas as $k) {
                                    echo '<tr>';
                                    echo '<td>' . $k->nama . '</td>';
                                    foreach ($prodi as $p) {
                                        $jum = $this->Md_siperpus_buku_prodi->getJumlahBukuProdiKlas($p->idmspst, $k->id);
                                        echo '<td>' . $jum . ' buku (0%)</td>';
                                    }
                                    echo '</tr>';
                                }
                                echo '<tr>';
                                echo '<td>Total</td>';
                                foreach ($prodi as $p) {
                                    $jum = $this->Md_siperpus_buku_prodi->getJumlahBukuProdi($p->idmspst);
                                    echo '<td>' . $jum . ' buku</td>';
                                }
                                echo '</tr>';
                            }
                            ?>
                        </table>
                    </div>

                <?php endif; ?>
                <?php if ($page_action == 'peminjaman') : ?>
                    <script src="<?php echo base_url(); ?>assets/demo/default/custom/components/forms/widgets/bootstrap-datepicker.js" type="text/javascript"></script>
                    <div class="m-form m-form--label-align-right  m--margin-bottom-30">
                        <form action="<?php echo base_url(); ?><?php echo $page_access; ?>/<?php echo $page_name; ?>/peminjaman" method="post">
                            <div class="row align-items-center">
                                <div class="col-xl-8 order-2 order-xl-1">
                                    <div class="form-group m-form__group row align-items-center">
                                        <div class="m-form__label">
                                            <label class="m-label m-label--single">
                                                Tanggal:
                                            </label>
                                        </div>
                                        <div class="d-md-none m--margin-bottom-10"></div>
                                        <div class="col-md-4">
                                            <div class="m-form__control">
                                                <div class='input-group date' id='m_datepicker_1'>
                                                    <input type='text' id="tanggalawal" name="tanggalawal" class="form-control m-input" value="<?php if ($tanggalawal) echo $tanggalawal; ?>" readonly placeholder="Masukkan tanggal" />
                                                    <span class="input-group-addon">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-md-none m--margin-bottom-10"></div>
                                        </div>
                                        S/D
                                        <div class="col-md-4">
                                            <div class="m-form__control">
                                                <div class='input-group date' id='m_datepicker_2'>
                                                    <input type='text' id="tanggalakhir" name="tanggalakhir" class="form-control m-input" value="<?php if ($tanggalakhir) echo $tanggalakhir; ?>" readonly placeholder="Masukkan tanggal" />
                                                    <span class="input-group-addon">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="d-md-none m--margin-bottom-10"></div>
                                        </div>
                                        <script>
                        function cleardate() {
                            $('#tanggalawal').datepicker('setDate', null);
                            $('#tanggalakhir').datepicker('setDate', null);
                        }
                                        </script>
                                        <div class="m-form__label">
                                            <input type="submit" class="btn btn-primary" value="Submit">
                                            <a href="#" class="btn btn-secondary" onClick="cleardate()">Reset</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata" name="imagedata"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_prodi" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata2" name="imagedata2"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_klas" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata3" name="imagedata3"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_mhs" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata4" name="imagedata4"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_mhsnow" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata5" name="imagedata5"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_mhsbefore" style="height:700px;"></div>
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
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_prodi"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata").value = o.getImageURI();
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
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_klas"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata2").value = o.getImageURI();
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
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_mhs"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata3").value = o.getImageURI();
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
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_mhsnow"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata4").value = o.getImageURI();
                                        });
                                        o.draw(a, e);
                                    },
                                    b = function () {
                                        var a = new google.visualization.DataTable;
                                        a.addColumn("string", ""),
    <?php foreach ($bxAxis as $x) : ?>
                                            a.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>"),
    <?php endforeach; ?>
                                        a.addRows([
                                            ['<?php echo $btitle; ?>', <?php
    $count = 0;
    foreach ($byAxis as $y) {
        if ($count > 0)
            echo ',';
        echo $y;
        $count++;
    }
    ?>]
                                        ]);
                                        var e = {
                                            title: "<?php echo $btitle; ?>",
                                            focusTarget: "category",
                                            hAxis: {
                                                title: "Total: <?php echo number_format($btotal); ?> Buku",
                                                textPosition: 'none'
                                            },
                                            vAxis: {
                                                title: "Buku"
                                            }
                                        },
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_mhsbefore"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata5").value = o.getImageURI();
                                        });
                                        o.draw(a, e);
                                    }
                            return {
                                init: function () {
                                    a()
                                },
                                runDemos: function () {
                                    e(), k(), m(), n(), b()
                                }
                            }
                        }();
                        GoogleChartsDemo.init();
                    </script>

                <?php endif; ?>
                <?php if ($page_action == 'periodik') : ?>
                    <div class="row">
                        <div class="col-md-2">
                            <form method="post">
                                <div class="form-group m-form__group">
                                    <label for="Lihat Statistik">
                                        Tahun
                                    </label>
                                    <select class="form-control m-input m-input--solid" onChange="this.form.submit()" id="tahun" name="tahun">
                                        <?php
                                        $thn = date('Y');
                                        for ($thn; $thn > 1980; $thn--) {
                                            ?>
                                            <option value="<?php echo $thn; ?>" <?php if ($thn == $curY) echo 'selected'; ?>><?php echo $thn; ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-2">
                            <form method="post">
                                <div class="form-group m-form__group">
                                    <label for="Lihat Statistik">
                                        Bulan
                                    </label>
                                    <select class="form-control m-input m-input--solid" onChange="this.form.submit()" id="bulan" name="bulan">
                                        <option value=''>-ALL-</option>
                                        <option value='1' <?php if ($curM == 1) echo 'selected'; ?>>Januari</option>
                                        <option value='2' <?php if ($curM == 2) echo 'selected'; ?>>Februari</option>
                                        <option value='3' <?php if ($curM == 3) echo 'selected'; ?>>Maret</option>
                                        <option value='4' <?php if ($curM == 4) echo 'selected'; ?>>April</option>
                                        <option value='5' <?php if ($curM == 5) echo 'selected'; ?>>Mei</option>
                                        <option value='6' <?php if ($curM == 6) echo 'selected'; ?>>Juni</option>
                                        <option value='7' <?php if ($curM == 7) echo 'selected'; ?>>July</option>
                                        <option value='8' <?php if ($curM == 8) echo 'selected'; ?>>Agustus</option>
                                        <option value='9' <?php if ($curM == 9) echo 'selected'; ?>>September</option>
                                        <option value='10' <?php if ($curM == 10) echo 'selected'; ?>>Oktober</option>
                                        <option value='11' <?php if ($curM == 11) echo 'selected'; ?>>November</option>
                                        <option value='12' <?php if ($curM == 12) echo 'selected'; ?>>Desember</option>
                                    </select>
                                </div>
                        </div>
                    </div>
                    </form>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata" name="imagedata"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_kunjungan" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata2" name="imagedata2"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_pinjamprodi" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata3" name="imagedata3"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_pinjamklas" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata4" name="imagedata4"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_inv" style="height:700px;"></div>
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
                                                title: "Total: <?php echo number_format($ptotal); ?> Orang",
                                                textPosition: 'none'
                                            }
                                        },
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_kunjungan"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata").value = o.getImageURI();
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
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_pinjamprodi"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata2").value = o.getImageURI();
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
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_pinjamklas"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata3").value = o.getImageURI();
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
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_inv"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata4").value = o.getImageURI();
                                        });
                                        o.draw(a, e);
                                    }
                            return {
                                init: function () {
                                    a()
                                },
                                runDemos: function () {
                                    e(), k(), m(), n()
                                }
                            }
                        }();
                        GoogleChartsDemo.init();
                    </script>
                <?php endif; ?>
                <?php if ($page_action == 'buku') : ?>
                    <div class="row">
                        <div class="col-md-2">
                            <form method="post">
                                <div class="form-group m-form__group">
                                    <label for="Lihat Statistik">
                                        Status Buku
                                    </label>
                                    <select class="form-control m-input m-input--solid" onChange="this.form.submit()" id="status" name="status">
                                        <option value='' <?php if ($cur == '') echo 'selected'; ?>>Aktif</option>
                                        <option value='H' <?php if ($cur == 'H') echo 'selected'; ?>>Hilang</option>
                                        <option value='R' <?php if ($cur == 'R') echo 'selected'; ?>>Rusak</option>
                                        <option value='A' <?php if ($cur == 'A') echo 'selected'; ?>>Diarsipkan</option>
                                        <option value='L' <?php if ($cur == 'L') echo 'selected'; ?>>Dilelang</option>
                                    </select>
                                </div>
                        </div>
                    </div>
                    </form>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata" name="imagedata"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_klas" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata2" name="imagedata2"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_kategori" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata3" name="imagedata3"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_asal" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata4" name="imagedata4"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_bahasa" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata5" name="imagedata5"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
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
                                                title: "Total: <?php echo number_format($ptotal); ?> Orang",
                                                textPosition: 'none'
                                            }
                                        },
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_klas"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata").value = o.getImageURI();
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
                                            document.getElementById("imagedata2").value = o.getImageURI();
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
                                            document.getElementById("imagedata3").value = o.getImageURI();
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
                                            document.getElementById("imagedata4").value = o.getImageURI();
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
                                            document.getElementById("imagedata5").value = o.getImageURI();
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
                        GoogleChartsDemo.init();
                    </script>

                <?php endif; ?>
                <?php if ($page_action == 'bukubyjudul') : ?>
                    <div class="row">
                        <div class="col-md-2">
                            <form method="post">
                                <div class="form-group m-form__group">
                                    <label for="Lihat Statistik">
                                        Status Buku
                                    </label>
                                    <select class="form-control m-input m-input--solid" onChange="this.form.submit()" id="status" name="status">
                                        <option value='' <?php if ($cur == '') echo 'selected'; ?>>Aktif</option>
                                        <option value='H' <?php if ($cur == 'H') echo 'selected'; ?>>Hilang</option>
                                        <option value='R' <?php if ($cur == 'R') echo 'selected'; ?>>Rusak</option>
                                        <option value='A' <?php if ($cur == 'A') echo 'selected'; ?>>Diarsipkan</option>
                                        <option value='L' <?php if ($cur == 'L') echo 'selected'; ?>>Dilelang</option>
                                    </select>
                                </div>
                        </div>
                    </div>
                    </form>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata" name="imagedata"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_klas" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata2" name="imagedata2"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_kategori" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata3" name="imagedata3"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_asal" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata4" name="imagedata4"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_bahasa" style="height:700px;"></div>
                    </div>
                    <div class="form-group m-form__group">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                            <input type="hidden" id="imagedata5" name="imagedata5"></input>
                            <button type="submit" class="btn m-btn--square  btn-primary">
                                Printable version
                            </button>
                        </form>
                        <div id="chart_pinjam" style="height:700px;"></div>
                    </div>
                    <script>
                        var GoogleChartsDemo = function () {
                            var a = function () {
                                google.load("visualization", "1", {
                                    packages: ["corechart", "bar"]
                                }),
                                        google.setOnLoadCallback(function () {
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
                                                title: "Total: <?php echo number_format($ptotal); ?> Judul Buku",
                                                textPosition: 'none'
                                            }
                                        },
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_klas"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata").value = o.getImageURI();
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
                                                title: "Total: <?php echo number_format($ktotal); ?> Judul Buku",
                                                textPosition: 'none'
                                            },
                                            vAxis: {
                                                title: "Buku"
                                            }
                                        },
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_kategori"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata2").value = o.getImageURI();
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
                                                title: "Total: <?php echo number_format($mtotal); ?> Judul Buku",
                                                textPosition: 'none'
                                            },
                                            vAxis: {
                                                title: "Buku"
                                            }
                                        },
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_asal"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata3").value = o.getImageURI();
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
                                                title: "Total: <?php echo number_format($ntotal); ?> Judul Buku",
                                                textPosition: 'none'
                                            },
                                            vAxis: {
                                                title: "Buku"
                                            }
                                        },
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_bahasa"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata4").value = o.getImageURI();
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
                                                title: "Total: <?php echo number_format($qtotal); ?> Judul Buku",
                                                textPosition: 'none'
                                            },
                                            vAxis: {
                                                title: "Buku"
                                            }
                                        },
                                                o = new google.visualization.ColumnChart(document.getElementById("chart_pinjam"));
                                        google.visualization.events.addListener(o, 'ready', function () {
                                            document.getElementById("imagedata5").value = o.getImageURI();
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
                        GoogleChartsDemo.init();
                    </script>
                <?php endif; ?>
                <?php if ($page_action == 'bukubythn_judul') : ?>
                    <div class="row">
                        <div class="col-md-2">
                            <form method="post">
                                <div class="form-group m-form__group">
                                    <label for="Lihat Statistik">
                                        Status Buku
                                    </label>
                                    <select class="form-control m-input m-input--solid" onChange="this.form.submit()" id="status" name="status">
                                        <option value='' <?php if ($cur == '') echo 'selected'; ?>>Aktif</option>
                                        <option value='H' <?php if ($cur == 'H') echo 'selected'; ?>>Hilang</option>
                                        <option value='R' <?php if ($cur == 'R') echo 'selected'; ?>>Rusak</option>
                                        <option value='A' <?php if ($cur == 'A') echo 'selected'; ?>>Diarsipkan</option>
                                        <option value='L' <?php if ($cur == 'L') echo 'selected'; ?>>Dilelang</option>
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group m-form__group">
                                <label for="Lihat Statistik">&nbsp;</label>
                                <select class="form-control m-input m-input--solid" onChange="this.form.submit()" id="tmp" name="tmp">
                                    <option value='' <?php if ($tmp == 'T') echo 'selected'; ?>>Total</option>
                                    <option value='Kls' <?php if ($tmp == 'Kls') echo 'selected'; ?>>Klasifikasi Buku</option>
                                    <option value='Kat' <?php if ($tmp == 'Kat') echo 'selected'; ?>>Kategori Buku</option>
                                    <option value='Pen' <?php if ($tmp == 'Pen') echo 'selected'; ?>>Penerbit Buku</option>
                                    <option value='Asa' <?php if ($tmp == 'Asa') echo 'selected'; ?>>Asal Buku</option>
                                </select>
                            </div>
                        </div>
                        <?php if ($tmp != 'T' && $tmp != 'Pen') : ?>
                            <div class="col-md-2">
                                <div class="form-group m-form__group">
                                    <label for="Lihat Statistik">&nbsp;</label>
                                    <select class="form-control m-input m-input--solid" onChange="this.form.submit()" id="tahun" name="tahun">
                                        <?php foreach ($gettahun as $t) : ?>
                                            <option value='<?php echo $t->thn_terbit; ?>' <?php if ($tahun == $t->thn_terbit) echo 'selected'; ?>><?php echo $t->thn_terbit; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>
                        </form>
                    </div>
                    <?php if ($tmp == 'T') : ?>
                        <div class="form-group m-form__group">
                            <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                                <input type="hidden" id="imagedata" name="imagedata"></input>
                                <button type="submit" class="btn m-btn--square  btn-primary">
                                    Printable version
                                </button>
                            </form>
                            <div id="chart_klas"></div>
                        </div>
                        <script>
                            var GoogleChartsDemo = function () {
                                var a = function () {
                                    google.load("visualization", "1", {
                                        packages: ["corechart", "bar"]
                                    }),
                                            google.setOnLoadCallback(function () {
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
                                                    title: "Total: <?php echo number_format($ptotal); ?> Judul Buku",
                                                    textPosition: 'none'
                                                },
                                                height: 1000
                                            },
                                                    o = new google.visualization.ColumnChart(document.getElementById("chart_klas"));
                                            google.visualization.events.addListener(o, 'ready', function () {
                                                document.getElementById("imagedata").value = o.getImageURI();
                                            });
                                            o.draw(a, e);
                                        }
                                return {
                                    init: function () {
                                        a()
                                    },
                                    runDemos: function () {
                                        e()
                                    }
                                }
                            }();
                        </script>
                    <?php endif; ?>
                    <?php if ($tmp == 'Kls') : ?>
                        <div class="form-group m-form__group">
                            <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                                <input type="hidden" id="imagedata2" name="imagedata2"></input>
                                <button type="submit" class="btn m-btn--square  btn-primary">
                                    Printable version
                                </button>
                            </form>
                            <div id="chart_klas" style="height:700px;"></div>
                        </div>
                        <script>
                            var GoogleChartsDemo = function () {
                                var a = function () {
                                    google.load("visualization", "1", {
                                        packages: ["corechart", "bar"]
                                    }),
                                            google.setOnLoadCallback(function () {
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
                                                    title: "Total: <?php echo number_format($ptotal); ?> Judul Buku",
                                                    textPosition: 'none'
                                                }
                                            },
                                                    o = new google.visualization.ColumnChart(document.getElementById("chart_klas"));
                                            google.visualization.events.addListener(o, 'ready', function () {
                                                document.getElementById("imagedata2").value = o.getImageURI();
                                            });
                                            o.draw(a, e);
                                        }
                                return {
                                    init: function () {
                                        a()
                                    },
                                    runDemos: function () {
                                        e()
                                    }
                                }
                            }();
                        </script>
                    <?php endif; ?>
                    <?php if ($tmp == 'Kat') : ?>
                        <div class="form-group m-form__group">
                            <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                                <input type="hidden" id="imagedata3" name="imagedata3"></input>
                                <button type="submit" class="btn m-btn--square  btn-primary">
                                    Printable version
                                </button>
                            </form>
                            <div id="chart_kat" style="height:700px;"></div>
                        </div>
                        <script>
                            var GoogleChartsDemo = function () {
                                var a = function () {
                                    google.load("visualization", "1", {
                                        packages: ["corechart", "bar"]
                                    }),
                                            google.setOnLoadCallback(function () {
                                                GoogleChartsDemo.runDemos()
                                            })
                                },
                                        e = function () {
                                            var a = new google.visualization.DataTable;
                                            a.addColumn("string", ""),
        <?php foreach ($kxAxis as $x) : ?>
                                                a.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>"),
        <?php endforeach; ?>
                                            a.addRows([
                                                ['<?php echo $ptitle; ?>', <?php
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
                                                title: "<?php echo $ptitle; ?>",
                                                focusTarget: "category",
                                                hAxis: {
                                                    title: "Total: <?php echo number_format($ptotal); ?> Judul Buku",
                                                    textPosition: 'none'
                                                }
                                            },
                                                    o = new google.visualization.ColumnChart(document.getElementById("chart_kat"));
                                            google.visualization.events.addListener(o, 'ready', function () {
                                                document.getElementById("imagedata3").value = o.getImageURI();
                                            });
                                            o.draw(a, e);
                                        }
                                return {
                                    init: function () {
                                        a()
                                    },
                                    runDemos: function () {
                                        e()
                                    }
                                }
                            }();
                        </script>
                    <?php endif; ?>
                    <?php if ($tmp == 'Pen') : ?>
                        <div class="form-group m-form__group">
                            <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                                <input type="hidden" id="imagedata4" name="imagedata4"></input>
                                <button type="submit" class="btn m-btn--square  btn-primary">
                                    Printable version
                                </button>
                            </form>
                            <div id="chart_penerbit"></div>
                        </div>
                        <script>
                            var GoogleChartsDemo = function () {
                                var a = function () {
                                    google.load("visualization", "1", {
                                        packages: ["corechart", "bar"]
                                    }),
                                            google.setOnLoadCallback(function () {
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
                                                    title: "Total: <?php echo number_format($ptotal); ?> Judul Buku",
                                                    textPosition: 'none'
                                                },
                                                height: 1000
                                            },
                                                    o = new google.visualization.ColumnChart(document.getElementById("chart_penerbit"));
                                            google.visualization.events.addListener(o, 'ready', function () {
                                                document.getElementById("imagedata4").value = o.getImageURI();
                                            });
                                            o.draw(a, e);
                                        }
                                return {
                                    init: function () {
                                        a()
                                    },
                                    runDemos: function () {
                                        e()
                                    }
                                }
                            }();
                        </script>
                    <?php endif; ?>
                    <?php if ($tmp == 'Asa') : ?>
                        <div class="form-group m-form__group">
                            <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                                <input type="hidden" id="imagedata5" name="imagedata5"></input>
                                <button type="submit" class="btn m-btn--square  btn-primary">
                                    Printable version
                                </button>
                            </form>
                            <div id="chart_asal" style="height:700px;"></div>
                        </div>
                        <script>
                            var GoogleChartsDemo = function () {
                                var a = function () {
                                    google.load("visualization", "1", {
                                        packages: ["corechart", "bar"]
                                    }),
                                            google.setOnLoadCallback(function () {
                                                GoogleChartsDemo.runDemos()
                                            })
                                },
                                        e = function () {
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
                                                title: "<?php echo $ptitle; ?>",
                                                focusTarget: "category",
                                                hAxis: {
                                                    title: "Total: <?php echo number_format($mtotal); ?> Judul Buku",
                                                    textPosition: 'none'
                                                }
                                            },
                                                    o = new google.visualization.ColumnChart(document.getElementById("chart_asal"));
                                            google.visualization.events.addListener(o, 'ready', function () {
                                                document.getElementById("imagedata5").value = o.getImageURI();
                                            });
                                            o.draw(a, e);
                                        }
                                return {
                                    init: function () {
                                        a()
                                    },
                                    runDemos: function () {
                                        e()
                                    }
                                }
                            }();
                        </script>
                    <?php endif; ?>
                    <script>
                        GoogleChartsDemo.init();
                    </script>
                <?php endif; ?>
                <?php if ($page_action == 'bukubythn_jml') : ?>
                    <div class="row">
                        <div class="col-md-2">
                            <form method="post">
                                <div class="form-group m-form__group">
                                    <label for="Lihat Statistik">
                                        Status Buku
                                    </label>
                                    <select class="form-control m-input m-input--solid" onChange="this.form.submit()" id="status" name="status">
                                        <option value='' <?php if ($cur == '') echo 'selected'; ?>>Aktif</option>
                                        <option value='H' <?php if ($cur == 'H') echo 'selected'; ?>>Hilang</option>
                                        <option value='R' <?php if ($cur == 'R') echo 'selected'; ?>>Rusak</option>
                                        <option value='A' <?php if ($cur == 'A') echo 'selected'; ?>>Diarsipkan</option>
                                        <option value='L' <?php if ($cur == 'L') echo 'selected'; ?>>Dilelang</option>
                                    </select>
                                </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group m-form__group">
                                <label for="Lihat Statistik">&nbsp;</label>
                                <select class="form-control m-input m-input--solid" onChange="this.form.submit()" id="tmp" name="tmp">
                                    <option value='' <?php if ($tmp == 'T') echo 'selected'; ?>>Total</option>
                                    <option value='Kls' <?php if ($tmp == 'Kls') echo 'selected'; ?>>Klasifikasi Buku</option>
                                    <option value='Kat' <?php if ($tmp == 'Kat') echo 'selected'; ?>>Kategori Buku</option>
                                    <option value='Pen' <?php if ($tmp == 'Pen') echo 'selected'; ?>>Penerbit Buku</option>
                                    <option value='Asa' <?php if ($tmp == 'Asa') echo 'selected'; ?>>Asal Buku</option>
                                </select>
                            </div>
                        </div>
                        <?php if ($tmp != 'T' && $tmp != 'Pen') : ?>
                            <div class="col-md-2">
                                <div class="form-group m-form__group">
                                    <label for="Lihat Statistik">&nbsp;</label>
                                    <select class="form-control m-input m-input--solid" onChange="this.form.submit()" id="tahun" name="tahun">
                                        <?php foreach ($gettahun as $t) : ?>
                                            <option value='<?php echo $t->thn_terbit; ?>' <?php if ($tahun == $t->thn_terbit) echo 'selected'; ?>><?php echo $t->thn_terbit; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?>
                        </form>
                    </div>
                    <?php if ($tmp == 'T') : ?>
                        <div class="form-group m-form__group">
                            <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                                <input type="hidden" id="imagedata" name="imagedata"></input>
                                <button type="submit" class="btn m-btn--square  btn-primary">
                                    Printable version
                                </button>
                            </form>
                            <div id="chart_total"></div><br><br>
                        </div>
                        <script>
                            var GoogleChartsDemo = function () {
                                var a = function () {
                                    google.load("visualization", "1", {
                                        packages: ["corechart", "bar"]
                                    }),
                                            google.setOnLoadCallback(function () {
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
                                                    title: "Total: <?php echo number_format($ptotal); ?> Judul Buku",
                                                    textPosition: 'none'
                                                },
                                                height: 1000
                                            },
                                                    o = new google.visualization.ColumnChart(document.getElementById("chart_total"));
                                            google.visualization.events.addListener(o, 'ready', function () {
                                                document.getElementById("imagedata").value = o.getImageURI();
                                            });
                                            o.draw(a, e);
                                        }
                                return {
                                    init: function () {
                                        a()
                                    },
                                    runDemos: function () {
                                        e()
                                    }
                                }
                            }();
                        </script>
                    <?php endif; ?>
                    <?php if ($tmp == 'Kls') : ?>
                        <div class="form-group m-form__group">
                            <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                                <input type="hidden" id="imagedata2" name="imagedata2"></input>
                                <button type="submit" class="btn m-btn--square  btn-primary">
                                    Printable version
                                </button>
                            </form>
                            <div id="chart_klas" style="height:700px;"></div>
                        </div>
                        <script>
                            var GoogleChartsDemo = function () {
                                var a = function () {
                                    google.load("visualization", "1", {
                                        packages: ["corechart", "bar"]
                                    }),
                                            google.setOnLoadCallback(function () {
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
                                                    title: "Total: <?php echo number_format($ptotal); ?> Judul Buku",
                                                    textPosition: 'none'
                                                }
                                            },
                                                    o = new google.visualization.ColumnChart(document.getElementById("chart_klas"));
                                            google.visualization.events.addListener(o, 'ready', function () {
                                                document.getElementById("imagedata2").value = o.getImageURI();
                                            });
                                            o.draw(a, e);
                                        }
                                return {
                                    init: function () {
                                        a()
                                    },
                                    runDemos: function () {
                                        e()
                                    }
                                }
                            }();
                        </script>
                    <?php endif; ?>
                    <?php if ($tmp == 'Kat') : ?>
                        <div class="form-group m-form__group">
                            <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                                <input type="hidden" id="imagedata3" name="imagedata3"></input>
                                <button type="submit" class="btn m-btn--square  btn-primary">
                                    Printable version
                                </button>
                            </form>
                            <div id="chart_kat" style="height:700px;"></div>
                        </div>
                        <script>
                            var GoogleChartsDemo = function () {
                                var a = function () {
                                    google.load("visualization", "1", {
                                        packages: ["corechart", "bar"]
                                    }),
                                            google.setOnLoadCallback(function () {
                                                GoogleChartsDemo.runDemos()
                                            })
                                },
                                        e = function () {
                                            var a = new google.visualization.DataTable;
                                            a.addColumn("string", ""),
        <?php foreach ($kxAxis as $x) : ?>
                                                a.addColumn("<?php echo $x[0]; ?>", "<?php echo $x[1]; ?>"),
        <?php endforeach; ?>
                                            a.addRows([
                                                ['<?php echo $ptitle; ?>', <?php
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
                                                title: "<?php echo $ptitle; ?>",
                                                focusTarget: "category",
                                                hAxis: {
                                                    title: "Total: <?php echo number_format($ptotal); ?> Judul Buku",
                                                    textPosition: 'none'
                                                }
                                            },
                                                    o = new google.visualization.ColumnChart(document.getElementById("chart_kat"));
                                            google.visualization.events.addListener(o, 'ready', function () {
                                                document.getElementById("imagedata3").value = o.getImageURI();
                                            });
                                            o.draw(a, e);
                                        }
                                return {
                                    init: function () {
                                        a()
                                    },
                                    runDemos: function () {
                                        e()
                                    }
                                }
                            }();
                        </script>
                    <?php endif; ?>
                    <?php if ($tmp == 'Pen') : ?>
                        <div class="form-group m-form__group">
                            <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                                <input type="hidden" id="imagedata4" name="imagedata4"></input>
                                <button type="submit" class="btn m-btn--square  btn-primary">
                                    Printable version
                                </button>
                            </form>
                            <div id="chart_penerbit"></div>
                        </div>
                        <script>
                            var GoogleChartsDemo = function () {
                                var a = function () {
                                    google.load("visualization", "1", {
                                        packages: ["corechart", "bar"]
                                    }),
                                            google.setOnLoadCallback(function () {
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
                                                    title: "Total: <?php echo number_format($ptotal); ?> Judul Buku",
                                                    textPosition: 'none'
                                                },
                                                height: 1000
                                            },
                                                    o = new google.visualization.ColumnChart(document.getElementById("chart_penerbit"));
                                            google.visualization.events.addListener(o, 'ready', function () {
                                                document.getElementById("imagedata4").value = o.getImageURI();
                                            });
                                            o.draw(a, e);
                                        }
                                return {
                                    init: function () {
                                        a()
                                    },
                                    runDemos: function () {
                                        e()
                                    }
                                }
                            }();
                        </script>
                    <?php endif; ?>
                    <?php if ($tmp == 'Asa') : ?>
                        <div class="form-group m-form__group">
                            <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post">
                                <input type="hidden" id="imagedata5" name="imagedata5"></input>
                                <button type="submit" class="btn m-btn--square  btn-primary">
                                    Printable version
                                </button>
                            </form>
                            <div id="chart_asal" style="height:700px;"></div>
                        </div>
                        <script>
                            var GoogleChartsDemo = function () {
                                var a = function () {
                                    google.load("visualization", "1", {
                                        packages: ["corechart", "bar"]
                                    }),
                                            google.setOnLoadCallback(function () {
                                                GoogleChartsDemo.runDemos()
                                            })
                                },
                                        e = function () {
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
                                                title: "<?php echo $ptitle; ?>",
                                                focusTarget: "category",
                                                hAxis: {
                                                    title: "Total: <?php echo number_format($mtotal); ?> Judul Buku",
                                                    textPosition: 'none'
                                                }
                                            },
                                                    o = new google.visualization.ColumnChart(document.getElementById("chart_asal"));
                                            google.visualization.events.addListener(o, 'ready', function () {
                                                document.getElementById("imagedata5").value = o.getImageURI();
                                            });
                                            o.draw(a, e);
                                        }
                                return {
                                    init: function () {
                                        a()
                                    },
                                    runDemos: function () {
                                        e()
                                    }
                                }
                            }();
                        </script>
                    <?php endif; ?>
                    <script>
                        GoogleChartsDemo.init();
                    </script>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>