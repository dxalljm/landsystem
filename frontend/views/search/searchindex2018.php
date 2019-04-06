<?php
namespace frontend\controllers;
use Yii;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use dosamigos\datetimepicker\DateTimePicker;
use frontend\helpers\MoneyFormat;
use yii\web\View;
use yii\helpers\ArrayHelper;
use app\models\ManagementArea;
use app\models\Search;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\User;
use app\models\MenuToUser;
?>
<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<div class="search-form">
  <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <?php User::tableBegin('综合查询');?>
                <?php $form = ActiveFormrdiv::begin(['method'=>'get']); ?>
                <?php $managementArea_array = ArrayHelper::map(ManagementArea::find()->where(['id'=>Farms::getManagementArea()['id']])->all(), 'id', 'areaname');
//                 	var_dump($tab);exit;
                	if(count($managementArea_array) > 1)
                		array_splice($managementArea_array,0,0,[0=>'全部']);
					if(isset($_GET[\Yii::$app->controller->id.'Search']['management_area']))
						$management_area = $_GET[\Yii::$app->controller->id.'Search']['management_area'];
					else 
						$management_area = User::searchManagearea();
// 					var_dump($management_area);
                ?>
<table class="table table-hover">
  <tr>

    <script>
// 		$('#management_area').change(function () {
// 			$('select[name="farmsSearch[management_area]"]').val('123');
// 		});
    </script>
    <td align="right">选项</td><?php //var_dump(MenuToUser::getUserSearch());exit;?>
    <td><?php echo html::dropDownList('tab',$tab,MenuToUser::getUserSearch(),['class'=>'form-control','id'=>'tablename'])?></td>
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
					'minView' => 2,
					'maxView' => 4,
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
					'minView' => 2,
					'maxView' => 4,
				]
			]);?></td>
    <td>止</td>
    <td><?= html::submitButton('查询',['class'=>'btn btn-success','disabled'=>'disabled','id'=>'searchButton'])?></td>
  </tr>
</table>
					<div id="dialogWait" title="正在生成数据...">
						<?= Html::img('images/wait.gif')?>
					</div>
<?php ActiveFormrdiv::end(); ?>
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



					<script>
						$( "#dialogWait" ).dialog({
							autoOpen: false,
							width: 300,
							open: function (event, ui) {
								$(".ui-dialog-titlebar-close", $(this).parent()).hide();
							}
						});
//						$('#searchButton').click(function(){
//							$( "#dialogWait" ).dialog( "open" );
//						});
// 						window.onbeforeunload=function (){
// //							alert("===onbeforeunload===");
// 							if(event.clientX>document.body.clientWidth && event.clientY < 0 || event.altKey){
// //								alert("你关闭了浏览器");
// 							}else{
// 								$( "#dialogWait" ).dialog( "open" );
// 							}
// 						}
//						$("form").submit(function(e){
//							$( "#dialogWait" ).dialog( "open" );
//						});
					</script>