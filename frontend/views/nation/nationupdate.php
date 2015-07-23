<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Nation */

$this->title = 'nation' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['nationindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['nationview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="nation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('nation_form', [
        'model' => $model,
    ]) ?>

</div>
