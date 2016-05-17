<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Insurance */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'Insurance'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['insuranceindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurance-view">

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
    	 <?= Html::a('添加', ['insurancecreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['insuranceupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['insurancedelete', 'id' => $model->id], [
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
            'management_area',
            'year',
            'farms_id',
            'policyholder',
            'cardid',
            'telephone',
            'wheat',
            'soybean',
            'insuredarea',
            'insuredwheat',
            'insuredsoybean',
            'company_id',
            'create_at',
            'update_at',
            'policyholdertime',
            'managemanttime',
            'halltime',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
