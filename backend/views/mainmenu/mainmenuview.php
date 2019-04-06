<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\mainmenu */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>strtolower('mainmenu')])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['mainmenuindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mainmenu-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
    	 <?= Html::a('添加', ['mainmenucreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['mainmenuupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['mainmenudelete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除这项吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>
	<?php $typenamearr = ['主页导航','板块','业务菜单'];?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'menuname',
            'menuurl:url',
            [
            	'attribute' => 'typename',
            	'value' => $typenamearr[$model->typename],
            ],
            'level',
        ],
    ]) ?>

</div>
