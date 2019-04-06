<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Goodseedinfocheck */

$this->title = 'Update Goodseedinfocheck: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Goodseedinfochecks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="goodseedinfocheck-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
