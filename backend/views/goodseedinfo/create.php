<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Goodseedinfo */

$this->title = 'Create Goodseedinfo';
$this->params['breadcrumbs'][] = ['label' => 'Goodseedinfos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goodseedinfo-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
