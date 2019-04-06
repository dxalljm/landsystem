<?php
namespace frontend\controllers;
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

    function myPREVIEW() {
        CreatePage();
        LODOP.PREVIEW();
    };
    function myPREVIEWpage() {
        CreatePagepage();
        LODOP.PREVIEW();
    };
    function myPREVIEWone(farms_id,farmerpinyin) {
        CreateOnePage(farms_id,farmerpinyin);
        LODOP.PREVIEW();
    };
    function myDesign() {
        CreatePage();
        LODOP.PRINT_DESIGN();

    };

	function CreatePage(){
		LODOP=getLodop();
		LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_整页缩放打印输出");
		<?php foreach ($data->all() as $farm) {?>
		LODOP.NewPage();
// 		LODOP.SET_PRINT_PAGESIZE(2,'297mm','210mm');
		LODOP.ADD_PRINT_TBURL(42,16,1078,48,"<?= Url::to(['print/sixtable','farms_id'=>$farm['id']])?>");
        LODOP.ADD_PRINT_TBURL(85,16,1078,863,"<?= Url::to(['print/leasetable','farms_id'=>$farm['id']])?>");
        LODOP.ADD_PRINT_TBURL(724,16,1078,863,"<?= Url::to(['print/downtable'])?>");
        LODOP.ADD_PRINT_TEXT(10,300,657,33,"<?= User::getYear()?>年度岭南生态农业示范区农业基础数据调查表");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        LODOP.ADD_PRINT_TEXT(26,20,341,24,"管理区：<?= ManagementArea::getManagementareaName($farm['id'])?>");
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        LODOP.ADD_PRINT_TEXT(26,900,160,25,"单位：亩、升、公斤、头");
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        LODOP.ADD_PRINT_TEXT(730,1050,160,25,"<?= $farm['farmerpinyin']?>");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED",1);
        <?php }?>
	};
    function CreatePagepage(){
        LODOP=getLodop();
        LODOP.PRINT_INIT("打印控件功能演示_Lodop功能_整页缩放打印输出");
        <?php foreach ($dataProvider->getModels() as $farm) {?>
        LODOP.NewPage();
// 		LODOP.SET_PRINT_PAGESIZE(2,'297mm','210mm');
        LODOP.ADD_PRINT_TBURL(42,16,1078,48,"<?= Url::to(['print/sixtable','farms_id'=>$farm['id']])?>");
        LODOP.ADD_PRINT_TBURL(85,16,1078,863,"<?= Url::to(['print/leasetable','farms_id'=>$farm['id']])?>");
        LODOP.ADD_PRINT_TBURL(724,16,1078,863,"<?= Url::to(['print/downtable'])?>");
        LODOP.ADD_PRINT_TEXT(10,300,657,33,"<?= User::getYear()?>年度岭南生态农业示范区农业基础数据调查表");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        LODOP.ADD_PRINT_TEXT(26,20,341,24,"管理区：<?= ManagementArea::getManagementareaName($farm['id'])?>");
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        LODOP.ADD_PRINT_TEXT(26,900,160,25,"单位：亩、升、公斤、头");
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        LODOP.ADD_PRINT_TEXT(730,1050,160,25,"<?= $farm['farmerpinyin']?>");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED",1);
        <?php }?>
    };
	function CreateOnePage(farms_id,farmerpinyin){
        LODOP=getLodop();
        LODOP.PRINT_INITA(0,0,"297mm","210mm","打印控件功能");
//			LODOP.SET_PRINT_PAGESIZE('2','297mm','210mm');
        LODOP.ADD_PRINT_TBURL(42,16,1078,100,"<?= Url::to(['print/sixtable','farms_id'=>$farm['id']])?>");
        LODOP.ADD_PRINT_TBURL(95,16,1078,863,"<?= Url::to(['print/leasetable','farms_id'=>$farm['id']])?>");
        LODOP.ADD_PRINT_TBURL(724,16,1078,863,"<?= Url::to(['print/downtable'])?>");
        LODOP.ADD_PRINT_TEXT(10,300,657,33,"<?= User::getYear()?>年度岭南生态农业示范区农业基础数据调查表");
        LODOP.SET_PRINT_STYLEA(0,"FontSize",14);
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
        LODOP.ADD_PRINT_TEXT(26,20,341,24,"管理区：<?= ManagementArea::getNowManagementareaName()?>");
        LODOP.ADD_PRINT_TEXT(26,900,160,25,"单位：亩、升、公斤、头");
        LODOP.ADD_PRINT_TEXT(730,1050,160,25,farmerpinyin);
        LODOP.SET_SHOW_MODE("LANDSCAPE_DEFROTATED",1);
        LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
        LODOP.SET_PRINT_STYLEA(0,"Bold",1);
    };
</script>