<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\MenuToUser */

$this->title = 'menu_to_user' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['menutouserindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-to-user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('menutouser_form', [
        'model' => $model,
    ]) ?>

</div>
