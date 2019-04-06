<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Cooperativetype */

$this->title = 'cooperativetype' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['cooperativetypeindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cooperativetype-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('cooperativetype_form', [
        'model' => $model,
    ]) ?>

</div>
