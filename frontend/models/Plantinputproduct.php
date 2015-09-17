<?php

namespace app\models;

use Yii;

//use frontend\helpers\eActionColumn;

/**
 * This is the model class for table "{{%plantinputproduct}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $lessee_id
 * @property integer $father_id
 * @property integer $son_id
 * @property integer $inputproduct_id
 * @property double $pconsumption
 * @property string $zongdi
 * @property integer $plant_id
 */


// $e = new eActionColumn();
// var_dump($e);
// exit;

class Plantinputproduct extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%plantinputproduct}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'lessee_id', 'father_id', 'son_id', 'inputproduct_id', 'plant_id'], 'integer'],
            [['pconsumption'], 'number'],
            [['zongdi'], 'string', 'max' => 500]
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
            'lessee_id' => '承租人ID',
            'father_id' => '类别',
            'son_id' => '子类ID',
            'inputproduct_id' => '化肥使用情况',
            'pconsumption' => '农药用量',
            'zongdi' => '宗地',
            'plant_id' => '种植结构',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        ];
    }
}
