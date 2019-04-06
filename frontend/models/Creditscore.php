<?php

namespace app\models;

use Yii;
use Zend\Stdlib\Guard\NullGuardTrait;

/**
 * This is the model class for table "{{%creditscore}}".
 *
 * @property integer $id
 * @property integer $farms_id
 * @property string $event
 * @property integer $value
 * @property integer $create_at
 */
class Creditscore extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%creditscore}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['farms_id', 'value', 'create_at','year','creditscoreconfig_id'], 'integer'],
            [['event'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'creditscoreconfig_id' => '事件ID',
            'farms_id' => '农场ID',
            'event' => '事件描述',
            'value' => '数值',
            'create_at' => '创建日期',
            'year' => '年度',
        ];
    }

    public static function run($farms_id,$save,$action=null)
    {
        if(empty($action))
            $action = Yii::$app->controller->id;
        $c = Creditscoreconfig::find()->where(['action'=>$action])->one();
        $value = self::conditionToValue($farms_id,$c,$save);
        $model = Creditscore::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'creditscoreconfig_id'=>$c['id']])->one();
        if(empty($model)) {
            $model = new Creditscore();
        }
        $model->farms_id = $farms_id;
        $model->creditscoreconfig_id = $c['id'];
        $model->event = self::actionToEvent($action);
        $model->value = $value;
        $model->create_at = time();
        $model->year = date('Y');
        $model->save();
        $farm = Farms::findOne($farms_id);
        $farm->creditscore += $value;
        $yearValue = Creditscore::find()->where(['farms_id'=>$farms_id,'year'=>date('Y')])->sum('value');
        $farm->star = $yearValue;
        $farm->save();
    }

    public static function conditionToValue($farms_id,$cconfig,$save)
    {
        $result = 0;
        $c = explode(':',$cconfig['condition']);
        $model = Creditscore::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'creditscoreconfig_id'=>$cconfig['id']])->one();
        switch ($c[0])
        {
            case 'time':
                $Date = strtotime(date('Y').'-'.$c[1]);
                $now = time();
                if($now > $Date and $save) {
                    if($model) {
                        $model->delete();
                    }
                }
                if($now <=  $Date and $save) {
                    $result = $cconfig['add'];
                }
                break;
            case 'save':
                $y = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()]);
                $check = Plantingstructurecheck::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()]);
                if($cconfig['action'] == 'plantingstructurecheck') {
                    $rows = $check->count();
                    if($rows == $y->count()) {
                        $sameNum = 0;
                        foreach ($check->all() as $value) {
                            $sameNum += $value['issame'];
                        }
                        if ($sameNum == $rows) {
                            $result = $cconfig['add'];
                        }
                        if($check->count() == 0) {
                            if($model) {
                                $model->delete();
                            }
                        }
                    }
                }
                if($cconfig['action'] == 'environment') {
                    $enviroment = Environment::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
                    if($enviroment['isgovernment']) {
                        $result = $cconfig['add'];
                    }
                    if($check->count() == 0) {
                        if($model) {
                            $model->delete();
                        }
                    }
                }
                break;
        }
        return $result;
    }

    public static function actionToEvent($action)
    {
        $str = '';
        switch ($action) {
            case 'tempprintbill':
                $str = '缴费信用';
                break;
            case 'plantingstructurecheck':
                $str = '种植结构复核数据是否与计划数据一致';
                break;
            case 'environment':
                $str = '是否进行环境治理';
                break;
        }
        return $str;
    }
}
