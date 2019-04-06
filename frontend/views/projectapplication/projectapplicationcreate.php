<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Projectapplication */

$this->title = 'projectapplication' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['projectapplicationindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projectapplication-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                                            </h3>
                </div>
                <div class="box-body">

    <?= $this->render('projectapplication_form', [
        'model' => $model,
    	'farm' => $farm,
    ]) ?>

</div>
