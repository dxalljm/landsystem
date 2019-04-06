<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%processname}}".
 *
 * @property integer $id
 * @property string $processdepartment
 * @property string $Identification
 * @property integer $user_id
 * @property integer $spareuser
 */
class Processname extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%processname}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['processdepartment', 'Identification','department_id','level_id'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'processdepartment' => '流程科室名称',
            'Identification' => '标识',
            'department_id' => '科室',
            'level_id' => '职级',
        ];
    }

    public static function getProcessname($department_id,$level_id)
    {
        $result = [];
        $processname = Processname::find()->all();
        foreach ($processname as $process) {
            if(in_array($department_id,explode(',',$process['department_id'])) and $level_id == $process['level_id']) {
                $result[$process['id']] = $process['Identification'];
            }
        }
        return $result;
    }
}
