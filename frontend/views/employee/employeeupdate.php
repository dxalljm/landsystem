<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = 'employee' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['employeeindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['employeeview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="employee-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('employee_form', [
        'model' => $model,
    ]) ?>

</div>
