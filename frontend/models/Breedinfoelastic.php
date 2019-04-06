<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%breedinfo}}".
 *
 * @property integer $id
 * @property integer $breed_id
 * @property integer $number
 * @property double $basicinvestment
 * @property double $housingarea
 * @property integer $breedtype_id
 */
class Breedinfoelastic extends \yii\elasticsearch\ActiveRecord
{
    public function attributes()
    {
        return [
			'id',
            'breed_id',
            'number',
            'basicinvestment',
            'housingarea',
            'breedtype_id',
        	'create_at',
        	'update_at',
        	'management_area',
        ];
    }
}
