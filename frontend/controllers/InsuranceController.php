<?php

namespace frontend\controllers;

<<<<<<< HEAD
use app\models\Farms;
use app\models\Insurancedck;
use app\models\Insurancetype;
use app\models\Plant;
use Composer\IO\NullIO;
use app\models\Plantingstructure;
use console\models\Lease;
use frontend\helpers\Pinyin;
use frontend\models\farmsSearch;
use frontend\models\insurancehistorySearch;
use frontend\models\plantingstructureSearch;
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
use Yii;
use app\models\Insurance;
use frontend\models\insuranceSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
<<<<<<< HEAD
use app\models\User;
use app\models\ManagementArea;
use app\models\Insurancecompany;
use app\models\Insurancehistory;
use app\models\Insurancemodfit;
use app\models\Logs;
use app\models\Insuranceplan;
use frontend\models\insuranceplanSearch;
use \PHPExcel_IOFactory;
use yii\web\UploadedFile;
use app\models\UploadForm;
=======

>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
/**
 * InsuranceController implements the CRUD actions for Insurance model.
 */
class InsuranceController extends Controller
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
<<<<<<< HEAD
    public function beforeAction($action)
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        } else {
            return true;
        }
    }
=======

>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
    /**
     * Lists all Insurance models.
     * @return mixed
     */
    public function actionInsuranceindex($farms_id)
    {
<<<<<<< HEAD
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $farm = Farms::findOne($farms_id);
        $isShow = true;
        $searchModel = new insuranceSearch();
        $params = Yii::$app->request->queryParams;
        
        $params['insuranceSearch']['farms_id'] = $farms_id;
        $params['insuranceSearch']['year'] = User::getYear();
        $dataProvider = $searchModel->search($params);
        $insuranceArea = sprintf("%.2f",Insurance::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->sum('insuredarea'));
        if(bccomp($farm->contractarea,$insuranceArea) == 0) {
            $isShow = false;
        }
        Logs::writeLog ( User::getYear().'年种植业保险申请列表' );
=======
        $searchModel = new insuranceSearch();
        $params = Yii::$app->request->queryParams;
        $params['insuranceSearch']['farms_id'] = $farms_id;
        $dataProvider = $searchModel->search($params);

>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
        return $this->render('insuranceindex', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'farms_id' => $farms_id,
<<<<<<< HEAD
            'isShow' => $isShow,
        ]);
    }

    public function actionInsuranceprintlist()
    {
        $searchModel = new insuranceSearch();
        $params = Yii::$app->request->queryParams;

        $params['insuranceSearch']['management_area'] = Farms::getManagementArea()['id'];
        $params['insuranceSearch']['year'] = User::getYear();
        $params['insuranceSearch']['fwdtstate'] = 1;
        $params['insuranceSearch']['state'] = 0;
        $dataProvider = $searchModel->search($params);
        Logs::writeLog ( User::getYear().'年种植业保险打印列表' );
        return $this->render('insuranceprintlist', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionXlsimport()
    {
        set_time_limit(0);
        $model = new UploadForm();
        //echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";echo "<br>";

        if (Yii::$app->request->isPost) {
            $model->file = UploadedFile::getInstance($model, 'file');

            if ($model->file && $model->validate()) {
//                var_dump($model);exit;
                $xlsName = time() . rand(100, 999);
                $model->file->name = $xlsName;
                $model->file->saveAs('uploads/' . $model->file->name . '.' . $model->file->extension);
                $path = 'uploads/' . $model->file->name . '.' . $model->file->extension;
                $loadxls = \PHPExcel_IOFactory::load($path);
                $no = [];
//                var_dump($loadxls);exit;
                for ($i = 2; $i <= $loadxls->getActiveSheet()->getHighestRow(); $i++) {
                    //echo $loadxls->getActiveSheet()->getCell('A'.$i)->getValue()."<br>";
                    $farmname = $loadxls->getActiveSheet()->getCell('B' . $i)->getValue();
                    $farmername = $loadxls->getActiveSheet()->getCell('C' . $i)->getValue();
                    $contractarea = $loadxls->getActiveSheet()->getCell('E' . $i)->getValue();
                    $policyholder = $loadxls->getActiveSheet()->getCell('D' . $i)->getValue();
//                    var_dump($farmname);var_dump($farmername);var_dump($contractnumber);
                    $farm = Farms::find()->where(['farmname' => $farmname, 'farmername' => $farmername, 'contractarea' => $contractarea])->one();
                    if (empty($farm)) {
                        $no[] = $loadxls->getActiveSheet()->getCell('A' . $i)->getValue();
                    }
//                    if(count($farm)>1) {
//                        var_dump($farm);
//                    }
//                    var_dump($farm);
                    $modelInsurance = new Insurance();
                    $modelInsurance->farms_id = $farm['id'];
                    $modelInsurance->create_at = time();
                    $modelInsurance->update_at = $modelInsurance->create_at;
                    $modelInsurance->management_area = $farm['management_area'];
                    $modelInsurance->year = '2018';
                    if (empty($policyholder)) {
                        $modelInsurance->policyholder = $farm['farmername'];
                    } else {
                        $modelInsurance->policyholder = $policyholder;
                    }
                    $modelInsurance->cardid = $farm['cardid'];
                    $modelInsurance->telephone = $farm['telephone'];
                    $modelInsurance->wheat = $loadxls->getActiveSheet()->getCell('G' . $i)->getValue();
                    $modelInsurance->insuredwheat = $loadxls->getActiveSheet()->getCell('G' . $i)->getValue();
                    $modelInsurance->soybean = $loadxls->getActiveSheet()->getCell('H' . $i)->getValue();
                    $modelInsurance->insurancesoybean = $loadxls->getActiveSheet()->getCell('H' . $i)->getValue();
                    $modelInsurance->other = $loadxls->getActiveSheet()->getCell('I' . $i)->getValue();
                    $modelInsurance->insuranceother = $loadxls->getActiveSheet()->getCell('I' . $i)->getValue();
                    $modelInsurance->company_id = 4;
                    $modelInsurance->farmername = $farm['farmername'];
                    $modelInsurance->state = 1;
                    $modelInsurance->fwdtstate = 1;
                    if ($modelInsurance->farmername == $modelInsurance->policyholder) {
                        $modelInsurance->nameissame = 1;
                        $modelInsurance->lease_id = 0;
                    } else {
                        $modelInsurance->nameissame = 0;
                    }
                    $modelInsurance->isselfselect = 1;
                    $modelInsurance->issame = 1;
                    $modelInsurance->farmstate = $farm['state'];
                    $modelInsurance->contractarea = $farm['contractarea'];
                    $modelInsurance->farmerpinyin = $farm['farmerpinyin'];
                    $modelInsurance->policyholderpinyin = Pinyin::encode($modelInsurance->policyholder);
                    $modelInsurance->isbxsame = 1;
                    $modelInsurance->statecontent = '补录';
                    $modelInsurance->insured = 99;
//                    $loanModel->serialnumber = $loadxls->getActiveSheet()->getCell('A'.$i)->getValue();
//
                    $modelInsurance->save();
                    $new = $modelInsurance->attributes;
                    Logs::writeLogs('xls批量保险信息', $modelInsurance);
                    //print_r($parchmodel->getErrors());
                }
            }
//            exit;
        }

        return $this->render('xlsimport', [
            'model' => $model,
        ]);
    }

        public function actionInsurancehistory()
    {
        $searchModel = new farmsSearch();
        $params = Yii::$app->request->queryParams;

//        $params['insuranceSearch'][''] = $farms_id;
        $dataProvider = $searchModel->search($params);

        $historyModel = new insurancehistorySearch();
        $historyparams = Yii::$app->request->queryParams;
        $historyData = $historyModel->search($historyparams);
        Logs::writeLog ( '查看种植业保险历史列表' );
        return $this->render('insurancehistory', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'historyModel' => $historyModel,
            'historyData' => $historyData,
//            'farms_id' => $farms_id,
        ]);
    }

    public function actionInsuranceinfo()
    {
        if(Yii::$app->user->isGuest) {
            return $this->redirect(['site/login']);
        }
        $searchModel = new insuranceSearch();
        $params = Yii::$app->request->queryParams;
        $params['insuranceSearch']['state'] = 1;
        $dataProvider = $searchModel->searchIndex ($params);

        $plantSearch = new plantingstructureSearch();
        $paramsplant = Yii::$app->request->queryParams;
        $paramsplant['plantingstructureSearch']['isinsurance'] = 1;
        $paramsplant['plantingstructureSearch']['year'] = User::getYear();
        $dataProviderplan = $plantSearch->searchIndex ($paramsplant);
        Logs::writeLogs('首页-保险业务');
        return $this->render('insuranceinfo',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchPlan' => $plantSearch,
            'dataProviderplan' => $dataProviderplan,
        ]);
    }
    public function actionInsurancefwdt()
    {
        $searchModel = new insuranceSearch();
        $params = Yii::$app->request->queryParams;
        $params['insuranceSearch']['fwdtstate'] = 2;
        $dataProvider = $searchModel->searchIndex($params);
        Logs::writeLogs('服务大厅办理保险业务列表');
        return $this->render('insurancefwdt', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

	public function actionInsurancefwdthd()
    {
        $searchModel = new insuranceSearch();
        $params = Yii::$app->request->queryParams;
        
        $params['insuranceSearch']['fwdtstate'] = 1;
        $params['insuranceSearch']['state'] = 1;
        $dataProvider = $searchModel->searchindex2($params);
        Logs::writeLogs('服务大厅保险业务核对列表');
//        var_dump($dataProvider->query->where);exit;
        return $this->render('insurancefwdthd', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'params' => $params,
        ]);
    }
    
    public function actionInsurancecheckup()
    {
        $searchModel = new insuranceSearch();
//         $management_area = Farms::getManagementArea()['id'];
//         var_dump($management_area);exit;
        $params = Yii::$app->request->queryParams;
//        	$params['insuranceSearch']['management_area'] = $management_area;
        $params['insuranceSearch']['fwdtstate'] = 1;
//         $params['insuranceSearch']['state'] = 1;
//         $params['insuranceSearch']['select'] = 'finished';
        $dataProvider = $searchModel->searchIndex($params);
        Logs::writeLog ( '种植业保险服务大厅审核列表' );
        return $this->render('insurancecheckup', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    
    public function actionInsuranceprintnull()
    {
    	return $this->render('insuranceprintnull');
    }
    
    public function actionInsurancefwdtcheckup($id,$page=NULL)
    {
        $model = $this->findModel($id);
        $farm = Farms::find()->where(['id'=>$model->farms_id])->one();
        if ($model->load(Yii::$app->request->post())) {
            $model->update_at = time();
            $model->halltime = time();
            $model->state = 0;
            $model->fwdtstate = 1;
            $model->save();
            Logs::writeLogs('地产科发送服务大厅审核',$model);
            if($page)
            	return $this->redirect(['insurancefwdt','page'=>$page]);
            else
            	return $this->redirect(['insurancefwdt']); 
        }
        return $this->render('insurancefwdtcheckup',[
        'model'=>$model,
        'farm' => $farm,
    ]);

    }
=======
        ]);
    }
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
	//农业保险审核
    public function actionInsurancereview($farms_id)
    {
    	$searchModel = new insuranceSearch();
    	$params = Yii::$app->request->queryParams;
    	$params['insuranceSearch']['farms_id'] = $farms_id;
    	$dataProvider = $searchModel->search($params);
<<<<<<< HEAD
        Logs::writeLog('地产科查看农场保险情况',$farms_id);
=======
    
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
    	return $this->render('insuranceindex', [
    			'searchModel' => $searchModel,
    			'dataProvider' => $dataProvider,
    			'farms_id' => $farms_id,
    	]);
    }
    
    /**
     * Displays a single Insurance model.
     * @param integer $id
     * @return mixed
     */
    public function actionInsuranceview($id)
    {
<<<<<<< HEAD
        $model = $this->findModel($id);
        $farm = Farms::find()->where(['id'=>$model->farms_id])->one();
        Logs::writeLogs('查看保险信息',$model);
        return $this->render('insuranceview',[
            'model'=>$model,
            'farm' => $farm,
        ]);
    }

    public function actionInsurancecancel($id,$content) {
        $model = $this->findModel($id);
        $model->state = -1;
        $model->statecontent = $content;
        $model->save();
        Logs::writeLogs('撤消一笔保险业务',$model);
    }
    
    public function actionInsuranceissamebx($id) {
    	$model = $this->findModel($id);
    	$model->isbxsame = 1;
    	$model->save();    
    }
=======
        return $this->render('insuranceview', [
            'model' => $this->findModel($id),
        ]);
    }

>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
    /**
     * Creates a new Insurance model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
<<<<<<< HEAD

    public function actionInsurancecreate2($farms_id)
    {
        $model = new Insurance();

        if ($model->load(Yii::$app->request->post())) {
        	$farm = Farms::find()->where(['id'=>$farms_id])->one();
            $insucance = Insurance::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->sum('insuredarea');
            $model->management_area = $farm['management_area'];
            $model->farms_id = $farms_id;
            $model->farmername = $farm['farmername'];
            $model->farmerpinyin = Pinyin::encode($model->farmername);
            $model->policyholderpinyin = Pinyin::encode($model->policyholder);
            if($model->farmername == $model->policyholder) {
            	$model->nameissame = 1;
            } else 
            	$model->nameissame = 0;
            $model->create_at = time();
            $model->update_at = $model->create_at;
            $model->year = date('Y');
            $model->managemanttime = $model->create_at;
            $model->state = 0;
            $model->fwdtstate = 2;
            $model->contractarea = $farm['contractarea'];
            if(bccomp($farm['contractarea'] , $insucance,2) == 0) {
                $model->iscontractarea = 1;
            } else {
                $model->iscontractarea = 0;
            }
            $model->save();
            Logs::writeLogs('新增保险业务',$model);
            $dckModel = new Insurancedck();
            $dckModel->farms_id = $model->farms_id;
            $dckModel->management_area = $model->management_area;
            $dckModel->insurance_id = $model->id;
            $dckModel->isbank = Yii::$app->request->post('isbank');
            $dckModel->iscompany = Yii::$app->request->post('iscompany');
            $dckModel->iscontract = Yii::$app->request->post('iscontract');
            $dckModel->isoneself = Yii::$app->request->post('isoneself');
            $dckModel->islease = Yii::$app->request->post('islease');
            if(isset($_POST['iswt']))
                $dckModel->iswt = Yii::$app->request->post('iswt');
            $dckModel->save();
            Logs::writeLogs('地产科保险业务审查情况',$dckModel);
            return $this->redirect(['insuranceindex', 'farms_id' => $model->farms_id]);
        } else {
            return $this->render('insurancecreate', [
                'model' => $model,
            	'farms_id' => $farms_id,
            ]);
        }
    }

    //由法人全保,获取所有种植数据
    public function actionGetall($farms_id)
    {
        $farm = Farms::findOne($farms_id);
        $area = 0;
        $insurancetype = Insurancetype::find()->all();
        foreach ($insurancetype as $value) {
            $$value['pinyin'] = 0;
        }
        foreach ($insurancetype as $value) {
            $$value['pinyin'] = Plantingstructure::find()->where(['plant_id'=>$value['plant_id'],'farms_id'=>$farms_id,'year'=>User::getYear()])->one()['area'];
            $area += $$value['pinyin'];
            $modeltArea[$value['pinyin']] = ['name'=>Plant::find()->where(['id'=>$value['plant_id']])->one()['typename'],'value'=>$$value['pinyin'],'pinyin'=>$value['pinyin']];
        }
        $allArea = Plantingstructure::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->sum('area');
//        var_dump($allArea);
        $otherArea = abs(sprintf("%.2f",$allArea - $area));
//        var_dump($otherArea);
        $modeltArea['other'] = ['name'=>'其他','value'=>$otherArea,'pinyin'=>'other'];
        $insuredarea = sprintf('%.2f',$area);
        echo json_encode(['lessee'=>$farm->farmername,'cardid'=>$farm->cardid,'telephone'=>$farm->telephone,'plantArea'=>$modeltArea ,'insuredarea'=>$insuredarea]);
    }

    public function actionInsurancesix($farms_id)
    {
        $people = [];
        $isShowAll = true;
        $leaseID = [];
        $area = 0;
        $insurancetype = Insurancetype::find()->all();
        foreach ($insurancetype as $value) {
            $$value['pinyin'] = 0;
            $row = $$value['pinyin'].'row';
            $$row = 0;
        }
        $model = new Insuranceplan();
        $farm = Farms::findOne($farms_id);
        $leases = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();
        $leaseSum = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->sum('lease_area');
        $insured_farmer = Insuranceplan::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'year'=>User::getYear()])->one();
        $insured_lessee = [];
        foreach ($leases as $lease) {
            $leaseInsured = Insuranceplan::find()->where(['farms_id'=>$farms_id,'lease_id'=>$lease['id'],'year'=>User::getYear()])->one();
            if(empty($leaseInsured)) {
                $insured_lessee[] = ['id'=>$lease['id'],'lessee'=>$lease['lessee'],'cardid'=>$lease['lessee_cardid'],'telephone'=>$lease['lessee_telephone']];
            }

        }
        $modeltArea = [];
        $insuredarea = 0;
        $farmerZZ = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'year'=>User::getYear()])->count();
        if(empty($insured_farmer)) {
            if(bccomp($farm['contractarea'],$leaseSum) == 1) {
                $people[0] = $farm->farmername;
            }
                if ($farmerZZ) {
                    $modeltArea = [];
                    $isFarmer = true;
                    foreach ($insurancetype as $value) {
                        $$value['pinyin'] = Plantingstructure::find()->where(['plant_id' => $value['plant_id'], 'farms_id' => $farms_id, 'lease_id' => 0, 'year' => User::getYear()])->one()['area'];
                        $area += $$value['pinyin'];
                        $modeltArea[$value['pinyin']] = ['name' => Plant::find()->where(['id' => $value['plant_id']])->one()['typename'], 'value' => $$value['pinyin'], 'pinyin' => $value['pinyin']];
                    }
                    $all = Plantingstructure::find()->where(['farms_id' => $farms_id, 'lease_id' => 0, 'year' => User::getYear()])->sum('area');
                    $other = abs(sprintf('%.2f', $all - $area));
                    $modeltArea['other'] = ['name' => '其他', 'value' => $other, 'pinyin' => 'other'];
                    $insuredarea = sprintf('%.2f', $area);
                    $model->cardid = $farm->cardid;
                    $model->telephone = $farm->telephone;
                    foreach ($insured_lessee as $lessee) {
                        $people[$lessee['id']] = $lessee['lessee'];
                    }
//                var_dump($modeltArea);exit;
                } else {
                    if ($insured_lessee) {
                        $modeltArea = [];

                        foreach ($insured_lessee as $lessee) {
                            $allrow = 0;
                            foreach ($insurancetype as $value) {
                                $allrow += Plantingstructure::find()->where(['plant_id' => $value['plant_id'], 'farms_id' => $farms_id, 'lease_id' => $lessee['id'], 'year' => User::getYear()])->count();
                            }
                            $people[$lessee['id']] = $lessee['lessee'];
                            if ($allrow > 0) {
                                $leaseInfo[] = ['id' => $lessee['id'], 'cardid' => $lessee['cardid'], 'telephone' => $lessee['telephone']];
                            }
                        }
                        foreach ($insurancetype as $value) {
                            $$value['pinyin'] = Plantingstructure::find()->where(['plant_id' => $value['plant_id'], 'farms_id' => $farms_id, 'lease_id' => $insured_lessee[0]['id'], 'year' => User::getYear()])->one()['area'];
                            $area += $$value['pinyin'];
                            $modeltArea[$value['pinyin']] = ['name' => Plant::find()->where(['id' => $value['plant_id']])->one()['typename'], 'value' => $$value['pinyin'], 'pinyin' => $value['pinyin']];
                        }
                        $all = Plantingstructure::find()->where(['farms_id' => $farms_id, 'lease_id' => $insured_lessee[0]['id'], 'year' => User::getYear()])->sum('area');
                        $other = abs(sprintf('%.2f', $all - $area));
                        $modeltArea['other'] = ['name' => '其他', 'value' => $other, 'pinyin' => 'other'];
                        $insuredarea = sprintf('%.2f', $area);
                        $model->cardid = $insured_lessee[0]['cardid'];
                        $model->telephone = $insured_lessee[0]['telephone'];
//                }
                    }
                }
//            }
        } else {
//            var_dump($insured_lessee);exit;
            if($insured_lessee) {
                $modeltArea = [];
//                var_dump($insured_lessee);
                foreach ($insured_lessee as $lessee) {
                    $allrow = 0;
                    foreach ($insurancetype as $value) {
                        $allrow += Plantingstructure::find()->where(['plant_id'=>$value['plant_id'],'farms_id'=>$farms_id,'lease_id'=>$lessee['id'],'year'=>User::getYear()])->count();
                    }
//                    if($allrow > 0) {
                        $people[$lessee['id']] = $lessee['lessee'];
                        $leaseInfo[] = ['id'=>$lessee['id'],'cardid'=>$lessee['cardid'],'telephone'=>$lessee['telephone']];
//                    }
                }
//                var_dump($people);exit;
                foreach ($insurancetype as $value) {
                    $$value['pinyin'] = Plantingstructure::find()->where(['plant_id'=>$value['plant_id'],'farms_id'=>$farms_id,'lease_id'=>$insured_lessee[0]['id'],'year'=>User::getYear()])->one()['area'];
                    $area += $$value['pinyin'];
                    $modeltArea[$value['pinyin']] = ['name'=>Plant::find()->where(['id'=>$value['plant_id']])->one()['typename'],'value'=>$$value['pinyin'],'pinyin'=>$value['pinyin']];
                }
                $all = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>$insured_lessee[0]['id'],'year'=>User::getYear()])->sum('area');
                $other = abs(sprintf('%.2f',$all - $area));
                $modeltArea['other'] = ['name'=>'其他','value'=>$other,'pinyin'=>'other'];
                $insuredarea = sprintf('%.2f',$area);
                $model->cardid = $insured_lessee[0]['cardid'];
                $model->telephone = $insured_lessee[0]['telephone'];
            }
        }
//        foreach ($insured_lessee as $lessee) {
//            $people[$lessee['id']] = $lessee['lessee'];
//        }
//        var_dump($modeltArea);exit;
        if(Insurance::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->count()) {
            $isShowAll = false;
        }
        return $this->renderAjax('insurancesix', [
            'model' => $model,
            'farms_id' => $farms_id,
            'farm' => $farm,
            'plantArea' => $modeltArea,
            'insuredarea' => $insuredarea,
            'people' => $people,
            'isShowAll' => $isShowAll,
            'farmerZZ' => $farmerZZ,
        ]);
    }

    public function actionInsurancesixdelete($id)
    {
        $model = $this->findModel($id);
        $state = $model->delete();
        Logs::writeLogs('撤消计划保险',$model);

        echo json_encode(['state'=>$state]);
    }

    public function actionInsuranceplansixdelete($id)
    {
        $model = Insuranceplan::findOne($id);
        $plantings = Plantingstructure::find()->where(['farms_id'=>$model->farms_id,'lease_id'=>$model->lease_id,'year'=>User::getYear()])->all();
        if($plantings) {
            foreach ($plantings as $planting) {
                $plantModel = Plantingstructure::findOne($planting['id']);
                $plantModel->isinsurance = 0;
                $plantModel->save();
                Logs::writeLogs('更新种植结构为不参加保险', $plantModel);
            }
        }
        $insuranceModel = Insurance::findOne($model->insurance_id);
        if($insuranceModel) {
            Logs::writeLogs('撤消保险任务',$insuranceModel);
            $insuranceModel->delete();
        }

        $state = $model->delete();
        Logs::writeLogs('撤消计划保险',$model);

        echo json_encode(['state'=>$state]);
    }

    public function actionInsurancecreate($farms_id)
    {
        $people = [];
        $area = 0;

        $insurancetype = Insurancetype::find()->all();
        foreach ($insurancetype as $value) {
            $$value['pinyin'] = 0;
        }
        $isShowAll = true;
        $model = new Insurance();
        $farm = Farms::findOne($farms_id);
        $leases = Lease::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->all();

        $insured_farmer = Insurance::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'year'=>User::getYear()])->one();
        $insured_lessee = [];
        foreach ($leases as $lease) {
            $leaseInsured = Insurance::find()->where(['farms_id'=>$farms_id,'lease_id'=>$lease['id'],'year'=>User::getYear()])->one();
            if(!$leaseInsured) {
                $insured_lessee[] = ['id'=>$lease['id'],'lessee'=>$lease['lessee'],'cardid'=>$lease['lessee_cardid'],'telephone'=>$lease['lessee_telephone']];
            }

        }
        $other = 0;
        $insuredarea = 0;
        $modeltArea = [];
        if(empty($insured_farmer)) {
            $farmerZZ = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'year'=>User::getYear()])->count();
            if($farmerZZ) {
                $modeltArea = [];
                $isFarmer = true;
                $people[0] = $farm->farmername;
                foreach ($insurancetype as $value) {
                    $$value['pinyin'] = Plantingstructure::find()->where(['plant_id'=>$value['plant_id'],'farms_id'=>$farms_id,'lease_id'=>0,'year'=>User::getYear()])->one()['area'];
                    $area += $$value['pinyin'];
                    $modeltArea[$value['pinyin']] = ['name'=>Plant::find()->where(['id'=>$value['plant_id']])->one()['typename'],'value'=>$$value['pinyin'],'pinyin'=>$value['pinyin']];
                }
                $all = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>0,'year'=>User::getYear()])->sum('area');
                $other = abs(sprintf('%.2f',$all - $area));
                $modeltArea['other'] = ['name'=>'其他','value'=>$other,'pinyin'=>'other'];
                $insuredarea = sprintf('%.2f',$area);
                $model->cardid = $farm->cardid;
                $model->telephone = $farm->telephone;
                foreach ($insured_lessee as $lessee) {
                    $people[$lessee['id']] = $lessee['lessee'];
                }
            } else {
                if($insured_lessee) {
                    $modeltArea = [];
                    foreach ($insured_lessee as $lessee) {
                        $people[$lessee['id']] = $lessee['lessee'];
                    }
                    foreach ($insurancetype as $value) {
                        $$value['pinyin'] = Plantingstructure::find()->where(['plant_id'=>$value['plant_id'],'farms_id'=>$farms_id,'lease_id'=>$insured_lessee[0]['id'],'year'=>User::getYear()])->one()['area'];
                        $area += $$value['pinyin'];
                        $modeltArea[$value['pinyin']] = ['name'=>Plant::find()->where(['id'=>$value['plant_id']])->one()['typename'],'value'=>$$value['pinyin'],'pinyin'=>$value['pinyin']];
                    }
                    $all = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>$insured_lessee[0]['id'],'year'=>User::getYear()])->sum('area');
                    $other = abs(sprintf('%.2f',$all - $area));
                    $modeltArea['other'] = ['name'=>'其他','value'=>$other,'pinyin'=>'other'];
                    $insuredarea = sprintf('%.2f',$area);
                    $model->cardid = $insured_lessee[0]['cardid'];
                    $model->telephone = $insured_lessee[0]['telephone'];
                }
            }
        } else {
            if($insured_lessee) {
                $modeltArea = [];
                foreach ($insured_lessee as $lessee) {
                    $people[$lessee['id']] = $lessee['lessee'];
                }
                foreach ($insurancetype as $value) {
                    $$value['pinyin'] = Plantingstructure::find()->where(['plant_id'=>$value['plant_id'],'farms_id'=>$farms_id,'lease_id'=>$insured_lessee[0]['id'],'year'=>User::getYear()])->one()['area'];
                    $area += $$value['pinyin'];
                    $modeltArea[$value['pinyin']] = ['name'=>Plant::find()->where(['id'=>$value['plant_id']])->one()['typename'],'value'=>$$value['pinyin'],'pinyin'=>$value['pinyin']];
                }
                $all = Plantingstructure::find()->where(['farms_id'=>$farms_id,'lease_id'=>$insured_lessee[0]['id'],'year'=>User::getYear()])->sum('area');
                $other = abs(sprintf('%.2f',$all - $area));
                $modeltArea['other'] = ['name'=>'其他','value'=>$other,'pinyin'=>'other'];
                $insuredarea = sprintf('%.2f',$area);
                $model->cardid = $insured_lessee[0]['cardid'];
                $model->telephone = $insured_lessee[0]['telephone'];
            }
        }
        if(Insurance::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->count()) {
            $isShowAll = false;
        }
        if ($model->load(Yii::$app->request->post())) {
            $farm = Farms::find()->where(['id'=>$farms_id])->one();
            $insucance = Insurance::find()->where(['farms_id'=>$farms_id,'year'=>User::getYear()])->sum('insuredarea');
            $model->management_area = $farm['management_area'];
            $model->farms_id = $farms_id;
            $model->farmername = $farm['farmername'];
            $model->farmerpinyin = Pinyin::encode($model->farmername);
            $model->policyholderpinyin = Pinyin::encode($model->policyholder);
            if($model->farmername == $model->policyholder) {
                $model->nameissame = 1;
            } else
                $model->nameissame = 0;
            $model->create_at = time();
            $model->update_at = $model->create_at;
            $model->year = date('Y');
            $model->managemanttime = $model->create_at;
            $model->state = 0;
            $model->fwdtstate = 2;
            $model->contractarea = $farm['contractarea'];
            $model->lease_id = $model->policyholder;
            $model->policyholder = Lease::find()->where(['id'=>$model->lease_id])->one()['lessee'];
            if(bccomp($farm['contractarea'] , $insucance,2) == 0) {
                $model->iscontractarea = 1;
            } else {
                $model->iscontractarea = 0;
            }
            $model->save();
            Logs::writeLogs('新增保险业务',$model);
            $dckModel = new Insurancedck();
            $dckModel->farms_id = $model->farms_id;
            $dckModel->management_area = $model->management_area;
            $dckModel->insurance_id = $model->id;
            $dckModel->isbank = Yii::$app->request->post('isbank');
            $dckModel->iscompany = Yii::$app->request->post('iscompany');
            $dckModel->iscontract = Yii::$app->request->post('iscontract');
            $dckModel->isoneself = Yii::$app->request->post('isoneself');
            $dckModel->islease = Yii::$app->request->post('islease');
            if(isset($_POST['iswt']))
                $dckModel->iswt = Yii::$app->request->post('iswt');
            $dckModel->save();
            Logs::writeLogs('地产科保险业务审查情况',$dckModel);
            return $this->redirect(['insuranceindex', 'farms_id' => $model->farms_id]);
        } else {
            return $this->render('insurancecreate', [
                'model' => $model,
                'farms_id' => $farms_id,
                'farm' => $farm,
                'plantArea' => $modeltArea,
                'insuredarea' => $insuredarea,
                'people' => $people,
                'isShowAll' => $isShowAll,
            ]);
        }
    }

    public function actionInsurancehistorycreate($farms_id = null)
    {
        
        if(empty($farms_id)) {
            $model = new Insurancehistory();
            if ($model->load(Yii::$app->request->post())) {
//                $farm = Farms::find()->where(['id'=>$farms_id])->one();
//                $model->management_area = Yii::$app->request->post('management_area');
//                $model->farmername = Yii::$app->request->post('farmername');
                $model->farmerpinyin = Pinyin::encode($model->farmername);
                $model->policyholderpinyin = Pinyin::encode($model->policyholder);
                if($model->farmername == $model->policyholder) {
                    $model->nameissame = 1;
                } else
                    $model->nameissame = 0;
                $model->create_at = time();
                $model->update_at = $model->create_at;
                $model->year = User::getYear();
                $model->managemanttime = $model->create_at;
                $model->state = 1;
                $model->fwdtstate = 2;
                $model->save();
                return $this->redirect(['insurancehistory']);
            } else {
                return $this->render('insurancehistorycreate2', [
                    'model' => $model,
                ]);
            }
        } else {
            $model = new Insurance();
            if ($model->load(Yii::$app->request->post())) {
                $farm = Farms::find()->where(['id'=>$farms_id])->one();
                $model->management_area = $farm['management_area'];
                $model->farms_id = $farms_id;
                $model->farmername = $farm['farmername'];
                $model->farmerpinyin = Pinyin::encode($model->farmername);
                $model->policyholderpinyin = Pinyin::encode($model->policyholder);
                if($model->farmername == $model->policyholder) {
                    $model->nameissame = 1;
                } else
                    $model->nameissame = 0;
                $model->create_at = time();
                $model->update_at = $model->create_at;
                $model->year = date('Y');
                $model->managemanttime = $model->create_at;
                $model->state = 0;
                $model->fwdtstate = 2;
                $model->save();
                return $this->redirect(['insuranceindex', 'farms_id' => $model->farms_id]);
            } else {
                return $this->render('insurancehistorycreate', [
                    'model' => $model,
                    'farms_id' => $farms_id,
                ]);
            }
        }
        if ($model->load(Yii::$app->request->post())) {
            $farm = Farms::find()->where(['id'=>$farms_id])->one();
            $model->management_area = $farm['management_area'];
            $model->farms_id = $farms_id;
            $model->farmername = $farm['farmername'];
            $model->farmerpinyin = Pinyin::encode($model->farmername);
            $model->policyholderpinyin = Pinyin::encode($model->policyholder);
            if($model->farmername == $model->policyholder) {
                $model->nameissame = 1;
            } else
                $model->nameissame = 0;
            $model->create_at = time();
            $model->update_at = $model->create_at;
            $model->year = date('Y');
            $model->managemanttime = $model->create_at;
            $model->state = 0;
            $model->fwdtstate = 2;
            $model->save();
            return $this->redirect(['insuranceindex', 'farms_id' => $model->farms_id]);
        } else {
            return $this->render('insurancehistorycreate', [
                'model' => $model,
                'farms_id' => $farms_id,
=======
    public function actionInsurancecreate($farms_id)
    {
        $model = new Insurance();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['insuranceview', 'id' => $model->id]);
        } else {
            return $this->render('insurancecreate', [
                'model' => $model,
            	'farms_id' => $farms_id,
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
            ]);
        }
    }

<<<<<<< HEAD
    public function actionInsuranceprint($id)
    {
        $model = $this->findModel($id);
        $farm = Farms::find()->where(['id'=>$model->farms_id])->one();
        if(User::getItemname('地产科')) {
            $model->state = 1;
            $model->save();
        }
        Logs::writeLogs('打印保险单',$model);
        return $this->render('insuranceprint',[
            'model'=>$model,
            'farm' => $farm,
        ]);
    }
    public function actionInsurancechiefview($id)
    {
        $model = $this->findModel($id);
        $farm = Farms::find()->where(['id'=>$model->farms_id])->one();
        Logs::writeLogs('查看一笔保险信息',$model);
        return $this->render('insurancechiefview',[
            'model'=>$model,
            'farm' => $farm,
        ]);
    }
    public function actionInsurancetableview($id)
    {
        $model = $this->findModel($id);
        $farm = Farms::find()->where(['id'=>$model->farms_id])->one();
        Logs::writeLogs('查看一笔保险信息',$model);
        return $this->render('insurancetableview',[
            'model'=>$model,
            'farm' => $farm,
        ]);
    }
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
    /**
     * Updates an existing Insurance model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
<<<<<<< HEAD
    public function actionInsuranceupdate($id,$btn='')
    {
        $people = [];
        $area = 0;
        $model = Insurance::findOne($id);
        $old = $model->attributes;
        $insurancetype = Insurancetype::find()->all();
        foreach ($insurancetype as $value) {
            $$value['pinyin'] = 0;
        }
        $isShowAll = true;
        $farm = Farms::findOne($model->farms_id);
        $leases = Lease::find()->where(['farms_id'=>$model->farms_id,'year'=>User::getYear()])->all();

        $insured_farmer = Insurance::find()->where(['farms_id'=>$model->farms_id,'lease_id'=>0,'year'=>User::getYear()])->one();
        $insured_lessee = [];
        foreach ($leases as $lease) {
            $leaseInsured = Insurance::find()->where(['farms_id'=>$model->farms_id,'lease_id'=>$lease['id'],'year'=>User::getYear()])->one();
            if(!$leaseInsured) {
                $insured_lessee[] = ['id'=>$lease['id'],'lessee'=>$lease['lessee'],'cardid'=>$lease['lessee_cardid'],'telephone'=>$lease['lessee_telephone']];
            }

        }
        $insuredarea = 0;
        $people[0] = $model->policyholder;
        foreach ($insurancetype as $value) {
            $$value['pinyin'] = Plantingstructure::find()->where(['plant_id'=>$value['plant_id'],'farms_id'=>$model->farms_id,'lease_id'=>$model->lease_id,'year'=>User::getYear()])->one()['area'];
            $area += $$value['pinyin'];
            $modeltArea[$value['pinyin']] = ['name'=>Plant::find()->where(['id'=>$value['plant_id']])->one()['typename'],'value'=>$$value['pinyin'],'pinyin'=>$value['pinyin']];
        }
        $all = Plantingstructure::find()->where(['farms_id'=>$model->farms_id,'lease_id'=>$model->lease_id,'year'=>User::getYear()])->sum('area');
        $other = abs(sprintf('%.2f',$all - $area));
        $modeltArea['other'] = ['name'=>'其他','value'=>$other,'pinyin'=>'other'];
        $insuredarea = sprintf('%.2f',$area);
        $model->cardid = $farm->cardid;
        $model->telephone = $farm->telephone;
        
        if(Insuranceplan::find()->where(['farms_id'=>$model->farms_id,'year'=>User::getYear()])->count()) {
            $isShowAll = false;
        }
        if ($model->load(Yii::$app->request->post())) {
            $model->update_at = time();
            $model->managemanttime = $model->update_at;
            $model->fwdtstate = 2;
            $model->save();
            Logs::writeLogs('更新保险业务信息',$model);
            $dckModel = new Insurancedck();
            $dckModel->farms_id = $model->farms_id;
            $dckModel->management_area = $model->management_area;
            $dckModel->insurance_id = $model->id;
            $dckModel->isbank = Yii::$app->request->post('isbank');
            $dckModel->iscompany = Yii::$app->request->post('iscompany');
            $dckModel->iscontract = Yii::$app->request->post('iscontract');
            $dckModel->isoneself = Yii::$app->request->post('isoneself');
            $dckModel->islease = Yii::$app->request->post('islease');
            if(isset($_POST['iswt']))
                $dckModel->iswt = Yii::$app->request->post('iswt');
            $dckModel->save();
            Logs::writeLogs('更新地产科保险审核信息',$dckModel);
            if(bccomp($old['insuredwheat'], $model->insuredwheat) or bccomp($old['insuredsoybean'], $model->insuredsoybean) or bccomp($old['insuredother'],$model->insuredother)) {
	            $modfiy = Insurancemodfit::find()->where(['insurance_id'=>$id])->one();
	            if($modfiy) {
	            	$modfiyModel = Insurancemodfit::findOne($modfiy['id']);
	            	$modfiyModel->update_at = time();
	            } else {
	            	$modfiyModel = new Insurancemodfit();
	            	$modfiyModel->create_at = time();
	            	$modfiyModel->update_at = $modfiyModel->create_at;
	            }
	            $modfiyModel->insurance_id = $id;
	            $modfiyModel->farms_id = $old['farms_id'];
	            $modfiyModel->management_area = $old['management_area'];
	            $modfiyModel->year = $old['year'];
	            $modfiyModel->oldpolicyholder = $old['policyholder'];
	            $modfiyModel->nowpolicyholder = $model->policyholder;
	            $modfiyModel->oldcardid = $old['cardid'];
	            $modfiyModel->nowcardid = $model->cardid;
	            $modfiyModel->oldtelephone = $old['policyholder'];
	            $modfiyModel->nowtelephone = $model->telephone;
	            $modfiyModel->oldinsuredarea = $old['insuredarea'];
	            $modfiyModel->newinsuredarea = $model->insuredarea;
	            $modfiyModel->oldinsuredwheat = $old['insuredwheat'];
	            $modfiyModel->nowinsuredwheat = $model->insuredwheat;
	            $modfiyModel->oldinsuredsoybean = $old['insuredsoybean'];
	            $modfiyModel->nowinsuredsoybean = $model->insuredsoybean;
	            $modfiyModel->oldinsuredother = $old['insuredother'];
	            $modfiyModel->nowinsuredother = $model->insuredother;
	            $modfiyModel->oldcompany_id = $old['company_id'];
	            $modfiyModel->nowcompany_id = $model->company_id;
//                $m
	            $modfiyModel->save();
                Logs::writeLogs('记录保险变更信息',$modfiyModel);
            }
=======
    public function actionInsuranceupdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
            return $this->redirect(['insuranceview', 'id' => $model->id]);
        } else {
            return $this->render('insuranceupdate', [
                'model' => $model,
<<<<<<< HEAD
                'farms_id' => $model->farms_id,
                'farm' => $farm,
                'plantArea' => $modeltArea,
                'insuredarea' => $insuredarea,
                'people' => $people,
                'isShowAll' => $isShowAll,
            ]);
        }
    }

    public function actionInsurancemodfiy($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model->update_at = time();
            $model->save();
            Logs::writeLogs('更新保险信息',$model);
            return $this->redirect(['insurancefwdthd']);
        } else {
            return $this->render('insurancemodfiy', [
                'model' => $model,
                'farms_id' => $model->farms_id,
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
            ]);
        }
    }

<<<<<<< HEAD
    public function actionInsurancesearch($tab,$begindate,$enddate)
    {
        if(isset($_GET['tab']) and $_GET['tab'] !== \Yii::$app->controller->id) {
            if($_GET['tab'] == 'yields')
                $class = 'plantingstructureSearch';
            else
                $class = $_GET['tab'].'Search';
            return $this->redirect ([$_GET['tab'].'/'.$_GET['tab'].'search',
                'tab' => $_GET['tab'],
                'begindate' => strtotime($_GET['begindate']),
                'enddate' => strtotime($_GET['enddate']),
//                 $class =>['management_area' =>  $_GET['management_area']],
            ]);
        }
        $searchModel = new insuranceSearch();
        if(!is_numeric($_GET['begindate']))
            $_GET['begindate'] = strtotime($_GET['begindate']);
        if(!is_numeric($_GET['enddate']))
            $_GET['enddate'] = strtotime($_GET['enddate']);
		$_GET['insuranceSearch']['state'] = 1;
		$_GET['insuranceSearch']['year'] = User::getYear();
        $dataProvider = $searchModel->searchSearch ( $_GET );
        Logs::writeLogs('综合查询-保险业务');
        return $this->render('insurancesearch',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tab' => $_GET['tab'],
            'begindate' => $_GET['begindate'],
            'enddate' => $_GET['enddate'],
            'params' => $_GET,
        ]);
    }

	public function actionInsurancetoxls()
    {
    	set_time_limit ( 0 );
    	require_once '../../vendor/phpoffice/phpexcel/Classes/PHPExcel/IOFactory.php';
    	unset($_GET[1]['page']);
    	unset($_GET[1]['per-page']);
//     	var_dump($_GET);exit;
    	$searchModel = new insuranceSearch();
    	$dataProvider = $searchModel->searchindex2($_GET[1]);
    	$dataProvider->pagination = ['pagesize'=>0];
    	$data = $dataProvider->getModels();
//     	var_dump($data);exit;
    	error_reporting(E_ALL);
    	ini_set('display_errors', TRUE);
    	ini_set('display_startup_errors', TRUE);
    	
    	define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
    	
    	date_default_timezone_set('Europe/London');
    	
    	/** PHPExcel_IOFactory */
        	
    	$objReader = \PHPExcel_IOFactory::createReader('Excel5');
    	$objPHPExcel = $objReader->load("template/insurance.xls");
    	$objPHPExcel->getActiveSheet()->setCellValue('A2', date('Y年m月d日'));
    	$baseRow = 5;
    	$row=0;
    	foreach($data as $r => $dataRow) {
    		$row = $baseRow + $r;
    		$objPHPExcel->getActiveSheet()->insertNewRowBefore($row,1);
    	
    		$objPHPExcel->getActiveSheet()->setCellValue('A'.$row, $r+1)
    		->setCellValue('B'.$row, ManagementArea::findOne($dataRow['management_area'])->areaname)
    		->setCellValue('C'.$row, Farms::findOne($dataRow['farms_id'])->farmname)
    		->setCellValue('D'.$row, $dataRow['farmername'])
    		->setCellValue('E'.$row, $dataRow['policyholder'])
    		->setCellValue('F'.$row, $dataRow['contractarea'])
    		->setCellValue('G'.$row, $dataRow['insuredarea'])
    		->setCellValue('H'.$row, $dataRow['insuredsoybean'])
    		->setCellValue('I'.$row, $dataRow['insuredwheat'])
    		->setCellValue('J'.$row, $dataRow['insuredother'])
    		->setCellValue('K'.$row, Insurancecompany::findOne($dataRow['company_id'])->companynname);
//     		$width = round($r/count($data)).'%';
//     		echo $this->render('insuranceprogress',['width'=>$width]);
    	}
    	$hjrow = $row+1;
    	$objPHPExcel->getActiveSheet()->insertNewRowBefore($hjrow,1);
    	$objPHPExcel->getActiveSheet()->setCellValue('B'.$hjrow, '合计')
    	->setCellValue('F'.$hjrow, '=SUM(F4:F'.$row.')')
    	->setCellValue('G'.$hjrow, '=SUM(G4:G'.$row.')')
    	->setCellValue('H'.$hjrow, '=SUM(H4:H'.$row.')')
    	->setCellValue('I'.$hjrow, '=SUM(I4:I'.$row.')')
    	->setCellValue('J'.$hjrow, '=SUM(J4:J'.$row.')');
    	
    	$objPHPExcel->getActiveSheet()->removeRow($baseRow-1,1);
//     	echo $this->render('insuranceprogress',['width'=>'100%']);
    	$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    	$filename = iconv("utf-8","gb2312//IGNORE",'insurance_xls/种植业保险情况统计表.xls');
    	$objWriter->save($filename);
    	Logs::writeLogs('生成保险业务情况统计表');
    	return $this->render('insurancexls',['filename'=>$filename]);
    }
    
    
=======
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
    /**
     * Deletes an existing Insurance model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionInsurancedelete($id)
    {
<<<<<<< HEAD
        $model = $this->findModel($id);
        $farms_id = $model->farms_id;
        $model->delete();
        Logs::writeLogs('删除一笔保险业务',$model);
        return $this->redirect(\Yii::$app->request->getReferrer());
=======
        $this->findModel($id)->delete();

        return $this->redirect(['insuranceindex']);
>>>>>>> e8af1cd29bb9d17f4c7726861a0ddbdd054c389f
    }

    /**
     * Finds the Insurance model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Insurance the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Insurance::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
