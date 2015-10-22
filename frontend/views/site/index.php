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
    <div class="col-lg-6 col-xs-6">

        <div id="statis-farms" style="min-width: 262px; height: 200px; margin: 0 auto"></div>
    </div>

    <div class="col-lg-6 col-xs-6">

    </div>

  </div>
  <div>
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    <div id="container2" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
  </div>

</section><!-- /.content -->

<script type="text/javascript">
var s = statis();
s.farms();
s.area();
s.payment();

</script>
