<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%breedtype}}".
 *
 * @property integer $id
 * @property integer $father_id
 * @property string $typename
 */
class Breedtype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%breedtype}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['father_id'], 'integer'],
            [['typename','unit'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'father_id' => '类别',
            'typename' => '类型名称',
        	'unit' => '单位',
        ];
    }
    
    public static function getAllTypename()
    {
    	$where = Farms::getManagementArea()['id'];
    	$result = Breedinfo::find ()->where(['management_area'=>$where])->all ();
    	//     	var_dump($farms);exit;
    	$data = [];
    	foreach($result as $value) {
    		$type = Breedtype::find()->where(['id'=>$value['breedtype_id']])->one();
    		$data[] = ['id'=>$type['id'],'typename'=>$type['typename']];
    	}
    	if($data) {
    		$newdata = Farms::unique_arr($data);
    		foreach ($newdata as $value) {
    			$d[$value['id']] = $value['typename'];
    		}
    		return $d;
    	}
    	else
    		return [];
    }
    
    public static function getTypenameOne($id)
    {
    	$data = self::getAllTypename();
    	return $data[$id];
    }
}
