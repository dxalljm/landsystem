<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Projectplan */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'projectplan'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['projectplanindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projectplan-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'project_id',
            'begindate',
            'enddate',
            'content:ntext',
            'create_at',
            'update_at',
            'state',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
