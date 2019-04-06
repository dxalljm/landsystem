<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%breed}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property string $breedname
 * @property string $breedaddress
 * @property integer $is_demonstration
 */
class Breedelastic extends \yii\elasticsearch\ActiveRecord
{
   
    public function attributes()
    {
        return [
			'id',
            'farms_id',
            'breedname',
            'breedaddress',
            'is_demonstration',
        	'create_at',
        	'update_at',
        	'management_area',
        ];
    }
}
