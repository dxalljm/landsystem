<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ManagementArea;
use app\models\Cooperative;
use frontend\helpers\grid\GridView; 
use yii\web\View;
use app\models\Cooperativetype;
use app\models\Parcel;
use app\models\Farms;
use app\models\Loan;
use app\models\Lockedinfo;
use app\models\Auditprocess;
use app\models\Reviewprocess;
use app\models\Session;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\farms */

?>
<div class="farms-view">

	<p>

        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    </p>
    <div >
        <div class="col-md-3">

        </div>
        <?php if(!Farms::getLocked($_GET['farms_id'])) {?>
        <!-- /.col -->
        <div class="col-md-6">
          <!-- Widget: user widget style 1 -->
          <div class="card">
            <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="card-header card-header-text text-center" data-background-color="blue" style="width:96%">
              <h1 class="text-center">部分转让</h1>
<!--              <h4 class="widget-user-desc">Contract assignment</h4>-->
            </div>

            <div class="box-footer">
              <div class="row">
                <div class="col-sm-6 border-right">
                  <div class="description-block">
                      <?= html::a('<h1>新建</h1><h5 class="widget-user-desc text-black">新建宜农林地承包合同</h5>',Url::to(['farms/farmssplit','farms_id'=>$farms_id]),['class'=>'btn btn-block btn-info btn-lg'])?>

                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                  <div class="description-block">
                      <?= html::a('<h1>合并</h1><h5 class="widget-user-desc text-black">与现有宜农林地承包合同合并</h5>',Url::to(['farms/farmsttpozongdi','farms_id'=>$farms_id]),['class'=>'btn btn-block btn-info btn-lg'])?>

                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
                <?php } else {?>
                    <div class="col-md-6">
                        <!-- Widget: user widget style 1 -->
                        <div class="box box-widget widget-user">
                            <div class="widget-user-header bg-red text-center">
                                <h1><?= Lockedinfo::find()->where(['farms_id'=>$_GET['farms_id']])->one()['lockedcontent']?></h1>
                            </div>
                        </div>
                    </div>
                <?php }?>
                </div>
              </div>
</div>

