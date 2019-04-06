<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Collection */

$this->title = 'collection' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['collectionindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['collectionview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="collection-update">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        缴费业务(<?= Theyear::findOne(1)['years']?>年度)
                    </h3>
                </div>
                <div class="box-body">

    <?= $this->render('collection_form', [
        'model' => $model,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
