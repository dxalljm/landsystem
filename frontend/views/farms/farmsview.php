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
/* @var $this yii\web\View */
/* @var $model app\models\farms */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'farms'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['farmsindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="farms-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	 <?= Html::a('添加', ['farmscreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['farmsupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['farmsdelete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除这项吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <table class="table table-striped table-bordered table-hover table-condensed">
		<tr>
			<td width=15% align='right'>农场名称</td>
			<td align='left'><?= $model->farmname; ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>农场位置</td>
			<td align='left'><?= $model->address?></td>
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
			<td width=15% align='right'>面积</td>
			<td align='left'><?= $model->measure ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>宗地</td>
			<td align='left'> 
			<?php 
			$zongdiarr = explode('、',$model->zongdi);
			foreach($zongdiarr as $zongdi){
				$zongdiinfo = Parcel::find()->where(['unifiedserialnumber'=>$zongdi])->one();
				eval($content = '  宗地号：'.$zongdiinfo->unifiedserialnumber."<br>".'土壤类型：'.$zongdiinfo->agrotype."<br>".'  含石量：'.$zongdiinfo->stonecontent."<br>".'  毛面积：'.$zongdiinfo->grossarea);
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
      </td>
		</tr>
		<tr>
			<td width=15% align='right'>合作社</td><?php if($model->cooperative_id !== NULL) $cooperative = Cooperative::findOne($model->cooperative_id); else $cooperative = NULL;?>
			<td align='left' ><div >
			    <div class="panel-heading">
			      <h4 class="panel-title">
			        <a data-toggle="collapse" data-parent="#accordion" 
			          href="#collapseOne">
			          <?php if($cooperative !== NULL) echo $cooperative->cooperativename;?>
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
				<tr>
					<td align='center'><?php if($cooperative !== NULL) echo $cooperative->cooperativename ?></td>
					<td align='center'><?php if($cooperative !== NULL) echo Cooperativetype::findOne($cooperative->cooperativetype)['typename'] ?></td>
					<td align='center'><?php if($cooperative !== NULL) echo $cooperative->directorname ?></td>
					<td align='center'><?php if($cooperative !== NULL) echo $cooperative->peoples ?></td>
				</tr>
			</table>
			      </div>
			    </div>
			  </div></td>
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
			<td width=15% align='right'>地星调查员</td>
			<td align='left'><?= $model->investigator ?></td>
		</tr>
		<tr>
			<td width=15% align='right'>农场法人签字</td>
			<td align='left'><?= $model->farmersign ?></td>
		</tr>
	</table>
</div>
<?php $this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>

   <script>$(function () 
      { $("[data-toggle='popover']").popover();
      });
   </script>
