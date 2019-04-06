<?php
namespace frontend\controllers;
use app\models\Lease;
use app\models\Plant;
use app\models\Plantingstructurecheck;
use app\models\Subsidyratio;
use app\models\Subsidytypetofarm;
use app\models\User;
use app\models\Tables;
use app\models\Huinong;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\ManagementArea;
use app\models\Farms;
use app\models\BankAccount;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\bankaccountSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'bank_account';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
$arrclass = explode('\\',$dataProvider->query->modelClass);
?>
<div class="bank-account-index">

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>&nbsp;&nbsp;&nbsp;&nbsp;<?= $this->title ?><font color="red">(<?= User::getYear()?>年度)</font></h3></div>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php
    if(User::getItemname('地产科')) {
        echo Html::a('生成汇总表', Url::to(['bankaccount/toxls', 'where' => json_encode($dataProvider->query->where)]), ['class' => 'btn btn-success']);
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'total' => '<tr height="40">
                                        <td></td>	
                                        <td align="left" id="pt0"><strong>合计</strong></td>
                                        <td align="left" id="pt1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="pt2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="pt3"></td>
                                        <td align="left" id="pt4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="pt5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="pt6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="pt7"><strong>'.count(Plant::getAllname()).'种</strong></td>
                                        <td align="left" id="pt8"><strong></strong></td>
                                    </tr>',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

//            'id',
                [
                    'label' => '管理区',
                    'attribute' => 'management_area',
                    'headerOptions' => ['width' => '150'],
                    'value' => function ($model) {
                        // 				            	var_dump($model);exit;
                        return ManagementArea::getAreanameOne($model->management_area);
                    },
                    'filter' => ManagementArea::getAreaname(),
                ],
                [
                    'label' => '农场名称',
                    'attribute' => 'farmname',
                    'options' => ['width' => 120],
                    'value' => function ($model) {

                        return Farms::find()->where([
                            'id' => $model->farms_id
                        ])->one()['farmname'];

                    }
                ],
                [
                    'label' => '法人名称',
                    'attribute' => 'farmername',
                    'options' => ['width' => 120],
                    'value' => function ($model) {

                        return Farms::find()->where([
                            'id' => $model->farms_id
                        ])->one()['farmername'];

                    }
                ],
                [
                    'label' => '合同号',
                    'attribute' => 'farmstate',
                    'value' => function ($model) {
                        return Farms::find()->where([
                            'id' => $model->farms_id
                        ])->one()['contractnumber'];
                    },
                    'filter' => [1 => '正常', 2 => '未更换合同', 3 => '临时性管理', 4 => '买断合同'],
                ],
                [
                    'label' => '合同面积',
                    'attribute' => 'contractarea',
                    'headerOptions' => ['width' => '60'],
                    'value' => function ($model) {
                        return Farms::find()->where([
                            'id' => $model->farms_id
                        ])->one()['contractarea'];
                    }
                ],
                [
                    'attribute' => 'lessee',
                    'headerOptions' => ['width' => '70'],
                ],
                [
                    'label' => '补贴种类',
                    'value' => function($model) {
                        $ps = Plantingstructurecheck::find()->where(['farms_id'=>$model->farms_id,'lease_id'=>$model->lease_id,'year'=>User::getYear()])->one();;
                        $plant = Plant::findOne($ps['plant_id']);
                        return $plant['typename'];
                    },
                    'filter' => Huinong::getPlant(),
                ],
                [
                    'label' => '补贴面积',
                    'value' => function($model) {
                        $ps = Plantingstructurecheck::find()->where(['farms_id'=>$model->farms_id,'lease_id'=>$model->lease_id,'year'=>User::getYear()])->one();;
                        return $ps['area'];
                    }
                ],
                'bank',
                'accountnumber',
//            ['class' => 'frontend\helpers\eActionColumn'],
            ],
        ]); ?>
        <?php
    }
    ?>
    <?php
    if(User::getItemname('服务大厅')) {
        ?>
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'total' => '<tr height="40">
                                        <td></td>	
                                        <td align="left" id="pt0"><strong>合计</strong></td>
                                        <td align="left" id="pt1"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="pt2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="pt3"></td>
                                        <td align="left" id="pt4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="pt5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="left" id="pt6"><strong></strong></td>
                                        <td align="left" id="pt7"><strong></strong></td>
                                        <td align="left" id="pt8"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                    </tr>',
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

//            'id',
                [
                    'label' => '管理区',
                    'attribute' => 'management_area',
                    'headerOptions' => ['width' => '150'],
                    'value' => function ($model) {
                        // 				            	var_dump($model);exit;
                        return ManagementArea::getAreanameOne($model->management_area);
                    },
                    'filter' => ManagementArea::getAreaname(),
                ],
                [
                    'label' => '农场名称',
                    'attribute' => 'farmname',
                    'options' => ['width' => 120],
                    'value' => function ($model) {

                        return Farms::find()->where([
                            'id' => $model->farms_id
                        ])->one()['farmname'];

                    }
                ],
                [
                    'label' => '法人名称',
                    'attribute' => 'farmername',
                    'options' => ['width' => 120],
                    'value' => function ($model) {

                        return Farms::find()->where([
                            'id' => $model->farms_id
                        ])->one()['farmername'];

                    }
                ],
                [
                    'label' => '合同号',
                    'attribute' => 'farmstate',
                    'value' => function ($model) {
                        return Farms::find()->where([
                            'id' => $model->farms_id
                        ])->one()['contractnumber'];
                    },
                    'filter' => [1 => '正常', 2 => '未更换合同', 3 => '临时性管理', 4 => '买断合同'],
                ],
                [
                    'label' => '合同面积',
                    'attribute' => 'contractarea',
                    'headerOptions' => ['width' => '150'],
                    'value' => function ($model) {
                        return Farms::find()->where([
                            'id' => $model->farms_id
                        ])->one()['contractarea'];
                    }
                ],
                [
                    'attribute' => 'lessee',
                    'headerOptions' => ['width' => '70'],
                ],
                'bank',
                'accountnumber',
                [
                    'label' => '操作',
                    'attribute' => 'state',
                    'format' => 'raw',
                    'value' => function ($model) {
                        if ($model->state == 2) {
                            return Html::button('审核', ['onclick' => 'examine(' . $model->id . ')', 'class' => 'btn btn-danger btn-xs']);
                        } else {
                            $html = '<span class="text text-green">通过</span>';
                            $html .= '&nbsp;&nbsp;';
                            $html .= Html::button('<span class="glyphicon glyphicon-pencil"></span>', ['onclick' => 'examinemodfiy(' . $model->id . ')', 'class' => 'btn btn-success btn-xs']);
                            $html .= '&nbsp;&nbsp;';
                            $html .= Html::button('<span class="glyphicon glyphicon-trash"></span>', ['onclick' => 'examinedelete(' . $model->id . ')', 'class' => 'btn btn-danger btn-xs']);
                        }
                        return $html;
                    },
                    'filter' => [1=>'通过',2=>'未通过']
                ],

//            ['class' => 'frontend\helpers\eActionColumn'],
            ],
        ]);
    }
    ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<div id="dialog" title="银行账号审核">
    <table class="table table-bordered table-hover">
        <tr>
            <td align="right">种植者:</td>
            <td>
                <?= html::textInput('lessee','',['id'=>'Lessee','readonly'=>true])?>
            </td>
        </tr>
        <tr>
            <td align="right">身份证号:</td>
            <td>
                <?= html::textInput('cardid','',['id'=>'Cardid','readonly'=>true])?>
            </td>
        </tr>
        <tr>
            <td align="right">银行:</td>
            <td><?= html::textInput('bank','',['id'=>'Bank','readonly'=>true])?></td>
        </tr>
        <tr>
            <td align="right">卡号：</td>
            <td>
                <input type="text" id="Accountnumber" name="actTransaction.opbankacntnoShow" size="26" maxlength="50" value="" onkeyup="Keystroke();"/
        </tr>
    </table>
    <div id="copybankacntno">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" size="30" id="bankacntnoEm" style="font-size:30px;color:blue;" value="" readonly="readonly"/>
    </div>
    <div  id="dialogDelete">
        <span class="text-red">是否确认撤消此项?</span>
    </div>
    <?= html::hiddenInput('bankid','',['id'=>'BankID'])?>
</div>
<?php
if(isset($_GET['bankaccountSearch']['state'])) {
    $state = $_GET['bankaccountSearch']['state'];
} else {
    $state = '';
}
?>
<div id="dialogModfiy" title="银行账号修改">
    <table class="table table-bordered table-hover">
        <tr>
            <td align="right">种植者:</td>
            <td>
                <?= html::textInput('lessee','',['id'=>'Lesseem','readonly'=>true])?>
            </td>
        </tr>
        <tr>
            <td align="right">身份证号:</td>
            <td>
                <?= html::textInput('cardid','',['id'=>'Cardidm','readonly'=>true])?>
            </td>
        </tr>
        <tr>
            <td align="right">银行:</td>
            <td><?= html::textInput('bank','',['id'=>'Bankm','readonly'=>true])?></td>
        </tr>
        <tr>
            <td align="right">卡号：</td>
            <td>
                <input type="text" id="Accountnumberm" name="actTransaction.opbankacntnoShow" size="26" maxlength="50" value="" onkeyup="Keystrokem();"/>
        </tr>
    </table>
    <div id="copybankacntno">
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" size="30" id="bankacntnoEmm" style="font-size:30px;color:blue;" value="" readonly="readonly"/>
    </div>
    <?= html::hiddenInput('bankidm','',['id'=>'BankIDm'])?>
    <?= html::hiddenInput('farms_id','',['id'=>'FarmsID'])?>
</div>

<script>
    function examine(id) {
        $.getJSON('index.php?r=bankaccount/getbankinfo', {id: id}, function (data) {
            $('#BankID').val(id);
            $('#Lessee').val(data.lessee);
            $('#Cardid').val(data.cardid);
            $('#Bank').val(data.bank);
            $('#Accountnumber').val(data.accountnumber);
            if(data.lease_id > 0) {
                $('#Cardid').attr('readonly',false);
            } else {
                $('#Cardid').attr('readonly',true);
            }
            var v = $('#Accountnumber').val();
            var vnew = v.replace(/\s/g, '').replace(/(.{4})/g, "$1 ");
            $("#bankacntnoEm").val(vnew);
        });

        $("#dialog").dialog("open");
    }
    function examinemodfiy(id) {
        $('#BankIDm').val(id);
        $.getJSON('index.php?r=bankaccount/getbankinfo', {id: id}, function (data) {
            $('#FarmsID').val(data.farms_id);
            $('#Lesseem').val(data.lessee);
            $('#Cardidm').val(data.cardid);
            $('#Bankm').val(data.bank);
            $('#Accountnumberm').val(data.accountnumber);
            if(data.lease_id > 0) {
                $('#Cardidm').attr('readonly',false);
            } else {
                $('#Cardidm').attr('readonly',true);
            }
            var v = $('#Accountnumberm').val();
            var vnew = v.replace(/\s/g, '').replace(/(.{4})/g, "$1 ");
            $("#bankacntnoEmm").val(vnew);
        });

        $("#dialogModfiy").dialog("open");
    }
    function examinedelete(id) {
        $('#BankIDm').val(id);
        $("#dialogDelete").dialog("open");
    }
    function Keystroke(){

        var v = $('#Accountnumber').val();
        var vnew = v.replace(/\s/g, '').replace(/(.{4})/g, "$1 ");
//		$('#Accountnumber').val(v.replace(/\s/g, '').replace(/(.{4})/g, "$1 "));
        $("#bankacntnoEm").val(vnew);
//		$("#Accountnumber").blur(function(){
//			$("#copybankacntno").hide();
//		});
//			$("#Accountnumber").mouseout(function(){
//				$("#copybankacntno").hide();
//			});
    }
    function Keystrokem(){

        var v = $('#Accountnumberm').val();
        var vnew = v.replace(/\s/g, '').replace(/(.{4})/g, "$1 ");
//		$('#Accountnumber').val(v.replace(/\s/g, '').replace(/(.{4})/g, "$1 "));
        $("#bankacntnoEmm").val(vnew);
//		$("#Accountnumber").blur(function(){
//			$("#copybankacntno").hide();
//		});
//			$("#Accountnumber").mouseout(function(){
//				$("#copybankacntno").hide();
//			});
    }
    $('#dialog').dialog({
        autoOpen: false,
        width:700,

        buttons: [
            {
                text: "审核通过",
                click: function() {
                    $.getJSON('index.php?r=bankaccount/setstate', {id: $('#BankID').val(),cardid:$('#Cardid').val(),'accountnumber':$('#Accountnumber').val()}, function (data) {
                        window.location.reload();
                    });
                    $( this ).dialog( "close" );
                }
            },
            {
                text: "关闭",
                click: function() {
                    $( this ).dialog( "close" );
                }
            }
        ]
    });
    $('#dialogModfiy').dialog({
        autoOpen: false,
        width:700,

        buttons: [
            {
                text: "确定",
                click: function() {
                    $.getJSON('index.php?r=bankaccount/bankaccountmodfiy',{'id':$('#BankIDm').val(),cardid:$('#Cardidm').val(),'accountnumber':$('#Accountnumberm').val()},function (data) {
                        if(data.state)
                            window.location.reload();
                        else
                            alert('保存失败');
                    });
//					$.getJSON('index.php?r=bankaccount/setstate', {id: $('#BankID').val(),checkid:$('#CheckID').val()}, function (data) {
//						if(data) {
//							window.location.reload();
//						} else {
//							alert('保存失败,请与管理员联系。');
//						}
//					});
                    $( this ).dialog( "close" );
                }
            },
            {
                text: "关闭",
                click: function() {
                    $( this ).dialog( "close" );
                }
            }
        ]
    });
    $('#dialogDelete').dialog({
        autoOpen: false,
        width:400,

        buttons: [
            {
                text: "确定",
                click: function() {
                    $.getJSON('index.php?r=bankaccount/bankaccountcacle',{'id':$('#BankIDm').val()},function (data) {
                        if(data.state)
                            window.location.reload();
                    });
                    $( this ).dialog( "close" );
                }
            },
            {
                text: "关闭",
                click: function() {
                    $( this ).dialog( "close" );
                }
            }
        ]
    });
    $('.shclDefault').shCircleLoader({color: "red"});
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#pt1').html(data + '户');
        });

        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#pt2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#pt4').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-lease_id',andwhere:'<?= json_encode('lease_id>0')?>'}, function (data) {
            $('#pt5').html('生产者'+ data + '人');
        });


//        $.getJSON('index.php?r=search/search', {modelClass: 'Farms',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'count-plant_id'}, function (data) {
//             $('#t4').html(data + '种');
//         });
        //$.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>',where:'<?//= json_encode($dataProvider->query->where)?>',command:'count-goodseed_id'}, function (data) {
//             $('#t5').html(data + '种');
//         });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-accountnumber',andwhere:'<?= json_encode(['state'=>$state])?>'}, function (data) {
            $('#pt8').html(data + '人');
        });

    });

    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t1').html(data + '户');
        });

        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farmer_id'}, function (data) {
            $('#t2').html(data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#t4').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-lease_id',andwhere:'<?= json_encode('lease_id>0')?>'}, function (data) {
            $('#t5').html('生产者'+ data + '人');
        });


//        $.getJSON('index.php?r=search/search', {modelClass: 'Farms',where:'<?//= json_encode($dataProvider->query->where)?>//',command:'count-plant_id'}, function (data) {
//             $('#t4').html(data + '种');
//         });
        //$.getJSON('index.php?r=search/search', {modelClass: '<?//= $arrclass[2]?>',where:'<?//= json_encode($dataProvider->query->where)?>',command:'count-goodseed_id'}, function (data) {
//             $('#t5').html(data + '种');
//         });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-accountnumber',andwhere:'<?= json_encode(['state'=>$state])?>'}, function (data) {
            $('#t8').html(data + '人');
        });

    });
</script>