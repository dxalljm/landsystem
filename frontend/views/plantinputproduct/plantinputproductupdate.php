<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Plantinputproduct */

$this->title = 'plantinputproduct' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['plantinputproductindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['plantinputproductview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="plantinputproduct-update">

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

    <?= $this->render('plantinputproduct_form', [
        'model' => $model,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
