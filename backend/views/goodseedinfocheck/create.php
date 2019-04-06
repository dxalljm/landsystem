<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Goodseedinfocheck */

$this->title = 'Create Goodseedinfocheck';
$this->params['breadcrumbs'][] = ['label' => 'Goodseedinfochecks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goodseedinfocheck-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
