<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Tables;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\tablefields */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="tablefields-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php if(isset($_GET['tables_id'])) {?>
    <?= $form->field($model, 'tables_id')->hiddenInput(['value'=>$_GET['tables_id']])->label(false);?>
    <?php } else {?>
    <?= $form->field($model, 'tables_id')->hiddenInput()->label(false);?>
    <?php }?>
    <?= $form->field($model, 'fields')->textInput(['maxlength' => 100]) ?>

   <?= $form->field($model, 'type')->dropDownList(['int(11)' => '数值型','varchar(500) null'=>'字符型','text'=>'文本型','FLOAT(10,2)'=>'浮点型']) ?>

    <?= $form->field($model, 'cfields')->textInput(['maxlength' => 100]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'tablefields-modal',
	'size'=>'modal-sm',
	'header' => '<h4 class="modal-title">请选择一个数据库表</h4>',
	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',

]); 
echo '<div id="modalContent">';
?>
<?php 
$tables = Tables::find()->all();
//print_r($tables);
foreach($tables as $val)
{	
	echo '&nbsp; &nbsp;'.$val['id'].':&nbsp; &nbsp; ';
	echo Html::a($val['Ctablename'],'#',['onclick'=>'setField('.$val['id'].')']);
	echo '<br>';
}
echo '</div>';

?>

<?php \yii\bootstrap\Modal::end(); ?>
</div>