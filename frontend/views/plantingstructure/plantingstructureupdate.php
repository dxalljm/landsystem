<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Plantingstructure */

$this->title = 'plantingstructure' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['plantingstructureindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['plantingstructureview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="plantingstructure-update">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">

    <?= $this->render('plantingstructureview_form', [
        'model' => $model,
        'farm' => $farm,
        'zongdi' => $zongdi,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
