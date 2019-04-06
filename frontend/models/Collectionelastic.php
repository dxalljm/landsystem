<?php

namespace app\models;

use Yii;
use app\models\Farms;
use frontend\helpers\MoneyFormat;

/**
 * This is the model class for table "{{%collection}}".
 *
 * @property integer $id
 * @property string $payyear
 * @property integer $farms_id
 * @property string $billingtime
 * @property integer $amounts_receivable
 * @property integer $real_income_amount
 * @property integer $ypayyear
 * @property integer $ypayarea
 * @property integer $ypaymoney
 * @property integer $owe
 */
class Collectionelastic extends \yii\elasticsearch\ActiveRecord {
	
	/**
	 * @inheritdoc
	 */
	public function attributes() {
		return [ 
				'id',
				'payyear',
				'farms_id',
				'billingtime',
				'amounts_receivable',
				'real_income_amount',
				'ypayyear',
				'ypayarea',
				'ypaymoney',
				'owe',
				'isupdate',
				'create_at',
				'update_at',
				'dckpay', 
				'management_area',
				'nonumber',
		];
	}
	
}
