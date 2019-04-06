<?php
namespace frontend\controllers;
use app\models\BankAccount;
use app\models\Machine;
use app\models\Machineoffarm;
use app\models\Machinetype;
use app\models\User;
use app\models\Tables;
use app\models\Machineapplymachine;
use frontend\helpers\Echartsdata;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Plant;
use app\models\Subsidiestype;
use yii\helpers\Url;
use app\models\Huinonggrant;
use dosamigos\datetimepicker\DateTimePicker;
use frontend\helpers\ActiveFormrdiv;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\Lease;
use app\models\Huinong;
use app\models\Subsidyratio;
use frontend\helpers\Tab;
use Yii;
use frontend\helpers\ES;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\HuinongSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'huinong';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
$arrclass = explode('\\',$dataProvider->query->modelClass);
$marrclass = explode('\\',$dataMachine->query->modelClass);
$totalData = clone $dataProvider;
$totalData->pagination = ['pagesize'=>0];
$totalDataMachine = clone $dataMachine;
$totalDataMachine->pagination = ['pagesize'=>0];
if(isset($_GET['bankaccountSearch']['state'])) {
	$state = $_GET['bankaccountSearch']['state'];
} else {
	$state = '';
}
// var_dump($huinongs);
?>
<div class="huinong-index">
	<?php $form = ActiveFormrdiv::begin(['method'=>'get']); ?>
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<div class="box-body">
							<div class="nav-tabs-custom">
							<ul class="nav nav-pills nav-pills-warning">
								<?php
								if(User::getItemname('服务大厅','服务大厅贷款') or User::getItemname('服务大厅','科长') or User::getItemname('地产科') or User::getItemname('项目科') or User::getItemname('管委会领导')) {
									?>
									<li class="active" id="pinfo"><a href="#pinfolist" data-toggle="tab"
																	 aria-expanded="true">
											<?php
											if (User::getItemname('地产科') or User::getItemname('项目科') or User::getItemname('管委会领导')) {
												echo '惠农补贴生产者补贴汇总表';
											} else {
												echo '惠农补贴审核';
											}
											?>
										</a></li>
									<li class="" id="pinfoechart"><a href="#pinfoechartlist" data-toggle="tab"
																   aria-expanded="false">生产者补贴图表(<?= User::getYear()?>年度)</a></li>
									<?php
								}
								?>
								<?php
								if(User::getItemname('服务大厅','服务大厅农机申请') or User::getItemname('服务大厅','科长') or User::getItemname('地产科') or User::getItemname('项目科') or User::getItemname('管委会领导')) {
									?>
									<li class="" id="minfo"><a href="#minfolist" data-toggle="tab"
																aria-expanded="false">农机补贴汇总(<?= User::getYear()?>年度)</a></li>
									<li class="" id="minfoechart"><a href="#minfoechartlist" data-toggle="tab"
																   aria-expanded="false">农机补贴图表(<?= User::getYear()?>年度)</a></li>
									<?php
								}
								?>


							</ul>
							<div class="tab-content">
								<?php
								if(User::getItemname('服务大厅','服务大厅保险') or User::getItemname('服务大厅','科长') or User::getItemname('地产科') or User::getItemname('项目科') or User::getItemname('管委会领导')) {
									?>
									<div class='tab-pane active' id="pinfolist">
										<?php
										if (User::getItemname('地产科') or User::getItemname('项目科')) {
											echo Html::a('生成汇总表', Url::to(['plantingstructurecheck/plantingstructurecheckhnxls', 'where' => json_encode($dataProvider->query->where)]), ['class' => 'btn btn-success']);
										}
										echo GridView::widget([
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
													'options' => ['width' => 150],
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
													'value' => function ($model) {
														return $model->contractarea;
													}
												],
//												'area',
												[
													'label' => '补贴对象',
													'attribute' => 'lessee',
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
												'area',
												[
													'label' => '补贴金额',
													'value' => function ($model) {
														$h = Huinong::find()->where(['year' => User::getYear(), 'typeid' => $model->plant_id])->one();
														$p = $h['subsidiesarea'] / 100;
														$r = bcmul($h['subsidiesmoney'], $model->area, 2);
														return bcmul($r, $p, 2);
													},
												],
											],

										]);

										?>
									</div>
									<div class='tab-pane' id="pinfoechartlist">
										<?php
										$x = Farms::getManagementArea('small')['areaname'];
										$s = Echartsdata::getHuinonginfo($totalData);
										$l = Echartsdata::getHuinongtypename($totalData,'typename');
										echo ES::barLabel2()->DOM('huinongecharts',true,'1500px','500px')->options(['color'=>['#003366','#e5323e'],'legend'=>$l,'xAxis'=>$x,'series'=>$s,'unit'=>'元'])->JS();
										?>
									</div>
									<?php
								}
								?>
								<?php
								if(User::getItemname('服务大厅','服务大厅农机申请') or User::getItemname('服务大厅','科长') or User::getItemname('地产科') or User::getItemname('项目科') or User::getItemname('管委会领导')) {
								?>
								<div class='tab-pane' id="minfolist">
									<?php
									echo GridView::widget([
										'dataProvider' => $dataMachine,
										'filterModel' => $machineSearch,
										'total' => '<tr height="40">
													<td></td>	
													<td align="left" id="mt0"><strong>合计</strong></td>
													<td align="left" id="mt1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
													<td align="left" id="mt2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
													<td align="left" id="mt3"></td>
													<td align="left" id="mt4"><strong></strong></td>
													<td align="left" id="mt5"><strong></strong></td>
													<td align="left" id="mt6"><strong></strong></td>
													<td align="left" id="mt7"><strong></strong></td>
													<td align="left" id="mt8"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
													<td align="left" id="mt9"><strong></strong></td>
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
//												'attribute' => 'farms_id',
												'options' => ['width' => 150],
												'value' => function ($model) {

													return Farms::find()->where([
														'id' => $model->farms_id
													])->one()['farmname'];

												}
											],
											[
												'label' => '法人名称',
												'attribute' => 'farmername',
												'options' => ['width' => 120],
											],
											[
												'label' => '合同号',
//												'attribute' => 'state',
												'options' => ['width' => 150],
												'value' => function ($model) {
													return Farms::find()->where([
														'id' => $model->farms_id
													])->one()['contractnumber'];
												},
//												'filter' => [1 => '正常', 2 => '未更换合同', 3 => '临时性管理', 4 => '买断合同'],
											],
//											[
//												'label' => '合同面积',
////												'attribute' => 'contractarea',
//												'value' => function ($model) {
//													return Farms::find()->where([
//														'id' => $model->farms_id
//													])->one()['contractarea'];
//												}
//											],
//											[
//												'label' => '身份证号码',
//												'attribute' => 'cardid',
//											],
											[
//												'label' => '农机类型',
												'attribute' => 'machinetype_id',
												'value' => function($model) {
													$machinetype = Machinetype::findOne($model['machinetype_id']);
													$father = Machinetype::findOne($machinetype['father_id']);
													return $father['typename'];
												},
												'filter' => Machinetype::getApplyType(),
											],
											[
												'label' => '机具名称',
												'value' => function($model) {
													$offarm = Machineapplymachine::findOne($model->machineoffarm_id);
													$machine = Machine::findOne($offarm['machine_id']);
													return $machine['filename'];
												},
											],
											[
												'label' => '机具型号',
												'value' => function($model) {
													$offarm = Machineapplymachine::findOne($model->machineoffarm_id);
													$machine = Machine::findOne($offarm['machine_id']);
													return $machine['enterprisename'];
												},
											],
											[
												'label' => '生产厂家',
												'value' => function($model) {
													$offarm = Machineapplymachine::findOne($model->machineoffarm_id);
													$machine = Machine::findOne($offarm['machine_id']);
													return $machine['implementmodel'];
												},
											],
											[
												'label' => '补贴金额',
												'attribute' => 'subsidymoney',
											],
											[
												'label' => '操作',
												'format' => 'raw',
												'value' => function($model) {
													return Html::a('查看详情',['machineapply/machineapplyview','machineoffarm_id'=>$model->machineoffarm_id],['class'=>'btn btn-xs btn-success']);
												}
											]
										],

									]);
									?>
								</div>
									<div class='tab-pane' id="minfoechartlist">
										<?php
										$mx = Echartsdata::getMachinetypename($totalDataMachine,'typename');
										$ms = Echartsdata::getMachineinfo($totalDataMachine);
										echo ES::bar()->DOM('machineecharts',true,'1500px','500px')->options(['legend'=>['农机补贴'],'xAxis'=>$mx,'unit'=>'台','series'=>$ms])->JS();
										?>
									</div>
								<?php
								}
								?>
							</div>
							</div>
						</div>
					</div>
				</div>
	</section>
	<?php ActiveFormrdiv::end(); ?>
</div>
<?php
$tab = new Tab();
echo $tab->createTab(Yii::$app->controller->action->id,['pinfo','minfo','pinfoechart','minfoechart']);
?>
<div id="dialog" title="银行账号审核">
	<table width=100% height="120px">
		<tr>
			<td align="right">种植者:</td>
			<td>
				<?= html::textInput('lessee','',['id'=>'Lessee','readonly'=>true])?>
			</td>
		</tr>
		<tr>
			<td align="right">身份证号:</td>
			<td>
				<?= html::textInput('cardid','',['id'=>'Cardid','readonly'=>true])?>
			</td>
		</tr>
		<tr>
			<td align="right">银行:</td>
			<td><?= html::textInput('bank','',['id'=>'Bank','readonly'=>true])?></td>
		</tr>
		<tr>
			<td align="right">卡号：</td>
			<td>
				<input type="text" id="Accountnumber" name="actTransaction.opbankacntnoShow" size="26" maxlength="50" value="" onkeyup="Keystroke();"/>
				<div id="copybankacntno">
					<input type="text" size="30" id="bankacntnoEm" style="font-size:30px;color:blue;" value="" readonly="readonly"/>
				</div>
		</tr>
	</table>
	<?= html::hiddenInput('bankid','',['id'=>'BankID'])?>
	<?= html::hiddenInput('checkid','',['id'=>'CheckID'])?>
</div>

<div id="dialogModfiy" title="银行账号修改">
	<table width=100% height="120px">
		<tr>
			<td align="right">种植者:</td>
			<td>
				<?= html::textInput('lessee','',['id'=>'Lesseem','readonly'=>true])?>
			</td>
		</tr>
		<tr>
			<td align="right">身份证号:</td>
			<td>
				<?= html::textInput('cardid','',['id'=>'Cardidm','readonly'=>true])?>
			</td>
		</tr>
		<tr>
			<td align="right">银行:</td>
			<td><?= html::textInput('bank','',['id'=>'Bankm','readonly'=>true])?></td>
		</tr>
		<tr>
			<td align="right">卡号：</td>
			<td>
				<input type="text" id="Accountnumberm" name="actTransaction.opbankacntnoShow" size="26" maxlength="50" value="" onkeyup="Keystrokem();"/>
				<div id="copybankacntnom">
					<input type="text" size="30" id="bankacntnoEmm" style="font-size:30px;color:blue;" value="" readonly="readonly"/>
				</div>
		</tr>
	</table>
	<?= html::hiddenInput('bankid','',['id'=>'BankIDm'])?>
	<?= html::hiddenInput('checkid','',['id'=>'CheckIDm'])?>
	<?= html::hiddenInput('farms_id','',['id'=>'FarmsID'])?>
</div>

<script>
	function examine(id) {
		$.getJSON('index.php?r=plantingstructurecheck/getcheck', {id: id}, function (data) {
			$('#CheckID').val(id);
			$('#BankID').val(data.id);
			$('#Lessee').val(data.lessee);
			$('#Cardid').val(data.cardid);
			$('#Bank').val(data.bank);
			$('#Accountnumber').val(data.accountnumber);
			var v = $('#Accountnumber').val();
			var vnew = v.replace(/\s/g, '').replace(/(.{4})/g, "$1 ");
			$("#bankacntnoEm").val(vnew);
		});

		$("#dialog").dialog("open");
	}
	function examinemodfiy(id) {
		$.getJSON('index.php?r=plantingstructurecheck/getcheck', {id: id}, function (data) {
			$('#CheckIDm').val(id);
			$('#FarmsID').val(data.farms_id);
			$('#BankIDm').val(data.id);
			$('#Lesseem').val(data.lessee);
			$('#Cardidm').val(data.cardid);
			$('#Bankm').val(data.bank);
			$('#Accountnumberm').val(data.accountnumber);
			var v = $('#Accountnumberm').val();
			var vnew = v.replace(/\s/g, '').replace(/(.{4})/g, "$1 ");
			$("#bankacntnoEmm").val(vnew);
		});

		$("#dialogModfiy").dialog("open");
	}
	function Keystroke(){

		var v = $('#Accountnumber').val();
		var vnew = v.replace(/\s/g, '').replace(/(.{4})/g, "$1 ");
//		$('#Accountnumber').val(v.replace(/\s/g, '').replace(/(.{4})/g, "$1 "));
		$("#bankacntnoEm").val(vnew);
//		$("#Accountnumber").blur(function(){
//			$("#copybankacntno").hide();
//		});
//			$("#Accountnumber").mouseout(function(){
//				$("#copybankacntno").hide();
//			});
	}
	function Keystrokem(){

		var v = $('#Accountnumberm').val();
		var vnew = v.replace(/\s/g, '').replace(/(.{4})/g, "$1 ");
//		$('#Accountnumber').val(v.replace(/\s/g, '').replace(/(.{4})/g, "$1 "));
		$("#bankacntnoEmm").val(vnew);
//		$("#Accountnumber").blur(function(){
//			$("#copybankacntno").hide();
//		});
//			$("#Accountnumber").mouseout(function(){
//				$("#copybankacntno").hide();
//			});
	}
	$('#dialog').dialog({
		autoOpen: false,
		width:700,

		buttons: [
			{
				text: "审核通过",
				click: function() {
					$.getJSON('index.php?r=bankaccount/setstate', {id: $('#BankID').val(),checkid:$('#CheckID').val()}, function (data) {
						if(data) {
							window.location.reload();
						} else {
							alert('保存失败,请与管理员联系。');
						}
					});
					$( this ).dialog( "close" );
				}
			},
			{
				text: "关闭",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});
	$('#dialogModfiy').dialog({
		autoOpen: false,
		width:700,

		buttons: [
			{
				text: "确定",
				click: function() {
					$.getJSON('index.php?r=bankaccount/bankaccountmodfiy',{'cardid':$('#Cardidm').val(),'accountnumber':$('#Accountnumberm').val(),'farms_id':$('#FarmsID').val()},function (data) {
						if(data.state) {
							window.location.reload();
						}
					});
//					$.getJSON('index.php?r=bankaccount/setstate', {id: $('#BankID').val(),checkid:$('#CheckID').val()}, function (data) {
//						if(data) {
//							window.location.reload();
//						} else {
//							alert('保存失败,请与管理员联系。');
//						}
//					});
					$( this ).dialog( "close" );
				}
			},
			{
				text: "关闭",
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});
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
		$.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'mulhuinongSum',andwhere:'<?= json_encode(['state'=>$state])?>'}, function (data) {
			$('#pt10').html(data + '元');
		});

	});
	$(document).ready(function () {
		$.getJSON('index.php?r=search/search', {modelClass: '<?= $marrclass[2]?>',where:'<?= json_encode($dataMachine->query->where)?>',command:'count-farms_id'}, function (data) {
			$('#mt1').html(data + '户');
		});

		$.getJSON('index.php?r=search/search', {modelClass: '<?= $marrclass[2]?>',where:'<?= json_encode($dataMachine->query->where)?>',command:'count-farmer_id'}, function (data) {
			$('#mt2').html(data + '人');
		});
//		$.getJSON('index.php?r=search/search', {modelClass: '<?//= $marrclass[2]?>//',where:'<?//= json_encode($dataMachine->query->where)?>//',command:'sum-contractarea'}, function (data) {
//			$('#pt4').html(data + '亩');
//		});
//		$.getJSON('index.php?r=search/search', {modelClass: '<?//= $marrclass[2]?>//',where:'<?//= json_encode($dataMachine->query->where)?>//',command:'count-lease_id',andwhere:'<?//= json_encode('lease_id>0')?>//'}, function (data) {
//			$('#pt5').html('生产者'+ data + '人');
//		});
//		$.getJSON('index.php?r=search/search', {modelClass: '<?//= $marrclass[2]?>//',where:'<?//= json_encode($dataMachine->query->where)?>//',command:'sum-area'}, function (data) {
//			$('#pt').html(data + '亩');
//		});
		$.getJSON('index.php?r=search/search', {modelClass: '<?= $marrclass[2]?>',where:'<?= json_encode($dataMachine->query->where)?>',command:'sum-subsidymoney'}, function (data) {
			$('#mt8').html(data + '元');
		});

	});
</script>
