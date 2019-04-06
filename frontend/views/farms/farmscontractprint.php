<?php
namespace frontend\controllers;use app\models\User;
use yii\helpers\Html;
use frontend\helpers\MoneyFormat;
/* @var $this yii\web\View */
/* @var $model app\models\Reviewprocess */

?>

<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0> 
       <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
        <div class="form-group">
    	   	&nbsp;&nbsp;<?= Html::Button('打印', ['class' => 'btn btn-primary','onclick'=>'prn_preview4()']) ?> 			
    	</div>
<!--             <div class="box"> -->

<!--                 <div class="box-body"> -->
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
	{font-family:仿宋_GB2312;}
@font-face
	{font-family:"\@宋体";
	panose-1:2 1 6 0 3 1 1 1 1 1;}
@font-face
	{font-family:"\@仿宋_GB2312";}
 /* Style Definitions */
 p.MsoNormal, li.MsoNormal, div.MsoNormal
	{margin:0cm;
	margin-bottom:.0001pt;
	text-align:justify;
	text-justify:inter-ideograph;
	font-size:10.5pt;
	font-family:"Times New Roman","serif";}
p.MsoHeader, li.MsoHeader, div.MsoHeader
	{margin:0cm;
	margin-bottom:.0001pt;
	text-align:center;
	layout-grid-mode:char;
	border:none;
	padding:0cm;
	font-size:9.0pt;
	font-family:"Times New Roman","serif";}
p.MsoFooter, li.MsoFooter, div.MsoFooter
	{margin:0cm;
	margin-bottom:.0001pt;
	layout-grid-mode:char;
	font-size:9.0pt;
	font-family:"Times New Roman","serif";}
p.MsoAcetate, li.MsoAcetate, div.MsoAcetate
	{margin:0cm;
	margin-bottom:.0001pt;
	text-align:justify;
	text-justify:inter-ideograph;
	font-size:9.0pt;
	font-family:"Times New Roman","serif";}
 /* Page Definitions */
 @page WordSection1
	{size:515.95pt 728.6pt;
	margin:3.0cm 62.35pt 70.9pt 3.0cm;
	layout-grid:15.6pt;}
div.WordSection1
	{page:WordSection1;}
 /* List Definitions */
 ol
	{margin-bottom:0cm;}
ul
	{margin-bottom:0cm;}
-->
</style>
<div class=WordSection1 style='layout-grid:15.6pt'>

<p class=MsoNormal style='margin-right:21.0pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal style='margin-right:21.0pt;text-indent:175.0pt'><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>合同号：<u><span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $model->contractnumber?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></u></span></p>

<p class=MsoNormal align=center style='margin-right:21.0pt;text-align:center'><b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></b></p>

<p class=MsoNormal align=center style='margin-right:21.0pt;text-align:center'><b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></b></p>

<p class=MsoNormal align=center style='margin-right:21.0pt;text-align:center'><b><span
style='font-size:22.0pt;font-family:仿宋_GB2312'>宜 农 林 地 承 包 合 同</span></b></p>

<p class=MsoNormal style='margin-right:21.0pt'><b><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></b></p>

<p class=MsoNormal style='margin-right:21.0pt'><b><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></b></p>

<p class=MsoNormal style='margin-right:21.0pt;text-indent:42.0pt'><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>发<span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;
</span>包<span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp; </span>方：<u><span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;岭南生态农业示范区管理委员会&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></u></span></p>

<p class=MsoNormal style='margin-right:21.0pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal style='margin-right:21.0pt;text-indent:42.0pt'><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>发包方法定代表人：<u><span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;商兆海&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></u><span lang=EN-US>&nbsp;&nbsp;</span></span></p>

<p class=MsoNormal style='margin-right:21.0pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal style='margin-right:21.0pt;text-indent:42.0pt'><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>承<span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;
</span>包<span lang=EN-US>&nbsp;&nbsp; &nbsp;</span>方 ：<u><span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $model->farmername?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></u></span></p>

<p class=MsoNormal style='margin-right:21.0pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal style='margin-right:21.0pt;text-indent:42.0pt'><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>承包方法定代表人：<u><span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $model->farmername?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></u></span></p>

<p class=MsoNormal style='margin-right:21.0pt'><u><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'><span style='text-decoration:
 none'>&nbsp;</span></span></u></p>

<p class=MsoNormal align=center style='margin-right:21.0pt;text-align:center'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal align=center style='margin-right:21.0pt;text-align:center'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal style='margin-right:21.0pt'><img width=114 height=47
src="宜农林地承包合同文本.files/image001.gif" align=left hspace=12 alt="文本框: 监制"><span
style='font-size:12.0pt;font-family:仿宋_GB2312'>大兴安岭地区行政公署岭南生态农业示范区管理委员会 </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal style='margin-right:21.0pt'><span style='font-size:12.0pt;
font-family:仿宋_GB2312'>大兴安岭林业管理局岭南生态农业示范区管理委员会</span></p>

<p class=MsoNormal align=center style='margin-right:21.0pt;text-align:center'><b><span
lang=EN-US style='font-size:18.0pt;font-family:仿宋_GB2312'>&nbsp;</span></b></p>

<p class=MsoNormal align=center style='margin-right:21.0pt;text-align:center'><b><span
style='font-size:18.0pt;font-family:仿宋_GB2312'>宜 农 林 地 承 包 合 同</span></b></p>

<p class=MsoNormal style='margin-right:21.0pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal style='margin-right:21.0pt'><span style='font-size:14.0pt;
font-family:仿宋_GB2312'>发包方（甲方）：<u><span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;岭南生态农业示范区管理委员会&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></u></span></p>

<p class=MsoNormal style='margin-right:21.0pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span></p>

<p class=MsoNormal style='margin-right:21.0pt'><span style='font-size:14.0pt;
font-family:仿宋_GB2312'>承包方（乙方）：<u><span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $model->farmername?>&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></u></span></p>

<p class=MsoNormal style='margin-right:21.0pt'><u><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'><span style='text-decoration:
 none'>&nbsp;</span></span></u></p>

<p class=MsoNormal style='margin-right:21.0pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<b>&nbsp;</b></span><b><span style='font-size:14.0pt;font-family:仿宋_GB2312'>第一章<span
lang=EN-US>&nbsp; </span>总<span lang=EN-US>&nbsp;&nbsp;&nbsp; </span>则</span></b></p>

<p class=MsoNormal style='margin-right:21.0pt;text-indent:13.65pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第一条</span></b><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>依据与原则：</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:28.0pt'><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>甲乙双方根据国家和省有关土地、森林等法律、法规和国家林业局文件精神，本着平等互利、自愿有偿、协商一致的原则，通过共同协商，订立本合同。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:16.05pt;margin-bottom:.0001pt'><b><span style='font-size:14.0pt;
font-family:仿宋_GB2312'>第二条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>名词解释：</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;&nbsp; &nbsp;</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>宜农林地：是指有机质含量高、腐殖质层较厚，土层厚度<span
lang=EN-US>30</span>厘米以上，坡度小于<span lang=EN-US>15</span>°，可进行农业生产的林业用地。 </span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:58.8pt;margin-bottom:.0001pt;text-indent:-42.75pt'><b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>第三条<span
style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span></b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>经营权限：</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:28.0pt'><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>乙方依据本合同，承包宜农林地进行经营。宜农林地所有权属于国家，林木资源、地下资源、埋藏物和公共设施等不在宜农林地承包范围之内。乙方应按批准的宜农林地开发利用规划和本合同规定，合理利用宜农林地。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:58.8pt;margin-bottom:.0001pt;text-indent:-42.75pt'><b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>第四条<span
style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span></b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>承包地域范围：</span></p>

<p class=MsoNormal style='margin-right:21.0pt;text-indent:29.7pt'><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>本合同项下的宜农林地位于<u><span lang=EN-US>&nbsp;<?= $model->address?>&nbsp;
</span></u>，合同编号为<u><span lang=EN-US>&nbsp;<?= $model->contractnumber?>&nbsp;</span></u>，面积为（小写）<u><span
lang=EN-US>&nbsp;<?= $model->measure+$model->notclear?>&nbsp; </span></u>亩，（大写）<u><span lang=EN-US>
</span></u></span></p>

<p class=MsoNormal style='margin-right:21.0pt'><u><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;<?= MoneyFormat::big($model->measure+$model->notclear)?>&nbsp;
</span></u><span style='font-size:14.0pt;font-family:仿宋_GB2312'>亩，其位置（坐标点）及现状如本合同第三十三条附图（坐标点）所示。附图（坐标点）已经甲、乙双方及相邻单位（或个人）共同确认，签字生效。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:58.8pt;margin-bottom:.0001pt;text-indent:-42.75pt'><b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>第五条<span
style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span></b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>承包期限：</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:26.9pt'><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>本宜农林地承包经营期限为十五年，自<?php $d = explode('-',$model->begindate);echo $d[0].'年'.$d[1].'月'.$d[2].'日';?>起至<?php $d = explode('-',$model->enddate);echo $d[0].'年'.$d[1].'月'.$d[2].'日';?>止。<span
lang=EN-US> </span></span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:58.8pt;margin-bottom:.0001pt;text-indent:-42.75pt'><b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>第六条<span
style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span></b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>宜农林地用途：</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:28.0pt'><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>本合同项下的宜农林地用途仅限于种植、畜牧、养殖或营造林。如需改变上述用途，必须征得甲方同意，依照有关规定重新签订合同。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:58.8pt;margin-bottom:.0001pt;text-indent:-42.75pt'><b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>第七条<span
style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp;&nbsp;&nbsp; </span></span></b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>承包费缴（交）纳：</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:28.0pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>1</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>、岭南管委会（甲方）是行署、林管局授权收缴宜农林地承包费的唯一合法部门。代表行署、林管局收取宜农林地承包费。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:28.0pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>2</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>、甲方在承包期内按下列办法向乙方收取宜农林地承包费：承包费计算标准仍按每亩产量<span
lang=EN-US>300</span>斤小麦的<span lang=EN-US>10%</span>的标准收取，即以每亩<span lang=EN-US>30</span>斤小麦国家收购价格折算，<span
lang=EN-US>2009</span>年小麦折算价为每斤<span lang=EN-US>0.84</span>元，甲方每斤按<span
lang=EN-US>0.80</span>元折算，折算后每亩收取<span lang=EN-US>24</span>元承包费，此收费标准自<span
lang=EN-US>2010</span>年至<span lang=EN-US>2012</span>年三年内不变。三年后由小麦产量和市场价格重新折算定价，承包费以现金形式交纳。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:28.0pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>3</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>、缴费期按年度计算，自当年<span lang=EN-US>1</span>月<span
lang=EN-US>1</span>日起至<span lang=EN-US>12</span>月<span lang=EN-US>15</span>日为缴费时间，不足一年时，按一个年度计算。</span></p>

<p class=MsoNormal style='margin-right:21.0pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:28.0pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span><b><span lang=EN-US style='font-size:16.0pt;font-family:仿宋_GB2312'>&nbsp;</span></b><b><span
style='font-size:16.0pt;font-family:仿宋_GB2312'>第二章<span lang=EN-US>&nbsp; </span>甲方的权利、义务</span></b></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:28.0pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:28.1pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第八条</span></b><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>甲方本着自愿互利的原则，积极组织乙方开</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.9pt;margin-bottom:.0001pt;text-indent:-.05pt'><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>展互助合作，努力提供产前、产中、产后服务。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:27.55pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第九条</span></b><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>甲方受行署、林管局的委托，对乙方进行计划生育、护林防火、造林绿化、环境保护、住宅建设等社会性管理，政策引导。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:27.55pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第十条</span></b><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>甲方在承包期内按规定向乙方收取承包费。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:28.1pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第十一条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>合同期内，根据社会公共利益或农业开发基础设施建设的需要，如需征用全部或部分乙方承包的宜农林地，甲方可以依照法定程序提前收回，并根据乙方承包年限和实际投资给予合理补偿。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;&nbsp; </span><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第十二条</span></b><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>撂荒、弃耕两年应视为弃置，甲方无偿收回，甲方有权对该宜农林地重新发包。</span></p>

<p class=MsoNormal style='margin-right:21.0pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal align=center style='margin-top:0cm;margin-right:21.0pt;
margin-bottom:0cm;margin-left:2.6pt;margin-bottom:.0001pt;text-align:center;
text-indent:-20.55pt'><b><span style='font-size:16.0pt;font-family:仿宋_GB2312'>第三章<span
lang=EN-US>&nbsp; </span>乙方的权利、义务</span></b></p>

<p class=MsoNormal style='margin-right:21.0pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第十三条</span></b><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>乙方有权咨询国家、省、地方实行的益农惠农政策，甲方应向乙方宣传国家、省、地方实行的农业政策。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第十四条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>乙方有权享有甲方提供科技服务、气象信息等项服务的权利。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第十五条</span></b><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>承包期内，经甲方批准，乙方可将宜农林地承包经营权转租、转包、抵押、继承。转租、转包、抵押、继承时限不能超过本合同规定的承包年限。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:27.45pt'><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>转租、转包、抵押、继承经营的应在<span
lang=EN-US>30</span>日内办理变更手续，变更后的当事人必须履行合同规定条款。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第十六条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>乙方必须按时向甲方缴纳宜农林地承包费。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第十七条 </span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;不得违法经营。违法行为的法律后果由乙方自负。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第十八条</span></b><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>乙方（承包区域内）的各种事故责任（人身伤亡、火灾等等）由乙方自负。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第十九条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>承包期届满，乙方如需继续承包，应在期满前一年向甲方提交申请，在同等条件下，乙方享有优先承包权。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第二十条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>乙方的债权、债务由乙方自行负责，甲方不负任何连带责任。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第二十一条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>乙方必须在承包范围内经营作业，不得以拱地头，扩地边等非法形式侵占林地，毁坏资源。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第二十二条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>乙方有保护岭南生态农业示范区生态环境的义务，及时清理生产、生活中所产生的各种垃圾及废弃物。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第二十三条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>乙方有义务宣传防火知识及扑救山火。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal align=center style='margin-top:0cm;margin-right:21.0pt;
margin-bottom:0cm;margin-left:78.8pt;margin-bottom:.0001pt;text-align:center;
text-indent:-64.5pt'><b><span lang=EN-US style='font-size:16.0pt;font-family:
仿宋_GB2312'>第四章<span style='font:7.0pt "Times New Roman"'>&nbsp;&nbsp; </span></span></b><b><span
style='font-size:16.0pt;font-family:仿宋_GB2312'>违约责任</span></b></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:14.3pt;margin-bottom:.0001pt'><b><span lang=EN-US
style='font-size:16.0pt;font-family:仿宋_GB2312'>&nbsp;</span></b></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第二十四条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>单方违反合同约定条款为违约行为，违约方应承担违约责任，向对方赔偿由此造成的直接经济损失。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第二十五条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>乙方不按时缴交承包费，甲方有权向人民法院提起诉讼强制执行。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第二十六条</span></b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>出现下列情况之一时，应当变更或解除合同：</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>1</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>、当事人双方协商同意，并且不损害国家、集体利益的；</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>2</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>、订立承包合同依据的计划、文件等变更或取消的；</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>3</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>、因国家、省、地、林业政策的调整，致使单方收益情况发生较大变化的；</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>4</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>、因发包方或承包方不履行合同规定的义务，致使合同无法继续履行或者没有必要继续履行的；</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>5</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>、承包人丧失承包能力的，没有继承人继续履行合同。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>6</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>、承包人进行破坏性、掠夺性生产经营经发包人劝阻无效的；</span></p>

<p class=MsoNormal style='margin-right:21.0pt;text-indent:14.0pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>7</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>、承包人私自转让、转包合同及转包渔利的。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:32.25pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span><b><span style='font-size:14.0pt;font-family:仿宋_GB2312'>第五章<span
lang=EN-US>&nbsp; </span>附<span lang=EN-US>&nbsp;&nbsp; </span>则</span></b></p>

<p class=MsoNormal style='margin-right:21.0pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;<b>&nbsp;</b></span><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第二十七条</span></b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>本合同经甲、乙双方签字盖章后生效。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:15.7pt'><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第二十八条</span></b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>本合同一式三份，甲乙双方各一份，存档一份。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:15.75pt'><b><span
lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span></b><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第二十九条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>本合同于＿＿年＿＿月＿＿日在 ＿＿省＿＿地区＿＿区签订。<b><span
lang=EN-US> </span></b></span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:29.5pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第三十条</span></b><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp; </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>本合同如需变更、修改、补充时，可由双方另行商定后作为合同附件，经双方当事人签字认定后，合同附件与本合同具有同等法律效力。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:29.5pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第三十一条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>甲、乙双方必须严格执行本合同条款。合同中未明确的内容按国家法律、地方规定执行。执行本合同发生争议，由争议双方协商解决，协商不成的，可以向有管辖权的人民法院起诉。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:29.5pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第三十二条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>本合同是唯一合法有效的文书，自签订之日起生效，农户以前持有的宜农林地承包合同及其它证件一律作废。</span></p>

<p class=MsoNormal style='margin-top:0cm;margin-right:21.0pt;margin-bottom:
0cm;margin-left:-17.95pt;margin-bottom:.0001pt;text-indent:29.5pt'><b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>第三十三条<span lang=EN-US>&nbsp; </span></span></b><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>本合同甲、乙双方签订的宜农林地位置图（坐标点）如下：</span></p>

<table class=MsoNormalTable border=1 cellspacing=0 cellpadding=0 align=left
 width=711 style='width:348.45pt;border-collapse:collapse;border:none;
 margin-left:6.75pt;margin-right:6.75pt'>
 <tr style='height:412.1pt'>
  <td width=41 valign="middle" style='width:32.5pt;border-top:windowtext;border-left:#333333;
  border-bottom:black;border-right:#333333;border-style:solid;border-width:
  1.0pt;padding:0cm 5.4pt 0cm 5.4pt;height:412.1pt'>
  <p class=MsoNormal align=center style='text-align:center'>&nbsp;</p>
  <p class=MsoNormal align=center style='text-align:center'>&nbsp;</p>
  <p class=MsoNormal align=center style='text-align:center'>&nbsp;</p>
  <p class=MsoNormal align=center style='text-align:center'>&nbsp;</p>
  <p class=MsoNormal align=center style='text-align:center'>&nbsp;</p>
  <p class=MsoNormal align=center style='text-align:center'>&nbsp;</p>
  <p class=MsoNormal align=center style='text-align:center'>&nbsp;</p>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:14.0pt;font-family:仿宋_GB2312'>位</span></p>
  <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
  style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>
  <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
  style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:14.0pt;font-family:仿宋_GB2312'>置</span></p>
  <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
  style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>
  <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
  style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>
  <p class=MsoNormal align=center style='text-align:center'><span
  style='font-size:14.0pt;font-family:仿宋_GB2312'>图</span></p>
  <p class=MsoNormal align=center style='text-align:center'><span lang=EN-US
  style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>
  </td>
  <td width=664 valign=top style='width:315.95pt;border-top:solid #333333 1.0pt;
  border-left:none;border-bottom:solid black 1.0pt;border-right:solid black 1.0pt;
  padding:0cm 5.4pt 0cm 5.4pt;height:412.1pt'>
  <p class=MsoNormal style='margin-right:21.0pt'><span lang=EN-US
  style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>
  </td>
 </tr>
</table>

<p class=MsoNormal style='margin-right:21.0pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'>&nbsp;</p>
<p class=MsoNormal style='margin-right:21.0pt'><span style='font-size:14.0pt;
font-family:仿宋_GB2312'>图幅号：<span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</span>调查员：<span
lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;</span>甲方签字：</span></p>

<p class=MsoNormal><span style='font-size:14.0pt;font-family:仿宋_GB2312'>比例尺：<span
lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;</span>制图人：<span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;</span>乙方签字：</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal style='text-indent:56.0pt'><span style='font-size:14.0pt;
font-family:仿宋_GB2312'>甲<span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </span>方：<span
lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span>（章）</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;</span><span style='font-size:14.0pt;font-family:仿宋_GB2312'>法定代表人：<u><span
lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></u>（签字）</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;&nbsp;
</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal style='text-indent:28.0pt'><span lang=EN-US
style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;&nbsp; </span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>乙<span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span>方：<span lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;</span>（章）</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span><span style='font-size:14.0pt;font-family:仿宋_GB2312'>法定代表人：<u><span
lang=EN-US>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></u>（签字）</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;</span></p>

<p class=MsoNormal><span lang=EN-US style='font-size:14.0pt;font-family:仿宋_GB2312'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span><span
style='font-size:14.0pt;font-family:仿宋_GB2312'>年<span lang=EN-US>&nbsp;&nbsp; </span>月<span
lang=EN-US>&nbsp;&nbsp; </span>日</span></p>

</div>
        </div>
        <!-- /.col -->
<!--                 </div> -->
<!--             </div> -->
        </div>
    </div>
</section>
<script language="javascript" type="text/javascript">
    var LODOP; //声明为全局变量 
	
	function prn_preview4() {	
		CreateOnePage();	
		LODOP.SET_PRINT_MODE("PRINT_PAGE_PERCENT","Auto-Width");	
		LODOP.PREVIEW();	
	};		
	
	function CreateOnePage(){
		LODOP=getLodop();  
		LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_整页缩放打印输出");
		strCenterStyle="<style/>form {text-align: center}</style>";
		LODOP.ADD_PRINT_HTM("25mm","20mm","RightMargin:0mm","BottomMargin:9mm",strCenterStyle+document.getElementById("ttpoprint").innerHTML); //上下边距9mm，左右边距0mm
// 		LODOP.ADD_PRINT_HTM("14%","10%","100%","90%","<body leftmargin=0 topmargin=0>"+document.getElementById('ttpoprint').innerHTML+"</body>");
		LODOP.SET_PRINT_STYLEA(0,"Horient",2);        
		LODOP.SET_PREVIEW_WINDOW(0,0,0,0,0,"");	
	};	
</script>