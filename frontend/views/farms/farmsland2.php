<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\ManagementArea;
use app\models\Farms;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\helpers\arraySearch;
use app\models\Search;
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
<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
// 	var_dump($totalData->getModels());exit;
	$data = arraySearch::find($totalData)->search();
?>
 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'total' => '<tr>
						<td></td>
						<td align="center"><strong>合计</strong></td>
						<td><strong>'.$data->count('id').'户</strong></td>
						<td><strong>'.$data->count('farmer_id').'个</strong></td>
						<td></td>
						<td></td>
						<td><strong>'.$data->sum('contractarea').'亩</strong></td>						
						<td></td>
					</tr>',
        'columns' => Search::getColumns(['management_area','farmname','farmername','address','telephone','contractarea','operation'],$totalData),

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
