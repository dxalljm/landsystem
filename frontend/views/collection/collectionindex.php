<?php
namespace frontend\controllers;
use Yii;
use yii\helpers\Url;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Theyear;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>
<div class="farms-menu">
	<h1>缴费业务(<?= Theyear::findOne(1)['years']?>年度)</h1>

<br />
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          // 'id',
            'farmername',
            'cardid',
            'telephone',
            [
            	'label' => '农场',
              	'attribute' => 'farmname',
            	'value' => 'farms.farmname',
            ],
            [

            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	return Html::a('缴费','#', [
            			'id' => 'collectionindex',
            			'title' => '农场相关业务办理',
            			'data-toggle' => 'modal',
            			'data-target' => '#collectioncreate-modal',
            			'onclick' => 'collectioncreate('.$model->farms_id.','.$model->cardid.')',
            			//'onclick' => "javascript:window.open('".yii::$app->urlManager->createUrl(['/collection/collectioncreate','id'=>$key,'year'=>$years])."','','width=700,height=600,top=50,left=380, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",
            	]);
            }
            ],
            [

            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	// $url = ['/user/userassign','id'=>$model->id];
            	return Html::a('详细信息','#', [
            			'id' => 'farmercreate',
            			'title' => '填写承包信息',
            			'data-toggle' => 'modal',
            			'data-target' => '#farmercontract-modal',
            			'onclick' => 'farmercontract('.$model->farms_id.')',
            			//'onclick' => "javascript:window.open('".yii::$app->urlManager->createUrl(['/farmer/farmercontract','id'=>$key])."','','width=1200,height=600,top=50,left=80, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",


            	]);
            }
            ],
        ],
    ]); ?>

</div>
<?php
$script = <<<JS
jQuery('#years').change(function(){
    var year = $(this).val();
    $.get('/landsystem/frontend/web/index.php?r=collection/collectionindex',{year:year},function (data) {
		      $('body').html(data);
		    });
});
JS;
$this->registerJs($script);
?>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'collectioncreate-modal',
	'size'=>'modal-lg',
	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
	'options' => ['data-keyboard' => 'false', 'data-backdrop' => 'static']
	//'header' => '<h4 class="modal-title"></h4>',
]);?>
<?php \yii\bootstrap\Modal::end(); ?>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'farmercontract-modal',
	'size'=>'modal-lg',
	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',
	//'header' => '<h4 class="modal-title"></h4>',
]);?>
<?php \yii\bootstrap\Modal::end(); ?>