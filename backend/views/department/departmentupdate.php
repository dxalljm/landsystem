<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Department */

$this->title = 'department' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['departmentindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['departmentview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="department-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('department_form', [
        'model' => $model,
    ]) ?>

</div>
