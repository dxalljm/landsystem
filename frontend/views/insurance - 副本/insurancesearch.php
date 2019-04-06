<?php

use app\models\Tables;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Plant;
use app\models\Insurancecompany;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Search;
use frontend\helpers\arraySearch;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<script type="text/javascript" src="vendor/bower/echarts/build/dist/echarts.js"></script>
<script type="text/javascript" src="vendor/bower/echarts/build/dist/echarts.min.js"></script>
<div class="lease-index">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">

              
<?= $this->render('..//search/searchindex',['tab'=>$tab,'begindate'=>$begindate,'enddate'=>$enddate,'params'=>$params]);?>
<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
?>

    <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
              <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">保险作物图表</a></li>
                <li class=""><a href="#timeline2" data-toggle="tab" aria-expanded="false">承保公司图表</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
                 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
                    'total' => '<tr>
						<td></td>		
						<td align="center"><strong>'.$data->count('farmer_id').'户</strong></td>
						<td><strong>法人'.$data->where(['nameissame'=>1])->count().'个</strong></td>
						<td><strong>租赁'.$data->where(['nameissame'=>0])->count().'个</strong></td>
						<td><strong>'.$data->sum('contractarea').'亩</strong></td>
						<td><strong>'.$data->sum('insuredarea').'亩</strong></td>
						<td><strong>'.$data->sum('insuredsoybean').'亩</strong></td>
						<td><strong>'.$data->sum('insuredwheat').'亩</strong></td>
						<td><strong>'.$data->sum('insuredother').'亩</strong></td>
						<td><strong>'.$data->count('company_id').'个</strong></td>
						<td><strong>完成'.$data->where(['state'=>1])->count().'个</strong></td>
						<td></td>
						<td></td>
					</tr>',
        'columns' => Search::getColumns(['management_area','farms_id','farmername','policyholder','contractarea','insuredarea','insuredsoybean','insuredwheat','insuredother','company_id','operation'],$totalData),
    ]);
                 $plantdata = [
                     ['value'=>$data->sum('insuredsoybean'),'name'=>'大豆'],
                     ['value'=>$data->sum('insuredwheat'),'name'=>'小麦'],
                     ['value'=>$data->sum('insuredother'),'name'=>'其它']
                 ];
                 $companydata = [];
                 $companys = Insurancecompany::getCompanyList();
                 foreach ($companys as $key => $company) {
                     $companydata[] = [
                        'value' => $data->where(['company_id'=>$key])->count(),
                         'name' => $company,
                     ];
                 }
                 ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                <div id="insuranceplant" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				showPie('insuranceplant','种植业保险农作物图表占比信息',<?= json_encode(['大豆','小麦','其它'])?>,'保险作物',<?= json_encode($plantdata)?>,'亩');
		</script>

            </div>
                <div class="tab-pane" id="timeline2">
                    <div id="insurancecompany" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
                    <script type="text/javascript">
                        showPie('insurancecompany','种植业保险承保公司图表占比信息',<?= json_encode($data->getName('Insurancecompany','companynname','company_id')->typenameList())?>,'承保公司',<?= json_encode($companydata)?>,'个');
                    </script>

                </div>
          </div>
 
                </div>

                </div>
            </div>
        </div>
    </div>
</section>
</div>
