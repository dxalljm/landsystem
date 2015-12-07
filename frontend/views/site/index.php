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
$this->title = '岭南管委会';
?>
<script type="text/javascript" src="js/showhighcharts.js"></script>
<!-- Content Header (Page header) -->
<section class="content-header">
<h1>
    <b>欢迎使用岭南农业数字化信息服务管理系统</b>
  </h1>
  <h1>
    <small>您所管辖的管理区:<?= $areaname?></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?= Url::to('index.php?r=site/index')?>"><i class="fa fa-dashboard"></i> 首页</a></li>
  </ol>




<div class="row">
    <div class="col-md-6">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="username"><a href="#">您所辖管理区农场数量统计数据</a></span>
                <span class="description">所辖管理区的农场数量在所有管理区中的占比</span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Mark as read">
                  <i class="fa fa-circle-o"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
<?php //var_dump(Farms::getFarmStatistics());

?>
        		<div id="statis-farms" style="wedth: 100%; height: 362px; margin: 0 auto;" ></div>
   			 <script type="text/javascript">
   			showCombination('statis-farms','农场数量情况统计信息',<?= json_encode(Farms::getManagementArea()['areaname'])?>,'',<?= Farms::getFarmrows()?>,'个');
		</script>
            </div>
            <!-- /.box-body -->
           
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="username"><a href="#">您所辖管理区农场面积统计数据</a></span>
                <span class="description">所辖管理区的农场面积在所有管理区中的占比</span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Mark as read">
                  <i class="fa fa-circle-o"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				<div id="statis-area" style="min-width: 262px; height: 362px; margin: 0 auto"; ></div>
				<script type="text/javascript">
   			showCombination('statis-area','农场面积情况统计信息',<?= json_encode(Farms::getManagementArea()['areaname'])?>,'',<?= Farms::getFarmarea()?>,'亩');
		</script>
            </div>
        
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      
      <div class="row">
        <div class="col-md-6">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="username"><a href="#">您所辖管理区缴费情况统计数据</a></span>
                <span class="description">所辖管理区的农场数量在所有管理区中的占比</span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Mark as read">
                  <i class="fa fa-circle-o"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              
<?php 
 var_dump(Plantingstructure::getPlantingstructure());
?>
        		<div id="collection" style="wedth: 100%; height: 362px; margin: 0 auto;" ></div>
   			 <script type="text/javascript">
   			showStacked('collection','缴费情况统计信息',<?= json_encode(Farms::getManagementArea()['areaname'])?>,'',<?= Collection::getCollection()?>,'元');
		</script>
            </div>
            <!-- /.box-body -->
           
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-6">
          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="user-block">
                <span class="username"><a href="#">您所辖管理区农场面积统计数据</a></span>
                <span class="description">所辖管理区的农场面积在所有管理区中的占比</span>
              </div>
              <!-- /.user-block -->
              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-toggle="tooltip" title="Mark as read">
                  <i class="fa fa-circle-o"></i></button>
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body">
				<div id="plantingstructure" style="min-width: 262px; height: 362px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				showColumn('plantingstructure','农场面积情况统计信息',<?= json_encode(Farms::getManagementArea()['areaname'])?>,'',<?= Plantingstructure::getPlantingstructure()?>,'种植面积','亩');
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
