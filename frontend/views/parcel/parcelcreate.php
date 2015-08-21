<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Parcel */

$this->title = 'parcel' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['parcelindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parcel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('parcel_form', [
        'model' => $model,
    ]) ?>

</div>