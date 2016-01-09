<?php

use yii\helpers\Html;
use yii\widgets\ActiveFormrdiv;
use app\models\Infrastructuretype;
use frontend\widgets\CategorySelect;
/* @var $this yii\web\View */
/* @var $model app\models\Projectapplication */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="projectapplication-form">

    <?php $form = ActiveFormrdiv::begin(); ?>
<table class="table table-bordered table-hover">
		<tr>
<td width=15% align='right'>项目类型</td>
<td align='left'>
<?php

			$listData = Infrastructuretype::getAllList();

			if (!$model->isNewRecord && isset($listData[$model->id])) {
				unset($listData[$model->id]);
			}

			if (count($listData) > 0) {
				echo CategorySelect::widget([
					"model" => $model,
					'isDisableParent' => null,
					"attribute" => 'projecttype',
					'isShowFinal' => null,
					"categories" => $listData,
					'selectedValue' => $model->projecttype,
					'htmlOptions' => [
						'class' => 'form-control col-sm-5 col-lg-5',
						'name' => 'Projectapplication[projecttype]'
					]
				]);

			} else {
				echo "无可用父分类";
			}
			?>
</tr>

<tr>
<td colspan="2" >
    <div class="col-md-6" id="ttpoprint">
<style>
<!--
 /* Font Definitions */
 @font-face
	{font-family:宋体;
	panose-1:2 1 6 0 3 1 1 1 1 1;}
@font-face
	{font-family:宋体;
	panose-1:2 1 6 0 3 1 1 1 1 1;}
@font-face
	{font-family:Calibri;
	panose-1:2 15 5 2 2 2 4 3 2 4;}
@font-face
	{font-family:仿宋;
	panose-1:2 1 6 9 6 1 1 1 1 1;}
@font-face
	{font-family:"\@宋体";
	panose-1:2 1 6 0 3 1 1 1 1 1;}
@font-face
	{font-family:"\@仿宋";
	panose-1:2 1 6 9 6 1 1 1 1 1;}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0cm;
	margin-bottom:.0001pt;
	text-align:justify;
	text-justify:inter-ideograph;
	font-size:10.5pt;
	font-family:"Calibri","sans-serif";}
.MsoChpDefault
	{font-family:"Calibri","sans-serif";}
 /* Page Definitions */
 @page WordSection1
	{size:595.3pt 841.9pt;
	margin:72.0pt 90.0pt 72.0pt 90.0pt;
	layout-grid:15.6pt;}
div.WordSection1
	{page:WordSection1;}
-->
</style>    

<div class=WordSection1 style='layout-grid:15.6pt'>

<p class=MsoNormal><span lang=EN-US>&nbsp;</span></p>
<p class=MsoNormal><span lang=EN-US>&nbsp;</span></p>
<p class=MsoNormal style='text-indent:46.0pt;line-height:30.0pt'><span
style='font-size:22.0pt;font-family:仿宋;letter-spacing:0pt'>我是<?= $farm->farmername?>，农场法人<?= $farm->farmername?>，农场位于<?= $farm->address?>。</span></p><p class=MsoNormal style='text-indent:46.0pt;line-height:30.0pt'><span
style='font-size:22.0pt;font-family:仿宋;letter-spacing:0pt'><?= Html::textInput('projectapplication-content','',['size'=>46,'placeholder'=>'申请原因及申请内容','style'=>'border-bottom:black 1px solid;border-top-style:none;border-right-style:none;border-left-style:none;background-color:transparent;'])?><?= Html::textInput('projectapplication-projectdata','',['placeholder'=>'输入申请数量','style'=>'border-bottom:black 1px solid;border-top-style:none;border-right-style:none;border-left-style:none;background-color:transparent;'])?><?= Html::dropDownList('projectapplication-unit','',['公里'=>'公里','平方米'=>'平方米','个'=>'个'],['prompt'=>'请选择...','style'=>'border-bottom:black 1px solid;border-top-style:none;border-right-style:none;border-left-style:none;background-color:transparent;'])?>，我承诺严格按森林防火及林政部门要求建<?= $model->projecttype?>，不非法占用林地及破坏森林资源，如有破坏我自愿接受林政资源部门的处罚并承担一切法律责任和经济责任。</span></p>

<p class=MsoNormal style='line-height:50.0pt'><span style='font-size:22.0pt;
font-family:仿宋;letter-spacing:0pt'>房舍坐标点：<?= $farm->longitude.'&nbsp;&nbsp;&nbsp;'.$farm->latitude?></span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal style='text-indent:32.0pt'><span style='font-size:22.0pt;
font-family:仿宋'>特此申请</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;</span></p>

<p class=MsoNormal align=right style='text-align:right'><span style='font-size:22.0pt;font-family:仿宋'>申请人：&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
<p class=MsoNormal><span lang=EN-US>&nbsp;</span></p>
<p class=MsoNormal align=right style='text-align:right'><span style='font-size:22.0pt;font-family:仿宋'><?= date('Y年m月d日',time())?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></p>
<p class=MsoNormal><span lang=EN-US style='font-size:22.0pt;font-family:仿宋'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span></p>

</div>
</div>
            <!-- /.box-body -->
</td>
</tr>
</table>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '添加' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveFormrdiv::end(); ?>

</div>
