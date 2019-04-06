<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
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
                <div class="box-body">

    <?= $this->render('createandview_form', [
        'model' => $model,
        'farmerinfoModel' => $farmerinfoModel,
    	'membermodel' => $membermodel,
    	'farmModel' => $farmModel,
    ]) ?>
	                </div>
            </div>
        </div>
    </div>
</section>
</div>
