<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ManagementArea;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'farms';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="farms-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['farmscreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
            	'attribute' => 'farmname',

            ],
            [
            	'label' => '管理区',
              	'attribute' => 'areaname',      						
            	'value' => 'managementarea.areaname',
            ],
            'spyear',
            // 'iscontract',
            // 'contractlife',

            ['class' => 'yii\grid\ActionColumn'],
            [
            'label'=>'更多操作',
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	// $url = ['/user/userassign','id'=>$model->id];
            	return Html::a('详细信息','#', [
            			'id' => 'farmercreate',
            			'title' => '填写承包信息',
            			//'class' => 'btn btn-primary btn-lg',
            			'data-toggle' => 'modal',
            			'data-target' => '#farmercontract-modal',
            			//'data-id' => $key,
            			'onclick'=> 'farmercontract('.$key.')',
            			//'data-pjax' => '0',
            
            	]);
            }
            ],
        ],
    ]); ?>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'farmercontract-modal',
	'size'=>'modal-lg',
	//'header' => '<h4 class="modal-title">登记表</h4>',
	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',

]); 

?>
<?php \yii\bootstrap\Modal::end(); ?>
</div>
