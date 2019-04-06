<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Log */

$this->title = 'log' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['logindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['logview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="log-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('log_form', [
        'model' => $model,
    ]) ?>

</div>
