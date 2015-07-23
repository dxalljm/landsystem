<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%menu_to_user}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $menulist
 */
class MenuToUser extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%menu_to_user}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['menulist'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'user_id' => '用户ID',
            'menulist' => '所属导航',
        ];
    }
}
