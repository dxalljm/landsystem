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
use app\models\Collection;
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
	<table width="600px" class="table table-bordered table-hover">
      <tr>
        <td align="right">管理区：</td>
        <td align="left"><?= ManagementArea::find()->where(['id'=>$farm->management_area])->one()['areaname']?></td>
        <td align="right">农场名称：</td>
        <td align="left"><?= $farm->farmname?></td>
        <td rowspan="4" align="left"><?php echo '&nbsp;'.Html::img(Farmer::find()->where(['farms_id'=>$farm->id])->one()['photo'],['height'=>'130px']);?></td>
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
    <td width="20%" align="center"><a href='<?= Url::to('index.php?r=farmer/farmercreate&farms_id='.$_GET['farms_id']) ?>'>
    <!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-user"></i></span>
  <div class="info-box-content">
  <span class="info-box-number">法人信息 </span>
    <span class="info-box-text">法人：<?= $farm->farmername ?></span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
      填写法人相关信息
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></a></td>
    <td width="5%" align="center"></td>
    <td width="20%" align="center"><a href='<?= Url::to('index.php?r=farms/farmsttpomenu&farms_id='.$_GET['farms_id']) ?>'>
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-users"></i></span>
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
</div><!-- /.info-box --></a></td>
    <td width="5%" align="center">&nbsp;</td>
    <td width="20%" align="center"><a href='<?= Url::to('index.php?r=lease/leaseindex&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-street-view"></i></span>
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
</div><!-- /.info-box --></a></td>
    <td width="5%" align="center">&nbsp;</td>
    <td width="20%" align="center"> <a href='<?= Url::to('index.php?r=lease/leaseindex&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
 <span class="info-box-icon"><i class="fa fa-university"></i></span>
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
</div><!-- /.info-box --></a></td>
    </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><?php //Html::submitButton('',['style'=>'background:url("images/User With Frame.png") no-repeat; width:150px;height:150px;',])
        
        ?>
    <td width="20%" align="center"><a href='<?= Url::to('index.php?r=dispute/disputeindex&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-commenting"></i></span>
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
</div><!-- /.info-box --></a></td>
    <td width="5%" align="center">&nbsp;</td>    
    <td width="20%" align="center"> <a href='<?= Url::to('index.php?r=cooperativeoffarm/cooperativeoffarmindex&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
 <span class="info-box-icon"><i class="fa fa-briefcase"></i></span>
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
</div><!-- /.info-box --></a></td>
    <td width="5%" align="center">&nbsp;</td>
    <td width="20%" align="center"> <a href='<?= Url::to('index.php?r=employee/employeefathers&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
 <span class="info-box-icon"><i class="fa fa-user-plus"></i></span>
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
</div><!-- /.info-box --></a></td>
    <td width="5%" align="center">&nbsp;</td>
    <td width="20%" align="center"> <a href='<?= Url::to('index.php?r=plantingstructure/plantingstructureindex&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
 <span class="info-box-icon"><i class="fa fa-sun-o"></i></span>
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
</div><!-- /.info-box --></a></td>
  </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><?php //Html::submitButton('',['style'=>'background:url("images/User With Frame.png") no-repeat; width:150px;height:150px;',])
        
        ?>
    <td width="20%" align="center"><a href='<?= Url::to('index.php?r=fireprevention/firepreventioncreate&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-fire-extinguisher"></i></span>
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
</div><!-- /.info-box --></a></td>
    <td width="5%" align="center"></td>    
    <td width="20%" align="center"><a href='<?= Url::to('index.php?r=Collection/Collectiondck&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-cny"></i></span>
  <div class="info-box-content">
  <span class="info-box-number">缴费业务</span>
    <span class="info-box-text"></span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     防火合同的签订
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></a></td>
    <td width="5%" align="center"></td>
    <td width="20%" align="center"><a href='<?= Url::to('index.php?r=yields/yieldsindex&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-balance-scale"></i></span>
  <div class="info-box-content">
  <span class="info-box-number">产品产量信息</span>
    <span class="info-box-text"></span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     农产品的产量信息
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></a></td>

      <td width="5%" align="center"></td>
      <td width="20%" align="center"><a href='<?= Url::to('index.php?r=sales/salesindex&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-cart-arrow-down"></i></span>
  <div class="info-box-content">
  <span class="info-box-number">农产品销售信息</span>
    <span class="info-box-text"></span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     农产品的销售情况
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></a></td>
  </tr>
  </table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><?php //Html::submitButton('',['style'=>'background:url("images/User With Frame.png") no-repeat; width:150px;height:150px;',])
        
        ?>
    <td width="20%" align="center"><a href='<?= Url::to('index.php?r=breed/breedcreate&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-fire-extinguisher"></i></span>
  <div class="info-box-content">
  <span class="info-box-number">养殖情况 </span>
    <span class="info-box-text"><?php if(Fireprevention::find()->where(['farms_id'=>$_GET['farms_id']])->count()) echo '完成防火工作'; else echo '未完成防火工作';?></span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     养殖相关的
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></a></td>
    <td width="5%" align="center"></td>    
    <td width="20%" align="center"><a href='<?= Url::to('index.php?r=Collection/Collectiondck&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-cny"></i></span>
  <div class="info-box-content">
  <span class="info-box-number">缴费业务</span>
    <span class="info-box-text"></span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     防火合同的签订
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></a></td>
    <td width="5%" align="center"></td>
    <td width="20%" align="center"><a href='<?= Url::to('index.php?r=yields/yieldsindex&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-balance-scale"></i></span>
  <div class="info-box-content">
  <span class="info-box-number">产品产量信息</span>
    <span class="info-box-text"></span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     农产品的产量信息
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></a></td>

      <td width="5%" align="center"></td>
      <td width="20%" align="center"><a href='<?= Url::to('index.php?r=sales/salesindex&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-cart-arrow-down"></i></span>
  <div class="info-box-content">
  <span class="info-box-number">农产品销售信息</span>
    <span class="info-box-text"></span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     农产品的销售情况
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></a></td>
  </tr>
  </table>
  <table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr><?php //Html::submitButton('',['style'=>'background:url("images/User With Frame.png") no-repeat; width:150px;height:150px;',])
        
        ?>
    <td width="20%" align="center"><a href='<?= Url::to('index.php?r=fireprevention/firepreventioncreate&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-fire-extinguisher"></i></span>
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
</div><!-- /.info-box --></a></td>
    <td width="5%" align="center"></td>    
    <td width="20%" align="center"><a href='<?= Url::to('index.php?r=Collection/Collectiondck&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-cny"></i></span>
  <div class="info-box-content">
  <span class="info-box-number">缴费业务</span>
    <span class="info-box-text"></span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     防火合同的签订
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></a></td>
    <td width="5%" align="center"></td>
    <td width="20%" align="center"><a href='<?= Url::to('index.php?r=yields/yieldsindex&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-balance-scale"></i></span>
  <div class="info-box-content">
  <span class="info-box-number">产品产量信息</span>
    <span class="info-box-text"></span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     农产品的产量信息
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></a></td>

      <td width="5%" align="center"></td>
      <td width="20%" align="center"><a href='<?= Url::to('index.php?r=sales/salesindex&farms_id='.$_GET['farms_id']) ?>'><!-- Apply any bg-* class to to the info-box to color it -->
<div class="info-box bg-blue">
  <span class="info-box-icon"><i class="fa fa-cart-arrow-down"></i></span>
  <div class="info-box-content">
  <span class="info-box-number">农产品销售信息</span>
    <span class="info-box-text"></span>
    <!-- The progress section is optional -->
    <div class="progress">
      <div class="progress-bar" style="width: 100%"></div>
    </div>
    <span class="progress-description">
     农产品的销售情况
    </span>
  </div><!-- /.info-box-content -->
</div><!-- /.info-box --></a></td>
  </tr>
  </table>
 <?php ActiveFormrdiv::end(); ?>
              </div>
            </div>
        </div>
    </div>
</section>
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


