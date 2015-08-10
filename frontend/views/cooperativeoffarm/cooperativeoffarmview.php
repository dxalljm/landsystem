<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CooperativeOfFarm */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'cooperative_of_farm'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['cooperativeoffarmindex','farms_id'=>$_GET['farms_id']]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cooperative-of-farm-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	 <?= Html::a('添加', ['cooperativeoffarmcreate', 'id' => $model->id,'farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['cooperativeoffarmupdate', 'id' => $model->id,'farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['cooperativeoffarmdelete', 'id' => $model->id], [
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
            'cia',
            'proportion',
            'bonus',
            'cooperative_id',
        ],
    ]) ?>

</div>
