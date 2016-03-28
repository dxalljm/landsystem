<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ManagementArea;
use app\models\Cooperative;
use yii\grid\GridView; 
use yii\web\View;
use app\models\Cooperativetype;
use app\models\Parcel;
use app\models\Lease;
/* @var $this yii\web\View */
/* @var $model app\models\farms */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'farms'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['farmsindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="farms-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">
<?= Html::a('添加', ['farmscreate'], ['class' => 'btn btn-success']) ?>
    <table class="table table-bordered table-hover">
		<tr>
			<td width=15% align='right'>农场名称</td>
			<td align='left'><?= $model->farmname; ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>承包人姓名</td>
			<td align='left'><?= $model->farmername?></td>
		</tr>
		<tr>
			<td width=15% align='right'>身份证号</td>
			<td align='left'><?= $model->cardid?></td>
		</tr>
			<tr>
			<td width=15% align='right'>电话号码</td>
			<td align='left'><?= $model->telephone ?></td>
		</tr>
		<tr>
		<tr>
			<td width=15% align='right'>农场位置</td>
			<td align='left'><?= $model->address?></td>
		</tr>
		<tr>
			<td width=15% align='right'>地理坐标</td>
			<td align='left'><?= $model->longitude.'&nbsp;&nbsp;&nbsp;&nbsp;'.$model->latitude?></td>
		</tr>
		<tr>
			<td width=15% align='right'>管理区</td>
			<td align='left'><?= ManagementArea::findOne($model->management_area)['areaname'] ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>审批年度</td>
			<td align='left'><?= $model->spyear ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>合同号</td>
			<td align='left'><?= $model->contractnumber ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>承包年限</td>
			<td align='left'><?= '自&nbsp;'.$model->begindate.'&nbsp;至&nbsp;'.$model->enddate.'&nbsp;止' ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>面积</td>
			<td align='left'><?= $model->measure ?>亩</td>
		</tr>
		<tr>
			<td width=15% align='right'>宗地</td>
			<td align='left'> 
			<?php 
			if(!empty($model->zongdi)) {
			$zongdiarr = explode('、',$model->zongdi);
			foreach($zongdiarr as $zongdi){
				$zongdiinfo = Parcel::find()->where(['unifiedserialnumber'=>Lease::getZongdi($zongdi)])->one();
// 				eval($content = '  宗地号：'.$zongdiinfo->unifiedserialnumber."<br>".'土壤类型：'.$zongdiinfo->agrotype."<br>".'  含石量：'.$zongdiinfo->stonecontent."<br>".'  毛面积：'.$zongdiinfo->grossarea);
				?>
				<div class="btn-group">
				<div class="btn dropdown-toggle" 
			      data-toggle="dropdown" data-trigger="hover">
			    	  <?= $zongdi?> <span class="caret"></span>
			   </div>
			   <ul class="dropdown-menu" role="menu">
			      <li><a href="#"><?= '  宗地号：'.$zongdiinfo->unifiedserialnumber?></a></li>
			      <li><a href="#"><?= '土壤类型：'.$zongdiinfo->agrotype?></a></li>
			      <li><a href="#"><?= '  含石量：'.$zongdiinfo->stonecontent?></a></li>
			      <li><a href="#"><?= ' 毛面积：'.$zongdiinfo->grossarea?></a></li>
			   </ul>
			   </div>
			<?php }?>
			<?= Html::a('明细','index.php?r=parcel/parcellist&zongdi='.$model->zongdi) ?>
			<?php }?>
      </td>
		</tr>
		<tr>
			<td width=15% align='right'>未明确地块</td>
			<td align='left'><?= $model->notclear?>亩</td>
		</tr>
		<tr>
			<td width=15% align='right'>未明确状态地块</td>
			<td align='left'><?= $model->notstate?>亩</td>
		</tr>
		<tr>
			<td width=15% align='right'>合作社</td>
			
				
			<td align='left' >
			
			<?php if($cooperativeoffarm) {?>
			
			<div >
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" 
			          href="#collapseOne">
			           <?php foreach($cooperativeoffarm as $cooper) {
						echo Cooperative::findOne($cooper['cooperative_id'])['cooperativename'].'&nbsp;&nbsp;&nbsp;&nbsp;';}?>
			          
			        </a>
			      </h4>
			    </div>
			    <div id="collapseOne" class="panel-collapse collapse">
			      <div class="panel-body">
			     
			        <table class="table table-striped table-bordered table-hover table-condensed">
				<tr>
					<td align='center'>合作社名称</td>
					<td align='center'>合作社类型</td>
					<td align='center'>理事长姓名</td>
					<td align='center'>入社人数</td>
				</tr>
				 <?php foreach($cooperativeoffarm as $cooper) {
					$cooperative = Cooperative::findOne($cooper['cooperative_id']);?>
				<tr>
					<td align='center'><?php if($cooperative !== NULL) echo $cooperative->cooperativename ?></td>
					<td align='center'><?php if($cooperative !== NULL) echo Cooperativetype::findOne($cooperative->cooperativetype)['typename'] ?></td>
					<td align='center'><?php if($cooperative !== NULL) echo $cooperative->directorname ?></td>
					<td align='center'><?php if($cooperative !== NULL) echo $cooperative->peoples ?></td>
				</tr><?php }?>
			</table>
			      </div>
			    </div>
			  </div><?php }?></td>
		</tr>
		<tr>
			<td width=15% align='right'>调查日期</td>
			<td align='left'><?= $model->surveydate ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>地产科签字</td>
			<td align='left'><?= $model->groundsign ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>农场法人签字</td>
			<td align='left'><?= $model->farmersign ?></td>
		</tr>
	</table>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php $this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>

   <script>$(function () 
      { $("[data-toggle='popover']").popover();
      });
   </script>
