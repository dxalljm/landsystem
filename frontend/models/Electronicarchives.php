<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%electronicarchives}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property string $archivesimage
 * @property string $create_at
 * @property string $update_at
 * @property integer $pagenumber
 */
class Electronicarchives extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%electronicarchives}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'pagenumber'], 'integer'],
            [['archivesimage', 'create_at', 'update_at'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'farms_id' => 'Farms ID',
            'archivesimage' => 'Archivesimage',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'pagenumber' => 'Pagenumber',
        ];
    }
}
