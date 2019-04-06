
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
            [
            //'label'=>'更多操作',
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	// $url = ['/user/userassign','id'=>$model->id];
            	return Html::a('字段管理','#', [
            			'id' => 'createassign',
            			'title' => '管理数据库表字段',
            			//'class' => 'btn btn-primary btn-lg',
            			'data-toggle' => 'modal',
            			'data-target' => '#activity-modal',
            			//'data-id' => $key,
            			'onclick' => "javascript:window.open('".yii::$app->urlManager->createUrl(['/tablefields/tablefieldsindex','id'=>$model->id])."','','width=1200,height=800,top=50,left=80, location=no, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",
            			//'data-pjax' => '0',
            
            	]);
            }
            ]
        ],
    ]); ?>

</div>
