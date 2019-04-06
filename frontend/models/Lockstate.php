<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%lockstate}}".
 *
 * @property integer $id
 * @property integer $systemstate
 * @property string $systemstatedate
 * @property string $platestate
 * @property integer $loanconfig
 * @property string $loanconfigdate
 * @property integer $transferconfig
 * @property string $transferconfigdate
 */
class Lockstate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%lockstate}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['systemstate', 'loanconfig', 'transferconfig','plantstate'], 'integer'],
            [['systemstatedate', 'platestate', 'loanconfigdate', 'transferconfigdate','plantstatedate','loanconfigdateend', 'transferconfigdateend','plantstatedateend','notuserloan'], 'string', 'max' => 500]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
			'id' => 'ID',
            'systemstate' => '系统状态',
            'systemstatedate' => '系统锁定时间',
            'platestate' => '板块锁定状态',
            'loanconfig' => '贷款配置项',
            'loanconfigdate' => '贷款配置冻结日期',
            'transferconfig' => '过户配置项',
            'transferconfigdate' => '过户冻结日期',
            'plantstate' => '种植结构 配置项',
            'plantstatedate' => '种植结构冻结日期',
            'loanconfigdateend' => '贷款配置冻结截至日期',
            'transferconfigdateend' => '过户冻结截至日期',
            'plantstatedateend' => '种植结构冻结截至日期',
            'notuserloan' => '贷款非冻结农场',
        ];
    }

    public static function getSystemLockState()
    {
        $model = Lockstate::findOne(1);
        if($model->systemstate) {
            if(time() < $model->systemstatedate) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public static function isLocked($id)
    {
        $model = Lockstate::findOne(1);
        $array = explode(',',$model->platestate);
        if(in_array($id,$array)) {
            return true;
        }
        return false;
    }

    public static function isUnlockloan($farms_id)
    {
        $farm = Farms::findOne($farms_id);
        if($farm['state'] == 4) {
            return true;
        }
        $farmsid = [];
        $farmsidTime = [];
        $model = Lockstate::findOne(1);

        if(!empty($model->notuserloan)) {
            $array = explode(',',$model->notuserloan);
            foreach ($array as $key => $value) {
                $val = explode('-', $value);
                $farmsid[$key] = $val[0];
                $farmsidTime[$key] = $val[1];
            }
        }
        if(in_array($farms_id,$farmsid)) {
            $time = $farmsidTime[array_search($farmsid,$farmsid)];
            $cha = time() - $time;
            if($cha > 259200) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public static function isLoanLocked($farms_id)
    {
        $model = Lockstate::findOne(1);
        if($model->loanconfig) {
            if(self::isCq($farms_id)) {
                return ['state'=>true,'msg'=>'因有陈欠未缴纳此项业务已经冻结。'];
            } else {
                $lockdate = strtotime(date('Y').'-'.$model->loanconfigdate);
                $lockdateend = strtotime(date('Y').'-'.$model->loanconfigdateend);
                if(time() > $lockdate and time() < $lockdateend) {
                    if(Collection::getYpay(User::getYear(),$farms_id)) {
                        return ['state'=>true,'msg'=>'因当年承包费未缴纳此项业务已经冻结。'];
                    } else {
                        return ['state'=>false];
                    }
                } else {
                    return ['state'=>false];
                }
            }
        } else {
            return ['state'=>false];
        }
    }

    public static function isTransferLocked($farms_id)
    {
        $farm = Farms::findOne($farms_id);
        if($farm['locked'] == 1) {
            return ['state' => true, 'msg' => '已冻结'];
        }
        $model = Lockstate::findOne(1);
        if($model->transferconfig) {
            if (self::isCq($farms_id)) {
                return ['state' => true, 'msg' => '因陈欠未缴纳此项已冻结'];
            } else {
                $lockdate = strtotime(date('Y') . '-' . $model->transferconfigdate);
                $lockdateend = strtotime(date('Y') . '-' . $model->transferconfigdateend);
                if (time() > $lockdate and time() < $lockdateend) {
                    if (Collection::getYpay(User::getYear(), $farms_id)) {
                        return ['state' => true, 'msg' => '当年承包费未缴纳此项业务已冻结'];
                    } else {
                        return ['state' => false];
                    }
                } else {
                    return ['state' => false];
                }
            }
        } else {
            return false;
        }
    }

    public static function isCq($farms_id)
    {
        $year = User::getLastYear();
        if(Collection::find()->where(['farms_id'=>$farms_id,'payyear'=>$year,'state'=>0])->count()) {
            return true;
        } else {
            return false;
        }
    }
}
