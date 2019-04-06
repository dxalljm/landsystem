<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Lockstate */

$this->title = '系统配置';
?>
<div class="lockstate-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                          <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">

    <p>
        <?= Html::a('更新', ['lockstateupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'systemstate',
            'systemstatedate',
            'platestate',
            'loanconfig',
            [
                'attribute' => 'loanconfigdate',
                'value' => $model->loanconfigdate.'——'.$model->loanconfigdateend,
            ],
            'transferconfig',
            [
                'attribute' => 'transferconfigdate',
                'value' => $model->transferconfigdate.'——'.$model->transferconfigdateend,
            ],
            'plantstate',
            [
                'attribute' => 'plantstatedate',
                'value' => $model->plantstatedate.'——'.$model->plantstatedateend,
            ],
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
