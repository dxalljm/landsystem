<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\Lease;
use app\models\Dispute;
use app\models\Machineoffarm;
use frontend\helpers\arraySearch;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'farms';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
$farmsTotal = clone $dataProvider;
$farmsTotal->pagination = ['pagesize'=>0];
$data = arraySearch::find($farmsTotal)->search();
?>
<div class="farms-index">

    <section class="content-header">

    <p>

        <?php //echo Html::a('XLS导入', ['farmsxls'], ['class' => 'btn btn-success']) ?>
        <?php //echo Html::a('生成XLS表', ['farmstoxls'], ['class' => 'btn btn-success']) ?>
    </p>
</section>
<?php  //echo $this->render('farms_search', ['model' => $searchModel]); ?>
    
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?><?php echo '&nbsp;'.Html::a('添加', ['farmscreate'], ['class' => 'btn btn-success']) ?>
                    </h3>
                </div>
                <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'total' => '<tr>
						<td></td>
						<td align="center"><strong>合计</strong></td>
						<td><strong>'.$data->count('id').'户</strong></td>
						<td><strong>'.$data->count('farmer_id').'个</strong></td>
						<td><strong>'.$data->sum('contractarea').'亩</strong></td>
						<td></td>
						<td></td>
						<td></td>
					</tr>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
            	'attribute' => 'management_area',
            	'headerOptions' => ['width' => '200'],
				'value'=> function($model) {
				     return ManagementArea::getAreanameOne($model->management_area);
				 },
				 'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
            ],
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
            'contractarea',
            'contractnumber',
            [
            	'attribute'=> 'state',
            	'value' => function ($model) {
// 					if($model->state <= 4) {
						return Farms::getFarmsStateOne($model->state);
// 					} else {
// 						return Farms::getFarmsStateOne($model->notstateinfo);
// 					}

            },
            'filter' => Farms::getFarmsState([0,1,2,3,4,5]),
            ],
			[
				'attribute' => 'create_at',
				'value' => function($model) {
					return date('Y-m-d',$model->create_at);
				}
			],
            [
                'attribute' => 'update_at',
                'value' => function($model) {
                    return date('Y-m-d',$model->update_at);
                }
            ],
            ['class' => 'frontend\helpers\eActionColumn'],
            [
            
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	$url = '';
            	$html = '';
            	
            	if($model->zongdi) {
            		$zongdioption = '<i class="fa fa-map text-red"></i>';
            		$zongdititle = Lease::getZongdiRows($model->zongdi).'块宗地';
            		$html .= Html::a($zongdioption,$url, [
            				'title' => $zongdititle,
            
            		]);
            		$html .= '&nbsp;';
	            	if($model->notclear) {
	            		$notclearoption = '<i class="fa fa-exclamation-circle text-red"></i>';
	            		$notcleartitle = '未明确地块面积'.$model->notclear.'亩';
	            		$html .= Html::a($notclearoption,$url, [
	            				'title' => $notcleartitle,
	            
	            		]);
	            		$html .= '&nbsp;';
	            	}
	            	if($model->notstate) {
	            		$notstateoption = '<i class="fa fa-tag text-red"></i>';
	            		$notstatetitle = '未明确状态面积'.$model->notstate.'亩';
	            		$html .= Html::a($notstateoption,$url, [
	            				'title' => $notstatetitle,
	            
	            		]);
	            		$html .= '&nbsp;';
	            	}
            	}
            	
            	if($model->locked == 1) {
            		$lockoption = '<i class="fa fa-lock text-red"></i>';
            		$locktitle = '已冻结';
            		$html .= Html::a($lockoption,$url, [
            				'id' => 'farmermenu',
            				'title' => $locktitle,
            		]);
            		$html .= '&nbsp;';
            	}
            	
            	//             	if($model->notstate) {
            	//             		$option.='<i class="fa fa-lock text-red"></i>';
            	//             		$title = '未明确状态面积';
            	//             	}
            	return $html;
            }
            ],
//             [
//             'label'=>'状态',
//             'format'=>'raw',
//             //'class' => 'btn btn-primary btn-lg',
//             'value' => function($model,$key){
//             	$option = '';
//             	$title = '';
//             	if($model->measure > Farms::getNowContractnumberArea($model->id)) {
//             		$option = '<i class="fa fa-check text-red"></i>';
//             		$title = '地块面积大于合同面积';
            		
//             	}
//             	return Html::a($option,'#', [
//             			'id' => 'farmermenu',
//             			'title' => $title,
            			
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
