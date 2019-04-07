<?php

namespace frontend\controllers;

use app\models\Breed;
use app\models\Breedinfo;
use app\models\Breedtype;
use app\models\Fireprevention;
use app\models\Insuranceplan;
use app\models\Lease;
use app\models\ManagementArea;
use app\models\Pesticides;
use app\models\Plant;
use app\models\Plantingstructure;
use app\models\Plantingstructureyearfarmsidplan;
use app\models\Plantpesticides;
use app\models\Sales;
use app\models\Subsidyratio;
use app\models\Subsidytypetofarm;
use app\models\Tables;
use app\models\User;
use app\models\Insurance;
use app\models\Theyear;
use frontend\helpers\Pinyin;
use Yii;
use frontend\helpers\Excel;
use yii\debug\models\search\Log;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Logs;
use app\models\Farms;
use app\models\Plantingstructurecheck;
use app\models\Saleswhere;
use app\models\Insurancetype;
use app\models\Plantinputproduct;
use app\models\BankAccount;
//use app\models\Insuracneplan;
/**
 * BreedController implements the CRUD actions for Breed model.
 */
class SixcheckController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/logout']);
        } else {
            return true;
        }
    }

    public function actionSixcheckindex($farms_id)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $isShow = true;
        $plantArea = [];
        $insurancetype = Insurancetype::find()->all();
        foreach ($insurancetype as $value) {
            $$value['pinyin'] = 0;
        }
        $farm = Farms::findOne($farms_id);
        $leases = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
        $leaseArea = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->sum('lease_area');
        $insurancePlan = Insuranceplan::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->count();
        if(!$insurancePlan) {
            $showInsuranceButton = true;
        } else {
            if (bccomp($farm->contractarea, $leaseArea) == 1) {
                $insuranceArea = Insuranceplan::find()->where(['farms_id' => $farms_id, 'lease_id' => 0, 'year' => User::getYear()])->count();
                if ($insuranceArea) {
                    $showInsuranceButton = false;
                } else {
                    $showInsuranceButton = true;
                }
            } else {
                $showInsuranceButton = false;
            }
        }
//        var_dump($showInsuranceButton);exit;
        $plantingstructure = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()]);
        $areaSum = $plantingstructure->sum('area');
        if(empty($areaSum)) {
            $areaSum = 0;
//            $isShow = false;
        }
        $fire = Fireprevention::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
        $breedinfos = Breedinfo::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
//        var_dump($farms_id);
//        var_dump($breedinfos);exit;
        $sales = Sales::find()->where(['farms_id'=>$farms_id,'year'=>User::getLastYear()])->all();
        $area = 0;
        $insuranceArea = 0;

        $insurance = Insuranceplan::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
        if(empty($insurance)) {
            foreach ($insurancetype as $value) {
                $$value['pinyin'] = Plantingstructure::find()->where(['plant_id'=>$value['plant_id'],'farms_id'=>$farms_id,'year'=>User::getYear()])->one()['area'];
                $area += $$value['pinyin'];
                $plantArea[$value['pinyin']] = ['name'=>Plant::find()->where(['id'=>$value['plant_id']])->one()['typename'],'value'=>$$value['pinyin'],'pinyin'=>$value['pinyin']];
            }
            $allArea = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->sum('area');
            $otherArea = sprintf("%.2f",$allArea - $area);
        } else {
            $insuranceArea = sprintf("%.2f",Insuranceplan::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->sum('insuredarea'));
            if(bccomp($farm->contractarea,$insuranceArea) == 0) {
                $isShow = false;
            }
        }
        $isFarmerPlanting = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'lease_id'=>0])->count();
        return $this->render('sixcheckindex', [
            'leases' => $leases,
            'noarea' => Lease::getNoArea($farms_id),
            'overarea' => Lease::getOverArea($farms_id),
            'farm' => $farm,
            'plantingstructure' => $plantingstructure->all(),
            'areaSum' => sprintf("%.2f",$areaSum),
            'fire' => $fire,
            'breedinfos' => $breedinfos,
            'sales' => $sales,
            'plantArea' => $plantArea,
            'insurance' => $insurance,
            'isShow' => $isShow,
            'isFarmerPlanting' => $isFarmerPlanting,
            'showInsuranceButton' => $showInsuranceButton,
        ]);
    }

    public function actionSixchecksave($value=null,$farms_id,$id=null)
    {
        $state = 1;
        $data = [];
        $farm = Farms::findOne($farms_id);
        $data = json_decode($value);
        $from = [];
        $isBack = '';
//        var_dump($data);exit;
        foreach ($data as $key => $item) {
            $pattern1 = '/\w*(?=\[)/';
            $pattern2 = '/(?:\[)(.*)(?:\])/i';

            preg_match($pattern1, $item->name, $classname);
            preg_match($pattern2, $item->name, $field);
            if(!empty($classname) and $classname[0] !== 'Indexecharts') {
                if (!empty($field)) {
                    if(strstr($item->name,'[]')) {
                        $f = str_replace("][","",$field[1]);
                        $from[$classname[0]][$f][] = $item->value;
                    } else {
                        $from[$classname[0]][$field[1]] = $item->value;
                    }
                }
            }
        }
//        var_dump($from);exit;
        $classArray = [];
        if($from) {
            foreach ($from as $key => $item) {
                switch ($key) {
                    case 'Subsidyratio':
                        $classArray[] = $key;
//                    var_dump($item);exit;
                        foreach ($item as $k => $v) {
                            $arr = explode('-', $k);
                            $typeid = Subsidytypetofarm::find()->where(['mark' => $arr[0]])->one()['id'];
                            $typetofarm = Subsidyratio::find()->where(['farms_id' => $farms_id,'lease_id'=>$Lease, 'typeid' => $typeid])->one();
                            if ($typetofarm) {
                                $model = Subsidyratio::findOne($typetofarm['id']);
                                $info = '更新承租者信息';
                            } else {
                                $model = new Subsidyratio();
                                $info = '新增承租者信息';
                            }
                            $model->farms_id = $farms_id;
                            $model->typeid = $typeid;
                            $model->lease_id = $Lease;
                            $model->$arr[1] = $v;
                            $model->create_at = time();
                            $model->year = User::getYear();
                            $state = $model->save();
                            Logs::writeLogs('基础调查表-'.$info,$model);
                            $data = $model;
                            $error[$key] = $model->getErrors();
                        }
                        break;
                    case 'Plantingstructure':
                        $classArray[] = $key;
                        $error[$key] = [];
//                        var_dump($item);exit;
                        foreach ($item as $k => $v) {
                            $arr = explode('-', $k);
                            if(count($arr) == 2) {
                                $plant_id = $arr[0];
                                $lease_id = $arr[1];
                            } else {
                                $plant_id = $arr[1];
                                $lease_id = $arr[2];
                            }

//                            var_dump($goodseed_id);
//                            var_dump($item[$arr[0] . '-' . $arr[1]]);
                            $plantArea = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id' => $lease_id, 'year' => User::getYear()])->sum('area');
//                            $leaseArea = Lease::find()->where(['id'=>$arr[1]])->one()['lease_area'];
//                            if(empty($leaseArea)) {
//                                $leaseArea = 0.0;
//                            }
//                            if(empty($plantArea)) {
//                                $plantArea = 0.0;
//                            }
//                            var_dump($plantArea);var_dump($leaseArea);exit;
                            $plantData = Plantingstructure::find()->where(['farms_id'=>$farms_id,'plant_id'=>$plant_id,'lease_id'=>$lease_id,'year'=>User::getYear()])->one();
                            if($plantData) {
                                if($item[$plant_id . '-' . $lease_id] == 0) {
                                    $model = Plantingstructure::findOne($plantData['id']);
                                    $model->delete();
                                    Plantingstructureyearfarmsidplan::newPlan($farms_id,0);
                                    $state = true;
                                    $info = '删除种植信息';
                                } else {
                                    $model = Plantingstructure::findOne($plantData['id']);
                                    $model->update_at = time();
                                    $info = '更新种植信息';
                                }
                            } else {
                                $model = new Plantingstructure();
                                $model->create_at = time();
                                $model->update_at = $model->create_at;
                                $info = '新增种植信息';
                            }
                            $model->farms_id = $farms_id;
                            $model->management_area = $farm['management_area'];
                            $model->year = (int)User::getYear();
                            $model->plant_id = $plant_id;
                            $model->lease_id = $lease_id;
                            $model->area = $item[$plant_id . '-' . $lease_id];
                            $model->state = $farm['state'];
//                            var_dump($plant_id);
//                            var_dump($model);exit;
                            if(isset($item['goodseed_id-'.$plant_id . '-' . $lease_id]))
                                $model->goodseed_id = $item['goodseed_id-'.$plant_id . '-' . $lease_id];
                            if ($model->lease_id) {
                                $model->planter = 1;
                            } else {
                                $model->planter = 0;
                            }
                            if(Insuranceplan::find()->where(['farms_id'=>$farms_id,'lease_id'=>$model->lease_id,'year'=>User::getYear()])->count()) {
                                $model->isinsurance = 1;
                            } else {
                                $model->isinsurance = 0;
                            }
//                            var_dump($model);
                            if ((float)$model->area > 0) {
//                            var_dump($model->area > 0);
                                if($item[$plant_id . '-' . $lease_id] > 0) {
                                    $state = $model->save();
                                    if($state) {
                                        Plantingstructureyearfarmsidplan::newPlan($model->farms_id,1);
                                    }
                                }
//                                var_dump($model);
//                                var_dump($model->getErrors());
                                Logs::writeLogs('基础调查表-'.$info,$model);
                                $error[$key][] = $model->getErrors();
//                            var_dump($model->getErrors());
                            }
                        }
                        break;
                    case 'Sales':
                        $classArray[] = $key;

                        $one = Plantingstructurecheck::findOne($id);
                        $model = new Sales();
                        $model->create_at = time();
                        $model->update_at = $model->create_at;
                        $model->year = User::getLastYear();
                        $model->management_area = Farms::getFarmsAreaID($farms_id);
                        foreach ($item as $k => $v) {
                            if ($k == 'whereabouts') {
                                $where = Saleswhere::find()->where(['wherename' => $v])->one();
                                if ($where) {
                                    $model->whereabouts = (string)$where['id'];
                                } else {
                                    $whereModel = new Saleswhere();
                                    $whereModel->wherename = $v;
                                    $whereModel->save();
                                    $model->whereabouts = (string)$whereModel->id;
                                }
                            } else {
                                $model->$k = $v;
                            }
                        }
                        $state = $model->save();
                        Logs::writeLogs('基础调查表-往年农产品销售去向',$model);
                        $error[$key][] = $model->getErrors();
                        $isBack = 'sales';
                        break;
                    case 'Plantinputproduct':
//                        var_dump($item);exit;
                        $planting_id = $item['planting_id'];
                        unset($item['planting_id']);
                        $planting = Plantingstructure::findOne($planting_id);
                        $plantinputproductModel = Plantinputproduct::find()->where(['planting_id' => $planting_id])->all();
                        $parmembersInputproduct = $item;
                        $this->deletePlantinput($plantinputproductModel, $parmembersInputproduct['id']);
                        if ($parmembersInputproduct) {
                            //var_dump($parmembers);
                            for($i=1;$i<count($parmembersInputproduct['inputproduct_id']);$i++) {
                                $plantinputproductModel = Plantinputproduct::findOne($parmembersInputproduct['id'][$i]);
                                if(empty($plantinputproductModel)) {
                                    $plantinputproductModel = new Plantinputproduct();
                                    $plantinputproductModel->create_at = time();
                                    $plantinputproductModel->update_at = $plantinputproductModel->create_at;
                                    $info = '新增投入品信息';
                                } else {
                                    $plantinputproductModel->update_at = time();
                                    $info = '更新投入品信息';
                                }
                                $plantinputproductModel->id = $parmembersInputproduct['id'][$i];
                                $plantinputproductModel->farms_id = $farms_id;
                                $plantinputproductModel->management_area = $farm->management_area;
                                $plantinputproductModel->lessee_id = $planting->lease_id;
//                                $plantinputproductModel->zongdi = $->zongdi;
                                $plantinputproductModel->plant_id = $planting->plant_id;
                                $plantinputproductModel->planting_id = $planting_id;
                                $plantinputproductModel->father_id = $parmembersInputproduct['father_id'][$i];
                                $plantinputproductModel->son_id = $parmembersInputproduct['son_id'][$i];
                                $plantinputproductModel->inputproduct_id = $parmembersInputproduct['inputproduct_id'][$i];
                                $plantinputproductModel->pconsumption = $parmembersInputproduct['pconsumption'][$i];

                                $state = $plantinputproductModel->save();
                                Logs::writeLogs('添加投入品',$plantinputproductModel);
                                $error[$key] = $plantinputproductModel->getErrors();
                            }
                        }
                        Logs::writeLogs('基础调查表-'.$info);
                        break;
                    case 'Plantpesticides':
                        $planting_id = $item['planting_id'];
                        unset($item['planting_id']);
                        $planting = Plantingstructure::findOne($planting_id);
                        $plantpesticidesModel = Plantpesticides::find()->where(['planting_id'=>$planting_id])->all();
                        $parmembersPesticides = $item;
                        $this->deletePlantpesticides($plantpesticidesModel, $parmembersPesticides['id']);
                        if($parmembersPesticides) {
                            for($i=1;$i<count($parmembersPesticides['pesticides_id']);$i++) {
                                $plantpesticidesModel = Plantpesticides::findOne($parmembersPesticides['id'][$i]);
                                if(empty($plantpesticidesModel)) {
                                    $plantpesticidesModel = new Plantpesticides();
                                    $plantpesticidesModel->create_at = time();
                                    $plantpesticidesModel->update_at = $plantpesticidesModel->create_at;
                                    $info = '新增农药信息';
                                } else {
                                    $plantpesticidesModel->update_at = time();
                                    $info = '更新农药信息';
                                }
                                $plantpesticidesModel->farms_id = $farms_id;
                                $plantpesticidesModel->management_area = $farm->management_area;
                                $plantpesticidesModel->lessee_id = $planting->lease_id;
                                $plantpesticidesModel->plant_id = $planting->plant_id;
                                $plantpesticidesModel->planting_id = $planting_id;
                                $plantpesticidesModel->pesticides_id = $parmembersPesticides['pesticides_id'][$i];
                                $plantpesticidesModel->pconsumption = $parmembersPesticides['pconsumption'][$i];

                                $state = $plantpesticidesModel->save();
                                $error[$key] = $plantpesticidesModel->getErrors();
                                Logs::writeLogs('添加投入品',$plantpesticidesModel);
                            }
                        }
                        Logs::writeLogs('基础调查表-'.$info);
                        break;
                    case 'breedtypePost':
                        $breed = Breed::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
                        $breedData = [];
                        foreach ($item as $k => $v) {
                            foreach ($v as $k2 => $v2) {
                                if ($k2 > 0) {
                                    $breedData[$k2][$k] = $v2;
                                }
                            }
                        }
//                        var_dump($breedData);exit;
                        foreach ($breedData as $kkk => $breedinfo) {
                            foreach ($breedinfo as $key => $value) {
                                if($key == 'id') {
                                    if(!empty($value)) {
                                        $model = Breedinfo::findOne($value);
                                        $model->update_at = time();
                                        $info = '更新畜牧信息';
                                    } else {
                                        $model = new Breedinfo();
                                        $model->create_at = time();
                                        $model->update_at = $model->create_at;
                                        $model->farms_id = $farms_id;
                                        $model->year = User::getYear();
                                        $model->management_area = $farm['management_area'];
                                        $model->farmstate = $farm->state;

                                        $info = '新增畜牧信息';
                                    }
                                } else {
                                    if($key !== 'father_id') {
                                        $model->$key = $value;
                                    }
                                    $model->breed_id = $breed['id'];
                                    $state = $model->save();
                                    Logs::writeLogs('基础调查表-'.$info,$model);
                                    $error[$kkk][] = $model->getErrors();
                                    $isBack = '';
                                }
                            }
                        }
                        break;
                    case 'Breed':
                        $data = Breed::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
                        if($data) {
                            $model = Breed::findOne($data['id']);
                            $model->update_at = time();
                            $info = '更新养殖场信息';
                        } else {
                            $model = new Breed();
                            $model->create_at = time();
                            $model->update_at = $model->create_at;
                            $info = '新增养殖场信息';
                        }
                        $model->farms_id = $farms_id;
                        $model->year = User::getYear();
                        $model->management_area = $farm['management_area'];
                        foreach ($item as $k => $v) {
                            $model->$k = $v;
                        }
                        $state = $model->save();

                        Logs::writeLogs('基础调查表-'.$info,$model);
                        $error[$key] = $model->getErrors();
                        break;
                    case 'Insuranceplan':
                        if($item['policyholder'] == 0) {
                            $farm = Farms::findOne($farms_id);
                            $leaseArea = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->sum('lease_area');
                            if($leaseArea) {
                                $insuranceArea = bcsub($farm->contractarea,$leaseArea,2);
                            } else {
                                $insuranceArea = $farm->contractarea;
                            }

                            $planModel = new Insuranceplan();
                            $planModel->management_area = $farm['management_area'];
                            $planModel->year = User::getYear();
                            $planModel->farms_id = $farm['id'];
                            $planModel->policyholder = $farm->farmername;
                            $planModel->cardid = $farm->cardid;
                            $planModel->telephone = $farm->telephone;
//                            var_dump($item);exit;
                            if(isset($item['isAll']) and !empty($item['isAll'])) {
                                $planModel->insuredarea = $farm->contractarea;
                            } else {
                                $planModel->insuredarea = $insuranceArea;
                            }
                            $planModel->create_at = time();
                            $planModel->update_at = $planModel->create_at;
                            $planModel->farmername = $farm['farmername'];
                            $planModel->contractarea = $farm['contractarea'];
                            $planModel->farmerpinyin = $farm['farmerpinyin'];
                            $planModel->policyholderpinyin = $farm['farmerpinyin'];
                            $planModel->farmstate = $farm['state'];
                            $planModel->lease_id = 0;

                            $insuranceModel = new Insurance();
                            $insuranceModel->management_area = $farm['management_area'];
                            $insuranceModel->year = User::getYear();
                            $insuranceModel->farms_id = $farm['id'];
                            $insuranceModel->policyholder = $farm->farmername;
                            $insuranceModel->cardid = $farm->cardid;
                            $insuranceModel->telephone = $farm->telephone;
                            $insuranceModel->insuredarea = $insuranceArea;
                            $insuranceModel->create_at = time();
                            $insuranceModel->update_at = $insuranceModel->create_at;
                            $insuranceModel->farmername = $farm['farmername'];
                            $insuranceModel->contractarea = $farm['contractarea'];
                            $insuranceModel->farmerpinyin = $farm['farmerpinyin'];
                            $insuranceModel->policyholderpinyin = $farm['farmerpinyin'];
                            $insuranceModel->farmstate = $farm['state'];
                            $insuranceModel->lease_id = 0;
                            $insuranceModel->save();
                            $planModel->insurance_id = $insuranceModel->id;
                            $state = $planModel->save();
                            Logs::writeLogs('新增保险任务', $insuranceModel);
                            $error[$key] = $planModel->getErrors();
                            Logs::writeLogs('新增计划保险', $planModel);
                            $plantings = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id' => 0, 'year' => User::getYear()])->all();
                            if ($plantings) {
                                foreach ($plantings as $planting) {
                                    $plantModel = Plantingstructure::findOne($planting['id']);
                                    $plantModel->isinsurance = 1;
                                    $plantModel->save();
                                    Logs::writeLogs('更新种植结构为不参加保险', $plantModel);
                                }
                            }
                        } else {
                            $model = Lease::findOne($item['policyholder']);
                            $planModel = Insuranceplan::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'lease_id'=>$model->id])->one();
                            if(empty($planModel)) {
                                $planModel = new Insuranceplan();
                                $planModel->create_at = time();
                                $planModel->update_at = $planModel->create_at;
                                $infop = '新增';
                            } else {
                                $planModel->update_at = time();
                                $infop = '更新';
                            }

                            $planModel->management_area = $farm['management_area'];
                            $planModel->year = User::getYear();
                            $planModel->farms_id = $farm['id'];
                            $planModel->policyholder = $model->lessee;
                            $planModel->cardid = $model->lessee_cardid;
                            $planModel->telephone = $model->lessee_telephone;
                            $planModel->insuredarea = $model->lease_area;

                            $planModel->farmername = $farm['farmername'];
                            $planModel->contractarea = $farm['contractarea'];
                            $planModel->farmerpinyin = $farm['farmerpinyin'];
                            $planModel->policyholderpinyin = Pinyin::encode($model->lessee);
                            $planModel->farmstate = $farm['state'];
                            $planModel->lease_id = $model->id;

                            $insuranceModel = Insurance::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'lease_id'=>$model->id])->one();
                            if(empty($insuranceModel)) {
                                $insuranceModel = new Insurance();
                                $insuranceModel->create_at = time();
                                $insuranceModel->update_at = $insuranceModel->create_at;
                                $infoi = '新增';
                            } else {
                                $insuranceModel->update_at = time();
                                $infoi = '更新';
                            }

                            $insuranceModel->management_area = $farm['management_area'];
                            $insuranceModel->year = User::getYear();
                            $insuranceModel->farms_id = $farm['id'];
                            $insuranceModel->policyholder = $model->lessee;
                            $insuranceModel->cardid = $model->lessee_cardid;
                            $insuranceModel->telephone = $model->lessee_telephone;
                            $insuranceModel->insuredarea = $model->lease_area;

                            $insuranceModel->farmername = $farm['farmername'];
                            $insuranceModel->contractarea = $farm['contractarea'];
                            $insuranceModel->farmerpinyin = $farm['farmerpinyin'];
                            $insuranceModel->policyholderpinyin = Pinyin::encode($model->lessee);
                            $insuranceModel->farmstate = $farm['state'];
                            $insuranceModel->lease_id = $model->id;
                            $insuranceModel->save();
                            $planModel->insurance_id = $insuranceModel->id;
                            $state = $planModel->save();
                            Logs::writeLogs($infoi.'保险任务', $insuranceModel);
                            $error[$key] = $planModel->getErrors();
                            Logs::writeLogs($infop.'计划保险', $planModel);
                            $plantings = Plantingstructure::find()->where(['lease_id' => $model->id, 'year' => User::getYear()])->all();
                            if ($plantings) {
                                foreach ($plantings as $planting) {
                                    $plantModel = Plantingstructure::findOne($planting['id']);
                                    $plantModel->isinsurance = 1;
                                    $plantModel->save();
                                    Logs::writeLogs('更新种植结构为不参加保险', $plantModel);
                                }
                            }
                        }

                        break;
                    case 'Lease':
                        if(isset($item['isinsurance'])) {
                            $isinsurance = $item['isinsurance'];
                        } else {
                            $isinsurance = false;
                        }
                        unset($item['isinsurance']);
                        $table = Tables::find()->where(['tablename'=>strtolower($key)])->one()['Ctablename'];
                        if ($id) {
                            $model = Lease::findOne($id);
                            $model->update_at = time();
                            $info = '更新'.$table.'信息';
                        } else {
//                            $data = $class::find()->where(['farms_id' => $farms_id, 'year' => User::getYear()])->one();
//                            if ($data) {
//                                $model = $class::findOne($data['id']);
//                                $model->update_at = time();
//                                $info = '更新'.$table.'信息';
//                            } else {
                            $model = new Lease();
                            $model->create_at = time();
                            $model->update_at = $model->create_at;
                            $info = '新增'.$table.'信息';
//                            }

                        }
                        $model->farms_id = $farms_id;
                        $model->year = User::getYear();
                        $model->management_area = $farm['management_area'];
                        foreach ($item as $k => $v) {
                            if($k == 'renttype') {
                                if(!empty($v)) {
                                    $model->$k = $v[0];
                                }
                            } else {
                                if($k !== 'accountnumber') {
                                    $model->$k = $v;
                                }
                            }
                        }
                        $state = $model->save();
                        Logs::writeLogs('基础调查表-'.$info,$model);
                        $error[$key] = $model->getErrors();
                        $$key = $model->id;
                        if($state) {
                            if($isinsurance) {
                                $planModel = Insuranceplan::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'lease_id'=>$model->id])->one();
                                if(empty($planModel)) {
                                    $planModel = new Insuranceplan();
                                    $planModel->create_at = time();
                                    $planModel->update_at = $planModel->create_at;
                                } else {
                                    $planModel->update_at = time();
                                }
                                $planModel->management_area = $farm['management_area'];
                                $planModel->year = User::getYear();
                                $planModel->farms_id = $farm['id'];
                                $planModel->policyholder = $model->lessee;
                                $planModel->cardid = $model->lessee_cardid;
                                $planModel->telephone = $model->lessee_telephone;
                                $planModel->insuredarea = $model->lease_area;

                                $planModel->farmername = $farm['farmername'];
                                $planModel->contractarea = $farm['contractarea'];
                                $planModel->farmerpinyin = $farm['farmerpinyin'];
                                $planModel->policyholderpinyin = Pinyin::encode($model->lessee);
                                $planModel->farmstate = $farm['state'];
                                $planModel->lease_id = $model->id;

                                $insuranceModel = Insurance::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear(),'lease_id'=>$model->id])->one();
                                if(empty($insuranceModel)) {
                                    $insuranceModel = new Insurance();
                                    $insuranceModel->create_at = time();
                                    $insuranceModel->update_at = $insuranceModel->create_at;
                                } else {
                                    $insuranceModel->update_at = time();
                                }

                                $insuranceModel->management_area = $farm['management_area'];
                                $insuranceModel->year = User::getYear();
                                $insuranceModel->farms_id = $farm['id'];
                                $insuranceModel->policyholder = $model->lessee;
                                $insuranceModel->cardid = $model->lessee_cardid;
                                $insuranceModel->telephone = $model->lessee_telephone;
                                $insuranceModel->insuredarea = $model->lease_area;

                                $insuranceModel->farmername = $farm['farmername'];
                                $insuranceModel->contractarea = $farm['contractarea'];
                                $insuranceModel->farmerpinyin = $farm['farmerpinyin'];
                                $insuranceModel->policyholderpinyin = Pinyin::encode($model->lessee);
                                $insuranceModel->farmstate = $farm['state'];
                                $insuranceModel->lease_id = $model->id;
                                $insuranceModel->save();
                                Logs::writeLogs('新增保险任务', $insuranceModel);
                                $planModel->insurance_id = $insuranceModel->id;
                                $state = $planModel->save();
                                Logs::writeLogs('新增计划保险',$planModel);
                                $plantings = Plantingstructure::find()->where(['lease_id'=>$model->id,'year'=>User::getYear()])->all();
                                if($plantings) {
                                    foreach ($plantings as $planting) {
                                        $plantModel = Plantingstructure::findOne($planting['id']);
                                        $plantModel->isinsurance = 1;
                                        $plantModel->save();
                                        Logs::writeLogs('更新种植结构为不参加保险', $plantModel);
                                    }
                                }
                            } else {
                                $planModel = Insuranceplan::find()->where(['farms_id'=>$farms_id,'lease_id'=>$model->id,'year'=>User::getYear()])->one();
                                $insurance = Insurance::find()->where(['farms_id'=>$farms_id,'lease_id'=>$model->id,'year'=>User::getYear()])->one();
                                $plantings = Plantingstructure::find()->where(['lease_id'=>$model->id,'year'=>User::getYear()])->all();
                                if($plantings) {
                                    foreach ($plantings as $planting) {
                                        $plantModel = Plantingstructure::findOne($planting['id']);
                                        $plantModel->isinsurance = 0;
                                        $plantModel->save();
                                        Logs::writeLogs('更新种植结构为不参加保险', $plantModel);
                                    }
                                }
                                if($planModel) {
                                    Logs::writeLogs('删除计划保险', $planModel);
                                    $planModel->delete();
                                }
                                if($insurance) {
                                    Logs::writeLogs('删除保险任务', $insurance);
                                    $insurance->delete();
                                }
                            }
                            if(!empty($item['accountnumber'])) {
                                $bank = BankAccount::find()->where(['farms_id'=>$farms_id,'lease_id'=>$model->id])->one();
                                if($bank) {
                                    $bankModel = BankAccount::findOne($bank['id']);
                                    $bankModel->update_at = time();
                                } else {
                                    $bankModel = new BankAccount();
                                    $bankModel->create_at = time();
                                    $bankModel->update_at = $bankModel->create_at;
                                }
                                $bankModel->farms_id = $farms_id;
                                $bankModel->bank = '大兴安岭农村商业银行';
                                $bankModel->accountnumber = $item['accountnumber'];
                                $bankModel->cardid = $model->lessee_cardid;
                                $bankModel->lessee = $model->lessee;

//                if (BankAccount::scanCard($cardid)) {
//                    $bankModel->state = $bankstate['state'];
//                    $bankModel->modfiyname = $bankstate['modfiyname'];
//                    $bankModel->modfiytime = $bankstate['modfiytime'];
//                } else {
                                $bankModel->state = 1;
//                }
                                $bankModel->management_area = $farm['management_area'];
                                $bankModel->farmername = $farm['farmername'];
                                $bankModel->farmerpinyin = $farm['farmerpinyin'];
                                $bankModel->farmname = $farm['farmname'];
                                $bankModel->farmpinyin = $farm['pinyin'];
                                $bankModel->lesseepinyin = Pinyin::encode($model->lessee);
                                $bankModel->contractnumber = $farm['contractnumber'];
                                $bankModel->contractarea = $farm['contractarea'];
                                $bankModel->farmstate = $farm['state'];
                                $bankModel->lease_id = $model->id;
                                Logs::writeLogs('创建' . $farm['farmername'] . '银行账号', $bankModel);
                                $bankModel->save();
//                                var_dump($bankModel->getErrors());
                            }
                        }
                        break;
                    case 'Fireprevention':
                        $table = Tables::find()->where(['tablename'=>strtolower($key)])->one()['Ctablename'];
                        $fire = Fireprevention::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
                        if($fire) {
                            $model = Fireprevention::findOne($fire['id']);
                            $model->update_at = time();
                            $info = '更新'.$table.'信息';
                        } else {
                            $model = new Fireprevention();
                            $model->create_at = time();
                            $model->update_at = $model->create_at;
                            $info = '新增'.$table.'信息';
                        }
                        $model->farms_id = $farms_id;
                        $model->year = User::getYear();
                        $model->management_area = $farm['management_area'];
                        foreach ($item as $k => $v) {
                            if($k == 'renttype') {
                                $model->$k = $v[0];
                            } else {
                                $model->$k = $v;
                            }
                        }
                        $state = $model->save();
                        $percent = Fireprevention::getPercent($model);
                        $model->percent = $percent;
                        if($percent > 60) {
                            $model->finished = 1;
                        }
                        if($percent > 0 and $percent <= 60) {
                            $model->finished = 2;
                        }
                        if($percent == 0 or empty($percent)) {
                            $model->finished = 0;
                        }
                        $model->save();
                        Logs::writeLogs('基础调查表-'.$info,$model);
                        $error[$key] = $model->getErrors();
                        break;
                    case 'Goodseedinfo':
                        
                        break;
                    default:
                        $classArray[] = $key;
                        $class = 'app\\models\\' . $key;
                        $table = Tables::find()->where(['tablename'=>strtolower($key)])->one()['Ctablename'];
                        if ($id) {
                            $model = $class::findOne($id);
                            $model->update_at = time();
                            $info = '更新'.$table.'信息';
                        } else {
//                            $data = $class::find()->where(['farms_id' => $farms_id, 'year' => User::getYear()])->one();
//                            if ($data) {
//                                $model = $class::findOne($data['id']);
//                                $model->update_at = time();
//                                $info = '更新'.$table.'信息';
//                            } else {
                                $model = new $class();
                                $model->create_at = time();
                                $model->update_at = $model->create_at;
                                $info = '新增'.$table.'信息';
//                            }

                        }
                        $model->farms_id = $farms_id;
                        $model->year = User::getYear();
                        $model->management_area = $farm['management_area'];
                        foreach ($item as $k => $v) {
                            if($k == 'renttype') {
                                $model->$k = $v[0];
                            } else {
                                $model->$k = $v;
                            }
                        }
                        $state = $model->save();
                        Logs::writeLogs('基础调查表-'.$info,$model);
                        $error[$key] = $model->getErrors();
                        $$key = $model->id;
                }
            }
//            var_dump($error);
            echo json_encode(['state' => $state, 'error' => $error,'class'=>$classArray,'data'=>$data,'isBack'=>$isBack]);
        } else {
            echo json_encode(['state' => true,'isBack'=>'']);
        }


    }

//	public function actionSixcheckindex($farms_id)
//	{
//        Logs::writeLogs('六项基础调查卡');
//        $state = 0;
//        $farm = Farms::findOne($farms_id);
//        $post = Yii::$app->request->post();
//        $plantingstructureData = Plantingstructure::find()->where(['farms_id'=>$farms_id])->andFilterWhere(['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]])->all();
//        if($plantingstructureData) {
//            $state = 1;
//        }
//        $leaseData = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
//        $insuranceData = Insurance::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
//        $fireData = Fireprevention::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
//        $breedData = Breed::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
//        $breedinfoData = Breedinfo::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->count();
//        //var_dump($plantingstructureData);exit;
//        if($post) {
////            var_dump($post);exit;
//            $userinfo = [];
//            $plantPost = Yii::$app->request->post('Plant');
//            $plantingstructurePost = Yii::$app->request->post('Plantingstructure');
//            $isInsurance = Yii::$app->request->post('plantingstructure');
////            foreach ($plantArray as $value) {
////                $valuepost = Yii::$app->request->post($value);
////                if($valuepost) {
////                    $userinfo[$value] = $valuepost;
////                }
////            }
////            exit;
////            var_dump($plantingstructurePost);exit;
////            var_dump($plantPost);exit;
////            foreach ($plantPost as $key => $plant) {
//                //var_dump($plant);
//                for ($i = 0; $i < count($plantingstructurePost['lease_id']); $i++) {
//                    for ($m = 0; $m < count($plantPost['plant'][$i]); $m++) {
//                        $isPost = false;
//
//                        if ($plantingstructurePost['cardid'][$i] == $farm->cardid) {
//                            $plantingstructure = Plantingstructure::find()->andFilterWhere(['farms_id' => $farms_id, 'plant_id' => $plantPost['plantson_id'][$i][$m], 'lease_id' => 0])->andFilterWhere(['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]])->one();
//                            if ($plantingstructure) {
//                                $model = Plantingstructure::findOne($plantingstructure['id']);
//                                $model->update_at = time();
//                            } else {
//                                $model = new Plantingstructure();
//                                $model->create_at = time();
//                                $model->update_at = $model->create_at;
//                            }
//                            $model->lease_id = 0;
//                            $model->year = User::getYear();
//                            $isFarmer = false;
//                            if (Yii::$app->request->post('plantingstructure')['issurance'][0]) {
//                                $insurancePost = Yii::$app->request->post('Insurance');
////                            var_dump($insurancePost);exit;
//                                for ($j = 0; $j < count($insurancePost['policyholder']); $j=$j+2) {
//                                    if($insurancePost['cardid'][$j] == $farm->cardid) {
//                                        $isFarmer = true;
//                                    }
//                                }
//                                if($isFarmer) {
//                                    $insurance = Insurance::find()->where(['year' => date('Y'), 'farms_id' => $farms_id, 'cardid' => $plantingstructurePost['cardid'][$i]])->one();
//                                    if ($insurance) {
//                                        $insuranceModel = Insurance::findOne($insurance['id']);
//                                        $insuranceModel->update_at = (string)time();
//                                    } else {
//                                        $insuranceModel = new Insurance();
//                                        $insuranceModel->create_at = (string)time();
//                                        $insuranceModel->update_at = $insuranceModel->create_at;
//                                    }
//                                    $insuranceModel->farms_id = $farms_id;
//                                    $insuranceModel->management_area = $farm->management_area;
//                                    $insuranceModel->policyholder = $plantingstructurePost['policyholder'][$i];
//                                    $insuranceModel->cardid = $plantingstructurePost['cardid'][$i];
//                                    $insuranceModel->telephone = $plantingstructurePost['telephone'][$i];
//                                    $insuranceModel->company_id = $insurancePost['companyname'][0];
//                                    $insuranceModel->wheat = $insurancePost['insuredwheat'][0];
//                                    $insuranceModel->insuredwheat = $insurancePost['insuredwheat'][0];
//                                    $insuranceModel->soybean = $insurancePost['insuredsoybean'][0];
//                                    $insuranceModel->insuredsoybean = $insurancePost['insuredsoybean'][0];
//                                    $insuranceModel->other = $insurancePost['insuredother'][0];
//                                    $insuranceModel->insuredother = $insurancePost['insuredother'][0];
//                                    $insuranceModel->insuredarea = $insurancePost['insurancearea'][0];
//                                    $insuranceModel->farmername = $farm->farmername;
//                                    $insuranceModel->state = 0;
//                                    $insuranceModel->farmerpinyin = $farm->farmerpinyin;
//                                    $insuranceModel->policyholderpinyin = $farm->farmerpinyin;
//                                    if($insuranceModel->policyholder == $farm->farmername) {
//                                        $insuranceModel->nameissame = 1;
//                                    } else {
//                                        $insuranceModel->nameissame = 0;
//                                    }
////                                    $insuranceModel->fwdtstate = 2;
//                                    $insuranceModel->contractarea = $farm->contractarea;
//                                    $insuranceModel->year = User::getYear();
//                                    $insuranceModel->save();
//                                    Logs::writeLogs('六项基础调查卡-保险业务',$insuranceModel);
////                                var_dump($insuranceModel);exit;
//                                }
//                            }
//                            $model->farms_id = $farms_id;
//                            $model->management_area = $farm->management_area;
//                            $model->plant_id = $plantPost['plantson_id'][$i][$m];
//                            $model->area = (float)$plantPost['area'][$i][$m];
//                            $model->goodseed_id = $plantPost['goodseed_id'][$i][$m];
//                            $model->save();
//                            Logs::writeLogs('六项基础调查卡-种植结构',$model);
//                        } else {
//                            $lease = Lease::find()->where(['farms_id' => $farms_id, 'year' => User::getYear(), 'lessee_cardid' => $plantingstructurePost['cardid'][$i]])->one();
//                            if ($lease) {
//                                $leaseModel = Lease::findOne($lease['id']);
//                                $leaseModel->update_at = (string)time();
//                            } else {
//                                $leaseModel = new Lease();
//                                $leaseModel->create_at = (string)time();
//                                $leaseModel->update_at = $leaseModel->create_at;
//                            }
//                            $leaseModel->lessee = $plantingstructurePost['lease_id'][$i];
//                            $leaseModel->lessee_cardid = $plantingstructurePost['cardid'][$i];
//                            $leaseModel->farms_id = $farms_id;
//                            $leaseModel->lease_area = (float)$plantPost['area'][$i][$m];
//                            $leaseModel->year = User::getYear();
//                            $leaseModel->lessee_telephone = $plantingstructurePost['telephone'][$i];
//                            $leaseModel->begindate = date('Y') . '-01-01';
//                            $leaseModel->enddate = date('Y') . '-12-31';
//                            $isFarmer = false;
//                            if (Yii::$app->request->post('plantingstructure')['issurance'][0]) {
//                                $insurancePost = Yii::$app->request->post('Insurance');
//                                for ($j = 0; $j < count($insurancePost['policyholder']); $j++) {
//                                    if($insurancePost['cardid'] == $farm->cardid) {
//                                        $isFarmer = true;
//                                    }
//                                }
//                                if(!$isFarmer) {
////                                    var_dump($insurancePost);
////                                    exit;
////                                for ($j = $f; $j < count($insurancePost['policyholder']); $j=$j+2) {
////                                    var_dump($insurancePost['insurancearea'][$j]);
//                                    $insurance = Insurance::find()->where(['year' => date('Y'), 'farms_id' => $farms_id, 'cardid' => $plantingstructurePost['cardid'][$i]])->one();
//                                    if ($insurance) {
//                                        $insuranceModel = Insurance::findOne($insurance['id']);
//                                        $insuranceModel->update_at = (string)time();
//                                    } else {
//                                        $insuranceModel = new Insurance();
//                                        $insuranceModel->create_at = (string)time();
//                                        $insuranceModel->update_at = $insuranceModel->create_at;
//                                    }
//                                    $insuranceModel->farms_id = $farms_id;
//                                    $insuranceModel->management_area = $farm->management_area;
//                                    $insuranceModel->policyholder = $plantingstructurePost['lease_id'][$i];
//                                    $insuranceModel->cardid = $plantingstructurePost['cardid'][$i];
//                                    $insuranceModel->telephone = $plantingstructurePost['telephone'][$i];
//                                    $insuranceModel->company_id = $insurancePost['companyname'][$i];
//                                    $insuranceModel->wheat = $insurancePost['insuredwheat'][$i];
//                                    $insuranceModel->insuredwheat = $insurancePost['insuredwheat'][$i];
//                                    $insuranceModel->soybean = $insurancePost['insuredsoybean'][$i];
//                                    $insuranceModel->insuredsoybean = $insurancePost['insuredsoybean'][$i];
//                                    $insuranceModel->other = $insurancePost['insuredother'][$i];
//                                    $insuranceModel->insuredother = $insurancePost['insuredother'][$i];
//                                    $insuranceModel->insuredarea = $insurancePost['insurancearea'][$i];
//                                    $insuranceModel->farmername = $farm->farmername;
//                                    $insuranceModel->state = 0;
//                                    $insuranceModel->farmerpinyin = $farm->farmerpinyin;
//                                    $insuranceModel->policyholderpinyin = Pinyin::encode($insurancePost['policyholder'][$i]);
//                                    $insuranceModel->nameissame = 1;
//                                    $insuranceModel->contractarea = $farm->contractarea;
//                                    $insuranceModel->year = User::getYear();
//                                    $insuranceModel->save();
//                                    Logs::writeLogs('六项基础调查卡-保险业务',$insuranceModel);
//                                }
////                                    var_dump($insuranceModel);
////                                var_dump($insuranceModel->getErrors());exit;
////                                }
////                                exit;
//                            }
//                            $insurance = Insurance::find()->where(['year' => date('Y'), 'farms_id' => $farms_id, 'cardid' => $plantingstructurePost['cardid'][$i]])->one();
//                            $leaseModel->policyholder = $insurance['policyholder'];
//                            $leaseModel->insured = $insurance['policyholder'];
//                            $leasePost = Yii::$app->request->post('Lease');
//                            $leaseModel->zhzb_farmer = $leasePost['zhzb_farmer'][$i];
//                            $leaseModel->zhzb_lessee = $leasePost['zhzb_lessee'][$i];
//                            $leaseModel->ddcj_farmer = $leasePost['ddcj_farmer'][$i];
//                            $leaseModel->ddcj_lessee = $leasePost['ddcj_lessee'][$i];
//                            $leaseModel->goodseed_farmer = $leasePost['goodseed_farmer'][$i];
//                            $leaseModel->goodseed_lessee = $leasePost['goodseed_lessee'][$i];
//                            $leaseModel->new_farmer = $leasePost['new_farmer'][$i];
//                            $leaseModel->new_lessee = $leasePost['new_lessee'][$i];
//                            $leaseModel->save();
//                            Logs::writeLogs('六项基础调查卡-租赁',$leaseModel);
//
//                            $lease = Lease::find()->where(['farms_id' => $farms_id, 'year' => User::getYear(), 'lessee_cardid' => $plantingstructurePost['cardid'][$i]])->one();
//                            $plantingstructure = Plantingstructure::find()->andFilterWhere(['farms_id' => $farms_id, 'plant_id' => $plantPost['plantson_id'][$i][$m], 'lease_id' => $lease['id']])->andFilterWhere(['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]])->one();
//                            if ($plantingstructure) {
//                                $model = Plantingstructure::findOne($plantingstructure['id']);
//                                $model->update_at = time();
//                            } else {
//                                $model = new Plantingstructure();
//                                $model->create_at = time();
//                                $model->update_at = $model->create_at;
//                            }
//
//                            //var_dump($leaseModel->getErrors());
//                            $model->lease_id = $lease['id'];
//                            $model->farms_id = $farms_id;
//                            $model->management_area = $farm->management_area;
//                            $model->plant_id = $plantPost['plantson_id'][$i][$m];
//                            $model->area = (float)$plantPost['area'][$i][$m];
//                            $model->goodseed_id = $plantPost['goodseed_id'][$i][$m];
//                            $model->year = User::getYear();
//                            $model->save();
//                            Logs::writeLogs('六项基础调查卡-种植结构',$model);
//                        }
//
//                        $plantinputproduct = Plantinputproduct::find()->where(['planting_id' => $model->id])->one();
//                        if ($plantinputproduct) {
//                            $plantinputproductModel = Plantinputproduct::findOne($plantinputproduct['id']);
//                            $plantinputproductModel->update_at = time();
//                        } else {
//                            $plantinputproductModel = new Plantinputproduct();
//                            $plantinputproductModel->create_at = time();
//                            $plantinputproductModel->update_at = $plantinputproductModel->create_at;
//                        }
//                        $plantinputproductModel->farms_id = $farms_id;
//                        $plantinputproductModel->management_area = $farm->management_area;
//                        $lease = Lease::find()->where(['farms_id' => $farms_id, 'year' => User::getYear(), 'lessee_cardid' => $plantingstructurePost['cardid'][$i]])->one();
//                        $plantinputproductModel->lessee_id = $model->lease_id;
////                    var_dump(Yii::$app->request->post(''))
//                        $plantinputproductModel->plant_id = $plantPost['plantson_id'][$i][$m];
//                        $plantinputproductModel->father_id = (int)$plantPost['father_id'][$i][$m];
//                        if(isset($plantPost['son_id'])) {
//                            $plantinputproductModel->son_id = (int)$plantPost['son_id'][$i][$m];
//                        }
//                        if(isset($plantPost['inputproduct_id'])) {
//                            $plantinputproductModel->inputproduct_id = (int)$plantPost['inputproduct_id'][$i][$m];
//                        }
//                        $plantinputproductModel->pconsumption = (float)$plantPost['productpconsumption'][$i][$m];
//                        $plantinputproductModel->planting_id = $model->id;
//                        $plantinputproductModel->save();
//                        Logs::writeLogs('六项基础调查卡-投入品',$plantinputproductModel);
//                        //var_dump($i);
////                    var_dump($plantinputproductModel);
//                        $pesticidesData = Plantpesticides::find()->where(['planting_id' => $model->id])->one();
//                        if ($pesticidesData) {
//                            $pesticidesModel = Plantpesticides::findOne($pesticidesData['id']);
//                            $pesticidesModel->update_at = time();
//                        } else {
//                            $pesticidesModel = new Plantpesticides();
//                            $pesticidesModel->create_at = time();
//                            $pesticidesModel->update_at = $pesticidesModel->create_at;
//                        }
//                        $pesticidesModel->farms_id = $farms_id;
//                        $pesticidesModel->management_area = $farm->management_area;
//                        $pesticidesModel->lessee_id = $model->lease_id;
//                        $pesticidesModel->plant_id = $plantPost['plantson_id'][$i][$m];
//                        $pesticidesModel->planting_id = $model->id;
//                        $pesticidesModel->pesticides_id = (int)$plantPost['pesticides'][$i][$m];
//                        $pesticidesModel->pconsumption = (float)$plantPost['pesticidepconsumption'][$i][$m];
//                        $pesticidesModel->save();
//                        Logs::writeLogs('六项基础调查卡-农药',$pesticidesModel);
////                    var_dump($pesticidesModel);exit;
//                    }
////            }
//                    $fire = Fireprevention::find()->where(['year' => User::getYear(), 'farms_id' => $farms_id])->one();
//                    $firePost = Yii::$app->request->post('Fireprevention');
//                    if ($fire) {
//                        $fireModel = Fireprevention::findOne($fire['id']);
//                        $fireModel->update_at = (string)time();
//                    } else {
//                        $fireModel = new Fireprevention();
//                        $fireModel->create_at = (string)time();
//                        $fireModel->update_at = $fireModel->create_at;
//                    }
//                    $fireModel->farms_id = $farms_id;
//                    $fireModel->management_area = $farm->management_area;
//                    $fireModel->year = User::getYear();
//                    $fireModel->firecontract = $firePost['firecontract'][0];
//                    $fireModel->safecontract = $firePost['safecontract'][0];
//                    $fireModel->environmental_agreement = $firePost['environmental_agreement'][0];
//                    $fireModel->fieldpermit = $firePost['fieldpermit'][0];
//                    $fireModel->leaflets = $firePost['leaflets'][0];
//                    $fireModel->rectification_record = $firePost['rectification_record'][0];
//                    $fireModel->save();
//                    Logs::writeLogs('六项基础调查卡-防火',$fireModel);
////            var_dump($fireModel->getErrors());exit;
//
//                    $breedtypePost = Yii::$app->request->post('Breedtype');
////                    var_dump($breedtypePost);exit;
//                    if ($breedtypePost['father_id'] !== '') {
//                        $breedPost = Yii::$app->request->post('Breed');
//                        $breed = Breed::find()->where(['farms_id' => $farms_id])->one();
//                        if ($breed) {
//                            $breedModel = Breed::findOne($breed['id']);
//                            $breedModel->update_at = time();
//                        } else {
//                            $breedModel = new Breed();
//                            $breedModel->create_at = time();
//                            $breedModel->update_at = $breedModel->create_at;
//                        }
//                        $breedModel->farms_id = $farms_id;
//                        $breedModel->management_area = $farm->management_area;
//                        $breedModel->breedname = $breedPost['breedname'][0];
//                        $breedModel->breedaddress = $breedPost['breedaddress'][0];
//                        $breedModel->is_demonstration = $breedPost['is_demonstration'][0];
//                        $breedModel->year = User::getYear();
//                        if ($breedModel->save()) {
//                            Logs::writeLogs('六项基础调查卡-牧场',$breedModel);
//                            $breedinfo = Breedinfo::find()->where(['breed_id' => $breedModel->id])->one();
//                            for ($o = 1; $o < count($breedtypePost['father_id']); $o++) {
//                                if ($breedinfo) {
//                                    $breedinfoModel = Breedinfo::findOne($breedinfo['id']);
//                                    $breedinfoModel->update_at = time();
//                                } else {
//                                    $breedinfoModel = new Breedinfo();
//                                    $breedinfoModel->create_at = time();
//                                    $breedinfoModel->update_at = $breedinfoModel->create_at;
//                                }
//                                $breedinfoModel->breed_id = $breedModel->id;
//                                $breedinfoModel->farms_id = $farms_id;
//                                $breedinfoModel->management_area = $farm->management_area;
//                                $breedinfoModel->year = User::getYear();
//                                $breedinfoModel->breedtype_id = $breedtypePost['breedtype_id'][$o];
//                                $breedinfoModel->number = $breedtypePost['number'][$o];
//                                $breedinfoModel->basicinvestment = $breedtypePost['basicinvestment'][$o];
//                                $breedinfoModel->housingarea = $breedtypePost['housingarea'][$o];
//                                $breedinfoModel->save();
//                                Logs::writeLogs('六项基础调查卡-畜牧',$breedinfoModel);
////                        var_dump($breedinfoModel->getErrors());exit;
//                            }
//                        }
//                    }
//                }
////            exit;
//            return $this->redirect(['sixcheckview',
//                'farms_id' => $farms_id
//            ]);
//
//        }
//        //exit;
//        return $this->render('sixcheckindex', [
//            'farm'=> $farm,
//            'plantingstructureData' => $plantingstructureData,
//            'leaseData' => $leaseData,
//            'insuranceData' => $insuranceData,
//            'breedData' => $breedData,
//            'breedinfoData' => $breedinfoData,
//            'fireData' => $fireData,
//            'state' => $state
//        ]);
//	}

    public function actionSixcheckview($farms_id)
    {
        $farm = Farms::findOne($farms_id);

        $plantingstructureData = Plantingstructure::find()->where(['farms_id'=>$farms_id])->andFilterWhere(['between', 'create_at', Theyear::getYeartime()[0], Theyear::getYeartime()[1]])->all();

        $leaseData = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
        $insuranceData = Insurance::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
        $fireData = Fireprevention::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
        $breedData = Breed::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->one();
        $breedinfoData = Breedinfo::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->count();
        Logs::writeLogs('六项基础调查卡-查看');
            return $this->render('sixcheckview', [
                'farm'=> $farm,
                'plantingstructureData' => $plantingstructureData,
                'leaseData' => $leaseData,
                'insuranceData' => $insuranceData,
                'breedData' => $breedData,
                'breedinfoData' => $breedinfoData,
                'fireData' => $fireData,
            ]);
    }

    public function actionSixcheckcreate($farms_id)
    {
        $this->layout='@app/views/layouts/nomain2.php';
        $farm = Farms::findOne($farms_id);
        Logs::writeLogs('填报六项基础调查卡',$farm);
        return $this->render('sixcheckcreate', [
            'farm'=> $farm,
        ]);
    }

    public function actionSixchecklease($farms_id)
    {
        $lease = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
        $farmname = Farms::findOne($farms_id)['farmname'];
        Logs::writeLogs($farmname.'的租赁信息');
        return $this->renderajax('sixchecklease', [
            'leases' => $lease,
            'noarea' => Lease::getNoArea($farms_id),
            'overarea' => Lease::getOverArea($farms_id),
        ]);
    }

    private function deletePlantinput($nowdatabase,$postdataidarr) {
        $databaseid = array();
        foreach($nowdatabase as $value) {
            $databaseid[] = $value['id'];
        }
        $result = array_diff($databaseid,$postdataidarr);
        if($result) {
            foreach($result as $val) {
                $model = Plantinputproduct::findOne($val);
                //var_dump($model->attributes);
                $oldAttr = $model->attributes;
                Logs::writeLogs('删除投入品',$model);
                $model->delete();
            }
            return true;
        } else
            return false;
    }

    private function deletePlantpesticides($nowdatabase,$postdataidarr) {
        $databaseid = array();
        foreach($nowdatabase as $value) {
            $databaseid[] = $value['id'];
        }
        $result = array_diff($databaseid,$postdataidarr);
        if($result) {
            foreach($result as $val) {
                $model = Plantpesticides::findOne($val);
                $oldAttr = $model->attributes;
                Logs::writeLogs('删除投入品',$model);
                $model->delete();
            }
            return true;
        } else
            return false;
    }
}
