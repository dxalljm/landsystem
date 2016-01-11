<?php

use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Farms;
use app\models\Plantingstructure;
use app\models\Plant;
use app\models\Lease;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Url;
use yii\widgets\ActiveFormrdiv;
use app\models\Search;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
     
  <?= $this->render('..//search/searchindex',['tab'=>$tab,'management_area'=>$management_area,'begindate'=>$begindate,'enddate'=>$enddate]);?>

 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'total' => '<tr>
						<td></td>
						<td align="center"><strong>合计</strong></td>
						<td><strong>'.Farms::getRows($params).'户</strong></td>
						<td><strong>'.Farms::getFarmerrows($params).'个</strong></td>
						<td></td>
						<td></td>
						<td><strong>'.Farms::getFarmarea($params).'万亩</strong></td>						
						<td></td>
					</tr>',
        'columns' => Search::getColumns(['management_area','farmname','farmername','address','telephone','measure','operation'],$params),

    ]); ?>
                </div>
            </div>
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
