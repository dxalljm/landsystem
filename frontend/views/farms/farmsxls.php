<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\farms */

$this->title = 'farms' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = 'XLS导入'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['farmsindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="farms-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('xls_form', [
        'model' => $model,
    	'rows' => $rows,
    ]) ?>

</div>
