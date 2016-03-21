<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%loan}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property double $mortgagearea
 * @property string $mortgagebank
 * @property double $mortgagemoney
 * @property string $mortgagetimelimit
 */
class Loan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%loan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id','management_area','state','reviewprocess_id'], 'integer'],
            [['mortgagearea', 'mortgagemoney', 'create_at','update_at'], 'number'],
            [['mortgagebank','begindate','enddate'], 'string', 'max' => 500]
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
        	'management_area' => '管理区',
            'mortgagearea' => '抵押面积',
            'mortgagebank' => '抵押银行',
            'mortgagemoney' => '贷款金额',
            'begindate' => '开始日期',
        	'enddate' => '结束日期',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'state' => '状态',
        	'reviewprocess_id' => '审核ID',
        ];
    }
    
    public static function getBankName()
    {
    	return [
    			'中国建设银行'=>'中国建设银行',
    			'中国工商银行'=>'中国工商银行',
    			'中国银行'=>'中国银行',
    			'中国农业银行'=>'中国农业银行',
    			'大兴安岭农村商业银行'=>'大兴安岭农村商业银行',
    			'龙江银行'=>'龙江银行',
    			'邮政储蓄'=>'邮政储蓄',
    	];
    }
    
    public static function getOneBank($id)
    {
    	return self::getBankName()[$id];
    }
}
