<?php

use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Plantingstructure;
use app\models\Plant;
use app\models\Lease;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Url;
use frontend\helpers\ActiveFormrdiv;
use app\models\Search;
use frontend\helpers\arraySearch;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
     <?php //echo '_GET';var_dump($_GET);//echo 'get';var_dump($params);?>
  <?= $this->render('..//search/searchindex',['tab'=>$tab,'begindate'=>$begindate,'enddate'=>$enddate,'params'=>$params]);?>
<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
// 	var_dump($totalData->getModels());exit;
	$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
?>
 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
	 'total' => '<tr height="40">
                                        <td></td>	
                                        <td align="left"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3"></td>
                                        <td align="left" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t5"></td>
                                        <td></td>
                                    </tr>',
        'columns' => [
			[
				'label'=>'管理区',
				'attribute'=>'management_area',
				'headerOptions' => ['width' => '130'],
				'value'=> function($model) {
// 				            	var_dump($model);exit;
					return ManagementArea::getAreanameOne($model->management_area);
				},
				'filter' => ManagementArea::getAreaname(),
			],
			'farmname',
			'farmername',
			'contractnumber',
			'telephone',
			'contractarea',
			[
				'attribute' => 'state',
	 			'value' => function($model) {
//					var_dump($model->state);
					return Farms::getStateInfo($model->state);
             },
             'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理',4=>'买断合同',5=>'其它',0=>'销户'],
			],
			[
				'label' => '操作',
				'format' => 'raw',
				'value' => function($model) {
					return Html::a('查看详情',Url::to(['farms/farmsfile','farms_id'=>$model->id]),['class'=>'btn btn-xs btn-primary']);
				}
			],
		],
    ]); ?>
                <?php User::dataListEnd();?>
        </div>
    </div>
</section>
<script>

	$('#tablename').change(function(){
		var input = $(this).val();
		if(input == 'parmpt')
			$('#searchButton').attr('disabled',"true"); 
		else
			$('#searchButton').removeAttr("disabled");
	});

</script>
<script>
	$('.shclDefault').shCircleLoader({color: "red"});
	$(document).ready(function () {
		$.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-id'}, function (data) {
			$('#t1').html(data + '户');
		});
		$.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
			$('#t2').html(data + '人');
		});
		$.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-contractarea'}, function (data) {
			$('#t4').html(data + '亩');
		});
	});
</script>