<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Farms;
use frontend\helpers\MoneyFormat;
use app\models\Loan;
/* @var $this yii\web\View */
/* @var $model app\models\Loan */
use app\models\Reviewprocess;

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
                        <?= Farms::find()->where(['id'=>$model->farms_id])->one()['farmname']; ?>
                    </h3>
                </div>
                <?php Farms::showRow($model->farms_id);?>
                <div class="box-body">
                    <?php if(!Loan::find()->where(['farms_id'=>$_GET['farms_id']])->count()) {?>
    <p>
    	 <?= Html::a('添加', ['loancreate', 'farms_id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['loanupdate', 'id' => $model->id,'farms_id'=>$model->farms_id], ['class' => 'btn btn-primary']) ?>
    </p>
<?php }?>
                    <?php
                    if($model->reviewprocess_id > 0) {
                        if (!Reviewprocess::loanISexamine($model->id))
                            echo Html::a('撤消', ['loandelete', 'id' => $model->id,'farms_id'=>$model->farms_id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => '您确定要撤消这项吗？',
                                    'method' => 'post',
                                ],
                            ]);
                    }?>
    <?php echo Farms::showFarminfo2($_GET['farms_id']);?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
//             'id',
//             [
//             	'attribute' => 'farms_id',
//             	'label' => '农场名称',
//             	'value' => Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'].'('.Farms::find()->where(['id'=>$model->farms_id])->one()['farmername'].')',
//             ],
            'mortgagearea',
            'mortgagebank',
            [
            	'attribute' => 'mortgagemoney',
            	'value' => MoneyFormat::num_format($model->mortgagemoney).'万元',
            ],
            [
            	'attribute' => 'state',
            	'value' => $model->state?'冻结':'解冻',
            ],
            [
            	'label' => '解冻书',
            	'format' => 'raw',
            	'attribute' => 'unlockbook',
            	'value' => Html::a(Html::img($model->unlockbook,['width'=>'400']),$model->unlockbook),
            ],
            
        ],
    ]) ?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>
