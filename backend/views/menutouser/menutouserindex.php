<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Mainmenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\menutouserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'menu_to_user';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-to-user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['menutousercreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'role_id',
            //'menulist',
            [
            'attribute'=>'menulist',
            'value'=>function($model){
            	$arr = [];
            	$menuarr = explode(',', $model->menulist);
            	foreach($menuarr as $val)
            	{
            		$arr[] = Mainmenu::find()->where(['id'=>$val])->one()['menuname'];
            	}
            	return implode(',', $arr);
            },
            ],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
