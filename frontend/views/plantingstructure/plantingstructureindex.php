<?php

use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Plantingstructure;
use app\models\Plantingstructurecheck;
use app\models\Plant;
use app\models\Help;
use app\models\Theyear;
use frontend\helpers\htmlColumn;
use yii\helpers\Url;
use app\models\User;
use app\models\Lockstate;
use app\models\Goodseedinfo;
use app\models\Goodseed;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="lease-index">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <?php Farms::showRow($_GET['farms_id']);?>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header">
                    <h3>
                        <?php $farms = Farms::find()->where(['id'=>$_GET['farms_id']])->one();
                        $plantings = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'year'=>User::getYear()]);?>
                        <?= Help::showHelp3('种植结构调查数据','plantingstructurecheck-index')?><font color="red">(<?= User::getYear()?>年度)</font>&nbsp;
                        <?= Html::a('复核',Url::to(['plantingstructurecheck/plantingstructurecheckindex','farms_id'=>$_GET['farms_id']]),['class'=>'btn btn-primary']);?>
                    </h3>
                </div>
                <div class="box-body">
                    <?php
                    $SumArea = 0.0;
                    $leaseSumArea = 0.0;
                    $farmerArea = 0.0;
                    $leaseArea = 0.0;
                    $strArea = '';
                    $arrayArea = [];
                    $plantFarmerArea = 0.00;
                    $allarea = $farms['contractarea'];
                    $plantFarmerArea = 0;
                    $farerShowButtion = false;
                    $plantLeaseArea = 0.0;
                    $leaseShowButton = false;
                    $leaseArea0 = \app\models\Lease::find()->where(['farms_id'=>$_GET['farms_id'],'year'=>User::getYear()])->sum('lease_area');
                    if(empty($leaseArea0)) {
//                        $leaseArea = sprintf('%.2f',Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'year'=>User::getYear()])->sum('area'));
                        $leaseArea = 0;
                    } else {
                        $leaseArea = sprintf('%.2f',$leaseArea0);
                    }
//                    var_dump($allarea);var_dump($leaseArea);
                    $cha = bcsub($allarea,$leaseArea,2);
                    if($leaseArea > 0) {
                        if(bccomp($allarea,$leaseArea) == 1) {
                            $plantFarmerArea = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>0,'year'=>User::getYear()])->sum('area');
                            $plantLeaseArea = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'year'=>User::getYear()])->andWhere('lease_id>0')->sum('area');
                            if($plantFarmerArea) {
                                if(bccomp($leaseArea,$plantFarmerArea) == 1) {
                                    $farerShowButtion = true;
                                } else {
                                    $farerShowButtion = false;
                                }
                            } else {
                                $plantFarmerArea = bcsub($allarea,$leaseArea,2);
                            }
                            if(bccomp($leaseArea,$plantLeaseArea) == 0) {
                                $leaseShowButton = false;
                            } else {
                                $leaseShowButton = true;
                            }
                        } else {
                            $farerShowButtion = false;
                            $plantLeaseArea = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'year'=>User::getYear()])->andWhere('lease_id>0')->sum('area');
                            if($plantLeaseArea) {
                                if(bccomp($leaseArea,$plantLeaseArea) == 1) {
                                    $leaseShowButton = true;
                                } else {
                                    $leaseShowButton = false;
                                }
                            } else {
                                $leaseShowButton = true;
                            }
                        }
                    }
                    if($cha > 0) {
                        $farmerArea = sprintf('%.2f',Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>0,'year'=>User::getYear()])->sum('area'));
                        if(bccomp($cha,$farmerArea) == 0) {
                            $farerShowButtion = false;
                        } else {
                            $farerShowButtion = true;
//                            $plantFarmerArea = bcsub($allarea,$Area,2);
                        }
                    }
//                    if($plantFarmerArea > 0) {
                        ?>
                        <table class="table table-bordered table-hover">
                            <tr bgcolor="#faebd7">
                                <td width="12%" colspan="2" align="center"><strong>法人</strong></td>
                                <td colspan="2" align="center"><strong>种植面积</strong></td>
                                <td width="22%" align="center"><strong>操作</strong></td>
                            </tr>
                            <tr>
                                <td width="12%" colspan="2" align="center"><?= $farms['farmername'] ?></td>
                                <td colspan="2" align="center"><?= bcsub($cha,$plantFarmerArea,2) ?>亩</td>
                                <td align="center"><?php if ($farerShowButtion) {
                                        if (User::disabled()) {
                                            echo Html::a('添加', '#', [
                                                'id' => 'employeecreate',
                                                'title' => '给' . $farms['farmername'] . '添加',
                                                'class' => 'btn btn-primary',
                                                'disabled' => User::disabled(),
                                            ]);
                                        } else {
                                            echo Html::a('添加', 'index.php?r=plantingstructure/plantingstructurecreate&lease_id=0&farms_id=' . $_GET['farms_id'], [
                                                'id' => 'employeecreate',
                                                'title' => '给' . $farms['farmername'] . '添加',
                                                'class' => 'btn btn-primary',
                                            ]);
                                        }
                                    } ?></td>
                            </tr>
                            <?php
                            $farmerplantings = Plantingstructure::find()->where(['farms_id' => $_GET['farms_id'], 'lease_id' => 0, 'year' => User::getYear()])->all();
                            if ($farmerplantings) {
                                foreach ($farmerplantings as $v) {
                                    ?>
                                    <tr>
                                        <td colspan="2" align="center">|_</td>

                                        <td align="center">种植作物面积：<?= $v['area'] ?>亩</td>
                                        <td align="center">
                                            作物：<?= Plant::find()->where(['id' => $v['plant_id']])->one()['typename'] ?><span id="is-goodseed_<?= $v['id']?>"><?php
                                                if(Goodseedinfo::isGoodseed($v['id'])) {
                                                    echo '<i class="fa fa-pagelines text-success"></i>';
                                                }
                                                ?></span></td>
                                        <td align="center"><?php
                                            $plants = Plantingstructurecheck::find()->where(['farms_id' => $_GET['farms_id'], 'year' => User::getYear()])->count();
                                            if ($plants == 0) {
                                                htmlColumn::show(['id' => $v['id'], 'lease_id' => $v['lease_id'], 'farms_id' => $v['farms_id']]);
                                                echo Html::a('良种','#',['class'=>'btn btn-xs btn-success','onClick'=>'showGoodseed('.$v['id'].','.$v['farms_id'].','.$v['plant_id'].')']);
//                                                echo htmlColumn::createButton('goodseed',['goodseedinfo/goodseedinfocreate','lease_id'=>$v['id'],'lease_id' => $v['lease_id'], 'farms_id' => $v['farms_id']],'良种');
                                            } ?></td>
                                    </tr>
                                <?php }
                            } ?>
                        </table>
                        <br>
                        <?php
//                    }
                if($leases) {
                    ?>
                    <table class="table table-bordered table-hover">
                        <tr bgcolor="#faebd7">
                            <td width="12%" colspan="2" align="center"><strong>承租人</strong></td>
                            <td colspan="2" align="center"><strong>承租面积</strong></td>
                            <td width="22%" align="center"><strong>操作</strong></td>
                        </tr>
                        <?php
                        $isLeaseViewAdd = 0;

                        foreach($leases as $val) {
                            $isLeaseViewAdd += Plantingstructure::find()->where(['lease_id'=>$val['id']])->one()['area'];
                            ?>
                            <tr>
                                <td colspan="2" align="center"><?= $val['lessee'] ?></td>
                                <td colspan="2"  align="center"><?= bcsub($leaseArea,$plantLeaseArea,2) ?>亩</td>
                                <?php
                                $leaseData = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>$val['id']])->andFilterWhere(['between','update_at',Theyear::getYeartime()[0],Theyear::getYeartime()[1]])->all();
                                ?>
                                <td align="center"><?php if($leaseShowButton) {
                                        if(User::disabled()) {
                                            echo Html::a('添加','#', [
                                                'id' => 'employeecreate',
                                                'title' => '给'.$val['lessee'].'添加',
                                                'class' => 'btn btn-primary',
                                                'disabled' => User::disabled(),
                                            ]);
                                        } else {
                                            echo Html::a('添加','index.php?r=plantingstructure/plantingstructurecreate&lease_id='.$val['id'].'&farms_id='.$_GET['farms_id'], [
                                                'id' => 'employeecreate',
                                                'title' => '给'.$val['lessee'].'添加',
                                                'class' => 'btn btn-primary',
                                            ]);
                                        }
                                    }?></td>
                            </tr>
                            <?php

                            foreach($leaseData as $v) {
                                ?>
                                <tr>
                                    <td colspan="2" align="center">|_</td>
                                    <td align="center">种植作物面积：<?= $v['area']?>亩</td>
                                    <td align="center">作物：<?= Plant::find()->where(['id'=>$v['plant_id']])->one()['typename']?><span id="is-goodseed_<?= $v['id']?>"><?php
                                            if(Goodseedinfo::isGoodseed($v['id'])) {
                                                echo '<i class="fa fa-pagelines text-success"></i>';
                                            }
                                            ?></span></td>
                                    <td align="center"><?php
                                        $plants = Plantingstructurecheck::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>$v['lease_id'],'year'=>User::getYear()])->count();
                                        if($plants == 0) {
                                            htmlColumn::show(['id'=>$v['id'],'lease_id'=>$v['lease_id'],'farms_id'=>$v['farms_id']]);
                                            echo Html::a('良种','#',['class'=>'btn btn-xs btn-success','onClick'=>'showGoodseed('.$v['id'].','.$v['farms_id'].','.$v['plant_id'].')']);
                                        }?></td>
                                </tr>
                            <?php }}?>
                    </table>
                <?php }?>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box">
                <div class="box-header">
                    <h3>
                        <?php $farms = Farms::find()->where(['id' => $_GET['farms_id']])->one();
                        $plantings = Plantingstructure::find()->where(['farms_id' => $_GET['farms_id'], 'year' => User::getYear()]); ?>
                        <?= Help::showHelp3('种植结构复核数据', 'plantingstructurecheck-index') ?><font
                            color="red">(<?= User::getYear() ?>年度)</font>&nbsp;
                        <!--                          --><?//= Html::a('复核',Url::to(['plantingstructurecheck/plantingstructurecheckindex','farms_id'=>$_GET['farms_id']]),['class'=>'btn btn-primary']);?>
                    </h3>
                </div>
                <div class="box-body">
                    <?php
                    $SumArea = 0.0;
                    $leaseSumArea = 0.0;
                    $farmerArea = 0.0;
                    $leaseArea = 0.0;
                    $strArea = '';
                    $arrayArea = [];
                    $plantFarmerArea = 0.00;
                    $allarea = $farms['contractarea'];
                    //		if($leases) {
                    //			foreach ($leases as $value) {
                    //				$leaseArea += $value['lease_area'];
                    //			}
                    //		} else {
                    $plantFarmerArea = Plantingstructurecheck::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>0,'year'=>User::getYear()])->sum('area');
                    $plantLeaseArea = Plantingstructurecheck::find()->where(['farms_id'=>$_GET['farms_id'],'year'=>User::getYear()])->andWhere('lease_id>0')->sum('area');
                    //		}
                    if(empty($plantFarmerArea)) {
                        $plantFarmerArea = 0;
                    }
                    if(empty($plantLeaseArea)) {
                        $plantLeaseArea = 0;
                    }
                    $farmerArea = (float)bcsub($allarea , $plantFarmerArea,2);
                    //		foreach ($plantings->all() as $value) {
                    //			$SumArea += $value['area'];
                    //		}
                    $isFarmerAdd = false;
                    if($farmerArea > 0) {
                        $isFarmerAdd = true;
                    }
                    //		$isPlantingViewAdd = (float)bcsub($farmerArea , $farmerSumArea,2);
                    //    var_dump($isPlantingViewAdd);
                    //		$plantFarmerArea = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'lease_id'=>0,'year'=>User::getYear()])->sum('area');

                    //		if($plantFarmerArea < $farmerArea) {
                    //			$isFarmerAdd = true;
                    //		}
                    $sum = $plantFarmerArea + $plantLeaseArea;
                    if($sum > 0) {
                        $isView = bcsub($allarea, $sum,2);
                    } else {
                        $isView = false;
                    }

                    if($isView) {
                        if($plantFarmerArea > 0) {
                    ?>
                    <table class="table table-bordered table-hover">
                    <tr bgcolor="#faebd7">
                        <td width="12%" colspan="2" align="center"><strong>法人</strong></td>
                        <td colspan="2" align="center"><strong>种植面积</strong></td>
                        <td width="22%" align="center"><strong>操作</strong></td>
                    </tr>
                    <tr>
                        <td width="12%" colspan="2" align="center"><?= $farms['farmername'] ?></td>
                    <td colspan="2" align="center"><?= sprintf('%.2f', $plantFarmerArea) ?>亩</td>
                    <td align="center"></td>
                    </tr>
                    <?php

                    $farmerplantings = Plantingstructurecheck::find()->where(['farms_id' => $_GET['farms_id'], 'lease_id' => 0, 'year' => User::getYear()])->all();
                    if ($farmerplantings) {
                        foreach ($farmerplantings as $v) {
                            ?>
                            <tr>
                                <td colspan="2" align="center">|_</td>

                                <td align="center">种植作物面积：<?= $v['area'] ?>亩</td>
                                <td align="center">
                                    作物：<?= Plant::find()->where(['id' => $v['plant_id']])->one()['typename'] ?></td>
                                <td align="center"><?php if ($v['issame']) echo '<h4 class="text-green">一致</h4>'; else echo '<h4 class="text-red">不一致</h4>'; ?></td>
                            </tr>
                        <?php } ?>
                        </table>
                        <br>
                    <?php }}
                    if ($leases) {
                        ?>
                        <table class="table table-bordered table-hover">
                            <tr bgcolor="#faebd7">
                                <td width="12%" colspan="2" align="center"><strong>承租人</strong></td>
                                <td colspan="2" align="center"><strong>承租面积</strong></td>
                                <td width="22%" align="center"><strong>状态</strong></td>
                            </tr>
                            <?php
                            $isLeaseViewAdd = 0;
                            foreach ($leases as $val) {
                                $isLeaseViewAdd += Plantingstructurecheck::find()->where(['lease_id' => $val['id']])->one()['area'];
                                ?>
                                <tr>
                                    <td colspan="2" align="center"><?= $val['lessee'] ?></td>
                                    <td colspan="2" align="center"><?= $val['lease_area'] ?>亩</td>
                                    <?php
                                    $leaseData = Plantingstructurecheck::find()->where(['farms_id' => $_GET['farms_id'], 'lease_id' => $val['id']])->andFilterWhere(['between', 'update_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]])->all();
                                    ?>
                                    <td align="center"></td>
                                </tr>
                                <?php

                                foreach ($leaseData as $v) {
                                    ?>
                                    <tr>
                                        <td colspan="2" align="center">|_</td>
                                        <td align="center">种植作物面积：<?= $v['area'] ?>亩</td>
                                        <td align="center">
                                            作物：<?= Plant::find()->where(['id' => $v['plant_id']])->one()['typename'] ?></td>
                                        <td align="center"><?php if ($v['issame']) echo '<h4 class="text-green">一致</h4>'; else echo '<h4 class="text-red">不一致</h4>'; ?></td>
                                    </tr>
                                <?php }
                            } ?>
                        </table>
                    <?php } }?>
                </div>
            </div>
        </div>
    </div>


</section>
</div>
<?php
//var_dump(Goodseed::isGoodseed(12));exit;
?>
<div id="goodseed" title="良种">
</div>
</div>
<script>
$('#rowjump').keyup(function(event){
	input = $(this).val();
	$.getJSON('index.php?r=farms/getfarmid', {id: input}, function (data) {
		$('#setFarmsid').val(data.farmsid);
	});
});
    $(function(){
        var dch = $('#dc').height();
        var fhh = $('#fh').height();
//        alert(dch);alert(fhh);
        if(dch > fhh) {
            $('#fh').height(dch);
        } else {
            $('#dc').height(fhh);
        }
    });

    function showGoodseed(planting_id,farms_id,plant_id) {
        $.get('index.php?r=goodseedinfo/goodseedinfolistajax', {'farms_id':farms_id,'planting_id':planting_id,'plant_id':plant_id}, function (body) {
//            console.log(body);
            $('#goodseed').html(body);
            $("#goodseed").dialog("open");
        });
    }

$( "#goodseed" ).dialog({
    autoOpen: false,
    width: 800,
    height:600,
    modal:true,
//        closeOnEscape:false,
//        open:function(event,ui){$(".ui-dialog-titlebar-close").hide();},
    buttons: [
        {
            text: "确定",
            click: function() {
                $( this ).dialog( "close" );
                var tempid = $('#temp-id').val();
                var planting_id = $('#planting-id').val();
                var type_id = new Array();
                var area_num = new Array();
                for(i=1;i<=tempid;i++) {
                    var t = $('#type_'+i).val();
                    if(t !== undefined || t !== '') {
                        type_id[i] = t;
                    }
                    var area = $('#area_'+i).val();
                    if(area !== undefined || area !== '') {
                        area_num[i] = area;
                    }
                }
                $.getJSON('index.php?r=goodseedinfo/goodseedinfosave2',{'typename':type_id.join(','),'area':area_num.join(','),'planting_id':planting_id},function (data) {
                    if(data.save > 0) {
                        $('#is-goodseed_' + planting_id).html('<i class="fa fa-pagelines text-success"></i>');
                    } else {
                        $('#is-goodseed_' + planting_id).html('');
                    }
                });
            }
        },
        {
            text: "取消",
            class:'btn btn-danger',
            click: function() {
                $( this ).dialog( "close" );
            }
        }
    ]
});
</script>
