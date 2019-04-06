<?php
namespace frontend\controllers;use app\models\User;
use Yii;
use yii\helpers\Url;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
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
                        缴费业务
                    </h3>
                </div>
                <div class="box-body">
<br />
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

          // 'id',
            [
	            'attribute' => 'management_area',
				'options' => ['width'=>150],
	            'value' => function($model) {
					return ManagementArea::getAreanameOne($model->management_area);
            },
				'filter' => ManagementArea::getAreaname(),
			],
           
            [
            	'label' => '农场名称',
				
              	'attribute' => 'farms_id',
				'value' => function ($model){
				
					return Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'];
				}
            ],
            [
	            'label' => '法人姓名',
				'attribute'=>'farmer_id',
// 				'options' => ['width'=>100],
	            'value' => function ($model){
            		return Farms::find()->where(['id'=>$model->farms_id])->one()['farmername'];
            	}
            ],
			[
				'label' => '合同号',
				'attribute' => 'contractnumber',
				'value' => function($model) {
					$farm = Farms::find()->where(['id'=>$model->farms_id])->one();
					return $farm['contractnumber'];
				}
			],
			[
				'label' => '合同面积',
				'attribute' => 'contractarea',
				'options' =>['width' => '120'],
			],
            [
            'label' => '缴费年度',
            'attribute' => 'payyear',
            'format' => 'raw',
            'options' => ['width'=>100],
            'value' => function($model) {
            	if($model->payyear == date('Y'))
            		return '<strong class="text text-red">'.$model->payyear.'年度</strong>';
            	else
            		return $model->payyear.'年度';
            }
            ],
            [
            	'attribute' => 'measure',
            		'value' => function($model) {
            			return $model->measure.'亩';
            		}
            ],
            [
            	//'label' => '应收金额',
            	'attribute' => 'amounts_receivable',
            	'value' => function($model) {
					if(Collection::find()->where(['farms_id'=>$model->farms_id,'ypayyear'=>$model->ypayyear])->count() > 1) {
						return MoneyFormat::num_format($model->real_income_amount).'元';
					}
            		return MoneyFormat::num_format($model->amounts_receivable).'元';
            	}
            ],
//            [
//	            //'label' => '实收金额',
//	            'attribute' => 'real_income_amount',
//	            'value' => function($model) {
//	            	return MoneyFormat::num_format($model->real_income_amount).'元';
//	            }
//            ],
            [
	            'label' => '应追缴金额',
				'attribute' => 'owe',
	            'value' => function($model) {
	            	return MoneyFormat::num_format(Collection::getOwe($model->farms_id,$model->ypayyear)).'元';
	            }
            ],
            //'cardid',
            //'telephone',
            [

            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            if($model->payyear == date('Y'))
            	$class = 'btn btn-danger';
            else 
            	$class = 'btn btn-success';
            	$html = Html::a('确认缴费','index.php?r=tempprintbill/tempprintbillcreate&id='.$model->id, [
            			'id' => 'collectioncreate',
            			'class' => $class,
            			'title' => '地产科确认提交的缴费申请，点击按钮确认缴费并打印发票',
            			
            			//'data-toggle' => 'modal',
            			//'data-target' => '#collectioncreate-modal',
            			//'onclick' => 'collectioncreate('.$model->farms_id.','.$model->cardid.')',
            			//'onclick' => "javascript:window.open('".yii::$app->urlManager->createUrl(['/collection/collectioncreate','id'=>$key,'year'=>$years])."','','width=700,height=600,top=50,left=380, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",
            	]);
//				$html .= '&nbsp;';
//				$html .= Html::a('撤消','index.php?r=collection/collectionreset&id='.$model->id, [
//					'id' => 'collectionreset',
//					'class' => 'btn btn-danger',
//				]);
				return $html;
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