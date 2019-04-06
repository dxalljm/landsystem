<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Theyear */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'theyear'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['theyearindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="theyear-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                   <?= Html::encode($this->title) ?>
                    </h3>
                </div>
    <div class="box-body">

    <p>
    	 <?= Html::a('添加', ['theyearcreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['theyearupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['theyeardelete', 'id' => $model->id], [
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
            'years',
        ],
    ]) ?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>
