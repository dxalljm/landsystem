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
use app\models\Collection;
use frontend\helpers\MoneyFormat;
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

?>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        缴费业务(<?= Theyear::findOne(1)['years']?>年度)
                    </h3>
                </div>
                <div class="box-body">
<br />
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          // 'id',
            
            [
            	'label' => '农场名称',
              	'attribute' => 'farmname',
            	'value' => 'farms.farmname',
            ],
            [
	            'label' => '法人姓名',
	            'value' => function ($model){
            		return Farms::find()->where(['id'=>$model->farms_id])->one()['farmername'];
            	}
            ],
            [
            	//'label' => '应收金额',
            	'attribute' => 'amounts_receivable',
            	'value' => function($model) {
            		return MoneyFormat::num_format($model->amounts_receivable).'元';
            	}
            ],
            [
	            //'label' => '实收金额',
	            'attribute' => 'real_income_amount',
	            'value' => function($model) {
	            	return MoneyFormat::num_format($model->real_income_amount).'元';
	            }
            ],
            [
	            'label' => '差额',
	            'value' => function($model) {
	            	return MoneyFormat::num_format(bcsub($model->amounts_receivable, $model->real_income_amount,2)).'元';
	            }
            ],
            //'cardid',
            //'telephone',
            [

            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	return Html::a('确认缴费','index.php?r=tempprintbill/tempprintbillcreate&id='.$model->id, [
            			'id' => 'collectioncreate',
            			'class' => 'btn btn-success',
            			'title' => '地产科确认提交的缴费申请，点击按钮确认缴费并打印发票',
            			
            			//'data-toggle' => 'modal',
            			//'data-target' => '#collectioncreate-modal',
            			//'onclick' => 'collectioncreate('.$model->farms_id.','.$model->cardid.')',
            			//'onclick' => "javascript:window.open('".yii::$app->urlManager->createUrl(['/collection/collectioncreate','id'=>$key,'year'=>$years])."','','width=700,height=600,top=50,left=380, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",
            	]);
            }
            ],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>

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