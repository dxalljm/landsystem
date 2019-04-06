<?php
use app\models\User;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Sales */

$this->title = 'sales' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['salesindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sales-create">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getLastYear()?>年度)</font></h3></div>
                <div class="box-body">

    <?= $this->render('sales_form', [
        'model' => $model,
    	'volume' => $volume,
        'plant' => $plant,
        'goodseed' => $goodseed,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
