<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;


/* @var $this yii\web\View */
/* @var $model app\models\Parcel */

$this->title = 'machinetype' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '导入'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['parcelindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parcel-xls">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('xls_form',[
    		'model' => $model,
    ]) ?>

</div>
