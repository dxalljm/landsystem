<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\farmer */

?>
<div class="farmer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('farmer_form', [
        'model' => $model,
    	'farm' => $farm,
    	'lease' => $lease,
    	//'farmer' =>　$farmer,
    ]) ?>

</div>
