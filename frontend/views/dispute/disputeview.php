<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Farms;
use app\models\Disputetype;

/* @var $this yii\web\View */
/* @var $model app\models\Dispute */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'dispute'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['disputeindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dispute-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">

    <p>
    	 <?= Html::a('添加', ['disputecreate', 'id' => $model->id,'farms_id'=>$model->farms_id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['disputeupdate', 'id' => $model->id,'farms_id'=>$model->farms_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['disputedelete', 'id' => $model->id], [
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
            	'value' => Farms::findOne($model->farms_id)['farmname'],
            ],
            [
            	'attribute' => 'disputetype_id',
            	'value' => Disputetype::findOne($model->disputetype_id)['typename'],
            ],
            
            'content:ntext',
            [
            	'attribute' => 'create_at',
            	'value' => date('Y-m-d H:m:s',$model->create_at),
            ],
            
            [
            	'attribute' => 'update_at',
            	'value' => date('Y-m-d H:m:s',$model->update_at),
            ],
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
