<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Pesticides */

$this->title = 'pesticides' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['pesticidesindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="pesticides-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('pesticides_form', [
        'model' => $model,
    ]) ?>

</div>
