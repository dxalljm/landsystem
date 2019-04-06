<?php
namespace frontend\controllers;use app\models\User;
use app\models\Insurancecompany;
use app\models\Tables;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use yii\helpers\ArrayHelper;
use app\models\Farms;
use frontend\helpers\arraySearch;
use frontend\helpers\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\insuranceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Insurance';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;

$totalData = clone $dataProvider;
$totalData->pagination = ['pagesize'=>0];
// 	var_dump($totalData->getModels());exit;
$data = arraySearch::find($totalData)->search();

?>
<div class="insurance-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">保险业务</h3>
                </div>
                <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
         'total' => '<tr>
						<td></td>		
						<td align="center"><strong>'.$data->count('farmer_id').'户</strong></td>
						<td><strong>法人'.$data->where(['nameissame'=>1])->count().'个</strong></td>
						<td><strong>租赁'.$data->where(['nameissame'=>0])->count().'个</strong></td>
						<td><strong>'.$data->sum('contractarea').'亩</strong></td>
						<td><strong>'.$data->sum('insuredarea').'亩</strong></td>
						<td><strong>'.$data->sum('insuredsoybean').'亩</strong></td>
						<td><strong>'.$data->sum('insuredwheat').'亩</strong></td>
						<td><strong>'.$data->sum('insuredother').'亩</strong></td>
						<td><strong>'.$data->count('company_id').'个</strong></td>
						<td><strong>完成'.$data->where(['state'=>1])->count().'个</strong></td>
						<td></td>
						<td></td>
					</tr>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//             'id',
//             'management_area',
            
//             'farms_id',
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
            ],
            // 'create_at',
            // 'update_at',
            // 'policyholdertime',
            // 'managemanttime',
            // 'halltime',
//             'year',
            [
                'label' => '状态',
            	'attribute' => 'select',
                'options' => ['width'=>'160'],
                'value' => function($model) {
                    if($model->state)
                        return '完成';
                    if($model->fwdtstate)
                        return '服务大厅审核通过';
                    return '审核中';
                },
                'filter'=>['issame'=>'面积不一致','isselfselect'=>'不是本人选择']
            ],
            [
                'label'=>'操作',
                'format'=>'raw',
                //'class' => 'btn btn-primary btn-lg',
                'value' => function($model){
                    if(!$model->fwdtstate)
                        return Html::a('审核',Url::to(['insurance/insurancefwdtcheckup','id'=>$model->id]),['class'=>'btn btn-danger btn-xs']);
                    else {
                        if(User::getItemname() == '地产科科长')
                            return Html::a('查看并打印',Url::to(['insurance/insuranceprint','id'=>$model->id]),['class'=>'btn btn-primary btn-xs']);
                        else
                            return Html::a('查看',Url::to(['insurance/insurancechiefview','id'=>$model->id]),['class'=>'btn btn-primary btn-xs']);
                    }

                },
                
            ],
//            [
//            'format'=>'raw',
//            //'class' => 'btn btn-primary btn-lg',
//            'value' => function($model){
//                return Html::a('查看并打印',Url::to(['insurance/insuranceprint','id'=>$model->id]),['class'=>'btn btn-primary btn-xs']);
//            }
//        ]
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
