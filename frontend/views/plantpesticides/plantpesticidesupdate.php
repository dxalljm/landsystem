<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Plantpesticides */

$this->title = 'plantpesticides' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['plantpesticidesindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['plantpesticidesview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="plantpesticides-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('plantpesticides_form', [
        'model' => $model,
    ]) ?>

</div>
