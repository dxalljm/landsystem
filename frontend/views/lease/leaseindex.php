<?php

use app\models\Tables;
use yii\helpers\Html;
use frontend\helpers\grid\GridView;
use app\models\Farms;
use app\models\Lease;
use app\models\Plantingstructure;
use yii\helpers\Url;
use app\models\Theyear;
use app\models\User;
use frontend\helpers\htmlColumn;
/* @var $this yii\web\View */
/* @var $searchModel frontend\models\leaseSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
?>
<div class="lease-index">
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        <?= Farms::find()->where(['id'=>$_GET['farms_id']])->one()['farmname']; ?><font color="red">(<?= User::getYear()?>年度)</font>
                    </h3>
                </div>
                <?php Farms::showRow($_GET['farms_id']);?>
                <div class="box-body">
					<?php
//					var_dump($areas);
					?>
<!--	--><?php
		?>

					<?php
					$years = [];
					for($i=date('Y');$i<date('Y')+3;$i++) {
						$years[] = $i;
					}
//					var_dump($years);
					?>
					<table>
						<tr>
							<td>
								<li class="dropdown">
									<a class=" btn btn-success dropdown-toggle" href="#" data-toggle="dropdown"><?= $year?>年度 <span class="caret"></span></a>
									<ul class="dropdown-menu" role="menu">
										<?php

										foreach ($years as $y) {
											?>
											<li><a href="<?= Url::to(['lease/leaseindex','farms_id'=>$_GET['farms_id'],'year'=>$y])?>" id="selectYear"><?= $y?>年度</a></li>
										<?php }?>
									</ul>
								</li>
							</td>
							<td>
								<?php if(Lease::getAllLeaseArea($_GET['farms_id'],$year) < $farm['contractarea'] and Plantingstructure::getWriteArea($_GET['farms_id'],$year) < $farm['contractarea']) {
									if(User::disabled()) {
										echo Html::a('添加', '#', ['class' => 'btn btn-success','disabled'=>User::disabled()]);
									} else {
										echo Html::a('添加', ['leasecreate','farms_id'=>$_GET['farms_id'],'year'=>$year], ['class' => 'btn btn-success']);
									}
								}?>
							</td>
						</tr>
					</table>



	<script type="text/javascript">
	function openwindows(url)
	{
		window.open(url,'','width=1200,height=600,top=50,left=80, toolbar=no, status=no, menubar=no, resizable=no, scrollbars=yes');
		self.close();
	}
	</script>
	<?php 
		$plantings = Plantingstructure::find()->where(['farms_id'=>$_GET['farms_id'],'year'=>$year])->all();
		$sum = 0.00;
		if($plantings) {
			foreach ($plantings as $planting) {
				$sum += (float)$planting['area'];
			}
		}
//	var_dump($overarea);var_dump($sum);
//	var_dump($sum);

	?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'lessee',
//             [
//             	'attribute' => 'lease_area',
//             	'label' => '宗地',
//             	'value' => function ($model) {
//             		if(strlen($model->lease_area)>70){ 
//             			return substr($model->lease_area,0,70).'......';
//             		} else 
//             			return $model->lease_area;
            		
//             	}
//             ],
			[
			'label' => '总面积',
			'value' => function ($model) {
				return Farms::find()->where(['id'=>$model->farms_id])->one()['contractarea'].'亩';
			},
			],
            [
            	'label' => '租赁面积',
            	'value' => function ($model) {
            		return Lease::getListArea($model->lease_area).'亩';
           		},
            ],
            //'plant_id',
            //'farms_id',

             [
            'class' => 'frontend\helpers\eActionColumn',
            'template' => '{view} {update} {delete} {print} {plant}',
            'buttons' => [
                // 下面代码来自于 yii\grid\ActionColumn 简单修改了下
                'view' => function ($url, $model, $key) {
//					$action = $this->controller.'view';
                    $options = [
                        'title' => Yii::t('yii', '查看'),
                        'aria-label' => Yii::t('yii', 'View'),
                        'data-pjax' => '0',
                    ];
                    $url.='&farms_id='.$_GET['farms_id'];
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, $options);
                },
                'update' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', '更新'),
                        'aria-label' => Yii::t('yii', 'Update'),
                        'data-pjax' => '0',
                    ];
                    $url.='&farms_id='.$_GET['farms_id'].'&year='.$model->year;
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, $options);
                },
                'delete' => function ($url, $model, $key) {
                    $options = [
                        'title' => Yii::t('yii', '删除'),
                        'aria-label' => Yii::t('yii', 'Delete'),
                        'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                        'data-method' => 'post',
                        'data-pjax' => '0',
                    ];
                    $url.='&farms_id='.$_GET['farms_id'].'&year='.$model->year;;
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, $options);
                },
                'plant' => function ($url, $model, $key) {
                	$over = (float)Plantingstructure::getOverArea($model->id, $_GET['farms_id'],$model->year);
                	$leaseArea = (float)Plantingstructure::getLeaseArea($model->id);
                	if(!bccomp($over, $leaseArea)) {
                		return html::button('种植结构',['class' => 'btn btn-success btn-xs','disabled'=>true]);
                	} else {
                		return html::button('种植结构',['class' => 'btn btn-success btn-xs','onclick'=>'showModel('.$model->id.','.$_GET['farms_id'].','.$model->year.')']);
                	}
                	return html::button('种植结构',['class' => 'btn btn-success btn-xs','onclick'=>'showModel('.$model->id.','.$_GET['farms_id'].','.$model->year.')']);
                },
                'print' => function ($url, $model, $key) {
                	$url = Url::to(['print/printleasecontract','lease_id'=>$model->id]);
                	$options = [
                			'title' => Yii::t('yii', '打印租赁合同'),
                			'class' => 'btn btn-success btn-xs',
                	];
                	return Html::a('租赁合同', $url, $options);
                },
            	]
       	 	],
        ],
    ]); ?>


	                </div>
            </div>
        </div>
    </div>
</section>
</div>
<div id="farmMsg" title="信息" class="text-red">
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
<div id="dialogMsg"></div>

<script>
	$(document).ready(function(){
		if(<?= Farms::isFarmsInfo($farm->id)?> == 1) {
			$("#farmMsg").dialog("open");
		}
	});
	$( "#farmMsg" ).dialog({
		autoOpen: false,
		width: 600,
		modal:true,
		closeOnEscape:false,
		open:function(event,ui){$(".ui-dialog-titlebar-close").hide();},
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
	$( "#dialogMsg" ).dialog({
		autoOpen: false,
		width: 1500,
		//    show: "blind",
		//    hide: "explode",
		modal: true,//设置背景灰的
		position: { using:function(pos){
			var topOffset = $(this).css(pos).offset().top;
			if (topOffset = 0||topOffset>0) {
				$(this).css('top', 20);
			}
			var leftOffset = $(this).css(pos).offset().left;
			if (leftOffset = 0||leftOffset>0) {
				$(this).css('left', 360);
			}
		}},
		buttons: [
			{
				text: "确定",
				class:'btn btn-success',
				click: function() {
					$( this ).dialog( "close" );
					var form = $('form').serializeArray();
					console.log($.toJSON(form));
//            alert($('#temp-id').val());
//            if($('#Refresh').val()) {
//                window.location.reload();
//            }
					$.getJSON('index.php?r=sixcheck/sixchecksave',{'value':$.toJSON(form),'farms_id':"<?= $_GET['farms_id']?>",'id':$('#temp-id').val()},function (data) {
						console.log(data);
						if(data.state && data.isBack == '') {
							window.location.reload();
						} else {
							if(data.isBack == 'sales') {
								salescreateDialog("<?= $_GET['farms_id']?>");
							}
						}
					});
				}

			},
			{
				text: "取消",
				class:'btn btn-danger',
				click: function() {
					$( this ).dialog( "close" );
				}
			}
		]
	});
function showModel(lease_id,farms_id,year) {
    $.get('index.php?r=plantingstructure/plantingstructureleasecreate', {lease_id:lease_id,farms_id:farms_id,year:year}, function (body) {
		$('#dialogMsg').html(body);
		$("#dialogMsg").dialog("open");
    });
}
$('#rowjump').keyup(function(event){
	input = $(this).val();
	$.getJSON('index.php?r=farms/getfarmid', {id: input}, function (data) {
		$('#setFarmsid').val(data.farmsid);
	});
});
</script>
