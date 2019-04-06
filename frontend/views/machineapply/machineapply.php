<?php
namespace frontend\controllers;
use app\models\Insurancecompany;
use frontend\helpers\datetozhongwen;
use yii\helpers\Html;
use frontend\helpers\ActiveFormrdiv;
use app\models\Tables;
use app\models\Tablefields;
use app\models\Farms;
use app\models\Insurancedck;
use app\models\User;
use app\models\ManagementArea;
/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */

?>
<style type="text/css">
	.ttpoprint {
		font-family: "仿宋";
		font-size:20px;
		height:"600px";
	}
	.tablehead{ width:100%; height:30px; line-height:20px; text-align:center; float:left; font-size:30px; font-family:"黑体"}
</style>
<div class="reviewprocess-form">


    <?php $form = ActiveFormrdiv::begin(); ?>

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
			
        <div class="form-group">

          <!-- Box Comment -->
          <div class="box box-widget">
            <div class="box-header with-border">
              <div class="tablehead"><?= date('Y')?>年农业机械购置补贴申请表</div>
            </div>
            <br>
            <!-- /.box-header -->
            <div class="box-body">
            <div class="box-header">岭南管委会</div>
             <table class="table table-bordered table-hover">
			  <tr>
			    <td width="10%" align="center">姓名</td>
			    <td  align="center"><?= $farm->farmername?></td>
			    <td align="center">年龄</td>
			    <td align="center"><?= Farms::getAge($farm['cardid'])?></td>
			    <td width="8%" colspan="2" align="center">性别</td>
			    <td align="center" colspan="2"><?= $farmer['gender']?></td>
			    </tr>
			  <tr>
			    <td align="center" width="25%">地址(身份证地址)</td>
			    <td colspan="9" align="left"><?php echo $form->field($farmer,'domicile')->textInput()->label(false)?></td>
			    </tr>
			  <tr>
				  <td align="center">农场所在管理区</td>
				  <td colspan="9" align="left"><?= ManagementArea::getAreanameOne($farm['management_area'])?></td></td>
			    </tr>
			  <tr>
			    <td align="center">身份证号</td>
			    <td align="center" colspan="2"><?php echo $form->field($farm,'cardid')->textInput()->label(false)?></td></td>
			    <td align="center">联系电话</td>
			    <td align="center" colspan="6"><?php echo $form->field($farm,'telephone')->textInput()->label(false)?></td></td>
			  </tr>
				 <tr>
					 <td align="center">机具名称</td>
					 <td align="center" colspan="2"></td>
					 <td align="center">生产厂家</td>
					 <td align="center" colspan="6"></td>
				 </tr>
				 <tr>
					 <td align="center">规格型号</td>
					 <td align="center" colspan="2"></td>
					 <td align="center">补贴额度(元)</td>
					 <td align="center" colspan="6"></td>
				 </tr>
			  <tr>
			    <td align="center">购机者签字</td>
			    <td colspan="9" align="left" valign="top">
			  </tr>
				 <tr>
					 <td align="center">地产组签字</td>
					 <td colspan="9" align="left" valign="top">
				 </tr>
				 <tr>
					 <td align="center">项目科签字</td>
					 <td colspan="9" align="left" valign="top">
				 </tr>
				 <tr>
					 <td align="center">分管领导签字</td>
					 <td colspan="9" align="left" valign="top">
				 </tr>
			</table>
				<span class="text text-left ttpoprint">购机者签字前确认知晓补贴政策,并已仔细阅读政策告知确认表。</span>
			<br>
			
            </div>
            <!-- /.box-body -->
           
          </div>
          <!-- /.box -->
        <!-- /.col -->

			<div class="form-group">
				<?= Html::submitButton($model->isNewRecord ? '申请' : '申请', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','disabled'=>true,'id'=>'submitButton']) ?>
			</div>
			
    <?php ActiveFormrdiv::end(); ?>
<!--                 </div> -->
<!--             </div> -->
        </div>
    </div>
</section>
</div>