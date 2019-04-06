<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%insurancedck}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $insurance_id
 * @property integer $management_area
 * @property integer $isoneself
 * @property integer $iscompany
 * @property integer $isbank
 * @property integer $iswt
 * @property integer $iscontract
 */
class Insurancedck extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%insurancedck}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'insurance_id', 'management_area', 'isoneself', 'iscompany', 'isbank', 'iswt', 'iscontract','islease'], 'integer']
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
            'insurance_id' => '保险单id',
            'management_area' => '管理区',
            'isoneself' => '是否本人办理',
            'iscompany' => '是否确定承保公司',
            'isbank' => '是否提交银行卡复印件',
            'iswt' => '是否提供委托书及委托人身份证',
            'iscontract' => '是否提供承包合同及法人身份证',
        	'islease' => '提供租赁合同或租赁协议书',
        ];
    }

    public static function attributesList()
    {
        return [
            'isoneself' => '本人办理',
            'iscontract' => '提供承包合同及法人身份证',
            'iscompany' => '确定承保公司',
            'isbank' => '提交银行卡复印件',
//         	'islease' => '提供租赁合同或租赁协议书',
        ];
    }

    public static function attributesKey()
    {
        return [
            'isoneself',
//            'iswt',
            'iscontract',
            'iscompany',
            'isbank',
        ];
    }
}
