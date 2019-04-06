<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Inputproduct;
use app\models\Infrastructuretype;
use app\models\User;
use Yii;
/* @var $this yii\web\View */
/* @var $model app\models\Projectapplication */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'projectapplication'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['projectapplicationindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="projectapplication-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                                            </h3>
                </div>
                <div class="box-body">
<?php if(User::getItemname('地产科')) {?>
    <p>
    	 <?= Html::a('添加', ['projectapplicationcreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['projectapplicationupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['projectapplicationdelete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除这项吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>
<?php }?>
<?= Html::a('返回', Yii::$app->getRequest()->getReferrer(), ['class' => 'btn btn-success'])?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [ 
            	'attribute'=>'projecttype',
            	'value' => Infrastructuretype::find()->where(['id'=>$model->projecttype])->one()['typename'],
            ],
            'content',
            'projectdata',
            'unit',
            [
            	'attribute' => 'update_at',
            	'value' => date('Y年m月d日',$model->update_at),
            ],
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
