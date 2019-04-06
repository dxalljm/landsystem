<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Insurancetype */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'Insurancetype'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => 'Insurancetypes', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="insurancetype-view">

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
        <?= Html::a('更新', ['insurancetypeupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['insurancetypedelete', 'id' => $model->id], [
        'class' => 'btn btn-danger',
        'data' => [
        'confirm' => 'Are you sure you want to delete this item?',
        'method' => 'post',
        ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'plant_id',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
