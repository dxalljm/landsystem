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
use app\models\MenuToUser;
use app\models\Projectapplication;
$this->title = '岭南管委会';
?>

<script type="text/javascript" src="js/showEcharts.js"></script>
<script type="text/javascript" src="vendor/bower/echarts/build/dist/echarts.js"></script>
<script type="text/javascript" src="vendor/bower/echarts/build/dist/echarts.min.js"></script>
<!-- Content Header (Page header) -->
<section class="content-header">
  <ol class="breadcrumb">
    <li><a href="<?= Url::to('index.php?r=site/index')?>"><i class="fa fa-dashboard"></i> 首页</a></li>
  </ol>

  <?php $plate = explode(',', MenuToUser::find()->where(['role_id'=>User::getItemname()])->one()['plate']);?>

<div class="row">
        <?php if(count($plate) == 2) {?>
        <div class="col-md-6">
        <?php } elseif(count($plate == 3)) {?>
        <div class="col-md-4">
        <?php } else {?>
         <div class="box box-default color-palette-box">        
        <?php }?>
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="username"><a href="#">您所辖管理区农场面积统计数据</a></span>
                <span class="description navbar-left"><?= Cache::getCache(\Yii::$app->getUser()->getId())['farmstitle']?></span>
                <span class="description navbar-right">单位（万亩）|（户）</span>
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
				<div id="statis-area" style="width: 100%; height: 300px; margin: -50 auto"; ></div>
				<?php //var_dump(Cache::getCache(\Yii::$app->getUser()->getId())['farmscache']);?>
				<script type="text/javascript">
   			showBar('statis-area',['面积','数量'],<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= Cache::getCache(\Yii::$app->getUser()->getId())['farmscache']?>,<?= json_encode(['面积'=>'万亩','数量'=>'户'])?>);
		</script>

            </div>
        
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
    <?php if(in_array(24, $plate)) {?>  
        <?php if(count($plate) == 2) {?>
        <div class="col-md-6">
        <?php } elseif(count($plate == 3)) {?>
        <div class="col-md-4">
        <?php }?>
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="username"><a href="#">您所辖管理区缴费情况统计数据</a></span>
                <span class="description navbar-left"><?= Cache::getCache(\Yii::$app->getUser()->getId())['collectiontitle'];?></span>
                <span class="description navbar-right">单位（万元）</span>
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

              <div id="collection" style="width:100%;height:300px"></div>
<script type="text/javascript">showShadow('collection',['应收金额','实收金额'],<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?php echo Cache::getCache(\Yii::$app->getUser()->getId())['collectioncache']?>,'万元')</script>


            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <?php }
        if(in_array(21, $plate)) {
        ?>
        <?php if(count($plate) == 2) {?>
        <div class="col-md-6">
        <?php } elseif(count($plate == 3)) {?>
        <div class="col-md-4">
        <?php } ?>
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="username"><a href="#">您所辖管理区作物统计数据</a></span>
                <span class="description navbar-left"><?= Cache::getCache(\Yii::$app->getUser()->getId())['plantingstructuretitle']?></span>
                <span class="description navbar-right">单位（万亩）</span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>

              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <?php
//            var_dump(Cache::getCache(\Yii::$app->getUser()->getId())['plantingstructurecache']);
            //$string = '{"result":[{"color":"#bdfdc9","type":"column","name":"\u4f5c\u7269", "dataLabels": { "enabled": false} ,"data":[0.07,0.16,0.03,0.06,0,0.02]},{"color":"#02c927","type":"column","name":"\u826f\u79cd", "dataLabels": { "color": "#000"}, "data":[0,0,0.01,0,0,0]},{"type":"pie","name":"wubaiqing-test", "center": [200, 20], "size": 50, "showInLegend": false, dataLabels: {"enabled": false}, "data":[{"name":"\u9ec4\u82aa","y":207.1}, {"name":"\u9ec4\u82aa2","y":27.1}]}]}';
            ?>
            <div class="box-body">
				<div id="plantingstructure" style="width: 100%; height: 300px; margin: 0 auto"; ></div>
				<script type="text/javascript">
//				showStacked('plantingstructure','',<?//= Cache::getCache(\Yii::$app->getUser()->getId())['plantingstructurecategories']?>//,'',<?//= Cache::getCache(\Yii::$app->getUser()->getId())['plantingstructurecache']?>//,'万亩');
				showAllShadow('plantingstructure',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= Cache::getCache(\Yii::$app->getUser()->getId())['plantingstructurecategories']?>,<?= Cache::getCache(\Yii::$app->getUser()->getId())['plantingstructurecache']?>,'万亩');
				//showStacked('collection','应收：<?php //echo Collection::totalAmounts()?> 实收：<?php //echo Collection::totalReal()?>',<?php //echo json_encode(Farms::getManagementArea('small')['areaname'])?>,'',<?php //echo Collection::getCollection()?>,'万元');
		</script>
            </div>
        
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <?php }?>
<!--       </div> -->
<!-- <div class="row"> -->
<?php if(in_array(21, $plate)) {?>
        <?php if(count($plate) == 2) {?>
        <div class="col-md-6">
        <?php } elseif(count($plate == 3)) {?>
        <div class="col-md-4">
        <?php } ?>
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="username"><a href="#">您所辖管理区农产品产量情况统计数据</a></span>
                <span class="description navbar-left"><?= Cache::getCache(\Yii::$app->getUser()->getId())['plantinputproducttitle']?></span>
                <span class="description navbar-right">单位（万斤）</span>
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
				<div id="plantinputproduct" style="width: 100%; height: 300px; margin: 0 auto"; ></div>
				<?php //var_dump(Plantingstructure::getPlantname());?>
				<script type="text/javascript">
				showAllShadow('plantinputproduct',<?= json_encode(Plantingstructure::getPlantname())?>,<?= Cache::getCache(\Yii::$app->getUser()->getId())['plantinputproductcategories']?>,<?= Cache::getCache(\Yii::$app->getUser()->getId())['plantinputproductcache']?>,'万斤');
		</script>
            </div>
        
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
<?php }?>  <?php if(in_array(23, $plate)) {?>
        <?php if(count($plate) == 2) {?>
        <div class="col-md-6">
        <?php } elseif(count($plate == 3)) {?>
        <div class="col-md-4">
        <?php } ?>
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="username"><a href="#">您所辖管理区惠农政策完成情况统计数据</a></span>
                <span class="description navbar-right">单位（万元）</span>
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
        		<div id="huinong-info" style="width: 100%; height: 300px; margin: 0 auto;" ></div>
   			 <script type="text/javascript">
   			showShadow('huinong-info',['应发','实发'],<?= Cache::getCache(\Yii::$app->getUser()->getId())['huinongcategories']?>,<?= Cache::getCache(\Yii::$app->getUser()->getId())['huinongcache']?>,'万元');
		</script>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <?php }?><?php if(in_array(27, $plate)) {?>
        <?php if(count($plate) == 2) {?>
        <div class="col-md-6">
        <?php } elseif(count($plate == 3)) {?>
        <div class="col-md-4">
        <?php } ?>
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="username"><a href="#">您所辖管理区项目统计数据</a></span>
                <span class="description navbar-right">单位（个）</span>
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
            <?php //var_dump(Projectapplication::getTypenamelist());exit;?>
				<div id="project" style="width: 100%; height: 300px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				showAllShadowProject('project',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= Cache::getCache(\Yii::$app->getUser()->getId())['projectapplicationcategories']?>,<?= Cache::getCache(\Yii::$app->getUser()->getId())['projectapplicationcache']?>,<?= json_encode(Projectapplication::getTypenamelist()['unit'])?>);

		</script>
            </div>
        
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <?php }?>
      </div>
<?= Farms::showEightPlantmenu()?>
</section>
<!-- Main content -->

<script type="text/javascript">
// var s = statis();
// s.farms();
// s.area();
// s.payment();

</script>
