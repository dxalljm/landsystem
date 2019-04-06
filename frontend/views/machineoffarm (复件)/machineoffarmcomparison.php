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
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
                    <h3>添加的农机</h3>
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td class="text-center"><strong>机具名称</strong></td>
                            <td class="text-center"><strong>分档名称</strong></td>
                            <td class="text-center" width="40%"><strong>基本配置和参数</strong></td>
                            <td class="text-center"><strong>生产厂家</strong></td>
                        </tr>
                        <tr height="80px">
                            <td class="text-center"><?= $machine['productname']?></td>
                            <td class="text-center"><?= $machine['filename']?></td>
                            <td class="text-left"><?= $machine['parameter']?></td>
                            <td class="text-center"><?= $machine['enterprisename']?></td>
                        </tr>
                    </table>
                    <h3>农机购置补贴机具对比</h3>
                    <table class="table table-bordered table-hover">
                        <tr>
                            <td class="text-center"><strong>品目</strong></td>
                            <td class="text-center"><strong>分档名称</strong></td>
                            <td class="text-center"><strong>基本配置和参数</strong></td>
                            <td class="text-center"><strong>中央财政补贴额</strong></td>
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
                                <td class="text-center"><?= Html::a('符合',Url::to(['machineapply/machineapplycomparison','subsidy_id'=>$machinesubsidy['id'],'apply_id'=>$apply_id]),['class'=>'btn btn-success'])?></td>
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