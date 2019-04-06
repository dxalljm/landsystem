<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\AuthItem */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '角色管理', 'url' => ['roleindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	<?= Html::a('添加', ['rolecreate', 'id' => $model->name], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['roleupdate', 'id' => $model->name], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['roledelete', 'id' => $model->name], [
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
            'name',
            'type',
            'description:ntext',
            'rule_name',
            'data:ntext',
            [
			 	'attribute'=>'created_at',
			 	'value'=>date('Y-m-d H:i:s',$model->created_at),
			],
			[
				'attribute'=>'updated_at',
				'value'=>date('Y-m-d H:i:s',$model->updated_at),
			],
			[
				'label' => '所属权限',
				'value'=>$childstr,
			]
        ],
    ]) ?>

</div>
