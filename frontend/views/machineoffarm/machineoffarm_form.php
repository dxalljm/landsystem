<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Machineoffarm;
use app\models\Machinetype;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\Machineoffarm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="machineoffarm-form">

    <?php $form = ActiveFormrdiv::begin(); ?>

<table class="table table-bordered table-hover">
	<tr>
		<td width=15% align='right'>机具大类</td>
		<td align='left' width=20%><?= html::dropDownList('bigclass',$bigclass,ArrayHelper::map(Machinetype::find()->where(['father_id'=>1])->all(), 'id', 'typename'),['class'=>'form-control','id'=>'bigClass','prompt'=>'请选择...']) ?></td>
		<td align='right'>机具小类</td>
		<td align='left'><?= html::dropDownList('smallclass',$smallclass,ArrayHelper::map(Machinetype::find()->where(['father_id'=>$bigclass])->all(),'id', 'typename'),['class'=>'form-control','id'=>'smallClass'])?></td>
		<td align='right'>机具品目</td>
		<td align='left'><?= html::dropDownList('lastclass',$lastclass,ArrayHelper::map(Machinetype::find()->where(['father_id'=>$smallclass])->all(),'id', 'typename'),['class'=>'form-control','id'=>'lastClass'])?></td>
	</tr>
	<?= $form->field($model, 'machinetype_id')->hiddenInput()->label(false)->error(false)?>
	<tr>
		<td align='right'>机具名称</td>
		<td colspan="5"><?= $form->field($model, 'machinename')->textInput()->label(false)->error(false)?></td>
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
            	$url = ['/machineoffarm/machineoffarmcreate','farms_id'=>$model->id,'machine_id'=>$model->id];    	
            	$option = '添加此机具';
            	return Html::a($option,$url, [
            			'id' => 'machineoffarm',
            			'class' => 'btn btn-primary btn-xs',
            	]);
            }
            ],
        ],
    ]); ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
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
</script>