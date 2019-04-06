<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%contractnumber}}".
 *
 * @property integer $id
 * @property string $contractnumber
 * @property string $lifeyear
 */
class Contractnumber extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%contractnumber}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['lifeyear'], 'string'],
            [['contractnumber'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'contractnumber' => '合同号',
            'lifeyear' => '年限',
        ];
    }
    
    public static function contractnumberAdd(){
    	$model = Contractnumber::findOne(1);
    	$model->contractnumber++;
    	$model->save();
        return $model->contractnumber;
    }
    
    public static function contractnumberSub(){
    	$model = Contractnumber::findOne(1);
    	$model->contractnumber--;
    	$model->save();
        return $model->contractnumber;
    }

    public static function now()
    {
        $model = Contractnumber::findOne(1);
        return $model->contractnumber;
    }
    public static function contractnumberSub(){
    	$model = Contractnumber::findOne(1);
    	$model->contractnumber--;
    	$model->save();
    }
}
