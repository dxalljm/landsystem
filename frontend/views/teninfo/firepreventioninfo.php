<?php

use app\models\Tables;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\User;
use app\models\Plant;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Search;
use frontend\helpers\arraySearch;
use app\models\Fireprevention;
use yii\helpers\Html;
use frontend\helpers\ES;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<div class="lease-index">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php User::tableBegin('防火工作');?>

<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
//'total' => '<tr>
//	        <td></td>
//	        <td align="center"><strong>合计</strong></td>
//	        <td><strong>'.$data->count('farms_id').'户</strong></td>
//	        <td><strong>'.$data->count('farmer_id').'个</strong></td>
//	        <td></td>
//			<td></td>
//	        </tr>',
	//'firecontract','safecontract','environmental_agreement','firetools','mechanical_fire_cover','chimney_fire_cover','isolation_belt','propagandist','fire_administrator','fieldpermit','propaganda_firecontract','employee_firecontract'
?>

            <ul class="nav nav-pills nav-pills-warning">
                <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
                <li class=""><a href="#echarts" data-toggle="tab" aria-expanded="false">图表</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
                  <?php
                  //var_dump($dataProvider->getModels());exit;
                  ?>
                <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
                    'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="t0"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
                                    </tr>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'label'=>'管理区',
                'attribute'=>'management_area',
                'headerOptions' => ['width' => '150'],
                'value'=> function($model) {
// 				            	var_dump($model);exit;
//                    return $model->management_area;
                    return ManagementArea::getAreanameOne($model->management_area);
                },
                'filter' => ManagementArea::getAreaname(),
            ],
            [
                'label' => '农场名称',
                'attribute' => 'farms_id',
                'options' =>['width'=>120],
                'value' => function ($model) {
                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['farmname'];

                }
            ],
            [
                'label' => '法人名称',
                'attribute' => 'farmer_id',
                'options' =>['width'=>120],
                'value' => function ($model) {
                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['farmername'];

                }
            ],
            [
                'label' => '合同号',
                'attribute'=>'farmstate',
                'options' => ['width'=>200],
                'value' => function($model) {
                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['contractnumber'];
                },
                'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理',4=>'买断合同',5=>'其它'],
            ],
            [
                'label' => '合同面积',
//                'attribute' => 'contractarea',
                'value' => function($model) {
                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['contractarea'];
                }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '完成进度',
                'template' => '{percent}',//只需要展示删除和更新
                'headerOptions' => ['width' => '400'],
                'buttons' => [
                    'percent' => function($url, $model, $key){
                    	if($model->percent >= 60)
                    		$class = 'progress-bar progress-bar-success';
                    	else 
                    		$class = 'progress-bar progress-bar-warning';
                        $html = '<div class="progress progress-xs progress-striped active">';
                        $html .= '<div class="'.$class.'" style="width: '.$model->percent.'%;"></div>';
                        $html .='</div>';
                        return Html::a($html);
                    },
                ],
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '%',
                'template' => '{percentvalue}',//只需要展示删除和更新
                'headerOptions' => ['width' => '40'],
                'buttons' => [
                    'percentvalue' => function($url, $model, $key){
                        $html = '<span class="badge bg-green">'.$model->percent.'</span>';
                        return Html::a($html);
                    },
                ],
            ],
            [
            	'label' => '状态',
            	'attribute' => 'finished',
            	'format' => 'raw',
            	'value' => function($model) {
            		if($model->finished == 1)
            			return '<span class="text-green">完成</span>';
            		if($model->finished == 0)
            			return '<span class="text-red">未完成</span>';
                    if($model->finished == 2) {
                        return '<span class="text-orange">部分完成</span>';
                    }
                },
                'filter' => ['未完成','完成','部分完成'],
            ],
            [
                'label' => '操作',
                'format' => 'raw',
                'value' => function($model) {
                    $option = '查看详情';
                    $title = '';
                    $url = [Yii::$app->controller->id.'/'.Yii::$app->controller->id.'view','id'=>$model->id,'farms_id'=>$model->farms_id];
                    $html = Html::a($option,$url, [
                        'id' => 'moreOperation',
                        'title' => $title,
                        'class' => 'btn btn-primary btn-xs',
// 				                    			'disabled' => $disabled,
                    ]);
                    return $html;
                }
            ],
        ],
    ]); ?>

                </div>
                <div class="tab-pane" id="echarts">
                    <?php
                    $data = \frontend\helpers\Echartsdata::getFireinfo($totalData);
                    $x = \frontend\helpers\whereHandle::getManagementArea($totalData,'areaname');
//                    var_dump($x);var_dump($data);exit;
                    echo ES::wdjStack()->DOM('fireEcharts',true,'1500px','500px')->options(['legend'=>['应完成','部分完成','已完成'],'xAxis'=>$x,'unit'=>'户','yAxis'=>[],'series'=>[$data['all'],$data['part'],$data['real']]])->JS();
                    ?>
                </div>
            </div>
            <?php User::tableEnd();?>
        </div>
    </div>
</section>
</div>
<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t1').html(data + '户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t2').html(data + '人');
        });
    });
</script>