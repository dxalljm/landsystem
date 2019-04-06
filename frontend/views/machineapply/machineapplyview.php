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

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <br>
                        <div class="tablehead"><?= date('Y')?>年农业机械购置补贴申请表</div>

                        <div class="box-body">
                            <br>
                            <?= Farms::showFarminfo2($model->farms_id)?>
                            <table width="100%" class="ttpoprint table table-bordered table-hover" border="1">
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
                            <?= Farms::showFarmerinfopic($model->farms_id)?>
                            <?= html::a('返回',Url::to(['huinonggrant/huinonggrantinfo']),['class'=>'btn btn-primary'])?>
                        </div>

                    </div>

                </div>
            </div>
        </div>
</div>
</section>
</div>