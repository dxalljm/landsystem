<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tables}}".
 *
 * @property integer $id
 * @property string $tablename
 * @property string $Ctablename
 */
class Tables extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tables}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tablename', 'Ctablename'], 'required'],
            [['tablename', 'Ctablename'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tablename' => '数据库表',
            'Ctablename' => '中文标识',
        ];
    }
    
    public static function getCtablename($controller)
    {
    	$tablename = Tables::find()->where(['tablename'=>$controller])->one();
    	return $tablename['Ctablename'];
    }
}
