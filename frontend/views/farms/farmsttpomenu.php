<?php
namespace backend\controllers;
use app\models\tables;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\ManagementArea;
use app\models\Cooperative;
use frontend\helpers\grid\GridView; 
use yii\web\View;
use app\models\Cooperativetype;
use app\models\Parcel;
use app\models\Farms;
use app\models\Loan;
use app\models\Lockedinfo;
use app\models\Auditprocess;
use app\models\Reviewprocess;
use app\models\Session;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\farms */

?>
<div class="farms-view">

	<p>

        <br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
    </p>
    <div >
        <div class="col-md-3">

        </div>
        <!-- /.col -->
        <div class="col-md-6">
          <!-- Widget: user widget style 1 -->
          <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-aqua-active">
                <h1 class="widget-user text-center">宜农林地承包合同转让</h1>
            </div>

            <div class="box-footer">
              <div class="row">
                <div class="col-sm-6 border-right">
                  <div class="description-block">
                      <?= html::a('<h1>整体转让</h1>',Url::to(['farms/farmsttpowhole','farms_id'=>$farms_id]),['class'=>'btn btn-block btn-info btn-lg'])?>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                  <div class="description-block">
                      <?= html::a('<h1>部分转让</h1>',Url::to(['farms/farmsttpopart','farms_id'=>$farms_id]),['class'=>'btn btn-block btn-info btn-lg'])?>
                  </div>
                  <!-- /.description-block -->
                </div>
                <!-- /.col -->
              </div>
              <!-- /.row -->
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

                            $newfarm = Farms::find()->where(['id'=>$value['newfarms_id']])->one();
                            $oldfarm = Farms::find()->where(['id'=>$value['oldfarms_id']])->one();
// 							var_dump($newfarm);exit;
//                            if(Reviewprocess::isShowProess($value['operation_id'])) {
                                $field = Reviewprocess::getProcessIdentification();
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
                                        $useritem = User::getItemname();
                                        $temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
                                        if($temp) {
                                            $useritem = User::getUserItemname($temp['user_id']);
                                        }
                                        // 								var_dump($useritem);
                                        if($useritem == '地产科科长' or $useritem == '主任' or  $useritem == '副主任' ) {?>
                                            <div class="btn-group">
                                                <div class="btn dropdown-toggle"
                                                     data-toggle="dropdown" data-trigger="hover">
                                                    <?= Reviewprocess::state($value['state']); ?> <span class="caret"></span>
                                                </div>
                                                <ul class="dropdown-menu" role="menu">
                                                    <?php
                                                    // 								   var_dump(Reviewprocess::getProcess($value['operation_id']));exit;
                                                    foreach (Reviewprocess::getProcess($value['operation_id']) as $val) {
// 								   var_dump($value[$val]);exit;
                                                        ?>
                                                        <li><a href="#"><?= Processname::find()->where(['Identification'=>$val])->one()['processdepartment'].':'.Reviewprocess::state($value[$val])?></a></li>
                                                    <?php }?>
                                                </ul>
                                            </div>
                                        <?php } else echo Reviewprocess::state($value['state']); ?>
                                    </td>
                                    <td align="center">
                                        <?php
                                        $s = false;
                                        // 								var_dump($field);
                                        foreach ($field as $v) {

                                            if($value[$v] == 2 or $value[$v] == 0)
                                                $s = true;
                                        }
                                        if($s) {
                                            echo  html::a('审核',['reviewprocess/reviewprocessinspections','id'=>$value['id'],'class'=>'farmstransfer'],['class'=>'btn btn-danger']);
                                        }
                                        else {
                                            echo  html::a('查看',['reviewprocess/reviewprocessview','id'=>$value['id'],'class'=>'farmstransfer'],['class'=>'btn btn-success']);
                                        }

                                        ?></td>
                                </tr>

                            <?php }?>
                    </table>
                <?php }?>

</div>

