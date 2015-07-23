<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Goodseed */

$this->title = 'goodseed' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['goodseedindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['goodseedview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="goodseed-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('goodseed_form', [
        'model' => $model,
    ]) ?>

</div>
