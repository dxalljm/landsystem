<?php
namespace frontend\controllers;
use yii;
use app\models\Farms;
use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\grid\GridView;
use app\models\Dispute;
use app\models\ManagementArea;
/* @var $this yii\web\View */
/* @var $model app\models\farms */

?>
<div class="farms-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        宗地转让</h3>
                </div>
                <div class="box-body">
                  <?php $form = ActiveFormrdiv::begin(); ?>
	<table class="table table-bordered table-hover">
	  <tr>
    		<td align="center" valign="middle">农场查询</td>
    		<td align="center" valign="middle"><?= html::textInput('farmSearch','',['class'=>'form-control']) ?></td>
			<td align="center" valign="middle"><?= Html::submitButton('查询', ['class' => 'btn btn-primary']) ?></td>
    	</tr>

  </table>
<?php ActiveFormrdiv::end(); ?>
<?php if(!empty($dataProvider)) {?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
            	'attribute' => 'farmname',
            ],
            'farmername',
            [
            	'attribute' => 'management_area',
            	'value' => function($model) {
            		return ManagementArea::find()->where(['id'=>$model->management_area])->one()['areaname'];
            	}
            ],
            'measure',
            //'management_area',
            [
            
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	$url = ['farms/farmstozongdi','farms_id'=>$model->id,'oldfarms_id'=>$_GET['farms_id']];
            	$disputerows = Dispute::find()->where(['farms_id'=>$model->id])->count();
            	if($disputerows) {
            		$option = '确认<i class="fa fa-commenting"></i>';
            		$title = '此农场有'.$disputerows.'条纠纷';
            	}
            	else { 
            		$option = '确认';
            		$title = '确认转让到此农场';
            	}
            	return Html::a($option,$url, [
            			'id' => 'farmermenu',
            			'title' => $title,
            			'class' => 'btn btn-success',
            	]);
            }
            ],
        ],
    ]); ?>
    <?php }?>
    <?= Html::a('返回', [Yii::$app->controller->id.'ttpomenu','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>

