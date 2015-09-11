<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Disputetype */

$this->title = 'disputetype' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['disputetypeindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="disputetype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('disputetype_form', [
        'model' => $model,
    ]) ?>

</div>
