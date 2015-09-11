<?php
namespace backend\controllers;
use app\models\tables;
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
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
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
