<?php
namespace frontend\controllers;
use app\models\Insurancetype;
use app\models\Plant;
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
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<?php if($isShow) {
    if(User::disabled()) {
        echo Html::a('种植业保险申请', '#', ['class' => 'btn btn-success','disabled'=>User::disabled()]);
    } else {
        echo Html::a('种植业保险申请', ['insurancecreate', 'farms_id' => $farms_id], ['class' => 'btn btn-success']);
    }
 }?>
    <?php
        $types = Insurancetype::find()->all();
        $key = 6;
    $columns = [
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
        ];
    foreach ($types as $value) {
        $columns[] = [
            'label' => Plant::find()->where(['id'=>$value['plant_id']])->one()['typename'],
            'custom' => $value,
            'value' => function($model,$key,$index,$obj) {
                $plants = explode(",",$model->insured);
                foreach($plants as $val) {
                    $pv = explode("-",$val);
                    if($pv[0] == $obj->custom['plant_id']) {
                        return $pv[1];
                    }
                }
            }
        ];
    }

//            [
//                'attribute' => 'insuredwheat',
//                'options' => ['width'=>"120"]
//            ],
//            [
//                'attribute' => 'insuredother',
//                'options' => ['width'=>"120"]
//            ],

        $columns[] = [
            'attribute'=>'company_id',
            'value' => function($model) {
                $result = Insurancecompany::find()->where(['id'=>$model->company_id])->one();
                return $result['companynname'];
            },
        ];
    $columns[] = [
            'attribute' => 'create_at',
            'value' => function($model) {
                return date('Y-m-d H:i:s',$model->create_at);
            }
        ];           // 'create_at',
    $columns[] = [
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
        ];
    $columns[] = [
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
                                $html = Html::a('初审',Url::to(['insurance/insuranceupdate','id'=>$model->id,'btn'=>'first']),['class'=>'btn btn-primary btn-xs','onclick'=>'isplant('.$model->id.')']);
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
        ];
    $columns[] = [
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
        ];
    ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => $columns,

    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script>
    function isplan(lease_id)
    {
//        alert('还没有填写种植结构,请填写种植结构之后再来重试。');
    }
</script>