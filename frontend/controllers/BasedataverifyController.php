<?php

namespace frontend\controllers;

use app\models\BankAccount;
use app\models\Lease;
use app\models\Plant;
use app\models\Plantingstructure;
use app\models\Plantingstructurecheck;
use app\models\Plantingstructureyearfarmsid;
use app\models\Insurance;
use frontend\helpers\whereHandle;
use frontend\models\logSearch;
use frontend\models\plantingstructurecheckSearch;
use app\models\User;
use Yii;
use app\models\Logs;
use app\models\Insuranceplan;
use yii\web\Controller;
use yii\filters\VerbFilter;
use frontend\models\PlantingstructureyearfarmsidSearch;
use frontend\models\farmsSearch;
use app\models\Plantinputproduct;
use app\models\Farms;
use app\models\ManagementArea;
use app\models\Plantingstructureyearfarmsidplan;
use frontend\models\PlantingstructureyearfarmsidplanSearch;
use frontend\models\plantingstructureSearch;
use app\models\Subsidyratio;
/**
 * AfterchenqianController implements the CRUD actions for Afterchenqian model.
 */
class BasedataverifyController extends Controller
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
    public function actionBasedataverifyindex()
    {
        return $this->render('basedataverifyindex');
    }
    /**
     * Lists all Afterchenqian models.
     * @return mixed
     */

    public function actionBasedataverifylist_error()
    {
        $result = [];
        $management_area = Farms::getManagementArea()['id'];
        $plantings = Plantingstructurecheck::find()->where(['management_area'=>$management_area,'year'=>User::getYear()])->all();
        $i = 0;
        foreach ($plantings as $k => $value) {
            $i++;
            $farm = Farms::findOne($value['farms_id']);
            $contractarea = $farm['contractarea'];
            $leasename = $farm['farmername'];
            $area = $farm['contractarea'];
            $cardid = $farm['cardid'];
            $telephone = $farm['telephone'];
            $plantsum = 0;
            $ddarea = 0;
            $ddzongdi = '';
            $ddbtfarmer = '';
            $ddbtlease = '';
            $ymarea = 0;
            $ymzongdi = '';
            $ymbtfarmer = '';
            $ymbtlease = '';
            $xm = 0;
            $mls = 0;
            $zd = 0;
            $by = 0;
            $lm = 0;
            $other = 0;
            $verifydate = '';
            $content = '';
            $isFarmer = false;
            if($value['lease_id'] == 0) {
                $isFarmer = true;
                $plantsum+=$value['area'];
                $verifydate = $value['verifydate'];
                $content = $value['content'];
                switch ($value['plant_id']) {
                    case 6:
                        $ddarea = $value['area'];
                        $ddzongdi = $value['zongdi'];
                        $ddbtfarmer = '100%';
                        $ddbtlease = '0%';
                        break;
                    case 3:
                        $ymarea = $value['area'];
                        $ymzongdi = $value['zongdi'];
                        $ymbtfarmer = '100%';
                        $ymbtlease = '0%';
                        break;
                    case 2:
                        if($value['area'])
                            $xm = $value['area'];
                        else {
                            $xm = 0;
                        }
                        break;
                    case 4:
                        if($value['area'])
                            $mls = $value['area'];
                        else
                            $mls = 0;
                        break;
                    case 14:
                        if($value['area'])
                            $zd = $value['area'];
                        else {
                            $zd = 0;
                        }
                        break;
                    case 8:
                        if($value['area'])
                            $by = $value['area'];
                        else
                            $by = 0;
                        break;
                    case 17:
                        if($value['area'])
                            $lm = $value['area'];
                        else
                            $lm = 0;
                        break;
                    case 16:
                        if($value['area'])
                            $other = $value['area'];
                        else
                            $other = 0;
                        break;
                }
            } else {
                if(Plantingstructurecheck::find()->where(['farms_id'=>$farm['id'],'year'=>User::getYear()])->count() > 2)
                    $contractarea = 0;
                $lease = Lease::findOne($value['lease_id']);
                $leasename = $lease->lessee;
                $cardid = $lease->lessee_cardid;
                $area = $plantsum;
                $telephone = $lease->lessee_telephone;
            }
            $result[] = [
                'row' => $i,
                'management_area' => ManagementArea::getAreanameOne($farm['management_area']),
                'farmname' => $farm['farmname'],
                'farmername' => $farm['farmername'],
                'contractnumber' => $farm['contractnumber'],
                'lease' => $leasename,
                'cardid' => $cardid,
                'telephone' => $telephone,
                'contractarea' => $contractarea,
                'area' => $area,
                'ddarea' => $ddarea,
                'ddzongdi' => $ddzongdi,
                'ddbtfarmer' => $ddbtfarmer,
                'ddbtlease' => $ddbtlease,
                'ymarea' => $ymarea,
                'ymzongdi' => $ymzongdi,
                'ymbtfarmer' => $ymbtfarmer,
                'ymbtlease' => $ymbtlease,
                'xm' => $xm,
                'mls' => $mls,
                'zd' => $zd,
                'by' => $by,
                'lm' => $lm,
                'other' => $other,
                'verifydate' => $verifydate,
                'content' => $content,
            ];
        }
        return $this->render('basedataverifylist', [
            'result' => $result,
            'areaname' => Farms::getManagementArea()['areaname'],
        ]);
    }

    public function actionBasedataverifylist($where)
    {
        Logs::writeLogs('种植结构汇总表');
        $f = 0;
        $l = 0;
        $whereArray = json_decode($where,true);
//        var_dump($whereArray);exit;
        $farmsWhere = whereHandle::toFarmsWhere($whereArray);
//        var_dump($farmsWhere);exit;
        $result = [];
        $management_area = Farms::getManagementArea()['id'];
        
        $farms = Plantingstructureyearfarmsid::find()->where($whereArray)->all();
//        var_dump(count($farms));exit;
        $sum = Plantingstructureyearfarmsid::find()->where($whereArray)->sum('contractarea');
//        var_dump($farms);exit;
//        $plantAllSum = Plantingstructurecheck::find()->where(whereHandle::toPlantWhere($whereArray))->andWhere(['year'=>User::getYear()])->sum('area');
        $count = count($farms);
        $i = 0;
        $farmsAllid = [];
        foreach ($farms as $key => $farm) {
            $farmerbank = BankAccount::find()->where(['cardid'=>$farm['cardid'],'lease_id'=>0,'state'=>1])->one();
//            var_dump($farmerbank);
            $ddarea = 0;
            $ddzongdi = '';
            $ddbtfarmer = 0;
            $ddbtlease = 0;
            $ymarea = 0;
            $ymzongdi = '';
            $ymbtfarmer = 0;
            $ymbtlease = 0;
            $xm = 0;
            $mls = 0;
            $zd = 0;
            $by = 0;
            $lm = 0;
            $other = 0;
            $verifydate = '';
            $content = '';
            //获取法人种植信息
            $planting = Plantingstructurecheck::find()->where(['farms_id' => $farm['farms_id'], 'lease_id' => 0, 'year' => User::getYear()])->all();
            //如果存在法人种植信息则执行以下操作
            if ($planting) {
                $farmsAllid[] = $farm['farms_id'];
                $f++;
                $i++;
                $plantsum = 0;
                foreach ($planting as $k => $value) {
                    $sub = Subsidyratio::getSubsidyratio($value['plant_id'],$value['farms_id'],$value['lease_id']);
//                    $management_area = ManagementArea::getAreaname($farm['management_area']);
                    $farmerp = (float)$sub['farmer']/100;
                    $lesseep = (float)$sub['lessee']/100;
                    $plantsum+=$value['area'];
//                    if($value['farms_id'] == 1305) {
//                        $plantsum;exit;
//                    }
                    $verifydate = $value['verifydate'];
                    $content = $value['content'];
                    switch ($value['plant_id']) {
                        case 6:
                            $ddarea = $value['area'];
                            if($value['lease_id'] == 0) {
                                $ddbtfarmer = $value['area'];
                            } else {
                                if (bccomp($farmerp, 1) == 0) {
                                    $p = false;
                                    $ddbtfarmer = bcmul($value['area'], $farmerp, 2);
                                }
                                if (bccomp($lesseep, 1) == 0) {
                                    $p = false;
                                    $ddbtlease = bcmul($value['area'], $lesseep, 2);
                                }
//						if (bccomp($farmerp, 0) == 1 and bccomp($farmerp, 1) == -1) {
                                if ($farmerp > 0 and $farmerp < 1) {
                                    $p = true;
                                    $ddbtfarmer = bcmul($value['area'], $farmerp, 3);
                                    $ddbtlease = bcmul($value['area'], $lesseep, 3);
                                }
                            }
                            $ddzongdi = $value['zongdi'];
//                            $ddbtfarmer = $value['area'];
//                            $ddbtlease = 0;
                            break;
                        case 3:
                            $ymarea = $value['area'];
                            if($value['lease_id'] == 0) {
                                $ymbtfarmer = $value['area'];
                            } else {
                                if (bccomp($farmerp, 1) == 0) {
                                    $p = false;
                                    $ymbtfarmer = bcmul($value['area'], $farmerp, 2);
                                }
                                if (bccomp($lesseep, 1) == 0) {
                                    $p = false;
                                    $ymbtlease = bcmul($value['area'], $lesseep, 2);
                                }
//						if (bccomp($farmerp, 0) == 1 and bccomp($farmerp, 1) == -1) {
                                if($farmerp > 0 and $farmerp < 1) {
                                    $p = true;
                                    $ymbtfarmer = bcmul($value['area'], $farmerp, 3);
                                    $ymbtlease = bcmul($value['area'], $lesseep, 3);
                                }
                            }
                            $ymzongdi = $value['zongdi'];
//                            $ymbtfarmer = '100%';
//                            $ymbtlease = '0%';
                            break;
                        case 2:
                            if($value['area'])
                                $xm = $value['area'];
                            else {
                                $xm = 0;
                            }
                            break;
                        case 4:
                            if($value['area'])
                                $mls = $value['area'];
                            else
                                $mls = 0;
                            break;
                        case 14:
                            if($value['area'])
                                $zd = $value['area'];
                            break;
                        case 9:
                            if($value['area'])
                                $zd = $value['area'];
                            break;
                        case 8:
                            if($value['area'])
                                $by = $value['area'];
                            else
                                $by = 0;
                            break;
                        case 17:
                            if($value['area'])
                                $lm = $value['area'];
                            else
                                $lm = 0;
                            break;
                        case 16:
                            if($value['area'])
                                $other = $value['area'];
                            else
                                $other = 0;
                            break;
                    }
                }
//                var_dump($ddbtfarmer);
//                $contractareaSum += $farm['contractarea'];
//                $plantAllSum += $plantsum;

//                if($value['farms_id'] == 1262) {
//                    var_dump($farm['cardid']);
//                    var_dump($bank);exit;
//                }
                $result[] = [
                    'row' => $i,
                    'management_area' => ManagementArea::getAreanameOne($farm['management_area']),
                    'farmname' => $farm['farmname'],
                    'farmername' => $farm['farmername'],
                    'farmercardid' => $farm['cardid'],
                    'farmeraccountnumber' => $farmerbank['accountnumber'],
                    'farmertelephone' => $farm['telephone'],
                    'contractnumber' => $farm['contractnumber'],
                    'lease' => $farm['farmername'],
                    'cardid' => $farm['cardid'],
                    'accountnumber' => $farmerbank['accountnumber'],
                    'telephone' => $farm['telephone'],
                    'contractarea' => $farm['contractarea'],
                    'area' => sprintf("%.2f",$plantsum),
                    'ddarea' => $ddarea,
                    'ddzongdi' => $ddzongdi,
                    'ddbtfarmer' => $ddbtfarmer,
                    'ddbtlease' => $ddbtlease,
                    'ymarea' => $ymarea,
                    'ymzongdi' => $ymzongdi,
                    'ymbtfarmer' => $ymbtfarmer,
                    'ymbtlease' => $ymbtlease,
                    'xm' => $xm,
                    'mls' => $mls,
                    'zd' => $zd,
                    'by' => $by,
                    'lm' => $lm,
                    'other' => $other,
                    'verifydate' => $verifydate,
                    'content' => $content,
                ];
            }
            //获取种植者种植信息
            $leases = Lease::find()->where(['farms_id' => $farm['farms_id'], 'year' => User::getYear()])->all();

//            var_dump(count($leases));
            //如果有种植者信息那么执行以下操作
            if ($leases) {
                $l++;
                $farmsAllid[] = $farm['id'];


                foreach ($leases as $k => $item) {
                    $i++;
                    $ddarea = 0;
                    $ddzongdi = '';
                    $ddbtfarmer = 0;
                    $ddbtlease = 0;
                    $ymarea = 0;
                    $ymzongdi = '';
                    $ymbtfarmer = 0;
                    $ymbtlease = 0;
                    $xm = 0;
                    $mls = 0;
                    $zd = 0;
                    $by = 0;
                    $lm = 0;
                    $other = 0;
                    $verifydate = '';
                    $content = '';
                    $leasearea = 0;
//                    $leasearea = $item['lease_area'];
                    $planting = Plantingstructurecheck::find()->where(['lease_id' => $item['id'], 'year' => User::getYear()])->all();
                    if ($planting) {
                        foreach ($planting as $k=>$value) {
                            $sub = Subsidyratio::getSubsidyratio($value['plant_id'],$value['farms_id'],$value['lease_id']);
                            $farmerp = (float)$sub['farmer']/100;
                            $lesseep = (float)$sub['lessee']/100;
                            $verifydate = $value['verifydate'];
                            $content = $value['content'];
                            $leasearea += $value['area'];
                            switch ($value['plant_id']) {
                                case 6:
                                    $ddarea = $value['area'];
                                    if($value['lease_id'] == 0) {
                                        $ddbtfarmer = $value['area'];
                                        $ddbtlease = 0;
                                    } else {
                                        if (bccomp($farmerp, 1) == 0) {
                                            $p = false;
                                            $ddbtfarmer = bcmul($value['area'], $farmerp, 2);
                                            $ddbtlease = 0;
                                        }
                                        if (bccomp($lesseep, 1) == 0) {
                                            $p = false;
                                            $ddbtfarmer = 0;
                                            $ddbtlease = bcmul($value['area'], $lesseep, 2);
                                        }
//						if (bccomp($farmerp, 0) == 1 and bccomp($farmerp, 1) == -1) {
                                        if ($farmerp > 0 and $farmerp < 1) {
                                            $p = true;
                                            $ddbtfarmer = bcmul($value['area'], $farmerp, 3);
                                            $ddbtlease = bcmul($value['area'], $lesseep, 3);
                                        }
                                    }
                                    $ddzongdi = $value['zongdi'];
                                    break;
                                case 3:
                                    $ymarea = $value['area'];
                                    if($value['lease_id'] == 0) {
                                        $ymbtfarmer = $value['area'];
                                        $ymbtlease = 0;
                                    } else {
                                        if (bccomp($farmerp, 1) == 0) {
                                            $p = false;
                                            $ymbtfarmer = bcmul($value['area'], $farmerp, 2);
                                            $ymbtlease = 0;
                                        }
                                        if (bccomp($lesseep, 1) == 0) {
                                            $p = false;
                                            $ymbtfarmer = 0;
                                            $ymbtlease = bcmul($value['area'], $lesseep, 2);
                                        }
//						if (bccomp($farmerp, 0) == 1 and bccomp($farmerp, 1) == -1) {
                                        if($farmerp > 0 and $farmerp < 1) {
                                            $p = true;
                                            $ymbtfarmer = bcmul($value['area'], $farmerp, 3);
                                            $ymbtlease = bcmul($value['area'], $lesseep, 3);
                                        }
                                    }
                                    $ymzongdi = $value['zongdi'];
                                    break;
                                case 2:
                                    if($value['area'])
                                        $xm = $value['area'];
                                    else {
                                        $xm = 0;
                                    }
                                    break;
                                case 4:
                                    if($value['area'])
                                        $mls = $value['area'];
                                    else
                                        $mls = 0;
                                    break;
                                case 14:
                                    if($value['area'])
                                        $zd = $value['area'];
                                    break;
                                case 9:
                                    if($value['area'])
                                        $zd = $value['area'];

                                    break;
                                case 8:
                                    if($value['area'])
                                        $by = $value['area'];
                                    else
                                        $by = 0;
                                    break;
                                case 17:
                                    if($value['area'])
                                        $lm = $value['area'];
                                    else
                                        $lm = 0;
                                    break;
                                case 16:
                                    if($value['area'])
                                        $other = $value['area'];
                                    else
                                        $other = 0;
                                    break;
                            }
                        }
                    }
//                    var_dump($ddbtlease);
//                    $contractareaSum += $farm['contractarea'];
//                    $plantAllSum += $plantsum;
//                    if($plantsum) {

                        $bank = BankAccount::find()->where(['lease_id'=>$item['id'],'cardid'=>$item['lessee_cardid'],'state'=>1])->one();
                        $result[] = [
                            'row' => $i,
                            'management_area' => ManagementArea::getAreanameOne($farm['management_area']),
                            'farmname' => $farm['farmname'],
                            'farmername' => $farm['farmername'],
                            'farmercardid' => $farm['cardid'],
                            'farmeraccountnumber' => $farmerbank['accountnumber'],
                            'farmertelephone' => $farm['telephone'],
                            'contractnumber' => $farm['contractnumber'],
                            'lease' => $item['lessee'],
                            'cardid' => $item['lessee_cardid'],
                            'accountnumber' => $bank['accountnumber'],
                            'telephone' => $item['lessee_telephone'],
                            'contractarea' => $farm['contractarea'],
                            'area' => sprintf("%.2f", $leasearea),
                            'ddarea' => $ddarea,
                            'ddzongdi' => $ddzongdi,
                            'ddbtfarmer' => $ddbtfarmer,
                            'ddbtlease' => $ddbtlease,
                            'ymarea' => $ymarea,
                            'ymzongdi' => $ymzongdi,
                            'ymbtfarmer' => $ymbtfarmer,
                            'ymbtlease' => $ymbtlease,
                            'xm' => $xm,
                            'mls' => $mls,
                            'zd' => $zd,
                            'by' => $by,
                            'lm' => $lm,
                            'other' => $other,
                            'verifydate' => $verifydate,
                            'content' => $content,
                        ];
//                    }
                }
            }
            if(empty($planting) and empty($leases)) {
                $farmsAllid[] = $farm['farms_id'];
                $f++;
                $i++;
                $plantsum = 0;
                $result[] = [
                    'row' => $i,
                    'management_area' => ManagementArea::getAreanameOne($farm['management_area']),
                    'farmname' => $farm['farmname'],
                    'farmername' => $farm['farmername'],
                    'farmercardid' => $farm['cardid'],
                    'farmeraccountnumber' => $farmerbank['accountnumber'],
                    'farmertelephone' => $farm['telephone'],
                    'contractnumber' => $farm['contractnumber'],
                    'lease' => '',
                    'cardid' => '',
                    'accountnumber' => '',
                    'telephone' => '',
                    'contractarea' => '',
                    'area' => sprintf("%.2f",$plantsum),
                    'ddarea' => $ddarea,
                    'ddzongdi' => $ddzongdi,
                    'ddbtfarmer' => $ddbtfarmer,
                    'ddbtlease' => $ddbtlease,
                    'ymarea' => $ymarea,
                    'ymzongdi' => $ymzongdi,
                    'ymbtfarmer' => $ymbtfarmer,
                    'ymbtlease' => $ymbtlease,
                    'xm' => $xm,
                    'mls' => $mls,
                    'zd' => $zd,
                    'by' => $by,
                    'lm' => $lm,
                    'other' => $other,
                    'verifydate' => $verifydate,
                    'content' => $content,
                ];
            }
        }
//        var_dump($farmsAllid);
//        var_dump(array_unique($farmsAllid));exit;
//        $contractareaSum = Farms::find()->where(['id'=>array_unique($farmsAllid)])->sum("contractarea");
//        $plantAllSum = Plantingstructurecheck::find()->where(['farms_id'=>array_unique($farmsAllid)])->sum('area');
//        var_dump($result);exit;
//        exit;
        return $this->render('basedataverifylist', [
            'result' => $result,
            'areaname' => Farms::getManagementArea()['areaname'],
            'contractareaSum' => sprintf('%2.f',$sum),
            'testdata' => [$f,$l],
        ]);
    }

//    public function actionBasedataverifyplanlist()
//    {
//        return $this->render('basedataverifyplanlist');
//    }

    public function actionBasedataverifyplanlist($where)
    {
        Logs::writeLogs('种植结构汇总表');
        $f = 0;
        $l = 0;
        $whereArray = json_decode($where,true);
//        var_dump(whereHandle::toPlantWhere($whereArray));exit;
        $farmsWhere = whereHandle::toFarmsWhere($whereArray);
//        var_dump($farmsWhere);exit;
        $result = [];
        $management_area = Farms::getManagementArea()['id'];

        $farms = Plantingstructureyearfarmsidplan::find()->where($farmsWhere)->all();
        $sum = Plantingstructureyearfarmsidplan::find()->where($farmsWhere)->groupBy('farms_id')->sum('contractarea');
//        var_dump(Plantingstructureyearfarmsidplan::find()->where($farmsWhere)->count());
//        var_dump($sum);exit;
//        $plantAllSum = Plantingstructurecheck::find()->where(whereHandle::toPlantWhere($whereArray))->andWhere(['year'=>User::getYear()])->sum('area');
        $count = count($farms);
        $i = 0;
        $farmsAllid = [];
        foreach ($farms as $key => $farm) {

            $ddarea = 0;
            $ddzongdi = '';
            $ddbtfarmer = '';
            $ddbtlease = '';
            $ymarea = 0;
            $ymzongdi = '';
            $ymbtfarmer = '';
            $ymbtlease = '';
            $xm = 0;
            $mls = 0;
            $zd = 0;
            $by = 0;
            $lm = 0;
            $other = 0;
            $verifydate = '';
            $content = '';
            //获取法人种植信息
            $planting = Plantingstructure::find()->where(['farms_id' => $farm['farms_id'], 'lease_id' => 0, 'year' => User::getYear()])->all();
            //如果存在法人种植信息则执行以下操作
            if ($planting) {
                $farmsAllid[] = $farm['farms_id'];
                $f++;
                $i++;
                $plantsum = 0;
                foreach ($planting as $k => $value) {

                    $plantsum+=$value['area'];
//                    $verifydate = $value['verifydate'];
//                    $content = $value['content'];
                    switch ($value['plant_id']) {
                        case 6:
                            $ddarea = $value['area'];
                            $ddzongdi = $value['zongdi'];
                            $ddbtfarmer = '100%';
                            $ddbtlease = '0%';
                            break;
                        case 3:
                            $ymarea = $value['area'];
                            $ymzongdi = $value['zongdi'];
                            $ymbtfarmer = '100%';
                            $ymbtlease = '0%';
                            break;
                        case 2:
                            if($value['area'])
                                $xm = $value['area'];
                            else {
                                $xm = 0;
                            }
                            break;
                        case 4:
                            if($value['area'])
                                $mls = $value['area'];
                            else
                                $mls = 0;
                            break;
                        case 14:
                            if($value['area'])
                                $zd = $value['area'];
                            else {
                                $zd = 0;
                            }
                            break;
                        case 8:
                            if($value['area'])
                                $by = $value['area'];
                            else
                                $by = 0;
                            break;
                        case 17:
                            if($value['area'])
                                $lm = $value['area'];
                            else
                                $lm = 0;
                            break;
                        case 16:
                            if($value['area'])
                                $other = $value['area'];
                            else
                                $other = 0;
                            break;
                    }
                }
//                $contractareaSum += $farm['contractarea'];
//                $plantAllSum += $plantsum;
                $result[] = [
                    'row' => $i,
                    'management_area' => ManagementArea::getAreanameOne($farm['management_area']),
                    'farmname' => $farm['farmname'],
                    'farmername' => $farm['farmername'],
                    'farmercardid' => $farm['cardid'],
                    'farmertelephone' => $farm['telephone'],
                    'contractnumber' => $farm['contractnumber'],
                    'lease' => $farm['farmername'],
                    'cardid' => $farm['cardid'],
                    'telephone' => $farm['telephone'],
                    'contractarea' => $farm['contractarea'],
                    'area' => sprintf("%.2f",$plantsum),
                    'ddarea' => $ddarea,
                    'ddzongdi' => $ddzongdi,
                    'ddbtfarmer' => $ddbtfarmer,
                    'ddbtlease' => $ddbtlease,
                    'ymarea' => $ymarea,
                    'ymzongdi' => $ymzongdi,
                    'ymbtfarmer' => $ymbtfarmer,
                    'ymbtlease' => $ymbtlease,
                    'xm' => $xm,
                    'mls' => $mls,
                    'zd' => $zd,
                    'by' => $by,
                    'lm' => $lm,
                    'other' => $other,
//                    'verifydate' => $verifydate,
//                    'content' => $content,
                ];
            }
            //获取种植者种植信息
            $leases = Lease::find()->where(['farms_id' => $farm['farms_id'], 'year' => User::getYear()])->all();

//            var_dump(count($leases));
            //如果有种植者信息那么执行以下操作
            if ($leases) {
                $l++;
                $farmsAllid[] = $farm['id'];


                foreach ($leases as $k => $item) {
                    $i++;
                    $ddarea = 0;
                    $ddzongdi = '';
                    $ddbtfarmer = '100%';
                    $ddbtlease = '0%';
                    $ymarea = 0;
                    $ymzongdi = '';
                    $ymbtfarmer = '100%';
                    $ymbtlease = '0%';
                    $xm = 0;
                    $mls = 0;
                    $zd = 0;
                    $by = 0;
                    $lm = 0;
                    $other = 0;
                    $verifydate = '';
                    $content = '';
                    $leasearea = $item['lease_area'];
                    $planting = Plantingstructure::find()->where(['lease_id' => $item['id'], 'year' => User::getYear()])->all();
                    if ($planting) {
                        foreach ($planting as $k=>$value) {

//                            $verifydate = $value['verifydate'];
//                            $content = $value['content'];
                            switch ($value['plant_id']) {
                                case 6:
                                    $ddarea = $value['area'];
                                    $ddzongdi = $value['zongdi'];
                                    break;
                                case 3:
                                    $ymarea = $value['area'];
                                    $ymzongdi = $value['zongdi'];
                                    break;
                                case 2:
                                    if($value['area'])
                                        $xm = $value['area'];
                                    else {
                                        $xm = 0;
                                    }
                                    break;
                                case 4:
                                    if($value['area'])
                                        $mls = $value['area'];
                                    else
                                        $mls = 0;
                                    break;
                                case 14:
                                    if($value['area'])
                                        $zd = $value['area'];
                                    else {
                                        $zd = 0;
                                    }
                                    break;
                                case 8:
                                    if($value['area'])
                                        $by = $value['area'];
                                    else
                                        $by = 0;
                                    break;
                                case 17:
                                    if($value['area'])
                                        $lm = $value['area'];
                                    else
                                        $lm = 0;
                                    break;
                                case 16:
                                    if($value['area'])
                                        $other = $value['area'];
                                    else
                                        $other = 0;
                                    break;
                            }
                        }
                    }
//                    $contractareaSum += $farm['contractarea'];
//                    $plantAllSum += $plantsum;
//                    if($plantsum) {
                    $result[] = [
                        'row' => $i,
                        'management_area' => ManagementArea::getAreanameOne($farm['management_area']),
                        'farmname' => $farm['farmname'],
                        'farmername' => $farm['farmername'],
                        'farmercardid' => $farm['cardid'],
                        'farmertelephone' => $farm['telephone'],
                        'contractnumber' => $farm['contractnumber'],
                        'lease' => $item['lessee'],
                        'cardid' => $item['lessee_cardid'],
                        'telephone' => $item['lessee_telephone'],
                        'contractarea' => $farm['contractarea'],
                        'area' => sprintf("%.2f", $leasearea),
                        'ddarea' => $ddarea,
                        'ddzongdi' => $ddzongdi,
                        'ddbtfarmer' => $item['ddcj_farmer'],
                        'ddbtlease' => $item['ddcj_lessee'],
                        'ymarea' => $ymarea,
                        'ymzongdi' => $ymzongdi,
                        'ymbtfarmer' => $item['ymcj_farmer'],
                        'ymbtlease' => $item['ymcj_lessee'],
                        'xm' => $xm,
                        'mls' => $mls,
                        'zd' => $zd,
                        'by' => $by,
                        'lm' => $lm,
                        'other' => $other,
//                        'verifydate' => $verifydate,
//                        'content' => $content,
                    ];
//                    }
                }
            }
        }
//        var_dump($farmsAllid);
//        var_dump(array_unique($farmsAllid));exit;
//        $contractareaSum = Farms::find()->where(['id'=>array_unique($farmsAllid)])->sum("contractarea");
//        $plantAllSum = Plantingstructurecheck::find()->where(['farms_id'=>array_unique($farmsAllid)])->sum('area');
//        var_dump($result);exit;
        return $this->render('basedataverifyplanlist', [
            'result' => $result,
            'areaname' => Farms::getManagementArea()['areaname'],
            'contractareaSum' => sprintf('%2.f',$sum),
            'testdata' => [$f,$l],
        ]);
    }

    public function actionSavedata($value)
    {
        $errors = [];
        $data = json_decode($value);
        $farm = Farms::findOne($data->farms_id);
        $leasecount = Lease::find()->where(['farms_id' => $farm->id, 'year' => User::getYear()])->count();
        $areaArray = [];
        foreach ($data as $k=>$d) {
            switch ($k) {
                case 'lease':
                    $lessee = $d;
                    break;
                case 'cardid':
                    $cardid = $d;
                    if ($farm['cardid'] == $d) {
                        $isFarmer = 1;
                    } else {
                        $isFarmer = 0;
                    }
                    break;
                case 'telephone':
                    $telephone = $d;
                    break;
                case 'contractarea':
                    $contractarea = $d;
                    break;
                case 'ddarea':
                    $areaArray['大豆'] = $d;
                    break;
                case 'ddzongdi':
                    $ddzongdi = $d;
                    break;
                case 'ymarea':
                    $areaArray['玉米'] = $d;
                    break;
                case 'ymzongdi':
                    $ymzongdi = $d;
                    break;
                case 'xm':
                    $areaArray['小麦'] = $d;
                    break;
                case 'mls':
                    $areaArray['马铃薯'] = $d;
                    break;
                case 'zd':
                    $areaArray['杂豆'] = $d;
                    break;
                case 'by':
                    $areaArray['北药'] = $d;
                    break;
                case 'lm':
                    $areaArray['蓝莓'] = $d;
                    break;
                case 'other':
                    $areaArray['其它'] = $d;
                    break;
                case 'verifydate':
                    $verifydate = $d;
                    break;
                case 'content':
                    $content = $d;
                    break;
                case 'ddbtfarmer':
                    $ddbtfarmer = $d;
                    break;
                case 'ddbtlease':
                    $ddbtlease = $d;
                    break;
                case 'ymbtfarmer':
                    $ymbtfarmer = $d;
                    break;
                case 'ymbtlease':
                    $ymbtlease = $d;
                    break;
            }
        }
        $sum = array_sum($areaArray);
        $overarea = Plantingstructurecheck::find()->where(['farms_id'=>$farm->id,'year'=>User::getYear()])->sum('area');
        if(bccomp($overarea , $contractarea) == -1) {
            if($leasecount) {
                $nowarea = $overarea + $sum;
            } else {
                $nowarea = $sum;
            }
        } else {
            $nowarea = $sum;
        }

        if(bccomp($nowarea , $contractarea) == 1) {
            echo json_encode(['status'=>0,'msg'=>'对不起,输入的面积总和('.$nowarea.'='.$overarea.'+'.$sum.')已经超过合同面积('.$contractarea.'),将显示剩余面积。','lastarea'=>$contractarea - $overarea]);
            exit;
        }
        if(!$isFarmer) {

//            if($nowarea > $contractarea) {
//                echo json_encode(['status'=>0,'msg'=>'对不起,输入的面积总和('.$nowarea.')已经超过合同面积('.$contractarea.'),将显示剩余面积。','lastarea'=>$contractarea - $overarea]);
//                exit;
//            }
            $lease = Lease::find()->where(['farms_id'=>$farm->id,'year'=>User::getYear(),'lessee_cardid'=>$cardid])->one();
            if($lease) {
                $model = Lease::findOne($lease['id']);
                $model->update_at = (string)time();
                $model->lease_area = $sum;
                $model->lessee_telephone = $telephone;
                $model->ddcj_farmer = $ddbtfarmer;
                $model->ddcj_lessee = $ddbtlease;
                $model->ymcj_farmer = $ymbtfarmer;
                $model->ymcj_lessee = $ymbtlease;
                $model->save();
                Logs::writeLogs('更新租赁信息',$model);
//                $errors['leaseModel'] = $model->getErrors();
                foreach ($areaArray as $key => $area) {
                    $plant_id = Plant::find()->where(['typename'=>$key])->one()['id'];
                    if(!empty($areaArray[$key])) {
                        $planting = Plantingstructurecheck::find()->where(['farms_id'=>$farm->id,'year'=>User::getYear(),'lease_id'=>$model->id,'plant_id'=>$plant_id])->one();
                        if($planting) {
                            $plantModel = Plantingstructurecheck::findOne($planting['id']);
                            $plantModel->update_at = time();
                            $logstitle = '更新种植信息';
                        } else {
                            $plantModel = new Plantingstructurecheck();
                            $plantModel->create_at = time();
                            $plantModel->update_at = $plantModel->create_at;
                            $logstitle = '创建种植信息';
                        }
                        $plantModel->plant_id = $plant_id;
                        $plantModel->area = $area;
                        if($key == '大豆') {
                            $plantModel->zongdi = $ddzongdi;
                        }
                        if($key == '玉米') {
                            $plantModel->zongdi = $ymzongdi;
                        }
                        $plantModel->farms_id = $farm->id;
                        $plantModel->lease_id = $model->id;
                        $plantModel->create_at = time();
                        $plantModel->update_at = $plantModel->create_at;
                        $plantModel->management_area = $farm->management_area;
                        $plantModel->contractarea = $farm['contractarea'];
                        $plantModel->state = $farm['state'];
                        $plantModel->issame = $isFarmer;
                        $plantModel->year = User::getYear();
                        $plantModel->verifydate = $verifydate;
                        $plantModel->content = $content;
                        $plantModel->save();
                        Logs::writeLogs($logstitle,$plantModel);
//                        $errors['plantModel'] = $plantModel->getErrors();
                    }
                }
            } else {
                $model = new Lease();
                $model->farms_id = $farm->id;
                $model->create_at = (string)time();
                $model->update_at = $model->create_at;
                $model->year = User::getYear();
                $model->lessee = $lessee;
                $model->lease_area = $sum;
                $model->lessee_cardid = $cardid;
                $model->lessee_telephone = $telephone;
                $model->begindate = User::getYear() . '-01-01';
                $model->enddate = User::getYear() . '-12-31';
                $model->ddcj_farmer = $ddbtfarmer;
                $model->ddcj_lessee = $ddbtlease;
                $model->ymcj_farmer = $ymbtfarmer;
                $model->ymcj_lessee = $ymbtlease;
                $model->huinongascription = 
                $model->save();
                Logs::writeLogs('创建租赁信息',$model);
//                $overarea = Lease::scanOverArea($farm->id);
//                $nowarea = $overarea + $sum;
//                if($nowarea > $contractarea) {
//                    echo json_encode(['status'=>0,'msg'=>'对不起,输入的面积总和('.$nowarea.')已经超过合同面积('.$contractarea.'),将显示剩余面积。','lastarea'=>$contractarea - $overarea]);
//                    exit;
//                }
//                $errors['leaseModel'] = $model->getErrors();
                foreach ($areaArray as $key => $area) {
                    $plant_id = Plant::find()->where(['typename'=>$key])->one()['id'];
                    if(!empty($areaArray[$key])) {
                        $planting = Plantingstructurecheck::find()->where(['farms_id'=>$farm->id,'year'=>User::getYear(),'lease_id'=>$model->id,'plant_id'=>$plant_id])->one();
                        if($planting) {
                            $plantModel = Plantingstructurecheck::findOne($planting['id']);
                            $plantModel->update_at = time();
                            $logstitle = '更新种植信息';
                        } else {
                            $plantModel = new Plantingstructurecheck();
                            $plantModel->create_at = time();
                            $plantModel->update_at = $plantModel->create_at;
                            $logstitle = '创建种植信息';
                        }
                        $plantModel->plant_id = $plant_id;
                        $plantModel->area = $area;
                        if($key == '大豆') {
                            $plantModel->zongdi = $ddzongdi;
                        }
                        if($key == '玉米') {
                            $plantModel->zongdi = $ymzongdi;
                        }
                        $plantModel->farms_id = $farm->id;
                        $plantModel->lease_id = $model->id;
                        $plantModel->contractarea = $farm['contractarea'];
                        $plantModel->state = $farm['state'];
                        $plantModel->management_area = $farm->management_area;
                        $plantModel->issame = $isFarmer;
                        $plantModel->year = User::getYear();
                        $plantModel->verifydate = $verifydate;
                        $plantModel->content = $content;
                        $plantModel->save();
                        Logs::writeLogs($logstitle,$plantModel);
//                        $errors['plantModel'] = $plantModel->getErrors();
                    }
                }
            }

        } else {
//            $overarea = Lease::scanOverArea($farm->id);
//
//            $nowarea = $overarea + $sum;
//            if($nowarea > $contractarea) {
//                echo json_encode(['status'=>0,'msg'=>'对不起,输入的面积总和('.$nowarea.')已经超过合同面积('.$contractarea.'),将显示剩余面积。','lastarea'=>$contractarea - $overarea]);
//                exit;
//            }
            foreach ($areaArray as $key => $area) {
                $plant_id = Plant::find()->where(['typename'=>$key])->one()['id'];
                if(!empty($areaArray[$key])) {
                    $planting = Plantingstructurecheck::find()->where(['farms_id'=>$farm->id,'year'=>User::getYear(),'lease_id'=>0,'plant_id'=>$plant_id])->one();
                    if($planting) {
                        $plantModel = Plantingstructurecheck::findOne($planting['id']);
                        $plantModel->update_at = time();
                        $logstitle = '更新种植信息';
                    } else {
                        $plantModel = new Plantingstructurecheck();
                        $plantModel->create_at = time();
                        $plantModel->update_at = $plantModel->create_at;
                        $logstitle = '创建种植信息';
                    }
                    $plantModel->plant_id = $plant_id;
                    $plantModel->area = $area;
                    if($key == '大豆') {
                        $plantModel->zongdi = $ddzongdi;
                    }
                    if($key == '玉米') {
                        $plantModel->zongdi = $ymzongdi;
                    }
                    $plantModel->farms_id = $farm->id;
                    $plantModel->lease_id = 0;

                    $plantModel->management_area = $farm->management_area;
                    $plantModel->issame = $isFarmer;
                    $plantModel->year = User::getYear();
                    $plantModel->verifydate = $verifydate;
                    $plantModel->content = $content;
                    $plantModel->save();
                    Logs::writeLogs($logstitle,$plantModel);
//                    $errors['plantModel'] = $plantModel->getErrors();
                }
            }
        }
        echo json_encode(['status'=>1,'msg'=>'']);
    }

    public function actionIsfarmer($value)
    {
        $data = json_decode($value);
        $farm = Farms::findOne($data->farms_id);
        if($data->cardid == $farm['cardid']) {
            $result = true;
        } else {
            $result = false;
        }
        echo json_encode(['isFarmer'=>$result]);
    }

    public function actionBasedataverifydckindex()
    {
        Logs::writeLogs('种植结构录入界面');
        //农场查询
        $whereArray = Farms::getManagementArea()['id'];
        if(count($whereArray) == 7)
            $whereArray = null;
        else
            $whereArray = $whereArray[0];
        $searchModel = new PlantingstructureyearfarmsidSearch();

        $params = Yii::$app->request->queryParams;
// 		$params ['farmsSearch'] ['state'] = [1,2,3,4,5];
        // 管理区域是否是数组
        if (empty($params['PlantingstructureyearfarmsidSearch']['management_area'])) {
            $params ['PlantingstructureyearfarmsidSearch'] ['management_area'] = $whereArray;
        }
        $dataProvider = $searchModel->search ( $params );
//        var_dump($dataProvider->getModels());exit;
        //种植结构查询
        $plantsearchModel = new plantingstructurecheckSearch();
        $plantparams = Yii::$app->request->queryParams;
        if (empty($plantparams['plantingstructurecheckSearch']['management_area'])) {
            $plantparams ['plantingstructurecheckSearch'] ['management_area'] = $whereArray;
        }
        $plantparams['plantingstructurecheckSearch']['year'] = User::getYear();
        $plantdataProvider = $plantsearchModel->searchIndex ( $plantparams );
        if (is_array($plantsearchModel->management_area)) {
            $plantsearchModel->management_area = null;
        }
        $plantdataProvider = $plantsearchModel->search ( $plantparams );
//        $finishedRows = Plantingstructureyearfarmsid::find()->andFilterWhere($params)->count();
//exit;

//        Logs::writeLogs ( '基础数据核实' );
        return $this->render ( 'basedataverifydckindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'plantsearchModel' => $plantsearchModel,
            'plantdataProvider' => $plantdataProvider,
//            'finishedRows' => $finishedRows,
            'farmsRows' => Plantingstructureyearfarmsid::find()->where(['year'=>User::getYear()])->count(),
        ] );
    }

    public function actionBasedataverifydckplan()
    {
        Logs::writeLogs('种植结构录入界面');
        //农场查询
        $whereArray = Farms::getManagementArea()['id'];
        if(count($whereArray) == 7)
            $whereArray = null;
        else
            $whereArray = $whereArray[0];
        $searchModel = new PlantingstructureyearfarmsidplanSearch();

        $params = Yii::$app->request->queryParams;
// 		$params ['farmsSearch'] ['state'] = [1,2,3,4,5];
        // 管理区域是否是数组
        if (empty($params['PlantingstructureyearfarmsidplanSearch']['management_area'])) {
            $params ['PlantingstructureyearfarmsidplanSearch'] ['management_area'] = $whereArray;
        }
        $params ['PlantingstructureyearfarmsidplanSearch'] ['state'] = [1,2,3,4,5];
        $params ['PlantingstructureyearfarmsidplanSearch'] ['year'] = User::getYear();
        $dataProvider = $searchModel->search ( $params );
//        var_dump($dataProvider->getModels());exit;
        //种植结构查询
        $plantsearchModel = new plantingstructureSearch();
        $plantparams = Yii::$app->request->queryParams;
        if (empty($plantparams['plantingstructureSearch']['management_area'])) {
            $plantparams ['plantingstructureSearch'] ['management_area'] = $whereArray;
        }
        $plantparams['plantingstructureSearch']['year'] = User::getYear();
        $plantdataProvider = $plantsearchModel->searchIndex ( $plantparams );
        if (is_array($plantsearchModel->management_area)) {
            $plantsearchModel->management_area = null;
        }
        $plantdataProvider = $plantsearchModel->search ( $plantparams );
//        $finishedRows = Plantingstructureyearfarmsid::find()->andFilterWhere($params)->count();
//exit;

//        Logs::writeLogs ( '基础数据核实' );
        return $this->render ( 'basedataverifydckplan', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'plantsearchModel' => $plantsearchModel,
            'plantdataProvider' => $plantdataProvider,
//            'finishedRows' => $finishedRows,
            'farmsRows' => Plantingstructureyearfarmsid::find()->where(['year'=>User::getYear()])->count(),
        ] );
    }

    public function actionBasedataverifydckinput($farms_id)
    {
        $lease = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
        $farmname = Farms::findOne($farms_id)['farmname'];
        Logs::writeLogs($farmname.'的租赁及种植结构信息');
        return $this->renderajax('basedataverifydckinput', [
            'leases' => $lease,
            'noarea' => Lease::getNoArea($farms_id),
            'overarea' => Lease::getOverArea($farms_id),
        ]);
    }

    public function actionBasedataverifysavelease($value)
    {
        $savedata = [];
        $data = json_decode($value);

        foreach ($data as $val) {
            $savedata[$val->name] = $val->value;
        }
        $model = new Lease();
//        var_dump($savedata);exit;
        $model->farms_id = $savedata['farms_id'];
        $model->create_at = (string)time();
        $model->update_at = $model->create_at;
        $model->year = User::getYear();
        $model->lessee = $savedata['Lease[lessee]'];
        $model->lessee_cardid = $savedata['Lease[lessee_cardid]'];
        $model->lessee_telephone = $savedata['Lease[lessee_telephone]'];
        $model->address = $savedata['Lease[address]'];
        $model->lease_area = $savedata['Lease[lease_area]'];
        $model->begindate = $savedata['Lease[begindate]'];
        $model->enddate = $savedata['Lease[enddate]'];
        $model->otherassumpsit = $savedata['Lease[otherassumpsit]'];
        $state = $model->save();
        Logs::writeLogs('新增种植者',$model);
        echo json_encode(['state'=>$state,'farms_id'=>$savedata['farms_id']]);
    }

    public function actionBasedataverifydeletelease($lease_id)
    {
        $model = Lease::findOne($lease_id);
        $farms_id = $model->farms_id;
        $plantings = Plantingstructure::find()->where(['lease_id'=>$lease_id,'year'=>User::getYear()])->all();
        foreach ($plantings as $value) {
            $pmodel = Plantingstructurecheck::findOne($value['id']);
            Logs::writeLogs('删除种植者种植结构信息',$pmodel);
            $pmodel->delete();
        }
        if(empty($plantings)) {
            Plantingstructureyearfarmsidplan::newPlan($farms_id, 0);
        }
        $insuranceplan = Insuranceplan::find()->where(['farms_id'=>$farms_id,'lease_id'=>$lease_id,'year'=>User::getYear()])->one();
        if($insuranceplan) {
            $insurance = Insurance::findOne($insuranceplan->insurance_id);
            Logs::writeLogs('删除计划保险',$insuranceplan);
            Logs::writeLogs('删除保险任务',$insurance);
            $insurance->delete();
            $insuranceplan->delete();

        }
        Logs::writeLogs('删除种植者',$model);
        $delete = $model->delete();
        echo json_encode(['state'=>$delete,'farms_id'=>$farms_id]);
    }

    public function actionBasedataverifydeleteleasecontent($lease_id)
    {
        $delete = false;
        $model = Lease::findOne($lease_id);
        $farms_id = $model->farms_id;
        $plantings = Plantingstructure::find()->where(['lease_id'=>$lease_id,'year'=>User::getYear()])->all();
        foreach ($plantings as $value) {
            $pmodel = Plantingstructure::findOne($value['id']);
            Logs::writeLogs('删除种植者种植结构信息',$pmodel);
            $delete = $pmodel->delete();
        }
        $rows = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
        if(empty($rows)) {
            Plantingstructureyearfarmsidplan::newPlan($farms_id, 0);
        }
        $insuranceplan = Insuranceplan::find()->where(['farms_id'=>$farms_id,'lease_id'=>$lease_id,'year'=>User::getYear()])->one();
        if($insuranceplan) {
            $insurance = Insurance::findOne($insuranceplan->insurance_id);
            Logs::writeLogs('删除计划保险',$insuranceplan);
            Logs::writeLogs('删除保险任务',$insurance);
            $insurance->delete();
            $insuranceplan->delete();

        }
        echo json_encode(['state'=>$delete,'farms_id'=>$farms_id]);
    }

    public function actionBasedataverifydeletefarmer($farms_id)
    {
        $plantings = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'year'=>User::getYear()])->all();
        foreach ($plantings as $value) {
            $pmodel = Plantingstructure::findOne($value['id']);
            Logs::writeLogs('删除法人种植结构信息',$pmodel);
            $delete = $pmodel->delete();
        }
        Plantingstructureyearfarmsidplan::newPlan($farms_id,0);
        $insuranceplan = Insuranceplan::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'year'=>User::getYear()])->one();
        if($insuranceplan) {
            $insurance = Insurance::findOne($insuranceplan->insurance_id);
            Logs::writeLogs('删除计划保险',$insuranceplan);
            Logs::writeLogs('删除保险任务',$insurance);
            $insurance->delete();
            $insuranceplan->delete();

        }
        echo json_encode(['state'=>true,'farms_id'=>$farms_id]);
    }

    public function actionBasedataverifysaveindex($value,$farms_id)
    {
        $farm = Farms::findOne($farms_id);
        $farmerData = [];
        $leaseData = [];
        $data = json_decode($value);
//        var_dump($data);exit;

        foreach ($data as $val) {
            if(strpos($val->name,"-")) {
                $arr= explode('-',$val->name);
                $leaseData[$arr[1]][$arr[0]] = $val->value;
            } else {
                if($val->name == 'dd' or $val->name == 'ym' or $val->name == 'xm' or $val->name == 'mls' or $val->name == 'zd' or $val->name == 'by' or $val->name == 'lm' or $val->name == 'other' or $val->name == 'area' or $val->name == 'ddcj_farmer' or $val->name == 'ddcj_lessee' or $val->name == 'ymcj_farmer' or $val->name == 'ymcj_lessee' or $val->name == 'verify') {
                    $farmerData[$val->name] = $val->value;
                }
            }
        }
//
//        var_dump($farmerData);
//        echo '<br><br>';
//        var_dump($leaseData);exit;
        if(count($farmerData) > 1) {
            foreach ($farmerData as $key => $val) {
                $plant_id = 0;
                switch ($key) {
                    case 'dd':
                        $plant_id = Plant::find()->where(['typename'=>'大豆'])->one()['id'];
                        break;
                    case 'ym':
                        $plant_id = Plant::find()->where(['typename'=>'玉米'])->one()['id'];
                        break;
                    case 'xm':
                        $plant_id = Plant::find()->where(['typename'=>'小麦'])->one()['id'];
                        break;
                    case 'mls':
                        $plant_id = Plant::find()->where(['typename'=>'马铃薯'])->one()['id'];
                        break;
                    case 'zd':
                        $plant_id = Plant::find()->where(['typename'=>'杂豆'])->one()['id'];
                        break;
                    case 'by':
                        $plant_id = Plant::find()->where(['typename'=>'北药'])->one()['id'];
                        break;
                    case 'lm':
                        $plant_id = Plant::find()->where(['typename'=>'蓝莓'])->one()['id'];
                        break;
                    case 'other':
                        $plant_id = Plant::find()->where(['typename'=>'其它'])->one()['id'];
                        break;

                }
                if(isset($plant_id)) {
                    $planting = Plantingstructurecheck::find()->where(['lease_id' => 0,'farms_id'=>$farms_id, 'plant_id' => $plant_id, 'year' => User::getYear()])->one();
                    if ($planting) {
                        $model = Plantingstructurecheck::findOne($planting['id']);
                        $model->update_at = time();
                    } else {
                        $model = new Plantingstructurecheck();
                        $model->create_at = time();
                        $model->update_at = $model->create_at;
                    }
                    $model->plant_id = $plant_id;
                    $model->area = $val;
                    $model->farms_id = $farms_id;
                    $model->lease_id = 0;
                    $model->management_area = $farm->management_area;
                    $model->planter = 1;
//                    $model->contractarea = $farm['contractarea'];
                    $model->state = $farm['state'];
                    $model->year = User::getYear();
//                    $model->verifydate = $farmerData['verify'];
                    if ($val) {
                        $model->save();
                        Logs::writeLogs('种植结构法人种植信息更新',$model);
                    } else {
                        Logs::writeLogs('种植结构种植者种植信息删除',$model);
                        $model->delete();
                    }
                }
            }
        }
//        var_dump($leaseData);exit;
        if($leaseData) {

            foreach ($leaseData as $lease_id => $lease) {
                foreach ($lease as $key => $val) {
//                    var_dump($plant_id);
                    $plant_id = 0;
                    switch ($key) {
                        case 'leasedd':
                            if($val) {
                                $plant_id = Plant::find()->where(['typename' => '大豆'])->one()['id'];
                                $leaseModel = Lease::findOne($lease_id);
                                $leaseModel->ddcj_farmer = $lease['leaseddcj_farmer'];
                                $leaseModel->ddcj_lessee = $lease['leaseddcj_lessee'];
                                $leaseModel->save();
                                Logs::writeLogs('种植者大豆差价补贴占比',$leaseModel);
//                                var_dump($key);
//                                var_dump('leasedd');
                            }
                            break;
                        case 'leaseym':
                            if($val) {
                                $plant_id = Plant::find()->where(['typename' => '玉米'])->one()['id'];
                                $leaseModel = Lease::findOne($lease_id);
                                $leaseModel->ymcj_farmer = $lease['leaseymcj_farmer'];
                                $leaseModel->ymcj_lessee = $lease['leaseymcj_lessee'];
                                $leaseModel->save();
                                Logs::writeLogs('种植者玉米差价补贴占比',$leaseModel);
//                                var_dump($key);
//                                var_dump('leaseym');
                            }
                            break;
                        case 'leasexm':
                            if($val) {
                                $plant_id = Plant::find()->where(['typename' => '小麦'])->one()['id'];
//                                var_dump($key);
//                                var_dump('leasexm');
                            }

                            break;
                        case 'leasemls':
                            if($val) {
                                $plant_id = Plant::find()->where(['typename' => '马铃薯'])->one()['id'];
//                                var_dump($key);
//                                var_dump('leasemls');
                            }
                            break;
                        case 'leasezd':
                            if($val) {
                                $plant_id = Plant::find()->where(['typename' => '杂豆'])->one()['id'];
//                                var_dump($key);
//                                var_dump('leasezd');
                            }
                            break;
                        case 'leaseby':
                            if($val) {
                                $plant_id = Plant::find()->where(['typename' => '北药'])->one()['id'];
//                                var_dump($key);
//                                var_dump('leaseby');
                            }
                            break;
                        case 'leaselm':
                            if($val) {
                                $plant_id = Plant::find()->where(['typename' => '蓝莓'])->one()['id'];
//                                var_dump($key);
//                                var_dump('leaselm');
                            }
                            break;
                        case 'leaseother':
                            if($val) {
                                $plant_id = Plant::find()->where(['typename' => '其它'])->one()['id'];
//                                var_dump($key);
//                                var_dump('leaseother');
                            }
                            break;

                    }
//                    var_dump($key.'====='.$plant_id);
                    if($plant_id > 0) {
                        $planting = Plantingstructurecheck::find()->where(['lease_id' => $lease_id, 'plant_id' => $plant_id, 'year' => User::getYear()])->one();
                        if ($planting) {
                            $model = Plantingstructurecheck::findOne($planting['id']);
                            $model->update_at = time();
                        } else {
                            $model = new Plantingstructurecheck();
                            $model->create_at = time();
                            $model->update_at = $model->create_at;
                        }
                        $model->plant_id = $plant_id;
                        $model->area = $val;
                        $model->farms_id = $farms_id;
                        $model->lease_id = $lease_id;
                        $model->management_area = $farm->management_area;
                        $model->issame = 0;
                        $model->contractarea = $farm['contractarea'];
                        $model->state = $farm['state'];
                        $model->year = User::getYear();
                        $model->verifydate = $lease['verify'];
                        if ($val) {
                            $model->save();
                            Logs::writeLogs('种植结构种植者种植信息更新',$model);
                        } else {
                            Logs::writeLogs('种植结构种植者种植信息删除',$model);
                            $model->delete();
                        }
                    }
                }
            }
        }
        $areaSum = Plantingstructurecheck::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->sum('area');
        if(bccomp($farm->contractarea,sprintf('%.2f',$areaSum)) == 0) {
            $plantfarm = Plantingstructureyearfarmsid::find()->where(['farms_id'=>$farms_id])->one();
            $plantfarm->isfinished = 1;
            $plantfarm->save();
        } else {
            $plantfarm = Plantingstructureyearfarmsid::find()->where(['farms_id'=>$farms_id])->one();
            $plantfarm->isfinished = 0;
            $plantfarm->save();
        }
        echo json_encode(['state'=>1]);
    }

    public function actionBasedataverifycreatecheck()
    {
        $farms = Farms::find()->where(['state'=>[1,2,3,4,5]])->all();
        foreach ($farms as $farm) {
//            $model = Plantingstructureyearfarmsid::find()->where(['farms_id' => $farm['id'], 'year' => User::getYear()])->one();
//            if (!$model) {
//                $model = new Plantingstructureyearfarmsid();
//                $model->farms_id = $farm['id'];
//                $model->cardid = $farm['cardid'];
//                $model->telephone = $farm['telephone'];
//                $model->state = $farm['state'];
//                $model->contractarea = $farm['contractarea'];
//                $model->management_area = $farm['management_area'];
//                $model->contractnumber = $farm['contractnumber'];
//                $model->isfinished = 0;
//                $model->year = User::getYear();
//                $model->create_at = time();
//                $model->farmname = $farm['farmname'];
//                $model->farmername = $farm['farmername'];
//                $model->pinyin = $farm['pinyin'];
//                $model->farmerpinyin = $farm['farmerpinyin'];
//                $model->save();
//            } else {
                $model = new Plantingstructureyearfarmsid();
                $model->farms_id = $farm['id'];
                $model->cardid = $farm['cardid'];
                $model->telephone = $farm['telephone'];
                $model->state = $farm['state'];
                $model->contractarea = $farm['contractarea'];
                $model->management_area = $farm['management_area'];
                $model->contractnumber = $farm['contractnumber'];
                $model->isfinished = 0;
                $model->year = User::getYear();
                $model->create_at = time();
                $model->farmname = $farm['farmname'];
                $model->farmername = $farm['farmername'];
                $model->pinyin = $farm['pinyin'];
                $model->farmerpinyin = $farm['farmerpinyin'];
                $model->save();
//            }
        }
    }

    public function actionGetlistdata()
    {
        $result = [];
        $management_area = Farms::getManagementArea()['id'];

        $farms = Plantingstructureyearfarmsid::find()->where(['management_area' => $management_area, 'state' => [1, 2, 3, 4, 5],'year'=>User::getYear()])->all();
        $i = 0;
        foreach ($farms as $key => $farm) {

            $ddarea = 0;
            $ddzongdi = '';
            $ddbtfarmer = '';
            $ddbtlease = '';
            $ymarea = 0;
            $ymzongdi = '';
            $ymbtfarmer = '';
            $ymbtlease = '';
            $xm = 0;
            $mls = 0;
            $zd = 0;
            $by = 0;
            $lm = 0;
            $other = 0;
            $verifydate = '';
            $content = '';
            $planting = Plantingstructurecheck::find()->where(['farms_id' => $farm['farms_id'], 'lease_id' => 0, 'year' => User::getYear()])->all();
            if ($planting) {
                $i++;
                foreach ($planting as $k => $value) {
                    $verifydate = $value['verifydate'];
                    $content = $value['content'];
                    switch ($value['plant_id']) {
                        case 6:
                            $ddarea = $value['area'];
                            $ddzongdi = $value['zongdi'];
                            $ddbtfarmer = '100%';
                            $ddbtlease = '0%';
                            break;
                        case 3:
                            $ymarea = $value['area'];
                            $ymzongdi = $value['zongdi'];
                            $ymbtfarmer = '100%';
                            $ymbtlease = '0%';
                            break;
                        case 2:
                            $xm = $value['area'];
                            break;
                        case 4:
                            $mls = $value['area'];
                            break;
                        case 14:
                            $zd = $value['area'];
                            break;
                        case 8:
                            $by = $value['area'];
                            break;
                        case 17:
                            $lm = $value['area'];
                            break;
                        case 16:
                            $other = $value['area'];
                            break;
                    }
                }

                $result[] = [
                    'row' => $i,
                    'management_area' => ManagementArea::getAreanameOne($farm['management_area']),
                    'farmname' => $farm['farmname'],
                    'farmername' => $farm['farmername'],
                    'farmercardid' => $farm['cardid'],
                    'farmertelephone' => $farm['telephone'],
                    'contractnumber' => $farm['contractnumber'],
                    'lease' => $farm['farmername'],
                    'cardid' => $farm['cardid'],
                    'telephone' => $farm['telephone'],
                    'contractarea' => $farm['contractarea'],
                    'ddarea' => $ddarea,
                    'ddzongdi' => $ddzongdi,
                    'ddbtfarmer' => $ddbtfarmer,
                    'ddbtlease' => $ddbtlease,
                    'ymarea' => $ymarea,
                    'ymzongdi' => $ymzongdi,
                    'ymbtfarmer' => $ymbtfarmer,
                    'ymbtlease' => $ymbtlease,
                    'xm' => $xm,
                    'mls' => $mls,
                    'zd' => $zd,
                    'by' => $by,
                    'verifydate' => $verifydate,
                    'content' => $content,
                ];
            }
            $leases = Lease::find()->where(['farms_id' => $farm['farms_id'], 'year' => User::getYear()])->all();

            if ($leases) {

                $ddarea = 0;
                $ddzongdi = '';
                $ddbtfarmer = '100%';
                $ddbtlease = '0%';
                $ymarea = 0;
                $ymzongdi = '';
                $ymbtfarmer = '100%';
                $ymbtlease = '0%';
                $xm = 0;
                $mls = 0;
                $zd = 0;
                $by = 0;
                $lm = 0;
                $other = 0;
                $verifydate = '';
                $content = '';
                foreach ($leases as $k => $item) {
                    $i++;
                    $planting = Plantingstructurecheck::find()->where(['lease_id' => $item['id'], 'year' => User::getYear()])->all();
                    if ($planting) {
                        foreach ($planting as $k=>$value) {
                            $verifydate = $value['verifydate'];
                            $content = $value['content'];
                            switch ($value['plant_id']) {
                                case 6:
                                    $ddarea = $value['area'];
                                    $ddzongdi = $value['zongdi'];
                                    break;
                                case 3:
                                    $ymarea = $value['area'];
                                    $ymzongdi = $value['zongdi'];
                                    break;
                                case 2:
                                    $xm = $value['area'];
                                    break;
                                case 4:
                                    $mls = $value['area'];
                                    break;
                                case 14:

                                    $zd += $value['area'];
                                case 9:
                                    $zd += $value['area'];
                                    break;
                                case 8:
                                    $by = $value['area'];
                                    break;
                                case 17:
                                    $lm = $value['area'];
                                    break;
                                case 16:
                                    $other = $value['area'];
                                    break;
                            }
                        }
                    }
        //                    $leaserow = $farmrow . '-' . $k + 1;
                    $result[] = [
                        'row' => $i,
                        'management_area' => ManagementArea::getAreanameOne($farm['management_area']),
                        'farmname' => $farm['farmname'],
                        'farmername' => $farm['farmername'],
                        'farmercardid' => $farm['cardid'],
                        'farmertelephone' => $farm['telephone'],
                        'contractnumber' => $farm['contractnumber'],
                        'lease' => $item['lessee'],
                        'cardid' => $item['lessee_cardid'],
                        'telephone' => $item['lessee_telephone'],
                        'contractarea' => $farm['contractarea'],
                        'ddarea' => $ddarea,
                        'ddzongdi' => $ddzongdi,
                        'ddbtfarmer' => $item['ddcj_farmer'],
                        'ddbtlease' => $item['ddcj_lessee'],
                        'ymarea' => $ymarea,
                        'ymzongdi'=>$ymzongdi,
                        'ymbtfarmer' => $item['ymcj_farmer'],
                        'ymbtlease' => $item['ymcj_lessee'],
                        'xm' => $xm,
                        'mls' => $mls,
                        'zd' => $zd,
                        'by' => $by,
                        'lm' => $lm,
                        'other' => $other,
                        'verifydate' => $verifydate,
                        'content' => $content,
                    ];
                }
            }
        }
        echo json_encode($result);
    }
}
