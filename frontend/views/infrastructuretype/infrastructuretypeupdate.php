<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Infrastructuretype */

$this->title = 'infrastructuretype' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['infrastructuretypeindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['infrastructuretypeview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="infrastructuretype-update">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                                            </h3>
                </div>
                <div class="box-body">

    <?= $this->render('infrastructuretype_form', [
        'model' => $model,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>