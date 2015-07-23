<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\BankAccount */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'bank_account'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['bankaccountindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bank-account-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	 <?= Html::a('添加', ['bankaccountcreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['bankaccountupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['bankaccountdelete', 'id' => $model->id], [
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
            'accountnumber',
            'farmer_id',
            'bank',
        ],
    ]) ?>

</div>
