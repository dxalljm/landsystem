<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%disaster}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $disastertype_id
 * @property double $disasterarea
 * @property string $disasterplant
 * @property double $insurancearea
 * @property double $yieldreduction
 * @property double $socmoney
 */
class Disaster extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%disaster}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'disastertype_id','isinsurance'], 'integer'],
            [['disasterarea', 'insurancearea', 'yieldreduction', 'socmoney'], 'number'],
            [['disasterplant'], 'string', 'max' => 500]
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
            'disastertype_id' => '灾害类型',
            'disasterarea' => '受灾面积',
            'disasterplant' => '受灾作物',
            'insurancearea' => '受保面积',
            'yieldreduction' => '减产量',
            'socmoney' => '理赔金额',
        	'isinsurance' => '是否保险'
        ];
    }
}
