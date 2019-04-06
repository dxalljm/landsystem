<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\farmer */

$this->title = 'farmer' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['farmerindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['farmerview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="farmer-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('farmer_form', [
        'model' => $model,
    ]) ?>

</div>
