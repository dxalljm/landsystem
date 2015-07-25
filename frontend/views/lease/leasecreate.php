<?php
namespace frontend\controllers;

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Lease */


?>
<div class="lease-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('lease_form', [
        'model' => $model,
    	'areas' => $areas,
    	'farm' => $farm,
    	'farmer' => $farmer
    ]) ?>

</div>
