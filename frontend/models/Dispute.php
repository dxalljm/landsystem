<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%dispute}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property string $content
 * @property string $create_at
 * @property string $update_at
 */
class Dispute extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%dispute}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id'], 'integer'],
            [['content'], 'string'],
            [['create_at', 'update_at'], 'string', 'max' => 500]
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
            'content' => '备注',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
        ];
    }
    
    public function getFarms()
    {
    	return $this->hasOne(Farms::className(), ['id' => 'farms_id']);
    }
}
