<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\ItemChild;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\authItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '角色管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('创建角色', ['rolecreate'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            //'type',
            //'description:ntext',
            //'rule_name',
            //'data:ntext',
            // 'created_at',
             [
			 	'label'=>'所属权限',
			 	'value'=>function($model){
			 		$arr = [];
			 		$child = ItemChild::find()->where(['parent'=>$model->name])->all();
				 	foreach($child as $key => $val)
				 	{
				 		$arr[] = $val->child;
				 	}
				 	$childstr = implode(",", $arr);
				 	return strlen($childstr)<=80 ? $childstr : (substr($childstr,0,80).chr(0)."......");;
                },
			],

            ['class' => 'backend\helpers\eActionColumn','header' => '操作',],
            [
                'label'=>'更多操作',
                'format'=>'raw',
                'value' => function($model){
                    $url = ['/role/roleaddchild','id'=>$model->name];
                    return Html::a('分配权限', $url, ['title' => '为角色添加权限']); 
                }
            ]        
        ],
    ]); ?>

</div>
