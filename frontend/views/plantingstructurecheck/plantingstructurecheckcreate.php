<?php
namespace frontend\controllers;use app\models\User;
use app\models\Plantingstructure;
use app\models\Tables;
use app\models\Plantinputproduct;
use app\models\Plantpesticides;


/* @var $this yii\web\View */
/* @var $model app\models\Plantingstructure */

$this->title = '种植结构复核' ;
?>
<div class="plantingstructure-create">


        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 >核查信息<font color="red">(<?= User::getYear()?>年度)</font></h3>
                </div>
                <div class="box-body">

    <?= $this->render('plantingstructurecheck_form', [
        			'plantinputproductModel' => $plantinputproductModel,
    				'plantpesticidesModel' => $plantpesticidesModel,
    				'model' => $model,
    				'farm' => $farm,
    				'noarea' => $noarea,
					'overarea' => $overarea,
    ]) ?>
                </div>
            </div>
        </div>

</div>
