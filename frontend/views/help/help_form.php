<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;

/* @var $this yii\web\View */
/* @var $model app\models\Help */
/* @var $form yii\widgets\ActiveForm */
?>
<link href="/vendor/bower/AdminLTE/plugins/ckeditor/contents.css" rel="stylesheet">
<script src="/vendor/bower/AdminLTE/plugins/ckeditor/ckeditor.js"></script>
<script src="/vendor/bower/AdminLTE/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<div class="help-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
<tr>
    <td width=15% align='right'>标识序号</td>
    <td align='left'><?= $form->field($model, 'number')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
    <td width=15% align='right'>标识</td>
    <td align='left'><?= $form->field($model, 'mark')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
    <td width=15% align='right'>标题</td>
    <td align='left'><?= $form->field($model, 'title')->textInput(['maxlength' => 500])->label(false)->error(false) ?></td>
</tr>
<tr>
    <td width=15% align='right'>内容</td>
    <td align='left'><?= $form->field($model, 'content')->textarea(['rows' => 6])->label(false)->error(false) ?></td>
</tr>

</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
<script>
    $(function () {
        // Replace the <textarea id="editor1"> with a CKEditor
        // instance, using default configuration.
        CKEDITOR.replace('help-content');
        //bootstrap WYSIHTML5 - text editor
        $(".textarea").wysihtml5();
    });
</script>