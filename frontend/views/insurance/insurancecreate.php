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
/* @var $model app\models\Insurance */

$this->title = 'Insurance' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['insuranceindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">申请表单</h3>
                </div>
                <div class="box-body">

    <?= $this->render('insurance_form', [
        'model' => $model,
<<<<<<< HEAD
        'farms_id' => $farms_id,
        'farm' => $farm,
        'plantArea' => $plantArea,
        'insuredarea' => $insuredarea,
        'people' => $people,
        'isShowAll' => $isShowAll,
=======
    	'farms_id' => $farms_id,
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
    ]) ?>

</div>
