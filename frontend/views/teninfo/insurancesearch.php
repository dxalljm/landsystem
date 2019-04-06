<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Insurancecompany;
use app\models\ManagementArea;
use frontend\helpers\arraySearch;
use app\models\Insurance;
use frontend\helpers\Echartsdata;
use frontend\helpers\ES;
use app\models\User;
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

<?= $this->render('..//search/searchindex',['tab'=>$tab,'class'=>$class,'begindate'=>$begindate,'enddate'=>$enddate,'params'=>$params]);?>
<?php 
	$totalData = clone $dataProvider;
	$totalData->pagination = ['pagesize'=>0];
	$data = arraySearch::find($totalData)->search();
$arrclass = explode('\\',$dataProvider->query->modelClass);
//'total' => '<tr>
//						<td></td>
//		<td></td>
//						<td align="center"><strong>'.$data->where(['state'=>1])->count('farms_id').'户</strong></td>
//						<td><strong>法人'.$data->where(['nameissame'=>1,'state'=>1])->count().'个</strong></td>
//						<td><strong>租赁'.$data->where(['nameissame'=>0,'state'=>1])->count().'个</strong></td>
//						<td><strong>'.$data->where(['state'=>1])->sum('contractarea').'亩</strong></td>
//						<td><strong>'.$data->where(['state'=>1])->sum('insuredarea').'亩</strong></td>
//						<td><strong>'.$data->where(['state'=>1])->sum('insuredsoybean').'亩</strong></td>
//						<td><strong>'.$data->where(['state'=>1])->sum('insuredwheat').'亩</strong></td>
//						<td><strong>'.$data->where(['state'=>1])->sum('insuredother').'亩</strong></td>
//						<td><strong>'.$data->count('company_id').'个</strong></td>
//						<td><strong>完成'.$data->where(['state'=>1])->count().'</strong></td>
//
//
//					</tr>',
?>
            <ul class="nav nav-pills nav-pills-warning">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">数据表</a></li>
              <li class=""><a href="#plantechartslist" data-toggle="tab" aria-expanded="false">保险作物图表</a></li>
                <li class=""><a href="#companyechartslist" data-toggle="tab" aria-expanded="false">承保公司图表</a></li>
                <li class=""><a href="#insurancenumberlist" data-toggle="tab" aria-expanded="false">农业保险情况图表（数量 ）</a></li>
                <li class=""><a href="#insurancearealist" data-toggle="tab" aria-expanded="false">农业保险情况图表（面积）</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
                 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
                     'total' =>  '<tr height="40">
                                        <td></td>		
                                        <td><strong>合计</strong></td>
                                        <td align="left" id="t1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t7"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t8"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t9"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="t10"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
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
                         'policyholder',
                         'contractarea',
                         'insuredarea',
                         'insuredsoybean',
                         'insuredwheat',
                         'insuredother',
                         [
                             'attribute' => 'company_id',
                             'options' =>['width' => '150'],
                             'value' => function($model) {
                                 $company = Insurancecompany::find()->where(['id'=>$model->company_id])->one();
                                 return $company['companynname'];
                             },
                             'filter' => ArrayHelper::map(Insurancecompany::find()->all(),'id','companynname'),
                         ],
                         [
                             'label' => '操作',
                             'format' => 'raw',
                         	'options' => ['width' => '100'],
                             'value' => function($model) {
                                 $option = '查看详情';
                                 $title = '';
                                 $url = '#';
                                 $url = [Yii::$app->controller->id.'/'.Yii::$app->controller->id.'view','id'=>$model->id,'farms_id'=>$model->farms_id];
                                 $html = Html::a($option,$url, [
                                     'id' => 'moreOperation',
                                     'title' => $title,
                                     'class' => 'btn btn-primary btn-xs',
                                 ]);
//                                 $html .= Html::a('电子信息采集',Url::to(['photograph/photographindex','farms_id'=>$model->id]),['class' => 'btn btn-primary btn-xs',]);
                                 return $html;
                             }
                         ],
                     ],
    ]);
                 $plantdata = [
                     ['value'=>$data->sum('insuredsoybean'),'name'=>'大豆'],
                     ['value'=>$data->sum('insuredwheat'),'name'=>'小麦'],
                     ['value'=>$data->sum('insuredother'),'name'=>'其它']
                 ];
//                 var_dump($plantdata);exit;
                 $companydata = [];
                 $companys = Insurancecompany::getCompanyList();
                 foreach ($companys as $key => $company) {
                     $companydata[] = [
                        'value' => $data->where(['company_id'=>$key])->count(),
                         'name' => $company,
                     ];
                 }
//                  var_dump($companydata);exit;
                $insuranceCount = $data->where(['state'=>1])->count('farms_id');
                $insuranctArea = Insurance::find()->where(['state'=>1])->sum('insuredarea');
                $farmsCount = Farms::find()->where(['state'=>1])->count();
                $farmsArea = Farms::find()->where(['state'=>1])->sum('contractarea');
//                 $insuranceCount = 0;
//                 $farmsCount = 0;
//                 $insuranctArea = 0;
//                 $farmsArea = 0;
                $noCount = $farmsCount - $insuranceCount;
                $noArea = $farmsArea - $insuranctArea;
                
                 ?>
              </div>
              <!-- /.tab-pane -->
                <div class="tab-pane" id="plantechartslist">
                    <?php
                    $legend = ['大豆','小麦','其它'];
                    $series = Echartsdata::getPlantCache($totalData);
                    //                  echo ES::pietest()->DOM('test222',true,'1500px','800px')->options(['title' => '种植业保险农作物饼形图表','name'=>'农作物','legend'=>$legend,'series'=>$series,'unit'=>'亩'])->JS();
                    //                  var_dump($series);
                    //                  饼形图,options=['title'=>'某站点用户访问来源','legend'=>['直接访问','邮件营销','联盟广告','视频广告','搜索引擎'],'series'=>[['value'=>335, 'name'=>'直接访问'], ['value'=>310, 'name'=>'邮件营销'], ['value'=>234, 'name'=>'联盟广告'], ['value'=>135, 'name'=>'视频广告'], ['value'=>1548, 'name'=>'搜索引擎']]]
                    echo ES::pie()->DOM('insuranceplant2',true,'1500px','500px')->options(['title' => '种植业保险农作物饼形图表','name'=>'农作物','legend'=>$legend,'series'=>$series,'unit'=>'亩'])->JS();
                    ?>

                </div>
                <div class="tab-pane" id="companyechartslist">
                    <?php
                    $legend = Echartsdata::getCompanyUse($totalData,true);
                    $series = Echartsdata::getCompanyCache($totalData);
                    //                  echo ES::pietest()->DOM('test222',true,'1500px','800px')->options(['title' => '种植业保险农作物饼形图表','name'=>'农作物','legend'=>$legend,'series'=>$series,'unit'=>'亩'])->JS();
                    //                  var_dump($series);
                    //                  饼形图,options=['title'=>'某站点用户访问来源','legend'=>['直接访问','邮件营销','联盟广告','视频广告','搜索引擎'],'series'=>[['value'=>335, 'name'=>'直接访问'], ['value'=>310, 'name'=>'邮件营销'], ['value'=>234, 'name'=>'联盟广告'], ['value'=>135, 'name'=>'视频广告'], ['value'=>1548, 'name'=>'搜索引擎']]]
                    echo ES::pie()->DOM('insuranceplant33',true,'1500px','500px')->options(['title' => '种植业保险公司饼形图表','name'=>'保险公司','xAxis'=>$legend,'series'=>$series,'unit'=>'户'])->JS();
                    ?>

                </div>
                <div class="tab-pane" id="insurancenumberlist">
                    <?php
                    $series = Echartsdata::getNumberCache($totalData);
                    //                  饼形图,options=['title'=>'某站点用户访问来源','legend'=>['直接访问','邮件营销','联盟广告','视频广告','搜索引擎'],'series'=>[['value'=>335, 'name'=>'直接访问'], ['value'=>310, 'name'=>'邮件营销'], ['value'=>234, 'name'=>'联盟广告'], ['value'=>135, 'name'=>'视频广告'], ['value'=>1548, 'name'=>'搜索引擎']]]
                    echo ES::pie()->DOM('farmsinsuranceinfo',true,'1500px','500px')->options(['title' => '种植业保险数量占比信息','name'=>'保险数量','xAxis'=>['已参加保险','未参加保险'],'series'=>$series,'unit'=>'户'])->JS();
                    ?>

                </div>
                <div class="tab-pane" id="insurancearealist">
                    <?php
                    $series = Echartsdata::getAreaCache($totalData);
                    //                  饼形图,options=['title'=>'某站点用户访问来源','legend'=>['直接访问','邮件营销','联盟广告','视频广告','搜索引擎'],'series'=>[['value'=>335, 'name'=>'直接访问'], ['value'=>310, 'name'=>'邮件营销'], ['value'=>234, 'name'=>'联盟广告'], ['value'=>135, 'name'=>'视频广告'], ['value'=>1548, 'name'=>'搜索引擎']]]
                    echo ES::pie()->DOM('farmsinsuranceinfoarea',true,'1500px','500px')->options(['title' => '种植业保险面积占比信息','name'=>'保险面积','xAxis'=>['已参加保险','未参加保险'],'series'=>$series,'unit'=>'亩'])->JS();
                    ?>

                    <?php User::dataListEnd();?>

                </div>

                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script>
    $('.shclDefault').shCircleLoader();
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
//            if(data)
            $('#t1').html(data + '户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-nameissame',andwhere:'<?= json_encode(['nameissame'=>1])?>'}, function (data) {
            $('#t2').html('法人'+data + '个');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-nameissame',andwhere:'<?= json_encode(['nameissame'=>0])?>'}, function (data) {
            $('#t3').html('租赁'+data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#t4').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-insuredarea'}, function (data) {
            $('#t5').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-insuredsoybean'}, function (data) {
            $('#t6').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-insuredwheat'}, function (data) {
            $('#t7').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-insuredother'}, function (data) {
            $('#t8').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-company_id'}, function (data) {
            $('#t9').html(data + '个');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-state',andwhere:'<?= json_encode(['state'=>1])?>'}, function (data) {
            $('#t10').html('完成'+data+'个');
        });
    });

</script>