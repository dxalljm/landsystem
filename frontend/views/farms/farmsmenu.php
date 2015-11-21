<?php
/* @var $this yii\web\View */
use app\models\ManagementArea;
use yii\helpers\Html;
use dosamigos\datetimepicker\DateTimePicker;
use app\models\Theyear;
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
use app\models\Loan;
use app\models\Breedinfo;
use app\models\Breedtype;
use app\models\Breed;
use app\models\Yields;
use app\models\Sales;
use app\models\Prevention;
?>

<div class="farms-menu">

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
    <br>
<?= $farmsmenu?>
    <script type="text/javascript">
    
	function geturl(farmid)
	{
		url = $.get('index.php',{r:/farmer/farmerview,id:farmid,year:$('#theyear-years').val});
		return url;
	}
    </script>

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


