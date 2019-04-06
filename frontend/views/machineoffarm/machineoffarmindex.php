<?php
namespace frontend\controllers;
use app\models\Farms;
use app\models\Machineapply;
use app\models\User;
use app\models\Tables;
use app\models\Machineapplymachine;
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
$arrclass = explode('\\',$dataProvider->query->modelClass);
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
//			echo Html::a('填写申请表', '#', ['class' => 'btn btn-success','disabled'=>User::disabled()]);
		} else {
			echo Html::a('添加', ['machineoffarmcreate','farms_id'=>$farms_id], ['class' => 'btn btn-success']);

//			$farm = Farms::findOne($farms_id);
//			$machineapply = Machineapply::find()->where(['cardid'=>$farm['cardid']])->orderBy('id DESC')->one();
//			if($machineapply) {
//				if($machineapply['state'] == -1)
//					echo Html::a('填写申请表', ['machineapply/machineapplycreate','farms_id'=>$farms_id], ['class' => 'btn btn-success']);
//				else {
//					echo Html::a('填写申请表', '#', ['class' => 'btn btn-success','disabled'=>'disabled']);
//				}
//			} else {
//				echo Html::a('填写申请表', ['machineapply/machineapplycreate','farms_id'=>$farms_id], ['class' => 'btn btn-success']);
//			}
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
<<<<<<< HEAD
			],
			[
				'label' => '补贴金额',
				'value' => function($model) {
					$apply = Machineapply::find()->where(['machineoffarmold_id'=>$model->id])->one();
					if($apply) {
						return $apply['subsidymoney'];
					}
				}
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
			],
			[
				'label' => '购置年份',
				'attribute' => 'acquisitiontime',
			],
//             'machinetype_id',
//             'machine_id',
			'machinename',
            ['class' => 'frontend\helpers\eActionColumn'],
			[
				'label' => '操作',
				'format' => 'raw',
				'value' => function ($model) {
					$html = '';
					$machine = Machine::findOne($model->machine_id);
					$apply = Machineapply::find()->where(['cardid'=>$model->cardid,'machineoffarmold_id'=>$model->id,'year'=>User::getYear(),'state'=>[0,1]])->one();
					if($apply) {
						if ($apply['state'] == 1) {
//							var_dump(Machineapplymachine::findOne($apply['machineoffarm_id'])['machine_id']);var_dump($model->machine_id);exit;
//							return $apply['machineoffarm_id'].'--'.$model->machine_id;
//							if (Machineapplymachine::findOne($apply['machineoffarm_id'])['machine_id'] === $model->machine_id) {
//								if ($apply['state'] == 1) {
									$html .= Html::a('查看补贴申报表', Url::to(['machineapply/machineapplyprint', 'id' => $apply['id'],'farms_id'=>$_GET['farms_id']]), ['class' => "btn btn-xs btn-sm btn-success"]);
//								}
//							} else {
//
//							}
						} else {
//							$html .= Html::a('确认补贴金额', Url::to(['machineoffarm/machineoffarmcomparison', 'apply_id' => $apply['id'], 'machineoffarm_id' => $model->id]), ['class' => 'btn btn-xs btn-danger']);
							if ($apply['dckstate'] == 1) {
								if($machine['state'] == 0) {
									$html .= '<span class="text text-red">此机具不在补贴范围内</span>';
								} else {
									if ($apply['machineoffarm_id'] == $model->id) {
										$html .= Html::a('确认补贴金额', Url::to(['machineoffarm/machineoffarmcomparison', 'apply_id' => $apply['id'], 'machineoffarm_id' => $model->id,'farms_id'=>$model->farms_id]), ['class' => 'btn btn-xs btn-danger']);
									}
								}
							}
							if ($apply['dckstate'] == 0) {
								$html .= Html::a('填写申请表', ['machineapply/machineapplycreate', 'machineoffarm_id' => $model->id], ['class' => 'btn btn-xs btn-success']);
							}
						}
					} else {
						if($machine['state'] == 0) {
//							$html .= Html::a('填写申请表', ['machineapply/machineapplycreate', 'machineoffarm_id' => $model->id], ['class' => 'btn btn-xs btn-success']);
							$html .= '<span class="text text-red">此机具不在补贴范围内</span>';
						} else {
							$apply = Machineapply::find()->where(['cardid'=>$model->cardid,'machineoffarmold_id'=>$model->id,'year'=>date('Y',$model->create_at),'state'=>1])->one();
							if($apply) {
								$html .= Html::a('查看补贴申报表', Url::to(['machineapply/machineapplyprint', 'id' => $apply['id'],'farms_id'=>$_GET['farms_id']]), ['class' => "btn btn-xs btn-sm btn-success"]);
							} else {
								$html .= Html::a('填写申请表', ['machineapply/machineapplycreate', 'machineoffarm_id' => $model->id], ['class' => 'btn btn-xs btn-success']);
							}
						}
					}

					return $html;
				}
			],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<script>
//	$('.shclDefault').shCircleLoader({color: "red"});
//	$(document).ready(function () {
//		$.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>//',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'sum-machineapply_subsidymoney'}, function (data) {
//			$('#t3').html(data + '元');
//		});

//        $.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>//',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'sum-mortgagemoney'}, function (data) {
//            $('#t5').html(data + '万元');
//        });
	});
</script>