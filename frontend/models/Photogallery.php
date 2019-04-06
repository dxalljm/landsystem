<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%photogallery}}".
 *
 * @property integer $id
 * @property integer $management_area
 * @property integer $farms_id
 * @property string $tablename
 * @property string $picaddress
 */
class Photogallery extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%photogallery}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['management_area', 'farms_id'], 'integer'],
            [['tablename', 'picaddress'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'management_area' => '管理区',
            'farms_id' => '农场ID',
            'tablename' => '数据表',
            'picaddress' => '图片地址',
        ];
    }
}
