<?php

use yii\helpers\Html;
use yii\helpers\Url;

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
                    <h1 class="text-red">警告!</h1>
                </div>
                <div class="box-body">
                    <h3 class="text-red">
                        <table class="table table-bordered table-hover">
                            <tr>
                                <td>
                                    对不起!以下三项法人信息必须填写完整,不能为空,请到<?= Html::a('法人信息',Url::to(['farmer/farmercreate','farms_id'=>$farm->id]))?>中填写。
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
                    <?php
                        
                    ?>
                        </h3>
              </div>
            </div>
        </div>
    </div>
</section>
</div>