<?php
namespace frontend\controllers;
use app\models\Farmerinfo;
use app\models\Insurancecompany;
use app\models\Machineapply;
use app\models\Machinescanning;
use frontend\helpers\cardidClass;
use frontend\helpers\imageClass;
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

<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>
<div class="reviewprocess-form">


    <?php $form = ActiveFormrdiv::begin(); ?>


    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="form-group">

                    <?= html::a('返回',\Yii::$app->request->getReferrer(),['class'=>'btn btn-primary'])?>
                </div>


                <!--             <div class="box"> -->

                <!--                 <div class="box-body"> -->
                <div class="col-md-6" id="ttpoprint">
                    <style type="text/css">
                        .ttpoprint {
                            font-family: "仿宋";
                            font-size:20px;
                            height:"600px";
                        }
                        .tablehead{ width:100%; height:30px; line-height:20px; text-align:center; float:left; font-size:40px; font-family:"黑体"}
                        .tablehead2{ width:100%; height:30px; line-height:20px; text-align:left; float:left; font-size:20px; font-family:"黑体"}
                    </style>
                    <br>
                    <!-- Box Comment -->
                    <div class="box box-widget">
                        <div class="box-header with-border">
                            <br>
                            <div class="tablehead"><?= date('Y')?>年农业机械购置补贴申请表</div>
                            <br>
                        </div>
                        <br>
                        <!-- /.box-header -->
                        <div class="box-body">
                            <div class="tablehead2">岭南管委会&nbsp;——&nbsp;<span style="font-size: 15px"><?= ManagementArea::getAreanameOne($model->management_area)?></span></div>
                            <table width="100%"  border="1" cellpadding="0" cellspacing="0" class="ttpoprint"">
                            <tr height="100px">
                                <td width="10%" align="center">姓名</td>
                                <td  align="center"><?= $model->farmername?></td>
                                <td align="center" width="14%" >年龄</td>
                                <td align="center" width="14%" ><?= $model->age?></td>
                                <td width="14%" colspan="2" align="center">性别</td>
                                <td align="center" colspan="2"><?php if($model->sex) echo $model->sex; else echo cardidClass::get_sex($model->cardid);?></td>
                            </tr>
                            <tr height="100px">
                                <td align="center" width="17%">户籍所在地<br>(身份证地址)</td>
                                <td colspan="9" align="left">&nbsp;&nbsp;<?= $model->domicile?></td>
                            </tr>
                            <tr height="100px">
                                <td align="center">身份证号</td>
                                <td align="center" colspan="2"><?= $model->cardid?></td>
                                <td align="center">联系电话</td>
                                <td align="center" colspan="6"><?= $model->telephone?></td>
                            </tr>
                            <tr height="100px">
                                <td align="center">机具名称</td>
                                <td align="center" colspan="2"><?php if(empty($machine->filename)) echo $machine->productname;else echo $machine->filename;?></td>
                                <td align="center">生产厂家</td>
                                <td align="center" colspan="6"><?= $machine->enterprisename?></td>
                            </tr>
                            <tr height="100px">
                                <td align="center">规格型号</td>
                                <td align="center" colspan="2"><?= $machine->implementmodel?></td>
                                <td align="center">补贴额度(元)</td>
                                <td align="center" colspan="6"><?= $model->subsidymoney?></td>
                            </tr>
                            <tr height="200px">
                                <td align="center">申请人签字<br>(购机者)</td>
                                <td colspan="9" align="left" valign="top">
                            </tr>
                            <tr height="100px">
                                <td align="center">地产组签字</td>
                                <td colspan="9" align="left" valign="top">
                            </tr>
                            <tr height="100px">
                                <td align="center">服务大厅签字</td>
                                <td colspan="9" align="left" valign="top">
                            </tr>
                            <tr height="100px">
                                <td align="center">地产科签字</td>
                                <td colspan="9" align="left" valign="top">
                            </tr>
                            <tr height="100px">
                                <td align="center">项目科签字</td>
                                <td colspan="9" align="left" valign="top">
                            </tr>
                            </table>
                            <?php
//                            $farmerinfo = Farmerinfo::find()->where(['cardid'=>$model->cardid])->one();
                            $scans = Machinescanning::find()->where(['cardid'=>$model->cardid])->all();
                            ?>
                            <table>
                                <?php
                                    foreach ($scans as $scan) {
                                        $imginfo = imageClass::getImageInfo($scan['scanimage']);
                                        $width = $imginfo['width']*0.25;
                                        $height = $imginfo['height']*0.25;
                                ?>
                                        <tr>
                                            <td>&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td><img border='1' width="<?= $width?>px" height="<?= $height?>px" src='http://192.168.1.10/<?= $scan['scanimage']?>'/></td>
                                        </tr>
                                <?php
                                    }
                                ?>
                            </table>
                            <br>

                        </div>
                        <!-- /.box-body -->

                    </div>
                    <!-- /.box -->
                </div>
                <!-- /.col -->



                <?php ActiveFormrdiv::end(); ?>
                <!--                 </div> -->
                <!--             </div> -->
            </div>
        </div>
    </section>
</div>