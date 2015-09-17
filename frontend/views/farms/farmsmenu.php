<?php
/* @var $this yii\web\View */
use app\models\ManagementArea;
use yii\helpers\Html;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Theyear;
use yii\widgets\ActiveFormrdiv;
use yii\helpers\ArrayHelper;
use app\models\Farms;
use app\models\Farmer;
use app\models\Cooperative;
use yii\helpers\Url;
use app\models\Lease;
use app\models\Dispute;
use app\models\CooperativeOfFarm;
use app\models\Employee;
use app\models\Plantingstructure;
use app\models\Fireprevention;
use app\models\Plantinputproduct;
use app\models\Plantpesticides;
?>

<div class="farms-menu">
<?php $form = ActiveFormrdiv::begin(); ?>
    <style>
        .info-box-content {
            width: 160px;

        }
    </style>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        业务办理
                    </h3>
                </div>
                <div class="box-body">
	<table class="table table-bordered table-hover">
      <tr>
        <td width="128" align="right">管理区：</td>
        <td width="193" align="left"><?= ManagementArea::find()->where(['id'=>$farm->management_area])->one()['areaname']?></td>
        <td width="92" align="right">农场名称：</td>
        <td width="278" align="left"><?= $farm->farmname?></td>
      </tr>
      <tr>
        <td align="right">审批年度：</td>
        <td align="left">&nbsp;
          <?= $farm->spyear;?></td>
        <td align="right">面积：</td>
        <td align="left">&nbsp;
          <?= $farm->measure.'亩'?></td>
      </tr>
      <tr>
        <td align="right">宗地：</td>
        <td colspan="3" align="left">&nbsp;
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
    <td width="20%" align="center">
    <!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <a href='<?= Url::to('index.php?r=farmer/farmercreate&farms_id='.$_GET['farms_id']) ?>'><span class="info-box-icon"><i class="fa fa-user"></i></span></a>
  <div class="info-box-content">
  <span class="info-box-number">承包 </span>
    <span class="info-box-text">法人：<?= Farmer::find()->where(['farms_id'=>$_GET['farms_id']])->one()['farmername'] ?></span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
      填写法人相关信息
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></td>
    <td width="5%" align="center"></td>
    <td width="20%" align="center"><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <a href='<?= Url::to('index.php?r=farmer/farmercreate&farms_id='.$_GET['farms_id']) ?>'><span class="info-box-icon"><i class="fa fa-users"></i></span></a>
  <div class="info-box-content">
  <span class="info-box-number">转让 </span>
    <span class="info-box-text">无转让信息</span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
      转让、分户、合并
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></td>
    <td width="5%" align="center">&nbsp;</td>
    <td width="20%" align="center"><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <a href='<?= Url::to('index.php?r=lease/leaseindex&farms_id='.$_GET['farms_id']) ?>'><span class="info-box-icon"><i class="fa fa-street-view"></i></span></a>
  <div class="info-box-content">
  <span class="info-box-number">租赁 </span>
    <span class="info-box-text">现共<?= Lease::find()->where(['farms_id'=>$_GET['farms_id']])->count(); ?>人租赁</span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
      承租人信息及年限
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></td>
    <td width="5%" align="center">&nbsp;</td>
    <td width="20%" align="center"><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <a href='<?= Url::to('index.php?r=lease/leaseindex&farms_id='.$_GET['farms_id']) ?>'><span class="info-box-icon"><i class="fa fa-university"></i></span></a>
  <div class="info-box-content">
  <span class="info-box-number">贷款 </span>
    <span class="info-box-text">现无贷款</span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     贷款相关信息
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><?php //Html::submitButton('',['style'=>'background:url("images/User With Frame.png") no-repeat; width:150px;height:150px;',])
        
        ?>
    <td width="20%" align="center"><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <a href='<?= Url::to('index.php?r=dispute/disputeindex&farms_id='.$_GET['farms_id']) ?>'><span class="info-box-icon"><i class="fa fa-commenting"></i></span></a>
  <div class="info-box-content">
  <span class="info-box-number">纠纷 </span>
    <span class="info-box-text">现有<?= Dispute::find()->where(['farms_id'=>$_GET['farms_id']])->count() ?>个纠纷</span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     纠纷具体事项
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></td>
    <td width="5%" align="center">&nbsp;</td>    
    <td width="20%" align="center"><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <a href='<?= Url::to('index.php?r=cooperativeoffarm/cooperativeoffarmindex&farms_id='.$_GET['farms_id']) ?>'><span class="info-box-icon"><i class="fa fa-briefcase"></i></span></a>
  <div class="info-box-content">
  <span class="info-box-number">合作社信息 </span>
    <span class="info-box-text">参加了<?= Cooperativeoffarm::find()->where(['farms_id'=>$_GET['farms_id']])->count() ?>个合作社</span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     注册资金等信息
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></td>
    <td width="5%" align="center">&nbsp;</td>
    <td width="20%" align="center"><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <a href='<?= Url::to('index.php?r=employee/employeefathers&farms_id='.$_GET['farms_id']) ?>'><span class="info-box-icon"><i class="fa fa-user-plus"></i></span></a>
  <div class="info-box-content">
  <span class="info-box-number">雇工 </span>
    <span class="info-box-text">雇佣了<?php $lease = Lease::find()->where(['farms_id'=>$_GET['farms_id']])->all();$rows=0;foreach($lease as $value) {$rows += Employee::find()->where(['father_id'=>$value['id']])->count();}echo $rows ?>人</span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     雇佣人员的详细信息
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></td>
    <td width="5%" align="center">&nbsp;</td>
    <td width="20%" align="center"><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <a href='<?= Url::to('index.php?r=plantingstructure/plantingstructureindex&farms_id='.$_GET['farms_id']) ?>'><span class="info-box-icon"><i class="fa fa-sun-o"></i></span></a>
  <div class="info-box-content">
  <span class="info-box-number">种植结构 </span>
    <span class="info-box-text">种植了<?= Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id']])->count()?>种作物</span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     承租人种植作物信息
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></td>
  </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><?php //Html::submitButton('',['style'=>'background:url("images/User With Frame.png") no-repeat; width:150px;height:150px;',])
        
        ?>
    <td width="20%" align="center"><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <a href='<?= Url::to('index.php?r=plantinputproduct/plantinputproductindex&farms_id='.$_GET['farms_id']) ?>'><span class="info-box-icon"><i class="fa fa-tint"></i></span></a>
  <div class="info-box-content">
  <span class="info-box-number">投入品 </span>
    <span class="info-box-text">投放了<?= Plantinputproduct::find()->where(['farms_id'=>$_GET['farms_id']])->count()?>种投入品</span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     农场投入品信息
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></td>
    <td width="5%" align="center"></td>    
    <td width="20%" align="center"><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <a href='<?= Url::to('index.php?r=plantpesticides/plantpesticidesindex&farms_id='.$_GET['farms_id']) ?>'><span class="info-box-icon"><i class="fa fa-exclamation-triangle"></i></span></a>
  <div class="info-box-content">
  <span class="info-box-number">农药 </span>
    <span class="info-box-text">使用了<?= Plantpesticides::find()->where(['farms_id'=>$_GET['farms_id']])->count()?>种农药</span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     农药使用情况
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></td>
    <td width="5%" align="center"></td>
    <td width="20%" align="center"><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <a href='<?= Url::to('index.php?r=fireprevention/firepreventioncreate&farms_id='.$_GET['farms_id']) ?>'><span class="info-box-icon"><i class="fa fa-fire-extinguisher"></i></span></a>
  <div class="info-box-content">
  <span class="info-box-number">防火工作 </span>
    <span class="info-box-text"><?php if(Fireprevention::find()->where(['farms_id'=>$_GET['farms_id']])->count()) echo '完成防火工作'; else echo '未完成防火工作';?></span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     防火合同的签订
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></td>

      <td width="5%" align="center"></td>
      <td width="20%" align="center"><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-ban"></i></span>
  <div class="info-box-content">
  <span class="info-box-number"></span>
    <span class="info-box-text"></span>
    <!-- The progress section is optional -->

    <span class="progress-description">
     
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></td>
  </tr>
  </table>

 <?php ActiveFormrdiv::end(); ?>
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
              </div>
            </div>
        </div>
    </div>
</section>

</div>


