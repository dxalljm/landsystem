<?php
namespace frontend\controllers;

use app\models\Photograph;
use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ManagementArea;
use app\models\Cooperative;
use frontend\helpers\grid\GridView; 
use yii\web\View;
use app\models\Cooperativetype;
use app\models\Parcel;
use app\models\Lease;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\farms */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'farms'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['farmsindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="farms-view">
  <?php
//  var_dump($farmer);exit;
  ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
<table class="table table-bordered table-hover">
  <tr>
      <td align='right'><table
		class="table table-bordered table-hover">
        <tr>
          <td width="15%" align='right'>农场名称</td>
          <td align='left'><strong>
            <?= $model->farmname; ?>
          </strong></td>
          <td align='right'>承包人姓名</td>
          <td align='left'><strong>
            <?= $model->farmername?>
          </strong></td>
          <td align='right'>身份证号</td>
          <td colspan="3" align='left'><strong>
            <?= $model->cardid?>
          </strong></td>
        </tr>
        <tr>
          <td width="15%" align='right'>电话号码</td>
          <td align='left'><strong>
            <?= $model->telephone ?>
          </strong></td>
          <td align='right'>农场位置</td>
          <td colspan="5" align='left'><strong>
            <?= $model->address?>
          </strong></td>
        </tr>
        <tr>
          <td width="15%" align='right'>管理区</td>
          <td align='left'><strong>
            <?= ManagementArea::findOne($model->management_area)['areaname'] ?>
          </strong></td>
          <td align='right'>合同号</td>
          <td align='left'><strong>
            <?= $model->contractnumber ?>
          </strong></td>
          <td align='right'>审批年度</td>
          <td align='left'><strong>
            <?= $model->spyear ?>
          </strong></td>
          <td align='right'>合同领取日期</td>
          <td align='left'><strong>
            <?php if($model->surveydate) echo date('Y-m-d',$model->surveydate);?>
          </strong></td>
        </tr>
        <tr>
          <td width="15%" align='right'>承包年限</td>
          <?php $model->begindate = '2010-09-13'?>
          <td align='center'>自</td>
          <td align='center'><strong>
            <?= $model->begindate?>
          </strong></td>
          <td align='center'>至</td>
          <?php $model->enddate = '2025-09-13'?>
          <td align='center'><strong>
            <?= $model->enddate?>
          </strong></td>
          <td align='center'>止</td>
          <td align='right'>地理坐标</td>
          <td align='center'><strong>
            <?= $model->longitude.'&nbsp;&nbsp;&nbsp;&nbsp;'.$model->latitude?>
          </strong></td>
        </tr>
        <tr>
          <td width="15%" align='right'>宗地</td>
          <?= html::hiddenInput('tempzongdi','',['id'=>'temp-zongdi'])?>
          <td colspan="7" align='left'><strong>
            <?php 
			if(!empty($model->zongdi)) {
			$zongdiarr = explode('、',$model->zongdi);
			foreach($zongdiarr as $zongdi){
				$zongdiinfo = Parcel::find()->where(['unifiedserialnumber'=>Lease::getZongdi($zongdi)])->one();
// 				eval($content = '  宗地号：'.$zongdiinfo->unifiedserialnumber."<br>".'土壤类型：'.$zongdiinfo->agrotype."<br>".'  含石量：'.$zongdiinfo->stonecontent."<br>".'  毛面积：'.$zongdiinfo->grossarea);
				?>
            </strong>
            <div class="btn-group">
              <div class="btn dropdown-toggle" 
			      data-toggle="dropdown" data-trigger="hover">
                <strong>
                  <?= $zongdi?>
                  </strong></div>
              <ul class="dropdown-menu" role="menu">
                <li><strong><a href="#">
                  <?= '  宗地号：'.$zongdiinfo->unifiedserialnumber?>
                  </a></strong></li>
                <li><strong><a href="#">
                  <?= '土壤类型：'.$zongdiinfo->agrotype?>
                  </a></strong></li>
                <li><strong><a href="#">
                  <?= '  含石量：'.$zongdiinfo->stonecontent?>
                  </a></strong></li>
                <li><strong><a href="#">
                  <?= ' 毛面积：'.$zongdiinfo->grossarea?>
                  </a></strong></li>
                </ul>
            </div>
            <strong>
            <?php }?>
            <?= Html::a('明细','index.php?r=parcel/parcellist&zongdi='.$model->zongdi) ?>
            <?php }?>
            </strong></td>
        </tr>
        <tr>
          <td align='right'>合同面积</td>
          <td align='left'><strong>
            <?= $model->contractarea ?>
            亩</strong></td>
          <td align='right'>宗地面积</td>
          <td align='left'><strong>
            <?= $model->measure ?>
            亩</strong></td>
          <td align='right'>未明确地块面积</td>
          <td align='left'><strong>
            <?= $model->notclear?>
            亩</strong></td>
          <td align='right'><?= $model->notstateinfo?></td>
          <td align='left'><strong>
            <?= $model->notstate?>
          </strong></td>
        </tr>
        <tr>
          <td width="15%" align='right'>地产科签字</td>
          <td align='left'><strong>
            <?= $model->groundsign?>
          </strong></td>
          <td align='right'>农场法人签字</td>
          <td align='left'><strong>
            <?=$model->farmersign?>
          </strong></td>
          <td align='right'>状态</td>
          <td colspan="3"><strong>
            <?= $model->state ? '正常' : '销户'?>
          </strong></td>
        </tr>
        <tr>
          <td width="15%" align='right'>备注</td>
          <td colspan="7" align='left'><strong>
            <?= $model->remarks?>
          </strong></td>
        </tr>
        <tr>
          <td align='right'>&nbsp;</td>
          <td colspan="7" align='left'>&nbsp;</td>
        </tr>

        <tr>
          <td align='right'>法人近照<br><?= Html::a('导出证件',Url::to(['photograph/photographexplode','farms_id'=>$model->id]),['class' => 'btn btn-primary btn-xs','id'=>'explodepic']);?></td>
          <td colspan="2" align='left'><?php if($farmer) echo Html::img('http://192.168.1.10/'.$farmer->photo,['width'=>'180px','height'=>'200px','id'=>'photo']); ?></td>
          <td align='left'>身份证扫描件</td>
          <td colspan="2" align='left'><?php if($farmer) echo '&nbsp;'.Html::img('http://192.168.1.10/'.$farmer->cardpic,['width'=>'400px','height'=>'220px','id'=>'cardpic']); ?></td>
          <td colspan="2" align='left'><?php if($farmer) echo '&nbsp;'.Html::img('http://192.168.1.10/'.$farmer->cardpicback,['width'=>'400px','height'=>'220px','id'=>'cardpicback']); ?></td>
          </tr>
      </table></td>
    </tr>
  </table>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php
//Photograph::batchDownload($fileInfo);
?>
<?php $this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>

   <script>
     $(function ()
      { $("[data-toggle='popover']").popover();
      });

   </script>
