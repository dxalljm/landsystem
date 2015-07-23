<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PlantPrice */

$this->title = 'plant_price' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['plantpriceindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plant-price-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('plantprice_form', [
        'model' => $model,
    ]) ?>

</div>
