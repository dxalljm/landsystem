<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Plantingstructure */

$this->title = 'plantingstructure' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['plantingstructureindex','farms_id'=>$_GET['farms_id']]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plantingstructure-create">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= $year?>年度)</font></h3></div>
                <div class="box-body">

    <?= $this->render('plantingstructure_form', [
        			'plantinputproductModel' => $plantinputproductModel,
    				'plantpesticidesModel' => $plantpesticidesModel,
    				'model' => $model,
    				'lease' => $lease,
    				'farm' => $farm,
    				'noarea' => $noarea,
					'overarea' => $overarea,
                    'year' => $year,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
