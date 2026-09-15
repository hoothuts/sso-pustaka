<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <script src="//www.google.com/jsapi" type="text/javascript"></script>

    <div class="m-subheader">
        <div class="d-flex align-items-center">
            <div class="mr-auto">
                <h3 class="m-subheader__title m-subheader__title--separator"><?php echo $page_title; ?></h3>
            </div>
        </div>
    </div>

    <div class="m-content">
        <div class="m-portlet">
            <div class="m-portlet__body">
                <form action="<?php echo base_url("dir/$page_name/buku_prodi"); ?>" method="post">
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label>Rentang Tanggal:</label>
                            <div class="input-group">
                                <input type="text" id="tanggalawal" name="tanggalawal" class="form-control m-input datepicker" value="<?php echo $tanggalawal; ?>" readonly>
                                <div class="input-group-append"><span class="input-group-text">S/D</span></div>
                                <input type="text" id="tanggalakhir" name="tanggalakhir" class="form-control m-input datepicker" value="<?php echo $tanggalakhir; ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>Klasifikasi:</label>
                            <select class="form-control" name="pilih_klas">
                                <option value="">Semua Klasifikasi</option>
                                <?php foreach ($list_klas as $k): ?>
                                    <option value="<?= $k->id ?>" <?= ($pilih_klas == $k->id) ? 'selected' : '' ?>>
                                        <?= $k->nama ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-primary ml-3">Filter</button>
                            <button type="button" class="btn btn-secondary" onClick="resetFilter()">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="row">
            <?php 
            $charts = [
                ['id' => 'judul', 'title' => $c1title, 'total' => $c1total, 'xAxis' => $c1xAxis, 'yAxis' => $c1yAxis],
                ['id' => 'eksemplar', 'title' => $c2title, 'total' => $c2total, 'xAxis' => $c2xAxis, 'yAxis' => $c2yAxis]
            ];
            foreach ($charts as $c): 
            ?>
            <div class="col-xl-12">
                <div class="m-portlet m-portlet--tab">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon"><i class="flaticon-graph"></i></span>
                                <h3 class="m-portlet__head-text"><?= $c['title'] ?></h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                    <form target="_blank" action="<?= base_url('admin/cetak/'); ?>" method="post">
                                        <input type="hidden" id="input_chart_<?= $c['id'] ?>" name="imagedata">
                                        <input type="hidden" name="judul_cetak" value="<?= $c['title'] ?>">
                                        <button type="submit" class="btn btn-outline-primary btn-sm"><i class="la la-print"></i> Print</button>
                                    </form>
                                </li>
                                <li class="m-portlet__nav-item">
                                    <button onclick="downloadChart('chart_<?= $c['id'] ?>', '<?= str_replace(' ', '_', $c['title']) ?>')" class="btn btn-outline-success btn-sm"><i class="la la-download"></i> PNG</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div id="chart_<?= $c['id'] ?>" style="height: 700px; width: 100%;"></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <script>
            google.charts.load('current', {packages: ['corechart']});
            google.charts.setOnLoadCallback(drawCharts);

            function drawCharts() {
                // Render menggunakan judul dari controller
                renderChart('chart_judul', '<?= $c1title ?>', 'Judul', <?= json_encode($c1xAxis) ?>, <?= json_encode($c1yAxis) ?>, '<?= $c1total ?>');
                renderChart('chart_eksemplar', '<?= $c2title ?>', 'Eksemplar', <?= json_encode($c2xAxis) ?>, <?= json_encode($c2yAxis) ?>, '<?= $c2total ?>');
            }

            function renderChart(id, title, label, xAxis, yAxis, total) {
                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Prodi');
                data.addColumn('number', label);
                data.addColumn({ type: 'string', role: 'style' });

                var colors = ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#5a5c69', '#6610f2', '#fd7e14', '#20c997', '#e83e8c'];
                var rows = [];
                for (var i = 0; i < xAxis.length; i++) {
                    rows.push([xAxis[i][1], yAxis[i], colors[i % colors.length]]);
                }
                data.addRows(rows);

                var options = {
                    title: title + ' (Total: ' + total + ')',
                    titleTextStyle: { fontSize: 16, bold: true },
                    hAxis: { slantedText: true, slantedTextAngle: 45 },
                    legend: { position: 'none' },
                    chartArea: { width: '85%', height: '60%', bottom: 250 }
                };

                var container = document.getElementById(id);
                var chart = new google.visualization.ColumnChart(container);

                google.visualization.events.addListener(chart, 'ready', function () {
                    document.getElementById('input_' + id).value = chart.getImageURI();
                });

                chart.draw(data, options);
            }

            function downloadChart(chartId, fileName) {
                var imgUri = document.getElementById('input_' + chartId).value;
                var link = document.createElement('a');
                link.href = imgUri;
                link.download = fileName + '.png';
                link.click();
            }
            
            $('.datepicker').datepicker({ format: 'yyyy-mm-dd', autoclose: true, todayHighlight: true });
            function resetFilter() {
                // 1. Kosongkan input tanggal awal dan akhir
                $('#tanggalawal').val('');
                $('#tanggalakhir').val('');

                // 2. Jika menggunakan bootstrap-datepicker, reset plugin-nya juga
                $('#tanggalawal, #tanggalakhir').datepicker('setDate', null);

                // 3. Reset dropdown Prodi ke pilihan pertama (Pilih Prodi)
                $('select[name="pilih_klas"]').val('');

                // 4. Submit form secara otomatis agar halaman kembali ke state awal tanpa filter
                // Atau jika ingin benar-benar bersih, arahkan ke URL asal tanpa POST data
                window.location.href = "<?php echo base_url("dir/$page_name/buku_prodi"); ?>";
            }
        </script>

        
        
       

    </div>
</div>