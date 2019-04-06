<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Verisonlist */

$this->title = 'Create Verisonlist';
$this->params['breadcrumbs'][] = ['label' => 'Verisonlists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verisonlist-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
