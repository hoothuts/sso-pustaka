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

                <form id="formFilter" action="<?php echo base_url("dir/$page_dir/kunjungan_baca_buku"); ?>" method="post">

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
                            <label class="m-checkbox m-checkbox--state-brand">
                                <input type="checkbox" name="pertanggal" value="1" <?= !empty($pertanggal) ? 'checked' : '' ?>>
                                Muncul Per Tanggal
                                <span></span>
                            </label>
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
                        <form target="_blank"
                              action="<?php echo base_url(); ?>admin/cetak/"
                              method="post"
                              style="display:inline;">

                            <input type="hidden"
                                   id="imagedata_print_1"
                                   name="imagedata">

                            <button type="submit"
                                    class="btn btn-primary m-btn--square">

                                <i class="la la-print"></i>
                                Printable Version

                            </button>

                        </form>

                        <input type="hidden"
                               id="imagedata_download_1">

                        <button type="button"
                                onclick="downloadChart()"
                                class="btn btn-success m-btn--square">

                            <i class="la la-download"></i>
                            Download PNG

                        </button>

                    </div>

                </div>

                <div id="chart_kategori"
                     style="height:700px; width:100%;"></div>

            </div>

        </div>

        <!-- ================= CHART TOP BUKU ================= -->
        <div class="m-portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Top <?= $top ?> Buku Paling Banyak Dibaca
                        </h3>
                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="row mb-3">
                    <div class="col-md-12">
                        <form target="_blank"
                              action="<?php echo base_url(); ?>admin/cetak/"
                              method="post"
                              style="display:inline;">

                            <input type="hidden"
                                   id="imagedata_print_2"
                                   name="imagedata">

                            <button type="submit"
                                    class="btn btn-primary m-btn--square">

                                <i class="la la-print"></i>
                                Printable Version

                            </button>

                        </form>

                        <input type="hidden"
                               id="imagedata_download_2">

                        <button type="button"
                                onclick="downloadChart2()"
                                class="btn btn-success m-btn--square">

                            <i class="la la-download"></i>
                            Download PNG

                        </button>

                    </div>

                </div>
                <div id="chart_top_buku"
                     style="height:800px; width:100%;"></div>

            </div>

        </div>

    </div>

</div>

<script>
    google.charts.load('current', {
        packages: ['corechart', 'bar']
    });

    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts()
    {
        drawKategoriChart();
        drawTopBukuChart();
    }

    // =========================
    // CHART KATEGORI
    // =========================

    function drawKategoriChart()
    {
        var data = new google.visualization.DataTable();
<?php if (!empty($is_stacked) && $is_stacked): ?>

            // ================= STACKED MODE =================

            data.addColumn('string', 'Tanggal');

    <?php foreach ($daftar_kategori as $k): ?>

                data.addColumn(
                        'number',
                        '<?= addslashes($k) ?>'
                        );

    <?php endforeach; ?>

            data.addRows(
    <?= json_encode($yAxis) ?>
            );

<?php else: ?>

            // ================= NORMAL MODE =================
            data.addColumn('string', 'Kategori');
            data.addColumn('number', 'Jumlah');
            data.addColumn({
                type: 'string',
                role: 'style'
            });

            var colors = [
                '#4e73df',
                '#1cc88a',
                '#36b9cc',
                '#f6c23e',
                '#e74a3b',
                '#5a5c69',
                '#6610f2',
                '#fd7e14'
            ];

            var rows = [];

    <?php for ($i = 0; $i < count($xAxis); $i++): ?>

                rows.push([
                    '<?= addslashes($xAxis[$i][1]) ?>',
        <?= $yAxis[$i] ?>,
                    'color: ' + colors[<?= $i ?> % colors.length]
                ]);

    <?php endfor; ?>

            data.addRows(rows);

<?php endif; ?>

        var options = {

            title: "<?= $chart_title ?>",
            isStacked: <?= !empty($is_stacked) && $is_stacked ? 'true' : 'false' ?>,
            hAxis: {
                title: "Total: <?= number_format($total) ?> Kunjungan",
                slantedText: true,
                slantedTextAngle: 45
            },
            vAxis: {
                title: "Jumlah"
            },
            chartArea: {
                width: '75%',
                height: '60%',
                bottom: 220
            },
            legend: {
                position: '<?= !empty($is_stacked) && $is_stacked ? "right" : "none" ?>'
            }
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_kategori'));

        google.visualization.events.addListener(chart, 'ready', function () {
            var uri = chart.getImageURI();
            document.getElementById('imagedata_print_1').value = uri;
            document.getElementById('imagedata_download_1').value = uri;

        });

        chart.draw(data, options);
    }

    // =========================
    // CHART TOP BUKU
    // =========================

    function drawTopBukuChart()
    {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Judul Buku');
        data.addColumn('number', 'Jumlah');

        var rows = [];

<?php for ($i = 0; $i < count($xAxis2); $i++): ?>

            rows.push([
                '<?= addslashes($xAxis2[$i][1]) ?>',
    <?= $yAxis2[$i] ?>
            ]);

<?php endfor; ?>

        data.addRows(rows);

        var options = {
            title: "Top <?= $top ?> Buku Paling Banyak Dibaca",
            bars: 'horizontal',
            legend: {
                position: 'none'
            },
            chartArea: {
                width: '70%',
                height: '80%'
            },
            hAxis: {
                title: "Jumlah Dibaca"
            },
            vAxis: {
                title: "Judul Buku"
            }
        };

        var chart = new google.visualization.BarChart( document.getElementById('chart_top_buku') );

        google.visualization.events.addListener(chart, 'ready', function () {
            var uri = chart.getImageURI();
            document.getElementById('imagedata_print_2').value = uri;
            document.getElementById('imagedata_download_2').value = uri;
        });

        chart.draw(data, options);

    }

    // =========================
    // DOWNLOAD PNG
    // =========================
    function downloadChart()
    {
        var imgUri = document.getElementById("imagedata_download_1").value;

        if (!imgUri) {
            alert( "Harap tunggu, grafik sedang diproses..." );
            return;
        }

        var link = document.createElement('a');
        link.href = imgUri;
        link.download = 'Statistik_Kunjungan_Baca_Buku.png';
        link.click();
    }

    function downloadChart2()
    {
        var imgUri = document.getElementById( "imagedata_download_2" ).value;

        if (!imgUri) { 
            alert("Harap tunggu, grafik sedang diproses...");
            return;
        }

        var link = document.createElement('a');
        link.href = imgUri;
        link.download ='Top_Buku_Paling_Banyak_Dibaca.png';
        link.click();
    }

    // =========================
    // RESET FILTER
    // =========================
    function resetFilter()
    {
        $('#tanggalawal').val('');
        $('#tanggalakhir').val('');
        $('.datepicker').datepicker('setDate', null);
        $('input[name="pertanggal"]').prop('checked', false);
        window.location.href = "<?php echo base_url("dir/statistik/kunjungan_baca_buku"); ?>";
    }

    // =========================
    // VALIDASI RANGE 31 HARI
    // =========================
    $('#formFilter').on('submit', function (e) {
        var awal = $('#tanggalawal').val();
        var akhir =  $('#tanggalakhir').val();
        var isChecked = $('input[name="pertanggal"]').is(':checked');

        if (isChecked && awal !== '' && akhir !== '') {
            var dateAwal = new Date(awal);
            var dateAkhir = new Date(akhir);
            var timeDiff = Math.abs(dateAkhir.getTime() - dateAwal.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if (diffDays > 31) {
                e.preventDefault();
                alert("Peringatan: Rentang tanggal maksimal untuk mode 'Per Tanggal' adalah 31 hari.");
                return false;
            }
        }
    });

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