<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Goodseedinfo */

$this->title = 'Update Goodseedinfo: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Goodseedinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="goodseedinfo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
