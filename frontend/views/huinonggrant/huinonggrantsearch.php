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
use app\models\Huinong;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
              
   <?= $this->render('..//search/searchindex',['tab'=>$tab,'begindate'=>$begindate,'enddate'=>$enddate,'params'=>$params]);?>
<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
	$namelist = $data->getName('Subsidiestype', 'typename', ['Huinong','huinong_id','subsidiestype_id'])->getList();
	
?>

<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
              <?php foreach(Huinong::getTypename() as $key => $value) {
//               	var_dump($value);exit;
              			echo '<li class=""><a href="#huinongview'.$key.'" data-toggle="tab" aria-expanded="false">'.$value.'图表</a></li>';
			  		}
			  	?>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
               <?= GridView::widget([
			        'dataProvider' => $dataProvider,
			        'filterModel' => $searchModel,
			        'total' => '<tr>
						        <td></td>
						        <td align="center"><strong>合计</strong></td>
						        <td><strong>'.$data->count('farms_id').'个</strong></td>
						        <td><strong>'.$data->count('farmer_id').'个</strong></td>
						        <td><strong>'.$data->count('lease_id').'个</strong></td>
								<td><strong></strong></td>
								<td><strong>'.$data->count('huinong_id').'个</strong></td>
						        <td><strong>'.$data->sum('money').'元</strong></td>
						        <td><strong>'.$data->sum('area').'亩</strong></td>
						        </tr>',
			        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','lease_id','subsidiestype_id','typeid','money','area'],$totalData),
			    ]); ?>
              </div>
              <?php foreach(Huinong::getTypename() as $key => $value) {
              $classname = 'huinong'.$key;
              	?>
              <!-- /.tab-pane -->
              <div class='tab-pane' id="huinongview<?= $key?>">
              <div id="<?= $classname?>" style="width:1000px; height: 600px; margin: 0 auto"></div>
				<?php $echartsData = $data->getName('Subsidiestype', 'typename', 'subsidiestype_id')->huinongShowShadow($key);?>
              </div>
              <script type="text/javascript">
              wdjHuinong('<?= $classname?>',<?= json_encode(['实发金额','应发金额'])?>,<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode($echartsData['all'])?>,<?= json_encode($echartsData['real'])?>,'元');
			</script>
              <!-- /.tab-pane -->
            <!-- /.tab-content -->
            <?php }?>
          </div>
                </div>
            </div>
        </div>
    </div>
</section>
</div>

		