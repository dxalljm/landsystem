<?php

namespace backend\controllers;
use app\models\Farmer;
use dosamigos\datetimepicker\DateTimePicker;
use Yii;
use yii\helpers\Url;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Reviewprocess;
use app\models\Auditprocess;
use app\models\Processname;
use app\models\User;
use app\models\ManagementArea;
use frontend\helpers\ActiveFormrdiv;
use frontend\helpers\photographDialog;
use app\models\Loan;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\ReviewprocessSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'reviewprocess';
$this->title = Tables::find ()->where ( [
	'tablename' => $this->title
] )->one ()['Ctablename'];
$this->params ['breadcrumbs'] [] = $this->title;
?>
<div class="reviewprocess-index">

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<?php User::tableBegin($title);?>

						<?php echo Html::hiddenInput('loanid','',['id'=>'selectloanid']);?>
						<div class="nav-tabs-custom">
									<?php
									if( Auditprocess::isAuditing('贷款冻结审批')) {
										?>
										<?= GridView::widget([
											'dataProvider' => $dataLoan,
											'filterModel' => $searchLoan,
											'columns' => [
												['class' => 'yii\grid\SerialColumn'],
												[
													'attribute' => 'management_area',
													'headerOptions' => ['width' => '200'],
													'value'=> function($model) {
														return ManagementArea::getAreanameOne($model->management_area);
													},
													'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
												],
												[
													'label' => '农场名称',
													'attribute' => 'farms_id',
													'value' => function($model) {
														return Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'];
													}
												],
												[
													'label' => '贷款人',
													'attribute' => 'farmer_id',
													'value' => function($model) {
														return Farms::find()->where(['id'=>$model->farms_id])->one()['farmername'];
													}
												],
												[
													'label' => '贷款面积',
													'attribute' => 'mortgagearea',
												],
												[
													'label' => '贷款金额',
													'attribute' => 'mortgagemoney',
												],
												[
													'label' => '贷款银行',
													'attribute' => 'mortgagebank',
													'options' =>['width'=>300],
													'filter' => Loan::getBankName(),
												],
												[
													'label' => '年度',
													'attribute' => 'year',
//													'value' => function($model) {
//														return Loan::find()->where(['reviewprocess_id'=>$model->id])->one()['year'];
//													},
													'filter' => Loan::getYears(),
												],
												[
													'label' => '冻结日期',
													'attribute' => 'create_at',
													'options' =>['width'=>100],
													'value' => function($model) {
														return date('Y-m-d',$model->create_at);
													}
												],
												[
													'label' => '状态',
//									'attribute' => 'state',
													'format' => 'raw',
//													'options' =>['width'=>500],
													'value' => function($model) {
//														$loan = Loan::find()->where(['reviewprocess_id'=>$model->id])->one();
														$farm = Farms::findOne($model->farms_id);
														if($farm->locked == 1)
															$html = '冻结';
														else
															$html = '已解冻';
														if(!empty($loan['unlockbook'])) {
															$html.='&nbsp;';
															$html.='<i class="fa fa-photo"></i>';
														}
														return $html;
													}
												],
//								[
//									'label' => '领取日期',
//									'value' => function($model) {
//										if($model->receivetime)
//											return date('Y-m-d',$model->receivetime);
//									}
//								],
												// 'breedtype_id',

												[
													'format' => 'raw',
													'options' =>['width'=>300],
													'value' => function($model) {
                                                        $farm = Farms::findOne($model->farms_id);
														if ($farm->locked == 1) {
															$html = html::a('解冻', ['loan/loanunlock', 'id' => $model->id], [
																'class' => 'btn btn-success',
																'data' => [
																	'confirm' => '您确定要解冻吗？',
																	'method' => 'post',
																],
															]);
														} else {
															$html = html::a('解冻', '#', [
																'class' => 'btn btn-success',
																'disabled'=>true,
															]);
														}
														$html .= '&nbsp;';
														$html .= photographDialog::showDialogClass('拍照','unlock-dialog',$model->id);
														$html .= '&nbsp;';
														$html .= html::a('查看', ['loan/loanview', 'id' => $model->id,'farms_id'=>$model->farms_id], [
																'class' => 'btn btn-success',
																
														]);
														return $html;
													}
												],
												
											],
										]); }?>

									<?php User::dataListEnd();?>
			</div>
		</div>
	</section>
</div>
<?php photographDialog::dialogHtml(true);?>
<script language="javascript" type="text/javascript">
$( "#dialog" ).dialog({
	autoOpen: false,
	width: 1000,
	height: 600,
});										
// 	$( ".unlock-dialog-link" ).click(function( event ) {
		
// 		$( "#dialog" ).dialog( "open" );
// 		event.preventDefault();
// 		Start1_onclick();

// 	});
	function Start1_onclick()
	{
		var str=captrue.bStopPlay();
		var str = captrue.bStartPlay();
		DefaultBrightness();
	}
	function Start2_onclick()
	{
		var str=captrue.bStopPlay();
		var str = captrue.bStartPlay2(0);
		DefaultBrightness();
	}
	function Stop_onclick()
	{
		var str=captrue.bStopPlay();
	}

	function SaveJPG_onclick()
	{
		var str=captrue.bSaveJPG("d:\\123\\","JPG");
	}

	function checkBoxclick(eid)
	{
		$("#electronic-id").val(eid);
	}

	function UpLoadJPG_onclick()
	{
		var field = 'unlockbook';
// 		alert(field);
		captrue.bSaveJPG("d:\\","JPG");
		var upload = captrue.bUpLoadImage("D:\\JPG.JPG", "192.168.1.10", 8001, "/front/uploadimage/upload.php");
//		alert(save);
		if(upload) {
			$.getJSON("<?= Url::to(['photogallery/photographdialogloan'])?>", {loan_id: $('#selectloanid').val(),field:field}, function (data) {
// 				var width = "400px"
				//$('#'+field).attr('src', data.url);
// 			    $('#'+field).attr('width',width);
				alert(data.url);
				captrue.bDeleteFile("d:\\JPG.jpg");
				var str=captrue.bStopPlay();
				//$('#farmer-'+field).val(data.url);
				$( "#dialog" ).dialog( "close" );
				//$('#shopic').attr('src',data.url);
			});
		}
		;
	}

	function DeleteJPG_onclick()
	{

		var str=captrue.bDeleteFile("D:\\JPG.jpg");
	}

	function SaveGray_onclick()
	{
		captrue.vSetRotate(90);
		var str=captrue.bSaveGray("D:\\","gray");

	}
	function SaveTifJPG_onclick()
	{
		captrue.vSetRotate(180);
		var str=captrue.bSaveTifJPG("D:\\","tifJPG");
	}
	function SaveTIF_onclick()
	{
		captrue.vSetRotate(270);
		captrue.vSetDPI(200,200);

		var str=captrue.bSaveTIF24Bit("D:\\","TIF", 0);
	}

	function SaveMulTIF_onclick()
	{
		captrue.vSetDPI(200,200);
		var str=captrue.bSaveTIF24Bit("D:\\","MulTIF", 1);
	}
	function ParaSet_onclick()
	{
		var str=captrue.displayVideoPara();
	}
	function ParaSetPIN_onclick()
	{
		var str=captrue.vSetCapturePin();
		captrue.bStartPlay();
	}
	function CutHB_onclick()
	{
		var str=captrue.vSetDelHBFlag(1);
	}
	function Skew_onclick()
	{
		var str=captrue.vSetSkewFlag(1);
	}
	function StartPDF_onclick()
	{
		var pdffileName = document.getElementById("pdffileName").value;
		if(pdffileName == "")
		{
			pdffileName =  "pdffile";
		}
		var str=captrue.bSavePDFStart("D:\\", pdffileName);
	}
	function ColorPDF_onclick()
	{
		var str=captrue.bSavePDFColorPage();
	}
	function BWPDF_onclick()
	{
		var str=captrue.bSavePDFBWPage();
	}

	function EndPDF_onclick()
	{
		var str=captrue.bSavePDFEnd();
	}

	function SetBrightness_onclick(){
		var BrightnessValue = document.getElementById("BrightnessValue").value;
		captrue.vSetBrightness(BrightnessValue);
	}
	//默认亮度0
	function DefaultBrightness(){
		captrue.vSetBrightness(0);
		$('#BrightnessValue').val(0);
	}

	function SetContrast_onclick(){
		var ContrastValue = document.getElementById("ContrastValue").value;
		captrue.vSetContrast(ContrastValue);
	}

	function SetBrightness(el){
		captrue.vSetBrightness(el.value);
	}
	function rotateMain(){
		var str=captrue.bStopPlay();
		var str = captrue.bStartPlayRotate(270);

	}

	function getDeviceId()
	{
		var deviceId = captrue.sGetDevicesId();
		alert(deviceId);
	}

	function selectAutoMode(el)
	{
		captrue.bSetMode(el.value);
	}

	function selectDefaultMode(el)
	{
		captrue.bSetMode(el.value);
	}

	function selectSfzMode(el)
	{
		captrue.bSetMode(el.value);
		if(el.value == 1)
		{
			captrue.bSetImageArea(1000,1000,8000,8000);
		}
	}

	function SelectExposure(el)
	{
		captrue.vSetExposure(el.value);
	}
	
	function openDialog(id)
	{
		$('#selectloanid').val(id);
		$( "#dialog" ).dialog( "open" );
		event.preventDefault();
		Start1_onclick();	
	}
</script>