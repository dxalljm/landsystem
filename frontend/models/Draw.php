<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%draw}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $year
 * @property integer $create_at
 * @property integer $management_area
 */
class Draw extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%draw}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'year', 'create_at', 'management_area','state'], 'integer'],
            [['cardid'],'string']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'farms_id' => '农场ID',
            'year' => '年度',
            'create_at' => '创建日期',
            'management_area' => '管理区',
            'cardid' => '身份证号',
            'state' => '状态'
        ];
    }

    public static function getFarms($management_area)
    {
        $farms = Plantingstructureyearfarmsid::find()->where(['management_area'=>$management_area])->all();
        $info = [];
        foreach ($farms as $farm) {
            $farminfo = Farms::findOne($farm['id']);
            $info[] = [
                'id' => $farminfo['id'],
                'farmname' => $farminfo['farmname'],
                'farmername' => $farminfo['farmername'],
            ];
        }
        return $info;
    }
}
