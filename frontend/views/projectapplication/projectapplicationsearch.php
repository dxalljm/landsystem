<?php

use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Infrastructuretype;
use app\models\Plant;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Search;
use app\models\Projectapplication;
use frontend\helpers\arraySearch;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>

<div class="lease-index">

<?= $this->render('..//search/searchindex',['tab'=>$tab,'begindate'=>$begindate,'enddate'=>$enddate,'params'=>$params]);?>
<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
?>
<div class="nav-tabs-custom">
    <ul class="nav nav-pills nav-pills-warning">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
              <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">图表</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
                <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
                    'total' => '<tr height="40">
                                        <td></td>
                                        <td align="left" id="t0"><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td></td>
                                        <td></td>
                                    </tr>',
                    'columns' => [
                        ['class' => 'yii\grid\SerialColumn'],
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
                        [
                            'label' => '农场名称',
                            'attribute' => 'farms_id',
                            'options' =>['width'=>120],
                            'value' => function ($model) {

                                return Farms::find ()->where ( [
                                    'id' => $model->farms_id
                                ] )->one ()['farmname'];

                            }
                        ],
                        [
                            'label' => '法人名称',
                            'attribute' => 'farmer_id',
                            'options' =>['width'=>120],
                            'value' => function ($model) {

                                return Farms::find ()->where ( [
                                    'id' => $model->farms_id
                                ] )->one ()['farmername'];

                            }
                        ],
                        [
                            'attribute' => 'projecttype',
                            'value'=> function($model,$params) {
                                return Infrastructuretype::find()->where(['id'=>$model->projecttype])->one()['typename'];
                            },
                            'filter' => Infrastructuretype::getAllname($params),
                        ],
                        [
                            'attribute' => 'projectdata',
                            'value' => function ($model) {
                                return $model->projectdata.$model->unit;
                            }
                        ],
                        [
                            'label' => '工程情况',
                            'value' => function ($model) {
                                $plan = Projectplan::find()->where(['project_id'=>$model->id])->one();
                                if($plan) {
                                    $now = time();
                                    if($now<=$plan['begindate'])
                                        return '未开始';
                                    if($now<=$plan['enddate'] and $now >= $plan['begindate'])
                                        return '施工中';
                                    if($now >= $plan['enddate'])
                                        return '工程结束';
                                } else {
                                    return '还没有工程计划';
                                }
                            }
                        ],
                    ],
                ]); ?>
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
              <?php //var_dump($data->getName('Infrastructuretype', 'typename', 'projecttype')->typenameList());?>
                <div id="projectapplication" style="width: 900px; height: 600px; margin: 0 auto"; ></div>
				<script type="text/javascript">
				showAllShadowProject('projectapplication',<?= json_encode(Farms::getManagementArea('small')['areaname'])?>,<?= json_encode($data->getName('Infrastructuretype', 'typename', 'projecttype')->typenameList())?>,<?= $data->getName('Infrastructuretype', 'typename', 'projecttype')->showAllShadow('sum','projectdata');?>,<?= json_encode(Projectapplication::getTypenamelist($params)['unit'])?>);
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
<script>
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t1').html(data + '户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-projecttype'}, function (data) {
            $('#t3').html(data + '个');
        });
    });
</script>