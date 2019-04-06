<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Parcel;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\parcelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '请选择宗地'
?>
<div class="parcel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <table class="table table-striped table-bordered table-hover table-condensed">
    
    	<tr>
    		<td align='center'>租赁面积（宗地）</td>
    	</tr>
    	<tr>
    		<td align='center'><?= Html::textInput('parcellist','',['id'=>'parcellist'])?></td>

    	</tr>
    	<tr>
    		<td align='center'><?php foreach($zdarea as $value) {echo html::button($value,['onclick'=>'toParcellist']);};?></td>

    	</tr>
    </table>

</div>
