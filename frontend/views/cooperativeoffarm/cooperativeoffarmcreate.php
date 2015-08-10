<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\CooperativeOfFarm */

$this->title = 'cooperative_of_farm' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['cooperativeoffarmindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cooperative-of-farm-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('cooperativeoffarm_form', [
        'model' => $model,
    ]) ?>

</div>
