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
    	'assign' => $assign,
    ]) ?>
    
    <?= Html::a('添加', ['assigncreate', 'id' => $user_id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['assignupdate', 'id' => $user_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['assigndelete', 'id' => $user_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>

</div>
