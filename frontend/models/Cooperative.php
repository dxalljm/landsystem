<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cooperative}}".
 *
 * @property integer $id
 * @property string $cooperativename
 * @property string $cooperativetype
 * @property string $directorname
 * @property integer $peoples
 * @property string $finance
 */
class Cooperative extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cooperative}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['peoples'], 'integer'],
            [['cooperativename', 'cooperativetype', 'directorname', 'finance'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'cooperativename' => '合作社名称',
            'cooperativetype' => '合作社类型',
            'directorname' => '理事长姓名',
            'peoples' => '入社人数',
            'finance' => '财务报表',
        ];
    }
}
