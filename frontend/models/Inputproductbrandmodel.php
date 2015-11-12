<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%Inputproductbrandmodel}}".
 *
 * @property integer $id
 * @property integer $inputproduct_id
 * @property string $brand
 * @property string $model
 */
class Inputproductbrandmodel extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%Inputproductbrandmodel}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['inputproduct_id'], 'integer'],
            [['brand', 'model'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'inputproduct_id' => '化肥使用情况',
            'brand' => '品牌',
            'model' => '型号',
        ];
    }
}
