<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Pesticides */

$this->title = 'pesticides' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['pesticidesindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['pesticidesview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="pesticides-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('pesticides_form', [
        'model' => $model,
    ]) ?>

</div>
