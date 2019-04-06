<?php
namespace frontend\controllers;use app\models\User;
use Yii;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use dosamigos\datetimepicker\DateTimePicker;
use frontend\helpers\MoneyFormat;
use yii\web\View;
?>
<link rel="stylesheet" type="text/css" href="css/styles.css" />

<script type="text/javascript" src="js/jquery.flip.min.js"></script>
<?php $this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/showhighcharts.js"></script>
<div class="search-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
  <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
<table class="table table-hover">
  <tr>
    <td align="right">农场</td>
    <td><?= html::textInput('farmname','',['class'=>'form-control'])?></td>
    <td align="right">法人</td>
    <td><?= html::textInput('farmername','',['class'=>'form-control'])?></td>
    <td align="right">选项</td><?php $class = ['parmpt'=>'请选择...','plant'=>'作物','infrastructure'=>'基础设施','yields'=>'产量信息','sales'=>'销量信息','breedinfo'=>'养殖信息','prevention'=>'防疫情况','fireprevention'=>'防火情况','loan'=>'贷款情况','ollection'=>'缴费情况','disaster'=>'灾害情况']?>
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
    <?php 
        if($collection) {
        ?>
			
			<div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user" id="collection">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-red">
              <h3 class="widget-user-username">承包费收缴情况统计</h3>
              <?php $amountsSum = 0;
              	foreach($collection['amounts_receivable'] as $val){
              		$amountsSum+=$val;
              	}
              ?>
              <?php $realSum = 0;
              	foreach($collection['real_income_amount'] as $val){
              		$realSum+=$val;
              	}
              ?>
              <h5 class="widget-user-desc"><?= $searchDate?><br>承包费收缴情况 </h5>
            </div>

            <div class="box-footer">
              <div class="row">
              <?php foreach ($collection as $key => $value) {?>
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header"><?php if($key == 'amounts_receivable') echo MoneyFormat::num_format($amountsSum).'元';
                    elseif($key == 'real_income_amount') echo MoneyFormat::num_format($realSum).'元';
                    else echo MoneyFormat::num_format($amountsSum-$realSum).'元';?></h5>
                    <span class="description-text"><?php if($key == 'amounts_receivable') echo '应收金额';elseif($key == 'real_income_amount') echo '实收金额';else echo '欠款金额';?></span>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
<script type="text/javascript">
showPie(<?php echo json_encode([['应收金额',$amountsSum],['实收金额',$realSum],['欠款金额',$amountsSum-$realSum]]);?>,'showHigh','承包费收缴情况统计');
</script>
                <?php }?>
              </div>
              <!-- /.row -->
            </div>
          </div>
          <!-- /.widget-user -->
          	<div class="col-md-4 sponsorData" id='showHigh' style="width: 100%"></div>

       </div>
        <!-- /.col -->  	


        <?php }?>
        <?php if($loan) {?>
        
        <div class="col-md-4">
        
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-aqua-active">
              <h3 class="widget-user-username">贷款情况统计</h3>
              <?php
              $allsum = 0;
              foreach($loan as $value){
              	$allsum += $value['mortgagemoney']; 
              	$result[$value['mortgagebank']][] = ['mortgagearea' => $value['mortgagearea'],'mortgagemoney'=>$value['mortgagemoney']];
              }?>
              <h5 class="widget-user-desc"><?= $searchDate?><br>全部贷款金额为<?= MoneyFormat::num_format($allsum)?>元</h5>
            </div>
            <div class="box-footer">
              <div class="row">
              <?php foreach ($result as $key => $value) {?>
                <div class="col-sm-4 border-right">
                  <div class="description-block"><?php $area = 0;$money = 0;?>
                    <h5 class="description-header"><?php foreach ($value as $val) {$area += $val['mortgagearea'];$money += $val['mortgagemoney'];} echo MoneyFormat::num_format($money).'元';?></h5>
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
  
          <div class="sponsorData" id='showpie'></div>
          <script type="text/javascript">showPie(<?php echo json_encode($loan)?>,'showpie','贷款情况统计');</script>
          <!-- /.widget-user -->
        </div>
        <!-- /.col -->   
        
     

        <?php } if($breedinfo) {
        ?>

          <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-red">
              <h3 class="widget-user-username">养殖情况统计</h3>
              <?php 
              $result = [];
              	foreach ($breedinfo as $value) {
					foreach ($value as $key=>$val) {
						$result[$key][] = ['number'=>$val['number'],'unit'=>$val['unit']];
					}
             	}
// 				var_dump($result);
              ?>
              <h5 class="widget-user-desc"><?= date('Y')?>年度养殖情况 </h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="images/plant.jpg" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
              <?php foreach ($result as $key => $value) {?>
                <div class="col-sm-4 border-right">
                  <div class="description-block"><?php $n = 0?>
                    <h5 class="description-header"><?php foreach($value as $val){$n += $val['number'];}echo $n.$value[0]['unit'];?></h5>
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
        <?php }?>  
        </div>
        </div>
        <div class="row">
        <?php if($planting) {?>
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
        
        <?php }
        if($collection) {
        ?>
        
          <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-red">
              <h3 class="widget-user-username">承包费收缴情况统计</h3>
              <?php $amountsSum = 0;
              	foreach($collection['amounts_receivable'] as $val){
              		$amountsSum+=$val;
              	}
              ?>
              <?php $realSum = 0;
              	foreach($collection['real_income_amount'] as $val){
              		$realSum+=$val;
              	}
              ?>
              <h5 class="widget-user-desc"><?= date('Y')?>年度承包费收缴情况 </h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="images/plant.jpg" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
              <?php foreach ($collection as $key => $value) {?>
                <div class="col-sm-4 border-right">
                  <div class="description-block">
                    <h5 class="description-header"><?php if($key == 'amounts_receivable') echo MoneyFormat::num_format($amountsSum).'元';
                    elseif($key == 'real_income_amount') echo MoneyFormat::num_format($realSum).'元';
                    else echo MoneyFormat::num_format($amountsSum-$realSum).'元';?></h5>
                    <span class="description-text"><?php if($key == 'amounts_receivable') echo '应收金额';elseif($key == 'real_income_amount') echo '实收金额';else echo '欠款金额';?></span>
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
       
        <?php }
//         var_dump($breedinfo);
        if($breedinfo) {
        ?>

          <div class="col-md-4">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-red">
              <h3 class="widget-user-username">养殖情况统计</h3>
              <?php 
              $result = [];
              	foreach ($breedinfo as $value) {
					foreach ($value as $key=>$val) {
						$result[$key][] = ['number'=>$val['number'],'unit'=>$val['unit']];
					}
             	}
// 				var_dump($result);
              ?>
              <h5 class="widget-user-desc"><?= date('Y')?>年度养殖情况 </h5>
            </div>
            <div class="widget-user-image">
              <img class="img-circle" src="images/plant.jpg" alt="User Avatar">
            </div>
            <div class="box-footer">
              <div class="row">
              <?php foreach ($result as $key => $value) {?>
                <div class="col-sm-4 border-right">
                  <div class="description-block"><?php $n = 0?>
                    <h5 class="description-header"><?php foreach($value as $val){$n += $val['number'];}echo $n.$value[0]['unit'];?></h5>
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
        <?php }?>  
        </div>
</section>

 <?php ActiveFormrdiv::end(); ?>
</div>
<input type="checkbox" class="all"/> 全选 <br>
					<input type="checkbox" class="invert"/> 反选 <br>
					<input type="checkbox" class="revoke"/> 取消选择 <br>

					<div>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
						<input type="checkbox" class="nodes"/> <br>
                    </div>


					<script>
						selected.all('.all', '.nodes');
						selected.invert('.invert', '.nodes');
						selected.revoke('.revoke', '.nodes');
					</script>
    

