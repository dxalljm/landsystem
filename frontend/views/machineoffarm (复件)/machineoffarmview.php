<?php
namespace frontend\controllers;use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Machine;
use app\models\Machinetype;
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $model app\models\Machineoffarm */

$this->title = 'ID:'.$model->id;
$title = Tables::find()->where(['tablename'=>'machineoffarm'])->one()['Ctablename'];
$this->params['breadcrumbs'][] = ['label' => $title, 'url' => ['machineoffarmindex']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="machineoffarm-view">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">

    <p>
    	 <?= Html::a('添加', ['machineoffarmcreate', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?= Html::a('更新', ['machineoffarmupdate', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['machineoffarmdelete', 'id' => $model->id], [
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
            	'label' => '法人',
            	'value' => Farms::find()->where(['id'=>$model->farms_id])->one()['farmername'],
            ],
            'machinename',
           	[
				'label' => '农机大类',
				'value' => Machinetype::getClass($model->machinetype_id)[0],
			],
			[
				'label' => '农机小类',
				'value' => Machinetype::getClass($model->machinetype_id)[1],
			],
			[
				'label' => '农机品目',
				'value' => Machinetype::getClass($model->machinetype_id)[2],
			],
			[
			'label' => '型号',
			'value' => Machine::find()->where(['id'=>$model->machine_id])->one()['implementmodel'],
			],
			[
			'label' => '生产厂商',
			'value' => Machine::find()->where(['id'=>$model->machine_id])->one()['enterprisename'],
			],
			[
			'label' => '企业所在省份',
			'value' => Machine::find()->where(['id'=>$model->machine_id])->one()['province'],
			],
			[
			'label' => '分档名称',
			'value' => Machine::find()->where(['id'=>$model->machine_id])->one()['filename'],
			],
			[
			'label' => '基本配置及参数',
			'value' => Machine::find()->where(['id'=>$model->machine_id])->one()['parameter'],
			],
			[
			'label' => '备注',
			'value' => Machine::find()->where(['id'=>$model->machine_id])->one()['content'],
			],
            
        ],
    ]) ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
