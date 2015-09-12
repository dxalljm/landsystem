<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Plantinputproduct */

$this->title = 'plantinputproduct' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['plantinputproductindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="plantinputproduct-create">

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

    <?= $this->render('plantinputproduct_form', [
        'model' => $model,
    	'planting' => $planting,
    	//'plantinputproducts' => $plantinputproducts,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
