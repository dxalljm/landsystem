<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Disputetype */

$this->title = 'disputetype' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['disputetypeindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['disputetypeview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="disputetype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('disputetype_form', [
        'model' => $model,
    ]) ?>

</div>
