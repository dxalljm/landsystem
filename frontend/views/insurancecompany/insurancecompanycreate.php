<?php
<<<<<<< HEAD
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
=======
namespace backend\controllers;
use app\models\tables;
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Insurancecompany */

$this->title = 'Insurancecompany' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['insurancecompanyindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurancecompany-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
<<<<<<< HEAD
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
=======
                    <h3 class="box-title">
                          <?= $this->title ?>                    </h3>
                </div>
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
                <div class="box-body">

    <?= $this->render('insurancecompany_form', [
        'model' => $model,
    ]) ?>

</div>
