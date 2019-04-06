<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%ttpozongdi}}".
 *
 * @property integer $id
 * @property integer $oldfarms_id
 * @property integer $newfarms_id
 * @property string $zongdi
 * @property string $create_at
 */
class Ttpozongdi extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%ttpozongdi}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['oldfarms_id','oldnewfarms_id','newfarms_id', 'newnewfarms_id','reviewprocess_id', 'state', 'auditprocess_id','samefarms_id'], 'integer'],
            [['newchangezongdi', 'oldzongdi', 'ttpozongdi', 'oldchangecontractnumber','oldchangezongdi','newzongdi'], 'string'],
            [['ttpoarea', 'oldmeasure', 'oldnotclear', 'oldnotstate', 'newmeasure', 'newnotclear', 'newnotstate', 'oldchangemeasure', 'oldchangenotclear', 'oldchangenotstate', 'newchangemeasure', 'newchangenotclear', 'newchangenotstate'], 'number'],
            [['create_at', 'oldcontractnumber', 'actionname', 'newcontractnumber', 'newchangecontractnumber'], 'string', 'max' => 500]
        ]; 
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
      	return [ 
            'id' => 'ID',
            'oldfarms_id' => '原农场ID',
            'newfarms_id' => '现农场ID',
      		'oldnewfarms_id' => '原农场新ID',
      		'newnewfarms_id' => '现农场新ID',
            'newchangezongdi' => '转让宗地',
            'create_at' => '创建日期',
            'oldzongdi' => '原宗地',
            'ttpozongdi' => '转让的宗地',
            'ttpoarea' => '转让的面积',
            'reviewprocess_id' => '审核过程ID',
            'oldcontractnumber' => '旧合同号',
            'state' => '状态',
            'auditprocess_id' => '流程ID',
            'actionname' => '方法名称',
            'oldmeasure' => '原宗地面积',
            'oldnotclear' => '原未明确地块面积',
            'oldnotstate' => '原未明确状态面积',
            'newcontractnumber' => '被转让方原合同号',
            'newmeasure' => '被转让方原宗地面积',
            'newnotclear' => '被转让方原未明确地块面积',
            'newnotstate' => '被转让方原未明确状态面积',
            'oldchangemeasure' => '宗地面积',
            'oldchangenotclear' => '未明确地块面积',
            'oldchangenotstate' => '未明确状态面积',
            'oldchangecontractnumber' => '合同号',
            'newchangemeasure' => '新改成宗地面积',
            'newchangenotclear' => '新改变未明确地块面积',
            'newchangenotstate' => '新改变未明确状态面积',
            'newchangecontractnumber' => '新改变合同面积',
            'newzongdi' => '新农场宗地',
      		'oldchangezongdi' => '原改变原宗地',
            'samefarms_id' => '分户指向原农场ID'
        ]; 
    }

    //判断是否为一个合同分户
    public static function isSplit($reviewprocess_id)
    {
        $ttpozongdi = self::find()->where(['reviewprocess_id'=>$reviewprocess_id])->one();
        if(empty($ttpozongdi->samefarms_id))
            return 0;
        return $ttpozongdi->samefarms_id;
    }

//    public static function getSameCount($reviewprocess_id)
//    {
//        $sameid = self::isSplit($reviewprocess_id);
//        $reviewprocess = Reviewprocess::find()->where(['id'=>$reviewprocess_id])->one():
//
//        return self::find()->where(['samefarms_id'=>$sameid])->count();
//    }

    public static function getSameCount($reviewprocess_id)
    {
//     	var_dump($reviewprocess_id);
        $reviewprocessID = [];
        $sameid = self::isSplit($reviewprocess_id);
        $allid = [];
        switch (Yii::$app->controller->action->id) {
            case 'reviewprocessing':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid])->all();
                break;
            case 'reviewprocesscacle':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>9])->all();
                break;
            case 'reviewprocessindex':
//                 $reviewprocess = Reviewprocess::findOne($reviewprocess_id);
// //                 var_dump($reviewprocess);
//                 if($reviewprocess->estate == 2) {
//                     $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->all();
                	
//                 }
//                 else {
                    $processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
//                     var_dump($processnames);
                    if ($processnames) {
                        $where = [];
//                        foreach ($processnames as $proces) {
                            return  Reviewprocess::find()->where(['samefarms_id'=>$sameid,$processnames[0]['Identification'] => 2])->count();
//                        }
                    }

//                 }

                break;
            case 'reviewprocesswait':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>0])->all();
                break;
            case 'reviewprocessfinished':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->all();
                break;
            case 'reviewprocessreturn':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>8])->all();
                break;
            case 'reviewprocessfarmssplit':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->all();
                break;
        }
//         var_dump($ttpozongdi);
        foreach ($ttpozongdi as $value) {
            $allid[] = $value['reviewprocess_id'];
        }
//        var_dump($allid);
//         if(yii::$app->controller->action->id == 'reviewprocesswait' or yii::$app->controller->action->id == 'reviewprocessfinished' or yii::$app->controller->action->id == 'reviewprocessing' or yii::$app->controller->action->id == 'reviewprocessreturn' or yii::$app->controller->action->id == 'reviewprocessindex') {
            return count($allid);
//         }
//         else
//             return Reviewprocess::find()->where(['id'=>$allid])->andFilterWhere(Processname::getUserIdentificationSql())->count();
    }

    public static function getSameCountRow($reviewprocess_id)
    {
        $reviewprocessID = [];
        $sameid = self::isSplit($reviewprocess_id);
        $allid = [];
        switch (Yii::$app->controller->action->id) {
            case 'reviewprocessing':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid])->all();
                break;
            case 'reviewprocesscacle':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>9])->all();
                break;
            case 'reviewprocessindex':
//                 $reviewprocess = Reviewprocess::findOne($reviewprocess_id);
//                 if($reviewprocess->estate == 2)
//                     $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->all();
//                 else {
                    $processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
                    if ($processnames) {
                        $where = [];
//                        foreach ($processnames as $proces) {
                        return  Reviewprocess::find()->where(['samefarms_id'=>$sameid,$processnames[0]['Identification'] => 2])->count();
//                        }
                    }

//                 }

                break;
            case 'reviewprocesswait':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>0])->all();
                break;
            case 'reviewprocessfinished':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->all();
                break;
            case 'reviewprocessreturn':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>8])->all();
                break;
            case 'reviewprocessfarmssplit':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->all();
                break;
            case 'reviewprocessview':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->all();
                break;
        }
        foreach ($ttpozongdi as $value) {
            $allid[] = $value['reviewprocess_id'];
        }
        $result = array_search($reviewprocess_id,$allid);
        return $result + 1;
////        var_dump($allid);
//        if(yii::$app->controller->action->id == 'reviewprocesswait' or yii::$app->controller->action->id == 'reviewprocessfinished' or yii::$app->controller->action->id == 'reviewprocessing' or yii::$app->controller->action->id == 'reviewprocessreturn' or yii::$app->controller->action->id == 'reviewprocessindex') {
//            return count($allid);
//        }
//        else
//            return Reviewprocess::find()->where(['id'=>$allid])->andFilterWhere(Processname::getUserIdentificationSql())->count();
    }

    public static function getSameCacleCount($reviewprocess_id)
    {
        $reviewprocessID = [];
        $sameid = self::isSplit($reviewprocess_id);
        $count = 0;
        $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid])->all();
        foreach ($ttpozongdi as $value) {
            $count += Reviewprocess::find()->where(['id'=>$value['reviewprocess_id'],'state'=>9])->count();;
        }
        return $count;
    }

    public static function getSameCacleFirstID($reviewprocess_id)
    {
//        var_dump($reviewprocess_id);
        $sameid = self::isSplit($reviewprocess_id);
        if($sameid == 0) {
            return $reviewprocess_id;
        }
        $data = [];
        $resultID = [];
        $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid])->all();
//        var_dump($ttpozongdi);
        foreach ($ttpozongdi as $value) {
            $data[] = Reviewprocess::find()->where(['id'=>$value['reviewprocess_id'],'state'=>9])->all();
        }

//        var_dump($reviewprocess);
        foreach ($data as $value) {
            foreach ($value as $val) {
                $resultID[] = $val['id'];
            }

        }
//        var_dump($resultID);
        if($data)
            return $resultID[0];
        else
            return $reviewprocess_id;
    }

 public static function getSameFirstID($reviewprocess_id)
{
//        var_dump($reviewprocess_id);
    $sameid = self::isSplit($reviewprocess_id);
    if($sameid == 0) {
        return $reviewprocess_id;
    }
    $allid = [];
    $resultID = [];
    switch (Yii::$app->controller->action->id) {
        case 'reviewprocessing':
            $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid])->all();
            break;
        case 'reviewprocesscacle':
            $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>9])->all();
            break;
        case 'reviewprocessindex':
            $reviewprocess = Reviewprocess::findOne($reviewprocess_id);
            if($reviewprocess->estate == 2)
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>0])->all();
            else {
                $processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
                if ($processnames) {
                    $reviewprocess = Reviewprocess::find()->where(['samefarms_id'=>$sameid,$processnames[0]['Identification'] => 2])->all();
                    if($reviewprocess) {
                        foreach ($reviewprocess as $value) {
                            $resultID[] = $value['id'];
                        }
                        return $resultID[0];
                    } else
                        return $reviewprocess_id;
                }

            }
            break;
        case 'reviewprocesswait':
            $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>0])->all();
            break;
        case 'reviewprocessfinished':
            $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->all();
            break;
        case 'reviewprocessreturn':
            $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>8])->all();
            break;
        case 'reviewprocessinspections':
            $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>4])->all();
            break;
        case 'reviewprocessfarmssplit':
            $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->all();
            break;
        case 'reviewprocessview':
            $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->all();
            break;
        default:
            $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->all();
    }
//        var_dump($ttpozongdi);
    foreach ($ttpozongdi as $value) {
        $allid[] = $value['reviewprocess_id'];
    }
//        var_dump($allid);
//        var_dump(Yii::$app->controller->action->id);exit;
//    if(yii::$app->controller->action->id == 'reviewprocesswait' or yii::$app->controller->action->id == 'reviewprocessfinished' or yii::$app->controller->action->id == 'reviewprocessing' or yii::$app->controller->action->id == 'reviewprocessreturn' or yii::$app->controller->action->id == 'reviewprocessindex') {
        $reviewprocess = Reviewprocess::find()->where(['id' => $allid])->all();
//    }
//    else
//        $reviewprocess = Reviewprocess::find()->where(['id'=>$allid])->andFilterWhere(Processname::getUserIdentificationSql())->all();
//        var_dump($reviewprocess);
    foreach ($reviewprocess as $value) {
        $resultID[] = $value['id'];
    }
//        var_dump($resultID);exit;
    if($allid)
        return $resultID[0];
    else
        return $reviewprocess_id;
}
    public static function getSameLastID($reviewprocess_id)
    {
//        var_dump($reviewprocess_id);
        $sameid = self::isSplit($reviewprocess_id);
        if($sameid == 0) {
            return $reviewprocess_id;
        }
        $allid = [];
        $resultID = [];
        switch (Yii::$app->controller->action->id) {
            case 'reviewprocessing':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid])->orderBy('id desc')->all();
                break;
            case 'reviewprocesscacle':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>9])->orderBy('id desc')->all();
                break;
            case 'reviewprocessindex':
//                 $reviewprocess = Reviewprocess::findOne($reviewprocess_id);
//                 if($reviewprocess->estate == 2)
//                     $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->orderBy('id desc')->all();
//                 else {
                    $processnames = Processname::getProcessname(Yii::$app->getUser()->getIdentity()->department_id,Yii::$app->getUser()->getIdentity()->level);
                    if ($processnames) {
                        $reviewprocess = Reviewprocess::find()->where(['samefarms_id'=>$sameid,$processnames[0]['Identification'] => 2])->orderBy('id desc')->all();
                        if($reviewprocess) {
                            foreach ($reviewprocess as $value) {
                                $resultID[] = $value['id'];
                            }
                            return $resultID[0];
                        } else
                            return $reviewprocess_id;
                    }

//                 }
                break;
            case 'reviewprocesswait':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>0])->orderBy('id desc')->all();
                break;
            case 'reviewprocessfinished':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->orderBy('id desc')->all();
                break;
            case 'reviewprocessreturn':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>8])->orderBy('id desc')->all();
                break;
            case 'reviewprocessinspections':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->orderBy('id desc')->all();
                break;
            case 'reviewprocessfarmssplit':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->orderBy('id desc')->all();
                break;
            case 'reviewprocessview':
                $ttpozongdi = self::find()->where(['samefarms_id'=>$sameid,'state'=>1])->all();
                break;
        }
//        var_dump($ttpozongdi);
        foreach ($ttpozongdi as $value) {
            $allid[] = $value['reviewprocess_id'];
        }
//        var_dump($allid);
//        var_dump(Yii::$app->controller->action->id);exit;
//         if(yii::$app->controller->action->id == 'reviewprocesswait' or yii::$app->controller->action->id == 'reviewprocessfinished' or yii::$app->controller->action->id == 'reviewprocessing' or yii::$app->controller->action->id == 'reviewprocessreturn' or yii::$app->controller->action->id == 'reviewprocessindex' or yii::$app->controller->action->id == 'reviewprocessview') {
            $reviewprocess = Reviewprocess::find()->where(['id' => $allid])->orderBy('id desc')->all();
//         }
//         else
//             $reviewprocess = Reviewprocess::find()->where(['id'=>$allid])->andFilterWhere(Processname::getUserIdentificationSql())->orderBy('id desc')->all();
//        var_dump($reviewprocess);
        foreach ($reviewprocess as $value) {
            $resultID[] = $value['id'];
        }
//        var_dump($resultID);exit;
        if($allid)
            return $resultID[0];
        else
            return $reviewprocess_id;
    }

    public static function getSamefarmsidCount($ttpozongdi_id)
    {
        $ttpozongdi = Ttpozongdi::find()->where(['id'=>$ttpozongdi_id])->one();
        if($ttpozongdi->samefarms_id)
            return Ttpozongdi::find()->where(['samefarms_id'=>$ttpozongdi['samefarms_id'],'state'=>[0]])->count();
        else
            return 0;
    }

    public static function getSameFirstContractnumber($reviewprocess_id)
    {
        $firstid = self::getSameFirstID($reviewprocess_id);
        $reviewprocess = Reviewprocess::find()->where(['id'=>$firstid])->one();
        $ttpo = Ttpozongdi::find()->where(['id'=>$reviewprocess['ttpozongdi_id']])->one();
        return $ttpo['oldcontractnumber'];
    }

    public static function getSameLastContractnumber($reviewprocess_id)
    {
        $firstid = self::getSameLastID($reviewprocess_id);
//        var_dump($firstid);
        $reviewprocess = Reviewprocess::find()->where(['id'=>$firstid])->one();
        $ttpo = Ttpozongdi::find()->where(['id'=>$reviewprocess['ttpozongdi_id']])->one();
        if(Farms::getContractnumberArea($ttpo['oldchangecontractnumber']))
            return $ttpo['oldchangecontractnumber'];
        else
            return '';
    }
    
    public static function getSameFirstZongdi($reviewprocess_id)
    {
    	$reviewprocess = Reviewprocess::find()->where(['id'=>$reviewprocess_id])->one();
    	$farm = Farms::find()->where(['id'=>$reviewprocess['samefarms_id']])->one();
//     	var_dump($farm);
    	return $farm['zongdi'];
    }
    public static function getSameFirstMeasure($reviewprocess_id)
    {
    	$reviewprocess = Reviewprocess::find()->where(['id'=>$reviewprocess_id])->one();
    	$farm = Farms::find()->where(['id'=>$reviewprocess['samefarms_id']])->one();
    	//     	var_dump($farm);
    	return $farm['measure'];
    }
    public static function getSameLastMeasure($reviewprocess_id)
    {
    	$lastid = self::getSameLastID($reviewprocess_id);
    	$reviewprocess = Reviewprocess::find()->where(['id'=>$lastid])->one();
    	$ttpo = Ttpozongdi::find()->where(['id'=>$reviewprocess['ttpozongdi_id']])->one();
    	$farm = Farms::find()->where(['id'=>$ttpo['oldnewfarms_id']])->one();
//     	    	var_dump($farm);
    	return $farm['measure'];
    }
}
