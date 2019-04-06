<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%processname}}".
 *
 * @property integer $id
 * @property string $processdepartment
 * @property string $Identification
 * @property integer $user_id
 * @property integer $spareuser
 */
class Processname extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%processname}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['processdepartment', 'Identification','rolename','sparerole'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'processdepartment' => '流程科室名称',
            'Identification' => '标识',
            'rolename' => '角色',
            'sparerole' => '备用角色',
        ];
    }

    public static function getUserIdentificationSql()
    {
        $where = [];
        $processname = self::find()->where(['department_id'=>Yii::$app->getUser()->getIdentity()->department_id,'level_id'=>Yii::$app->getUser()->getIdentity()->level])->all();
        switch (Yii::$app->controller->action->id) {
            case 'reviewprocessindex':
                $n = 2;
                break;
            case 'reviewprocesscacle':
                $n = 0;
                break;
            case 'reviewprocessing':
                $n = 2;
                break;
            case 'reviewprocessreturn':
                $n = 8;
                break;
        }
        foreach ($processname as $value) {
            $where[$value['Identification']] = $n;
        }
        return $where;
    }

   public static function getIdentification($model)
    {
        $temp = Tempauditing::find()->where(['tempauditing' => Yii::$app->getUser()->id, 'state' => 1])->andWhere('begindate<=' . strtotime(date('Y-m-d')) . ' and enddate>=' . strtotime(date('Y-m-d')))->one();
        if($temp) {
            $userinfo = User::find()->where(['id'=>$temp['user_id']])->one();
            $data = self::getProcessname($userinfo['department_id'],$userinfo['level']);
        } else {
            $data = self::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
        }

        $r = [];
        foreach ($data as $value) {
            if($model->$value == 2 or $model->$value == 8) {
                $classname = 'app\\models\\' . ucfirst($value);
                if($model->actionname == 'loancreate') {
                    $arr = $classname::loanAttributesKey();
                } else {
                    $arr = $classname::attributesKey();
                }
                $r[] = implode(',', $arr);
            }
        }
        return implode(',',$r);
    }

    public static function getAgreefield()
    {
        $data = self::find()->where(['rolename'=>User::getItemname()])->all();
    }

    public static function getDepartment($field,$model)
    {
        $k = 999;
        $data = self::find()->where(['Identification'=>Reviewprocess::getDepartment($field,$model)])->one();
        $undo = explode(',',$model->undo);
        $fromundo = explode(',',$model->fromundo);
        foreach ($fromundo as $key => $value) {
            if($field == $value) {
                $k = $key;
            }
        }
        if($k !== 999) {
            if ($undo[$k] == 'undo') {
                return '撤消';
            } else {
                return '退回' . $data['processdepartment'];
            }
        } else
            return '';
    }

    public static function getProcessname($department_id,$level_id)
    {
        $result = [];
        $processname = Processname::find()->all();
        foreach ($processname as $process) {
            if(in_array($department_id,explode(',',$process['department_id'])) and $level_id == $process['level_id']) {
                $result[] = $process['Identification'];
            }
        }
//        var_dump($result);exit;
        return $result;
    }

    public static function getProcessnameobj($department_id,$level_id)
    {
        $result = [];
        $processname = Processname::find()->all();
        foreach ($processname as $process) {
            if(in_array($department_id,explode(',',$process['department_id'])) and $level_id == $process['level_id']) {
                $result[] = $process;
            }
        }
//        var_dump($result);exit;
        return $result;
    }
    public static function getAuditprocess($classname)
    {
        $auditprocess = Auditprocess::find()->where(['actionname'=>$classname])->one();
//        var_dump($auditprocess['process']);exit;
        return explode('>',$auditprocess['process']);
    }
}
