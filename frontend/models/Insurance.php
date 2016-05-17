<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%insurance}}".
 *
 * @property integer $id
 * @property integer $management_area
 * @property string $year
 * @property integer $farms_id
 * @property string $policyholder
 * @property string $cardid
 * @property string $telephone
 * @property double $wheat
 * @property double $soybean
 * @property double $insuredarea
 * @property double $insuredwheat
 * @property double $insuredsoybean
 * @property string $company_id
 * @property string $create_at
 * @property string $update_at
 * @property string $policyholdertime
 * @property string $managemanttime
 * @property string $halltime
 */
class Insurance extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%insurance}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['management_area', 'farms_id'], 'integer'],
            [['wheat', 'soybean', 'insuredarea', 'insuredwheat', 'insuredsoybean'], 'number'],
            [['year', 'policyholder', 'cardid', 'telephone', 'company_id', 'create_at', 'update_at', 'policyholdertime', 'managemanttime', 'halltime'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'management_area' => '管理区',
            'year' => '年度',
            'farms_id' => '农场ID',
            'policyholder' => '投保人',
            'cardid' => '法人身份证',
            'telephone' => '联系电话',
            'wheat' => '小麦',
            'soybean' => '大豆',
            'insuredarea' => '投保面积',
            'insuredwheat' => '投保小麦面积',
            'insuredsoybean' => '投保大豆面积',
            'company_id' => '保险公司',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'policyholdertime' => '投保人签字日期',
            'managemanttime' => '管理区提交日期',
            'halltime' => '保险负责人提交日期',
        ];
    }
}
