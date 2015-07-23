<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\tablefields */

$this->title = '添加表项目';
$this->params['breadcrumbs'][] = ['label' => '表项管理', 'url' => ['tablefieldsindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tablefields-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
