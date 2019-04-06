<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Insurancecompany */

$this->title = 'Insurancecompany' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['insurancecompanyindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['insurancecompanyview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="insurancecompany-update">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

    <?= $this->render('insurancecompany_form', [
        'model' => $model,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
