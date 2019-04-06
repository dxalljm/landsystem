<?php
namespace frontend\controllers;use app\models\User;
use yii;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Plant;
use app\models\Subsidiestype;
use yii\helpers\Url;
use app\models\Farms;
use app\models\Lease;
use frontend\helpers\MoneyFormat;
use app\models\Dispute;
use app\models\Collection;
use app\models\Goodseed;
use frontend\helpers\ActiveFormrdiv;
use app\models\Huinong;
use app\models\Huinonggrant;
use app\models\ManagementArea;
use yii\helpers\ArrayHelper;
use frontend\helpers\arraySearch;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\HuinongSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'huinong';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
$total = arraySearch::find($data)->search();
$alltotal = arraySearch::find($allData)->search();
$result = [];
// $cacheKey = 'cache-key-huinongprovide1'.\Yii::$app->getUser()->id;
// $resultcache = Yii::$app->cache->get($cacheKey);
// if (!empty($resultcache)) {
// 	$result = $resultcache;
// } else {
	$result['all'] = $alltotal->count();
	$result['state1'] = $alltotal->where(['state'=>1])->count();
	$result['state0'] = $alltotal->where(['state'=>0])->count();
	$result['yfmoney'] = MoneyFormat::num_format($alltotal->sum('money'));
	$result['realmoney'] = MoneyFormat::num_format($alltotal->where(['state'=>1])->sum('money'));
	$result['cha'] = MoneyFormat::num_format($alltotal->where(['state'=>0])->sum('money'));
// 	Yii::$app->cache->set ( $cacheKey, $result, 999999 );
// }
?>
<style type="text/css">
#textSubmit { display:none }
</style>
<div class="huinong-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
                <?php $huinongGrant = Huinonggrant::find();
                
                ?>
                <table class="table table-bordered table-hover">
                	<tr>
                		<td align="right"><strong>享受补贴人数：</strong></td>
                		<td align="left"><strong>
               		    <?= $result['all']?>
               		    人</strong></td>
                		<td align="right"><strong>已发放补贴人数：</strong></td>
                		<td align="left"><strong>
               		    <?= $result['state1']?>
               		    人</strong></td>
                		<td align="right"><strong>未发放补贴人数：</strong></td>
                		<td align="left"><strong>
               		    <?= $result['state0']?>
               		    人</strong></td>
                		<td align="right"><strong>应发放金额：</strong></td>
                		<td align="left"><strong>
               		    <?= $result['yfmoney']?>
               		    元</strong></td>
                		<td align="right"><strong>已发放金额：</strong></td>
                		<td align="left"><strong>
               		    <?= $result['realmoney']?>
               		    元</strong></td>
                		<td align="right"><strong>差额：</strong></td>
                		<td align="left"><strong>
               		    <?= $result['cha']?>
               		    元</strong></td>
                		<td align="right"><strong>完成度：</strong></td>
                		
                		<td align="left"><strong><?php if($result['state1']) $wcd = $result['state1']/$result['all'];else $wcd = 0;echo sprintf ( "%.2f", $wcd ).'%'; ?></strong></td>
                	</tr>
                </table>
               
                <?php $form = ActiveFormrdiv::begin(); ?>
    			<br>
    			<table class="table table-bordered table-hover">
    			<?php if($classname == 'plantingstructure') {?>
    				
    				<tr>
    					<td width="28" align="center"><b>序号</b></td>
    					<td width="51" align="center"><b>管理区</b></td>
    					<td width="51" align="center"><b>农场名称</b></td>
    					<td width="28" align="center"><b>法人</b></td>
    					<td width="40" align="center"><b>租赁者</b></td>
    					<?php if($model->subsidiestype_id == 'plant') {?>
    						<td width="28" align="center"><b>作物</b></td>
    					<?php }?>
    					<?php if($model->subsidiestype_id == 'goodseed') {?>
	    					<td width="28" align="center"><b>作物</b></td>
	    					<td width="28" align="center"><b>良种</b></td>
    					<?php }?>
    					<td width="28" align="center"><b>纠纷</b></td>
    					<td width="56" align="center"><b>缴费情况</b></td>
    					<td align="center"><b>面积</b></td>
    					<td align="center"><b>补贴金额</b></td>
    					<td colspan="2" align="center"><b>状态</b></td>
    				</tr>
    				<tr>
    					<td width="28" align="center"><b></b></td>
    					<?php $managementArea_array = ArrayHelper::map(ManagementArea::find()->where(['id'=>Farms::getManagementArea()['id']])->all(), 'id', 'areaname');
                	if(count($managementArea_array) > 1)
                		array_splice($managementArea_array,0,0,[0=>'全部']);
                	if(isset($post['management_area']))
                		$areaValue = $post['management_area'];
                	else 
                		$areaValue = '';
                	?>
    					<td width="28" align="center"><b><?= html::dropDownList('management_area',$areaValue,$managementArea_array,['class'=>'form-control','id'=>'managementarea'])?></b></td>
    					<td align="center"><?= html::textInput('farmname',$post['farmname'],['class'=>'form-control'])?></td>
    					<td align="center"><b><?= html::textInput('farmername',$post['farmername'],['class'=>'form-control'])?></b></td>
    					<td align="center"><b><?= html::textInput('lesseename',$post['lesseename'],['class'=>'form-control'])?></b></td>
    					<?php if($model->subsidiestype_id == 'plant') {?>
    						<td width="28" align="center"><b></b></td>
    					<?php }?>
    					<?php if($model->subsidiestype_id == 'goodseed') {?>
	    					<td width="28" align="center"><b></b></td>
	    					<td width="28" align="center"><b></b></td>
    					<?php }?>
    					<td width="28" align="center"><b></b></td>
    					<td width="56" align="center"><b></b></td>
    					<td align="center"></td>
    					<td align="center"></td>
    					<td colspan="2" align="center" width="150"><b><?= html::radioList('is_provide',$post['is_provide'],[0=>'未发',1=>'已发'],['id'=>'isprovide'])?></b></td>
    				</tr>
    				<?php 
    				$i=1;$areaSum=0.0;$moneySum=0.0;
    				foreach ($data as $value) {
    				?>
    				<tr><?php $farm = Farms::find()->where(['id'=>$value['farms_id']])->one();?>
    					<td align="center"><?= $i++ ?></td>
    					<td align="center"><?= ManagementArea::find()->where(['id'=>$farm->management_area])->one()['areaname'] ?></td>
    					<td align="center"><?= $farm->farmname ?></td>
    					<td align="center"><?= $farm->farmername ?></td>
    					<td align="center"><?= Lease::find()->where(['id'=>$value['lease_id']])->one()['lessee']?></td>
    					
    					<?php if($model->subsidiestype_id == 'plant') {?>
    						<td align="center"><?= Plant::find()->where(['id'=>$model->typeid])->one()['typename']?></td>
    					<?php }?>
    					<?php if($model->subsidiestype_id == 'goodseed') {
    						$goodseed = Goodseed::find()->where(['id'=>$model->typeid])->one();
    					?>
    						<td align="center"><?= Plant::find()->where(['id'=>$goodseed['plant_id']])->one()['typename']?></td>
    						<td align="center"><?= $goodseed['typename']?></td>
    					<?php }?>
    					<td align="center"><?= Dispute::find()->where(['farms_id'=>$value['farms_id']])->count().'条'?></td>
    					<td align="center"><?= Collection::getCollecitonInfo($value['farms_id'])?></td>
    					<td align="center"><?= $value['area'].' 亩'?></td>
    					<td align="center"><?= $value['money'].' 元'?></td>
    					<td colspan="2" align="center"><?php if($value['state']) echo '已发放'; else echo  html::checkboxList('isSubmit[]',$value['state'],[$value['id']=>'是否发放']);?></td>
    				</tr><?php $areaSum += $value['area'];$moneySum += $value['money'];?>
    				<?php }?>
    				<tr>
    					<td align="center"><b>合计</b></td>
    					<td align="center"><b></b></td>
    					<td align="center"><b></b></td>
    					<td align="center"><b></b></td>
    					<?php if($model->subsidiestype_id == 'plant') {?>
    						<td align="center"><b></b></td>
    					<?php }?>
    					<?php if($model->subsidiestype_id == 'goodseed') {?>
    						<td align="center"><b></b></td>
    						<td align="center"><b></b></td>
    					<?php }?>
    					<td align="center"><b></b></td>
    					<td align="center"><b></b></td>
    					<td align="center" width="100"><b></b></td>
    					<td align="center" width="100"><b><?php echo MoneyFormat::num_format($areaSum).'亩';?></b></td>
    					<td align="right" width="53"><b><?php echo MoneyFormat::num_format($moneySum).'元';?></b></td><?php $yfmoney = Huinonggrant::find()->where(['huinong_id'=>$model->id,'state'=>1])->sum('money')?>
    					<td align="center" width="120"><b>已发：<?php echo html::textInput('moneySum',MoneyFormat::num_format($yfmoney),['readonly'=>'readonly','id'=>'money','width'=>14]);?></b></td>
    				</tr>
    				<?php }?>
    			</table>
<input type="submit" id="textSubmit">
    <?php ActiveFormrdiv::end(); ?>
                </div>
                
            </div>
        </div>
    </div>
</section>
</div>
<script>
$('input:checkbox[name="isSubmit[]"]').click(function(){
	var input = $(this).is(":checked");
	var areaSum = $('#area').val();
	var moneySum = $('#money').val();
	var val = $(this).val();
	var valArr = val.split('/');
	if(input == true) {
		if(confirm("确定已经发放了吗？"))
		 {
			var areaResult = areaSum*1 + valArr[3]*1;
			$('#area').val(areaResult.toFixed(2));
			var moneyResult = moneySum*1 + valArr[2]*1;
			$('#money').val(moneyResult.toFixed(2));
			$("form").submit();
		} else {
			 $(this).prop('checked', false);
		}
		
	} else {
		var areaResult = areaSum*1 - valArr[3]*1;
		$('#area').val(areaResult.toFixed(2));
		var moneyResult = moneySum*1 - valArr[2]*1;
		$('#money').val(moneyResult.toFixed(2));
	}
});
$('#isprovide').click(function(){
	$("form").submit();
});
$('#managementarea').change(function(){
	$("form").submit();
});
// function setSum(money,area)
// {
// 	var areaSum = $('#area').val();
// 	var moneySum = $('#money').val();
// 	alert($('input:checkbox[name="isSubmit[]"]').is(":checked"));
// 		if($('input:checkbox[name="isSubmit[]"]').is(":checked")==true) {
// 			$('#area').val(areaSum*1 + area*1);
// 			$('#money').val(moneySum*1 + money*1);
// 		} else {
// 			$('#area').val(areaSum*1 - area*1);
// 			$('#money').val(moneySum*1 - money*1);
// 		}
	
	
// }
</script>
