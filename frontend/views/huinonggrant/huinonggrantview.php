<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Huinonggrant */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'huinonggrant'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['huinonggrantindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="huinonggrant-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

    <p>
    	 <?= Html::a('添加', ['huinonggrantcreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['huinonggrantupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['huinonggrantdelete', 'id' => $model->id], [
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
            'huinong_id',
            'money',
            'area',
            'state',
            'note:ntext',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
