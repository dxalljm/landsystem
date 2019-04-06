<?php
namespace frontend\controllers;use app\models\User;
use Yii;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use frontend\helpers\MoneyFormat;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\cooperativeoffarmSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'cooperative_of_farm';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cooperative-of-farm-index">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php if(User::disabled()) {
            echo Html::a('添加', '#', ['class' => 'btn btn-success','disabled'=>User::disabled()]);
        } else {
            echo Html::a('添加', ['cooperativeoffarmcreate', 'farms_id' => $_GET['farms_id']], ['class' => 'btn btn-success']);
        } ?>
    </p>
<?php Farms::showRow($_GET['farms_id']);?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
            	'label'=>'农场名称',
            	'attribute' => 'farms_id',
            	'value' => function($model) {
            		return Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'];
            	}
            ],
             [
            	//'label'=>'农场名称',
            	'attribute' => 'cia',
            	'value' => function($model) {
            		return MoneyFormat::num_format($model->cia).'元';
            	}
            ],
            
            'proportion',
            'bonus',
            // 'cooperative_id',

            [
            'class' => 'yii\grid\ActionColumn',
            'template' => '{view} {update} {delete}',
            'buttons' => [
                // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                'view' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'View'),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                    ];
                    $url.='&farms_id='.$_GET['farms_id'];
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                },
                'update' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'Update'),
                        'aria-label' => Yii::t('yii', 'Update'),
                        'data-pjax' => '0',
                    ];
                    $url.='&farms_id='.$_GET['farms_id'];
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                },
                'delete' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', 'Delete'),
                        'aria-label' => Yii::t('yii', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ];
                    $url.='&farms_id='.$_GET['farms_id'];
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                },
            ]
        ],
		]
    ]); ?>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script>
$('#rowjump').keyup(function(event){
	input = $(this).val();
	$.getJSON('index.php?r=farms/getfarmid', {id: input}, function (data) {
		$('#setFarmsid').val(data.farmsid);
	});
});
</script>