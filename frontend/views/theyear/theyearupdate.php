<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Theyear */

?>
<div class="theyear-update">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                   <?= Html::encode($this->title) ?>
                    </h3>
                </div>
    <div class="box-body">

    <?= $this->render('theyear_form', [
        'model' => $model,
    ]) ?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>
