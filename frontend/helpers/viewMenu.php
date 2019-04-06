<?php

namespace frontend\helpers;

use Yii;
use Closure;
use yii\helpers\Html;
use yii\helpers\Url;

class viewMenu
{	
	public function displayMenu()
	{
		echo Html::a('添加', [yii::$app->controller->id.'create', 'id' => $model->id], ['class' => 'btn btn-success']);
		echo Html::a('更新', [yii::$app->controller->id.'pdate', 'id' => $model->id], ['class' => 'btn btn-primary']);
		echo Html::a('删除', [yii::$app->controller->id.'elete', 'id' => $model->id], [
		            'class' => 'btn btn-danger',
		            'data' => [
		                'confirm' => '您确定要删除这项吗？',
		                'method' => 'post',
		            ],
		        ]);
	}
}