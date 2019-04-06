<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\user */

?>
<div class="user-update">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?></h3></div>
                <div class="box-body">

    <?= $this->render('_userinfoform', [
        'model' => $model,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
