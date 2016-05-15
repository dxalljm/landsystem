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
		<td align='right'>购置年限</td>
		<td colspan="2"><?= $form->field($model, 'acquisitiontime')->textInput()->label(false)->error(false)->widget(
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
            // 'enterprisename',
            // 'parameter:ntext',

            [
            
            'format'=>'raw',
            
            'value' => function($model,$key){
            	return Html::submitButton('添加此机具', ['class' => 'btn btn-primary btn-xs','id'=>'createbutton','onclick'=>'setMachineid('.$model->id.')']);
//             	return Html::submitButton($option,$url, [
// //             			'id' => 'AddMachineoffarmButton',
//             			'class' => 'btn btn-primary btn-xs machineoffarm',
//             	]);
            }
            ],
        ],
    ]); ?>


    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
function setState(state){
	$('#state').val(state);
}
function setMachineid(id){
	$('#state').val('create');
	$('#machineoffarm-machine_id').val(id);
}
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