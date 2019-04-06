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
use app\models\Huinonggrant;
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
                <?php $huinongGrant = Huinonggrant::find();?>
                <table class="table table-bordered table-hover">
                	<tr>
                		<td align="right"><strong>享受补贴人数：</strong></td>
                		<td align="left"><strong>
               		    <?= $huinongGrant->where(['huinong_id'=>$model->id])->count()?>
               		    人</strong></td>
                		<td align="right"><strong>已发放补贴人数：</strong></td>
                		<td align="left"><strong>
               		    <?= $huinongGrant->where(['huinong_id'=>$model->id,'state'=>1])->count()?>
               		    人</strong></td>
                		<td align="right"><strong>未发放补贴人数：</strong></td>
                		<td align="left"><strong>
               		    <?= $huinongGrant->where(['huinong_id'=>$model->id,'state'=>0])->count()?>
               		    人</strong></td>
                		<td align="right"><strong>应发放金额：</strong></td>
                		<td align="left"><strong>
               		    <?= $huinongGrant->where(['huinong_id'=>$model->id])->sum('money')?>
               		    元</strong></td>
                		<td align="right"><strong>已发放金额：</strong></td>
                		<td align="left"><strong>
               		    <?= $huinongGrant->where(['huinong_id'=>$model->id,'state'=>1])->sum('money')?>
               		    元</strong></td>
                		<td align="right"><strong>差额：</strong></td>
                		<td align="left"><strong>
               		    <?= $huinongGrant->where(['huinong_id'=>$model->id,'state'=>0])->sum('money')?>
               		    元</strong></td>
                		<td align="right"><strong>完成度：</strong></td>
                		<td align="left"><strong>
               		    <?php $wcd = $huinongGrant->where(['huinong_id'=>$model->id,'state'=>1])->count()/$huinongGrant->where(['huinong_id'=>$model->id])->count()*100;echo $wcd.'%'?>
                		</strong></td>
                	</tr>
                </table>
                <br>
                <?php $form = ActiveFormrdiv::begin(); ?>
    			<table class="table table-bordered table-hover">
    			<?php if($classname == 'plantingstructure') {?>
    				<tr>
    					<td align="center"><b>序号</b></td>
    					<td align="center"><b>农场名称</b></td>
    					<td align="center"><b>法人</b></td>
    					<td align="center"><b>租赁者</b></td>
    					<?php if($model->subsidiestype_id == 'plant') {?>
    						<td align="center"><b>作物</b></td>
    					<?php }?>
    					<?php if($model->subsidiestype_id == 'goodseed') {?>
	    					<td align="center"><b>作物</b></td>
	    					<td align="center"><b>良种</b></td>
    					<?php }?>
    					<td align="center"><b>纠纷</b></td>
    					<td align="center"><b>缴费情况</b></td>
    					<td align="center"><b>面积</b></td>
    					<td align="center"><b>补贴金额</b></td>
    					<td align="center"><b>状态</b></td>
    				</tr>
    				
    				<?php 
    				$i=1;$areaSum=0.0;$moneySum=0.0;
    				foreach ($data as $value) {
    				?>
    				<tr><?php $farm = Farms::find()->where(['id'=>$value['farms_id']])->one();?>
    					<td align="center"><?= $i++ ?></td>
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
    					<td align="center"><?= '有'.Dispute::find()->where(['farms_id'=>$value['farms_id']])->count().'条纠纷'?></td>
    					<td align="center"><?= Collection::getCollecitonInfo($value['farms_id'])?></td>
    					<td align="center"><?= $value['area'].' 亩'?></td>
    					<td align="center"><?= $value['money'].' 元'?></td>
    					<td align="center"><?= $value['state']?'已发放':'未发放'?></td><?php $areaSum += $value['area'];$moneySum += $value['money'];?>
    				</tr>
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
    					<td align="center" width="150px"><b><?php echo $areaSum.'亩';?></b></td>
    					<td align="center" width="150px"><b>应发：<?php echo $moneySum.'元';?></b></td>
    					<td align="center"><b>已发：<?= Huinonggrant::find()->where(['huinong_id'=>$model->id,'state'=>1])->sum('money')?></b></td>
    				</tr>
    				<?php }?>
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
$('input:checkbox[name="isSubmit[]"]').click(function(){
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
