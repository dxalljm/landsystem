<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

?>
<div class="huinong-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">输入法人姓名或农场名称中文或首拼字母</h3>
                </div>
                <div class="box-body">

    <?= $this->render('huinong_search', [
        'model' => $model,
    ]) ?>

</div>
