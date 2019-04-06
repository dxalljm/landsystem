<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\tablefields */

$this->title = '添加表项目';
?>
<div class="tablefields-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
