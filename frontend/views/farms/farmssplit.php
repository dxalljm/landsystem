<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperative;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Parcel;
use app\models\ManagementArea;
use app\models\Lease;
use app\models\Farms;

/* @var $this yii\web\View */
/* @var $model app\models\Farms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farms-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">
  <table width="100%" border="0">
    <tr>
    <td width="46%" valign="top"><table width="104%" height="458px"
		class="table table-bordered table-hover">
      <tr>
        <td width="15%" align='right' valign="middle">农场名称</td>
        <td colspan="5" align='left' valign="middle"><?= $oldFarm->farmname?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">承包人姓名</td>
        <td colspan="5" align='left' valign="middle"><?= $oldFarm->farmername ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">身份证号</td>
        <td colspan="5" align='left' valign="middle"><?= $oldFarm->cardid ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">电话号码</td>
        <td colspan="5" align='left' valign="middle"><?= $oldFarm->telephone ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">农场位置</td>
        <td colspan="5" align='left' valign="middle"><?= $oldFarm->address?></td>
        </tr>
      <tr>
      <tr>
        <td width="20%" align='right' valign="middle">管理区</td>
        <td colspan="5" align='left' valign="middle"><?= ManagementArea::find()->where(['id'=>$oldFarm->management_area])->one()['areaname']?></td>
        </tr>
      <tr>
      <tr>
			<td width=15% align='right'>合同号</td><?php if($oldFarm->contractnumber == '') $oldFarm->contractnumber = Farms::getContractnumber($_GET['farms_id']);?>
			<td colspan="5" align='left'><?= html::textInput('oldcontractnumber',$oldFarm->contractnumber,['id'=>'oldfarms-contractnumber','class'=>'form-control'])?></td>
		</tr>
		<tr>
			<td width=15% align='right'>承包年限</td>
			<td align='center'>自</td>
			<td align='center'><?php echo DateTimePicker::widget([
				'name' => 'oldbegindate',
				'value' => $oldFarm->begindate,
				'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
				'options' => [
					'readonly' => true
				],
				'clientOptions' => [
					'language' => 'zh-CN',
					'format' => 'yyyy-mm-dd',
					'todayHighlight' => true,
					'autoclose' => true,
					'minView' => 3,
					'maxView' => 3,
				]
			]);?></td>
			<td align='center'>至</td>
			<td align='center'><?php echo DateTimePicker::widget([
				'name' => 'oldenddate',
				'value' => $oldFarm->enddate,
				'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
				//'type' => DatePicker::TYPE_COMPONENT_APPEND,
				'options' => [
					'readonly' => true
				],
				'clientOptions' => [
					'language' => 'zh-CN',
					'format' => 'yyyy-mm-dd',
					//'todayHighlight' => true,
					'autoclose' => true,
					'minView' => 3,
					'maxView' => 3,
				]
			]);?></td>
			<td align='center'>止</td>
		</tr>
		<tr>
        <td width="20%" align='right' valign="middle">宗地</td>
        <td colspan="5" align='left' valign="middle"><?php $arrayZongdi = explode('、', $oldFarm->zongdi);
        $i=0;
        foreach($arrayZongdi as $value) {
        	echo html::button($value,['onclick'=>'toZongdi("'.Lease::getZongdi($value).'","'.Lease::getArea($value).'")','value'=>$value,'id'=>Lease::getZongdi($value),'class'=>"btn btn-default"]).'&nbsp;&nbsp;&nbsp;';
        	$i++;
        	if($i%5 == 0)
        		echo '<br><br>';
        }
        ?></td>
        </tr>
      <tr><?= Html::hiddenInput('oldzongdi',$oldFarm->zongdi,['id'=>'oldfarm-zongdi']) ?>
      <?= Html::hiddenInput('ttpozongdi','',['id'=>'ttpozongdi-zongdi']) ?>
      <?= Html::hiddenInput('ttpoarea',0,['id'=>'ttpozongdi-area']) ?>
        <td align='right' valign="middle">面积</td><?= html::hiddenInput('tempoldmeasure',$oldFarm->measure,['id'=>'temp_oldmeasure']) ?>
        										 <?= html::hiddenInput('tempoldnotclear',$oldFarm->notclear,['id'=>'temp_oldnotclear']) ?>
        <td colspan="5" align='left' valign="middle"><?= html::textInput('oldmeasure',$oldFarm->measure,['readonly' => true,'id'=>'oldfarms-measure','class'=>'form-control']) ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">未明确地块</td>
        <td colspan="5" align='left' valign="middle"><?= html::textInput('oldnotclear',$oldFarm->notclear,['readonly' => true,'id'=>'oldfarms-notclear','class'=>'form-control']) ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">法人签字</td>
        <td colspan="5" align='left' valign="middle"><?= html::textInput('oldfarmersign', $oldFarm->farmersign ,['class'=>'form-control'])?></td>
        </tr>
    </table></td>
    <td width="4%" align="center"><font size="5"><i class="fa fa-arrow-right"></i></font></td>
    <td width="50%">
    <table width="99%" height="458px" class="table table-bordered table-hover">
      <tr>
        <td width="30%" align='right'>农场名称</td>
        <td colspan="5" align='left'><?=  $form->field($model, 'farmname')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>承包人姓名</td>
        <td colspan="5" align='left'><?=  $form->field($model, 'farmername')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>身份证号</td>
        <td colspan="5" align='left'><?=  $form->field($model, 'cardid')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>电话号码</td>
        <td colspan="5" align='left'><?=  $form->field($model, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>农场位置</td>
        <td colspan="5" align='left'><?=  $oldFarm->address?></td>
        </tr>
       <tr>
        <td width="30%" align='right' valign="middle">管理区</td>
        <td colspan="5" align='left' valign="middle"><?=  ManagementArea::find()->where(['id'=>$oldFarm->management_area])->one()['areaname']?></td>
        </tr>
       <tr>
			<td width=30% align='right'>合同号</td><?php $model->contractnumber = Farms::getContractnumber($_GET['farms_id'],'new');?>
			<td colspan="5" align='left'><?= $form->field($model, 'contractnumber')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=30% align='right'>承包年限</td>
			<td align='center'>自</td><?php if($oldFarm->begindate == '') $model->begindate='2010-09-13'; else $model->begindate = $oldFarm->begindate;if($oldFarm->enddate == '') $model->enddate = '2025-09-13';else $model->enddate = $oldFarm->enddate;?>
			<td align='center'><?= $form->field($model, 'begindate')->textInput()->label(false)->error(false)->widget(
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
        ]]) ?></td>
			<td align='center'>至</td>
			<td align='center'><?= $form->field($model, 'enddate')->textInput()->label(false)->error(false)->widget(
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
        ]])?></td>
			<td align='center'>止</td>
		</tr>
		<tr>
		  <td align='right'>宗地</td>
		  <td colspan="5" align='left'><?= $form->field($model, 'zongdi')->textarea(['readonly' => false])->label(false)->error(false) ?></td>
		  </tr>
		<?php $model->notclear = 0;$model->measure = 0;?>
		<tr>
        <td align='right'>面积</td><?= html::hiddenInput('tempmeasure',$model->measure,['id'=>'temp_measure']) ?>
								  <?= html::hiddenInput('tempnotclear',$model->notclear,['id'=>'temp_notclear']) ?>
        <td colspan="5" align='left'><?= $form->field($model, 'measure')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
        </tr>
      <tr>
        <td align='right'>未明确地块面积</td>
        <td colspan="5" align='left'><?= $form->field($model, 'notclear')->textInput(['readonly' => true])->label(false)->error(false) ?></td>
       </tr>
       <tr>
        <td align='right'>转让未明确地块面积</td>
        <td colspan="5" align='left'><?= html::textInput('inputnotclear','',['id'=>'input-notclear','class'=>'form-control']) ?></td>
       </tr>
      <tr>
        <td align='right'>法人签字</td>
        <td colspan="5" align='left'><?= $form->field($model, 'farmersign')->textInput()->label(false)->error(false) ?></td>
        </tr>
    </table></td>
  </tr>
</table>
<div class="form-group">
      <?= Html::submitButton('提交', ['class' =>  'btn btn-success']) ?>
      <?= Html::button('重置', ['class' => 'btn btn-primary','id'=>'reset']) ?>
      <?= Html::a('返回', [Yii::$app->controller->id.'ttpomenu','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
</div>

    <?php ActiveFormrdiv::end(); ?>
    
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script>
function toZongdi(zongdi,area){
	$('#'+zongdi).attr('disabled',true);
	var value = $('#oldfarms-measure').val()*1-area*1;
	$('#oldfarms-measure').val(value.toFixed(2));
	var newvalue = $('#farms-measure').val()*1 + area*1;
	$('#farms-measure').val(newvalue.toFixed(2));
	$('#temp_measure').val(newvalue.toFixed(2));
	var newzongdi = $('#farms-zongdi').val()+'、'+zongdi+'('+area+')';
	var first = newzongdi.substr(0,1);
	if(first == '、') {
		$('#farms-zongdi').val(newzongdi.substring(1));
	} else
		$('#farms-zongdi').val(newzongdi);
	var oldzongdi = $('#oldfarm-zongdi').val();
	$('#oldfarm-zongdi').val(oldzongdi.replace(zongdi, ""));
	oldzongdistr = $('#oldfarm-zongdi').val();
	var first = oldzongdistr.substr(0,1);
	var last = oldzongdistr.substr(oldzongdistr.length-1,1);
	if(first == '、') {
		$('#oldfarm-zongdi').val(oldzongdistr.substring(1));
	}
	if(last == '、') {
		$('#oldfarm-zongdi').val(oldzongdistr.substring(0,oldzongdistr.length-1));
	}
	//alert($('#oldfarm-zongdi').val());
	var ttpozongdi = $('#ttpozongdi-zongdi').val();
	ttpozongdi = ttpozongdi + '、' + zongdi;
	var first = ttpozongdi.substr(0,1);
	if(first == '、') {
		$('#ttpozongdi-zongdi').val(ttpozongdi.substring(1));
	}
	else
		$('#ttpozongdi-zongdi').val(ttpozongdi);
	
	var ttpoarea = $('#ttpozongdi-area').val();
	ttpoarea = area*1 + ttpoarea*1;
	$('#ttpozongdi-area').val(ttpoarea);
	toHTH();
}
$('#reset').click(function() {
	 
    location.reload();

});
function toHTH()
{
	//生成合同号
	var hth = $('#farms-contractnumber').val();
	var arrayhth = hth.split('-');
	arrayhth[2] = $('#farms-measure').val();
	$('#farms-contractnumber').val(arrayhth.join('-'));
	
	var hth = $('#oldfarms-contractnumber').val();
	var arrayhth = hth.split('-');
	arrayhth[2] = $('#oldfarms-measure').val();
	$('#oldfarms-contractnumber').val(arrayhth.join('-'));
}
$('#input-notclear').blur(function(){
	var input = $(this).val();
	if(input*1 > $('#temp_oldnotclear').val()*1) {
		
		alert('输入的数值不能大于'+$('#temp_oldnotclear').val());
		$(this).val(0);
		$(this).focus();
	} else {
		if(input > $('#temp_notclear').val()) {
			var tempmeasure = $('#temp_measure').val();
			var farmsmeasure = $('#farms-measure').val();
			if(farmsmeasure < tempmeasure) {
				var result = farmsmeasure*1 + input*1;
				$('#temp_measure').val(result.toFixed(2));
				$('#farms-measure').val(result.toFixed(2));
				$('#farms-notclear').val(input);
				$('#temp_notclear').val(input);	
				
			} else {
				
				var cha = input*1 - $('#temp_notclear').val()*1;
				var result = farmsmeasure*1 + cha*1;
				var oldmeasure = $('#oldfarms-measure').val();
				var oldresult = oldmeasure*1 - cha*1;
				$('#oldfarms-measure').val(oldresult.toFixed(2));
				var oldnotclear = $('#oldfarms-notclear').val();
				var notclearresult = oldnotclear*1 - cha*1;
				$('#oldfarms-notclear').val(notclearresult.toFixed(2));
				$('#temp_measure').val(result.toFixed(2));
				$('#farms-measure').val(result.toFixed(2));
				$('#farms-notclear').val(input);
				$('#temp_notclear').val(input);	
				
			}
		} 
		if(input < $('#temp_notclear').val()) {
			var tempmeasure = $('#temp_measure').val();
			var farmsmeasure = $('#farms-measure').val();
			if(farmsmeasure < tempmeasure) {
				var result = farmsmeasure*1 + input*1;
				$('#temp_measure').val(result.toFixed(2));
				$('#farms-measure').val(result.toFixed(2));
				$('#farms-notclear').val(input);	
				$('#temp_notclear').val(input);	
				
			} else {
				var cha = $('#temp_notclear').val()*1 - input*1;
				var result = farmsmeasure*1 - cha*1;
				var oldmeasure = $('#oldfarms-measure').val();
				var oldresult = oldmeasure*1 + cha*1;
				$('#oldfarms-measure').val(oldresult.toFixed(2));
				var oldnotclear = $('#oldfarms-notclear').val();
				var notclearresult = oldnotclear*1 + cha*1;
				$('#oldfarms-notclear').val(notclearresult.toFixed(2));
				$('#temp_measure').val(result.toFixed(2));
				$('#farms-measure').val(result.toFixed(2));
				$('#farms-notclear').val(input);
				$('#temp_notclear').val(input);	
				
			}
		}
	}
	toHTH();
});
$('#input-notclear').keyup(function (event) {
	var input = $(this).val();
	if(event.keyCode == 8) {
		$(this).val('');
		$('#farms-notclear').val(0);
		var result = $('#farms-measure').val()-$('#temp_notclear').val();
		$('#farms-measure').val(result.toFixed(2));
		$('#oldfarms-measure').val($('#oldfarms-measure').val()*1+$('#temp_notclear').val()*1);
		$('#oldfarms-notclear').val($('#oldfarms-notclear').val()*1+$('#temp_notclear').val()*1);	
		$('#temp_notclear').val('');
		$('#temp_measure').val('');
	} else {
		if(/^\d+(\.\d+)?$/.test(input)) {
			if($('#temp_notclear').val() !== '') {
				var result = $('#farms-measure').val()-$('#temp_notclear').val();
				$('#farms-measure').val(result.toFixed(2));
				$('#oldfarms-measure').val($('#oldfarms-measure').val()*1+$('#temp_notclear').val()*1);
				$('#oldfarms-notclear').val($('#oldfarms-notclear').val()*1+$('#temp_notclear').val()*1);	
				$('#temp_notclear').val('');
				$('#temp_measure').val('');
			}
		} else {
			alert('输入的必须为数字');
			var last = input.substr(input.length-1,1);
			$('#input-notclear').val(input.substring(0,input.length-1));
		}
	}
});

</script>
<?php

$script = <<<JS


$("#farms-zongdi").keyup(function (event) {
    var input = $(this).val();
	if (event.keyCode == 32) {  
		input = $.trim(input)+'、';  
		$("#farms-zongdi").val(input);
	}
	$.getJSON('index.php?r=parcel/parcelarea', {zongdi: input}, function (data) {
		if (data.status == 1) {
			$('#farms-measure').val(data.area);
		}
	});
 });
$('#farms-zongdi').blur(function(){
	var input = $(this).val();
    input = $.trim(input);
	$.getJSON('index.php?r=parcel/areasum', {zongdi: input}, function (data) {
		if (data.status == 1) {
			$("#farms-measure").val(data.sum);	
			var zarea = $("#temp_oldmeasure").val();
			var result = zarea*1-data.sum*1-$("#farms-notclear").val()*1;
			$('#oldfarms-measure').val(result.toFixed(2));
			var hth = $('#farms-contractnumber').val();
			var arrayhth = hth.split('-');
			arrayhth[2] = $('#farms-measure').val();
			$('#farms-contractnumber').val(arrayhth.join('-'));
			
			var hth = $('#oldfarms-contractnumber').val();
			var arrayhth = hth.split('-');
			arrayhth[2] = $('#oldfarms-measure').val();
			$('#oldfarms-contractnumber').val(arrayhth.join('-'));
		}	
	});
});
JS;
$this->registerJs($script);





?>
<div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true">Activity</a></li>
              <li class=""><a href="#timeline" data-toggle="tab" aria-expanded="false">Timeline</a></li>
              <li><a href="#settings" data-toggle="tab">Settings</a></li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="activity">
                <!-- Post -->
                <div class="post">
                  <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="../../dist/img/user1-128x128.jpg" alt="user image">
                        <span class="username">
                          <a href="#">Jonathan Burke Jr.</a>
                          <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                        </span>
                    <span class="description">Shared publicly - 7:30 PM today</span>
                  </div>
                  <!-- /.user-block -->
                  <p>
                    Lorem ipsum represents a long-held tradition for designers,
                    typographers and the like. Some people hate it and argue for
                    its demise, but others ignore the hate as they create awesome
                    tools to help create filler text for everyone from bacon lovers
                    to Charlie Sheen fans.
                  </p>
                  <ul class="list-inline">
                    <li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
                    <li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a>
                    </li>
                    <li class="pull-right">
                      <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments
                        (5)</a></li>
                  </ul>

                  <input class="form-control input-sm" type="text" placeholder="Type a comment">
                </div>
                <!-- /.post -->

                <!-- Post -->
                <div class="post clearfix">
                  <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="../../dist/img/user7-128x128.jpg" alt="User Image">
                        <span class="username">
                          <a href="#">Sarah Ross</a>
                          <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                        </span>
                    <span class="description">Sent you a message - 3 days ago</span>
                  </div>
                  <!-- /.user-block -->
                  <p>
                    Lorem ipsum represents a long-held tradition for designers,
                    typographers and the like. Some people hate it and argue for
                    its demise, but others ignore the hate as they create awesome
                    tools to help create filler text for everyone from bacon lovers
                    to Charlie Sheen fans.
                  </p>

                  <form class="form-horizontal">
                    <div class="form-group margin-bottom-none">
                      <div class="col-sm-9">
                        <input class="form-control input-sm" placeholder="Response">
                      </div>
                      <div class="col-sm-3">
                        <button type="submit" class="btn btn-danger pull-right btn-block btn-sm">Send</button>
                      </div>
                    </div>
                  </form>
                </div>
                <!-- /.post -->

                <!-- Post -->
                <div class="post">
                  <div class="user-block">
                    <img class="img-circle img-bordered-sm" src="../../dist/img/user6-128x128.jpg" alt="User Image">
                        <span class="username">
                          <a href="#">Adam Jones</a>
                          <a href="#" class="pull-right btn-box-tool"><i class="fa fa-times"></i></a>
                        </span>
                    <span class="description">Posted 5 photos - 5 days ago</span>
                  </div>
                  <!-- /.user-block -->
                  <div class="row margin-bottom">
                    <div class="col-sm-6">
                      <img class="img-responsive" src="../../dist/img/photo1.png" alt="Photo">
                    </div>
                    <!-- /.col -->
                    <div class="col-sm-6">
                      <div class="row">
                        <div class="col-sm-6">
                          <img class="img-responsive" src="../../dist/img/photo2.png" alt="Photo">
                          <br>
                          <img class="img-responsive" src="../../dist/img/photo3.jpg" alt="Photo">
                        </div>
                        <!-- /.col -->
                        <div class="col-sm-6">
                          <img class="img-responsive" src="../../dist/img/photo4.jpg" alt="Photo">
                          <br>
                          <img class="img-responsive" src="../../dist/img/photo1.png" alt="Photo">
                        </div>
                        <!-- /.col -->
                      </div>
                      <!-- /.row -->
                    </div>
                    <!-- /.col -->
                  </div>
                  <!-- /.row -->

                  <ul class="list-inline">
                    <li><a href="#" class="link-black text-sm"><i class="fa fa-share margin-r-5"></i> Share</a></li>
                    <li><a href="#" class="link-black text-sm"><i class="fa fa-thumbs-o-up margin-r-5"></i> Like</a>
                    </li>
                    <li class="pull-right">
                      <a href="#" class="link-black text-sm"><i class="fa fa-comments-o margin-r-5"></i> Comments
                        (5)</a></li>
                  </ul>

                  <input class="form-control input-sm" type="text" placeholder="Type a comment">
                </div>
                <!-- /.post -->
              </div>
              <!-- /.tab-pane -->
              <div class="tab-pane" id="timeline">
                <!-- The timeline -->
                <ul class="timeline timeline-inverse">
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-red">
                          10 Feb. 2014
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-envelope bg-blue"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 12:05</span>

                      <h3 class="timeline-header"><a href="#">Support Team</a> sent you an email</h3>

                      <div class="timeline-body">
                        Etsy doostang zoodles disqus groupon greplin oooj voxy zoodles,
                        weebly ning heekya handango imeem plugg dopplr jibjab, movity
                        jajah plickers sifteo edmodo ifttt zimbra. Babblely odeo kaboodle
                        quora plaxo ideeli hulu weebly balihoo...
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-primary btn-xs">Read more</a>
                        <a class="btn btn-danger btn-xs">Delete</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-user bg-aqua"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 5 mins ago</span>

                      <h3 class="timeline-header no-border"><a href="#">Sarah Young</a> accepted your friend request
                      </h3>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-comments bg-yellow"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 27 mins ago</span>

                      <h3 class="timeline-header"><a href="#">Jay White</a> commented on your post</h3>

                      <div class="timeline-body">
                        Take me to your leader!
                        Switzerland is small and neutral!
                        We are more like Germany, ambitious and misunderstood!
                      </div>
                      <div class="timeline-footer">
                        <a class="btn btn-warning btn-flat btn-xs">View comment</a>
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <!-- timeline time label -->
                  <li class="time-label">
                        <span class="bg-green">
                          3 Jan. 2014
                        </span>
                  </li>
                  <!-- /.timeline-label -->
                  <!-- timeline item -->
                  <li>
                    <i class="fa fa-camera bg-purple"></i>

                    <div class="timeline-item">
                      <span class="time"><i class="fa fa-clock-o"></i> 2 days ago</span>

                      <h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>

                      <div class="timeline-body">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                        <img src="http://placehold.it/150x100" alt="..." class="margin">
                      </div>
                    </div>
                  </li>
                  <!-- END timeline item -->
                  <li>
                    <i class="fa fa-clock-o bg-gray"></i>
                  </li>
                </ul>
              </div>
              <!-- /.tab-pane -->

              <div class="tab-pane" id="settings">
                <form class="form-horizontal">
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                    <div class="col-sm-10">
                      <input type="email" class="form-control" id="inputEmail" placeholder="Email">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputName" class="col-sm-2 control-label">Name</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputName" placeholder="Name">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputExperience" class="col-sm-2 control-label">Experience</label>

                    <div class="col-sm-10">
                      <textarea class="form-control" id="inputExperience" placeholder="Experience"></textarea>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="inputSkills" class="col-sm-2 control-label">Skills</label>

                    <div class="col-sm-10">
                      <input type="text" class="form-control" id="inputSkills" placeholder="Skills">
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <div class="checkbox">
                        <label>
                          <input type="checkbox"> I agree to the <a href="#">terms and conditions</a>
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                      <button type="submit" class="btn btn-danger">Submit</button>
                    </div>
                  </div>
                </form>
              </div>
              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>