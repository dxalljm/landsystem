<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%projectapplication}}".
 *
 * @property integer $id
 * @property string $projecttype
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $is_agree
 */
class Projectapplication extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%projectapplication}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['create_at', 'update_at', 'is_agree','farms_id','management_area','reviewprocess_id'], 'integer'],
            [['projecttype'], 'string', 'max' => 500],
        	[['content'],'string']
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
            'projecttype' => '项目类型',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'is_agree' => '是否立项',
        	'content' => '申请内容',
        	'management_area' => '管理区ID',
        	'reviewprocess' => '流程ID',
        ];
    }
    
    public static function getFarmRows($params)
    {
    	$where = [];
    	foreach ($params['projectapplicationSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$row = Projectapplication::find ()->where ($where)->count ();
    	return $row;
    }
    
    public static function getFarmerrows($params)
    {
    	$where = [];
    	foreach ($params['projectapplicationSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$yields = Projectapplication::find ()->where ($where)->all ();
    	//     	var_dump($farms);exit;
    	$data = [];
    	foreach($yields as $value) {
    		$farmallid[] = $value['farms_id'];    		
    	}
    	$farms = Farms::find()->where(['id'=>$farmallid])->all();
    	foreach ($farms as $value) {
    		$data[] = ['farmername'=>$value['farmername'],'cardid'=>$value['cardid']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		return count($newdata);
    	}
    	else
    		return 0;;
    }
    
    public static function getProjecttype($params)
    {
    	$where = [];
    	foreach ($params['projectapplicationSearch'] as $key => $value) {
    		if($value !== '')
    			$where[$key] = $value;
    	}
    	$yields = Projectapplication::find ()->where ($where)->all ();
    	//     	var_dump($farms);exit;
    	$data = [];
    	foreach($yields as $value) {
    		$data[] = ['typeid'=>$value['projecttype']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		return count($newdata);
    	}
    	else
    		return 0;;
    }
}
