<?php
namespace frontend\controllers;

use Yii;
use app\models\Insurancecompany;
use app\models\Tables;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use yii\helpers\ArrayHelper;
use app\models\Farms;
use frontend\helpers\arraySearch;
use frontend\helpers\grid\GridView;
use app\models\ManagementArea;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\insuranceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Insurance';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;

$totalData = clone $dataProvider;
$totalData->pagination = ['pagesize'=>0];
// 	var_dump($totalData->getModels());exit;
$data = arraySearch::find($totalData)->search();
// var_dump($dataProvider->query->where);exit;
?>
<style>
.ui-dialog-titlebar-close {display:none;}
</style>
<div class="insurance-index">

    <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3>保险业务<font color="red">(<?= User::getYear()?>年度)</font></h3> <?= html::a('导出XLS',['insurance/insurancetoxls',$params],['class'=>'btn btn-success','id'=>'toXls']);?>
                </div>
                <div class="box-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'filterSelector' => "select[name='".$dataProvider->getPagination()->pageSizeParam."'],input[name='".$dataProvider->getPagination()->pageParam."']",
        'pager' => [
        		'class' => \frontend\helpers\LinkPager::className(),
        		'template' => '{pageButtons} {customPage} {pageSize}', //分页栏布局
        		'pageSizeList' => [10, 20, 30, 50], //页大小下拉框值
        		'customPageWidth' => 50,            //自定义跳转文本框宽度
        		'customPageBefore' => ' 跳转到第 ',
        		'customPageAfter' => ' 页  每页显示 ',
        ],
//          'total' => '<tr>
// 						<td></td>		
// 						<td align="center"><strong>'.$data->where(['state'=>1])->count('farms_id').'户</strong></td>
// 						<td><strong>法人'.$data->where(['nameissame'=>1,'state'=>1])->count().'个</strong></td>
// 						<td><strong>租赁'.$data->where(['nameissame'=>0,'state'=>1])->count().'个</strong></td>
// 						<td><strong>'.$data->where(['state'=>1])->sum('contractarea').'亩</strong></td>
// 						<td><strong>'.$data->where(['state'=>1])->sum('insuredarea').'亩</strong></td>
// 						<td><strong>'.$data->where(['state'=>1])->sum('insuredsoybean').'亩</strong></td>
// 						<td><strong>'.$data->where(['state'=>1])->sum('insuredwheat').'亩</strong></td>
// 						<td><strong>'.$data->where(['state'=>1])->sum('insuredother').'亩</strong></td>
// 						<td><strong>'.$data->count('company_id').'个</strong></td>
// 						<td><strong>完成'.$data->where(['state'=>1])->count().'个</strong></td>
// 						<td></td>
						
// 					</tr>',
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

//             'id',
            [
            	'attribute' => 'management_area',
            	'options' => ['width'=>"180"],
            	'value'=> function($model) {
				    return ManagementArea::getAreanameOne($model->management_area);
				},
        		'filter' => ManagementArea::getAreaname(),
            ],
            
//             'farms_id',
			[
				'label' => '农场名称',
                'attribute' => 'farmname',
				'options' => ['width'=>"180"],
				'value' => function ($model) {
					return Farms::findOne($model->farms_id)->farmname;
			}
			],
			[
				'attribute' => 'farmername',
				'options' => ['width'=>"110"],
				
			],
// 			'policyholder',
            [
                'attribute'=>'policyholder',
            		'options' => ['width'=>"110"],
            ],
//             'cardid',
//             'telephone',
			[
				'attribute' => 'contractarea',
				'options' => ['width'=>"120"]
			],
            [
				'attribute' => 'insuredarea',
            	'options' => ['width'=>"120"]
            ],
            [
	            'attribute' => 'insuredsoybean',
	            'options' => ['width'=>"120"]
            ],
            [
				'attribute' => 'insuredwheat',
            	'options' => ['width'=>"120"]
            ],
            [
				'attribute' => 'insuredother',
            	'options' => ['width'=>"120"]
            ],
           
            [
                'attribute'=>'company_id',
            	'options' => ['width'=>"220"],
                'value' => function($model) {
                    $result = Insurancecompany::find()->where(['id'=>$model->company_id])->one();
                    return $result['companynname'];
                },
                'filter' => ArrayHelper::map(Insurancecompany::find()->all(),'id','companynname'),
            ],
            // 'create_at',
            // 'update_at',
            // 'policyholdertime',
            // 'managemanttime',
            // 'halltime',
//             'year',
           
            [
                'label'=>'与保险公司一致',
            	'attribute'=>'isbxsame',
                'format'=>'raw',
                'options' => ['width'=>"100"],
                //'class' => 'btn btn-primary btn-lg',
                'value' => function($model){
                	if($model->isbxsame == 1)
                		return '一致';
                    return Html::checkbox('isbxsame','',['id'=>'isSameBx'.$model->id,'onclick'=>'openDialog('.$model->id.')']);
                },
                'filter' => [1=>'一致',0=>'不一致'],
            ],
            [
            'format'=>'raw',
            //'class' => 'btn btn-primary btn-lg',
            'value' => function($model){
                $options = [
                    'title' => Yii::t('yii', '更新'),
                    'aria-label' => Yii::t('yii', 'Update'),
                    'data-pjax' => '0',
                ];
                $url = Url::to(['insurance/insurancemodfiy','id'=>$model->id]);
                $html = Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                $html .= ' ';
                $printoptions = [
                    'title' => Yii::t('yii', '查看并打印'),
                    'aria-label' => Yii::t('yii', 'View'),
                    'data-pjax' => '0',
                ];

                $html .= Html::a('<span class="fa fa-print"></span>', Url::to(['insurance/insuranceprint','id'=>$model->id]), $printoptions);
//                $html .= Html::a('查看并打印',Url::to(['insurance/insuranceprint','id'=>$model->id]),['class'=>'btn btn-primary btn-xs']);

                return $html;
            }
        ]
        ],
    ]); ?>

                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?= Html::hiddenInput('nowid','',['id'=>'now-id'])?>
<div id="dialog" title="">
    确定与保险公司保险面积一致吗？
</div>
<div id="dialogToXls" title="正在生成xls文件...">
<?= Html::img('images/wait.gif')?>
</div>
<script>
$( "#dialogToXls" ).dialog({
    autoOpen: false,
    width: 300,
});
$('#toXls').click(function(){
	$( "#dialogToXls" ).dialog( "open" );
});
    $( "#dialog" ).dialog({
        autoOpen: false,
        width: 300,
        buttons: [
            {
                text: "确定",
                click: function() {
                    $( this ).dialog( "close" );
                    $.get("<?= Url::to(['insurance/insuranceissamebx'])?>", {id:$('#now-id').val()}, function (data){
                        
                        window.location.reload();
                    });
                }
            },
            {
                text: "取消",
                click: function() {
                    $( this ).dialog( "close" );
                    $('#isSameBx' + $('#now-id').val()).attr('checked',false);
                }
            }
        ]
    });
    function openDialog(id) {
        $('#now-id').val(id);
        $( "#dialog" ).dialog( "open" );
    }
</script>