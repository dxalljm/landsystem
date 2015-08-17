<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Fireprevention */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'fireprevention'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['firepreventionindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fireprevention-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	 <?= Html::a('添加', ['firepreventioncreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['firepreventionupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['firepreventiondelete', 'id' => $model->id], [
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
            'farms_id',
            'firecontract',
            'safecontract',
            'environmental_agreement',
            'firetools',
            'mechanical_fire_cover',
            'chimney_fire_cover',
            'isolation_belt',
            'propagandist',
            'fire_administrator',
            'cooker',
            'fieldpermit',
            'propaganda_firecontract',
            'leaflets',
            'employee_firecontract',
            'rectification_record',
            'equipmentpic',
            'peoplepic',
            'facilitiespic',
        ],
    ]) ?>

</div>
