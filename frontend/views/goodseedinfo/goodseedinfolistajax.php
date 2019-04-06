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
                    <?php
                    $newarea = bcsub($plantarea,$goodseedarea,2);
                    ?>
                    <?= Html::hiddenInput('plantarea',$plantarea,['id'=>'temp_plantarea'])?>
                    <?= Html::hiddenInput('plantingid',$planting_id,['id'=>'planting-id'])?>
                    <h3>种植作物:<?= Plant::findOne($plant_id)->typename; ?><span id="plantarea"><?= $plantarea?></span>(<span id="newarea"><?= $newarea?></span>亩)</h3></div>
                <div class="box-body">
                    <?php
                        echo Html::hiddenInput('plant_id',$plant_id,['id'=>'plant-id']);
                    ?>

                    <div class="form-group">
                        <?php
                        echo Html::button('增加', ['class' => 'btn btn-info', 'title' => '点击可增加一行', 'id' => 'add-goodseed']);
                        ?>
                    </div>
                    <?= Html::hiddenInput('temp_id',Goodseedinfo::isGoodseed($planting_id),['id'=>'temp-id'])?>
                    <table class="table table-bordered table-hover" id="Goodseed">
                        <thead id="goodseed-template" class="d-none">
                            <tr id="tr_0">
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
                            <?php if($goodseedinfo) {
                                foreach ($goodseedinfo as $key => $value) {
                                    $k = $key + 1;
                                    echo Html::hiddenInput('Goodseed[id][]',$value['id'],['id'=>'id_'.$k])
                                    ?>
                                    <tr id="tr_<?= $k?>">
                                        <td><?php echo Html::dropDownList('Goodseed[typename][]', $value['goodseed_id'], ArrayHelper::map(Goodseed::find()->where(['plant_id'=>$plant_id,'state'=>1])->all(), 'id', 'typename'),['class' => 'form-control goodseedtype','id'=>'type_'.$k]); ?></td>
                                        <td><?php echo Html::textInput('Goodseed[area][]', $value['area'], ['class' => 'form-control goodseedarea','id'=>'area_'.$k]); ?></td>
                                        <td valign="middle" align="center"><?php echo Html::button('-', ['class' => 'btn btn-warning delete-goodseed','id'=>'delete_'.$k]) ?></td>
                                    </tr>
                                <?php }}?>
                        </tbody>

                    </table>


                </div>
            </div>
        </div>
    </div>
</div>
<?php
//var_dump(Goodseed::isGoodseed($plant_id));exit;
?>
<script>
    $(document).ready(function(){
        if(Number($('#newarea').text()) > 0) {
            $('#add-goodseed').show();
        } else {
            $('#add-goodseed').hide();
        }
    });
    // 添加
    $('#add-goodseed').click(function () {
        var is = <?php echo Goodseed::isGoodseed($plant_id);?>;
//        alert(is);
        if(is > 0) {
            var tempid = $('#temp-id').val();
            ++tempid;
            $('#goodseed-template').find('select').attr('id', 'type_' + tempid);
            $('#goodseed-template').find('input').attr('id', 'area_' + tempid);
            $('#goodseed-template').find('tr').attr('id', 'td_' + tempid);

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
//        $('#goodseed-template').find('input').each(function (i,val) {
//            $(val).attr('id','');
//        });
            $('#goodseed-template').find('input').attr('id', '');
            $('#goodseed-template').find('select').attr('id', '');
            $('#goodseed-template').find('tr').attr('id', 'td_0');
        } else {
            var template = '<tr><td colspan="3">此作物还没有良种信息,如需要添加,请联系管理员。</td></tr>';
            $('#Goodseed > tbody').append(template);
        }
    });

    // 删除
    $(document).on("click", ".delete-goodseed", function () {
        $(this).parent().parent().remove();
        $('#add-goodseed').show();
        getPlantArea('sub');
        var id = $(this).attr('id');
        var arr = id.split('_');
        var temp_id = arr[1];
//        alert(temp_id);
        var typeid = $('#type_'+temp_id).val();
        var area = $('#area_'+temp_id).val();
        $.getJSON('index.php?r=goodseedinfo/goodseedinfodelete',{id:$('#id_'+temp_id).val()},function (data) {
            
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
            $('#newarea').text(to.toFixed(2));
        }
        if(str == 'add') {
            var to = area*1 + sum*1;
            $('#newarea').text(to.toFixed(2));
        }
    }

    $(document).on("change", ".goodseedarea", function () {
        var sum = getInputArea();
        var area = $('#temp_plantarea').val();
        if(sum == area) {
            $('#add-goodseed').hide();
        }
        if(sum < area) {
            $('#add-goodseed').show();
        }
        if(sum > area) {
            alert('对不起,输入的面积总和大于种植面积'+area+'亩,将自动截取为剩余面积。');
            $(this).val($('#newarea').text());
            $('#add-goodseed').hide();
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