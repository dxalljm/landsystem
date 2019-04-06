<?php
namespace frontend\controllers;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\ManagementArea;
use app\models\User;
?>
<object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width=0 height=0>
    <embed id="LODOP_EM" type="application/x-print-lodop" width=0 height=0></embed>
</object>
<div class="tempprintbill-view">
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                    <?= Html::a('打印', '#', ['class' => 'btn btn-success','onclick'=>'myPREVIEW()']) ?>
        			<?= Html::a('打印设计','#', ['class' => 'btn btn-success','onclick'=>'myDesign()']) ?>
                        <script language="javascript" type="text/javascript">
                            var LODOP; //声明为全局变量
                            window.onload = function() {
                                CreatePage();
                                LODOP.PREVIEW();
                            };
                            function myPREVIEW() {
                                CreatePage();
                                LODOP.PREVIEW();
                            };
                            function myDesign() {
                                CreatePage();
                                LODOP.PRINT_DESIGN();

                            };
                            function CreatePage(){
                                LODOP=getLodop();
                                LODOP.PRINT_INITA(10,10,"297mm","210mm","打印控件功能");
// 								LODOP.SET_PRINT_PAGESIZE('2','297mm','210mm');
                                LODOP.ADD_PRINT_TBURL(42,6,1078,48,"<?= Url::to(['print/sixtable','farms_id'=>$farms_id])?>");
                                LODOP.ADD_PRINT_TBURL(85,6,1078,863,"<?= Url::to(['print/hstable','farms_id'=>$farms_id])?>");
                                LODOP.ADD_PRINT_TBURL(720,6,1078,863,"<?= Url::to(['print/hsdowntable'])?>");
                                LODOP.ADD_PRINT_TEXT(10,300,657,33,"<?= User::getYear()?>年岭南生态示范区农业基础数据核实表");
                                LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
                                LODOP.SET_PRINT_STYLEA(0,"Bold",1);
                                LODOP.ADD_PRINT_TEXT(26,20,341,24,"管理区：<?= ManagementArea::getManagementareaName($farms_id)?>");
                                LODOP.ADD_PRINT_TEXT(26,900,160,25,"单位：亩、升、公斤、头");
                                LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED",1);
                            };
                            
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
