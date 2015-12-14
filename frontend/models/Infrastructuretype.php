<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%infrastructuretype}}".
 *
 * @property integer $id
 * @property integer $father_id
 * @property string $typename
 */
class Infrastructuretype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%infrastructuretype}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['father_id'], 'integer'],
            [['typename'], 'string', 'max' => 500]
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
        ];
    }

    /**
     * 获取全部的分类信息
     *
     * @return array
     */
    public static function getAllList()
    {
        $allList = [];

        $query = self::find()->select(
            ['id', 'father_id', 'typename']
        )->where(['is_delete' => 0])
         ->orderBy(['sort'=>SORT_ASC, 'id' => SORT_ASC]);


        $rows = $query->all();

        foreach ($rows as $rowkey => $rowValue) {
            $allList[$rowValue['id']] = $rowValue->attributes;
        }

        return $allList;
    }

    public static function getNameById($id)
    {
        $query = self::find()->select(
            ['id', 'father_id', 'typename']
        )->where(['id' => $id]);

        $rows = $query->one();

        $name = '';
        if (!empty($rows)) {
            return $rows->typename;
        }
        return $name;

    }

}
