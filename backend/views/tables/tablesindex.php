
<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\tablesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '数据库表管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tables-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建数据库表', ['tablescreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'tablename',
            'Ctablename',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
