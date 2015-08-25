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

/* @var $this yii\web\View */
/* @var $model app\models\farmer */

?>
<div class="farmer-form">
<h3><?= $farm->farmname.'('.Theyear::findOne(1)['years'].'年度)' ?></h3>
<?= Html::a('XLS导入', ['farmerxls'], ['class' => 'btn btn-success']) ?>
<?php $form = ActiveFormrdiv::begin(['id' => "farmer-form",'enableAjaxValidation' => false,'options' => ['enctype' => 'multipart/form-data'],]); ?>
      <?= $form->field($model, 'isupdate')->hiddenInput()->label(false);?>
      <?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['id']])->label(false);?>
    <table width="662"  class="table table-bordered table-hover table-condensed">
      <tr>
        <td width="10%" height="25" align="right" valign="middle">承包人姓名</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'farmername')->textInput(['maxlength' => 10])->label(false)->error(false); else echo '&nbsp;'.$model->farmername; ?></td>
        <td width="9%" height="25" align="right" valign="middle">曾用名</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'farmerbeforename')->textInput(['maxlength' => 8])->label(false)->error(false); else echo '&nbsp;'.$model->farmerbeforename; ?></td>
        <td width="19%" rowspan="6" align="center" valign="middle"><p>
          <?php if(!$model->isupdate and $model->photo == '') echo $form->field($model, 'photo')->fileInput(['maxlength' => 200])->label(false)->error(false); else echo Html::img($model->photo,['width'=>'180px','height'=>'200px']); ?>
        </p></td>
     </tr>
      <tr>
        <td align="right" valign="middle">身份证号</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'cardid')->textInput(['maxlength' => 18])->label(false)->error(false); else echo '&nbsp;'.$model->cardid; ?></td>
        <td align="right" valign="middle">性别</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'gender')->dropDownList(['男'=>'男','女'=>'女'])->label(false)->error(false); else echo '&nbsp;'.$model->gender; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">民族</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'nation')->dropDownList(ArrayHelper::map(Nation::find()->all(),'id','nationname'))->label(false)->error(false); else echo '&nbsp;'.Nation::find()->where(['id'=>$model->nation])->one()['nationname']; ?></td>
        <td align="right" valign="middle">政治面貌</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'political_outlook')->dropDownList(['党员'=>'党员','群众'=>'群众'])->label(false)->error(false); else echo '&nbsp;'.$model->political_outlook; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">文化程度</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'cultural_degree')->dropDownList(['研究生'=>'研究生','本科'=>'本科','大专'=>'大专','高中'=>'高中','初中'=>'初中','小学'=>'小学','文盲'=>'文盲'])->label(false)->error(false); else echo '&nbsp;'.$model->cultural_degree; ?></td>
        <td align="right" valign="middle">电话</td>
        <td colspan="2" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'telephone')->textInput(['size' => 11])->label(false)->error(false); else echo '&nbsp;'.$model->telephone; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">现住地</td>
        <td colspan="5" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'nowlive')->textInput(['maxlength' => 200])->label(false)->error(false); else echo '&nbsp;'.$model->nowlive; ?></td>
      </tr>
	  <tr>
        <td align="right" valign="middle">户籍所在地</td>
        <td colspan="5" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'domicile')->textInput(['maxlength' => 200])->label(false)->error(false); else echo '&nbsp;'.$model->domicile; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">合同号</td>
        <td colspan="5" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'contractnumber')->textInput(['maxlength' => 200])->label(false)->error(false); else echo '&nbsp;'.$model->contractnumber; ?></td>
        <td valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" valign="middle">承包年限</td>
        <td width="5%" align="center" valign="middle">自        </td>
        <td width="27%" align="center" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'begindate')->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 3,
        	'maxView' => 3,
            'format' => 'yyyy-mm-dd'
        ]
]); else echo '&nbsp;'.$model->begindate; ?></td>
        <td align="center" valign="middle">至</td>
        <td width="27%" align="center" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'enddate')->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 3,
        	'maxView' => 3,
            'format' => 'yyyy-mm-dd'
        ]
]); else echo '&nbsp;'.$model->enddate; ?></td>
        <td width="5%" align="center" valign="middle">止</td>
        <td align="center" valign="middle">&nbsp;</td>
      </tr>
      <tr>
        <td align="right" valign="middle">身份证扫描件</td>
        <td colspan="6" valign="middle"><?php if(!$model->isupdate and $model->cardpic == '') echo $form->field($model, 'cardpic')->fileInput(['maxlength' => 200])->label(false)->error(false); else echo '&nbsp;'.Html::img($model->cardpic,['width'=>'400px','height'=>'220px']); ?></td>
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
              <td><?php echo Html::hiddenInput('Parmembers[id][]', '', ['class' => 'form-control']); ?><?php echo Html::hiddenInput('Parmembers[farmer_id][]', '', ['class' => 'form-control']); ?><?php echo Html::textInput('Parmembers[relationship][]', '', ['class' => 'form-control']); ?></td>
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
			  <td><?php echo Html::hiddenInput('Parmembers[id][]', '', ['class' => 'form-control']); ?><?php echo Html::hiddenInput('Parmembers[farmer_id][]', '', ['class' => 'form-control']); ?><?php echo Html::textInput('Parmembers[relationship][]', '', ['class' => 'form-control']); ?></td>
			  <td><?php echo Html::textInput('Parmembers[membername][]', '', ['class' => 'form-control']); ?></td>
			  <td><?php echo Html::textInput('Parmembers[cardid][]', '', ['class' => 'form-control']); ?></td>
			  <td><?php echo Html::textInput('Parmembers[remarks][]', '', ['class' => 'form-control']); ?></td>
              <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-member-family']) ?></td>
		  </tr>
		<?php } else {?>
		<?php foreach($membermodel as $value) { ?>
		 <tr>
			  <td><?php echo Html::hiddenInput('Parmembers[id][]', $value['id'], ['class' => 'form-control']); ?><?php echo Html::hiddenInput('Parmembers[farmer_id][]', $value['farmer_id'], ['class' => 'form-control']); ?><?php echo Html::textInput('Parmembers[relationship][]', $value['relationship'], ['class' => 'form-control']); ?></td>
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

</script>
</div>
