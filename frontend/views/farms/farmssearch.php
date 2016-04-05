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
use frontend\helpers\arraySearch;
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
