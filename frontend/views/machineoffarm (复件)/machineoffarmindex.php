<?php
namespace frontend\controllers;
use app\models\Farms;
use app\models\Machineapply;
use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Machinetype;
use app\models\Machine;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\MachineoffarmSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'machineoffarm';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="machineoffarm-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;农机器具<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?php
		if(User::disabled()) {
			echo Html::a('添加', '#', ['class' => 'btn btn-success','disabled'=>User::disabled()]);
			echo Html::a('填写申请表', '#', ['class' => 'btn btn-success','disabled'=>User::disabled()]);
		} else {
			echo Html::a('添加', ['machineoffarmcreate','farms_id'=>$farms_id], ['class' => 'btn btn-success']);

			$farm = Farms::findOne($farms_id);
			$machineapply = Machineapply::find()->where(['cardid'=>$farm['cardid']])->orderBy('id DESC')->one();
			if($machineapply) {
				if($machineapply['state'] == -1)
					echo Html::a('填写申请表', ['machineapply/machineapplycreate','farms_id'=>$farms_id], ['class' => 'btn btn-success']);
				else {
					echo Html::a('填写申请表', '#', ['class' => 'btn btn-success','disabled'=>'disabled']);
				}
			} else {
				echo Html::a('填写申请表', ['machineapply/machineapplycreate','farms_id'=>$farms_id], ['class' => 'btn btn-success']);
			}
		}
		?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//             'id',
//             'farms_id',
			[
				'label' => '农机大类',
				'value' => function ($model) {
					return Machinetype::getClass($model->machinetype_id)[0];
				}
			],
			[
				'label' => '农机小类',
				'value' => function ($model) {
					return Machinetype::getClass($model->machinetype_id)[1];
				}
			],
			[
				'label' => '农机品目',
				'value' => function ($model) {
					return Machinetype::getClass($model->machinetype_id)[2];
				}
			],
			[
				'label' => '型号',
				'value' => function ($model) {
					return Machine::find()->where(['id'=>$model->machine_id])->one()['implementmodel'];
				}
			],
			[
				'label' => '生产厂商',
				'value' => function ($model) {
					return Machine::find()->where(['id'=>$model->machine_id])->one()['enterprisename'];
				}
			],
			[
				'label' => '购置年份',
				'attribute' => 'acquisitiontime',
			],
//             'machinetype_id',
//             'machine_id',
			'machinename',
			[
				'label' => '操作',
				'format' => 'raw',
				'value' => function ($model) {
					$html = '';
					$apply = Machineapply::find()->where(['cardid'=>$model->cardid,'state'=>1])->one();
					if($apply) {
						$html .= Html::a('查看补贴申报表',Url::to(['machineapply/machineapplyview','machineoffarm_id'=>$model->id]),['class'=>"btn btn-sm btn-success"]);
					}
					return $html;
				}
			],
            ['class' => 'frontend\helpers\eActionColumn'],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
