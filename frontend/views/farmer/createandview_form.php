<?php
use yii\helpers\ArrayHelper;
use frontend\helpers\ActiveFormrdiv;
use app\models\tables;
use app\models\Nation;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Theyear;
use yii\web\View;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Farms;
use yii\helpers\Url;
use frontend\helpers\fileUtil;
use app\models\Tablefields;
use app\models\Farmermembers;
use frontend\helpers\photographDialog;
use app\models\Photogallery;
/* @var $this yii\web\View */
/* @var $model app\models\farmer */

?>
<script src="js/photographDialog.js"></script>
<div class="farmer-form">
<h3><?= $farmModel->farmname.'('.Theyear::findOne(1)['years'].'年度)' ?></h3>
<?php //echo Html::a('XLS导入', ['farmerxls'], ['class' => 'btn btn-success']) ?>
<?php Farms::showRow($_GET['farms_id']);?>
<?php photographDialog::dialogHtml();?>
<?php $form = ActiveFormrdiv::begin(['id' => "farmer-form",'enableAjaxValidation' => false,'options' => ['enctype' => 'multipart/form-data'],]); ?>
      <?= $form->field($model, 'isupdate')->hiddenInput()->label(false);?>
      <?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false);?>
      
    <table width="662"  class="table table-bordered table-hover">
      <tr>
        <td width="10%" height="25" align="right" valign="middle">承包人姓名</td>
        <td width="7%" valign="middle"><?= $farmModel->farmername?></td>
        <td width="11%" height="25" align="right" valign="middle">曾用名</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'farmerbeforename')->textInput(['maxlength' => 8])->label(false)->error(false); else echo '&nbsp;'.$model->farmerbeforename; ?></td>
        <td width="36%" rowspan="6" align="center" valign="middle">
        <span class="btn btn-success fileinput-button">
		    <i class="glyphicon glyphicon-plus"></i>
		    <span>请选择...</span>
		    <input id="fileuploadphoto" type="file" name="upload_file" multiple="">
		</span>
		<?php photographDialog::showDialog('拍照','photo-dialog');?>
        <p>
          <?php echo Html::img($model->photo,['width'=>'180px','id'=>'photo']); ?>
          <?php echo $form->field($model,'photo')->hiddenInput(['id'=>'photoresult'])->label(false)?>
        </p></td>
     </tr>
      <tr>
        <td align="right" valign="middle">身份证号</td>
        <td valign="middle"><?php if($farmModel->cardid)echo $farmModel->cardid;else echo Html::textInput('farms-cardid',$farmModel->cardid,['class'=>'form-control']);?></td>
        <td align="right" valign="middle">性别</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'gender')->dropDownList(['男'=>'男','女'=>'女'])->label(false)->error(false); else echo '&nbsp;'.$model->gender; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">民族</td>
        <td valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'nation')->dropDownList(ArrayHelper::map(Nation::find()->all(),'id','nationname'))->label(false)->error(false); else echo '&nbsp;'.Nation::find()->where(['id'=>$model->nation])->one()['nationname']; ?></td>
        <td align="right" valign="middle">政治面貌</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'political_outlook')->dropDownList(['群众'=>'群众','团员'=>'团员','党员'=>'党员','民主党派'=>'民主党派','其他'=>'其他'])->label(false)->error(false); else echo '&nbsp;'.$model->political_outlook; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">文化程度</td>
        <td valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'cultural_degree')->dropDownList(['文盲'=>'文盲','小学'=>'小学','初中'=>'初中','高中'=>'高中','中专'=>'中专','大专'=>'大专','本科'=>'本科','研究生'=>'研究生'])->label(false)->error(false); else echo '&nbsp;'.$model->cultural_degree; ?></td>
        <td align="right" valign="middle">电话</td>
        <td colspan="2" valign="middle"><?= Html::textInput('farms-telephone',$farmModel->telephone,['class'=>'form-control'])?></td>
      </tr>
      <tr><?php if($model->domicile == '') $model->domicile = '黑龙江省大兴安岭地区加格达奇区';?><?php if($model->nowlive == '') $model->nowlive = '黑龙江省大兴安岭地区加格达奇区';?>
        <td align="right" valign="middle">户籍所在地</td>
        <td colspan="4" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'domicile')->textInput(['maxlength' => 200])->label(false)->error(false); else echo '&nbsp;'.$model->domicile; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">现住地</td>
        <td colspan="4" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'nowlive')->textInput(['maxlength' => 200])->label(false)->error(false); else echo '&nbsp;'.$model->nowlive; ?></td>
      </tr>
	  
      
      <tr>
        <td align="right" valign="middle">身份证扫描件<br><span class="btn btn-success fileinput-button">
		    <i class="glyphicon glyphicon-plus"></i>
		    <span>请选择...</span>
		    <input id="fileuploadcardpic" type="file" name="upload_file" multiple="">		    
		</span>
		<?php photographDialog::showDialog('身份证正面拍照','cardpic-dialog');?>
		<?php photographDialog::showDialog('身份证反面拍照','cardpicback-dialog');?>
		</td>
        <td valign="middle">
          <?php echo '&nbsp;'.Html::img($model->cardpic,['width'=>'400px','height'=>'220px','id'=>'cardpic']); ?>
        <?php echo $form->field($model,'cardpic')->hiddenInput(['id'=>'cardpicresult'])->label(false)?></td>
        <td colspan="4" valign="middle"><?php echo '&nbsp;'.Html::img($model->cardpicback,['width'=>'400px','height'=>'220px','id'=>'cardpicback']); ?> <?php echo $form->field($model,'cardpicback')->hiddenInput(['id'=>'cardpicbackresult'])->label(false)?></td>
      </tr>
  </table>

 
<h3>家庭主要成员</h3>

<?php if(!$model->isupdate) {?>
<div class="form-group">
        <?= Html::button('增加成员', ['class' => 'btn btn-info','title'=>'点击可增加家庭成员', 'id' => 'add-member-family']) ?>
    </div><?php }?>
    <?php if(!$model->isupdate) {?>

  <table  class="table table-bordered table-hover table-condensed" id="member-family">

	  <!-- 家庭成员模板 -->
	  
      <thead id="member-family-template" class="d-none">
          <tr>
              <td><?php echo Html::hiddenInput('Parmembers[id][]', '', ['class' => 'form-control']); ?>
              <?php echo Html::hiddenInput('Parmembers[farmer_id][]', '', ['class' => 'form-control']); ?>
              <?php echo Html::dropDownList('Parmembers[relationship][]', '', Farmermembers::getRelationship(),['class' => 'form-control']); ?></td>
              <td><?php echo Html::textInput('Parmembers[membername][]', '', ['class' => 'form-control']); ?></td>
              <td><?php echo Html::textInput('Parmembers[cardid][]', '', ['class' => 'form-control']); ?></td>
              <td><?php echo Html::textInput('Parmembers[remarks][]', '', ['class' => 'form-control']); ?></td>
              <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-member-family']) ?></td>
          </tr>
      </thead>
	
      <tbody>
		  <tr>
			  <td width="88" height="25" align="center" valign="middle">关系</td>
			  <td width="97" align="center" valign="middle">姓名</td>
			  <td width="126" height="25" align="center" valign="middle">身份证号码</td>
			  <td width="121" align="center" valign="middle">备注</td>
              <td width="20" align="center" valign="middle">操作</td>
		  </tr>
		  <?php if(empty($membermodel)) {?>
		  <tr>
			  <td><?php echo Html::hiddenInput('Parmembers[id][]', '', ['class' => 'form-control']); ?>
			  <?php echo Html::hiddenInput('Parmembers[farmer_id][]', '', ['class' => 'form-control']); ?>
			  <?php echo Html::dropDownList('Parmembers[relationship][]', '',Farmermembers::getRelationship(), ['class' => 'form-control']); ?></td>
			  <td><?php echo Html::textInput('Parmembers[membername][]', '', ['class' => 'form-control']); ?></td>
			  <td><?php echo Html::textInput('Parmembers[cardid][]', '', ['class' => 'form-control']); ?></td>
			  <td><?php echo Html::textInput('Parmembers[remarks][]', '', ['class' => 'form-control']); ?></td>
              <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-member-family']) ?></td>
		  </tr>
		<?php } else {?>
		<?php foreach($membermodel as $value) { ?>
		 <tr>
			  <td><?php echo Html::hiddenInput('Parmembers[id][]', $value['id'], ['class' => 'form-control']); ?>
			  <?php echo Html::hiddenInput('Parmembers[farmer_id][]', $value['farmer_id'], ['class' => 'form-control']); ?>
			  <?php echo Html::dropDownList('Parmembers[relationship][]', $value['relationship'],Farmermembers::getRelationship(), ['class' => 'form-control']); ?></td>
			  <td><?php echo Html::textInput('Parmembers[membername][]', $value['membername'], ['class' => 'form-control']); ?></td>
			  <td><?php echo Html::textInput('Parmembers[cardid][]', $value['cardid'], ['class' => 'form-control']); ?></td>
			  <td><?php echo Html::textInput('Parmembers[remarks][]', $value['remarks'], ['class' => 'form-control']); ?></td>
			  <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-member-family']) ?></td>
		  </tr>
		<?php }}?>
      </tbody>
  </table>
<?php } else {?>
  <table  class="table table-bordered table-hover table-condensed">


	  <!-- 家庭成员显示模板 -->
		  <tr>
			  <td width="88" height="25" align="center" valign="middle">关系</td>
			  <td width="97" align="center" valign="middle">姓名</td>
			  <td width="126" height="25" align="center" valign="middle">身份证号码</td>
			  <td width="121" align="center" valign="middle">备注</td>
		  </tr>
<?php foreach($membermodel as $value) {?>
		  <tr>
			  <td valign="middle" align="center"><?php echo Farmermembers::getRelationship($value['relationship']); ?></td>
			  <td valign="middle" align="center"><?php echo $value['membername']; ?></td>
			  <td valign="middle" align="center"><?php echo $value['cardid']; ?></td>
			  <td valign="middle" align="center"><?php echo $value['remarks']; ?></td>
		  </tr>
<?php }?>
  </table>
  
<?php }?>
<?php Farms::showRow($_GET['farms_id']);?>
<?php if(!$model->isupdate) {?>
<div class="form-group">

  		<?= Html::submitButton('提交', ['class' => 'btn btn-danger','title'=>'注意：点提交后不可更改','method' => 'post','onclick'=>'submittype(0)']) ?>

        <?= Html::submitButton('保存', ['class' => 'btn btn-success','title'=>'注意：在不确定数据正确可点击保存','method' => 'post','onclick'=>'submittype(0)']) ?>

<?php }?>
 </div>
    <?php ActiveFormrdiv::end(); ?>

<script type="text/javascript">

	$( "#dialog" ).dialog({
		autoOpen: false,
		width: 1000,
		height: 600,
	});
	// Link to open the dialog
	$( "#cardpicback-dialog-link" ).click(function( event ) {
		
		$( "#dialog" ).dialog( "open" );
		
		event.preventDefault();
		Start1_onclick();
		$('#tempField').val('cardpicback');
		
	});
	$( "#cardpic-dialog-link" ).click(function( event ) {

		$( "#dialog" ).dialog( "open" );
		event.preventDefault();
		Start1_onclick();
		$('#tempField').val('cardpic');
	});
	$( "#photo-dialog-link" ).click(function( event ) {

		$( "#dialog" ).dialog( "open" );
		event.preventDefault();
		Start1_onclick();
		$('#tempField').val('photo');
	});
function submittype(v) {
	$('#farmer-isupdate').val(v);
}

    // 添加家庭成员
    $('#add-member-family').click(function () {
        var template = $('#member-family-template').html();
        $('#member-family > tbody').append(template);
    });

    // 删除
    $(document).on("click", ".delete-member-family", function () {
        $(this).parent().parent().remove();
    });

    // 关系
    $(document).on("blur", "input[name='Parmembers[relationship][]']", function () {
        if ($(this).val() == '') {
            return alert('关系不能为空');
        }
    });

    // 姓名
	$(document).on("blur", "input[name='Parmembers[membername][]']", function () {
        if ($(this).val() == '') {
            return alert('姓名不能为空');
        }
    });

    // 身份证
	$(document).on("blur", "input[name='Parmembers[cardid][]']", function () {
        var val = $(this).val();
		if (val == '') {
			return alert('身份证不能为空');
		}

        if(/^[0-9X]/i.test(val) == false) {
            return alert('请填写正确的身份证号码');
        }
	});
	$('#rowjump').keyup(function(event){
		input = $(this).val();
		$.getJSON('index.php?r=farms/getfarmid', {id: input}, function (data) {
			$('#setFarmsid').val(data.farmsid);
		});
	});
</script>
<script language="javascript" type="text/javascript">
function Start1_onclick()
{
// 	console.log(captrue);
      var str=captrue.bStopPlay();  	
      var str = captrue.bStartPlay();
}
function Start2_onclick()
{
	  var str=captrue.bStopPlay();
	  var str = captrue.bStartPlay2(0);
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
		var field = $('#tempField').val();
// 		alert(field);
		captrue.bSaveJPG("d:\\","JPG");
		var upload = captrue.bUpLoadImage("D:\\JPG.JPG", "192.168.1.10", 8001, "/front/uploadimage/upload.php");
//		alert(save);
		if(upload) {			
	        $.getJSON("<?= Url::to(['photogallery/photographdialog'])?>", {farms_id: <?= $_GET['farms_id']?>,field:field}, function (data) {
// 				var width = "400px"
			    $('#'+field).attr('src', data.url);
// 			    $('#'+field).attr('width',width);
				captrue.bDeleteFile("d:\\JPG.jpg");   
				var str=captrue.bStopPlay(); 
				$( "#dialog" ).dialog( "close" );
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
	
	function SetContrast_onclick(){
		var ContrastValue = document.getElementById("ContrastValue").value;
		captrue.vSetContrast(ContrastValue);
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
	$(function () {
		var url = "<?= Url::to(['photogallery/fileupload','controller'=>yii::$app->controller->id,'field'=>'cardpic','farms_id'=>$_GET['farms_id']]);?>";
        $('#fileuploadcardpic').fileupload({
            url: url,
            dataType: 'json',
			done: function (e, data) {
				var url2 = data.result.url;
				$('#cardpic').attr('src', url2);
				$('#cardpicresult').attr('value', url2);
            }
        });
	});
	$(function () {
		var url = "<?= Url::to(['photogallery/fileupload','controller'=>yii::$app->controller->id,'field'=>'photo','farms_id'=>$_GET['farms_id']]);?>";
        $('#fileuploadphoto').fileupload({
            url: url,
            dataType: 'json',
			done: function (e, data) {
				var url2 = data.result.url;
				$('#photo').attr('src', url2);
				$('#photoresult').attr('value', url2);
            }
        });
	});
</script>