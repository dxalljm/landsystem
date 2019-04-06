<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Sales */

$this->title = 'sales' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['salesindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['salesview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="sales-update">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

    <?= $this->render('sales_form', [
        'model' => $model,
    	'volume' => $volume,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
