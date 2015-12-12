<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'reviewprocess'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['reviewprocessindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="reviewprocess-view">

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
    	 <?= Html::a('添加', ['reviewprocesscreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['reviewprocessupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['reviewprocessdelete', 'id' => $model->id], [
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
            'create_at',
            'update_at',
            'estate',
            'finance',
            'filereview',
            'publicsecurity',
            'leader',
            'mortgage',
            'steeringgroup',
            'estatecontent',
            'financecontent',
            'filereviewcontent',
            'publicsecuritycontent',
            'leadercontent',
            'mortgagecontent',
            'steeringgroupcontent',
            'estatetime:datetime',
            'financetime:datetime',
            'filereviewtime:datetime',
            'publicsecuritytime:datetime',
            'leadertime:datetime',
            'mortgagetime:datetime',
            'steeringgrouptime:datetime',
            'regulations',
            'regulationscontent',
            'regulationstime:datetime',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
