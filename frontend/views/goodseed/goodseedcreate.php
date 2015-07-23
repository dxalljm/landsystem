<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Goodseed */

$this->title = 'goodseed' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['goodseedindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="goodseed-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('goodseed_form', [
        'model' => $model,
    ]) ?>

</div>
