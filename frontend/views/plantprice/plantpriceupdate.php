<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PlantPrice */

$this->title = 'plant_price' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['plantpriceindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['plantpriceview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="plant-price-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('plantprice_form', [
        'model' => $model,
    ]) ?>

</div>
