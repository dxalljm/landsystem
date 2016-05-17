<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Insurance */

$this->title = 'Insurance' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['insuranceindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['insuranceview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="insurance-update">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                          <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">

    <?= $this->render('insurance_form', [
        'model' => $model,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
