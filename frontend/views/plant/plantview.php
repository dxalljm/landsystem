<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Plant;
/* @var $this yii\web\View */
/* @var $model app\models\Plant */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'plant'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['plantindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plant-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

    <p>
    	 <?= Html::a('添加', ['plantcreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['plantupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['plantdelete', 'id' => $model->id], [
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
            'typename',
             [
            	'attribute'=>'father_id',
            	'value' => Plant::find()->where(['id'=>$model->father_id])->one()['typename'],
			],
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
