<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Huinonggrant */

$this->title = 'huinonggrant' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['huinonggrantindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['huinonggrantview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="huinonggrant-update">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                          <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">

    <?= $this->render('huinonggrant_form', [
        'model' => $model,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
