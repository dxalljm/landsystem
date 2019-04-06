<?php

namespace app\models;

use frontend\models\breedinfoSearch;
use Yii;

/**
 * This is the model class for table "{{%collectionsum}}".
 *
 * @property integer $id
 * @property integer $management_area
 * @property double $allsum
 * @property double $realsum
 */
class Collectionsum extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%collectionsum}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['management_area'], 'integer'],
            [['allsum', 'realsum'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'management_area' => 'Management Area',
            'allsum' => 'Allsum',
            'realsum' => 'Realsum',
        ];
    }

    public static function updateReal($year)
    {
        $managementarea = [0,1,2,3,4,5,6,7];

        foreach ($managementarea as $id) {
            $sum = Collectionsum::find()->where(['management_area'=>$id,'year'=>$year])->one();
            if($sum)
                $model = Collectionsum::findOne($sum['id']);
            else
                $model = new Collectionsum();
            $model->management_area = $id;
            $model->year = $year;
            switch ($id) {
                case 0:
                    $model->realsum = Collection::find()->where(['state'=>[1,2],'payyear'=>$year])->sum('real_income_amount');
                    break;
                default:
                    $model->realsum = Collection::find()->where(['management_area'=>$id,'state'=>[1,2],'payyear'=>$year])->sum('real_income_amount');
            }
//            var_dump($model);
            $model->save();
        }
    }

    public static function updateSum($year)
    {
        $managementarea = [0,1,2,3,4,5,6,7];

        foreach ($managementarea as $id) {
            $sum = Collectionsum::find()->where(['management_area'=>$id,'year'=>$year])->one();
            if($sum)
                $model = Collectionsum::findOne($sum['id']);
            else
                $model = new Collectionsum();
            $model->management_area = $id;
            $model->year = $year;
            switch ($id) {
                case 0:
                    $sumarea = Farms::find()->where(['state'=>[1,2,3]])->sum('contractarea');
                    $model->allsum = $sumarea*30 - $model->realsum;
                    break;
                default:
                    $sumarea = Farms::find()->where(['state'=>[1,2,3],'management_area'=>$id])->sum('contractarea');
                    $model->allsum = $sumarea*30 - $model->realsum;
            }
//            var_dump($model);
            $model->save();
        }
    }
}
