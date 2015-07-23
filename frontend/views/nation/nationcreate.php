<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Nation */

$this->title = 'nation' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['nationindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="nation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('nation_form', [
        'model' => $model,
    ]) ?>

</div>
