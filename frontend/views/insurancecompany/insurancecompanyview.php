<?php
<<<<<<< HEAD
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
=======
namespace backend\controllers;
use app\models\tables;
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Insurancecompany */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'Insurancecompany'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['insurancecompanyindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurancecompany-view">

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

    <p>
    	 <?= Html::a('添加', ['insurancecompanycreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['insurancecompanyupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['insurancecompanydelete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除这项吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'companynname',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
