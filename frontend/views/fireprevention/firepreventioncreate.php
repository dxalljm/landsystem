<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fireprevention */

$this->title = 'fireprevention' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['firepreventionindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fireprevention-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('fireprevention_form', [
        'model' => $model,
    ]) ?>

</div>
