<?php
namespace backend\controllers;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\mainmenu */

$this->title = 'Mainmenu ' ;
$title = Tables::find()->where(['tablename'=>strtolower($this->title)])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['mainmenuindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="mainmenu-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('mainmenu_form', [
        'model' => $model,
    ]) ?>

</div>
