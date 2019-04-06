<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Infrastructuretype;
use yii\helpers\Url;
use app\models\Reviewprocess;
use app\models\Projectplan;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\projectapplicationSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'projectapplication';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projectapplication-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                                            </h3>
                </div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//             'id',
            [
            	'attribute' => 'projecttype',
            	'value' => function($model) {
            		return Infrastructuretype::find()->where(['id'=>$model->projecttype])->one()['typename'];
            }
            ],
            [
            	'attribute' => 'content',
            	'value' => function ($model) {
            		return $model->content.$model->projectdata.$model->unit;
            }
            ],
            
			[
				'format' => 'raw',
				'label'=>'审核状态',
				'value' => function ($model) {
					return Reviewprocess::state(Reviewprocess::find()->where(['id'=>$model->reviewprocess_id])->one()['state']);
            }
            ],
            [
            	'label' => '工程情况',
            	'value' => function ($model) {
	            	$plan = Projectplan::find()->where(['project_id'=>$model->id])->one();
	            	if($plan) {
		            	$now = time();
		            	if($now<=$plan['begindate'])
		            		return '未开始';
		            	if($now<=$plan['enddate'] and $now >= $plan['begindate'])
		            		return '施工中';
		            	if($now >= $plan['enddate'])
		            		return '工程结束';  
	            	} else {
	            		return '还没有工程计划'; 
	            	}
            }
            ],
             [
	            'label'=>'操作',
	            'format'=>'raw',
	            //'class' => 'btn btn-primary btn-lg',
	            'value' => function($model,$key){
	            	$plan = Projectplan::find()->where(['project_id'=>$model->id])->one();
	            	if($plan['state']) {
	            		$url = Url::to(['projectplan/projectplanview','id'=>$plan['id']]);
	            		$option = '查看工程计划';
	            		$title = '';
	            	} else {
		            	$url = Url::to(['projectplan/projectplancreate','id'=>$model->id]);
		            	$option = '工程计划';
		            	$title = '';
	            	}
	            	if(Reviewprocess::find()->where(['id'=>$model->reviewprocess_id])->one()['state'] == 7) {
		            	return Html::a($option,$url, [
		            			'id' => 'projectcreate',
		            			'title' => $title,
		            			'class' => 'btn btn-primary btn-xs',
		            	]);
	            	} else {
	            		return Html::a($option,$url, [
		            			'id' => 'projectcreate',
		            			'title' => $title,
		            			'class' => 'btn btn-primary btn-xs',
	            				'disabled'=>'disabled',
		            	]);
	            	}
	            }
            ],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
