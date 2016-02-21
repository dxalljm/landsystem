<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tempauditing}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $tempauditing
 * @property integer $update_at
 * @property integer $begindate
 * @property integer $enddate
 * @property integer $create_at
 */
class Tempauditing extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%tempauditing}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'update_at', 'create_at','state'], 'integer'],
            [['tempauditing', 'begindate', 'enddate'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'user_id' => '用户ID',
            'tempauditing' => '临时受权人',
            'update_at' => '更新日期',
            'begindate' => '开始日期',
            'enddate' => '结束日期',
            'create_at' => '创建日期',
        	'state' => '状态',
        ];
    }
    
    public static function tempauditingIsExpire()
    {
    	$temp = Tempauditing::find()->where(['user_id'=>Yii::$app->getUser()->id])->all();
    	if($temp) {
	    	foreach ($temp as $value) {
	    		$now = strtotime(date('Y-m-d'));
	    		if($value['enddate'] < $now) {
	    			$model = Tempauditing::findOne($value['id']);
	    			$model->state = 0;
	    			$model->save();
	    		}
	    	}
	    	return true;
    	} else
    		return false;
    }
    
    public static function is_tempauditing()
    {
    	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->one();
    	if($temp) {
    		return $temp['id'];
    	} else 
    		return false;
    	return false;
    }
    
    public static function getEnddate()
    {
    	$temp = Tempauditing::find()->where(['tempauditing'=>Yii::$app->getUser()->id,'state'=>1])->one();
    	if($temp)
    		return $temp['enddate'];
    	else
    		return false;
    }
}
