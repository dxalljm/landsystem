<?php
/* @var $this yii\web\View */
use app\models\ManagementArea;
use yii\helpers\Html;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Theyear;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Farms;


?>

<div class="farms-menu">
<?php $form = ActiveFormrdiv::begin(); ?>
<br><br><br>
	<table class="table table-striped table-bordered table-hover table-condensed">
      <tr>
        <td width="128" align="right">管理区：</td>
        <td width="193" align="left">&nbsp;
        <?= html::dropDownList('managementarea',$areaid,ArrayHelper::map(ManagementArea::find()->all(),'id','areaname'),['id'=>'managementarea'])?>           </td>
        <td width="92" align="right">农场名称：</td>
        <td width="278" align="left">&nbsp;<?= html::dropDownList('farmname',$farm->id,ArrayHelper::map(Farms::find()->where(['management_area'=>$areaid])->all(),'id','farmname'),['id'=>'farmname','prompt'=>'请选择'])?>        </td>
      </tr>
      <tr>
        <td align="right">合作社</td>
        <td align="left">&nbsp;
          <?= $farm->cooperative_id;?></td>
        <td align="right">审批年度：</td>
        <td align="left">&nbsp;
          <?= $farm->spyear;?></td>
      </tr>
      <tr>
        <td align="right">面积：</td>
        <td align="left">&nbsp;
          <?= $farm->measure.'亩'?></td>
        <td align="right">宗地：</td>
        <td align="left">&nbsp;
          <?= $farm->zongdi;?></td>
      </tr>
      <tr>
        <td align="right">农场位置：</td>
        <td colspan="3" align="left">&nbsp;
          <?= $farm->address;?></td>
      </tr>
    </table>
    <script type="text/javascript">
    
	function geturl(farmid)
	{
		url = $.get('index.php',{r:/farmer/farmerview,id:farmid,year:$('#theyear-years').val});
		return url;
	}
    </script>
  <p>&nbsp;</p>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><?php //Html::submitButton('',['style'=>'background:url("images/User With Frame.png") no-repeat; width:150px;height:150px;',])
        
        ?>
    <td width="20%" align="center"><?=  Html::a(html::img('images/User With Frame.png',['width'=>'150']),'index.php?r=farmer/farmercreate&id='.$farm->id, [
            			'id' => 'farmerview',
            			'title' => '承包信息',
            			'target' => '_blank',
            			//'data-toggle' => 'modal',
            			//'data-target' => '#farmercreate-modal',
            			//'onclick' => "javascript:window.open('".yii::$app->urlManager->createUrl(['/farmer/farmercreate','id'=>$farm->id])."','','width=1200,height=1000,top=150,left=180, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",
            			//'onclick' => 'farmercreate($("#farmname").val())',
             	]);?></td>
    <td width="20%" align="center"><?=  Html::a(html::img('images/farmerzr.png',['width'=>'150']),'#', [
            			'id' => 'farmerview',
            			'title' => '转让信息',
            			'target' => '_blank',
            			'data-toggle' => 'modal',
            			'data-target' => '#leaseindex-modal',
            			'onclick' => 'leaseindex($("#farmname").val())',
             	]);?></td>
    <td width="20%" align="center"><?=  Html::a(html::img('images/farmerzp.png',['width'=>'150']),'index.php?r=lease/leaseindex&farms_id='.$farm->id, [
            			'id' => 'leaseview',
            			'title' => '租赁信息',
            			'target' => '_blank',
            			/* 'data-toggle' => 'modal',
            			'data-target' => '#leaseindex-modal',
            			'onclick' => 'leaseindex($("#farmname").val())', */
             	]);?></td>
    <td width="20%" align="center"><?=  Html::a(html::img('images/farmerdk.png',['width'=>'150']),'#', [
            			'id' => 'farmerview',
            			'title' => '抵押贷款信息',
            			'target' => '_blank',
            			'onclick' => "javascript:window.open('".yii::$app->urlManager->createUrl(['/lease/leaseindex','id'=>$farm->id,'year'=>$year])."','','width=1200,height=600,top=50,left=80, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",
             	]);?></td>
    <td width="20%" align="center"><?=  Html::a(html::img('images/farmerjf.png',['width'=>'150']),'#', [
            			'id' => 'farmerview',
            			'title' => '承包经营权纠纷',
            			'target' => '_blank',
            			'onclick' => "javascript:window.open('".yii::$app->urlManager->createUrl(['/farmer/farmerview','id'=>$farm->id])."','','width=1200,height=600,top=50,left=80, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",
             	]);?></td>
  </tr>
  </table>
  <br>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><?php //Html::submitButton('',['style'=>'background:url("images/User With Frame.png") no-repeat; width:150px;height:150px;',])
        
        ?>
    <td width="20%" align="center"><?=  Html::a('合作社管理','index.php?r=cooperative/cooperativeindex&farms_id='.$farm->id, [
            			'id' => 'farmerview',
            			'title' => '承包信息',
            			'target' => '_blank',
            			//'data-toggle' => 'modal',
            			//'data-target' => '#farmercreate-modal',
            			//'onclick' => "javascript:window.open('".yii::$app->urlManager->createUrl(['/farmer/farmercreate','id'=>$farm->id])."','','width=1200,height=1000,top=150,left=180, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",
            			//'onclick' => 'farmercreate($("#farmname").val())',
             	]);?></td>
    <td width="20%" align="center"><?=  Html::a('合作社信息','index.php?r=cooperativeoffarm/cooperativeoffarmindex&farms_id='.$farm->id, [
            			'id' => 'farmerview',
            			'title' => '合作社信息',
            			'target' => '_blank',

             	]);?></td>    
    <td width="20%" align="center"><?=  Html::a('雇工信息','index.php?r=employee/employeefathers&farms_id='.$farm->id, [
            			'id' => 'employeefathers',
            			'title' => '雇工信息',
            			'target' => '_blank',
            			
             	]);?></td>
    <td width="20%" align="center"><?=  Html::a('防火工作','index.php?r=fireprevention/firepreventioncreate&farms_id='.$farm->id, [
            			'id' => 'firepreventioncreate',
            			'title' => '防火工作',
            			'target' => '_blank',
            			
             	]);?></td>
    <td width="20%" align="center"><?=  Html::a('种值结构','index.php?r=plant/plantindex&farms_id='.$farm->id, [
            			'id' => 'plantindex',
            			'title' => '种值结构',
            			'target' => '_blank',
            			
             	]);?></td>
  </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><?php //Html::submitButton('',['style'=>'background:url("images/User With Frame.png") no-repeat; width:150px;height:150px;',])
        
        ?>
    <td width="20%" align="center"><?=  Html::a('宗地管理','index.php?r=parcel/parcelindex&farms_id='.$farm->id, [
            			'id' => 'parcelindex',
            			'title' => '宗地管理',
            			'target' => '_blank',
            			//'data-toggle' => 'modal',
            			//'data-target' => '#farmercreate-modal',
            			//'onclick' => "javascript:window.open('".yii::$app->urlManager->createUrl(['/farmer/farmercreate','id'=>$farm->id])."','','width=1200,height=1000,top=150,left=180, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');return false;",
            			//'onclick' => 'farmercreate($("#farmname").val())',
             	]);?></td>
    <td width="20%" align="center"><?=  Html::a('合作社信息','index.php?r=cooperativeoffarm/cooperativeoffarmindex&farms_id='.$farm->id, [
            			'id' => 'farmerview',
            			'title' => '合作社信息',
            			'target' => '_blank',

             	]);?></td>    
    <td width="20%" align="center"><?=  Html::a('雇工信息','index.php?r=employee/employeeindex&farms_id='.$farm->id, [
            			'id' => 'employeeindex',
            			'title' => '雇工信息',
            			'target' => '_blank',
            			
             	]);?></td>
    <td width="20%" align="center"><?=  Html::a('防火工作','index.php?r=fireprevention/firepreventioncreate&farms_id='.$farm->id, [
            			'id' => 'firepreventioncreate',
            			'title' => '防火工作',
            			'target' => '_blank',
            			
             	]);?></td>
    <td width="20%" align="center"><?=  Html::a('种值结构','index.php?r=plant/plantindex&farms_id='.$farm->id, [
            			'id' => 'plantindex',
            			'title' => '种值结构',
            			'target' => '_blank',
            			
             	]);?></td>
  </tr>
  </table>
 <?php ActiveFormrdiv::end(); ?>
</div>
<?php
$script = <<<JS


jQuery('#managementarea').change(function(){
    var area = $(this).val();
    $.get('/landsystem/frontend/web/index.php?r=farms/farmsmenu',{id:$("#farmname").val(),areaid:area},function (data) {
		      $('body').html(data);
		    });
});
jQuery('#farmname').change(function(){
    var farmsid = $(this).val();
    $.get('/landsystem/frontend/web/index.php?r=farms/farmsmenu',{id:farmsid,areaid:$("#managementarea").val()},function (data) {
		      $('body').html(data);
		    });
});
JS;
$this->registerJs($script);
?>
<?php \yii\bootstrap\Modal::begin([
    'id' => 'farmercreate-modal',
	'size'=>'modal-lg',
	//'header' => '<h4 class="modal-title"></h4>',
	'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">关闭</a>',

]); 

?>
<?php \yii\bootstrap\Modal::end(); ?>

<?php

\yii\bootstrap\Modal::begin([
    'id' => 'leaseindex-modal',
	'size'=>'modal-lg',
	//'header' => '<h4 class="modal-title"></h4>',
	'footer' => '<a href="#" class="btn btn-primary" id="close-leaseindex-modal" >关闭</a>',
]);
\yii\bootstrap\Modal::end();

?>
