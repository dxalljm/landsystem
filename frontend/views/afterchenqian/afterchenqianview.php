<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Farms;
use frontend\helpers\MoneyFormat;

/* @var $this yii\web\View */
/* @var $model app\models\Afterchenqian */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'afterchenqian'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['afterchenqianindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="afterchenqian-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

    <p>
    	 <?= Html::a('添加', ['afterchenqiancreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['afterchenqianupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['afterchenqiandelete', 'id' => $model->id], [
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
//             'management_area',
            [
            	'attribute' => 'farms_id',
            	'label' => '农场名称',
            	'value' => Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'],
            ],
            [
	            'attribute' => 'year',
	            'value' => $model->year.'年',
            ],
            [
	            'attribute' => 'money',
	            'value' => MoneyFormat::num_format($model->money).'元',
            ],
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
