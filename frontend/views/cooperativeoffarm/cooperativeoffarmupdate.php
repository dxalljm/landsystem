<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CooperativeOfFarm */

$this->title = 'cooperative_of_farm' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['cooperativeoffarmindex','farms_id'=>$_GET['farms_id']]];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['cooperativeoffarmview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="cooperative-of-farm-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('cooperativeoffarm_form', [
        'model' => $model,
    ]) ?>

</div>
