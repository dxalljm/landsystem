<?php
namespace frontend\controllers;use app\models\User;
use yii;
use app\models\Farms;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use frontend\helpers\grid\GridView;
use app\models\Dispute;
use app\models\ManagementArea;
use app\models\Lockedinfo;
use app\models\Help;
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
                        <?= Help::showHelp3('整体转让(合并)','ttpo-transfermerge')?></h3>
                </div>
                <div class="box-body">
                <?php if(!Farms::getLocked($_GET['farms_id'])) {?>

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
//            [
//            	'attribute' => 'management_area',
//            	'value' => function($model) {
//            		return ManagementArea::find()->where(['id'=>$model->management_area])->one()['areaname'];
//            	}
//            ],
            'contractnumber',
            'contractarea',
			'cardid',
            'telephone',
            [
            
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	$url = ['farms/farmstransfermergecontract','farms_id'=>$_GET['farms_id'],'newfarms_id'=>$model->id];
                $disputerows = Dispute::find()->where(['farms_id'=>$model->id])->count();
                if($disputerows) {
                    $option = '转让<i class="fa fa-commenting"></i>';
                    $title = '此农场有'.$disputerows.'条纠纷';
                }
                else {
                    $option = '转让';
                    $title = '确认转让到此农场';
                }
                if($model->locked == 1) {
                    return '<span class="text text-red">冻结中</span>';
                }
//                var_dump($model->id);var_dump($_GET['farms_id']);
                if((int)$_GET['farms_id'] !== $model->id) {
                    return Html::a($option, $url, [
                        'id' => 'farmermenu',
                        'title' => $title,
                        'class' => 'btn btn-success',
                    ]);
                } else {
                    return '不能转让给自己';
                }
            }
            ],
        ],
    ]); ?>
    <?= Html::a('返回', [Yii::$app->controller->id.'ttpomenu','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
    <?php } else {?>
    	<h4><?= Lockedinfo::find()->where(['farms_id'=>$_GET['farms_id']])->one()['lockedcontent']?></h4>
    <?php }?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>

