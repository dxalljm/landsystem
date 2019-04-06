<?php
namespace frontend\controllers;
use yii;
use app\models\Tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ManagementArea;
use app\models\Lockedinfo;
use frontend\helpers\grid\GridView; 
use yii\web\View;
use app\models\Farms;
use app\models\Auditprocess;
use app\models\Reviewprocess;
use yii\helpers\Url;
use app\models\User;
use app\models\Processname;
/* @var $this yii\web\View */
/* @var $model app\models\farms */
User::disabled();
?>
<style>
    #dialogMsg {
        display: none;
    }
</style>
<div class="farms-view">

	<p>

        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    </p>
    <div >
        <div class="col-md-3">

        </div>
        <!-- /.col -->
        <?php if(!Farms::getLocked($_GET['farms_id'])) {?>
        <div class="col-md-6">

          <!-- Widget: user widget style 1 -->
          <div class="card">
            <!-- Add the bg color to the header using any of the bg-* classes -->
              <div class="card-header card-header-text text-center" data-background-color="blue" style="width:96%">
                  <h1 class="card-title">宜农林地承包合同转让</h1>
              </div>


            <div class="box-footer">
              <div class="row">
                <div class="col-sm-6 border-right">
                  <div class="description-block">
                      <?php
                      if(Farms::isFarmsInfo($farms_id) or User::disabled())
                          echo html::a('<h1>整体转让</h1>','#',['class'=>'btn btn-block btn-info btn-lg','disabled'=>User::disabled()]);
                      else {

                              echo html::a('<h1>整体转让</h1>', Url::to(['farms/farmsttpowhole', 'farms_id' => $farms_id]), ['class' => 'btn btn-block btn-info btn-lg']);

                      }?>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                  <div class="description-block">
                      <?php
                      if(Farms::isFarmsInfo($farms_id) or User::disabled())
                          echo html::a('<h1>部分转让</h1>','#',['class'=>'btn btn-block btn-info btn-lg','disabled'=>User::disabled()]);
                      else {
                          if(Farms::getContractstate($farms_id) == 'M' or Farms::getContractstate($farms_id) == 'L') {
                              echo html::a('<h1>部分转让</h1><h5 class="widget-user-desc text-black">此合同必须先整体转为新合同后才能做其他操作</h5>','#',['class'=>'btn btn-block btn-info btn-lg','disabled'=>User::disabled()]);
                          } else {
                              echo html::a('<h1>部分转让</h1>', Url::to(['farms/farmsttpopart', 'farms_id' => $farms_id]), ['class' => 'btn btn-block btn-info btn-lg']);
                          }
                      }

                      ?>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->

                </div>
          </div>
        </div>
        </div>
            <?php } else {?>
            <div class="col-md-6">
                <div class="alert alert-rose alert-with-icon" data-notify="container">
                    <i class="fa fa-lock" data-notify="icon"></i>
                    <button aria-hidden="true" class="close" type="button">
                        <i class="material-icons"></i>
                    </button>
                    <span data-notify="message"><h2><?= Lockedinfo::find()->where(['farms_id'=>$_GET['farms_id']])->one()['lockedcontent']?></h2></span>
                </div>

                <?php }?>
</div>
        <div class="content col-xs-12 box box-body">
              <?php
              if($ttpozongdiModel) {
                  $oldfarm = Farms::find()->where(['id'=>$farms_id])->one();
                  ?>
                  <table class="table table-bordered table-hover">
                      <tr height="40px">
                          <td align="center"><strong>管理区</strong></td>
                          <td align="center"><strong>原农场名称</strong></td>
                          <td align="center"><strong>原法人</strong></td>
                          <td align="center"><strong>原面积</strong></td>
                          <td align="center"><strong>现农场名称</strong></td>
                          <td align="center"><strong>现法人</strong></td>
                          <td align="center"><strong>现面积</strong></td>
                          <td align="center"><strong>状态</strong></td>
                          <td align="center"><strong>操作</strong></td>
                      </tr>
                      <?php
                      // 							var_dump($farmstransfer);exit;
                      foreach ($ttpozongdiModel as $value) {

                          $newfarm = Farms::find()->where(['id'=>$value['newnewfarms_id']])->one();
                          $oldfarm = Farms::find()->where(['id'=>$value['oldfarms_id']])->one();
// 							var_dump($newfarm);exit;
//                            if(Reviewprocess::isShowProess($value['operation_id'])) {
//                          $field = Reviewprocess::getProcessIdentification();
                          ?>
                          <tr height="40px">
                              <td align="center"><?= ManagementArea::getAreaname($oldfarm->management_area)?></td>
                              <td align="center"><?= $oldfarm->farmname?></td>
                              <td align="center"><?= $oldfarm->farmername?></td>
                              <td align="center"><?= $oldfarm->contractarea?>亩</td>
                              <td align="center"><?= $newfarm->farmname?></td>
                              <td align="center"><?= $newfarm->farmername?></td>
                              <td align="center"><?= $newfarm->contractarea?>亩</td>

                              <td align="center">
                                  <?php
                                    echo Reviewprocess::getState($value['reviewprocess_id']);
                                  ?>
                                  </ul>
                                  </td>
                              <td align="center">
                                  <?php

                                  echo  html::a('查看',['farms/farmsttpozongdiview','id'=>$value['id']],['class'=>'btn btn-success']);


                                  ?></td>
                          </tr>

                      <?php }?>
                  </table>
              <?php } else {
                  echo '<h4>&nbsp;&nbsp;无转让信息</h4>';
              }?>

            </div>
</div>
    <div id="dialogMsg" title="信息" class="text-red">
        <table class="table table-bordered table-hover">
            <tr>
                <td>
                    对不起!以下三项法人信息必须填写完整,不能为空,请到<strong><font color="red"><?= Html::a('法人信息',Url::to(['farmer/farmercreate','farms_id'=>$farm->id]))?></font></strong>中填写。
                </td>
            </tr>
            <tr>
                <td>
                    <?php if($farm->cardid) {
                        if(strlen($farm->cardid) == 18)
                            echo '身份证信息:'.$farm->cardid.'   请检查是否正确。';
                        else {
                            echo '身份证信息不正确,请仔细检查';
                        }
                    } else {
                        echo '身份证信息为空,请补充此信息。';
                    }?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                    if(empty($farm->address)) {
                        echo '农场位置为空,请补充此信息。';
                    } else {
                        echo '农场位置信息:'.$farm->address.'   请检查是否正确。';
                    }
                    ?>
                </td>
            </tr>
            <tr>
                <td>
                    <?php
                    if(empty($farm->longitude) or empty($farm->latitude)) {
                        echo '农场坐标为空,请补充此信息。';
                    } else {
                        echo '农场坐标信息:'.$farm->longitude.'  '.$farm->latitude.'    请检查是否正确。';
                    }
                    ?>
                </td>
            </tr>
        </table>
        </div>
<script>
    $(document).ready(function(){
        if(<?= Farms::isFarmsInfo($farm->id)?> == 1) {
            $("#dialogMsg").dialog("open");
        }
    });
    $( "#dialogMsg" ).dialog({
        autoOpen: false,
        width: 600,
        buttons: [
            {
                text: "确定",
                click: function() {
                    $( this ).dialog( "close" );
                    location.href = "<?= Url::to(['farms/farmsmenu','farms_id'=>$farm->id])?>";
                }

            },

        ]
    });
</script>