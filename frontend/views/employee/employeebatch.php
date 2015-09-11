<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Employee */

$this->title = 'employee' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['employeefathers','farms_id'=>$_GET['farms_id']]];

?>
<div class="employee-batch">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">
    <?= $this->render('batch_form', [
        'model' => $model,
    	'employees' => $employees,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
