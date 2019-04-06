<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\logSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'log';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="log-index">

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?></h3></div>
                        <div class="box-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'user_id',
                'value' => function($model) {
                    return User::find()->where(['id'=>$model->user_id])->one()['realname'];
                }
            ],
//            'user_ip',
//            'action',
            'action_type',
             'object_name',
             'object_id',
             'operate_desc',
            [
                'attribute' => 'operate_time',
                'value' => function($model) {
                    return date('Y-m-d H:i:s',$model->operate_time);
                }
            ],
            'user_ip',
            'macadress',
//             'operate_time',
            // 'object_old_attr:ntext',
            // 'object_new_attr:ntext',

            ['class' => 'frontend\helpers\eActionColumn'],
        ],
    ]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
</div>
