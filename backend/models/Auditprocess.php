<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%auditprocess}}".
 *
 * @property integer $id
 * @property string $projectname
 * @property string $process
 * @property string $actionname
 */
class Auditprocess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auditprocess}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projectname', 'process', 'actionname'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'projectname' => '项目名称',
            'process' => '流程',
            'actionname' => '方法名称',
        ];
    }
}
