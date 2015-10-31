<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ttpozongdi}}".
 *
 * @property integer $id
 * @property integer $oldfarms_id
 * @property integer $newfarms_id
 * @property string $zongdi
 * @property string $create_at
 */
class Ttpozongdi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ttpozongdi}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldfarms_id', 'newfarms_id','create_at'], 'integer'],
            [['zongdi','oldzongdi','ttpozongdi'], 'string'],
            [['ttpoarea'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'oldfarms_id' => '原农场ID',
            'newfarms_id' => '现农场ID',
            'zongdi' => '宗地',
        	'oldzongdi' => '原宗地',
            'create_at' => '创建日期',
        	'ttpozongdi' => '转让的宗地',
        	'ttpoarea' => '转让的面积',
        ];
    }
}