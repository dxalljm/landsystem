<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ManagementArea */

$this->title = 'management_area' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['managementareaindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['managementareaview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="management-area-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('managementarea_form', [
        'model' => $model,
    ]) ?>

</div>
