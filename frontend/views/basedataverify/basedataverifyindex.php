<?php

use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Wantingsetup;
use app\models\Basedataverify;
use app\models\ManagementArea;
/* @var $this yii\web\View */
/* @var $model app\models\tables */
//var_dump($data);

?>

<div class="regular-import">
    <section class="content">
        <div class="row">
<!--            <div class="col-xs-12">-->
                <div class="box">
                    <?= Html::hiddenInput('autoSave',0,['id'=>'auto-save'])?>
                    <div class="text text-center"><H2><?= \app\models\User::getYear().'年'.Basedataverify::$tablename?></H2></div>
                    <div></div>
                    <div>请选择管理区:
                        <?php
                            $management = ArrayHelper::map(ManagementArea::find()->all(),'id','areaname');
                            foreach($management as $id => $areaname) {
                                echo Html::a($areaname,Url::to(['basedataverify/basedataverifylist','management_area'=>$id]),['class'=>'btn btn-success']);
                                echo "&nbsp;&nbsp;";
                            }
                        ?>
                    </div>
<!--                    </div>-->
                </div>
<!--            </div>-->
        </div>
        </section>
</div>