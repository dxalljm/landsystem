<?php
namespace frontend\controllers;use app\models\User;
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
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\HuinongSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'huinong';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="huinong-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
                <?php $form = ActiveFormrdiv::begin(); ?>
    			<table class="table table-bordered table-hover">
    			<?php if($classname == 'Plant' or $classname == 'Goodseed') {?>
    				<tr class>
    					<td align="center"><b>序号</b></td>
    					<td align="center"><b>农场名称</b></td>
    					<td align="center"><b>法人</b></td>
    					<td align="center"><b>租赁者</b></td>
						<td align="center"><b>补贴对象</b></td>
						<td align="center"><b>补贴比率</b></td>
    					<?php if($classname == 'Plant') {?>
    					<td align="center"><b>作物</b></td>
    					<?php } if($classname == 'Goodseed') {?>
    					<td align="center"><b>良种</b></td>
    					<?php }?>
    					<td align="center"><b>纠纷</b></td>
    					<td align="center"><b>缴费情况</b></td>
    					<td align="center"><b>面积</b></td>
    					<td align="center"><b>补贴金额</b></td>
    					<td align="center"><b><?= Html::radioList('issubmitSearch',$issubmitSearch,['否','是'],['id'=>'issubmitsearch'])?></b></td>
    				</tr>
    				
    				<?php 
    				$i=1;$areaSum=0.0;$moneySum=0.0;
    				if($data) {
    				foreach ($data as $value) {
    					$areaSum += $value['area'];
    					$money = $model->subsidiesmoney*0.01*$value['area']*$model->subsidiesarea*(float)$value['proportion']/100;
						$huinongascription = Lease::getHuinonginfo($value['lease_id']);
					?>
    				<tr><?php $farm = Farms::find()->where(['id'=>$value['farms_id']])->one();?>
    					<td align="center"><?= $i++ ?></td>
    					<td align="center"><?= $farm->farmname ?></td>
    					<td align="center"><?= $farm->farmername ?></td>
						<td align="center"><?= Lease::find()->where(['id'=>$value['lease_id']])->one()['lessee']?></td>

						<td align="center"><?= $value['subsidyobject']?></td>
						<td align="center"><?= $value['proportion']?></td>
    					<?php if($classname == 'Plant') {?>
    					<td align="center"><?= Plant::find()->where(['id'=>$value['typeid']])->one()['typename']?></td>
    					<?php }
    					if($classname == 'Goodseed') {
    					?>
    					<td align="center"><?= Goodseed::find()->where(['id'=>$value['typeid']])->one()['typename']?></td>
    					<?php }?>
    					<td align="center"><?= '有'.Dispute::find()->where(['farms_id'=>$value['farms_id']])->count().'条纠纷'?></td>
    					<td align="center"><?= Collection::getCollecitonInfo($value['farms_id'])?></td>
    					<td align="center"><?= $value['area'].' 亩'?></td>
    					<td align="center"><?= $value['money'].' 元'?></td>
    					<td align="center"><?php if($value->issubmit) echo '已提交'; else {?><label><input type="checkbox" name="isSubmit[]" class="nodes" value=<?= $value['id'].'/'.$value['lease_id'].'/'.sprintf("%.2f",$money).'/'.$value['area']?>/>是否提交</label><?php }?></td>
    				</tr>
    				<?php }?>
    				<tr>
    					<td align="center"><b>合计</b></td>
    					<td align="center"><b></b></td>
    					<td align="center"><b></b></td>
    					<td align="center"><b></b></td>
						<td align="center"><b></b></td>
						<td align="center"><b></b></td>
    					<td align="center"><b></b></td>
    					<?php if($classname == 'Plant') {?>
    					<td align="center"><b></b></td>
    					<?php } if($classname == 'Goodseed') {?>
    					<td align="center"><b></b></td>
    					<?php }?>
    					<td align="center"><b></b></td>
    					<td align="center" width="150px"><b><?php if($issubmitSearch) echo $areaSum.'亩'; else echo html::textInput('areaSum',0.0,['readonly'=>'readonly','class'=>'form-control','id'=>'area'])?></b></td>
    					<td align="center" width="150px"><b><?php if($issubmitSearch) echo $money.'元'; else echo html::textInput('moneySum',0.0,['readonly'=>'readonly','class'=>'form-control','id'=>'money'])?></b></td>
    					<td align="center"><?php if(!$issubmitSearch) {?><label><input type="checkbox" class="all"/> 全选</label><label><input type="checkbox" class="invert"/> 反选</label><label><input type="checkbox" class="revoke"/> 取消选择 </label><?php }?></td>
    				</tr>
    				<?php }}?>
    			</table>
    <div class="form-group">
        <?= Html::submitButton('提交', ['class' => 'btn btn-primary'])?>
    </div>

    <?php ActiveFormrdiv::end(); ?>
                </div>
                
            </div>
        </div>
    </div>
</section>
</div>
<script>

$('#issubmitsearch').change(function(){
	$("form").submit();
});
selected.all('.all', '.nodes');
selected.invert('.invert', '.nodes');
selected.revoke('.revoke', '.nodes');
$('.all').click(function(){
	 if ($(this).is(':checked') == true) {
		$('.nodes').each(function(){
			var areaSum = $('#area').val();
			var moneySum = $('#money').val();
			var val = $(this).val();
			var valArr = val.split('/');
			 if ($(this).is(':checked') == true) {
				 areasum = areaSum*1 + valArr[3]*1;
				 moneysum = moneySum*1 + valArr[2]*1;
	
		         $('#area').val(areasum.toFixed(2));
		         $('#money').val(moneysum.toFixed(2));
		     }
		});
	 } else {
		 $('#area').val(0);
         $('#money').val(0);
	 }
});
$('.invert').click(function(){
	if ($(this).is(':checked') == true) {
		$('#area').val(0);
        $('#money').val(0);
	$('.nodes').each(function(){
		var areaSum = $('#area').val();
		var moneySum = $('#money').val();
		var val = $(this).val();
		var valArr = val.split('/');
		 if ($(this).is(':checked') == true) {
			 areasum = areaSum*1 + valArr[3]*1;
			 moneysum = moneySum*1 + valArr[2]*1;

	         $('#area').val(areasum.toFixed(2));
	         $('#money').val(moneysum.toFixed(2));
	     }
	});
	} else {
		$('#area').val(0);
        $('#money').val(0);
		$('.nodes').each(function(){
			var areaSum = $('#area').val();
			var moneySum = $('#money').val();
			var val = $(this).val();
			var valArr = val.split('/');
			 if ($(this).is(':checked') == true) {
				 areasum = areaSum*1 + valArr[3]*1;
				 moneysum = moneySum*1 + valArr[2]*1;

		         $('#area').val(areasum.toFixed(2));
		         $('#money').val(moneysum.toFixed(2));
		     }
		});
	}
});
$('.revoke').click(function(){
	 $('#area').val(0);
     $('#money').val(0);
});
$("document").ready(function(){
	
	$('.nodes').click(function(){
		var input = $(this).is(":checked");
		var areaSum = $('#area').val();
		var moneySum = $('#money').val();
		var val = $(this).val();
		var valArr = val.split('/');
		
		if(input == true) {
			var areaResult = areaSum*1 + valArr[3]*1;
			$('#area').val(areaResult.toFixed(2));
			var moneyResult = moneySum*1 + valArr[2]*1;
			$('#money').val(moneyResult.toFixed(2));
		} else {
			var areaResult = areaSum*1 - valArr[3]*1;
			$('#area').val(areaResult.toFixed(2));
			var moneyResult = moneySum*1 - valArr[2]*1;
			$('#money').val(moneyResult.toFixed(2));
		}
	});
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
