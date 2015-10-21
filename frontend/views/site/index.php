<?php
/* @var $this yii\web\View */
namespace frontend\controllers;
use Yii;
use yii\helpers\Html;
use app\models\Farms;
use yii\helpers\Url;

$this->title = '岭南管委会';



?>
<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    <small>您所管辖的管理区:</small><?= $areaname?>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= Url::to('index.php?r=site/index')?>"><i class="fa fa-dashboard"></i> 首页</a></li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <!-- Small boxes (Stat box) -->
  <div class="row">
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-aqua">
        <script type="text/javascript">
          $(function () {
        	    $('#container').highcharts({
        	        chart: {
        	            plotBackgroundColor: null,
        	            plotBorderWidth: null,
        	            plotShadow: false
        	        },
        	        title: {
        	            text: 'Browser market shares at a specific website, 2010'
        	        },
        	        tooltip: {
        	    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        	        },
        	        plotOptions: {
        	            pie: {
        	                allowPointSelect: true,
        	                cursor: 'pointer',
        	                dataLabels: {
        	                    enabled: true,
        	                    color: '#000000',
        	                    connectorColor: '#000000',
        	                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
        	                }
        	            }
        	        },
        	        series: [{
        	            type: 'pie',
        	            name: 'Browser share',
        	            data: [
        	                ['Firefox',   45.0],
        	                ['IE',       26.8],
        	                {
        	                    name: 'Chrome',
        	                    y: 12.8,
        	                    sliced: true,
        	                    selected: true
        	                },
        	                ['Safari',    8.5],
        	                ['Opera',     6.2],
        	                ['Others',   0.7]
        	            ]
        	        }]
        	    });
        	});
          </script>
      </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-green">
        <div class="inner">
          <h3><span id="statis-area"></span><sup style="font-size: 20px">亩</sup></h3>
          <p>面积</p>
        </div>
        <div class="icon">
          <i class="fa fa-map"></i>
        </div>
        <i class="fa"></i>
      </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h4>实收：<span id="statis-real"></span><sup style="font-size: 10px">元</sup></h4>
          <h4>应收：<span id="statis-mounts"></span><sup style="font-size: 10px">元</sup></h4>
          <p>缴费情况</p>
        </div>
        <div class="icon">
          <i class="fa fa-yen"></i>
        </div>

      </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          <h3>65</h3>
          <p>Unique Visitors</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div><!-- ./col -->
  </div><!-- /.row -->
  <div>
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
  </div>

</section><!-- /.content -->

<script type="text/javascript">
var s = statis();
s.farms();
s.area();
s.payment();







$(function () {
    $('#container').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: 'Browser market shares at a specific website, 2010'
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            type: 'pie',
            name: 'Browser share',
            data: [
                ['Firefox',   45.0],
                ['IE',       26.8],
                {
                    name: 'Chrome',
                    y: 12.8,
                    sliced: true,
                    selected: true
                },
                ['Safari',    8.5],
                ['Opera',     6.2],
                ['Others',   0.7]
            ]
        }]
    });
});








</script>
