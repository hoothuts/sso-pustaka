<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

    <div class="m-subheader">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator"><?php echo $page_title; ?></h3>
                <ul class="m-subheader__breadcrumbs m-nav m-nav--inline">
                    <li class="m-nav__item m-nav__item--home">
                        <a href="<?php echo base_url(); ?>admin/<?php echo $this->session->userdata('default'); ?>" class="m-nav__link m-nav__link--icon">
                            <i class="m-nav__link-icon la la-home"></i>
                        </a>
                    </li>
                    <li class="m-nav__separator"> - </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link"> <span class="m-nav__link-text"> Statistik </span></a>
                    </li>
                    <li class="m-nav__separator"> - </li>
                    <li class="m-nav__item">
                        <a href="" class="m-nav__link"> <span class="m-nav__link-text"><?php echo $page_title; ?></span> </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="m-content">
        <div class="m-portlet">
            <div class="m-portlet__body">
                <?php if ($this->session->flashdata('error_msg')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                        <?php echo $this->session->flashdata('error_msg'); ?>
                    </div>
                <?php endif; ?>
                <form id="formFilter" action="<?php echo base_url("dir/$page_dir/denda"); ?>" method="post">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label>Rentang Tanggal:</label>
                            <div class="input-group">
                                <input type="text" id="tanggalawal" name="tanggalawal" class="form-control m-input datepicker" value="<?php echo $tanggalawal; ?>" readonly>
                                <div class="input-group-append"><span class="input-group-text">S/D</span></div>
                                <input type="text" id="tanggalakhir" name="tanggalakhir" class="form-control m-input datepicker" value="<?php echo $tanggalakhir; ?>" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 mt-4">
                            <label class="m-checkbox m-checkbox--state-brand">
                                <input type="checkbox" name="pertanggal" value="1" <?= $pertanggal ? 'checked' : '' ?>> Muncul Per Tanggal <span></span>
                            </label>
                            <button type="submit" class="btn btn-primary ml-3">Filter</button>
                            <button type="button" class="btn btn-secondary" onClick="resetFilter()">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="m-portlet">
            <div class="m-portlet__body">
                <div class="form-group m-form__group">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form target="_blank" action="<?php echo base_url(); ?>admin/cetak/" method="post" id="form-cetak" style="display:inline;">
                                <input type="hidden" id="imagedata" name="imagedata">
                                <button type="submit" class="btn btn-primary m-btn--square">
                                    <i class="la la-print"></i> Printable Version
                                </button>
                            </form>
                            
                            <button type="button" onclick="downloadChart()" class="btn btn-success m-btn--square">
                                <i class="la la-download"></i> Download PNG
                            </button>
                        </div>
                    </div>
                    
                    <div id="chart_denda" style="height:700px; width:100%;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    var GoogleChartsDemo = function () {
        var initChart = function () {
            google.charts.load('current', {
                packages: ["corechart", "bar"]
            });
            google.charts.setOnLoadCallback(function () {
                GoogleChartsDemo.runDemos();
            });
        },
        drawDendaChart = function () {
            var data = new google.visualization.DataTable();

            <?php if ($is_stacked): ?>
                // MODE STACKED (Satu batang per tanggal, isi tumpukan prodi)
                data.addColumn('string', 'Tanggal');
                <?php foreach ($daftar_prodi as $p): ?>
                    data.addColumn('number', '<?= addslashes($p) ?>');
                <?php endforeach; ?>
                data.addRows(<?= json_encode($yAxis) ?>);
            <?php else: ?>
                // MODE BIASA (Per Prodi Total)
                data.addColumn('string', 'Prodi');
                data.addColumn('number', 'Orang');
                data.addColumn({type: 'string', role: 'style'});

                var colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#5a5c69', '#6610f2', '#fd7e14'];
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
                isStacked: <?= $is_stacked ? 'true' : 'false' ?>,
                hAxis: {
                    title: "Total: <?= number_format($total) ?> Denda",
                    slantedText: true, 
                    slantedTextAngle: 45
                },
                vAxis: { title: "Jumlah Denda" },
                chartArea: { width: '75%', height: '60%', bottom: 220 },
                legend: { position: '<?= $is_stacked ? "right" : "none" ?>' }
            };

            var chart = new google.visualization.ColumnChart(document.getElementById("chart_denda"));

            google.visualization.events.addListener(chart, 'ready', function () {
                // Simpan data gambar ke input hidden
                document.getElementById("imagedata").value = chart.getImageURI();
            });

            chart.draw(data, options);
        }

        return {
            init: function () {
                initChart();
            },
            runDemos: function () {
                drawDendaChart();
            }
        }
    }();

    $(document).ready(function () {
        GoogleChartsDemo.init();

        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            todayHighlight: true
        });
    });

    // FUNGSI DOWNLOAD PNG
    function downloadChart() {
        var imgUri = document.getElementById("imagedata").value;
        if(!imgUri) {
            alert("Harap tunggu, grafik sedang diproses...");
            return;
        }
        var link = document.createElement('a');
        link.href = imgUri;
        link.download = 'Statistik_Denda.png';
        link.click();
    }

    function resetFilter() {
        $('#tanggalawal').val('');
        $('#tanggalakhir').val('');
        $('.datepicker').datepicker('setDate', null);
        $('input[name="pertanggal"]').prop('checked', false);
        window.location.href = "<?php echo base_url("dir/statistik/denda"); ?>";
    }
    
    // --- FUNGSI VALIDASI RENTANG TANGGAL ---
    $('#formFilter').on('submit', function(e) {
        var awal = $('#tanggalawal').val();
        var akhir = $('#tanggalakhir').val();
        var isChecked = $('input[name="pertanggal"]').is(':checked');

        if (isChecked && awal !== '' && akhir !== '') {
            var dateAwal = new Date(awal);
            var dateAkhir = new Date(akhir);
            
            // Hitung selisih hari
            var timeDiff = Math.abs(dateAkhir.getTime() - dateAwal.getTime());
            var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

            if (diffDays > 31) {
                e.preventDefault(); // Batalkan submit
                alert("Peringatan: Rentang tanggal maksimal untuk mode 'Per Tanggal' adalah 31 hari. Rentang Anda saat ini: " + diffDays + " hari.");
                return false;
            }
        }
    });
</script>