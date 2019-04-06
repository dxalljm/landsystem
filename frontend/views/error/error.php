<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Collection */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="collection-form">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h1 class="text-red">错误!</h1>
                </div>
                <div class="box-body">
                    <h3 class="text-red">
                    <?php
                        echo $msg;
                    ?>
                        </h3>
              </div>
            </div>
        </div>
    </div>
</section>
</div>