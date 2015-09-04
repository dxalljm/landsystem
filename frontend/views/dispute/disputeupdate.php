<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Dispute */

$this->title = 'dispute' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['disputeindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['disputeview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="dispute-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('dispute_form', [
        'model' => $model,
    ]) ?>

</div>
