<?php

use app\models\tables;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\Farms;
use app\models\Plantingstructure;
use app\models\Plant;
use app\models\Lease;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use dosamigos\datetimepicker\DateTimePicker;
use yii\helpers\Url;
use yii\widgets\ActiveFormrdiv;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="lease-index">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-body">
<?php $form = ActiveFormrdiv::begin(); ?>

  <?php $managementArea_array = ArrayHelper::map(ManagementArea::find()->all(), 'id', 'areaname');
                	array_splice($managementArea_array,0,0,[0=>'全部']);
                ?>              
  <table class="table table-hover">
  <tr>
  	<td align="right">管理区</td>
    <td><?= html::dropDownList('managementarea','',$managementArea_array,['class'=>'form-control','id'=>'management_area'])?></td>
    <td align="right">选项</td><?php $class = ['parmpt'=>'请选择...','farms'=>'农场法人','plantingstructure'=>'种植作物','infrastructure'=>'基础设施','yields'=>'产量信息','sales'=>'销量信息','breedinfo'=>'养殖信息','prevention'=>'防疫情况','fireprevention'=>'防火情况','loan'=>'贷款情况','ollection'=>'缴费情况','disaster'=>'灾害情况']?>
    <td><?php echo html::dropDownList('tab','',$class,['class'=>'form-control','id'=>'tabname'])?></td>
    <td align="right">自</td>
    <td><?php echo DateTimePicker::widget([
				'name' => 'begindate',
    			'language' => 'zh-CN',
				//'value' => $oldFarm->begindate,
				'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
				'options' => [
					'readonly' => true
				],
				'clientOptions' => [
					
					'format' => 'yyyy-mm-dd',
					'todayHighlight' => true,
					'autoclose' => true,
					'minView' => 3,
					'maxView' => 3,
				]
			]);?></td>
    <td>至</td>
    <td><?php echo DateTimePicker::widget([
				'name' => 'enddate',
    			'language' => 'zh-CN',
				//'value' => $oldFarm->enddate,
				'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
				//'type' => DatePicker::TYPE_COMPONENT_APPEND,
				'options' => [
					'readonly' => true
				],
				'clientOptions' => [
					'language' => 'zh-CN',
					'format' => 'yyyy-mm-dd',
					//'todayHighlight' => true,
					'autoclose' => true,
					'minView' => 3,
					'maxView' => 3,
				]
			]);?></td>
    <td>止</td>
    <td><?= html::submitButton('查询',['class'=>'btn btn-success'])?></td>
    
  </tr>
</table>
 <?php ActiveFormrdiv::end(); ?>
 <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => Plantingstructure::plantingstructure(),
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
