<?php

use yii\helpers\Html;
use frontend\helpers\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\VerisonlistSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Verisonlists';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="verisonlist-index">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
    <?php
    \app\models\User::tableBegin('历史版本');
    ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'ver',
            'update:ntext',
            'update_at'
        ],
    ]); ?>
    <?php
    \app\models\User::tableEnd();
    ?>
            </div>
        </div>
    </section>
</div>
