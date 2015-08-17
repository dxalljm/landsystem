<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%fireprevention}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property string $firecontract
 * @property string $safecontract
 * @property string $environmental_agreement
 * @property string $firetools
 * @property string $mechanical_fire_cover
 * @property string $chimney_fire_cover
 * @property string $isolation_belt
 * @property string $propagandist
 * @property string $fire_administrator
 * @property string $cooker
 * @property string $fieldpermit
 * @property string $propaganda_firecontract
 * @property string $leaflets
 * @property string $employee_firecontract
 * @property string $rectification_record
 * @property string $equipmentpic
 * @property string $peoplepic
 * @property string $facilitiespic
 */
class Fireprevention extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%fireprevention}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id'], 'integer'],
            [['firecontract', 'safecontract', 'environmental_agreement', 'firetools', 'mechanical_fire_cover', 'chimney_fire_cover', 'isolation_belt', 'propagandist', 'fire_administrator', 'cooker', 'fieldpermit', 'propaganda_firecontract', 'leaflets', 'employee_firecontract', 'rectification_record', 'equipmentpic', 'peoplepic', 'facilitiespic'], 'string', 'max' => 500]
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
            'firecontract' => '文火合同',
            'safecontract' => '安全生产合同',
            'environmental_agreement' => '环境保护协议',
            'firetools' => '扑火工具',
            'mechanical_fire_cover' => '机械设备防火罩',
            'chimney_fire_cover' => '烟囱防火罩',
            'isolation_belt' => '房屋防火隔离带',
            'propagandist' => '防火义务宣管员',
            'fire_administrator' => '一盒火管理员',
            'cooker' => '液化气灶具',
            'fieldpermit' => '野外作业许可证',
            'propaganda_firecontract' => '防火合同',
            'leaflets' => '防火宣传单',
            'employee_firecontract' => '雇工防火合同',
            'rectification_record' => '防火检查整改记录',
            'equipmentpic' => '设备照片',
            'peoplepic' => '人员照片',
            'facilitiespic' => '设施照片',
        ];
    }
}
