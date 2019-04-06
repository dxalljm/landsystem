<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%otherfarms}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property double $measure
 * @property string $describe
 * @property string $contractnumber
 * @property string $zongdi
 * @property string $remarks
 */
class Otherfarms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%otherfarms}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id'], 'integer'],
            [['measure'], 'number'],
            [['zongdi', 'remarks'], 'string'],
            [['describe', 'contractnumber'], 'string', 'max' => 500]
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
            'measure' => '面积',
            'describe' => '描述',
            'contractnumber' => '合同号',
            'zongdi' => '宗地',
            'remarks' => '备注',
        ];
    }
}
