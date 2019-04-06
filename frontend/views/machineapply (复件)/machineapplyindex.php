<?php
namespace frontend\controllers;
use app\models\Machine;
use app\models\Machineoffarm;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\ManagementArea;
use yii\helpers\Url;
use frontend\helpers\arraySearch;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\MachineapplySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Machineapply';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php
$totalData = clone $dataProvider;
$totalData->pagination = ['pagesize'=>0];
$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
?>
<div class="machineapply-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php User::tableBegin($this->title);?>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'total' => '<tr height="40">
                        <td></td>	
                        <td align="left" id="t0"><strong>合计</strong></td>
                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                        <td align="left" id="t0"><strong></strong></td>
                        <td align="left" id="t0"><strong></strong></td>
                        <td align="left" id="t0"><strong></strong></td>
                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                     </tr>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
//            'farms_id',
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
                'attribute' => 'farmername',
                'options' => ['width'=>'60'],
            ],
//            'farmername',
//            'age',
//            'sex',
            // 'domicile',
            // 'management_area',
             'cardid',
            'domicile',
//             'telephone',
            [
                'attribute' => 'telephone',
                'options' => ['width'=>'80'],
            ],
            [
                'attribute' => 'machineoffarm_id',
                'label' => '机具型号',
                'options' => ['width'=>'60'],
                'value' => function($model) {
                    $mf = Machineoffarm::findOne($model->machineoffarm_id);
                    if($mf) {
                        $m = Machine::findOne($mf->machine_id);
                        if($m) {
                            return $m->implementmodel;
                        }
                    } else {
                        return '未确认';
                    }
                }
            ],
            [
                'attribute' => 'subsidymoney',
                'options' => ['width'=>'100'],
            ],
//            'subsidymoney',
            [
                'attribute' => 'state',
                'format' => 'raw',
                'value' => function($model) {
                    if($model->state == 1) {
                        $result = '<span class="text-green">已完成</span>';
                    }
                    if($model->state == 0) {
                        $result = '已申请';
                    }
                    if($model->state == -1) {
                        $result = '<span class="text-red">已撤消</span>';
                    }
                    return $result;
                },
                'filter' => [0=>'已申请',1=>'已完成',-1=>'已撤消'],
            ],
            [
                'attribute' => 'create_at',
                'value' => function($model) {
                    return date('Y-m-d',$model->create_at);
                }
            ],
            // 'machineoffarm_id',
            // 'farmerpinyin',
            [
                'label' => '操作',
                'format' => 'raw',
                'options' => ['width'=>250],
                'value' => function($model) {
                    $html = '';
                    switch ($model->state) {
                        case 0:
                            $html .= Html::a('确认农机', Url::to(['machineoffarm/machineoffarmadd', 'farms_id' => $model->farms_id, 'id' => $model->id]), ['class' => 'btn btn-xs btn-danger']);
                            $html .= '&nbsp;';
                            $html .= Html::a('查看', '#', ['class' => 'btn btn-xs btn-success','disabled'=>'disabled']);
                            $html .= '&nbsp;';
                            $html .= Html::a('撤消', Url::to(['machineapply/machineapplydelete','id'=>$model->id]), ['class' => 'btn btn-xs btn-danger','data' => [
                                'confirm' => '是否确认撤销？',
                                'method' => 'post',
                            ],]);
                            break;
                        case 1:
                            $html .= Html::a('确认农机', '#', ['class' => 'btn btn-xs btn-success','disabled'=>'disabled']);
                            $html .= '&nbsp;';
                            $html .= Html::a('查看', Url::to(['machineapply/machineapplyprint', 'id' => $model->id]), ['class' => 'btn btn-xs btn-success']);
                            $html .= '&nbsp;';
                            $html .= Html::a('导出电子材料', Url::to(['machineapply/machineapplyexplode', 'id' => $model->id]), ['class' => 'btn btn-xs btn-primary']);
                            break;
                        case -1:
                            $html .= Html::a('确认农机', '#', ['class' => 'btn btn-xs btn-success','disabled'=>'disabled']);
//                            $html .= '&nbsp;';
//                            $html .= Html::a('查看', Url::to(['machineapply/machineapplyprint', 'id' => $model->id]), ['class' => 'btn btn-success']);
//                            $html .= '&nbsp;';
                            break;
                    }
                    return $html;
                }
            ]
//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
                <?php User::dataListEnd();?>
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
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-subsidymoney'}, function (data) {
            $('#t3').html(data + '元');
        });

//        $.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>//',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'sum-mortgagemoney'}, function (data) {
//            $('#t5').html(data + '万元');
//        });
    });
</script>