<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%project}}".
 *
 * @property integer $id
 * @property integer $reviewprocess_id
 * @property integer $projectisAgree
 * @property string $projectisAgreecontent
 * @property string $projectundo
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%project}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['reviewprocess_id', 'projectisAgree'], 'integer'],
            [['projectisAgreecontent'], 'string'],
            [['projectundo'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'reviewprocess_id' => '审核过程ID',
            'projectisAgree' => '是否同意',
            'projectisAgreecontent' => '情况说明',
            'projectundo' => '退回',
        ];
    }
}
