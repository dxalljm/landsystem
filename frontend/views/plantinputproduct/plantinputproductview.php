<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Plantinputproduct */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'plantinputproduct'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['plantinputproductindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plantinputproduct-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	 <?= Html::a('添加', ['plantinputproductcreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['plantinputproductupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['plantinputproductdelete', 'id' => $model->id], [
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
            'farms_id',
            'lessee_id',
            'father_id',
            'son_id',
            'inputproduct_id',
            'pconsumption',
            'zongdi',
            'plant_id',
        ],
    ]) ?>

</div>
