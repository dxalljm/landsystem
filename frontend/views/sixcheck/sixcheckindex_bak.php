<?php

use yii\helpers\Html;
use yii\helpers\Url;
use frontend\helpers\ActiveFormrdiv;
use app\models\Farms;
use app\models\User;
/* @var $this yii\web\View */
/* @var $model app\models\Tables */
//var_dump($inputData);
?>
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
                    <div class="box-body">
                        <?= Farms::showFarminfo($_GET['farms_id'])?>
                        <table class="table table-bordered table-hover" id="lease">
                            <tbody>
                            <tr>
                                <td class="text text-right" colspan="12">是否租赁</td>
                                <td colspan="12"><?= Html::radioList('islease',1,['是','否'],['id'=>'is-lease'])?></td>
                                <td colspan="12"><?= Html::button('增加租赁信息', ['class' => 'btn btn-info','title'=>'点击可增加租赁者', 'disabled'=>true,'id' => 'add-lease','onclick'=>'addLease()']) ?></td>
                            </tr>
                            </tbody>
                        </table>
                        <div id="leaseinfo">

                        </div>
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
                                <td><?= Html::textInput('Plant[lease_id][]',$farm['farmername'],['class'=>'form-control','id'=>'plant-lease-0','readonly'=>true])?></td>
                                <td><?= Html::textInput('Plant[sum][]','0',['class'=>'form-control','id'=>'plant-sum-0','readonly'=>true])?></td>
                                <td><?= Html::textInput('Plant[dd][]','0',['class'=>'form-control','id'=>'plant-dd-0'])?></td>
                                <td><?= Html::textInput('Plant[xm][]','0',['class'=>'form-control','id'=>'plant-sm-0'])?></td>
                                <td><?= Html::textInput('Plant[mls][]','0',['class'=>'form-control','id'=>'plant-msl-0'])?></td>
                                <td><?= Html::textInput('Plant[zd][]','0',['class'=>'form-control','id'=>'plant-zd-0'])?></td>
                                <td><?= Html::textInput('Plant[other][]','0',['class'=>'form-control','id'=>'plant-other-0'])?></td>
                                <td><label><?= Html::checkbox('Plant[issurance][]',false,['id'=>'plant-issurance-0'])?>是</label></td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="table">
                            <tr class="bg-red">
                                <td ></td>
                            </tr>
                        </table>
                        <div id="insuranceinfo"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div id="js">

    </div>
    <div id="insuranceJS">

    </div>
    <?php
    echo html::textInput('row',1,['id'=>'rows']);
    ?>
</div>
<script>
    $(function () {
        $("[data-mask]").inputmask();
    });
    // 添加租赁
//    $('#add-lease').click(function () {
//        var template = $('#lease-template').html();
//        $('#leaseinfo').append(template);
//        var planttemp = $('#plant-template').html();
//        $('#plant > tbody').append(planttemp);
//    });

    // 删除租赁
    function deleteLease(id) {
        var nid = id*1 +1;
        $('#plantgs-'+nid).remove();
        $(document).on("click", ".delete-lease", function () {
            $(this).parent().parent().parent().remove();
            $("#plant tr:last").remove();
            $('#leaseredline').remove();
            var r = $('#rows').val();
            $('#rows').val(--r);
        });

    }

    // 删除保险
    function deleteInsurance(id)
    {
        $('#plant-issurance-'+id).attr("checked",false);
        $(document).on("click", ".delete-insurance", function () {
            $(this).parent().parent().parent().remove();
            $('#insuranceredline').remove();
            var r = $('#rows').val();
            $('#rows').val(--r);
        });
    }
    //如果有租赁,则增加按钮可使用
    $('#is-lease').change(function(){
        if($('input:radio[name="islease"]').prop('checked') == true) {
            $('#add-lease').attr('disabled',false);
        } else {
            $('#rows').val(1);
            $('#add-lease').attr('disabled',true);
            $(".leasetable").remove();
            $(".plantgs").remove();
        }
    });
    //如参加保险,则增加按钮可使用
    $('#is-insurance').change(function(){
        if($('input:radio[name="isInsurance"]').prop('checked') == true) {
            addInsurance();
        } else {
            $(".insurancetable").remove();
            $("#insuranceredline").remove();
        }
    });

    function getUNBFB(id,str) {
        var bfb = {'100%':'0%','90%':'10%','80%':'20%','70%':'30%','60%':'40%','50%':'50%','40%':'60%','30%':'70%','20%':'80%','10%':'90%','0%':'100%'};
        $("#"+id).val(bfb[str]);
    }

    function addInsurance(i) {
//        alert($('#plant-lessee-'+i).val());
        var html = '<table class="table table-bordered table-hover insurancetable">';
        html += '<tr>';
        html += '<td colspan="10" align="center" class=""><h4>种植业保险</h4></td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td align="center">投保人</td>';
        html += '<td align="center">被保险人</td>';
        html += '<td align="center">身份证号</td>';
        html += '<td align="center">联系电话</td>';
        html += '<td align="center">承保公司</td>';
        html += '<td align="center">计划投保面积</td>';
        html += '<td align="center">小麦</td>';
        html += '<td align="center">大豆</td>';
        html += '<td align="center">其他</td>';
        html += '<td rowspan="3" valign="middle"><button type="button" class="btn btn-warning delete-insurance" onclick="deleteInsurance('+i+')">-</button></td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td><input type="text" name="Insurance[policyholder2][]" id="Insurance-policyholder2-' + i + '" class="form-control" value="'+$('#Lease-lessee-'+i).val()+'"></td>';
        html += '<td><input type="text" name="Insurance[policyholder][]" id="Insurance-policyholder-' + i + '" class="form-control" value="'+$('#Lease-lessee-'+i).val()+'"></td>';
        html += '<td><input type="text" name="Insurance[cardid][]" id="Insurance-cardid-' + i + '" class="form-control" value="'+$('#Lease-cardid-'+i).val()+'"></td>';
        html += '<td><input type="text" name="Insurance[telephone][]" id="Insurance-telephone-' + i + '" class="form-control"></td>';
        html += '<td><input type="text" name="Insurance[company][]" id="Insurance-company-"' + i + '" class="form-control"></td>';
        html += '<td><input type="text" name="Insurance[insuredarea][]" id="Insurance-insuredarea-' + i + '" class="form-control"></td>';
        html += '<td><input type="text" name="Insurance[insuredwheat][]" id="Insurance-insuredwheat-' + i + '" class="form-control"></td>';
        html += '<td><input type="text" name="Insurance[insuredsoybean][]" id="Insurance-insuredsoybean-' + i + '" class="form-control"></td>';
        html += '<td><input type="text" name="Insurance[insuredother][]" id="Insurance-insuredother-' + i + '" class="form-control"></td>';
        html += '</tr>';
        html += '</table>';
        html += '<table class="table" id="insuranceredline"><tr class="bg-red"><td></td></tr></table>'
        $('#insuranceinfo').append(html);

//        $("#Lease-lessee-"+id).keyup(function(){$("#Plant-lessee-"'+id+').val($(this).val());});
        var html3 = '<script>';

        html3 += '<\/script>';
        $('#insuranceJS').append(html3);

    }
    $('#plant-issurance-0').change(function () {
        if ($(this).get(0).checked) {
           addInsurance(0);
        } else {
            $(".insurancetable").remove();
            $("#insuranceredline").remove();
        }
    });
    function addLease() {
        var id = $('#rows').val();
        html = '<input type="hidden" value="'+id+'" id="trID-'+id+'">';
        html += '<table class="table table-bordered table-hover leasetable">';
        html += '<tr>';
        html += '<td colspan="32" align="center" class=""><h4>承租人信息</h4></td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td colspan="4" align="right">种植者名称</td>';
        html += '<td colspan="4"><input type="text" name="Lease[lessee][]" id="Lease-lessee-'+id+'" class="form-control"></td>';
        html += '<td colspan="4" align="right">身份证号</td>';
        html += '<td colspan="4"><input type="text" name="Lease[cardid][]" id="Lease-cardid-'+id+'" class="form-control"></td>';
        html += '<td colspan="4" align="right">种植者电话</td>';
        html += '<td colspan="4"><input type="text" name="Lease[telephone][]" id="Lease-telephone-"'+id+'" class="form-control"></td>';
        html += '<td colspan="4" align="right">租赁面积</td>';
        html += '<td colspan="4"><input type="text" name="Lease[area][]" id="Lease-area-'+id+'" class="form-control"></td>';
        html += '<td rowspan="4" valign="middle"><button type="button" class="btn btn-warning delete-lease" onclick="deleteLease('+$("trID")+')">-</button></td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td colspan="32" align="center" class=""><h4>补贴归属</h4></td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td colspan="8" align="center" class="">综合直补</td>';
        html += '<td colspan="8" align="center" class="">大豆差价补贴</td>';
        html += '<td colspan="8" align="center" class="">良种补贴</td>';
        html += '<td colspan="8" align="center" class="">其他新增补贴</td>';
        html += '</tr>';
        html += '<tr>';
        html += '<td colspan="2" align="right" width="6%">法人占比</td>';
        html += '<td colspan="2" align="left" ><select class="" name="butie[zhzb-farmer][]" id="zhzb-farmer-'+id+'">';
        html += '<option value="0%">0%</option>';
        html += '<option value="10%">10%</option>';
        html += '<option value="20%">20%</option>';
        html += '<option value="30%">30%</option>';
        html += '<option value="40%">40%</option>';
        html += '<option value="50%">50%</option>';
        html += '<option value="60%">60%</option>';
        html += '<option value="70%">70%</option>';
        html += '<option value="80%">80%</option>';
        html += '<option value="90%">90%</option>';
        html += '<option value="100%" selected="selected">100%</option>';
        html += '</select></td>';
        html += '<td colspan="2" align="right" width="7%">承租人占比</td>';
        html += '<td colspan="2" align="left"><select class="" name="butie[zhzb-lessee][]" id="zhzb-lessee-'+id+'">';
        html += '<option value="0%">0%</option>';
        html += '<option value="10%">10%</option>';
        html += '<option value="20%">20%</option>';
        html += '<option value="30%">30%</option>';
        html += '<option value="40%">40%</option>';
        html += '<option value="50%">50%</option>';
        html += '<option value="60%">60%</option>';
        html += '<option value="70%">70%</option>';
        html += '<option value="80%">80%</option>';
        html += '<option value="90%">90%</option>';
        html += '<option value="100%">100%</option>';
        html += '</select></td>';

        html += '<td colspan="2" align="right" width="6%">法人占比</td>';
        html += '<td colspan="2" align="left" ><select class="" name="butie[ddcj-farmer][]" id="ddcj-farmer-'+id+'">';
        html += '<option value="0%">0%</option>';
        html += '<option value="10%">10%</option>';
        html += '<option value="20%">20%</option>';
        html += '<option value="30%">30%</option>';
        html += '<option value="40%">40%</option>';
        html += '<option value="50%">50%</option>';
        html += '<option value="60%">60%</option>';
        html += '<option value="70%">70%</option>';
        html += '<option value="80%">80%</option>';
        html += '<option value="90%">90%</option>';
        html += '<option value="100%" selected="selected">100%</option>';
        html += '</select></td>';

        html += '<td colspan="2" align="right" width="7%">承租人占比</td>';
        html += '<td colspan="2" align="left"><select class="" name="butie[ddcj-lessee][]" id="ddcj-lessee-'+id+'">';
        html += '<option value="0%">0%</option>';
        html += '<option value="10%">10%</option>';
        html += '<option value="20%">20%</option>';
        html += '<option value="30%">30%</option>';
        html += '<option value="40%">40%</option>';
        html += '<option value="50%">50%</option>';
        html += '<option value="60%">60%</option>';
        html += '<option value="70%">70%</option>';
        html += '<option value="80%">80%</option>';
        html += '<option value="90%">90%</option>';
        html += '<option value="100%">100%</option>';
        html += '</select></td>';
        html += '<td colspan="2" align="right" width="6%">法人占比</td>';
        html += '<td colspan="2" align="left"><select class="" name="butie[goodseed-farmer][]" id="goodseed-farmer-'+id+'">';
        html += '<option value="0%">0%</option>';
        html += '<option value="10%">10%</option>';
        html += '<option value="20%">20%</option>';
        html += '<option value="30%">30%</option>';
        html += '<option value="40%">40%</option>';
        html += '<option value="50%">50%</option>';
        html += '<option value="60%">60%</option>';
        html += '<option value="70%">70%</option>';
        html += '<option value="80%">80%</option>';
        html += '<option value="90%">90%</option>';
        html += '<option value="100%" selected="selected">100%</option>';
        html += '</select></td>';
        html += '<td colspan="2" align="right" width="7%">承租人占比</td>';
        html += '<td colspan="2" align="left"><select class="" name="butie[goodseed-lessee][]" id="goodseed-lessee-'+id+'">';
        html += '<option value="0%">0%</option>';
        html += '<option value="10%">10%</option>';
        html += '<option value="20%">20%</option>';
        html += '<option value="30%">30%</option>';
        html += '<option value="40%">40%</option>';
        html += '<option value="50%">50%</option>';
        html += '<option value="60%">60%</option>';
        html += '<option value="70%">70%</option>';
        html += '<option value="80%">80%</option>';
        html += '<option value="90%">90%</option>';
        html += '<option value="100%">100%</option>';
        html += '</select></td>';
        html += '<td colspan="2" align="right" width="6%">法人占比</td>';
        html += '<td colspan="2" align="left" ><select class="" name="butie[new-farmer][]" id="new-farmer-'+id+'">';
        html += '<option value="0%">0%</option>';
        html += '<option value="10%">10%</option>';
        html += '<option value="20%">20%</option>';
        html += '<option value="30%">30%</option>';
        html += '<option value="40%">40%</option>';
        html += '<option value="50%">50%</option>';
        html += '<option value="60%">60%</option>';
        html += '<option value="70%">70%</option>';
        html += '<option value="80%">80%</option>';
        html += '<option value="90%">90%</option>';
        html += '<option value="100%" selected="selected">100%</option>';
        html += '</select></td>';
        html += '<td colspan="2" align="right" width="7%">承租人占比</td>';
        html += '<td colspan="2" align="left"><select class="" name="butie[new-lessee][]" id="new-lessee-'+id+'">';
        html += '<option value="0%">0%</option>';
        html += '<option value="10%">10%</option>';
        html += '<option value="20%">20%</option>';
        html += '<option value="30%">30%</option>';
        html += '<option value="40%">40%</option>';
        html += '<option value="50%">50%</option>';
        html += '<option value="60%">60%</option>';
        html += '<option value="70%">70%</option>';
        html += '<option value="80%">80%</option>';
        html += '<option value="90%">90%</option>';
        html += '<option value="100%">100%</option>';
        html += '</select></td>';
        html += '</tr>';
        html += '</table>';
        html += '<table class="table" id="leaseredline"><tr class="bg-red"><td></td></tr></table>'
        $('#leaseinfo').append(html);
        var nid = id*1 + 1;
        $('#rows').val(nid);
        var html2 = '<tr id="plantgs-'+$('#rows').val()+'">';
        html2 += '<td class="text text-center">'+$('#rows').val()+'</td>';
        html2 += '<td><input type="text" name="Plant[lease_id][]" id="plant-lessee-'+id+'" class="form-control" readonly="readonly"></td>';
        html2 += '<td><input type="text" name="Plant[sum][]" id="plant-sum-'+id+'" value="0" class="form-control" readonly="readonly"></td>';
        html2 += '<td><input type="text" name="Plant[dd][]" id="plant-dd-'+id+'" value="0" class="form-control"></td>';
        html2 += '<td><input type="text" name="Plant[xm][]" id="plant-sm-'+id+'" value="0" class="form-control"></td>';
        html2 += '<td><input type="text" name="Plant[mls][]" id="plant-mls-'+id+'" value="0" class="form-control"></td>';
        html2 += '<td><input type="text" name="Plant[zd][]" id="plant-zd-'+id+'" value="0" class="form-control"></td>';
        html2 += '<td><input type="text" name="Plant[other][]" id="plant-other-'+id+'" value="0" class="form-control"></td>';
        html2 += '<td><label><input type="checkbox" id="plant-issurance-'+id+'" name="Plant[issurance][]" value="1">是</label></td>'
        html2 += '</tr>';
        $('#plant > tbody').append(html2);
//        $("#Lease-lessee-"+id).keyup(function(){$("#Plant-lessee-"'+id+').val($(this).val());});
        var html3 = '<script>';
        html3 += '$("#Lease-lessee-'+id+'").keyup(function(){$("#plant-lessee-'+id+'").val($(this).val());});';
        html3 += '$("#zhzb-farmer-'+id+'").change(function(){getUNBFB("zhzb-lessee-'+id+'",$(this).val());});';
        html3 += '$("#zhzb-lessee-'+id+'").change(function(){getUNBFB("zhzb-farmer-'+id+'",$(this).val());});';

        html3 += '$("#ddcj-farmer-'+id+'").change(function(){getUNBFB("ddcj-lessee-'+id+'",$(this).val());});';
        html3 += '$("#ddcj-lessee-'+id+'").change(function(){getUNBFB("ddcj-farmer-'+id+'",$(this).val());});';

        html3 += '$("#goodseed-farmer-'+id+'").change(function(){getUNBFB("goodseed-lessee-'+id+'",$(this).val());});';
        html3 += '$("#goodseed-lessee-'+id+'").change(function(){getUNBFB("goodseed-farmer-'+id+'",$(this).val());});';

        html3 += '$("#new-farmer-'+id+'").change(function(){getUNBFB("new-lessee-'+id+'",$(this).val());});';
        html3 += '$("#new-lessee-'+id+'").change(function(){getUNBFB("new-farmer-'+id+'",$(this).val());});';

        html3 += '$("#plant-dd-'+id+'").click(function () {var input = $(this).val();if(input == 0) {$(this).val("");}});';
        html3 += '$("#plant-sm-'+id+'").click(function () {var input = $(this).val();if(input == 0) {$(this).val("");}});';
        html3 += '$("#plant-mls-'+id+'").click(function () {var input = $(this).val();if(input == 0) {$(this).val("");}});';
        html3 += '$("#plant-zd-'+id+'").click(function () {var input = $(this).val();if(input == 0) {$(this).val("");}});';
        html3 += '$("#plant-other-'+id+'").click(function () {var input = $(this).val();if(input == 0) {$(this).val("");}});';
        html3 += '$("#plant-dd-'+id+'").keyup(function (event) {var input = $(this).val();if(event.keyCode == 8) {var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);}if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);} else {if (input !== ""){alert("输入的必须为数字");$(this).val("");var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);}}});';
        html3 += '$("#plant-sm-'+id+'").keyup(function (event) {var input = $(this).val();if(event.keyCode == 8) {var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);}if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);} else {if (input !== ""){alert("输入的必须为数字");$(this).val("");var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);}}});';
        html3 += '$("#plant-mls-'+id+'").keyup(function (event) {var input = $(this).val();if(event.keyCode == 8) {var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);}if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);} else {if (input !== ""){alert("输入的必须为数字");$(this).val("");var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);}}});';
        html3 += '$("#plant-zd-'+id+'").keyup(function (event) {var input = $(this).val();if(event.keyCode == 8) {var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);}if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);} else {if (input !== ""){alert("输入的必须为数字");$(this).val("");var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);}}});';
        html3 += '$("#plant-other-'+id+'").keyup(function (event) {var input = $(this).val();if(event.keyCode == 8) {var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);}if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);} else {if (input !== ""){alert("输入的必须为数字");$(this).val("");var result = $("#plant-dd-'+id+'").val()*1 + $("#plant-sm-'+id+'").val()*1 + $("#plant-mls-'+id+'").val()*1 + $("#plant-zd-'+id+'").val()*1 + $("#plant-other-'+id+'").val()*1;$("#plant-sum-'+id+'").val(result);}}});';
        html3 += '$("#plant-issurance-'+id+'").change(function () {if ($(this).get(0).checked) {addInsurance('+id+');} else {$(".insurancetable").remove();$(".insuranceredline").remove();}});';
        html3 += '<\/script>';
        $('#js').append(html3);

    }
    $("#plant-dd-0").click(function () {
        var input = $(this).val();
        if(input == 0) {
            $(this).val("");
        }
    });
    $("#plant-dd-0").keyup(function (event) {
        var input = $(this).val();
        if(event.keyCode == 8) {
            var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
            $("#plant-sum-0").val(result);
        }
        if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
            var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
            $("#plant-sum-0").val(result);
        } else {
            if (input !== ''){
                alert('输入的必须为数字');
                $(this).val("");
                var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
                $("#plant-sum-0").val(result);
            }
        }
    });
    $("#plant-sm-0").click(function () {
        var input = $(this).val();
        if(input == 0) {
            $(this).val("");
        }
    });
    $("#plant-sm-0").keyup(function (event) {
        var input = $(this).val();
        if(event.keyCode == 8) {
            var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
            $("#plant-sum-0").val(result);
        }
        if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
            var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
            $("#plant-sum-0").val(result);
        } else {
            if (input !== ''){
                alert('输入的必须为数字');
                $(this).val("");
                var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
                $("#plant-sum-0").val(result);
            }
        }
    });
    $("#plant-msl-0").click(function () {
        var input = $(this).val();
        if(input == 0) {
            $(this).val("");
        }
    });
    $("#plant-msl-0").keyup(function (event) {
        var input = $(this).val();
        if(event.keyCode == 8) {
            var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
            $("#plant-sum-0").val(result);
        }
        if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
            var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
            $("#plant-sum-0").val(result);
        } else {
            if (input !== ''){
                alert('输入的必须为数字');
                $(this).val("");
                var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
                $("#plant-sum-0").val(result);
            }
        }
    });
    $("#plant-zd-0").click(function () {
        var input = $(this).val();
        if(input == 0) {
            $(this).val("");
        }
    });
    $("#plant-zd-0").keyup(function (event) {
        var input = $(this).val();
        if(event.keyCode == 8) {
            var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
            $("#plant-sum-0").val(result);
        }
        if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
            var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
            $("#plant-sum-0").val(result);
        } else {
            if (input !== ''){
                alert('输入的必须为数字');
                $(this).val("");
                var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
                $("#plant-sum-0").val(result);
            }
        }
    });
    $("#plant-other-0").click(function () {
        var input = $(this).val();
        if(input == 0) {
            $(this).val("");
        }
    });
    $("#plant-other-0").keyup(function (event) {
        var input = $(this).val();
        if(event.keyCode == 8) {
            var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
            $("#plant-sum-0").val(result);
        }
        if(/^[0-9]{0}([0-9]|[.])+$/.test(input)) {
            var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
            $("#plant-sum-0").val(result);
        } else {
            if (input !== ''){
                alert('输入的必须为数字');
                $(this).val("");
                var result = $('#plant-dd-0').val()*1 + $("#plant-sm-0").val()*1 + $("#plant-msl-0").val()*1 + $("#plant-zd-0").val()*1 + $("#plant-other-0").val()*1;
                $("#plant-sum-0").val(result);
            }
        }
    });
</script>