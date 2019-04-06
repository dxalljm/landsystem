<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\mainmenuSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Mainmenu ' ;
$this->title = Tables::find()->where(['tablename'=>strtolower($this->title)])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mainmenu-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['mainmenucreate'], ['class' => 'btn btn-success']) ?>
    </p>
	<?php ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sort',
            'menuname',
            'menuurl:url',
			[
				'attribute' => 'typename',
				'value' => function($model) {
					$typenamearr = ['主页导航','板块','业务菜单'];
					return $typenamearr[$model->typename];
				}
			],
            ['class' => 'backend\helpers\eActionColumn'],
        ],
    ]); ?>

</div>
