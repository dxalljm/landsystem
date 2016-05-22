<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%regulations}}".
 *
 * @property integer $id
 * @property integer $reviewprocess_id
 * @property integer $sfdj
 * @property string $sfdjcontent
 */
class Regulations extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%regulations}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reviewprocess_id', 'sfdj'], 'integer'],
            [['sfdjcontent'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'reviewprocess_id' => '审核过程ID',
            'sfdj' => '是否有司法机关、行政机关查封、冻结行为',
            'sfdjcontent' => '情况说明',
        ];
    }
    
    public static function attributesList()
    {
    	return [
    			'sfdj' => '是否有司法机关、行政机关查封、冻结行为',
    	];
    }
}
