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
            [['processdepartment', 'Identification','rolename','sparerole'], 'string', 'max' => 500]
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
            'rolename' => '角色',
            'sparerole' => '备用角色',
        ];
    }
}
