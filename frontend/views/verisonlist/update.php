<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Verisonlist */

$this->title = 'Update Verisonlist: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Verisonlists', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="verisonlist-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
