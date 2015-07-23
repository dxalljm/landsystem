<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\tables */

$this->title = "ID:".$model->id;
$this->params['breadcrumbs'][] = ['label' => '数据库表管理', 'url' => ['tablesindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tables-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	<?= Html::a('添加', ['tablescreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['tablesupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['tablesdelete', 'id' => $model->id], [
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
            'tablename',
            'Ctablename',
        ],
    ]) ?>

</div>
