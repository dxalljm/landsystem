<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\mainmenu */

$this->title = 'Mainmenu ' ;
$this->title = Tables::find()->where(['tablename'=>strtolower($this->title)])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['mainmenuindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['mainmenuview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="mainmenu-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('mainmenu_form', [
        'model' => $model,
    ]) ?>

</div>
