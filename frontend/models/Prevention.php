<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%prevention}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $breedinfo_id
 * @property integer $preventionnumber
 * @property integer $breedinfonumber
 * @property double $preventionrate
 * @property integer $isepidemic
 * @property string $vaccine
 */
class Prevention extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%prevention}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'breedinfo_id', 'breedtype_id','preventionnumber', 'breedinfonumber','create_at','update_at','management_area'], 'integer'],
            
            [['vaccine','preventionrate', 'isepidemic'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'farms_id' => '农场ID',
            'breedinfo_id' => '养殖信息ID',
        	'breedtype_id' => '种类',
            'preventionnumber' => '免疫数量',
            'breedinfonumber' => '应免数量',
            'preventionrate' => '免疫率',
            'isepidemic' => '有无疫情',
            'vaccine' => '疫苗接种情况',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'management_area' => '管理区',
        ];
    }
}
