<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CooperativeOfFarm */

$this->title = 'cooperative_of_farm' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['cooperativeoffarmindex','farms_id'=>$_GET['farms_id']]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['cooperativeoffarmview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="cooperative-of-farm-update">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

    <?= $this->render('cooperativeoffarm_form', [
        'model' => $model,
    ]) ?>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
