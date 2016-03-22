<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%auditprocess}}".
 *
 * @property integer $id
 * @property string $projectname
 * @property string $process
 * @property string $actionname
 */
class Auditprocess extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%auditprocess}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['projectname', 'process', 'actionname'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'projectname' => '项目名称',
            'process' => '流程',
            'actionname' => '方法名称',
        ];
    }
    
    public static function isShowProcess()
    {
    	$role = User::getItemname();
    	$processname = Processname::find()->where(['rolename'=>$role])->all();
    	$rolenames = [];
    	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->andWhere('begindate<='.strtotime(date('Y-m-d')).' and enddate>='.strtotime(date('Y-m-d')))->one();
    	foreach ($processname as $process) {
    		$rolenames[] = $process['Identification'];
    	}
    	
    	$auditprocess = self::find()->all();
    	$audits = [];
    	foreach($auditprocess as $value) {
    		foreach ($rolenames as $rolename) {
    			if(in_array($rolename, explode('>',$value['process'])))
    				return true;
    		}
    		
    	}
    	if($temp)
    		return true;
    	else
    		return false;
    }
}
