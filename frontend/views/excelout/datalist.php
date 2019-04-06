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


<!--                    <div class="box-body">-->
                    <div style='margin-top: 20px;'>
                        <div style='float: left;'>
                            &nbsp;&nbsp;&nbsp;&nbsp;<input type="button" class="btn btn-default" value="导出xls" id='excelExport' onclick="closeDialog()"/>
                        </div>
                    </div>
                            <div class="text text-center"><H2>数据已经生成,可导出xls</H2></div>
                            <div id="jqxgrid"></div>

</div>
<div id="jqxLoader" class="text-red">
</div>

<script type="text/javascript">
function closeDialog() {
    $('#outDialog').dialog( "close" );
    setTimeout(function(){
        $("#loading").hide();
    },800);
}
    $(document).ready(function () {
        var employees = <?= json_encode($data)?>;
        var source =
        {
            localData: employees,

            datatype: "array",
            datafields: <?= json_encode($datafields)?>,

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
//                showstatusbar: true,
//                statusbarheight: 50,
//                showaggregates: true,
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
                pageSize: 15,
//                pageable: true,
//                pagerMode: 'advanced',
                pageSizeOptions: ['15', '20', '30', '50', '80', '100'],
//                editSettings: { saveOnPageChange: true, saveOnBlur: true, saveOnSelectionChange: true, cancelOnEsc: true, saveOnEnter: true, editSingleCell: true, editOnDoubleClick: true, editOnF2: true },
//                sortable: true,
                columns: <?= json_encode($columns)?>,

            });

        $("#excelExport").jqxButton();
        $("#excelExport").click(function () {
            $("#jqxgrid").jqxGrid('exportdata', 'xls', '<?= date('YmdHis')?>');
//            $("#jqxGrid").jqxGrid('exportdata', 'xls', '<?//= \app\models\User::getYear().'年'.Basedataverify::$tablename?>//', true, null, true, "http://land.app/save-file.php");
        });


    });
</script>
