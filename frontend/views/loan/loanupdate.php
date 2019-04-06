<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $model app\models\Loan */

$this->title = 'loan' ;
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['loanindex']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['loanview', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>
<div class="loan-update">

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
    	'process' => $process,
    ]) ?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>
