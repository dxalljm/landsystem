<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%loan}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property double $mortgagearea
 * @property string $mortgagebank
 * @property double $mortgagemoney
 * @property string $mortgagetimelimit
 */
class Loan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%loan}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id'], 'integer'],
            [['mortgagearea', 'mortgagemoney', 'create_at','update_at'], 'number'],
            [['mortgagebank','begindate','enddate'], 'string', 'max' => 500]
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
            'mortgagearea' => '抵押面积',
            'mortgagebank' => '抵押银行',
            'mortgagemoney' => '贷款金额',
            'begindate' => '开始日期',
        	'enddate' => '结束日期',
        	'create_at' => '创建日期',
        	'update_at' => '更新日期',
        ];
    }
}
