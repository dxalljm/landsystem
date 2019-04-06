<?php
namespace backend\controllers;
use app\models\Mainmenu;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ManagementArea;

/* @var $this yii\web\View */
/* @var $model app\models\Department */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'department'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['departmentindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	 <?= Html::a('添加', ['departmentcreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['departmentupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['departmentdelete', 'id' => $model->id], [
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
            'departmentname',
            [
            	'attribute' => 'membership',
            	'value' => ManagementArea::getAreaname($model->membership),
            ],
            'leader',
            'sectionchief',
            'chippackage',
            [
                'attribute' => 'menulist',
//                'value' => Mainmenu::find()->where(['id' =>$model->controllername])->one()['menuname'],
            ],
            'businessmenu',
            'searchmenu',
        ],
    ]) ?>

</div>
