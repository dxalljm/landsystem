<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Parcel */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'parcel'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['parcelindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parcel-view">
    
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">


    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'serialnumber',
            'temporarynumber',
            'unifiedserialnumber',
            'powei',
            'poxiang',
            'podu',
            'agrotype',
            'stonecontent',
            'grossarea',
            'piecemealarea',
            'netarea',
            'figurenumber',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
