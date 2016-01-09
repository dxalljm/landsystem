<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Projectapplication;
use app\models\Inputproduct;
use app\models\Infrastructuretype;

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
            [
            	'label' => '项目内容',
            	'attribute' => 'project_id',
            	'value' => Projectapplication::find()->where(['id'=>$model->project_id])->one()['content'].Projectapplication::find()->where(['id'=>$model->project_id])->one()['projectdata'].Projectapplication::find()->where(['id'=>$model->project_id])->one()['unit'],
            ],
            [
            	'attribute' => 'begindate',
            	'value' => date('Y年m月d日',$model->begindate),
            ],
            [
            'attribute' => 'begindate',
            'value' => date('Y年m月d日',$model->enddate),
            ],
            [
            	'label' => '项目计划',
            	'value' => $model->content,
            ],
            
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
