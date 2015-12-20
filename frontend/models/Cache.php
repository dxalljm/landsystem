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
            [['farmscache', 'collectioncache', 'plantingstructurecache', 'plantinputproductcache', 'huinongcache', 'infrastructurecache', 'plantinputproductcategories', 'infrastructurecategories'], 'string'],
            [['farmstitle', 'farmscategories', 'collectiontitle', 'collectioncategories', 'plantingstructuretitle', 'plantingstructurecategories', 'huinongtitle', 'huinongcategories', 'plantinputproducttitle', 'infrastructuretitle'], 'string', 'max' => 500]
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
            'farmstitle' => '农场标题',
            'farmscategories' => '管理区列表',
            'collectiontitle' => '缴费情况标题',
            'collectioncategories' => '应收实收列表',
            'plantingstructuretitle' => '作物标题',
            'plantingstructurecategories' => '作物列表',
            'huinongtitle' => '惠农标题',
            'huinongcategories' => '惠农政策列表',
            'plantinputproducttitle' => '投入品标题',
            'plantinputproductcategories' => '投入品列表',
            'infrastructuretitle' => '基础设施标题',
            'infrastructurecategories' => '基础设施列表',
        ]; 
    } 
    
    public static function getCache($user)
    {
    	$cache = Cache::find()->where(['user_id'=>$user])->one();
    	return $cache;
    }
}
