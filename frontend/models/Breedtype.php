<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%breedtype}}".
 *
 * @property integer $id
 * @property integer $father_id
 * @property string $typename
 */
class Breedtype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%breedtype}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['father_id'], 'integer'],
            [['typename','unit'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'father_id' => '类别',
            'typename' => '类型名称',
        	'unit' => '单位',
        ];
    }
}
