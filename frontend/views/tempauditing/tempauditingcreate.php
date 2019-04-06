<?php
namespace frontend\controllers;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model app\models\Tempauditing */

$this->title = 'tempauditing' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['tempauditingindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tempauditing-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?></h3></div>
                <div class="box-body">

    <?= $this->render('tempauditing_form', [
        'model' => $model,
    ]) ?>
<?= GridView::widget([
        'dataProvider' => $dataProvider,
//         'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//             'id',
//             'user_id',
			[
				'attribute' => 'tempauditing',
				'value' => function ($model) {
					return User::find()->where(['id'=>$model->tempauditing])->one()['realname'];
			}
			],
			[
			'attribute' => 'begindate',
			'value' => function ($model) {
				return date('Y-m-d',$model->begindate);
			}
			],
			[
				'attribute' => 'enddate',
				'value' => function ($model) {
					return date('Y-m-d',$model->enddate);
			}
			],
			[
				'attribute' => 'state',
				'value' => function ($model) {
					return $model->state ? '已授权' : '未授权';
			}
			],
            [
            
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){
            	$url = ['/tempauditing/tempauditingback','id'=>$model->id];
            	$option = '回收授权';
		        return Html::a($option,$url,[
		            'class' => 'btn btn-danger btn-xs',
		            'data' => [
		            	    'confirm' => '您确定要回收授权吗？',
		                'method' => 'post',
		            ],
		        ]);
            }
            ],
        ],
    ]); ?>
</div>
