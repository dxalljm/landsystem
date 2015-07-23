<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\farms */

$this->title = 'farms' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['farmsindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['farmsview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="farms-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('farms_form', [
        'model' => $model,
    ]) ?>

</div>
