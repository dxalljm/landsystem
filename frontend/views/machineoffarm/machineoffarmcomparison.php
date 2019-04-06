<?php
namespace frontend\controllers;
use app\models\User;
use app\models\Tables;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use app\models\Machine;
use app\models\Machinetype;
use app\models\Farms;
/* @var $this yii\web\View */
/* @var $model app\models\Machineoffarm */

?>
<style type="text/css">
    *{ padding:0px; margin:0px;list-style:none;}
    .boxmachine{width:1500px;background-image: url("images/background.jpg");height:140px;margin:0px 0px;}
    .fixed{position:fixed;top:0;margin-top:0;}
</style>
<section class="content"">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 style="font-size:30px">&nbsp;&nbsp;&nbsp;&nbsp;确认补贴金额<font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
                    <h3 style="font-size:30px">添加的农机</h3>
                    <div class="">
                        <table class="table table-bordered table-hover boxmachine">
                            <tr>
                                <td class="text-center" width="10%"><strong>机具名称</strong></td>
                                <td class="text-center" width="10%"><strong>机具型号</strong></td>
                                <td class="text-center" width="25%"><strong>分档名称</strong></td>
                                <td class="text-center" width="40%"><strong>基本配置和参数</strong></td>
                                <td class="text-center"><strong>生产厂家</strong></td>
                                <td class="text-center"><strong>机具类型</strong></td>
                            </tr>
                            <tr height="80px">
                                <td class="text-center"><?= $machine['productname']?></td>
                                <td class="text-center"><?= $machine['implementmodel']?></td>
                                <td class="text-center"><?= $machine['filename']?></td>
                                <td class="text-left"><?= $machine['parameter']?></td>
                                <td class="text-center"><?= $machine['enterprisename']?></td>
                                <td class="text-center"><?= $machine['machinetype']?></td>
                            </tr>
                        </table>
                    </div>
                    <br>
                    <h3 style="font-size:30px">农机购置补贴机具对比</h3>
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td class="text-center"><strong>品目</strong></td>
                            <td class="text-center"><strong>分档名称</strong></td>
                            <td class="text-center"><strong>基本配置和参数</strong></td>
                            <td class="text-center"><strong>中央财政补贴额</strong></td>
                            <td class="text-center"><strong>备注</strong></td>
                            <td class="text-center"><strong>操作</strong></td>
                        </tr>
                        <?php
                        foreach ($machinesubsidys as $machinesubsidy) {
                            ?>
                            <tr>
                                <td class="text-center"><?= Machinetype::find()->where(['id'=>$machinesubsidy['machinetype_id']])->one()['typename'] ?></td>
                                <td class="text-center"><?= $machinesubsidy['filename'] ?></td>
                                <td class="text-center"><?= $machinesubsidy['parameter'] ?></td>
                                <td class="text-center"><?= $machinesubsidy['subsidymoney'] ?></td>
                                <td class="text-center"><?= $machinesubsidy['machinetype'] ?></td>
                                <td class="text-center"><?= Html::a('符合',Url::to(['machineapply/machineapplycomparison','subsidy_id'=>$machinesubsidy['id'],'apply_id'=>$apply_id,'farms_id'=>$farms_id]),['class'=>'btn btn-success'])?></td>
                            </tr>
                            <?php
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(document).ready(function(){

        var box = $(".boxmachine");
        var boxTop = box.offset().top;  //元素到页面顶部的距离

        $(document).scroll(function(){

            var winScrollTop = $(window).scrollTop();  //获取窗口滚动的距离
            console.log(winScrollTop);
            if(winScrollTop > boxTop){
                box.addClass("fixed");
            }else{
                box.removeClass("fixed");
            }

        });
    });

</script>
</html>