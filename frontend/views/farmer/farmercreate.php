<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\farmer */

?>
<div class="farmer-create">

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

    <?= $this->render('createandview_form', [
        'model' => $model,
    	'membermodel' => $membermodel,
    	'farm' => $farm,
    ]) ?>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
