<?php
namespace frontend\controllers;
use app\models\Goodseed;
use app\models\Plant;
use app\models\User;
use app\models\Tables;
use frontend\models\inputproductbrandmodelSearch;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Goodseedinfo;
/* @var $this yii\web\View */
/* @var $model app\models\Goodseed */

?>
<div class="goodseed">

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <?= Html::hiddenInput('plantarea',$input,['id'=>'temp_plantarea'])?>
                    <h3>种植作物:<?= Plant::findOne($plant_id)->typename; ?>(<span id="plantarea"><?= $input?></span>亩)</h3></div>
                <div class="box-body">
<!--                    --><?php
                    echo Html::hiddenInput('plant_id',$plant_id,['id'=>'plant-id']);
                    echo Html::hiddenInput('planter',$planter,['id'=>'Planter']);
                    echo Html::hiddenInput('type',$type,['id'=>'Type']);
                    echo Html::hiddenInput('id',$id,['id'=>'ID']);
                    echo Html::hiddenInput('id',$input,['id'=>'total_area']);
//                    echo Html::hiddenInput('input',$input,['id'=>'Input']);
//                    echo '<table>';
//                    echo '<tr>';
//                    echo '<td width="20%" align="right">良种:</td>';
//                    echo '<td>';
//                    echo Html::textInput('Goodseed',$goodseedtypename,['list'=>'selectList','id'=>'goodseedtype']);
//                    echo '<datalist id="selectList">';
//
//		            foreach ($goodseedlist as $value) {
//                        echo '<option>'.$value.'</option>';
//                    }
//                    echo '</datalist>';
//                    echo '</td>';
//                    echo '</tr>';
//                    echo '</table>';
//                    echo Html::hiddenInput('farms_id',$plant_id,['id'=>'farms-id']);
//                    echo Html::hiddenInput('management_area',$plant_id,['id'=>'plant-id']);
//                    echo '<table>';
//                    echo '<tr>';
//                    echo '<td width="20%" align="right">良种:</td>';
//                    echo '<td>';
//                    echo Html::dropDownList('Goodseed','',ArrayHelper::map(Goodseed::find()->all(),'id','typename'),['class'=>'form-control','list'=>'selectList','id'=>'goodseedtype']);
//                    echo '</td>';
//                    echo '<td>&nbsp;&nbsp;</td>';
//                    echo '<td>种植亩数:</td>';
//                    echo '<td>';
//                    echo Html::textInput('area','',['id'=>'Area','class'=>'form-control']);
//                    echo '</td>';
//                    echo '<td>操作</td>';
//                    echo '</tr>';
//                    echo '</table>';
                    ?>

                    <div class="form-group">
                        <?= Html::button('增加', ['class' => 'btn btn-info','title'=>'点击可增加一行', 'id' => 'add-goodseed']) ?>
                    </div>
                    <?= Html::hiddenInput('temp_id',0,['id'=>'temp-id'])?>
                    <table class="table table-bordered table-hover" id="Goodseed">
                        <thead id="goodseed-template" class="d-none">
                            <tr>
                                <td><?php echo Html::dropDownList('Goodseed[typename][]', '', ArrayHelper::map(Goodseed::find()->where(['plant_id'=>$plant_id,'state'=>1])->all(), 'id', 'typename'),['class' => 'form-control goodseedtype','id'=>'']); ?></td>
                                <td><?php echo Html::textInput('Goodseed[area][]', '', ['class' => 'form-control goodseedarea']); ?></td>
                                <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-goodseed']) ?></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td align='center'>良种</td>
                                <td align='center'>种植亩数</td>
                                <td align='center'>操作</td>
                            </tr>
                        </tbody>
                        <?php if($goodseedinfo) {
                            foreach ($goodseedinfo as $key => $value) {
                                $k = $key + 1;
                        ?>
                                <tr>
                                    <td><?php echo Html::dropDownList('Goodseed[typename][]', $value['goodseed_id'], ArrayHelper::map(Goodseed::find()->where(['plant_id'=>$plant_id,'state'=>1])->all(), 'id', 'typename'),['class' => 'form-control goodseedtype','id'=>'type_'.$k]); ?></td>
                                    <td><?php echo Html::textInput('Goodseed[area][]', $value['area'], ['class' => 'form-control goodseedarea','id'=>'area_'.$k]); ?></td>
                                    <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-goodseed','id'=>'delete_'.$k]) ?></td>
                                </tr>
                        <?php }}?>
                    </table>


                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // 添加雇工人员
    $('#add-goodseed').click(function () {
        alert(333);
        var isG = <?= Goodseed::isGoodseed($plant_id)?>;
        alert(isG);
        if(isG) {
            var tempid = $('#temp-id').val();
            ++tempid;
            $('#goodseed-template').find('select').attr('id','type_'+tempid);
            $('#goodseed-template').find('input').each(function (i,val) {
                if($(val).attr('class') == 'form-control goodseedarea') {
                    $('.goodseedarea').attr('id','area_'+tempid);
                }
//            if($(val).attr('class') == 'form-control delete-goodseed') {
//                $('.goodseedtype').attr('id','delete_'+tempid);
//            }
            });

//        $('.goodseedtype').attr('id','type_'+tempid);
//        $('.goodseedarea').attr('id','area_'+tempid);
//        $('.delete-goodseed').attr('id','delete_'+tempid);
//        $('#goodseed-template').find('td').each(function (i,val) {
//            $(val).attr('class','td_'+tempid+'_'+i);
//            console.log(val.attributes.class);
//        });
            $('#temp-id').val(tempid);
            var template = $('#goodseed-template').html();
            $('#Goodseed > tbody').append(template);
            $('#goodseed-template').find('input').each(function (i,val) {
                $(val).attr('id','');
            });
            $('#goodseed-template').find('select').attr('id','');
        } else {
            var template = '<tr><td colspan="3">此作物还没有良种信息,如需要添加,请联系管理员。</td></tr>';
            $('#Goodseed > tbody').append(template);
        }

    });

    // 删除
    $(document).on("click", ".delete-goodseed", function () {
        alert(444);
        $(this).parent().parent().remove();
        getPlantArea('sub');
        var id = $(this).attr('id');
        var arr = id.split('_');
        var temp_id = arr[1];
//        alert(temp_id);
        var typeid = $('#type_'+temp_id).val();
        var area = $('#area_'+temp_id).val();
        console.log(typeid);
        console.log(area);
        $.getJSON('index.php?r=goodseedinfo/goodseedinfodelete',{farms_id:<?= $_GET['farms_id']?>,typeid:typeid,area:area},function (data) {    
            
        });
    });

    function getInputArea() {
        var sum = 0;
        $('.goodseedarea').each(function (i,v) {
//            console.log(v.value);
            sum = sum*1 + v.value*1;
        });
        return sum;
    }

    function getPlantArea(str) {
        var sum = getInputArea();
        var area = $('#temp_plantarea').val();
        if(str == 'sub') {
            var to = area - sum.toFixed(2);
            $('#plantarea').text(to.toFixed(2));
        }
        if(str == 'add') {
            var to = area*1 + sum*1;
            $('#plantarea').text(to.toFixed(2));
        }
    }

    $(document).on("blur", ".goodseedarea", function () {
        var sum = getInputArea();
        var area = $('#temp_plantarea').val();
        if(sum > area) {
            alert('对不起,输入的面积总和大于种植面积'+area+'亩,将自动截取为剩余面积。');
            $(this).val($('#plantarea').text());
        } else {
            getPlantArea('sub');
        }
    });

//    $('#type_1').change(function () {
//        console.log($('#type_1 option:selected').text());
//    });

//    $(document).on("click", ".goodseedarea", function () {
//        var classname = $(this).attr('id');
////        console.log(classname);
//        var tempid = getNumber(classname);
//        var val = $('#type_'+tempid+' option:selected').val();
//        console.log(val);
//        if(val == '') {
//            alert('请先选择良种种类');
//            $('#type_'+tempid).focus();
//        }
//    });
//
//    function getNumber(str) {
//        var arr = str.split('_');
//        return arr[1];
//    }

</script>