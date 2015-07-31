<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%farmermembers}}".
 *
 * @property integer $id
 * @property integer $farmer_id
 * @property string $relationship
 * @property string $membername
 * @property string $cardid
 * @property string $remarks
 */
class Farmermembers extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%farmermembers}}';
    }

    /**
     * @inheritdoc
     */
public function rules() 
    { 
        return [
            [['farmer_id', 'isupdate'], 'integer'],
            [['remarks'], 'string'],
            [['relationship', 'membername', 'cardid'], 'string', 'max' => 500]
        ]; 
    } 

    /** 
     * @inheritdoc 
     */ 
    public function attributeLabels() 
    { 
        return [ 
            'id' => 'ID',
            'farmer_id' => '法人ID',
            'relationship' => '关系',
            'membername' => '姓名',
            'cardid' => '身份证号',
            'remarks' => '备注',
            'isupdate' => '是否可更新',
        ]; 
    }


    /**
     * 格式化家庭成员数据
     * @param array $attr POST-Parmember数据
     * @param array $reuqireField  必填字段
     * @return array 新格式化的数组
     */
    public static function formatAttr(array $attr, array $reuqireField)
    {
        $newAttr = [];
        foreach ($attr as $key => $filed) {
            foreach ($filed as $childKey => $value) {

                // 判断是否为空
                $relationship = self::isEmpty($attr['relationship'][$childKey]);
                $membername   = self::isEmpty($attr['membername'][$childKey]);
                $cardid       = self::isEmpty($attr['cardid'][$childKey]);
                $remarks      = self::isEmpty($attr['remarks'][$childKey]);

                // 如果都为空，跳出循环
                if (empty($relationship) && empty($membername) && empty($cardid) && empty($remarks)) {
                    continue;
                }

            }
        }
        return $newAttr;
    }

    /**
     * 验证是否为空
     * @param string $data
     * @return bool
     */
    public static function isEmpty($data)
    {
        if (!empty($data)) {
            return true;
        }
        return false;
    }

    public static function returnData($status, $data)
    {

    }

}
