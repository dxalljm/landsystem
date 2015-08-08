<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cooperative */

$this->title = 'cooperative' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['cooperativeindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['cooperativeview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="cooperative-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('cooperative_form', [
        'model' => $model,
    ]) ?>

</div>
