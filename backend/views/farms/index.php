<?php
namespace backend\controllers;
use app\models\Tables;
use app\models\farmer;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\farmsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Farms';
$this->title = Tables::find()->where(['tablename'=>strtolower($this->title)])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="farms-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'farmname',
            'address',

            ['class' => 'frontend\helpers\eActionColumn'],
        ],
    ]); ?>

</div>
