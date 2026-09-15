<!-- ================= KPI ================= -->

<style>

.dashboard-card{

    position: relative;

    overflow: hidden;

    border-radius: 12px;

    margin-bottom: 25px;

    background: #fff;

    box-shadow: 0 4px 15px rgba(0,0,0,0.05);

    transition: all 0.3s ease;
}

.dashboard-card:hover{

    transform: translateY(-4px);

    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

.dashboard-card-body{

    padding: 30px;

    position: relative;

    min-height: 170px;

    display: flex;

    flex-direction: column;

    justify-content: flex-start;
}

.dashboard-card-number{

    font-size: 42px;

    font-weight: 700;

    color: #2c2e3e;

    line-height: 1;
}

.dashboard-card-title{

    margin-top: 12px;

    font-size: 18px;

    font-weight: 600;

    color: #5d6078;
    
    min-height: 54px;
}

.dashboard-card-info{

    margin-top: 0px;

    color: #a0a3bd;

    font-size: 13px;
}

.dashboard-card-icon{

    position: absolute;

    right: 18px;

    bottom: 12px;

    font-size: 82px;

    /*opacity: 0.18;*/

    transition: all 0.3s ease;
}

.dashboard-card-icon i{
    font-size:30px;
}

.dashboard-card:hover .dashboard-card-icon{

    transform: scale(1.08);

    opacity: 0.24;
}

.dashboard-card-primary .dashboard-card-icon{
    color: #5867dd;
}

.dashboard-card-success .dashboard-card-icon{
    color: #34bfa3;
}

.dashboard-card-info .dashboard-card-icon{
    color: #36a3f7;
}

.dashboard-card-danger .dashboard-card-icon{
    color: #f4516c;
}



.dashboard-chart-header{

    display: flex;

    align-items: center;

    gap: 15px;
}

.dashboard-chart-header .m-portlet__head-text{

    margin-bottom: 0;
}

.dashboard-period-filter{

    width: 140px;

    height: 32px;

    padding-top: 2px;
}

</style>

<div class="m-grid__item m-grid__item--fluid m-wrapper">

    <div class="m-content">

        <!-- ================= KPI ================= -->

        <div class="row">

            <!-- TOTAL TODAY -->
            <div class="col-xl-3">

                <div class="dashboard-card dashboard-card-primary">

                    <div class="dashboard-card-body">

                        <div class="dashboard-card-icon">
                            <i class="fa fa-calendar-check-o"></i>
                        </div>

                        <div class="dashboard-card-number">
                            <?= number_format($total_today) ?>
                        </div>

                        <div class="dashboard-card-title">
                            Total Access Hari Ini
                        </div>

                        <div class="dashboard-card-info">
                            Aktivitas akses jurnal hari ini
                        </div>

                    </div>

                </div>

            </div>

            <!-- TOTAL MONTH -->
            <div class="col-xl-3">

                <div class="dashboard-card dashboard-card-success">

                    <div class="dashboard-card-body">

                        <div class="dashboard-card-icon">
                            <i class="fa fa-bar-chart"></i>
                        </div>

                        <div class="dashboard-card-number">
                            <?= number_format($total_month) ?>
                        </div>

                        <div class="dashboard-card-title">
                            Total Access Bulan Ini
                        </div>

                        <div class="dashboard-card-info">
                            Statistik akses bulan berjalan
                        </div>

                    </div>

                </div>

            </div>

            <!-- TOTAL USER -->
            <div class="col-xl-3">

                <div class="dashboard-card dashboard-card-info">

                    <div class="dashboard-card-body">

                        <div class="dashboard-card-icon">
                            <i class="fa fa-users"></i>
                        </div>

                        <div class="dashboard-card-number">
                            <?= number_format($total_user) ?>
                        </div>

                        <div class="dashboard-card-title">
                            Mahasiswa Pengguna
                        </div>

                        <div class="dashboard-card-info">
                            Total mahasiswa menggunakan jurnal
                        </div>

                    </div>

                </div>

            </div>

            <!-- TOTAL VENDOR -->
            <div class="col-xl-3">

                <div class="dashboard-card dashboard-card-danger">

                    <div class="dashboard-card-body">

                        <div class="dashboard-card-icon">
                            <i class="fa fa-book"></i>
                        </div>

                        <div class="dashboard-card-number">
                            <?= number_format($total_vendor) ?>
                        </div>

                        <div class="dashboard-card-title">
                            Vendor Aktif
                        </div>

                        <div class="dashboard-card-info">
                            Vendor jurnal tersedia
                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ================= CHART ================= -->

        <div class="row">

            <!-- CHART 7 HARI -->
            <div class="col-xl-8">

                <div class="m-portlet">

                    <div class="m-portlet__head">

                        <div class="m-portlet__head-caption">

                            <div class="m-portlet__head-title" style="vertical-align: middle; margin-top: 20px;">

                                <div class="dashboard-chart-header">

                                    <h3 class="m-portlet__head-text">
                                        Statistik Akses Jurnal
                                    </h3>

                                    <select id="filter_period" class="form-control form-control-sm dashboard-period-filter">
                                        <option value="7d">
                                            7 Hari
                                        </option>
                                        <option value="30d" selected>
                                            30 Hari
                                        </option>
                                        <option value="1y">
                                            1 Tahun
                                        </option>
                                    </select>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="m-portlet__body">

                        <div id="chart_7_hari"
                             style="height:350px;">

                        </div>

                    </div>

                </div>

            </div>

            <!-- AKTIVITAS -->
            <div class="col-xl-4">

                <div class="m-portlet">

                    <div class="m-portlet__head">

                        <div class="m-portlet__head-caption">

                            <div class="m-portlet__head-title">

                                <h3 class="m-portlet__head-text">

                                    Distribusi Aktivitas

                                    <button type="button"
                                            class="btn btn-sm btn-info btn-icon"
                                            data-toggle="modal"
                                            data-target="#modal_info_aktivitas"
                                            style="margin-left:10px;">

                                        <i class="fa fa-info"></i>

                                    </button>

                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="m-portlet__body">

                        <div id="chart_aktivitas"
                             style="height:350px;">

                        </div>

                    </div>

                </div>

            </div>

        </div>

        <!-- ================= TOP DATA ================= -->

        <div class="row">

            <!-- TOP VENDOR -->
            <div class="col-xl-6">

                <div class="m-portlet">

                    <div class="m-portlet__head">

                        <div class="m-portlet__head-caption">

                            <div class="m-portlet__head-title">

                                <h3 class="m-portlet__head-text">
                                    Top Vendor Jurnal
                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="m-portlet__body">

                        <div id="chart_vendor"
                             style="height:350px;">

                        </div>

                    </div>

                </div>

            </div>

            <!-- TOP PRODI -->
            <div class="col-xl-6">

                <div class="m-portlet">

                    <div class="m-portlet__head">

                        <div class="m-portlet__head-caption">

                            <div class="m-portlet__head-title">

                                <h3 class="m-portlet__head-text">
                                    Top Prodi Pengguna
                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="m-portlet__body">

                        <div id="chart_prodi"
                             style="height:350px;">

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- ================= MODAL INFO AKTIVITAS ================= -->
<div class="modal fade"
     id="modal_info_aktivitas"
     tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <div class="modal-header m--bg-info">

                <h5 class="modal-title m--font-light">

                    Informasi Aktivitas Sistem Jurnal

                </h5>

                <button type="button"
                        class="close"
                        data-dismiss="modal">

                    <span>&times;</span>

                </button>

            </div>

            <div class="modal-body">

                <div class="alert alert-info">

                    Aktivitas berikut dicatat otomatis oleh sistem
                    untuk kebutuhan audit penggunaan jurnal
                    berlangganan perpustakaan.

                </div>

                <div class="table-responsive">

                    <table class="table table-bordered table-hover">

                        <thead>

                            <tr>

                                <th width="220">
                                    Aktivitas
                                </th>

                                <th>
                                    Penjelasan
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            <tr>

                                <td>

                                    <span class="m-badge m-badge--info m-badge--wide">
                                        SHOW_ACCOUNT
                                    </span>

                                </td>

                                <td>
                                    Mahasiswa meminta account / credential jurnal dari sistem.
                                </td>

                            </tr>

                            <tr>

                                <td>

                                    <span class="m-badge m-badge--warning m-badge--wide">
                                        VIEW_PASSWORD
                                    </span>

                                </td>

                                <td>
                                    Password account jurnal ditampilkan kepada user.
                                </td>

                            </tr>

                            <tr>

                                <td>

                                    <span class="m-badge m-badge--success m-badge--wide">
                                        COPY_ACCOUNT
                                    </span>

                                </td>

                                <td>
                                    User menyalin username atau password account jurnal.
                                </td>

                            </tr>

                            <tr>

                                <td>

                                    <span class="m-badge m-badge--danger m-badge--wide">
                                        AUTO RELEASE
                                    </span>

                                </td>

                                <td>
                                    Account jurnal dilepas otomatis oleh sistem.
                                </td>

                            </tr>

                            <tr>

                                <td>

                                    <span class="m-badge m-badge--primary m-badge--wide">
                                        RELEASE_ACCOUNT
                                    </span>

                                </td>

                                <td>
                                    Account jurnal dilepas manual oleh user.
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- APEXCHART -->
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script>

$(document).ready(function(){

    load_chart_access();

    load_chart_vendor();

    load_chart_prodi();

    load_chart_aktivitas();
    
    $('#filter_period').change(function(){

        $('#chart_7_hari').html('');

        load_chart_access();

    });

});

/* ===================================================== */
/* CHART ACEESS */
/* ===================================================== */

function load_chart_access()
{
    let period = $('#filter_period').val();

    $.get(

        "<?= base_url('dir/dashboard_jurnal/chart_access') ?>",

        {
            period: period
        },

        function(res){

            let categories = [];
            let series = [];

            $.each(res, function(i, item){

                categories.push(item.label);

                series.push(parseInt(item.total));

            });

            let chartType = 'line';

            if(period == '1y'){
                chartType = 'area';
            }

            var options = {

                chart: {

                    type: chartType,

                    height: 350,

                    toolbar: {
                        show: true
                    }

                },

                series: [{
                    name: 'Jumlah Akses',
                    data: series
                }],

                xaxis: {

                    categories: categories,

                    title: {
                        text: 'Periode'
                    }

                },

                yaxis: {

                    title: {
                        text: 'Jumlah Akses'
                    }

                },

                stroke: {
                    curve: 'smooth'
                },

                dataLabels: {
                    enabled: true
                }

            };

            let chart = new ApexCharts(
                document.querySelector("#chart_7_hari"),
                options
            );

            chart.render();

        },

        'json'

    );
}

/* ===================================================== */
/* TOP VENDOR */
/* ===================================================== */

function load_chart_vendor()
{
    $.get(

        "<?= base_url('dir/dashboard_jurnal/top_vendor') ?>",

        function(res){

            let categories = [];
            let series = [];

            $.each(res, function(i, item){

                categories.push(item.nama_vendor);

                series.push(parseInt(item.total));

            });

            var options = {

                chart: {
                    type: 'bar',
                    height: 350
                },

                plotOptions: {
                    bar: {
                        horizontal: true
                    }
                },

                dataLabels: {
                    enabled: true
                },

                series: [{
                    name: 'Jumlah Akses',
                    data: series
                }],

                xaxis: {
                    categories: categories,

                    title: {
                        text: 'Jumlah Akses'
                    }
                },

                yaxis: {
                    title: {
                        text: 'Vendor Jurnal'
                    }
                }

            };

            let chart = new ApexCharts(
                document.querySelector("#chart_vendor"),
                options
            );

            chart.render();

        },

        'json'

    );
}

/* ===================================================== */
/* TOP PRODI */
/* ===================================================== */

function load_chart_prodi()
{
    $.get(

        "<?= base_url('dir/dashboard_jurnal/top_prodi') ?>",

        function(res){

            let categories = [];
            let series = [];

            $.each(res, function(i, item){

                categories.push(item.kelas);

                series.push(parseInt(item.total));

            });

            var options = {

                chart: {
                    type: 'bar',
                    height: 350
                },

                plotOptions: {
                    bar: {
                        horizontal: true
                    }
                },

                dataLabels: {
                    enabled: true
                },

                series: [{
                    name: 'Jumlah Akses',
                    data: series
                }],

                xaxis: {
                    categories: categories,

                    title: {
                        text: 'Jumlah Akses'
                    }
                },

                yaxis: {
                    title: {
                        text: 'Program Studi'
                    }
                }

            };

            let chart = new ApexCharts(
                document.querySelector("#chart_prodi"),
                options
            );

            chart.render();

        },

        'json'

    );
}

/* ===================================================== */
/* DISTRIBUSI AKTIVITAS */
/* ===================================================== */

function load_chart_aktivitas()
{
    $.get(

        "<?= base_url('dir/dashboard_jurnal/aktivitas_distribution') ?>",

        function(res){

            let labels = [];
            let series = [];

            $.each(res, function(i, item){

                labels.push(item.jenis_aktivitas);

                series.push(parseInt(item.total));

            });

            var options = {

                chart: {

                    type: 'pie',

                    height: 350,

                    toolbar: {
                        show: true
                    }

                },

                labels: labels,

                series: series,
                
                legend: {
                    position: 'bottom'
                },

                dataLabels: {
                    enabled: true
                }

            };

            let chart = new ApexCharts(
                document.querySelector("#chart_aktivitas"),
                options
            );

            chart.render();

        },

        'json'

    );
}

</script>