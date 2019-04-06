<?php

use yii\helpers\Html;
use yii\web\View;
use yii\helpers\Url;
use frontend\helpers\ActiveFormrdiv;
use app\models\Farms;
use app\models\Plantingstructurecheck;
use app\models\User;
use app\models\ManagementArea;
use app\models\Lease;
use app\models\Basedataverify;
/* @var $this yii\web\View */
/* @var $model app\models\tables */
//var_dump($data);
//var_dump($result);
?>
<link rel="stylesheet" href="js/jqwidgets-ver4.5.1//jqwidgets/styles/jqx.base.css" type="text/css" />
<link href="vendor/bower/jquery-ui-1.11.4/jquery-ui.css" rel="stylesheet">
<!--<script type="text/javascript" src="js/jqwidgets-ver4.5.1//scripts/jquery-1.11.1.min.js"></script>-->
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxcore.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxbuttons.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxscrollbar.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxmenu.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxcheckbox.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxlistbox.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxdropdownlist.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxgrid.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxgrid.filter.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxgrid.edit.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxgrid.pager.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxgrid.selection.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxgrid.aggregates.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxgrid.columnsresize.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxprogressbar.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxloader.js"></script>

<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxdata.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxdata.export.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxgrid.export.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//jqwidgets/jqxgrid.sort.js"></script>
<script type="text/javascript" src="js/jquery.json-2.2.min.js"></script>
<script type="text/javascript" src="js/jqwidgets-ver4.5.1//scripts/demos.js"></script>

<div class="regular-import">
    <section class="content">
        <div class="row">
<!--            <div class="col-xs-12">-->
                <div class="box">

<!--                    <div class="box-body">-->
                    <div style='margin-top: 20px;'>
                        <div style='float: left;'>
                            &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="导出xls" id='excelExport'/>
                        </div>
                    </div>
                            <div class="text text-center"><H2><?= \app\models\User::getYear().'年岭南享受玉米、大豆生产者补贴汇总表'?></H2></div>
                            <div><span class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;单位名称:岭南管委会</span><span class="pull-right">单位:亩&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
                            <div id="jqxgrid"></div>

<!--                    </div>-->
                </div>
<!--            </div>-->
        </div>
        </section>
</div>
<div id="jqxLoader" class="text-red">
</div>

<script type="text/javascript">

    $(document).ready(function () {

        var employees = <?= json_encode($result)?>;

        var source =
        {
            localData: employees,

            datatype: "array",
            datafields: [
                {name : 'row', type : 'number'},
                {name : 'management_area', type: 'string'},
                {name : 'farmname', type: 'string'},
                {name : 'farmername', type: 'string'},
                {name : 'cardid', type: 'string'},
                {name : 'telephone', type: 'string'},
                {name : 'address', type: 'string'},
                {name : 'contractnumber', type: 'string'},
                {name : 'contractarea', type: 'string'},
                {name : 'content', type: 'string'}
            ],
            id: 'row',

        };


        var dataAdapter = new $.jqx.dataAdapter(source, {
            downloadComplete: function (data, status, xhr) { },
            loadComplete: function (data) { },
            loadError: function (xhr, status, error) { }
        });


        // create Tree Grid
        $("#jqxgrid").jqxGrid(
            {
                width: "100%",
//                altrows: true,
                sortable: true,
                showfilterrow: true,
                filterable: true,
                showstatusbar: true,
                statusbarheight: 50,
                showaggregates: true,
//                editable: true,
//                editmode: 'selectedrow',
//                enabletooltips: true,
                pageable: true,
                autoheight: true,
                columnsresize: true,
                pagermode: 'default',
//                selectionmode: 'multiplecellsadvanced',
//                showeverpresentrow: true,
//                showfilterrow: true,
//                everpresentrowactions: "add delete reset",
                source: dataAdapter,

//                filterable: true,
//                filterMode: 'simple',
//                columnsHeight: 50,
//                showToolbar: true,
//                altRows: true,
//                selectionmode: 'multiplecellsextended',
                pageSize: 20,
//                pageable: true,
//                pagerMode: 'advanced',
                pageSizeOptions: ['15', '20', '30', '50', '80', '100'],
//                editSettings: { saveOnPageChange: true, saveOnBlur: true, saveOnSelectionChange: true, cancelOnEsc: true, saveOnEnter: true, editSingleCell: true, editOnDoubleClick: true, editOnF2: true },
//                sortable: true,
                columns: [
                    {text : '序号',datafield :'row',width : 40,align:'center',editable:false,cellsAlign:"center"},
                    {text : '管理区',datafield :'management_area',width : 150,align:'center',cellsAlign:"center",filtertype: 'checkedlist'},
                    {text : '农场名称',datafield :'farmname',width : 100,align:'center',cellsAlign:"center"},
                    {text : '法人',datafield :'farmername',width : 100,align:'center',cellsAlign:"center"},
                    {text : '身份证号',datafield :'cardid',width : 100,align:'center',cellsAlign:"center"},
                    {text : '联系方式',datafield :'telephone',width : 100,align:'center',cellsAlign:"center"},
                    {text : '农场地址',datafield :'address',width : 100,align:'center',cellsAlign:"center"},
                    {text : '合同号',datafield :'contractnumber',width : 200,align:'center',cellsAlign:"center"},
                    {text : '合同面积',datafield :'contractarea',width : 100,align:'center',cellsAlign:"center"},
                    {text : '备注',datafield :'content',width : 320,align:'center',cellsAlign:"left"},
                ],
            });

        $("#excelExport").jqxButton();
        $("#excelExport").click(function () {
            $("#jqxgrid").jqxGrid('exportdata', 'xls', '<?= date('YmdHis')?>');
//            $("#jqxGrid").jqxGrid('exportdata', 'xls', '<?//= \app\models\User::getYear().'年'.Basedataverify::$tablename?>//', true, null, true, "http://land.app/save-file.php");
        });


    });
</script>
