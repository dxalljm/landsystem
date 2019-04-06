<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%insurancemodfit}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property integer $management_area
 * @property integer $create_at
 * @property integer $update_at
 * @property string $year
 * @property double $oldinsuredarea
 * @property double $newinsuredarea
 * @property double $oldinsuredwheat
 * @property double $nowinsuredwheat
 * @property double $oldinsuredsoybean
 * @property double $nowinsuredsoybean
 * @property double $oldinsuredother
 * @property double $nowinsuredother
 * @property string $oldpolicyholder
 * @property string $nowpolicyholder
 * @property string $oldcardid
 * @property string $nowcardid
 * @property string $oldtelephone
 * @property string $nowtelephone
 * @property integer $oldcompany_id
 * @property integer $nowcompany_id
 * @property string $modfiycontent
 * @property integer $insurance_id
 */
class Insurancemodfit extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%insurancemodfit}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'management_area', 'create_at', 'update_at', 'oldcompany_id', 'nowcompany_id', 'insurance_id'], 'integer'],
            [['oldinsuredarea', 'newinsuredarea', 'oldinsuredwheat', 'nowinsuredwheat', 'oldinsuredsoybean', 'nowinsuredsoybean', 'oldinsuredother', 'nowinsuredother'], 'number'],
            [['modfiycontent'], 'string'],
            [['year', 'oldpolicyholder', 'nowpolicyholder', 'oldcardid', 'nowcardid', 'oldtelephone', 'nowtelephone'], 'string', 'max' => 500],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'farms_id' => 'Farms ID',
            'management_area' => 'Management Area',
            'create_at' => 'Create At',
            'update_at' => 'Update At',
            'year' => 'Year',
            'oldinsuredarea' => 'Oldinsuredarea',
            'newinsuredarea' => 'Newinsuredarea',
            'oldinsuredwheat' => 'Oldinsuredwheat',
            'nowinsuredwheat' => 'Nowinsuredwheat',
            'oldinsuredsoybean' => 'Oldinsuredsoybean',
            'nowinsuredsoybean' => 'Nowinsuredsoybean',
            'oldinsuredother' => 'Oldinsuredother',
            'nowinsuredother' => 'Nowinsuredother',
            'oldpolicyholder' => 'Oldpolicyholder',
            'nowpolicyholder' => 'Nowpolicyholder',
            'oldcardid' => 'Oldcardid',
            'nowcardid' => 'Nowcardid',
            'oldtelephone' => 'Oldtelephone',
            'nowtelephone' => 'Nowtelephone',
            'oldcompany_id' => 'Oldcompany ID',
            'nowcompany_id' => 'Nowcompany ID',
            'modfiycontent' => 'Modfiycontent',
            'insurance_id' => 'Insurance ID',
        ];
    }
}
