<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Plant */

$this->title = 'plant' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['plantindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plant-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('plant_form', [
        'model' => $model,
    ]) ?>

</div>
