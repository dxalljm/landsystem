<?php
namespace frontend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\MachineSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'machinetype';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="machine-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['machinetypecreate'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('XLS导入', ['machinetypexls'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'father_id',
            'typename',
            'is_delete',
            'sort',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
