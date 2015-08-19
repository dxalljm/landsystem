<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Parcel */

$this->title = 'parcel' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['parcelindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['parcelview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="parcel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('parcel_form', [
        'model' => $model,
    ]) ?>

</div>
