<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\farms */

$this->title = 'farmer' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = 'XLS导入'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['farmerindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="farmer-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('xls_form', [
        'model' => $model,
    	'rows' => $rows,
    ]) ?>

</div>
