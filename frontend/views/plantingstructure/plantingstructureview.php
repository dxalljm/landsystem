<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Plant;
use app\models\Goodseed;
use app\models\Inputproduct;
use app\models\Pesticides;
use app\models\Farms;
use app\models\Lease;

/* @var $this yii\web\View */
/* @var $model app\models\Plantingstructure */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'plantingstructure'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['plantingstructureindex','farms_id'=>$model->farms_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plantingstructure-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	
        <?= Html::a('更新', ['plantingstructureupdate', 'id' => $model->id,'lease_id' => $model->lease_id, 'farms_id'=>$model->farms_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['plantingstructuredelete', 'id' => $model->id], [
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
            	'label' => '农场名称',
            	'attribute' => 'farms_id',
            	'value' => Farms::find()->where(['id'=>$model->farms_id])->one()['farmname']
            ],
            [
            	'label' => '承租人',
            	'attribute' => 'lease_id',
            	'value' => Lease::find()->where(['id'=>$model->lease_id])->one()['lessee']
            ],
            'zongdi',
            'area',
            [
            	'attribute'=>'plant_id',
            	'value'=>Plant::find()->where(['id'=>$model->plant_id])->one()['cropname']
            ],
            [
            	'attribute'=>'goodseed_id',
            	'value'=>Goodseed::find()->where(['id'=>$model->goodseed_id])->one()['plant_model']
            ],
            [
            	'attribute'=>'inputproduct_id',
            	'value'=>Inputproduct::find()->where(['id'=>$model->inputproduct_id])->one()['fertilizer']	
            ],
            [
            	'attribute' => 'pesticides_id',
            	'value' => Pesticides::find()->where(['id'=>$model->pesticides_id])->one()['pesticidename']
            ],
            'pconsumption',    
        ],
    ]) ?>

</div>