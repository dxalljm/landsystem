<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Plantingstructure;
use app\models\Plant;

/* @var $this yii\web\View */
/* @var $model app\models\Sales */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'sales'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['salesindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-view">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

    <p>
        <?= Html::a('更新', ['salesupdate', 'id' => $model->id,'planting_id'=>$_GET['planting_id'],'farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['salesdelete', 'id' => $model->id], [
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
            [
            	'label' => '种植作物',
            	'attribute' => 'planting_id',
            	'value' => Plant::find()->where(['id' => Plantingstructure::find()->where(['id'=>$model->planting_id])->one()['plant_id']])->one()['typename'],
            ],
            'whereabouts',
            'volume',
            'price',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
