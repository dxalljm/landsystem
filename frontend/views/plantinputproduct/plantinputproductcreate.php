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

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('plantinputproduct_form', [
        'model' => $model,
    	'planting' => $planting,
    	//'plantinputproducts' => $plantinputproducts,
    ]) ?>

</div>
