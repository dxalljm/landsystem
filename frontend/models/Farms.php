<?php

namespace app\models;

use Yii;
use yii\helpers\Json;
use app\models\ManagementArea;
/**
 * This is the model class for table "{{%farms}}".
 *
 * @property integer $id
 * @property string $farmname
 * @property string $address
 * @property string $management_area
 * @property string $spyear
 * @property integer $measure
 * @property string $zongdi
 * @property string $cooperative_id
 * @property string $surveydate
 * @property string $groundsign
 * @property string $investigator
 * @property string $farmersign
 */
class Farms extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%farms}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farmname'], 'required'],
            [['measure'], 'number'],
            [['farmname', 'address', 'zongdi', 'cooperative_id', 'groundsign', 'investigator', 'farmersign','pinyin'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'farmname' => '农场名称',
            'address' => '农场位置',
            'management_area' => '管理区',
            'spyear' => '审批年度',
            'measure' => '面积',
            'zongdi' => '宗地',
            'cooperative_id' => '合作社',
            'surveydate' => '调查日期',
            'groundsign' => '地产科签字',
            'investigator' => '地星调查员',
            'farmersign' => '农场法人签字',
        	'pinyin' => '农场名称拼音首字母',
        ];
    }
    
    public function getmanagementarea()
    {
    	return $this->hasOne(ManagementArea::className(), ['id' => 'management_area']);
    }

    /**
     * 修改农场保存之后
     */
    public function afterSave($insert, $changedAttributes)
    {
        // 调用父级afterSave
        parent::afterSave($insert, $changedAttributes);

        // 删除指定缓存
        $cacheKeyList = [
            'farms-search-all'
        ];
        foreach ($cacheKeyList as $cacheName) {
            Yii::$app->cache->delete($cacheKey);
        }
    }

    /**
     * 搜索所有农场信息
     * @author wubaiqing <wubaiqing@vip.qq.com>
     */
    public static function searchAll()
    {
        $cacheKey = 'farms-search-all';

        $result = Yii::$app->cache->get($cacheKey);
        if (!empty($result)) {
            return $result;
        }

        // 所有农场
        $data = [];
        $result = Farms::find()->all();
        foreach ($result as $farm) {
          $data[] = [
            'value' => $farm['pinyin'], // 拼音
            'data' => $farm['farmname'], // 下拉框显示的名称
            'role' => 1, // 角色 
          ];
        }
		$farmer = Farmer::find()->all();
		foreach ($farmer as $farm) {
			$data[] = ['value' => $farm['pinyin'], 'data' => $farm['farmername']];
		}
        $jsonData = Json::encode($data);
        Yii::$app->cache->set($cacheKey, $jsonData, 3);
        
        return $jsonData;
    }
    
    
}
