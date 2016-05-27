<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%estate}}".
 *
 * @property integer $id
 * @property integer $tjsqjbzs
 * @property string $tjsqjbzscontent
 * @property integer $tjsffyj
 * @property string $tjsffyjcontent
 * @property integer $sfyzy
 * @property string $sfyzycontent
 * @property integer $sfmqzongdi
 * @property string $sfmqzongdicontent
 * @property integer $sfydcbg
 * @property string $sfydcbgcontent
 * @property integer $reviewprocess_id
 */
class Estate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%estate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['tjsqjbzs', 'tjsffyj', 'sfyzy', 'sfmqzongdi', 'sfydcbg', 'reviewprocess_id','estateisAgree'], 'integer'],
            [['tjsqjbzscontent', 'tjsffyjcontent', 'sfyzycontent', 'sfmqzongdicontent', 'sfydcbgcontent','estateisAgreecontent'], 'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'tjsqjbzs' => '双方是否提交申请及保证书',
            'tjsqjbzscontent' => '情况说明',
            'tjsffyj' => '是否提交双方复印件',
            'tjsffyjcontent' => '情况说明',
            'sfyzy' => '宜农林地是否有争议',
            'sfyzycontent' => '情况说明',
            'sfmqzongdi' => '是否明确地块',
            'sfmqzongdicontent' => '情况说明',
            'sfydcbg' => '是否有调查报告',
            'sfydcbgcontent' => '情况说明',
            'reviewprocess_id' => '审核过程ID',
        	'estateisAgree' => '是否同意',
        	'estateisAgreecontent' => '情况说明'
        ];
    }
    
    public static function attributesList()
    {
    	return [
//     			'id' => 'ID',
    			'tjsqjbzs' => '承包人（出让方）是否在辖区管理',
    			'tjsffyj' => '承包人拟转让承包合同面积与缴费面积是否一致',
    			'sfyzy' => '拟转让的承包经营权限有无争议',
    			'sfmqzongdi' => '双方当事人提供的拟转让地块图与实际是否一致',
    			'sfydcbg' => '是否撰写调查报告',
    			'estateisAgree' => ''
//     			'reviewprocess_id' => '审核过程ID',
    	];
    }
    
    public static function attributesKey()
    {
    	return [
    	//     			'id' => 'ID',
    			'tjsqjbzs',
    			'tjsffyj',
    			'sfyzy',
    			'sfmqzongdi',
    			'sfydcbg',
    			'estateisAgree',
    			//     			'reviewprocess_id' => '审核过程ID',
    	];
    }
}
