<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%fireimg}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $create_at
 * @property integer $year
 * @property string $pic
 */
class Picturelibrary extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%picturelibrary}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'create_at', 'year'], 'integer'],
            [['pic','classname','field'], 'string']
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
            'create_at' => '创建日期',
            'year' => '年度',
            'pic' => '图片地址',
            'clasname' => '类名称',
            'field' => '字段名称',
        ];
    }
}
