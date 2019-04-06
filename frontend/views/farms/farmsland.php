<?php
namespace frontend\controllers;
use app\models\Lockstate;
use app\models\Mainmenu;
use app\models\Reviewprocess;
use app\models\Tables;
use frontend\helpers\Dialog;
use yii\helpers\Html;
use yii;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\Theyear;
use app\models\Dispute;
use app\models\Machineoffarm;
use app\models\User;
use app\models\Farmer;
use yii\helpers\Url;
use frontend\helpers\arraySearch;
use frontend\helpers\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'farms';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<?php
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
// 	var_dump($totalData->getModels());exit;
//	$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
//'<tr>
//						<td></td>
//						<td align="center"><strong>合计</strong></td>
//						<td><strong>'.$data->count('id').'户</strong></td>
//						<td><strong>'.$data->count('farmer_id').'个</strong></td>
//						<td></td>
//						<td></td>
//						<td><strong>'.$data->sum('contractarea').'亩</strong></td>
//						<td></td>
//					</tr>',
?>
<div class="farms-index">

<?php  //echo $this->render('farms_search', ['model' => $searchModel]); ?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
			<?php User::tableBegin('宜农林地
			');?>
			<?php
			if(User::getItemname('信息科'))
				echo Html::a('XLS导出','#',['class'=>'btn btn-success','onclick'=>'dialog()']);
			?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
		'total' => '<tr height="40">
                                        <td></td>	
                                        <td align="left"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3"></td>
                                        <td align="left" id="t4"></td>
                                        <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
                                    </tr>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
// 			'id',
            [
            	'attribute' => 'management_area',
            	'headerOptions' => ['width' => '200'],
				'value'=> function($model) {
				     return ManagementArea::getAreanameOne($model->management_area);
				 },
				 'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
            ],
            [
            	'attribute' => 'farmname',
//				'options' => ['width' => '']
            ],
            'farmername',
//             [
//             	'label' => '管理区',
//               	'attribute' => 'areaname',      						
//             	'value' => 'managementarea.areaname',
//             ],
			//'management_area',
			[
				'attribute' => 'address',
//				'options' => ['width'=>'300'],
			],
//			'address',
			'telephone',
			[
				'attribute' => 'contractarea',
//				'headerOptions' => ['width' => '100'],
			],
			[
				'attribute' => 'contractnumber',
//				'headerOptions' => ['width' => '150'],
			],

//            'contractnumber',
			
//			[
//				'attribute' => 'create_at',
//				'value' => function($model) {
////					var_dump($model->state);
//					return date('Y-m-d',$model->create_at);
//				}
//			],
//			[
//				'attribute' => 'update_at',
//				'value' => function($model) {
////					var_dump($model->state);
//					return date('Y-m-d',$model->update_at);
//				}
//			],
//			'state',
             [
            	'attribute'=> 'state',
				 'format' => 'raw',
//				 'options' => ['width'=>220],
             	'value' => function ($model) {
//					var_dump($model->state);exit;
					if($model->state == 0) {
						$re = Reviewprocess::find()->where(['oldfarms_id'=>$model->id,'actionname'=>'farmstransfer','state'=>[6,7]])->all();
						$same = Reviewprocess::find()->where(['samefarms_id'=>$model->id,'actionname'=>'farmstransfer','state'=>[6,7]])->all();
						if($same) {
							$re = $same;
						}
						if($re) {
							$text = [];
							foreach($re as $val) {
								$newfarms = Farms::findOne($val['newfarms_id']);
								$text[] = $newfarms->farmername;
							}

							return '已销户：转让给<span class="text text-red">'.implode(',',$text).'</span>';
						}
					}
             		return Farms::getStateInfo($model->state);
             },
             'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理',4=>'买断合同',5=>'其它',0=>'销户'],
             ],
            [
	            'label'=>'更多操作',
	             'format'=>'raw',
//    			'options' => ['width'=>'220'],
				            	//'class' => 'btn btn-primary btn-lg',
				                'value' => function($model,$key){
					                $option = '查看详情';
				                	$url = Url::to(['farms/farmslandview','id'=>$model->id]);               	
				                    $html = Html::a($option,$url, [
						            			'id' => 'moreOperation',
						            			'class' => 'btn btn-primary btn-xs',
// 				                    			'disabled' => $disabled,
						            	]);
// 				                    var_dump(User::getItemname());
									if(User::getItemname('法规科') or User::getItemname('服务大厅')) {
// 										$farmer = Farmer::find()->where(['farms_id'=>$model->id])->one();
// 										if($farmer['photo'] == '' or $farmer['cardpic'] == '' or $farmer['cardpicback'] == '') {
										$html.= '&nbsp;';
										$html.= Html::a('电子信息采集',Url::to(['photograph/photographindex','farms_id'=>$model->id]),['class' => 'btn btn-primary btn-xs',]);
//										$html.= '&nbsp;';
//										$html.= Html::a('导出证件',Url::to(['photograph/photographexplode','farms_id'=>$model->id]),['class' => 'btn btn-primary btn-xs',]);
// 										}
									}
									if(User::getItemname('法规科') or User::getItemname('地产科')) {
										$url = Url::to(['farms/farmsadminupdate','id'=>$model->id]);
										$html.= '&nbsp;';
										$html .= Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
											'title' => Yii::t('yii', '更新'),
											'data-pjax' => '0',
										]);
									}
									if(Yii::$app->user->identity->id == '15') {
										$html .= '&nbsp;';
										if(Lockstate::isLoanLocked($model->id)['state']) {
											if (Lockstate::isUnlockloan($model->id)) {
												$html .= Html::a('临时解锁', '#', ['class' => 'btn btn-primary btn-xs', 'disabled' => 'disabled']);
											} else {
												$html .= Html::a('临时解锁', Url::to(['lockstate/lockstateunset', 'farms_id' => $model->id]), ['class' => 'btn btn-primary btn-xs',]);
											}
										} else {
											$html .= Html::a('临时解锁', '#', ['class' => 'btn btn-primary btn-xs', 'disabled' => 'disabled']);
										}
									}
// 									var_dump(User::getItemname('主任'));exit;
//					            	if(User::getItemname('管委会领导') or User::getItemname('法规科') or User::getItemname('地产科') or User::getItemname('服务大厅')) {
						            	return $html;
//					            	}
//					            	else
//					            		return '';
				                }
				            ],
        ],
    ]); ?>
			<?php
			//var_dump(json_encode($dataProvider->query->where));exit;
			echo Dialog::show($arrclass[2],json_encode($dataProvider->query->where));
			?>
                <?php User::tableEnd();?>
        </div>
    </div>
</section>
</div>

<script>

	$('.shclDefault').shCircleLoader({color: "red"});
	$(document).ready(function () {
		$.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-id'}, function (data) {
			$('#t1').html(data + '户');
		});
		$.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
			$('#t2').html(data + '人');
		});
		$.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-contractarea'}, function (data) {
			$('#t5').html(data + '亩');
		});
	});
</script>