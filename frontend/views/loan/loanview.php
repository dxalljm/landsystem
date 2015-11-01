<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $model app\models\Loan */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'loan'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['loanindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-view">

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

    <p>
    	 <?= Html::a('添加', ['loancreate', 'farms_id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['loanupdate', 'id' => $model->id,'farms_id'=>$_GET['farms_id']], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['loandelete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除这项吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
            	'attribute' => 'farms_id',
            	'label' => '农场名称',
            	'value' => Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'].'('.Farms::find()->where(['id'=>$model->farms_id])->one()['farmername'].')',
            ],
            'mortgagearea',
            'mortgagebank',
            'mortgagemoney',
            
        ],
    ]) ?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>
