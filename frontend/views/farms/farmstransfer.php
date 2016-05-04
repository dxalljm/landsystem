<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Cooperative;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Parcel;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\Contractnumber;
use app\models\Loan;
use app\models\Lockedinfo;
/* @var $this yii\web\View */
/* @var $model app\models\Farms */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="farms-form">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                       整体转让
                    </h3>
                </div>
                <div class="box-body">
<?php if(!Farms::getLocked($farms_id)) {?>
    <?php $form = ActiveFormrdiv::begin(); ?>
  <table width="100%" border="0">
    <tr>
    <td width="47%">
    <table width="100%" height="408px" class="table table-bordered table-hover">
      <tr>
        <td width="20%" align='right' valign="middle">农场名称</td>
        <td width="30%" colspan="5" align='left' valign="middle"><?= $model->farmname?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">承包人姓名</td>
        <td colspan="5" align='left' valign="middle"><?= $model->farmername ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">身份证号</td>
        <td colspan="5" align='left' valign="middle"><?= $model->cardid ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">电话号码</td>
        <td colspan="5" align='left' valign="middle"><?= $model->telephone ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">合同号</td><?php if($model->contractnumber == '') $model->contractnumber = Farms::getContractnumber($_GET['farms_id']);?>
        <td colspan="5" align='left' valign="middle"><?= $model->contractnumber ?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">承包年限</td>
        <td align='center'>自</td>
        <td align='center'><?= $model->begindate ?></td>
        <td align='center'>至</td>
        <td align='center'><?= $model->enddate?></td>
        <td align='center'>止</td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">农场位置</td>
        <td colspan="5" align='left' valign="middle"><?= $model->address?></td>
        </tr>
      <tr>
        <td width="20%" align='right' valign="middle">宗地</td>
        <td colspan="5" align='left' valign="middle"><?= $model->zongdi?></td>
        </tr>
       <tr>
        <td align='right' valign="middle">合同面积</td>
        <td colspan="5" align='left' valign="middle"><?= $model->contractarea ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">宗地面积</td>
        <td colspan="5" align='left' valign="middle"><?= $model->measure ?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">未明确地块</td>
        <td colspan="5" align='left' valign="middle"><?= $model->notclear?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">未明确状态地块</td>
        <td colspan="5" align='left' valign="middle"><?= $model->notstate?></td>
        </tr>
      <tr>
        <td align='right' valign="middle">备注</td>
        <td colspan="5" align='left' valign="middle"><?= $model->remarks ?></td>
        </tr>
    </table></td>
    <td width="4%" align="center"><font size="5"><i class="fa fa-arrow-right"></i></font></td>
    <td width="50%" valign="top">
    <table width="411" class="table table-bordered table-hover">
      <tr>
        <td width="30%" align='right'>农场名称</td>
        <td colspan="4" align='left'><?=  $form->field($nowModel, 'farmname')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchFarms','class'=>'btn btn-success'])?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>承包人姓名</td>
        <td colspan="4" align='left'><?=  $form->field($nowModel, 'farmername')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchFarmer','class'=>'btn btn-success'])?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>身份证号</td>
        <td colspan="4" align='left'><?=  $form->field($nowModel, 'cardid')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchCardid','class'=>'btn btn-success'])?></td>
        </tr>
      <tr>
        <td width="30%" align='right'>电话号码</td>
        <td colspan="4" align='left'><?=  $form->field($nowModel, 'telephone')->textInput(['maxlength' => 500])->label(false)->error(false)?></td>
        <td align='left'><?= html::a('查询','#',['id'=>'searchTelephone','class'=>'btn btn-success'])?></td>
        </tr>
        <tr>
			<td width=25% align='right'>合同号</td><?php $nowModel->contractnumber = $model->contractnumber;?>
			<td colspan="5" align='left'><?= $form->field($nowModel, 'contractnumber')->textInput(['maxlength' => 500,'readonly'=>'readonly'])->label(false)->error(false) ?></td>
		</tr>
		<tr>
			<td width=25% align='right'>承包年限</td>
			<td align='center'>自</td><?php $nowModel->begindate = '2010-09-13';$nowModel->enddate='2025-09-13';?>
			<td align='center'><?= $form->field($nowModel, 'begindate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
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
			<td align='center'><?= $form->field($nowModel, 'enddate')->textInput(['maxlength' => 500])->label(false)->error(false)->widget(
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
		  <td align='right' valign="middle">农场位置</td>
		  <td colspan="5" align='left' valign="middle"><?php if(empty($model->address)) echo $form->field($nowModel, 'zongdi')->textInput()->label(false)->error(false); ?></td>
		  </tr>
		<tr>
		  <td align='right' valign="middle">宗地</td>
		  <td colspan="5" align='left' valign="middle"><?php if($model->measure != $model->contractarea) echo $form->field($nowModel, 'zongdi')->textarea(['readonly' => false,'rows' => 2])->label(false)->error(false); ?></td>
		  </tr>
		<tr>
		  <td align='right' valign="middle">面积</td>
		  <td colspan="5" align='left' valign="middle"><?= $model->measure ?></td>
		  </tr>
		<tr>
		  <td align='right' valign="middle">未明确地块</td>
		  <td colspan="5" align='left' valign="middle"><?= $model->notclear?></td>
		  </tr>
      <tr>
        <td align='right'>备注</td>
        <td  colspan="5" align='left'><?= $form->field($nowModel, 'remarks')->textarea(['rows' => 2])->label(false)->error(false) ?></td>
      </tr>
    </table></td>
  </tr>
</table>
<div class="form-group">
      <?= Html::submitButton('提交申请', ['class' => 'btn btn-success']) ?>
      <?= Html::a('返回', [Yii::$app->controller->id.'ttpomenu','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success'])?>
</div>

    <?php ActiveFormrdiv::end(); ?>
     <?php } else {?>
    	<h4><?= Lockedinfo::find()->where(['farms_id'=>$farms_id])->one()['lockedcontent']?></h4>
    <?php }?>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'zongdi-modal',
	'size'=>'modal-small',

]); 

?>

<?php \yii\bootstrap\Modal::end(); ?>
<?php

$script = <<<JS
function modelShow(zongdi)
{
	$.get(
	    'index.php',         
	    {
	    	r: zongdi/zongdimodel,
	        zongdi: zongdi,
	         
	    },
	    function (data) {
	        $('.modal-body').html(data);
			$('#zongdi-modal').modal('show');       
	    }  
	);
}
$("#farms-zongdi").keyup(function (event) {

    var input = $(this).val();
// 		alert(event.keyCode);
	if (event.keyCode == 32) {
		
		input = $.trim(input);
		$.getJSON('index.php?r=parcel/parcelarea', {zongdi: input}, function (data) {
			//alert(data.area);
			if (data.status == 1) {
				var oldfarmsmeasure = parseFloat($('#oldfarms-measure').val());
				var notclear = parseFloat($('#farms-notclear').val());
				var value = $('#farms-measure').val()*1+data.area*1;
				$('#farms-measure').val(value.toFixed(2));
				$('#temp_measure').val(value.toFixed(2));
				
				$('#temp-zongdi').val($.trim(input)+'、');
				$("#farms-zongdi").val($.trim(input)+'、');
				if(oldfarmsmeasure == 0) {
					var notclear = $('#oldfarms-notclear').val()*1 - data.area*1;
					$('#oldfarms-notclear').val(notclear.toFixed(2));					
					
					$('#temp_oldcontractarea').val($('#oldfarms-contractarea').val());
				}
				
				var measure = $("#farms-measure").val()*1;
				if(measure < contractarea) {
					var cha = contractarea - measure;
					$("#farms-notclear").val(cha.toFixed(2));
				} else {
					$("#farms-notclear").val(0);
					$("#farms-contractarea").val(value.toFixed(2));
				}
				
			}
			else {
				alert(data.message);
				$("#farms-zongdi").val($('#temp-zongdi').val());
				
			}
		});
		
	}
	if (event.keyCode == 8) {
		var zongdi = $('#farms-zongdi').val();
		var arrayZongdi = zongdi.split('、');
		var rows = arrayZongdi.length*1 - 1;
		var delZongdi = arrayZongdi[rows];
		var zongdiNumber = delZongdi.split('(');
		resetZongdi(zongdiNumber[0],zongdiNumber[1]);
		arrayZongdi.splice(rows,1); 
		$('#farms-zongdi').val(arrayZongdi.join('、'));
		var input = $(this).val();
		if(input) {
		    input = $.trim(input);
			$.getJSON('index.php?r=parcel/getformatzongdi', {zongdi: input}, function (data) {
				if (data.status == 1) {
					var oldfarmsmeasure = parseFloat($('#oldfarms-measure').val());
					$("#farms-zongdi").val($.trim(data.formatzongdi));	
					$("#farms-measure").val(data.sum);
					if(oldfarmsmeasure == 0) {
						var notclear = $('#temp_oldnotclear').val()*1 - data.sum*1 - $('#farms-notclear').val()*1;
						$('#oldfarms-notclear').val(notclear.toFixed(2));
						$('#temp_oldcontractarea').val(notclear.toFixed(2));
						toHTH();
					}
					var contractarea = $('#farms-contractarea').val()*1;
					var measure = $('#farms-measure').val()*1;
					if(measure > contractarea) {
						$('#farms-notstate').val(measure - contractarea);
					}
				
				}	
			});
		} else {
			$("#farms-measure").val(0);
			
			var notclear = $('#temp_oldnotclear').val()*1 - $('#farms-notclear').val()*1;
			$('#oldfarms-notclear').val(notclear.toFixed(2));
			$('#temp_oldcontractarea').val(notclear.toFixed(2));
			
			
		}
	}
 });
$('#farms-zongdi').blur(function(){
	var input = $(this).val();
	if(input) {
	    input = $.trim(input);
		
		$.getJSON('index.php?r=parcel/getformatzongdi', {zongdi: input}, function (data) {
			if (data.status == 1) {
				
				$("#farms-zongdi").val($.trim(data.formatzongdi));	
				$("#farms-measure").val(data.sum);
				$('#temp_measure').val(data.sum);
				var oldfarmsmeasure = parseFloat($('#oldfarms-measure').val());
				toHTH();
				var measure = $("#farms-measure").val()*1;
				if(oldfarmsmeasure == 0) {
					
					var contractarea = $("#farms-contractarea").val()*1;
					var result = $('#temp_oldnotclear').val()*1 - contractarea*1;
					$('#oldfarms-notclear').val(result.toFixed(2));
					$('#temp_oldcontractarea').val(result.toFixed(2));
					
				} else {
					
// 					var tempzongdi = $('#temp-zongdi').val();
// 					var arrayTempZongdi = tempzongdi.split('、');
					var zongdi = $('#farms-zongdi').val();
					var arrayZongdi = zongdi.split('、');
					var sum = 0.0;
					$.each(arrayZongdi,function(n,value) { 
						sum +=  getArea(value)*1;
					});
					
					var result = $('#temp_oldmeasure').val() *1 - sum*1;
					$('#oldfarms-measure').val(result.toFixed(2));
					
					var contractarea = $("#farms-contractarea").val()*1;
					var tempoldcontractarea = $('#temp_oldcontractarea').val()*1;
					if(contractarea < tempoldcontractarea) {
						$('#farms-notstate').val(0);
					}
					
				}
			}	
		});
	} else {
		$("#farms-measure").val(0);
		
	}
});

$('#searchFarms').click(function(){
	var input = $('#farms-farmname').val();
	$.getJSON('index.php?r=farms/getfarminfo', {str: input}, function (data) {
		if (data.status == 1) {
			$('#farms-farmername').val(data.data['farmername']);
			$('#farms-cardid').val(data.data['cardid']);
			$('#farms-telephone').val(data.data['telephone']);
		}	
	});
});
$('#searchFarmer').click(function(){
	var input = $('#farms-farmername').val();
	
	$.getJSON('index.php?r=farms/getfarmerinfo', {str: input}, function (data) {
		if (data.status == 1) {
			$('#farms-farmname').val(data.data['farmname']);
			$('#farms-cardid').val(data.data['cardid']);
			$('#farms-telephone').val(data.data['telephone']);
		}	
	});
});
$('#searchCardid').click(function(){
	var input = $('#farms-cardid').val();
	
	$.getJSON('index.php?r=farms/getcardidinfo', {str: input}, function (data) {
		if (data.status == 1) {
			$('#farms-farmname').val(data.data['farmname']);
			$('#farms-farmername').val(data.data['farmername']);
			$('#farms-telephone').val(data.data['telephone']);
		}	
	});
});
$('#searchTelephone').click(function(){
	var input = $('#farms-telephone').val();
	
	$.getJSON('index.php?r=farms/gettelephoneinfo', {str: input}, function (data) {
		if (data.status == 1) {
			$('#farms-farmname').val(data.data['farmname']);
			$('#farms-cardid').val(data.data['cardid']);
			$('#farms-farmername').val(data.data['farmername']);
		}	
	});
});
JS;
$this->registerJs($script);





?>
