<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Tempprintbill */

$this->title = 'tempprintbill' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['tempprintbillindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tempprintbill-create">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

    <?= $this->render('tempprintbill_form', [
        'model' => $model,
    	'nonumber' => $nonumber,
        'collectionModel' => $collectionModel,
    	'farm' => $farm,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
