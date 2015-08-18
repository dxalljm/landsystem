<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = 'employee' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['employeeindex']];

?>
<div class="employee-create">

    <?= $this->render('batch_form', [
        'model' => $model,
    	'employees' => $employees,
    ]) ?>

</div>
