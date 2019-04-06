<?php
namespace frontend\controllers;use app\models\User;
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
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<?php if(!Insurance::find()->where(['farms_id'=>$farms_id])->count()) {?>
    <p>
        <?= Html::a('种植业保险申请', ['insurancecreate','farms_id'=>$farms_id], ['class' => 'btn btn-success']) ?>
    </p>
<?php }?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
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
            ],            // 'create_at',
            // 'update_at',
            // 'policyholdertime',
            // 'managemanttime',
            // 'halltime',
            [
                'label' => '状态',
                'options' => ['width'=>'150'],
                'value' => function($model) {
                    if($model->state)
                        return '完成';
                    if($model->fwdtstate)
                        return '服务大厅审核通过';
                    return '审核中';
                }
            ],
            [
                'label'=>'操作',
                'format'=>'raw',
                //'class' => 'btn btn-primary btn-lg',
                'value' => function($model){
                    if(!$model->state)
                        return Html::a('撤消申请',Url::to(['insurance/insurancedelete','id'=>$model->id]),[
                        		'class'=>'btn btn-danger btn-xs',
                        		'data' => [
                        				'confirm' => '您确定要撤消申请吗？',
                        				'method' => 'post',
                        		],
                    ]);
                    else
                        return Html::a('撤消申请','#',['class'=>'btn btn-danger btn-xs','disabled'=>true]);
                }
            ],
            [
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model){
            	if($model->fwdtstate)
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
