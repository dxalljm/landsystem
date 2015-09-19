<?php

namespace app\models;

use Yii;
use yii\helpers\Json;
use app\models\ManagementArea;
use yii\helpers\Url;
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
            [['farmname', 'farmername'], 'required'],
            [['measure'], 'number'],
            [['zongdi'], 'string'],
            [['create_at', 'update_at'], 'integer'],
            [['farmname', 'farmername', 'cardid', 'telephone', 'address', 'management_area', 'spyear', 'cooperative_id', 'surveydate', 'groundsign', 'investigator', 'farmersign', 'pinyin','farmerpinyin'], 'string', 'max' => 500]
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
            'farmername' => '承包人姓名',
            'cardid' => '身份证号',
            'telephone' => '电话号码',
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
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'pinyin' => '农场名称拼音首字母',
        ]; 
    }
    
    public function getfarmer()
    {
    	return $this->hasOne(Farmer::className(), ['farms_id' => 'id']);
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
            Yii::$app->cache->delete($cacheName);
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
        $departmentid = User::find()->where(['id'=>Yii::$app->getUser()->id])->one()['department_id'];
        $keshi = Department::find()->where(['id'=>$departmentid])->one()['departmentname'];
        switch ($keshi)
        {
        	case '财务科';
        		$url = 'index.php?r=collection/collectioncreate&farms_id=';
        		break;
        	default:
        		$url = 'index.php?r=farms/farmsmenu&farms_id=';
        }
        
        // 所有农场
        $data = [];
        $result = Farms::find()->all();
        foreach ($result as $farm) {
        	
          $data[] = [
            'value' => $farm['pinyin'], // 拼音
            'data' => $farm['farmname'], // 下拉框显示的名称
            'url' => Url::to($url.$farm['id']), 
          ];
        }
		$farmers = Farmer::find()->all();
		foreach ($farmers as $farmer) {
			$data[] = [
				'value' => $farmer['pinyin'], 
				'data' => $farmer['farmername'],
				'url' => Url::to($url.$farmer['farms_id'].'&cardid='.$farmer['cardid'].'&year='.date('Y')),
					
			];
		}
        $jsonData = Json::encode($data);
        Yii::$app->cache->set($cacheKey, $jsonData, 3600);
        
        return $jsonData;
    }
    
    
}
