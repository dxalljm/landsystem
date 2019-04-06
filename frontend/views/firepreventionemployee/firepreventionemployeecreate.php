<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Firepreventionemployee */

$this->title = 'fireprevention_employee' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['firepreventionemployeeindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="firepreventionemployee-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('firepreventionemployee_form', [
        'model' => $model,
    ]) ?>

</div>
