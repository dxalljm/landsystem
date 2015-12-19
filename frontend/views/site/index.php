<?php
/* @var $this yii\web\View */
namespace frontend\controllers;
use Yii;
use yii\helpers\Html;
use app\models\Farms;
use yii\helpers\Url;
use app\models\User;
use app\models\Department;
use app\models\ManagementArea;
use app\models\Collection;
use app\models\Plantingstructure;
use app\models\Cache;
$this->title = '岭南管委会';
?>
<script type="text/javascript" src="js/showhighcharts.js"></script>
<!-- Content Header (Page header) -->
<section class="content-header">
  <ol class="breadcrumb">
    <li><a href="<?= Url::to('index.php?r=site/index')?>"><i class="fa fa-dashboard"></i> 首页</a></li>
  </ol>

<?php //var_dump(Farms::getFarmarea());?>


<div class="row">
        <div class="col-md-4">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="username"><a href="#">您所辖管理区农场面积统计数据</a></span>
                <span class="description">所辖管理区的农场面积以及户数在所有管理区中的占比，单位（万亩）</span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>

              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
           
				<div id="statis-area" style="min-width: 262px; height: 300px; margin: 0 auto"; ></div>
				<script type="text/javascript">
   			showCombination('statis-area','面积：<?php echo Farms::totalArea()?> 农场户数：<?php echo Farms::totalNum()?>',<?php echo json_encode(Farms::getManagementArea('small')['areaname'])?>,'',<?php echo Cache::getCache(\Yii::$app->getUser()->getId())['farmscache']?>,'万亩');
		</script>
            </div>
        
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      
        <div class="col-md-4">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="username"><a href="#">您所辖管理区缴费情况统计数据</a></span>
                <span class="description">单位（万元）</span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>

              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <?php //var_dump(Collection::getCollection());?>
        		<div id="collection" style="min-width: 100%; height: 300px; margin: 0 auto;" ></div>
   			 <script type="text/javascript">
   			showStacked('collection','应收：<?php echo Collection::totalAmounts()?> 实收：<?php echo Collection::totalReal()?>',<?php echo json_encode(Farms::getManagementArea('small')['areaname'])?>,'',<?php echo Cache::getCache(\Yii::$app->getUser()->getId())['collectioncache']?>,'万元');
		</script>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-4">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="username"><a href="#">您所辖管理区作物统计数据</a></span>
                <span class="description">所辖管理区的各农作物种植面积</span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>

              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <?php //var_dump(Plantingstructure::getPlantingstructure());?>
            <div class="box-body">
				<div id="plantingstructure" style="min-width: 262px; height: 300px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				showStacked('plantingstructure','作物种植面积统计',<?= json_encode(Plantingstructure::getPlantname())?>,'',<?= Cache::getCache(\Yii::$app->getUser()->getId())['plantingstructurecache']?>,'亩');
				//showStacked('collection','应收：<?php //echo Collection::totalAmounts()?> 实收：<?php //echo Collection::totalReal()?>',<?php //echo json_encode(Farms::getManagementArea('small')['areaname'])?>,'',<?php //echo Collection::getCollection()?>,'万元');
		</script>
            </div>
        
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
</section>
<!-- Main content -->

<script type="text/javascript">
// var s = statis();
// s.farms();
// s.area();
// s.payment();

</script>
