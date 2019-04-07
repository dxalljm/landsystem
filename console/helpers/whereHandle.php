<?php
namespace frontend\helpers;
use app\models\Farms;
use app\models\ManagementArea;
use app\models\User;
use app\models\Theyear;

/**
 * Created by PhpStorm.
 * User: liujiaming
 * Date: 2017/7/16
 * Time: 14:08
 */
class whereHandle
{
    public static function whereToArray($condition,$delete=null) {
        if (! is_array ( $condition )) {
            return $condition;
        }
        if (! isset ( $condition [0] )) {
            // hash format: 'column1' => 'value1', 'column2' => 'value2', ...
            foreach ( $condition as $name => $value ) {
                if (self::isEmpty ( $value )) {
                    unset ( $condition [$name] );
                }
            }
            return $condition;
        }
//        var_dump($condition);exit;
        $operator = array_shift ( $condition );
        switch (strtoupper ( $operator )) {
            case 'NOT' :
            case 'AND' :
            case 'OR' :
                foreach ( $condition as $i => $operand ) {
                    $subcondition = self::whereToArray( $operand );
                    if (self::isEmpty ( $subcondition )) {
                        unset ( $condition [$i] );
                    } else {
                        $condition [$i] = $subcondition;
                    }
                }
                break;
            case 'LIKE' :
                return [
                    'like',$condition [0], $condition [1]
                ];
                break;
            case 'BETWEEN':
                return [
                    ['between',$condition [0],$condition [1],$condition [2]]]
                    ;
                break;
            default :
                $condition = null;
        }
//        var_dump($condition);exit;
        $result = [];
        if(is_array($condition)) {
            foreach ($condition as $value) {
                foreach ($value as $k => $v) {
                    $result[$k] = $v;
                }
            }
        } else {
            $result = $condition;
        }
//        var_dump($result);exit;
        if(!empty($delete)) {
            if(is_array($delete)) {
                foreach ($delete as $item) {
                    if (isset($result[$item])) {
                        unset($result[$item]);
                    }
                }
            } else {
                if (isset($result[$delete])) {
                    unset($result[$delete]);
                }
            }
        }
        return $result;
    }

    public static function getField($array,$field)
    {
        $result = [];
        if(empty($array)) {
            if($field == 'year') {
                return User::getYear();
            }
        }
        switch ($field) {
            case 'year':
                if (isset($array[$field])) {
                    return $array[$field];
                } else {
                    if(is_array($array)) {
                        foreach ($array as $value) {
                            if (is_array($value)) {
                                if (count($value) >= 4) {
                                    if ($value[1] == 'update_at') {
                                        return Theyear::getYears($value[2], $value[3]);
                                    }
                                }
                            }
                        }
                    }
                }
                break;
            case 'update_at':
                foreach ($array as $value) {
                    if(is_array($value)) {
                        if($value[0] === 'update_at') {
                            return Theyear::getYears($value[2],$array[3]);
                        }
                    }
                }
                break;
            default:
                if (isset($array[$field])) {
                    return $array[$field];
                }
                return '';
        }
    }

    protected static function isEmpty($value) {
        return $value === '' || $value === [ ] || $value === null || is_string ( $value ) && trim ( $value ) === '';
    }
    
    public static function getOnlyWhere($condition,$only)
    {
        $result = [];
        $array = self::whereToArray($condition);
        foreach ($array as $key => $value) {
            foreach ($only as $val) {
                if($key == $val) {
                    $result[$val] = $value;
                }
            }
        }
        return $result;
    }
    
    public static function toFarmsWhere($condition)
    {
        $result = ['year'=>User::getYear()];
        $array = self::whereToArray($condition);
//        var_dump($array);exit;
        if(is_array($array)) {
            foreach ($array as $key => $value) {

                switch ($key) {
                    case 'management_area':
                        $result['management_area'] = $value;
                        break;
                    case 'state':
                        if($value)
                            $result['state'] = $value;
                        else {
                            $result['state'] = [1,2,3,4,5];
                        }
                        break;
                    case 'farmerpinyin':
                    case 'farmername':
                    case 'pinyin':
                    case 'farmname':
                        $farmsid = [];
                        $farms = Farms::find()->andFilterWhere($condition)->all();
                        foreach ($farms as $farm) {
                            $farmsid[] = $farm['id'];
                        }
                        $result['id'] = $farmsid;
                        break;
                }
            }
        }
        return $result;
    }

    public static function toPlantWhere($condition)
    {
//        var_dump($condition);
//        $result['year'] = User::getYear();
        $array = self::whereToArray($condition);
//        var_dump($array);
        $result = [];
        if(is_array($array)) {
            foreach ($array as $key => $value) {
                switch ($key) {
                    case 'year':
                        $result['year'] = $value;
                        break;
                    case 'contractarea':
                        $result['contractarea'] = $value;
                        break;
                    case 'management_area':
                        $result['management_area'] = $value;
                        break;
                    case 'state':
//                        $new = [];
//                        if(is_array($value)) {
//                            for ($i=$value[0];$i<=$value[1];$i++) {
//                                $new[] = $i;
//                            }
//                            $result['state'] = $new;
//                        } else {
                            $result['state'] = $value;
//                        }

                        break;
//                    case 'year':
//                        unset($condition['year']);
//                        break;
                    case 'farmerpinyin':
                        $where[$key] = $value;
                    case 'farmername':
                        $where[$key] = $value;
                    case 'pinyin':
                        $where[$key] = $value;
                    case 'farmname':
                        $where[$key] = $value;
                        $farmsid = [];
                        $farms = Farms::find()->andFilterWhere($where)->all();
                        foreach ($farms as $farm) {
                            $farmsid[] = $farm['id'];
                        }
                        $result['farms_id'] = $farmsid;
                        break;
//                    case 'id':
//                        $result['farms_id'] = $value;
//                        break;
//                    case 'isfinished':
//                        $result['isfinished'] = $value;
//                        break;
                }
            }
        }
//        var_dump($result);exit;
        return $result;
    }

    public static function toFireWhere($condition)
    {
//        var_dump($condition);
        $result['year'] = User::getYear();
        $array = self::whereToArray($condition);
//        var_dump($array);exit;
        if(is_array($array)) {
            foreach ($array as $key => $value) {

                switch ($key) {
                    case 'contractarea':
                        $result['contractarea'] = $value;
                        break;
                    case 'state':
                        $new = [];
                        if(is_array($value)) {
                            for ($i=$value[0];$i<=$value[1];$i++) {
                                $new[] = $i;
                            }
                            $result['state'] = $new;
                        } else {
                            $result['state'] = $value;
                        }

                        break;
//                    case 'year':
//                        unset($condition['year']);
//                        break;
                    case 'farmerpinyin':
                        $where[$key] = $value;
                    case 'farmername':
                        $where[$key] = $value;
                    case 'pinyin':
                        $where[$key] = $value;
                    case 'farmname':
                        $where[$key] = $value;
                        $farmsid = [];
                        $farms = Farms::find()->andFilterWhere($where)->all();
                        foreach ($farms as $farm) {
                            $farmsid[] = $farm['id'];
                        }
                        $result['farms_id'] = $farmsid;
                        break;
                    case 'id':
                        $result['farms_id'] = $value;
                        break;
                }
            }
        }
        return $result;
    }

    public static function getManagementArea($totalData,$str=null)
    {
        $where = $totalData->query->where;
//        var_dump($where);exit;
        foreach ($where as $key => $value) {
            if($key === 'management_area') {
                if($str == 'areaname') {
                    if(is_array($value)) {
                        foreach ($value as $id) {
                            $names[] = ManagementArea::getAreaname($id);
                        }
                        return $names;
                    } else {
                        $names[] = ManagementArea::getAreaname($value);
                        return $names;
                    }
                }
                if($str == 'small') {
                    foreach ($value as $id) {
                        $names [] = str_ireplace('管理区', '', ManagementArea::getAreaname($id));
                    }
                    return $names;
                }
                return $value;
            }
            if(is_array($value)) {
                foreach($value as $k => $v) {
                    if($k === 'management_area') {
                        if($str == 'areaname') {
                            foreach ($v as $id) {
                                $names[] = ManagementArea::getAreaname($id);
                            }
                            return $names;
                        }
                        if($str == 'small') {
                            foreach ($v as $id) {
                                $names [] = str_ireplace('管理区', '', ManagementArea::getAreaname($id));
                            }
                            return $names;
                        }
                        return $v;
                    }
                }
            }
        }
        return [1,2,3,4,5,6,7];
    }
}