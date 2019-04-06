<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;
use app\models\Plantingstructure;
use app\models\Plant;
use app\models\Farms;

/* @var $this yii\web\View */
/* @var $model app\models\Yields */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'yields'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['yieldsindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="yields-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
<?php if(User::getItemname('地产科')) {?>
    <p>
    	 <?= Html::a('添加', ['yieldscreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['yieldsupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['yieldsdelete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '您确定要删除这项吗？',
                'method' => 'post',
            ],
        ]) ?>
    </p>
<?php }?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            [
            	'label' => '种植作物',
            	'attribute' => 'planting_id',
            	'value' => Plant::find()->where(['id'=>Plantingstructure::find()->where(['id'=>$model->planting_id])->one()['plant_id']])->one()['typename'],
            ],
            [
            	'label' => '法人',
            	'attribute' => 'farmer_id',
            	'value' => Farms::find()->where(['id'=>$model->farms_id])->one()['farmername'],
            ],
            'single',
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
