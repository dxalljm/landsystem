<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Mainmenu;

/* @var $this yii\web\View */
/* @var $model app\models\MenuToUser */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'menu_to_user'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['menutouserindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-to-user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	 <?= Html::a('添加', ['menutousercreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['menutouserupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['menutouserdelete', 'id' => $model->id], [
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
            'role_id',
            [
            	'attribute' => 'menulist',
            ],
            
            'plate',
            'businessmenu',
            'searchmenu',
            'auditinguser',
        ],
    ]) ?>

</div>
