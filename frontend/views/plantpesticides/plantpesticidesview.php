<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Farms;
use app\models\Lease;
use app\models\Plant;
use app\models\Pesticides;

/* @var $this yii\web\View */
/* @var $model app\models\Plantpesticides */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'plantpesticides'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['plantpesticidesindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plantpesticides-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

    <p>
    <?= Html::a('添加', ['plantpesticidescreate', 'id' => $model->id,'plant_id'=>$model->plant_id,'lease_id'=>$model->lessee_id,'farms_id'=>$model->farms_id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['plantpesticidesupdate', 'id' => $model->id,'plant_id'=>$model->plant_id,'lease_id'=>$model->lessee_id,'farms_id'=>$model->farms_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['plantpesticidesdelete', 'id' => $model->id], [
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
            	'value' => Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'],
            ],
			[
			'label' => '承租人',
				'attribute' => 'lessee_id',
				'value' => Lease::find()->where(['id'=>$model->lessee_id])->one()['lessee'],
			],
            [
            'label' => '作物名称',
            	'attribute' => 'plant_id',
            	'value' => Plant::find()->where(['id'=>$model->plant_id])->one()['typename'],
            ],
            [
            	'attribute' => 'pesticides_id',
            	'value' => Pesticides::findOne($model->pesticides_id)['pesticidename'],
            ],
            'pconsumption',
           	[
           		'attribute' => 'create_at',
           		'value' => date('Y-m-d H:i:s',$model->create_at),
           	],
           	[
           		'attribute' => 'update_at',
           		'value' => date('Y-m-d H:i:s',$model->update_at),
           	],
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
