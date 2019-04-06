<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Parcel */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'parcel'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['parcelindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parcel-view">


</div>
