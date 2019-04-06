<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "piwigo_users".
 *
 * @property string $id
 * @property string $username
 * @property string $password
 * @property string $mail_address
 */
class PiwigoUsers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'piwigo_users';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username'], 'string', 'max' => 100],
            [['password', 'mail_address'], 'string', 'max' => 255],
            [['username'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
			'username' => 'username',
			'password' => 'password',
			'mail_address' => 'mail_address',
        ];
    }
}
