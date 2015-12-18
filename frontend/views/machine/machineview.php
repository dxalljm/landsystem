<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Machine;
use app\models\Machinetype;

/* @var $this yii\web\View */
/* @var $model app\models\Machine */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'machine'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['machineindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="machine-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                          <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">

    <p>
    	 <?= Html::a('添加', ['machinecreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['machineupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['machinedelete', 'id' => $model->id], [
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
            	'label'=> '机具大类',
            	'value' => Machinetype::getClass($model->machinetype_id)[0],
            ],
            [
            'label'=> '机具小类',
            'value' => Machinetype::getClass($model->machinetype_id)[1],
            ],
            [
            'label'=> '机具品目',
            'value' => Machinetype::getClass($model->machinetype_id)[2],
            ],
            'productname',
            'implementmodel',
            'filename',
            'province',
            'enterprisename',
            'parameter:ntext',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
