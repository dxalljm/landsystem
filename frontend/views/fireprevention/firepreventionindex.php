<?php
namespace frontend\controllers;
use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\firepreventionSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'fireprevention';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fireprevention-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['firepreventioncreate','farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'farms_id',
            'firecontract',
            'safecontract',
            'environmental_agreement',
            // 'firetools',
            // 'mechanical_fire_cover',
            // 'chimney_fire_cover',
            // 'isolation_belt',
            // 'propagandist',
            // 'fire_administrator',
            // 'cooker',
            // 'fieldpermit',
            // 'propaganda_firecontract',
            // 'leaflets',
            // 'employee_firecontract',
            // 'rectification_record',
            // 'equipmentpic',
            // 'peoplepic',
            // 'facilitiespic',

            ['class' => 'frontend\helpers\eActionColumn'],
        ],
    ]); ?>

</div>
