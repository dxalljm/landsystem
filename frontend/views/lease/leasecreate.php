<?php
namespace frontend\controllers;

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
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">

    <?= $this->render('lease_form', [
        'model' => $model,
        'overarea' => $overarea,
        'noarea' => $noarea,
        'farm' => $farm,
        'farmer' => $farmer,
    ]) ?>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
