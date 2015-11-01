<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Breedtype */

$this->title = 'breedtype' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['breedtypeindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['breedtypeview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="breedtype-update">

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

    <?= $this->render('breedtype_form', [
        	'model' => $model,
            'father' => $father,
            'father_id' => 0,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
