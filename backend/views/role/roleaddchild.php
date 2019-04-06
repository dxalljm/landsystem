<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */

$this->title = '创建权限';
$this->params['breadcrumbs'][] = ['label' => '权限管理', 'url' => ['roleindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_childform', [
        'model' => $model,
    	'allController' => $allController,
    ]) ?>

</div>
