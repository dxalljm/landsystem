<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Collection */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'collection'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['collectionindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="collection-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	 <?= Html::a('添加', ['collectioncreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['collectionupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['collectiondelete', 'id' => $model->id], [
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
            'payyear',
            'farms_id',
            'billingtime',
            'amounts_receivable',
            'real_income_amount',
            'ypayyear',
            'ypayarea',
            'ypaymoney',
            'owe',
            'isupdate',
        ],
    ]) ?>

</div>
