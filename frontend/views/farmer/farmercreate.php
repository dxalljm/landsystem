<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\farmer */

?>
<div class="farmer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('createandview_form', [
        'model' => $model,
    	'farm' => $farm,
        'farmembers' => $farmembers,
    ]) ?>

</div>
