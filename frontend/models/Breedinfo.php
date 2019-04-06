<?php

namespace app\models; 

use Yii;
use yii\helpers\ArrayHelper;

/** 
 * This is the model class for table "{{%breedinfo}}". 
 * 
 * @property integer $id
 * @property integer $breed_id
 * @property integer $number
 * @property double $basicinvestment
 * @property double $housingarea
 * @property integer $breedtype_id
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $management_area
 */ 
class Breedinfo extends \yii\db\ActiveRecord
{ 
    /** 
     * @inheritdoc 
     */ 
    public static function tableName() 
    { 
        return '{{%breedinfo}}'; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function rules() 
    { 
        return [
            [['breed_id', 'farms_id','number', 'breedtype_id', 'create_at', 'update_at', 'management_area','farmstate','clrate','state'], 'integer'],
            [['basicinvestment', 'housingarea'], 'number'],
            [['year'],'string'],
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'breed_id' => '养殖户ID',
        	'farms_id' => '农场ID',
            'number' => '数量',
            'basicinvestment' => '基础投资',
            'housingarea' => '圈舍面积',
            'breedtype_id' => '养殖种类',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'management_area' => '管理区',
            'year' => '年份',
            'farmstate' => '农场状态',
            'clrate' => '出栏',
            'state' => '状态'
        ]; 
    }

    public static function getBreedtypeList()
    {
        $typename = Breedtype::find()->andWhere('father_id>1')->all();
        return ArrayHelper::map($typename,'id','typename');
    }

    public static function getBreedinfoCache($state)
    {
        $result = [];
        $typeNumber = [];
        $dw = [];
        $typeValue = [];
        $type = [];
        $typeResult = [];
        $area = Farms::getManagementArea()['id'];
        $typename = self::getBreedtypeList();
//        var_dump($bank);
        foreach ( $area as $key => $value ) {
            foreach ($typename as $k => $val) {
                $number = Breedinfo::find()->where(['management_area'=>$value,'breedtype_id'=>$k,'year'=>User::getYear()])->sum('number');
                if($number) {
                    $typeNumber[] = $number;
                    $type[$k] = $val;
                }
            }
            $result['data'][] = [
                'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id'=>$value])->one()['areaname']),
                'type' => 'bar',
                'stack' => $value,
                'data' => $typeNumber,
            ];
        }
        $typeValue = array_unique($type);
        foreach ($typeValue as $key => $item) {
            $typeResult[] = $item;
            $dw[] = Breedtype::find()->where(['id'=>$key])->one()['unit'];
        }
        $result['typename'] = $typeResult;
        $result['dw'] = $dw;
//        var_dump($result);
        $jsonData = json_encode ($result[$state]);
        return $jsonData;
    }

    public static function getTypenameList()
    {
        $typename = [];
        $type = [];
        $management_area = Farms::getManagementArea()['id'];
        $breediinfos = Breedinfo::find()->where(['management_area'=>$management_area,'year'=>User::getYear()])->all();
        foreach ($breediinfos as $value) {
            $typename[] = Breedtype::find()->where(['id'=>$value['breedtype_id']])->one()['typename'];
        }
        $result = array_unique($typename);
        foreach ($result as $value) {
            $type[] = $value;
        }
        return json_encode($type);
    }
}