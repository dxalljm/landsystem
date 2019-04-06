<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%afterchenqian}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $management_area
 * @property integer $year
 * @property double $money
 */
class Afterchenqian extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%afterchenqian}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'management_area', 'year'], 'integer'],
            [['money'], 'number']
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
            'management_area' => '管理区',
            'year' => '年份',
            'money' => '陈欠金额',
        ];
    }
}
