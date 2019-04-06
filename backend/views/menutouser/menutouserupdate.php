<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\MenuToUser */

$this->title = 'menu_to_user' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['menutouserindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['menutouserview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="menu-to-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('menutouser_form', [
        'model' => $model,
    ]) ?>

</div>
