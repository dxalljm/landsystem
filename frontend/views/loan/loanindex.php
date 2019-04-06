<?php
namespace frontend\controllers;
use app\models\Loan;
use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Reviewprocess;
use yii\helpers\Url;
use app\models\Processname;
use app\models\User;
use Yii;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\loanSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'loan';
$this->title = Tables::find()->where(['tablename'=>$this->title])->one()['Ctablename'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="loan-index">

   <section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 >
                        <?= Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmname']; ?><font color="red">(<?= User::getYear()?>年度)</font>
                    </h3>
                </div>
                <?php Farms::showRow($_GET['farms_id']);?>
				<?= Farms::showFarminfo2($_GET['farms_id'])?>
                <div class="box-body">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<?php if(!Farms::find()->where(['id'=>$_GET['farms_id']])->one()['locked']) {
	if(!Farms::isFarmsInfo($farm->id)) {
		if(User::disabled()) {
			echo Html::a('添加', '#', ['class' => 'btn btn-success', 'disabled' => User::disabled()]);
		} else {
			echo Html::a('添加', ['loancreate', 'farms_id' => $_GET['farms_id']], ['class' => 'btn btn-success']);
		}
    }} else {
		echo '<span class="text text-red>冻结中，不能添加贷款。</span>';
	}?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'farms_id',
            [
				'attribute' => 'mortgagearea',
				'value' => function($model) {
					return $model->mortgagearea.'亩';
				}
			],
			'mortgagebank',
			[
				'attribute' => 'mortgagemoney',
				'value' => function($model) {
					return $model->mortgagemoney.'万元';
				}
			],
			[
				'label'=>'贷款期限',
				'value' => function ($model) {
					return $model->begindate.'~'.$model->enddate;
			}
			],
			[
				'label' => '贷款状态',
				'value' => function ($model) {
					$farms = Farms::findOne($model->farms_id);
					if($farms->locked and $model->lock) {
						return '冻结中';
					} else {
						return '已解冻';
					}
			}
			],
			[
				'label' => '解冻日期',
				'value' => function ($model) {
				$farms = Farms::findOne($model->farms_id);
					if($farms->locked and $model->lock)
						return '';
					else
						return date('Y-m-d',$model->update_at);
			}
			],


            // 'mortgagetimelimit',
            [
            'label' => '审核状态',
            'attribute' => 'state',
            'format'=>'raw',
            'value' => function ($model) {
				if($model->reviewprocess_id == 0) {
					return '通过';
				}
            	$reviewprocess = Reviewprocess::find()->where(['id'=>$model->reviewprocess_id])->one();
            	$html = '';
            	$result = Reviewprocess::state($reviewprocess['state']);
//				var_dump($reviewprocess['state']);exit;
            	if($reviewprocess['state'] == 4) {
            		$html.= '<div class="btn-group">';
            		$html.=  '<div class="dropdown-toggle" data-toggle="dropdown" data-trigger="hover">';
            		$html.= $result.'<span class="caret"></span>';
            		$html.=  '</div>';
            		$html.=  '<ul class="dropdown-menu" role="menu">';
//					var_dump(Reviewprocess::getProcess($reviewprocess['operation_id']));exit;
            		foreach (Reviewprocess::getProcess($reviewprocess['operation_id']) as $val) {
            			//$html.= '<li><a href="#">111</a></li>';
            			$html.=  '<li>'.Processname::find()->where(['Identification'=>$val])->one()['processdepartment'].':'.Reviewprocess::state($reviewprocess[$val]).'</li>';
            		}
            		$html.=  '</ul>';
            		$html.=  '</div>';
            		return Html::a($html,'#',['class' => 'dropdown-toggle', ]);
            	} else
            		return $result;
            		
            } ],
            ['class' => 'frontend\helpers\eActionColumn'],
            [
//             		'label'=>'操作',
            		'format'=>'raw',
            		'value' => function($model,$key) {
						if($model->reviewprocess_id == 0) {
							return '';
						}
						$html = '';
// 						var_dump($model->farms_id);
							if (!Reviewprocess::loanISexamine($model->id)) {
								$url2 = Url::to(['loan/loandelete', 'id' => $model->id, 'farms_id' => $model->farms_id]);
								$option2 = '撤消';
								$title2 = '';
								$html .= Html::a($option2, $url2, [

									'title' => $title2,
									'class' => 'btn btn-danger	btn-xs',
									'data' => [
										'confirm' => '您确定要撤消这项吗？',
										'method' => 'post',
									],
								]);
								return $html;
							}
							if(User::getItemname('地产科')) {
								$reviewprocess = Reviewprocess::find()->where(['id'=>$model->reviewprocess_id])->one();
								$url = Url::to(['loan/loanupdate','id'=>$model->id,'farms_id'=>$model->farms_id]);
								if($reviewprocess['state'] == 8) {
									return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, [
											'title' => Yii::t('yii', '更新'),
											'data-pjax' => '0',
									]);
								}
							}
						return '';
            		}],
        ],
    ]); ?>
              </div>
            </div>
        </div>
    </div>
</section>
</div>
<div id="dialogMsg" title="信息" class="text-red">
	<table class="table table-bordered table-hover">
		<tr>
			<td>
				对不起!以下三项法人信息必须填写完整,不能为空,请到<strong><font color="red"><?= Html::a('法人信息',Url::to(['farmer/farmercreate','farms_id'=>$farm->id]))?></font></strong>中填写。
			</td>
		</tr>
		<tr>
			<td>
				<?php if($farm->cardid) {
					if(strlen($farm->cardid) == 18)
						echo '身份证信息:'.$farm->cardid.'   请检查是否正确。';
					else {
						echo '身份证信息不正确,请仔细检查';
					}
				} else {
					echo '身份证信息为空,请补充此信息。';
				}?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				if(empty($farm->address)) {
					echo '农场位置为空,请补充此信息。';
				} else {
					echo '农场位置信息:'.$farm->address.'   请检查是否正确。';
				}
				?>
			</td>
		</tr>
		<tr>
			<td>
				<?php
				if(empty($farm->longitude) or empty($farm->latitude)) {
					echo '农场坐标为空,请补充此信息。';
				} else {
					echo '农场坐标信息:'.$farm->longitude.'  '.$farm->latitude.'    请检查是否正确。';
				}
				?>
			</td>
		</tr>
	</table>
</div>
<script>
	$(document).ready(function(){
		if(<?= Farms::isFarmsInfo($farm->id)?> == 1) {
			$("#dialogMsg").dialog("open");
		}
	});
	$( "#dialogMsg" ).dialog({
		autoOpen: false,
		width: 600,
		open: function (event, ui) {
			$(".ui-dialog-titlebar-close", $(this).parent()).hide();
		},
		buttons: [
			{
				text: "确定",
				click: function() {
					$( this ).dialog( "close" );
					location.href = "<?= Url::to(['farms/farmsmenu','farms_id'=>$farm->id])?>";
				}

			},
		]
	});
</script>