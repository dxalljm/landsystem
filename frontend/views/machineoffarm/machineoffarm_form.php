<?php

use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Machineoffarm;
use app\models\Machinetype;
use frontend\helpers\grid\GridView;
use dosamigos\datetimepicker\DateTimePicker;
/* @var $this yii\web\View */
/* @var $model app\models\Machineoffarm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="machineoffarm-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
	<tr>
		<td width=15% align='right'>机具大类</td>
		<td align='left' width=20%><?= html::dropDownList('bigclass',$bigclass,ArrayHelper::map(Machinetype::find()->where(['father_id'=>0])->all(), 'id', 'typename'),['class'=>'form-control','id'=>'bigClass','prompt'=>'请选择...']) ?></td>
		<td align='right'>机具小类</td>
		<td align='left'><?= html::dropDownList('smallclass',$smallclass,ArrayHelper::map(Machinetype::find()->where(['father_id'=>$bigclass])->all(),'id', 'typename'),['class'=>'form-control','id'=>'smallClass'])?></td>
		<td align='right'>机具品目</td>
		<td align='left'><?= html::dropDownList('lastclass',$lastclass,ArrayHelper::map(Machinetype::find()->where(['father_id'=>$smallclass])->all(),'id', 'typename'),['class'=>'form-control','id'=>'lastClass'])?></td>
	</tr>
	<?= $form->field($model, 'machinetype_id')->hiddenInput()->label(false)->error(false)?>
	<?= $form->field($model, 'machine_id')->hiddenInput()->label(false)->error(false)?>
	<tr>
		<td align='right'>机具名称</td>
		<td align='left'><?= $form->field($model, 'machinename')->textInput()->label(false)->error(false)?></td>
		<td align='center' id="machinemodel">机具型号:</td>
		<td align='right'>购置年限</td>
		<?php
		if(empty($model->acquisitiontime)) {
			$model->acquisitiontime = date('Y');
		}
		?>
		<td colspan=""><?= $form->field($model, 'acquisitiontime')->textInput()->label(false)->error(false)->widget(
    DateTimePicker::className(), [
        // inline too, not bad
        'inline' => false, 
    	'language'=>'zh-CN',
        
        'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
        	'minView' => 4,
        	'startView' => 4,
            'format' => 'yyyy'
        ]]) ?></td><?= Html::hiddenInput('tempState','list',['id'=>'state'])?>

		<td><?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'createbutton','onclick'=>$model->isNewRecord ? 'setState("create")': 'setState("update")']) ?></td>
		
	</tr>
</table>
<?php ActiveFormrdiv::end(); ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//             'id',
            'productname',
            'implementmodel',
            'filename',
            'enterprisename',
			'year',
            // 'enterprisename',
            // 'parameter:ntext',

            [
            
            'format'=>'raw',            
            'value' => function($model,$key){
            	return Html::button('添加此机具', ['class' => 'btn btn-primary btn-xs','id'=>'createbutton','onclick'=>'setMachineid('.$model->id.')']);
//             	return Html::submitButton($option,$url, [
// //             			'id' => 'AddMachineoffarmButton',
//             			'class' => 'btn btn-primary btn-xs machineoffarm',
//             	]);
            }
            ],
        ],
    ]); ?>


    

</div>
<div id="machineinfo" title="确认的机具信息">
	<table class="table table-bordered table-hover" id="machine">
		<tr>
			<td class="text-center"><strong>机具名称</strong></td>
			<td class="text-center" id="productname"></td>

		</tr>
		<tr>
			<td class="text-center"><strong>机具型号</strong></td>
			<td class="text-center" id="implementmodel"></td>

		</tr>
		<tr>
			<td class="text-center"><strong>分档名称</strong></td>
			<td class="text-center" id="filename"></td>

		</tr>
		<tr height="80px">
			<td class="text-center" width=""><strong>基本配置和参数</strong></td>
			<td class="text-center" id="parameter"></td>

		</tr>
		<tr>
			<td class="text-center"><strong>生产厂家</strong></td>
			<td class="text-center" id="enterprisename"></td>

		</tr>
		<tr>
			<td class="text-center"><strong>机具类型</strong></td>
			<td class="text-center" id="machinetype"></td>

		</tr>
	</table>
</div>
<script>
	function setMachineid(id){

// 	console.dir($("#bigClass option[value=25]").prop("selected", "selected"));
// 	console.dir($('#bigClass').val(25).prop("selected", "selected"));

// 	alert($("#bigClass").val());
		$('#state').val('create');
		$('#machineoffarm-machine_id').val(id);
		$.getJSON('index.php?r=machine/getmachineinfo', {id: id}, function (data) {
			$('#productname').text(data.data.machinename);
			$('#implementmodel').text(data.data.machinemodel);
			$('#filename').text(data.data.filename);
			$('#parameter').text(data.data.parameter);
			$('#enterprisename').text(data.data.enterprisename);
			$('#machinetype').text(data.data.machinetype);
		});
		$('#machineinfo').dialog("open");
	}
function setState(state){
	$('#state').val(state);
}
$('#machineinfo').dialog({
	autoOpen: false,
	width: 800,
	//    show: "blind",
	//    hide: "explode",
	modal: true,//设置背景灰的
	buttons: [
		{
			text: "确定",
			class:'btn btn-success',
			click: function() {
				$( this ).dialog( "close" );
				$.getJSON('index.php?r=machine/getmachineinfo', {id: $('#machineoffarm-machine_id').val()}, function (data) {
					if (data.status == 1) {
						$('#bigClass').val(data.data.big.id).prop("selected","selected");
						$('#smallClass').html(null);
						$('#smallClass').append('<option value="prompt">请选择...</option>');
						for(i=0;i<data.data.small.data.length;i++) {
							$('#smallClass').append('<option value="'+data.data.small.data[i]['id']+'">'+data.data.small.data[i]['typename']+'</option>');
						}
						$('#smallClass').val(data.data.small.id).prop("selected","selected");
						$('#lastClass').html(null);
						$('#lastClass').append('<option value="prompt">请选择...</option>');
						for(i=0;i<data.data.last.data.length;i++) {
							$('#lastClass').append('<option value="'+data.data.last.data[i]['id']+'">'+data.data.last.data[i]['typename']+'</option>');
						}
						$('#lastClass').val(data.data.last.id).prop("selected","selected");
						$('#machineoffarm-machinename').val(data.data.machinename);
						$('#machinemodel').text('机具型号:'+data.data.machinemodel);

					}
				});
			}

		},
		{
			text: "取消",
			class:'btn btn-danger',
			click: function() {
				$( this ).dialog( "close" );
			}
		}
	]
});

$('#bigClass').change(function(){
	var input = $(this).val();
	$.getJSON('index.php?r=machinetype/getsmallclass', {father_id: input}, function (data) {
		
		if (data.status == 1) {
			$('#smallClass').html(null);
			$('#smallClass').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.data.length;i++) {
				$('#smallClass').append('<option value="'+data.data[i]['id']+'">'+data.data[i]['typename']+'</option>');
			}
		}
		else {
			$('#smallClass').html(null);
			$('#smallClass').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
$('#smallClass').change(function(){
	var input = $(this).val();
	$.getJSON('index.php?r=machinetype/getsmallclass', {father_id: input}, function (data) {
		
		if (data.status == 1) {
			$('#lastClass').html(null);
			$('#lastClass').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.data.length;i++) {
				$('#lastClass').append('<option value="'+data.data[i]['id']+'">'+data.data[i]['typename']+'</option>');
			}
		}
		else {
			$('#lastClass').html(null);
			$('#lastClass').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
$('#lastClass').change(function(){
	var input = $(this).val();
	$('machineoffarm-machinetype_id').val(input);
	$("form").submit();
});
// $('#createbutton').click(function(){
// 	if($('#machineoffarm-acquisitiontime').val() == '') {
// 		alert('请输入购置年限');
// 		$('#machineoffarm-acquisitiontime').focus();
// 	}
// });
// $('#machineoffarm-machinename').focus(function(){
// 	$('#createbutton').removeAttr("disabled");
// });
// $('#machineoffarm-machinename').blur(function(){
// 	var input = $(this).val();
// 	if(input == '')
// 		$('#createbutton').attr('disabled',true);
// });
</script>