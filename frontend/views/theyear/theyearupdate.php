<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Theyear */

?>
<div class="theyear-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('theyear_form', [
        'model' => $model,
    ]) ?>

</div>
