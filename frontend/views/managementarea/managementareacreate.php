<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\ManagementArea */

$this->title = 'management_area' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['managementareaindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="management-area-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('managementarea_form', [
        'model' => $model,
    ]) ?>

</div>
