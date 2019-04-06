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
    
    public static function getCfields($contrller,$modelfield)
    {
    	$tableid = Tables::find()->where(['tablename'=>$contrller])->one()['id'];
    	$field = Tablefields::find()->where(['tables_id'=>$tableid,'fields'=>$modelfield])->one();
//        var_dump($field['cfields']);exit;
    	return $field['cfields'];
//
//        if($modelfield == 'photo') {
//            return '照片';
//        }
//        if($modelfield == 'cardpic' or $modelfield == 'cardpicback') {
//            return '身份证扫描件';
//        }
//        if($modelfield == 'archivesimage') {
//            return '合同扫描件';
//        }
    }

    public static function getCTable($class)
    {
        $tableid = Tables::find()->where(['tablename'=>$class])->one();
        return $tableid['Ctablename'];
    }
}
