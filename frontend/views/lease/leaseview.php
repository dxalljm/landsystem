<?php
namespace frontend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Lease */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'lease'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['leaseindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="lease-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	 <?= Html::a('添加', ['leasecreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['leaseupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['leasedelete', 'id' => $model->id], [
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
            'lease_area',
            'lessee',
            'plant_id',
            'farms_id',
        ],
    ]) ?>

</div>
