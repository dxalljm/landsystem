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

    public function rules()
    {
        return [
            [['user_id','breedinfotime', 'plantingstructuretime', 'plantinputproducttime', 'huinongtime', 'infrastructuretime', 'projectapplicationtime', 'insurancetime', 'loantime', 'firetime', 'farmstime', 'collectiontime'], 'integer'],
            [['farmscache','breedinfocache', 'collectioncache', 'plantingstructurecache', 'plantinputproductcache', 'huinongcache', 'infrastructurecache', 'plantinputproductcategories', 'infrastructurecategories', 'projectapplicationcache', 'insurancecache', 'loancache', 'firecache'], 'string'],
            [['farmstitle','breedinfotitle', 'breedinfocategories','farmscategories', 'collectiontitle', 'collectioncategories', 'plantingstructuretitle', 'plantingstructurecategories', 'huinongtitle', 'huinongcategories', 'plantinputproducttitle', 'infrastructuretitle', 'projectapplicationtitle', 'projectapplicationcategories', 'year', 'insurancetitle', 'insurancecategories', 'loantitle', 'firetitle', 'firecategories','projectapplicationdw'], 'string', 'max' => 500],
            [['loancategories'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'farmscache' => 'Farmscache',
            'collectioncache' => 'Collectioncache',
            'plantingstructurecache' => 'Plantingstructurecache',
            'plantingstructuretime' => 'Plantingstructuretime',
            'plantinputproductcache' => 'Plantinputproductcache',
            'plantinputproducttime' => 'Plantinputproducttime',
            'huinongcache' => 'Huinongcache',
            'huinongtime' => 'Huinongtime',
            'infrastructurecache' => 'Infrastructurecache',
            'infrastructuretime' => 'Infrastructuretime',
            'farmstitle' => 'Farmstitle',
            'farmscategories' => 'Farmscategories',
            'collectiontitle' => 'Collectiontitle',
            'collectioncategories' => 'Collectioncategories',
            'plantingstructuretitle' => 'Plantingstructuretitle',
            'plantingstructurecategories' => 'Plantingstructurecategories',
            'huinongtitle' => 'Huinongtitle',
            'huinongcategories' => 'Huinongcategories',
            'plantinputproducttitle' => 'Plantinputproducttitle',
            'plantinputproductcategories' => 'Plantinputproductcategories',
            'infrastructuretitle' => 'Infrastructuretitle',
            'infrastructurecategories' => 'Infrastructurecategories',
            'projectapplicationcache' => 'Projectapplicationcache',
            'projectapplicationtime' => 'Projectapplicationtime',
            'projectapplicationtitle' => 'Projectapplicationtitle',
            'projectapplicationcategories' => 'Projectapplicationcategories',
            'year' => 'Year',
            'insurancetitle' => 'Insurancetitle',
            'insurancecategories' => 'Insurancecategories',
            'insurancecache' => 'Insurancecache',
            'insurancetime' => 'Insurancetime',
            'loantitle' => 'Loantitle',
            'loancategories' => 'Loancategories',
            'loancache' => 'Loancache',
            'loantime' => 'Loantime',
            'firetitle' => 'Firetitle',
            'firecategories' => 'Firecategories',
            'firecache' => 'Firecache',
            'firetime' => 'Firetime',
            'farmstime' => 'Farmstime',
            'collectiontime' => 'Collectiontime',
            'breedinfocache' => 'Breedinfocache',
            'breedinfocategories' => 'Breedinfocategories',
            'breedinfotitle' => 'Breedinfotitle',
            'breedinfotime' => 'Breedinfotime',
            'projectapplicationdw' => 'Projectapplicationdw'
        ];
    }
    
    public static function getCache($user)
    {
    	$cache = Cache::find()->where(['user_id'=>$user,'year'=>User::getYear()])->one();
//     	var_dump($cache);
    	return $cache;
    }
}
