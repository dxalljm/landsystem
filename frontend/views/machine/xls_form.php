<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;


/* @var $this yii\web\View */
/* @var $model app\models\Parcel */


?>
<div class="parcelxls-form">

<?php $form = ActiveFormrdiv::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'file')->fileInput()->label('请选择XLS文件') ?>

<button>提交</button>

<?php ActiveFormrdiv::end() ?>

</div>
