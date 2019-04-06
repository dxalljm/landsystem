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
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */

?>
<style type="text/css">
	.ttpoprint {
		font-family: "仿宋";
		font-size:20px;
		/*border-color: #000000;*/
		/*height:"600px";*/
	}
	.tablehead{ width:100%; height:30px; line-height:20px; text-align:center; float:left; font-size:50px; font-family:"黑体"}
	.tablehead2{ width:100%; height:30px; line-height:20px; text-align:left; float:left; font-size:20px; font-family:"黑体"}
</style>
<div class="reviewprocess-form">


    <?php $form = ActiveFormrdiv::begin(); ?>

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<br>
						<div class="tablehead"><?= date('Y')?>年农业机械购置补贴申请表</div>
					<div class="box-body">
						<div class="tablehead2">岭南管委会&nbsp;——&nbsp;<span style="font-size: 15px;"> <?= ManagementArea::getAreanameOne($model->management_area)?></span></div>
             <table width="100%" class="ttpoprint" border="1">
			  <tr>
			    <td width="10%" height="50px" align="center"><strong>姓名</strong></td>
			    <td align="center"><?= $model->farmername?></td>
			    <td align="center"><strong>年龄</strong></td>
			    <td align="center"><?= $model->age?></td>
			    <td width="8%" colspan="2" align="center"><strong>性别</strong></td>
			    <td align="center" colspan="2"><?= $model->sex?></td>
			    </tr>
			  <tr>
			    <td height="50px" align="center" width="25%"><strong>户籍所在地</strong></td>
			    <td colspan="9" align="left">&nbsp;&nbsp;<?= $model->domicile?></td>
			    </tr>
			  <tr>
			    <td height="50px" align="center"><strong>身份证号</strong></td>
			    <td align="center" colspan="2"><?= $model->cardid?></td></td>
			    <td align="center"><strong>联系电话</strong></td>
			    <td align="center" colspan="6"><?= $model->telephone?></td></td>
			  </tr>
				 <tr>
					 <td height="50px" align="center"><strong>机具名称</strong></td>
					 <td align="center" colspan="2"><?= $machine->productname?></td>
					 <td align="center"><strong>分档名称</strong></td>
					 <td align="center" colspan="6"><?= $machine->filename?></td>

				 </tr>
				 <tr>
					 <td height="50px" align="center"><strong>规格型号</strong></td>
					 <td align="center" colspan="2"><?= $machine->implementmodel?></td>
					 <td align="center"><strong>生产厂家</strong></td>
					 <td align="center" colspan="6"><?= $machine->enterprisename?></td>
				 </tr>
				 <tr>
					 <td height="50px" align="center"><strong>补贴额度(元)</strong></td>
					 <td align="left" colspan="8">&nbsp;&nbsp;<?= $subsidymoney?></td>
				 </tr>
			</table>
				<?= $form->field($model,'subsidymoney')->hiddenInput()->label(false)->error(false);?>
<br>
			<div class="form-group">
				<?= Html::submitButton('确认申请表', ['class' => 'btn btn-primary']) ?>
				<?= Html::a('撤消',Url::to(['machineapply/machineapplycacle','id'=>$_GET['apply_id']]) ,['class' => 'btn btn-danger']) ?>
			</div>

    <?php ActiveFormrdiv::end(); ?>
					</div>

					</div>

				</div>
		</div>
</div>
</div>
</section>
</div>