<?php
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveFormrdiv;
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
/* @var $this yii\web\View */
/* @var $model app\models\farmer */

?>
<div class="farmer-form">
<h3><?= $farm->farmname.'('.Theyear::findOne(1)['years'].'年度)' ?></h3>
<?php //echo Html::a('XLS导入', ['farmerxls'], ['class' => 'btn btn-success']) ?>
<?php Farms::showRow($_GET['farms_id']);?>
<?php $form = ActiveFormrdiv::begin(['id' => "farmer-form",'enableAjaxValidation' => false,'options' => ['enctype' => 'multipart/form-data'],]); ?>
      <?= $form->field($model, 'isupdate')->hiddenInput()->label(false);?>
      <?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['farms_id']])->label(false);?>
    <table width="662"  class="table table-bordered table-hover">
      <tr>
        <td width="12%" height="25" align="right" valign="middle">承包人姓名</td>
        <td colspan="2" valign="middle"><?= $farm->farmername?></td>
        <td width="9%" height="25" align="right" valign="middle">曾用名</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'farmerbeforename')->textInput(['maxlength' => 8])->label(false)->error(false); else echo '&nbsp;'.$model->farmerbeforename; ?></td>
        <td width="19%" rowspan="6" align="center" valign="middle">
        <span class="btn btn-success fileinput-button">
		    <i class="glyphicon glyphicon-plus"></i>
		    <span>请选择...</span>
		    <input id="fileuploadphoto" type="file" name="upload_file" multiple="">
		</span>
        <p>
          <?php echo Html::img($model->photo,['width'=>'180px','height'=>'200px','id'=>'photo']); ?>
          <?php echo $form->field($model,'photo')->hiddenInput(['id'=>'photoresult'])->label(false)?>
        </p></td>
     </tr>
      <tr>
        <td align="right" valign="middle">身份证号</td>
        <td colspan="2" valign="middle"><?= $farm->cardid?></td>
        <td align="right" valign="middle">性别</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'gender')->dropDownList(['男'=>'男','女'=>'女'])->label(false)->error(false); else echo '&nbsp;'.$model->gender; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">民族</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'nation')->dropDownList(ArrayHelper::map(Nation::find()->all(),'id','nationname'))->label(false)->error(false); else echo '&nbsp;'.Nation::find()->where(['id'=>$model->nation])->one()['nationname']; ?></td>
        <td align="right" valign="middle">政治面貌</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'political_outlook')->dropDownList(['群众'=>'群众','团员'=>'团员','党员'=>'党员','民主党派'=>'民主党派','其他'=>'其他'])->label(false)->error(false); else echo '&nbsp;'.$model->political_outlook; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">文化程度</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'cultural_degree')->dropDownList(['文盲'=>'文盲','小学'=>'小学','初中'=>'初中','高中'=>'高中','中专'=>'中专','大专'=>'大专','本科'=>'本科','研究生'=>'研究生'])->label(false)->error(false); else echo '&nbsp;'.$model->cultural_degree; ?></td>
        <td align="right" valign="middle">电话</td>
        <td colspan="2" valign="middle"><?= $farm->telephone?></td>
      </tr>
      <tr><?php if($model->domicile == '') $model->domicile = '黑龙江省大兴安岭地区加格达奇区';?><?php if($model->nowlive == '') $model->nowlive = '黑龙江省大兴安岭地区加格达奇区';?>
        <td align="right" valign="middle">户籍所在地</td>
        <td colspan="5" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'domicile')->textInput(['maxlength' => 200])->label(false)->error(false); else echo '&nbsp;'.$model->domicile; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">现住地</td>
        <td colspan="5" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'nowlive')->textInput(['maxlength' => 200])->label(false)->error(false); else echo '&nbsp;'.$model->nowlive; ?></td>
      </tr>
	  
      
      <tr>
        <td align="right" valign="middle">身份证扫描件<br><span class="btn btn-success fileinput-button">
		    <i class="glyphicon glyphicon-plus"></i>
		    <span>请选择...</span>
		    <input id="fileuploadcardpic" type="file" name="upload_file" multiple="">
		</span></td>
        <td colspan="6" valign="middle">
       <?php echo '&nbsp;'.Html::img($model->cardpic,['width'=>'400px','height'=>'220px','id'=>'cardpic']); ?>
       <?php echo $form->field($model,'cardpic')->hiddenInput(['id'=>'cardpicresult'])->label(false)?>
       </td>
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
              <?php echo Html::dropDownList('Parmembers[relationship][]', '', ['妻子','丈夫','儿子','女儿','父亲','母亲','岳父','岳母','公公','婆婆','弟兄','姐妹'],['class' => 'form-control']); ?></td>
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
			  <?php echo Html::dropDownList('Parmembers[relationship][]', '',['妻子','丈夫','儿子','女儿','父亲','母亲','岳父','岳母','公公','婆婆','弟兄','姐妹'], ['class' => 'form-control']); ?></td>
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
			  <?php echo Html::dropDownList('Parmembers[relationship][]', $value['relationship'],['妻子','丈夫','儿子','女儿','父亲','母亲','岳父','岳母','公公','婆婆','弟兄','姐妹'], ['class' => 'form-control']); ?></td>
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
			  <td valign="middle" align="center"><?php echo $value['relationship']; ?></td>
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

  		<?= Html::submitButton('提交', ['class' => 'btn btn-danger','title'=>'注意：点提交后不可更改','method' => 'post','onclick'=>'submittype(1)']) ?>

        <?= Html::submitButton('保存', ['class' => 'btn btn-success','title'=>'注意：在不确定数据正确可点击保存','method' => 'post','onclick'=>'submittype(0)']) ?>

<?php }?>
 </div>
    <?php ActiveFormrdiv::end(); ?>
    <?php $this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>
<script type="text/javascript">
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
</div>
<script language="javascript" type="text/javascript">

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