<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Inputproduct */

$this->title = 'inputproduct' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['inputproductindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['inputproductview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="inputproduct-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('inputproductupdate_form', [
        'model' => $model,
    ]) ?>

</div>
