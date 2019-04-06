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
                            <div class="text text-center"><H2><?= \app\models\User::getYear().'年'.Basedataverify::$plantablename?></H2></div>
                            <div><span class="pull-left">&nbsp;&nbsp;&nbsp;&nbsp;管理区:<?= implode(',',$areaname)?></span><span class="pull-right">单位:亩&nbsp;&nbsp;&nbsp;&nbsp;</span></div>
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
                {name : 'farmercardid', type: 'string'},
                {name : 'farmertelephone', type: 'string'},
                {name : 'contractnumber', type: 'string'},
                {name : 'lease', type: 'string'},
                {name : 'cardid', type: 'string'},
                {name : 'telephone', type: 'string'},
                {name : 'contractarea', type: 'number'},
                {name : 'area', type: 'number'},
                {name : 'ddarea', type: 'number'},
                {name : 'ddzongdi', type: 'string'},
                {name : 'ddbtfarmer', type: 'string'},
                {name : 'ddbtlease', type: 'string'},
                {name : 'ymarea', type: 'number'},
                {name : 'ymzongdi', type: 'string'},
                {name : 'ymbtfarmer', type: 'string'},
                {name : 'ymbtlease', type: 'string'},
                {name : 'xm', type: 'number'},
                {name : 'mls', type: 'number'},
                {name : 'zd', type: 'number'},
                {name : 'by', type: 'number'},
                {name : 'lm', type: 'number'},
                {name : 'other', type: 'number'},
//                {name : 'verifydate', type: 'string'},
//                {name : 'content', type: 'string'}
            ],
            id: 'row',

        };

        var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            if (value !== $("#jqxgrid").jqxGrid('getcellvalue', row, 'farmername')) {
                return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + '; color: #ff0000;">' + value + '</span>';
            }
            else {
                return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + '; color: #008000;">' + value + '</span>';
            }
        }
        var areacellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties, rowdata) {
            if (value !== $("#jqxgrid").jqxGrid('getcellvalue', row, 'contractarea')) {
                return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + '; color: #ff0000;">' + value + '</span>';
            }
            else {
                return '<span style="margin: 4px; float: ' + columnproperties.cellsalign + '; color: #008000;">' + value + '</span>';
            }
        }

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
                    {text : '管理区',datafield :'management_area',width : 150,editable:false,align:'center',cellsAlign:"center",filtertype: 'checkedlist'},
                    {text : '农场',datafield :'farmname',width : 150,editable:false,align:'center',cellsAlign:"center"},
                    {text : '法人身份证',datafield :'farmercardid',width : 200,editable:false,align:'center',cellsAlign:"center"},
                    {text : '法人联系电话',datafield :'farmertelephone',width : 150,editable:false,align:'center',cellsAlign:"center"},
                    {text : '法人', datafield :'farmername',width : 100,editable:false,align:'center',cellsAlign:"center"},
                    {text : '合同号', datafield :'contractnumber',width : 150,editable:false,align:'center',cellsAlign:"center"},
                    {text : '种植者',datafield :'lease',width : 100,align:'center',cellsAlign:"center",cellsrenderer: cellsrenderer},
                    {text : '身份证号',datafield :'cardid',width : 200,align:'center',cellsAlign:"center"},
                    {text : '联系方式',datafield :'telephone',width : 150,align:'center',cellsAlign:"center"},
                    {text : '合同面积',datafield :'contractarea',width : 150,editable:false,align:'center',cellsAlign:"right",cellsformat: 'd2',aggregates: [{ '<b>合计</b>':
                        function (aggregatedValue, currentValue, column, record) {
//                            return aggregatedValue+currentValue;
                            return <?= $contractareaSum?>;
                        }
                    }],
//                        aggregatesrenderer: function (aggregates) {
//                            var renderstring = "";
//                            $.each(aggregates, function (key, value) {
//                                var name = key == 'min' ? 'Min' : 'Max';
//                                renderstring += '<div style="position: relative; margin: 4px; overflow: hidden;">' + name + ': ' + value + '</div>';
//                            });
//                            return renderstring;
                    },
                    {text : '种植面积',datafield :'area',width : 150,editable:false,align:'center',cellsAlign:"right",cellsformat: 'd2',cellsrenderer: areacellsrenderer,aggregates: [{ '<b>合计</b>':
                        function (aggregatedValue, currentValue, column, record) {
                            return aggregatedValue+currentValue;
                        }
                    }]},
                    {text : '面积',datafield :'ddarea',width : 150,align:'center','columngroup' : 'dd',cellsAlign:"right",cellsformat: 'd2',aggregates: [{ '<b>合计</b>':
                        function (aggregatedValue, currentValue, column, record) {
                            return aggregatedValue+currentValue;
                        }
                    }]},
                    {text : '地号',datafield :'ddzongdi',width : 100,align:'center','columngroup' : 'dd',cellsAlign:"left"},
                    {text : '补贴归属(法人)',datafield :'ddbtfarmer',width : 100,align:'center','columngroup' : 'dd',cellsAlign:"right"},
                    {text : '补贴归属(种植者)',datafield :'ddbtlease',width : 100,align:'center','columngroup' : 'dd',cellsAlign:"right"},
                    {text : '面积',datafield :'ymarea',width : 150,align:'center','columngroup' : 'ym',cellsAlign:"right",cellsformat: 'd2',aggregates: [{ '<b>合计</b>':
                        function (aggregatedValue, currentValue, column, record) {
                            return aggregatedValue+currentValue;
                        }
                    }]},
                    {text : '地号',datafield :'ymzongdi',width : 100,align:'center','columngroup' : 'ym',cellsAlign:"left"},
                    {text : '补贴归属(法人)',datafield :'ymbtfarmer',width : 100,align:'center','columngroup' : 'ym',cellsAlign:"right"},
                    {text : '补贴归属(种植者)',datafield :'ymbtlease',width : 100,align:'center','columngroup' : 'ym',cellsAlign:"right"},
                    {text : '小麦',datafield :'xm',width : 150,align:'center',cellsAlign:"right",cellsformat: 'd2',aggregates: [{ '<b>合计</b>':
                            function (aggregatedValue, currentValue, column, record) {
                                return aggregatedValue+currentValue;
                            }
                        }]
                    },
                    {text : '马铃薯',datafield :'mls',width : 150,align:'center',cellsAlign:"right",cellsformat: 'd2',aggregates: [{ '<b>合计</b>':
                        function (aggregatedValue, currentValue, column, record) {
                            return aggregatedValue+currentValue;
                        }
                    }]},
                    {text : '杂豆',datafield :'zd',width : 150,align:'center',cellsAlign:"right",cellsformat: 'd2',aggregates: [{ '<b>合计</b>':
                        function (aggregatedValue, currentValue, column, record) {
                            return aggregatedValue+currentValue;
                        }
                    }]},
                    {text : '北药',datafield :'by',width : 150,align:'center',cellsAlign:"right",cellsformat: 'd2',aggregates: [{ '<b>合计</b>':
                        function (aggregatedValue, currentValue, column, record) {
                            return aggregatedValue+currentValue;
                        }
                    }]},
                    {text : '蓝莓',datafield :'lm',width : 150,align:'center',cellsAlign:"right",cellsformat: 'd2',aggregates: [{ '<b>合计</b>':
                        function (aggregatedValue, currentValue, column, record) {
                            return aggregatedValue+currentValue;
                        }
                    }]},
                    {text : '其它',datafield :'other',width : 150,align:'center',cellsAlign:"right",cellsformat: 'd2',aggregates: [{ '<b>合计</b>':
                        function (aggregatedValue, currentValue, column, record) {
                            return aggregatedValue+currentValue;
                        }
                    }]},
//                    {text : '核实日期',datafield :'verifydate',width : 100,align:'center',cellsAlign:"right"},
//                    {text : '备注',datafield :'content',width : 200,align:'center',cellsAlign:"left"},
                ],
                columnGroups: [
                    {text : '大豆', align :'center', name :'dd'},
                    {text : '玉米', align :'center', name :'ym'},
                ],
            });

        $("#excelExport").jqxButton();
        $("#excelExport").click(function () {
            $("#jqxgrid").jqxGrid('exportdata', 'xls', '<?= date('YmdHis')?>');
//            $("#jqxGrid").jqxGrid('exportdata', 'xls', '<?//= \app\models\User::getYear().'年'.Basedataverify::$tablename?>//', true, null, true, "http://land.app/save-file.php");
        });


    });
</script>
