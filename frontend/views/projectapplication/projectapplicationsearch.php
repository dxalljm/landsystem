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
use app\models\Breedinfo;
use app\models\Projectapplication;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="lease-index">
<?= $this->render('..//search/searchindex',['tab'=>$tab,'management_area'=>$management_area,'begindate'=>$begindate,'enddate'=>$enddate]);?>
<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
              <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">图表</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
                <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'total' => '<tr>
			        <td></td>
			        <td align="center"><strong>合计</strong></td>
			        <td><strong>'.Projectapplication::getFarmRows($params).'户</strong></td>
			        <td><strong>'.Projectapplication::getFarmerrows($params).'个</strong></td>
			        <td><strong>'.Projectapplication::getProjecttype($params).'个</strong></td>
			        <td><strong></strong></td>
			        </tr>',
        'columns' => Search::getColumns(['management_area','farms_id','farmer_id','projecttype'],$params),
    ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <?php //var_dump(Projectapplication::getTypenamelist($params));?>
                <div id="projectapplication" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				showAllShadowProject('projectapplication',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode(Projectapplication::getTypenamelist($params)['projecttype'])?>,<?= Projectapplication::getProjectapplication($params)?>,<?= json_encode(Projectapplication::getTypenamelist($params)['unit'])?>);
				//showStacked('collection','应收：<?php //echo Collection::totalAmounts()?> 实收：<?php //echo Collection::totalReal()?>',<?php //echo json_encode(Farms::getManagementArea('small')['areaname'])?>,'',<?php //echo Collection::getCollection()?>,'万元');
		</script>

            </div>
          </div>
 
                </div>
            </div>
        </div>
    </div>
</section>
</div>
