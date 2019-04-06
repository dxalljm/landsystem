<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Fireprevention */

$this->title = 'fireprevention' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['firepreventionindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="fireprevention-create">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

    <?= $this->render('fireprevention_form', [
        'model' => $model,
    	'employees' => $employees,
    	//'fireemployeeModel' => $fireemployeeModel,
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
