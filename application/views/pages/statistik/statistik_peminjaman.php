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
                <?php if ($this->session->flashdata('error_msg')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"></button>
                        <?php echo $this->session->flashdata('error_msg'); ?>
                    </div>
                <?php endif; ?>
                <form id="formFilter" action="<?php echo base_url("dir/$page_name/peminjaman"); ?>" method="post">
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
                            <label>Program Studi:</label>
                            <select class="form-control" name="pilihprodi">
                                <option value="">Semua Prodi</option>
                                <?php foreach ($list_prodi as $key): ?>
                                    <option value="<?php echo $key->nmmspst ?>" <?php echo ($pilihprodi == $key->nmmspst) ? 'selected' : ''; ?>><?php echo $key->nmmspst ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-4 mt-4">
                            <label class="m-checkbox m-checkbox--state-brand">
                                <input type="checkbox" name="pertanggal" value="1" <?= $this->input->post('pertanggal') ? 'checked' : '' ?>> Muncul Per Tanggal <span></span>
                            </label>
                            <button type="submit" class="btn btn-primary ml-3">Filter</button>
                            <button type="button" class="btn btn-secondary" onClick="resetFilter()">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <?php
        // Definisi Array Chart
        $charts = [
            ['id' => 'chart_prodi',    'input' => 'imagedata',  'title' => $ptitle, 'total' => $ptotal, 'xAxis' => $pxAxis, 'yAxis' => $pyAxis, 'mode_tgl' => $is_mode_tanggal],
            ['id' => 'chart_klas',     'input' => 'imagedata2', 'title' => $ktitle, 'total' => $ktotal, 'xAxis' => $kxAxis, 'yAxis' => $kyAxis, 'mode_tgl' => $is_mode_tanggal_klas],
            ['id' => 'chart_mhs',      'input' => 'imagedata3', 'title' => $mtitle, 'total' => $mtotal, 'xAxis' => $mxAxis, 'yAxis' => $myAxis, 'mode_tgl' => false],
            ['id' => 'chart_mhsnow',   'input' => 'imagedata4', 'title' => $ntitle, 'total' => $ntotal, 'xAxis' => $nxAxis, 'yAxis' => $nyAxis, 'mode_tgl' => false],
            ['id' => 'chart_mhsbefore','input' => 'imagedata5', 'title' => $btitle, 'total' => $btotal, 'xAxis' => $bxAxis, 'yAxis' => $byAxis, 'mode_tgl' => false],
            ['id' => 'chart_kategori', 'input' => 'imagedata6', 'title' => $gtitle, 'total' => $gtotal, 'xAxis' => $gaxAxis, 'yAxis' => $gyAxis, 'mode_tgl' => $is_mode_tanggal_kat],
            ['id' => 'chart_buku',     'input' => 'imagedata7', 'title' => $batitle, 'total' => $batotal, 'xAxis' => $baxAxis, 'yAxis' => $bayAxis, 'mode_tgl' => $is_mode_tanggal_buku],
            ['id' => 'chart_pegawai',  'input' => 'imagedata8', 'title' => $pgtitle, 'total' => $pgtotal, 'xAxis' => $pgxAxis, 'yAxis' => $pgyAxis, 'mode_tgl' => false],
        ];
        ?>

        <div class="row">
            <?php foreach ($charts as $c): ?>
            <div class="col-lg-12">
                <div class="m-portlet m-portlet--head-sm m-portlet--rounded mb-5 shadow-sm">
                    <div class="m-portlet__head">
                        <div class="m-portlet__head-caption">
                            <div class="m-portlet__head-title">
                                <span class="m-portlet__head-icon"><i class="flaticon-statistics"></i></span>
                                <h3 class="m-portlet__head-text"><?= $c['title'] ?></h3>
                            </div>
                        </div>
                        <div class="m-portlet__head-tools">
                            <ul class="m-portlet__nav">
                                <li class="m-portlet__nav-item">
                                    <form target="_blank" action="<?= base_url('admin/cetak/'); ?>" method="post" style="display:inline;">
                                        <input type="hidden" id="input_<?= $c['id'] ?>" name="<?= $c['input'] ?>">
                                        <button type="submit" class="btn btn-outline-primary btn-sm m-btn m-btn--icon">
                                            <span><i class="la la-print"></i><span>Print</span></span>
                                        </button>
                                    </form>
                                </li>
                                <li class="m-portlet__nav-item">
                                    <button type="button" onclick="downloadChart('<?= $c['id'] ?>', '<?= $c['title'] ?>')" class="btn btn-outline-success btn-sm m-btn m-btn--icon">
                                        <span><i class="la la-download"></i><span>PNG</span></span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="m-portlet__body">
                        <div id="<?= $c['id'] ?>" style="height:600px; width:100%;"></div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<script>
// ─── HELPER DOWNLOAD PNG ──────────────────────────────────────────
function downloadChart(containerId, title) {
    var imgUri = document.getElementById('input_' + containerId).value;
    if(imgUri) {
        var link = document.createElement('a');
        link.href = imgUri;
        link.download = title + '.png';
        link.click();
    } else {
        alert('Grafik sedang diproses, silakan tunggu sebentar.');
    }
}

var GoogleChartsDemo = (function () {
    
    // Simpan ImageURI ke input hidden berdasarkan ID container
    var saveChartImage = function (chart, containerId) {
        google.visualization.events.addListener(chart, 'ready', function () {
            var el = document.getElementById('input_' + containerId);
            if (el) el.value = chart.getImageURI();
        });
    };

    var getBaseOptions = function (title, total, satuan, legendOpts, chartAreaOpts) {
        return {
            title: title,
            focusTarget: 'category',
            hAxis: { title: 'Tanggal (Total: ' + total + ' ' + satuan + ')', titleTextStyle: { italic: false, bold: true } },
            vAxis: { title: 'Jumlah Peminjaman', viewWindow: { min: 0 } },
            chartArea: Object.assign({ width: '80%', height: '70%', top: 50 }, chartAreaOpts || {}),
            legend: Object.assign({ position: 'right', textStyle: { fontSize: 12 } }, legendOpts || {}),
            isStacked: true
        };
    };

    var drawDateChart = function (containerId, xAxis, yAxis, options) {
        var container = document.getElementById(containerId);
        if (!container) return;
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Tanggal');
        xAxis.forEach(function (col) { data.addColumn('number', col[1]); });
        data.addRows(yAxis);
        
        var chart = new google.visualization.ColumnChart(container);
        saveChartImage(chart, containerId);
        chart.draw(data, options);
    };

    var drawGenericChart = function (containerId, title, totalLabel, xAxis, yAxis) {
        var container = document.getElementById(containerId);
        if (!container || !yAxis || yAxis.length === 0) return;

        var data = new google.visualization.DataTable();

        if (xAxis.length == 1) {

            data.addColumn('string', '');
            data.addColumn('number', xAxis[0][1]);

            data.addRow(['', Number(yAxis[0])]);

        } else {

            data.addColumn('string', '');

            xAxis.forEach(function(col){
                data.addColumn(col[0], col[1]);
            });

            var flatY = Array.isArray(yAxis[0]) ? yAxis[0] : yAxis;
            data.addRow([title].concat(flatY.map(Number)));
        }

        var chart = new google.visualization.ColumnChart(container);
        saveChartImage(chart, containerId);
        chart.draw(data, {
            title: title,
            vAxis: { 
                title: 'Jumlah Peminjaman', // Ini akan muncul di sebelah kiri (Legend Kiri)
                viewWindow: { min: 0 } 
            },
            hAxis: { title: 'Total: ' + totalLabel + ' Peminjaman', textPosition: 'none' },
            legend: { position: 'right', textStyle: { fontSize: 12 } },
            chartArea: { width: '70%', height: '70%' }
        });
    };

    return {
        init: function () {
            google.load('visualization', '1', { packages: ['corechart'] });
            google.setOnLoadCallback(function () { GoogleChartsDemo.runDemos(); });
        },
        runDemos: function () {
            // Chart 1: Prodi
            <?php if ($is_mode_tanggal): ?>
                drawDateChart('chart_prodi', <?= json_encode($pxAxis) ?>, <?= json_encode($pyAxis) ?>, getBaseOptions('<?= $ptitle ?>', '<?= number_format($ptotal) ?>', 'Peminjaman', {position:'top'}));
            <?php else: ?>
                drawGenericChart('chart_prodi', '<?= $ptitle ?>', '<?= number_format($ptotal) ?>', <?= json_encode($pxAxis) ?>, <?= json_encode($pyAxis) ?>);
            <?php endif; ?>

            // Chart 2: Klasifikasi
            <?php if ($is_mode_tanggal_klas): ?>
                drawDateChart('chart_klas', <?= json_encode($kxAxis) ?>, <?= json_encode($kyAxis) ?>, getBaseOptions('<?= $ktitle ?>', '<?= number_format($ktotal) ?>', 'Buku', {position:'top'}));
            <?php else: ?>
                drawGenericChart('chart_klas', '<?= $ktitle ?>', '<?= number_format($ktotal) ?>', <?= json_encode($kxAxis) ?>, <?= json_encode($kyAxis) ?>);
            <?php endif; ?>

            // Chart 3, 4, 5: Mahasiswa
            drawGenericChart('chart_mhs', '<?= $mtitle ?>', '<?= number_format($mtotal) ?>', <?= json_encode($mxAxis) ?>, <?= json_encode($myAxis) ?>);
            drawGenericChart('chart_mhsnow', '<?= $ntitle ?>', '<?= number_format($ntotal) ?>', <?= json_encode($nxAxis) ?>, <?= json_encode($nyAxis) ?>);
            drawGenericChart('chart_mhsbefore', '<?= $btitle ?>', '<?= number_format($btotal) ?>', <?= json_encode($bxAxis) ?>, <?= json_encode($byAxis) ?>);

            // Chart 6: Kategori
            <?php if ($is_mode_tanggal_kat): ?>
                drawDateChart('chart_kategori', <?= json_encode($gaxAxis) ?>, <?= json_encode($gyAxis) ?>, getBaseOptions('<?= $gtitle ?>', '<?= number_format($gtotal) ?>', 'Buku', {position:'right'}, {width:'75%', left:100}));
            <?php else: ?>
                drawGenericChart('chart_kategori', '<?= $gtitle ?>', '<?= number_format($gtotal) ?>', <?= json_encode($gaxAxis) ?>, <?= json_encode($gyAxis) ?>);
            <?php endif; ?>

            // Chart 7: Buku Terpopuler
            <?php if ($is_mode_tanggal_buku): ?>
                drawDateChart('chart_buku', <?= json_encode($baxAxis) ?>, <?= json_encode($bayAxis) ?>, getBaseOptions('<?= $batitle ?>', '<?= number_format($batotal) ?>', 'Peminjaman', {position:'right'}, {width:'75%', left:70}));
            <?php else: ?>
                drawGenericChart('chart_buku', '<?= $batitle ?>', '<?= number_format($batotal) ?>', <?= json_encode($baxAxis) ?>, <?= json_encode($bayAxis) ?>);
            <?php endif; ?>
                
            // Chart 8: Pegawai Terbanyak Peminjaman
            drawGenericChart('chart_pegawai','<?= $pgtitle ?>','<?= number_format($pgtotal) ?>',<?= json_encode($pgxAxis) ?>,<?= json_encode($pgyAxis) ?>);

        }
    };
})();

$(document).ready(function() {
    GoogleChartsDemo.init();
    $('.datepicker').datepicker({ format: 'yyyy-mm-dd', autoclose: true, todayHighlight: true });
});

function resetFilter() {
    // 1. Kosongkan input tanggal awal dan akhir
    $('#tanggalawal').val('');
    $('#tanggalakhir').val('');
    
    // 2. Jika menggunakan bootstrap-datepicker, reset plugin-nya juga
    $('#tanggalawal, #tanggalakhir').datepicker('setDate', null);
    
    // 3. Reset dropdown Prodi ke pilihan pertama (Pilih Prodi)
    $('select[name="pilihprodi"]').val('');
    
    // 4. Uncheck checkbox "Muncul Per Tanggal"
    $('input[name="pertanggal"]').prop('checked', false);
    
    // 5. Submit form secara otomatis agar halaman kembali ke state awal tanpa filter
    // Atau jika ingin benar-benar bersih, arahkan ke URL asal tanpa POST data
    window.location.href = "<?php echo base_url("dir/$page_name/peminjaman"); ?>";
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