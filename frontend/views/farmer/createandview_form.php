<?php
use yii\helpers\ArrayHelper;
use frontend\helpers\ActiveFormrdiv;
use app\models\Tables;
use app\models\Nation;
use yii\helpers\Html;
use app\models\User;
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
//var_dump($farmerinfoModel);exit;
?>
<script src="/js/photographDialog.js"></script>
<!-- InputMask -->
<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<div class="farmer-form">
<h3><?= $farmModel->farmname?><font color="red">(<?= User::getYear()?>年度)</font></h3>
<?php //echo Html::a('XLS导入', ['farmerxls'], ['class' => 'btn btn-success']) ?>
<?php Farms::showRow($_GET['farms_id']);?>
<?php photographDialog::dialogHtml();?>

<?php $form = ActiveFormrdiv::begin(['id' => "farmer-form",'enableAjaxValidation' => false,'options' => ['enctype' => 'multipart/form-data']]); ?>
      <?= $form->field($model, 'isupdate')->hiddenInput()->label(false);?>
      <?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false);?>
      
    <table width="662"  class="table table-bordered table-hover">
      <tr>
        <td width="10%" height="25" align="right" valign="middle">承包人姓名</td>
        <td width="7%" valign="middle"><?= $farmModel->farmername?></td>
        <td width="11%" height="25" align="right" valign="middle">曾用名</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($farmerinfoModel, 'farmerbeforename')->textInput(['maxlength' => 8])->label(false)->error(false); else echo '&nbsp;'.$model->farmerbeforename; ?></td>
        <td width="16%" rowspan="6" align="center" valign="middle">
        <span class="btn btn-success fileinput-button">
		    <i class="glyphicon glyphicon-plus"></i>
		    <span>请选择...</span>
		    <input id="fileuploadphoto" type="file" name="upload_file" multiple="">
		</span>
		<?php //photographDialog::showDialog('拍照','photo-dialog');?>
        <p>
          <?php echo Html::img('http://192.168.1.10/'.$farmerinfoModel->photo,['width'=>'180px','id'=>'photo']); ?>
          <?php echo $form->field($farmerinfoModel,'photo')->hiddenInput()->label(false)?>
        </p></td>
     </tr>
      <tr>
        <td align="right" valign="middle">身份证号</td>
        <td valign="middle"><?php 
        
        if((strlen($farmModel->cardid) > 18) or (strlen($farmModel->cardid) < 18))
        	echo Html::textInput('farms-cardid',$farmModel->cardid,['class'=>'form-control']);
        else 
        	echo $farmModel->cardid;?></td>
        <td align="right" valign="middle">性别</td>
		  <td valign="middle"><?php if(!$model->isupdate) echo $form->field($farmerinfoModel, 'gender')->dropDownList(['男'=>'男','女'=>'女'])->label(false)->error(false); else echo '&nbsp;'.Nation::find()->where(['id'=>$farmerinfoModel->nation])->one()['nationname']; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">民族</td>
        <td valign="middle"><?php if(!$model->isupdate) echo $form->field($farmerinfoModel, 'nation')->dropDownList(ArrayHelper::map(Nation::find()->all(),'id','nationname'))->label(false)->error(false); else echo '&nbsp;'.Nation::find()->where(['id'=>$farmerinfoModel->nation])->one()['nationname']; ?></td>
        <td align="right" valign="middle">政治面貌</td>
        <td colspan="" valign="middle"><?php if(!$model->isupdate) echo $form->field($farmerinfoModel, 'political_outlook')->dropDownList(['群众'=>'群众','团员'=>'团员','党员'=>'党员','民主党派'=>'民主党派','其他'=>'其他'])->label(false)->error(false); else echo '&nbsp;'.$farmerinfoModel->political_outlook; ?></td>
		<td valign="left" id="zhibu"><?php if($farmerinfoModel->political_outlook == '党员') echo '<table width="100%"><tr><td style="vertical-align: middle">所在支部：</td><td>'.$form->field($farmerinfoModel,'zhibu')->textInput()->label(false)->error(false).'</td></tr></table>';?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">文化程度</td>
        <td valign="middle"><?php if(!$model->isupdate) echo $form->field($farmerinfoModel, 'cultural_degree')->dropDownList(['文盲'=>'文盲','小学'=>'小学','初中'=>'初中','高中'=>'高中','中专'=>'中专','大专'=>'大专','本科'=>'本科','研究生'=>'研究生'])->label(false)->error(false); else echo '&nbsp;'.$model->cultural_degree; ?></td>
        <td align="right" valign="middle">电话</td>
        <td colspan="2" valign="middle"><?= Html::textInput('farms-telephone',$farmModel->telephone,['class'=>'form-control'])?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">农场位置</td>
        <td valign="middle"><?php echo Html::textInput('farms-address',$farmModel->address,['class'=>'form-control','id'=>'address']);?></td>
        <td align="right" valign="middle">坐标</td>
        <td valign="middle"><?php echo Html::textInput('farms-longitude',$farmModel->longitude,['id'=>'longitude','class'=>'form-control', 'data-inputmask'=>'"mask": "E999°99′99.99″"', 'data-mask'=>""]);?></td>
        <td valign="middle"><?php echo Html::textInput('farms-latitude',$farmModel->latitude,['id'=>'latitude','class'=>'form-control','data-inputmask'=>'"mask": "N99°99′99.99″"', 'data-mask'=>""]);?></td>
      </tr>
      <tr><?php if($farmerinfoModel->domicile == '') $farmerinfoModel->domicile = '黑龙江省大兴安岭地区加格达奇区';?><?php if($farmerinfoModel->nowlive == '') $farmerinfoModel->nowlive = '黑龙江省大兴安岭地区加格达奇区';?>
        <td align="right" valign="middle">户籍所在地</td>
        <td colspan="4" valign="middle"><?php if(!$model->isupdate) echo $form->field($farmerinfoModel, 'domicile')->textInput(['maxlength' => 200])->label(false)->error(false); else echo '&nbsp;'.$farmerinfoModel->domicile; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">现住地</td>
        <td colspan="4" valign="middle"><?php if(!$model->isupdate) echo $form->field($farmerinfoModel, 'nowlive')->textInput(['maxlength' => 200])->label(false)->error(false); else echo '&nbsp;'.$farmerinfoModel->nowlive; ?></td>
      </tr>
	  
      
      <tr>
        <td align="right" valign="middle">身份证扫描件<br><span class="btn btn-success fileinput-button">
		    <i class="glyphicon glyphicon-plus"></i>
		    <span>请选择...(正面)</span>
		    <input id="fileuploadcardpic" type="file" name="upload_file" multiple="">
		</span>
			<span class="btn btn-success fileinput-button">
		    <i class="glyphicon glyphicon-plus"></i>
		    <span>请选择...(背面)</span>
		    <input id="fileuploadcardpicback" type="file" name="upload_file" multiple="">
		</span>
		<?php //photographDialog::showDialog('身份证正面拍照','cardpic-dialog');?>
		<?php //photographDialog::showDialog('身份证反面拍照','cardpicback-dialog');?>
		</td>
        <td valign="middle">
          <?php echo Html::img('http://192.168.1.10/'.$farmerinfoModel->cardpic,['width'=>'400px','height'=>'220px','id'=>'cardpic']); ?>
        <?php echo $form->field($farmerinfoModel,'cardpic')->hiddenInput()->label(false)?>
        <?php echo $form->field($farmerinfoModel,'cardpicback')->hiddenInput()->label(false)?>
        </td>
        <td colspan="4" valign="middle"><?php echo '&nbsp;'.Html::img('http://192.168.1.10/'.$farmerinfoModel->cardpicback,['width'=>'400px','height'=>'220px','id'=>'cardpicback']); ?></td>
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
		<?php
			  foreach($membermodel as $key => $value) {
				  ?>
		 <tr>
			  <td><?php echo Html::hiddenInput('Parmembers[id][]', $value['id'], ['class' => 'form-control']); ?>
			  <?php echo Html::hiddenInput('Parmembers[farmer_id][]', $value['farmer_id'], ['class' => 'form-control']); ?>
			  <?php echo Html::dropDownList('Parmembers[relationship][]', $value['relationship'],Farmermembers::getRelationship(), ['class' => 'form-control']); ?></td>
			  <td><?php echo Html::textInput('Parmembers[membername][]', $value['membername'], ['class' => 'form-control']); ?></td>
			  <td><?php echo Html::textInput('Parmembers[cardid][]', $value['cardid'], ['class' => 'form-control']); ?></td>
			  <td><?php echo Html::textInput('Parmembers[remarks][]', $value['remarks'], ['class' => 'form-control']); ?></td>
			  <td valign="middle" align="center"><?php echo Html::button('<i class="fa fa-minus"></i>', ['class' => 'btn btn-warning delete-member-family']) ?></td>
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

        <?= Html::submitButton('更新', ['class' => 'btn btn-success','method' => 'post','onclick'=>'submittype(0)']) ?>
<?php }?>
 </div>
    <?php ActiveFormrdiv::end(); ?>

<script type="text/javascript">
//	$("#longitude").blur(function () {
//		var s = /\d+/g;
//		var input = $(this).val();
//		var num = input.match(s);
//		$.each(num,function (key,val) {
//			switch (val.length) {
//				case 3:
//					if(val[0] == val[1] and val[0])
//					break;
//				case 2:
//					break;
//				case 1:
//					break;
//			}
//		});
//	});

	function mySubmit(flag){
		return flag;
	}

	$( "#dialog" ).dialog({
		autoOpen: false,
		width: 1000,
		height: 600,
	});


	$('#farmerinfo-political_outlook').change(function(){
		var input = $(this).val();
		if(input == '党员') {
			var html = '<table width="100%"><tr><td style="vertical-align: middle">所在支部：</td><td><?= Html::textInput('Farmerinfo[zhibu]',$farmerinfoModel->zhibu,['class'=>'form-control'])?></td></tr></table>';
			$('#zhibu').html(html);
		} else {
			$('#zhibu').html('');
		}
	});

	// Link to open the dialog
	$( "#cardpicback-dialog-link" ).click(function( event ) {
		
		$( "#dialog" ).dialog( "open" );		
//		event.preventDefault();
		Start1_onclick();
		$('#tempField').val('cardpicback');
		
	});
	$( "#cardpic-dialog-link" ).click(function( event ) {
		$( "#dialog" ).dialog( "open" );
//		event.preventDefault();
		Start1_onclick();
		$('#tempField').val('cardpic');
	});
	$( "#photo-dialog-link" ).click(function( event ) {
		$( "#dialog" ).dialog( "open" );
//		event.preventDefault();
		Start1_onclick();
		$('#tempField').val('photo');
	});
	$("#farmer-form").submit(function () {
	if($('#address').val() == '') {
		alert('对不起,农场位置不能为空');
		$('#address').focus();
		return mySubmit(false);
	}
	if($('#cardid').val() == '') {
		alert('对不起,身份证号不能为空');
		$('#cardid').focus();
		return mySubmit(false);
	}
	if($('#longitude').val() == '') {
		alert('对不起,农场坐标不能为空');
		$('#longitude').focus();
		return mySubmit(false);
	}
	if($('#latitude').val() == '') {
		alert('对不起,农场坐标不能为空');
		$('#latitude').focus();
		return mySubmit(false);
	}
	$('#farmer-isupdate').val(v);

});

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
		var field = $('#tempField').val();
// 		alert(field);
		captrue.bSaveJPG("d:\\","JPG");
		var upload = captrue.bUpLoadImage("D:\\JPG.JPG", "192.168.1.9", 80, "uploadimage/upload.php");
//		alert(save);
		if(upload) {
	        $.getJSON("<?= Url::to(['photogallery/photographdialog'])?>", {farms_id: <?= $_GET['farms_id']?>,field:field}, function (data) {
// 				var width = "400px"
			    $('#'+field).attr('src', data.url);
// 			    $('#'+field).attr('width',width);
				captrue.bDeleteFile("d:\\JPG.jpg");
				var str=captrue.bStopPlay();
				$('#farmer-'+field).val(data.url);
				$( "#dialog" ).dialog( "close" );
	    	});
		}
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
	$(function () {
		var url = "<?= Url::to(['photogallery/fileupload','controller'=>yii::$app->controller->id,'field'=>'cardpic','farms_id'=>$_GET['farms_id']]);?>";
        $('#fileuploadcardpic').fileupload({
            url: url,
            dataType: 'json',
			done: function (e, data) {
				var url2 = data.result.url;
				$('#cardpic').attr('src', url2);
				$('#farmer-cardpic').attr('value', url2);
            }
        });
	});
	$(function () {
		var url = "<?= Url::to(['photogallery/fileupload','controller'=>yii::$app->controller->id,'field'=>'cardpicback','farms_id'=>$_GET['farms_id']]);?>";
		$('#fileuploadcardpicback').fileupload({
			url: url,
			dataType: 'json',
			done: function (e, data) {
				var url2 = data.result.url;
				$('#cardpicback').attr('src', url2);
				$('#farmer-cardpicback').attr('value', url2);
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
	<script>
		$(function () {
			//Initialize Select2 Elements
			$(".select2").select2();

			//Money Euro
			$("[data-mask]").inputmask();


		});
	</script>