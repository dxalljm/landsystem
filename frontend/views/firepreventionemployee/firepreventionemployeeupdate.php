<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Firepreventionemployee */

$this->title = 'fireprevention_employee' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['firepreventionemployeeindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['firepreventionemployeeview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="firepreventionemployee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('firepreventionemployee_form', [
        'model' => $model,
    ]) ?>

</div>
