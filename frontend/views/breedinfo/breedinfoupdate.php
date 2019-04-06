<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Breedinfo */

$this->title = 'breedinfo' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['breedinfoindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['breedinfoview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="breedinfo-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('breedinfo_form', [
        'model' => $model,
    ]) ?>

</div>
