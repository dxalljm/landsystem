<?php
namespace frontend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ManagementArea;
use app\models\Farmer;
use app\models\Dispute;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

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
 		
 	</tr><?php $i=1;?>
 	<?php foreach($data as $value) {?>
 	<tr>
 		<td><?= $i++?></td>
 		<td><?= $value['id']?></td>
 		<td><?= $value['farmername']?></td>
 		<td><?= $value['farmname']?></td>
 		<td><?= $value['contractnumber']?></td>
 		<td><?= $value['measure']?></td>
 		
 	</tr>
 	<?php }?>
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