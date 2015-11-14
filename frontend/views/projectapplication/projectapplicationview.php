<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Projectapplication */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'projectapplication'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['projectapplicationindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projectapplication-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                                            </h3>
                </div>
                <div class="box-body">

    <p>
    	 <?= Html::a('添加', ['projectapplicationcreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['projectapplicationupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['projectapplicationdelete', 'id' => $model->id], [
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
            'projecttype',
            'create_at',
            'update_at',
            'is_agree',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
