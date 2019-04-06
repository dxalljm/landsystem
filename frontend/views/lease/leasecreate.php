<?php
namespace frontend\controllers;use app\models\User;

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Lease */


?>
<div class="lease-create">

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
        'overarea' => $overarea,
        'noarea' => $noarea,
        'farm' => $farm,
        'farmer' => $farmer,
        'isinsurance' => $isinsurance,
        'year' => $year,
    ]) ?>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
