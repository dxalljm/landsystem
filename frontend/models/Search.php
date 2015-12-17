<?php

namespace app\models;

use Yii;
use frontend\helpers\MoneyFormat;

/**
 * This is the model class for table "{{%session}}".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 */
class Search extends \yii\db\ActiveRecord {
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
				] 
		];
		return $array [$tab];
	}
	public static function getColumns(array $field) {
		// $controller = Yii::$app->controller->id;
		$columns [] = [ 
				'class' => 'yii\grid\SerialColumn' 
		];
		// var_dump(yii::$app->controller->id);
		if (yii::$app->controller->id !== 'farms') {
			foreach ( $field as $value ) {
				
				switch ($value) {
					case 'management_area' :
						$columns [] = [ 
								'label' => '管理区',
								'value' => function ($model) {
									$managementArea = Farms::find ()->where ( [ 
											'id' => $model->farms_id 
									] )->one ()['management_area'];
									return ManagementArea::find ()->where ( [ 
											'id' => $managementArea 
									] )->one ()['areaname'];
								} 
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
								'value' => function ($model) {
									return Goodseed::find ()->where ( [ 
											'id' => $model->goodseed_id 
									] )->one ()['plant_model'];
								} 
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
					case 'planting_id' :
						$columns [] = [ 
								'label' => '作物',
								'attribute' => $value,
								'value' => function ($model) {
									$planting = Plantingstructure::find ()->where ( [ 
											'id' => $model->planting_id 
									] )->one ();
									return Plant::find ()->where ( [ 
											'id' => $planting ['plant_id'] 
									] )->one ()['cropname'];
								} 
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
								'value' => function ($model) {
									$breed = Breed::find ()->where ( [ 
											'id' => $model->breed_id 
									] )->one ();
									$management_area = Farms::find ()->where ( [ 
											'id' => $breed->farms_id 
									] )->one ()['management_area'];
									return ManagementArea::find ()->where ( [ 
											'id' => $management_area 
									] )->one ()['areaname'];
								} 
						];
						$columns [] = [ 
								'label' => '农场名称',
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
								'value' => function ($model) {
									$breed = Breed::find ()->where ( [ 
											'id' => $model->breed_id 
									] )->one ();
									return $breed->breedname;
								} 
						];
						$columns [] = [ 
								'label' => '养殖场位置',
								'value' => function ($model) {
									$breed = Breed::find ()->where ( [ 
											'id' => $model->breed_id 
									] )->one ();
									return $breed->breedaddress;
								} 
						];
						$columns [] = [ 
								'label' => '示范户',
								'value' => function ($model) {
									$breed = Breed::find ()->where ( [ 
											'id' => $model->breed_id 
									] )->one ();
									return $breed->is_demonstration ? '是' : '否';
								} 
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
								'value' => function ($model) {
									$breedtype = Breedtype::find ()->where ( [ 
											'id' => $model->breedtype_id 
									] )->one ();
									return $breedtype->typename;
								} 
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
						$columns [] = [ 
								'label' => '种类',
								'value' => function ($model) {
									$breedinfo = Breedinfo::find ()->where ( [ 
											'id' => $model->breedinfo_id 
									] )->one ();
									$breedtype = Breedtype::find ()->where ( [ 
											'id' => $breedinfo->breedtype_id 
									] )->one ();
									return $breedtype->typename;
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
								'value' => function ($model) {
									return Plant::find ()->where ( [ 
											'id' => $model->plant_id 
									] )->one ()['cropname'];
								} 
						];
						break;
					default :
						$columns [] = $value;
				}
			}
		} else {
			foreach ( $field as $value ) {
				if ($value == 'management_area')
					$columns [] = [ 
							'attribute' => 'management_area',
							'value' => function ($model) {
								return ManagementArea::find ()->where ( [ 
										'id' => $model->management_area 
								] )->one ()['areaname'];
							} 
					];
				else
					$columns [] = $value;
			}
		}
		return $columns;
	}
}
