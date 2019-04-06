<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%insurancetype}}".
 *
 * @property integer $id
 * @property integer $plant_id
 */
class Insurancetype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%insurancetype}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['plant_id'], 'integer'],
            [['pinyin'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'plant_id' => '种植结构',
            'pinyin' => '拼音'
        ];
    }

    public static function getTypes()
    {
        $result = [];
        $data = self::find()->all();
        foreach ($data as $value) {
            $result[$value['plant_id']] = Plant::find()->where(['id'=>$value['plant_id']])->one()['typename'];
        }
        return $result;
    }
}
