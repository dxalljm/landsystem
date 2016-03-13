<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%huinonggrant}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $huinong_id
 * @property double $money
 * @property double $area
 * @property integer $state
 * @property string $note
 */
class Huinonggrant extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%huinonggrant}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'huinong_id','lease_id','subsidiestype_id','typeid', 'state','create_at','update_at','lease_id','management_area','issubmit'], 'integer'],
            [['money', 'area'], 'number'],
            [['note'], 'string']
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
        	'lease_id' => '承租人',
            'huinong_id' => '惠农政策ID',
        	'subsidiestype_id' => '补贴类型',
        	'typeid' => '补贴种类',
            'money' => '补贴金额',
        	'lease_id' => '租赁者ID',
            'area' => '种植面积',
            'state' => '状态',
            'note' => '备注',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        	'management_area' => '管理区ID',
        	'issubmit' => '是否提交',
        ];
    }
    
    public static function isInHuinonggrant($huinong_id)
    {
    	
    	if(Huinonggrant::find()->where(['huinong_id'=>$huinong_id])->count())
    		return true;
    	else 
    		return false;
    }
}
