<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Auditprocess */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'auditprocess'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['auditprocessindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auditprocess-view">

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
    	 <?= Html::a('添加', ['auditprocesscreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['auditprocessupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['auditprocessdelete', 'id' => $model->id], [
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
            'projectname',
            'process',
            'actionname',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
