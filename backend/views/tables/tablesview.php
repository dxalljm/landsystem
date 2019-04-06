<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Tables */

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
        <?= Html::a('字段管理','#', [
            			'id' => 'createassign',
            			'title' => '管理数据库表字段',
            			//'class' => 'btn btn-primary btn-lg',
            			'data-toggle' => 'modal',
            			'data-target' => '#activity-modal',
            			//'data-id' => $key,
            			'onclick' => "javascript:window.open('".yii::$app->urlManager->createUrl(['/tablefields/tablefieldsindex','id'=>$model->id])."','','width=1200,height=800,top=50,left=80, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",
            			'class' => 'btn btn-success',
            
            	])?>
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
