<?php
/* @var $this yii\web\View */
namespace frontend\controllers;
use app\models\Indexecharts;
use app\models\Theyear;
use frontend\models\electronicarchivesSearch;
use Yii;
use yii\helpers\Html;
use app\models\Farms;
use yii\helpers\Url;
use app\models\User;
use app\models\Department;
use app\models\ManagementArea;
use frontend\helpers\arraySearch;
use app\models\Plantingstructure;
use app\models\Cache;
use app\models\MenuToUser;
use app\models\Projectapplication;
use frontend\helpers\MacAddress;
use frontend\helpers\ActiveFormrdiv;
use app\models\Insurance;
use app\models\Collectionsum;
$this->title = '岭南管委会';
?>
<style>

    .col-md-1-5 {
        width: 20%;
        float: left;
    }
    .col-xs-1-5,.col-sm-1-5,.col-md-1-5,.col-lg-1-5 {
        min-height: 1px;
        padding-left: 15px;
        padding-right: 15px;
        position: relative;
    }
    *{margin:0;padding:0;list-style-type:none;}

</style>
<!-- Content Header (Page header) -->
<div class="container-fluid" id="echarts">
	<?php
//	var_dump('测试');
	?>
  <?php
//Theyear::newYear();
  $plate = User::getPlate()['id'];?>
<div class="row">
	<div id="onelup">
		<?php echo Indexecharts::showEcharts(20,'onel');?>
	</div>
	<div id="onemup">
		<?php echo Indexecharts::showEcharts(Indexecharts::getEchartsID('onem'),'onem');?>
	</div>
	<div id="onerup">
		<?php echo Indexecharts::showEcharts(Indexecharts::getEchartsID('oner'),'oner');?>
	</div>

</div>
	<div class="row">
		<div id="twolup"><?php echo Indexecharts::showEcharts(Indexecharts::getEchartsID('twol'),'twol');?></div>
		<div id="twomup"><?php echo Indexecharts::showEcharts(Indexecharts::getEchartsID('twom'),'twom');?></div>
		<div id="tworup">
			<?php echo Indexecharts::showEcharts(Indexecharts::getEchartsID('twor'),'twor');?>
		</div>
	</div>
<?= Farms::showEightPlantmenu()?>

</div>

<div id="dialog" title="密码不能为'111111',必须重新修改密码。">
	<table width=100%>
		<tr>
			<td align="right">新密码：</td>
			<td><?= html::textInput('password','',['id'=>'user-password','type'=>'password','class'=>'form-control'])?></td>
		</tr>
		<tr>
			<td align="right">&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="right">密码确认：</td>
			<td><?= html::textInput('password_again','',['id'=>'user-passwordagain','type'=>'password','class'=>'form-control'])?></td>
		</tr>
	</table>
</div>
<!-- Main content -->
<?php
//echo strtotime(date('2017-10-13'));
$user = User::findOne(Yii::$app->getUser()->id);
if($user->passwordshow == '' or $user->passwordshow == '111111') {
	echo Html::hiddenInput('isPassword',1,['id'=>'is-password']);	
} else
	echo Html::hiddenInput('isPassword',0,['id'=>'is-password']);	
//echo Indexecharts::showScript('20','onel');
////var_dump(Indexecharts::getEchartsID('onem'));
//if(Indexecharts::getEchartsID('onem')){
//	echo Indexecharts::showScript(Indexecharts::getEchartsID('onem'), 'onem');
//}
//if(Indexecharts::getEchartsID('oner')) {
//	echo Indexecharts::showScript(Indexecharts::getEchartsID('oner'),'oner');
//}
//if(Indexecharts::getEchartsID('twol')) {
//	echo Indexecharts::showScript(Indexecharts::getEchartsID('twol'),'twol');
//}
//if(Indexecharts::getEchartsID('twom')) {
//	echo Indexecharts::showScript(Indexecharts::getEchartsID('twom'),'twom');
//}
//if(Indexecharts::getEchartsID('twor')) {
//	echo Indexecharts::showScript(Indexecharts::getEchartsID('twor'),'twor');
//}
?>
<script type="text/javascript">
// var s = statis();
// s.farms();
// s.area();
// s.payment();
function refresh(id,address) {
	$.getJSON("<?= Url::to(['indexecharts/refresh'])?>", {id: id,address:address}, function (data) {
		$("#"+address+"up").html(data);
	});
}

$( "#dialog" ).dialog({
	autoOpen: false,
	modal: true,
	width: 400,
	bgiframe: true,
	closeOnEscape: false,
	buttons: [
		{
			text: "确定",
			click: function() { 
// 				$( this ).dialog( "close" );
				var password = $('#user-password').val();
				var passwordagain = $('#user-passwordagain').val()
				if(password != '' && passwordagain != '') {
					if(password != passwordagain) {
						alert('两次输入的密码不一致，请重新输入');
						$('#user-passwordagain').focus();
					} else {
						$.get("<?= Url::to(['user/modfiypassword'])?>", {password: $('#user-password').val()}, function (data){
							alert('密码修改成功');
							$( "#dialog" ).dialog( "close" );
						});
					}
				} else
					alert('密码不能为空');
			}				
		},
// 		{
// 			text: "取消",
// 			click: function() {
// 				$( this ).dialog( "close" );
// 			}
// 		}
	]
});

$(function(){
	if($('#is-password').val() == 1) {
		$( "#dialog" ).dialog( "open" );
	}
});
$('div .ui-dialog-titlebar-close').hide();
$('.shclDefault').shCircleLoader({color: "red"});


</script>
