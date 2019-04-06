<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Auditprocess */

$this->title = 'auditprocess' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['auditprocessindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['auditprocessview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="auditprocess-update">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                          <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">

    <?= $this->render('auditprocess_form', [
        'model' => $model,
    	'processnamestr' => $processnamestr,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
