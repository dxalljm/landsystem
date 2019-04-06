<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%verisonlist}}".
 *
 * @property integer $id
 * @property string $ver
 * @property string $update
 */
class Verisonlist extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%verisonlist}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['update'], 'string'],
            [['ver','update_at'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ver' => '版本号',
            'update' => '更新说明',
            'update_at' => '更新时间'
        ];
    }
}
