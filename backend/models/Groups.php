<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%groups}}".
 *
 * @property integer $id
 * @property string $groupname
 * @property string $grouprole
 * @property string $groupmark
 */
class Groups extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%groups}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['groupname', 'grouprole', 'groupmark'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'groupname' => '用户组名称',
            'grouprole' => '用户组权限',
            'groupmark' => '用户组标识',
        ];
    }
}
