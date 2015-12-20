<?php
namespace backend\controllers;

use yii\widgets\ActiveFormrdiv;


/* @var $this yii\web\View */
/* @var $model app\models\Parcel */


?>
<div class="farmsxls-form">

<?php $form = ActiveFormrdiv::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'file')->fileInput()->label('请选择XLS文件') ?>

<button id="btnSubmit">提交</button>
<?php echo $area;?>
<?php ActiveFormrdiv::end() ?>

</div>
