<?php
namespace backend\controllers;
use app\models\Plant;
use app\models\Saleswhere;
use app\models\tables;
use console\models\Farms;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\SaleswhereSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Saleswhere';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="saleswhere-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <p>
                        <?= Html::a('新增销售信息', '#', ['class' => 'btn btn-success','onclick'=>'createSales('.$_GET['farms_id'].','.$_GET['planting_id'].')']) ?>
                    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'farms_id',
                'value' => function($model) {
                    $farm = Farms::findOne($model->farms_id);
                    return $farm->farmname;
                }
            ],
            [
                'attribute' => 'plant_id',
                'value' => function($model) {
                    $typename = Plant::findOne($model->plant_id);
                    return $typename->typename;
                }
            ],
            [
                'attribute' => 'whereabouts',
                'value' => function($model) {
                    $where = Saleswhere::findOne($model->whereabouts);
                    return $where->wherename;
                },
                'filter' => ArrayHelper::map(Saleswhere::find()->all(),'id','wherename')
            ],
            'volume',
            'price',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script>
    function createSales(farms_id,planting_id) {
        $('#temp-id').val(planting_id);
        $.get('index.php?r=sales/salescreateajax', {farms_id: farms_id,planting_id:planting_id}, function (body) {
            $('#dialogMsg').html('');
            $('#dialogMsg').html(body);
            $("#dialogMsg").dialog("open");
        });
    }
</script>