<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\groupsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'groups';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="groups-index">

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
        <?= Html::a('添加', ['groupscreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'groupname',
            'grouprole',
            'groupmark',

             [
            	'class' => 'backend\helpers\eActionColumn','header' => '操作',
            	
            ],
            [
                'label'=>'更多操作',
                'format'=>'raw',
            	//'class' => 'btn btn-primary btn-lg',
                'value' => function($model,$key){
                    $url = ['/groups/groupsrole'];
                    return Html::a('权限分配',$url, [
                    'id' => 'createassign',
                    'title' => '给用户组分配权限',
                    //'data-pjax' => '0',

                ]);
                }
            ]        
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
