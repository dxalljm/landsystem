<?php
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fixed */

$this->title = '新增固定资产';
$this->params['breadcrumbs'][] = ['label' => 'Fixeds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fixed-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                          <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">

                    <?= $this->render('fixed_form', [
                    'model' => $model,
                    ]) ?>

</div>
