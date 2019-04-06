<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Cooperativetype;

/* @var $this yii\web\View */
/* @var $model app\models\Cooperative */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'cooperative'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['cooperativeindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cooperative-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

    <p>
    	 <?= Html::a('添加', ['cooperativecreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['cooperativeupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['cooperativedelete', 'id' => $model->id], [
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
            'cooperativename',
            //'cooperativetype:html',
            [
            	'label'=>'合作社类型',
            	'value'=>Cooperativetype::find()->where(['id'=>$model->cooperativetype])->one()['typename'],
            ],
            'directorname',
            'peoples',
            'finance',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
