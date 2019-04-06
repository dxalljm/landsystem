<?php
namespace frontend\controllers;
use app\models\User;
use app\models\Insurance;
use app\models\Insurancecompany;
use app\models\Tables;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\insuranceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Insurance';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;种植业保险<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//             'id',
            [
                'label' => '农场名称',
                'options' => ['width'=>"180"],
                'value' => function ($model) {
                    return Farms::findOne($model->farms_id)->farmname;
                }
            ],
            [
                'attribute' => 'farmername',

            ],
// 			'policyholder',
            [
                'attribute'=>'policyholder',

            ],
//             'cardid',
//             'telephone',
            [
                'attribute' => 'contractarea',
                'options' => ['width'=>"120"]
            ],
            [
                'attribute' => 'insuredarea',
                'options' => ['width'=>"120"]
            ],
            [
                'attribute' => 'insuredsoybean',
                'options' => ['width'=>"120"]
            ],
            [
                'attribute' => 'insuredwheat',
                'options' => ['width'=>"120"]
            ],
            [
                'attribute' => 'insuredother',
                'options' => ['width'=>"120"]
            ],

            [
                'attribute'=>'company_id',
                'value' => function($model) {
                    $result = Insurancecompany::find()->where(['id'=>$model->company_id])->one();
                    return $result['companynname'];
                },
                'filter' => ArrayHelper::map(Insurancecompany::find()->all(),'id','companynname'),
            ],            // 'create_at',
            // 'update_at',
            // 'policyholdertime',
            // 'managemanttime',
            // 'halltime',
            [
                'label' => '状态',
                'options' => ['width'=>'150'],
                'value' => function($model) {
                    $result = '';
                    switch ($model->state) {
                        case 1:
                            $result = '完成';
                            break;
                        case 0:
                            if($model->fwdtstate == 1)
                                $result = '服务大厅审核通过';
                            else
                            	if(empty($model->managemanttime))
                            		$result = '未初审';
                            	else
                                	$result = '审核中';
                            break;
                        case -1;
                            $result = '取消';
                    }
                    return $result;
                }
            ],
            [
                'label'=>'操作',
                'format'=>'raw',
                //'class' => 'btn btn-primary btn-lg',
                'value' => function($model){
                	$html = '';
	                switch ($model->state) {
	                	case 1:
	                		$html = Html::a('撤消申请','#',['class'=>'btn btn-danger btn-xs','disabled'=>true]);
	                		break;
	                	case 0:
	                		if($model->fwdtstate == 1)
	                			$html = $html = Html::a('撤消申请','#',['class'=>'btn btn-danger btn-xs','disabled'=>true]);
                    		else {
	                			if(empty($model->managemanttime))
	                				$html = Html::a('初审',Url::to(['insurance/insuranceupdate','id'=>$model->id,'btn'=>'first']),['class'=>'btn btn-primary btn-xs']);
	                			else
	                				$html = Html::a('撤消申请',Url::to(['insurance/insurancedelete','id'=>$model->id]),[
                    			'class'=>'btn btn-danger btn-xs',
                    			'data' => [
                    					'confirm' => '您确定要撤消申请吗？',
                    					'method' => 'post',
                    			],
                    	]);
                    	}
	                			break;
	                	case -1;
	                	$html = $html = Html::a('撤消申请','#',['class'=>'btn btn-danger btn-xs','disabled'=>true]);
	                }
//                     if($model->state) {
//                     	return Html::a('撤消申请','#',['class'=>'btn btn-danger btn-xs','disabled'=>true]);                      
//                     } elseif(empty($model->managementtime)) {
//                     	return Html::a('初审',Url::to(['insurance/insuranceupdate','id'=>$model->id,'btn'=>'first']),['class'=>'btn btn-primary btn-xs']);
//                     } else {
//                     	return Html::a('撤消申请',Url::to(['insurance/insurancedelete','id'=>$model->id]),[
//                     			'class'=>'btn btn-danger btn-xs',
//                     			'data' => [
//                     					'confirm' => '您确定要撤消申请吗？',
//                     					'method' => 'post',
//                     			],
//                     	]);
//                     }
                	return $html;
                }
            ],
            [
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model){
                if($model->state == -1) {
                    return Html::a('查看', Url::to(['insurance/insurancechiefview', 'id' => $model->id]), ['class' => 'btn btn-danger btn-xs']);
                }
            	if($model->fwdtstate == 1)
                	return Html::a('查看并打印',Url::to(['insurance/insuranceprint','id'=>$model->id]),['class'=>'btn btn-primary btn-xs']);
            	else
            		return Html::a('查看',Url::to(['insurance/insurancetableview','id'=>$model->id]),['class'=>'btn btn-primary btn-xs']);
            }
        ]
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
