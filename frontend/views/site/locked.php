<?php

use yii\helpers\Html;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = '系统信息';
$lockinfo = \app\models\Lockstate::findOne(1);
?>
<link rel="stylesheet" href="js/Countdown/Countdown.css">
<script src="js/Countdown/Countdown.js"></script>
<div class="site-error">
    <table background="images/lockback.jpg" height="1013" width="1880" border="0" align="center" cellpadding="0" cellspacing="0">
        <tr>
            <td class="text text-center text-red" style="font-size:40px"><?= $this->title ?></td>
        </tr>
        <tr>
            <td class="text text-center" style="font-size:40px">对不起,系统被锁定,暂时不能使用。</span></td>
        </tr>
        <tr>
            <td class="text text-center" style="font-size:50px">解锁时间为<?= date('Y-m-d H:i:s',$lockinfo->systemstatedate)?></td>
        </tr>
        <tr>
            <td class="text text-center" style="font-size:30px">还有&nbsp;&nbsp;<?php
                $s = $lockinfo->systemstatedate - time();
                echo '<script>$(function(){timer('.$s.');});</script>';
                if($s > 0) {
                    echo '<strong id="day_show" class="time-item">0天</strong>';
                    echo '<strong id="hour_show" class="time-item">0时</strong>';
                    echo '<strong id="minute_show" class="time-item">0分</strong>';
                    echo '<strong id="second_show" class="time-item">0秒</strong>';

                }
                ?>&nbsp;&nbsp;解锁</td>
        </tr>
        <tr><td height="30%"></td></tr>
</table>
</div>
<script type="text/javascript" language="javascript">
    //setTimeout(function(){window.location="<?//= Url::to('index.php?r=collection/collectionsend&farms_id='.$farms_id)?>";},5000);

    var SysSecond;
    var InterValObj;
    $(document).ready(function() {
        SysSecond = parseInt(<?= $s?>);
        InterValObj = window.setInterval(SetRemainTime, 1000);
    });
    function SetRemainTime() {
        if (SysSecond > 0) {
            SysSecond = SysSecond - 1;
            var second = Math.floor(SysSecond % 60);
            var minite = Math.floor((SysSecond / 60) % 60);
            var hour = Math.floor((SysSecond / 3600) % 24);
            var day = Math.floor((SysSecond / 3600) / 24);
        } else {
            window.clearInterval(InterValObj);
            window.location="<?= Url::to(['site/index'])?>";
        }
    }
</script>
