<?php

namespace app\models;

use Yii;
<<<<<<< HEAD
use yii\helpers\ArrayHelper;
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f

/**
 * This is the model class for table "{{%insurancecompany}}".
 *
 * @property integer $id
 * @property string $companynname
 */
class Insurancecompany extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%insurancecompany}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['companynname'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'companynname' => '保险公司名称',
        ];
    }
<<<<<<< HEAD

    public static function getCompanyList()
    {
        $data = ArrayHelper::map(self::find()->all(),'id','companynname');
        return $data;
    }
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
}
