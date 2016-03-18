<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Machinetype;
use app\models\Machine;

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
                    <h3 class="box-title">
                        <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('添加', ['machineoffarmcreate','farms_id'=>$farms_id], ['class' => 'btn btn-success']) ?>
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
//             'machinetype_id',
//             'machine_id',
			'machinename',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
