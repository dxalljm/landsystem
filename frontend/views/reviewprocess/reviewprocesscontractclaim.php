<?php

namespace frontend\controllers;
use app\models\Farmer;
use dosamigos\datetimepicker\DateTimePicker;
use Yii;
use yii\helpers\Url;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Reviewprocess;
use app\models\Auditprocess;
use app\models\Processname;
use app\models\User;
use app\models\ManagementArea;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ReviewprocessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'reviewprocess';
$this->title = Tables::find ()->where ( [ 
		'tablename' => $this->title 
] )->one ()['Ctablename'];
$this->params ['breadcrumbs'] [] = $this->title;
?>
<div class="reviewprocess-index">

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3 class="box-title">合同领取</h3>
					</div>
					<div class="box-body">
						<?= GridView::widget([
							'dataProvider' => $dataProvider,
							'filterModel' => $searchModel,
							'columns' => [
								['class' => 'yii\grid\SerialColumn'],
								[
									'attribute' => 'management_area',
									'headerOptions' => ['width' => '200'],
									'value'=> function($model) {
										return ManagementArea::getAreanameOne($model->management_area);
									},
									'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
								],
								[
									'label' => '农场名称',
									'attribute' => 'farmname',
									'value' => function($model) {
										return Farms::find()->where(['id'=>$model->newfarms_id])->one()['farmname'];
									}
								],
								[
									'label' => '法人',
									'attribute' => 'farmername',
									'value' => function($model) {
										return Farms::find()->where(['id'=>$model->newfarms_id])->one()['farmername'];
									}
								],
								[
									'label' => '合同号',
//									'attribute' => 'contractnumber',
									'value' => function($model) {
										return Farms::find()->where(['id'=>$model->newfarms_id])->one()['contractnumber'];
									}
								],
								[
									'label' => '面积',
									'attribute' => 'contractarea',
									'value' => function($model) {
										return Farms::find()->where(['id'=>$model->newfarms_id])->one()['contractarea'];
									}
								],
								[
									'label' => '状态',
//									'attribute' => 'state',
									'format' => 'raw',
									'value' => function($model) {
										return Reviewprocess::state($model->state);
									}
								],
								[
									'label' => '领取日期',
									'value' => function($model) {
										if($model->receivetime)
											return date('Y-m-d',$model->receivetime);
									}
								],
									// 'breedtype_id',

								[
									'format' => 'raw',
									'value' => function($model) {
										$html =  html::a('电子信息采集',['photograph/photographcontractclaim','farms_id'=>$model->newfarms_id],['class'=>'btn btn-primary','id'=>'claim']);
										$html .= '&nbsp;&nbsp;';
										if($model->state == 7) {
											$html.= html::a('领取','#',['class'=>'btn btn-primary','disabled'=>true]);
										} else {
											$html .= html::a('领取', '#', ['class' => 'btn btn-primary', 'onClick' => 'receive(' . $model->id . ')']);
										}
										return $html;
									}
								],
						],
						]); ?>
					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<?= Html::hiddenInput('nowid','',['id'=>'now-id'])?>
<div id="dialog" title="确定已经领取了吗?">
	<table width=100%>
		<tr>
			<td align="right">领取日期：</td>
			<td><?= DateTimePicker::widget([
					'id' => 'receiveTime',
					'name' => 'receivetime',//当没有设置model时和attribute时必须设置name
					'value' => date('Y-m-d'),
					'language' => 'zh-CN',
					'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
//					'size' => 'ms',
					'clientOptions' => [
						'autoclose' => true,
						'startView' => 2,
						'minView' => 3,
						'format' => 'yyyy-mm-dd',
						'todayBtn' => true
					]
				]);?> </td>
		</tr>
	</table>
</div>
<script>
	$( "#dialog" ).dialog({
		autoOpen: false,
		width: 400,
		buttons: [
			{
				text: "确定",
				click: function() {
					$( this ).dialog( "close" );
//					alert($('#receiveTime').val());
					$.get("<?= Url::to(['reviewprocess/reviewprocessclaim'])?>", {id:$('#now-id').val(),time:$('#receiveTime').val()}, function (data){
						window.location.reload();
					});
				}
			},
			{
				text: "取消",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});
	function receive(id) {
		$('#now-id').val(id);
		$( "#dialog" ).dialog( "open" );
	}
</script>