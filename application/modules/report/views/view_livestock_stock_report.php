<!--sidebar end-->
<!--main content start-->
<section id="main-content">
    <section class="wrapper site-min-height">
        <!-- page start-->
        <section class="panel">
            <header class="panel-heading bg-info">
                <i class="fa-solid fa-chart-pie"></i> <?= lang('livestock_stock_reports'); ?>
                <button class="export" onclick="javascript:window.print();"><i class="fa-solid fa-print"></i> <?php echo lang('print'); ?></button>
            </header>
            <div class="panel-body">
                <div class="adv-table editable-table">
                    <!-- ====================== Stock Report ====================== -->
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="new__cards card__box bg-primary-light">
                                <div class="col-xs-8">
                                    <p><?php echo lang('total_purchased'); ?> (<?= $settings->unit; ?>)</p>
                                    <h3><?php $purQ = $this->report_model->getTotalPurchaseSubTotal('purv_quantity');
                                        if ($purQ) {
                                            echo $purQ;
                                        } else {
                                            echo 0;
                                        }
                                        ?></h3>
                                </div>
                                <div class="col-xs-4">
                                    <div class="text-right">
                                        <ul class="social__icon">
                                            <li class="icon">
                                                <span><i class="fa-solid fa-cart-shopping"></i></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="new__cards card__box bg-warning-light">
                                <div class="col-xs-8">
                                    <p><?= lang('total_sale'); ?>(<?= $settings->unit; ?>)</p>

                                    <h3><?php $saleQ = $this->report_model->getTotalLivestockSaleQuantity('lssv_quantity');
                                        if ($saleQ) {
                                            echo $saleQ;
                                        } else {
                                            echo 0;
                                        }
                                        ?></h3>

                                </div>
                                <div class="col-xs-4">
                                    <div class="text-right">
                                        <ul class="social__icon">
                                            <li class="icon">
                                                <span><i class="fa-solid fa-scale-balanced"></i></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="new__cards card__box bg-danger-light">
                                <div class="col-xs-8">
                                    <p><?= lang('total'); ?> <?= lang('death'); ?>(<?= $settings->unit; ?>)</p>
                                    <h3><?php $deathQ = $this->report_model->getTotalDeath('ld_death_quantity');
                                        if ($deathQ) {
                                            echo $deathQ;
                                        } else {
                                            echo 0;
                                        }
                                        ?>
                                    </h3>
                                </div>
                                <div class="col-xs-4">
                                    <div class="text-right">
                                        <ul class="social__icon">
                                            <li class="icon">
                                                <span><i class="fa-solid fa-house-circle-xmark"></i></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-6">
                            <div class="new__cards card__box bg-success-light">
                                <div class="col-xs-8">
                                    <p><?= lang('stock_quantity'); ?>(<?= $settings->unit; ?>)</p>
                                    <h3><?php
                                        $inStock = $purQ - $saleQ - $deathQ;
                                        if ($inStock) {
                                            echo $inStock;
                                        } else {
                                            echo 0;
                                        }
                                        ?></h3>

                                </div>
                                <div class="col-xs-4">
                                    <div class="text-right">
                                        <ul class="social__icon">
                                            <li class="icon">
                                                <span><i class="fa-solid fa-house-flag"></i></span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ============================ JS Chart ============================ -->
                    <div class="row text-center chart__position">
                        <div class="col-xs-12">
                            <!-- JS Pie Chart -->
                            <!-- <canvas id="myChart" style="width:100%;max-width:600px"></canvas> -->
                            <div id="chart"></div>
                            <!-- /.JS Pie Chart -->
                        </div>
                        <!-- <div class="col-xs-6"> -->
                        <!-- <canvas id="myChart1" style="width:100%;max-width:600px"></canvas> -->
                        <!-- </div> -->
                    </div>
                    <hr>
                    <!-- <div class="row">
                        <div class="col-xs-12">
                            <div id="chart"></div>
                        </div>
                    </div> -->
                    <!-- ============================ JS Chart ============================ -->

                </div>
            </div>
        </section>
        <!-- page end-->
    </section>
</section>
<!--main content end-->
<!--footer start-->





<script src="common/assets/apexChart/apexchart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<script src="<?php echo base_url('common/js/jquery-1.11.1.min.js'); ?>"></script>
<script>
    var xValues = ["<?= lang('purchase'); ?>", "<?= lang('sale'); ?>", "<?= lang('death'); ?>", "<?= lang('stock'); ?>"];
    var yValues = [<?php echo $purQ; ?>, <?php echo $saleQ; ?>, <?php echo $deathQ; ?>, <?php echo $inStock; ?>];
    var barColors = [
        "#008000",
        "#e8c3b9",
        "#b91d47",
        "#00FFFF",
    ];

    new Chart("myChart", {
        type: "doughnut",
        // type: "pie",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: barColors,
                data: yValues
            }]
        },
        options: {
            title: {
                display: true,
                text: "<?= lang('livestock_stock_reports'); ?>"
            }
        }
    });
</script>


<script>
    var xValues = ["<?= lang('purchase'); ?>", "<?= lang('sale'); ?>", "<?= lang('death'); ?>", "<?= lang('stock'); ?>"];
    var yValues = [<?php echo $purQ; ?>, <?php echo $saleQ; ?>, <?php echo $deathQ; ?>, <?php echo $inStock; ?>];
    var barColors = [
        "#008000",
        "#e8c3b9",
        "#b91d47",
        "#00FFFF",
    ];

    new Chart("myChart1", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: barColors,
                data: yValues
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: "<?= lang('livestock_stock_reports'); ?>"
            }
        }
    });
</script>




<script>
    var color1 = ['#008FFB', '#FEB019', '#FF4560', '#00E396'];
    var options = {
        series: [{
            name: [],
            data: [<?php echo $purQ; ?>, <?php echo $saleQ; ?>, <?php echo $deathQ; ?>, <?php echo $inStock; ?>]
        }],
        chart: {
            type: 'bar',
            height: 350
        },
        plotOptions: {
            bar: {
                borderRadius: 10,
                horizontal: false,
                columnWidth: '55%',
                endingShape: 'rounded'
            },
        },
        dataLabels: {
            enabled: false,
        },
        stroke: {
            show: true,
            width: 2,
            colors: ['transparent']
        },
        xaxis: {
            categories: ['<?= lang('purchase'); ?>', '<?= lang('sale'); ?>', '<?= lang('death'); ?>', '<?= lang('stock'); ?>'],
            labels: {
                style: {
                    colors: color1,
                    fontSize: '12px'
                }
            },
            tickPlacement: 'on'
        },
        yaxis: {
            title: {
                text: ''
            }
        },
        fill: {
            opacity: 1
        },
        tooltip: {
            y: {
                formatter: function(val) {
                    return "" + val + " <?= $settings->unit; ?>"
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();
</script>