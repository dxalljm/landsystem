<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%userlevel}}".
 *
 * @property integer $id
 * @property string $levelname
 */
class Userlevel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%userlevel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['levelname'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'levelname' => '级别名称',
        ];
    }
}
