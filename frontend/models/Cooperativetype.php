<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cooperativetype}}".
 *
 * @property integer $id
 * @property string $typename
 */
class Cooperativetype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cooperativetype}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['typename'], 'string', 'max' => 500],
            [['typename'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'typename' => '类型名称',
        ];
    }
}
