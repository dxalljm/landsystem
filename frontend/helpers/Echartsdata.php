<?php
/**
 * Created by PhpStorm.
 * User: liujiaming
 * Date: 2019/1/13
 * Time: 10:02
 */

namespace frontend\helpers;
use app\models\Breedinfo;
use app\models\Disaster;
use app\models\Disastertype;
use app\models\Farms;
use app\models\Fireprevention;
use app\models\Fixed;
use app\models\Fixedtype;
use app\models\Goodseed;
use app\models\Insurance;
use app\models\Insurancecompany;
use app\models\Loan;
use app\models\Machineapply;
use app\models\Machineoffarm;
use app\models\Machinetype;
use app\models\Plant;
use app\models\Plantingstructure;
use app\models\Plantingstructurecheck;
use app\models\Sales;
use app\models\Saleswhere;
use app\models\Subsidytypetofarm;
use app\models\User;
use app\models\Breedtype;
use app\models\ManagementArea;
use app\models\Collection;
use app\models\PlantPrice;
use app\models\Huinong;
use app\models\Yieldbase;
use app\models\Theyear;
use app\models\Projectapplication;

class Echartsdata
{

    //农场首页图表数据
    public static function getFarmarea()
    {
        $areas = [];
        $percent = [];
        $rows = [];
        $rowpercent = [];
        $i=0;
        $query = Farms::getAllCondition();

        $all = sprintf ( "%.2f", $query->sum ( 'contractarea' ) );
        foreach (Farms::getManagementArea()['id'] as $value) {

            $area = Farms::getTotalArea($value);
            $areas[] = (float)sprintf("%.2f", $area);
            if($area>0) {
                $percent[] = sprintf("%.2f", $area / $all * 100);
            } else {

            }
            $i++;
        }
        $allcount = $query->count ();
        foreach (Farms::getManagementArea()['id'] as $value) {
            $row = Farms::getTotalNum($value);
            $rows[] = (int)$row;
            $rowpercent[] = sprintf("%.2f", $row/$allcount*100);
        }
        $result = [[
                'percent' => $percent,
                'data' => $areas,
            ],
            [
                'percent' => $rowpercent,
                'data' => $rows,
            ]
        ];

        return $result;
    }

    public static function getTypenameTotal($totalData,$field)
    {
        $result = [];
        foreach($totalData->getMoudels as $value) {
            $result[] = $value[$field];
        }
        $new = array_unique($result);
        $newresult = [];
        foreach($result as $value) {
            $newresult[] = $value;
        }
        return $newreult;
    }

    public static function totalNum() {
        $query = Farms::getCondition();
        return $query->count () . '户';
    }

    public static function totalArea() {
        if(User::getYear() == date('Y')) {
            $query = Farms::getCondition();
//        var_dump($query);
            return sprintf("%.2f", $query->sum('contractarea')) . '亩';
        } else {
            return '1030946.15亩';
        }
//        return '1030946.15亩';
    }

    //防火首页图表数据
    public static function getBfblist($user_id = null)
    {
        $all = [];
        $part = [];
        $finished = [];
//     	$color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
//     	$amountsColor = ['#fedfdf','#feeedf','#fefddf','#e1fedf','#dffcfe','#dfe3fe','#fedffe'];
        if(!empty($user_id)) {
            $managementarea = Farms::getUserManagementArea($user_id);
        } else {
            $managementarea = Farms::getManagementArea()['id'];
        }
        foreach ($managementarea['id'] as $value) {
            $all[] = Fireprevention::find()->where(['management_area' => $value, 'year' => User::getYear()])->count();
            $part[] = Fireprevention::find()->where(['management_area' => $value, 'year' => User::getYear(), 'finished' => 2])->count();
            $finished[] = Fireprevention::find()->where(['management_area' => $value, 'year' => User::getYear(), 'finished' => 1])->count();
        }
        return json_encode(['all' => $all, 'part' => $part, 'real' => $finished]);
    }
    public static function getAllbfb($user_id = null)
    {
// 		$color = ['#f30703','#f07304','#f1f100','#02f202','#01f0f0','#0201f2','#f101f1'];
// 		$amountsColor = ['#fedfdf','#feeedf','#fefddf','#e1fedf','#dffcfe','#dfe3fe','#fedffe'];
        if(!empty($user_id)) {
            $managementarea = Farms::getUserManagementArea($user_id)['id'];
        } else {
            $managementarea = Farms::getManagementArea()['id'];
        }
        $allfire = Fireprevention::find()->where(['management_area' => $managementarea, 'year' => User::getYear()])->count();
        $part = Fireprevention::find()->where(['management_area' => $managementarea, 'year' => User::getYear(), 'finished' => 2])->count();
        $finished = Fireprevention::find()->where(['management_area' => $managementarea, 'year' => User::getYear(), 'finished' => 1])->count();
        $count = $part + $finished;
        if ($allfire) {
            return (float)sprintf("%.2f", $count / $allfire)*100;
        } else {
            return 0;
        }
    }

    //畜牧业首页图表数据
    public static function getBreedinfoCache($user_id = null,$state)
    {
        $result = [];
        if(!empty($user_id)) {
            $managementarea = Farms::getUserManagementArea($user_id)['id'];
        } else {
            $managementarea = Farms::getManagementArea()['id'];
        }
        $typename = self::getBreedtypeList();
        foreach ($typename as $k => $val) {
            $number = Breedinfo::find()->where(['management_area'=>$managementarea,'breedtype_id'=>$k])->sum('number');
            $result['data'][] = $number;
            $result['dw'][] = Breedtype::find()->where(['id'=>$k])->one()['unit'];
            $result['typename'][] = $val;
        }
        $jsonData = json_encode ($result[$state]);
        return $jsonData;
    }

    public static function getBreedtypeList()
    {
        $result = [];
        $data = Breedinfo::find()->all();
        foreach ($data as $value) {
            $typename[] = $value['breedtype_id'];
        }
        $newname = array_unique($typename);
        foreach ($newname as $value) {
            $result[$value] = Breedtype::findOne($value)->typename;
        }
        return $result;
    }
    
    //承包费首页图表数据
    public static function getCollection($user_id)
    {
        if(!empty($user_id)) {
            $managementarea = Farms::getUserManagementArea($user_id)['id'];
        } else {
            $managementarea = Farms::getManagementArea()['id'];
        }
        $i = 0;
        $amounts_receivable = [];
        $real_income_amount = [];
        foreach ($managementarea as $value ) {
            $allmoney = Collection::find()->andFilterWhere(['management_area'=>$value,'payyear'=>User::getYear()])->groupBy('farms_id')->sum ( 'amounts_receivable' );
            $realmoney = (float)sprintf("%.2f",Collection::find ()->where ( [
                'management_area' => $value,
                'payyear' => User::getYear(),

            ] )->andWhere('state>0')->sum ( 'real_income_amount' ));
            $amounts_receivable [] = $allmoney;
            $real_income_amount [] = $realmoney;
            $i ++;
        }
        $result = [
            'all'=> $amounts_receivable,
            'real'=> $real_income_amount,
        ];
        $jsonData = json_encode ($result);
        return $jsonData;
    }

    public static function totalAmounts()
    {
        $query = Farms::getCondition('collection');
        $measureSum = $query->sum('contractarea');
        $result = (float)sprintf("%.2f",$measureSum * PlantPrice::find ()->where ( [
                'years' => User::getYear()
            ] )->one ()['price']);
        return $result;
    }

    public static function totalReal($begindate=null,$enddate=null,$user_id = null)
    {
        if(!empty($user_id)) {
            $managementarea = Farms::getUserManagementArea($user_id)['id'];
        } else {
            $managementarea = Farms::getManagementArea()['id'];
        }
        if(empty($begindate)) {
            $result = sprintf("%.2f", Collection::find()->where(['management_area' => $managementarea, 'payyear' => User::getYear()])->andWhere('state>0')->sum('real_income_amount'));
        } else {
            $result = sprintf("%.2f", Collection::find()->where(['management_area' => $managementarea, 'payyear' => User::getYear()])->andWhere('state>0')->andFilterWhere(['between','update_at',$begindate,$enddate])->sum('real_income_amount'));
        }
        return $result;
    }

    public static function totalBfb($begindate=null,$enddate=null,$user_id=null)
    {
        $bfb = 0;
        $real = self::totalReal($begindate,$enddate,$user_id);
        $all = self::totalAmounts();
        if($real >0) {
            $bfb = sprintf("%.2f",$real/$all*100);
        }
        return $bfb.'%';
    }
    //承包费板块图表
    public static function getCollectionEcharts4($totalData) {
        $i = 0;
        $amounts_receivable = [];
        $real_income_amount = [];
        $area = whereHandle::getManagementArea($totalData);
        $whereArray = whereHandle::whereToArray($totalData->query->where);
//		var_dump($whereArray);exit;
//		var_dump(Farms::getManagementArea ()['id']);
        foreach ( $area as $value ) {
//			$farmQuery = Farms::getAllCondition('collection');
            if($whereArray) {
                foreach($whereArray as $k=>$w) {
                    if(is_numeric($k)) {
                        $query = Collection::find()->andFilterWhere($w);
                    } else {
                        $query = Collection::find()->andFilterWhere([$k=>$w]);
                    }
                }
            }
            $allmoney =Collection::find()->andFilterWhere(['management_area'=>$value])->groupBy('farms_id')->sum ( 'amounts_receivable' );
            $realmoney = (float)sprintf("%.2f",$query->andFilterWhere( [
                'management_area' => $value,

            ] )->andWhere('state>0')->sum ( 'real_income_amount' ));
//			$amounts_receivable [] = (float)sprintf("%.2f",$allmeasure * PlantPrice::find ()->where ( [
//							'years' => User::getYear()
//					] )->one ()['price']);
//			$amounts_receivable [] = (float)bcsub($allmoney,$realmoney,2);
            $amounts_receivable [] = (float)$allmoney;
            $real_income_amount [] = $realmoney;
            $i ++;
        }
        $result = [
            'all'=> $amounts_receivable,
            'real'=> $real_income_amount,
        ];
        return $result;
    }

    //灾害图表
    public static function getDisasterEcharts4($totalData)
    {
        $result = [];
        $area = Farms::getManagementArea()['id'];
        $where = whereHandle::whereToArray($totalData->query->where,'plant_id');
        $plantid = self::getDisasterPlant($totalData);
        foreach ( $area as $value ) {
            foreach ($plantid as $plant_id) {
                $area = sprintf('%.2f',Disaster::find()->where($where)->andFilterWhere(['disasterplant'=>$plant_id])->sum('disasterarea'));
                $result[$value][] = $area;
            }
        }
        return $result;
    }

    public static function getDisasterPlant($totalData,$state='id')
    {
        $result= ['id'=>[],'typename' => []];
        foreach ($totalData->getModels() as $value) {
            $plantid[] = $value->attributes('disasterplant');
        }
        $id = [];
        $typename = [];
        if(!empty($plantid)) {
            $newid = array_unique($plantid);
            foreach ($newid as $value) {
                $id[] = $value;
                $typename[] = Plant::findOne($value)->typename;
            }
            $result = ['id'=>$id,'typename'=>$typename];
        }
        return $result[$state];
    }

    //种植结构首页图表数据
    public static function getPlantingstructure($user_id)
    {
        $area = Farms::getUserManagementArea($user_id)['id'];
        $plantid = self::getPlan_checkname($user_id)['id'];
//			// 农场区域
        $plantArea = [];
        foreach ($plantid as $val) {

            $planSum = Plantingstructure::find()->where(['management_area'=>$area,'plant_id'=>$val,'year'=>User::getYear()])->sum('area');
            $factSum = Plantingstructurecheck::find()->where(['management_area'=>$area,'plant_id'=>$val,'year'=>User::getYear()])->sum('area');
            if(empty($planSum)) {
                $planSum = 0;
            }
            if(empty($factSum)) {
                $factSum = 0;
            }
            if($planSum > 0 or $factSum > 0) {
                $plantArea['plan'][] = (float)sprintf("%.2f", $planSum);
                $plantArea['fact'][] = (float)sprintf("%.2f", $factSum);
            }
        }
        return $plantArea;
    }

    public static function getPlantingstructureEcharts4($totalData)
    {
        $plantid = self::getPlantname($totalData);
//			// 农场区域
        $plantArea = ['plan'=>[],'fact'=>[]];
        foreach ($plantid as $key => $val) {
            $planSum = Plantingstructure::find()->andFilterWhere($totalData->query->where)->andFilterWhere(['plant_id'=>$key])->sum('area');
            $factSum = Plantingstructurecheck::find()->andFilterWhere($totalData->query->where)->andFilterWhere(['plant_id'=>$key])->sum('area');
            if(empty($planSum)) {
                $planSum = 0;
            }
            if(empty($factSum)) {
                $factSum = 0;
            }
            if($planSum > 0 or $factSum > 0) {
                $plantArea['plan'][] = (float)sprintf("%.2f", $planSum);
                $plantArea['fact'][] = (float)sprintf("%.2f", $factSum);
            }
        }
        return $plantArea;
    }

    public static function getPlantname($totalData = NULL,$state = null)
    {
        $result = [];
        $data = [];
        if(empty($totalData)) {
            $where = Farms::getManagementArea()['id'];
            $planting = Plantingstructurecheck::find()->where(['management_area'=>$where,'year'=>User::getLastYear()])->all();
            foreach ($planting as $value) {
                $data[] = $value['plant_id'];
            }
        } else {
            foreach($totalData->getModels() as $value) {
//                     		var_dump($value->attributes);exit;
                $data[] = $value->attributes['plant_id'];
            }
        }
        if($data) {
            $newdata = array_unique($data);
            if($state == 'id') {
                return $newdata;
            }
            foreach ($newdata as $value) {
                $result[$value] =  Plant::find()->where(['id' => $value])->one()['typename'];
            }
        }
        $r= [];
        if(!empty($state)) {
            foreach ($result as $value) {
                $r[] = $value;
            }
            return $r;
        }
        return $result;
    }

    public static function getPlantingstructureInfoEcharts4($totalData)
    {
        $plantid = self::getPlantname($totalData);
//			// 农场区域
        $plantArea = ['plan'=>[],'fact'=>[]];
        foreach ($plantid as $key => $val) {
            $planSum = Plantingstructure::find()->andFilterWhere($totalData->query->where)->andFilterWhere(['plant_id'=>$key])->sum('area');
            $factSum = Plantingstructurecheck::find()->andFilterWhere($totalData->query->where)->andFilterWhere(['plant_id'=>$key])->sum('area');
            if(empty($planSum)) {
                $planSum = 0;
            }
            if(empty($factSum)) {
                $factSum = 0;
            }
            if($planSum > 0 or $factSum > 0) {
                $plantArea['plan'][] = ['value'=>(float)sprintf("%.2f", $planSum),'name'=>$val];
                $plantArea['fact'][] = ['value'=>(float)sprintf("%.2f", $factSum),'name'=>$val];
            }
        }
        return $plantArea;
    }

    public static function getPlantGoodseedSum($user_id) {
        $planSum = 0.0;
        $planGoodseedSum = 0.0;
        $factSum = 0.0;
        $factGoodseedSum = 0.0;
        if(!empty($user_id)) {
            $managementarea = Farms::getUserManagementArea($user_id)['id'];
        } else {
            $managementarea = Farms::getManagementArea()['id'];
        }
        $plan = Plantingstructure::find()->where(['management_area'=>$managementarea,'year'=>User::getYear()])->all();
        foreach ($plan as $v) {
            $planSum += $v['area'];
            if($v['goodseed_id']) {
                $goodseedarea = $v['area'];
            } else {
                $goodseedarea = 0.0;
            }

            $planGoodseedSum += $goodseedarea;
        }
        $fact = Plantingstructurecheck::find()->where(['management_area'=>$managementarea,'year'=>User::getYear()])->all();
        foreach ($fact as $v) {
            $factSum += $v['area'];
            if($v['goodseed_id']) {
                $goodseedarea = $v['area'];
            } else {
                $goodseedarea = 0.0;
            }

            $factGoodseedSum += $goodseedarea;
        }
        $result = ['plan'=>['plantSum'=>(float)sprintf("%.2f", $planSum),'goodseedSum'=>(float)sprintf("%.2f", $planGoodseedSum)],'fact'=>['plantSum'=>(float)sprintf('%.2f',$factSum),'goodseedSum'=>(float)sprintf("%.2f", $factGoodseedSum)]];
        return $result;
    }
    public static function getPlan_checkname($user_id=null,$totalData = null)
    {
        $result = [];
        $data = [];
        $name = [];
        if(empty($totalData)) {
            if(!empty($user_id)) {
                $managementarea = Farms::getUserManagementArea($user_id)['id'];
            } else {
                $managementarea = Farms::getManagementArea();
            }
            $plan = Plantingstructure::find()->where(['management_area'=>$managementarea,'year'=>User::getYear()])->all();
            $plan_plantid = [];
            foreach ($plan as $value) {
                $plan_plantid[] = $value['plant_id'];
            }
            $newplan_plantid = array_unique($plan_plantid);
            $check = Plantingstructurecheck::find()->where(['management_area'=>$managementarea,'year'=>User::getYear()])->all();
            $check_plantid = [];
            foreach ($check as $value) {
                $check_plantid[] = $value['plant_id'];
            }
            $newcheck_plantid = array_unique($check_plantid);
            $merge_data = array_merge($newplan_plantid,$newcheck_plantid);
            $data = array_unique($merge_data);
            foreach ($data as $value) {
                $name[] = Plant::findOne($value)->typename;
            }
            return ['id'=>$data,'typename'=>$name];
        } else {
            foreach($totalData->getModels() as $value) {
                $plantid[] = $value->attributes['plant_id'];
                $plantname[] = Plant::findOne($value)->typename;
            }
            $data = array_unique($plantid);
            foreach ($data as $value) {
                $name[] = Plant::findOne($value)->typename;
            }
            return ['id'=>$data,'typename'=>$name];
        }
    }

    public static function getHuinonginfo($totalData)
    {
        $sum = [];
        $whereArray = whereHandle::whereToArray($totalData->query->where,'plant_id');
        $year = whereHandle::getField($whereArray,'year');
        $management_area = Farms::getManagementArea()['id'];
        $huinong = self::getHuinongtypename($totalData);
        foreach ($huinong as $key =>$h) {
            foreach ($management_area as $mid) {
                foreach ($whereArray as $k =>$w) {
                    if(is_numeric($k)) {
                        $query = Plantingstructurecheck::find()->andFilterWhere($w);
                    } else {
                        $query = Plantingstructurecheck::find()->andFilterWhere([$k=>$w]);
                    }
                }
                $areaSum = $query->andFilterWhere(['plant_id' => $h['plant_id'],'management_area'=>$mid])->sum('area');
                $sum[$key][] = sprintf('%.2f', $areaSum * $h['subsidiesmoney']);
            }
        }
        return $sum;
    }

    //防火数据图表
    public static function getFireinfo($totalData)
    {
        $all = [];
        $part = [];
        $finished = [];
        $managementarea = whereHandle::getManagementArea($totalData);
        
        $where = whereHandle::toFireWhere($totalData);
        if($managementarea) {
            foreach ($managementarea as $value) {
                $all[] = Fireprevention::find()->where($where)->andFilterWhere(['management_area' => $value])->count();
                $part[] = Fireprevention::find()->where($where)->andFilterWhere(['management_area' => $value, 'finished' => 2])->count();
                $finished[] = Fireprevention::find()->where($where)->andFilterWhere(['management_area' => $value, 'finished' => 1])->count();
            }
            return ['all' => $all, 'part' => $part, 'real' => $finished];
        }
    }

    public static function getHuinongtypename($totalData,$state='id')
    {
        $result = [];
        $id = [];
        $typename = [];
        $whereArray = whereHandle::whereToArray($totalData->query->where,'plant_id');
        $year = whereHandle::getField($whereArray,'year');
        $huinong = Huinong::find()->where(['year'=>$year])->all();
        foreach ($huinong as $h) {
            $id[] = ['plant_id'=>$h['typeid'],'subsidiesmoney'=>$h['subsidiesmoney']];
            $typename[] = Subsidytypetofarm::findOne($h['subsidiestype_id'])['typename'];
        }
        $result = ['id'=>$id,'typename'=>$typename];
        return $result[$state];
    }

    //贷款图表数据
    public static function getDataBankList($total)
    {
        $bankname = [];
        $where = $total->query->where;
        $loans = Loan::find()->where($where)->all();
        foreach ($loans as $loan) {
            $bankname[] = $loan->mortgagebank;
        }

        $result = array_unique($bankname);
        sort($result);
        return $result;
    }

    public static function getLoan($total)
    {
        $result = [];
        $money = 0.0;
        $bank = self::getDataBankList($total);
        $areaMoney = [];
        foreach ($bank as $key =>$val) {
            foreach (self::getMonths($total) as $value) {
                $money = Loan::find()->where($total->query->where)->andFilterWhere(['mortgagebank' => $val])->andFilterWhere(['between','update_at',strtotime($value[0]),strtotime($value[1])])->sum('mortgagemoney');
                $result[$key][] = sprintf("%.2f", $money);
            }
        }
        return $result;
    }

    public static function getLoansearch($total)
    {
        $result = [];
        $money = 0.0;
        $bank = self::getDataBankList($total);
        $areaMoney = [];
        foreach ($bank as $key =>$val) {
//            var_dump(self::getMonthsSearch($total,'time'));exit;
            foreach (self::getMonthsSearch($total,'time') as $value) {
                $money = Loan::find()->where($total->query->where)->andFilterWhere(['mortgagebank' => $val])->andFilterWhere(['between','update_at',strtotime($value[0]),strtotime($value[1])])->sum('mortgagemoney');
                $result[$key][] = sprintf("%.2f", $money);
            }
        }
        return $result;
    }

    public static function getYearLoan($total)
    {
        $result = [];
        $money = 0.0;
        $bank = self::getDataBankList($total);
        $areaMoney = [];
        foreach ($bank as $key =>$val) {
            $money = Loan::find()->where($total->query->where)->andFilterWhere(['mortgagebank' => $val])->sum('mortgagemoney');
            $result[] = sprintf("%.2f", $money);
        }
        return $result;
    }

    public static function getLoanPjz($total)
    {
        $result = [];
        $money = 0.0;
        $bank = self::getDataBankList($total);
        $areaMoney = [];
        if(count($bank) > 0) {
            foreach (self::getMonths($total) as $value) {
                $money = Loan::find()->where($total->query->where)->andFilterWhere(['between', 'update_at', strtotime($value[0]), strtotime($value[1])])->sum('mortgagemoney');
                $result[] = sprintf("%.2f", $money / count($bank));
            }
        } else {
            $result = 0;
        }
        return $result;
    }
    //农机图表数据
    public static function getMachinetypename($totalData,$state='id')
    {
        $id = [];
        $typename = [];
        $allid = [];
        $result = [];
        foreach ($totalData->getModels() as $value) {
            $allid[] = $value['machinetype_id'];
        }
        $newid = array_unique($allid);
        if(!empty($newid)) {
            foreach ($newid as $val) {
                if(!empty($val)) {
                    $id[] = $val;
                    $typename[] = Machinetype::findOne($val)->typename;
                }
            }
        }
        $result = ['id'=>$id,'typename'=>$typename];
        return $result[$state];
    }

    public static function getMachineinfo($totalData)
    {
        $result = [];
        $whereArray = whereHandle::whereToArray($totalData->query->where,'machinetype_id');
//        $year = whereHandle::getField($whereArray,'year');
        $typename = self::getMachinetypename($totalData,'id');
//        var_dump($typename);exit;
        foreach ($typename as $value) {
            foreach ($whereArray as $k =>$w) {
                if(is_numeric($k)) {
                    $query = Machineapply::find()->andFilterWhere($w);
                } else {
                    $query = Machineapply::find()->andFilterWhere([$k=>$w]);
                }
            }
            $result[] = $query->andFilterWhere(['machinetype_id'=>$value])->sum('subsidymoney');
        }
//        var_dump($result);exit;
        return $result;
    }

    public static function getMachinenumber($totalData)
    {
        $result = [];
        $whereArray = whereHandle::whereToArray($totalData->query->where,'machinetype_id');
        $year = whereHandle::getField($whereArray,'year');
        $typename = self::getMachinetypename($totalData,'id');
        foreach ($typename as $value) {
            if(!empty($whereArray)) {
                foreach ($whereArray as $k => $w) {
                    if (is_numeric($k)) {
                        $query = Machineapply::find()->andFilterWhere($w);
                    } else {
                        $query = Machineapply::find()->andFilterWhere([$k => $w]);
                    }
                }
            } else {
                $query = Machineapply::find();
            }

            $result[] = $query->andFilterWhere(['machinetype_id'=>$value])->count();
        }
        return $result;
    }

    //保险图表数据
    public static function getPlantCache($totalData)
    {
        $result = [];
        $plant = ['insuredsoybean'=>'大豆','insuredwheat'=>'小麦','insuredother'=>'其它'];
        foreach ($plant as $key => $value) {
            $sum = (float)Insurance::find()->where($totalData->query->where)->sum($key);
            if(empty($sum)) {
                $sum = 0;
            }
            $result[] = ['value'=>$sum,'name'=>$value];
        }
        return $result;
    }

    //畜牧业图表数据
    public static function getBreedinfoTypename($totalData,$state='id')
    {
        $id = [];
        $typename = [];
        $unit = [];
        $result = [];
        foreach ($totalData->getModels() as $value) {
            $allid[] = $value['breedtype_id'];
        }
        $newid = array_unique($allid);
        if(!empty($newid)) {
            foreach ($newid as $val) {
                $id[] = $val;
                $breed =  Breedtype::findOne($val);
                $typename[] = $breed->typename;
                $unit[] = $breed->unit;
            }
        }
        $result = ['id'=>$id,'typename'=>$typename,'unit'=>$unit];
        return $result[$state];
    }

    public static function getBreedinfoinfo($totalData)
    {
        $result = [];
        $whereArray = whereHandle::whereToArray($totalData->query->where,'breedtype_id');
        $year = whereHandle::getField($whereArray,'year');
        $typename = self::getBreedinfoTypename($totalData,'id');
        foreach ($typename as $value) {
            foreach ($whereArray as $k =>$w) {
                if(is_numeric($k)) {
                    $query = Breedinfo::find()->andFilterWhere($w);
                } else {
                    $query = Breedinfo::find()->andFilterWhere([$k=>$w]);
                }
            }
            $result[] = $query->andFilterWhere(['breedtype_id'=>$value])->sum('number');
        }
        return $result;
    }

    //惠农首页图表数据
    public static function getHuinongCache($user_id)
    {
        if(!empty($user_id)) {
            $managementarea = Farms::getUserManagementArea($user_id)['id'];
        } else {
            $managementarea = Farms::getManagementArea()['id'];
        }
        $huinong = Huinong::find()->where(['year'=>User::getYear()])->all();
        $sum = [];
        foreach ($huinong as $h) {
            $areaSum = Plantingstructurecheck::find()->where(['year'=>User::getYear(),'plant_id'=>$h['typeid'],'management_area'=>$managementarea])->sum('area');
            $sum[] = sprintf('%.2f',$areaSum*$h['subsidiesmoney']);
        }

        $sum[] = Machineapply::find()->where(['year'=>User::getYear(),'state'=>1])->sum('subsidymoney');
        return json_encode($sum);
    }

    public static function getAllmoney($user_id)
    {
        if(!empty($user_id)) {
            $managementarea = Farms::getUserManagementArea($user_id)['id'];
        } else {
            $managementarea = Farms::getManagementArea()['id'];
        }
        $huinong = Huinong::find()->where(['year'=>User::getYear()])->all();
        $sum = 0;
        foreach ($huinong as $h) {
            $areaSum = Plantingstructurecheck::find()->where(['year'=>User::getYear(),'plant_id'=>$h['typeid'],'management_area'=>$managementarea])->sum('area');
            $sum += sprintf('%.2f',$areaSum*$h['subsidiesmoney']);
        }

        $sum += Machineapply::find()->where(['year'=>User::getYear(),'state'=>1])->sum('subsidymoney');

        return $sum;
    }
    public static function getTypename()
    {
        $typename = [];
        $huinong = Huinong::find()->where(['year'=>User::getYear()])->all();
        foreach ($huinong as $value) {
            $typename[] = Subsidytypetofarm::findOne($value['subsidiestype_id'])['typename'];
        }
        $typename[] = '农机补贴';
        return json_encode($typename);
    }

    //保险首页图表数据
    public static function getInsurancecache()
    {
        $companys = self::getCompanyUse();
        if(empty($companys)) {
            return json_encode([['area'=>[],'percent'=>[]],['num'=>[],'percentnum'=>[]]]);
        }
        foreach ($companys as $id => $company) {
            $insurance = Insurance::find()->where(['company_id'=>$id,'state'=>1,'year'=>2018]);
            $area = sprintf('%.2f',$insurance->sum('insuredarea'));
            $resultarea[] = $area;
            $allarea = sprintf('%.2f',Insurance::find()->where(['state'=>1,'year'=>2018])->sum('insuredarea'));
            $percent[] = sprintf("%.2f", $area / $allarea * 100);
            $num = $insurance->count();
            $resultnum[] = $num;
            $allnum = Insurance::find()->where(['state'=>1,'year'=>2018])->count();
            $numparcent[] = sprintf("%.2f", $num / $allnum * 100);
        }
        $result = [['area'=>$resultarea,'percent'=>$percent],['num'=>$resultnum,'percentnum'=>$numparcent]];
        $jsonData = json_encode($result);
        return $jsonData;
    }

    public static function getCompanyCache($totalData)
    {
        $result = [];
        $companys = self::getCompanyUse($totalData);
        foreach ($companys as $key => $value) {
            $sum = (float)Insurance::find()->where($totalData->query->where)->andFilterWhere(['company_id'=>$key])->count();
            if(empty($sum)) {
                $sum = 0;
            }
            $result[] = ['value'=>$sum,'name'=>$value];
        }
        return $result;
    }

    public static function getCompanyUse($totalData=null,$state=false)
    {
        if(!empty($totalData)) {
            $where = $totalData->query->where;
        } else {
            $where = ['year'=>User::getYear(),'state'=>1];
        }

        $data = Insurance::find()->where($where)->all();
        $result = [];
        foreach ($data as $value) {
            $id[] = $value['company_id'];
        }
        if(isset($id)) {
            $unid = array_unique($id);
            foreach ($unid as $value) {
                $company = Insurancecompany::findOne($value);
                $result[$value] = $company['companynname'];
            }

            if ($state) {
                foreach ($result as $value) {
                    $result2[] = $value;
                }
                return $result2;
            }
            return $result;
        } else {
            return [];
        }
    }

    public static function getNumberCache($totalData)
    {
        $yes = Insurance::find()->where($totalData->query->where)->count();
        $whereArray = whereHandle::whereToArray($totalData->query->where);
        $management_area = whereHandle::getField($whereArray,'management_area');
        if(empty($management_area)) {
            $management_area = Farms::getManagementArea()['id'];
        }
        $farmCount = Farms::find()->where(['management_area'=>$management_area,'state'=>[1,2,3,4,5]])->count();
        $no = $farmCount - $yes;
        return [['value'=>$yes,'name'=>'已参加保险'],['value'=>$no,'name'=>'未参加保险']];
    }

    public static function getAreaCache($totalData)
    {
        $yes = sprintf('%.2f',Insurance::find()->where($totalData->query->where)->sum('insuredarea'));
        $whereArray = whereHandle::whereToArray($totalData->query->where);
        $management_area = whereHandle::getField($whereArray,'management_area');
        if(empty($management_area)) {
            $management_area = Farms::getManagementArea()['id'];
        }
        $farmCount = Farms::find()->where(['management_area'=>$management_area,'state'=>[1,2,3,4,5]])->sum('contractarea');
        $no = bcsub($farmCount, $yes,2);
        return [['value'=>$yes,'name'=>'已参加保险'],['value'=>$no,'name'=>'未参加保险']];
    }

    //贷款首页图表数据
    public static function getLoancache($user_id)
    {
        $result = [];
        $money = 0.0;
        $area = Farms::getUserManagementArea($user_id)['id'];
        $bank = self::getBankList();
        foreach ($bank as $val) {
            $money = Loan::find()->where(['management_area'=>$area,'mortgagebank'=>$val,'state'=>1,'lock'=>1])->sum('mortgagemoney');
            $result[] = sprintf("%.2f", $money);
        }
        $jsonData = json_encode ($result);

        return $jsonData;
    }
    public static function getBankList($type=null)
    {
        $typename = [];
        $bankname = [];
        $loans = Loan::find()->where(['management_area'=>Farms::getManagementArea()['id'],'state'=>1,'lock'=>1])->all();
        foreach ($loans as $loan) {
            $bankname[] = $loan->mortgagebank;
        }
        $result = array_unique($bankname);
        if($type == 'small') {
            foreach ($result as $val) {
                $bank = Loan::getBankName($type);
                $typename[] = $bank[$val];
            }
        } else {
            $typename = $result;
        }
        return $typename;
    }
    public static function getLoanMoney($user_id)
    {
        $result = Loan::find()->where(['management_area'=>Farms::getUserManagementArea($user_id)['id'],'lock'=>1,'state'=>1])->sum('mortgagemoney');
        return $result;
    }
    public static function getLoanNowMoney($user_id)
    {
        $result = Loan::find()->where(['management_area'=>Farms::getUserManagementArea($user_id)['id'],'state'=>1,'lock'=>1,'year'=>User::getYear()])->sum('mortgagemoney');
        if(empty($result)) {
            return 0;
        }
        return $result;
    }
    public static function getLoanLastMoney($user_id)
    {
        $result = Loan::find()->where(['management_area'=>Farms::getUserManagementArea($user_id)['id'],'state'=>1,'lock'=>1,'year'=>User::getLastYear()])->sum('mortgagemoney');
        if(empty($result)) {
            return 0;
        }
        return $result;
    }

    //农产品首页图表数据
    public static function getYieldsCache($totalData=null)
    {
        $result = [];
        $areaNum = 0;
        $management_area = Farms::getManagementArea()['id'];
        if(empty($totalData)) {
            $plantid = self::getYieldsTypename()['id'];
            $plantArea = [];
            foreach ($plantid as $val) {
                $area = sprintf('%.2f', Plantingstructurecheck::find()->where(['management_area' => $management_area, 'plant_id' => $val, 'year' => User::getLastYear()])->sum('area'));
//            var_dump($area);
                $yieldBase = Yieldbase::find()->where(['year' => User::getLastYear(), 'plant_id' => $val])->one()['yield'];
//            var_dump($yieldBase);
                $result[] = (float)bcmul($area, $yieldBase, 2);
            }
//        var_dump($result);
        } else {
            $plantid = self::getYieldsTypename($totalData);
            $where = whereHandle::whereToArray($totalData->query->where,'plant_id');
            $year = whereHandle::getField($where,'year');
//            var_dump($year);
//            $update = whereHandle::getField($where,'update_at');
            if(is_array($year)) {
                foreach ($plantid['id'] as $value) {
                    foreach ($year as $y) {
                        foreach ($where as $k => $w) {
                            if(is_numeric($k)) {
                                $query = Plantingstructurecheck::find()->andFilterWhere($w);
                            } else {
                                $query = Plantingstructurecheck::find()->andFilterWhere([$k=>$w]);
                            }
                        }
                        $area = sprintf('%.2f', $query->andFilterWhere(['plant_id' => $value, 'year' => $y])->sum('area'));
                        $yieldBase = Yieldbase::find()->where(['year' => $y, 'plant_id' => $value])->one()['yield'];
                        $result[] = (float)bcmul($area, $yieldBase, 2);
                    }
                }
            } else {
//                var_dump($where);
                foreach ($plantid['id'] as $value) {
                    foreach ($where as $k => $w) {
                        if(is_numeric($k)) {
                            $query = Plantingstructurecheck::find()->andFilterWhere($w);
                        } else {
                            $query = Plantingstructurecheck::find()->andFilterWhere([$k=>$w]);
                        }
                    }
//                    var_dump($query->where);
                    $area = sprintf('%.2f', $query->andFilterWhere(['plant_id' => $value])->sum('area'));
//                    var_dump($area);
                    $yieldBase = Yieldbase::find()->where(['year' => $year, 'plant_id' => $value])->one()['yield'];
                    $result[] = (float)bcmul($area, $yieldBase, 2);
                }
            }
        }
//        $jsonData = json_encode ($result);
        return $result;
    }

    public static function getYieldsTypename($totalData=null)
    {
        $data = [];
        $result = ['id'=>[],'typename'=>[]];
        if(empty($totalData)) {
            $yields = Plantingstructurecheck::find()->where(['management_area' => Farms::getManagementArea()['id'], 'year' => User::getLastYear()]);
            foreach ($yields->all() as $value) {
                $data[] = $value['plant_id'];
            }
            if ($data) {
                $newdata = array_unique($data);
                foreach ($newdata as $value) {
                    $result['id'][] = $value;
                    $result['typename'][] = Plant::find()->where(['id' => $value])->one()['typename'];
                }
            }
        } else {
            foreach($totalData->getModels() as $value) {
                $plantid[] = $value->attributes['plant_id'];
//                $plantname[] = Plant::findOne($value->attributes['plant_id'])->typename;
            }
            if(!empty($plantid)) {
                $data = array_unique($plantid);
                foreach ($data as $value) {
                    $id[] = $value;
                    $name[] = Plant::findOne($value)->typename;
                }
                $result['id'] = $id;
                $result['typename'] = $name;
            }
        }
//		var_dump($result);
        return  $result;
    }

    public static function getGoodseedTypename($totalDataPlan,$totolDataFact=null,$state='id')
    {
        $result = ['id'=>[],'typename'=>[]];
        $id = [];
        $plantid = [];
        $plantname = [];
        foreach($totalDataPlan->getModels() as $value) {
            if(!empty($value->attributes['goodseed_id'])) {
                $plantid[] = $value->attributes['goodseed_id'];
//                $plantname[] = Goodseed::findOne($value->attributes['goodseed_id'])->typename;
            }
        }
        if(!empty($totolDataFact)) {
            foreach ($totolDataFact->getModels() as $value) {
                if (!empty($value->attributes['goodseed_id'])) {
                    $plantid[] = $value->attributes['goodseed_id'];
//                $plantname[] = Goodseed::findOne($value->attributes['goodseed_id'])->typename;
                }
            }
        }
        if(!empty($plantid)) {
            $data = array_unique($plantid);
            foreach ($data as $value) {
                $id[] = $value;
                $name[] = Goodseed::findOne($value)->typename;
            }
            $result = ['id' => $id, 'typename' => $name];
        }
        return $result[$state];
    }

    public static function getGoodseedinfo($totalDataPlan,$totalDataFact=null)
    {
        $result = [];
        $goodid = self::getGoodseedTypename($totalDataPlan,$totalDataFact);
        $goodname = self::getGoodseedTypename($totalDataPlan,$totalDataFact,'typename');
        if(empty($goodid)) {
            return [];
        }
        if(!empty($totalDataFact)) {
            $where = whereHandle::whereToArray($totalDataFact->query->where, 'goodseed_id');
            foreach ($goodid as $key => $value) {
                foreach ($where as $w) {
                    $queryPlan = Plantingstructure::find()->andFilterWhere($w);
                    $queryFact = Plantingstructurecheck::find()->andFilterWhere($w);
                }
                $result['plan'][] = ['value'=>sprintf('%.2f',$queryPlan->andFilterWhere(['goodseed_id'=>$value])->sum('area')),'name'=>$goodname[$key]];
                $result['fact'][] = ['value'=>sprintf('%.2f',$queryFact->andFilterWhere(['goodseed_id'=>$value])->sum('area')),'name'=>$goodname[$key]];
            }
        } else {
            $where = whereHandle::whereToArray($totalDataPlan->query->where, 'goodseed_id');
            foreach ($goodid as $key => $value) {
                foreach ($where as $k => $w) {
                    if(is_numeric($k)) {
                        $query = Plantingstructurecheck::find()->andFilterWhere($w);
                    } else {
                        $query = Plantingstructurecheck::find()->andFilterWhere([$k=>$w]);
                    }
                }
                $result[] = ['value' => sprintf('%.2f', $query->andFilterWhere(['goodseed_id' => $value])->sum('area')), 'name' => $goodname[$key]];
            }
        }

        return $result;
    }


    //销售去向信息表数据
    public static function getSalesinfo($totalData)
    {
        $result = [];
        $whereid = self::getSalestypename($totalData);
        $wherename = self::getSalestypename($totalData,'typename');
        if(empty($whereid)) {
            return [];
        }
        $where = whereHandle::whereToArray($totalData->query->where,'whereabouts');
        foreach ($whereid as $key => $value) {
            if(empty($where)) {
                $query = Sales::find();
            } else {
                foreach ($where as $k => $w) {
                    if (is_numeric($k)) {
                        $query = Sales::find()->andFilterWhere($w);
                    } else {
                        $query = Sales::find()->andFilterWhere([$k => $w]);
                    }
                }
            }
            $result[] = ['value'=>(float)sprintf('%.2f',$query->andFilterWhere(['whereabouts'=>$value])->sum('volume')),'name'=>$wherename[$key]];
        }
//        var_dump($result);
        return $result;
    }

    public static function getSalestypename($totalData,$state = 'id')
    {
        $result = ['id'=>[],'typename'=>[]];
        $id = [];
        foreach ($totalData->getModels() as $value) {
            $id[] = $value->attributes['whereabouts'];
        }
        if(isset($id)) {
            $newid = array_unique($id);
//            var_dump($newid);exit;
            foreach ($newid as $value) {
                $result['id'][] = $value;
                $sale = Saleswhere::findOne($value);
//                var_dump($sale);
                $result['typename'][] = $sale['wherename'];
            }
//            exit;
            return $result[$state];
        } else {
            return [];
        }
    }

    //项目首页图表数据
    public static function getProjectapplicationcache()
    {
        $where = Farms::getManagementArea()['id'];
        $type = self::getProjectTypename()['id'];
//     	var_dump($type);
        //     	var_dump($input);exit;
        $data = [];
        $result = [];
        $dw = [];
        foreach ($where as $areaid) {
            foreach ($type as $value) {
                $sum = Projectapplication::find()->where(['management_area'=>$areaid,'projecttype'=>$value,'state'=>1])->sum('projectdata');
                if($sum)
                    $data[$areaid][] = (float)$sum;
                else
                    $data[$areaid][] = 0.0;
            }
        }
        if($data) {
            foreach ($data as $key => $value) {
                $result[] = [
                    'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id' => $key])->one()['areaname']),
                    'type' => 'bar',
                    'stack' => $key,
                    'data' => $value,
                ];
            }
        } else {
            foreach ($where as $areaid) {
                $result[] = [
                    'name' => str_ireplace('管理区', '', ManagementArea::find()->where(['id' => $areaid])->one()['areaname']),
                    'type' => 'bar',
                    'stack' => $areaid,
                    'data' => [],
                ];
            }
        }
//     	var_dump($result);
        $jsonData = json_encode ($result);

        return $jsonData;
    }

    public static function getProjectTypename()
    {
        $where = Farms::getManagementArea()['id'];

        $input = Projectapplication::find()->where(['management_area'=>$where,'year'=>User::getYear()])->all();
        //     	var_dump($input);exit;
        $data = [];
        $result = ['id'=>[],'projecttype'=>[],'unit'=>[]];
        foreach ($input as $value) {
            $data[] = ['id'=>Infrastructuretype::find()->where(['id'=>$value['projecttype']])->one()['id']];
        }
        if($data) {
            $newdata = Farms::unique_arr($data);
            foreach ($newdata as $value) {
                $result['id'][] = $value['id'];
                $result['projecttype'][] = Infrastructuretype::find()->where(['id' => $value['id']])->one()['typename'];
                $result['unit'][] = Projectapplication::find()->where(['projecttype'=>$value['id']])->one()['unit'];
            }
        }
//     	    	var_dump($result);
        return $result;
    }

    public static function getFixedinfo($totalData)
    {
        $result = [];
        $where = $totalData->query->where;
        $whereArray = whereHandle::whereToArray($where,'typeid');
        $typeids = self::getFixedtypename($totalData);
        foreach ($typeids as $typeid) {
            $result[] = Fixed::find()->where($whereArray)->andFilterWhere(['typeid'=>$typeid])->sum('number');
        }
        return $result;
    }

    public static function getFixedtypename($totalData,$state='id')
    {
        $id = [];
        $name = [];
        $result = ['id'=>[],'name'=>[]];
        $typeid = [];
//        var_dump($totalData);
        foreach ($totalData->getModels() as $value) {
            $typeid[] = $value['typeid'];
        }
        if(!empty($typeid)) {
            $newid = array_unique($typeid);
            foreach ($newid as $value) {
                $id[] = $value;
                $name[] = Fixedtype::findOne($value)->typename;
                $unit[] = Fixedtype::findOne($value)->unit;
            }
            $result = ['id'=>$id,'typename'=>$name,'unit'=>$unit];
        }
        return $result[$state];
    }

    public static function getMonths($totalData,$str='')
    {
        $result = [];
        $years = [];
        foreach ($totalData->getModels() as $value) {
            $years[] = $value['year'];
        }
        $data = array_unique($years);
        sort($data);

        foreach ($data as $year) {
            if(empty($str)) {
                foreach (Theyear::getMonths($year) as $val) {
                    $result[] = $val;
                }
            } else {
                foreach (Theyear::getMonths($year) as $val) {
                    $time = strtotime($val[0]);
                    $result[] = date($str,$time);
                }
            }
        }
        return $result;
    }

    public static function getMonthsSearch($totalData,$str='date')
    {
        $result = [];
        $times = [];
        foreach ($totalData->getModels() as $value) {
            $times[] = $value['create_at'];
        }
        if(!empty($times)) {
            $min = min($times);
            $max = max($times);
            $minyear = date('Y',$min);
            $minmonth = date('m',$min);
            $maxyear = date('Y',$max);
            $maxmonth = date('m',$max);
//        var_dump($minyear);var_dump($maxyear);
            if($minyear == $maxyear) {
                for($i=$minmonth;$i<=$maxmonth;$i++) {
                    if($str == 'time') {
                        $result[] = self::getMonth($minyear,$i);
                    } else {
                        $result[] = $minyear . '年' . $i . '月';
                    }
                }
            } else {
                for($i=$minyear;$i<=$maxyear;$i++) {
                    if($i == $minyear) {
                        for($j=$minmonth;$j<=12;$j++) {
                            if($str == 'time') {
                                $result[] = self::getMonth($i,$j);
                            } else {
                                $result[] = $i . '年' . $j . '月';
                            }
                        }
                    }
                    if($i > $minyear and $i > $maxyear) {
                        for($j=1;$j<=12;$j++) {
                            if($str == 'time') {
                                $result[] = self::getMonth($i,$j);
                            } else {
                                $result[] = $i . '年' . $j . '月';
                            }
                        }
                    }
                    if($i == $maxyear) {
                        for($j=1;$j<=$maxmonth;$j++) {
                            if($str == 'time') {
                                $result[] = self::getMonth($i,$j);
                            } else {
                                $result[] = $maxyear.'年'.$j.'月';
                            }

                        }
                    }
                }
            }
        }

        return $result;
    }

    public static function getMonth($year,$month)
    {
        $mb = date("Y-m-d", strtotime($year.'-'.$month.'-'.'01'));
        $time = strtotime($mb);
        $me = date('Y-m-t', strtotime($mb));
        return [$mb,$me];
    }
}