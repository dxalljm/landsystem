<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Machineoffarm */

$this->title = 'machineoffarm' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['machineoffarmindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="machineoffarm-create">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                          <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">

    <?= $this->render('machineoffarm_form', [
       		'model' => $model,
    		'lastclass' => $lastclass,
    		'smallclass' => $smallclass,
    		'bigclass' => $bigclass,
    		'dataProvider' => $dataProvider,
    		'searchModel' => $searchModel,
    		'farms_id' => $farms_id,
    ]) ?>

</div>
