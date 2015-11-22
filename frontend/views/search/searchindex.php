<?php
namespace frontend\controllers;
use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use dosamigos\datetimepicker\DateTimePicker;
?>
<div class="search-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
  <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">
<table class="table table-hover">
  <tr>
    <td align="right">农场名称</td>
    <td><?= html::textInput('farmname','',['class'=>'form-control'])?></td>
    <td align="right">法人名称</td>
    <td><?= html::textInput('farmername','',['class'=>'form-control'])?></td>
    <td align="right">类别选项</td><?php $class = ['plant'=>'作物','infrastructure'=>'基础设施','yields'=>'产量信息','sales'=>'销量信息','breedinfo'=>'养殖信息','prevention'=>'防疫情况','fireprevention'=>'防火情况','loan'=>'贷款情况','ollection'=>'缴费情况','disaster'=>'灾害情况']?>
    <td><?php echo html::dropDownList('tab','',$class,['class'=>'form-control'])?></td>
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

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        
        <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-aqua-active">
              <h3 class="widget-user-username">农作物种植情况统计</h3><?php $areaSum = 0;foreach($planting as $value){foreach($value['area'] as $val){$areaSum+=$val;}}?>
              <h5 class="widget-user-desc">今年种植了 <?= count($planting);?> 种作物,共计<?= $areaSum?>亩</h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="images/plant.jpg" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
              <?php foreach ($planting as $key => $value) {?>
                <div class="col-sm-4 border-right">
                  <div class="description-block"><?php $area = 0;?>
                    <h5 class="description-header"><?php foreach($value['area'] as $val) {$area += $val;} echo $area.'亩';?></h5>
                    <span class="description-text"><?= $key?></span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <?php }?>
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.widget-user -->
        </div>
        <!-- /.col -->
</section>

 <?php ActiveFormrdiv::end(); ?>
</div>
