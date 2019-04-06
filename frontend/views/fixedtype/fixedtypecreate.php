<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fixedtype */

$this->title = '新增类别';
$this->params['breadcrumbs'][] = ['label' => 'Fixedtypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fixedtype-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                          <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">

                    <?= $this->render('fixedtype_form', [
                    'model' => $model,
                    ]) ?>

</div>
