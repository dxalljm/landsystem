<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%filereview}}".
 *
 * @property integer $id
 * @property integer $fileishg
 * @property string $fileishgcontent
 * @property integer $htisyz
 * @property string $htisyzcontent
 * @property integer $lockinfo
 * @property string $lockinfocontent
 * @property integer $areaisyz
 * @property string $areaisyzcontent
 * @property integer $reviewprocess_id
 */
class Filereview extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%filereview}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fileishg', 'htisyz', 'areaisyz', 'reviewprocess_id','filereviewisAgree'], 'integer'],
            [['fileishgcontent', 'htisyzcontent', 'areaisyzcontent','filereviewisAgreecontent','filereviewundo'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'fileishg' => '审查承包人（出让方）合同档案是否审查合格',
            'fileishgcontent' => '情况说明',
            'htisyz' => '承包人提供的合同与存档是否真实一致',
            'htisyzcontent' => '情况说明',
            'areaisyz' => '合同面积是否与实测面积一致',
            'areaisyzcontent' => '情况说明',
            'reviewprocess_id' => '审核过程ID',
        	'filereviewisAgree' => '是否同意',
        	'filereviewisAgreecontent' => '情况说明',
            'filereviewundo' => '退回',
        ];
    }
    
    public static function attributesList()
    {
    	return [
            'fileishg' => '审查承包人（出让方）合同档案是否审查合格',
            'htisyz' => '承包人提供的合同与存档是否真实一致',
            'areaisyz' => '合同面积是否与实测面积一致',
    	];
    }
    
    public static function attributesKey()
    {
    	return [
    			'fileishg',
    			'htisyz',
    			'areaisyz',
    			'filereviewisAgree'
    	];
    }
}
