<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\tablefields */

$this->title = 'ID:'.$model->id;
$this->params['breadcrumbs'][] = ['label' => '表项管理', 'url' => ['tablefieldsindex','id'=>$model->tables_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tablefields-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	<?= Html::a('添加', ['tablefieldscreate', 'id' => $model->id,'tables_id'=>$model->tables_id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['tablefieldsupdate', 'id' => $model->id,'tables_id'=>$model->tables_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['tablefieldsdelete', 'id' => $model->id,'tables_id'=>$model->tables_id], [
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
            'tables_id',
            'fields',
            'type',
            'cfields',
        ],
    ]) ?>

</div>
