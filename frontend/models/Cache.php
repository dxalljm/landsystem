<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%cache}}".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $farmscache
 * @property string $collectioncache
 * @property string $plantingstructurecache
 * @property string $plantinputproductcache
 * @property string $huinongcache
 * @property string $infrastructurecache
 */
class Cache extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%cache}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'integer'],
            [['farmscache', 'collectioncache', 'plantingstructurecache', 'plantinputproductcache', 'huinongcache', 'infrastructurecache'], 'string']
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
            'farmscache' => '农场面积暂存',
            'collectioncache' => '收费情况暂存',
            'plantingstructurecache' => '作物情况暂存',
            'plantinputproductcache' => '投入品情况暂存',
            'huinongcache' => '惠农政策情况暂存',
            'infrastructurecache' => '基础设施情况暂存',
        ];
    }
    
    public static function getCache($user)
    {
    	$cache = Cache::find()->where(['user_id'=>$user])->one();
    	return $cache;
    }
}
