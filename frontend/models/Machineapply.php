<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%machineapply}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property string $farmername
 * @property integer $age
 * @property string $sex
 * @property string $domicile
 * @property integer $management_area
 * @property string $cardid
 * @property string $telephone
 * @property integer $machineoffarm_id
 * @property string $farmerpinyin
 */
class Machineapply extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%machineapply}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'age', 'management_area', 'machineoffarm_id','create_at','update_at','state','dckstate','year','scanfinished','machinetype_id','machineoffarmold_id'], 'integer'],
            [['farmername', 'sex', 'domicile', 'cardid', 'telephone', 'farmerpinyin','subsidymoney'], 'string', 'max' => 500],
            [['domicile'],'required'],
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
            'farmername' => '法人姓名',
            'age' => '年龄',
            'sex' => '性别',
            'domicile' => '户籍所在地',
            'management_area' => '管理区',
            'cardid' => '法人身份证',
            'telephone' => '联系电话',
            'machineoffarm_id' => '农机',
            'farmerpinyin' => '法人拼音首字母',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'state' => '状态',
            'dckstate' => '地产科提交状态',
            'subsidymoney' => '补贴金额',
            'year' => '年度',
            'scanfinished' => '扫描完成',
            'machinetype_id' => '农机类型',
            'machineoffarmold_id' => '原农机ID',
        ];
    }

    public static function getCount()
    {
        $processRows = Machineapply::find()->where(['scanfinished'=>0,'state'=>1,'dckstate'=>1,'year'=>User::getYear()])->count();

        if($processRows)
            return '<small class="label pull-right bg-red">'.$processRows.'</small>';
        else
            return false;
    }
}
