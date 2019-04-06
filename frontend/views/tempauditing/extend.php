<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Tempauditing;

/* @var $this yii\web\View */
/* @var $model app\models\Collection */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="collection-form">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
                <div id="remainSeconds" style="display:none">5</div>
         <div id="remainTime" style="font-size:20px;font-weight:800;color:#FF9900"></div>
  <h4>日期已经延长至 <?= date('Y-m-d',Tempauditing::getEnddate())?></h4>
              </div>
            </div>
        </div>
    </div>
</section>
</div>
<script type="text/javascript" language="javascript">

var SysSecond;
var InterValObj;
$(document).ready(function() {
SysSecond = parseInt($("#remainSeconds").html());
InterValObj = window.setInterval(SetRemainTime, 1000);
});
function SetRemainTime() {
if (SysSecond > 0) {
SysSecond = SysSecond - 1;
var second = Math.floor(SysSecond % 60);  
var minite = Math.floor((SysSecond / 60) % 60); 
var hour = Math.floor((SysSecond / 3600) % 24);  
var day = Math.floor((SysSecond / 3600) / 24); 
$("#remainTime").html(second + "秒"+'后跳转页面');
 } else {
	 window.clearInterval(InterValObj);
	 window.location="<?= Url::to(['reviewprocess/reviewprocessindex']);?>";
 }
 }
</script>