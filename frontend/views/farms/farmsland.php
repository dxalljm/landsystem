<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ManagementArea;
use app\models\Farms;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'farms';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="farms-index">
<?php  //echo $this->render('farms_search', ['model' => $searchModel]); ?>
    
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
<table class="table table-bordered table-hover">
  <tr>
    <td align="center"><strong>户数：
      <?= Farms::getFarmrows();?>户</strong></td>
    <td align="center"><strong>面积：
      <?= Farms::getFarmarea()?>万亩</strong></td>
    <td align="center"><strong>法人：
      <?php echo Farms::getFarmerrows()?>个</strong></td>
</tr>
</table>
                    <?php
                    // 计算
//                    var_dump($dataProvider->getModels());
//                    exit;
                    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => "{summary}\n wubaiqign - test 合计,合计合计..... {items}\n {pager}",
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
            	'label'=>'管理区',
            	'attribute'=>'management_area',
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
            'telephone',
            'measure',
            'contractnumber',

            [
                'header' => '合计',
                'value' => function () {
                    return '123';
                }
            ],

//             ['class' => 'yii\grid\ActionColumn'],
            [
	            'label'=>'操作',
	            'format'=>'raw',
	            //'class' => 'btn btn-primary btn-lg',
	            'value' => function($model,$key){
	            	$url = Url::to(['farms/farmsfile','farms_id'=>$model->id]);
	            	$option = '农场档案';
	            	$title = '';
	            	if($model->measure > Farms::getNowContractnumberArea($model->id)) {
	            		$option = '<i class="fa fa-check text-red"></i>';
	            		$title = '地块面积大于合同面积';
	            		
	            	}
	            	return Html::a($option,$url, [
	            			'id' => 'farmerland',
	            			'title' => $title,
	            			'class' => 'btn btn-primary btn-xs',
	            	]);
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
