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
// $total = arraySearch::find($data)->search();
$alltotal = arraySearch::find($allData)->search();
$result = [];
	$result['all'] = $alltotal->count();
	$result['state1'] = $alltotal->where(['state'=>1])->count();
	$result['state0'] = $alltotal->where(['state'=>0])->count();
	$result['yfmoney'] = MoneyFormat::num_format($alltotal->sum('money'));
	$result['realmoney'] = MoneyFormat::num_format($alltotal->where(['state'=>1])->sum('money'));
	$result['cha'] = MoneyFormat::num_format($alltotal->where(['state'=>0])->sum('money'));

?>
<style type="text/css">
#accordion { display:none }
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
                <?php if(isset($_GET['huinong_id'])) {?>
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
               <?php }?>
                <?php $form = ActiveFormrdiv::begin(); ?>
    			<br>
    			<table class="table table-bordered table-hover">
    			<?php
//     			var_dump($classname);
//     				if($classname == 'plantingstructure') {
    				if($huinonggrant) {
    					if(!is_array($huinonggrant)) {
    						$sub = Subsidiestype::find()->where(['id'=>$model->subsidiestype_id])->one();
//     						var_dump($sub['urladdress']);
    					?>
    				<tr>
    					<td width="8%" align="center"><b>管理区</b></td>
    					<td width="8%" align="center"><b>农场名称</b></td>
    					<td width="8%" align="center"><b>法人</b></td>
    					<td width="8%" align="center"><b>租赁者</b></td>
    					<?php if($sub['urladdress'] == 'Plant') {?>
    						<td width="8%" align="center"><b>作物</b></td>
    					<?php }?>
    					<?php if($sub['urladdress'] == 'Goodseed') {?>
	    					<td width="8%" align="center"><b>作物</b></td>
	    					<td width="8%" align="center"><b>良种</b></td>
    					<?php }?>
    					<td width="8%" align="center"><b>纠纷</b></td>
    					<td width="8%" align="center"><b>缴费情况</b></td>
    					<td align="center"><b>面积</b></td>
    					<td align="center"><b>补贴金额</b></td>
    					<td colspan="2" align="center"><b>状态</b></td>
    				</tr>
    				<?php 
    				$i=1;$areaSum=0.0;$moneySum=0.0;	
//     				var_dump($huinonggrant);exit;
    				?>
    				<tr>
    					<td align="center"><?= ManagementArea::find()->where(['id'=>$farm['management_area']])->one()['areaname'] ?></td>
    					<td align="center"><?= $farm['farmname'] ?></td>
    					<td align="center">
    					<a data-toggle="collapse" data-parent="#accordion" 
					          href="#collapseOne" class="showFamer">
					          <?= $farm['farmername']?>
					        </a>
    					</td>
    					<td align="center"><?= Lease::find()->where(['id'=>$huinonggrant['lease_id']])->one()['lessee']?></td>
    					
    					<?php if($sub['urladdress'] == 'Plant') {?>
    						<td align="center"><?= Plant::find()->where(['id'=>$model->typeid])->one()['typename']?></td>
    					<?php }?>
    					<?php if($sub['urladdress'] == 'Goodseed') {
    						$goodseed = Goodseed::find()->where(['id'=>$model->typeid])->one();
    					?>
    						<td align="center"><?= Plant::find()->where(['id'=>$goodseed['plant_id']])->one()['typename']?></td>
    						<td align="center"><?= $goodseed['typename']?></td>
    					<?php }?>
    					<td align="center"><?= Dispute::find()->where(['farms_id'=>$huinonggrant['farms_id']])->count().'条'?></td>
    					<td align="center"><?= Collection::getCollecitonInfo($huinonggrant['farms_id'])?></td>
    					<td align="center"><?= $huinonggrant['area'].' 亩'?></td>
    					<td align="center"><?= $huinonggrant['money'].' 元'?></td>
    					<td colspan="2" align="center"><?php if($huinonggrant['state']) echo '已发放'; else echo  html::checkboxList('isSubmit[]',$huinonggrant['state'],[$huinonggrant['id']=>'是否发放']);?></td>
    				</tr><?php $areaSum += $huinonggrant['area'];$moneySum += $huinonggrant['money'];?>
    				<?php 
    			} else {
    				?>
    						<tr>
    					<td width="10%" align="center"><b>补贴类型</b></td>
    					<td width="8%" align="center"><b>管理区</b></td>
    					<td width="8%" align="center"><b>农场名称</b></td>
    					<td width="8%" align="center"><b>法人</b></td>
    					<td width="8%" align="center"><b>租赁者</b></td>
    					
	    					<td width="8%" align="center"><b>作物</b></td>
	    					<td width="8%" align="center"><b>良种</b></td>
    					
    					<td width="8%" align="center"><b>纠纷</b></td>
    					<td width="8%" align="center"><b>缴费情况</b></td>
    					<td align="center"><b>面积</b></td>
    					<td align="center"><b>补贴金额</b></td>
    					<td colspan="2" align="center"><b>状态</b></td>
    				</tr>
    				<?php 
    				$i=1;$areaSum=0.0;$moneySum=0.0;	
    				foreach ($huinonggrant as $value) {
    					$huinong = Huinong::find()->where(['id'=>$value['huinong_id']])->one();
    				?>
    				<tr>
    					<td align="center"><?= Plant::find()->where(['id'=>$huinong['typeid']])->one()['typename'].Subsidiestype::find()->where(['id'=>$huinong['subsidiestype_id']])->one()['typename'] ?></td>
    					<td align="center"><?= ManagementArea::find()->where(['id'=>$farm['management_area']])->one()['areaname'] ?></td>
    					<td align="center">
    					<?= $farm['farmname']?>
    					</td>
    					<td align="center"><a data-toggle="collapse" data-parent="#accordion" 
					          href="#collapseOne" class="showFamer">
					          <?= $farm['farmername']?>
					        </a></td>
    					<td align="center"><?= Lease::find()->where(['id'=>$value['lease_id']])->one()['lessee']?></td>
    					<?php 
    						$goodseed = Goodseed::find()->where(['id'=>$value['typeid']])->one();
    					?>
    						<td align="center"><?= Plant::find()->where(['id'=>$value['typeid']])->one()['typename']?></td>
    						<td align="center"><?= $goodseed['typename']?></td>
    					
    					<td align="center"><?= Dispute::find()->where(['farms_id'=>$value['farms_id']])->count().'条'?></td>
    					<td align="center"><?= Collection::getCollecitonInfo($value['farms_id'])?></td>
    					<td align="center"><?= $value['area'].' 亩'?></td>
    					<td align="center"><?= $value['money'].' 元'?></td>
    					<td colspan="2" align="center"><?php if($value['state']) echo '已发放'; else echo  html::checkboxList('isSubmit[]',$value['state'],[$value['id']=>'是否发放']);?></td>
    				</tr><?php $areaSum += $value['area'];$moneySum += $value['money'];?>
    				<?php 
    				}}} else {
				    		echo '此用户不在补贴范围内或地产科还未提交此用户。';
    				}?>
    			</table>
    			<div class="panel-group" id="accordion">
				  <div class="panel panel-default">
				    <div id="collapseOne" class="panel-collapse collapse">
				      <div class="panel-body">
				        <table class="table table-bordered table-hover">
		            					<tr>
		            						<td align="right">身份证号：</td>
		            						<td><?= $farm['cardid']?></td>
		            						<td align="right">性别:</td>
		            						<td><?= $farmer['gender']?></td>
		            						<td align="right">电话：</td>
		            						<td><?= $farm['telephone']?></td>
		            						
		            					</tr>
		            					<tr>
		            						<td align="right" valign="middle">法人照片</td>
		            						<td align="center"><?= Html::img($farmer['photo'],['height'=>"220"])?></td>
		            						<td align="right" valign="middle">身份证复印件</td>
		            						<td align="center"><?= Html::img($farmer['cardpic'],['height'=>"220"])?></td>
		            						<td align="right" valign="middle">身份证复印件反面</td>
		            						<td align="center"><?= Html::img($farmer['cardpicback'],['height'=>"220"])?></td>
		            					</tr>
		            				</table>
				      </div>
				    </div>
				  </div>
<input type="submit" id="textSubmit">
    
                </div>
                
            </div>
        </div>
    </div>
</section>
</div>

<script>
$(".showFamer").click(function(){
	if($("#accordion").css("display")=="none"){
	$("#accordion").show();
	}else{
		setTimeout(function () {
			$("#accordion").hide();
	    }, 300);
	
	}
	});
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
			//alert($('#w0').size());
			$("#w0").submit();
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
<?php ActiveFormrdiv::end(); ?>