<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tablefields}}".
 *
 * @property integer $id
 * @property integer $tables_id
 * @property string $fields
 * @property string $type
 * @property string $cfields
 */
class Tablefields extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tablefields}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tables_id', 'fields', 'type', 'cfields'], 'required'],
            [['tables_id'], 'integer'],
            [['fields', 'type', 'cfields'], 'string', 'max' => 100]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tables_id' => '表ID',
            'fields' => '项目名称',
            'type' => '项目类型',
            'cfields' => '中文标识',
        ];
    }
    
    public function gettables()
    {
    	return $this->hasOne(Tables::className(), ['id' => 'tables_id']);
    }
}
