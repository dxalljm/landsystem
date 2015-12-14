<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%session}}".
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 */
class Search extends \yii\db\ActiveRecord
{
	public static function plantingstructure()
    {
    	$columns[] = ['class' => 'yii\grid\SerialColumn'];
    	$columns[] = [
    			'label' => '管理区',
    			'value' => function($model) {
    				$managementArea = Farms::find()->where(['id'=>$model->farms_id])->one()['management_area'];
    				return ManagementArea::find()->where(['id'=>$managementArea])->one()['areaname'];
    			}
    	];
    	$columns[] = [
    			'label' => '农场名称',
    			'attribute' => 'farms_id',
    			'value' => function($model) {
    				return Farms::find()->where(['id'=>$model->farms_id])->one()['farmname'];
    			}
    	];
    	$columns[] = [
    			'label' => '法人姓名',
    			'attribute' => 'farms_id',
    			'value' => function($model) {
    				return Farms::find()->where(['id'=>$model->farms_id])->one()['farmername'];
    			}
    	];
    	$columns[] = [
    			'label' => '承租人',
    			'attribute' => 'lease_id',
    			'value' => function($model) {
    				return Lease::find()->where(['id'=>$model->lease_id])->one()['lessee'];
    			}
    	];
    	$columns[] = [
    			'label' => '良种信息',
    			'attribute' => 'goodseed_id',
    			'value' => function($model) {
    				return Goodseed::find()->where(['id'=>$model->goodseed_id])->one()['plant_model'];
    			}
    	];
    	$columns[] = 'area';
    	return $columns;
    }
}
