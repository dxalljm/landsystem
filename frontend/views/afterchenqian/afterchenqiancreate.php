<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Afterchenqian */

$this->title = 'afterchenqian' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['afterchenqianindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="afterchenqian-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?><font color="red">（<?= User::getYear()?>年度）</font>
                    </h3>
                </div>
                <div class="box-body">

    <?= $this->render('afterchenqian_form', [
        'model' => $model,
    ]) ?>

</div>
