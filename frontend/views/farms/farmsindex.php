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

    <section class="content-header">

    <p>
        <?= Html::a('添加', ['farmscreate'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('XLS导入', ['farmsxls'], ['class' => 'btn btn-success']) ?>
        <?php //echo Html::a('XLS导入宗地', ['farmszdxls'], ['class' => 'btn btn-success']) ?>
    </p>
</section>
<?php  //echo $this->render('farms_search', ['model' => $searchModel]); ?>
    
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
            	'attribute' => 'farmname',

            ],
            'farmername',
//             [
//             	'label' => '管理区',
//               	'attribute' => 'areaname',      						
//             	'value' => 'managementarea.areaname',
//             ],
			//'management_area',
            'measure',
            // 'iscontract',
            // 'contractlife',

            ['class' => 'yii\grid\ActionColumn'],
//             [
//             'label'=>'更多操作',
//             'format'=>'raw',
//             //'class' => 'btn btn-primary btn-lg',
//             'value' => function($model,$key){
//             	// $url = ['/user/userassign','id'=>$model->id];
//             	return Html::a('详细信息','#', [
//             			'id' => 'farmercreate',
//             			'title' => '填写承包信息',
//             			//'class' => 'btn btn-primary btn-lg',
//             			'data-toggle' => 'modal',
//             			'data-target' => '#farmercontract-modal',
//             			//'data-id' => $key,
//             			'onclick'=> 'farmercontract('.$key.')',
//             			//'data-pjax' => '0',
            
//             	]);
//             }
//             ],
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
            </div>
        </div>
    </div>
</section>
</div>
