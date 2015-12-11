<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%reviewprocess}}".
 *
 * @property integer $id
 * @property string $farms_id
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $estate
 * @property integer $finance
 * @property integer $filereview
 * @property integer $publicsecurity
 * @property integer $leader
 * @property integer $mortgage
 * @property integer $steeringgroup
 * @property string $estatecontent
 * @property string $financecontent
 * @property string $filereviewcontent
 * @property string $publicsecuritycontent
 * @property string $leadercontent
 * @property string $mortgagecontent
 * @property string $steeringgroupcontent
 * @property integer $estatetime
 * @property integer $financetime
 * @property integer $filereviewtime
 * @property integer $publicsecuritytime
 * @property integer $leadertime
 * @property integer $mortgagetime
 * @property integer $steeringgrouptime
 */
class Reviewprocess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%reviewprocess}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'create_at', 'update_at', 'estate', 'finance', 'filereview', 'publicsecurity', 'leader', 'mortgage', 'steeringgroup', 'estatetime', 'financetime', 'filereviewtime', 'publicsecuritytime', 'leadertime', 'mortgagetime', 'steeringgrouptime'], 'integer'],
            [['estatecontent', 'financecontent', 'filereviewcontent', 'publicsecuritycontent', 'leadercontent', 'mortgagecontent', 'steeringgroupcontent'], 'string', 'max' => 500]
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
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'estate' => '地产科状态',
            'finance' => '财务报表',
            'filereview' => '档案审查状态',
            'publicsecurity' => '公安部门状态',
            'leader' => '分管领导状态',
            'mortgage' => '抵押贷款审查状态',
            'steeringgroup' => '领导小组状态',
            'estatecontent' => '地产科意见',
            'financecontent' => '财务科意见',
            'filereviewcontent' => '档案审查情况',
            'publicsecuritycontent' => '公安部门意见',
            'leadercontent' => '分管领导意见',
            'mortgagecontent' => '抵押贷款审查',
            'steeringgroupcontent' => '领导小组意见',
            'estatetime' => '地产科审查时间',
            'financetime' => '财务科审核时间',
            'filereviewtime' => '档案审查时间',
            'publicsecuritytime' => '公安部门审核时间',
            'leadertime' => '分管领导审核时间',
            'mortgagetime' => '抵押贷款审核时间',
            'steeringgrouptime' => '领导小组审核时间',
        ];
    }
}
