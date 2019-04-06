<?php
namespace frontend\controllers;use app\models\User;
use Yii;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Disputetype;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\disputeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'dispute';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dispute-index">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3>

                </div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php if(User::disabled()) {
			echo Html::a('添加', '#', ['class' => 'btn btn-success','disabled'=>User::disabled()]);
		} else {
			echo Html::a('添加', ['disputecreate', 'farms_id' => $_GET['farms_id']], ['class' => 'btn btn-success']);
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
            	'label' => '农场名称',
            	'attribute' => 'farms_id',
            	'value' => function ($model) {
            		return Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'];
            	},
			],
			[
			'attribute' => 'disputetype_id',
			'value' => function($model) {
				return Disputetype::find()->where(['id'=>$model->disputetype_id])->one()['typename'];
			},
			],
            'content:ntext',
            [
            	'attribute' => 'state',
            	'value' => function($model) {
            		return $model->state?'已解决':'未解决';
			}
			],
            //'create_at',
            //'update_at',

            ['class' => 'frontend\helpers\eActionColumn'],
            [
            	'class' => 'frontend\helpers\addActionColumn',
            		'template' => '{state}',
            		'buttons' => [
            				// 下面代码来自于 yii\grid\ActionColumn 简单修改了下
            				'state' => function ($url, $model, $key) {
            					$options = [
            							'title' => Yii::t('yii', '解决纠纷'),
            							'aria-label' => Yii::t('yii', 'state'),
            							'data-pjax' => '0',
            					];
            					$url.='&farms_id='.$_GET['farms_id'];
            					if($model->state == 0)
            					return Html::a('解决纠纷', $url, $options);
            				},
            				]
			]
        ],
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