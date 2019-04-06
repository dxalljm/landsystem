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
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['insuranceindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['insuranceview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="insurance-update">

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
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
