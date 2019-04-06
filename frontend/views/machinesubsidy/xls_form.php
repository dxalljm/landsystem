<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;


/* @var $this yii\web\View */
/* @var $model app\models\Parcel */


?>
<div class="parcelxls-form">

<?php $form = ActiveFormrdiv::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

<?= $form->field($model, 'file')->fileInput()->label('请选择XLS文件') ?>

<button>提交</button>

<?php ActiveFormrdiv::end() ?>

</div>
