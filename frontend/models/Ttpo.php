<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ttpo}}".
 *
 * @property integer $id
 * @property integer $oldfarms_id
 * @property integer $newfarms_id
 * @property string $create_at
 */
class Ttpo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ttpo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldfarms_id', 'newfarms_id','create_at','reviewprocess_id'], 'integer'],
           // [[''], 'string', 'max' => 500]
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
            'create_at' => '创建日期',
        	'reviewprocess_id' => '审核过程',
        ];
    }
}
