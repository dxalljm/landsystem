<?php
//namespace frontend\controllers;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Draw */

$this->title = 'Create Draw';
$this->params['breadcrumbs'][] = ['label' => 'Draws', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="draw-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                          <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">

                    <?= $this->render('_form', [
                    'model' => $model,
                    ]) ?>

</div>
