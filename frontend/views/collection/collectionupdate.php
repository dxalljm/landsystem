<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Collection */

$this->title = 'collection' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['collectionindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['collectionview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="collection-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('collection_form', [
        'model' => $model,
    ]) ?>

</div>
