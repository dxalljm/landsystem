<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%machinetrial}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $apply_id
 * @property integer $management_area
 * @property string $isoneself
 * @property string $iscooperative
 * @property string $ismaterial
 */
class Machinetrial extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%machinetrial}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'apply_id', 'management_area','create_at','update_at'], 'integer'],
            [['isoneself', 'iscooperative', 'ismaterial'], 'string', 'max' => 500]
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
            'apply_id' => '申请单ID',
            'management_area' => '管理区',
            'isoneself' => '是否本人办理',
            'iscooperative' => '是否为农机合作社法人或成员',
            'ismaterial' => '补贴申请材料是否齐全',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
        ];
    }

    public static function attributesList()
    {
        return [
            'isoneself' => '是否本人办理',
//            'iscooperative' => '是否为农机合作社法人或成员',
            'ismaterial' => '补贴申请材料是否齐全',
        ];
    }

    public static function attributesKey()
    {
        return [
            'isoneself',
//            'iscooperative',
            'ismaterial',
        ];
    }
}
