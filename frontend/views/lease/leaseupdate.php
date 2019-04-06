<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Lease */

$this->title = 'lease' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['leaseindex','farms_id'=>$_GET['farms_id']]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['leaseview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="lease-update">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

    <?= $this->render('lease_form', [
        'model' => $model,
        'bankModel' => $bankModel,
    	'farm' => $farm,
    	'farmer' => $farmer,
    	'overarea' => $overarea,
        'noarea' => $noarea,
        'isinsurance' => $isinsurance,
        'year' => $year,
    ]) ?>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
