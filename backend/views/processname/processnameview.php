<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Processname */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'processname'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['processnameindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="processname-view">

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
    	 <?= Html::a('添加', ['processnamecreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['processnameupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['processnamedelete', 'id' => $model->id], [
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
            'processdepartment',
            'Identification',
            'department_id',
            'level_id',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
