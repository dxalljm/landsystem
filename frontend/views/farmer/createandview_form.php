<?php
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveFormrdiv;
use app\models\tables;
use app\models\Nation;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Theyear;
use yii\web\View;


/* @var $this yii\web\View */
/* @var $model app\models\farmer */

?>
<div class="farmer-form">
<h3><?= $farm->farmname.'('.Theyear::findOne(1)['years'].'年度)' ?></h3>
<?php $form = ActiveFormrdiv::begin(['id' => "farmer-form",'enableAjaxValidation' => false,'options' => ['enctype' => 'multipart/form-data'],]); ?>
      <?= $form->field($model, 'isupdate')->hiddenInput()->label(false);?>
      <?= $form->field($model, 'farms_id')->hiddenInput(['value'=>$_GET['id']])->label(false);?>
    <table  class="table table-bordered table-hover table-condensed">
      <tr>
        <td width="99" height="25" align="right" valign="middle">承包人姓名</td>
        <td width="129" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'farmername')->textInput(['maxlength' => 10])->label(false)->error(false); else echo '&nbsp;'.$model->farmername; ?></td>
        <td width="83" height="25" align="right" valign="middle">曾用名</td>
        <td width="121" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'farmerbeforename')->textInput(['maxlength' => 8])->label(false)->error(false); else echo '&nbsp;'.$model->farmerbeforename; ?></td>
        <td width="79" rowspan="6" align="center" valign="middle"><p>
          <?php if(!$model->isupdate and $model->photo == '') echo $form->field($model, 'photo')->fileInput(['maxlength' => 200])->label(false)->error(false); else echo Html::img($model->photo,['width'=>'180px','height'=>'200px']); ?>
        </p></td>
     </tr>
      <tr>
        <td align="right" valign="middle">身份证号</td>
        <td valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'cardid')->textInput(['maxlength' => 18])->label(false)->error(false); else echo '&nbsp;'.$model->cardid; ?></td>
        <td align="right" valign="middle">性别</td>
        <td valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'gender')->dropDownList(['男'=>'男','女'=>'女'])->label(false)->error(false); else echo '&nbsp;'.$model->gender; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">民族</td>
        <td valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'nation')->dropDownList(ArrayHelper::map(Nation::find()->all(),'id','nationname'))->label(false)->error(false); else echo '&nbsp;'.Nation::find()->where(['id'=>$model->nation])->one()['nationname']; ?></td>
        <td align="right" valign="middle">政治面貌</td>
        <td valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'political_outlook')->dropDownList(['党员'=>'党员','群众'=>'群众'])->label(false)->error(false); else echo '&nbsp;'.$model->political_outlook; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">文化程度</td>
        <td valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'cultural_degree')->dropDownList(['研究生'=>'研究生','本科'=>'本科','大专'=>'大专','高中'=>'高中','初中'=>'初中','小学'=>'小学','文盲'=>'文盲'])->label(false)->error(false); else echo '&nbsp;'.$model->cultural_degree; ?></td>
        <td align="right" valign="middle">电话</td>
        <td valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'telephone')->textInput(['size' => 11])->label(false)->error(false); else echo '&nbsp;'.$model->telephone; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">现住地</td>
        <td colspan="3" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'nowlive')->textInput(['maxlength' => 200])->label(false)->error(false); else echo '&nbsp;'.$model->nowlive; ?></td>
      </tr>
	  <tr>
        <td align="right" valign="middle">户籍所在地</td>
        <td colspan="3" valign="middle"><?php if(!$model->isupdate) echo $form->field($model, 'domicile')->textInput(['maxlength' => 200])->label(false)->error(false); else echo '&nbsp;'.$model->domicile; ?></td>
      </tr>
      <tr>
        <td align="right" valign="middle">身份证扫描件</td>
        <td colspan="4" valign="middle"><?php if(!$model->isupdate and $model->cardpic == '') echo $form->field($model, 'cardpic')->fileInput(['maxlength' => 200])->label(false)->error(false); else echo '&nbsp;'.Html::img($model->cardpic,['width'=>'400px','height'=>'220px']); ?></td>
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

</script>
</div>
