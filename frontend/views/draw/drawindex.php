<?php

use app\models\Draw;
use app\models\Farms;
use app\models\ManagementArea;
use app\models\Tables;
use backend\helpers\Pinyin;
use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\DrawSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Draw';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<script type="text/javascript" src="js/jquery.json-2.2.min.js"></script>
<div class="draw-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= $this->title ?>                    </h3>
                </div>
                <div class="box-body">
    <?php
        echo '&nbsp;&nbsp;&nbsp;&nbsp;'.Html::a('生成XLS表', Url::to(['draw/drawtoxls', 'where' => json_encode($dataProvider->query->where)]), ['class' => 'btn btn-success']);
        echo '&nbsp;&nbsp;&nbsp;&nbsp;'.Html::a('重置', '#', ['class' => 'btn btn-danger','id'=>'reset']);
    ?>

                    <p>
                        <?php
                        $m = Farms::getManagementArea();
                        for($i=0;$i<7;$i++) {
                            echo '&nbsp;&nbsp;&nbsp;&nbsp;';
                            if(Draw::find()->where(['management_area'=>$m['id'][$i],'state'=>1])->count()) {
                                echo Html::button($m['areaname'][$i], ['class' => 'btn btn-default','disabled'=>true]);
                            } else {
                                echo Html::button($m['areaname'][$i], ['class' => 'btn btn-success', 'id' => 'createDraw' . $m['id'][$i], 'onclick' => 'openDialog(' . $m["id"][$i] . ')']);
                            }
                        }
                        ?>

                    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//            'id',
            [
                'label'=>'管理区',
                'attribute'=>'management_area',
//                'headerOptions' => ['width' => '130'],
                'value'=> function($model) {
                    return ManagementArea::getAreanameOne($model->management_area);
                },
                'filter' => ManagementArea::getAreaname(),
            ],
            [
                'label' => '农场名称',
//                'attribute' => 'farms_id',
//                'options' =>['width'=>120],
                'value' => function ($model) {

                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['farmname'];

                }
            ],
            [
                'label' => '法人名称',
//                'attribute' => 'farmer_id',
//                'options' =>['width'=>120],
                'value' => function ($model) {

                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['farmername'];

                }
            ],
            [
                'label' => '合同号',
                'attribute' => 'contractnumber',
//                'options' => ['width'=>'150'],
                'value' => function($model) {
                    return Farms::find ()->where ( [
                        'id' => $model->farms_id
                    ] )->one ()['contractnumber'];
                },
//                'filter' => [1=>'正常',2=>'未更换合同',3=>'临时性管理'],
            ],

//            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?php
//var_dump(Farms::getManagementArea('small')['areaname']);exit;
?>
<!--<div id="msg" title="提示信息">-->
<!---->
<!--</div>-->
<?= Html::hiddenInput('areaid','',['id'=>'AreaID'])?>
<?= Html::hiddenInput('rows','',['id'=>'Rows'])?>
<?= Html::textarea('data','',['id'=>'datalist','style'=>'display:none'])?>
<?= Html::textarea('nowid','',['id'=>'nowids','style'=>'display:none'])?>
<?= Html::textarea('nums','',['id'=>'Nums','style'=>'display:none'])?>
<div id="msg">

</div>
<div id="drawDialog" title="随机抽签">
    <table width="100%">
        <tr>
            <td width="30%">
                <table width="100%">
                    <tr height="50px">
                        <td align="center"></td>
                    </tr>
                    <tr height="100px">
                        <td align="center"><span id="areaname" style="font-size:40px"></span></td>
                    </tr>
                    <tr height="600px">
                        <td align="center" valign="middle" id="msginfo">
                            <br><br><br><br><br><br><br><br><br><br><br><br>
                            <h3 id="msg1" style="font-size:40px"></h3>
                            <h2 id="msg3" style="font-size:40px"></h2>
                            <h2 id="msg2" style="font-size:40px"></h2>
                        </td>
                    </tr>
                    <tr height="50px">
                        <td align="center"><?= Html::button('开始抽签',['class'=>'btn btn-success','id'=>'selectButton'])?><?= Html::button('停止',['class'=>'btn btn-danger','id'=>'stopButton','disabled'=>true])?></td>
                    </tr>
                </table>
            </td>
            <td>
                <span class="text-center"><h3 style="font-size:30px">抽签结果</h3></span>
                <table class="table table-bordered table-hover">
                    <tr>
                        <td align="center" width="10%">序号</td>
                        <td align="center" width="20%">农场名称</td>
                        <td align="center" width="20%">法人名称</td>
                        <td align="center" width="10%">序号</td>
                        <td align="center" width="20%">农场名称</td>
                        <td align="center" width="20%">法人名称</td>
                    </tr>
                    <?php
                    for($k=1;$k<=15;$k++) {
                        $newk = $k + 15;
                        echo '<tr>';
                        echo Html::hiddenInput('num','',['id'=>'num'.$k]);
                        echo Html::hiddenInput('farms_id','',['id'=>'farms_id'.$k]);
                        echo Html::hiddenInput('cardid','',['id'=>'cardid'.$k]);
                        echo '<td align="center" id="id'.$k.'">'.$k.'</td>';
                        echo '<td align="center" id="farmname'.$k.'"></td>';
                        echo '<td align="center" id="farmername'.$k.'"></td>';
                        echo Html::hiddenInput('num','',['id'=>'num'.$newk]);
                        echo Html::hiddenInput('farms_id','',['id'=>'farms_id'.$newk]);
                        echo Html::hiddenInput('cardid','',['id'=>'cardid'.$newk]);
                        echo '<td align="center" id="id'.$newk.'">'.$newk.'</td>';
                        echo '<td align="center" id="farmname'.$newk.'"></td>';
                        echo '<td align="center" id="farmername'.$newk.'"></td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </td>
        </tr>
    </table>
</div>

<script>
    var numArray = new Array();
    function openDialog(id) {
        $('#AreaID').val(id);
        $("#drawDialog").dialog("open");
        var id = $("#AreaID").val();
        var row = $('#Rows').val();
        $('#datalist').val('');
        $('#Nums').val("");
        for(i=1;i<=row;i++) {
            $('#farms_id' + i).val('');
            $('#farmname' + i).html('');
            $('#farmername' + i).html('');
            $('#cardid' + i).val('');
            $('#num' + i).val();
        }
        $.getJSON('index.php?r=draw/getfarms',{'management_area':id},function (data) {
            $('#datalist').val($.toJSON(data));
        });
        $.getJSON('index.php?r=draw/getmsg',{'management_area':id},function (data) {
            $('#areaname').html(data.areaname);
            $("#Rows").val(data.raw);
            $('#msg1').html(data.msg1);
            $('#msg2').html(data.msg2);
            $('#msg3').html(data.msg3);
        });
    }

//    function datamove(num) {
//        var list = $.parseJSON($('#datalist').val());
////        var list = [1,2,3,4,5,6,7,8,9];
//        var cardid = list[num]['cardid'];
//        $.each(list,function (k,v) {
//                delete list[k];
//            }
//        });
//        $('#datalist').val($.toJSON(list));
//        console.log(list);
//    }
    var immer;
    function rand() {
        var list = $.parseJSON($('#datalist').val());
        immer = setInterval(function () {
            var row = $('#Rows').val();
            for(i=1;i<=row;i++) {
                num = Math.floor(Math.random() * list.length);
                $('#farms_id'+i).val(list[num]['id']);
                $('#farmname'+i).html(list[num]['farmname']);
                $('#farmername'+i).html(list[num]['farmername']);
                $('#cardid'+i).val(list[num]['cardid']);
                $('#num'+i).val(num);
            }
        }, 30);
    }

    $('#selectButton').click(function () {
        $(this).attr('disabled',true);
        $('#stopButton').attr('disabled',false);
//        $.getJSON('index.php?r=draw/drawdeleteall',{'management_area':$('#AreaID').val()});
        var now = new Date().getTime();
        $.getJSON('index.php?r=draw/drawrand',{management_area:$('#AreaID').val(),now:now},function (data) {
            $('#Nums').val(data.arrayNum);
        });
//        $.ajax({
//            type:"GET",
//            url:"index.php?r=draw/drawrand&management_area="+$('#AreaID').val(),
//            dataType:"json",
//            cache:false;
//            function (data) {
//                $('#Nums').val(data.arrayNum);
//                alert('ajax');
//            }
//        });
        var row = $('#Rows').val();
        var id = $('#AreaID').val();
        $('#Nums').val('');
        $('#nowids').val('');
        for(i=1;i<=row;i++) {
            $('#farms_id' + i).val('');
            $('#farmname' + i).html('');
            $('#farmername' + i).html('');
            $('#cardid' + i).val('');
            $('#num' + i).val();
        }

        rand();
    });



    function getNum() {
        var list = $.parseJSON($('#datalist').val());
        var num;
        num = Math.floor(Math.random() * list.length);
        return num;
    }

    function randNum() {
        immerNum = setInterval(function (array,i) {
            var n = getNum();
            if(!array.in_array(n)) {
                array[i] = n;
            }
        }, 30);
    }

    $('#stopButton').click(function(){
        $(this).attr('disabled',true);
        $('#selectButton').attr('disabled',false);
        $("#nowids").val('');
        var row = $('#Rows').val();

        clearInterval(immer);
        var nums = $('#Nums').val();
        var arrayNum = nums.split(',');
        $.each(arrayNum,function (k,num) {
            var list = $.parseJSON($('#datalist').val());
            var j=k+1;
            $('#farms_id'+j).val(list[num]['id']);
            $('#farmname'+j).html(list[num]['farmname']);
            $('#farmername'+j).html(list[num]['farmername']);
            $('#cardid'+j).val(list[num]['cardid']);
            $('#num'+j).val(num);
            var ids = $('#nowids').val();
            if(ids == '') {
                $('#nowids').val(list[num]['id']);
            } else {
                $('#nowids').val(ids + ',' + list[num]['id']);
            }
        });
    });

    $('#reset').click(function () {
        $('#msg').text('重置会将当年所有管理区抽查结果清空,确定要重置吗?');
        $("#msg").dialog("open");
    });
    $('#msg').dialog({
        autoOpen: false,
        width:500,
        buttons: [
            {
                text: "确定",
                click: function() {
                    $( this ).dialog( "close" );
                    $.getJSON('index.php?r=draw/drawreset',function (data) {
                        if(data.state) {
                            window.location.reload();
                        }
                    });

                },
            },
            {
                text: "取消",
                click: function() {
                    $( this ).dialog( "close" );
                }
            }
        ]
    });
    $('#drawDialog').dialog({
        autoOpen: false,
        width:1500,

        buttons: [
            {
                text: "确定",
                click: function() {
//                    console.log($.toJSON($('#nowids').val()));
                    $.getJSON('index.php?r=draw/drawsave',{allid:$('#nowids').val(),'management_area':$('#AreaID').val()},function (data) {
                        if(data.state) {
//                            $(this).dialog("close");
                            window.location.reload();
                        }
                    });
                }
            },
            {
                text: "取消",
                click: function() {
                    $.getJSON('index.php?r=draw/drawdeleteall',{'management_area':$('#AreaID').val()});
                    $( this ).dialog( "close" );
                }
            }
        ]
    });
</script>
<script type="text/javascript">
    //设置显示的所有抽奖名字
    //最终抽奖结果会从下面的这个数组里选出
//    lis = ['小念','小狗狗'];
//
//    $('#start').click(function(){
//        $('#jiang').html('等待抽奖中');
//        immer = setInterval(function(){
//            num = Math.floor(Math.random() * list.length);
//            $('#box').html(list[num]);
//        },30)
//    });
//
//    $('#stop').click(function(){
//        clearInterval(immer);
//        num1 = Math.floor(Math.random() * lis.length);
//        $('#box').html(lis[num1]);
//        //设置抽奖结果
//        li = ['跳芭蕾舞','跳脱衣舞','一等奖','跳钢管舞'];
//        (function(){
//            se =  setInterval(function(){
//                nu = Math.floor(Math.random() * li.length);
//                $('#jiang').html(li[nu]);
//            },30);
//        })();
//    });
</script>
