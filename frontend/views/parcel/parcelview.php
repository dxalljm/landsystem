<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Parcel */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'parcel'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['parcelindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parcel-view">

<section class="content-header">
    <h1>
        <?= Html::encode($this->title) ?>
        <small></small>
    </h1>
</section>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
    <p>
    	 <?= Html::a('添加', ['parcelcreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['parcelupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['parceldelete', 'id' => $model->id], [
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
            'serialnumber',
            'temporarynumber',
            'unifiedserialnumber',
            'powei',
            'poxiang',
            'podu',
            'agrotype',
            'stonecontent',
            'grossarea',
            'piecemealarea',
            'netarea',
            'figurenumber',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
