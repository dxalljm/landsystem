<?php

use yii\helpers\Html;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\user */

$this->title = '为 ' . ' ' . User::find()->where(['id'=>$user_id])->one()['username'].' '.'分配角色';
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user_id, 'url' => ['view', 'id' => $user_id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>
    
    <?= $this->render('_assignform', [
    	'user_id' => $user_id,
    	'model' => $model,
    ]) ?>
</div>
