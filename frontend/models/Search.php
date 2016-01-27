<?php

namespace app\models;

use Yii;
use frontend\helpers\MoneyFormat;
use app\models\ManagementArea;
use app\models\Fireprevention;
use app\models\Collection;
use yii\helpers\Html;

use yii\helpers\Url;
use frontend\helpers\arraySearch;
/**
 * This is the model class for table "{{%session}}".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 */
class Search extends \yii\db\ActiveRecord {
	public static $totalData;
	public static function getParameter($tab) {
		$array = [ 
				'farms' => [ 
						'farmname',
						'farmername',
						'telephone',
						'address' 
				],
				
				// 'plantingstructure' => ['plantfather','plantson','goodseed','Inputproductfather','Inputproductson_id','Inputproduct','pesticides'],
				'plantingstructure' => [ 
						'plantfather',
						'plantson',
						'goodseed' 
				],
				'breedinfo' => ['breed_id','basicinvestment','housingarea','breedtype_id','number'],
		];
		return $array [$tab];
	}
	public static function getColumns(array $field,$params=NULL) {
// 		var_dump($params);exit;
		self::$totalData = arraySearch::find($params)->search();
		$columns [] = [ 
				'class' => 'yii\grid\SerialColumn' 
		];
		// var_dump(yii::$app->controller->id);
// 		if (yii::$app->controller->id !== 'farms') {

			foreach ( $field as $value ) {
				
				switch ($value) {
					case 'operation' :
						$columns[] = [
				                'label'=>'更多操作',
				                'format'=>'raw',
				            	//'class' => 'btn btn-primary btn-lg',
				                'value' => function($model,$key){
				                	if(Yii::$app->controller->id == 'plantingstructure')
				                    	$url = [Yii::$app->controller->id.'/'.Yii::$app->controller->id.'view','id'=>$model->id,'farms_id'=>$model->farms_id,'lease_id'=>$model->lease_id];

				                	if(Yii::$app->controller->id == 'farms')
				                		$url = Url::to(['farms/farmsfile','farms_id'=>$model->id]);

				                    $option = '查看详情';
					            	$title = '';
					            	return Html::a($option,$url, [
					            			'id' => 'farmerland',
					            			'title' => $title,
					            			'class' => 'btn btn-primary btn-xs',
					            	]);
				                }
				            ];
						break;
					case 'management_area' :
						$columns [] = [
				            	'label'=>'管理区',
				            	'attribute'=>$value,
				            	'headerOptions' => ['width' => '200'],
				            	'value'=> function($model) {
// 				            	var_dump($model);exit;
				            		return ManagementArea::getAreanameOne($model->management_area);
				           	 	},
				            	'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
				            ];
						break;
					case 'ypayyear' :
							$columns [] = [
							// 				            	'label'=>'管理区',
							'attribute'=>$value,
							
							'filter' => Collection::getYear(),     //此处我们可以将筛选项组合成key-value形式
							];
							break;
					case 'farms_id' :
						$columns [] = [ 
								'label' => '农场名称',
								'attribute' => $value,
								'value' => function ($model) {
									return Farms::find ()->where ( [ 
											'id' => $model->farms_id 
									] )->one ()['farmname'];
								} 
						];
						break;
					case 'farmer_id':
						$columns [] = [ 
								'label' => '法人姓名',
								'attribute' => $value,
								'value' => function ($model) {
									return Farms::find ()->where ( [ 
											'id' => $model->farms_id 
									] )->one ()['farmername'];
								} 
						];
						break;
					case 'lease_id' :
						$columns [] = [ 
								'label' => '承租人',
								'attribute' => $value,
								'value' => function ($model) {
									return Lease::find ()->where ( [ 
											'id' => $model->lease_id 
									] )->one ()['lessee'];
								} 
						];
						break;
					case 'goodseed_id' :
						$columns [] = [ 
								'label' => '良种信息',
								'attribute' => $value,
								'value'=> function($model,$params) {
// 				            	var_dump( arraySearch::find(self::$totalData)->getName('Goodseed', 'plant_model', 'goodseed_id')->getList());exit;
				            		return self::$totalData->getName('Goodseed', 'plant_model', 'goodseed_id')->getOne($model->goodseed_id);
				           	 	},
				            	'filter' => self::$totalData->getName('Goodseed', 'plant_model', 'goodseed_id')->getList(),
							
						];
						break;
					case 'area' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return $model->area . '亩';
								} 
						];
						break;
						case 'projecttype' :
							$columns [] = [
							'attribute' => $value,
							'value'=> function($model,$params) {
// 				            	var_dump($model);exit;
				            		return self::$totalData->getName('Infrastructuretype', 'typename', 'projecttype')->getOne($model->projecttype);
				           	 	},
				            	'filter' => self::$totalData->getName('Infrastructuretype', 'typename', 'projecttype')->getList(),
							];
							break;
					case 'planting_id' :
						$columns [] = [ 
								'label' => '作物',
								'attribute' => 'plant_id',
								'value' => function ($model) {
																
									return Yields::getNameOne($model->plant_id);
								},
								'filter' => Yields::getAllname(),
						];
						$columns [] = [ 
								'label' => '种植面积',
								'attribute' => $value,
								'value' => function ($model) {
									$planting = Plantingstructure::find ()->where ( [ 
											'id' => $model->planting_id 
									] )->one ();
									return $planting ['area'] . '亩';
								} 
						];
						break;
					case 'single' :
						$columns [] = [ 
								'label' => '单产（每亩）',
								'attribute' => $value,
								'value' => function ($model) {
									return $model->single . '斤';
								} 
						];
						break;
					case 'allsingle' :
						$columns [] = [ 
								'label' => '总产',
								'value' => function ($model) {
									$planting = Plantingstructure::find ()->where ( [ 
											'id' => $model->planting_id 
									] )->one ();
									return $planting ['area'] * $model->single . '斤';
								} 
						];
						break;
					case 'volume' :
						$columns [] = [ 
								'label' => '销售量',
								'attribute' => $value,
								'value' => function ($model) {
									return $model->volume . '斤';
								} 
						];
						break;
					case 'price' :
						$columns [] = [ 
								'label' => '单价',
								'attribute' => $value,
								'value' => function ($model) {
									return $model->price . '元';
								} 
						];
						$columns [] = [ 
								'label' => '总价',
								'value' => function ($model) {
									return MoneyFormat::num_format ( $model->volume * $model->price ) . '元';
								} 
						];
						break;
					case 'breed_id' :
						$columns [] = [ 
								'label' => '农场名称',
								'attribute' => 'farms_id',
								'value' => function ($model) {
									$breed = Breed::find ()->where ( [ 
											'id' => $model->breed_id 
									] )->one ();
									return Farms::find ()->where ( [ 
											'id' => $breed->farms_id 
									] )->one ()['farmname'];
								} 
						];
						$columns [] = [ 
								'label' => '法人姓名',
								'attribute' => 'farmer_id',
								'value' => function ($model) {
									$breed = Breed::find ()->where ( [ 
											'id' => $model->breed_id 
									] )->one ();
									return Farms::find ()->where ( [ 
											'id' => $breed->farms_id 
									] )->one ()['farmername'];
								} 
						];
						$columns [] = [ 
								'label' => '养殖场名称',
								'attribute' => 'breedname',
								'value' => function ($model) {
									$breed = Breed::find ()->where ( [ 
											'id' => $model->breed_id 
									] )->one ();
									return $breed->breedname;
								} 
						];
						$columns [] = [ 
								'label' => '养殖场位置',
								'attribute' => 'breedaddress',
								'value' => function ($model) {
									$breed = Breed::find ()->where ( [ 
											'id' => $model->breed_id 
									] )->one ();
									return $breed->breedaddress;
								} 
						];
						$columns [] = [ 
								'label' => '示范户',
								'attribute' => 'is_demonstration',
								'value' => function ($model) {
									$breed = Breed::find ()->where ( [ 
											'id' => $model->breed_id 
									] )->one ();
									return $breed->is_demonstration ? '是' : '否';
								} ,
								'filter' => ['否','是'],
						];
						break;
					case 'basicinvestment' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return MoneyFormat::num_format ( $model->basicinvestment ) . '元';
								} 
						];
						break;
					case 'housingarea' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return $model->housingarea . '平方米';
								} 
						];
						break;
					case 'breedtype_id' :
						$columns [] = [ 
								'attribute' => $value,
								'value'=> function($model) {
// 				            	var_dump($model);exit;
				            		return self::$totalData->getName('Breedtype', 'typename', 'breedtype_id')->getOne($model->breedtype_id);
				           	 	},
				            	'filter' => self::$totalData->getName('Breedtype', 'typename', 'breedtype_id')->getList(),
								
						];
						break;
					case 'number' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									$breedtype = Breedtype::find ()->where ( [ 
											'id' => $model->breedtype_id 
									] )->one ();
									return $model->number . $breedtype->unit;
								} 
						];
						break;
					
					case 'breedinfo_id' :
						$columns [] = [ 
								'label' => '养殖场名称',
								'attribute' => $value,
								'value' => function ($model) {
									$breedinfo = Breedinfo::find ()->where ( [ 
											'id' => $model->breedinfo_id 
									] )->one ();
									$breed = Breed::find ()->where ( [ 
											'id' => $breedinfo->breed_id 
									] )->one ();
									return $breed->breedname;
								} 
						];
						break;
					case 'mortgagearea' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return $model->mortgagearea . '亩';
								} 
						];
						break;
					case 'mortgagebank' :
						$columns[] = [
								'attribute' => $value,
								'value' => function ($model) {
									return Loan::getOneBank($model->mortgagebank);
								},
								'filter' => Loan::getBankName(),
						];
						break;
					case 'mortgagemoney' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return MoneyFormat::num_format ( $model->mortgagemoney ) . '元';
								} 
						];
						break;
					case 'amounts_receivable' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return MoneyFormat::num_format ( $model->amounts_receivable ) . '元';
								} 
						];
						break;
					case 'real_income_amount' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return MoneyFormat::num_format ( $model->real_income_amount ) . '元';
								} 
						];
						break;
					case 'owe' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return MoneyFormat::num_format ( $model->owe ) . '元';
								} 
						];
						break;
					case 'ypayarea' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return $model->ypayarea . '亩';
								} 
						];
						break;
					case 'ypaymoney' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return MoneyFormat::num_format ( $model->ypaymoney ) . '元';
								} 
						];
						break;
					case 'disastertype_id' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return Disastertype::find ()->where ( [ 
											'id' => $model->disastertype_id 
									] )->one ()['typename'];
								} 
						];
						break;
					
					case 'disasterarea' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return MoneyFormat::num_format ( $model->disasterarea ) . '亩';
								} 
						];
						break;
					case 'disasterplant' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return Plant::find ()->where ( [ 
											'id' => $model->disasterplant 
									] )->one ()['cropname'];
								} 
						];
						break;
					case 'isinsurance' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return $model->isinsurance ? '是' : '否';
								} 
						];
						break;
					case 'yieldreduction' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									$v = $model->yieldreduction * 100;
									return $v . '%';
								} 
						];
						break;
					case 'plant_id' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function($model) {
// 								var_dump($params);exit;
									return self::$totalData->getName('Plant', 'cropname', 'plant_id')->getOne($model->plant_id);
								},
								'filter' => self::$totalData->getName('Plant', 'cropname', 'plant_id')->getList(),
								];
						break;
					case 'isepidemic' :
							$columns [] = [
							'attribute' => $value,
							'value' => function($model) {
							// 								var_dump($params);exit;
							return $model->isepidemic;
							},
							'filter' => ['无'=>'无','有'=>'有'],
							];
							break;
					case 'percent' :
						$columns[] = [
					           //动作列yii\grid\ActionColumn 
					           //用于显示一些动作按钮，如每一行的更新、删除操作。
					          'class' => 'yii\grid\ActionColumn',
					          'header' => '完成进度', 
					          'template' => '{percent}',//只需要展示删除和更新
					          'headerOptions' => ['width' => '440'],
					          'buttons' => [
					            'percent' => function($url, $model, $key){
					            $html = '<div class="progress progress-xs progress-striped active">';
					            $html .= '<div class="progress-bar progress-bar-success" style="width: '.Fireprevention::getPercent($model).'"></div>';
					            $html .='</div>';
					               return Html::a($html);
					             },                     
					           ],
					         ];
						
						break;
					case 'percentvalue' :
						$columns[] = [
					           //动作列yii\grid\ActionColumn 
					           //用于显示一些动作按钮，如每一行的更新、删除操作。
					          'class' => 'yii\grid\ActionColumn',
					          'header' => '完成进度', 
					          'template' => '{percentvalue}',//只需要展示删除和更新
					          'headerOptions' => ['width' => '40'],
					          'buttons' => [
					            'percentvalue' => function($url, $model, $key){
					            $html = '<span class="badge bg-green">'.Fireprevention::getPercent($model).'</span>';
					               return Html::a($html);
					             },                     
					           ],
					         ];
						
						break;
					default :
						$columns [] = $value;
				}
			}
// 		} else {
// 			foreach ( $field as $value ) {
// 				if ($value == 'management_area')
// 					$columns [] = [ 
// 							'attribute' => 'management_area',
// 							'value'=> function($model) {
// 				            		return ManagementArea::getAreanameOne($model->management_area);
// 				           	 	},
// 				            	'filter' => ManagementArea::getAreaname(),     //此处我们可以将筛选项组合成key-value形式
// 					];
// 				else
// 					$columns [] = $value;
// 			}
// 		}
		return $columns;
	}
}
