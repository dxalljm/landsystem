<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\ManagementArea;
use app\models\Farmer;
use app\models\Dispute;
use yii\helpers\Url;
use app\models\Farms;
use app\models\Lease;
use frontend\helpers\arraySearch;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

var_dump($data);exit;
// 	var_dump($totalData->getModels());exit;
$farmdata = arraySearch::find($data)->search();
?>
<div class="farms-menu">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                       地块面积大于合同面积
                    </h3>
                </div>
                <div class="box-body">
 <table class="table table-bordered table-hover">
 	<tr>
 		<td>序号</td>
 		<td>农场ID</td>
 		<td>法人姓名</td>
 		<td>农场名称</td>
 		<td>合同号</td>
 		<td>面积</td>
 		<td>合同面积</td>
 		<td>宗地面积</td>
 		<td>未明确面积</td>
 		<td>未明确状态面积</td>
 		
 	</tr><?php $i=1;?>
 	<?php 
 	$zongdiarea = 0.0;
 	$contractarea = 0.0;
 	foreach($data as $value) {
 	$zongdiarea += Lease::getListArea($value['zongdi']);
 	$contractarea += Farms::getNowContractnumberArea($value['id']);
 	?>
 	<tr>
 		<td><?= $i++?></td>
 		<td><?= $value['id']?></td>
 		<td><?= $value['farmername']?></td>
 		<td><?= $value['farmname']?></td>
 		<td><?= $value['contractnumber']?></td>
 		<td><?= $value['measure']?></td>
 		<td><?= Farms::getNowContractnumberArea($value['id'])?></td>
 		<td><?= Lease::getListArea($value['zongdi'])?></td>
 		<td><?= $value['notclear']?></td>
 		<td><?= $value['notstate']?></td>
 	</tr>
 	<?php }?>
 	<tr>
 		<td></td>
 		<td></td>
 		<td></td>
 		<td></td>
 		<td></td>
 		<td><?= $farmdata->sum('measure')?></td>
 		<td><?= $contractarea?></td>
 		<td><?= $zongdiarea?></td>
 		<td><?= $farmdata->sum('notclear')?></td>
 		<td><?= $farmdata->sum('notstate')?></td>
 	</tr>
 </table>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'farmercontract-modal',
	'size'=>'modal-lg',
	//'header' => '<h4 class="modal-title">登记表</h4>',
	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',

]); 

?>
<?php \yii\bootstrap\Modal::end(); ?>