<?php
namespace frontend\controllers;
use app\models\Plantingstructure;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Plant;
use Yii;
use yii\helpers\Url;
use app\models\ManagementArea;
use app\models\Farms;
use frontend\helpers\arraySearch;
use app\models\User;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\plantSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$totalData = clone $dataProvider;
$totalData->pagination = ['pagesize'=>0];
$data = arraySearch::find($totalData)->search();
?>
<script type="text/javascript" src="js/jquery.json-2.2.min.js"></script>

<div class="plant-index">

	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<h3>&nbsp;&nbsp;&nbsp;&nbsp;综合打印</h3></div>
						<p>
        
        <?= Html::a('全部打印', '#', ['class' => 'btn btn-success','onclick'=>'myPREVIEW()']) ?>
        <?= Html::a('按页打印', '#', ['class' => 'btn btn-success','onclick'=>'myPREVIEWpage()']) ?>
        <?php
        echo Html::hiddenInput('lessee','',['id'=>'lesseelist']);
        ?>
    </p>
					<div class="box-body" id="print">
						<?= GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'filterSelector' => "select[name='" . $dataProvider->getPagination()->pageSizeParam . "'],input[name='" . $dataProvider->getPagination()->pageParam . "']",
                            'pager' => [
                                'class' => \frontend\helpers\LinkPager::className(),
                                'template' => '{pageButtons} {customPage} {pageSize}', //分页栏布局
                                'pageSizeList' => [10, 20, 30, 50, 200], //页大小下拉框值
                                'customPageWidth' => 50,            //自定义跳转文本框宽度
                                'customPageBefore' => ' 跳转到第 ',
                                'customPageAfter' => ' 页  每页显示 ',
                            ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
            	'attribute' => 'management_area',
            	'headerOptions' => ['width' => '200'],
				'value'=> function($model) {
				     return ManagementArea::getAreanameOne($model->management_area);
				 },
				 'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
            ],
            [
            	'attribute' => 'farmname',

            ],
            'farmername',
//             [
//             	'label' => '管理区',
//               	'attribute' => 'areaname',      						
//             	'value' => 'managementarea.areaname',
//             ],
			//'management_area',
            'contractarea',
            'contractnumber',
         			
            ['class' => 'frontend\helpers\eActionColumn'],
            
            [
            'label'=>'操作',
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model,$key){            	
            	return Html::a('打印','#', [
            		'class'=>'btn btn-success btn-xs',
            		'onclick' => 'myPREVIEWone('.$model->id.',"'.$model->farmerpinyin.'")'
            	]);
            }
            ],
        ],
    ]); ?>

					</div>
				</div>
			</div>
		</div>
	</section>
</div>

<script language="javascript" type="text/javascript">
	var LODOP; //声明为全局变量
    LODOP=getLodop();
    LODOP.SET_LICENSES("","1B34A57BE904141D3A3B3071D83B717F","C94CEE276DB2187AE6B65D56B3FC2848","");
    function writeLessee(farms_id)
    {
        //$('#lesseelist').val('');
        $.ajaxSettings.async = false;
        $.getJSON("<?= Url::to(['print/getlessee'])?>", {farms_id: farms_id}, function (data) {
            //console.log(data);
            $('#lesseelist').val(data.str);
//            var str = $('#lesseelist').val();
//            alert(str);
        });
        $.ajaxSettings.async = true;
    }

    function myPREVIEW() {
        CreatePage();
        //LODOP.PREVIEW();
    };
    function myPREVIEWpage() {
        CreatePagepage();
        //LODOP.PREVIEW();
    };


    function myPREVIEWone(farms_id,farmerpinyin) {
        writeLessee(farms_id);
        CreateOnePage(farms_id,farmerpinyin);
        LODOP.PREVIEW();
//        LODOP.PRINT();
    };
    function myDesign() {
        CreatePage();
        LODOP.PRINT_DESIGN();

    };

	function CreatePage(){

//        LODOP.PRINT_INITA(0,0,"297mm","210mm","打印控件功能");
        LODOP.SET_PRINT_PAGESIZE(2,"0","0","A4");
		<?php foreach ($data->all() as $farm) {?>
        var farms_id = <?= $farm['id']?>;
        var farmerpinyin = "<?= $farm['farmerpinyin']?>";
        create(farms_id,farmerpinyin);
        <?php }?>
	};
    function CreatePagepage(){
//        LODOP=getLodop();
        LODOP.PRINT_INITA(0,0,"297mm","210mm","打印控件功能");

        <?php foreach ($dataProvider->getModels() as $farm) {?>
        var farms_id = <?= $farm['id']?>;
        var farmerpinyin = "<?= $farm['farmerpinyin']?>";
        create(farms_id,farmerpinyin);
        <?php }?>
    };

	function CreateOnePage(farms_id,farmerpinyin){
//        LODOP=getLodop();
        LODOP.PRINT_INITA(0,0,"297mm","210mm","打印控件功能");

        var str = $('#lesseelist').val();
        var lessees = str.split(',');
        for(i=0;i<lessees.length;i++) {
            var py = farmerpinyin;

            LODOP.NewPage();
            LODOP.ADD_PRINT_TBURL(66,16,1078,140,"index.php?r=print/farminfotable&farms_id="+farms_id);
            if(lessees[i] == 0) {
                LODOP.ADD_PRINT_TBURL(187,15,1078,500,"index.php?r=print/hstable&farms_id="+farms_id);
            } else {
                var m=i*1+1;
                LODOP.ADD_PRINT_TBURL(187,15,1078,763,"index.php?r=print/hstablelease&farms_id="+farms_id + "&lease_id="+lessees[i]);
                py = farmerpinyin + '-' + m;
            }
            LODOP.ADD_PRINT_TBURL(700,16,1078,863,"<?= Url::to(['print/hsdowntable'])?>");
            LODOP.ADD_PRINT_TEXT(10,320,657,33,"<?= User::getYear()?>年岭南生态示范区农业基础数据核实表");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",18);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(46,20,341,24,"管理区：<?= ManagementArea::getNowManagementareaName()?>");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(46,980,160,25,"单位：亩");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(735,1030,160,25,py);
            LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED",1);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        }
    };

    function create(farms_id,farmerpinyin){
        writeLessee(farms_id);
        var str = $('#lesseelist').val();
        var lessees = str.split(',');
        LODOP.SET_PRINT_PAGESIZE(2,"210mm","297mm","");
        for(i=0;i<lessees.length;i++) {
            var py = farmerpinyin;
            LODOP.NewPage();
            LODOP.ADD_PRINT_TBURL(66,16,1078,140,"index.php?r=print/farminfotable&farms_id="+farms_id);
            if(lessees[i] == 0) {
                LODOP.ADD_PRINT_TBURL(187,15,1078,500,"index.php?r=print/hstable&farms_id="+farms_id);
            } else {
                var m=i*1+1;
                LODOP.ADD_PRINT_TBURL(187,15,1078,763,"index.php?r=print/hstablelease&farms_id="+farms_id + "&lease_id="+lessees[i]);
                py = farmerpinyin + '-' + m;
            }
            LODOP.ADD_PRINT_TBURL(700,16,1078,863,"<?= Url::to(['print/hsdowntable'])?>");
            LODOP.ADD_PRINT_TEXT(20,320,657,33,"<?= User::getYear()?>年岭南生态示范区农业基础数据核实表");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",18);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(46,20,341,24,"管理区：<?= ManagementArea::getNowManagementareaName()?>");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(46,980,160,25,"单位：亩");
            LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
            LODOP.ADD_PRINT_TEXT(735,1030,160,25,py);
            LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED",1);
            LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
            LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        }
        LODOP.PRINT();
//        LODOP.PREVIEW();
    };

</script>