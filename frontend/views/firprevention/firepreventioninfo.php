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
            <div class="box">
                <div class="box-body">
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
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;防火工作<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3>
    <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
                <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
                    'total' => '<tr height="40">
                                        <td align="left" id="t0"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
                                    </tr>',
        'columns' => [
            [
                'label'=>'管理区',
                'attribute'=>'management_area',
                'headerOptions' => ['width' => '130'],
                'value'=> function($model) {
// 				            	var_dump($model);exit;
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
                'class' => 'yii\grid\ActionColumn',
                'header' => '完成进度',
                'template' => '{percent}',//只需要展示删除和更新
                'headerOptions' => ['width' => '840'],
                'buttons' => [
                    'percent' => function($url, $model, $key){
                        $html = '<div class="progress progress-xs progress-striped active">';
                        $html .= '<div class="progress-bar progress-bar-success" style="width: '.Fireprevention::getPercent($model).'"></div>';
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
                        $html = '<span class="badge bg-green">'.Fireprevention::getPercent($model).'</span>';
                        return Html::a($html);
                    },
                ],
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
              <!-- /.tab-pane -->

          </div>
 
                </div>                         


                </div>
            </div>
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