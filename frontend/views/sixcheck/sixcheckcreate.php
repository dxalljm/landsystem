<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use frontend\helpers\ActiveFormrdiv;
use app\models\Farms;
use app\models\User;
use app\models\Insurancecompany;
use app\models\Goodseed;
use app\models\Inputproduct;
use app\models\Pesticides;
use app\models\Plant;
/* @var $this yii\web\View */
/* @var $model app\models\Tables */
//var_dump($inputData);
?>
<style>
    td{
        vertical-align: middle !important;
    }
</style>
<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.js"></script>
<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.date.extensions.js"></script>
<script src="/vendor/bower/AdminLTE/plugins/input-mask/jquery.inputmask.extensions.js"></script>
<div class="sixcheck-index">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3>农业基础数据调查表<font color="red">(<?= User::getYear()?>年度)</font></h3></div>
<!--                    <div class="box-body">-->
                        <?= Farms::showFarminfo($_GET['farms_id'])?>
                        <table class="table table-bordered table-hover" id="plant">

                            <tbody>
                            <tr class="">
                                <td class="text text-center" colspan="9"><h4>种植结构申报</h4></td>
                            </tr>
                            <tr>
                                <td class="text text-center">序号</td>
                                <td class="text text-center">种植者</td>
                                <td class="text text-center">合计</td>
                                <td class="text text-center">大豆(亩)</td>
                                <td class="text text-center">小麦(亩)</td>
                                <td class="text text-center">马铃薯(亩)</td>
                                <td class="text text-center">杂豆(亩)</td>
                                <td class="text text-center">其他(亩)</td>
                                <td class="text text-center">是否参加保险</td>
                            </tr>
                            <tr>
                                <td class="text text-center">1</td>
                                <td><?= Html::textInput('Plant[lease_id][]',$farm['farmername'],['class'=>'form-control','id'=>'plant-lease','readonly'=>true])?></td>
                                <td><?= Html::textInput('Plant[sum][]','0',['class'=>'form-control','id'=>'plant-sum','readonly'=>true])?></td>
                                <td><?= Html::textInput('Plant[dd][]','0',['class'=>'form-control','id'=>'plant-dd'])?></td>
                                <td><?= Html::textInput('Plant[xm][]','0',['class'=>'form-control','id'=>'plant-sm'])?></td>
                                <td><?= Html::textInput('Plant[mls][]','0',['class'=>'form-control','id'=>'plant-msl'])?></td>
                                <td><?= Html::textInput('Plant[zd][]','0',['class'=>'form-control','id'=>'plant-zd'])?></td>
                                <td><?= Html::textInput('Plant[other][]','0',['class'=>'form-control','id'=>'plant-other'])?></td>
                                <td><label><?= Html::checkbox('Plant[issurance][]',false,['id'=>'plant-issurance'])?>是</label></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered table-hover" id="plantinput">
                            <thead>
                            <tr>
                                <td rowspan="2" align="center">作物名称</td>
                                <td align="center">良种</td>
                                <td align="center">投入品父类</td>
                                <td align="center">投入品子类</td>
                                <td align="center">投入品</td>
                                <td align="center" width="10%">投入品用量</td>
                                <td align="center">农药</td>
                                <td align="center" width="10%">农药用量</td>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    <div id="insuranceinfo"></div>
                    <div class="d-none" id="insurance">
                        <table class="table table-bordered table-hover" id="insurancetable">
                            <tr data-height="3px">
                                <td colspan="10" class="bg-red"></td>
                            </tr>
                            <tr>
                            <tr>
                                <td colspan="10" align="center" class=""><h4>种植业保险</h4></td>
                                </tr>
                            <tr>
                                <td align="center">投保人</td>
                                <td align="center">被保险人</td>
                                <td align="center">身份证号</td>
                                <td align="center">联系电话</td>
                                <td align="center">承保公司</td>
                                <td align="center">计划投保面积</td>
                                <td align="center">小麦</td>
                                <td align="center">大豆</td>
                                <td align="center">其他</td>
                            </tr>
                            <tr>
                                <td><?= Html::textInput('Insurance[policyholder2][]',$farm['farmername'],['class'=>'form-control','id'=>'Insurance-policyholder2'])?></td>
                                <td><?= Html::textInput('Insurance[policyholder][]',$farm['farmername'],['class'=>'form-control','id'=>'Insurance-policyholder'])?></td>
                                <td><?= Html::textInput('Insurance[cardid][]',$farm['cardid'],['class'=>'form-control','id'=>'Insurance-cardid'])?></td>
                                <td><?= Html::textInput('Insurance[telephone][]',$farm['telephone'],['class'=>'form-control','id'=>'Insurance-telephone'])?></td>
                                <td><?= Html::dropDownList('Insurance[companyname][]','',\yii\helpers\ArrayHelper::map(Insurancecompany::find()->all(),'id','companynname'),['class'=>'form-control','id'=>'Insurahce-companyname'])?></td>
                                <td><?= Html::textInput('Insurance[insurancearea][]',$farm['contractarea'],['class'=>'form-control','id'=>'Insurance-insurancearea','realonly'=>true])?></td>
                                <td><?= Html::textInput('Insurance[insuredwheat][]','',['class'=>'form-control','id'=>'Insurance-insuredwheat'])?></td>
                                <td><?= Html::textInput('Insurance[insuredsoybean][]','',['class'=>'form-control','id'=>'Insurance-insuredsoybean'])?></td>
                                <td><?= Html::textInput('Insurance[insuredother][]','',['class'=>'form-control','id'=>'Insurance-insuredother'])?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<script>
    $('#plant-issurance').change(function () {
        if ($(this).get(0).checked) {
            var html = $('#insurance').html();
            $('#insuranceinfo').append(html);
        } else {
            $("#insurancetable").remove();
            $("#insuranceredline").remove();
        }
    });
    function getUNBFB(id,str) {
        var bfb = {'100%':'0%','90%':'10%','80%':'20%','70%':'30%','60%':'40%','50%':'50%','40%':'60%','30%':'70%','20%':'80%','10%':'90%','0%':'100%'};
        $("#"+id).val(bfb[str]);
    }
    $("#plant-dd").click(function () {
        var input = $(this).val();
        if(input == 0) {
            $(this).val("");
        }
    });
    $("#plant-dd").change(function () {
        var input = $(this).val();
        if(input !== '') {
            addPlantInput('plant-dd');
        } else {
            $('#plantinput-dd').remove();
        }
    });
    $("#plant-dd").keyup(function (event) {
        var input = $(this).val();
        if(event.keyCode == 8) {
            var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
            $("#plant-sum").val(result);
        }
        if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
            var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
            $("#plant-sum").val(result);
            isContractarea("plant-dd");
        } else {
            if (input !== ''){
                alert('输入的必须为数字');
                $(this).val("");
                var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
                $("#plant-sum").val(result);
            }
        }
    });
    $("#plant-sm").change(function () {
        var input = $(this).val();
        if(input !== '') {
            addPlantInput('plant-sm');
        }
    });
    $("#plant-sm").click(function () {
        var input = $(this).val();
        if(input == 0) {
            $(this).val("");
        }
    });
    $("#plant-sm").keyup(function (event) {
        var input = $(this).val();
        if(event.keyCode == 8) {
            var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
            $("#plant-sum").val(result);
        }
        if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
            var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
            $("#plant-sum").val(result);
            isContractarea("plant-sm");
        } else {
            if (input !== ''){
                alert('输入的必须为数字');
                $(this).val("");
                var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
                $("#plant-sum").val(result);
            }
        }
    });
    $("#plant-msl").change(function () {
        var input = $(this).val();
        if(input !== '') {
            addPlantInput('plant-msl');
        }
    });
    $("#plant-msl").click(function () {
        var input = $(this).val();
        if(input == 0) {
            $(this).val("");
        }
    });
    $("#plant-msl").keyup(function (event) {
        var input = $(this).val();
        if(event.keyCode == 8) {
            var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
            $("#plant-sum").val(result);
        }
        if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
            var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
            $("#plant-sum").val(result);
            isContractarea("plant-msl");
        } else {
            if (input !== ''){
                alert('输入的必须为数字');
                $(this).val("");
                var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
                $("#plant-sum").val(result);
            }
        }
    });
    $("#plant-zd").change(function () {
        var input = $(this).val();
        if(input !== '') {
            addPlantInput('plant-zd');
        }
    });
    $("#plant-zd").click(function () {
        var input = $(this).val();
        if(input == 0) {
            $(this).val("");
        }
    });
    $("#plant-zd").keyup(function (event) {
        var input = $(this).val();
        if(event.keyCode == 8) {
            var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
            $("#plant-sum").val(result);
        }
        if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
            var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
            $("#plant-sum").val(result);
            isContractarea("plant-zd");
        } else {
            if (input !== ''){
                alert('输入的必须为数字');
                $(this).val("");
                var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
                $("#plant-sum").val(result);
            }
        }
    });
    $("#plant-other").click(function () {
        var input = $(this).val();
        if(input == 0) {
            $(this).val("");
        }
    });
    $("#plant-other").keyup(function (event) {
        var input = $(this).val();
        if(event.keyCode == 8) {
            var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
            $("#plant-sum").val(result);
        }
        if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
            var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
            $("#plant-sum").val(result);
            isContractarea("plant-other");
        } else {
            if (input !== ''){
                alert('输入的必须为数字');
                $(this).val("");
                var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
                $("#plant-sum").val(result);
            }
        }
    });
    function addPlantInput(id) {
        var idArr = id.split('-');
        switch (idArr[1]) {
            case 'dd':
                var plant = '大豆';
                var seedlist = <?= json_encode(ArrayHelper::map(Goodseed::find()->where(['plant_id'=>Plant::find()->where(['typename'=>'大豆'])->one()['id']])->all(),'id','typename'));?>;
                var inputs = <?= json_encode(ArrayHelper::map(Inputproduct::find()->where(['father_id'=>1])->all(),'id','fertilizer'))?>;
                var pesticides = <?= json_encode(ArrayHelper::map(Pesticides::find()->all(),'id','pesticidename'))?>;
                break;
            case 'sm':
                var plant = '小麦';
                var seedlist = <?= json_encode(ArrayHelper::map(Goodseed::find()->where(['plant_id'=>Plant::find()->where(['typename'=>'小麦'])->one()['id']])->all(),'id','typename'));?>;
                var inputs = <?= json_encode(ArrayHelper::map(Inputproduct::find()->where(['father_id'=>1])->all(),'id','fertilizer'))?>;
                var pesticides = <?= json_encode(ArrayHelper::map(Pesticides::find()->all(),'id','pesticidename'))?>;
                break;
            case 'msl':
                var plant = '马铃薯';
                var seedlist = <?= json_encode(ArrayHelper::map(Goodseed::find()->where(['plant_id'=>Plant::find()->where(['typename'=>'马铃薯'])->one()['id']])->all(),'id','typename'));?>;
                var inputs = <?= json_encode(ArrayHelper::map(Inputproduct::find()->where(['father_id'=>1])->all(),'id','fertilizer'))?>;
                var pesticides = <?= json_encode(ArrayHelper::map(Pesticides::find()->all(),'id','pesticidename'))?>;
                break;
            case 'zd':
                var plant = '杂豆';
                var seedlist = <?= json_encode(ArrayHelper::map(Goodseed::find()->where(['plant_id'=>Plant::find()->where(['typename'=>'杂豆'])->one()['id']])->all(),'id','typename'));?>;
                var inputs = <?= json_encode(ArrayHelper::map(Inputproduct::find()->where(['father_id'=>1])->all(),'id','fertilizer'))?>;
                var pesticides = <?= json_encode(ArrayHelper::map(Pesticides::find()->all(),'id','pesticidename'))?>;
                break;
            case 'other':
                var plant = '其他';
                var seedlist = '';
                var inputs = <?= json_encode(ArrayHelper::map(Inputproduct::find()->where(['father_id'=>1])->all(),'id','fertilizer'))?>;
                var pesticides = <?= json_encode(ArrayHelper::map(Pesticides::find()->all(),'id','pesticidename'))?>;
                break;
        }

        var html = '<tr id="plantinput-'+idArr[1]+'">';
        html += '<td align="center">'+plant+'</td>';
        html += '<td><select class="form-control" name="input[goodseed][]" id="input-goodseed-'+idArr[1]+'">';
        html += '<option value="prompt">请选择...</option>';
        if(seedlist !== '') {
            $.each(seedlist, function (key, val) {
                html += '<option value="'+key+'">' + val + '</option>';
            });
        }
        html += '</select></td>';
        html += '<td><select class="form-control" name="input[inputfather][]" id="input-inputfather-'+idArr[1]+'">';
        html += '<option value="prompt">请选择...</option>';
        if(inputs !== '') {
            $.each(inputs, function (key, val) {
                html += '<option value="'+key+'">' + val + '</option>';
            });
        }
        html += '</select></td>';
        html += '<td><select class="form-control" name="input[inputson][]" id="input-inputson-'+idArr[1]+'">';
        html += '</select></td>';
        html += '<td><select class="form-control" name="input[inpu][]" id="input-input-'+idArr[1]+'">';
        html += '</select></td>';
        html += '<td><input type="text" class="form-control" name="input[inputnumber][]" id="input-inputnumber-'+idArr[1]+'"</td>';
        html += '<td><select class="form-control" name="input[pesticides][]" id="input-pesticides-'+idArr[1]+'">';
        html += '<option value="prompt">请选择...</option>';
        if(pesticides !== '') {
            $.each(pesticides, function (key, val) {
                html += '<option value="'+key+'">' + val + '</option>';
            });
        }
        html += '</select></td>';
        html += '<td><input type="text" class="form-control" name="input[pesticidesnumber][]" id="input-pesticidesnumber-'+idArr[1]+'"</td>';
        html += '</tr>';

//        html += '<table class="table" id="insuranceredline"><tr class="bg-red"><td></td></tr></table>'
        if ( $("#plantinput-"+idArr[1]).length == 0 ) {
            $('#plantinput > tbody').append(html);
        }


    }
    // 投入品子类联动
    $(document).on("change", "select[name='input[inputfather][]']", function () {
        // 投入品子类，投入品
        var fertilizerChild = $(this).parent().next().children(),
            father_id = $(this).val();

        // 请求二级分类
        $.getJSON('index.php?r=inputproduct/inputproductgetfertilizer', {father_id: father_id}, function (data) {
            fertilizerChild.html(null);
            fertilizerChild.append('<option value="prompt">请选择</option>');
            if (data.status == 1 && data.inputproductson !== null) {
                for(i = 0; i < data.inputproductson.length; i++) {
                    fertilizerChild.append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
                }
            }
        });
    });

    // 投入品选择
    $(document).on("change", "select[name='input[inputson][]']", function () {
        // 投入品子类，投入品
        var product = $(this).parent().next().children(),
            father_id = $(this).val();

        // 请求二级分类
        $.getJSON('index.php?r=inputproduct/inputproductgetfertilizer', {father_id: father_id}, function (data) {
            product.html(null);
            product.append('<option value="prompt">请选择</option>');
            if (data.status == 1 && data.inputproductson !== null) {
                for(i = 0; i < data.inputproductson.length; i++) {
                    product.append('<option value="'+data.inputproductson[i]['id']+'">'+data.inputproductson[i]['fertilizer']+'</option>');
                }
            }
        });
    });
    //判断填写的种植结构面积是否超过合同面积
    function isContractarea(id) {
        var contractarea = <?= $farm['contractarea']?>;
        var sum = $('#plant-sum').val();
        if(sum > contractarea) {
            alert('对不起,输入的面积总和已经超过合同面积,请检查后重新输入。');
            $('#'+id).val("");
            var result = $('#plant-dd').val()*1 + $("#plant-sm").val()*1 + $("#plant-msl").val()*1 + $("#plant-zd").val()*1 + $("#plant-other").val()*1;
            $("#plant-sum").val(result);
        }
    }
</script>