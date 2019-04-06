<?php

namespace app\models;

use Yii;
use frontend\helpers\MoneyFormat;
use app\models\ManagementArea;
use app\models\Fireprevention;
use app\models\Collection;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\Subsidiestype;
use yii\helpers\Url;
use frontend\helpers\arraySearch;
use  yii\web\Session;
/**
 * This is the model class for table "{{%session}}".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 */
class Search extends \yii\db\ActiveRecord {
	public static $totalData;
	public static $subsidiestypename;
	public static $list;
	public static $saveTemp;
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
		self::$totalData = arraySearch::find($params)->search();
// 		if(isset($_GET['huinonggrant']['subsidiestype_id'])) {
// 			self::$subsidiestypename = Subsidiestype::find()->where(['id'=>$_GET['huinonggrant']['subsidiestype_id']])->one()['urladdress'];
// 			self::$list = [];
// 			if(self::$subsidiestypename == 'Plant') {
// 				self::$list = self::$totalData->getName(self::$subsidiestypename, 'typename', 'typeid')->getOne($model->typeid);
// 			}
// 			if(self::$subsidiestypename == 'Goodseed') {
// 				self::$list = self::$totalData->getName(self::$subsidiestypename, 'typename', 'typeid')->getOne($model->typeid);
				
// 			}
// 		}
		$columns [] = [ 
				'class' => 'yii\grid\SerialColumn' 
		];
		$typelist = [1];
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
// 				                var_dump(User::getItemname());
					                $option = '查看详情';
					                $title = '';
									$url = '#';
				                	switch (Yii::$app->controller->id)
				                	{
				                		case 'farms':
				                			if(User::getItemname('法规科') or User::getItemname('服务大厅') or User::getItemname('副主任') or User::getItemname('主任'))
				                				$url = Url::to(['farms/farmslandview','id'=>$model->id]);
				                			else
				                				$url = Url::to(['farms/farmsfile','farms_id'=>$model->id]);
				                			break;
				                		case 'plantingstructure':
				                			$url = [Yii::$app->controller->id.'/'.Yii::$app->controller->id.'view','id'=>$model->id,'farms_id'=>$model->farms_id,'lease_id'=>$model->lease_id];
											break;
				                		case 'collection':
//											var_dump(User::getItemname());
											
				                			if(User::getItemname('地产科','科长') or User::getItemname('财务科','科长')) {
					                			if($model->state >= 1) {
					                				$url = [Yii::$app->controller->id.'/'.Yii::$app->controller->id.'send','farms_id'=>$model->farms_id];
					                				$option = '已缴费';
					                			} else {
													$url = [Yii::$app->controller->id.'/'.Yii::$app->controller->id.'send','farms_id'=>$model->farms_id];
					                				if($model->dckpay == 1)
					                					$option = '详情';
					                				else
					                					$option = '缴费';
					                			}
				                			}
// 				                			var_dump(User::getItemname());
				                			if(User::getItemname('财务科','科员')) {
				                				$url = [Yii::$app->controller->id.'/'.Yii::$app->controller->id.'view','farms_id'=>$model->farms_id];
				                				$option = '详情';
// 				                				var_dump($option);
				                			}
				                			
				                			break;
				                		default:
				                			$url = [Yii::$app->controller->id.'/'.Yii::$app->controller->id.'view','id'=>$model->id,'farms_id'=>$model->farms_id];
				                	}
				                	
				                    $html = Html::a($option,$url, [
						            			'id' => 'moreOperation',
						            			'title' => $title,
						            			'class' => 'btn btn-primary btn-xs',
// 				                    			'disabled' => $disabled,
						            	]);
				                    
									if(User::getItemname('服务大厅')) {
										$farmer = Farmer::find()->where(['farms_id'=>$model->id])->one();
// 										if($farmer['photo'] == '' or $farmer['cardpic'] == '' or $farmer['cardpicback'] == '') {
// 											$html.= '&nbsp;';
											$html.= Html::a('电子信息采集',Url::to(['photograph/photographindex','farms_id'=>$model->id]),['class' => 'btn btn-primary btn-xs',]);
// 										}
									}
					            	if(User::getItemname('主任') or User::getItemname('法规科') or User::getItemname('地产科') or User::getItemname('财务科')) {
						            	return $html;
					            	}
					            	else 
					            		return '';
				                }
				            ];
						break;
					case 'issame':
						$columns [] = [
//							'label'=>'是否一致',
							'format' =>'raw',
							'attribute'=>$value,
							'headerOptions' => ['width' => '150'],
							'value'=> function($model) {
								if($model->issame)
									return '一致';
								else
									return '<font color="red">不一致</font>';
							},
							'filter' => [0=>'不一致',1=>'一致'],     //此处我们可以将筛选项组合成key-value形式
						];
						break;
					case 'management_area' :
						$columns [] = [
				            	'label'=>'管理区',
				            	'attribute'=>$value,
				            	'headerOptions' => ['width' => '130'],
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
								'options' =>['width'=>120],
								'value' => function ($model) {
								
									return Farms::find ()->where ( [ 
											'id' => $model->farms_id 
									] )->one ()['farmname'];
								
						} 
						];
						break;
					case 'update_at':
						$columns [] = [
								'label' => '缴费日期',
// 								'attribute' => $value,
								'options' =>['width'=>120],
								'value' => function ($model) {
								if($model->state >= 1)
									return date('Y-m-d',$model->update_at);
								else
									return '';
								}
						];
						break;
					case 'farmer_id':
						$columns [] = [ 
								'label' => '法人姓名',
								'attribute' => $value,
								'options' =>['width'=>120],
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
								'options' =>['width'=>120],
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
// 								return $model->goodseed_id;
// 				            	var_dump( arraySearch::find(self::$totalData)->getName('Goodseed', 'typename', 'goodseed_id')->getList());exit;
				            		return self::$totalData->getName('Goodseed', 'typename', 'goodseed_id')->getOne($model->goodseed_id);
				           	 	},
				            	'filter' => self::$totalData->getName('Goodseed', 'typename', 'goodseed_id')->getList(),
							
						];
						break;
					case 'area' :
						$columns [] = [ 
								'attribute' => $value,
								'options' => ['width'=>120],
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
								'options' =>['width'=>150],
								'value' => function ($model) {
									return Yieldbase::find()->where(['plant_id'=>$model->plant_id,'year'=>User::getYear()])->one()['yield'] . '斤';
								} 
						];
						break;
					case 'allsingle' :
						$columns [] = [ 
								'label' => '总产',
								'options' =>['width'=>200],
								'value' => function ($model) {
									return MoneyFormat::num_format($model->area * Yieldbase::find()->where(['plant_id'=>$model->plant_id,'year'=>User::getYear()])->one()['yield']) . '斤';
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
// 								'options' =>['width'=>80],
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
									$result = $model->mortgagemoney;
																	
									return $result  . '万元';
								} 
						];
						break;
					case 'amounts_receivable' :
						$columns [] = [ 
								'attribute' => $value,
// 								'options' => ['width' => 150],
								'value' => function ($model) {
									return MoneyFormat::num_format ( $model->amounts_receivable ) . '元';
								} 
						];
						break;
					case 'real_income_amount' :
						$columns [] = [ 
								'attribute' => $value,
								'options' => ['width' => 150],
								'value' => function ($model) {
									if($model->real_income_amount)
										return MoneyFormat::num_format ( $model->real_income_amount ) . '元';
									else 
										return '0元';
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
								'options' => ['width' => 150],
								'value' => function ($model) {
									return MoneyFormat::num_format ( $model->ypaymoney ) . '元';
								} 
						];
						break;
					case 'disastertype_id' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return self::$totalData->getName('Disastertype', 'typename', 'disastertype_id')->getOne($model->disastertype_id);
						},
								'filter' => self::$totalData->getName('Disastertype', 'typename', 'disastertype_id')->getList()
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
									return self::$totalData->getName('Plant', 'typename', 'disasterplant')->getOne($model->disasterplant);
								},
								'filter' => self::$totalData->getName('Plant', 'typename', 'disasterplant')->getList()
						];
						break;
					case 'isinsurance' :
						$columns [] = [ 
								'attribute' => $value,
								'value' => function ($model) {
									return $model->isinsurance ? '是' : '否';
								},
								'filter' => ['否','是'],
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
								
									return self::$totalData->getName('Plant', 'typename', 'plant_id')->getOne($model->plant_id);
								},
								'filter' => self::$totalData->getName('Plant', 'typename', 'plant_id')->getList(),
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
						case 'subsidiestype_id' :
						$columns [] = [ 
								'label' => '补贴种类',
								'attribute' => $value,
								'value' => function ($model) {
									return self::$totalData->getName('Subsidiestype', 'typename',['Huinong','huinong_id','subsidiestype_id'])->getOne($model->subsidiestype_id);
								},
								'filter' => self::$totalData->getName('Subsidiestype', 'typename', ['Huinong','huinong_id','subsidiestype_id'])->getList()
								
						];
// 						var_dump(self::$totalData->getName('Subsidiestype', 'typename', ['Huinong','huinong_id','subsidiestype_id'])->getList());
						break;
						case 'typeid' :
							
							$columns [] = [
							'label' => '作物',
							'attribute' => $value,
							'value' => function ($model) {
							
								self::$subsidiestypename = Subsidiestype::find()->where(['id'=>$model->subsidiestype_id])->one()['urladdress'];
									if(self::$subsidiestypename == 'Plant') {
										$result = self::$totalData->getName(self::$subsidiestypename, 'typename', 'typeid')->getOne($model->typeid);										
										self::$saveTemp = self::$totalData->getName(self::$subsidiestypename, 'typename', 'typeid')->getList();
										return $result;
									}
									if(self::$subsidiestypename == 'Goodseed') {
										$result = self::$totalData->getName(self::$subsidiestypename, 'typename', 'typeid')->getOne($model->typeid);
										self::$saveTemp = [$model->typeid => self::$totalData->getName(self::$subsidiestypename, 'typename', 'typeid')->typenameList()];
										return $result;
									}
									
								},
								'filter' => self::$saveTemp,
							];
// 							var_dump($_SESSION['typenamelist']);
							break;
						case 'projectdata':
							$columns [] = [
// 									'label' => '数量',
									'attribute' => $value,
									'value' => function ($model) {
										return $model->projectdata.$model->unit;
									}
									];
							break;
						case 'projectstate':
							$columns [] = [
							
							'label' => '工程情况',
							'value' => function ($model) {
								$plan = Projectplan::find()->where(['project_id'=>$model->id])->one();
								if($plan) {
									$now = time();
									if($now<=$plan['begindate'])
										return '未开始';
									if($now<=$plan['enddate'] and $now >= $plan['begindate'])
										return '施工中';
									if($now >= $plan['enddate'])
										return '工程结束';
								} else {
									return '还没有工程计划';
								}
							}
							];
							break;
					case 'company_id':
						$columns[] = [
							'attribute' => $value,
							'options' =>['width' => '150'],
							'value' => function($model) {
								$company = Insurancecompany::find()->where(['id'=>$model->company_id])->one();
								return $company['companynname'];
							},
							'filter' => ArrayHelper::map(Insurancecompany::find()->all(),'id','companynname'),
						];
						break;
					case 'state':
						$columns[] = [
						'format'=>'raw',
							'attribute' => $value,
							'options' =>['width' => '100'],
							'value' => function($model) {
								if($model->state) {
									if($model->state == 1)
										return '<span class="text-green">已缴纳</span>';
									if($model->state == 2)
										return '<span class="text-green">部分缴纳</span>';
								}
								else {
									if($model->dckpay)
										return '<span class="text-blue">已提交</span>';
									else
										return '<span class="text-red">未缴纳</span>';
								}
							},
							'filter' => [1=>'已缴纳',0=>'未缴纳',2=>'部分缴纳',3=>'已提交'],
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
