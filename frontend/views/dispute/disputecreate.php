<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Dispute */

$this->title = 'dispute' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['disputeindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dispute-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('dispute_form', [
        'model' => $model,
    ]) ?>

</div>
