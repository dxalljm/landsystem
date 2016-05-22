<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ttpozongdi}}".
 *
 * @property integer $id
 * @property integer $oldfarms_id
 * @property integer $newfarms_id
 * @property string $zongdi
 * @property string $create_at
 */
class Ttpozongdi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ttpozongdi}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldfarms_id', 'newfarms_id', 'reviewprocess_id', 'state', 'auditprocess_id'], 'integer'],
            [['newchangezongdi', 'oldzongdi', 'ttpozongdi', 'oldchangecontractnumber','oldchangezongdi','newzongdi'], 'string'],
            [['ttpoarea', 'oldmeasure', 'oldnotclear', 'oldnotstate', 'newmeasure', 'newnotclear', 'newnotstate', 'oldchangemeasure', 'oldchangenotclear', 'oldchangenotstate', 'newchangemeasure', 'newchangenotclear', 'newchangenotstate'], 'number'],
            [['create_at', 'oldcontractnumber', 'actionname', 'newcontractnumber', 'newchangecontractnumber'], 'string', 'max' => 500]
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
      	return [ 
            'id' => 'ID',
            'oldfarms_id' => '原农场ID',
            'newfarms_id' => '现农场ID',
            'newchangezongdi' => '转让宗地',
            'create_at' => '创建日期',
            'oldzongdi' => '原宗地',
            'ttpozongdi' => '转让的宗地',
            'ttpoarea' => '转让的面积',
            'reviewprocess_id' => '审核过程ID',
            'oldcontractnumber' => '旧合同号',
            'state' => '状态',
            'auditprocess_id' => '流程ID',
            'actionname' => '方法名称',
            'oldmeasure' => '原宗地面积',
            'oldnotclear' => '原未明确地块面积',
            'oldnotstate' => '原未明确状态面积',
            'newcontractnumber' => '被转让方原合同号',
            'newmeasure' => '被转让方原宗地面积',
            'newnotclear' => '被转让方原未明确地块面积',
            'newnotstate' => '被转让方原未明确状态面积',
            'oldchangemeasure' => '宗地面积',
            'oldchangenotclear' => '未明确地块面积',
            'oldchangenotstate' => '未明确状态面积',
            'oldchangecontractnumber' => '合同号',
            'newchangemeasure' => '新改成宗地面积',
            'newchangenotclear' => '新改变未明确地块面积',
            'newchangenotstate' => '新改变未明确状态面积',
            'newchangecontractnumber' => '新改变合同面积',
            'newzongdi' => '新农场宗地',
      		'oldchangezongdi' => '原改变原宗地',
        ]; 
    }
}
