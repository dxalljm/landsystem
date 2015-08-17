<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Firepreventionemployee */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'firepreventionemployee'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['firepreventionemployeeindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firepreventionemployee-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	 <?= Html::a('添加', ['firepreventionemployeecreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['firepreventionemployeeupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['firepreventionemployeedelete', 'id' => $model->id], [
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
            'employee_id',
            'is_smoking',
            'is_retarded',
            'create_at',
            'update_at',
        ],
    ]) ?>

</div>
