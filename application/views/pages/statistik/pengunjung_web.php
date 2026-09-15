<script type="text/javascript"  src="https://www.gstatic.com/charts/loader.js"></script>
<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-subheader">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator">
                    <?php echo $page_title; ?>
                </h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?php echo base_url(); ?>admin/<?php echo $this->session->userdata('default'); ?>"
                           class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator"> - </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link">
                            <span class="m-nav__link-text">Statistik</span>
                        </a>
                    </li>
                    <li class="m-nav__separator"> - </li>
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

    <div class="m-content">
        <!-- ================= FILTER ================= -->
        <div class="m-portlet">
            <div class="m-portlet__body">
                <?php if ($this->session->flashdata('error_msg')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                        <?php echo $this->session->flashdata('error_msg'); ?>
                    </div>
                <?php endif; ?>

                <form id="formFilter" action="<?php echo base_url("dir/$page_dir/pengunjung_web"); ?>" method="post">

                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label>Rentang Tanggal:</label>
                            <div class="input-group">
                                <input type="text" id="tanggalawal" name="tanggalawal" class="form-control m-input datepicker" value="<?php echo $tanggalawal; ?>" readonly>
                                <div class="input-group-append">
                                    <span class="input-group-text">S/D</span>
                                </div>
                                <input type="text" id="tanggalakhir" name="tanggalakhir" class="form-control m-input datepicker" value="<?php echo $tanggalakhir; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-primary ml-3">
                                Filter
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="resetFilter()">Reset </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- ================= CHART KATEGORI ================= -->
        <div class="m-portlet">
            <div class="m-portlet__body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post" style="display:inline;">
                            <input type="hidden" id="imagedata_print" name="imagedata">
                            <button type="submit"  class="btn m-btn--square btn-primary">
                                <i class="la la-print"></i>
                                Printable Version
                            </button>
                        </form>
                        <input type="hidden" id="imagedata_download">
                        <button type="button" onclick="downloadChart()" class="btn btn-success m-btn--square">
                            <i class="la la-download"></i> Download PNG
                        </button>
                    </div>
                </div>

                <div id="chart_anggota" style="height:700px;"></div>

            </div>
        </div>      
    </div>
</div>

<script>
    function downloadChart()
    {
        var imgUri = document.getElementById("imagedata_download").value;
        if (!imgUri) {
            alert( "Harap tunggu, grafik sedang diproses...");
            return;
        }

        var link = document.createElement('a');
        link.href = imgUri;
        link.download ='Statistik_Pengunjung_Web.png';
        link.click();
    }

    var GoogleChartsDemo = function () {
        var a = function () {
            google.load("visualization", "1", {
                packages: ["corechart", "bar"]
            }), google.setOnLoadCallback(function () {
                GoogleChartsDemo.runDemos()
            });
        },
       e = function () {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Tanggal');
            data.addColumn('number', 'Pengunjung');

            var rows = [
                <?php
                $totalData = count($xAxis);

                for ($i = 0; $i < $totalData; $i++):
                ?>

                    [
                        '<?= addslashes($xAxis[$i][1]) ?>',
                        <?= (int)$yAxis[$i] ?>
                    ]

                    <?php if ($i < ($totalData - 1)) echo ','; ?>

                <?php endfor; ?>

            ];

            data.addRows(rows);

            var options = {

                title: "Statistik Total Kunjungan Web Berdasarkan Tanggal",
                curveType: 'function',
                pointSize: 8,
                lineWidth: 3,
                legend: {
                    position: 'none'
                },
                hAxis: {
                    title: 'Tanggal',
                    slantedText: true,
                    slantedTextAngle: 45
                },
                vAxis: {
                    title: 'Pengunjung',
                    minValue: 0
                },
                chartArea: {
                    width: '80%',
                    height: '65%'
                }
            };

            var chart = new google.visualization.LineChart(
                document.getElementById("chart_anggota")
            );

            google.visualization.events.addListener(chart, 'ready', function () {
                var uri = chart.getImageURI();
                document.getElementById("imagedata_print").value = uri;
                document.getElementById("imagedata_download").value = uri;
            });
            chart.draw(data, options);
        }

        return {
            init: function () {
                a();
            },
            runDemos: function () {
                e();
            }
        }
    }();
    GoogleChartsDemo.init();

    // =========================
    // DATEPICKER
    // =========================
    $(document).ready(function () {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });
</script>