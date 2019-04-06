<?php

use app\models\Tables;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Subsidiestype;
use app\models\Plant;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Search;
use frontend\helpers\arraySearch;
use app\models\Huinong;
use app\models\BankAccount;
use app\models\Lease;
use app\models\Subsidyratio;
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
$arrclass = explode('\\',$dataProvider->query->modelClass);
?>

<div class="nav-tabs-custom">
    <ul class="nav nav-pills nav-pills-warning">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
              <?php foreach(Huinong::getTypename() as $key => $value) {
//               	var_dump($value);exit;
              			//echo '<li class=""><a href="#huinongview'.$key.'" data-toggle="tab" aria-expanded="false">'.$value.'图表</a></li>';
			  		}
			  	?>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
               <?= GridView::widget([
                   'dataProvider' => $dataProvider,
                   'filterModel' => $searchModel,
                   'total' => '<tr height="40">
													<td></td>
													<td align="left" id="pt0"><strong>合计</strong></td>
													<td align="left" id="pt1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
													<td align="left" id="pt2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
													<td align="left" id="pt3"></td>
													<td align="left" id="pt4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
													<td align="left" id="pt5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
													<td align="left" id="pt6"><strong></strong></td>
													<td align="left" id="pt7"><strong></strong></td>
													<td align="left" id="pt8"><strong></strong></td>
													<td align="left" id="pt9"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
													<td align="left" id="pt10"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
												</tr>',
                   'columns' => [
                       ['class' => 'yii\grid\SerialColumn'],
                       [
                           'label' => '管理区',
                           'attribute' => 'management_area',
                           'headerOptions' => ['width' => '150'],
                           'value' => function ($model) {
                               // 				            	var_dump($model);exit;
                               return ManagementArea::getAreanameOne($model->management_area);
                           },
                           'filter' => ManagementArea::getAreaname(),
                       ],
                       [
                           'label' => '农场名称',
                           'attribute' => 'farms_id',
                           'options' => ['width' => 150],
                           'value' => function ($model) {

                               return Farms::find()->where([
                                   'id' => $model->farms_id
                               ])->one()['farmname'];

                           }
                       ],
                       [
                           'label' => '法人名称',
                           'attribute' => 'farmer_id',
                           'options' => ['width' => 120],
                           'value' => function ($model) {

                               return Farms::find()->where([
                                   'id' => $model->farms_id
                               ])->one()['farmername'];

                           }
                       ],
                       [
                           'label' => '合同号',
                           'attribute' => 'state',
                           'options' => ['width' => 180],
                           'value' => function ($model) {
                               return Farms::find()->where([
                                   'id' => $model->farms_id
                               ])->one()['contractnumber'];
                           },
                           'filter' => [1 => '正常', 2 => '未更换合同', 3 => '临时性管理', 4 => '买断合同'],
                       ],
                       [
                           'label' => '合同面积',
                           'attribute' => 'contractarea',
                           'options' => ['width' => 150],
                           'value' => function ($model) {
                               return $model->contractarea;
                           }
                       ],
//												'area',
                       [
                           'label' => '补贴对象',
                           'attribute' => 'lessee',
                           'options' => ['width' => 120],
                           'value' => function ($model) {
                               $farm = Farms::findOne($model->farms_id);
                               if ($model->lease_id == 0) {
                                   return $farm['farmername'];
                               }
                               $lease = Lease::findOne($model->lease_id);
                               $sub = Subsidyratio::getSubsidyratio($model->plant_id, $model['farms_id'], $model->lease_id);
                               $farmerp = (float)$sub['farmer'] / 100;
                               $lesseep = (float)$sub['lessee'] / 100;
                               if (bccomp($farmerp, 1) == 0) {
                                   return $farm['farmername'];
                               }
                               return $lease['lessee'];
                           }
                       ],
                       [
                           'label' => '身份证号码',
                           'value' => function ($model) {
                               $farm = Farms::findOne($model->farms_id);
                               if ($model->lease_id == 0) {
                                   return $farm->cardid;
                               } else {
                                   $lease = Lease::findOne($model->lease_id);
                                   $sub = Subsidyratio::getSubsidyratio($model->plant_id, $model['farms_id'], $model->lease_id);
                                   $farmerp = (float)$sub['farmer'] / 100;
                                   $lesseep = (float)$sub['lessee'] / 100;
                                   if (bccomp($farmerp, 1) == 0) {
                                       return $farm['cardid'];
                                   }
                                   return $lease['lessee_cardid'];
                               }
                           }
                       ],
                       [
                           'label' => '一折(卡)通账号',
                           'format' => 'raw',
                           'value' => function ($model) {
                               $farm = Farms::findOne($model->farms_id);
                               if ($model->lease_id == 0) {
                                   $bank = BankAccount::find()->where(['cardid' => $farm->cardid, 'farms_id' => $model->farms_id])->one();
                                   if ($bank)
                                       return $bank->accountnumber;
                               } else {
                                   $lease = Lease::findOne($model->lease_id);
                                   $sub = Subsidyratio::getSubsidyratio($model->plant_id, $model['farms_id'], $model->lease_id);
                                   $farmerp = (float)$sub['farmer'] / 100;
                                   $lesseep = (float)$sub['lessee'] / 100;
                                   if (bccomp($farmerp, 1) == 0) {
                                       $bank = BankAccount::find()->where(['cardid' => $farm->cardid, 'farms_id' => $model->farms_id])->one();
                                       if ($bank)
                                           return $bank->accountnumber;
                                   }
                                   $bank = BankAccount::find()->where(['cardid' => $lease['lessee_cardid'], 'farms_id' => $model->farms_id])->one();
                                   if ($bank)
                                       return $bank->accountnumber;
                               }
                               return '<span class="text text-red">未填写银行账号</span>';
                           }
                       ],
//												[
//													'label' => '联系方式',
//													'value' => function ($model) {
//														$farm = Farms::findOne($model->farms_id);
//														if($model->lease_id == 0) {
//															return $farm->telephone;
//														} else {
//															$lease = Lease::findOne($model->lease_id);
//															$sub = Subsidyratio::getSubsidyratio($model->plant_id,$model['farms_id']);
//															$farmerp = (float)$sub['farmer']/100;
//															$lesseep = (float)$sub['lessee']/100;
//															if(bccomp($farmerp,1) == 0) {
//																return $farm->telephone;
//															}
//															return $lease['lessee_telephone'];
//														}
//													}
//												],
                       [
                           'label' => '种植结构',
                           'attribute' => 'plant_id',
                           'value' => function ($model) {
                               return Plant::find()->where(['id' => $model->plant_id])->one()['typename'];
                           },
                           'filter' => Huinong::getPlant(),
                       ],
                       [
                           'attribute'=>'area',
                           'options' => ['width' => 150],
                       ],
                       [
                           'label' => '补贴金额',
                           'options' => ['width' => '160'],
                           'value' => function ($model) {
                               $h = Huinong::find()->where(['year' => $model->year, 'typeid' => $model->plant_id])->one();
                               $p = $h['subsidiesarea'] / 100;
                               $r = bcmul($h['subsidiesmoney'], $model->area, 2);
                               return bcmul($r, $p, 2);
                           },
                       ],
                   ],

               ]);

               ?>
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
              //wdjHuinong('<?//= $classname?>',<?//= json_encode(['实发金额','应发金额'])?>,<?//= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?//= json_encode($echartsData['all'])?>,<?= json_encode($echartsData['real'])?>,'元');
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
<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#pt1').html(data + '户');
        });

        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#pt2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#pt4').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-lease_id',andwhere:'<?= json_encode('lease_id>0')?>'}, function (data) {
            $('#pt5').html('生产者'+ data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-area'}, function (data) {
            $('#pt9').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'mulhuinongSum'}, function (data) {
            $('#pt10').html(data + '元');
        });

    });
</script>
		