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
class Breed extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%breed}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'is_demonstration'], 'integer'],
            [['breedname', 'breedaddress'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'farms_id' => '农场ID',
            'breedname' => '养殖场名称',
            'breedaddress' => '养殖位置',
            'is_demonstration' => '是否示范户',
        ];
    }
}
