<?php

namespace app\models;

use Yii;
use frontend\helpers\arraySearch;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%disastertype}}".
 *
 * @property integer $id
 * @property string $typename
 */
class Disastertype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%disastertype}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['typename'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'typename' => '类型名称',
        ];
    }

    public static function getAlltypename()
    {
        return ArrayHelper::map(self::find()->all(),'id','typename');
    }
}
