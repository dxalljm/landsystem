<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Logicalpoint */

$this->title = 'logicalpoint' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['logicalpointindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['logicalpointview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="logicalpoint-update">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                          <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">

    <?= $this->render('logicalpoint_form', [
        'model' => $model,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
