<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Insurancetype */

$this->title = '新增保险种类';
$this->params['breadcrumbs'][] = ['label' => 'Insurancetypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurancetype-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                          <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">

                    <?= $this->render('insurancetype_form', [
                    'model' => $model,
                    ]) ?>

</div>
