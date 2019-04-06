<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Cooperativetype */

$this->title = 'cooperativetype' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['cooperativetypeindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['cooperativetypeview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="cooperativetype-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('cooperativetype_form', [
        'model' => $model,
    ]) ?>

</div>
