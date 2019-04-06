<?php

namespace app\models;

use Yii;
use app\models\Machineoffarm;
/**
 * This is the model class for table "{{%machineapplymachine}}".
 *
 * @property integer $id
 * @property integer $machine_id
 * @property integer $farms_id
 * @property integer $machinetype_id
 * @property string $machinename
 * @property integer $create_at
 * @property integer $update_at
 * @property integer $acquisitiontime
 * @property string $cardid
 */
class Machineapplymachine extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%machineapplymachine}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['machine_id', 'farms_id', 'machinetype_id', 'create_at', 'update_at', 'acquisitiontime'], 'integer'],
            [['machinename', 'cardid'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'machine_id' => '农机ID',
            'farms_id' => '农场ID',
            'machinetype_id' => '农机ID',
            'machinename' => '农机名称',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'acquisitiontime' => '购置年份',
            'cardid' => '身份证号',
        ];
    }

    public static function newMachine($machineoffarm_id)
    {
        $model = new Machineapplymachine();
        $offarm = Machineoffarm::findOne($machineoffarm_id);
//        $model->id = $offarm['id'];
        $model->cardid = $offarm['cardid'];
        $model->machinename = $offarm ['machinename'];
        $model->machinetype_id = $offarm['machinetype_id'];
        $model->machine_id = $offarm['machine_id'];
        $model->create_at = $offarm['create_at'];
        $model->update_at = $offarm['update_at'];
        $model->acquisitiontime = $offarm['acquisitiontime'];
        $model->farms_id = $offarm['farms_id'];
        $model->save ();
        return $model->id;
    }
}
