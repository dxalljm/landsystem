<?php
namespace frontend\controllers;

use yii;
use app\models\Insurancecompany;
use app\models\Tables;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
use yii\helpers\ArrayHelper;
use app\models\Farms;
use frontend\helpers\arraySearch;
use frontend\helpers\grid\GridView;
use frontend\helpers\dbcache;
use app\models\ManagementArea;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\insuranceSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Insurance';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
//$data = new dbcache($dataProvider,100);
//var_dump($data->cache);exit;
//exit;
//if(!empty($data)) {
//    $totalData = clone $dataProvider;
//    $totalData->pagination = ['pagesize' => 0];
//// 	var_dump($totalData->getModels());exit;
//    $data = arraySearch::find($totalData)->search();
//}
//$insurance_dependency = [
//    'class' => 'yii\caching\DbDependency',
//    'sql' => 'SELECT MAX(update_at) FROM land_insurance',
//];
//
//if ($this->beginCache(Yii::$app->controller->action->id, ['dependency' => $insurance_dependency])) {
//
//'total' =>  '<tr>
//                                        <td></td>
//                                        <td align="center" id="t1"><strong>'.$data->where(['state'=>1])->count('farms_id').'户</strong></td>
//                                        <td id="t2"><strong>法人'.$data->where(['nameissame'=>1,'state'=>1])->count().'个</strong></td>
//                                        <td id="t3"><strong>租赁'.$data->where(['nameissame'=>0,'state'=>1])->count().'个</strong></td>
//                                        <td id="t4"><strong>'.$data->where(['state'=>1])->sum('contractarea').'亩</strong></td>
//                                        <td id="t5"><strong>'.$data->where(['state'=>1])->sum('insuredarea').'亩</strong></td>
//                                        <td id="t6"><strong>'.$data->where(['state'=>1])->sum('insuredsoybean').'亩</strong></td>
//                                        <td id="t7"><strong>'.$data->where(['state'=>1])->sum('insuredwheat').'亩</strong></td>
//                                        <td id="t8"><strong>'.$data->where(['state'=>1])->sum('insuredother').'亩</strong></td>
//                                        <td id="t9"><strong>'.$data->count('company_id').'个</strong></td>
//                                        <td id="t10"><strong>完成'.$data->where(['state'=>1])->count().'个</strong></td>
//                                        <td></td>
//                                        <td></td>
//                                    </tr>',
//var_dump($dataProvider->query->where);exit;

$arrclass = explode('\\',$dataProvider->query->modelClass);
?>
<script type="text/javascript" src="vendor/bower/CircleLoader/jquery.shCircleLoader-min.js"></script>
<link href="/vendor/bower/CircleLoader/jquery.shCircleLoader.css" rel="stylesheet">
<div class="insurance-index">

    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3>保险业务<font color="red">(<?= User::getYear()?>年度)</font></h3>
                    </div>
                    <div class="box-body">
                        <?php
//                        $dependency = [
//                            'class' => 'yii\caching\DbDependency',
//                            'sql' => 'SELECT MAX(update_at) FROM land_'.Yii::$app->controller->id,
//                        ];
//                        if ($this->beginCache(Yii::$app->controller->action->id, ['dependency' => $dependency])) {
                        echo GridView::widget([
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
                            'total' =>  '<tr height="40">
                                        <td></td>	
                                        
                                        <td align="center" id="t1"><strong>合计</strong></td>
                                        <td align="center" id="t2"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t3"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t4"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t5"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t6"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t7"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t8"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t9"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                        <td align="center" id="t10"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                        				<td align="center" id="t11"><strong><div class="shclDefault" style="width: 25px; height: 25px;"></div></strong></td>
                                    </tr>',
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                            		[
                            		'label'=>'管理区',
                            		'attribute'=>'management_area',
                            		'headerOptions' => ['width' => '130'],
                            		'value'=> function($model) {
                            			// 				            	var_dump($model);exit;
                            			return ManagementArea::getAreanameOne($model->management_area);
                            		},
                            		'filter' => ManagementArea::getAreaname(),
                            		],
                                [
                                    'label' => '农场名称',
                                	'attribute' => 'farms_id',
//                                     'options' => ['width' => "100"],
                                    'value' => function ($model) {
                                        return Farms::findOne($model->farms_id)->farmname;
                                    }
                                ],
                                [
                                    'attribute' => 'farmername',

                                ],
// 			'policyholder',
                                [
                                    'attribute' => 'policyholder',

                                ],
//             'cardid',
//             'telephone',
                                [
                                    'attribute' => 'contractarea',
                                    'options' => ['width' => "120"]
                                ],
                                [
                                    'attribute' => 'insuredarea',
                                    'options' => ['width' => "120"]
                                ],
                                [
                                    'attribute' => 'insuredsoybean',
                                    'options' => ['width' => "120"]
                                ],
                                [
                                    'attribute' => 'insuredwheat',
                                    'options' => ['width' => "120"]
                                ],
                                [
                                    'attribute' => 'insuredother',
                                    'options' => ['width' => "120"]
                                ],

                                [
                                    'attribute' => 'company_id',
                                    'value' => function ($model) {
                                        $result = Insurancecompany::find()->where(['id' => $model->company_id])->one();
                                        return $result['companynname'];
                                    },
                                    'filter' => ArrayHelper::map(Insurancecompany::find()->all(), 'id', 'companynname'),
                                ],
                                // 'create_at',
                                // 'update_at',
                                // 'policyholdertime',
                                // 'managemanttime',
                                // 'halltime',
//             'year',
                                [
                                    'label' => '状态',
                                    'attribute' => 'select',
                                    'options' => ['width' => '160'],
                                    'format' => 'raw',
                                    'value' => function ($model) {
                                        if ($model->state == -1)
                                            return '<font color="red">取消</font>';
                                        if ($model->state == 1) {
                                            if ($model->contractarea !== $model->insuredarea)
                                                return '<font color="red">完成</font>';
                                            else
                                                return '<font color="green">完成</font>';
                                        }
                                        if ($model->fwdtstate == 1)
                                            return '服务大厅审核通过';
                                        return '审核中';
                                    },
                                    'filter' => ['dsh' => '待审核', 'finished' => '完成', 'cancal' => '取消', 'issame' => '面积不一致', 'isselfselect' => '不是本人选择']
                                ],
                                [
                                    'label' => '操作',
                                    'format' => 'raw',
                                    'options' => ['width' => "200"],
                                    //'class' => 'btn btn-primary btn-lg',
                                    'value' => function ($model) {
                                        $html = '';
                                        if ($model->fwdtstate == 2) {
                                            if (isset($_GET['page']))
                                                return Html::a('审核', Url::to(['insurance/insurancefwdtcheckup', 'id' => $model->id, 'page' => $_GET['page']]), ['class' => 'btn btn-danger btn-xs']);
                                            else
                                                return Html::a('审核', Url::to(['insurance/insurancefwdtcheckup', 'id' => $model->id]), ['class' => 'btn btn-danger btn-xs']);
                                        }
                                        if($model->fwdtstate == 1){
                                            if (User::getItemname('地产科'))
                                                return Html::a('查看并打印', Url::to(['insurance/insuranceprint', 'id' => $model->id]), ['class' => 'btn btn-primary btn-xs']);
                                            else {
                                                if ($model->state !== -1) {
                                                    if ((time() - $model->create_at) <= 48 * 3600) {
                                                        $html .= Html::a('取消申请', '#', [
                                                            'class' => 'btn btn-danger btn-xs',
                                                            'onclick' => 'openDialog("' . $model->id . '")',
//                                        'data' => [
//                                            'confirm' => '您确定要取消申请吗？',
//                                            'method' => 'post',
//                                        ],
                                                        ]);
                                                        $html .= '&nbsp;';
                                                    }
                                                    $html .= Html::a('查看', Url::to(['insurance/insurancechiefview', 'id' => $model->id]), ['class' => 'btn btn-primary btn-xs']);
                                                } else
                                                    $html .= Html::a('查看', Url::to(['insurance/insurancechiefview', 'id' => $model->id]), ['class' => 'btn btn-danger btn-xs']);

                                                return $html;

                                            }
                                        }

                                    },

                                ],
//            [
//            'format'=>'raw',
//            //'class' => 'btn btn-primary btn-lg',
//            'value' => function($model){
//                return Html::a('查看并打印',Url::to(['insurance/insuranceprint','id'=>$model->id]),['class'=>'btn btn-primary btn-xs']);
//            }
//        ]
                            ],
                        ]);
//                        $this->endCache();
//                        }
                        ?>
                </div>
            </div>
        </div>
    </div>
</section>
</div>
<?= Html::hiddenInput('nowid','',['id'=>'now-id'])?>
<div id="dialog" title="您确定要取消这次申请吗？请在下面注明取消事由。">
    <table width=100%>
        <tr>
            <td align="left">取消事由:</td>
        </tr>
        <tr>
            <td><?= html::textarea('statecontent','',['id'=>'stateContent','rows'=>5,'cols'=>78])?></td>
        </tr>
    </table>
</div>
<script>
    $( "#dialog" ).dialog({
        autoOpen: false,
        width: 600,
        buttons: [
            {
                text: "确定",
                click: function() {
                    $( this ).dialog( "close" );
                    $.get("<?= Url::to(['insurance/insurancecancel'])?>", {id:$('#now-id').val(),content:$('#stateContent').val()}, function (data){
                        window.location.reload();
                    });
                }
            },
            {
                text: "取消",
                click: function() {
                    $( this ).dialog( "close" );
                }
            }
        ]
    });
    function openDialog(id) {
        $('#now-id').val(id);
        $( "#dialog" ).dialog( "open" );
    }
    $('.shclDefault').shCircleLoader();
    $(document).ready(function () {
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id'}, function (data) {
            $('#t2').html(data + '户');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-nameissame',andwhere:'<?= json_encode(['nameissame'=>1])?>'}, function (data) {
            $('#t3').html('法人'+data + '个');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-nameissame',andwhere:'<?= json_encode(['nameissame'=>0])?>'}, function (data) {
            $('#t4').html('租赁'+data + '人');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-contractarea'}, function (data) {
            $('#t5').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-insuredarea'}, function (data) {
            $('#t6').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-insuredsoybean'}, function (data) {
            $('#t7').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-insuredwheat'}, function (data) {
            $('#t8').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'sum-insuredother'}, function (data) {
            $('#t9').html(data + '亩');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-company_id'}, function (data) {
            $('#t10').html(data + '个');
        });
        $.getJSON('index.php?r=search/search', {modelClass: '<?= $arrclass[2]?>',where:'<?= json_encode($dataProvider->query->where)?>',command:'count-farms_id',andwhere:'<?= json_encode(['state'=>1])?>'}, function (data) {
            $('#t11').html('完成'+data+'个');
        });
    });


</script>