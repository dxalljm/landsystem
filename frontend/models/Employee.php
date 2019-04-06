<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%employee}}".
 *
 * @property integer $id
 * @property integer $father_id
 * @property string $employeetype
 * @property string $employeename
 * @property string $cardid
 * @property integer $create_at
 * @property integer $update_at
 */
class Employee extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%employee}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['father_id', 'farms_id', 'create_at', 'update_at','year'], 'integer'],
            [['employeetype', 'employeename', 'cardid','telephone'], 'string', 'max' => 500]
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
            'father_id' => '类别',
            'employeetype' => '雇工类型',
            'employeename' => '雇工姓名',
            'cardid' => '身份证号',
        	'telephone' => '电话',
            'create_at' => '创建日期',
            'update_at' => '更新日期',
            'year' => '年度'
        ];
    }


    /**
     * 批量添加员工
     * @param array $employeesPost 员工数组
     */
    public static function batchAdd($employeesPost)
    {
        foreach ($employeesPost['employeename'] as $k => $item) {

            // 如果为空跳出循环
            if (empty($item)) {
                continue;
            }
			$oldAttr = '';
            // 查找员工
            if(($model = self::findOne($employeesPost['id'][$k])) !== null) {
            	$oldAttr = $model->attributes;
            	$model->update_at = time();
            } else {
                $model = new Employee();
                $model->create_at = time();
                $model->update_at = time();
            }
            $model->farms_id = $employeesPost['farms_id'][$k];
            $model->father_id = $employeesPost['father_id'][$k];
            $model->employeename = $employeesPost['employeename'][$k];
            $model->employeetype = $employeesPost['employeetype'][$k];
            $model->cardid = $employeesPost['cardid'][$k];
            $model->telephone = $employeesPost['telephone'][$k];
            $model->year = User::getYear();
            $model->save();
			$newAttr = $model->attributes;
			Logs::writeLog('雇工信息批量添加更新',$model->id,$oldAttr,$newAttr);
        }
    }
}
