<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\Lease;
use app\models\Dispute;
use app\models\Machineoffarm;
use app\models\User;
use app\models\Farmer;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'farms';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<?php 
// 	$totalData = clone $dataProvider;
// 	$totalData->pagination = ['pagesize'=>0];
// // 	var_dump($totalData->getModels());exit;
// 	$data = arraySearch::find($totalData)->search();
?>
<div class="farms-index">

<?php  //echo $this->render('farms_search', ['model' => $searchModel]); ?>
    
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
//         'total' => '<tr>
// 						<td></td>
// 						<td align="center"><strong>合计</strong></td>
// 						<td><strong>'.$data->count('id').'户</strong></td>
// 						<td><strong>'.$data->count('farmer_id').'个</strong></td>
// 						<td></td>
// 						<td></td>
// 						<td><strong>'.$data->sum('contractarea').'亩</strong></td>
// 						<td></td>
// 					</tr>',
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
			'address',
			'telephone',
            'contractarea',
            'contractnumber',
//             [
//             	'attribute'=> 'state',
//             	'value' => function ($model) {
//             		if($model->state)
//             			return '正常';
//             		else 
//             			return '销户';
//             }
//             ],
            [
				                'label'=>'更多操作',
				                'format'=>'raw',
				            	//'class' => 'btn btn-primary btn-lg',
				                'value' => function($model,$key){
					                $option = '查看详情';
				                	$url = Url::to(['farms/farmslandview','id'=>$model->id]);               	
				                    $html = Html::a($option,$url, [
						            			'id' => 'moreOperation',
						            			'class' => 'btn btn-primary btn-xs',
// 				                    			'disabled' => $disabled,
						            	]);
									if(User::getItemname() == '法规科科长') {
										$farmer = Farmer::find()->where(['farms_id'=>$model->id])->one();
// 										if($farmer['photo'] == '' or $farmer['cardpic'] == '' or $farmer['cardpicback'] == '') {
											$html.= '&nbsp;';
											$html.= Html::a('电子信息采集',Url::to(['photograph/photographindex','farms_id'=>$model->id]),['class' => 'btn btn-primary btn-xs',]);
// 										}
									}
// 									var_dump($html);exit;
					            	if(User::getItemname() == '主任' or User::getItemname() == '副主任' or User::getItemname() == '法规科科长' or User::getItemname() == '地产科科长') {
						            	return $html;
					            	}
					            	else 
					            		return '';
				                }
				            ],
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
