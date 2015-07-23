<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Plant */

$this->title = 'plant' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['plantindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['plantview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="plant-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('plant_form', [
        'model' => $model,
    ]) ?>

</div>
