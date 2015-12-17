<?php
namespace frontend\controllers;
use Yii;
use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use dosamigos\datetimepicker\DateTimePicker;
use frontend\helpers\MoneyFormat;
use yii\web\View;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use app\models\Search;
use yii\grid\GridView;
use app\models\Plant;
use app\models\Inputproduct;
use app\models\Pesticides;
?>
<style type="text/css">
#farms { display:none }
#plantingstructure { display:none }
</style>
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
                    <h3 class="box-title">
                        <?= $this->title ?>
                    </h3>
                </div>
                <div class="box-body">
                <?php $managementArea_array = ArrayHelper::map(ManagementArea::find()->all(), 'id', 'areaname');
                	array_splice($managementArea_array,0,0,[0=>'全部']);
                ?>
<table class="table table-hover">
  <tr>
  	<td align="right">管理区</td>
    <td><?= html::dropDownList('managementarea',$management_area,$managementArea_array,['class'=>'form-control','id'=>'management_area'])?></td><?= html::hiddenInput('oldtablename','',['id'=>'old-tablename'])?>
    <td align="right">选项</td><?php $class = ['parmpt'=>'请选择...','farms'=>'农场法人','plantingstructure'=>'种植作物','infrastructure'=>'基础设施','yields'=>'产量信息','sales'=>'销量信息','breedinfo'=>'养殖信息','prevention'=>'防疫情况','fireprevention'=>'防火情况','loan'=>'贷款情况','collection'=>'缴费情况','disaster'=>'灾害情况']?>
    <td><?php echo html::dropDownList('tab',$tab,$class,['class'=>'form-control','id'=>'tablename'])?></td>
    <td align="right">自</td>
    <td><?php echo DateTimePicker::widget([
				'name' => 'begindate',
    			'language' => 'zh-CN',
				'value' => date('Y-m-d',$begindate),
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
				'value' => date('Y-m-d',$enddate),
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
  <tr >
  	<td colspan="10">
  		<table class="table table-hover" id="farms" width="100%">
 		 <tr>
		    <td>农场名称</td>
		    <td><?= html::textInput('farmname','',['class'=>'form-control'])?></td>
		    <td>法人姓名</td>
		    <td><?= html::textInput('farmername','',['class'=>'form-control'])?></td>
		    <td>电话</td>
		    <td><?= html::textInput('telephone','',['class'=>'form-control'])?></td>
		    <td>农场位置</td>
		    <td><?= html::textInput('address','',['class'=>'form-control'])?></td>
		  </tr>
		</table>
		<table class="table table-hover" id="plantingstructure" width="100%">
 		 <tr>
		    <td>种植作物</td>
		     <td align='left'><?= html::dropDownList('plantfather','',ArrayHelper::map(Plant::find()->where(['father_id'=>1])->all(), 'id', 'cropname'),['prompt'=>'请选择...','class'=>'form-control','id'=>'plantfather']) ?></td>
			  <td colspan="2" align='right'><?= html::dropDownList('plantson','',[],['prompt'=>'请选择...','class'=>'form-control','id'=>'plant-son']) ?></td>
			  <td colspan="2" align="right">良种型号</td>
			  <td colspan="2"><?= html::dropDownList('goodseed','',[],['prompt'=>'请选择...','class'=>'form-control','id'=>'goodseed']) ?></td>
<!-- 		    <td>投放品</td> -->
<!-- 		     <td><?php echo Html::dropDownList('Inputproductfather', '', ArrayHelper::map(Inputproduct::find()->where(['father_id'=>1])->all(), 'id', 'fertilizer'),['prompt'=>'请选择...', 'class'=>'form-control','id' => 'inputproductfather']); ?></td> -->
<!--               <td><?php echo Html::dropDownList('Inputproductson_id', '',[], ['id'=>'plantinputproduct-son_id', 'prompt'=>'请选择...','class' => 'form-control','id'=>'inputproductson']); ?></td> -->
<!--               <td><?php echo Html::dropDownList('Inputproduct', '',[] ,['id'=>'plantinputproduct-inputproduct_id','prompt'=>'请选择...','class' => 'form-control','id'=>'Inputproductvalue']); ?></td> -->
<!-- 		    <td>农药</td> -->
<!--		     <td><?php echo Html::dropDownList('pesticides', '', ArrayHelper::map(Pesticides::find()->all(), 'id', 'pesticidename'),['prompt'=>'请选择...', 'id'=>'plantpesticides','class' => 'form-control']); ?></td> -->
		    
		  </tr>
		</table>
  	</td>
  </tr>
</table>


<?php if(!empty($dataProvider)) {?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => Search::$controllername(),
    ]); ?>
    <?php }?>



 <?php ActiveFormrdiv::end(); ?>
</div>
<script type="text/javascript">

$('#tablename').change(function(){
	var input = $(this).val();
	if($('#old-tablename').val() == '') {
		$('#'+input).css('display', 'inline');
		$('#old-tablename').val(input);
	} else {
		$('#'+$('#old-tablename').val()).css('display', 'none');
		$('#'+input).css('display', 'inline');
		$('#old-tablename').val(input);
	}
	
});
$('#'+$('#tablename').val()).css('display', 'inline');
$('#old-tablename').val($('#tablename').val());
//投入品子类联动
$(document).on("change", "select[name='PlantInputproductPost[father_id][]']", function () {
	// 投入品子类，投入品
	var fertilizerChild = $(this).parent().next().children(),
		father_id = $(this).val();

	// 请求二级分类
	$.getJSON('index.php?r=inputproduct/inputproductgetfertilizer', {father_id: father_id}, function (data) {
		fertilizerChild.html(null);
		fertilizerChild.append('<option value="prompt">请选择</option>');
		if (data.status == 1 && data.inputproductson !== null) {
			for(i = 0; i < data.inputproductson.length; i++) {
				fertilizerChild.append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
			}
		}
	});
});

// 投入品选择
$(document).on("change", "select[name='PlantInputproductPost[son_id][]']", function () {
	// 投入品子类，投入品
	var product = $(this).parent().next().children(),
	father_id = $(this).val();

	// 请求二级分类
	$.getJSON('index.php?r=inputproduct/inputproductgetfertilizer', {father_id: father_id}, function (data) {
		product.html(null);
		product.append('<option value="prompt">请选择</option>');
		if (data.status == 1 && data.inputproductson !== null) {
			for(i = 0; i < data.inputproductson.length; i++) {
				product.append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
			}
		}
	});
});
$('#plantfather').change(function(){
	father_id = $(this).val();
	$.getJSON('index.php?r=plant/plantgetson', {father_id: father_id}, function (data) {
		if (data.status == 1) {
			$('#plant-son').html(null);
			$('#plant-son').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.son.length;i++) {
				$('#plant-son').append('<option value="'+data.son[i]['id']+'">'+data.son[i]['cropname']+'</option>');
			}
		}
		else {
			$('#plant-son').html(null);
			$('#plant-son').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
$('#plant-son').change(function(){
	plant_id = $(this).val();
	
	$.getJSON('index.php?r=goodseed/goodseedgetmodel', {plant_id: plant_id}, function (data) {
		
		if (data.status == 1) {
			$('#goodseed').html(null);
			$('#goodseed').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.goodseed.length;i++) {
				$('#goodseed').append('<option value="'+data.goodseed[i]['id']+'">'+data.goodseed[i]['plant_model']+'</option>');
			}
		}
		else {
			$('#goodseed').html(null);
			$('#goodseed').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
$('#inputproductfather').change(function(){
	father_id = $(this).val();
	
	$.getJSON('index.php?r=inputproduct/inputproductgetfertilizer', {father_id: father_id}, function (data) {
		
		if (data.status == 1) {
			$('#inputproductson').html(null);
			$('#inputproductson').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.inputproductson.length;i++) {
				$('#inputproductson').append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
			}
		}
		else {
			$('#inputproductson').html(null);
			$('#inputproductson').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
$('#inputproductson').change(function(){
	father_id = $(this).val();
	
	$.getJSON('index.php?r=inputproduct/inputproductgetfertilizer', {father_id: father_id}, function (data) {
		
		if (data.status == 1) {
			$('#Inputproductvalue').html(null);
			$('#Inputproductvalue').append('<option value="prompt">请选择...</option>');
			for(i=0;i<data.inputproductson.length;i++) {
				$('#Inputproductvalue').append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
			}
		}
		else {
			$('#Inputproductvalue').html(null);
			$('#Inputproductvalue').append('<option value="prompt">请选择...</option>');
		}
			
	});
});
</script>
    

