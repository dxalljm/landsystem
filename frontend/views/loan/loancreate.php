<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use app\models\Farms;

/* @var $this yii\web\View */
/* @var $model app\models\Loan */

$this->title = 'loan' ;
$title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->title = '添加'.$title;
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['loanindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-create">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmname']; ?>
                    </h3>
                </div>
                <?php Farms::showRow($_GET['farms_id']);?>
                <div class="box-body">

    <?= $this->render('loan_form', [
        'model' => $model,
    ]) ?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>
