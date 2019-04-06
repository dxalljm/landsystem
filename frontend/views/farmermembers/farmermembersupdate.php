<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Farmermembers */

$this->title = 'farmermembers' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['farmermembersindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['farmermembersview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="farmermembers-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('farmermembers_form', [
        'model' => $model,
    ]) ?>

</div>
