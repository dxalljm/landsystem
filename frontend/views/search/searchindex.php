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
?>

<script type="text/javascript" src="js/jquery.flip.min.js"></script>
<?php $this->registerJsFile('js/vendor/bower/jquery/dist/jquery.min.js', ['position' => View::POS_HEAD]); ?>
<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/showhighcharts.js"></script>
<div class="search-form">
  <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
                <?php $form = ActiveFormrdiv::begin(); ?>
                <?php $managementArea_array = ArrayHelper::map(ManagementArea::find()->all(), 'id', 'areaname');
                	array_splice($managementArea_array,0,0,[0=>'全部']);
//                 var_dump($begindate);
                ?>
<table class="table table-hover">
  <tr>
  	<td align="right">管理区</td>
    <td><?= html::dropDownList('management_area',$management_area,$managementArea_array,['class'=>'form-control','id'=>'management_area'])?></td><?= html::hiddenInput('oldtablename','',['id'=>'old-tablename'])?>
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
    <td><?= html::submitButton('查询',['class'=>'btn btn-success','disabled'=>'disabled','id'=>'searchButton'])?></td>
  </tr>
</table>
<script>
if($('#tablename').val() !== 'parmpt')
	$('#searchButton').removeAttr("disabled"); 
else
	$('#tablename').change(function(){
		var input = $(this).val();
		if(input == 'parmpt')
			$('#searchButton').attr('disabled',"true"); 
		else
			$('#searchButton').removeAttr("disabled");
	});

</script>

