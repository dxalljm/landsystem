<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Fireprevention */

$this->title = 'fireprevention' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['firepreventionindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['firepreventionview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="fireprevention-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('firepreventionupdate_form', [
        'model' => $model,
    ]) ?>

</div>
