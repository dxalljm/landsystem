<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%lockedinfo}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property string $lockedcontent
 */
class Lockedinfo extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lockedinfo}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id'], 'integer'],
            [['lockedcontent'], 'string', 'max' => 500]
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
            'lockedcontent' => '冻结原因',
        ];
    }

    public static function deleteLockinfo($farms_id)
    {
        $lockinfo = self::find()->where(['farms_id'=>$farms_id])->one();
        $model = self::findOne($lockinfo['id']);
        if($model)
            $model->delete();
    }
}
