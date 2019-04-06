<?php
namespace frontend\controllers;
use app\models\Logs;
use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Log */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'log'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['logindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-view">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?></h3></div>
                    <div class="box-body">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	 <?= Html::a('添加', ['logcreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['logupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['logdelete', 'id' => $model->id], [
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
            [
                'attribute' => 'id',
                'option' => ['width'=>"10%"],
            ],
            [
                'attribute' => 'user_id',
                'value' => User::find()->where(['id'=>$model->user_id])->one()['realname'],
            ],
            'user_ip',
            'action',
            'action_type',
            'object_name',
            'object_id',
            'operate_desc',
            [
                'attribute' => 'operate_time',
                'value' => date('Y-m-d H:i:s',$model->operate_time)
            ],

            [
                'label'=>'访问数据',
                'format' => 'raw',
                'value' => Logs::getData($model),
            ],
//            'object_old_attr:ntext',
//            'object_new_attr:ntext',
        ],
    ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
