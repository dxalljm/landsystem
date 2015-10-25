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
class Breedinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%breedinfo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['breed_id', 'number', 'breedtype_id'], 'integer'],
            [['basicinvestment', 'housingarea'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'breed_id' => '养殖户ID',
            'number' => '数量',
            'basicinvestment' => '基础投资',
            'housingarea' => '圈舍面积',
            'breedtype_id' => '养殖种类',
        ];
    }
}
